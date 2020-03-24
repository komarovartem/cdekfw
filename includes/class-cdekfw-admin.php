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
		add_action( 'woocommerce_admin_field_cdek_sync_pvz', array( $this, 'add_sync_pvz_button' ) );
		add_filter( 'woocommerce_get_sections_shipping', array( $this, 'settings_page' ) );
		add_filter( 'woocommerce_get_settings_shipping', array( $this, 'settings' ), 10, 2 );
		add_action( 'wp_ajax_cdek_sync_pvz', array( $this, 'ajax_sync_pvz' ) );
		add_action( 'init', array( $this, 'auto_sync_pvz' ) );
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
	 * Create custom control to sync pvz base
	 *
	 * @param array $data Field control attributes.
	 */
	public function add_sync_pvz_button( $data ) {
		include 'settings/control-pvz-button.php';
	}

	/**
	 * Get PVZ point via ajax
	 */
	public function ajax_sync_pvz() {
		$data = $this->sync_pvz();

		if ( ! $data ) {
			// translators: %1$s and %2$s are links.
			wp_send_json_error( sprintf( __( 'Error. Please check %1$sWooCommerce Logs%2$s with "CDEK" key to get more information about the issue', 'cdek-for-woocommerce' ), '<a href="' . admin_url( 'admin.php?page=wc-status&tab=logs&log_file=' . WC_Log_Handler_File::get_log_file_name( 'cdek' ) ) . '">', '</a>' ) );
		} else {
			wp_send_json_success( __( 'List of delivery pickup points synchronized successfully.', 'cdek-for-woocommerce' ) );
		}
	}

	/**
	 * Get new updated version for delivery points from API
	 */
	public function sync_pvz() {
		if ( ! $data = CDEKFW::get_data_from_api( 'v2/deliverypoints', array(), 'GET' ) ) {
			return false;
		}

		if ( ! $file = fopen( CDEK_ABSPATH . 'includes/lists/pvz.txt', 'w+' ) ) {
			CDEKFW::log_it( __( 'Cannot open file for delivery points.', 'cdek-for-woocommerce' ), 'error' );
			return false;
		}

		self::debugging_deliver_points( $data, 1 );

//		// get list of all address skip possibly missing fields like corpus and etc.
//		foreach ( $data as $pvz ) {
//			$address                = implode(
//				', ',
//				array_filter(
//					array(
//						@$pvz['address']['location'], // @codingStandardsIgnoreLine
//						@$pvz['address']['street'], // @codingStandardsIgnoreLine
//						@$pvz['address']['house'] . @$pvz['address']['letter'], // @codingStandardsIgnoreLine
//						@$pvz['address']['building'], // @codingStandardsIgnoreLine
//						@$pvz['address']['room'], // @codingStandardsIgnoreLine
//						@$pvz['address']['corpus'], // @codingStandardsIgnoreLine
//					)
//				)
//			);
//			$index                  = $pvz['location']['index'];
//			$state                  = RPAEFW_PRO_Helper::match_region_name_with_number( $pvz['address']['region'] );
//			$city                   = str_replace( array( 'г. ', 'д. ', 'п. ', 'г ' ), '', $pvz['address']['place'] );
//			$coordinates            = $pvz['latitude'] . ',' . $pvz['longitude'];
//			$card_payment           = intval( $pvz['card-payment'] );
//			$cash_payment           = intval( $pvz['cash-payment'] );
//			$contents_checking      = intval( $pvz['contents-checking'] );
//			$functionality_checking = intval( $pvz['functionality-checking'] );
//			$with_fitting           = intval( $pvz['with-fitting'] );
//			$brand_name             = @$pvz['brand-name']; // @codingStandardsIgnoreLine
//
//			if ( $address ) {
//				fwrite(
//					$file,
//					implode(
//						"\t",
//						array(
//							$index,
//							$state,
//							$city,
//							$address,
//							$coordinates,
//							$card_payment,
//							$cash_payment,
//							$contents_checking,
//							$functionality_checking,
//							$with_fitting,
//							$brand_name,
//						)
//					) . "\t\n"
//				);
//			}
//		}
//
//		fclose( $file );

		return true;
	}

	/**
	 * Auto update delivery point each day
	 */
	public function auto_sync_pvz() {
		$pvz_has_updated = get_transient( 'rpaefw_auto_sync_pvz' );
		if ( ! $pvz_has_updated ) {
			$sync_pvz = $this->sync_pvz();
			set_transient( 'rpaefw_auto_sync_pvz', $sync_pvz, DAY_IN_SECONDS );
		}
	}

	/**
	 * Debugging helper function which can save all data without shorter attributes
	 *
	 * @param array $data All delivery points from API.
	 * @param bool  $debugging By default is off.
	 *
	 * @return bool
	 */
	public static function debugging_deliver_points( $data, $debugging ) {
		if ( ! $debugging ) {
			return false;
		}

		$file_all = fopen( CDEK_ABSPATH . 'includes/lists/pvz-all.json', 'w+' );
		fwrite( $file_all, json_encode( $data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT ) );
		fclose( $file_all );

		return true;
	}
}

new CDEKFW_Admin();
