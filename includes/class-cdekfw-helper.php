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
	 * Get all available tariffs.
	 *
	 * @return array
	 */
	public static function get_tariffs() {
		return array(
			1   => 'Экспресс лайт дверь-дверь',
			3   => 'Супер-экспресс до 18 дверь-дверь',
			5   => 'Экономичный экспресс склад-склад',
			10  => 'Экспресс лайт склад-склад',
			11  => 'Экспресс лайт склад-дверь',
			12  => 'Экспресс лайт дверь-склад',
			15  => 'Экспресс тяжеловесы склад-склад',
			16  => 'Экспресс тяжеловесы склад-дверь',
			17  => 'Экспресс тяжеловесы дверь-склад',
			18  => 'Экспресс тяжеловесы дверь-дверь',
			57  => 'Супер-экспресс до 9 дверь-дверь',
			58  => 'Супер-экспресс до 10 дверь-дверь',
			59  => 'Супер-экспресс до 12 дверь-дверь',
			60  => 'Супер-экспресс до 14 дверь-дверь',
			61  => 'Супер-экспресс до 16 дверь-дверь',
			62  => 'Магистральный экспресс склад-склад',
			63  => 'Магистральный супер-экспресс склад-склад',
			118 => 'Экономичный экспресс дверь-дверь',
			119 => 'Экономичный экспресс склад-дверь',
			120 => 'Экономичный экспресс дверь-склад',
			121 => 'Магистральный экспресс дверь-дверь',
			122 => 'Магистральный экспресс склад-дверь',
			123 => 'Магистральный экспресс дверь-склад',
			124 => 'Магистральный супер-экспресс дверь-дверь',
			125 => 'Магистральный супер-экспресс склад-дверь',
			126 => 'Магистральный супер-экспресс дверь-склад',
			// Экспресс-доставка за/из-за границы документов и писем.
			7   => 'Международный экспресс документы дверь-дверь',
			// Экспресс-доставка за/из-за границы грузов и посылок до 30 кг.
			8   => 'Международный экспресс грузы дверь-дверь',
			// Услуга экономичной доставки товаров по России для компаний, осуществляющих дистанционную торговлю.
			136 => 'Посылка склад-склад',
			137 => 'Посылка склад-дверь',
			138 => 'Посылка дверь-склад',
			139 => 'Посылка дверь-дверь',
			// Экспресс-доставка за/из-за границы грузов и посылок до 30 кг.
			// Экспресс-доставка за/из-за границы документов и писем.
			// Услуга экономичной наземной доставки товаров по России для компаний, осуществляющих дистанционную торговлю.
			// Услуга действует по направлениям из Москвы в подразделения СДЭК, находящиеся за Уралом и в Крым.
			// до 50 кг.
			233 => 'Экономичная посылка склад-дверь',
			234 => 'Экономичная посылка склад-склад',
			// Тарифы Китайский экспресс.
			243 => 'Китайский экспресс склад-склад',
			245 => 'Китайский экспресс дверь-дверь',
			246 => 'Китайский экспресс склад-дверь',
			247 => 'Китайский экспресс дверь-склад',
			// Сервис по доставке товаров из-за рубежа в Россию.
			// Украину, Казахстан, Киргизию, Узбекистан с услугами по таможенному оформлению.
			291 => 'CDEK Express склад-склад',
			293 => 'CDEK Express дверь-дверь',
			294 => 'CDEK Express склад-дверь',
			295 => 'CDEK Express дверь-склад',
			480 => 'Экспресс дверь-дверь',
			481 => 'Экспресс дверь-склад',
			482 => 'Экспресс склад-дверь',
			483 => 'Экспресс склад-склад',
		);
	}

	/**
	 * Get tariff name by its id.
	 *
	 * @param int $id Taiff id.
	 *
	 * @return string
	 */
	public static function get_tariff_name( $id ) {
		return isset( self::get_tariffs()[ $id ] ) ? self::get_tariffs()[ $id ] : 'NAN';
	}

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
	public static function get_cdek_region_code( $region_code, $return_official_code = false ) {
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

		if ( $return_official_code ) {
			$found = array_search( intval( $region_code ), $region_codes, true );

			return $found ? $found : false;
		}

		return isset( $region_codes[ $region_code ] ) ? $region_codes[ $region_code ] : false;
	}

	public static function get_city_codes() {
		$file   = fopen( plugin_dir_path( __FILE__ ) . 'lists/cdek-codes.txt', 'r' );
		$cities = array();

		if ( ! $file ) {
			CDEKFW::log_it( __( 'Cannot open file of city codes.', 'cdek-for-woocommerce' ), 'error' );

			return $cities;
		}

		while ( ( $line = fgets( $file ) ) !== false ) {
			list( $cdek_code, $region_code, $region, $city, $postal_codes ) = explode( "\t", $line );
			$cities[ $cdek_code ] = $region . ' - ' . $city;
		}
		fclose( $file );

		$cities = array_unique( $cities );

		sort( $cities );

		return $cities;
	}

	/**
	 * Get city code based on address
	 *
	 * @param string $shipping_state Shipping state.
	 * @param string $shipping_city Shipping city.
	 * @param string $shipping_postcode Shipping postcode.
	 *
	 * @return false|mixed|int
	 */
	public static function get_city_code( $shipping_state = '', $shipping_city = '', $shipping_postcode = '' ) {
		$code           = '';
		$file           = fopen( plugin_dir_path( __FILE__ ) . 'lists/cdek-codes.txt', 'r' );
		$shipping_state = is_numeric( $shipping_state ) ? intval( $shipping_state ) : $shipping_state;

		if ( ! $file ) {
			CDEKFW::log_it( __( 'Cannot open file of city codes.', 'cdek-for-woocommerce' ), 'error' );

			return false;
		}

		if ( is_numeric( $shipping_state ) ) {
			while ( ( $line = fgets( $file ) ) !== false ) {
				list( $cdek_code, $region_code, $region, $city, $postal_codes ) = explode( "\t", $line );

				// if PRO is active state will be presented as code.
				if ( $region_code == $shipping_state && $city == $shipping_city ) {
					$code = $cdek_code;
					break;
				}
			}
		}

		// if we could not locate code by region code and city name, try postcode.
		if ( ! $code ) {
			if ( CDEKFW::is_pro_active() ) {
				$shipping_postcode = CDEKFW_PRO_Ru_Base::get_index_based_on_address( $shipping_state, $shipping_city );
			}

			if ( $shipping_postcode ) {
				fseek( $file, 0 );
				while ( ( $line = fgets( $file ) ) !== false ) {
					list( $cdek_code, $region_code, $region, $city, $postal_codes ) = explode( "\t", $line );
					$city_postal_codes = explode( ',', $postal_codes );

					if ( in_array( $shipping_postcode, $city_postal_codes ) ) {
						$code = $cdek_code;
						break;
					}
				}
			}
		}

		fclose( $file );

		return intval( $code );
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

	/**
	 * All tariffs related to from door type
	 *
	 * @return array
	 */
	public static function get_from_door_tariffs() {
		return array( 1, 3, 12, 17, 18, 57, 58, 59, 60, 61, 118, 120, 121, 123, 124, 126, 7, 8, 138, 139, 180, 183, 231, 232, 245, 247, 293, 295, 480, 481 );
	}

	/**
	 * Prepare services list for sending
	 *
	 * @param array $services Selected shipping services.
	 * @param float $ordered_value Subtotal of the order.
	 *
	 * @return array
	 */
	public static function get_services_for_shipping_calculation( $services, $ordered_value ) {
		$cdek_order_type = intval( get_option( 'cdek_type', 1 ) );
		$services_ids    = array();

		// for Online Store agreement types insurance is required.
		if ( 1 === $cdek_order_type && ! in_array( '2', $services, true ) ) {
			$services[] = 2;
		}

		foreach ( $services as $service ) {
			$service_id = intval( $service );
			if ( 2 === $service_id ) {
				$services_ids[] = array(
					'id'    => $service_id,
					'param' => ceil( $ordered_value ),
				);
			} elseif ( 24 === $service_id ) {
				$services_ids[] = array(
					'id'    => $service_id,
					'param' => 1,
				);
			} else {
				$services_ids[] = array( 'id' => $service_id );
			}
		}

		return $services_ids;
	}

	/**
	 * Get basic city code of country for international shipments
	 *
	 * @param string $country_code Country Code.
	 *
	 * @return bool|mixed
	 */
	public static function get_international_city_id( $country_code ) {
		$city_ids = array(
			'AT' => 32,
			'AM' => 7114,
			'BY' => 9220,
			'FR' => 10090,
			'DE' => 196,
			'IL' => 11580,
			'KZ' => 4961,
			'KG' => 5444,
			'KR' => 11157,
			'MN' => 1868,
			'US' => 5917,
			'UA' => 7870,
			'UZ' => 11562,
			'CN' => 12683,
		);

		return isset( $city_ids[ $country_code ] ) ? $city_ids[ $country_code ] : false;
	}
}
