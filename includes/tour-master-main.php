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

		// Original CHIP option (default - no payment method restriction)
		$options['chip'] = array(
			'title'   => esc_html__( 'CHIP (All Methods)', 'chip-for-tour-master' ),
			'options' => array(
				'chip-title'          => array(
					'title'   => __( 'Payment Method Title', 'chip-for-tour-master' ),
					'type'    => 'text',
					'default' => esc_html__( 'CHIP', 'chip-for-tour-master' ),
				),
				'chip-secret-key'     => array(
					'title' => __( 'CHIP Secret Key', 'chip-for-tour-master' ),
					'type'  => 'text',
				),
				'chip-brand-id'       => array(
					'title' => __( 'CHIP Brand ID', 'chip-for-tour-master' ),
					'type'  => 'text',
				),
				'chip-logo'           => array(
					'title'       => __( 'Accepted Payment Type Logo', 'chip-for-tour-master' ),
					'type'        => 'multi-combobox',
					'options'     => array(
						'fpx'         => esc_html__( 'FPX', 'chip-for-tour-master' ),
						'duitnow_qr'  => esc_html__( 'QR Payment', 'chip-for-tour-master' ),
						'visa'        => esc_html__( 'Visa', 'chip-for-tour-master' ),
						'master-card' => esc_html__( 'Master Card', 'chip-for-tour-master' ),
						'tng'         => esc_html__( 'TnG', 'chip-for-tour-master' ),
						'grabpay'     => esc_html__( 'GrabPay', 'chip-for-tour-master' ),
						'maybank_qr'  => esc_html__( 'Maybank QR', 'chip-for-tour-master' ),
						'shopeepay'   => esc_html__( 'ShopeePay', 'chip-for-tour-master' ),
						'atome'       => esc_html__( 'Atome', 'chip-for-tour-master' ),
					),
					'default'     => array( 'fpx', 'duitnow_qr', 'visa', 'master-card' ),
					'description' => esc_html__( 'Only display images below CHIP option.', 'chip-for-tour-master' ),
				),
				'chip-msg-checkout'   => array(
					'title'       => __( 'Message', 'chip-for-tour-master' ),
					'type'        => 'text',
					'description' => esc_html__( 'Message on checkout page.', 'chip-for-tour-master' ),
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

		// FPX Payment Method
		$options['chip-fpx'] = array(
			'title'   => esc_html__( 'CHIP FPX', 'chip-for-tour-master' ),
			'options' => array(
				'chip-fpx-title'          => array(
					'title'   => __( 'Payment Method Title', 'chip-for-tour-master' ),
					'type'    => 'text',
					'default' => esc_html__( 'FPX', 'chip-for-tour-master' ),
				),
				'chip-fpx-secret-key'     => array(
					'title' => __( 'CHIP Secret Key', 'chip-for-tour-master' ),
					'type'  => 'text',
				),
				'chip-fpx-brand-id'       => array(
					'title' => __( 'CHIP Brand ID', 'chip-for-tour-master' ),
					'type'  => 'text',
				),
				'chip-fpx-logo'           => array(
					'title'       => __( 'Accepted Payment Type Logo', 'chip-for-tour-master' ),
					'type'        => 'multi-combobox',
					'options'     => array(
						'fpx'         => esc_html__( 'FPX', 'chip-for-tour-master' ),
					),
					'default'     => array( 'fpx' ),
					'description' => esc_html__( 'Only display images below CHIP option.', 'chip-for-tour-master' ),
				),
				'chip-fpx-msg-checkout'   => array(
					'title'       => __( 'Message', 'chip-for-tour-master' ),
					'type'        => 'text',
					'description' => esc_html__( 'Message on checkout page.', 'chip-for-tour-master' ),
				),
				'chip-fpx-currency-code'  => array(
					'title'   => esc_html__( 'CHIP Currency Code', 'chip-for-tour-master' ),
					'type'    => 'text',
					'default' => 'MYR',
				),
				'chip-fpx-processing-fee' => array(
					'title'       => esc_html__( 'CHIP Processing Fee', 'chip-for-tour-master' ),
					'type'        => 'text',
					'default'     => '0',
					'description' => esc_html__( 'Set 100 for RM 1 charge', 'chip-for-tour-master' ),
				),
			),
		);

		// FPX Corporate Payment Method
		$options['chip-fpx-corporate'] = array(
			'title'   => esc_html__( 'CHIP FPX Corporate', 'chip-for-tour-master' ),
			'options' => array(
				'chip-fpx-corporate-title'          => array(
					'title'   => __( 'Payment Method Title', 'chip-for-tour-master' ),
					'type'    => 'text',
					'default' => esc_html__( 'FPX Corporate', 'chip-for-tour-master' ),
				),
				'chip-fpx-corporate-secret-key'     => array(
					'title' => __( 'CHIP Secret Key', 'chip-for-tour-master' ),
					'type'  => 'text',
				),
				'chip-fpx-corporate-brand-id'       => array(
					'title' => __( 'CHIP Brand ID', 'chip-for-tour-master' ),
					'type'  => 'text',
				),
				'chip-fpx-corporate-logo'           => array(
					'title'       => __( 'Accepted Payment Type Logo', 'chip-for-tour-master' ),
					'type'        => 'multi-combobox',
					'options'     => array(
						'fpx'         => esc_html__( 'FPX', 'chip-for-tour-master' ),
					),
					'default'     => array( 'fpx' ),
					'description' => esc_html__( 'Only display images below CHIP option.', 'chip-for-tour-master' ),
				),
				'chip-fpx-corporate-msg-checkout'   => array(
					'title'       => __( 'Message', 'chip-for-tour-master' ),
					'type'        => 'text',
					'description' => esc_html__( 'Message on checkout page.', 'chip-for-tour-master' ),
				),
				'chip-fpx-corporate-currency-code'  => array(
					'title'   => esc_html__( 'CHIP Currency Code', 'chip-for-tour-master' ),
					'type'    => 'text',
					'default' => 'MYR',
				),
				'chip-fpx-corporate-processing-fee' => array(
					'title'       => esc_html__( 'CHIP Processing Fee', 'chip-for-tour-master' ),
					'type'        => 'text',
					'default'     => '0',
					'description' => esc_html__( 'Set 100 for RM 1 charge', 'chip-for-tour-master' ),
				),
			),
		);

		// Card Payment Method
		$options['chip-card'] = array(
			'title'   => esc_html__( 'CHIP Card', 'chip-for-tour-master' ),
			'options' => array(
				'chip-card-title'          => array(
					'title'   => __( 'Payment Method Title', 'chip-for-tour-master' ),
					'type'    => 'text',
					'default' => esc_html__( 'Card (Visa / Mastercard)', 'chip-for-tour-master' ),
				),
				'chip-card-secret-key'     => array(
					'title' => __( 'CHIP Secret Key', 'chip-for-tour-master' ),
					'type'  => 'text',
				),
				'chip-card-brand-id'       => array(
					'title' => __( 'CHIP Brand ID', 'chip-for-tour-master' ),
					'type'  => 'text',
				),
				'chip-card-logo'           => array(
					'title'       => __( 'Accepted Payment Type Logo', 'chip-for-tour-master' ),
					'type'        => 'multi-combobox',
					'options'     => array(
						'visa'        => esc_html__( 'Visa', 'chip-for-tour-master' ),
						'master-card' => esc_html__( 'Master Card', 'chip-for-tour-master' ),
					),
					'default'     => array( 'visa', 'master-card' ),
					'description' => esc_html__( 'Only display images below CHIP option.', 'chip-for-tour-master' ),
				),
				'chip-card-msg-checkout'   => array(
					'title'       => __( 'Message', 'chip-for-tour-master' ),
					'type'        => 'text',
					'description' => esc_html__( 'Message on checkout page.', 'chip-for-tour-master' ),
				),
				'chip-card-currency-code'  => array(
					'title'   => esc_html__( 'CHIP Currency Code', 'chip-for-tour-master' ),
					'type'    => 'text',
					'default' => 'MYR',
				),
				'chip-card-processing-fee' => array(
					'title'       => esc_html__( 'CHIP Processing Fee', 'chip-for-tour-master' ),
					'type'        => 'text',
					'default'     => '0',
					'description' => esc_html__( 'Set 100 for RM 1 charge', 'chip-for-tour-master' ),
				),
			),
		);

		// E-Wallet Payment Method
		$options['chip-ewallet'] = array(
			'title'   => esc_html__( 'CHIP E-Wallet', 'chip-for-tour-master' ),
			'options' => array(
				'chip-ewallet-title'          => array(
					'title'   => __( 'Payment Method Title', 'chip-for-tour-master' ),
					'type'    => 'text',
					'default' => esc_html__( 'E-Wallet', 'chip-for-tour-master' ),
				),
				'chip-ewallet-secret-key'     => array(
					'title' => __( 'CHIP Secret Key', 'chip-for-tour-master' ),
					'type'  => 'text',
				),
				'chip-ewallet-brand-id'       => array(
					'title' => __( 'CHIP Brand ID', 'chip-for-tour-master' ),
					'type'  => 'text',
				),
				'chip-ewallet-logo'           => array(
					'title'       => __( 'Accepted Payment Type Logo', 'chip-for-tour-master' ),
					'type'        => 'multi-combobox',
					'options'     => array(
						'tng'         => esc_html__( 'TnG', 'chip-for-tour-master' ),
						'grabpay'     => esc_html__( 'GrabPay', 'chip-for-tour-master' ),
						'maybank_qr'  => esc_html__( 'Maybank QR', 'chip-for-tour-master' ),
						'shopeepay'   => esc_html__( 'ShopeePay', 'chip-for-tour-master' ),
					),
					'default'     => array( 'tng', 'grabpay', 'maybank_qr', 'shopeepay' ),
					'description' => esc_html__( 'Only display images below CHIP option.', 'chip-for-tour-master' ),
				),
				'chip-ewallet-msg-checkout'   => array(
					'title'       => __( 'Message', 'chip-for-tour-master' ),
					'type'        => 'text',
					'description' => esc_html__( 'Message on checkout page.', 'chip-for-tour-master' ),
				),
				'chip-ewallet-currency-code'  => array(
					'title'   => esc_html__( 'CHIP Currency Code', 'chip-for-tour-master' ),
					'type'    => 'text',
					'default' => 'MYR',
				),
				'chip-ewallet-processing-fee' => array(
					'title'       => esc_html__( 'CHIP Processing Fee', 'chip-for-tour-master' ),
					'type'        => 'text',
					'default'     => '0',
					'description' => esc_html__( 'Set 100 for RM 1 charge', 'chip-for-tour-master' ),
				),
			),
		);

		// Atome Payment Method
		$options['chip-atome'] = array(
			'title'   => esc_html__( 'CHIP Atome', 'chip-for-tour-master' ),
			'options' => array(
				'chip-atome-title'          => array(
					'title'   => __( 'Payment Method Title', 'chip-for-tour-master' ),
					'type'    => 'text',
					'default' => esc_html__( 'Atome', 'chip-for-tour-master' ),
				),
				'chip-atome-secret-key'     => array(
					'title' => __( 'CHIP Secret Key', 'chip-for-tour-master' ),
					'type'  => 'text',
				),
				'chip-atome-brand-id'       => array(
					'title' => __( 'CHIP Brand ID', 'chip-for-tour-master' ),
					'type'  => 'text',
				),
				'chip-atome-logo'           => array(
					'title'       => __( 'Accepted Payment Type Logo', 'chip-for-tour-master' ),
					'type'        => 'multi-combobox',
					'options'     => array(
						'atome'       => esc_html__( 'Atome', 'chip-for-tour-master' ),
					),
					'default'     => array( 'atome' ),
					'description' => esc_html__( 'Only display images below CHIP option.', 'chip-for-tour-master' ),
				),
				'chip-atome-msg-checkout'   => array(
					'title'       => __( 'Message', 'chip-for-tour-master' ),
					'type'        => 'text',
					'description' => esc_html__( 'Message on checkout page.', 'chip-for-tour-master' ),
				),
				'chip-atome-currency-code'  => array(
					'title'   => esc_html__( 'CHIP Currency Code', 'chip-for-tour-master' ),
					'type'    => 'text',
					'default' => 'MYR',
				),
				'chip-atome-processing-fee' => array(
					'title'       => esc_html__( 'CHIP Processing Fee', 'chip-for-tour-master' ),
					'type'        => 'text',
					'default'     => '0',
					'description' => esc_html__( 'Set 100 for RM 1 charge', 'chip-for-tour-master' ),
				),
			),
		);

		// Duitnow QR Payment Method
		$options['chip-duitnow-qr'] = array(
			'title'   => esc_html__( 'CHIP DuitNow QR', 'chip-for-tour-master' ),
			'options' => array(
				'chip-duitnow-qr-title'          => array(
					'title'   => __( 'Payment Method Title', 'chip-for-tour-master' ),
					'type'    => 'text',
					'default' => esc_html__( 'DuitNow QR (TnG, ShopeePay, GrabPay, Maybank QR, MAE, etc)', 'chip-for-tour-master' ),
				),
				'chip-duitnow-qr-secret-key'     => array(
					'title' => __( 'CHIP Secret Key', 'chip-for-tour-master' ),
					'type'  => 'text',
				),
				'chip-duitnow-qr-brand-id'       => array(
					'title' => __( 'CHIP Brand ID', 'chip-for-tour-master' ),
					'type'  => 'text',
				),
				'chip-duitnow-qr-logo'           => array(
					'title'       => __( 'Accepted Payment Type Logo', 'chip-for-tour-master' ),
					'type'        => 'multi-combobox',
					'options'     => array(
						'duitnow_qr'  => esc_html__( 'QR Payment', 'chip-for-tour-master' ),
					),
					'default'     => array( 'duitnow_qr' ),
					'description' => esc_html__( 'Only display images below CHIP option.', 'chip-for-tour-master' ),
				),
				'chip-duitnow-qr-msg-checkout'   => array(
					'title'       => __( 'Message', 'chip-for-tour-master' ),
					'type'        => 'text',
					'description' => esc_html__( 'Message on checkout page.', 'chip-for-tour-master' ),
				),
				'chip-duitnow-qr-currency-code'  => array(
					'title'   => esc_html__( 'CHIP Currency Code', 'chip-for-tour-master' ),
					'type'    => 'text',
					'default' => 'MYR',
				),
				'chip-duitnow-qr-processing-fee' => array(
					'title'       => esc_html__( 'CHIP Processing Fee', 'chip-for-tour-master' ),
					'type'        => 'text',
					'default'     => '0',
					'description' => esc_html__( 'Set 100 for RM 1 charge', 'chip-for-tour-master' ),
				),
			),
		);

		// Add all payment methods to the payment-settings
		$options['payment-settings']['options']['payment-method']['options']['chip'] = chip_get_payment_title( 'chip' );
		$options['payment-settings']['options']['payment-method']['options']['chip-fpx'] = chip_get_payment_title( 'chip-fpx' );
		$options['payment-settings']['options']['payment-method']['options']['chip-fpx-corporate'] = chip_get_payment_title( 'chip-fpx-corporate' );
		$options['payment-settings']['options']['payment-method']['options']['chip-card'] = chip_get_payment_title( 'chip-card' );
		$options['payment-settings']['options']['payment-method']['options']['chip-ewallet'] = chip_get_payment_title( 'chip-ewallet' );
		$options['payment-settings']['options']['payment-method']['options']['chip-atome'] = chip_get_payment_title( 'chip-atome' );
		$options['payment-settings']['options']['payment-method']['options']['chip-duitnow-qr'] = chip_get_payment_title( 'chip-duitnow-qr' );

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

// Add AJAX handlers for new payment methods
add_action( 'wp_ajax_chip-fpx_payment_charge', 'chip_fpx_create_purchase' );
add_action( 'wp_ajax_nopriv_chip-fpx_payment_charge', 'chip_fpx_create_purchase' );

add_action( 'wp_ajax_chip-fpx-corporate_payment_charge', 'chip_fpx_corporate_create_purchase' );
add_action( 'wp_ajax_nopriv_chip-fpx-corporate_payment_charge', 'chip_fpx_corporate_create_purchase' );

add_action( 'wp_ajax_chip-card_payment_charge', 'chip_card_create_purchase' );
add_action( 'wp_ajax_nopriv_chip-card_payment_charge', 'chip_card_create_purchase' );

add_action( 'wp_ajax_chip-ewallet_payment_charge', 'chip_ewallet_create_purchase' );
add_action( 'wp_ajax_nopriv_chip-ewallet_payment_charge', 'chip_ewallet_create_purchase' );

add_action( 'wp_ajax_chip-atome_payment_charge', 'chip_atome_create_purchase' );
add_action( 'wp_ajax_nopriv_chip-atome_payment_charge', 'chip_atome_create_purchase' );

add_action( 'wp_ajax_chip-duitnow-qr_payment_charge', 'chip_duitnow_qr_create_purchase' );
add_action( 'wp_ajax_nopriv_chip-duitnow-qr_payment_charge', 'chip_duitnow_qr_create_purchase' );

add_filter( 'tourmaster_additional_payment_method', 'chip_additional_payment_method' );

if ( ! function_exists( 'chip_additional_payment_method' ) ) {
	function chip_additional_payment_method( $methods ) {
		$payment_methods = tourmaster_get_option( 'payment', 'payment-method', array() );
		$ret = '';

		// Original CHIP (All Methods)
		if ( in_array( 'chip', $payment_methods, true ) ) {
			$ret .= chip_get_payment_method_html( 'chip', chip_get_payment_title( 'chip' ), 'chip_payment_charge' );
		}

		// FPX
		if ( in_array( 'chip-fpx', $payment_methods, true ) ) {
			$ret .= chip_get_payment_method_html( 'chip-fpx', chip_get_payment_title( 'chip-fpx' ), 'chip_fpx_payment_charge' );
		}

		// FPX Corporate
		if ( in_array( 'chip-fpx-corporate', $payment_methods, true ) ) {
			$ret .= chip_get_payment_method_html( 'chip-fpx-corporate', chip_get_payment_title( 'chip-fpx-corporate' ), 'chip_fpx_corporate_payment_charge' );
		}

		// Card
		if ( in_array( 'chip-card', $payment_methods, true ) ) {
			$ret .= chip_get_payment_method_html( 'chip-card', chip_get_payment_title( 'chip-card' ), 'chip_card_payment_charge' );
		}

		// E-Wallet
		if ( in_array( 'chip-ewallet', $payment_methods, true ) ) {
			$ret .= chip_get_payment_method_html( 'chip-ewallet', chip_get_payment_title( 'chip-ewallet' ), 'chip_ewallet_payment_charge' );
		}

		// Atome
		if ( in_array( 'chip-atome', $payment_methods, true ) ) {
			$ret .= chip_get_payment_method_html( 'chip-atome', chip_get_payment_title( 'chip-atome' ), 'chip_atome_payment_charge' );
		}

		// DuitNow QR
		if ( in_array( 'chip-duitnow-qr', $payment_methods, true ) ) {
			$ret .= chip_get_payment_method_html( 'chip-duitnow-qr', chip_get_payment_title( 'chip-duitnow-qr' ), 'chip_duitnow_qr_payment_charge' );
		}

		return $ret;
	}
}

if ( ! function_exists( 'chip_get_payment_method_html' ) ) {
	function chip_get_payment_method_html( $method_key, $title, $ajax_action ) {
		$ret = '';
		$ret .= '<div class="tourmaster-online-payment-method tourmaster-payment-paypal">';
		$ret .= '<img width="170" height="76" src="' . esc_attr( CTM_PLUGIN_URL ) . '/assets/chip-payment.png" alt="' . esc_attr( $method_key ) . '" ';
		$ret .= 'data-method="ajax" data-action="tourmaster_payment_selected" data-ajax="' . esc_url( TOURMASTER_AJAX_URL ) . '" ';
		$ret .= 'data-action-type="' . esc_attr( $method_key ) . '" ';
		$ret .= ' />';

		// Get appropriate logos based on payment method
		$payment_types = chip_get_payment_logos( $method_key );
		if ( ! empty( $payment_types ) ) {
			$ret .= '<div class="tourmaster-payment-credit-card-type" >';
			foreach ( $payment_types as $type ) {
				$ret .= '<img style="height: 23px !important;" src="' . esc_attr( CTM_PLUGIN_URL ) . '/assets/' . esc_attr( $type ) . '.png" alt="' . esc_attr( $type ) . '" />';
			}
			$ret .= '</div>';
		}

		$checkout_message = tourmaster_get_option( 'payment', $method_key . '-msg-checkout', '' );
		if ( ! empty( $checkout_message ) ) {
			$ret .= '<div class="tourmaster-payment-paypal-service-fee-text" >';
			$ret .= esc_html( $checkout_message );
			$ret .= '</div>';
		}

		$ret .= '</div>';

		return $ret;
	}
}

if ( ! function_exists( 'chip_get_payment_logos' ) ) {
	function chip_get_payment_logos( $method_key ) {
		switch ( $method_key ) {
			case 'chip':
				return tourmaster_get_option( 'payment', 'chip-logo', array() );
			case 'chip-fpx':
				return tourmaster_get_option( 'payment', 'chip-fpx-logo', array( 'fpx' ) );
			case 'chip-fpx-corporate':
				return tourmaster_get_option( 'payment', 'chip-fpx-corporate-logo', array( 'fpx' ) );
			case 'chip-card':
				return tourmaster_get_option( 'payment', 'chip-card-logo', array( 'visa', 'master-card' ) );
			case 'chip-ewallet':
				return tourmaster_get_option( 'payment', 'chip-ewallet-logo', array( 'tng', 'grabpay', 'maybank_qr', 'shopeepay' ) );
			case 'chip-atome':
				return tourmaster_get_option( 'payment', 'chip-atome-logo', array( 'atome' ) );
			case 'chip-duitnow-qr':
				return tourmaster_get_option( 'payment', 'chip-duitnow-qr-logo', array( 'duitnow_qr' ) );
			default:
				return array();
		}
	}
}

if ( ! function_exists( 'chip_get_payment_title' ) ) {
	function chip_get_payment_title( $method_key ) {
		switch ( $method_key ) {
			case 'chip':
				return tourmaster_get_option( 'payment', 'chip-title', esc_html__( 'CHIP', 'chip-for-tour-master' ) );
			case 'chip-fpx':
				return tourmaster_get_option( 'payment', 'chip-fpx-title', esc_html__( 'FPX', 'chip-for-tour-master' ) );
			case 'chip-fpx-corporate':
				return tourmaster_get_option( 'payment', 'chip-fpx-corporate-title', esc_html__( 'FPX Corporate', 'chip-for-tour-master' ) );
			case 'chip-card':
				return tourmaster_get_option( 'payment', 'chip-card-title', esc_html__( 'Card (Visa / Mastercard)', 'chip-for-tour-master' ) );
			case 'chip-ewallet':
				return tourmaster_get_option( 'payment', 'chip-ewallet-title', esc_html__( 'E-Wallet  (TnG, ShopeePay, GrabPay, Maybank QR)', 'chip-for-tour-master' ) );
			case 'chip-atome':
				return tourmaster_get_option( 'payment', 'chip-atome-title', esc_html__( 'Atome', 'chip-for-tour-master' ) );
			case 'chip-duitnow-qr':
				return tourmaster_get_option( 'payment', 'chip-duitnow-qr-title', esc_html__( 'DuitNow QR  (TnG, ShopeePay, GrabPay, Maybank QR, MAE, etc)', 'chip-for-tour-master' ) );
			default:
				return esc_html__( 'CHIP', 'chip-for-tour-master' );
		}
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
					var action = 'chip_payment_charge'; // Default action

					$.ajax({
						type: 'POST',
						url: form.attr('data-ajax-url'),
						data: { 'action': action, 'tid': tid },
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

// Add payment forms for new payment methods
add_filter( 'goodlayers_chip-fpx_payment_form', 'tourmaster_chip_fpx_payment_form', 10, 2 );
if ( ! function_exists( 'tourmaster_chip_fpx_payment_form' ) ) {
	function tourmaster_chip_fpx_payment_form( $ret = '', $tid = '' ) {
		return tourmaster_chip_payment_form_generic( $ret, $tid, 'chip-fpx_payment_charge' );
	}
}

add_filter( 'goodlayers_chip-fpx-corporate_payment_form', 'tourmaster_chip_fpx_corporate_payment_form', 10, 2 );
if ( ! function_exists( 'tourmaster_chip_fpx_corporate_payment_form' ) ) {
	function tourmaster_chip_fpx_corporate_payment_form( $ret = '', $tid = '' ) {
		return tourmaster_chip_payment_form_generic( $ret, $tid, 'chip-fpx-corporate_payment_charge' );
	}
}

add_filter( 'goodlayers_chip-card_payment_form', 'tourmaster_chip_card_payment_form', 10, 2 );
if ( ! function_exists( 'tourmaster_chip_card_payment_form' ) ) {
	function tourmaster_chip_card_payment_form( $ret = '', $tid = '' ) {
		return tourmaster_chip_payment_form_generic( $ret, $tid, 'chip-card_payment_charge' );
	}
}

add_filter( 'goodlayers_chip-ewallet_payment_form', 'tourmaster_chip_ewallet_payment_form', 10, 2 );
if ( ! function_exists( 'tourmaster_chip_ewallet_payment_form' ) ) {
	function tourmaster_chip_ewallet_payment_form( $ret = '', $tid = '' ) {
		return tourmaster_chip_payment_form_generic( $ret, $tid, 'chip-ewallet_payment_charge' );
	}
}

add_filter( 'goodlayers_chip-atome_payment_form', 'tourmaster_chip_atome_payment_form', 10, 2 );
if ( ! function_exists( 'tourmaster_chip_atome_payment_form' ) ) {
	function tourmaster_chip_atome_payment_form( $ret = '', $tid = '' ) {
		return tourmaster_chip_payment_form_generic( $ret, $tid, 'chip-atome_payment_charge' );
	}
}

add_filter( 'goodlayers_chip-duitnow-qr_payment_form', 'tourmaster_chip_duitnow_qr_payment_form', 10, 2 );
if ( ! function_exists( 'tourmaster_chip_duitnow_qr_payment_form' ) ) {
	function tourmaster_chip_duitnow_qr_payment_form( $ret = '', $tid = '' ) {
		return tourmaster_chip_payment_form_generic( $ret, $tid, 'chip-duitnow-qr_payment_charge' );
	}
}

if ( ! function_exists( 'tourmaster_chip_payment_form_generic' ) ) {
	function tourmaster_chip_payment_form_generic( $ret = '', $tid = '', $action = 'chip_payment_charge' ) {
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
						data: { 'action': '<?php echo esc_js( $action ); ?>', 'tid': tid },
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

				$send_params = apply_filters( 'tourmaster_chip_payment_send_params_tour', $send_params, $tid );

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
					'method_key'     => 'chip', // Store the method key for status updates
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

// FPX Payment Creation Function
if ( ! function_exists( 'chip_fpx_create_purchase' ) ) {
	function chip_fpx_create_purchase() {
		$ret = chip_create_purchase_with_method( 'chip-fpx', array( 'fpx' ) );
		die( wp_json_encode( $ret ) );
	}
}

// FPX Corporate Payment Creation Function
if ( ! function_exists( 'chip_fpx_corporate_create_purchase' ) ) {
	function chip_fpx_corporate_create_purchase() {
		$ret = chip_create_purchase_with_method( 'chip-fpx-corporate', array( 'fpx_b2b1' ) );
		die( wp_json_encode( $ret ) );
	}
}

// Card Payment Creation Function
if ( ! function_exists( 'chip_card_create_purchase' ) ) {
	function chip_card_create_purchase() {
		$ret = chip_create_purchase_with_method( 'chip-card', array( 'visa', 'mastercard', 'maestro' ) );
		die( wp_json_encode( $ret ) );
	}
}

// E-Wallet Payment Creation Function
if ( ! function_exists( 'chip_ewallet_create_purchase' ) ) {
	function chip_ewallet_create_purchase() {
		$ret = chip_create_purchase_with_method( 'chip-ewallet', array( 'razer_grabpay', 'razer_shopeepay', 'razer_tng', 'razer_maybankqr' ) );
		die( wp_json_encode( $ret ) );
	}
}

// Atome Payment Creation Function
if ( ! function_exists( 'chip_atome_create_purchase' ) ) {
	function chip_atome_create_purchase() {
		$ret = chip_create_purchase_with_method( 'chip-atome', array( 'razer_atome' ) );
		die( wp_json_encode( $ret ) );
	}
}

// DuitNow QR Payment Creation Function
if ( ! function_exists( 'chip_duitnow_qr_create_purchase' ) ) {
	function chip_duitnow_qr_create_purchase() {
		$ret = chip_create_purchase_with_method( 'chip-duitnow-qr', array( 'duitnow_qr' ) );
		die( wp_json_encode( $ret ) );
	}
}

// Generic function to create purchase with specific payment method
if ( ! function_exists( 'chip_create_purchase_with_method' ) ) {
	function chip_create_purchase_with_method( $method_key, $payment_method_whitelist ) {
		$ret       = array();
		$timestamp = time();

		if ( ! empty( $_POST['tid'] ) ) {
			$tid = preg_replace( '/[^0-9]/', '', $_POST['tid'] );
			$tid = absint( $tid );

			$secret_key = trim( tourmaster_get_option( 'payment', $method_key . '-secret-key', '' ) );
			$brand_id   = trim( tourmaster_get_option( 'payment', $method_key . '-brand-id', '' ) );

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
			} elseif ( $currency_code != 'MYR' ) {
				$ret['status'] = 'failed';
				// translators: $curency_code is currency code.
				$ret['message'] = sprintf( esc_html__( '%1$s is unsupported currency.', 'chip-for-tour-master' ), $currency_code );
			} else {
				$price = round( floatval( $price ) * 100 );

				$chip = new Chip_Travel_Tour_API( $secret_key, $brand_id );

				// Resolve the whitelist so the DuitNow QR group (duitnow_qr/dnqr)
				// is sent as whatever the merchant actually has, prioritizing dnqr.
				$payment_method_whitelist = $chip->resolve_duitnow_methods( $payment_method_whitelist, $currency_code, $price );

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
					'payment_method_whitelist' => $payment_method_whitelist,
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

				$process_fee = trim( tourmaster_get_option( 'payment', $method_key . '-processing-fee', 0 ) );
				$process_fee = absint( wp_unslash( $process_fee ) );

				if ( $process_fee > 0 ) {
					$send_params['purchase']['products'][] = array(
						'name'  => esc_html__( 'Processing Fee', 'chip-for-tour-master' ),
						'price' => round( $process_fee ),
					);
				}

				$send_params = apply_filters( 'tourmaster_chip_payment_send_params_tour', $send_params, $tid );

				$purchase = $chip->create_payment( $send_params );

				if ( ! array_key_exists( 'id', $purchase ) ) {
					$ret['status']  = 'failed';
					$ret['message'] = sprintf( esc_html__( 'Failed to create purchase. %s', 'chip-for-tour-master' ), wp_json_encode( $purchase, JSON_PRETTY_PRINT ) );
					return $ret;
				}

				$payment_info = array(
					'id'             => $purchase['id'],
					'transaction_id' => $purchase['id'] . '-pending',
					'payment_method' => 'CHIP',
					'payment_status' => $purchase['status'],
					'timestamp'      => $timestamp,
					'method_key'     => $method_key, // Store the method key for status updates
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

		return $ret;
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

	// Use the generic handler for all CHIP payment methods
	// The method_key is already stored in payment_info from when the purchase was created
	chip_handle_status_update( $payment_info, $tid, true );
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

	// The method_key is already stored in payment_info from when the purchase was created
	$method_key = isset( $payment_info['method_key'] ) ? $payment_info['method_key'] : 'chip';
	$secret_key = trim( tourmaster_get_option( 'payment', $method_key . '-secret-key', '' ) );
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

	// Handle callback specifically for webhook data
	$method_key = isset( $payment_info['method_key'] ) ? $payment_info['method_key'] : 'chip';
	
	// Get processing fee for the specific method
	$process_fee = trim( tourmaster_get_option( 'payment', $method_key . '-processing-fee', 0 ) );
	$process_fee = absint( wp_unslash( $process_fee ) ) / 100;
	
	$price = $purchase['payment']['amount'] / 100;
	$price = $price - $process_fee;

	if ( ! empty( $booking_data->currency ) ) {
		$currency = json_decode( $booking_data->currency, true );
		$price    = $price / floatval( $currency['exchange-rate'] );
	}

	$new_payment_info = array(
		'transaction_id'  => $purchase['id'],
		'amount'          => $price,
		'payment_method'  => $method_key,
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

// Generic status update function that determines which payment method was used
if ( ! function_exists( 'chip_handle_status_update' ) ) {
	function chip_handle_status_update( $payment_info, $tid, $is_redirect = false ) {
		$method_key = isset( $payment_info['method_key'] ) ? $payment_info['method_key'] : 'chip';
		
		// Get the appropriate secret key and brand ID for the method
		$secret_key = trim( tourmaster_get_option( 'payment', $method_key . '-secret-key', '' ) );
		$brand_id   = trim( tourmaster_get_option( 'payment', $method_key . '-brand-id', '' ) );
		
		// Get processing fee for the specific method
		$process_fee = trim( tourmaster_get_option( 'payment', $method_key . '-processing-fee', 0 ) );
		$process_fee = absint( wp_unslash( $process_fee ) ) / 100;
		
		$chip     = new Chip_Travel_Tour_API( $secret_key, $brand_id );
		$purchase = $chip->get_payment( $payment_info['id'] );

		if ( $purchase['status'] !== 'paid' ) {
			if ( $is_redirect ) {
				wp_safe_redirect( tourmaster_get_template_url( 'payment' ) );
				exit;
			} else {
				exit;
			}
		}

		$price = $purchase['payment']['amount'] / 100;
		$price = $price - $process_fee;

		$booking_data = tourmaster_get_booking_data( array( 'id' => $tid ), array( 'single' => true ) );

		if ( ! empty( $booking_data->currency ) ) {
			$currency = json_decode( $booking_data->currency, true );
			$price    = $price / floatval( $currency['exchange-rate'] );
		}

		$new_payment_info = array(
			'transaction_id'  => $purchase['id'],
			'amount'          => $price,
			'payment_method'  => $method_key,
			'payment_status'  => $purchase['status'],
			'submission_date' => current_time( 'mysql' ),
			'timestamp'       => time(),
		);

		$payment_infos = json_decode( $booking_data->payment_info, true );
		
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

		if ( $is_redirect ) {
			$success_redirect = add_query_arg(
				array(
					'tid'            => $tid,
					'step'           => 4,
					'payment_method' => 'paypal',
				),
				tourmaster_get_template_url( 'payment' )
			);
			wp_safe_redirect( $success_redirect );
			exit;
		}
	}
}

add_filter( 'tourmaster_custom_payment_enable', 'chip_tm_custom_payment_enable', 10, 2 );

/**
 * Enable CHIP payment method
 *
 * @param bool   $status         Payment status.
 * @param string $payment_method Payment method.
 *
 * @return bool
 */
function chip_tm_custom_payment_enable( $status, $payment_method ) {
	if ( $status ) {
		return true;
	}
	
	// Check for all CHIP payment methods
	$chip_methods = array( 'chip', 'chip-fpx', 'chip-fpx-corporate', 'chip-card', 'chip-ewallet', 'chip-atome', 'chip-duitnow-qr' );
	foreach ( $chip_methods as $method ) {
		if ( in_array( $method, $payment_method, true ) ) {
			return true;
		}
	}
	
	return false;
}

add_filter( 'tourmaster_custom_payment_selection', 'chip_tm_custom_payment_selection', 10, 2 );
/** Add CHIP payment method to the payment selection.
 *
 * @param string $ret           The current payment method.
 * @param array  $payment_method The selected payment methods.
 *
 * @return string The updated payment method.
 */
function chip_tm_custom_payment_selection( $ret, $payment_method ) {
	$payment_methods = array(
		'chip' => chip_get_payment_title( 'chip' ),
		'chip-fpx' => chip_get_payment_title( 'chip-fpx' ),
		'chip-fpx-corporate' => chip_get_payment_title( 'chip-fpx-corporate' ),
		'chip-card' => chip_get_payment_title( 'chip-card' ),
		'chip-ewallet' => chip_get_payment_title( 'chip-ewallet' ),
		'chip-atome' => chip_get_payment_title( 'chip-atome' ),
		'chip-duitnow-qr' => chip_get_payment_title( 'chip-duitnow-qr' ),
	);
	
	foreach ( $payment_methods as $method_key => $method_name ) {
		if ( in_array( $method_key, $payment_method, true ) ) {
			$ret .= '<option value="' . esc_attr( $method_key ) . '">' . esc_html( $method_name ) . '</option>';
		}
	}
	
	return $ret;
}
