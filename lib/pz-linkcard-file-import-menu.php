<?php defined('ABSPATH' ) || wp_die; ?>
<p><a href="<?php echo esc_url($this->cacheman_url ); ?>" class="pz-man-return-button button"><?php esc_html_e('Return to Cache Manager', 'pz-linkcard' ); ?></a></p>

<h2><?php esc_html_e('Import LinkCard Data', 'pz-linkcard' ); ?></h2>
<p><?php esc_html_e('Import files exported from the Pz-LinkCard series and load them into the cache manager.', 'pz-linkcard' ); ?></p>
<table class="pz-man-filemenu" style="width: 100%;">
	<tr style="vertical-align: middle; height: 40px;">
		<td><input type="file" id="import_file" name="import_file" accept=".csv,text/csv" required style="width: 100%;"></td>
	</tr>
	<tr style="vertical-align: middle; height: 40px;">
		<td><label><input type="checkbox" id="import_clear" name="import_clear" value="1"> <?php esc_html_e('Clear all cache entries', 'pz-linkcard' ); ?></label></td>
	</tr>
	<tr style="vertical-align: middle; height: 40px;">
		<td><button type="submit" id="import_button" name="action" value="exec-import" class="pz-man-file-button button button-primary" disabled="disabled" onclick="return confirm('<?php echo esc_js(__('Are you sure?', 'pz-linkcard' ) ); ?>');"><?php esc_html_e('Start the import immediately', 'pz-linkcard' ); ?></button></td>
	</tr>
</table>
