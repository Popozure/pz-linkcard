<?php defined('ABSPATH' ) || wp_die; ?>
<div class="pz-page<?php echo $pz_page_active('pz-check' ); ?>" id="pz-check">
	<div class="pz-submit-float"><?php submit_button(); ?></div>
	<h2><?php echo	__('Link Check Settings', 'pz-linkcard' ).$help_open.'link-check'.$help_close; ?></h2>
	<table class="form-table">
		<tr>
			<th scope="row"><?php esc_html_e('Set No-Follow', 'pz-linkcard' ); ?></th>
			<td>
				<label>
					<input type="hidden"   name="properties[flg-nofollow]" value="" />
					<input type="checkbox" name="properties[flg-nofollow]" value="1" <?php checked($this->options['flg-nofollow'] ); ?> />
					<?php	echo __('In the case of an external site, it puts the "nofollow".', 'pz-linkcard' ).__('(Deprecation)', 'pz-linkcard' ); ?>
				</label>
			</td>
		</tr>
		<tr>
			<th scope="row"><?php esc_html_e('Set No-Opener', 'pz-linkcard' ); ?></th>
			<td>
				<label>
					<input type="hidden"   name="properties[flg-noopener]" value="" />
					<input type="checkbox" name="properties[flg-noopener]" value="1" <?php checked($this->options['flg-noopener'] ); ?> />
					<?php	echo __('In the case of an external site, it puts the "noopener".', 'pz-linkcard' ).__('(Recommended)', 'pz-linkcard' ); ?>
				</label>
			</td>
		</tr>
		<tr>
			<th scope="row"><?php esc_html_e('Relative URL', 'pz-linkcard' ); ?></th>
			<td>
				<label>
					<input type="hidden"   name="properties[flg-relative-url]" value="" />
					<input type="checkbox" name="properties[flg-relative-url]" value="1" <?php checked($this->options['flg-relative-url'] ); ?> />
					<?php esc_html_e('For relative-specified URLs, complement the site URL.', 'pz-linkcard' ); ?>
				</label>
			</td>
		</tr>
		<tr>
			<th scope="row"><?php esc_html_e('Do Not Link at Error', 'pz-linkcard' ); ?></th>
			<td>
				<label>
					<input type="hidden"   name="properties[flg-unlink]" value="" />
					<input type="checkbox" name="properties[flg-unlink]" value="1" <?php checked($this->options['flg-unlink'] ); ?> />
					<?php esc_html_e('When access status is "403", "404", "410", unlink.', 'pz-linkcard' ); ?>
				</label>
			</td>
		</tr>
		<tr>
			<th scope="row"><?php esc_html_e('Enable SSL Verification', 'pz-linkcard' ); ?></th>
			<td>
				<label>
					<input type="hidden"   name="properties[flg-sslverify]" value="" />
					<input type="checkbox" name="properties[flg-sslverify]" value="1" <?php checked($this->options['flg-sslverify'] ); ?> />
					<?php echo __('Verify the certificate when using SSL/TLS communication.', 'pz-linkcard' ).__('(Recommended)', 'pz-linkcard' ); ?>
				</label>
			</td>
		</tr>
		<tr>
			<th scope="row"><?php esc_html_e('Follow Location', 'pz-linkcard' ); ?></th>
			<td>
				<label>
					<input type="hidden"   name="properties[flg-redir]" value="" />
					<input type="checkbox" name="properties[flg-redir]" value="1" <?php checked($this->options['flg-redir'] ); ?> />
					<?php esc_html_e('Track when the link destination is redirected.', 'pz-linkcard' ); ?>
				</label>
			</td>
		</tr>
		<tr>
			<th scope="row"><?php esc_html_e('Use User-Agent', 'pz-linkcard' ); ?></th>
			<td>
				<label>
					<input type="hidden"   name="properties[flg-agent]" value="" />
					<input type="checkbox" name="properties[flg-agent]" value="1" <?php checked($this->options['flg-agent'] ); ?> class="pz-sync-check" />
					<?php esc_html_e('Notify using Pz-LinkCard to the link destination.', 'pz-linkcard' ); ?>
				</label>
				<p>&emsp;&ensp;<input name="properties[user-agent]" type="text" size="80" value="<?php echo	esc_attr($this->options['user-agent'] ); ?>" /></p>
			</td>
		</tr>
		<tr>
			<th scope="row"><?php esc_html_e('Click Count', 'pz-linkcard' ); ?></th>
			<td>
				<label>
					<input type="hidden"   name="properties[flg-click-count]" value="" />
					<input type="checkbox" name="properties[flg-click-count]" value="1" <?php checked($this->options['flg-click-count'] ); ?> />
				</label>
				<?php esc_html_e('The card management screen displays the total number of clicks to date.', 'pz-linkcard' ); ?>
			</td>
		</tr>
		<tr>
			<th scope="row"><?php esc_html_e('Broken Link Checker', 'pz-linkcard' ); ?></th>
			<td>
				<label>
					<input type="hidden"   name="properties[flg-alive]" value="" />
					<input type="checkbox" name="properties[flg-alive]" value="1" <?php checked($this->options['flg-alive'] ); ?> />
					<?php esc_html_e('Alive confirmation of the link destination.', 'pz-linkcard' ); ?>
				</label>
			</td>
		</tr>
		<tr>
			<th scope="row"><?php esc_html_e('Broken Link Count', 'pz-linkcard' ); ?></th>
			<td>
				<label>
					<input type="hidden"   name="properties[flg-alive-count]" value="" />
					<input type="checkbox" name="properties[flg-alive-count]" value="1" <?php checked($this->options['flg-alive-count'] ); ?> />
					<?php esc_html_e('The number of broken links is displayed next to the submenu.', 'pz-linkcard' ); ?>
				</label>
			</td>
		</tr>
	</table>
	<?php submit_button(); ?>
</div>
