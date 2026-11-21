<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

$letter_rows = array(
	'title'   => __('Title', 'pz-linkcard' ),
	'excerpt' => __('Excerpt', 'pz-linkcard' ),
	'url'     => __('URL', 'pz-linkcard' ),
	'date'    => __('Date', 'pz-linkcard' ),
	'heading' => __('Header Text', 'pz-linkcard' ),
	'more'    => __('More Button', 'pz-linkcard' ),
	'info'    => __('Site Information', 'pz-linkcard' ),
	'added'   => __('Added Text', 'pz-linkcard' ),
	'cat'     => __('Category', 'pz-linkcard' ),
);
$letter_colors = array(
	'color'         => __('Text Color', 'pz-linkcard' ),
	'outline-color' => __('Outline Color', 'pz-linkcard' ),
	'bg-color'      => __('Background Color', 'pz-linkcard' ),
);
$letter_numbers = array(
	'size'    => array('label' => __('Size', 'pz-linkcard' ), 'max' => 999, 'unit' => __('px', 'pz-linkcard' ) ),
	'height'  => array('label' => __('Line Height', 'pz-linkcard' ), 'max' => 999, 'unit' => __('px', 'pz-linkcard' ) ),
	'maxline' => array('label' => __('Line Limit', 'pz-linkcard' ), 'max' => 99, 'unit' => '' ),
);
$letter_styles = array(
	'bold'      => __('Bold', 'pz-linkcard' ),
	'italic'    => __('Italic', 'pz-linkcard' ),
	'underline' => __('Underline', 'pz-linkcard' ),
	'hover'     => str_replace('<br>', ' ', __('Underline<br>On Hover', 'pz-linkcard' ) ),
);
?>
<div class="pz-page<?php echo esc_attr($pz_page_active('pz-letter' )); ?>" id="pz-letter">
	<div class="pz-submit-float"><?php submit_button(); ?></div>
	<h2><?php echo wp_kses_post(__('Text Settings', 'pz-linkcard' ).$help_open.'letter'.$help_close ); ?></h2>
	<div class="pz-letter-sections">
		<?php foreach ($letter_rows as $letter_name => $letter_label ) { ?>
		<section class="pz-letter-section" aria-labelledby="pz-letter-heading-<?php echo esc_attr($letter_name ); ?>">
			<h3 id="pz-letter-heading-<?php echo esc_attr($letter_name ); ?>"><?php echo esc_html($letter_label ); ?></h3>
			<div class="pz-letter-fields">
				<?php foreach ($letter_colors as $suffix => $label ) {
					$key = $letter_name.'-'.$suffix;
					if (!array_key_exists($key, self::DEFAULTS ) ) { continue; }
				?>
				<div class="pz-letter-field">
					<label for="pz-letter-<?php echo esc_attr($key ); ?>"><?php echo esc_html($label ); ?></label>
					<input type="text" id="pz-letter-<?php echo esc_attr($key ); ?>" name="properties[<?php echo esc_attr($key ); ?>]" value="<?php echo esc_attr($prop[$key] ?? '' ); ?>" class="pz-sync-text pz-color pz-monospace pz-color-picker" />
				</div>
				<?php } ?>
				<?php foreach ($letter_numbers as $suffix => $field ) {
					$key = $letter_name.'-'.$suffix;
					if (!array_key_exists($key, self::DEFAULTS ) ) { continue; }
					$value = preg_replace('/[^0-9]/', '', ($prop[$key] ?? '') );
				?>
				<div class="pz-letter-field">
					<label for="pz-letter-<?php echo esc_attr($key ); ?>"><?php echo esc_html($field['label'] ); ?></label>
					<span><input type="number" id="pz-letter-<?php echo esc_attr($key ); ?>" name="properties[<?php echo esc_attr($key ); ?>]" value="<?php echo esc_attr($value ); ?>" class="pz-letter-box-r" min="0" max="<?php echo esc_attr($field['max'] ); ?>" /> <?php echo esc_html($field['unit'] ); ?></span>
				</div>
				<?php } ?>
			</div>
			<div class="pz-letter-styles">
				<?php foreach ($letter_styles as $suffix => $label ) {
					$key = $letter_name.'-'.$suffix;
					if (!array_key_exists($key, self::DEFAULTS ) ) { continue; }
					$value = preg_replace('/[^0-9]/', '', ($prop[$key] ?? '') );
				?>
				<label><input type="hidden" name="properties[<?php echo esc_attr($key ); ?>]" value="" /><input type="checkbox" name="properties[<?php echo esc_attr($key ); ?>]" value="1" <?php checked((bool) $value ); ?> /> <?php echo esc_html($label ); ?></label>
				<?php } ?>
			</div>
		</section>
		<?php } ?>
	</div>
	<?php submit_button(); ?>
</div>
