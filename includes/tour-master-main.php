<?php

// add_filter( 'goodlayers_credit_card_payment_gateway_options', 'chip_pg_options' );
if ( ! function_exists( 'chip_pg_options' ) ) {
	function chip_pg_options( $options ) {
		$options['chip'] = esc_html__( 'CHIP', 'chip-for-tour-master' );

		return $options;
	}
}

// init the script on payment page head
add_filter( 'goodlayers_plugin_payment_option', 'chip_payment_option' );
if ( ! function_exists( 'chip_payment_option' ) ) {
	function chip_payment_option( $options ) {

		$options['chip'] = array(
			'title'   => esc_html__( 'CHIP', 'chip-for-tour-master' ),
			'options' => array(
				'chip-secret-key'     => array(
					'title' => __( 'CHIP Secret Key', 'chip-for-tour-master' ),
					'type'  => 'text',
				),
				'chip-brand-id'       => array(
					'title' => __( 'CHIP Brand ID', 'chip-for-tour-master' ),
					'type'  => 'text',
				),
				'chip-currency-code'  => array(
					'title'   => esc_html__( 'CHIP Currency Code', 'chip-for-tour-master' ),
					'type'    => 'text',
					'default' => 'MYR',
				),
				'chip-processing-fee' => array(
					'title'       => esc_html__( 'CHIP Processing Fee', 'chip-for-tour-master' ),
					'type'        => 'text',
					'default'     => '0',
					'description' => esc_html__( 'Set 100 for RM 1 charge', 'chip-for-tour-master' ),
				),
			),
		);

		$options['payment-settings']['options']['payment-method']['options']['chip'] = esc_html__( 'CHIP', 'chip-for-tour-master' );

		return $options;
	}
}

// $current_payment_gateway = apply_filters( 'goodlayers_payment_get_option', '', 'credit-card-payment-gateway' );
// if ( $current_payment_gateway == 'chip' ) {
// include_once( TOURMASTER_LOCAL . '/include/authorize/autoload.php' );

// add_filter( 'goodlayers_plugin_payment_attribute', 'goodlayers_authorize_payment_attribute' );
// add_filter( 'goodlayers_authorize_payment_form', 'goodlayers_authorize_payment_form', 10, 2 );

// add_action( 'wp_ajax_chip_payment_charge', 'chip_create_purchase' );
// add_action( 'wp_ajax_nopriv_chip_payment_charge', 'chip_create_purchase' );
// }

add_action( 'wp_ajax_chip_payment_charge', 'chip_create_purchase' );
add_action( 'wp_ajax_nopriv_chip_payment_charge', 'chip_create_purchase' );

add_filter( 'tourmaster_additional_payment_method', 'chip_additional_payment_method' );

if ( ! function_exists( 'chip_additional_payment_method' ) ) {
	function chip_additional_payment_method( $methods ) {
		$chip_button_atts = apply_filters( 'tourmaster_chip_button_atts', array() );

		$ret  = '';
		$ret .= '<div class="tourmaster-online-payment-method tourmaster-payment-paypal" >';
		$ret .= '<img width="170" height="76" src="' . esc_attr( CTM_PLUGIN_URL ) . '/assets/chip-payment.png" alt="chip" ';
		if ( ! empty( $chip_button_atts['method'] ) && $chip_button_atts['method'] == 'ajax' ) {
			$ret .= 'data-method="ajax" data-action="tourmaster_payment_selected" data-ajax="' . esc_url( TOURMASTER_AJAX_URL ) . '" ';
			if ( ! empty( $chip_button_atts['type'] ) ) {
				$ret .= 'data-action-type="' . esc_attr( $chip_button_atts['type'] ) . '" ';
			}
		}
		$ret .= ' />';
		$ret .= '<div class="tourmaster-payment-paypal-service-fee-text" >';
		$ret .= esc_html__( 'Pay with FPX, Card & E-Wallet.', 'chip-for-tour-master' );
		$ret .= '</div>';
		$ret .= '</div>';

		return $ret;
	}
}

// add attribute for payment button
add_filter( 'tourmaster_chip_button_atts', 'tourmaster_chip_button_atts' );
if ( ! function_exists( 'tourmaster_chip_button_atts' ) ) {
	function tourmaster_chip_button_atts( $attributes ) {
		return array(
			'method' => 'ajax',
			'type'   => 'chip',
		);
	}
}


