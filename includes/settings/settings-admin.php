<?php
/**
 * Settings for admin page.
 *
 * @package CDEK/Admin
 */

defined( 'ABSPATH' ) || exit;

$test_warning = __( 'If field is empty, test account data will be used.', 'cdek-for-woocommerce' );

return array(
	array(
		'title' => __( 'CDEK', 'cdek-for-woocommerce' ),
		'type'  => 'title',
	),
	array(
		'title' => __( 'Account', 'cdek-for-woocommerce' ),
		'desc'  => __( 'Client ID', 'cdek-for-woocommerce' ) . ' ' . $test_warning,
		'type'  => 'text',
		'id'    => 'cdek_account',
	),
	array(
		'title' => __( 'Secure password', 'cdek-for-woocommerce' ),
		'desc'  => __( 'секретный ключ клиента.', 'cdek-for-woocommerce' ) . ' ' . $test_warning,
		'type'  => 'text',
		'id'    => 'cdek_password',
	),
	array(
		'title' => __( 'Индекс', 'cdek-for-woocommerce' ),
		'desc'  => __( 'Индекс города отправителя. Если поле не заполнено индекс 101000 будет использован по умолчанию', 'cdek-for-woocommerce' ),
		'type'  => 'number',
		'id'    => 'cdek_sender_post_code',
	),
	array(
		'type' => 'sectionend',
	),
	array(
		'title' => __( 'Габаритные характеристики упаковки одного товара по умолчанию', 'cdek-for-woocommerce' ),
		'desc'  => __( 'Данные значения будут браться в расчет при условии отсутствия габаритных характеристик в карточке товара', 'cdek-for-woocommerce' ),
		'type'  => 'title',
	),
	array(
		'title' => __( 'Вес упаковки', 'cdek-for-woocommerce' ),
		'desc'  => __( 'в килограммах', 'cdek-for-woocommerce' ),
		'type'  => 'number',
		'id'    => 'cdek_dimensions_pack_weight',
	),
	array(
		'title' => __( 'Длина упаковки', 'cdek-for-woocommerce' ),
		'desc'  => __( 'в сантиметрах', 'cdek-for-woocommerce' ),
		'type'  => 'number',
		'id'    => 'cdek_dimensions_pack_length',
	),
	array(
		'title' => __( 'Ширина упаковки', 'cdek-for-woocommerce' ),
		'desc'  => __( 'в сантиметрах', 'cdek-for-woocommerce' ),
		'type'  => 'number',
		'id'    => 'cdek_dimensions_pack_width',
	),
	array(
		'title' => __( 'Высота упаковки', 'cdek-for-woocommerce' ),
		'desc'  => __( 'в сантиметрах', 'cdek-for-woocommerce' ),
		'type'  => 'number',
		'id'    => 'cdek_dimensions_pack_height',
	),
	array(
		'type' => 'sectionend',
	),
	array(
		'title' => __( 'Delivery Points', 'cdek-for-woocommerce' ),
		'desc'  => __( 'Список действующих ПВЗ (пунктов выдачи заказов), откуда клиент самостоятельно может забрать заказ.', 'cdek-for-woocommerce' ),
		'type'  => 'title',
	),
	array(
		'title' => __( 'Database of Delivery Points', 'cdek-for-woocommerce' ),
		'type'  => 'cdek_sync_pvz',
		'id'    => 'cdek_pvz',
	),
	array(
		'title'             => __( 'Yandex Maps JavaScript API', 'cdek-for-woocommerce' ),
		'desc'              => CDEKFW::only_in_pro_ver_text() . __( 'Set the API key for Yandex Maps if you want to let customers to choose ECOM delivery points on the map.', 'russian-post-and-ems-pro-for-woocommerce' ),
		'type'              => 'text',
		'id'                => 'cdek_pro_yandex_api',
		'custom_attributes' => array(
			CDEKFW::is_pro_active() ? '' : 'disabled' => '',
		),
	),
	array(
		'type' => 'sectionend',
	),
);
