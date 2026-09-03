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

		// テンプレート
		$temp_color			=	'<tr><th scope="row">%s</th><td><input name="properties[%s]" type="color" value="%s" class="pz-sync-text pz-letter-color-code" /><input name="properties[%s]" type="text"  value="%s" class="pz-sync-text" /></td></tr>';
		$temp_text			=	'<tr><th scope="row">%s</th><td><input name="properties[%s]" type="text" value="%s" size="%s" class="%s" %s />%s</td></tr>';
		$temp_checkbox		=	'<tr><th scope="row">%s</th><td><label><input type="hidden" name="properties[%s]" value="" /><input type="checkbox" %s value="1" %s />%s</label></td></tr>';
		$temp_select		=	'<tr><th scope="row">%s</th><td><select %s class="%s" %s >%s</select></td>%s</tr>';

		// 小見出し
		echo	'<h3>'.__('Basic', 'pz-linkcard' ).'</h3>';
		echo	'<table class="form-table">';

		// 新しいタブで開く
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

		// 枠線の書式

		// 枠色
		echo				'<tr><th>'.__('Border Color', 'pz-linkcard' ).'</th><td>';
		$item_name		=	$t['name'].'-border-color';
		$item_value		=	$prop[$item_name];
		echo				'<label><input type="text"     name="properties['.$item_name.']" value="'.$item_value.'" class="pz-wp-color-picker" />';
		echo				'</td></tr>';

		// 背景色
		echo				'<tr><th>'.__('Background Color', 'pz-linkcard' ).'</th><td>';
		$item_name		=	$t['name'].'-bg-color';
		$item_value		=	$prop[$item_name];
		echo				'<label><input type="text"     name="properties['.$item_name.']" value="'.$item_value.'" class="pz-wp-color-picker" />';
		echo				'</td></tr>';

		// 背景色
		echo				'<tr><th>'.__('On Hover Color', 'pz-linkcard' ).'</th><td>';
		if	($t['name']	==	'th' ) {
			$item_value	=	__('Use the same setting as Internal Link', 'pz-linkcard' );
			echo			'<label><input type="text"     name="" value="'.$item_value.'" size="30" disabled="disabled" />';
		} else {
			$item_name		=	$t['name'].'-hover-bg-color';
			$item_value		=	$prop[$item_name];
			echo			'<label><input type="text"     name="properties['.$item_name.']" value="'.$item_value.'" class="pz-wp-color-picker" />';
		}
		echo			'</td></tr>';

		// 背景画像
		$item_name			=	$t['name'].'-image';
		$item_title			=	__('Background Image', 'pz-linkcard' );
		$item_notice		=	'';
		$item_value			=	'';
		$item_class			=	'large-text';
		$item_maxlength		=	80;
		$item_disabled		=	'';
		if	(array_key_exists($item_name, Self::DEFAULTS ) ) {
			$item_value		=	esc_attr($prop[$item_name] );
		} else {
			if	($t['name']	==	'th' ) {
				$item_value	=	__('Use the same setting as Internal Link', 'pz-linkcard' );
			}
			$item_disabled	=	'disabled="disabled"';
		}
		echo_text($item_name, $item_value, $item_title, $item_notice, $item_class, $item_maxlength, $item_disabled );

		echo			'</table>';

		// 「記事内容」の設定始め
		$item_title		=	__('Article Content',	'pz-linkcard' );
		echo			'<h3>'.$item_title.'</h3>';
		echo			'<table class="form-table">';

		// 記事の取得方法
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

		// タイトルのカスタムフィールド
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

		// 抜粋文のカスタムフィールド
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

		// 「ヘッダー」の設定始め
		$item_title		=	__('Heading',	'pz-linkcard' );
		echo			'<h3>'.$item_title.'</h3>';
		echo			'<table class="form-table">';

		// 「ヘッダー」のテキスト
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

		// 「ヘッダー」の設定終わり
		echo		'</table>';


		// 「続きを読むボタン」の設定始め
		$item_header		=	__('More',	'pz-linkcard' );
		echo			'<h3>'.$item_header.'</h3>';
		echo			'<table class="form-table">';

		// 「続きを読むボタン」のテキスト
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

		// 「サイト情報の追加テキスト」の設定始め
		$item_header		=	__('Site Information',	'pz-linkcard' );
		echo			'<h3>'.$item_header.'</h3>';
		echo			'<table class="form-table">';

		// 「サイト情報の追加」の枠線
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

		// サイトアイコンの取得方法
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

		// サイトアイコンの代替テキスト
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

		// 小見出し
		echo	'<h3>'.__('Thumbnail', 'pz-linkcard' ).'</h3>';
		echo	'<table class="form-table">';

		// サムネイルの取得方法
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

		// サムネイルのサイズ
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

		// サムネイルの代替テキスト
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
