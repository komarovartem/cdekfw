<p class="cdekfw-pvz-block">
	<label for="cdekfw-pvz-code">
		<?php esc_html_e( 'Pick-up point', 'cdek-for-woocommerce' ); ?>
	</label>
	<select name="cdekfw-pvz-code" id="cdekfw-pvz-code" data-noresults="<?php esc_attr_e( 'Address is not found', 'cdek-for-woocommerce' ); ?>">
		<?php foreach ( $pvz as $pvz_code => $address ) : ?>
			<option value="<?php echo esc_attr( $pvz_code ); ?>|<?php echo esc_attr( $address ); ?>" <?php selected( $pvz_code, $selected_pvz ); ?>><?php echo esc_attr( $address ); ?></option>
		<?php endforeach; ?>
	</select>
</p>

<?php if ( get_option( 'cdekfw_yandex_api' ) ) : ?>
	<small>
		<a href="#" id="rpaefw-ekom-map-trigger">
			<?php esc_html_e( 'Select a pick-up point on the map', 'russian-post-and-ems-pro-for-woocommerce' ); ?>
		</a>
	</small>

	<script>
		var rpaefwYandexMapData =  <?php echo wp_json_encode( $pvz_map_object, JSON_UNESCAPED_UNICODE ); ?>;

		(function () {
			if (typeof rpaefwYandexMap === "undefined") {
				jQuery('#rpaefw-ekom-map-trigger').hide();
			}

			jQuery('#rpaefw-ekom-map-trigger').click(function () {
				jQuery('#rpaefw-yandex-map-wrapper').css('display', 'flex');
				rpaefwYandexMap.setCenter(<?php echo wp_json_encode( $map_coordinates ); ?>);
				rpaefwYandexObjectManager.removeAll();
				rpaefwYandexObjectManager.add(rpaefwYandexMapData);
				return false;
			});
		})();
	</script>
	<?php
endif;
