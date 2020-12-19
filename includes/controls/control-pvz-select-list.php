<?php
/**
 * CDEK PVZ select list
 *
 * @package CDEK/Controls/PVZ
 * @since   1.0.0
 *
 * @var array $pvz_list The item being displayed
 * @var string $selected_pvz selected pvz from checkout
 */

?>

<div class="cdekfw-pvz-block">
	<label for="cdekfw-pvz-code">
		<?php esc_html_e( 'Pick-up point', 'cdek-for-woocommerce' ); ?>
	</label>
	<select name="cdekfw-pvz-code" id="cdekfw-pvz-code"
			data-noresults="<?php esc_attr_e( 'Address is not found', 'cdek-for-woocommerce' ); ?>">
		<?php foreach ( $pvz_list as $item ) : ?>
			<?php $pvz_value = $item['code'] . '|' . $item['address'] . '|' . $item['city_code']; ?>
            <?php $item['address'] = $item['fullAddress'] ? $item['fullAddress'] : $item['address']; ?>
			<option value="<?php echo esc_attr( $pvz_value ); ?>" <?php selected( $pvz_value, $selected_pvz ); ?> data-search-term="<?php echo esc_attr( mb_strtolower( $item['name'] . ' ' . $item['address'] . ' ' . $item['nearest_station'] ) ); ?>"><?php
				echo esc_attr( $item['address'] );
			?></option>
		<?php endforeach; ?>
	</select>
</div>

<?php do_action( 'cdek_pvz_block_after', $pvz_list, $selected_pvz ); ?>
