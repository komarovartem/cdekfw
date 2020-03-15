<?php
/*
Plugin Name: CDEK for WooCommerce
Description: The plugin allows you to automatically calculate the shipping cost for CDEK
Version: 1.0.0
Author: YumeCommerce
Author URI: mailto:yumecommerce@gmail.com
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.html
Text Domain: cdek-for-woocommerce
WC requires at least: 3.0.0
WC tested up to: 4.0
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class CDEKFW {
	public function __construct() {
		// apply plugin textdomain
		add_action( 'plugins_loaded', array( $this, 'load_textdomain' ) );

		// add new shipping method
		add_action( 'woocommerce_shipping_init', array( $this, 'init_method' ) );
		add_filter( 'woocommerce_shipping_methods', array( $this, 'register_method' ) );

		add_filter( 'woocommerce_get_sections_shipping', array( $this, 'settings_page' ) );
		add_filter( 'woocommerce_get_settings_shipping', array( $this, 'settings' ), 10, 2 );
		add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'plugin_action_links' ) );


		$this->init();
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
	function init_method() {
		if ( ! class_exists( 'CDEKFW_Shipping_Method' ) ) {
			include_once( dirname( __FILE__ ) . '/includes/class-cdekfw-shipping-method.php' );
		}
	}

	/**
	 * Register shipping method
	 *
	 * @param $methods
	 *
	 * @return array
	 *
	 */
	function register_method( $methods ) {
//		$methods[ 'cdek' ] = 'CDEKFW_Shipping_Method';
//
//		return $methods;
	}


	/**
	 * Register settings page
	 *
	 * @param $sections
	 *
	 * @return mixed
	 */
	public function settings_page( $sections ) {
		$sections[ 'cdek' ] = esc_html__( 'CDEK', 'cdek-for-woocommerce' );

		return $sections;
	}

	/**
	 * Main settings page
	 *
	 * @param $settings
	 * @param $current_section
	 *
	 * @return array|mixed
	 */
	public function settings( $settings, $current_section ) {
		if ( $current_section == 'cdek' ) {
			$settings = array(
				array(
					'title' => __( 'Russian Post', 'cdek-for-woocommerce' ),
					'type'  => 'title',
					'id'    => 'cdek_options',
				),
				array(
					'title' => __( 'API Account', 'cdek-for-woocommerce' ),
					'desc'  => __( 'идентификатор клиента', 'cdek-for-woocommerce' ),
					'type'  => 'text',
					'id'    => 'cdek_account',
				),
				array(
					'title' => __( 'Secure password', 'cdek-for-woocommerce' ),
					'desc'  => __( 'секретный ключ клиента', 'cdek-for-woocommerce' ),
					'type'  => 'number',
					'id'    => 'cdek_password',
				),
			);

			$settings[] = array(
				'type' => 'sectionend',
				'id'   => 'cdek_options',
			);
		}

		return $settings;
	}

	/**
	 * Check if PRO plugin active
	 * Used in many places to load PRO content and functionality
	 *
	 * @return bool
	 */
	public static function is_pro_active() {
		if ( in_array( 'russian-post-and-ems-pro-for-woocommerce/cdek-pro-for-woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
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
		return RPAEFW::is_pro_active() ? '' : 'Доступно только в PRO версии. ';
	}

	/**
	 * Plugin dir url helper
	 *
	 * @return string
	 */
	public static function plugin_dir_url() {
		return plugin_dir_url( __FILE__ );
	}

	/**
	 * Add plugin partials
	 */
	public function init() {
		include_once( dirname( __FILE__ ) . '/includes/class-cdekfw-admin.php' );
	}

	/**
	 * Display helpful links
	 *
	 * @param array $links key - link pair.
	 *
	 * @return array
	 */
	public function plugin_action_links( $links ) {
		return array_merge( array(
			'settings' => '<a href="' . admin_url( 'admin.php?page=wc-settings&tab=shipping&section=cdek' ) . '">' . esc_html__( 'Settings', 'cdek-for-woocommerce' ) . '</a>',
			'docs'     => '<a href="https://yumecommerce.com/pochta/docs/" target="_blank">' . esc_html__( 'Documentation', 'cdek-for-woocommerce' ) . '</a>'
		), $links );
	}
}

// init plugin if woo is active
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
	new CDEKFW();
}
