<?php defined('ABSPATH' ) || wp_die; ?>
<div class="pz-page<?php echo $pz_page_active('pz-etc' ); ?>" id="pz-etc">
	<div class="pz-submit-float"><?php submit_button(); ?></div>
	<h2><?php echo	__('Stylesheet Settings', 'pz-linkcard' ).$help_open.'css'.$help_close; ?></h2>
	<table class="form-table">
		<tr>
			<th scope="row"><?php esc_html_e('Stylesheet URL to Add', 'pz-linkcard' ); ?></th>
			<td><input name="properties[css-add-url]"	type="url"  size="120" title="<?php echo	esc_attr($prop['css-add-url'] ); ?>" value="<?php echo	esc_attr($prop['css-add-url'] ); ?>" /><br><p><?php echo	__('(', 'pz-linkcard' ).__('ex.', 'pz-linkcard' ).' '.$this->home_url.'/style.css '.__(')', 'pz-linkcard' ); ?></p></td>
		</tr>
		<tr>
			<th scope="row"><?php esc_html_e('Stylesheet Text to Add', 'pz-linkcard' ); ?></th>
			<td>
				<textarea name="properties[css-add]" maxlength="1024" class="pz-css-add code"><?php echo	esc_textarea($prop['css-add'] ); ?></textarea>
			</td>
		</tr>
		<tr class="pz-debug-only">
			<th scope="row"><?php esc_html_e('Stylesheet Version', 'pz-linkcard' ); ?></th>
			<td><input name="" type="number" min="0" step="1" size="10" title="<?php echo esc_attr($prop['css-count'] ); ?>" value="<?php echo esc_attr($prop['css-count'] ); ?>" readonly="readonly" /></td>
		</tr>
		<tr>
			<th scope="row"><?php echo __('CSS File URL', 'pz-linkcard' ); ?></th>
			<td><input name=""							type="text" size="120" title="<?php echo esc_attr(PZLKC_URL_STYLE.'style.css'     ); ?>" class="pz-click-all-select" value="<?php echo	esc_attr(PZLKC_URL_STYLE.'style.css'     ); ?>" readonly="readonly" /></td>
		</tr>
		<tr>
			<th scope="row"><?php echo __('CSS File URL', 'pz-linkcard' ).' '.__('(Compressed)', 'pz-linkcard' ); ?></th>
			<td><input name="" 							type="text" size="120" title="<?php echo esc_attr(PZLKC_URL_STYLE.'style.min.css' ); ?>" class="pz-click-all-select" value="<?php echo	esc_attr(PZLKC_URL_STYLE.'style.min.css' ); ?>" readonly="readonly" /></td>
		</tr>
		<tr class="pz-debug-only">
			<th scope="row"><?php esc_html_e('Stylesheet Template File', 'pz-linkcard' ); ?></th>
			<td><input name=""	type="text" size="120" title="<?php echo esc_attr(PZLKC_FILE_TEMPLATE ); ?>" class="pz-click-all-select" value="<?php echo esc_attr(PZLKC_FILE_TEMPLATE ); ?>" readonly="readonly" /></td>
		</tr>

	</table>
	<?php submit_button(); ?>

	<h2><?php echo	__('Web-API Settings', 'pz-linkcard' ).$help_open.'web-api'.$help_close; ?></h2>
	<table class="form-table">
		<tr>
			<th scope="row"><?php esc_html_e('Site Icon API', 'pz-linkcard' ); ?></th>
			<td>
				<input name="properties[siteicon-api]" type="url" size="120" class="pz-click-all-select" value="<?php echo	esc_attr($prop['siteicon-api'] ); ?>" />
				<p><?php echo	__('%DOMAIN% replace to domain name.', 'pz-linkcard' ).' '.__('(', 'pz-linkcard' ).__('ex.', 'pz-linkcard' ).' '.$pz_domain.' '.__(')', 'pz-linkcard' ).'<br>'.__('%DOMAIN_URL% replace to domain URL.', 'pz-linkcard').' '.__('(', 'pz-linkcard' ).__('ex.', 'pz-linkcard' ).' '.$pz_domain_url.' '.__(')', 'pz-linkcard' ).'<br>'.__('%URL% replace to URL.', 'pz-linkcard' ).' '.__('(', 'pz-linkcard' ).__('ex.', 'pz-linkcard' ).' '.$pz_url.self::PLUGIN_PATH.' '.__(')', 'pz-linkcard' ); ?>
				<p><?php esc_html_e('ex1.', 'pz-linkcard' ); ?><input name="" type="text" size="70" class="pz-click-all-select" value="https://www.google.com/s2/favicons?domain=%DOMAIN%" readonly="readonly" /></p>
				<p><?php esc_html_e('ex2.', 'pz-linkcard' ); ?><input name="" type="text" size="70" class="pz-click-all-select" value="https://favicon.hatena.ne.jp/?url=%URL%" readonly="readonly" /></p>
			</td>
		</tr>
		<tr>
			<th scope="row" rowspan="3"><?php esc_html_e('Thumbnail API', 'pz-linkcard' ); ?></th>
			<td>
				<input name="properties[thumbnail-api]" type="url" size="120" class="pz-click-all-select" value="<?php echo	esc_attr($prop['thumbnail-api'] ); ?>" />
				<p><?php echo	__('%URL% replace to URL.', 'pz-linkcard' ).' '.__('(', 'pz-linkcard' ).__('ex.', 'pz-linkcard' ).' '.$pz_url.self::PLUGIN_PATH.' '.__(')', 'pz-linkcard' ); ?></p>
				<p><?php esc_html_e('ex1.', 'pz-linkcard' ); ?><input name="" type="text" size="70" class="pz-click-all-select" value="https://s.wordpress.com/mshots/v1/%URL%?w=200" readonly="readonly" /></p>
				<p><?php esc_html_e('ex2.', 'pz-linkcard' ); ?><input name="" type="text" size="70" class="pz-click-all-select" value="https://capture.heartrails.com/200x200?%URL%" readonly="readonly" /></p>
			</td>
		</tr>
	</table>
	<?php submit_button(); ?>

	<h2><?php echo	__('Image Settings', 'pz-linkcard' ).$help_open.'image'.$help_close; ?></h2>
	<table class="form-table">
		<tr>
			<th scope="row"><?php esc_html_e('Image Cache URL', 'pz-linkcard' ); ?></th>
			<td>
				<p><input name="" type="url" title="<?php echo	PZLKC_URL_CACHE; ?>" class="pz-click-all-select" value="<?php echo PZLKC_URL_CACHE; ?>" size="120" readonly="readonly" /></p>
				<p><?php esc_html_e('Schemes (http and https) are omitted.', 'pz-linkcard' ); ?></p>
				<p><?php echo esc_html(sprintf(__('File Count: %s', 'pz-linkcard' ), number_format_i18n(pz_GetDirFileCount(PZLKC_DIR_CACHE ) ) ) ); ?></p>
				<p><?php $size = pz_GetDirSize(PZLKC_DIR_CACHE ); echo esc_html(sprintf(__('Used: %1$s (%2$s bytes)', 'pz-linkcard' ), pz_GetSizeStringSi($size ), number_format_i18n($size ) ) ); ?></p>
				<p class="pz-image-cache-delete"><button type="submit" name="action" value="delete-all-image-cache" class="pz-button-sure pz-db-clear" disabled="disabled" onclick="return confirm('<?php echo esc_js(__('Delete all image cache files? This action cannot be undone.', 'pz-linkcard' ) ); ?>');"><?php esc_html_e('Delete All', 'pz-linkcard' ); ?></button><label><input id="pz-confirm-delete-image-cache" class="pz-db-clear-enabled" type="checkbox" name="confirm-delete-image-cache" value="1"> <?php esc_html_e('I understand that this data cannot be restored and authorize its deletion.', 'pz-linkcard' ); ?></label></p>
			</td>
		</tr>
		<tr class="pz-debug-only">
			<th scope="row"><?php esc_html_e('Image Cache Directory', 'pz-linkcard' ); ?></th>
			<td>
				<p><input name="" type="text" title="<?php echo PZLKC_DIR_CACHE; ?>" class="pz-click-all-select" value="<?php echo PZLKC_DIR_CACHE; ?>" size="120" readonly="readonly" /></p>
			</td>
		</tr>
	</table>

	<?php
		global	$wpdb;
		$get_table_size = function($table_name ) use ($wpdb ) {
			$table_status	= $wpdb->get_row($wpdb->prepare('SHOW TABLE STATUS LIKE %s', $wpdb->esc_like($table_name ) ) );
			$data_size		= $table_status ? intval($table_status->Data_length ) : 0;
			$index_size		= $table_status ? intval($table_status->Index_length ) : 0;
			$safe_table_name	= str_replace('`', '``', $table_name );
			$record_count	= $table_status ? intval($wpdb->get_var("SELECT COUNT(*) FROM `$safe_table_name`" ) ) : 0;
			return array(
				'count'	=> $record_count,
				'data'	=> $data_size,
				'index'	=> $index_size,
				'total'	=> $data_size + $index_size,
			);
		};
		$card_table_size	= $get_table_size($this->db_card );
		$click_table_size	= $get_table_size($this->db_click );
	?>
	<h2><?php esc_html_e('Database Table Information', 'pz-linkcard' ); ?></h2>
	<table class="form-table pz-linkcard-table-info">
		<tr>
			<th scope="row"><?php esc_html_e('LinkCard Data', 'pz-linkcard' ); ?></th>
			<td>
				<p><input type="text" class="pz-click-all-select" value="<?php echo esc_attr($this->db_card ); ?>" size="48" readonly="readonly"></p>
				<p><?php echo esc_html(sprintf(__('Records: %s', 'pz-linkcard' ), number_format_i18n($card_table_size['count'] ) ) ); ?></p>
				<p><?php echo esc_html(sprintf(__('Total Size: %1$s (%2$s bytes), Index: %3$s (%4$s bytes), Data: %5$s (%6$s bytes)', 'pz-linkcard' ), size_format($card_table_size['total'], 2 ), number_format_i18n($card_table_size['total'] ), size_format($card_table_size['index'], 2 ), number_format_i18n($card_table_size['index'] ), size_format($card_table_size['data'], 2 ), number_format_i18n($card_table_size['data'] ) ) ); ?></p>
				<p class="pz-destructive-action"><button type="submit" name="action" value="delete-all-card-table-rows" class="pz-button-sure pz-db-clear" disabled="disabled" onclick="return confirm('<?php echo esc_js(__('Delete all LinkCard data? This action cannot be undone.', 'pz-linkcard' ) ); ?>');"><?php esc_html_e('Delete All', 'pz-linkcard' ); ?></button><label><input id="pz-confirm-delete-card-table" class="pz-db-clear-enabled" type="checkbox" name="confirm-delete-card-table" value="1"> <?php esc_html_e('I understand that this data cannot be restored and authorize its deletion.', 'pz-linkcard' ); ?></label></p>
			</td>
		</tr>
		<tr>
			<th scope="row"><?php esc_html_e('Click Data', 'pz-linkcard' ); ?></th>
			<td>
				<p><input type="text" class="pz-click-all-select" value="<?php echo esc_attr($this->db_click ); ?>" size="48" readonly="readonly"></p>
				<p><?php echo esc_html(sprintf(__('Records: %s', 'pz-linkcard' ), number_format_i18n($click_table_size['count'] ) ) ); ?></p>
				<p><?php echo esc_html(sprintf(__('Total Size: %1$s (%2$s bytes), Index: %3$s (%4$s bytes), Data: %5$s (%6$s bytes)', 'pz-linkcard' ), size_format($click_table_size['total'], 2 ), number_format_i18n($click_table_size['total'] ), size_format($click_table_size['index'], 2 ), number_format_i18n($click_table_size['index'] ), size_format($click_table_size['data'], 2 ), number_format_i18n($click_table_size['data'] ) ) ); ?></p>
				<p class="pz-destructive-action"><button type="submit" name="action" value="delete-all-click-table-rows" class="pz-button-sure pz-db-clear" disabled="disabled" onclick="return confirm('<?php echo esc_js(__('Delete all click data? This action cannot be undone.', 'pz-linkcard' ) ); ?>');"><?php esc_html_e('Delete All', 'pz-linkcard' ); ?></button><label><input id="pz-confirm-delete-click-table" class="pz-db-clear-enabled" type="checkbox" name="confirm-delete-click-table" value="1"> <?php esc_html_e('I understand that this data cannot be restored and authorize its deletion.', 'pz-linkcard' ); ?></label></p>
			</td>
		</tr>
	</table>

	<div class="pz-debug-only">
		<h2><?php echo	__('Survey Settings', 'pz-linkcard' ); ?></h2>
		<table class="form-table">
			<tr class="pz-debug-only">
				<th scope="row"><?php esc_html_e('Log URL', 'pz-linkcard' ); ?></th>
				<td>
					<p><input name="" type="url" title="<?php echo PZLKC_URL_DEBUG; ?>" class="pz-click-all-select" value="<?php echo PZLKC_URL_DEBUG; ?>" size="120" readonly="readonly" /></p>
					<p><?php esc_html_e('Schemes (http and https) are omitted.', 'pz-linkcard' ); ?></p>
					<p><?php echo esc_html__('File Count', 'pz-linkcard' ).esc_html__(': ', 'pz-linkcard' ).'<span class="pz-monospace">'.esc_html(number_format_i18n(pz_GetDirFileCount(PZLKC_DIR_DEBUG ) ) ).'</span>'; ?></p>
					<p><?php $size = pz_GetDirSize(PZLKC_DIR_DEBUG ); echo __('Used', 'pz-linkcard' ).__(': ', 'pz-linkcard' ).'<span class="pz-monospace">'.pz_GetSizeStringSi($size).' ('.pz_GetStringBytes($size).')'; ?></span></p>
					<p><button type="submit" name="action" value="clear-log" class="pz-button-sure" onclick="return confirm('<?php echo esc_js(__('Are you sure?', 'pz-linkcard' ) ); ?>');"><?php esc_html_e('Delete All', 'pz-linkcard' ); ?></button></p>
				</td>
			</tr>
			<tr class="pz-debug-only">
				<th scope="row"><?php esc_html_e('Log Directory', 'pz-linkcard' ); ?></th>
				<td>
					<p><input name="" type="text" title="<?php echo	PZLKC_DIR_DEBUG; ?>" class="pz-click-all-select" value="<?php echo PZLKC_DIR_DEBUG; ?>" size="120" readonly="readonly" /></p>
				</td>
			</tr>
		</table>
	</div>
</div>
