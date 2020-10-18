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

		// add new order status.
		add_action( 'init', array( $this, 'register_delivering_status' ) );
		add_filter( 'wc_order_statuses', array( $this, 'add_order_status' ), 10, 1 );

		// hide order item meta.
		add_filter( 'woocommerce_hidden_order_itemmeta', array( $this, 'hide_order_itemmeta' ) );
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

	/**
	 * Register new status
	 */
	public function register_delivering_status() {
		register_post_status(
			'wc-delivering',
			array(
				'label'                     => esc_html__( 'Delivering', 'cdek-for-woocommerce' ),
				'public'                    => true,
				'exclude_from_search'       => false,
				'show_in_admin_all_list'    => true,
				'show_in_admin_status_list' => true,
				'label_count'               => false,
			)
		);
	}

	/**
	 * Add new order status
	 *
	 * @param array $order_statuses Order statuses.
	 *
	 * @return array
	 */
	public function add_order_status( $order_statuses ) {
		$new_order_statuses = array();
		// add new order status after processing.
		foreach ( $order_statuses as $key => $status ) {
			$new_order_statuses[ $key ] = $status;
			if ( 'wc-processing' === $key ) {
				$new_order_statuses['wc-delivering'] = esc_html__( 'Delivering', 'cdek-for-woocommerce' );
			}
		}

		return $new_order_statuses;
	}

	/**
	 * Hide tariff id
	 *
	 * @param array $itemmeta Hidden order item meta.
	 *
	 * @return mixed array
	 */
	public function hide_order_itemmeta( $itemmeta ) {
		$itemmeta[] = 'tariff_id';

		return $itemmeta;
	}
}

new CDEKFW_Admin();
