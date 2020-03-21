<?php
/**
 * CDEK setup
 *
 * @package CDEK
 * @since   1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Main CDEK Class.
 *
 * @class CDEKFW
 */
class CDEKFW {
	/**
	 * CDEKFW constructor.
	 */
	public function __construct() {
		$this->includes();
		$this->init_hooks();
	}

	/**
	 * Hook into actions and filters.
	 */
	public function init_hooks() {
		add_action( 'plugins_loaded', array( $this, 'load_textdomain' ) );
		add_action( 'woocommerce_shipping_init', array( $this, 'init_method' ) );
		add_filter( 'woocommerce_shipping_methods', array( $this, 'register_method' ) );
		add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'plugin_action_links' ) );
	}

	/**
	 * Load textdomain for a plugin
	 */
	public function load_textdomain() {
		load_plugin_textdomain( 'cdek-for-woocommerce' );
	}

	/**
	 * Add shipping method
	 */
	public function init_method() {
		if ( ! class_exists( 'CDEKFW_Shipping_Method' ) ) {
			include_once CDEK_ABSPATH . 'includes/class-cdekfw-shipping-method.php';
		}
	}

	/**
	 * Register shipping method
	 *
	 * @param array $methods shipping methods.
	 *
	 * @return array
	 */
	public function register_method( $methods ) {
		$methods['cdek'] = 'CDEKFW_Shipping_Method';

		return $methods;
	}

	/**
	 * Check if PRO plugin active
	 * Used in many places to load PRO content and functionality
	 *
	 * @return bool
	 */
	public static function is_pro_active() {
		if ( in_array( 'cdek-for-woocommerce/cdek-pro-for-woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ), true ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Helper function to avoid typing same strings
	 *
	 * @return string
	 */
	public static function only_in_pro_ver_text() {
		return self::is_pro_active() ? '' : 'Доступно только в PRO версии. ';
	}

	/**
	 * Add plugin partials
	 */
	public function includes() {
		include_once CDEK_ABSPATH . 'includes/class-cdekfw-admin.php';
	}

	/**
	 * Display helpful links
	 *
	 * @param array $links key - link pair.
	 *
	 * @return array
	 */
	public function plugin_action_links( $links ) {
		return array_merge(
			array(
				'settings' => '<a href="' . admin_url( 'admin.php?page=wc-settings&tab=shipping&section=cdek' ) . '">' . esc_html__( 'Settings', 'cdek-for-woocommerce' ) . '</a>',
				'docs'     => '<a href="https://yumecommerce.com/pochta/docs/" target="_blank">' . esc_html__( 'Documentation', 'cdek-for-woocommerce' ) . '</a>',
			),
			$links
		);
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
				'api_url'  => 'http://api.cdek.ru/v2/',
			);
		} else {
			return array(
				'account'  => 'z9GRRu7FxmO53CQ9cFfI6qiy32wpfTkd',
				'password' => 'w24JTCv4MnAcuRTx0oHjHLDtyt3I6IBq',
				'api_url'  => 'http://api.edu.cdek.ru/v2/',
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

			$request         = add_query_arg( $parameters, $client['api_url'] . 'oauth/token?parameters' );
			$remote_response = wp_remote_post( $request, array( 'timeout' => 15 ) );

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
	 * @param string $method Type.
	 * @param array  $body Request body.
	 *
	 * @return bool|mixed|null
	 */
	public static function get_data_from_api( $url, $method = 'POST', $body = array() ) {

		$client            = self::get_client_credentials();
		$client_auth_token = self::get_client_auth_token();

		$remote_response = wp_remote_request(
			$client['api_url'] . $url,
			array(
				'headers' => array(
					'Authorization'        => 'Bearer ' . $client_auth_token,
					'X-User-Authorization' => 'Basic ' . $key,
					'Accept'               => 'application/json;charset=UTF-8',
					'Content-Type'         => 'application/json',
				),
				'method'  => $method,
				'body'    => wp_json_encode( $body, JSON_UNESCAPED_UNICODE ),
				'timeout' => 100, // must be that big for huge requests like getting PVZ list.
			)
		);

		self::log_it( esc_html__( 'Making request to get:', 'cdek-for-woocommerce' ) . ' ' . $url );

		if ( is_wp_error( $remote_response ) ) {
			self::log_it( esc_html__( 'Cannot connect to', 'cdek-for-woocommerce' ) . ' ' . $url . ' ' . $remote_response->get_error_message() . ' Body: ' . wp_json_encode( $body, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT ), 'error' );

			return false;
		}

		$response_code = wp_remote_retrieve_response_code( $remote_response );

		if ( 200 !== $response_code ) {
			self::log_it( esc_html__( 'Cannot connect to', 'cdek-for-woocommerce' ) . ' ' . $url . ' ' . $response_code . ' ' . wp_remote_retrieve_body( $remote_response ) . ' Body: ' . wp_json_encode( $body, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT ), 'error' );

			return false;
		}

		return json_decode( wp_remote_retrieve_body( $remote_response ), true );
	}

	/**
	 * Send message to logger
	 *
	 * @param string $message Log text.
	 * @param string $type Message type.
	 */
	public static function log_it( $message, $type = 'info' ) {
		$logger = wc_get_logger();
		$logger->{$type}( $message, array( 'source' => 'cdek' ) );
	}
}

//add_action( 'wp_footer', function () {
//	CDEKFW::get_client_auth_token();
//} );
