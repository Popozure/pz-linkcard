<?php defined('ABSPATH' ) || wp_die; ?>
<div class="pz-page<?php echo $pz_page_active('pz-letter' ); ?>" id="pz-letter">
	<div class="pz-submit-float"><?php submit_button(); ?></div>
	<h2><?php echo	__('Letter Settings', 'pz-linkcard' ).$help_open.'letter'.$help_close; ?></h2>
	<table class="pz-letter-table form-table">
		<tr class="pz-letter-head">
			<th></th>
			<th><?php esc_html_e('Letter Color',		'pz-linkcard' ); ?></th>
			<th><?php esc_html_e('Outline Color',		'pz-linkcard' ); ?></th>
			<th><?php esc_html_e('Background Color',	'pz-linkcard' ); ?></th>
			<th><?php esc_html_e('Size',				'pz-linkcard' ); ?></th>
			<th><?php esc_html_e('Line Height',			'pz-linkcard' ); ?></th>
			<th><?php esc_html_e('Line Limit',			'pz-linkcard' ); ?></th>
			<th><?php esc_html_e('Length',				'pz-linkcard' ); ?></th>
			<th><?php esc_html_e('Bold',				'pz-linkcard' ); ?></th>
			<th><?php esc_html_e('Italic',				'pz-linkcard' ); ?></th>
			<th><?php esc_html_e('Underline',			'pz-linkcard' ); ?></th>
			<th><?php echo wp_kses_post(__('Underline<br>On Hover', 'pz-linkcard' ) ); ?></th>
		</tr>
		<?php
		$table	=	array(
			array( 'name' => 'title'	,	'title' => __('Title',				'pz-linkcard' ) ),
			array( 'name' => 'excerpt'	,	'title' => __('Excerpt',			'pz-linkcard' ) ),
			array( 'name' => 'url'		,	'title' => __('URL',				'pz-linkcard' ) ),
			array( 'name' => 'date'		,	'title' => __('Date',				'pz-linkcard' ) ),
			array( 'name' => 'heading'	,	'title' => __('Header Text',		'pz-linkcard' ) ),
			array( 'name' => 'more'		,	'title' => __('More Button',		'pz-linkcard' ) ),
			array( 'name' => 'info'		,	'title' => __('Site Information',	'pz-linkcard' ) ),
			array( 'name' => 'added'	,	'title' => __('Added Text',			'pz-linkcard' ) ),
			array( 'name' => 'cat'		,	'title' => __('Category',			'pz-linkcard' ) ),
		);
		foreach ($table as $t) {
			echo	'<tr>';
			echo	'<th>'.$t['title'].'</th>';
			echo	'<td>';
			$key		=		$t['name'].'-color';
			if		(array_key_exists($key, self::DEFAULTS ) ) {
				$value	=		$prop[$key];
				echo	'<input type="text"     name="properties['.$key.']" value="'.$value.'" class="pz-sync-text pz-color pz-monospace pz-color-picker" />';
			} else {
				echo	'<input type="text"     name="" value="" disabled="disabled" readonly="readonly" class="pz-color-dummy" />';
			}
			echo		'</td>';

			echo		'<td>';
			$key		=		$t['name'].'-outline-color';
			if			(array_key_exists($key, self::DEFAULTS ) ) {
				$value	=		$prop[$key];
				echo	'<input type="text"     name="properties['.$key.']" value="'.$value.'" class="pz-sync-text pz-color pz-monospace pz-color-picker" />';
			} else {
				echo	'<input type="text"     name="" value="" disabled="disabled" readonly="readonly" class="pz-color-dummy" />';
			}
			echo		'</td>';

			echo		'<td>';
			$key		=		$t['name'].'-bg-color';
			if			(array_key_exists($key, self::DEFAULTS ) ) {
				$value	=		$prop[$key];
				echo	'<input type="text"     name="properties['.$key.']" value="'.$value.'" class="pz-sync-text pz-color pz-monospace pz-color-picker" />';
			} else {
				echo	'<input type="text"     name="" value="" disabled="disabled" readonly="readonly" class="pz-color-dummy" />';
			}
			echo		'</td>';

			echo	'<td>';
			$key		=		$t['name'].'-size';
			if		(array_key_exists($key, self::DEFAULTS ) ) {
				$value	=	preg_replace('/[^0-9]/', '', ($prop[$key] ?? '') );
				echo	'<input type="number"   name="properties['.$key.']" value="'.$value.'" class="pz-letter-box-r" min="0" max="999" />'.__('px', 'pz-linkcard' );
			} else {
				echo	'<input type="number"   name="" value="" disabled="disabled" readonly="readonly" class="pz-letter-box-r" min="0" max="999" />'.__('px', 'pz-linkcard' );
			}
			echo	'</td>';

			echo	'<td>';
			$key		=		$t['name'].'-height';
			if		(array_key_exists($key, self::DEFAULTS ) ) {
				$value	=	preg_replace('/[^0-9]/', '', ($prop[$key] ?? '') );
				echo	'<input type="number"   name="properties['.$key.']" value="'.$value.'" class="pz-letter-box-r" min="0" max="999" />'.__('px', 'pz-linkcard' );
			} else {
				echo	'<input type="number"   name="" value="" disabled="disabled" readonly="readonly" class="pz-letter-box-r" min="0" max="999" />'.__('px', 'pz-linkcard' );
			}
			echo	'</td>';

			echo	'<td>';
			$key		=		$t['name'].'-maxline';
			if		(array_key_exists($key, self::DEFAULTS ) ) {
				$value	=	preg_replace('/[^0-9]/', '', ($prop[$key] ?? '') );
				echo	'<input type="number"   name="properties['.$key.']" value="'.$value.'" class="pz-letter-box-r" min="0" max="99" />';
			} else {
				echo	'<input type="number"   name="" value="" disabled="disabled" readonly="readonly" class="pz-letter-box-r" min="0" max="99" />';
			}
			echo	'</td>';

			echo	'<td>';
			$key		=		$t['name'].'-length';
			if		(array_key_exists($key, self::DEFAULTS ) ) {
				$value	=	preg_replace('/[^0-9]/', '', ($prop[$key] ?? '') );
				echo	'<input type="number"   name="properties['.$key.']" value="'.$value.'" class="pz-letter-box-r" min="0" max="9999" />';
			} else {
				echo	'<input type="number"   name="" value="" disabled="disabled" readonly="readonly" class="pz-letter-box-r" min="0" max="9999" />';
			}
			echo	'</td>';

			echo	'<td>';
			$key		=		$t['name'].'-bold';
			if		(array_key_exists($key, self::DEFAULTS ) ) {
				$value	=	preg_replace('/[^0-9]/', '', ($prop[$key] ?? '') );
				echo	'<input type="hidden"   name="properties['.$key.']" value="" />';
				echo	'<input type="checkbox" name="properties['.$key.']" value="1" '.($value ? 'checked="checked"' : '' ).' />';
			} else {
				echo	'<input type="checkbox" name="" value="" disabled="disabled" readonly="readonly" />';
			}
			echo	'</td>';

			echo	'<td>';
			$key		=		$t['name'].'-italic';
			if		(array_key_exists($key, self::DEFAULTS ) ) {
				$value	=	preg_replace('/[^0-9]/', '', ($prop[$key] ?? '') );
				echo	'<input type="hidden"   name="properties['.$key.']" value="" />';
				echo	'<input type="checkbox" name="properties['.$key.']" value="1" '.($value ? 'checked="checked"' : '' ).' />';
			} else {
				echo	'<input type="checkbox" name="" value="" disabled="disabled" readonly="readonly" />';
			}
			echo	'</td>';

			echo	'<td>';
			$key		=		$t['name'].'-underline';
			if		(array_key_exists($key, self::DEFAULTS ) ) {
				$value	=	preg_replace('/[^0-9]/', '', ($prop[$key] ?? '') );
				echo	'<input type="hidden"   name="properties['.$key.']" value="" />';
				echo	'<input type="checkbox" name="properties['.$key.']" value="1" '.($value ? 'checked="checked"' : '' ).' />';
			} else {
				echo	'<input type="checkbox" name="" value="" disabled="disabled" readonly="readonly" />';
			}
			echo	'</td>';

			echo	'<td>';
			$key		=		$t['name'].'-hover';
			if		(array_key_exists($key, self::DEFAULTS ) ) {
				$value	=	preg_replace('/[^0-9]/', '', ($prop[$key] ?? '') );
				echo	'<input type="hidden"   name="properties['.$key.']" value="" />';
				echo	'<input type="checkbox" name="properties['.$key.']" value="1" '.($value ? 'checked="checked"' : '' ).' />';
			} else {
				echo	'<input type="checkbox" name="" value="" disabled="disabled" readonly="readonly" />';
			}
			echo	'</td>';

			echo	'</tr>';
		};
	echo	'</table>';

	submit_button();
	echo	'</div>';
