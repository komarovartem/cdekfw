<?php
/**
 * CDEK Admin
 *
 * @package CDEK/Admin
 * @since   1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * CDEK admin class.
 *
 * @class CDEKFW_Admin
 */
class CDEKFW_Admin {
	/**
	 * CDEKFW_Admin constructor.
	 */
	public function __construct() {
		add_filter( 'woocommerce_get_sections_shipping', array( $this, 'settings_page' ) );
		add_filter( 'woocommerce_get_settings_shipping', array( $this, 'settings' ), 10, 2 );
	}

	/**
	 * Register settings page
	 *
	 * @param array $sections admin sections.
	 *
	 * @return mixed
	 */
	public function settings_page( $sections ) {
		$sections['cdek'] = esc_html__( 'CDEK', 'cdek-for-woocommerce' );

		return $sections;
	}

	/**
	 * Main settings page
	 *
	 * @param array  $settings section setting.
	 * @param string $current_section current admin section.
	 *
	 * @return array|mixed
	 */
	public function settings( $settings, $current_section ) {
		if ( 'cdek' === $current_section ) {
			$settings = include 'settings/settings-admin.php';
		}

		return $settings;
	}
}

new CDEKFW_Admin();
