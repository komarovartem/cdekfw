<?php
/**
 * Plugin Name: CDEK for WooCommerce
 * Description: The plugin allows you to automatically calculate the shipping cost for CDEK
 * Version: 1.0.0
 * Author: Artem Komarov
 * Author URI: mailto:yumecommerce@gmail.com
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain: cdek-for-woocommerce
 * WC requires at least: 3.0.0
 * WC tested up to: 4.3
 *
 * @package CDEK
 */

defined( 'ABSPATH' ) || exit;

if ( ! defined( 'CDEK_PLUGIN_FILE' ) ) {
	define( 'CDEK_PLUGIN_FILE', __FILE__ );
}

if ( ! defined( 'CDEK_ABSPATH' ) ) {
	define( 'CDEK_ABSPATH', dirname( CDEK_PLUGIN_FILE ) . '/' );
}

// Include the main class.
if ( ! class_exists( 'CDEKFW', false ) ) {
	include_once dirname( CDEK_PLUGIN_FILE ) . '/includes/class-cdekfw.php';
}

// Init plugin if woo is active.
if ( in_array(
	'woocommerce/woocommerce.php',
	apply_filters( 'active_plugins', get_option( 'active_plugins' ) ),
	true
) ) {
	new CDEKFW();
}
