<?php
/**
 * Settings for CDEK shipping.
 *
 * @package CDEK/Classes/Shipping
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
	$post_index_message = '<br><br><span style="color: red">Пожалуйста, обратите внимание,</span><span style="color: #007cba"> что расчет доставки происходит только от индекса отправителя до индекса получателя. Убедитесь, что в вашем магазине поле индекс при оформлении заказа не отключено и является обязательным для заполнения, иначе расчет будет невозможно произвести. Это ограничение отсутствует в PRO версии плагина так как используется база регионов и городов РФ.</span>';
}

$settings = array(
	'title'               => array(
		'title'       => __( 'Method title', 'cdek-for-woocommerce' ),
		'type'        => 'text',
		'description' => __( 'This controls the title which the user sees during checkout.', 'cdek-for-woocommerce' ),
		'default'     => __( 'CDEK', 'cdek-for-woocommerce' ),
	),
	'tariff'              => array(
		'title'       => __( 'Tariff', 'cdek-for-woocommerce' ),
		'description' => __( 'Please note. Not all tariffs available for some particular destinations. For example international shipment will work only for specific countries. So please always check what tariffs for what destination are available by checking official calculator.', 'cdek-for-woocommerce' ) . ' <a href="https://cdek.ru/calculate" target="_blank">https://cdek.ru/calculate</a>' . $post_index_message,
		'type'        => 'select',
		'class'       => 'wc-enhanced-select',
		'default'     => 'taxable',
		'options'     => array(
			1   => 'Экспресс лайт дверь-дверь (до 30 кг)',
			3   => 'Супер-экспресс до 18 дверь-дверь (до 30 кг)',
			5   => 'Экономичный экспресс склад-склад',
			10  => 'Экспресс лайт склад-склад (до 30 кг)',
			11  => 'Экспресс лайт склад-дверь (до 30 кг)',
			12  => 'Экспресс лайт дверь-склад (до 30 кг)',
			15  => 'Экспресс тяжеловесы склад-склад (до 30 кг)',
			16  => 'Экспресс тяжеловесы склад-дверь (до 30 кг)',
			17  => 'Экспресс тяжеловесы дверь-склад (до 30 кг)',
			18  => 'Экспресс тяжеловесы дверь-дверь (до 30 кг)',
			57  => 'Супер-экспресс до 9 дверь-дверь (до 30 кг)',
			58  => 'Супер-экспресс до 10 дверь-дверь (до 30 кг)',
			59  => 'Супер-экспресс до 12 дверь-дверь (до 30 кг)',
			60  => 'Супер-экспресс до 14 дверь-дверь (до 30 кг)',
			61  => 'Супер-экспресс до 16 дверь-дверь (до 30 кг)',
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
			7   => 'Международный экспресс документы дверь-дверь (до 5 кг)',
			// Экспресс-доставка за/из-за границы грузов и посылок до 30 кг.
			8   => 'Международный экспресс грузы дверь-дверь (до 30 кг)',
			// Услуга экономичной доставки товаров по России для компаний, осуществляющих дистанционную торговлю.
			136 => 'Посылка склад-склад (до 30 кг)',
			137 => 'Посылка склад-дверь (до 30 кг)',
			138 => 'Посылка дверь-склад (до 30 кг)',
			139 => 'Посылка дверь-дверь (до 30 кг)',
			// Экспресс-доставка за/из-за границы грузов и посылок до 30 кг.
			178 => 'Международный экспресс грузы склад-склад (до 30 кг)',
			179 => 'Международный экспресс грузы склад-дверь (до 30 кг)',
			180 => 'Международный экспресс грузы дверь-склад (до 30 кг)',
			// Экспресс-доставка за/из-за границы документов и писем.
			181 => 'Международный экспресс документы склад-склад (до 5 кг)',
			182 => 'Международный экспресс документы склад-дверь (до 5 кг)',
			183 => 'Международный экспресс документы дверь-склад (до 5 кг)',
			// Услуга экономичной наземной доставки товаров по России для компаний, осуществляющих дистанционную торговлю.
			// Услуга действует по направлениям из Москвы в подразделения СДЭК, находящиеся за Уралом и в Крым.
			231 => 'Экономичная посылка дверь-дверь (до 50 кг)',
			232 => 'Экономичная посылка дверь-склад (до 50 кг)',
			233 => 'Экономичная посылка склад-дверь (до 50 кг)',
			234 => 'Экономичная посылка склад-склад (до 50 кг)',
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
	'tax_status'          => array(
		'title'   => __( 'Tax status', 'cdek-for-woocommerce' ),
		'type'    => 'select',
		'class'   => 'wc-enhanced-select',
		'default' => 'taxable',
		'options' => array(
			'taxable' => __( 'Taxable', 'cdek-for-woocommerce' ),
			'none'    => _x( 'None', 'Tax status', 'cdek-for-woocommerce' ),
		),
	),
	'add_settings_title'  => array(
		'title' => __( 'Additional Settings', 'cdek-for-woocommerce' ),
		'type'  => 'title',
	),
	'services'            => array(
		'title'   => __( 'Additional Services', 'cdek-for-woocommerce' ),
		'type'    => 'multiselect',
		'class'   => 'wc-enhanced-select',
		'options' => array(
			2  => 'Страхование',
			3  => 'Доставка в выходной день',
			7  => 'Опасный груз',
			24 => 'Упаковка 1 (31*21*28см для грузов до 10 кг)',
			30 => 'Примерка на дому',
			36 => 'Частичная доставка',
			37 => 'Осмотр вложения',
		),
	),
	'add_cost'            => array(
		'title' => __( 'Additional Cost', 'cdek-for-woocommerce' ),
		'type'  => 'number',
	),
	'add_weight'          => array(
		'title'       => __( 'Additional Weight', 'cdek-for-woocommerce' ),
		'description' => __( 'Set additional weight. It could be package weight for example.', 'cdek-for-woocommerce' ),
		'type'        => 'number',
	),
	'delivery_time_title' => array(
		'title' => __( 'Delivery time', 'cdek-for-woocommerce' ),
		'type'  => 'title',
	),
	'show_delivery_time'  => array(
		'title' => __( 'Show delivery time', 'cdek-for-woocommerce' ),
		'type'  => 'checkbox',
	),
	'add_delivery_time'   => array(
		'title' => __( 'Additional Time for Delivery', 'cdek-for-woocommerce' ),
		'type'  => 'number',
	),
	'package_title'       => array(
		'title'       => __( 'Custom Package Size', 'cdek-for-woocommerce' ),
		'description' => __( 'Package size is not affecting shipping rate but required for creating order in a dashboard. If you select "Package 1" in services, its size will be used in condition that custom package size field are empty. If you wish to create separate custom package sizes depending on shipping class or weight of the order you can do it by creating separate shipping methods with different conditions. If customer order is not fit in provided custom dimensions you will be able to change it before sending order to CDEK dashboard. ', 'cdek-for-woocommerce' ),
		'type'        => 'title',
	),
	'package_width'       => array(
		'title'             => __( 'Package Width (in cm)', 'cdek-for-woocommerce' ),
		'description'       => CDEKFW::only_in_pro_ver_text(),
		'type'              => 'number',
		'custom_attributes' => array(
			CDEKFW::is_pro_active() ? '' : 'disabled' => '',
		),
	),
	'package_height'      => array(
		'title'             => __( 'Package Height (in cm)', 'cdek-for-woocommerce' ),
		'description'       => CDEKFW::only_in_pro_ver_text(),
		'type'              => 'number',
		'custom_attributes' => array(
			CDEKFW::is_pro_active() ? '' : 'disabled' => '',
		),
	),
	'package_length'      => array(
		'title'             => __( 'Package Length (in cm)', 'cdek-for-woocommerce' ),
		'description'       => CDEKFW::only_in_pro_ver_text(),
		'type'              => 'number',
		'custom_attributes' => array(
			CDEKFW::is_pro_active() ? '' : 'disabled' => '',
		),
	),
	'conditions_title'    => array(
		'title'       => __( 'Conditions', 'cdek-for-woocommerce' ),
		'description' => esc_html__( 'Disable a method based on some conditions.', 'cdek-for-woocommerce' ),
		'type'        => 'title',
	),
	'cond_min_cost'       => array(
		'title'             => esc_html__( 'Min. cost of order in RUB', 'cdek-for-woocommerce' ),
		'description'       => CDEKFW::only_in_pro_ver_text() . esc_html__( 'Disable this method if the cost of the order is less than inputted value. Leave this field empty to allow any order cost.', 'cdek-for-woocommerce' ),
		'type'              => 'number',
		'custom_attributes' => array(
			CDEKFW::is_pro_active() ? '' : 'disabled' => '',
		),
	),
	'cond_min_weight'     => array(
		'title'             => esc_html__( 'Min. weight of order in grams', 'cdek-for-woocommerce' ),
		'description'       => CDEKFW::only_in_pro_ver_text() . esc_html__( 'Disable this method if the weight of the order is less than inputted value. Leave this field empty to allow any order weight.', 'cdek-for-woocommerce' ),
		'type'              => 'number',
		'custom_attributes' => array(
			CDEKFW::is_pro_active() ? '' : 'disabled' => '',
		),
	),
	'cond_max_weight'     => array(
		'title'             => esc_html__( 'Max. weight of order in grams', 'cdek-for-woocommerce' ),
		'description'       => CDEKFW::only_in_pro_ver_text() . esc_html__( 'Disable this method if the weight of the order is more than inputted value. Leave this field empty to allow any order weight.', 'cdek-for-woocommerce' ),
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
			'title'             => sprintf( esc_html__( '"%s" shipping class cost', 'cdek-for-woocommerce' ), esc_html( $shipping_class->name ) ),
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
