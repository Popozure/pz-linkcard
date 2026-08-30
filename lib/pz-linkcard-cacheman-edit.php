<?php defined('ABSPATH' ) || wp_die; ?>
<!-- Edit form -->
&nbsp;
<div class="pz-man-cache-editor-title"><div class="pz-man-cache-editor-icon"><?php echo __('&#x1f4dd;&#xfe0f;', 'pz-linkcard' ); ?></div>&ensp;<div class="pz-man-cache-editor-text"><?php echo __('Cache Editor', 'pz-linkcard' ); ?></div></div>
<div class="pz-man-cache-editor">
	<table class="wp-list-table" style="line-height: 32px;"><!--  wp-list-table widefat fixed -->
		<tr>
			<td colspan="2" style="text-align: right;">
				<button type="submit" name="action" value="update" class="button button-primary button-large"><?php esc_html_e('Update', 'pz-linkcard' ) ?></button>
				&emsp;
				<button type="submit" name="action" value="cancel" class="button button-large"><?php esc_html_e('Cancel', 'pz-linkcard' ) ?></button>
			</td>
		</tr>
		<tr>
			<th><?php esc_html_e('ID', 'pz-linkcard' ) ?></th>
			<td><input name="data[id]" type="text" value="<?php echo esc_attr($data['id'] ); ?>" size="5" readonly="readonly" /></td>
		</tr>
		<tr>
			<th><?php esc_html_e('URL', 'pz-linkcard' ) ?></th>
			<td><input name="data[url]" type="url" value="<?php echo esc_attr($data['url'] ); ?>" size="80" readonly="readonly" /></td>
		</tr>
		<tr class="pz-debug-only">
			<th><?php esc_html_e('Redirect URL', 'pz-linkcard' ) ?></th>
			<td><input name="data[url_redir]" type="url" value="<?php echo esc_attr($data['url_redir'] ); ?>" size="80" readonly="readonly" /></td>
		</tr>
		<tr class="pz-debug-only">
			<th><?php esc_html_e('Scheme', 'pz-linkcard' ) ?></th>
			<td><input name="data[scheme]" type="text" value="<?php echo esc_attr($data['scheme'] ); ?>" size="80" readonly="readonly" /></td>
		</tr>
		<tr>
			<th><?php esc_html_e('Site Name', 'pz-linkcard' ) ?> (<span style="text-decoration: underline;">1</span>)</th>
			<td><input name="data[site_name]" type="text" value="<?php echo esc_attr($data['site_name'] ); ?>" size="80" accesskey="1" /></td>
		</tr>
		<tr>
			<th><?php esc_html_e('Domain', 'pz-linkcard' ) ?></th>
			<?php if (function_exists('idn_to_utf8' ) && defined('INTL_IDNA_VARIANT_UTS46' ) && substr($data['domain'], 0, 4 ) == 'xn--' ) { ?>
			<td><input name="data[domain]" type="text" value="<?php echo esc_attr($data['domain'] ); ?>" size="40" readonly="readonly" />&nbsp;<input name="data[domain]" type="text" value="<?php echo esc_attr(idn_to_utf8($data['domain'], 0, INTL_IDNA_VARIANT_UTS46 ) ); ?>" size="31" readonly="readonly" /></td>
			<?php } else { ?>
			<td><input name="data[domain]" type="text" value="<?php echo esc_attr($data['domain'] ); ?>" size="80" readonly="readonly" /></td>
			<?php } ?>
		</tr>
		<tr class="pz-debug-only">
			<th><?php esc_html_e('Registered Title', 'pz-linkcard' ) ?></th>
			<td><input name="data[regist_title]" type="text" value="<?php echo esc_attr($data['regist_title'] ); ?>" size="80" readonly="readonly"<?php if ($this->options['admin-mode'] ) { echo ' ondblclick="this.readOnly=false;"'; } ?> /></td>
		</tr>
		<tr>
			<th><?php esc_html_e('Title', 'pz-linkcard' ) ?> (<span style="text-decoration: underline;">2</span>)</th>
			<td><input name="data[title]" type="text" value="<?php echo esc_attr($data['title'] ); ?>" size="80" accesskey="2" /></td>
		</tr>
		<tr class="pz-debug-only">
			<th><?php esc_html_e('Modified Title', 'pz-linkcard' ) ?></th>
			<td><input name="data[mod_title]" type="text" value="<?php echo esc_attr($data['title'] <> $data['regist_title'] ? true : false ); ?>" size="1" readonly="readonly" /></td>
		</tr>
		<tr class="pz-debug-only">
			<th><?php esc_html_e('Registered Excerpt', 'pz-linkcard' ) ?></th>
			<td><textarea name="data[regist_excerpt]" cols="83" rows="5" wrap="soft" readonly="readonly"<?php if ($this->options['admin-mode'] ) { echo ' ondblclick="this.readOnly=false;"'; } ?>><?php echo esc_textarea($data['regist_excerpt'] ); ?></textarea></td>
		</tr>
		<tr>
			<th><?php esc_html_e('Excerpt', 'pz-linkcard' ) ?> (<span style="text-decoration: underline;">3</span>)</th>
			<td><textarea name="data[excerpt]" cols="83" rows="5" wrap="soft" accesskey="3"><?php echo esc_textarea($data['excerpt'] ); ?></textarea></td>
		</tr>
		<tr class="pz-debug-only">
			<th><?php esc_html_e('Modified Excerpt', 'pz-linkcard' ) ?></th>
			<td><input name="data[mod_excerpt]" type="text" value="<?php echo esc_attr($data['excerpt'] <> $data['regist_excerpt'] ? true : false ); ?>" size="1" readonly="readonly" /></td>
		</tr>
		<tr>
			<th><?php esc_html_e('Character Set', 'pz-linkcard' ) ?></th>
			<td><?php echo esc_html($data['regist_charset'] ).'&nbsp;'.__('->', 'pz-linkcard' ); ?>&nbsp;<input name="data[charset]" type="text" value="edit" size="8" readonly="readonly"<?php if ($this->options['admin-mode'] ) { echo ' ondblclick="this.readOnly=false;"'; } ?> /></td>
		</tr>
		<tr>
			<th><?php esc_html_e('Thumbnail URL', 'pz-linkcard' ) ?></th>
			<td>
				<div class="pz-media-url-field">
					<input name="data[thumbnail]" type="url" value="<?php echo esc_attr($data['thumbnail'] ); ?>" size="80" readonly="readonly" ondblclick="this.readOnly=false;" />
					<span class="pz-debug-only"><button type="button" class="button pz-media-select-image" data-target="data[thumbnail]"><?php esc_html_e('Select Image', 'pz-linkcard' ); ?></button></span>
				</div>
			</td>
		</tr>
		<tr>
			<th><?php esc_html_e('Favicon URL', 'pz-linkcard' ) ?></th>
			<td>
				<div class="pz-media-url-field">
					<input name="data[favicon]" type="url" value="<?php echo esc_attr($data['favicon'] ); ?>" size="80" readonly="readonly" ondblclick="this.readOnly=false;" />
					<span class="pz-debug-only"><button type="button" class="button pz-media-select-image" data-target="data[favicon]"><?php esc_html_e('Select Image', 'pz-linkcard' ); ?></button></span>
				</div>
			</td>
		</tr>
		<tr id="update_result">
			<th><?php esc_html_e('Result Code', 'pz-linkcard' ) ?></th>
			<td>
				<input name="data[update_result]" type="text" value="<?php echo esc_attr($data['update_result'] ); ?>" size="1" readonly="readonly"<?php if ($this->options['admin-mode'] ) { echo ' ondblclick="this.readOnly=false;"'; } ?> class="pz-debug-only" />
				&ensp;<?php $rs = intval($data['update_result'] ); echo esc_html($rs.' '.$this->pz_HTTPMessage($rs ) ); ?>
			</td>
		</tr>
		<tr>
			<th><?php esc_html_e('Ignore Failure', 'pz-linkcard' ) ?> (<span style="text-decoration: underline;">4</span>)</th>
			<td>
				<label><input name="data[no_failure]" type="checkbox" value="1" <?php checked(!empty($data['no_failure'] ? true : false ) ); ?> accesskey="4" /><?php esc_html_e('The result code indicates that the URL is inaccessible, but it can actually be accessed.', 'pz-linkcard' ); ?></label>
			</td>
		</tr>
		<tr>
			<th><?php esc_html_e('Post ID', 'pz-linkcard' ) ?></th>
			<td>
				<input name="data[use_post_id1]" type="text" value="<?php echo esc_attr($data['use_post_id1'] ); ?>" size="5" readonly="readonly"<?php if ($this->options['admin-mode'] ) { echo ' ondblclick="this.readOnly=false;"'; } ?> />
				<input name="data[use_post_id2]" type="text" value="<?php echo esc_attr($data['use_post_id2'] ); ?>" size="5" readonly="readonly"<?php if ($this->options['admin-mode'] ) { echo ' ondblclick="this.readOnly=false;"'; } ?> />
				<input name="data[use_post_id3]" type="text" value="<?php echo esc_attr($data['use_post_id3'] ); ?>" size="5" readonly="readonly"<?php if ($this->options['admin-mode'] ) { echo ' ondblclick="this.readOnly=false;"'; } ?> />
				<input name="data[use_post_id4]" type="text" value="<?php echo esc_attr($data['use_post_id4'] ); ?>" size="5" readonly="readonly"<?php if ($this->options['admin-mode'] ) { echo ' ondblclick="this.readOnly=false;"'; } ?> />
				<input name="data[use_post_id5]" type="text" value="<?php echo esc_attr($data['use_post_id5'] ); ?>" size="5" readonly="readonly"<?php if ($this->options['admin-mode'] ) { echo ' ondblclick="this.readOnly=false;"'; } ?> />
				<input name="data[use_post_id6]" type="text" value="<?php echo esc_attr($data['use_post_id6'] ); ?>" size="5" readonly="readonly"<?php if ($this->options['admin-mode'] ) { echo ' ondblclick="this.readOnly=false;"'; } ?> />
			</td>
		</tr>
		<tr>
			<th><?php esc_html_e('SNS', 'pz-linkcard' ) ?></th>
			<td>
				<?php esc_html_e('Tw', 'pz-linkcard' ) ?>:<input name="data[sns_twitter]"  type="text" value="<?php echo esc_attr($data['sns_twitter'] ); ?>" size="5" class="pz-debug-only" readonly="readonly"<?php if ($this->options['admin-mode'] ) { echo ' ondblclick="this.readOnly=false;"'; } ?> /><?php echo esc_attr($data['sns_twitter'] >= 0 ? $data['sns_twitter'] : '-' ); ?>&emsp;
				<?php esc_html_e('fb', 'pz-linkcard' ) ?>:<input name="data[sns_facebook]" type="text" value="<?php echo esc_attr($data['sns_facebook'] ); ?>" size="5" class="pz-debug-only" readonly="readonly"<?php if ($this->options['admin-mode'] ) { echo ' ondblclick="this.readOnly=false;"'; } ?> /><?php echo esc_attr($data['sns_facebook'] >= 0 ? $data['sns_facebook'] : '-' ); ?>&emsp;
				<?php esc_html_e('B!', 'pz-linkcard' ) ?>:<input name="data[sns_hatena]"   type="text" value="<?php echo esc_attr($data['sns_hatena'] ); ?>" size="5" class="pz-debug-only" readonly="readonly"<?php if ($this->options['admin-mode'] ) { echo ' ondblclick="this.readOnly=false;"'; } ?> /><?php echo esc_attr($data['sns_hatena'] >= 0 ? $data['sns_hatena'] : '-' ); ?>
			</td>
		</tr>
		<tr class="pz-debug-only">
			<th><?php esc_html_e('Last SNS Check', 'pz-linkcard' ) ?></th>
			<td><input name="data[sns_time]" type="text" value="<?php echo esc_attr($data['sns_time'] ); ?>" size="8" readonly="readonly"<?php if ($this->options['admin-mode'] ) { echo ' ondblclick="this.readOnly=false;"'; } ?> /><?php echo ' '.$this->pz_Date(PZLKC_DATETIME_FORMAT, $data['sns_time'] ); ?></td>
		</tr>
		<tr class="pz-debug-only">
			<th><?php esc_html_e('Next SNS Check', 'pz-linkcard' ) ?></th>
			<td><input name="data[sns_nexttime]" type="text" value="<?php echo esc_attr($data['sns_nexttime'] ); ?>" size="8" readonly="readonly"<?php if ($this->options['admin-mode'] ) { echo ' ondblclick="this.readOnly=false;"'; } ?> /><?php echo ' '.$this->pz_Date(PZLKC_DATETIME_FORMAT, $data['sns_nexttime'] ); ?></td>
		</tr>
		<tr class="pz-debug-only">
			<th><?php esc_html_e('Registered Character Set', 'pz-linkcard' ) ?></th>
			<td><input name="data[regist_charset]" type="text" value="<?php echo esc_attr($data['regist_charset'] ); ?>" size="8" readonly="readonly"<?php if ($this->options['admin-mode'] ) { echo ' ondblclick="this.readOnly=false;"'; } ?> /></td>
		</tr>
		<tr>
			<th><?php esc_html_e('Registered Date', 'pz-linkcard' ) ?></th>
			<td>
				<input name="data[regist_time]" type="text" value="<?php echo esc_attr($data['regist_time'] ); ?>" size="8"  class="pz-debug-only" readonly="readonly"<?php if ($this->options['admin-mode'] ) { echo ' ondblclick="this.readOnly=false;"'; } ?> />
				<?php echo ' '.$this->pz_Date(PZLKC_DATETIME_FORMAT, $data['regist_time'] ); ?>
			</td>
		</tr>
		<tr class="pz-debug-only">
			<th><?php esc_html_e('Registered Result Code', 'pz-linkcard' ) ?></th>
			<td>
				<input name="data[regist_result]" type="text" value="<?php echo esc_attr($data['regist_result'] ); ?>" size="1" readonly="readonly"<?php if ($this->options['admin-mode'] ) { echo ' ondblclick="this.readOnly=false;"'; } ?> />
				&ensp;<?php $rs = intval($data['regist_result'] ); echo esc_html($rs.' '.$this->pz_HTTPMessage($rs ) ); ?>
			</td>
		</tr>
		<tr class="pz-debug-only">
			<th><?php esc_html_e('Last Alive Check', 'pz-linkcard' ) ?></th>
			<td><input name="data[alive_time]" type="text" value="<?php echo esc_attr($data['alive_time'] ); ?>" size="8" readonly="readonly"<?php if ($this->options['admin-mode'] ) { echo ' ondblclick="this.readOnly=false;"'; } ?> /><?php echo ' '.$this->pz_Date(PZLKC_DATETIME_FORMAT, $data['alive_time'] ); ?></td>
		</tr>
		<tr class="pz-debug-only">
			<th><?php esc_html_e('Next Alive Check', 'pz-linkcard' ) ?></th>
			<td><input name="data[alive_nexttime]" type="text" value="<?php echo esc_attr($data['alive_nexttime'] ); ?>" size="8" readonly="readonly"<?php if ($this->options['admin-mode'] ) { echo ' ondblclick="this.readOnly=false;"'; } ?> /><?php echo ' '.$this->pz_Date(PZLKC_DATETIME_FORMAT, $data['alive_nexttime'] ); ?></td>
		</tr>
		<tr class="pz-debug-only">
			<th><?php esc_html_e('Alive Check Result Code', 'pz-linkcard' ) ?></th>
			<td>
				<input name="data[alive_result]" type="text" value="<?php echo esc_attr($data['alive_result'] ); ?>" size="1" readonly="readonly"<?php if ($this->options['admin-mode'] ) { echo ' ondblclick="this.readOnly=false;"'; } ?> />
				&ensp;<?php $rs = intval($data['alive_result'] ); echo esc_html($rs.' '.$this->pz_HTTPMessage($rs ) ); ?>
			</td>
		</tr>
		<tr>
			<th><?php esc_html_e('Update Date', 'pz-linkcard' ) ?></th>
			<td>
				<input name="data[update_time]" type="text" value="<?php echo esc_attr($data['update_time'] ); ?>" size="8" class="pz-debug-only" readonly="readonly" />
				<?php echo ' '.$this->pz_Date(PZLKC_DATETIME_FORMAT, $data['update_time'] ); ?>
			</td>
		</tr>
		<tr>
			<td colspan="2" style="text-align: right;">
				<button type="submit" name="action" value="update" class="button button-primary button-large"><?php esc_html_e('Update', 'pz-linkcard' ) ?></button>
				&emsp;
				<button type="submit" name="action" value="cancel" class="button button-large"><?php esc_html_e('Cancel', 'pz-linkcard' ) ?></button>
			</td>
		</tr>
	</table>
</div>
