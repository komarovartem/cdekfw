<?php
/**
 * CDEK client
 *
 * @package CDEK/Client
 * @since   1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Client API connection
 *
 * @class CDEKFW_Client
 */
class CDEKFW_Client {
	/**
	 * Main api url.
	 *
	 * @var string
	 */
	private static $api_url = 'https://api.cdek.ru/';

	/**
	 * Calculate shipping rate https://confluence.cdek.ru/pages/viewpage.action?pageId=15616129#id-%D0%9F%D1%80%D0%BE%D1%82%D0%BE%D0%BA%D0%BE%D0%BB%D0%BE%D0%B1%D0%BC%D0%B5%D0%BD%D0%B0%D0%B4%D0%B0%D0%BD%D0%BD%D1%8B%D0%BC%D0%B8(v1.5)-4.14.1.%D0%A0%D0%B0%D1%81%D1%87%D0%B5%D1%82%D1%81%D1%82%D0%BE%D0%B8%D0%BC%D0%BE%D1%81%D1%82%D0%B8%D0%BF%D0%BE%D1%82%D0%B0%D1%80%D0%B8%D1%84%D0%B0%D0%BC%D1%81%D0%BF%D1%80%D0%B8%D0%BE%D1%80%D0%B8%D1%82%D0%B5%D1%82%D0%BE%D0%BC
	 *
	 * @param array $args Shipping params.
	 *
	 * @return bool|mixed|null
	 */
	public static function calculate_rate( $args ) {
		$client = self::get_client_credentials();
		$date   = gmdate( 'Y-m-d', strtotime( current_time( 'mysql' ) ) );

		$args = array_merge(
			$args,
			array(
				'version'     => '1.0',
				'currency'    => get_woocommerce_currency(),
				'dateExecute' => $date,
				'authLogin'   => $client['account'],
				'secure'      => md5( $date . '&' . $client['password'] ),
			)
		);

		return self::get_data_from_api( 'calculator/calculate_price_by_json.php', $args, 'POST', false );
	}

	/**
	 * Create new order https://confluence.cdek.ru/pages/viewpage.action?pageId=29923926
	 *
	 * @param array $args Orders params.
	 *
	 * @return bool|mixed|null
	 */
	public static function create_order( $args ) {
		return self::get_data_from_api( 'v2/orders', $args );
	}

	/**
	 * Delete order info https://confluence.cdek.ru/pages/viewpage.action?pageId=29924487
	 *
	 * @param string $args Order uuid key.
	 *
	 * @return bool|mixed|null
	 */
	public static function delete_order( $args ) {
		return self::get_data_from_api( 'v2/orders/' . $args, array(), 'DELETE' );
	}

	/**
	 * Get order info https://confluence.cdek.ru/pages/viewpage.action?pageId=29923975
	 *
	 * @param string $args Order uuid key.
	 *
	 * @return bool|mixed|null
	 */
	public static function get_order( $args ) {
		return self::get_data_from_api( 'v2/orders/' . $args, array(), 'GET' );
	}

	/**
	 * Get delivery points https://confluence.cdek.ru/pages/viewpage.action?pageId=36982648
	 *
	 * @return array|bool
	 */
	public static function get_pvz_list() {
		$postcode        = WC()->customer->get_shipping_postcode();
		$state           = WC()->customer->get_shipping_state();
		$city            = WC()->customer->get_shipping_city();
		$country         = WC()->customer->get_shipping_country();
		$is_cod          = 'allowed_cod';
		$delivery_points = array();

		$args = array(
			'country_code' => $country,
		);

		if ( 'RU' === $country ) {
			if ( CDEKFW::is_pro_active() && $state && $city ) {
				$postcode = CDEKFW_PRO_Ru_Base::get_index_based_on_address( $state, $city );
			}
			$args['postal_code'] = $postcode;
		}

		$items = self::get_data_from_api( add_query_arg( $args, 'v2/deliverypoints' ), array(), 'GET', false );

		if ( ! $items ) {
			return false;
		}

		foreach ( $items as $item ) {
			if ( isset( $item['location']['adress'] ) && isset( $item['location']['latitude'] ) ) {
				$delivery_points[] = array(
					'fullAddress' => 'RU' === $country ? '' : $item['location']['city'] . ',' . $item['location']['adress'],
					'code'        => $item['code'],
					'city_code'   => $item['location']['city_code'],
					'address'     => $item['location']['adress'],
					'coordinates' => $item['location']['latitude'] . ',' . $item['location']['longitude'],
				);
			}
		}

		return $delivery_points;
	}

