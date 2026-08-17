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
	 * DuitNow QR payment method group.
	 *
	 * dnqr is the modern identifier; duitnow_qr is the legacy identifier kept
	 * for backward compatibility. The group is resolved against the merchant's
	 * actual /payment_methods/ response at runtime, prioritizing dnqr.
	 *
	 * @since 1.1.0
	 */
	const DUITNOW_GROUP = array( 'duitnow_qr', 'dnqr' );

	/**
	 * Shopee Pay payment method group.
	 *
	 * shopee_pay is the modern identifier (whitelist-only); razer_shopeepay is
	 * the legacy identifier. The group is resolved against the merchant's actual
	 * /payment_methods/ response at runtime, prioritizing shopee_pay.
	 *
	 * @since 1.1.0
	 */
	const SHOPEE_GROUP = array( 'razer_shopeepay', 'shopee_pay' );

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

	/**
	 * Resolve the configured payment_method_whitelist against the merchant's
	 * actual /payment_methods/ response, applying group preference for both the
	 * DuitNow QR (dnqr) and Shopee Pay (shopee_pay) groups.
	 *
	 * Steps:
	 *   1. Short-circuit: if the whitelist intersects neither group, return unchanged
	 *      (no API call, no group injection).
	 *   2. Group expansion: any member of a configured group expands to its full group.
	 *   3. Cache key: brand + currency + amount-bucket (round to 100-sen steps).
	 *   4. Try cache. On miss, call /payment_methods/ once and cache the result.
	 *   5. Fallback: return the expanded whitelist unchanged if the API fails.
	 *   6. Resolve each group against the available methods with preference:
	 *      dnqr beats duitnow_qr; shopee_pay beats razer_shopeepay.
	 *   7. Build the final whitelist (original non-group entries + resolved groups).
	 *
	 * @param array  $whitelist Configured payment_method_whitelist.
	 * @param string $currency  Order currency code (e.g. 'MYR').
	 * @param int    $amount    Order total in sen (e.g. 12345 = RM 123.45).
	 * @return array            Final whitelist to send to CHIP.
	 */
	public function resolve_duitnow_methods( $whitelist, $currency, $amount ) {
		$whitelist = array_values( (array) $whitelist );

		$groups = array(
			'dnqr'     => array(
				'members'  => self::DUITNOW_GROUP,
				'prefer'   => 'dnqr',
				'fallback' => 'duitnow_qr',
			),
			'shopee'   => array(
				'members'  => self::SHOPEE_GROUP,
				'prefer'   => 'shopee_pay',
				'fallback' => 'razer_shopeepay',
			),
		);

		// 1. Short-circuit: a whitelist that intersects neither group is returned
		// untouched (no API call, no group injection).
		$all_members = array();
		foreach ( $groups as $group ) {
			$all_members = array_merge( $all_members, $group['members'] );
		}

		$has_group_member = count( array_intersect( $whitelist, $all_members ) ) > 0;

		if ( ! $has_group_member ) {
			return $whitelist;
		}

		// 2. Group expansion: any configured group member expands to its full group.
		$expanded = array_values( array_unique( array_merge( $whitelist, $all_members ) ) );

		// 3. Cache key: brand + currency + amount-bucket (round to 100-sen steps).
		$cache_key = 'chip_pm_' . md5( $this->brand_id . '|' . $currency . '|' . intval( $amount / 100 ) );

		// 4. Try cache. If hit, use it. If miss, call /payment_methods/ once.
		$available = get_transient( $cache_key );
		if ( false === $available ) {
			$response = $this->payment_methods( $currency, '', $amount ); // No language param.
			if ( ! is_array( $response ) || ! isset( $response['available_payment_methods'] ) ) {
				// 5. Fallback: return expanded whitelist unchanged.
				return $expanded;
			}
			$available = $response['available_payment_methods'];
			set_transient( $cache_key, $available, 30 * MINUTE_IN_SECONDS );
		}

		$available = (array) $available;

		// 6. Resolve each configured group against the available methods with preference.
		$resolved_groups = array();
		foreach ( $groups as $group ) {
			if ( ! $this->whitelist_has_group( $whitelist, $group['members'] ) ) {
				continue;
			}

			// Intersect: keep only group members the merchant actually has.
			$resolved = array_values( array_intersect( $group['members'], $available ) );

			// Priority: the preferred value wins when both are present.
			if ( in_array( $group['prefer'], $resolved, true ) ) {
				$resolved = array_values( array_diff( $resolved, array( $group['fallback'] ) ) );
			}

			$resolved_groups = array_merge( $resolved_groups, $resolved );
		}

		// 7. Build final whitelist: original entries (with group members stripped) + resolved groups.
		$final = array_values( array_diff( $expanded, $all_members ) );
		$final = array_merge( $final, $resolved_groups );

		return $final;
	}

	/**
	 * Whether the whitelist contains any member of the given group.
	 *
	 * @param array $whitelist Configured payment_method_whitelist.
	 * @param array $members   Group members to look for.
	 * @return bool
	 */
	private function whitelist_has_group( $whitelist, $members ) {
		return count( array_intersect( $whitelist, $members ) ) > 0;
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
