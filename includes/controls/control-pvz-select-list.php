<p class="cdekfw-pvz-block">
	<label for="cdekfw-pvz-code">
		<?php esc_html_e( 'Pick-up point', 'cdek-for-woocommerce' ); ?>
	</label>
	<select name="cdekfw-pvz-code" id="cdekfw-pvz-code"
			data-noresults="<?php esc_attr_e( 'Address is not found', 'cdek-for-woocommerce' ); ?>">
		<?php foreach ( $pvz as $item ) : ?>
			<option value="<?php echo esc_attr( $item['code'] ); ?>|<?php echo esc_attr( $item['address'] ); ?>" <?php selected( $item['code'] . '|' . $item['address'], $selected_pvz ); ?>><?php echo esc_attr( $item['address'] ); ?></option>
		<?php endforeach; ?>
	</select>
</p>

<?php if ( CDEKFW::is_pro_active() && get_option( 'cdek_pro_yandex_api' ) ) : ?>
	<?php
	$pvz_map_object = array(
		'type'     => 'FeatureCollection',
		'features' => array(),
	);

	foreach ( $pvz as $item ) {
		$pvz_map_object['features'][] = array(
			'type'       => 'Feature',
			'id'         => $item['code'],
			'geometry'   => array(
				'type'        => 'Point',
				'coordinates' => explode( ',', $item['coordinates'] ),
			),
			'properties' => array(
				'balloonContentHeader' => $item['name'],
				'balloonContentBody'   => '<a href="#" style="text-decoration:underline" onclick="cdekfwSetPvzFromBaloon(' . esc_attr( '"' . $item['code'] . '|' . $item['address'] . '"' ) . '); return false">' . esc_attr__( 'Select a pick-up point', 'cdek-for-woocommerce' ) . '</a>',
				'balloonContentFooter' => $item['address'],
			),
		);
	}
	?>
	<small>
		<?php $map_center = explode( ',', $pvz[0]['coordinates'] ); ?>
		<a href="#" id="cdekfw-map-trigger" data-map-center="<?php echo esc_attr( wp_json_encode( $map_center ) ); ?>">
			<?php esc_html_e( 'Select a pick-up point on the map', 'cdek-for-woocommerce' ); ?>
		</a>
	</small>

	<script>
		var cdekfwYandexMapData =  <?php echo wp_json_encode( $pvz_map_object, JSON_UNESCAPED_UNICODE ); ?>;
	</script>
	<?php
endif;
