<?php
/**
 * CDEK helper functions
 *
 * @package CDEK/Helper
 * @since   1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * CDEK Helper Class
 *
 * @class CDEKFW_Helper
 */
class CDEKFW_Helper {
	/**
	 * Get default weight and dimensions based on basic settings
	 *
	 * @return array
	 */
	public static function get_default_dimensions() {
		$dimensions = array(
			'weight' => 100, // g.
			'length' => 10, // cm.
			'width'  => 10, // cm.
			'height' => 10, // cm.
		);

		foreach ( $dimensions as $k => $v ) {
			$option = get_option( 'cdek_dimensions_item_' . $k, $v );
			if ( $option ) {
				$dimensions[ $k ] = $option;
			}
		}

		return $dimensions;
	}

	/**
	 * Match official region code with custom CDEK code
	 *
	 * @param int $region_code Official code number.
	 *
	 * @return bool|mixed
	 */
	public static function get_cdek_region_code( $region_code ) {
		$region_codes = array(
			27 => 14,
			54 => 23,
			05 => 21,
			50 => 9,
			42 => 54,
			20 => 71,
			91 => 90,
			67 => 65,
			06 => 72,
			92 => 975,
			45 => 28,
			72 => 52,
			9  => 80,
			34 => 40,
			24 => 13,
			40 => 33,
			64 => 47,
			01 => 61,
			60 => 30,
			14 => 10,
			8  => 46,
			21 => 64,
			43 => 44,
			36 => 63,
			53 => 31,
			56 => 5,
			11 => 1,
			71 => 37,
			25 => 18,
			74 => 3,
			69 => 50,
			29 => 66,
			39 => 38,
			49 => 59,
			87 => 83,
			52 => 67,
			73 => 70,
			12 => 32,
			66 => 24,
			44 => 42,
			79 => 76,
			15 => 79,
			18 => 48,
			10 => 73,
			78 => 82,
			07 => 78,
			28 => 56,
			46 => 62,
			32 => 29,
			75 => 49,
			35 => 51,
			48 => 60,
			55 => 15,
			33 => 17,
			70 => 53,
			37 => 8,
			62 => 41,
			63 => 57,
			76 => 35,
			77 => 81,
			26 => 19,
			83 => 77,
			16 => 39,
			58 => 69,
			13 => 68,
			68 => 58,
			38 => 4,
			19 => 74,
			30 => 34,
			41 => 55,
			23 => 7,
			22 => 2,
			03 => 12,
			02 => 27,
			65 => 20,
			57 => 36,
			61 => 45,
			17 => 75,
			04 => 25,
			47 => 26,
			86 => 11,
			89 => 6,
			31 => 16,
			59 => 22,
			51 => 43,
		);

		return isset( $region_codes[ $region_code ] ) ? $region_codes[ $region_code ] : false;
	}

	/**
	 * Get all CDEK methods from db
	 *
	 * @return array
	 */
	public static function get_all_methods() {
		global $wpdb;

		$rp_db_options = $wpdb->get_results( "SELECT option_name, option_value FROM $wpdb->options WHERE option_name LIKE 'woocommerce_cdek_shipping_%'" );
		$rp_options    = array();

		foreach ( $rp_db_options as $rp_db_option ) {
			$option_value                             = maybe_unserialize( $rp_db_option->option_value );
			$rp_options[ $rp_db_option->option_name ] = $option_value['title'];
		}

		return $rp_options;
	}

	/**
	 * Match services number with names
	 * number comes from API 1.5 but names are used in API 2.0
	 *
	 * @param int $service_id Service ID.
	 *
	 * @return bool|mixed
	 */
	public static function match_service( $service_id ) {
		$match_service = array(
			2  => 'INSURANCE',
			3  => 'DELIV_WEEKEND',
			7  => 'DANGER_CARGO',
			24 => 'PACKAGE_1',
			30 => 'TRYING_ON',
			36 => 'PART_DELIV',
			37 => 'INSPECTION_CARGO',
		);

		return isset( $match_service[ $service_id ] ) ? $match_service[ $service_id ] : false;
	}
}
