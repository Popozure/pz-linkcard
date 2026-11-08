<?php defined('ABSPATH' ) || wp_die; ?>
<div class="pz-page<?php echo $pz_page_active('pz-display' ); ?>" id="pz-display">
	<div class="pz-submit-float"><?php submit_button(); ?></div>
	<h2><?php echo	__('Display Settings', 'pz-linkcard' ).$help_open.'display'.$help_close; ?></h2>

	<table class="form-table" style="width: 100%;">
		<tr>
			<th scope="row" rowspan="2"><?php esc_html_e('Stylesheet', 'pz-linkcard' ); ?></th>
			<th scope="row"><?php esc_html_e('Reset Image', 'pz-linkcard' ); ?></th>
			<td>
				<?php pz_Checkbox($prop, 'flg-style-reset', __('Resetting image styles may help if an unwanted border appears around an image.', 'pz-linkcard' ).__('(Recommended)', 'pz-linkcard' ) ); ?>
			</td>
		</tr>
		<tr>
			<th scope="row"><?php esc_html_e('Important Style', 'pz-linkcard' ); ?></th>
			<td>
				<?php pz_Checkbox($prop, 'flg-style-important', __('If the style is not applied due to the theme or plugin, you can apply it forcibly.', 'pz-linkcard' ).__('(Recommended)', 'pz-linkcard' ) ); ?>
			</td>
		</tr>
		<tr>
			<th scope="row" rowspan="5"><?php esc_html_e('Link Card', 'pz-linkcard' ); ?></th>
			<th scope="row"><?php esc_html_e('Publication and Update Dates', 'pz-linkcard' ); ?></th>
			<td>
				<?php pz_Option($prop, 'date-pattern', '', array(
								''		=>		__('None', 'pz-linkcard' ),
								'p'		=>		__('Post Date', 'pz-linkcard' ),
								'u'		=>		__('Update Date', 'pz-linkcard' ),
								'pu'	=>		__('Post Date and Update Date', 'pz-linkcard' ), ) ); ?>
			</td>
		</tr>
		<tr>
			<th scope="row"><?php esc_html_e('Date Format', 'pz-linkcard'); ?></th>
			<td>
				<?php foreach (array('Y.m.d', 'Y-m-d', 'Y年n月j日', 'F j, Y') as $date_format) : ?>
				<label class="pz-date-format-option" style="margin-right:1em"><input type="radio" name="properties[date-format]" value="<?php echo esc_attr($date_format); ?>" <?php checked($prop['date-format'], $date_format); ?> <?php disabled(empty($prop['date-pattern'])); ?> /><?php echo esc_html($date_format); ?></label>
				<?php endforeach; ?>
				<label class="pz-date-format-custom"><input type="radio" name="properties[date-format]" value="custom" <?php checked($prop['date-format'], 'custom'); ?> aria-label="<?php esc_attr_e('Custom Date Format', 'pz-linkcard'); ?>" <?php disabled(empty($prop['date-pattern'])); ?> /><input type="text" name="properties[date-format-custom]" value="<?php echo esc_attr($prop['date-format-custom']); ?>" size="20" aria-label="<?php esc_attr_e('Custom Date Format', 'pz-linkcard'); ?>" <?php disabled(empty($prop['date-pattern']) || $prop['date-format'] !== 'custom'); ?> /></label>
			</td>
		</tr>
		<?php
		$date_icons = array('&#x1F4C5;&#xFE0F;', '&#x1F5D3;&#xFE0F;', '&#x1F504;&#xFE0F;', '&#x270F;&#xFE0F;', '&#x1F4DD;&#xFE0F;', '&#x270D;&#xFE0F;', '&#x1F5D2;&#xFE0F;', '&#x1F4CB;&#xFE0F;', '&#x2B50;&#xFE0F;', '&#x2728;&#xFE0F;', '&#x1F3B5;&#xFE0F;', '&#x1F3B6;&#xFE0F;', '&#x1F389;&#xFE0F;', '&#x1F380;&#xFE0F;', '&#x1F381;&#xFE0F;', '&#x1F4CC;&#xFE0F;');
		?>
		<tr>
			<th scope="row"><?php esc_html_e('Icon', 'pz-linkcard'); ?></th>
			<td>
				<?php foreach (array('date-icon-post' => __('Post Date', 'pz-linkcard'), 'date-icon-update' => __('Update Date', 'pz-linkcard')) as $icon_key => $icon_label) : ?>
				<?php $selected_icon = html_entity_decode((string) $prop[$icon_key], ENT_QUOTES | ENT_HTML5, 'UTF-8'); ?>
				<label style="display:inline-block;margin-right:2em">
				<?php echo esc_html($icon_label); ?>：
				<select name="properties[<?php echo esc_attr($icon_key); ?>]" <?php disabled(!in_array((string) $prop['date-pattern'], $icon_key === 'date-icon-post' ? array('p', 'pu') : array('u', 'pu'), true)); ?>>
					<option value="" <?php selected($prop[$icon_key], ''); ?>><?php esc_html_e('None', 'pz-linkcard'); ?></option>
					<?php foreach ($date_icons as $date_icon) : ?>
					<?php $date_icon_value = html_entity_decode($date_icon, ENT_QUOTES | ENT_HTML5, 'UTF-8'); ?>
					<option value="<?php echo esc_attr($date_icon_value); ?>" <?php selected($selected_icon, $date_icon_value); ?>><?php echo esc_html($date_icon_value); ?></option>
					<?php endforeach; ?>

				</select>
				</label>
				<?php endforeach; ?>
			</td>
		</tr>
		<tr>
			<th scope="row"><?php esc_html_e('Separator Line', 'pz-linkcard'); ?></th>
			<td>
				<?php pz_Checkbox($prop, 'separator', __('Display a separator line between site information and article content', 'pz-linkcard' ) ); ?>
			</td>
		</tr>
		<tr>
			<th scope="row"><?php esc_html_e('Broken Link Border', 'pz-linkcard' ); ?></th>
			<td>
				<div class="pz-color-note-row">
					<input name="properties[unlink-border-color]" type="text" value="<?php echo esc_attr($prop['unlink-border-color'] ); ?>" class="pz-sync-text pz-color pz-monospace pz-color-picker" />
					<span class="pz-note"><?php esc_html_e('* If you do not wish to change the border color, press the “Clear” button to leave it blank.', 'pz-linkcard' ); ?></span>
				</div>
			</td>
		</tr>
		<tr>
			<th scope="row" rowspan="2"><?php esc_html_e('SNS Count', 'pz-linkcard' ); ?></th>
			<th scope="row"><?php esc_html_e('Display', 'pz-linkcard' ); ?></th>
			<td>
				<ul>
					<li>
						<label>
							<input type="hidden"   name="properties[sns-tw]" value="" />
							<input type="checkbox" name="properties[sns-tw]" value="1" <?php checked($prop['sns-tw'] ); ?> />
							<?php echo __('X (Twitter)', 'pz-linkcard' ).__('* The count is not updated', 'pz-linkcard' ); ?>
						</label>
						<label>
							<input type="hidden"   name="properties[sns-tw-x]" value="" />
							<input type="checkbox" name="properties[sns-tw-x]" value="1" <?php checked($prop['sns-tw-x'] ); ?> />
							<?php echo __('Use "Tweet" instead of "Post" as the count label.', 'pz-linkcard' ); ?>
						</label>
					</li>
					<li>
						<label>
							<input type="hidden"   name="properties[sns-fb]" value="" />
							<input type="checkbox" name="properties[sns-fb]" value="1" <?php checked($prop['sns-fb'] ); ?> />
							<?php echo __('Facebook', 'pz-linkcard' ).__('* The count is not updated', 'pz-linkcard' ); ?>
						</label>
					</li>
					<li>
						<label>
							<input type="hidden"   name="properties[sns-hb]" value="" />
							<input type="checkbox" name="properties[sns-hb]" value="1" <?php checked($prop['sns-hb'] ); ?> />
							<?php echo __('Hatena', 'pz-linkcard' ); ?>
						</label>
					</li>
					<li>
						<label>
							<input type="hidden"   name="properties[sns-qiita]" value="" />
							<input type="checkbox" name="properties[sns-qiita]" value="1" <?php checked($prop['sns-qiita'] ); ?> />
							<?php echo __('Qiita likes and stocks', 'pz-linkcard' ); ?>
						</label>
					</li>
				</ul>
			</td>
		</tr>
		<tr>
			<th scope="row"><?php esc_html_e('Recurrence', 'pz-linkcard' ); ?></th>
			<td>
				<?php
					echo sprintf(esc_html__('The %s are on the “Advanced” tab.', 'pz-linkcard'), '<a class="pz-schedule-setting-link" href="#pz-sns-recurrence-settings">'.esc_html__('settings', 'pz-linkcard').'</a>');
				?>
			</td>
		</tr>
	</table>
	<?php submit_button(); ?>
</div>
