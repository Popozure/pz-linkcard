<?php defined('ABSPATH' ) || wp_die; ?>
<form id="import" name="import" method="post" enctype="multipart/form-data">
	<?php wp_nonce_field('pz-cacheman' ); ?>
	<input type="hidden" name="action" value="import">
	<input type="hidden" name="flg-inhibit" value="<?php echo esc_attr($inhibit ?? 0 ); ?>">
	<table class="pz-man-filemenu">
		<tr>
			<td><input  type="file"   id="import_file"   name="import_file"  accept=".csv" required /></td>
			<td><button type="submit" id="import_button" name="action" value="exec-import" class="pz-man-file-button button button-primary"><?php esc_html_e('Upload Import File', 'pz-linkcard' ); ?></button></td>
			<td><label><input type="checkbox" id="import_clear" name="import_clear" value="1" /><?php esc_html_e('Clear all cache', 'pz-linkcard' ); ?></label></td>
		</tr>
	</table>
</form>
