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
		$this->method_description = __( 'Calculate shipping rates for CDEK tariffs.', 'cdek-for-woocommerce' );
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
		$services      = $this->services ? $this->services : array();
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

		$args = array(
			'receiverCityPostCode' => $to_postcode,
			'receiverCountryCode'  => $to_country,
			'senderCityPostCode'   => $from_postcode ? $from_postcode : 101000,
			'goods'                => $this->get_goods_dimensions( $package ),
			'tariffId'             => intval( $this->tariff ),
			'services'             => $this->get_services( $services ),
		);

		if ( 'RU' !== $to_country ) {
			unset( $args['receiverCityPostCode'] );
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
	 * Get basic city code of country for international shipments
	 *
	 * @param string $country_code Country Code.
	 *
	 * @return bool|mixed
	 */
	public function get_international_city_id( $country_code ) {
		$city_ids = array(
			'AT' => 32,
			'AM' => 7114,
			'BY' => 9220,
			'FR' => 10090,
			'DE' => 196,
			'IL' => 11580,
			'KZ' => 4961,
			'KG' => 5444,
			'KR' => 11157,
			'MN' => 1868,
			'US' => 5917,
			'UA' => 7870,
			'UZ' => 11562,
			'CN' => 12683,
		);

		return isset( $city_ids[ $country_code ] ) ? $city_ids[ $country_code ] : false;
	}

	/**
	 * Prepare services list for sending
	 *
	 * @param array $services Selected shipping services.
	 *
	 * @return array
	 */
	public function get_services( $services ) {
		$services_ids = array();

		foreach ( $services as $service ) {
			$service_id = intval( $service );
			// in case insurance.
			if ( 2 === $service_id ) {
				$services_ids[] = array(
					'id'    => $service_id,
					'param' => ceil( WC()->cart->get_cart_contents_total() ),
				);
			} elseif ( 24 === $service_id || 25 === $service_id ) {
				$services_ids[] = array(
					'id'    => $service_id,
					'param' => 1,
				);
			} else {
				$services_ids[] = array( 'id' => $service_id );
			}
		}

		return $services_ids;
	}
}
