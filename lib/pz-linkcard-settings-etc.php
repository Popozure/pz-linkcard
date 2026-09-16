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
		<tr class="pz-debu-only">
			<th scope="row"><?php echo __('CSS File URL', 'pz-linkcard' ); ?></th>
			<td><input name=""							type="text" size="120" title="<?php echo esc_attr(PZLKC_DIR_STYLE.'style.css'     ); ?>" class="pz-click-all-select" value="<?php echo	esc_attr(PZLKC_DIR_STYLE.'style.css'     ); ?>" readonly="readonly" /></td>
		</tr>
		<tr class="pz-debug-only">
			<th scope="row"><?php echo __('CSS File URL', 'pz-linkcard' ).' '.__('(Compressed)', 'pz-linkcard' ); ?></th>
			<td><input name="" 							type="text" size="120" title="<?php echo esc_attr(PZLKC_DIR_STYLE.'style.min.css' ); ?>" class="pz-click-all-select" value="<?php echo	esc_attr(PZLKC_DIR_STYLE.'style.min.css' ); ?>" readonly="readonly" /></td>
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
				<input name="properties[favicon-api]" type="url" size="120" class="pz-click-all-select" value="<?php echo	esc_attr($prop['favicon-api'] ); ?>" />
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
				<p><?php $size = pz_GetDirSize(PZLKC_DIR_CACHE ); echo	__('Used', 'pz-linkcard' ).__(': ', 'pz-linkcard' ).'<span class="pz-monospace">'.pz_GetSizeStringSi($size).' ('.pz_GetStringBytes($size).')'; ?></span></p>
			</td>
		</tr>
		<tr class="pz-debug-only">
			<th scope="row"><?php esc_html_e('Image Cache Directory', 'pz-linkcard' ); ?></th>
			<td>
				<p><input name="" type="text" title="<?php echo PZLKC_DIR_CACHE; ?>" class="pz-click-all-select" value="<?php echo PZLKC_DIR_CACHE; ?>" size="120" readonly="readonly" /></p>
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
					<p><?php $size = pz_GetDirSize(PZLKC_DIR_DEBUG ); echo __('Used', 'pz-linkcard' ).__(': ', 'pz-linkcard' ).'<span class="pz-monospace">'.pz_GetSizeStringSi($size).' ('.pz_GetStringBytes($size).')'; ?></span></p>
					<p><button type="button" name="action" value="clear-log" class="pz-button" onclick="return confirm('<?php echo esc_js(__('(Unimplemented)', 'pz-linkcard' ) ); ?>');"><?php esc_html_e('Clear LOG File', 'pz-linkcard' ); ?></button><?php esc_html_e('(Unimplemented)', 'pz-linkcard' ); ?></p>
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
