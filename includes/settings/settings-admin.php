<?php
/**
 * Settings for admin page.
 *
 * @package CDEK/Admin
 */

defined( 'ABSPATH' ) || exit;

return array(
	array(
		'title' => __( 'CDEK', 'cdek-for-woocommerce' ),
		'type'  => 'title',
		'desc'  => __( '', 'cdek-for-woocommerce' ),
		'id'    => 'cdek_options',
	),
	array(
		'title' => __( 'Account', 'cdek-for-woocommerce' ),
		'desc'  => __( 'Client ID. If account and password is not filled in the plugin will work in test mode.', 'cdek-for-woocommerce' ),
		'type'  => 'text',
		'id'    => 'cdek_account',
	),
	array(
		'title' => __( 'Secure password', 'cdek-for-woocommerce' ),
		'desc'  => __( 'секретный ключ клиента', 'cdek-for-woocommerce' ),
		'type'  => 'text',
		'id'    => 'cdek_password',
	),
	array(
		'type' => 'sectionend',
		'id'   => 'cdek_options',
	),
);
