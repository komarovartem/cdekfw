<?php
/**
 * CDEK helper functions
 *
 * @package CDEK
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
			'weight' => 100,
			'length' => 10,
			'width'  => 10,
			'height' => 10,
		);

		foreach ( $dimensions as $k => $v ) {
			$option = get_option( 'cdek_dimensions_item_' . $k, $v );
			if ( $option ) {
				$dimensions[ $k ] = $option;
			}
		}

		return $dimensions;

	}
}
