<?php
/**
 * Settings for CDEK shipping.
 *
 * @package CDEK/Settings/Shipping
 */

defined( 'ABSPATH' ) || exit;

$shipping_classes         = WC()->shipping()->get_shipping_classes();
$post_index_message       = '';
$shipping_classes_options = array();
foreach ( $shipping_classes as $shipping_class ) {
	if ( ! isset( $shipping_class->term_id ) ) {
		continue;
	}
	$shipping_classes_options[ $shipping_class->term_id ] = $shipping_class->name;
}
$cost_desc = __( 'Enter a cost (excl. tax) or sum, e.g. 10.00 * [qty].', 'cdek-for-woocommerce' ) . '<br/><br/>' . __( 'Use [qty] for the number of items, [cost] for the total cost of items, and [fee percent="10" min_fee="20" max_fee=""] for percentage based fees.', 'cdek-for-woocommerce' );

if ( ! CDEKFW::is_pro_active() ) {
	$post_index_message = '<br><br><span style="color: red">' . __( 'Please note!', 'cdek-for-woocommerce' ) . '</span> <span style="color: #007cba">' . __( 'Delivery is calculated only from the sender postcode to the recipient\'s postcode. Make sure that the postcode field in your store is not disabled on the checkout page and is required, otherwise, the calculation will not be possible. This limitation is absent in the PRO version of the plugin since the bases of regions and cities of the Russian Federation are used.', 'cdek-for-woocommerce' ) . '</span>';
}

