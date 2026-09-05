<?php defined('ABSPATH' ) || wp_die; ?>
<div class="pz-page<?php echo $pz_page_active('pz-display' ); ?>" id="pz-display">
	<div class="pz-submit-float"><?php submit_button(); ?></div>
	<h2><?php echo	__('Display Settings', 'pz-linkcard' ).$help_open.'display'.$help_close; ?></h2>

	<table class="form-table" style="width: 100%;">
		<tr>
			<th scope="row"><?php esc_html_e('Reset Image', 'pz-linkcard' ); ?></th>
			<td>
				<?php pz_Checkbox($prop, 'style-reset-img', __('When unnecessary frame is displayed on the image, you can improve it by case', 'pz-linkcard' ) ); ?>
			</td>
		</tr>

		<tr>
			<th scope="row" rowspan="10"><?php esc_html_e('Link Card', 'pz-linkcard' ); ?></th>
			<td>
				<?php pz_Option($prop, 'display-url', __('Position to display URL', 'pz-linkcard' ), array(
								''		=>		__('None', 'pz-linkcard' ),
								'1'		=>		__('Under Title', 'pz-linkcard' ),
								'2'		=>		__('Bihind Site-Info', 'pz-linkcard' ), ) ); ?>
			</td>
		</tr>
		<tr>
			<td>
				<?php pz_Option($prop, 'display-date', __('For internal links, display date instead of URL.', 'pz-linkcard' ), array(
								''		=>		__('Off', 'pz-linkcard' ),
								'1'		=>		__('Post Date', 'pz-linkcard' ),
								'2'		=>		__('Update Date', 'pz-linkcard' ),
								'3'		=>		__('Post Date and Update Date', 'pz-linkcard' ), ) ); ?>
			</td>
		</tr>
		<tr>
			<td>
				<?php pz_Checkbox($prop, 'separator', __('Separator Line', 'pz-linkcard' ) ); ?>
			</td>
		</tr>
		<tr>
			<td>
				<?php pz_Checkbox($prop, 'content-inset', __('Show Contents Frame', 'pz-linkcard' ) ); ?>
			</td>
		</tr>
		<tr>
			<td>
				<?php pz_Checkbox($prop, 'display-excerpt', __('Show Excerpt', 'pz-linkcard' ) ); ?>
			</td>
		</tr>
		<tr>
			<td>
				<?php pz_Checkbox($prop, 'shadow-inset', __('Shadow-inset', 'pz-linkcard' ) ); ?>
			</td>
		</tr>
		<tr>
			<td>
				<?php pz_Checkbox($prop, 'shadow', __('Shadow', 'pz-linkcard' ) ); ?>
			</td>
		</tr>
		<tr>
			<td>
				<?php pz_Option($prop, 'radius', __('Round a square', 'pz-linkcard' ), LIST_MARGIN ); ?>
			</td>
		</tr>
		<tr>
			<td>
				<?php pz_Option($prop, 'border-style', __('Style', 'pz-linkcard' ), LIST_BORDER ); ?>
				<?php pz_Option($prop, 'border-width', __('Width', 'pz-linkcard' ), LIST_PX ); ?>
			</td>
		</tr>
		<tr>
			<td>
				<?php
					pz_Option($prop, 'more-style', __('More button', 'pz-linkcard' ), array(
								''		=>		__('None',				'pz-linkcard' ),
								'TXT'	=>		__('Text only',			'pz-linkcard' ),
								'SMP'	=>		__('Simple button',		'pz-linkcard' ),
								'BTN'	=>		__('Button',			'pz-linkcard' ),
								'PSH'	=>		__('Push Button',		'pz-linkcard' ),) );
				?>
			</td>
		</tr>
		<tr>
			<th scope="row"><?php esc_html_e('On Hover', 'pz-linkcard' ); ?></th>
			<td>
				<?php pz_Option($prop, 'hover', __('When the mouse is on', 'pz-linkcard' ), array(
								''		=>		__('None', 'pz-linkcard' ),
								'1'		=>		__('Lighten', 'pz-linkcard' ),
								'2'		=>		__('Hover (Light)', 'pz-linkcard' ),
								'3'		=>		__('Hover (Dark)', 'pz-linkcard' ),
								'4'		=>		__('Retract (for Shadow)', 'pz-linkcard' ),
								'7'		=>		__('Radius', 'pz-linkcard' ), ) ); ?>
			</td>
		</tr>
		<tr>
			<th scope="row" rowspan="3"><?php esc_html_e('Thumbnail', 'pz-linkcard' ); ?></th>
			<td>
				<?php pz_Checkbox($prop, 'thumbnail-border', __('Show Thumbnail Border', 'pz-linkcard' ) ); ?>
			</td>
		</tr>
		<tr>
			<td>
				<?php pz_Checkbox($prop, 'thumbnail-shadow', __('Show Thumbnail Shadow', 'pz-linkcard' ) ); ?>
			</td>
		</tr>
		<tr>
			<td>
				<?php pz_Option($prop, 'thumbnail-radius', __('Round a square', 'pz-linkcard' ), array(
								''		=>		__('None', 'pz-linkcard' ),
								'2px'	=>		__('2px', 'pz-linkcard' ),
								'4px'	=>		__('4px', 'pz-linkcard' ),
								'6px'	=>		__('6px', 'pz-linkcard' ),
								'8px'	=>		__('8px', 'pz-linkcard' ),
								'12px'	=>		__('12px', 'pz-linkcard' ),
								'16px'	=>		__('16px', 'pz-linkcard' ),
								'50%'	=>		__('50%', 'pz-linkcard' ), ) ); ?>
			</td>
		</tr>
		<tr>
			<th scope="row"><?php esc_html_e('Display SNS Count', 'pz-linkcard' ); ?></th>
			<td>
				<select name="properties[sns-position]">
					<option value=""  <?php selected($prop['sns-position'] == ''  ); ?>><?php esc_html_e('None',				'pz-linkcard' ); ?></option>
					<option value="1" <?php selected($prop['sns-position'] == '1' ); ?>><?php esc_html_e('Under Title',			'pz-linkcard' ); ?></option>
					<option value="2" <?php selected($prop['sns-position'] == '2' ); ?>><?php esc_html_e('Bihind Site-Info',	'pz-linkcard' ); ?></option>
				</select>
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
							<?php echo __('Change the unit of measure to "tweets".', 'pz-linkcard' ); ?>
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
							<input type="hidden"   name="properties[sns-po]" value="" />
							<input type="checkbox" name="properties[sns-po]" value="1" <?php checked($prop['sns-po'] ); ?> />
							<?php echo __('Pocket', 'pz-linkcard' ); ?>
						</label>
					</li>
				</ul>
			</td>
		</tr>
	</table>
	<?php submit_button(); ?>
</div>
