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
		<tr>
			<th scope="row" colspan="2"><?php esc_html_e('Click Count', 'pz-linkcard' ); ?></th>
			<td>
				<label>
					<input type="hidden"   name="properties[flg-click-count]" value="" />
					<input type="checkbox" name="properties[flg-click-count]" value="1" <?php checked($this->options['flg-click-count'] ); ?> />
				</label>
				<?php esc_html_e('The card management screen displays the total number of clicks to date.', 'pz-linkcard' ); ?>
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