$settings = array(
	'title'                              => array(
		'title'       => __( 'Method title', 'cdek-for-woocommerce' ),
		'type'        => 'text',
		'description' => __( 'This title the user sees during checkout.', 'cdek-for-woocommerce' ),
		'default'     => __( 'CDEK', 'cdek-for-woocommerce' ),
	),
	'tariff'                             => array(
		'title'       => __( 'Tariff', 'cdek-for-woocommerce' ),
		'description' => '<span style="color: red">' . __( 'Please note. Not all tariffs available for some particular destinations.', 'cdek-for-woocommerce' ) . '</span><br>' . __( 'For example, international shipment will work only for specific countries or if you are creating a shipping zone to deliver from Moscow to Moscow and Moscow region you can select special tariffs that are available just for this destination and will not work for other destinations. So please always check what tariffs for what destination are available by checking the official calculator.', 'cdek-for-woocommerce' ) . ' <a href="https://cdek.ru/calculate" target="_blank">https://cdek.ru/calculate</a>' . $post_index_message,
		'type'        => 'select',
		'class'       => 'wc-enhanced-select',
		'default'     => 'taxable',
		'options'     => CDEKFW_Helper::get_tariffs(),
	),
	'tax_status'                         => array(
		'title'   => __( 'Tax status', 'cdek-for-woocommerce' ),
		'type'    => 'select',
		'class'   => 'wc-enhanced-select',
		'default' => 'taxable',
		'options' => array(
			'taxable' => __( 'Taxable', 'cdek-for-woocommerce' ),
			'none'    => _x( 'None', 'Tax status', 'cdek-for-woocommerce' ),
		),
	),
	'add_settings_title'                 => array(
		'title' => __( 'Additional Settings', 'cdek-for-woocommerce' ),
		'type'  => 'title',
	),
	'services'                           => array(
		'title'       => __( 'Additional Services', 'cdek-for-woocommerce' ),
		'description' => __( 'For type of agreement "Online store" orders the "Insurance" service is on by default.', 'cdek-for-woocommerce' ),
		'type'        => 'multiselect',
		'class'       => 'wc-enhanced-select',
		'options'     => array(
			2  => __( 'Insurance', 'cdek-for-woocommerce' ),
			3  => __( 'Delivery on weekends', 'cdek-for-woocommerce' ),
			7  => __( 'Dangerous cargoes', 'cdek-for-woocommerce' ),
			24 => __( 'Package 1 (31*21.5*28cm for packages to 10kg)', 'cdek-for-woocommerce' ),
			30 => __( 'Home fitting', 'cdek-for-woocommerce' ),
			36 => __( 'Partial delivery', 'cdek-for-woocommerce' ),
			37 => __( 'Inspection of contents', 'cdek-for-woocommerce' ),
		),
	),
	'remove_declared_value'              => array(
		'title'       => __( 'Remove Declared Value', 'cdek-for-woocommerce' ),
		'label'       => __( 'Make declared value equal to zero', 'cdek-for-woocommerce' ),
		'description' => __( 'By default for Insurance service the declared value of the product will be equal to its price but if you wish you can make it equal to zero. It will reduce shipping cost for a customer. Also it remove declared value for products during synchronization with personal dashboard for orders with this shipping method.', 'cdek-for-woocommerce' ),
		'type'        => 'checkbox',
	),
	'add_cost'                           => array(
		'title'       => __( 'Additional Cost', 'cdek-for-woocommerce' ),
		'description' => __( 'Additional flat rate for this shipping method. This may be the average value of the package for example', 'cdek-for-woocommerce' ) . ' ' . __( 'You can set negative number to subtract shipping cost if you wish.', 'cdek-for-woocommerce' ),
		'type'        => 'price',
	),
	'add_percentage_cost'                => array(
		'title'       => __( 'Additional Percentage Cost', 'cdek-for-woocommerce' ),
		'description' => __( 'Additional percentage rate for this shipping method.', 'cdek-for-woocommerce' ) . ' ' . __( 'You can set negative number to subtract shipping cost if you wish.', 'cdek-for-woocommerce' ),
		'type'        => 'number',
	),
	'add_percentage_cost_type'           => array(
		'description' => __( 'Type of calculation for additional percentage cost', 'cdek-for-woocommerce' ),
		'type'        => 'select',
		'default'     => 'percentage_subtotal',
		'options'     => array(
			'percentage_subtotal'      => __( 'Percentage of Subtotal', 'cdek-for-woocommerce' ),
			'percentage_shipping_cost' => __( 'Percentage of Shipping Cost', 'cdek-for-woocommerce' ),
			'percentage_total'         => __( 'Percentage of Total', 'cdek-for-woocommerce' ),
		),
	),
	'add_weight'                         => array(
		'title'       => __( 'Additional Weight (in g.)', 'cdek-for-woocommerce' ),
		'description' => __( 'Set additional weight. It could be package weight for example.', 'cdek-for-woocommerce' ),
		'type'        => 'number',
	),
	'delivery_time_title'                => array(
		'title' => __( 'Delivery time', 'cdek-for-woocommerce' ),
		'type'  => 'title',
	),
	'show_delivery_time'                 => array(
		'title' => __( 'Show delivery time', 'cdek-for-woocommerce' ),
		'type'  => 'checkbox',
	),
	'add_delivery_time'                  => array(
		'title' => __( 'Additional Time for Delivery', 'cdek-for-woocommerce' ),
		'type'  => 'number',
	),
	'package_title'                      => array(
		'title'       => __( 'Custom Package Size', 'cdek-for-woocommerce' ),
		'description' => __( 'Package size is affecting the shipping rate and required for creating order in a dashboard. If you select "Package 1" in services, its size will be used in the condition that custom package size fields are empty. If nor "Package 1" selected in services or custom package size fields are empty, the dimensions of the package will be calculated by CDEK combining all dimensions of the products. If you wish to create separate custom package sizes depending on the shipping class or weight of the order you can do it by creating separate shipping methods with different conditions with PRO plugin. If a customer order is not fit in provided custom dimensions you will be able to change it before sending the order to the CDEK dashboard.', 'cdek-for-woocommerce' ),
		'type'        => 'title',
	),
	'package_length'                     => array(
		'title' => __( 'Package Length (in cm)', 'cdek-for-woocommerce' ),
		'type'  => 'number',
	),
	'package_width'                      => array(
		'title' => __( 'Package Width (in cm)', 'cdek-for-woocommerce' ),
		'type'  => 'number',
	),
	'package_height'                     => array(
		'title' => __( 'Package Height (in cm)', 'cdek-for-woocommerce' ),
		'type'  => 'number',
	),
	'warehouse_title'                    => array(
		'title'       => __( 'Separate Shipment point', 'cdek-for-woocommerce' ),
		'description' => __( 'If you have several warehouses and wish to specify this shipping method to particular shipping zone you can set separate postcode and address data just for this method. If you send all packages from one location there is no need to specify a separate address for each shipping method and you can leave these fields empty.', 'cdek-for-woocommerce' ),
		'type'        => 'title',
	),
	'postcode'                           => array(
		'title'             => __( 'Postcode', 'cdek-for-woocommerce' ),
		'description'       => CDEKFW::only_in_pro_ver_text(),
		'type'              => 'text',
		'custom_attributes' => array(
			CDEKFW::is_pro_active() ? '' : 'disabled' => '',
		),
	),
	'shipment_point'                     => array(
		'title'             => __( 'Shipment point code', 'cdek-for-woocommerce' ),
		// translators: %s href link.
		'description'       => CDEKFW::only_in_pro_ver_text() . sprintf( __( 'You can find code for point in your city on %1$s official website. %2$s', 'cdek-for-woocommerce' ), '<a href="https://cdek.ru/offices" target="_blank">', '</a>' ),
		'type'              => 'text',
		'custom_attributes' => array(
			CDEKFW::is_pro_active() ? '' : 'disabled' => '',
		),
	),
	'region'                             => array(
		'title'             => __( 'Region', 'cdek-for-woocommerce' ),
		'description'       => CDEKFW::only_in_pro_ver_text(),
		'type'              => 'text',
		'custom_attributes' => array(
			CDEKFW::is_pro_active() ? '' : 'disabled' => '',
		),
	),
	'sub_region'                         => array(
		'title'             => __( 'Sub Region', 'cdek-for-woocommerce' ),
		'description'       => CDEKFW::only_in_pro_ver_text(),
		'type'              => 'text',
		'custom_attributes' => array(
			CDEKFW::is_pro_active() ? '' : 'disabled' => '',
		),
	),
	'city'                               => array(
		'title'             => __( 'City', 'cdek-for-woocommerce' ),
		'description'       => CDEKFW::only_in_pro_ver_text(),
		'type'              => 'text',
		'custom_attributes' => array(
			CDEKFW::is_pro_active() ? '' : 'disabled' => '',
		),
	),
	'address'                            => array(
		'title'             => __( 'Address', 'cdek-for-woocommerce' ),
		'description'       => CDEKFW::only_in_pro_ver_text(),
		'type'              => 'text',
		'custom_attributes' => array(
			CDEKFW::is_pro_active() ? '' : 'disabled' => '',
		),
	),
	'kladr_code'                         => array(
		'title'             => __( 'KLADR Code', 'cdek-for-woocommerce' ),
		// translators: %s href link.
		'description'       => CDEKFW::only_in_pro_ver_text() . sprintf( __( 'Set KLADR Code of the location. You can find KLADR code on %1$sthe official website%2$s by selecting your Region > City > Street name', 'cdek-for-woocommerce' ), '<a href="https://kladr-rf.ru/" target="_blank">', '</a>' ),
		'type'              => 'text',
		'custom_attributes' => array(
			CDEKFW::is_pro_active() ? '' : 'disabled' => '',
		),
	),
	'tariff_list'                        => array(
		'title'       => __( 'Tariff List', 'cdek-for-woocommerce' ),
		'description' => __( 'For some regions not all tariffs are available you can set multiple shipping methods for specific region but in this case if all tariffs are available all of them will be visible for customer to choose from. If you wish to provide a single tariff without creating several shipping methods you can set tariff list which will calculate selected tariffs one by one until one of provided tariff options will be successfully calculated.', 'cdek-for-woocommerce' ),
		'type'        => 'title',
	),
	'tariff_list_1'                      => array(
		'title'             => __( 'Additional Tariffs', 'cdek-for-woocommerce' ),
		'description'       => CDEKFW::only_in_pro_ver_text() . __( 'Tariff - 1. Set additional tariff in case the main one cannot be calculated for customer region.', 'cdek-for-woocommerce' ),
		'type'              => 'select',
		'class'             => 'wc-enhanced-select',
		'options'           => array( '' => __( 'Not Selected', 'cdek-for-woocommerce' ) ) + CDEKFW_Helper::get_tariffs(),
		'custom_attributes' => array(
			CDEKFW::is_pro_active() ? '' : 'disabled' => '',
		),
	),
	'tariff_list_2'                      => array(
		'description'       => CDEKFW::only_in_pro_ver_text() . __( 'Tariff - 2. Set alternative tariff in case the additional Tariff - 1 cannot be calculated.', 'cdek-for-woocommerce' ),
		'type'              => 'select',
		'class'             => 'wc-enhanced-select',
		'options'           => array( '' => __( 'Not Selected', 'cdek-for-woocommerce' ) ) + CDEKFW_Helper::get_tariffs(),
		'custom_attributes' => array(
			CDEKFW::is_pro_active() ? '' : 'disabled' => '',
		),
	),
	'tariff_list_3'                      => array(
		'description'       => CDEKFW::only_in_pro_ver_text() . __( 'Tariff - 3. Set alternative tariff in case the additional tariff - 2 cannot be calculated.', 'cdek-for-woocommerce' ),
		'type'              => 'select',
		'class'             => 'wc-enhanced-select',
		'options'           => array( '' => __( 'Not Selected', 'cdek-for-woocommerce' ) ) + CDEKFW_Helper::get_tariffs(),
		'custom_attributes' => array(
			CDEKFW::is_pro_active() ? '' : 'disabled' => '',
		),
	),
	'fixed_cost_title'                   => array(
		'title'       => __( 'Fixed Cost', 'cdek-for-woocommerce' ),
		'description' => __( 'You can set fixed shipping cost for this method but keep other features like delivery time and shipping points map active.', 'cdek-for-woocommerce' ),
		'type'        => 'title',
	),
	'fixed_cost'                         => array(
		'title'             => __( 'Shipping Cost', 'cdek-for-woocommerce' ),
		'description'       => CDEKFW::only_in_pro_ver_text() . __( 'Set fixed cost if you wish to overwrite calculated cost by CDEK.', 'cdek-for-woocommerce' ),
		'type'              => 'number',
		'custom_attributes' => array(
			CDEKFW::is_pro_active() ? '' : 'disabled' => '',
		),
	),
	'free_shipping_title'                => array(
		'title'       => __( 'Conditions for Free Shipping', 'cdek-for-woocommerce' ),
		'description' => __( 'You can make free shipping and display delivery date at the same time to inform your clients.', 'cdek-for-woocommerce' ),
		'type'        => 'title',
	),
	'free_shipping'                      => array(
		'type'              => 'checkbox',
		'title'             => __( 'Free Shipping', 'cdek-for-woocommerce' ),
		'description'       => CDEKFW::only_in_pro_ver_text(),
		'label'             => __( 'Make shipping cost equal to zero', 'cdek-for-woocommerce' ),
		'default'           => 'no',
		'custom_attributes' => array(
			CDEKFW::is_pro_active() ? '' : 'disabled' => '',
		),
	),
	'free_shipping_custom_title'         => array(
		'description'       => CDEKFW::only_in_pro_ver_text() . __( 'Custom method title. You could rename method title based on free shipping if you wish.', 'cdek-for-woocommerce' ),
		'type'              => 'text',
		'custom_attributes' => array(
			CDEKFW::is_pro_active() ? '' : 'disabled' => '',
		),
	),
	'free_shipping_cond'                 => array(
		'type'              => 'select',
		'label'             => __( 'Free Shipping Conditions', 'cdek-for-woocommerce' ),
		'description'       => CDEKFW::only_in_pro_ver_text() . __( 'Select requirements for free shipping', 'cdek-for-woocommerce' ),
		'default'           => '',
		'options'           => array(
			''           => __( 'Without conditions', 'cdek-for-woocommerce' ),
			'coupon'     => __( 'A valid free shipping coupon', 'cdek-for-woocommerce' ),
			'min_amount' => __( 'A minimum order amount', 'cdek-for-woocommerce' ),
			'either'     => __( 'A minimum order amount OR a coupon', 'cdek-for-woocommerce' ),
			'both'       => __( 'A minimum order amount AND a coupon', 'cdek-for-woocommerce' ),
		),
		'custom_attributes' => array(
			CDEKFW::is_pro_active() ? '' : 'disabled' => '',
		),
	),
	'free_shipping_cond_amount'          => array(
		'description'       => CDEKFW::only_in_pro_ver_text() . __( 'Set minimum order amount if you select it for free shipping conditions', 'cdek-for-woocommerce' ),
		'type'              => 'price',
		'custom_attributes' => array(
			CDEKFW::is_pro_active() ? '' : 'disabled' => '',
		),
	),
	'free_shipping_ignore_discounts'     => array(
		'label'             => __( 'Apply minimum order rule before coupon discount', 'cdek-for-woocommerce' ),
		'type'              => 'checkbox',
		'description'       => CDEKFW::only_in_pro_ver_text() . __( 'If checked, free shipping would be available based on pre-discount order amount.', 'cdek-for-woocommerce' ),
		'default'           => 'no',
		'custom_attributes' => array(
			CDEKFW::is_pro_active() ? '' : 'disabled' => '',
		),
	),
	'free_shipping_hide_if_not_achieved' => array(
		'label'             => __( 'Hide method if condition for free shipping is not achieved', 'cdek-for-woocommerce' ),
		'description'       => CDEKFW::only_in_pro_ver_text(),
		'type'              => 'checkbox',
		'default'           => 'no',
		'custom_attributes' => array(
			CDEKFW::is_pro_active() ? '' : 'disabled' => '',
		),
	),
	'conditions_title'                   => array(
		'title'       => __( 'Conditions', 'cdek-for-woocommerce' ),
		'description' => __( 'Disable a method based on some conditions.', 'cdek-for-woocommerce' ),
		'type'        => 'title',
	),
	'cond_min_cost'                      => array(
		'title'             => __( 'Min. cost of order in RUB', 'cdek-for-woocommerce' ),
		'description'       => CDEKFW::only_in_pro_ver_text() . __( 'Disable this method if the cost of the order is less than inputted value. Leave this field empty to allow any order cost.', 'cdek-for-woocommerce' ),
		'type'              => 'number',
		'custom_attributes' => array(
			CDEKFW::is_pro_active() ? '' : 'disabled' => '',
		),
	),
	'cond_min_weight'                    => array(
		'title'             => __( 'Min. weight of order in grams', 'cdek-for-woocommerce' ),
		'description'       => CDEKFW::only_in_pro_ver_text() . __( 'Disable this method if the weight of the order is less than inputted value. Leave this field empty to allow any order weight.', 'cdek-for-woocommerce' ),
		'type'              => 'number',
		'custom_attributes' => array(
			CDEKFW::is_pro_active() ? '' : 'disabled' => '',
		),
	),
	'cond_max_weight'                    => array(
		'title'             => __( 'Max. weight of order in grams', 'cdek-for-woocommerce' ),
		'description'       => CDEKFW::only_in_pro_ver_text() . __( 'Disable this method if the weight of the order is more than inputted value. Leave this field empty to allow any order weight.', 'cdek-for-woocommerce' ),
		'type'              => 'number',
		'custom_attributes' => array(
			CDEKFW::is_pro_active() ? '' : 'disabled' => '',
		),
	),
);

