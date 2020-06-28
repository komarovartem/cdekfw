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
		'title' => __( 'Габаритные характеристики одного товара по умолчанию', 'cdek-for-woocommerce' ),
		'desc'  => __( 'Данные значения будут браться в расчет при условии отсутствия габаритных характеристик в карточке товара', 'cdek-for-woocommerce' ),
		'type'  => 'title',
	),
	array(
		'title' => __( 'Вес', 'cdek-for-woocommerce' ),
		'desc'  => __( 'в граммах', 'cdek-for-woocommerce' ),
		'type'  => 'number',
		'id'    => 'cdek_dimensions_item_weight',
	),
	array(
		'title' => __( 'Длина', 'cdek-for-woocommerce' ),
		'desc'  => __( 'в сантиметрах', 'cdek-for-woocommerce' ),
		'type'  => 'number',
		'id'    => 'cdek_dimensions_item_length',
	),
	array(
		'title' => __( 'Ширина', 'cdek-for-woocommerce' ),
		'desc'  => __( 'в сантиметрах', 'cdek-for-woocommerce' ),
		'type'  => 'number',
		'id'    => 'cdek_dimensions_item_width',
	),
	array(
		'title' => __( 'Высота', 'cdek-for-woocommerce' ),
		'desc'  => __( 'в сантиметрах', 'cdek-for-woocommerce' ),
		'type'  => 'number',
		'id'    => 'cdek_dimensions_item_height',
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
		'desc'              => CDEKFW::only_in_pro_ver_text() . __( 'Set the API key for Yandex Maps if you want to let customers to choose delivery points on the map.', 'cdek-for-woocommerce' ),
		'type'              => 'text',
		'id'                => 'cdek_pro_yandex_api',
		'custom_attributes' => array(
			CDEKFW::is_pro_active() ? '' : 'disabled' => '',
		),
	),
	array(
		'type' => 'sectionend',
	),
	array(
		'title' => __( 'Sender', 'cdek-for-woocommerce' ),
		'desc'  => __( 'Для регистрации заказов в ИС СДЭК на доставку товаров до покупателей. Только для договора типа "доставка". Может быть создан любым клиентом с договором (но доступны тарифы только для обычной доставки).', 'cdek-for-woocommerce' ),
		'type'  => 'title',
	),
	array(
		'title'             => __( 'Company Name', 'cdek-for-woocommerce' ),
		'desc'              => CDEKFW::only_in_pro_ver_text(),
		'type'              => 'text',
		'id'                => 'cdek_pro_sender_company',
		'custom_attributes' => array(
			CDEKFW::is_pro_active() ? '' : 'disabled' => '',
		),
	),
	array(
		'title'             => __( 'Name', 'cdek-for-woocommerce' ),
		'desc'              => CDEKFW::only_in_pro_ver_text() . __( 'ФИО контактного лица', 'cdek-for-woocommerce' ),
		'type'              => 'text',
		'id'                => 'cdek_pro_sender_name',
		'custom_attributes' => array(
			CDEKFW::is_pro_active() ? '' : 'disabled' => '',
		),
	),
	array(
		'title'             => __( 'Email', 'cdek-for-woocommerce' ),
		'desc'              => CDEKFW::only_in_pro_ver_text(),
		'type'              => 'text',
		'id'                => 'cdek_pro_sender_email',
		'custom_attributes' => array(
			CDEKFW::is_pro_active() ? '' : 'disabled' => '',
		),
	),
	array(
		'title'             => __( 'Phone', 'cdek-for-woocommerce' ),
		'desc'              => CDEKFW::only_in_pro_ver_text() . __( 'Должен передаваться в международном формате: код страны (для России +7) и сам номер (10 и более цифр)', 'cdek-for-woocommerce' ),
		'type'              => 'text',
		'id'                => 'cdek_pro_sender_phone',
		'custom_attributes' => array(
			CDEKFW::is_pro_active() ? '' : 'disabled' => '',
		),
	),
	array(
		'type' => 'sectionend',
	),
	array(
		'title' => __( 'Other', 'cdek-for-woocommerce' ),
		'type'  => 'title',
	),
	array(
		'title'             => __( 'Vat Rate', 'cdek-for-woocommerce' ),
		'desc'              => CDEKFW::only_in_pro_ver_text() . __( 'Значения - 0, 10, 18, 20 и т.п.', 'cdek-for-woocommerce' ),
		'type'              => 'number',
		'id'                => 'cdek_pro_vat_rate',
		'custom_attributes' => array(
			CDEKFW::is_pro_active() ? '' : 'disabled' => '',
		),
	),
	array(
		'type' => 'sectionend',
	),
	array(
		'title'    => __( 'Log Messages', 'cdek-for-woocommerce' ),
		'type'     => 'checkbox',
		'id'       => 'cdek_hide_info_log',
		'desc'     => __( 'Hide Info Log Messages', 'cdek-for-woocommerce' ),
		'default'  => 'no',
		'desc_tip' => __( 'By default all requests stored in WooCommerce logs. You can hide info messages and keep only errors and warnings.', 'cdek-for-woocommerce' ),
	),
);
