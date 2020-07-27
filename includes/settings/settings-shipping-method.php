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
		'options'     => array(
			1   => 'Экспресс лайт дверь-дверь',
			3   => 'Супер-экспресс до 18 дверь-дверь',
			5   => 'Экономичный экспресс склад-склад',
			10  => 'Экспресс лайт склад-склад',
			11  => 'Экспресс лайт склад-дверь',
			12  => 'Экспресс лайт дверь-склад',
			15  => 'Экспресс тяжеловесы склад-склад',
			16  => 'Экспресс тяжеловесы склад-дверь',
			17  => 'Экспресс тяжеловесы дверь-склад',
			18  => 'Экспресс тяжеловесы дверь-дверь',
			57  => 'Супер-экспресс до 9 дверь-дверь',
			58  => 'Супер-экспресс до 10 дверь-дверь',
			59  => 'Супер-экспресс до 12 дверь-дверь',
			60  => 'Супер-экспресс до 14 дверь-дверь',
			61  => 'Супер-экспресс до 16 дверь-дверь',
			62  => 'Магистральный экспресс склад-склад',
			63  => 'Магистральный супер-экспресс склад-склад',
			118 => 'Экономичный экспресс дверь-дверь',
			119 => 'Экономичный экспресс склад-дверь',
			120 => 'Экономичный экспресс дверь-склад',
			121 => 'Магистральный экспресс дверь-дверь',
			122 => 'Магистральный экспресс склад-дверь',
			123 => 'Магистральный экспресс дверь-склад',
			124 => 'Магистральный супер-экспресс дверь-дверь',
			125 => 'Магистральный супер-экспресс склад-дверь',
			126 => 'Магистральный супер-экспресс дверь-склад',
			// Экспресс-доставка за/из-за границы документов и писем.
			7   => 'Международный экспресс документы дверь-дверь',
			// Экспресс-доставка за/из-за границы грузов и посылок до 30 кг.
			8   => 'Международный экспресс грузы дверь-дверь',
			// Услуга экономичной доставки товаров по России для компаний, осуществляющих дистанционную торговлю.
			136 => 'Посылка склад-склад',
			137 => 'Посылка склад-дверь',
			138 => 'Посылка дверь-склад',
			139 => 'Посылка дверь-дверь',
			// Экспресс-доставка за/из-за границы грузов и посылок до 30 кг.
			178 => 'Международный экспресс грузы склад-склад',
			179 => 'Международный экспресс грузы склад-дверь',
			180 => 'Международный экспресс грузы дверь-склад',
			// Экспресс-доставка за/из-за границы документов и писем.
			181 => 'Международный экспресс документы склад-скла',
			182 => 'Международный экспресс документы склад-двер',
			183 => 'Международный экспресс документы дверь-скла',
			// Услуга экономичной наземной доставки товаров по России для компаний, осуществляющих дистанционную торговлю.
			// Услуга действует по направлениям из Москвы в подразделения СДЭК, находящиеся за Уралом и в Крым.
			// до 50 кг.
			231 => 'Экономичная посылка дверь-дверь',
			232 => 'Экономичная посылка дверь-склад',
			233 => 'Экономичная посылка склад-дверь',
			234 => 'Экономичная посылка склад-склад',
			// Тарифы Китайский экспресс.
			243 => 'Китайский экспресс склад-склад',
			245 => 'Китайский экспресс дверь-дверь',
			246 => 'Китайский экспресс склад-дверь',
			247 => 'Китайский экспресс дверь-склад',
			// Сервис по доставке товаров из-за рубежа в Россию, Украину, Казахстан, Киргизию, Узбекистан с услугами по таможенному оформлению.
			291 => 'CDEK Express склад-склад',
			293 => 'CDEK Express дверь-дверь',
			294 => 'CDEK Express склад-дверь',
			295 => 'CDEK Express дверь-склад',
		),
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
	'add_cost'                           => array(
		'title'       => __( 'Additional Cost', 'cdek-for-woocommerce' ),
		'description' => __( 'Additional flat rate for this shipping method. This may be the average value of the package for example', 'cdek-for-woocommerce' ),
		'type'        => 'price',
	),
	'add_percentage_cost'                => array(
		'title'       => __( 'Additional Percentage Cost', 'cdek-for-woocommerce' ),
		'description' => __( 'Additional percentage rate for this shipping method.', 'cdek-for-woocommerce' ),
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
		'description' => __( 'Package size is not affecting the shipping rate but required for creating order in a dashboard. If you select "Package 1" in services, its size will be used in the condition that custom package size fields are empty. If you wish to create separate custom package sizes depending on the shipping class or weight of the order you can do it by creating separate shipping methods with different conditions. If a customer order is not fit in provided custom dimensions you will be able to change it before sending the order to the CDEK dashboard.', 'cdek-for-woocommerce' ),
		'type'        => 'title',
	),
	'package_length'                     => array(
		'title'             => __( 'Package Length (in cm)', 'cdek-for-woocommerce' ),
		'description'       => CDEKFW::only_in_pro_ver_text(),
		'type'              => 'number',
		'custom_attributes' => array(
			CDEKFW::is_pro_active() ? '' : 'disabled' => '',
		),
	),
	'package_width'                      => array(
		'title'             => __( 'Package Width (in cm)', 'cdek-for-woocommerce' ),
		'description'       => CDEKFW::only_in_pro_ver_text(),
		'type'              => 'number',
		'custom_attributes' => array(
			CDEKFW::is_pro_active() ? '' : 'disabled' => '',
		),
	),
	'package_height'                     => array(
		'title'             => __( 'Package Height (in cm)', 'cdek-for-woocommerce' ),
		'description'       => CDEKFW::only_in_pro_ver_text(),
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
