<?php
/**
 * CDEK Shipping Method.
 *
 * @version 1.0.0
 * @package CDEK/Classes/Shipping
 */

defined( 'ABSPATH' ) || exit;

/**
 * CDEKFW_Shipping_Method class.
 */
class CDEKFW_Shipping_Method extends WC_Shipping_Method {

	/**
	 * Constructor
	 *
	 * @param int $instance_id Shipping method instance ID.
	 */
	public function __construct( $instance_id = 0 ) {
		$this->id                 = 'cdek_shipping';
		$this->instance_id        = absint( $instance_id );
		$this->method_title       = __( 'CDEK', 'cdek-for-woocommerce' );
		$this->method_description = __( 'Lets you charge a fixed rate for shipping.', 'cdek-for-woocommerce' );
		$this->supports           = array(
			'shipping-zones',
			'instance-settings',
		);
		$this->init();
	}

	/**
	 * Init variables
	 */
	public function init() {
		$this->instance_form_fields = include 'settings/settings-shipping-method.php';

		foreach ( $this->instance_form_fields as $key => $settings ) {
			$this->{$key} = $this->get_option( $key );
		}

		add_action( 'woocommerce_update_options_shipping_' . $this->id, array( $this, 'process_admin_options' ) );
	}

	/**
	 * Calculate shipping rate
	 *
	 * @param array $package Package of items from cart.
	 */
	public function calculate_shipping( $package = array() ) {
		$label         = $this->title;
		$from_postcode = get_option( 'cdek_sender_post_code', 101000 );
		$to_country    = $package['destination']['country'] ? $package['destination']['country'] : 'RU';
		$to_postcode   = wc_format_postcode( $package['destination']['postcode'], $to_country );
		$state         = $package['destination']['state'];
		$city          = $package['destination']['city'];

		if ( CDEKFW::is_pro_active() && $state && $city && 'RU' === $to_country ) {
			$to_postcode = CDEKFW_PRO_Ru_Base::get_index_based_on_address( $state, $city );
		}

		if ( ! $to_postcode ) {
			return;
		}

		$args = array(
			'receiverCityPostCode' => $to_postcode,
			'receiverCountryCode'  => $to_country,
			'receiverCity'         => $city,
			'senderCityPostCode'   => $from_postcode ? $from_postcode : 101000,
			'goods'                => $this->get_goods_dimensions( $package ),
			'tariffId'             => intval( $this->tariff ),
			'services'             => array(),
		);

		$shipping_rate = CDEKFW_Client::calculate_rate( $args );

		if ( ! $shipping_rate ) {
			return;
		}

		$shipping_rate = $shipping_rate['result'];
		$cost          = ceil( $shipping_rate['price'] );
		$delivery_time = intval( $shipping_rate['deliveryPeriodMax'] );

		if ( $this->add_cost ) {
			$cost += intval( $this->add_cost );
		}

		if ( $this->show_delivery_time ) {
			if ( $this->add_delivery_time ) {
				$delivery_time += $this->add_delivery_time;
			}

			/* translators: %s: Delivery time */
			$label .= ' (' . sprintf( _n( '%s day', '%s days', $delivery_time, 'cdek-for-woocommerce' ), number_format_i18n( $delivery_time ) ) . ')';
		}

		$this->add_rate(
			array(
				'id'      => $this->get_rate_id(),
				'label'   => $label,
				'cost'    => $cost,
				'package' => $package,
			)
		);
	}

	/**
	 * Get all goods dimensions
	 *
	 * @param array $package Package of items from cart.
	 *
	 * @return array
	 */
	public function get_goods_dimensions( $package ) {
		$defaults = CDEKFW_Helper::get_default_dimensions();
		$goods    = array();

		foreach ( $package['contents'] as $item_id => $item_values ) {
			if ( ! $item_values['data']->needs_shipping() ) {
				continue;
			}

			$weight = wc_get_weight( $item_values['data']->get_weight(), 'kg' );
			$length = wc_get_dimension( $item_values['data']->get_length(), 'cm' );
			$width  = wc_get_dimension( $item_values['data']->get_width(), 'cm' );
			$height = wc_get_dimension( $item_values['data']->get_height(), 'cm' );

			for ( $i = 0; $i < $item_values['quantity']; $i ++ ) {
				$goods[] = array(
					'weight' => $weight ? $weight : wc_get_weight( $defaults['weight'], 'kg' ),
					'length' => $length ? $length : $defaults['length'],
					'width'  => $width ? $width : $defaults['width'],
					'height' => $height ? $height : $defaults['height'],
				);
			}
		}

		return $goods;
	}
}