if ( $shipping_classes_options ) {
	$settings['cond_has_shipping_class'] = array(
		'title'             => __( 'Exclude for specific shipping classes', 'cdek-for-woocommerce' ),
		'description'       => CDEKFW::only_in_pro_ver_text() . __( 'Hide the method if the order contains at least one product with the selected shipping class. This condition can be used in order not to display the parcel or simple parcel for orders with bulky and / or heavy goods by weight when placing an order.', 'cdek-for-woocommerce' ),
		'type'              => 'multiselect',
		'class'             => 'wc-enhanced-select',
		'options'           => $shipping_classes_options,
		'custom_attributes' => array(
			CDEKFW::is_pro_active() ? '' : 'disabled' => '',
		),
	);
	$settings['class_costs_title']       = array(
		'title'             => __( 'Additional costs for shipping classes', 'cdek-for-woocommerce' ),
		'type'              => 'title',
		'default'           => '',
		// translators: %s href link.
		'description'       => CDEKFW::only_in_pro_ver_text() . sprintf( __( 'These costs can optionally be added based on the <a href="%s">product shipping class</a>.', 'cdek-for-woocommerce' ), admin_url( 'admin.php?page=wc-settings&tab=shipping&section=classes' ) ),
		'custom_attributes' => array(
			CDEKFW::is_pro_active() ? '' : 'disabled' => '',
		),
	);
	foreach ( $shipping_classes as $shipping_class ) {
		if ( ! isset( $shipping_class->term_id ) ) {
			continue;
		}

		$settings[ 'class_cost_' . $shipping_class->term_id ] = array(
			// translators: %s shipping class name.
			'title'             => sprintf( __( '"%s" shipping class cost', 'cdek-for-woocommerce' ), esc_html( $shipping_class->name ) ),
			'type'              => 'text',
			'placeholder'       => __( 'N/A', 'cdek-for-woocommerce' ),
			'description'       => CDEKFW::only_in_pro_ver_text() . $cost_desc,
			'desc_tip'          => true,
			'sanitize_callback' => array( $this, 'sanitize_cost' ),
			'custom_attributes' => array(
				CDEKFW::is_pro_active() ? '' : 'disabled' => '',
			),
		);
	}

	$settings['no_class_cost'] = array(
		'title'             => __( 'No shipping class cost', 'cdek-for-woocommerce' ),
		'type'              => 'text',
		'placeholder'       => 'N/A',
		'description'       => CDEKFW::only_in_pro_ver_text() . $cost_desc,
		'default'           => '',
		'desc_tip'          => true,
		'sanitize_callback' => array( $this, 'sanitize_cost' ),
		'custom_attributes' => array(
			CDEKFW::is_pro_active() ? '' : 'disabled' => '',
		),
	);

	$settings['class_cost_calc_type'] = array(
		'title'             => __( 'Calculation type', 'cdek-for-woocommerce' ),
		'description'       => CDEKFW::only_in_pro_ver_text(),
		'type'              => 'select',
		'class'             => 'wc-enhanced-select',
		'default'           => 'class',
		'options'           => array(
			'class' => __( 'Per class: Charge shipping for each shipping class individually', 'cdek-for-woocommerce' ),
			'order' => __( 'Per order: Charge shipping for the most expensive shipping class', 'cdek-for-woocommerce' ),
		),
		'custom_attributes' => array(
			CDEKFW::is_pro_active() ? '' : 'disabled' => '',
		),
	);
}

return $settings;
