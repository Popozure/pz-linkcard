<?php defined('ABSPATH' ) || wp_die; ?>
<div class="pz-page<?php echo $pz_page_active('pz-advanced' ); ?>" id="pz-advanced">
	<div class="pz-submit-float"><?php submit_button(); ?></div>

	<h2><?php echo __('Senior Settings', 'pz-linkcard' ).$help_open.'advanced'.$help_close; ?></h2>
	<table class="form-table">
		<?php
			// 末尾のスラッシュの除去
			$item_name		=	'trail-slash';
			$item_list		=	array(
				''			=>		__('As it',							'pz-linkcard' ),
				'1'			=> 		__('When only domain name, remove',	'pz-linkcard' ),
				'2'			=>		__('Always remove',					'pz-linkcard' ),
			);
			$item_descript		=	__('Trailing Slash',				'pz-linkcard' );
			echo_list($item_name, $prop[$item_name], $item_list, $item_descript, $item_notice );
		?>
		<tr>
			<th scope="row"><?php esc_html_e('Class ID to be Added (for PC)',	'pz-linkcard' ); ?></th>
			<td><input name="properties[class-pc]"			type="text" size="40" value="<?php echo	(isset($this->options['class-pc'] ) ? esc_attr($this->options['class-pc'] ) : '' ); ?>" /></td>
		</tr>
		<tr>
			<th scope="row"><?php esc_html_e('Class ID to be Added (for Mobile)',	'pz-linkcard' ); ?></th>
			<td><input name="properties[class-mobile]"		type="text" size="40" value="<?php echo	(isset($this->options['class-mobile'] ) ? esc_attr($this->options['class-mobile'] ) : '' ); ?>" /><br>
		</tr>
		<tr>
			<th scope="row"><?php esc_html_e('Filter Priority', 'pz-linkcard' ); ?></th>
			<td>
				<label>
					<input name="properties[mce-priority]" type="number" min="0" max="9999" size="80" value="<?php echo esc_attr($this->options['mce-priority'] ); ?>" /><?php esc_html_e('(Null or 0-9999)',  'pz-linkcard' ); ?>
					<?php esc_html_e('Setting a larger value may improve when the insert button does not appear in the editor.', 'pz-linkcard' ); ?>
				</label>
			</td>
		</tr>
		<tr>
			<th scope="row"><?php esc_html_e('Compress', 'pz-linkcard' ); ?></th>
			<td>
				<label>
					<input type="hidden"   name="properties[flg-compress]" value="" />
					<input type="checkbox" name="properties[flg-compress]" value="1" <?php checked($this->options['flg-compress'] ); ?> />
					<?php esc_html_e('Compress CSS and JavaScript to improve access speed.', 'pz-linkcard' ); ?>
				</label>
			</td>
		</tr>
		<tr>
			<th scope="row"><?php esc_html_e('Date Format (Manager)',	'pz-linkcard' ); ?></th>
			<td><input name="properties[date-format-man]"		type="text" size="40" value="<?php echo	(isset($this->options['date-format-man'] ) ? esc_attr($this->options['date-format-man'] ) : '' ); ?>" list="date-dormat-man-default" /></td>
			<datalist id="date-dormat-man-default"><option value="Y\<\b\r\/\>m/d\<\b\r\/\>H:i">Y\<\b\r\/\>m/d\<\b\r\/\>H:i</option><option value="d-M\<\b\r\/\>Y\<\b\r\/\>h:i\<\b\r\/\>a">d-M\<\b\r\/\>Y\<\b\r\/\>h:i\<\b\r\/\>a</option></datalist>
		</tr>
		<tr>
			<th scope="row"><?php esc_html_e('Input Inhibit Time', 'pz-linkcard' ); ?></th>
			<td>
				<label>
					<input type="hidden"   name="properties[flg-inhibit]" value="" />
					<input type="checkbox" name="properties[flg-inhibit]" value="1" <?php checked($this->options['flg-inhibit'] ); ?> />
					<?php esc_html_e('After pressing a button, the screen goes dark to prevent accidental input.', 'pz-linkcard' ); ?>
				</label>
			</td>
		</tr>
	</table>
	<?php submit_button(); ?>

	<h2><?php echo	__('Not Recommended Settings', 'pz-linkcard' ).$help_open.'deprecation'.$help_close; ?></h2>
	<table class="form-table">
		<tr>
			<th scope="row"><?php esc_html_e('Google AMP determination', 'pz-linkcard' ); ?></th>
			<td>
				<p>
					<label>
						<input type="hidden"   name="properties[flg-amp-url]" value="" />
						<input type="checkbox" name="properties[flg-amp-url]" value="1" <?php checked($this->options['flg-amp-url'] ); ?> />
						<?php echo __('Simplified display if the URL ends with "/amp", "/amp/", or "/?amp=1".', 'pz-linkcard' ).__('(Deprecation)', 'pz-linkcard' ); ?>
					</label>
				</p>
			</td>
		</tr>
		<tr>
			<th scope="row"><?php esc_html_e('Hide URL Error', 'pz-linkcard' ); ?></th>
			<td>
				<label>
					<input type="hidden"   name="properties[error-mode-hide]" value="" />
					<input type="checkbox" name="properties[error-mode-hide]" value="1" class="pz-tab-show" <?php checked($this->options['error-mode-hide'] ); ?> />
					<?php echo __('Do not display an error on the admin page.', 'pz-linkcard' ).__('(Deprecation)', 'pz-linkcard' ); ?>
				</label>
			</td>
		</tr>
	</table>
	<?php submit_button(); ?>

	<h2><?php echo	__('Extension Settings', 'pz-linkcard' ).$help_open.'extension'.$help_close; ?></h2>
	<table class="form-table">
		<tr>
			<th scope="row"><?php esc_html_e('File Menu', 'pz-linkcard' ); ?></th>
			<td>
				<label>
					<input type="hidden"   name="properties[flg-filemenu]" value="" />
					<input type="checkbox" name="properties[flg-filemenu]" value="1" <?php checked($this->options['flg-filemenu'] ); ?> />
					<?php esc_html_e('Display the file menu on the card management screen.', 'pz-linkcard' ); ?>
				</label>
			</td>
		</tr>
		<tr>
			<th scope="row"><?php esc_html_e('Admin Bar', 'pz-linkcard' ); ?></th>
			<td>
				<label>
					<input type="hidden"   name="properties[flg-adminbar]" value="" />
					<input type="checkbox" name="properties[flg-adminbar]" value="1" <?php checked($this->options['flg-adminbar'] ?? 0 ); ?> />
					<?php esc_html_e('Display the menu in the admin bar.', 'pz-linkcard' ); ?>
				</label>
			</td>
		</tr>
		<tr>
			<th scope="row"><?php esc_html_e('Initialize Tab', 'pz-linkcard' ); ?></th>
			<td>
				<label>
					<input type="hidden"   name="properties[flg-initialize]" value="" />
					<input type="checkbox" name="properties[flg-initialize]" value="1" class="pz-tab-show" <?php checked($this->options['flg-initialize'] ); ?> />
					<?php esc_html_e('Display the initialize tab on the settings screen.', 'pz-linkcard' ); ?>
				</label>
			</td>
		</tr>
		<tr>
			<th scope="row" style="color: #06f !important; background-color: #ccccee !important;"><span ><?php esc_html_e('Debug Mode', 'pz-linkcard' ); ?></span></th>
			<td	style="background-color: #f8f8ff !important;">
				<label>
					<input type="hidden"   name="properties[debug-mode]" value="" />
					<input type="checkbox" name="properties[debug-mode]" value="1" class="pz-tab-show" <?php checked($this->options['debug-mode'] ); ?> />
					<span style="color: #06f !important;"><?php echo __('Displays normally hidden items to find and fix defects.', 'pz-linkcard' ).__('(Deprecation)', 'pz-linkcard' ); ?></span>
				</label>
			</td>
		</tr>
		<tr class="pz-debug-only">
			<th scope="row" style="color: #06f !important; background-color: #ccccee !important;"><span ><?php esc_html_e('Survey Mode', 'pz-linkcard' ); ?></span></th>
			<td	style="background-color: #f8f8ff !important;">
				<label>
					<input type="hidden"   name="properties[survey-mode]" value="" />
					<input type="checkbox" name="properties[survey-mode]" value="1" class="pz-tab-show" <?php checked($this->options['survey-mode'] ); ?> />
					<span style="color: #06f !important;"><?php echo __('Collect logs. May slow down operation.', 'pz-linkcard' ).__('(Deprecation)', 'pz-linkcard' ); ?></span>
				</label>
			</td>
		</tr>
		<tr class="pz-debug-only">
			<th scope="row" style="color: #c38 !important; background-color: #d8cce0 !important;"><?php esc_html_e('Administrator Mode', 'pz-linkcard' ); ?></th>
			<td style="background-color: #fff8ff !important;">
				<label>
					<input type="hidden"   name="properties[admin-mode]" value="" />
					<input type="checkbox" name="properties[admin-mode]" value="1" class="pz-tab-show" <?php checked($this->options['admin-mode'] ); if (!$this->options['admin-mode'] ) {echo 'readonly="readonly"'; }; if (!$this->options['admin-mode'] ) { echo 'ondblclick="this.readOnly=false;"'; } ?> />
					<span style="color: #c38 !important;"><?php echo __('Display information that is not normally needed or open special settings.', 'pz-linkcard' ).__('(Deprecation)', 'pz-linkcard' ); ?></span>
				</label>
			</td>
		</tr>
		<tr class="pz-admin-only">
			<th scope="row" style="color: #f62 !important; background-color: #ddccbb !important;"><?php esc_html_e('MultiSite Mode', 'pz-linkcard' ); ?></th>
			<td style="background-color: #fcf4f0 !important;">
				<label>
					<input type="hidden"   name="properties[multi-mode]" value="" />
					<input type="checkbox" name="properties[multi-mode]" value="1" <?php checked($menu_multi || $is_multisite ); echo ($is_multisite ? ' readonly="readonly"' : '' ); ?> />
					<span style="color: #f62 !important;"><?php esc_html_e('Displays a menu for Multi-Site', 'pz-linkcard' ); ?></span>
				</label>
			</td>
		</tr>
		<tr  class="pz-develop-only">
			<th scope="row" style="color: #0a8 !important; background-color: #b0bbbb !important;"><?php esc_html_e('Develop Mode', 'pz-linkcard' ); ?></th>
			<td style="background-color: #fffff8 !important;">
				<label>
					<input type="hidden"   name="properties[develop-mode]" value="" />
					<input type="checkbox" name="properties[develop-mode]" value="1" <?php checked($this->options['develop-mode'] ); ?> readonly="readonly" />
					<span style="color: #0a8 !important;"><?php esc_html_e('Currently working in a development environment.', 'pz-linkcard' ); ?></span>
				</label>
			</td>
		</tr>
	</table>
	<?php submit_button(); ?>
</div>
