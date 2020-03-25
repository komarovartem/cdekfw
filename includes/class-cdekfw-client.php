<?php
/**
 * CDEK client
 *
 * @package CDEK
 * @since   1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Client API API connection
 *
 * @class CDEKFW_Client
 */
class CDEKFW_Client {
	/**
	 * Calculate shipping rate
	 *
	 * @param array $args Shipping params.
	 *
	 * @return bool|mixed|null
	 */
	public static function calculate_rate( $args ) {
		$client = self::get_client_credentials();
		$date   = current_time( 'mysql' );

		$args = array_merge(
			$args,
			array(
				'version'     => '1.0',
				'currency'    => get_woocommerce_currency(),
				'authLogin'   => $client['account'],
				'secure'      => md5( $date . '&' . $client['password'] ),
				'dateExecute' => $date,
			)
		);

		return self::get_data_from_api( 'calculator/calculate_tarifflist.php', $args );
	}

	/**
	 * Get delivery points
	 *
	 * @return array|bool
	 */
	public static function get_pvz_list() {
		$postcode        = WC()->customer->get_shipping_postcode();
		$is_cod          = 'allowed_cod';
		$delivery_points = array();

		$items = self::get_data_from_api( 'v2/deliverypoints?postal_code=' . $postcode, array(), 'GET' );

		if ( ! $items ) {
			return false;
		}

		foreach ( $items as $item ) {
			$delivery_points[ $item['code'] ] = $item['location']['adress'];
		}

		return $delivery_points;
	}

	/**
	 * Get client credentials for requests
	 *
	 * If no credentials are set use test data
	 *
	 * @return array
	 */
	public static function get_client_credentials() {
		if ( get_option( 'cdek_account' ) ) {
			return array(
				'account'  => get_option( 'cdek_account' ),
				'password' => get_option( 'cdek_password' ),
				'api_url'  => 'http://api.cdek.ru/',
			);
		} else {
			return array(
				'account'  => 'z9GRRu7FxmO53CQ9cFfI6qiy32wpfTkd',
				'password' => 'w24JTCv4MnAcuRTx0oHjHLDtyt3I6IBq',
				'api_url'  => 'http://api.edu.cdek.ru/',
			);
		}
	}

	/**
	 * Get client auth token
	 *
	 * @return string
	 */
	public static function get_client_auth_token() {
		$client     = self::get_client_credentials();
		$hash       = 'cdek_auth_token_' . md5( $client['account'] );
		$auth_token = get_transient( $hash );

		if ( ! $auth_token ) {
			$parameters = array(
				'grant_type'    => 'client_credentials',
				'client_id'     => $client['account'],
				'client_secret' => $client['password'],
			);

			$request         = add_query_arg( $parameters, $client['api_url'] . 'v2/oauth/token' );
			$remote_response = wp_remote_post(
				$request,
				array(
					'timeout'   => 30,
					'sslverify' => false,
					'headers'   => array(
						'Content-Type' => 'application/x-www-form-urlencoded',
					),
				)
			);

			if ( ! $remote_response ) {
				return false;
			}

			$response_code = wp_remote_retrieve_response_code( $remote_response );

			if ( 200 !== $response_code ) {
				return false;
			}

			$response_body = json_decode( wp_remote_retrieve_body( $remote_response ), true );

			if ( ! isset( $response_body['access_token'] ) ) {
				return false;
			}

			$auth_token = $response_body['access_token'];

			set_transient( $hash, $auth_token, $response_body['expires_in'] );
		}

		return $auth_token;
	}

	/**
	 * Connect to Post API and get body for requested URL
	 *
	 * @param string $url API url.
	 * @param array  $body Request body.
	 * @param string $method Type.
	 *
	 * @return bool|mixed|null
	 */
	public static function get_data_from_api( $url, $body = array(), $method = 'POST' ) {

		$client            = self::get_client_credentials();
		$client_auth_token = self::get_client_auth_token();

		if ( ! $client_auth_token ) {
			CDEKFW::log_it( esc_html__( 'Could not get client auth token', 'cdek-for-woocommerce' ) . ' ' . $url );

			return false;
		}

		$remote_response = wp_remote_request(
			$client['api_url'] . $url,
			array(
				'headers' => array(
					'Authorization' => 'Bearer ' . $client_auth_token,
					'Accept'        => 'application/json;charset=UTF-8',
					'Content-Type'  => 'application/json',
				),
				'method'  => $method,
				'body'    => wp_json_encode( $body, JSON_UNESCAPED_UNICODE ),
				'timeout' => 100, // must be that big for huge requests like getting PVZ list.
			)
		);

		CDEKFW::log_it( esc_html__( 'Making request to get:', 'cdek-for-woocommerce' ) . ' ' . $url );

		if ( is_wp_error( $remote_response ) ) {
			CDEKFW::log_it( esc_html__( 'Cannot connect to', 'cdek-for-woocommerce' ) . ' ' . $url . ' ' . $remote_response->get_error_message() . ' Body: ' . wp_json_encode( $body, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT ), 'error' );

			return false;
		}

		$response_code = wp_remote_retrieve_response_code( $remote_response );

		if ( 200 !== $response_code ) {
			CDEKFW::log_it( esc_html__( 'Cannot connect to', 'cdek-for-woocommerce' ) . ' ' . $url . ' ' . $response_code . ' ' . wp_remote_retrieve_body( $remote_response ) . ' Body: ' . wp_json_encode( $body, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT ), 'error' );

			return false;
		}

		$response_body = json_decode( wp_remote_retrieve_body( $remote_response ), true );

		if ( isset( $response_body['error'] ) ) {
			CDEKFW::log_it( esc_html__( 'API request error:', 'cdek-for-woocommerce' ) . ' ' . $url . wp_json_encode( $response_body, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT ) . '<br> Body' . wp_json_encode( $body, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT ), 'error' );

			return false;
		}

		return $response_body;
	}
}


function cdek_test() {
	$client = CDEKFW_Client::get_client_credentials();
	$date   = current_time( 'mysql' );

	$args = array(
		'version'              => '1.0',
		'receiverCityPostCode' => '675000',
		'senderCityPostCode'   => '101000',
		'goods'                => array(
			array(
				'weight' => 1,
				'length' => 10,
				'width'  => 20,
				'height' => 10,
			),
		),
		'tariffId'             => 1,
		'currency'             => get_woocommerce_currency(),
		'services'             => array(),
		'authLogin'            => $client['account'],
		'secure'               => md5( $date . '&' . $client['password'] ),
		'dateExecute'          => $date,
	);

	// $res = CDEKFW::get_data_from_api( 'calculator/calculate_tarifflist.php', $args );
	// $data = CDEKFW::get_data_from_api( 'v2/deliverypoints?postal_code=675000', array(), 'GET' );

	// CDEKFW::get_client_auth_token();
}

// add_action( 'wp_footer', 'cdek_test' );
