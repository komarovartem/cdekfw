<?php
/**
 * CDEK pvz shipping options
 *
 * @package CDEK
 * @since   1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Delivery points for CDEK methods
 *
 * @class CDEKFW_Pvz_Shipping
 */
class CDEKFW_Pvz_Shipping {
	/**
	 * Class constructor.
	 */
	public function __construct() {
		// add_action( 'woocommerce_admin_order_data_after_shipping_address', array( $this, 'display_ekom_in_admin_order' ) );
		add_action( 'woocommerce_after_shipping_rate', array( $this, 'add_pvz_select' ), 10, 2 );
		// add_action( 'woocommerce_checkout_update_order_meta', array( $this, 'save_ekom_to_order_meta' ) );
		// add_action( 'woocommerce_email_order_meta', array( $this, 'display_ekom_in_email' ), 10, 4 );
		// add_action( 'woocommerce_order_details_after_order_table', array( $this, 'display_ekom_in_order_details' ) );
		// add_action( 'wp_footer', array( $this, 'print_map' ) );
	}

	/**
	 * Add select with delivery points after shipping rate in checkout
	 *
	 * @param object $method shipping method.
	 */
	public function add_pvz_select( $method ) {
		if ( ! is_checkout() ) {
			return;
		}

		if ( WC()->session->get( 'chosen_shipping_methods' )[0] !== $method->id ) {
			return;
		}

		$method_settings = get_option( 'woocommerce_cdek_shipping_' . $method->instance_id . '_settings' );
		$type            = intval( $method_settings['tariff'] );

		// Only tariff shipping goes to warehouse.
		if ( ! in_array(
			$type,
			array( 5, 10, 12, 15, 17, 62, 63, 120, 123, 126, 136, 138, 178, 180, 181, 183, 232, 234, 243, 247, 291, 295 ),
			true
		) ) {
			return;
		}

		$pvz          = CDEKFW_Client::get_pvz_list();
		$selected_pvz = $this->get_selected_pvz_code();

		include 'controls/control-pvz-select-list.php';
	}

	/**
	 * Keep pvz code selected on checkout update
	 *
	 * @return string
	 */
	public function get_selected_pvz_code() {
		$pvz_code = '';

		$post_data = ! empty( $_REQUEST['post_data'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['post_data'] ) ) : null; // phpcs:disable WordPress.Security.NonceVerification.Missing -- Nonce already verified in WC_Checkout::process_checkout().

		if ( $post_data ) {
			parse_str( $post_data, $post_data );

			if ( isset( $post_data['cdekfw-pvz-code'] ) ) {
				$pvz_code = esc_attr( $post_data['cdekfw-pvz-code'] );
			}
		}

		return $pvz_code;
	}
}

new CDEKFW_Pvz_Shipping();
