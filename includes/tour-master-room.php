<?php

add_filter( 'goodlayers_room_chip_payment_form', 'chip_create_purchase_room', 10, 3 );

/**
 *
 * Create purchase for room order
 *
 * @psalm-suppress MissingNonceVerification
 *
 * @phpcs:disable WordPress.Security.NonceVerification
 */
function chip_create_purchase_room( $ret = '', $tid = '', $pay_full_amount = true ) {
	$timestamp = time();

	if ( ! empty( $tid ) ) {
		// prepare data.

		$form            = stripslashes_deep( $_POST['form'] );
		$pay_full_amount = empty( $form['pay_full_amount'] ) ? false : true;

		$secret_key = trim( tourmaster_get_option( 'room_payment', 'chip-secret-key', '' ) );
		$brand_id   = trim( tourmaster_get_option( 'room_payment', 'chip-brand-id', '' ) );

		global $wpdb;
		$sql           = "SELECT id, contact_info, total_price, payment_info, currency FROM {$wpdb->prefix}tourmaster_room_order ";
		$sql          .= $wpdb->prepare( 'WHERE id = %d', $tid );
		$order         = $wpdb->get_row( $sql );
		$contact_info  = json_decode( $order->contact_info, true );
		$payment_infos = empty( $order->payment_info ) ? array() : json_decode( $order->payment_info, true );

		$price = $order->total_price;
		if ( empty( $pay_full_amount ) ) {
			$deposit_info = tourmaster_room_get_deposit_info( $price, $payment_infos );
			if ( ! empty( $deposit_info['deposit_amount'] ) ) {
				$price = $deposit_info['deposit_amount'];
			}
		} else {
			$paid_amount = 0;
			foreach ( $payment_infos as $payment_info ) {
				$paid_amount += empty( $payment_info['amount'] ) ? 0 : floatval( $payment_info['amount'] );
			}
			$price = $price - $paid_amount;
		}

		$currency_code = strtoupper( tourmaster_get_option( 'general', 'currency-code', 'USD' ) );

		// apply currency
		if ( ! empty( $order->currency ) ) {
			$currency = json_decode( $order->currency, true );
			if ( ! empty( $currency ) ) {
				$currency_code = strtoupper( $currency['currency-code'] );
				$price         = $price * floatval( $currency['exchange-rate'] );
			}
		}

		if ( empty( $price ) ) {
			return esc_html__( 'Cannot retrieve pricing data, please try again.', 'chip-for-tour-master' );

			// Start the payment process
		} else {

			$price = round( floatval( $price ) * 100 );

			$send_params = array(
				'success_callback' => add_query_arg(
					array(
						'chip_tour_master' => 'callback_room_flow',
						'tid'              => $tid,
						'timestamp'        => $timestamp,
					),
					site_url( '/' )
				),
				'success_redirect' => add_query_arg(
					array(
						'chip_tour_master' => 'redirect_room_flow',
						'tid'              => $tid,
						'timestamp'        => $timestamp,
					),
					site_url( '/' )
				),
				'failure_redirect' => tourmaster_get_template_url( 'room-payment' ),
				'cancel_redirect'  => tourmaster_get_template_url( 'room-payment' ),
				'creator_agent'    => 'TourMaster: ' . CTM_MODULE_VERSION,
				'reference'        => $tid,
				'platform'         => 'api', // traveltour.
				'brand_id'         => $brand_id,
				'client'           => array(
					'email'     => $contact_info['email'],
					'full_name' => substr( $contact_info['first_name'] . ' ' . $contact_info['last_name'], 0, 30 ),
				),
				'purchase'         => array(
					'currency' => $currency_code,
					'products' => array(
						array(
							'name'  => substr( 'Room booking: #' . $order->id, 0, 256 ),
							'price' => $price,
						),
					),
				),
			);

			$process_fee = trim( tourmaster_get_option( 'room_payment', 'chip-processing-fee', 0 ) );
			$process_fee = absint( wp_unslash( $process_fee ) );

			if ( $process_fee > 0 ) {
				$send_params['purchase']['products'][] = array(
					'name'  => esc_html__( 'Processing Fee', 'chip-for-tour-master' ),
					'price' => round( $process_fee ),
				);
			}

			$chip     = new Chip_Travel_Tour_API( $secret_key, $brand_id );
			$purchase = $chip->create_payment( $send_params );

			if ( ! array_key_exists( 'id', $purchase ) ) {
				return sprintf( esc_html__( 'Failed to create purchase. %s', 'chip-for-tour-master' ), wp_json_encode( $purchase, JSON_PRETTY_PRINT ) );
			}

			$payment_info = array(
				'id'             => $purchase['id'],
				'amount'         => $price,
				'transaction_id' => $purchase['id'] . '-pending',
				'payment_method' => 'CHIP',
				'payment_status' => $purchase['status'],
				'timestamp'      => $timestamp,
			);

			// get old payment info
			$payment_infos   = json_decode( $order->payment_info, true );
			$payment_infos   = tourmaster_payment_info_format( $payment_infos, $order->order_status );
			$payment_infos[] = $payment_info;

			$wpdb->update(
				"{$wpdb->prefix}tourmaster_room_order",
				array(
					'payment_info' => wp_json_encode( $payment_infos ),
				),
				array( 'id' => $tid ),
				array( '%s' ),
				array( '%d' )
			);

			ob_start();
			?>
			<div class="gdlr-core-purchase-form">
				<div class="gdlr-core-purchase-form-title">
					<?php esc_html_e( 'Redirecting to payment page...', 'chip-for-tour-master' ); ?>
				</div>
				<div class="gdlr-core-purchase-form-content">
					<?php esc_html_e( 'Please wait while we redirect you to the payment page.', 'chip-for-tour-master' ); ?>
				</div>
				<script type="text/javascript">
					window.location.href = '<?php echo $purchase['checkout_url']; ?>';
				</script>
			<?php
			$ret = ob_get_contents();
			ob_end_clean();

			return $ret;
		}
	}
}

