<?php
defined('ABSPATH' ) || wp_die;

if (empty($skipped_records ) ) {
	return;
}
?>
<div class="notice notice-warning">
	<p><strong><?php esc_html_e('Skipped records', 'pz-linkcard' ); ?></strong></p>
	<table class="widefat striped">
		<thead><tr>
			<th scope="col"><?php esc_html_e('Data row (excluding header)', 'pz-linkcard' ); ?></th>
			<th scope="col"><?php esc_html_e('URL', 'pz-linkcard' ); ?></th>
			<th scope="col"><?php esc_html_e('Reason', 'pz-linkcard' ); ?></th>
		</tr></thead>
		<tbody>
			<?php foreach ($skipped_records as $skipped_record ) : ?>
				<tr>
					<td><?php echo esc_html($skipped_record['row'] ); ?></td>
					<td style="overflow-wrap: anywhere;"><?php echo esc_html($skipped_record['url'] ); ?></td>
					<td style="overflow-wrap: anywhere;"><?php echo esc_html($skipped_record['reason'] ); ?></td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>
