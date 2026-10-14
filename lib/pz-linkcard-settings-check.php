<?php defined('ABSPATH' ) || wp_die; ?>
<div class="pz-page<?php echo $pz_page_active('pz-check' ); ?>" id="pz-check">
	<div class="pz-submit-float"><?php submit_button(); ?></div>
	<h2><?php echo	__('Link Check Settings', 'pz-linkcard' ).$help_open.'link-check'.$help_close; ?></h2>
	<table class="form-table">
		<tr>
			<th scope="row" colspan="2"><?php esc_html_e('Set No-Follow', 'pz-linkcard' ); ?></th>
			<td>
				<label>
					<input type="hidden"   name="properties[flg-nofollow]" value="" />
					<input type="checkbox" name="properties[flg-nofollow]" value="1" <?php checked($this->options['flg-nofollow'] ); ?> />
					<?php	echo __('In the case of an external site, it puts the "nofollow".', 'pz-linkcard' ).__('(Deprecation)', 'pz-linkcard' ); ?>
				</label>
			</td>
		</tr>
		<tr>
			<th scope="row" colspan="2"><?php esc_html_e('Set No-Opener', 'pz-linkcard' ); ?></th>
			<td>
				<label>
					<input type="hidden"   name="properties[flg-noopener]" value="" />
					<input type="checkbox" name="properties[flg-noopener]" value="1" <?php checked($this->options['flg-noopener'] ); ?> />
					<?php	echo __('In the case of an external site, it puts the "noopener".', 'pz-linkcard' ).__('(Recommended)', 'pz-linkcard' ); ?>
				</label>
			</td>
		</tr>
		<tr>
			<th scope="row" colspan="2"><?php esc_html_e('Relative URL', 'pz-linkcard' ); ?></th>
			<td>
				<label>
					<input type="hidden"   name="properties[flg-relative-url]" value="" />
					<input type="checkbox" name="properties[flg-relative-url]" value="1" <?php checked($this->options['flg-relative-url'] ); ?> />
					<?php esc_html_e('For relative-specified URLs, complement the site URL.', 'pz-linkcard' ); ?>
				</label>
			</td>
		</tr>
		<tr>
			<th scope="row" rowspan="2"><?php esc_html_e('When Not Found', 'pz-linkcard' ); ?></th>
			<th scope="row"><?php esc_html_e('Disable Link', 'pz-linkcard' ); ?></th>
			<td>
				<label>
					<input type="hidden"   name="properties[flg-unlink]" value="" />
					<input type="checkbox" name="properties[flg-unlink]" value="1" <?php checked($this->options['flg-unlink'] ); ?> />
					<?php esc_html_e('Unlink when the access status is "403", "404", or "410".', 'pz-linkcard' ); ?>
				</label>
			</td>
		</tr>
		<tr>
			<th scope="row"><?php esc_html_e('Border', 'pz-linkcard' ); ?></th>
			<td>
				<?php esc_html_e('The settings are located on the “Display” tab.', 'pz-linkcard' ); ?>
			</td>
		</tr>
		<tr>
			<th scope="row" colspan="2"><?php esc_html_e('SSL Certificate Verification', 'pz-linkcard' ); ?></th>
			<td>
				<label>
					<input type="hidden"   name="properties[flg-sslverify]" value="" />
					<input type="checkbox" name="properties[flg-sslverify]" value="1" <?php checked($this->options['flg-sslverify'] ); ?> />
					<?php echo __('Verify the certificate when using SSL/TLS communication.', 'pz-linkcard' ).__('(Recommended)', 'pz-linkcard' ); ?>
				</label>
			</td>
		</tr>
		<tr>
			<th scope="row" colspan="2"><?php esc_html_e('Refer to robots.txt', 'pz-linkcard' ); ?></th>
			<td>
				<label>
					<input type="hidden"   name="properties[flg-robots]" value="" />
					<input type="checkbox" name="properties[flg-robots]" value="1" <?php checked($this->options['flg-robots'] ); ?> />
					<?php echo __('Follow robots.txt when retrieving external link information.', 'pz-linkcard' ).__('(Recommended)', 'pz-linkcard' ); ?>
				</label>
			</td>
		</tr>
		<tr>
			<th scope="row" colspan="2"><?php esc_html_e('Block local IP addresses', 'pz-linkcard' ); ?></th>
			<td>
				<label>
					<input type="hidden"   name="properties[flg-local-check]" value="" />
					<input type="checkbox" name="properties[flg-local-check]" value="1" <?php checked($this->options['flg-local-check'] ); ?> />
					<?php echo __('Blocking local IP addresses prevents SSRF.', 'pz-linkcard' ).__('(Recommended)', 'pz-linkcard' ); ?>
				</label>
			</td>
		</tr>
		<tr>
			<th scope="row" colspan="2"><?php esc_html_e('Follow Location', 'pz-linkcard' ); ?></th>
			<td>
				<label>
					<input type="hidden"   name="properties[flg-redir]" value="" />
					<input type="checkbox" name="properties[flg-redir]" value="1" <?php checked($this->options['flg-redir'] ); ?> />
					<?php esc_html_e('Track when the link destination is redirected.', 'pz-linkcard' ); ?>
				</label>
			</td>
		</tr>
		<tr>
			<th scope="row" colspan="2"><?php esc_html_e('Use User-Agent', 'pz-linkcard' ); ?></th>
			<td>
				<select name="properties[user-agent]" class="pz-sync pz-user-agent">
					<?php foreach (LIST_USER_AGENT as $key => $value ) { ?>
						<option value="<?php echo esc_attr($key ); ?>" title="<?php echo esc_attr($value ); ?>" <?php selected($key, $this->options['user-agent'] ); ?>><?php echo esc_html($value ); ?></option>
					<?php } ?>
				</select>
			</td>
		</tr>
		<tr id="pz-click-count-settings">
			<th scope="row" rowspan="3"><?php esc_html_e('Click Count', 'pz-linkcard' ); ?></th>
			<th scope="row"><?php esc_html_e('Count', 'pz-linkcard' ); ?></th>
			<td>
				<label>
					<input type="hidden"   name="properties[flg-click-count]" value="" />
					<input type="checkbox" name="properties[flg-click-count]" value="1" class="pz-sync-check" <?php checked($this->options['flg-click-count'] ); ?> />
					<?php esc_html_e('Record the number of times clicked.', 'pz-linkcard' ); ?>
				</label>
			</td>
		</tr>
		<tr>
			<th scope="row"><?php esc_html_e('Record', 'pz-linkcard' ); ?></th>
			<td>
				<label>
					<input type="hidden"   name="properties[flg-click-log]" value="" />
					<input type="checkbox" name="properties[flg-click-log]" value="1" <?php checked($this->options['flg-click-log'] ); ?> />
					<?php esc_html_e('Record click details.', 'pz-linkcard' ); ?>
				</label>
			</td>
		</tr>
		<tr id="pz-click-log-retention-settings">
			<th scope="row"><?php esc_html_e('Retention Period', 'pz-linkcard' ); ?></th>
			<td>
				<select name="properties[click-log-retention]">
					<option value="1w" <?php selected('1w', $this->options['click-log-retention'] ); ?>><?php esc_html_e('1 week', 'pz-linkcard' ); ?></option>
					<option value="1m" <?php selected('1m', $this->options['click-log-retention'] ); ?>><?php esc_html_e('1 month', 'pz-linkcard' ); ?></option>
					<option value="2m" <?php selected('2m', $this->options['click-log-retention'] ); ?>><?php esc_html_e('2 months', 'pz-linkcard' ); ?></option>
					<option value="3m" <?php selected('3m', $this->options['click-log-retention'] ); ?>><?php esc_html_e('3 months', 'pz-linkcard' ); ?></option>
					<option value="6m" <?php selected('6m', $this->options['click-log-retention'] ); ?>><?php esc_html_e('6 months', 'pz-linkcard' ); ?></option>
					<option value="1y" <?php selected('1y', $this->options['click-log-retention'] ); ?>><?php esc_html_e('1 year', 'pz-linkcard' ); ?></option>
					<option value="2y" <?php selected('2y', $this->options['click-log-retention'] ); ?>><?php esc_html_e('2 years (long-term)', 'pz-linkcard' ); ?></option>
					<option value="3y" <?php selected('3y', $this->options['click-log-retention'] ); ?>><?php esc_html_e('3 years (long-term)', 'pz-linkcard' ); ?></option>
					<option value="5y" <?php selected('5y', $this->options['click-log-retention'] ); ?>><?php esc_html_e('5 years (long-term)', 'pz-linkcard' ); ?></option>
					<option value="forever" <?php selected('forever', $this->options['click-log-retention'] ); ?>><?php esc_html_e('Permanent (not recommended)', 'pz-linkcard' ); ?></option>
				</select>
				<p class="description"><?php esc_html_e('Detailed click logs older than the retention period are deleted daily after 4:00 AM. The cumulative click count is retained.', 'pz-linkcard' ); ?></p>
				<p class="description"><?php esc_html_e('Long-term retention increases database usage according to the number of clicks.', 'pz-linkcard' ); ?></p>
				<p>
					<button type="submit" name="action" value="delete-expired-click-logs" class="button"><?php esc_html_e('Delete expired logs now', 'pz-linkcard' ); ?></button>
					<button type="submit" name="action" value="delete-all-click-logs" class="button" onclick="return confirm('<?php echo esc_js(__('Delete all detailed click logs? The cumulative click count will not be reset.', 'pz-linkcard' ) ); ?>');"><?php esc_html_e('Delete all logs', 'pz-linkcard' ); ?></button>
				</p>
			</td>
		</tr>
		<tr>
			<th scope="row" rowspan="3"><?php esc_html_e('Broken Link', 'pz-linkcard' ); ?></th>
			<th scope="row"><?php esc_html_e('Number', 'pz-linkcard' ); ?></th>
			<td>
				<label>
					<input type="hidden"   name="properties[flg-alive-count]" value="" />
					<input type="checkbox" name="properties[flg-alive-count]" value="1" <?php checked($this->options['flg-alive-count'] ); ?> />
					<?php esc_html_e('The number of broken links is displayed next to the submenu.', 'pz-linkcard' ); ?>
				</label>
			</td>
		</tr>
		<tr>
			<th scope="row"><?php esc_html_e('Inspection', 'pz-linkcard' ); ?></th>
			<td>
				<label>
					<input type="hidden"   name="properties[flg-alive]" value="" />
					<input type="checkbox" name="properties[flg-alive]" value="1" <?php checked($this->options['flg-alive'] ); ?> />
					<?php esc_html_e('Alive confirmation of the link destination.', 'pz-linkcard' ); ?>
				</label>
			</td>
		</tr>
		<tr>
			<th scope="row"><?php esc_html_e('Recurrence', 'pz-linkcard' ); ?></th>
			<td>
				<?php esc_html_e('The settings are located on the “Advanced” tab.', 'pz-linkcard' ); ?>
			</td>
		</tr>
	</table>
	<?php submit_button(); ?>
</div>