add_action( 'init', 'chip_redirect_room_status_update', 10, 0 );
function chip_redirect_room_status_update() {
	if ( ! isset( $_GET['chip_tour_master'] ) ) {
		return;
	}

	if ( $_GET['chip_tour_master'] !== 'redirect_room_flow' ) {
		return;
	}

	if ( ! isset( $_GET['timestamp'] ) ) {
		exit( 'No timestamp' );
	}

	$tid = preg_replace( '/[^0-9]/', '', $_GET['tid'] );
	$tid = absint( $tid );

	$success_redirect = add_query_arg(
		array(
			'tid'            => $tid,
			'step'           => 4,
			'payment_method' => 'paypal',
		),
		tourmaster_get_template_url( 'room-payment' )
	);

	global $wpdb;
	$sql   = "SELECT id, contact_info, total_price, payment_info, currency, order_status FROM {$wpdb->prefix}tourmaster_room_order ";
	$sql  .= $wpdb->prepare( 'WHERE id = %d', $tid );
	$order = $wpdb->get_row( $sql );

	if ( 'online-paid' === $order->order_status ) {
		wp_safe_redirect( $success_redirect );
		exit;
	}

	$payment_infos = json_decode( $order->payment_info, true );
	if ( empty( $payment_infos ) || ! is_array( $payment_infos ) ) {
		wp_safe_redirect( tourmaster_get_template_url( 'room-payment' ) );
		exit;
	}

	$payment_info = array();

	foreach ( $payment_infos as $key => $pinfo ) {
		if ( empty( $pinfo['transaction_id'] ) || empty( $pinfo['payment_method'] ) ) {
			continue;
		}

		if ( $pinfo['timestamp'] == $_GET['timestamp'] ) {
			$payment_info = $pinfo;
			break;
		}
	}

	if ( empty( $payment_info ) ) {
		wp_safe_redirect( tourmaster_get_template_url( 'room-payment' ) );
		exit;
	}

	if ( $payment_info['payment_method'] !== 'CHIP' ) {
		wp_safe_redirect( tourmaster_get_template_url( 'room-payment' ) );
		exit;
	}

	$secret_key = trim( tourmaster_get_option( 'room_payment', 'chip-secret-key', '' ) );

	$chip     = new Chip_Travel_Tour_API( $secret_key, '' );
	$purchase = $chip->get_payment( $payment_info['id'] );

	if ( $purchase['status'] !== 'paid' ) {
		wp_safe_redirect( tourmaster_get_template_url( 'room-payment' ) );
		exit;
	}

	$price = $purchase['payment']['amount'] / 100;

	$process_fee = trim( tourmaster_get_option( 'room_payment', 'chip-processing-fee', 0 ) );
	$process_fee = absint( wp_unslash( $process_fee ) ) / 100;
	$price       = $price - $process_fee;

	if ( ! empty( $order->currency ) ) {
		$currency = json_decode( $order->currency, true );
		$price    = $price / floatval( $currency['exchange-rate'] );
	}

	$new_payment_info = array(
		'transaction_id'  => $purchase['id'],
		'amount'          => $price,
		'payment_method'  => 'CHIP',
		'payment_status'  => $purchase['status'],
		'submission_date' => current_time( 'mysql' ),
		'timestamp'       => time(),
	);

	foreach ( $payment_infos as $key => $value ) {
		if ( absint( $_GET['timestamp'] ) === $value['timestamp'] ) {
			unset( $payment_infos[ $key ] );
			break;
		}
	}

	$payment_infos = array_values( $payment_infos );

	$payment_infos[] = $new_payment_info;
	$order_status    = tourmaster_room_payment_order_status( $order->total_price, $payment_infos, true );

	$wpdb->update(
		"{$wpdb->prefix}tourmaster_room_order",
		array(
			'payment_info' => wp_json_encode( $payment_infos ),
			'order_status' => $order_status,
		),
		array( 'id' => $tid ),
		array( '%s', '%s' ),
		array( '%d' )
	);

	// send an email.
	if ( $order_status == 'deposit-paid' ) {
		tourmaster_room_mail_notification( 'deposit-payment-made-mail', $tid, '', array( 'custom' => $new_payment_info ) );
		tourmaster_room_mail_notification( 'admin-deposit-payment-made-mail', $tid, '', array( 'custom' => $new_payment_info ) );
	} elseif ( $order_status == 'approved' || $order_status == 'online-paid' ) {
		tourmaster_room_mail_notification( 'payment-made-mail', $tid, '', array( 'custom' => $new_payment_info ) );
		tourmaster_room_mail_notification( 'admin-online-payment-made-mail', $tid, '', array( 'custom' => $new_payment_info ) );
	}
	tourmaster_room_send_email_invoice( $tid );

	wp_safe_redirect( $success_redirect );
	exit;
}

