<?php
/**
 * CDEK PVZ select list
 *
 * @package CDEK/Controls
 * @since   1.0.0
 */

?>

<div class="cdekfw-pvz-block">
	<label for="cdekfw-pvz-code">
		<?php esc_html_e( 'Pick-up point', 'cdek-for-woocommerce' ); ?>
	</label>
	<select name="cdekfw-pvz-code" id="cdekfw-pvz-code"
			data-noresults="<?php esc_attr_e( 'Address is not found', 'cdek-for-woocommerce' ); ?>">
		<?php foreach ( $pvz as $item ) : ?>
			<option value="<?php echo esc_attr( $item['code'] ); ?>|<?php echo esc_attr( $item['address'] ); ?>" <?php selected( $item['code'] . '|' . $item['address'], $selected_pvz ); ?>>
				<?php
				if ( $item['fullAddress'] ) {
					echo esc_attr( $item['fullAddress'] );
				} else {
					echo esc_attr( $item['address'] );
				}
				?>
			</option>
		<?php endforeach; ?>
	</select>
</div>

<?php do_action( 'cdek_pvz_block_after', $pvz ); ?>
