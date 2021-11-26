<?php
/**
 * Plugin Name: CDEK for WooCommerce
 * Description: Automatically calculate the shipping cost for CDEK tariffs
 * Version: 1.1.6
 * Author: Artem Komarov
 * Author URI: mailto:yumecommerce@gmail.com
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain: cdek-for-woocommerce
 * Domain Path: /languages
 * Requires at least: 5.5
 * Requires PHP: 7.0
 * WC requires at least: 5.0
 * WC tested up to: 5.9
 *
 * @package CDEK
 */

defined( 'ABSPATH' ) || exit;

define( 'CDEK_PLUGIN_FILE', __FILE__ );
define( 'CDEK_PLUGIN_DIR_URL', plugin_dir_url( __FILE__ ) );
define( 'CDEK_ABSPATH', dirname( CDEK_PLUGIN_FILE ) . '/' );

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
