<?php
/**
 * Control PVZ button for admin page.
 *
 * @package CDEK/Admin
 */

?>
<tr valign="top">
	<th scope="row" class="titledesc">
		<label for="<?php echo esc_attr( $data['id'] ); ?>"><?php echo esc_html( $data['title'] ); ?></label>
	</th>
	<td class="forminp">
		<div id="cdekfw_sync_pvz_form">
			<p>
				<?php esc_html_e( 'Date of last update', 'cdek-for-woocommerce' ); ?>
				: <?php echo esc_html( wp_date( 'j F Y H:i:s', filemtime( CDEK_ABSPATH . 'includes/lists/pvz.txt' ) ) ); ?>
			</p>
			<br>
			<button type="button" class="button" id="cdekfw_sync_pvz">
				<?php esc_html_e( 'Synchronize Pickup Points', 'cdek-for-woocommerce' ); ?>
			</button>
			<p class="description">
				<?php esc_html_e( 'The list of Pickup Points is required for EKOM shipping type.', 'cdek-for-woocommerce' ); ?>
			</p>
		</div>
		<p id="cdekfw_sync_pvz_response"></p>
	</td>
</tr>

<script>
	jQuery("#cdekfw_sync_pvz").click(function () {
		var resp = jQuery('#cdekfw_sync_pvz_response');

		jQuery('#cdekfw_sync_pvz_form').hide();

		resp.html('<?php esc_attr_e( 'Loading...', 'cdek-for-woocommerce' ); ?>');
		jQuery.post(ajaxurl, {action: "cdek_sync_pvz"}, function (response) {
			resp.html(response.data);
		}).fail(function () {
			alert('<?php esc_attr_e( 'Issue with synchronization of delivery points.', 'cdek-for-woocommerce' ); ?>');
		});

		return false;
	});
</script>