add_action( 'init', 'chip_callback_room_status_update', 10, 0 );
function chip_callback_room_status_update() {
	if ( ! isset( $_GET['chip_tour_master'] ) ) {
		return;
	}

	if ( 'callback_room_flow' !== $_GET['chip_tour_master'] ) {
		return;
	}

	if ( ! isset( $_GET['timestamp'] ) ) {
		exit( 'No timestamp' );
	}

	$tid = preg_replace( '/[^0-9]/', '', $_GET['tid'] );
	$tid = absint( $tid );

	global $wpdb;
	$sql   = "SELECT id, contact_info, total_price, payment_info, currency, order_status FROM {$wpdb->prefix}tourmaster_room_order ";
	$sql  .= $wpdb->prepare( 'WHERE id = %d', $tid );
	$order = $wpdb->get_row( $sql );

	if ( 'online-paid' === $order->order_status ) {
		exit;
	}

	$payment_infos = json_decode( $order->payment_info, true );
	if ( empty( $payment_infos ) || ! is_array( $payment_infos ) ) {
		exit;
	}

	$payment_info = array();

	foreach ( $payment_infos as $key => $pinfo ) {
		if ( empty( $pinfo['transaction_id'] ) || empty( $pinfo['payment_method'] ) ) {
			continue;
		}

		if ( absint( $_GET['timestamp'] ) == $pinfo['timestamp'] ) {
			$payment_info = $pinfo;
			break;
		}
	}

	if ( empty( $payment_info ) ) {
		exit;
	}

	if ( $payment_info['payment_method'] !== 'CHIP' ) {
		exit;
	}

	$secret_key     = trim( tourmaster_get_option( 'room_payment', 'chip-secret-key', '' ) );
	$ten_secret_key = substr( $secret_key, 0, 10 );

	if ( empty( $public_key = get_option( 'chip_tm_' . $ten_secret_key ) ) ) {
		$chip       = new Chip_Travel_Tour_API( $secret_key, '' );
		$public_key = str_replace( '\n', "\n", $chip->public_key() );
		update_option( 'chip_tm_' . $ten_secret_key, $public_key );
	}

	$content = file_get_contents( 'php://input' );

	if ( openssl_verify( $content, base64_decode( $_SERVER['HTTP_X_SIGNATURE'] ), $public_key, 'sha256WithRSAEncryption' ) != 1 ) {
		exit( 'Invalid signature' );
	}

	$purchase = json_decode( $content, true );

	if ( 'paid' !== $purchase['status'] ) {
		exit;
	}

	$price = $purchase['payment']['amount'] / 100;

	$process_fee = trim( tourmaster_get_option( 'room_payment', 'chip-processing-fee', 0 ) );
	$process_fee = absint( wp_unslash( $process_fee ) ) / 100;
	$price       = $price - $process_fee;

	if ( ! empty( $order->currency ) ) {
		$currency = json_decode( $order->currency, true );
		$price    = $price / floatval( $currency['exchange-rate'] );
	}

	$new_payment_info = array(
		'transaction_id'  => $purchase['id'],
		'amount'          => $price,
		'payment_method'  => 'CHIP',
		'payment_status'  => $purchase['status'],
		'submission_date' => current_time( 'mysql' ),
		'timestamp'       => time(),
	);

	foreach ( $payment_infos as $key => $value ) {
		if ( absint( $_GET['timestamp'] ) === $value['timestamp'] ) {
			unset( $payment_infos[ $key ] );
			break;
		}
	}

	$payment_infos = array_values( $payment_infos );

	$payment_infos[] = $new_payment_info;
	$order_status    = tourmaster_room_payment_order_status( $order->total_price, $payment_infos, true );

	$wpdb->update(
		"{$wpdb->prefix}tourmaster_room_order",
		array(
			'payment_info' => wp_json_encode( $payment_infos ),
			'order_status' => $order_status,
		),
		array( 'id' => $tid ),
		array( '%s', '%s' ),
		array( '%d' )
	);

	// send an email.
	if ( $order_status == 'deposit-paid' ) {
		tourmaster_room_mail_notification( 'deposit-payment-made-mail', $tid, '', array( 'custom' => $new_payment_info ) );
		tourmaster_room_mail_notification( 'admin-deposit-payment-made-mail', $tid, '', array( 'custom' => $new_payment_info ) );
	} elseif ( $order_status == 'approved' || $order_status == 'online-paid' ) {
		tourmaster_room_mail_notification( 'payment-made-mail', $tid, '', array( 'custom' => $new_payment_info ) );
		tourmaster_room_mail_notification( 'admin-online-payment-made-mail', $tid, '', array( 'custom' => $new_payment_info ) );
	}
	tourmaster_room_send_email_invoice( $tid );

	exit( 'Callback success' );
}

add_filter( 'tourmaster_room_payment_methods', 'add_chip_to_room_payment_methods', 10, 1 );

/**
 * Add CHIP to room payment methods
 *
 * @param array $payments_title Payment method title.
 *
 * @return array
 */
function add_chip_to_room_payment_methods( $payments_title ) {

	$payment_method = tourmaster_get_option('payment', 'payment-method', array());
	$payments_title['chip'] = esc_html__( 'CHIP', 'chip-for-tour-master' );

	return $payments_title;
}