	/**
	 * Get new updated version for delivery points from API
	 *
	 * @return bool
	 */
	public static function retrieve_all_pvz() {
		$data = self::get_data_from_api( 'v2/deliverypoints', array(), 'GET' );

		if ( ! $data ) {
			return false;
		}

		$file_all = fopen( CDEK_ABSPATH . 'includes/lists/pvz-all.json', 'w+' );
		fwrite( $file_all, json_encode( $data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT ) );
		fclose( $file_all );

		return true;
	}

	/**
	 * Get new updated version for delivery points from API
	 *
	 * @return bool
	 */
	public static function retrieve_all_city_codes() {
		$url  = add_query_arg(
			array(
				'country_codes' => array( 'RU' ),
				'size'          => 99999,
				'page'          => 0,
			),
			'v2/location/cities'
		);
		$data = self::get_data_from_api( $url, array(), 'GET' );

		if ( ! $data ) {
			return false;
		}

		$file_all = fopen( CDEK_ABSPATH . 'includes/lists/cities-ru.json', 'w+' );
		fwrite( $file_all, json_encode( $data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT ) );
		fclose( $file_all );

		return true;
	}

	/**
	 * Get new updated version for delivery points from API
	 *
	 * @return bool
	 */
	public static function retrieve_all_region_codes() {
		$url  = add_query_arg(
			array(),
			'v2/location/regions'
		);
		$data = self::get_data_from_api( $url, array(), 'GET' );

		if ( ! $data ) {
			return false;
		}

		$file_all = fopen( CDEK_ABSPATH . 'includes/lists/regions.json', 'w+' );
		fwrite( $file_all, json_encode( $data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT ) );
		fclose( $file_all );

		return true;
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
				'api_url'  => 'https://api.cdek.ru/',
			);
		} else {
			return array(
				'account'  => 'EMscd6r9JnFiQ3bLoyjJY6eM78JrJceI',
				'password' => 'PjLZkKBHEiLK3YsjtNrt3TGNG0ahs3kG',
				'api_url'  => 'https://api.edu.cdek.ru/',
			);
		}
	}

	/**
	 * Get client auth token
	 *
	 * @return string|mixed
	 */
	public static function get_client_auth_token() {
		$client     = self::get_client_credentials();
		$hash       = 'cdek_cache_auth_token_' . md5( $client['account'] );
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
					'timeout'   => 50,
					'sslverify' => false,
					'headers'   => array(
						'Content-Type' => 'application/x-www-form-urlencoded',
					),
				)
			);

			$error_msg = esc_html__( 'Could not get client auth token.', 'cdek-for-woocommerce' );

			if ( ! $remote_response ) {
				CDEKFW::log_it( $error_msg . ' ' . wp_json_encode( $remote_response ), 'error' );

				return false;
			}

			$response_code = wp_remote_retrieve_response_code( $remote_response );

			if ( 200 !== $response_code ) {
				CDEKFW::log_it( $error_msg . ' ERROR: ' . wp_json_encode( $response_code ) . ' ' . wp_remote_retrieve_body( $remote_response ), 'error' );

				return false;
			}

			$response_body = json_decode( wp_remote_retrieve_body( $remote_response ), true );

			if ( ! isset( $response_body['access_token'] ) ) {
				CDEKFW::log_it( $error_msg . ' ' . wp_json_encode( $response_body ), 'error' );

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
	 * @param string  $url API url.
	 * @param array   $body Request body.
	 * @param string  $method Type.
	 * @param boolean $skip_cache Skip cash.
	 *
	 * @return bool|mixed|null
	 */
	public static function get_data_from_api( $url, $body = array(), $method = 'POST', $skip_cache = true ) {
		if ( ! $skip_cache ) {
			$client = self::get_client_credentials();
			$hash   = self::get_request_hash( $client['account'], $url, $body );
			$cache  = get_transient( $hash );

			if ( $cache ) {
				if ( isset( $cache['error'] ) || isset( $response_body['errors'] ) ) {
					CDEKFW::log_it( esc_html__( 'API request error:', 'cdek-for-woocommerce' ) . ' ' . $url . ' ' . wp_json_encode( $cache, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT ) . 'Body' . wp_json_encode( $body, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT ), 'error' );

					return false;
				}

				return $cache;
			}
		}

		$client_auth_token = self::get_client_auth_token();

		if ( ! $client_auth_token ) {
			return false;
		}

		$remote_response = wp_remote_request(
			self::$api_url . $url,
			array(
				'headers' => array(
					'Authorization' => 'Bearer ' . $client_auth_token,
					'Accept'        => 'application/json;charset=UTF-8',
					'Content-Type'  => 'application/json',
				),
				'method'  => $method,
				'body'    => $body ? wp_json_encode( $body, JSON_UNESCAPED_UNICODE ) : '',
				'timeout' => 100, // must be that big for huge requests like getting PVZ list.
			)
		);

		CDEKFW::log_it( esc_html__( 'Making request to', 'cdek-for-woocommerce' ) . ' ' . $method . ': ' . $url . ' ' . esc_html__( 'with the next body:', 'cdek-for-woocommerce' ) . ' ' . wp_json_encode( $body, JSON_UNESCAPED_UNICODE ) );

		if ( is_wp_error( $remote_response ) ) {
			CDEKFW::log_it( esc_html__( 'Cannot connect to', 'cdek-for-woocommerce' ) . ' ' . $url . ' ' . $remote_response->get_error_message() . ' Body: ' . wp_json_encode( $body, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT ), 'error' );

			return false;
		}

		$response_code = intval( wp_remote_retrieve_response_code( $remote_response ) );

		if ( ! in_array( $response_code, array( 200, 202 ), true ) ) {
			CDEKFW::log_it( esc_html__( 'Cannot connect to', 'cdek-for-woocommerce' ) . ' ' . $url . ' ' . esc_html__( 'response status code:', 'cdek-for-woocommerce' ) . ' ' . $response_code . ' ' . wp_remote_retrieve_body( $remote_response ) . ' Body: ' . wp_json_encode( $body, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT ), 'error' );

			return false;
		}

		$response_body = json_decode( wp_remote_retrieve_body( $remote_response ), true );

		if ( ! $skip_cache ) {
			set_transient( $hash, $response_body, DAY_IN_SECONDS );
		}

		if ( isset( $response_body['error'] ) || isset( $response_body['errors'] ) ) {
			CDEKFW::log_it( esc_html__( 'API request error:', 'cdek-for-woocommerce' ) . ' ' . $url . ' ' . wp_json_encode( $response_body, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT ) . 'Body' . wp_json_encode( $body, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT ), 'error' );

			return false;
		}

		return $response_body;
	}

	/**
	 * Get hash by removing time relevant data
	 *
	 * @param string $account Account ID.
	 * @param string $url Request url.
	 * @param array  $body Request body.
	 *
	 * @return string
	 */
	public static function get_request_hash( $account, $url, $body ) {
		unset( $body['authLogin'] );
		unset( $body['secure'] );
		unset( $body['dateExecute'] );

		return 'cdek_cache_' . md5( $account . $url . wp_json_encode( $body ) );
	}
}


function cdek_test() {
	$args = array(
//		'city_code' => 7114,
//		'postal_code' => '0008',
		'country_code' => 'UA',
	);

	$items = CDEKFW_Client::get_data_from_api( add_query_arg( $args, 'v2/deliverypoints' ), array(), 'GET' );

	var_dump( $items );
}


//add_action( 'wp_footer', 'cdek_test' );
