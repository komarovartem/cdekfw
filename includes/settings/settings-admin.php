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
		'desc'  => __( 'Client ID.', 'cdek-for-woocommerce' ) . ' ' . $test_warning,
		'type'  => 'text',
		'id'    => 'cdek_account',
	),
	array(
		'title' => __( 'Secure password', 'cdek-for-woocommerce' ),
		'desc'  => __( 'Client secret key.', 'cdek-for-woocommerce' ) . ' ' . $test_warning,
		'type'  => 'text',
		'id'    => 'cdek_password',
	),
	array(
		'title' => __( 'Postcode', 'cdek-for-woocommerce' ),
		'desc'  => __( 'Postal code of the sender. If the field is empty, the 101000 index will be used by default.', 'cdek-for-woocommerce' ),
		'type'  => 'number',
		'id'    => 'cdek_sender_post_code',
	),
	array(
		'type' => 'sectionend',
	),
	array(
		'title' => __( 'Dimensions of one product by default', 'cdek-for-woocommerce' ),
		'desc'  => __( 'These values will be taken into account in the absence of overall characteristics of the product.', 'cdek-for-woocommerce' ),
		'type'  => 'title',
	),
	array(
		'title' => __( 'Weight', 'cdek-for-woocommerce' ),
		'desc'  => __( 'in grams', 'cdek-for-woocommerce' ),
		'type'  => 'number',
		'id'    => 'cdek_dimensions_item_weight',
	),
	array(
		'title' => __( 'Length', 'cdek-for-woocommerce' ),
		'desc'  => __( 'in centimeters', 'cdek-for-woocommerce' ),
		'type'  => 'number',
		'id'    => 'cdek_dimensions_item_length',
	),
	array(
		'title' => __( 'Width', 'cdek-for-woocommerce' ),
		'desc'  => __( 'in centimeters', 'cdek-for-woocommerce' ),
		'type'  => 'number',
		'id'    => 'cdek_dimensions_item_width',
	),
	array(
		'title' => __( 'Height', 'cdek-for-woocommerce' ),
		'desc'  => __( 'in centimeters', 'cdek-for-woocommerce' ),
		'type'  => 'number',
		'id'    => 'cdek_dimensions_item_height',
	),
	array(
		'type' => 'sectionend',
	),
	array(
		'title' => __( 'Delivery Points', 'cdek-for-woocommerce' ),
		'desc'  => __( 'A list of existing delivery points, from where the client can pick up the order on his own.', 'cdek-for-woocommerce' ),
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
		'title' => __( 'Tracking', 'cdek-for-woocommerce' ),
		'type'  => 'title',
	),
	array(
		'title'   => __( 'Tracking code sending', 'cdek-for-woocommerce' ),
		'desc'    => __( 'You can send tracking code immediately after creating new package via personal dashboard or when order status changed to delivering.', 'cdek-for-woocommerce' ),
		'type'    => 'select',
		'id'      => 'cdek_use_auto_email_tracking_code',
		'options' => array(
			''                                  => __( 'Send Manually', 'cdek-for-woocommerce' ),
			'after_creating_new_package'        => __( 'After creating new package', 'cdek-for-woocommerce' ),
			'after_status_change_to_delivering' => __( 'After status changed to delivering', 'cdek-for-woocommerce' ),
		),
	),
	array(
		'title'   => __( 'Tracking info block', 'cdek-for-woocommerce' ),
		'desc'    => __( 'Display tracking info on the customer account page.', 'cdek-for-woocommerce' ),
		'type'    => 'select',
		'id'      => 'cdek_account_tracking_position',
		'options' => array(
			'before' => __( 'Before order table', 'cdek-for-woocommerce' ),
			'after'  => __( 'After order table', 'cdek-for-woocommerce' ),
			'hide'   => __( 'Do not display', 'cdek-for-woocommerce' ),
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
		'title'    => __( 'Log Messages', 'cdek-for-woocommerce' ),
		'type'     => 'checkbox',
		'id'       => 'cdek_hide_info_log',
		'desc'     => __( 'Hide Info Log Messages', 'cdek-for-woocommerce' ),
		'default'  => 'no',
		'desc_tip' => __( 'By default all requests stored in WooCommerce logs. You can hide info messages and keep only errors and warnings.', 'cdek-for-woocommerce' ),
	),
	array(
		'type' => 'sectionend',
	),
);
