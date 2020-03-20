<?php
/**
 * Settings for admin page.
 *
 * @package CDEK/Admin
 */

defined( 'ABSPATH' ) || exit;

return array(
	array(
		'title' => __( 'Russian Post', 'cdek-for-woocommerce' ),
		'type'  => 'title',
		'id'    => 'cdek_options',
	),
	array(
		'title' => __( 'API Account', 'cdek-for-woocommerce' ),
		'desc'  => __( 'идентификатор клиента', 'cdek-for-woocommerce' ),
		'type'  => 'text',
		'id'    => 'cdek_account',
	),
	array(
		'title' => __( 'Secure password', 'cdek-for-woocommerce' ),
		'desc'  => __( 'секретный ключ клиента', 'cdek-for-woocommerce' ),
		'type'  => 'number',
		'id'    => 'cdek_password',
	),
	array(
		'type' => 'sectionend',
		'id'   => 'cdek_options',
	),
);