// payment form
add_filter( 'goodlayers_chip_payment_form', 'tourmaster_chip_payment_form', 10, 2 );
if ( ! function_exists( 'tourmaster_chip_payment_form' ) ) {
	function tourmaster_chip_payment_form( $ret = '', $tid = '' ) {
		ob_start();
		?>
		<div class="goodlayers-payment-form goodlayers-with-border">
			<form action="" method="POST" id="goodlayers-chip-payment-form"
				data-ajax-url="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>">
				<div class="now-loading"></div>
				<div class="payment-errors"></div>
				<div class="goodlayers-payment-req-field"><?php esc_html_e( 'Please fill all required fields', 'chip-for-tour-master' ); ?>
				</div>
				<input type="hidden" name="tid" value="<?php echo esc_attr( $tid ); ?>" />
				<input class="goodlayers-payment-button submit" type="submit"
					value="<?php esc_html_e( 'Submit Payment', 'chip-for-tour-master' ); ?>" />

				<!-- for proceeding to last step -->
				<div class="goodlayers-payment-plugin-complete"></div>
			</form>
		</div>
		<script type="text/javascript">
			(function ($) {
				var form = $('#goodlayers-chip-payment-form');

				function goodlayersChipPurchase() {

					var tid = form.find('input[name="tid"]').val();

					$.ajax({
						type: 'POST',
						url: form.attr('data-ajax-url'),
						data: { 'action': 'chip_payment_charge', 'tid': tid },
						dataType: 'json',
						error: function (a, b, c) {
							console.log(a, b, c);

							// display error messages
							form.find('.payment-errors').text('<?php echo esc_html__( 'An error occurs, please refresh the page to try again.', 'chip-for-tour-master' ); ?>').slideDown(200);
							form.find('.submit').prop('disabled', false).removeClass('now-loading');
						},
						success: function (data) {
							if (data.status == 'success') {
								// trigger the complete button
								// form.find('.goodlayers-payment-plugin-complete').trigger('click');
								window.location.href = data.url;
							} else if (typeof (data.message) != 'undefined') {
								form.find('.payment-errors').text(data.message).slideDown(200);
							}

							form.find('.submit').prop('disabled', false).removeClass('now-loading');
						}
					});
				};

				form.submit(function (event) {
					var req = false;
					form.find('input').each(function () {
						if (!$(this).val()) {
							req = true;
						}
					});

					if (req) {
						form.find('.goodlayers-payment-req-field').slideDown(200)
					} else {
						form.find('.submit').prop('disabled', true).addClass('now-loading');
						form.find('.payment-errors, .goodlayers-payment-req-field').slideUp(200);
						goodlayersChipPurchase();
					}

					return false;
				});
			})(jQuery);
		</script>
		<?php
		$ret = ob_get_contents();
		ob_end_clean();
		return $ret;
	}
}

