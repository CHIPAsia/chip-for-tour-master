<?php
/**
 * Chip Travel Tour API Class File
 *
 * This file contains the Chip_Travel_Tour_API class, which provides methods
 * for interacting with the CHIP API for travel and tour-related operations.
 *
 * @package Chip_Travel_Tour
 */
class Chip_Travel_Tour_API {
	/**
	 * CHIP Secret Key.
	 *
	 * @var $secret_key
	 */
	public $secret_key;

	/**
	 * CHIP Brand ID.
	 *
	 * @var $brand_id
	 */
	public $brand_id;

	/**
	 * Constructor for the Chip_Travel_Tour_API class.
	 *
	 * @param string $secret_key The CHIP secret key.
	 * @param string $brand_id   The CHIP brand ID.
	 */
	public function __construct( $secret_key, $brand_id ) {
		$this->secret_key = $secret_key;
		$this->brand_id   = $brand_id;
	}

	/**
	 * Set the CHIP secret key and brand ID.
	 *
	 * @param string $secret_key The CHIP secret key.
	 * @param string $brand_id   The CHIP brand ID.
	 */
	public function set_key( $secret_key, $brand_id ) {
		$this->secret_key = $secret_key;
		$this->brand_id   = $brand_id;
	}

	/**
	 * Create a payment using the CHIP API.
	 *
	 * @param array $params The parameters for the payment.
	 * @return array|null The response from the API or null on failure.
	 */
	public function create_payment( $params ) {
		return $this->call( 'POST', '/purchases/?time=' . time(), $params );
	}

	/**
	 * Create a client using the CHIP API.
	 *
	 * @param array $params The parameters for client.
	 * @return array|null The response from the API or null on failure.
	 */
	public function create_client( $params ) {
		return $this->call( 'POST', '/clients/', $params );
	}

	/**
	 * Get a client by email using the CHIP API.
	 *
	 * @param string $email The email address of the client.
	 * @return array|null The response from the API or null on failure.
	 */
	public function get_client_by_email( $email ) {
		$email_encoded = urlencode( $email );
		return $this->call( 'GET', "/clients/?q={$email_encoded}" );
	}

	/**
	 * Patch a client by ID using the CHIP API.
	 *
	 * @param string $client_id The ID of the client.
	 * @param array  $params The parameter of the client.
	 * @return array|null The response from the API or null on failure.
	 */
	public function patch_client( $client_id, $params ) {
		return $this->call( 'PATCH', "/clients/{$client_id}/", $params );
	}

	public function delete_token( $purchase_id ) {
		return $this->call( 'POST', "/purchases/$purchase_id/delete_recurring_token/" );
	}

	public function capture_payment( $payment_id, $params = array() ) {
		return $this->call( 'POST', "/purchases/{$payment_id}/capture/", $params );
	}

	public function release_payment( $payment_id ) {
		return $this->call( 'POST', "/purchases/{$payment_id}/release/" );
	}

	public function charge_payment( $payment_id, $params ) {
		return $this->call( 'POST', "/purchases/{$payment_id}/charge/", $params );
	}

	public function payment_methods( $currency, $language, $amount ) {
		return $this->call(
			'GET',
			"/payment_methods/?brand_id={$this->brand_id}&currency={$currency}&language={$language}&amount={$amount}"
		);
	}

	public function payment_recurring_methods( $currency, $language, $amount ) {
		return $this->call(
			'GET',
			"/payment_methods/?brand_id={$this->brand_id}&currency={$currency}&language={$language}&amount={$amount}&recurring=true"
		);
	}

	public function get_payment( $payment_id ) {
		// time() is to force fresh instead cache
		$result = $this->call( 'GET', "/purchases/{$payment_id}/?time=" . time() );

		return $result;
	}

	public function refund_payment( $payment_id, $params ) {
		$result = $this->call( 'POST', "/purchases/{$payment_id}/refund/", $params );

		return $result;
	}

	public function public_key() {

		$result = $this->call( 'GET', '/public_key/' );

		return $result;
	}

	public function turnover() {
		$result = $this->call( 'GET', '/account/json/turnover/?currency=MYR' );

		return $result;
	}

	public function balance() {
		$result = $this->call( 'GET', '/account/json/balance/?currency=MYR' );

		return $result;
	}

	private function call( $method, $route, $params = array() ) {
		$secret_key = $this->secret_key;
		if ( ! empty( $params ) ) {
			$params = json_encode( $params );
		}

		$response = $this->request(
			$method,
			sprintf( '%s/v1%s', 'https://gate.chip-in.asia/api', $route ),
			$params,
			array(
				'Content-type'  => 'application/json',
				'Authorization' => "Bearer {$secret_key}",
			)
		);

		$result = json_decode( $response, true );

		if ( ! $result ) {
			return null;
		}

		if ( ! empty( $result['errors'] ) ) {
			return null;
		}

		return $result;
	}

	private function request( $method, $url, $params = array(), $headers = array() ) {

		$wp_request = wp_remote_request(
			$url,
			array(
				'method'    => $method,
				'sslverify' => ! defined( 'TT_CHIP_SSLVERIFY_FALSE' ),
				'headers'   => $headers,
				'body'      => $params,
				'timeout'   => 10, // charge card require longer timeout.
			)
		);

		$response = wp_remote_retrieve_body( $wp_request );

		switch ( $code = wp_remote_retrieve_response_code( $wp_request ) ) {
			case 200:
			case 201:
				break;
			default:
		}

		return $response;
	}
}
