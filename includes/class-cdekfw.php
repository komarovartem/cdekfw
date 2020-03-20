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
		if ( in_array( 'russian-post-and-ems-pro-for-woocommerce/cdek-pro-for-woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ), true ) ) {
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
}