// ajax for payment submission
if ( ! function_exists( 'chip_create_purchase' ) ) {
	function chip_create_purchase() {

		$ret       = array();
		$timestamp = time();

		if ( ! empty( $_POST['tid'] ) ) {
			$tid = preg_replace( '/[^0-9]/', '', $_POST['tid'] );
			$tid = absint( $tid );
			// prepare data.

			$secret_key = trim( tourmaster_get_option( 'payment', 'chip-secret-key', '' ) );
			$brand_id   = trim( tourmaster_get_option( 'payment', 'chip-brand-id', '' ) );

			$booking_data = tourmaster_get_booking_data( array( 'id' => $_POST['tid'] ), array( 'single' => true ) );

			$billing_info = json_decode( $booking_data->billing_info, true );

			$currency_code = strtoupper( tourmaster_get_option( 'general', 'currency-code', 'USD' ) );

			$t_data = apply_filters( 'goodlayers_payment_get_transaction_data', array(), $_POST['tid'], array( 'currency', 'price', 'email' ) );

			$price = '';
			if ( $t_data['price']['deposit-price'] ) {
				$price = $t_data['price']['deposit-price'];
			} else {
				$price = $t_data['price']['pay-amount'];
			}

				// apply currency
			if ( ! empty( $t_data['currency'] ) ) {
				$currency_code = strtoupper( $t_data['currency']['currency-code'] );
				$price         = $price * floatval( $t_data['currency']['exchange-rate'] );
			}

			if ( empty( $price ) ) {
				$ret['status']  = 'failed';
				$ret['message'] = esc_html__( 'Cannot retrieve pricing data, please try again.', 'chip-for-tour-master' );

				// Start the payment process.
			} elseif ( $currency_code != 'MYR' ) {
				$ret['status'] = 'failed';
				// translators: $curency_code is currency code.
				$ret['message'] = sprintf( esc_html__( '%1$s is unsupported currency.', 'chip-for-tour-master' ), $currency_code );
			} else {
				$price = round( floatval( $price ) * 100 );

				$send_params = array(
					'success_callback' => add_query_arg(
						array(
							'chip_tour_master' => 'callback_flow',
							'tid'              => $tid,
							'timestamp'        => $timestamp,
						),
						site_url( '/' )
					),
					'success_redirect' => add_query_arg(
						array(
							'chip_tour_master' => 'redirect_flow',
							'tid'              => $tid,
							'timestamp'        => $timestamp,
						),
						site_url( '/' )
					),
					'failure_redirect' => tourmaster_get_template_url( 'payment' ),
					'cancel_redirect'  => tourmaster_get_template_url( 'payment' ),
					'creator_agent'    => 'TourMaster: ' . CTM_MODULE_VERSION,
					'reference'        => $tid,
					'platform'         => 'api', // traveltour.
					'brand_id'         => $brand_id,
					'client'           => array(
						'email'     => $billing_info['email'],
						'full_name' => substr( $billing_info['first_name'] . ' ' . $billing_info['last_name'], 0, 30 ),
					),
					'purchase'         => array(
						'currency' => $currency_code,
						'products' => array(
							array(
								'name'  => substr( get_the_title( $booking_data->tour_id ), 0, 256 ),
								'price' => $price,
							),
						),
					),
				);

				$process_fee = trim( tourmaster_get_option( 'payment', 'chip-processing-fee', 0 ) );
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
					$ret['status']  = 'failed';
					$ret['message'] = sprintf( esc_html__( 'Failed to create purchase. %s', 'chip-for-tour-master' ), wp_json_encode( $purchase, JSON_PRETTY_PRINT ) );
					die( wp_json_encode( $ret ) );
				}

				$payment_info = array(
					'id'             => $purchase['id'],
					'transaction_id' => $purchase['id'] . '-pending',
					'payment_method' => 'CHIP',
					'payment_status' => $purchase['status'],
					'timestamp'      => $timestamp,
				);

				// get old payment info
				$payment_infos   = json_decode( $booking_data->payment_info, true );
				$payment_infos   = tourmaster_payment_info_format( $payment_infos, $booking_data->order_status );
				$payment_infos[] = $payment_info;

				tourmaster_update_booking_data(
					array(
						'payment_info' => wp_json_encode( $payment_infos ),
					),
					array( 'id' => $tid ),
					array( '%s' ),
					array( '%d' )
				);

				$ret['status'] = 'success';
				$ret['url']    = $purchase['checkout_url'];

			}
		}

		die( wp_json_encode( $ret ) );
	}
}

add_action( 'init', 'chip_redirect_status_update', 10, 0 );
/**
 *
 * Redirect function for user redirect after payment. It is also for status update.
 *
 * @psalm-suppress MissingNonceVerification
 *
 * @phpcs:disable WordPress.Security.NonceVerification
 */
function chip_redirect_status_update() {
	if ( ! isset( $_GET['chip_tour_master'] ) ) {
		return;
	}

	if ( 'redirect_flow' !== $_GET['chip_tour_master'] ) {
		return;
	}

	if ( ! isset( $_GET['tid'] ) ) {
		exit( 'No tid' );
	}

	$tid = sanitize_text_field( wp_unslash( $_GET['tid'] ) );
	$tid = absint( $tid );

	$success_redirect = add_query_arg(
		array(
			'tid'            => $tid,
			'step'           => 4,
			'payment_method' => 'paypal',
		),
		tourmaster_get_template_url( 'payment' )
	);

	$booking_data = tourmaster_get_booking_data( array( 'id' => $tid ), array( 'single' => true ) );

	if ( 'online-paid' === $booking_data->order_status ) {
		wp_safe_redirect( $success_redirect );
		exit;
	}

	$payment_infos = json_decode( $booking_data->payment_info, true );
	if ( empty( $payment_infos ) || ! is_array( $payment_infos ) ) {
		wp_safe_redirect( tourmaster_get_template_url( 'payment' ) );
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
		wp_safe_redirect( tourmaster_get_template_url( 'payment' ) );
		exit;
	}

	if ( $payment_info['payment_method'] !== 'CHIP' ) {
		wp_safe_redirect( tourmaster_get_template_url( 'payment' ) );
		exit;
	}

	$secret_key = trim( tourmaster_get_option( 'payment', 'chip-secret-key', '' ) );

	$chip     = new Chip_Travel_Tour_API( $secret_key, '' );
	$purchase = $chip->get_payment( $payment_info['id'] );

	if ( $purchase['status'] !== 'paid' ) {
		wp_safe_redirect( tourmaster_get_template_url( 'payment' ) );
		exit;
	}

	$price = $purchase['payment']['amount'] / 100;

	$process_fee = trim( tourmaster_get_option( 'payment', 'chip-processing-fee', 0 ) );
	$process_fee = absint( wp_unslash( $process_fee ) ) / 100;
	$price       = $price - $process_fee;

	if ( ! empty( $booking_data->currency ) ) {
		$currency = json_decode( $booking_data->currency, true );
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
		if ( $value['timestamp'] == $_GET['timestamp'] ) {
			unset( $payment_infos[ $key ] );
			break;
		}
	}

	$payment_infos = array_values( $payment_infos );

	tourmaster_update_booking_data(
		array(
			'payment_info' => wp_json_encode( $payment_infos ),
		),
		array( 'id' => $tid ),
		array( '%s' ),
		array( '%d' )
	);

	do_action( 'goodlayers_set_payment_complete', $tid, $new_payment_info );

	wp_safe_redirect( $success_redirect );
	exit;
}

