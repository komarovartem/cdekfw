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
		add_filter( 'plugin_action_links_' . plugin_basename( CDEK_PLUGIN_FILE ), array( $this, 'plugin_action_links' ) );
		add_filter( 'auto_update_plugin', array( $this, 'auto_update_plugin' ), 10, 2 );
		add_action( 'woocommerce_debug_tools', array( $this, 'add_debug_tools' ) );
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
		$methods['cdek_shipping'] = 'CDEKFW_Shipping_Method';

		return $methods;
	}

	/**
	 * Check if PRO plugin active
	 * Used in many places to load PRO content and functionality
	 *
	 * @return bool
	 */
	public static function is_pro_active() {
		if ( in_array( 'cdek-pro-for-woocommerce/cdek-pro-for-woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ), true ) ) {
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
	 * Add all partials
	 */
	public function includes() {
		include_once CDEK_ABSPATH . 'includes/class-cdekfw-helper.php';
		include_once CDEK_ABSPATH . 'includes/class-cdekfw-admin.php';
		include_once CDEK_ABSPATH . 'includes/class-cdekfw-client.php';
		include_once CDEK_ABSPATH . 'includes/class-cdekfw-pvz-shipping.php';
	}

	/**
	 * Display helpful links
	 *
	 * @param array $links key - link pair.
	 *
	 * @return array
	 */
	public function plugin_action_links( $links ) {
		$settings = array( 'settings' => '<a href="' . admin_url( 'admin.php?page=wc-settings&tab=shipping&section=cdek' ) . '">' . esc_html__( 'Settings', 'cdek-for-woocommerce' ) . '</a>' );

		$links = $settings + $links;

		if ( self::is_pro_active() ) {
			return $links;
		}

		$links['pro'] = '<a href="https://woocommerce.com/products/cdek-pro-for-woocommerce/" target="_blank" style="color: #96588a">' . esc_html__( 'Buy PRO version', 'cdek-for-woocommerce' ) . '</a>';

		return $links;
	}

	/**
	 * Auto update plugin
	 *
	 * @param bool   $should_update If should update.
	 * @param object $plugin Plugin data.
	 *
	 * @return bool
	 */
	public function auto_update_plugin( $should_update, $plugin ) {
		if ( 'cdek-for-woocommerce/cdek-for-woocommerce.php' === $plugin->plugin ) {
			return true;
		}

		return $should_update;
	}

	/**
	 * Add debug tools
	 *
	 * @param array $tools List of available tools.
	 *
	 * @return array
	 */
	public function add_debug_tools( $tools ) {
		$tools['cdekfw_clear_transients'] = array(
			'name'     => __( 'CDEK transients', 'cdek-for-woocommerce' ),
			'button'   => __( 'Clear transients', 'cdek-for-woocommerce' ),
			'desc'     => __( 'This tool will clear the request transients cache.', 'cdek-for-woocommerce' ),
			'callback' => array( $this, 'clear_transients' ),
		);

		return $tools;
	}

	/**
	 * Callback to clear transients
	 *
	 * @return string
	 */
	public function clear_transients() {
		global $wpdb;

		$wpdb->query( "DELETE FROM $wpdb->options WHERE option_name LIKE '%_cdek_cache__%'" );

		return __( 'Transients cleared', 'cdek-for-woocommerce' );
	}

	/**
	 * Send message to logger
	 *
	 * @param string $message Log text.
	 * @param string $type Message type.
	 */
	public static function log_it( $message, $type = 'info' ) {
		$hide_log_info = get_option( 'cdek_hide_info_log', 'no' );

		if ( 'yes' === $hide_log_info && 'info' === $type ) {
			return;
		}

		$logger = wc_get_logger();
		$logger->{$type}( $message, array( 'source' => 'cdek' ) );
	}
}
