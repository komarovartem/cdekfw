<?php
/**
 * Settings for admin page.
 *
 * @package CDEK/Settings/Admin
 */

defined( 'ABSPATH' ) || exit;

return array(
	array(
		'title' => __( 'CDEK', 'cdek-for-woocommerce' ),
		'type'  => 'title',
	),
	array(
		'id'    => 'cdek_account',
		'title' => __( 'Account', 'cdek-for-woocommerce' ),
		'desc'  => __( 'Client ID.', 'cdek-for-woocommerce' ),
		'type'  => 'text',
	),
	array(
		'id'    => 'cdek_password',
		'title' => __( 'Secure password', 'cdek-for-woocommerce' ),
		'desc'  => __( 'Client secret key.', 'cdek-for-woocommerce' ),
		'type'  => 'text',
	),
	array(
		'id'      => 'cdek_type',
		'title'   => __( 'Type of agreement', 'cdek-for-woocommerce' ),
		'desc'    => __( '"Online store" -  only for a client with the type of agreement "online store". "Delivery" can be created by any client with a contract (but tariffs are available only for regular delivery).', 'cdek-for-woocommerce' ),
		'type'    => 'select',
		'options' => array(
			1 => __( 'Online store', 'cdek-for-woocommerce' ),
			2 => __( 'Delivery', 'cdek-for-woocommerce' ),
		),
	),
	array(
		'id'    => 'cdek_sender_post_code',
		'title' => __( 'Postcode', 'cdek-for-woocommerce' ),
		'desc'  => __( 'Postal code of the sender. If the field is empty, the 101000 index will be used by default.', 'cdek-for-woocommerce' ),
		'type'  => 'number',
	),
	array(
		'id'                => 'cdek_shipment_point',
		'title'             => __( 'Shipment point code', 'cdek-for-woocommerce' ),
		/* translators: %s are links. */
		'desc'              => CDEKFW::only_in_pro_ver_text() . sprintf( __( 'For integration with CDEK dashboard. It is required for tariffs "from shipment point". Shipment point code is usually three letters and number like "MSK114" or "KSD42". You can find code for your city point on %1$s official website. %2$s', 'cdek-for-woocommerce' ), '<a href="https://cdek.ru/offices" target="_blank">', '</a>' ),
		'type'              => 'text',
		'custom_attributes' => array(
			CDEKFW::is_pro_active() ? '' : 'disabled' => '',
			'required'                                => true,
		),
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
		'id'    => 'cdek_dimensions_item_length',
		'title' => __( 'Length (cm.)', 'cdek-for-woocommerce' ),
		'type'  => 'number',
	),
	array(
		'title' => __( 'Width (cm.)', 'cdek-for-woocommerce' ),
		'type'  => 'number',
		'id'    => 'cdek_dimensions_item_width',
	),
	array(
		'title' => __( 'Height (cm.)', 'cdek-for-woocommerce' ),
		'type'  => 'number',
		'id'    => 'cdek_dimensions_item_height',
	),
	array(
		'title' => __( 'Weight (g.)', 'cdek-for-woocommerce' ),
		'type'  => 'number',
		'id'    => 'cdek_dimensions_item_weight',
	),
	array(
		'type' => 'sectionend',
	),
	array(
		'title' => __( 'Shipper', 'cdek-for-woocommerce' ),
		'desc'  => __( 'Only for orders "Online store" with international shipment.', 'cdek-for-woocommerce' ),
		'type'  => 'title',
	),
	array(
		'title'             => __( 'Shipper Name', 'cdek-for-woocommerce' ),
		'desc'              => CDEKFW::only_in_pro_ver_text(),
		'type'              => 'text',
		'id'                => 'cdek_pro_shipper_name',
		'custom_attributes' => array(
			CDEKFW::is_pro_active() ? '' : 'disabled' => '',
			'required'                                => true,
		),
	),
	array(
		'title'             => __( 'Shipper Address', 'cdek-for-woocommerce' ),
		'desc'              => CDEKFW::only_in_pro_ver_text(),
		'type'              => 'text',
		'id'                => 'cdek_pro_shipper_address',
		'custom_attributes' => array(
			CDEKFW::is_pro_active() ? '' : 'disabled' => '',
			'required'                                => true,
		),
	),
	array(
		'type' => 'sectionend',
	),
	array(
		'title' => __( 'Seller', 'cdek-for-woocommerce' ),
		'desc'  => __( 'Details of the real seller. It is used when printing invoices to display the address of the real seller of the goods, as well as for international orders.', 'cdek-for-woocommerce' ),
		'type'  => 'title',
	),
	array(
		'title'             => __( 'Seller Name', 'cdek-for-woocommerce' ),
		'desc'              => CDEKFW::only_in_pro_ver_text(),
		'type'              => 'text',
		'id'                => 'cdek_pro_seller_name',
		'custom_attributes' => array(
			CDEKFW::is_pro_active() ? '' : 'disabled' => '',
			'required'                                => true,
		),
	),
	array(
		'title'             => __( 'INN', 'cdek-for-woocommerce' ),
		'desc'              => CDEKFW::only_in_pro_ver_text(),
		'type'              => 'text',
		'id'                => 'cdek_pro_seller_inn',
		'custom_attributes' => array(
			CDEKFW::is_pro_active() ? '' : 'disabled' => '',
			'required'                                => true,
		),
	),
	array(
		'title'             => __( 'Phone', 'cdek-for-woocommerce' ),
		'desc'              => CDEKFW::only_in_pro_ver_text(),
		'type'              => 'text',
		'id'                => 'cdek_pro_seller_phone',
		'custom_attributes' => array(
			CDEKFW::is_pro_active() ? '' : 'disabled' => '',
			'required'                                => true,
		),
	),
	array(
		'title'             => __( 'Ownership Form', 'cdek-for-woocommerce' ),
		'desc'              => CDEKFW::only_in_pro_ver_text(),
		'type'              => 'select',
		'options'           => array(
			9   => 'Акционерное общество',
			61  => 'Закрытое акционерное общество',
			63  => 'Индивидуальный предприниматель',
			119 => 'Открытое акционерное общество',
			137 => 'Общество с ограниченной ответственностью',
			147 => 'Публичное акционерное общество',
		),
		'id'                => 'cdek_pro_seller_ownership_form',
		'custom_attributes' => array(
			CDEKFW::is_pro_active() ? '' : 'disabled' => '',
			'required'                                => true,
		),
	),
	array(
		'title'             => __( 'Address', 'cdek-for-woocommerce' ),
		'desc'              => CDEKFW::only_in_pro_ver_text(),
		'type'              => 'text',
		'id'                => 'cdek_pro_seller_address',
		'custom_attributes' => array(
			CDEKFW::is_pro_active() ? '' : 'disabled' => '',
			'required'                                => true,
		),
	),
	array(
		'type' => 'sectionend',
	),
	array(
		'title' => __( 'Sender', 'cdek-for-woocommerce' ),
		'desc'  => __( 'To register orders with CDEK dashboard for the delivery of goods to customers.', 'cdek-for-woocommerce' ),
		'type'  => 'title',
	),
	array(
		'title'             => __( 'Company Name', 'cdek-for-woocommerce' ),
		'desc'              => CDEKFW::only_in_pro_ver_text(),
		'type'              => 'text',
		'id'                => 'cdek_pro_sender_company',
		'custom_attributes' => array(
			CDEKFW::is_pro_active() ? '' : 'disabled' => '',
			'required'                                => true,
		),
	),
	array(
		'title'             => __( 'Full Name', 'cdek-for-woocommerce' ),
		'desc'              => CDEKFW::only_in_pro_ver_text() . __( 'Full name of contact person', 'cdek-for-woocommerce' ),
		'type'              => 'text',
		'id'                => 'cdek_pro_sender_name',
		'custom_attributes' => array(
			CDEKFW::is_pro_active() ? '' : 'disabled' => '',
			'required'                                => true,
		),
	),
	array(
		'title'             => __( 'Email', 'cdek-for-woocommerce' ),
		'desc'              => CDEKFW::only_in_pro_ver_text(),
		'type'              => 'text',
		'id'                => 'cdek_pro_sender_email',
		'custom_attributes' => array(
			CDEKFW::is_pro_active() ? '' : 'disabled' => '',
			'required'                                => true,
		),
	),
	array(
		'title'             => __( 'Phone', 'cdek-for-woocommerce' ),
		'desc'              => CDEKFW::only_in_pro_ver_text() . __( 'It must be transmitted in the international format: country code (for Russia +7) and the number itself (10 or more digits)', 'cdek-for-woocommerce' ),
		'type'              => 'text',
		'id'                => 'cdek_pro_sender_phone',
		'custom_attributes' => array(
			CDEKFW::is_pro_active() ? '' : 'disabled' => '',
			'required'                                => true,
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
		'title'             => __( 'Synchronize order with tracking status', 'cdek-for-woocommerce' ),
		'desc'              => CDEKFW::only_in_pro_ver_text() . __( 'Select how often orders should check for tracking status. Automatically set order status from "Processing" to "Delivering" after shipping will be accepted in CDEK. And setting order status to "Completed" when shipping will be received by customer.', 'cdek-for-woocommerce' ),
		'type'              => 'select',
		'id'                => 'cdek_pro_use_auto_change_order_status',
		'options'           => array(
			''   => __( 'Do not synchronize', 'cdek-for-woocommerce' ),
			'1'  => __( 'Every 1 hour', 'cdek-for-woocommerce' ),
			'6'  => __( 'Every 6 hours', 'cdek-for-woocommerce' ),
			'12' => __( 'Every 12 hours', 'cdek-for-woocommerce' ),
			'24' => __( 'Every 24 hours', 'cdek-for-woocommerce' ),
		),
		'custom_attributes' => array(
			CDEKFW::is_pro_active() ? '' : 'disabled' => '',
		),
	),
	array(
		'title'             => __( 'Tracking code sending', 'cdek-for-woocommerce' ),
		'desc'              => CDEKFW::only_in_pro_ver_text() . __( 'You can send tracking code immediately after creating new package or when order status changed to delivering.', 'cdek-for-woocommerce' ),
		'type'              => 'select',
		'id'                => 'cdek_pro_use_auto_email_tracking_code',
		'options'           => array(
			''                                  => __( 'Send Manually', 'cdek-for-woocommerce' ),
			'after_creating_new_package'        => __( 'After creating new package', 'cdek-for-woocommerce' ),
			'after_status_change_to_delivering' => __( 'After status changed to delivering', 'cdek-for-woocommerce' ),
		),
		'custom_attributes' => array(
			CDEKFW::is_pro_active() ? '' : 'disabled' => '',
		),
	),
	array(
		'title'             => __( 'Tracking info block', 'cdek-for-woocommerce' ),
		'desc'              => CDEKFW::only_in_pro_ver_text() . __( 'Display tracking info on the customer account page.', 'cdek-for-woocommerce' ),
		'type'              => 'select',
		'id'                => 'cdek_pro_account_tracking_position',
		'options'           => array(
			'before' => __( 'Before order table', 'cdek-for-woocommerce' ),
			'after'  => __( 'After order table', 'cdek-for-woocommerce' ),
			'hide'   => __( 'Do not display', 'cdek-for-woocommerce' ),
		),
		'custom_attributes' => array(
			CDEKFW::is_pro_active() ? '' : 'disabled' => '',
		),
	),
	array(
		'type' => 'sectionend',
	),
	array(
		'title' => __( 'Order', 'cdek-for-woocommerce' ),
		'type'  => 'title',
	),
	array(
		'title'             => __( 'Auto submission of packages to CDEK dashboard', 'cdek-for-woocommerce' ),
		'desc'              => CDEKFW::only_in_pro_ver_text() . __( 'You can create automatic submission for packages with a specific order status.', 'cdek-for-woocommerce' ),
		'type'              => 'select',
		'id'                => 'cdek_pro_auto_order_submit',
		'options'           => array(
			''           => __( 'Send Manually', 'cdek-for-woocommerce' ),
			'processing' => __( 'After order got status Processing', 'cdek-for-woocommerce' ),
		),
		'custom_attributes' => array(
			CDEKFW::is_pro_active() ? '' : 'disabled' => '',
		),
	),
	array(
		'title'             => __( 'Admin order shipping', 'cdek-for-woocommerce' ),
		'desc'              => CDEKFW::only_in_pro_ver_text() . __( 'Select a method that will be used as a template for orders created via admin area.', 'cdek-for-woocommerce' ),
		'type'              => 'select',
		'id'                => 'cdek_pro_admin_shipping_method',
		'options'           => CDEKFW_Helper::get_all_methods(),
		'custom_attributes' => array(
			CDEKFW::is_pro_active() ? '' : 'disabled' => '',
		),
	),
	array(
		'title'             => __( 'Free shipping', 'cdek-for-woocommerce' ),
		'desc'              => CDEKFW::only_in_pro_ver_text() . __( 'To make synchronization of order with free shipping with CDEK API, select a method that will be used as a template for creating packages.', 'cdek-for-woocommerce' ),
		'type'              => 'select',
		'id'                => 'cdek_pro_free_shipping_method',
		'options'           => CDEKFW_Helper::get_all_methods(),
		'custom_attributes' => array(
			CDEKFW::is_pro_active() ? '' : 'disabled' => '',
		),
	),
	array(
		'title'             => __( 'Print barcode settings', 'cdek-for-woocommerce' ),
		'desc'              => CDEKFW::only_in_pro_ver_text() . __( 'Choose a format for printing barcode.', 'cdek-for-woocommerce' ),
		'type'              => 'select',
		'id'                => 'cdek_pro_barcode_format',
		'options'           => array(
			'A4' => 'A4',
			'A5' => 'A5',
			'A6' => 'A6',
		),
		'custom_attributes' => array(
			CDEKFW::is_pro_active() ? '' : 'disabled' => '',
		),
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
		'title' => __( 'Other', 'cdek-for-woocommerce' ),
		'type'  => 'title',
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