add_action( 'init', 'chip_callback_status_update', 10, 0 );
/**
 *
 * Callback function for CHIP payment status update.
 *
 * @psalm-suppress MissingNonceVerification
 *
 * @phpcs:disable WordPress.Security.NonceVerification
 */
function chip_callback_status_update() {
	if ( ! isset( $_GET['chip_tour_master'] ) ) {
		return;
	}

	if ( 'callback_flow' !== $_GET['chip_tour_master'] ) {
		return;
	}

	if ( ! isset( $_SERVER['HTTP_X_SIGNATURE'] ) ) {
		exit( 'No X Signature header' );
	}

	if ( ! isset( $_GET['timestamp'] ) ) {
		exit( 'No timestamp' );
	}

	if ( ! isset( $_GET['tid'] ) ) {
		exit( 'No tid' );
	}

	$tid = sanitize_text_field( wp_unslash( $_GET['tid'] ) );
	$tid = absint( $tid );

	$booking_data = tourmaster_get_booking_data( array( 'id' => $tid ), array( 'single' => true ) );

	if ( 'online-paid' === $booking_data->order_status ) {
		exit;
	}

	$payment_infos = json_decode( $booking_data->payment_info, true );
	if ( empty( $payment_infos ) || ! is_array( $payment_infos ) ) {
		wp_safe_redirect( tourmaster_get_template_url( 'payment' ) );
		exit;
	}

	$payment_info = array();

	foreach ( $payment_infos as $key => $pinfo ) {
		if ( empty( $pinfo['transaction_id'] ) || empty( $pinfo['payment_method'] ) ) {
			continue;
		}

		if ( absint( $_GET['timestamp'] ) === $pinfo['timestamp'] ) {
			$payment_info = $pinfo;
			break;
		}
	}

	if ( empty( $payment_info ) ) {
		wp_safe_redirect( tourmaster_get_template_url( 'payment' ) );
		exit;
	}

	if ( $payment_info['payment_method'] !== 'CHIP' ) {
		exit;
	}

	$secret_key     = trim( tourmaster_get_option( 'payment', 'chip-secret-key', '' ) );
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

	if ( $purchase['status'] !== 'paid' ) {
		exit;
	}

	$price = $purchase['payment']['amount'] / 100;

	$process_fee = trim( tourmaster_get_option( 'payment', 'chip-processing-fee', 0 ) );
	$process_fee = absint( wp_unslash( $process_fee ) ) / 100;
	$price       = $price - $process_fee;

	if ( ! empty( $booking_data->currency ) ) {
		$currency = json_decode( $booking_data->currency, true );
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

	tourmaster_update_booking_data(
		array(
			'payment_info' => wp_json_encode( $payment_infos ),
		),
		array( 'id' => $tid ),
		array( '%s' ),
		array( '%d' )
	);

	do_action( 'goodlayers_set_payment_complete', $tid, $new_payment_info );

	exit( 'Callback success' );
}

add_filter( 'tourmaster_custom_payment_enable', 'chip_tm_custom_payment_enable', 10, 2 );

function chip_tm_custom_payment_enable( $status, $payment_method ) {
	return in_array( 'chip', $payment_method );
}
