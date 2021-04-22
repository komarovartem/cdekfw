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
		'title' => __( 'API Account', 'cdek-for-woocommerce' ),
		'desc'  => __( 'Account for API integration.', 'cdek-for-woocommerce' ) . ' ' . __( 'If you do not have API credentials you can get it by sending a request to integrator@cdek.ru. In the request, you must indicate your contract number with CDEK and e-mail to receive keys and notifications from the API integration.', 'cdek-for-woocommerce' ),
		'type'  => 'text',
	),
	array(
		'id'    => 'cdek_password',
		'title' => __( 'API Secure Password', 'cdek-for-woocommerce' ),
		'desc'  => __( 'Secret key for API integration.', 'cdek-for-woocommerce' ) . ' ' . __( 'If you do not have API credentials you can get it by sending a request to integrator@cdek.ru. In the request, you must indicate your contract number with CDEK and e-mail to receive keys and notifications from the API integration.', 'cdek-for-woocommerce' ),
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
		'id'                => 'cdek_sender_city_code',
		'title'             => __( 'CDEK City Code', 'cdek-for-woocommerce' ),
		/* translators: %s are links. */
		'desc'              => sprintf( __( 'Enter your city code here. You can get it from %1$s this table. %2$s', 'cdek-for-woocommerce' ), '<a href="' . plugin_dir_url( CDEK_PLUGIN_FILE ) . 'includes/lists/cdek-codes.html" target="_blank">', '</a>' ) . '<br><br>' . __( 'Some codes for popular cities.', 'cdek-for-woocommerce' ) . '<br>' . __( 'Moscow - 44', 'cdek-for-woocommerce' ) . '<br>' . __( 'St. Petersburg - 137', 'cdek-for-woocommerce' ) . '<br>' . __( 'Novosibirsk - 270', 'cdek-for-woocommerce' ) . '<br>' . __( 'Yekaterinburg - 250', 'cdek-for-woocommerce' ) . '<br>',
		'type'              => 'number',
		'custom_attributes' => array(
			'required' => true,
		),
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
			9   => __( 'Joint-stock company', 'cdek-for-woocommerce' ),
			61  => __( 'Closed joint-stock company', 'cdek-for-woocommerce' ),
			63  => __( 'Individual entrepreneur', 'cdek-for-woocommerce' ),
			119 => __( 'Open joint-stock company', 'cdek-for-woocommerce' ),
			137 => __( 'Limited liability company', 'cdek-for-woocommerce' ),
			147 => __( 'Public joint-stock company', 'cdek-for-woocommerce' ),
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
		'title'             => __( 'Region', 'cdek-for-woocommerce' ),
		'desc'              => CDEKFW::only_in_pro_ver_text() . __( 'Region name.', 'cdek-for-woocommerce' ),
		'type'              => 'text',
		'id'                => 'cdek_pro_sender_region',
		'custom_attributes' => array(
			CDEKFW::is_pro_active() ? '' : 'disabled' => '',
			'required'                                => true,
		),
	),
	array(
		'title'             => __( 'Sub Region', 'cdek-for-woocommerce' ),
		'desc'              => CDEKFW::only_in_pro_ver_text() . __( 'Sub region name if applicable.', 'cdek-for-woocommerce' ),
		'type'              => 'text',
		'id'                => 'cdek_pro_sender_sub_region',
		'custom_attributes' => array(
			CDEKFW::is_pro_active() ? '' : 'disabled' => '',
		),
	),
	array(
		'title'             => __( 'City', 'cdek-for-woocommerce' ),
		'desc'              => CDEKFW::only_in_pro_ver_text(),
		'type'              => 'text',
		'id'                => 'cdek_pro_sender_city',
		'custom_attributes' => array(
			CDEKFW::is_pro_active() ? '' : 'disabled' => '',
			'required'                                => true,
		),
	),
	array(
		'title'             => __( 'Address', 'cdek-for-woocommerce' ),
		'desc'              => CDEKFW::only_in_pro_ver_text(),
		'type'              => 'text',
		'id'                => 'cdek_pro_sender_address',
		'custom_attributes' => array(
			CDEKFW::is_pro_active() ? '' : 'disabled' => '',
			'required'                                => true,
		),
	),
	array(
		'title'             => __( 'KLADR Code', 'cdek-for-woocommerce' ),
		'desc'              => CDEKFW::only_in_pro_ver_text() . sprintf( __( 'Set KLADR Code of the location. You can find KLADR code on %1$sthe official website%2$s by selecting your Region > City > Street name', 'cdek-for-woocommerce' ), '<a href="https://kladr-rf.ru/" target="_blank">', '</a>' ),
		'type'              => 'text',
		'id'                => 'cdek_pro_sender_kladr',
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
		'title' => __( 'Courier pick up call', 'cdek-for-woocommerce' ),
		'desc'  => __( 'Default values for creating courier pick up call.', 'cdek-for-woocommerce' ),
		'type'  => 'title',
	),
	array(
		'title'             => __( 'Courier waiting time', 'cdek-for-woocommerce' ),
		'desc'              => CDEKFW::only_in_pro_ver_text() . __( 'The start time of the waiting courier. Please note: the time should not be earlier than 10:00 AM.', 'cdek-for-woocommerce' ),
		'type'              => 'number',
		'placeholder'       => '10',
		'id'                => 'cdek_pro_intake_time_from',
		'custom_attributes' => array(
			CDEKFW::is_pro_active() ? '' : 'disabled' => '',
			'min'                                     => 10,
		),
	),
	array(
		'desc'              => CDEKFW::only_in_pro_ver_text() . __( 'The end time of the waiting courier.', 'cdek-for-woocommerce' ),
		'type'              => 'number',
		'placeholder'       => '18',
		'id'                => 'cdek_pro_intake_time_until',
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
		'title'             => __( 'Middle Name Field Key', 'cdek-for-woocommerce' ),
		'desc'              => CDEKFW::only_in_pro_ver_text() . __( 'If you have a middle name field in your checkout form, add a meta key here to send it to CDEK dashboard with the rest of data.', 'cdek-for-woocommerce' ),
		'type'              => 'text',
		'id'                => 'cdek_pro_middle_name_field',
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
