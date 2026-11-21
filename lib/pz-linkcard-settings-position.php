<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

$position_spacing = array(
	'margin' => array(
		'top' => __('Margin top', 'pz-linkcard' ), 'right' => __('Margin right', 'pz-linkcard' ),
		'bottom' => __('Margin bottom', 'pz-linkcard' ), 'left' => __('Margin left', 'pz-linkcard' ),
	),
	'card' => array(
		'top' => __('Padding top', 'pz-linkcard' ), 'right' => __('Padding right', 'pz-linkcard' ),
		'bottom' => __('Padding bottom', 'pz-linkcard' ), 'left' => __('Padding left', 'pz-linkcard' ),
	),
);
?>
<div class="pz-page<?php echo esc_attr($pz_page_active('pz-position' )); ?>" id="pz-position">
	<div class="pz-submit-float"><?php submit_button(); ?></div>
	<h2><?php echo wp_kses_post(__('Position Settings', 'pz-linkcard' ).$help_open.'position'.$help_close ); ?></h2>
	<div class="pz-position-spacing-sections">
		<?php foreach ($position_spacing as $prefix => $directions ) { ?>
		<section class="pz-position-section<?php echo $prefix === 'card' ? ' pz-position-padding-section' : ''; ?>" aria-labelledby="pz-position-<?php echo esc_attr($prefix === 'card' ? 'padding' : $prefix ); ?>-heading">
			<h3 id="pz-position-<?php echo esc_attr($prefix === 'card' ? 'padding' : $prefix ); ?>-heading"><?php echo esc_html($prefix === 'margin' ? __('Margin', 'pz-linkcard' ) : __('Padding', 'pz-linkcard' ) ); ?></h3>
			<div class="pz-position-spacing-grid<?php echo $prefix === 'card' ? ' pz-position-spacing-padding' : ''; ?>">
				<?php foreach ($directions as $direction => $label ) { ?>
				<label class="pz-position-spacing-<?php echo esc_attr($direction ); ?>"><span><?php echo esc_html($label ); ?></span><?php pz_Select($prop, $prefix.'-'.$direction, LIST_MARGIN ); ?></label>
				<?php } ?>
				<div class="pz-position-spacing-center" aria-hidden="true"><?php esc_html_e('Link Card', 'pz-linkcard' ); ?></div>
			</div>
		</section>
		<?php } ?>
	</div>
	<div class="pz-position-sections">
		<section class="pz-position-section" aria-labelledby="pz-position-card-heading">
			<h3 id="pz-position-card-heading"><?php esc_html_e('Link Card', 'pz-linkcard' ); ?></h3>
			<div class="pz-position-fields">
				<label><span><?php esc_html_e('Width', 'pz-linkcard' ); ?></span><span><input name="properties[width]" type="number" value="<?php echo esc_attr($prop['width'] ); ?>" size="3" min="0" step="1" /> <select name="properties[width-unit]" aria-label="<?php esc_attr_e('Width', 'pz-linkcard' ); ?>"><option value="px" <?php selected($prop['width-unit'], 'px' ); ?>>px</option><option value="%" <?php selected($prop['width-unit'], '%' ); ?>>%</option></select></span></label>
				<label><span><?php esc_html_e('Height', 'pz-linkcard' ); ?></span><span><input name="properties[content-height]" type="number" value="<?php echo esc_attr($prop['content-height'] ); ?>" size="3" min="0" step="1" /> px</span></label>
			</div>
			<div class="pz-position-card-preview pz-position-spacing-center" aria-hidden="true"><?php esc_html_e('Link Card', 'pz-linkcard' ); ?></div>
			<div class="pz-position-check"><?php pz_Checkbox($prop, 'centering', __('Centering', 'pz-linkcard' ) ); ?></div>
		</section>
		<section class="pz-position-section" aria-labelledby="pz-position-thumbnail-heading">
			<h3 id="pz-position-thumbnail-heading"><?php esc_html_e('Thumbnail', 'pz-linkcard' ); ?></h3>
			<div class="pz-position-fields">
				<label><span><?php esc_html_e('Position', 'pz-linkcard' ); ?></span><?php pz_Select($prop, 'thumbnail-position', array('0' => __('None', 'pz-linkcard' ), '2' => __('Left Side', 'pz-linkcard' ), '1' => __('Right Side', 'pz-linkcard' ), '3' => __('Top Side', 'pz-linkcard' ) ) ); ?></label>
				<?php foreach (array('width' => __('Width', 'pz-linkcard' ), 'height' => __('Height', 'pz-linkcard' ) ) as $dimension => $label ) { $key = 'thumbnail-'.$dimension; ?>
				<label><span><?php echo esc_html($label ); ?></span><span><input name="properties[<?php echo esc_attr($key ); ?>]" type="number" value="<?php echo esc_attr($prop[$key] ); ?>" size="2" min="0" step="1" /> px</span></label>
				<?php } ?>
			</div>
			<div class="pz-position-thumbnail-preview" data-position="<?php echo esc_attr($prop['thumbnail-position'] ); ?>" aria-hidden="true"><span></span><div class="pz-position-thumbnail-preview-label"><?php esc_html_e('Link Card', 'pz-linkcard' ); ?></div></div>
		</section>
		<section class="pz-position-section" aria-labelledby="pz-position-info-heading">
			<h3 id="pz-position-info-heading"><?php esc_html_e('Site Information', 'pz-linkcard' ); ?></h3>
			<div class="pz-position-fields">
				<label><span><?php esc_html_e('Position', 'pz-linkcard' ); ?></span><?php pz_Select($prop, 'info-position', array('' => __('None', 'pz-linkcard' ), '1' => __('Top Side', 'pz-linkcard' ), '2' => __('Bottom Side', 'pz-linkcard' ) ) ); ?></label>
			</div>
			<div class="pz-position-check"><?php pz_Checkbox($prop, 'flg-use-sitename', __('Use Site Name', 'pz-linkcard' ) ); ?></div>
			<div class="pz-position-info-preview" data-position="<?php echo esc_attr($prop['info-position'] ); ?>" aria-hidden="true"><span></span><div class="pz-position-info-preview-label"><?php esc_html_e('Link Card', 'pz-linkcard' ); ?></div></div>
		</section>
	</div>
	<section class="pz-position-section" aria-labelledby="pz-position-behavior-heading">
		<h3 id="pz-position-behavior-heading"><?php esc_html_e('Display Settings', 'pz-linkcard' ); ?></h3>
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
					<input type="hidden"   name="properties[flg-anti-select]" value="" />
					<input type="checkbox" name="properties[flg-anti-select]" value="1" <?php checked($this->options['flg-anti-select'] ); ?> />
					<?php esc_html_e('Prevent text selection within LinkCards.', 'pz-linkcard' ); ?>
				</label>
			</td>
		</tr>
		<tr>
			<th scope="row"><?php esc_html_e('Resize', 'pz-linkcard' ); ?></th>
			<td>
				<label>
					<input type="hidden"   name="properties[flg-resize]" value="" />
					<input type="checkbox" name="properties[flg-resize]" value="1" <?php checked($prop['flg-resize'] ); ?> />
					<?php esc_html_e('Adjust the thumbnail and text size according to the width.', 'pz-linkcard' ); ?>
				</label>
			</td>
		</tr>
	</table>
	</section>
	<?php submit_button(); ?>
</div>
