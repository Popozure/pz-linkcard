<?php defined('ABSPATH' ) || wp_die; ?>
<div class="pz-page<?php echo $pz_page_active('pz-position' ); ?>" id="pz-position">
	<div class="pz-submit-float"><?php submit_button(); ?></div>
	<h2><?php echo	__('Position Settings', 'pz-linkcard' ).$help_open.'position'.$help_close; ?></h2>

	<table class="pz-position-margin">
		<tr>
			<td colspan="3">
				<?php
					echo	__('Margin top', 'pz-linkcard' ).'<br>';
					pz_Select($prop, 'margin-top', LIST_MARGIN );
				?>
			</td>
		</tr>
		<tr>
			<td>
				<?php
					echo	__('Margin left', 'pz-linkcard' ).'<br>';
					pz_Select($prop, 'margin-left', LIST_MARGIN );
				?>
			</td>
			<td>
				<table class="pz-position-card">
					<tr>
						<td colspan="5">
							<?php
								echo	__('Padding top', 'pz-linkcard' ).'<br>';
								pz_Select($prop, 'card-top', LIST_MARGIN );
							?>
						</td>
					</tr>
					<tr>
						<td colspan="5">
							<table class="pz-position-siteinfo">
								<tr>
									<th>
										<?php esc_html_e('Site Information', 'pz-linkcard' ); ?>
									</th>
									<td>
										<?php
											pz_Select($prop, 'info-position',
												array(
													''		=>		__('None',				'pz-linkcard' ),
													'1'		=>		__('Top Side',			'pz-linkcard' ),
													'3'		=>		__('Above the Title',	'pz-linkcard' ),
													'2'		=>		__('Bottom Side',		'pz-linkcard' ),
											) );
											echo	'&emsp;';
											pz_Checkbox($prop, 'flg-use-sitename', __('Use Site Name', 'pz-linkcard' ) );
										?>
									</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td>
							<?php
								echo	__('Padding left', 'pz-linkcard' ).'<br>';
								pz_Select($prop, 'card-left', LIST_MARGIN );
							?>
						</td>
						<td>
							<table class="pz-position-thumbnail">
								<tr>
									<th colspan="2">
										<?php esc_html_e('Thumbnail', 'pz-linkcard' ); ?>
									</th>
								</tr>
								<tr>
									<td>
										<?php esc_html_e('Position', 'pz-linkcard' ); ?>
									</td>
									<td>
										<?php
											pz_Select($prop,	'thumbnail-position',
												array(
													'0'	=>		__('None',			'pz-linkcard' ),
													'1'	=>		__('Right Side',	'pz-linkcard' ),
													'2'	=>		__('Left Side',		'pz-linkcard' ),
													'3'	=>		__('Top Side',		'pz-linkcard' ),
											) );
										?>
									</td>
								</tr>
								<tr>
									<td>
										<?php esc_html_e('Width', 'pz-linkcard' );  ?>
									</td>
									<td>
										<input name="properties[thumbnail-width]"	type="text" value="<?php echo esc_attr($prop['thumbnail-width'] ); ?>" size="2" />
									</td>
								</tr>
								<tr>
									<td>
										<?php esc_html_e('Height', 'pz-linkcard' ); ?>
									</td>
									<td>
										<input name="properties[thumbnail-height]"	type="text" value="<?php echo esc_attr($prop['thumbnail-height'] ); ?>" size="2" />
									</td>
								</tr>
							</table>
						</td>
						<td>
							<table class="pz-position-size">
								<tr>
									<td>
										<?php esc_html_e('Width', 'pz-linkcard' ); ?>
									</td>
									<td>
										<input name="properties[width]"          type="text" value="<?php echo	esc_attr($prop['width'] ); ?>" size="3" />
									</td>
								</tr>
								<tr>
									<td>
										<?php esc_html_e('Height', 'pz-linkcard' ); ?>
									</td>
									<td style="margin: 0; padding: 0; text-align: left;">
										<input name="properties[content-height]" type="text" value="<?php echo	esc_attr($prop['content-height'] ); ?>" size="3" />
									</td>
								</tr>
							</table>
						</td>
						<td>
							<?php
								echo	__('Padding right', 'pz-linkcard' ).'<br>';
								pz_Select($prop, 'card-right', LIST_MARGIN );
							?>
						</td>
					</tr>
					<tr>
						<td>
						</td>
						<td colspan="2">
							<?php
								echo	__('Padding bottom', 'pz-linkcard' ).'<br>';
								pz_Select($prop, 'card-bottom', LIST_MARGIN );
							?>
						</td>
						<td>
						</td>
					</tr>
				</table>
			</td>
			<td>
				<?php
					echo	__('Margin right', 'pz-linkcard' ).'<br>';
					pz_Select($prop, 'margin-right', LIST_MARGIN );
				?>
			</td>
		</tr>
		<tr>
			<td>
				<?php pz_Checkbox($prop, 'centering', __('Centering', 'pz-linkcard' ) ); ?>
			</td>
			<td>
				<?php
					echo	__('Margin bottom', 'pz-linkcard' ).'<br>';
					pz_Select($prop, 'margin-bottom', LIST_MARGIN );
				?>
			</td>
			<td>
			</td>
		</tr>
	</table>
	<table class="form-table">
		<tr>
			<th scope="row"><?php esc_html_e('Wrapper Tag', 'pz-linkcard' ); ?></th>
			<td>
				<?php
					pz_Select($prop, 'enclose-tag', LIST_ENCLOSE_TAG );
				?>
			</td>
		</tr>
		<tr>
			<th scope="row"><?php esc_html_e('Link the Entire Card', 'pz-linkcard' ); ?></th>
			<td>
				<?php
					pz_Checkbox($prop, 'flg-linkall', __('Wrap the entire card in a link.', 'pz-linkcard' ) );
				?>
			</td>
		</tr>
		<tr>
			<th scope="row"><?php esc_html_e('Text Selection', 'pz-linkcard' ); ?></th>
			<td>
				<label>
					<input type="hidden"   name="properties[flg-unti-select]" value="" />
					<input type="checkbox" name="properties[flg-unti-select]" value="1" <?php checked($this->options['flg-unti-select'] ); ?> />
					<?php esc_html_e('Prohibit the selection of text in the Link-Card.', 'pz-linkcard' ); ?>
				</label>
			</td>
		</tr>
		<tr>
			<th scope="row"><?php esc_html_e('Resize', 'pz-linkcard' ); ?></th>
			<td>
				<label>
					<input type="hidden"   name="properties[thumbnail-resize]" value="" />
					<input type="checkbox" name="properties[thumbnail-resize]" value="1" <?php checked($prop['flg-resize'] ); ?> />
					<?php esc_html_e('Adjust the thumbnail and text size according to the width.', 'pz-linkcard' ); ?>
				</label>
			</td>
		</tr>
	</table>
	<?php submit_button(); ?>
</div>
