<?php defined('ABSPATH' ) || wp_die; ?>
<?php
	$title_list	=	array(
		array( 'name' => 'ex',	'type' => 'external',	'title' => __('External Link Settings',		'pz-linkcard' )	),
		array( 'name' => 'in',	'type' => 'internal',	'title' => __('Internal Link Settings',		'pz-linkcard' )	),
		array( 'name' => 'th',	'type' => 'samepage',	'title' => __('Same Page Link Settings',	'pz-linkcard' )	),
	);
	foreach ($title_list as $t) {
		echo	'<div class="pz-page'.$pz_page_active('pz-'.$t['type'] ).'" id="pz-'.$t['type'].'">';
		echo	'<div class="pz-submit-float">';
		submit_button();
		echo	'</div>';

		echo	'<h2>'.$t['title'].$help_open.$t['type'].'-link'.$help_close.'</h2>';

		// 繝・Φ繝励Ξ繝ｼ繝・
		$temp_color			=	'<tr><th scope="row">%s</th><td><input name="properties[%s]" type="color" value="%s" class="pz-sync-text pz-letter-color-code" /><input name="properties[%s]" type="text"  value="%s" class="pz-sync-text" /></td></tr>';
		$temp_text			=	'<tr><th scope="row">%s</th><td><input name="properties[%s]" type="text" value="%s" size="%s" class="%s" %s />%s</td></tr>';
		$temp_checkbox		=	'<tr><th scope="row">%s</th><td><label><input type="hidden" name="properties[%s]" value="" /><input type="checkbox" %s value="1" %s />%s</label></td></tr>';
		$temp_select		=	'<tr><th scope="row">%s</th><td><select %s class="%s" %s >%s</select></td>%s</tr>';
		$echo_card_appearance	=	function($t, $prop, $state = '' ) {
			$prefix			=	$t['name'].$state;
			$is_hover		=	($state === '-hover' );
			$enabled_default	=	$is_hover ? 0 : 1;

			echo				'<tr><th scope="row">'.__('Adjustment', 'pz-linkcard' ).'</th><td><span class="pz-card-prop-row">';
			$item_name			=	$prefix.'-transform-enabled';
			$item_value			=	isset($prop[$item_name] ) ? $prop[$item_name] : 1;
			echo				'<label class="pz-card-prop-switch"><span>'.esc_html__('Enabled', 'pz-linkcard' ).'</span><input type="hidden" name="properties['.$item_name.']" value="" /><input type="checkbox" name="properties['.$item_name.']" value="1" '.checked($item_value, 1, false ).' /><span class="pz-card-switch-ui"></span></label>';
			foreach	(array('x' => array(__('Horizontal', 'pz-linkcard' ), -100, 100, 0, 'px' ), 'y' => array(__('Vertical', 'pz-linkcard' ), -100, 100, 0, 'px' ), 'rotate' => array(__('Rotate', 'pz-linkcard' ), -180, 180, 0, 'deg' ), 'scale' => array(__('Scale', 'pz-linkcard' ), 1, 200, 100, '%' ) ) as $transform_key => $transform_item ) {
				$item_name		=	$prefix.'-transform-'.$transform_key;
				$item_value		=	isset($prop[$item_name] ) ? intval($prop[$item_name] ) : $transform_item[3];
				$item_center	=	($transform_item[1] < 0 || $transform_key === 'scale') ? ' data-center="'.esc_attr($transform_item[3] ).'"' : '';
				echo			'<label class="pz-card-prop-number"><span>'.esc_html($transform_item[0] ).'</span><span><input type="number" name="properties['.$item_name.']" value="'.esc_attr($item_value ).'" min="'.esc_attr($transform_item[1] ).'" max="'.esc_attr($transform_item[2] ).'" step="1" /><span>'.esc_html($transform_item[4] ).'</span><input type="range" class="pz-card-range" data-target="properties['.$item_name.']" value="'.esc_attr($item_value ).'" min="'.esc_attr($transform_item[1] ).'" max="'.esc_attr($transform_item[2] ).'" step="1"'.$item_center.' /></span></label>';
			}
			echo				'</span></td></tr>';

			echo				'<tr><th scope="row">'.__('Background Color', 'pz-linkcard' ).'</th><td><span class="pz-card-prop-row">';
			$item_name			=	$prefix.'-bg-enabled';
			$item_value			=	isset($prop[$item_name] ) ? $prop[$item_name] : $enabled_default;
			echo				'<label class="pz-card-prop-switch"><span>'.esc_html__('Enabled', 'pz-linkcard' ).'</span><input type="hidden" name="properties['.$item_name.']" value="" /><input type="checkbox" name="properties['.$item_name.']" value="1" '.checked($item_value, 1, false ).' /><span class="pz-card-switch-ui"></span></label>';
			$item_name			=	$prefix.'-bg-color';
			$item_value			=	isset($prop[$item_name] ) ? $prop[$item_name] : '';
			echo				'<label class="pz-card-prop-color"><span>'.esc_html__('Color', 'pz-linkcard' ).'</span><input type="text" name="properties['.$item_name.']" value="'.esc_attr($item_value ).'" class="pz-sync-text pz-color pz-monospace pz-color-picker" /></label>';
			$item_name			=	$prefix.'-image';
			$item_value			=	isset($prop[$item_name] ) ? esc_attr($prop[$item_name] ) : '';
			echo				'<label class="pz-card-prop-bulk"><span>'.esc_html__('Batch Specified Properties', 'pz-linkcard' ).'</span><input type="text" name="properties['.$item_name.']" value="'.$item_value.'" size="80" class="large-text" maxlength="80" /></label>';
			echo				'</span></td></tr>';

			echo				'<tr><th scope="row">'.__('Border Color', 'pz-linkcard' ).'</th><td><span class="pz-card-prop-row">';
			$item_name			=	$prefix.'-border-enabled';
			$item_value			=	isset($prop[$item_name] ) ? $prop[$item_name] : $enabled_default;
			echo				'<label class="pz-card-prop-switch"><span>'.esc_html__('Enabled', 'pz-linkcard' ).'</span><input type="hidden" name="properties['.$item_name.']" value="" /><input type="checkbox" name="properties['.$item_name.']" value="1" '.checked($item_value, 1, false ).' /><span class="pz-card-switch-ui"></span></label>';
			$item_name			=	$prefix.'-border-color';
			$item_value			=	isset($prop[$item_name] ) ? $prop[$item_name] : '';
			echo				'<label class="pz-card-prop-color"><span>'.esc_html__('Color', 'pz-linkcard' ).'</span><input type="text" name="properties['.$item_name.']" value="'.esc_attr($item_value ).'" class="pz-sync-text pz-color pz-monospace pz-color-picker" /></label>';
			$item_name			=	$prefix.'-border-style';
			$item_value			=	isset($prop[$item_name] ) ? $prop[$item_name] : 'solid';
			echo				'<label class="pz-card-prop-select"><span>'.esc_html__('Style', 'pz-linkcard' ).'</span><select name="properties['.$item_name.']">';
			foreach	(LIST_BORDER as $option_value => $option_text ) {
				echo			'<option value="'.esc_attr($option_value ).'"'.selected($item_value, $option_value, false ).'>'.esc_html($option_text ).'</option>';
			}
			echo				'</select></label>';
			$item_name			=	$prefix.'-border-width';
			$item_value			=	isset($prop[$item_name] ) ? intval($prop[$item_name] ) : 1;
			echo				'<label class="pz-card-prop-number"><span>'.esc_html__('Width', 'pz-linkcard' ).'</span><span><input type="number" name="properties['.$item_name.']" value="'.esc_attr($item_value ).'" min="0" max="64" step="1" /><span>px</span><input type="range" class="pz-card-range" data-target="properties['.$item_name.']" value="'.esc_attr($item_value ).'" min="0" max="64" step="1" /></span></label>';
			$item_name			=	$prefix.'-border-radius';
			$item_value			=	isset($prop[$item_name] ) ? intval($prop[$item_name] ) : 4;
			echo				'<label class="pz-card-prop-number"><span>'.esc_html__('Round a square', 'pz-linkcard' ).'</span><span><input type="number" name="properties['.$item_name.']" value="'.esc_attr($item_value ).'" min="0" max="64" step="1" /><span>px</span><input type="range" class="pz-card-range" data-target="properties['.$item_name.']" value="'.esc_attr($item_value ).'" min="0" max="64" step="1" /></span></label>';
			echo				'</span></td></tr>';

			echo				'<tr><th scope="row">'.__('Shadow', 'pz-linkcard' ).'</th><td><span class="pz-card-prop-row">';
			$item_name			=	$prefix.'-shadow-enabled';
			$item_value			=	isset($prop[$item_name] ) ? $prop[$item_name] : 0;
			echo				'<label class="pz-card-prop-switch"><span>'.esc_html__('Enabled', 'pz-linkcard' ).'</span><input type="hidden" name="properties['.$item_name.']" value="" /><input type="checkbox" name="properties['.$item_name.']" value="1" '.checked($item_value, 1, false ).' /><span class="pz-card-switch-ui"></span></label>';
			$item_name			=	$prefix.'-shadow-color';
			$item_value			=	isset($prop[$item_name] ) ? $prop[$item_name] : '#aaaacc';
			echo				'<label class="pz-card-prop-color"><span>'.esc_html__('Color', 'pz-linkcard' ).'</span><input type="text" name="properties['.$item_name.']" value="'.esc_attr($item_value ).'" class="pz-sync-text pz-color pz-monospace pz-color-picker" /></label>';
			foreach	(array('x' => __('Horizontal', 'pz-linkcard' ), 'y' => __('Vertical', 'pz-linkcard' ), 'blur' => __('Blur', 'pz-linkcard' ), 'spread' => __('Spread', 'pz-linkcard' ) ) as $shadow_key => $shadow_label ) {
				$item_name		=	$prefix.'-shadow-'.$shadow_key;
				$item_value		=	isset($prop[$item_name] ) ? intval($prop[$item_name] ) : ($shadow_key === 'spread' ? 0 : 8);
				$item_min		=	in_array($shadow_key, array('blur', 'spread' ), true ) ? 0 : -64;
				echo			'<label class="pz-card-prop-number"><span>'.esc_html($shadow_label ).'</span><span><input type="number" name="properties['.$item_name.']" value="'.esc_attr($item_value ).'" min="'.esc_attr($item_min ).'" max="64" step="1" /><span>px</span><input type="range" class="pz-card-range" data-target="properties['.$item_name.']" value="'.esc_attr($item_value ).'" min="'.esc_attr($item_min ).'" max="64" step="1" /></span></label>';
			}
			$item_name			=	$prefix.'-shadow-inset';
			$item_value			=	isset($prop[$item_name] ) ? $prop[$item_name] : 0;
			echo				'<label class="pz-card-prop-switch"><span>'.esc_html__('Inner Shadow', 'pz-linkcard' ).'</span><input type="hidden" name="properties['.$item_name.']" value="" /><input type="checkbox" name="properties['.$item_name.']" value="1" '.checked($item_value, 1, false ).' /><span class="pz-card-switch-ui"></span></label>';
			echo				'</span></td></tr>';

			echo				'<tr><th scope="row">'.esc_html('遷移速度' ).'</th><td><span class="pz-card-prop-row">';
			$item_name			=	$prefix.'-transition';
			$item_value			=	number_format(isset($prop[$item_name] ) ? floatval($prop[$item_name] ) : 0, 1, '.', '' );
			echo				'<label class="pz-card-prop-number"><span>'.esc_html('秒' ).'</span><span><input type="number" name="properties['.$item_name.']" value="'.esc_attr($item_value ).'" min="0" max="10" step="0.1" /><span>s</span><input type="range" class="pz-card-range" data-target="properties['.$item_name.']" value="'.esc_attr($item_value ).'" min="0" max="10" step="0.1" /></span></label>';
			echo				'</span></td></tr>';
		};

		// 蟆剰ｦ句・縺・
		echo	'<h3>'.__('Basic', 'pz-linkcard' ).'</h3>';
		echo	'<table class="form-table">';

		// 譁ｰ縺励＞繧ｿ繝悶〒髢九￥
		$item_name			=	$t['name'].'-target';
		if	(array_key_exists($item_name, Self::DEFAULTS ) ) {
			$item_value		=	esc_attr($prop[$item_name] );
			$item_list		=	LIST_NEWTAB;
			$item_title		=	__('Open New Window/Tab', 'pz-linkcard' );
			$item_notice	=	'';
			$item_enabled	=	true;
			echo_list($item_name, $item_value, $item_list, $item_title, $item_notice,  $item_enabled );
		} else {
			$item_value		=	'';
			$item_list		=	LIST_INTERNAL;
			$item_title		=	__('Open New Window/Tab', 'pz-linkcard' );
			$item_notice	=	'';
			$item_enabled	=	false;
			echo_list($item_name, $item_value, $item_list, $item_title, $item_notice,  $item_enabled );
		}

		echo			'</table>';

		echo	'<h3>'.__('Link Card', 'pz-linkcard' ).'</h3>';
		echo	'<table class="form-table">';

		// 譫邱壹・譖ｸ蠑・

		// 譫濶ｲ
		$echo_card_appearance($t, $prop );
		echo			'</table>';

		echo	'<h3>'.__('On Hover', 'pz-linkcard' ).'</h3>';
		echo	'<table class="form-table">';
		$echo_card_appearance($t, $prop, '-hover' );
		echo			'</table>';
		// 縲瑚ｨ倅ｺ句・螳ｹ縲阪・險ｭ螳壼ｧ九ａ
		$item_title		=	__('Article Content',	'pz-linkcard' );
		echo			'<h3>'.$item_title.'</h3>';
		echo			'<table class="form-table">';

		// 險倅ｺ九・蜿門ｾ玲婿豕・
		$item_title	=		__('Get Contents', 'pz-linkcard' );
		$item_name		=		$t['name'].'-get';
		$item_notice		=		'';
		if	(array_key_exists($item_name, Self::DEFAULTS ) ) {
			$s_name			=	'name="properties['.$item_name.']"';
			$item_class		=	'pz-sync-check';
			$s_switch		=	'';
			$item_value		=	esc_attr($prop[$item_name] );
			$item_value_list	=
				array(
					''			=>	__('Always extract from the latest articles', 								'pz-linkcard' ),
					'1'			=>	__('Always use the most recent article content. Prioritize "Excerpt"', 		'pz-linkcard' ),
					'3'			=>	__('Always use the most recent article content. Prioritize "Custom-Field"', 'pz-linkcard' ),
					'2'			=>	__('Always display the contents registered in card management', 			'pz-linkcard' ),
				);
			$s_option		=	'';
		} else {
			$s_name			=	'';
			$item_class		=	'';
			$s_switch		=	'disabled="disabled"';
			$item_value		=	'';
			if	($t['name'] == 'th' ) {
				$item_value_list	=	LIST_INTERNAL;
			} else {
				$item_value_list	=	array();
			}
		}
		foreach		($item_value_list		as	$value	=>	$description ) {
			
			if	(($t['name'] == 'ex' ) && ($value == '' || $value == '1' || $value == '3' ) ) {
				$dis		=	'disabled="disabled"';
			} else {
				$dis		=	'';
			}

			$s_option	.=	'<option value="'.$value.'" '.($item_value == $value ? 'selected="selected"' : '' ).' '.$dis.'>'.$description.'</option>';
		}
		echo	sprintf($temp_select,   $item_title, $s_name, $item_class, $s_switch, $s_option, $item_notice );

		// 繧ｿ繧､繝医Ν縺ｮ繧ｫ繧ｹ繧ｿ繝繝輔ぅ繝ｼ繝ｫ繝・
		$item_name			=	$t['name'].'-field-title';
		$item_value			=	'';
		$item_list			=	$meta_list;
		$item_title			=	__('Custom Field (Title)',		'pz-linkcard' );
		$item_notice		=	'';
		$item_class			=	'';
		$item_disabled		=	null;
		if	(array_key_exists($item_name, Self::DEFAULTS ) ) {
			$item_value		=	$prop[$item_name];
		} else {
			if	($t['name'] == 'th' ) {
				$item_value	=	__('Use the same setting as Internal Link', 'pz-linkcard' );
			}
			$item_disabled		=	'disabled="disabled"';
		}
		echo_combo($item_name, $item_value, $item_list, $item_title, $item_notice, $item_class, 99, $item_disabled );

		// 謚懃ｲ区枚縺ｮ繧ｫ繧ｹ繧ｿ繝繝輔ぅ繝ｼ繝ｫ繝・
		$item_name			=	$t['name'].'-field-excerpt';
		$item_value			=	'';
		$item_list			=	$meta_list;
		$item_title			=	__('Custom Field (Excerpt)',	'pz-linkcard' );
		$item_notice		=	'';
		$item_class			=	'';
		$item_disabled		=	null;
		if	(array_key_exists($item_name, Self::DEFAULTS ) ) {
			$item_value		=	$prop[$item_name];
		} else {
			if	($t['name'] == 'th' ) {
				$item_value	=	__('Use the same setting as Internal Link', 'pz-linkcard' );
			}
			$item_disabled		=	'disabled="disabled"';
		}
		echo_combo($item_name, $item_value, $item_list, $item_title, $item_notice, $item_class, 99, $item_disabled );

		// 
		switch	($t['name'] ) {
		case	'ex':
			$item_name		=	null;
			$item_value		=	null;
			$item_title		=	__('Reserved', 'pz-linkcard' );
			$item_notice	=	__('Reserved', 'pz-linkcard' );
			$item_enabled	=	false;
			echo	'<tr><th>'.$item_title.'</th><td>';
			echo_checkbox($item_name, $item_value, $item_list, $item_title, $item_notice, $item_enabled );
			echo	'</td></tr>';
			echo	'<tr><th>'.$item_title.'</th><td>';
			echo_checkbox($item_name, $item_value, $item_list, $item_title, $item_notice, $item_enabled );
			echo	'</td></tr>';
			break;

		case	'in':
			$item_name		=	'in-get-url';
			$item_value		=	$prop[$item_name];
			$item_list		=	null;
			$item_title		=	__('Get Redirect', 'pz-linkcard' );
			$item_notice	=	__('When the `Post ID` can not be acquired, it is acquired again.', 'pz-linkcard' );
			$item_enabled	=	true;
			echo	'<tr><th>'.$item_title.'</th><td>';
			echo_checkbox($item_name, $item_value, $item_list, $item_title, $item_notice, $item_enabled );
			echo	'</td></tr>';

			$item_name		=	null;
			$item_value		=	null;
			$item_list		=	null;
			$item_title		=	__('Reserved', 'pz-linkcard' );
			$item_notice	=	__('Reserved', 'pz-linkcard' );
			$item_enabled	=	false;
			echo	'<tr><th>'.$item_title.'</th><td>';
			echo_checkbox($item_name, $item_value, $item_list, $item_title, $item_notice, $item_enabled );
			echo	'</td></tr>';
			break;

		default:
			$item_name		=	null;
			$item_value		=	null;
			$item_title		=	__('Reserved', 'pz-linkcard' );
			$item_notice	=	__('Use the same setting as Internal Link', 'pz-linkcard' );
			$item_enabled	=	false;
			echo	'<tr><th>'.$item_title.'</th><td>';
			echo_checkbox($item_name, $item_value, $item_list, $item_title, $item_notice, $item_enabled );
			echo	'</td></tr>';
			echo	'<tr><th>'.$item_title.'</th><td>';
			echo_checkbox($item_name, $item_value, $item_list, $item_title, $item_notice, $item_enabled );
			echo	'</td></tr>';
		}
		echo	'</table>';

		// 縲後・繝・ム繝ｼ縲阪・險ｭ螳壼ｧ九ａ
		$item_title		=	__('Heading',	'pz-linkcard' );
		echo			'<h3>'.$item_title.'</h3>';
		echo			'<table class="form-table">';

		// 縲後・繝・ム繝ｼ縲阪・繝・く繧ｹ繝・
		$item_title			=	__('Text',	'pz-linkcard' );
		$item_notice		=	__('When a string is entered, it is overlaid on the top border.', 'pz-linkcard' );
		$item_class			=	'regular-text';
		$item_name			=	$t['name'].'-heading-text';
		$item_value			=	esc_attr($prop[$item_name] );
		$item_list		=	array(
			__('External site',			'pz-linkcard' ),
			__('This site',				'pz-linkcard' ),
			__('This page',				'pz-linkcard' ),
			__('Reference',				'pz-linkcard' ),
		);
		echo			'<tr><th scope="row">'.$item_title.'</th><td>';
		echo			'<label><input type="text" name="properties['.esc_attr($item_name ).']" value="'.esc_attr($item_value ).'" class="'.esc_attr($item_class ).'" list="datalist-'.esc_attr($item_name ).'" /></label>';
		echo			'<datalist id="datalist-'.esc_attr($item_name ).'">';
		foreach			($item_list			as	$value ) {
			echo		'<option value="'.esc_attr($value ).'">'.esc_attr($value ).'</option>';
		}
		echo			'</datalist>';
		if				($item_notice ) {
			echo		'<p>'.$item_notice.'</p>';
		}
		echo			'</td></tr>';
		echo			'</table>';

		// 縲後・繝・ム繝ｼ縲阪・險ｭ螳夂ｵゅｏ繧・
		echo		'</table>';


		// 縲檎ｶ壹″繧定ｪｭ繧繝懊ち繝ｳ縲阪・險ｭ螳壼ｧ九ａ
		$item_header		=	__('More',	'pz-linkcard' );
		echo			'<h3>'.$item_header.'</h3>';
		echo			'<table class="form-table">';

		// 縲檎ｶ壹″繧定ｪｭ繧繝懊ち繝ｳ縲阪・繝・く繧ｹ繝・
		$item_title			=	__('Text',	'pz-linkcard' );
		$item_notice		=	__('When a string is entered, it is overlaid on the lower right corner of the article content.', 'pz-linkcard' );
		$item_class			=	'regular-text';
		$item_name			=	$t['name'].'-more-text';
		$item_value			=	esc_attr($prop[$item_name] );
		$item_list		=	array(
			__('More...',				'pz-linkcard' ),
			__('Read more',				'pz-linkcard' ),
			__('Go read the article',	'pz-linkcard' ),
		);
		echo			'<tr><th scope="row">'.$item_title.'</th><td>';
		echo			'<label><input type="text" name="properties['.esc_attr($item_name ).']" value="'.esc_attr($item_value ).'" class="'.esc_attr($item_class ).'" list="datalist-'.esc_attr($item_name ).'" /></label>';
		echo			'<datalist id="datalist-'.esc_attr($item_name ).'">';
		foreach			($item_list			as	$value ) {
			echo		'<option value="'.esc_attr($value ).'">'.esc_attr($value ).'</option>';
		}
		echo			'</datalist>';
		if				($item_notice ) {
			echo		'<p>'.$item_notice.'</p>';
		}
		echo			'</td></tr>';
		echo			'</table>';

		// 縲後し繧､繝域ュ蝣ｱ縺ｮ霑ｽ蜉繝・く繧ｹ繝医阪・險ｭ螳壼ｧ九ａ
		$item_header		=	__('Site Information',	'pz-linkcard' );
		echo			'<h3>'.$item_header.'</h3>';
		echo			'<table class="form-table">';

		// 縲後し繧､繝域ュ蝣ｱ縺ｮ霑ｽ蜉縲阪・譫邱・
		$item_title			=	__('Text',	'pz-linkcard' );
		$item_notice		=	__('Enter a string to display after the site name.', 'pz-linkcard' );
		$item_class			=	'regular-text';
		$item_name			=	$t['name'].'-added-text';
		$item_value			=	esc_attr($prop[$item_name] );
		$item_list		=	array(
			__('External site',			'pz-linkcard' ),
			__('This site',				'pz-linkcard' ),
			__('This page',				'pz-linkcard' ),
		);
		echo			'<tr><th scope="row">'.$item_title.'</th><td>';
		echo			'<label><input type="text" name="properties['.esc_attr($item_name ).']" value="'.esc_attr($item_value ).'" class="'.esc_attr($item_class ).'" list="datalist-'.esc_attr($item_name ).'" /></label>';
		echo			'<datalist id="datalist-'.esc_attr($item_name ).'">';
		foreach			($item_list			as	$value ) {
			echo		'<option value="'.esc_attr($value ).'">'.esc_attr($value ).'</option>';
		}
		echo			'</datalist>';
		if				($item_notice ) {
			echo		'<p>'.$item_notice.'</p>';
		}
		echo			'</td></tr>';

		// 繧ｵ繧､繝医い繧､繧ｳ繝ｳ縺ｮ蜿門ｾ玲婿豕・
		$item_title	=			__('How to get Site-Icon', 'pz-linkcard' );
		$item_name			=	$t['name'].'-favicon';
		$item_notice		=	'';
		if	(array_key_exists($item_name, Self::DEFAULTS ) ) {
			$s_name			=	'name="properties['.$item_name.']"';
			$item_class		=	'';
			$s_switch		=	'';
			$item_value		=	esc_attr($prop[$item_name] );
			$item_value_list	=
				array(
					''		=>	__('None',					'pz-linkcard' ),
					'1'		=>	__('Direct',				'pz-linkcard' ),
					'13'	=>	__('Direct > Use WebAPI',	'pz-linkcard' ),
					'3'		=>	__('Use WebAPI',			'pz-linkcard' ),
				);
			$s_option		=	'';
		} else {
			$s_name			=	'';
			$item_class		=	'';
			$s_switch		=	'disabled="disabled"';
			$item_value		=	'';
			$item_value_list	=	LIST_INTERNAL;
		}
		foreach		($item_value_list		as	$value	=>	$description ) {
			$s_option	.=	'<option value="'.$value.'" '.($item_value == $value ? 'selected="selected"' : '' ).'>'.$description.'</option>';
		}
		echo	sprintf($temp_select,   $item_title, $s_name, $item_class, $s_switch, $s_option, $item_notice );

		// 繧ｵ繧､繝医い繧､繧ｳ繝ｳ縺ｮ莉｣譖ｿ繝・く繧ｹ繝・
		$item_title	=		__('Alternative text', 'pz-linkcard' );
		$item_name		=		$t['name'].'-favicon-alt';
		$s_len		=		'';
		$item_class	=		'regular-text';
		$item_notice		=		'';
		if	(array_key_exists($item_name, Self::DEFAULTS ) ) {
			$s_name		=	'name="properties['.$item_name.']"';
			$item_value	=	esc_attr($prop[$item_name] );
			$s_switch	=	'';
		} else {
			$s_name		=	'';
			$item_value	=	__('Use the same setting as Internal Link', 'pz-linkcard' );;
			$s_switch	=	'disabled="disabled"';
		}
		echo	sprintf($temp_text, $item_title, $s_name, $item_value, $s_len, $item_class, $s_switch, $item_notice );

		echo	'</table>';

		// 蟆剰ｦ句・縺・
		echo	'<h3>'.__('Thumbnail', 'pz-linkcard' ).'</h3>';
		echo	'<table class="form-table">';

		// 繧ｵ繝繝阪う繝ｫ縺ｮ蜿門ｾ玲婿豕・
		$item_title	=		__('Thumbnail', 'pz-linkcard' );
		$item_name		=		$t['name'].'-thumbnail';
		$item_notice		=		'';
		if	(array_key_exists($item_name, Self::DEFAULTS ) ) {
			$s_name			=	'name="properties['.$item_name.']"';
			$item_class		=	'pz-sync-check';
			$s_switch		=	'';
			$item_value		=	esc_attr($prop[$item_name] );
			$item_value_list	=
				array(
					''			=>	__('None',					'pz-linkcard' ),
					'1'			=>	__('Direct',				'pz-linkcard' ),
					'13'		=>	__('Direct > Use WebAPI',	'pz-linkcard' ),
					'3'			=>	__('Use WebAPI',			'pz-linkcard' ),
				);
			$s_option		=	'';
		} else {
			$s_name			=	'';
			$item_class		=	'';
			$s_switch		=	'disabled="disabled"';
			$item_value		=	'';
			$item_value_list	=	LIST_INTERNAL;
		}
		foreach		($item_value_list		as	$value	=>	$description ) {
			$s_option	.=	'<option value="'.$value.'" '.($item_value == $value ? 'selected="selected"' : '' ).'>'.$description.'</option>';
		}
		echo	sprintf($temp_select,   $item_title, $s_name, $item_class, $s_switch, $s_option, $item_notice );

		// 繧ｵ繝繝阪う繝ｫ縺ｮ繧ｵ繧､繧ｺ
		$item_title	=		__('Thumbnail Size', 'pz-linkcard' );
		$item_name		=		$t['name'].'-thumbnail-size';
		$item_notice		=		'';
		if	(array_key_exists($item_name, Self::DEFAULTS ) ) {
			$s_name			=	'name="properties['.$item_name.']"';
			$item_class		=	'pz-sync-check';
			$s_switch		=	'';
			$item_value		=	esc_attr($prop[$item_name] );
			$item_value_list	=
				array(
					'thumbnail'	=>	__('Thumbnail (150px)', 'pz-linkcard' ),
					'medium'	=>	__('Medium (300px)', 'pz-linkcard' ),
					'large'		=>	__('Large (1024px)', 'pz-linkcard' ),
					'full'		=>	__('Original Size', 'pz-linkcard' ),
				);
			$s_option		=	'';
		} else {
			$s_name			=	'';
			$item_class		=	'';
			$s_switch		=	'disabled="disabled"';
			$item_value		=	'';
			$item_value_list	=	LIST_INTERNAL;
		}
		foreach		($item_value_list		as	$value	=>	$description ) {
			$s_option	.=	'<option value="'.$value.'" '.($item_value == $value ? 'selected="selected"' : '' ).'>'.$description.'</option>';
		}
		echo	sprintf($temp_select,   $item_title, $s_name, $item_class, $s_switch, $s_option, $item_notice );

		// 繧ｵ繝繝阪う繝ｫ縺ｮ莉｣譖ｿ繝・く繧ｹ繝・
		$item_title	=		__('Thubnail Alt Text', 'pz-linkcard' );
		$item_name		=		$t['name'].'-thumbnail-alt';
		$s_len		=		'';
		$item_class	=		'regular-text';
		$item_notice		=		'';
		if	(array_key_exists($item_name, Self::DEFAULTS ) ) {
			$s_name		=	'name="properties['.$item_name.']"';
			$item_value	=	esc_attr($prop[$item_name] );
			$s_switch	=	'';
		} else {
			$s_name		=	'';
			$item_value	=	__('Use the same setting as Internal Link', 'pz-linkcard' );;
			$s_switch	=	'disabled="disabled"';
		}
		echo	sprintf($temp_text, $item_title, $s_name, $item_value, $s_len, $item_class, $s_switch, $item_notice );

		echo	'</table>';

		submit_button();
		echo	'</div>';
	}
