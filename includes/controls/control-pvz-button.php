<?php
/**
 * Control PVZ button for admin page.
 *
 * @package CDEK/Admin
 */

$file = CDEK_ABSPATH . 'includes/lists/pvz.txt'; ?>
<tr valign="top">
	<th scope="row" class="titledesc">
		<label for="<?php echo esc_attr( $data['id'] ); ?>"><?php echo esc_html( $data['title'] ); ?></label>
	</th>
	<td class="forminp">
		<div id="rpaefw_sync_pvz_form">
			<p>
				<?php esc_html_e( 'Date of last update', 'russian-post-and-ems-pro-for-woocommerce' ); ?>
				: <?php echo esc_html( wp_date( 'j F Y H:i:s', filemtime( $file ) ) ); ?>
			</p>
			<br>
			<button type="button" class="button" id="rpaefw_sync_pvz">
				<?php esc_html_e( 'Synchronize Pickup Points', 'russian-post-and-ems-pro-for-woocommerce' ); ?>
			</button>
			<p class="description">
				<?php esc_html_e( 'The list of Pickup Points is required for EKOM shipping type.', 'russian-post-and-ems-pro-for-woocommerce' ); ?>
			</p>
		</div>
		<p id="rpaefw_sync_pvz_response"></p>
	</td>
</tr>

<script>
	jQuery("#rpaefw_sync_pvz").click(function () {
		var resp = jQuery('#rpaefw_sync_pvz_response');

		jQuery('#rpaefw_sync_pvz_form').hide();

		resp.html('<?php esc_attr_e( 'Loading...', 'russian-post-and-ems-pro-for-woocommerce' ); ?>');
		jQuery.post(ajaxurl, {action: "cdek_sync_pvz"}, function (response) {
			resp.html(response.data);
		}).fail(function () {
			alert('<?php esc_attr_e( 'Issue with synchronization of delivery points.', 'russian-post-and-ems-pro-for-woocommerce' ); ?>');
		});

		return false;
	});
</script>