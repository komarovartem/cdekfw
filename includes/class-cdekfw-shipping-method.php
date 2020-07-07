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
		$time          = '';
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

		if ( $this->check_condition_for_disable( $package ) ) {
			return;
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

		$shipping_rate       = $shipping_rate['result'];
		$shipping_class_cost = $this->get_shipping_class_cost( $package );
		$cost                = ceil( $shipping_rate['price'] ) + intval( $this->add_cost ) + $shipping_class_cost;
		$delivery_time       = intval( $shipping_rate['deliveryPeriodMax'] );

		if ( $this->show_delivery_time ) {
			if ( $this->add_delivery_time ) {
				$delivery_time += $this->add_delivery_time;
			}

			/* translators: %s: Delivery time */
			$time = ' (' . sprintf( _n( '%s day', '%s days', $delivery_time, 'cdek-for-woocommerce' ), number_format_i18n( $delivery_time ) ) . ')';
		}

		if ( 'yes' === $this->free_shipping ) {
			if ( $this->is_free_shipping_available() ) {
				$label = $this->free_shipping_custom_title ? $this->free_shipping_custom_title : $label;
				$this->add_rate(
					array(
						'id'      => $this->get_rate_id(),
						'label'   => $label . $time,
						'taxes'   => false,
						'package' => $package,
						'cost'    => 0,
					)
				);

				return;
			}

			if ( 'yes' === $this->free_shipping_hide_if_not_achieved ) {
				return;
			}
		}

		$this->add_rate(
			array(
				'id'      => $this->get_rate_id(),
				'label'   => $label . $time,
				'cost'    => $cost,
				'package' => $package,
			)
		);
	}

	/**
	 * Check all condition to display a method before calculation
	 *
	 * @param array $package Shipping package.
	 *
	 * @return bool
	 */
	public function check_condition_for_disable( $package ) {
		$total_val = WC()->cart->get_cart_subtotal();
		$weight    = wc_get_weight( WC()->cart->get_cart_contents_weight(), 'g' );

		// check if cost is less than provided in options.
		if ( $this->cond_min_cost && intval( $this->cond_min_cost ) > 0 && $total_val < $this->cond_min_cost ) {
			return true;
		}

		// check conditional weights.
		if ( ( $this->cond_min_weight && $weight < intval( $this->cond_min_weight ) ) || ( $this->cond_max_weight && $weight > intval( $this->cond_max_weight ) ) ) {
			return true;
		}

		// check if has specific shipping class.
		if ( $this->cond_has_shipping_class ) {
			$found_shipping_classes  = $this->find_shipping_classes( $package );
			$is_shipping_class_found = false;
			foreach ( $found_shipping_classes as $shipping_class => $products ) {
				$shipping_class_term = get_term_by( 'slug', $shipping_class, 'product_shipping_class' );
				if ( $shipping_class_term && $shipping_class_term->term_id && in_array( (string) $shipping_class_term->term_id, $this->cond_has_shipping_class, true ) ) {
					$is_shipping_class_found = true;
					break;
				}
			}

			if ( $is_shipping_class_found ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Add additional cost based on shipping classes
	 *
	 * @param array $package Shipping package.
	 *
	 * @return int
	 */
	public function get_shipping_class_cost( $package ) {
		$shipping_classes = WC()->shipping()->get_shipping_classes();
		$cost             = 0;

		if ( ! empty( $shipping_classes ) && isset( $this->class_cost_calc_type ) ) {
			$found_shipping_classes = $this->find_shipping_classes( $package );
			$highest_class_cost     = 0;

			foreach ( $found_shipping_classes as $shipping_class => $products ) {
				// Also handles BW compatibility when slugs were used instead of ids.
				$shipping_class_term = get_term_by( 'slug', $shipping_class, 'product_shipping_class' );
				$class_cost_string   = $shipping_class_term && $shipping_class_term->term_id ? $this->get_option( 'class_cost_' . $shipping_class_term->term_id, $this->get_option( 'class_cost_' . $shipping_class, '' ) ) : $this->get_option( 'no_class_cost', '' );

				if ( '' === $class_cost_string ) {
					continue;
				}

				$class_cost = $this->evaluate_cost(
					$class_cost_string,
					array(
						'qty'  => array_sum( wp_list_pluck( $products, 'quantity' ) ),
						'cost' => array_sum( wp_list_pluck( $products, 'line_total' ) ),
					)
				);

				if ( 'class' === $this->class_cost_calc_type ) {
					$cost += $class_cost;
				} else {
					$highest_class_cost = $class_cost > $highest_class_cost ? $class_cost : $highest_class_cost;
				}
			}

			if ( 'order' === $this->class_cost_calc_type && $highest_class_cost ) {
				$cost += $highest_class_cost;
			}
		}

		return $cost;
	}

	/**
	 * Finds and returns shipping classes and the products with said class.
	 *
	 * @param mixed $package Package of items from cart.
	 *
	 * @return array
	 */
	public function find_shipping_classes( $package ) {
		$found_shipping_classes = array();

		foreach ( $package['contents'] as $item_id => $values ) {
			if ( $values['data']->needs_shipping() ) {
				$found_class = $values['data']->get_shipping_class();

				if ( ! isset( $found_shipping_classes[ $found_class ] ) ) {
					$found_shipping_classes[ $found_class ] = array();
				}

				$found_shipping_classes[ $found_class ][ $item_id ] = $values;
			}
		}

		return $found_shipping_classes;
	}

	/**
	 * Work out fee (shortcode).
	 *
	 * @param array $atts Attributes.
	 *
	 * @return string
	 */
	public function fee( $atts ) {
		$atts = shortcode_atts(
			array(
				'percent' => '',
				'min_fee' => '',
				'max_fee' => '',
			),
			$atts,
			'fee'
		);

		$calculated_fee = 0;

		if ( $atts['percent'] ) {
			$calculated_fee = $this->fee_cost * ( floatval( $atts['percent'] ) / 100 );
		}

		if ( $atts['min_fee'] && $calculated_fee < $atts['min_fee'] ) {
			$calculated_fee = $atts['min_fee'];
		}

		if ( $atts['max_fee'] && $calculated_fee > $atts['max_fee'] ) {
			$calculated_fee = $atts['max_fee'];
		}

		return $calculated_fee;
	}


	/**
	 * Evaluate a cost from a sum/string.
	 *
	 * @param string $sum Sum of shipping.
	 * @param array  $args Args.
	 *
	 * @return string
	 */
	protected function evaluate_cost( $sum, $args = array() ) {
		include_once WC()->plugin_path() . '/includes/libraries/class-wc-eval-math.php';

		// Allow 3rd parties to process shipping cost arguments.
		$args           = apply_filters( 'woocommerce_evaluate_shipping_cost_args', $args, $sum, $this );
		$locale         = localeconv();
		$decimals       = array(
			wc_get_price_decimal_separator(),
			$locale['decimal_point'],
			$locale['mon_decimal_point'],
			',',
		);
		$this->fee_cost = $args['cost'];

		// Expand shortcodes.
		add_shortcode( 'fee', array( $this, 'fee' ) );

		$sum = do_shortcode(
			str_replace(
				array(
					'[qty]',
					'[cost]',
				),
				array(
					$args['qty'],
					$args['cost'],
				),
				$sum
			)
		);

		remove_shortcode( 'fee', array( $this, 'fee' ) );

		// Remove whitespace from string.
		$sum = preg_replace( '/\s+/', '', $sum );

		// Remove locale from string.
		$sum = str_replace( $decimals, '.', $sum );

		// Trim invalid start/end characters.
		$sum = rtrim( ltrim( $sum, "\t\n\r\0\x0B+*/" ), "\t\n\r\0\x0B+-*/" );

		// Do the math.
		return $sum ? WC_Eval_Math::evaluate( $sum ) : 0;
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
					'weight' => $weight ? $weight : intval( $defaults['weight'] ) / 1000,
					'length' => $length ? $length : $defaults['length'],
					'width'  => $width ? $width : $defaults['width'],
					'height' => $height ? $height : $defaults['height'],
				);
			}
		}

		// additional weight.
		if ( $this->add_weight ) {
			$goods[] = array(
				'weight' => intval( $this->add_weight ) / 1000,
				'length' => 1,
				'width'  => 1,
				'height' => 1,
			);
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

	/**
	 * Check if free shipping is available based on the package and cart.
	 *
	 * @return bool
	 */
	public function is_free_shipping_available() {
		$has_coupon         = false;
		$has_met_min_amount = false;

		if ( in_array( $this->free_shipping_cond, array( 'coupon', 'either', 'both' ), true ) ) {
			$coupons = WC()->cart->get_coupons();

			if ( $coupons ) {
				foreach ( $coupons as $code => $coupon ) {
					if ( $coupon->is_valid() && $coupon->get_free_shipping() ) {
						$has_coupon = true;
						break;
					}
				}
			}
		}

		if ( in_array( $this->free_shipping_cond, array( 'min_amount', 'either', 'both' ), true ) ) {
			$total = WC()->cart->get_displayed_subtotal();

			if ( WC()->cart->display_prices_including_tax() ) {
				$total = $total - WC()->cart->get_discount_tax();
			}

			if ( 'no' === $this->free_shipping_ignore_discounts ) {
				$total = $total - WC()->cart->get_discount_total();
			}

			$total = round( $total, wc_get_price_decimals() );

			if ( $total >= $this->free_shipping_cond_amount ) {
				$has_met_min_amount = true;
			}
		}

		switch ( $this->free_shipping_cond ) {
			case 'min_amount':
				$is_available = $has_met_min_amount;
				break;
			case 'coupon':
				$is_available = $has_coupon;
				break;
			case 'both':
				$is_available = $has_met_min_amount && $has_coupon;
				break;
			case 'either':
				$is_available = $has_met_min_amount || $has_coupon;
				break;
			default:
				$is_available = true;
				break;
		}

		return $is_available;
	}
}
