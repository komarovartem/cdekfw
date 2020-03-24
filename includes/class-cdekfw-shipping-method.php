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
	 * Constructor.
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
	 * Init user set variables.
	 */
	public function init() {
		$this->instance_form_fields = include 'settings/settings-shipping-method.php';

		foreach ( $this->instance_form_fields as $key => $settings ) {
			$this->{$key} = $this->get_option( $key );
		}

		add_action( 'woocommerce_update_options_shipping_' . $this->id, array( $this, 'process_admin_options' ) );
	}

	/**
	 * Calculate the shipping costs.
	 *
	 * @param array $package Package of items from cart.
	 */
	public function calculate_shipping( $package = array() ) {
		$from         = get_option( 'cdek_sender_post_code' );
		$country_code = $package['destination']['country'] ? $package['destination']['country'] : 'RU';
		$postal_code  = wc_format_postcode( $package['destination']['postcode'], $country_code );
		$state        = $package['destination']['state'];
		$city         = $package['destination']['city'];

		$rate = array(
			'id'      => $this->get_rate_id(),
			'label'   => $this->title,
			'cost'    => 102,
			'package' => $package,
		);

		$this->add_rate( $rate );
	}
}
