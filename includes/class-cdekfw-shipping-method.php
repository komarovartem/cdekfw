<?php
/**
 * CDEK Shipping Method.
 *
 * @version 1.0.0
 * @package CDEK/Shipping
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
		$label        = $this->title;
		$time         = '';
		$to_code      = '';
		$rate         = array();
		$tariff_list  = $this->get_tariff_list();
		$services     = $this->services ? $this->services : array();
		$from_code    = intval( get_option( 'cdek_sender_city_code' ) );
		$from_country = get_option( 'woocommerce_default_country', 'RU' );
		$from_country = $from_country ? explode( ':', $from_country )[0] : 'RU';
		$to_country   = $package['destination']['country'] ? $package['destination']['country'] : 'RU';
		$to_postcode  = wc_format_postcode( $package['destination']['postcode'], $to_country );
		$to_state     = $package['destination']['state'];
		$to_city      = $package['destination']['city'];

		if ( $this->check_condition_for_disable( $package ) ) {
			return;
		}

		if ( ! $from_code ) {
			CDEKFW::log_it( __( 'The CDEK city code field is empty.', 'cdek-for-woocommerce' ), 'error' );
			// translators: %s html links.
			$this->maybe_print_error( esc_html__( 'The CDEK city code field is empty. Please fill the city code in the main plugin settings to calculate the shipping.', 'cdek-for-woocommerce' ) );

			return;
		}

		if ( 'RU' === $to_country ) {
			$to_code = CDEKFW_Helper::get_city_code( $to_state, $to_city, $to_postcode );

			if ( ! $to_code ) {
				return;
			}
		}

		$ordered_value = 'yes' === $this->remove_declared_value ? 0 : WC()->cart->get_cart_contents_total();

		$args = array(
			'from_location' => array(
				'code' => $from_code,
			),
			'to_location'   => array(
				'code' => $to_code,
			),
			'packages'      => $this->get_goods_dimensions( $package, $services ),
			// 'services'      => CDEKFW_Helper::get_services_for_shipping_calculation( $services, $ordered_value ),
		);

		if ( 'RU' !== $to_country ) {
			$to_code = CDEKFW_Helper::get_international_city_id( $to_country );
			// Get pvz list for tariffs which are related to warehouses.
			if ( in_array(
				$this->tariff,
				CDEKFW_PVZ_Shipping::get_warehouse_tariffs(),
				true
			) ) {
				$pvz_list          = CDEKFW_Client::get_pvz_list( WC()->customer );
				$selected_pvz      = CDEKFW_PVZ_Shipping::get_selected_pvz_code();
				$selected_pvz_code = $selected_pvz ? explode( '|', $selected_pvz )[0] : false;

				if ( $pvz_list ) {
					if ( $selected_pvz_code && array_key_exists( $selected_pvz_code, $pvz_list ) ) {
						$to_code = intval( explode( '|', $selected_pvz )[2] );
					} else {
						$to_code = current( $pvz_list )['city_code'];
					}
				} else {
					// Do nothing since no pvz were found.
					return;
				}
			}

			$args['to_location']['code'] = $to_code;
		}

		$shipping_rates = CDEKFW_Client::calculate_rate( $args );

		if ( ! $shipping_rates ) {
			$this->maybe_print_error();

			return;
		}

		if ( isset( $shipping_rates['errors'] ) ) {
			$this->maybe_print_error( $shipping_rates['errors'][0]['message'] );

			return;
		}

		foreach ( $tariff_list as $tariff ) {
			foreach ( $shipping_rates['tariff_codes'] as $shipping_rate ) {
				if ( $tariff === $shipping_rate['tariff_code'] && ! $rate ) {
					$rate = array(
						'tariff_id'  => intval( $shipping_rate['tariff_code'] ),
						'price'      => ceil( $shipping_rate['delivery_sum'] ),
						'period_max' => intval( $shipping_rate['period_max'] ),
					);
				}
			}
		}

		if ( ! $rate ) {
			CDEKFW::log_it( __( 'For current destination next tariffs available', 'cdek-for-woocommerce' ) . ' - ' . $to_country . ' - ' . $package['destination']['city'] . ' - ' . $args['to_location']['code'] . json_encode( $shipping_rates, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT ), 'error' );

			$this->maybe_print_error( __( 'There are no matching or available tariffs for this destinations.', 'cdek-for-woocommerce' ) );

			return;
		}

		// $this->maybe_print_error( json_encode( $tariff_list, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT ) );

		$shipping_class_cost = ceil( $this->get_shipping_class_cost( $package ) );
		$shipping_price      = intval( $this->fixed_cost ) ? intval( $this->fixed_cost ) : $rate['price'];
		$percentage_cost     = ceil( $this->get_percentage_cost( $shipping_price ) );
		$cost                = $shipping_price + intval( $this->add_cost ) + $shipping_class_cost + $percentage_cost;
		$delivery_time       = $rate['period_max'];

		if ( 'yes' === $this->show_delivery_time ) {
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
						'id'        => $this->get_rate_id(),
						'label'     => $label . $time,
						'taxes'     => false,
						'package'   => $package,
						'cost'      => 0,
						'meta_data' => array(
							'tariff_id' => $rate['tariff_id'],
							'CDEK'      => CDEKFW_Helper::get_tariff_name( $rate['tariff_id'] ),
						),
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
				'id'        => $this->get_rate_id(),
				'label'     => $label . $time,
				'cost'      => $cost,
				'package'   => $package,
				'meta_data' => array(
					'tariff_id' => $rate['tariff_id'],
					'CDEK'      => CDEKFW_Helper::get_tariff_name( $rate['tariff_id'] ),
				),
			)
		);
	}

	/**
	 * Generate tariff list
	 *
	 * @return array
	 */
	public function get_tariff_list() {
		$list    = array();
		$tariffs = array(
			intval( $this->tariff ) ? intval( $this->tariff ) : 1,
			intval( $this->tariff_list_1 ),
			intval( $this->tariff_list_2 ),
			intval( $this->tariff_list_3 ),
		);

		$tariffs = array_filter( $tariffs );

		foreach ( $tariffs as $tariff ) {
			$list[] = $tariff;
		}

		return $list;
	}

	/**
	 * Check all condition to display a method before calculation
	 *
	 * @param array $package Shipping package.
	 *
	 * @return bool
	 */
	public function check_condition_for_disable( $package ) {
		$total_val = WC()->cart->get_cart_contents_total();
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
		if ( isset( $this->cond_has_shipping_class ) ) {
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
	 * Additional percentage cost.
	 *
	 * @param int $shipping_cost Shipping cost.
	 *
	 * @return float|int
	 * @since 1.0.3
	 */
	public function get_percentage_cost( $shipping_cost ) {
		$percentage = floatval( $this->add_percentage_cost ) / 100;
		$type       = $this->add_percentage_cost_type;

		if ( ! $percentage ) {
			return 0;
		}

		switch ( $type ) {
			case 'percentage_shipping_cost':
				return $shipping_cost * $percentage;
			case 'percentage_total':
				return ( WC()->cart->get_subtotal() + WC()->cart->get_fee_total() + $shipping_cost ) * $percentage;
			default:
				return WC()->cart->get_subtotal() * $percentage;
		}
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
	 * @param array $services Method services.
	 *
	 * @return array
	 */
	public function get_goods_dimensions( $package, $services ) {
		$defaults       = CDEKFW_Helper::get_default_dimensions();
		$goods          = array();
		$cart_weight    = wc_get_weight( WC()->cart->get_cart_contents_weight(), 'g' );
		$package_length = ceil( $this->package_length ? $this->package_length : 0 );
		$package_width  = ceil( $this->package_width ? $this->package_width : 0 );
		$package_height = ceil( $this->package_height ? $this->package_height : 0 );

		// If package selected in services.
		if ( in_array( '24', $services, true ) && ! $package_length ) {
			$package_length = 31;
			$package_width  = 22;
			$package_height = 28;
		}

		if ( $package_length && $package_width && $package_height ) {
			$goods[] = array(
				'weight' => intval( $this->add_weight ) + ceil( $cart_weight ),
				'length' => $package_length,
				'width'  => $package_width,
				'height' => $package_height,
			);
		} else {
			foreach ( $package['contents'] as $item_id => $item_values ) {
				if ( ! $item_values['data']->needs_shipping() ) {
					continue;
				}

				$weight = wc_get_weight( floatval( $item_values['data']->get_weight() ), 'g' );
				$length = wc_get_dimension( floatval( $item_values['data']->get_length() ), 'cm' );
				$width  = wc_get_dimension( floatval( $item_values['data']->get_width() ), 'cm' );
				$height = wc_get_dimension( floatval( $item_values['data']->get_height() ), 'cm' );

				for ( $i = 0; $i < $item_values['quantity']; $i ++ ) {
					$goods[] = array(
						'weight' => $weight ? ceil( $weight ) : ceil( $defaults['weight'] ),
						'length' => $length ? ceil( $length ) : ceil( $defaults['length'] ),
						'width'  => $width ? ceil( $width ) : ceil( $defaults['width'] ),
						'height' => $height ? ceil( $height ) : ceil( $defaults['height'] ),
					);
				}
			}

			// Additional weight.
			if ( $this->add_weight ) {
				$goods[] = array(
					'weight' => intval( $this->add_weight ),
					'length' => 1,
					'width'  => 1,
					'height' => 1,
				);
			}
		}

		return $goods;
	}

	/**
	 * Print error for debugging
	 *
	 * @param string $message custom error message.
	 */
	public function maybe_print_error( $message = '' ) {
		if ( ! current_user_can( 'manage_woocommerce' ) ) {
			return;
		}

		$this->add_rate(
			array(
				'id'        => $this->get_rate_id(),
				'label'     => $message ? $this->title . '. ' . $message : $this->title . '. ' . __( 'Error during calculation. This message and method are visible only for the site Administrator for debugging purposes.', 'cdek-for-woocommerce' ),
				'cost'      => 0,
				'meta_data' => array( 'cdek_error' => true ),
			)
		);
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
