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
		$from         = get_option( 'cdek_sender_post_code', 101000 );
		$country_code = $package['destination']['country'] ? $package['destination']['country'] : 'RU';
		$postcode     = wc_format_postcode( $package['destination']['postcode'], $country_code );
		$state        = $package['destination']['state'];
		$city         = $package['destination']['city'];

		if ( ! $postcode ) {
			return;
		}

		$args = array(
			'receiverCityPostCode' => intval( $postcode ),
			'senderCityPostCode'   => $from ? $from : 101000,
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
		$defaults = $this->get_default_dimensions();
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
					'weight' => $weight ? $weight : $defaults['weight'],
					'length' => $length ? $length : $defaults['length'],
					'width'  => $width ? $width : $defaults['width'],
					'height' => $height ? $height : $defaults['height'],
				);
			}
		}

		return $goods;
	}

	/**
	 * Get default weight and dimensions based on basic settings
	 *
	 * @return array
	 */
	public function get_default_dimensions() {
		$dimensions = array(
			'weight' => 0.3,
			'length' => 10,
			'width'  => 10,
			'height' => 10,
		);

		foreach ( $dimensions as $k => $v ) {
			$option = get_option( 'cdek_dimensions_pack_' . $k, $v );
			if ( $option ) {
				$dimensions[ $k ] = $option;
			}
		}

		return $dimensions;

	}
}
