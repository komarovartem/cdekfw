<?php
/**
 * CDEK pvz shipping options
 *
 * @package CDEK/PVZ
 * @since   1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Delivery points for CDEK methods
 *
 * @class CDEKFW_PVZ_Shipping
 */
class CDEKFW_PVZ_Shipping {
	/**
	 * Class constructor.
	 */
	public function __construct() {
		add_action( 'woocommerce_after_shipping_rate', array( $this, 'add_pvz_select' ), 10, 2 );
		add_action( 'woocommerce_checkout_update_order_meta', array( $this, 'save_pvz_to_order_meta' ) );
		add_action( 'woocommerce_admin_order_data_after_shipping_address', array( $this, 'display_pvz_after_shipping_address' ) );
		add_action( 'woocommerce_email_order_meta', array( $this, 'display_pvz_in_email' ), 10, 4 );
		add_action( 'woocommerce_order_details_after_order_table', array( $this, 'display_pvz_in_order_details' ) );
	}

	/**
	 * Add select with delivery points after shipping rate in checkout
	 *
	 * @param object $method shipping method.
	 */
	public function add_pvz_select( $method ) {
		$meta_data = $method->meta_data;

		if ( isset( $meta_data['cdek_error'] ) ) {
			// translators: %s: Links.
			echo ' ' . sprintf( __( 'Please check %1$sWooCommerce Logs%2$s to get more information about the issue.', 'cdek-for-woocommerce' ), '<a style="color: #ac0608; font-weight: bold;" target="_blank" href="' . admin_url( 'admin.php?page=wc-status&tab=logs&log_file=' . WC_Log_Handler_File::get_log_file_name( 'cdek' ) ) . '">', '</a>' ); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped

			return;
		}

		if ( ! is_checkout() ) {
			return;
		}

		if ( 'cdek_shipping' !== $method->method_id ) {
			return;
		}

		if ( WC()->session->get( 'chosen_shipping_methods' )[0] !== $method->id ) {
			return;
		}

		$type = intval( $meta_data['tariff_id'] );

		// Only if tariff shipping goes to warehouse.
		if ( ! in_array(
			$type,
			self::get_warehouse_tariffs(),
			true
		) ) {
			return;
		}

		$pvz_list     = CDEKFW_Client::get_pvz_list( WC()->customer );
		$selected_pvz = self::get_selected_pvz_code();

		if ( ! $pvz_list ) {
			return;
		}

		include 'controls/control-pvz-select-list.php';
	}

	/**
	 * Keep pvz code selected on checkout update
	 *
	 * @return string
	 */
	public static function get_selected_pvz_code() {
		$pvz_code = '';

		$post_data = ! empty( $_REQUEST['post_data'] ) ? urldecode( $_REQUEST['post_data'] ) : null; // phpcs:disable WordPress.Security.NonceVerification.Missing -- Nonce already verified in WC_Checkout::process_checkout().
		if ( $post_data ) {
			parse_str( $post_data, $post_data );

			if ( isset( $post_data['cdekfw-pvz-code'] ) ) {
				$pvz_code = esc_attr( $post_data['cdekfw-pvz-code'] );
			}
		}

		return $pvz_code;
	}

	/**
	 * Save PVZ for order
	 *
	 * @param int $order_id Order ID.
	 */
	public function save_pvz_to_order_meta( $order_id ) {
		$pvz = ! empty( $_REQUEST['cdekfw-pvz-code'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['cdekfw-pvz-code'] ) ) : null; // @codingStandardsIgnoreLine -- Nonce already verified in WC_Checkout::process_checkout()

		if ( ! $pvz ) {
			return;
		}

		$pvz = explode( '|', $pvz );

		$pvz_data = array(
			'code'      => $pvz[0],
			'address'   => $pvz[1],
			'city_code' => $pvz[2],
		);

		update_post_meta( $order_id, '_cdekfw_pvz', $pvz_data );
	}

	/**
	 * Display info about PVZ in admin order details
	 *
	 * @param int $order Order ID.
	 */
	public function display_pvz_after_shipping_address( $order ) {
		$pvz = get_post_meta( $order->get_id(), '_cdekfw_pvz', true );
		if ( $pvz ) {
			echo '<div class="cdekfw-admin-pvz" style="float: left; width: 100%; margin: 10px 0;"><strong>ПВЗ: </strong><span class="cdekfw-admin-pvz-value">' . esc_attr( $pvz['code'] ) . ', ' . esc_attr( $pvz['address'] ) . '</span></div>';
		}
	}

	/**
	 * Display info about PVZ delivery points in customer email
	 *
	 * @param int $order Order ID.
	 */
	public function display_pvz_in_email( $order ) {
		$pvz = get_post_meta( $order->get_id(), '_cdekfw_pvz', true );
		if ( $pvz ) {
			?>
			<h2><?php esc_html_e( 'Delivery Point', 'cdek-for-woocommerce' ); ?></h2>
			<p><?php echo esc_html( $pvz['address'] ); ?></p>
			<br>
			<?php
		}
	}

	/**
	 * Display info about PVZ in order details
	 *
	 * @param int $order Order ID.
	 */
	public function display_pvz_in_order_details( $order ) {
		$this->display_pvz_in_email( $order );
	}

	/**
	 * All tariffs related to warehouses
	 *
	 * @return array
	 */
	public static function get_warehouse_tariffs() {
		return array( 5, 10, 12, 15, 17, 62, 63, 120, 123, 126, 136, 138, 178, 180, 181, 183, 232, 234, 243, 247, 291, 295, 481, 483 );
	}
}

new CDEKFW_PVZ_Shipping();
