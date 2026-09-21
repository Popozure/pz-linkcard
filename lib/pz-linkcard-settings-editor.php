<?php defined('ABSPATH' ) || wp_die; ?>
<div class="pz-page<?php echo $pz_page_active('pz-editor' ); ?>" id="pz-editor">
	<div class="pz-submit-float"><?php submit_button(); ?></div>
	<h2><?php echo	__('Convert Settings', 'pz-linkcard' ).$help_open.'editor'.$help_close; ?></h2>
	<table class="form-table">
		<tr>
			<th scope="row"><?php esc_html_e('Convert from Text Link', 'pz-linkcard' ); ?></th>
			<td>
				<label>
					<input type="hidden"   name="properties[auto-atag]" value="" />
					<input type="checkbox" name="properties[auto-atag]" value="1" <?php checked($prop['auto-atag'] ); ?> class="pz-sync-check" />
					<?php esc_html_e('Convert lines with text link only to Linkcard.', 'pz-linkcard' ); ?>
				</label>
			</td>
		</tr>
		<tr>
			<th scope="row"><?php esc_html_e('Convert from URL', 'pz-linkcard' ); ?></th>
			<td>
				<label>
					<input type="hidden"   name="properties[auto-url]" value="" />
					<input type="checkbox" name="properties[auto-url]" value="1" <?php checked($prop['auto-url'] ); ?> class="pz-sync-check" />
					<?php esc_html_e('Convert lines with URL only to Linkcard.', 'pz-linkcard' ); ?>
				</label>
			</td>
		</tr>
		<tr>
			<th scope="row"><?php esc_html_e('Do Shortcode', 'pz-linkcard' ); ?></th>
			<td>
				<label>
					<input type="hidden"   name="properties[flg-do-shortcode]" value="" />
					<input type="checkbox" name="properties[flg-do-shortcode]" value="1" <?php checked($prop['flg-do-shortcode'] ); ?> />
					<?php esc_html_e('Force shortcode development.', 'pz-linkcard' ); ?>
				</label>
			</td>
		</tr>
		<tr>
			<th scope="row"><?php esc_html_e('External Link Only', 'pz-linkcard' ); ?></th>
			<td>
				<label>
					<input type="hidden"   name="properties[auto-external]" value="" />
					<input type="checkbox" name="properties[auto-external]" value="1" <?php checked($prop['auto-external'] ); ?> />
					<?php esc_html_e('Convert only external links.', 'pz-linkcard' ); ?>
				</label>
			</td>
		</tr>
		<tr>
			<th scope="row"><?php esc_html_e('Exclude URL', 'pz-linkcard' ); ?></th>
			<td>
				<textarea name="properties[exclude-url]" rows="5" cols="80" class="large-text code"><?php echo	esc_textarea($this->options['exclude-url'] ); ?></textarea>
			</td>
		</tr>
	</table>
	<?php submit_button(); ?>

	<h2><?php echo	__('Editor Settings', 'pz-linkcard' ).$help_open.'editor'.$help_close; ?></h2>
	<table class="form-table">
		<tr>
			<th scope="row"><?php esc_html_e('Add Block', 'pz-linkcard' ); ?></th>
			<td>
				<label>
					<input type="hidden"   name="properties[flg-edit-block]" value="0" />
					<input type="checkbox" name="properties[flg-edit-block]" value="1" <?php checked($prop['flg-edit-block'] ); ?> />
					<?php esc_html_e('Add block to block editor.', 'pz-linkcard' ); ?>
				</label>
			</td>
		</tr>
		<tr>
			<th scope="row"><?php esc_html_e('Add Insert Button', 'pz-linkcard' ); ?></th>
			<td>
				<label>
					<input type="hidden"   name="properties[flg-edit-insert]" value="" />
					<input type="checkbox" name="properties[flg-edit-insert]" value="1" <?php checked($prop['flg-edit-insert'] ); ?> />
					<?php esc_html_e('Add insert button to visual editor.', 'pz-linkcard' ); ?>
				</label>
			</td>
		</tr>
		<tr>
			<th scope="row"><?php esc_html_e('Add Quick Tag', 'pz-linkcard' ); ?></th>
			<td>
				<label>
					<input type="hidden"   name="properties[flg-edit-qtag]" value="" />
					<input type="checkbox" name="properties[flg-edit-qtag]" value="1" <?php checked($prop['flg-edit-qtag'] ); ?> />
					<?php esc_html_e('Add quick tag button to text editor.', 'pz-linkcard' ); ?>
				</label>
			</td>
		</tr>
		<tr>
			<th scope="row"><?php esc_html_e('Clear Excerpt', 'pz-linkcard' ); ?></th>
			<td>
				<label>
					<input type="hidden"   name="properties[flg-clear-excerpt]" value="" />
					<input type="checkbox" name="properties[flg-clear-excerpt]" value="1" <?php checked($prop['flg-clear-excerpt'] ); ?> />
					<?php esc_html_e('If TITLE parameter is specified, EXCERPT is also cleared.', 'pz-linkcard' ); ?>
				</label>
			</td>
		</tr>
		</table>
	<?php submit_button(); ?>

	<h2><?php echo	__('Shortcode Settings', 'pz-linkcard' ).$help_open.'editor'.$help_close; ?></h2>
	<table class="form-table pz-editor-shortcode-table">
		<tr>
			<th scope="row"><?php esc_html_e('ShortCode 1', 'pz-linkcard' ); ?></th>
			<td>[<input name="properties[code1]" type="text" class="pz-shortcode pz-shortcode-1" value="<?php echo	esc_attr($prop['code1'] ); ?>" /> url="http://popozure.info" <span class="pz-shortcode-title"><span class="pz-shortcode-parameter">title</span>="xxxxxx"</span> <span class="pz-shortcode-content"><span class="pz-shortcode-parameter">content</span>="xxxxxx"</span>]<p><?php esc_html_e('Case-sensitive', 'pz-linkcard' ); ?></p></td>
		</tr>
		<tr>
			<th scope="row"><?php esc_html_e('Use inline text', 'pz-linkcard' ); ?></th>
			<td>
				[<span class="pz-shortcode-copy"><?php echo	esc_attr($prop['code1'] ); ?></span> url="http://xxx"]
				<select name="properties[use-inline]" class="pz-shortcode-enabled">
					<option value=""	<?php selected($prop['use-inline'] == ''  ); ?>><?php esc_html_e('Do not use',		'pz-linkcard' ); ?></option>
					<option value="1"	<?php selected($prop['use-inline'] == '1' ); ?>><?php esc_html_e('Use as excerpt',	'pz-linkcard' ); ?></option>
					<option value="2"	<?php selected($prop['use-inline'] == '2' ); ?>><?php esc_html_e('Use as title',	'pz-linkcard' ); ?></option>
				</select>
				[/<span class="pz-shortcode-copy"><?php echo	esc_attr($prop['code1'] ); ?></span>]
				<p><?php esc_html_e('This setting applies only to the Shortcode1', 'pz-linkcard' ); ?></p></td>
			</td>
		</tr>
		<tr>
			<th scope="row"><?php esc_html_e('ShortCode 2', 'pz-linkcard' ); ?></th>
			<td>[<input name="properties[code2]" type="text" class="pz-shortcode" value="<?php echo	esc_attr($prop['code2'] ); ?>" /> url="http://popozure.info" <span class="pz-shortcode-title"><span class="pz-shortcode-parameter">title</span>="xxxxxx"</span> <span class="pz-shortcode-content"><span class="pz-shortcode-parameter">content</span>="xxxxxx"</span>]<p><?php esc_html_e('Case-sensitive', 'pz-linkcard' ); ?></p></td>
		</tr>
		<tr>
			<th scope="row"><?php esc_html_e('ShortCode 3', 'pz-linkcard' ); ?></th>
			<td>[<input name="properties[code3]" type="text" class="pz-shortcode" value="<?php echo	esc_attr($prop['code3'] ); ?>" /> url="http://popozure.info" <span class="pz-shortcode-title"><span class="pz-shortcode-parameter">title</span>="xxxxxx"</span> <span class="pz-shortcode-content"><span class="pz-shortcode-parameter">content</span>="xxxxxx"</span>]<p><?php esc_html_e('Case-sensitive', 'pz-linkcard' ); ?></p></td>
		</tr>
		<tr>
			<th scope="row"><?php esc_html_e('Example Entry', 'pz-linkcard' ); ?></th>
			<td>
				<p><?php echo __('ex1.', 'pz-linkcard' ).'&ensp;'.__('Specify only URL parameters.', 'pz-linkcard' ); ?><div class="pz-shortcode-example pz-click-all-select">[<span class="pz-shortcode-copy"><?php echo esc_attr($prop['code1'] ); ?></span> url="https://xxx"]</div></p>
				<p><?php echo __('ex2.', 'pz-linkcard' ).'&ensp;'.__('Specify URL and title parameters.', 'pz-linkcard' ); ?><div class="pz-shortcode-example pz-click-all-select">[<span class="pz-shortcode-copy"><?php echo esc_attr($prop['code1'] ); ?></span> url="https://xxx" <span class="pz-shortcode-title"><span class="pz-shortcode-parameter">title</span>="xxxxxx"</span>]</div></p>
				<p><?php echo __('ex3.', 'pz-linkcard' ).'&ensp;'.__('Specify URL, title and content parameters.', 'pz-linkcard' ); ?><div class="pz-shortcode-example pz-click-all-select">[<span class="pz-shortcode-copy"><?php echo esc_attr($prop['code1'] ); ?></span> url="https://xxx" <span class="pz-shortcode-title"><span class="pz-shortcode-parameter">title</span>="xxxxxx"</span> <span class="pz-shortcode-content"><span class="pz-shortcode-parameter">content</span>="xxxxxx"</span>]</div></p>
				<p><?php esc_html_e('For any shortcode you can change the title and excerpt with `title` parameter and `content` parameter', 'pz-linkcard' ); ?></p>
			</td>
		</tr>
	</table>
	<?php submit_button(); ?>
</div>
