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

		if ( 'RU' === $to_country ) {
			if ( CDEKFW::is_pro_active() && $state && $city ) {
				$to_postcode = CDEKFW_PRO_Ru_Base::get_index_based_on_address( $state, $city );
			}

			if ( ! $to_postcode ) {
				return;
			}
		}

		// https://confluence.cdek.ru/pages/viewpage.action?pageId=15616129#id-%D0%9F%D1%80%D0%BE%D1%82%D0%BE%D0%BA%D0%BE%D0%BB%D0%BE%D0%B1%D0%BC%D0%B5%D0%BD%D0%B0%D0%B4%D0%B0%D0%BD%D0%BD%D1%8B%D0%BC%D0%B8(v1.5)-4.14Calculator%D0%9A%D0%B0%D0%BB%D1%8C%D0%BA%D1%83%D0%BB%D1%8F%D1%82%D0%BE%D1%80.
		$args = array(
//			'receiverCityPostCode' => $to_postcode,
			'receiverCountryCode'  => strtolower($to_country),
//			'receiverCity'         => $city,
//			'senderCityPostCode'   => $from_postcode ? $from_postcode : 101000,
			'goods'                => $this->get_goods_dimensions( $package ),
			'tariffId'             => intval( $this->tariff ),
//			'services'             => array(),
		);

		if ( 'RU' !== $to_country ) {
			unset( $args['receiverCityPostCode'] );
			unset( $args['receiverCity'] );

			$args['senderCityId'] = 286;
			$args['receiverCityId'] = $this->get_international_city_id( $to_country );
		}

		$shipping_rate = CDEKFW_Client::calculate_rate( $args );

		if ( ! $shipping_rate ) {
			$this->maybe_print_error();
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

	/**
	 * Print human error only for admin to easy debug errors
	 */
	public function maybe_print_error() {
		if ( ! current_user_can( 'administrator' ) ) {
			return;
		}

		$this->add_rate(
			array(
				'id'        => $this->get_rate_id(),
				'label'     => $this->title . '. ' . __( 'Error during calculation. This message and method are visible only for the site Administrator for debugging purposes.', 'cdek-for-woocommerce' ),
				'cost'      => 0,
				'meta_data' => array( 'cdek_error' => true ),
			)
		);
	}

	/**
	 * Get basic country code for international shipments
	 *
	 * @param string $country_code Country Code.
	 *
	 * @return bool|mixed
	 */
	public function get_international_city_id( $country_code ) {
		$city_ids = array(
			'US' => 5917,
			'UA' => 7870,
		);

		return isset( $city_ids[ $country_code ] ) ? $city_ids[ $country_code ] : false;
	}
}
