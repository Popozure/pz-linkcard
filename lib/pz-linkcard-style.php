<?php defined('ABSPATH' ) || wp_die; ?>
<?php
	// スタイルシート出力先を準備
	$css_dir			=	PZLKC_DIR_STYLE;
	if	(!is_dir($css_dir ) ) {
		if	(!wp_mkdir_p($css_dir ) ) {
			$result			=	9;
			return;
		}
	}

	$result			=	0;
	$prop			=	$this->options;

	if (!isset($prop['style'] ) || !$prop['style'] ) {
		// テンプレートファイルを読み込む
		$file_text	=	file_get_contents(PZLKC_FILE_TEMPLATE );
		if ($file_text ) {
			// 特殊フォーマットごとの固定スタイル
			switch ($prop['special-format'] ) {
			case 'LkC': // Pz-LkC Default
				$file_text	=	str_replace('/*EX-BG-IMAGE*/',			'background-image: linear-gradient(#78f 0%, #78f 10%, #fff 30%);', $file_text );
				$file_text	=	str_replace('/*IN-BG-IMAGE*/',			'background-image: linear-gradient(#ca4 0%, #ca4 10%, #fff 30%);', $file_text );
				break;
			case 'hbc': // プリセット: はてなブログカード風
				$file_text	=	str_replace('/*EX-BORDER*/',			'border: 1px solid rgba(0,0,0,0.1);', $file_text );
				$file_text	=	str_replace('/*IN-BORDER*/',			'border: 1px solid rgba(0,0,0,0.1);', $file_text );
				$file_text	=	str_replace('/*SHADOW*/',				'box-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);', $file_text );
				break;
			case 'smp': // プリセット: シンプル
				$file_text	=	str_replace('/*EX-BG-COLOR*/',			'', $file_text );
				$file_text	=	str_replace('/*EX-BG-IMAGE*/',				'', $file_text );
				$file_text	=	str_replace('/*EX-BORDER*/',			'border: none;', $file_text );
				$file_text	=	str_replace('/*IN-BG-IMAGE*/',				'', $file_text );
				$file_text	=	str_replace('/*IN-BG-COLOR*/',			'', $file_text );
				$file_text	=	str_replace('/*IN-BORDER*/',			'border: none;', $file_text );
				$file_text	=	str_replace('/*NONE-INFO*/',			'display: none !important;', $file_text );
				$file_text	=	str_replace('/*NONE-EXCERPT*/',			'display: none !important;', $file_text );
				break;
			case 'cmp': // プリセット: コンパクト
				$file_text	=	str_replace('/*EX-BORDER*/',			'border: 1px solid rgba(0,0,0,0.1);', $file_text );
				$file_text	=	str_replace('/*IN-BORDER*/',			'border: 1px solid rgba(0,0,0,0.1);', $file_text );
				$file_text	=	str_replace('/*CONTENT-HEIGHT*/',		'height: 108px;', $file_text );
				$file_text	=	str_replace('/*WRAP-MARGIN*/',			'margin: 0;', $file_text );
				$file_text	=	str_replace('/*PADDING*/',				'padding: 0;', $file_text );
				$file_text	=	str_replace('/*CARD-TOP*/',				'margin: 0;', $file_text );
				$file_text	=	str_replace('/*CARD-BOTTOM*/',			'', $file_text );
				$file_text	=	str_replace('/*CARD-LEFT*/',			'', $file_text );
				$file_text	=	str_replace('/*CARD-RIGHT*/',			'', $file_text );
				$file_text	=	str_replace('/*MARGIN-TITLE*/',			'margin: 30px 0 0 108px;', $file_text );
				$file_text	=	str_replace('/*MARGIN-URL*/',			'margin: 0 0 0 108px;', $file_text );
				$file_text	=	str_replace('/*MARGIN-EXCERPT*/',		'margin: 0 0 0 108px;', $file_text );
				$file_text	=	str_replace('/*CONTENT-PADDING*/',		'padding: 0;', $file_text );
				$file_text	=	str_replace('/*CONTENT-MARGIN*/',		'margin: 0;', $file_text );

				$file_text	=	str_replace('/*THUMBNAIL-WIDTH*/',		'display: block; overflow: hidden;', $file_text );
				$file_text	=	str_replace('/*THUMBNAIL-HEIGHT*/',		'height: 108px;', $file_text );
				$file_text	=	str_replace('/*THUMBNAIL-IMG-WIDTH*/',	'width: 100px;', $file_text );
				$file_text	=	str_replace('/*THUMBNAIL-IMG-HEIGHT*/',	'height: 108px;', $file_text );
				$file_text	=	str_replace('/*THUMBNAIL-POSITION*/',	'float: left;', $file_text );
				$file_text	=	str_replace('/*THUMBNAIL-MARGIN*/',		'margin: 0 8px 0 0;', $file_text );
				$file_text	=	str_replace('/*THUMBNAIL-RADIUS*/',		'border-radius: 16px 0 0 16px;', $file_text );
				$file_text	=	str_replace('/*POSITION-INFO*/',		'position: absolute; top: 8px; left: 108px;', $file_text );
				$file_text	=	str_replace('/*NONE-INFO*/',			'display: none !important;', $file_text );
				break;
			case 'JIN': // プリセット: JIN風
				$file_text	=	str_replace('/*MARGIN-TOP*/',			'margin: 24px auto 30px auto;', $file_text );
				$file_text	=	str_replace('/*MARGIN-BOTTOM*/',		'', $file_text );
				$file_text	=	str_replace('/*MARGIN-LEFT*/',			'', $file_text );
				$file_text	=	str_replace('/*MARGIN-RIGHT*/',			'', $file_text );
				$file_text	=	str_replace('/*CARD-TOP*/',				'margin: 24px 20px 20px 20px !important;', $file_text );
				$file_text	=	str_replace('/*CARD-BOTTOM*/',			'', $file_text );
				$file_text	=	str_replace('/*CARD-LEFT*/',			'', $file_text );
				$file_text	=	str_replace('/*CARD-RIGHT*/',			'', $file_text );
				$file_text	=	str_replace('/*WRAP-MARGIN*/',			'margin: 0 auto;', $file_text );
				$file_text	=	str_replace('/*EX-BG-COLOR*/',			'', $file_text );
				$file_text	=	str_replace('/*EX-BG-IMAGE*/',			'', $file_text );
				$file_text	=	str_replace('/*IN-BG-IMAGE*/',			'', $file_text );
				$file_text	=	str_replace('/*IN-BG-COLOR*/',			'', $file_text );
				$file_text	=	str_replace('/*OPTION*/',				'.linkcard p { display: none; }', $file_text );
				foreach		(array('ex', 'in' )	as	$t ) {
					$T		=	strtoupper($t );
					$file_text		=	str_replace('/*'.$T.'-HEADING*/',		'padding: 0 16px !important; position: absolute; top: -15px; left: 20px; padding: 0 10px; height: 20px; '.txt_color('background-color: ', $prop['in-border-color'] ).';', $file_text );
					$value_border	=	'border: solid '.($prop[$t.'-border-color'] ?? '').' 4px;';
					$file_text		=	str_replace('/*'.$T.'-BORDER*/',			$value_border, $file_text );
					$file_text		=	str_replace('/*'.$T.'-HEADING-BORDER*/',	$value_border, $file_text );
					$value_bg_color	=	'background-color: '.$prop[$t.'-border-color'] ?? '';
					$file_text		=	str_replace('/*'.$T.'-HEADING-BG-COLOR*/',	$value_bg_color.';', $file_text );
					$file_text		=	str_replace('/*'.$T.'-HOVER-HEADING-BG-COLOR*/',	$value_bg_color.';', $file_text );
					$file_text		=	str_replace('/*'.$T.'-HOVER-OPTION*/',		'', $file_text );
				}
				if (isset($prop['flg-resize'] ) && $prop['flg-resize'] == '1' ) {
					$size_title			=	intval(preg_replace('/[^0-9]/', '', isset($prop['title-size'] ) ? $prop['title-size'] : self::pz_GetDefaultOption('title-size' ) ) );
					$size_excerpt		=	intval(preg_replace('/[^0-9]/', '', isset($prop['excerpt-size'] ) ? $prop['excerpt-size'] : self::pz_GetDefaultOption('excerpt-size' ) ) );
					$height_title		=	intval(preg_replace('/[^0-9]/', '', isset($prop['title-height'] ) ? $prop['title-height'] : self::pz_GetDefaultOption('title-height' ) ) );
					$height_excerpt		=	intval(preg_replace('/[^0-9]/', '', isset($prop['excerpt-height'] ) ? $prop['excerpt-height'] : self::pz_GetDefaultOption('excerpt-height' ) ) );
					$thumbnail_width	=	150;
					$file_text	=	str_replace('/*RESIZE*/',
						'@media screen and (max-width: 767px)  { .lkc-internal-wrap { max-width: 100% } .lkc-external-wrap { max-width: 100% } .lkc-title { font-size: '.intval($size_title * 0.9).'px; line-height: '.intval($height_title * 0.9).'px; } .lkc-excerpt { font-size: '.intval($size_excerpt * 0.95).'px; } .lkc-thumbnail { max-width: '.intval($thumbnail_width * 0.9).'px; } .lkc-thumbnail-img { max-width: '.intval($thumbnail_width * 0.9).'px; } }'.
						'@media screen and (max-width: 512px)  { .lkc-internal-wrap { max-width: 100% } .lkc-external-wrap { max-width: 100% } .lkc-title { font-size: '.intval($size_title * 0.8).'px; line-height: '.intval($height_title * 0.8).'px; } .lkc-excerpt { font-size: '.intval($size_excerpt * 0.80).'px; } .lkc-thumbnail { max-width: '.intval($thumbnail_width * 0.7).'px; } .lkc-thumbnail-img { max-width: '.intval($thumbnail_width * 0.7).'px; } }'.
						'@media screen and (max-width: 320px)  { .lkc-internal-wrap { max-width: 100% } .lkc-external-wrap { max-width: 100% } .lkc-title { font-size: '.intval($size_title * 0.7).'px; line-height: '.intval($height_title * 0.7).'px; } .lkc-excerpt { font-size: '.intval($size_excerpt * 0.60).'px; } .lkc-thumbnail { max-width: '.intval($thumbnail_width * 0.5).'px; } .lkc-thumbnail-img { max-width: '.intval($thumbnail_width * 0.5).'px; } }', $file_text );
				}
				$file_text		=	str_replace('/*SCALE*/',		'transform: scale(1.1);', $file_text );
				$file_text		=	str_replace('/*TRANSFORM*/',	'-webkit-transition: color 0.4s ease, background 0.4s ease, transform 0.4s ease, opacity 0.4s ease, border 0.4s ease, padding 0.4s ease, left 0.4s ease, box-shadow 0.4s ease; transition: color 0.4s ease, background 0.4s ease, transform 0.4s ease, opacity 0.4s ease, border 0.4s ease, padding 0.4s ease, left 0.4s ease, box-shadow 0.4s ease;', $file_text );
				break;
			case 'ecl': // プリセット: 囲み
				$css	=	'.lkc-external-wrap         , .lkc-internal-wrap { transition: all 0.7s ease-in-out; border-width: 2px; }';
				$css	.=	'.lkc-external-wrap::before , .lkc-internal-wrap::before { content: ""; display: block; position: absolute; border: 2px solid #888888; box-sizing: border-box; width: 24px; height: 24px; transition: all 0.7s ease-in-out; top: -6px; left: -6px; border-width: 2px 0 0 2px; }';
				$css	.=	'.lkc-external-wrap::after  , .lkc-internal-wrap::after  { content: ""; display: block; position: absolute; border: 2px solid #888888; box-sizing: border-box; width: 24px; height: 24px; transition: all 0.7s ease-in-out; bottom: -6px; right: -6px; border-width: 0 2px 2px 0; }';
				$css	.=	'.lkc-external-wrap:hover         { '.txt_color('border-color: ', $prop['ex-bg-color'] ).' }';
				$css	.=	'.lkc-internal-wrap:hover         { '.txt_color('border-color: ', $prop['in-bg-color'] ).' }';
				$css	.=	'.lkc-external-wrap:hover::before { width: calc(100% + 12px); height: calc(100% + 12px); '.txt_color('border-color: ', $prop['ex-bg-color'] ).' }';
				$css	.=	'.lkc-internal-wrap:hover::before { width: calc(100% + 12px); height: calc(100% + 12px); '.txt_color('border-color: ', $prop['in-bg-color'] ).' }';
				$css	.=	'.lkc-external-wrap:hover::after  { width: calc(100% + 12px); height: calc(100% + 12px); '.txt_color('border-color: ', $prop['ex-bg-color'] ).' }';
				$css	.=	'.lkc-internal-wrap:hover::after  { width: calc(100% + 12px); height: calc(100% + 12px); '.txt_color('border-color: ', $prop['in-bg-color'] ).' }';
				$file_text	=	str_replace('/*OPTION*/',			$css, $file_text );
				break;
			case 'ref': // プリセット: 反射
				$css	=	'.lkc-external-wrap               , .lkc-internal-wrap { overflow: hidden; }';
				$css	.=	'.lkc-external-wrap:hover::before , .lkc-internal-wrap:hover::before { margin-left: 300% ; }';
				$css	.=	'.lkc-external-wrap::before       , .lkc-internal-wrap::before       { content: ""; display: block; width: 500px; height: 120px; position: absolute; top: -10px; left: -500px; transform: rotate(-45deg); transition: all .3s ease-in-out; }';
				$css	.=	'.lkc-external-wrap::before { '.txt_color('background-color: ',  $prop['ex-border-color'] ).' }';
				$css	.=	'.lkc-internal-wrap::before { '.txt_color('background-color: ',  $prop['in-border-color'] ).' }';
				$file_text	=	str_replace('/*OPTION*/', $css, $file_text );
				break;
			case 'wxp': // Windows XP
				$file_text	=	str_replace('/*EX-BORDER*/',		'border: none;',	$file_text );
				$file_text	=	str_replace('/*IN-BORDER*/',		'border: none;',	$file_text );
				$file_text	=	str_replace('/*THUMBNAIL-MARGIN*/',	'margin: 0 8px;',	$file_text );
				$file_text	=	str_replace('/*CONTENT-MARGIN*/',	'margin: 8px 0;',	$file_text );
				$css	=	'.lkc-external-wrap a , .lkc-internal-wrap a { cursor: default; }';
				$css	.=	'.lkc-unlink *	{ color: #888; }';
				$css	.=	'.lkc-card		{ margin: 16px; padding: 0; border: 3px #1f61e3 solid; border-radius: 5px; background: #eeecdf; }';
				$css	.=	'.lkc-info		{ margin: 0; padding: 4px; background: linear-gradient(to bottom, #2790ff, #1f61e3); background: -webkit-linear-gradient(top, #2790ff, #1f61e3); font-weight: bold; font-size: 11px; line-height: 16px; }';
				$css	.=	'.lkc-info *	{ color: #fff !important; }';
				$css	.=	'.lkc-thumbnail	{ margin: 0 8px !important; }';
				$css	.=	'.lkc-title		{ padding: 0; }';
				$css	.=	'.lkc-url		{ padding: 4px; cursor: pointer; }';
				$css	.=	'.lkc-excerpt	{ padding: 4px; }';
				$file_text	=	str_replace('/*OPTION*/', $css, $file_text );
				break;
			case 'w95': // Windows 95
				$file_text	=	str_replace('/*EX-BORDER*/',		'border: none;', $file_text );
				$file_text	=	str_replace('/*IN-BORDER*/',		'border: none;', $file_text );
				$file_text	=	str_replace('/*CONTENT-MARGIN*/',	'margin: 4px 0;', $file_text );
				$css	=	'.lkc-external-wrap a , .lkc-internal-wrap a { cursor: default; }';
				$css	.=	'.lkc-unlink *	{ color: #888; }';
				$css	.=	'.lkc-card		{ margin: 16px; padding: 4px; border: 3px #c0c7c8 solid; background: #e0e0e0; border: 1px #87888f solid; }';
				$css	.=	'.lkc-info		{ margin: 0; padding: 4px; border: 1px #87888f solid; background: #0000a8; font-weight: bold; font-size: 11px; line-height: 16px; }';
				$css	.=	'.lkc-info *	{ color: #fff !important; font-weight: normal; }';
				$css	.=	'.lkc-thumbnail	{ margin: 0 4px !important; }';
				$css	.=	'.lkc-title		{ padding: 0; }';
				$css	.=	'.lkc-url		{ padding: 4px; cursor: pointer; }';
				$css	.=	'.lkc-excerpt	{ padding: 4px; }';
				$file_text	=	str_replace('/*OPTION*/', $css, $file_text );
				break;
			case 'ct1': // プリセット: セロファンテープ中央
				$file_text	=	str_replace('/*WRAP-BEFORE*/',		'content: ""; display: block; position: absolute; left:   40%; top: -16px; width: 95px; height: 25px; z-index: 2; background-color: rgba(243,245,228,0.5); border: 2px solid rgba(255,255,255,0.5); -webkit-box-shadow: 1px 1px 4px rgba(200,200,180,0.8); -moz-box-shadow: 1px 1px 4px rgba(200,200,180,0.8); box-shadow: 1px 1px 4px rgba(200,200,180,0.8); -webkit-transform: rotate(3deg); -moz-transform: rotate(3deg); -o-transform: rotate(3deg);', $file_text );
				$file_text	=	str_replace('/*SHADOW*/',			'box-shadow: 0px 0px 2px rgba(0, 0, 0, 0.2);', $file_text );
				break;
			case 'ct2': // プリセット: セロファンテープ左右
				$file_text	=	str_replace('/*MARGIN-LEFT*/',		'padding-left: 40px;', $file_text );
				$file_text	=	str_replace('/*MARGIN-RIGHT*/',		'padding-right: 25px;', $file_text );
				$file_text	=	str_replace('/*WRAP-BEFORE*/',		'content: ""; display: block; position: absolute; left:  -40px; top: -4px; width: 75px; height: 25px; z-index: 2; background-color: rgba(243,245,228,0.5); border: 2px solid rgba(255,255,255,0.5); -webkit-box-shadow: 1px 1px 4px rgba(200,200,180,0.8); -moz-box-shadow: 1px 1px 4px rgba(200,200,180,0.8); box-shadow: 1px 1px 4px rgba(200,200,180,0.8); -webkit-transform: rotate(-45deg); -moz-transform: rotate(-45deg); -o-transform: rotate(-45deg);', $file_text );
				$file_text	=	str_replace('/*WRAP-AFTER*/',		'content: ""; display: block; position: absolute; right: -20px; top: -2px; width: 75px; height: 25px; z-index: 2; background-color: rgba(243,245,228,0.5); border: 2px solid rgba(255,255,255,0.5); -webkit-box-shadow: 1px 1px 4px rgba(200,200,180,0.8); -moz-box-shadow: 1px 1px 4px rgba(200,200,180,0.8); box-shadow: 1px 1px 4px rgba(200,200,180,0.8); -webkit-transform: rotate(16deg); -moz-transform: rotate(16deg); -o-transform: rotate(16deg); transform: rotate(16deg);', $file_text );
				$file_text	=	str_replace('/*SHADOW*/',			'box-shadow: 0px 0px 2px rgba(0, 0, 0, 0.2);', $file_text );
				break;
			case 'ct3': // プリセット: セロファンテープ上部
				$file_text	=	str_replace('/*MARGIN-LEFT*/',		'padding-left: 32px;', $file_text );
				$file_text	=	str_replace('/*MARGIN-RIGHT*/',		'padding-right: 32px;', $file_text );
				$file_text	=	str_replace('/*WRAP-BEFORE*/', 		'content: ""; display: block; position: absolute; left:   -5%; top: -12px; width: 110%; height: 25px; z-index: 2; background-color: rgba(243,245,228,0.5); border: 2px solid rgba(255,255,255,0.5); -webkit-box-shadow: 1px 1px 4px rgba(200,200,180,0.8); -moz-box-shadow: 1px 1px 4px rgba(200,200,180,0.8); box-shadow: 1px 1px 4px rgba(200,200,180,0.8); -webkit-transform: rotate(-3deg); -moz-transform: rotate(-3deg); -o-transform: rotate(-3deg);', $file_text );
				$file_text	=	str_replace('/*SHADOW*/',			'box-shadow: 0px 0px 2px rgba(0, 0, 0, 0.2);', $file_text );
				break;
			case 'ct4': // プリセット: セロファンテープ左上
				$file_text	=	str_replace('/*MARGIN-LEFT*/',		'padding-left: 24px;', $file_text );
				$file_text	=	str_replace('/*WRAP-BEFORE*/',		'content: ""; display: block; position: absolute; left:  -24px; top: 0px; width: 200px; height: 25px; z-index: 2; background-color: rgba(243,245,228,0.5); border: 2px solid rgba(255,255,255,0.5); -webkit-box-shadow: 1px 1px 4px rgba(200,200,180,0.8); -moz-box-shadow: 1px 1px 4px rgba(200,200,180,0.8); box-shadow: 1px 1px 4px rgba(200,200,180,0.8); -webkit-transform: rotate(-8deg); -moz-transform: rotate(-8deg); -o-transform: rotate(-8deg);', $file_text );
				$file_text	=	str_replace('/*SHADOW*/',			'box-shadow: 0px 0px 2px rgba(0, 0, 0, 0.2);', $file_text );
				break;
			case 'ppc': // プリセット: 紙めくれ
				$file_text	=	str_replace('/*WRAP-AFTER*/',		'z-index: -1; content:""; height: 10px; width: 60%; position: absolute; right: 16px; bottom: 14px; left: auto; transform: skew(5deg) rotate(3deg); -webkit-transform: skew(5deg) rotate(3deg); -moz-transform: skew(5deg) rotate(3deg); box-shadow: 0 16px 16px rgba(0,0,0,1); -webkit-box-shadow: 0 16px 16px rgba(0,0,0,1); -moz-box-shadow: 0 16px 12px rgba(0,0,0,1);', $file_text );
				$file_text	=	str_replace('/*SHADOW*/',			'box-shadow: 0px 2px 6px rgba(0, 0, 0, 0.8);', $file_text );
				$file_text	=	str_replace('/*OPTION*/',			'article { position: relative; z-index: 0; } article blockquote { position: relative; z-index: 0; }', $file_text );
				break;
			case 'tac': // プリセット: テープと紙めくれ
				$file_text	=	str_replace('/*MARGIN-LEFT*/',		'padding-left: 24px;', $file_text );
				$file_text	=	str_replace('/*WRAP-BEFORE*/',		'content: ""; display: block; position: absolute; left:  -24px; top: 0px; width: 200px; height: 25px; z-index: 2; background-color: rgba(243,245,228,0.5); border: 2px solid rgba(255,255,255,0.5); -webkit-box-shadow: 1px 1px 4px rgba(200,200,180,0.8); -moz-box-shadow: 1px 1px 4px rgba(200,200,180,0.8); box-shadow: 1px 1px 4px rgba(200,200,180,0.8); -webkit-transform: rotate(-8deg); -moz-transform: rotate(-8deg); -o-transform: rotate(-8deg);', $file_text );
				$file_text	=	str_replace('/*WRAP-AFTER*/',		'z-index: -1; content:""; height: 10px; width: 60%; position: absolute; right: 16px; bottom: 14px; left: auto; transform: skew(5deg) rotate(3deg); -webkit-transform: skew(5deg) rotate(3deg); -moz-transform: skew(5deg) rotate(3deg); box-shadow: 0 16px 16px rgba(0,0,0,1); -webkit-box-shadow: 0 16px 16px rgba(0,0,0,1); -moz-box-shadow: 0 16px 12px rgba(0,0,0,1);', $file_text );
				$file_text	=	str_replace('/*SHADOW*/',			'box-shadow: 0px 2px 6px rgba(0, 0, 0, 0.8);', $file_text );
				$file_text	=	str_replace('/*OPTION*/',			'article { position: relative; z-index: 0; } article blockquote { position: relative; z-index: 0; }', $file_text );
				break;
			case 'sBR': // プリセット: 縫い目 赤系
				$file_text	=	str_replace('/*EX-BORDER*/',		'border: 2px dashed rgba(255,255,255,0.5);', $file_text );
				$file_text	=	str_replace('/*IN-BORDER*/',		'border: 2px dashed rgba(255,255,255,0.5);', $file_text );
				$file_text	=	str_replace('/*EX-BG-COLOR*/',		'background: #bcddff; box-shadow: 0 0 0 5px #aabbee, 3px 3px 6px 4px rgba(0,0,0,0.6); -moz-box-shadow: 0 0 0 5px #aabbee, 3px 3px 6px 4px rgba(0,0,0,0.6); -webkit-box-shadow: 0 0 0 5px #aabbee, 3px 3px 6px 4px rgba(0,0,0,0.6);', $file_text );
				$file_text	=	str_replace('/*IN-BG-COLOR*/',		'background: #f8d0d0; box-shadow: 0 0 0 5px #e8a8a8, 3px 3px 6px 4px rgba(0,0,0,0.6); -moz-box-shadow: 0 0 0 5px #e8a8a8, 3px 3px 6px 4px rgba(0,0,0,0.6); -webkit-box-shadow: 0 0 0 5px #e8a8a8, 3px 3px 6px 4px rgba(0,0,0,0.6);', $file_text );
				break;
			case 'sGY': // プリセット: 縫い目 緑黄系
				$file_text	=	str_replace('/*EX-BORDER*/',		'border: 2px dashed rgba(255,255,255,0.5);', $file_text );
				$file_text	=	str_replace('/*IN-BORDER*/',		'border: 2px dashed rgba(255,255,255,0.5);', $file_text );
				$file_text	=	str_replace('/*EX-BG-COLOR*/',		'background: #acefdd; box-shadow: 0 0 0 5px #8abecb, 3px 3px 6px 4px rgba(0,0,0,0.6); -moz-box-shadow: 0 0 0 5px #8abecb, 3px 3px 6px 4px rgba(0,0,0,0.6); -webkit-box-shadow: 0 0 0 5px #8abecb, 3px 3px 6px 4px rgba(0,0,0,0.6);', $file_text );
				$file_text	=	str_replace('/*IN-BG-COLOR*/',		'background: #ffde51; box-shadow: 0 0 0 5px #fbca4d, 3px 3px 6px 4px rgba(0,0,0,0.6); -moz-box-shadow: 0 0 0 5px #fbca4d, 3px 3px 6px 4px rgba(0,0,0,0.6); -webkit-box-shadow: 0 0 0 5px #fbca4d, 3px 3px 6px 4px rgba(0,0,0,0.6);', $file_text );
				break;
			case 'pin': // プリセット: 押しピン
				$file_text	=	str_replace('/*WRAP-AFTER*/',		'content: ""; display: block; position: absolute; background-image: url("'.$this->plugin_dir_url.'img/pin.png"); background-repeat: no-repeat; background-position: center; left: 47%; top: -16px; width: 40px; height: 40px; z-index: 1; pointer-events: none;', $file_text );
				break;
			case 'inN': // プリセット: Ingress Neutral
				$color		=	'#59fbea';
				$file_text	=	str_replace('/*EX-BORDER*/', 		'border: 4px solid '.$color.';', $file_text );
				$file_text	=	str_replace('/*IN-BORDER*/', 		'border: 4px solid '.$color.';', $file_text );
				$file_text	=	str_replace('/*TITLE-COLOR*/',		'color: '.$color.';', $file_text );
				$file_text	=	str_replace('/*URL-COLOR*/',		'color: '.$color.';', $file_text );
				$file_text	=	str_replace('/*EXCERPT-COLOR*/',	'color: '.$color.';', $file_text );
				$file_text	=	str_replace('/*MORE-COLOR*/',		'color: '.$color.';', $file_text );
				$file_text	=	str_replace('/*INFO-COLOR*/',		'color: '.$color.';', $file_text );
				$file_text	=	str_replace('/*ADDED-COLOR*/',		'color: '.$color.';', $file_text );
				$file_text	=	str_replace('/*EX-BG-COLOR*/',		'background-color: rgba(  35 , 100 ,  93 , 0.90 );', $file_text );
				$file_text	=	str_replace('/*IN-BG-COLOR*/',		'background-color: rgba(   8 ,  25 ,  23 , 0.90 );', $file_text );
				break;
			case 'inI': // プリセット: Ingress Enlightened
				$color		=	'#ebbc4a';
				$file_text	=	str_replace('/*EX-BORDER*/', 		'border: 4px solid '.$color.';', $file_text );
				$file_text	=	str_replace('/*IN-BORDER*/', 		'border: 4px solid '.$color.';', $file_text );
				$file_text	=	str_replace('/*TITLE-COLOR*/',		'color: '.$color.';', $file_text );
				$file_text	=	str_replace('/*URL-COLOR*/',		'color: '.$color.';', $file_text );
				$file_text	=	str_replace('/*EXCERPT-COLOR*/',	'color: '.$color.';', $file_text );
				$file_text	=	str_replace('/*MORE-COLOR*/',		'color: '.$color.';', $file_text );
				$file_text	=	str_replace('/*INFO-COLOR*/',		'color: '.$color.';', $file_text );
				$file_text	=	str_replace('/*ADDED-COLOR*/',		'color: '.$color.';', $file_text );
				$file_text	=	str_replace('/*EX-BG-COLOR*/',		'background-color: rgba(  94 ,  75, 29 , 0.90 );', $file_text );
				$file_text	=	str_replace('/*IN-BG-COLOR*/',		'background-color: rgba(  23 ,  18,  7 , 0.90 );', $file_text );
				break;
			case 'inE': // プリセット: Ingress Enlightened Color
				$color		=	'#28f428';
				$file_text	=	str_replace('/*EX-BORDER*/', 		'border: 4px solid '.$color.';', $file_text );
				$file_text	=	str_replace('/*IN-BORDER*/', 		'border: 4px solid '.$color.';', $file_text );
				$file_text	=	str_replace('/*TITLE-COLOR*/',		'color: '.$color.';', $file_text );
				$file_text	=	str_replace('/*URL-COLOR*/',		'color: '.$color.';', $file_text );
				$file_text	=	str_replace('/*EXCERPT-COLOR*/',	'color: '.$color.';', $file_text );
				$file_text	=	str_replace('/*MORE-COLOR*/',		'color: '.$color.';', $file_text );
				$file_text	=	str_replace('/*INFO-COLOR*/',		'color: '.$color.';', $file_text );
				$file_text	=	str_replace('/*ADDED-COLOR*/',		'color: '.$color.';', $file_text );
				$file_text	=	str_replace('/*EX-BG-COLOR*/',		'background-color: rgba(  16 ,  97 ,  16 , 0.90 );', $file_text );
				$file_text	=	str_replace('/*IN-BG-COLOR*/',		'background-color: rgba(   4 ,  24 ,   4 , 0.90 );', $file_text );
				break;
			case 'inR': // プリセット: Ingress Resistance Color
				$color		=	'#00c2ff';
				$file_text	=	str_replace('/*EX-BORDER*/', 		'border: 4px solid '.$color.';',	$file_text );
				$file_text	=	str_replace('/*IN-BORDER*/', 		'border: 4px solid '.$color.';',	$file_text );
				$file_text	=	str_replace('/*TITLE-COLOR*/',		'color: '.$color.';',	$file_text );
				$file_text	=	str_replace('/*URL-COLOR*/',		'color: '.$color.';',	$file_text );
				$file_text	=	str_replace('/*EXCERPT-COLOR*/',	'color: '.$color.';',	$file_text );
				$file_text	=	str_replace('/*MORE-COLOR*/',		'color: '.$color.';',	$file_text );
				$file_text	=	str_replace('/*INFO-COLOR*/',		'color: '.$color.';',	$file_text );
				$file_text	=	str_replace('/*ADDED-COLOR*/',		'color: '.$color.';',	$file_text );
				$file_text	=	str_replace('/*EX-BG-COLOR*/',		'background-color: rgba(   0 ,  77 , 102 , 0.90 );',	$file_text );
				$file_text	=	str_replace('/*IN-BG-COLOR*/',		'background-color: rgba(   0 ,  19 ,  25 , 0.90 );',	$file_text );
				break;
			case 'slt': // プリセット: 斜め
				$file_text	=	str_replace('/*WRAP*/',					'transform:skew(-10deg) rotate(1deg); -webkit-transform: skew(-10deg) rotate(1deg); -moz-transform:skew(-10deg) rotate(1deg);', $file_text );
				$file_text	=	str_replace('/*MARGIN-LEFT*/',			'padding-left: 12px;', $file_text );
				$file_text	=	str_replace('/*MARGIN-RIGHT*/',			'padding-right: 30px;', $file_text );
				break;
			case '3Dr': // プリセット: 3D回転
				$file_text	=	str_replace('/*WRAP*/',					'-webkit-transform:perspective(150px) scale3d(0.84,0.9,1) rotate3d(1,0,0,12deg);',			$file_text );
				$file_text	=	str_replace('/*SHADOW*/',				'box-shadow: 0 20px 16px rgba(0, 0, 0, 0.6) , 0px 32px 32px rgba(0, 0, 0, 0.2) inset;',		$file_text );
				break;
			case 'sqr': // プリセット: スクエア
				$file_text	=	str_replace('/*HEIGHT*/',				'height: 340px;',	$file_text );
				$file_text	=	str_replace('/*CONTENT-HEIGHT*/',		'height: 340px;',	$file_text );
				$file_text	=	str_replace('/*THUMBNAIL-POSITION*/',	'display: block;',	$file_text );
				$file_text	=	str_replace('/*THUMBNAIL-MARGIN*/',		'margin: 0;',		$file_text );
				$file_text	=	str_replace('/*THUMBNAIL-WIDTH*/',		'',					$file_text );
				$file_text	=	str_replace('/*THUMBNAIL-HEIGHT*/',		'',					$file_text );
				$file_text	=	str_replace('/*THUMBNAIL-IMG-WIDTH*/',	'width: calc(100% - 2px);',				$file_text );
				$file_text	=	str_replace('/*THUMBNAIL-IMG-HEIGHT*/',	'height: 200px; overflow: hidden;',		$file_text );
				$file_text	=	str_replace('/*OPTION*/',				'.lkc-external-wrap, .lkc-internal-wrap { overflow: visible; } .lkc-card { height: calc(100% - 16px); overflow: hidden; box-sizing: border-box; }', $file_text );
				break;
			}

			// テキスト選択を禁止
			if		($prop['flg-anti-select'] ) {
				$file_text			=	str_replace('/*SELECTION*/',		'user-select: none;',		$file_text );
			}

			// 文字スタイル
			$items		=	array('title', 'url', 'excerpt', 'date', 'info', 'added', 'heading', 'more', 'cat' );
			foreach	($items as $item ) {

				$item_name			=	strtolower($item.'-color' );
				if		(array_key_exists($item_name, $prop ) && $prop[$item_name] ) {
					$after			=	'color: '.$prop[$item_name].';';
					$file_text		=	str_replace('/*'.strtoupper($item_name ).'*/',		$after,		$file_text );
				}

				$item_name			=	strtolower($item.'-outline-color' );
				if		(array_key_exists($item_name, $prop ) && $prop[$item_name] ) {
					$after			=	'letter-spacing: 1px; text-shadow: 0 -1px '.$prop[$item_name]  .', 1px -1px '.$prop[$item_name]  .', 1px 0 '.$prop[$item_name]  .', 1px 1px '.$prop[$item_name]  .', 0 1px '.$prop[$item_name]  .', -1px 1px '.$prop[$item_name]  .', -1px 0 '.$prop[$item_name]  .', -1px -1px '.$prop[$item_name]  .';';

					$file_text		=	str_replace('/*'.strtoupper($item_name ).'*/',		$after,		$file_text );
				}

				$item_name			=	strtolower($item.'-bg-color' );
				if		(array_key_exists($item_name, $prop ) && $prop[$item_name] ) {
					$after			=	'padding: 4px; background-color: '.$prop[$item_name].';';
					$file_text		=	str_replace('/*'.strtoupper($item_name ).'*/',		$after,		$file_text );
				}

				$item_name			=	strtolower($item.'-size' );
				if		(array_key_exists($item_name, $prop ) && $prop[$item_name] ) {
					$after			=	'font-size: '.intval($prop[$item_name] ).'px;';
					$file_text		=	str_replace('/*'.strtoupper($item_name ).'*/',		$after,		$file_text );
				}

				$item_name			=	strtolower($item.'-height' );
				if		(array_key_exists($item_name, $prop ) && $prop[$item_name] ) {
					$after			=	'line-height: '.intval($prop[$item_name] ).'px;';
					$file_text		=	str_replace('/*'.strtoupper($item_name ).'*/',		$after,		$file_text );
				}

				$item_name			=	strtolower($item.'-maxline' );
				if		(array_key_exists($item_name, $prop ) ) {
					$maxline		=	intval($prop[$item_name] );
					if	($maxline	==	0 ) {
						$after		=	'white-space: wrap; text-overflow: ellipsis;';
					} else {
						$after		=	'white-space: wrap; text-overflow: ellipsis; display: -webkit-box !important; -webkit-box-orient: vertical; -webkit-line-clamp: '.$maxline.';';
					}
					$file_text		=	str_replace('/*'.strtoupper($item_name ).'*/',		$after,		$file_text );
				}

				$item_name			=	strtolower($item.'-bold' );
				if		(array_key_exists($item_name, $prop ) ) {
					if	($prop[$item_name] ) {
						$after		=	'font-weight: bold;';
					} else {
						$after		=	'font-weight: normal;';
					}
					$file_text		=	str_replace('/*'.strtoupper($item_name ).'*/',		$after,		$file_text );
				}

				$item_name			=	strtolower($item.'-italic' );
				if		(array_key_exists($item_name, $prop ) ) {
					if	($prop[$item_name] ) {
						$after		=	'font-style: italic;';
					} else {
						$after		=	'font-style: normal;';
					}
					$file_text		=	str_replace('/*'.strtoupper($item_name ).'*/',		$after,		$file_text );
				}

				$item_name			=	strtolower($item.'-underline' );
				if		(array_key_exists($item_name, $prop ) ) {
					if	($prop[$item_name] ) {
						$after		=	'text-decoration: underline;';
					} else {
						$after		=	'text-decoration: none;';
					}
					$file_text		=	str_replace('/*'.strtoupper($item_name ).'*/',		$after,		$file_text );
				}

				$item_name			=	strtolower($item.'-hover' );
				if		(array_key_exists($item_name, $prop ) ) {
					if	($prop[$item_name] ) {
						$after		=	'text-decoration: underline;';
					} else {
						$after		=	'text-decoration: none;';
					}
					$file_text		=	str_replace('/*'.strtoupper($item_name ).'*/',		$after,		$file_text );
				}

			}

			// カード外側の余白
			if	($prop['margin-top']		!==		'' ) {
				$file_text		=	str_replace('/*MARGIN-TOP*/',		'margin-top: '.		$prop['margin-top'].	' !important;',		$file_text );
			}
			if	($prop['margin-bottom']		!==		'' ) {
				$file_text		=	str_replace('/*MARGIN-BOTTOM*/',	'margin-bottom: '.	$prop['margin-bottom'].' !important;',		$file_text );
			}
			if	($prop['margin-left']		!==		'' ) {
				$file_text		=	str_replace('/*MARGIN-LEFT*/',		'padding-left: '.	$prop['margin-left'].	' !important;',		$file_text );
			}
			if	($prop['margin-right']		!==		'' ) {
				$file_text		=	str_replace('/*MARGIN-RIGHT*/',		'padding-right: '.	$prop['margin-right'].	' !important;',		$file_text );
			}

			// カード内側の余白
			$file_text	=	str_replace('/*PADDING*/',				'padding: 0;', $file_text );

			// カード本体の余白
			$margin_top		=	$prop['card-top']		== ''	? '8px' : $prop['card-top'];
			$margin_bottom	=	$prop['card-bottom']	== ''	? '8px' : $prop['card-bottom'];
			$margin_left	=	$prop['card-left']		== ''	? '8px' : $prop['card-left'];
			$margin_right	=	$prop['card-right']		== ''	? '8px' : $prop['card-right'];
			$file_text		=	str_replace('/*CARD-TOP*/',		'margin-top: '.		$margin_top.	';', $file_text );
			$file_text		=	str_replace('/*CARD-BOTTOM*/',	'margin-bottom: '.	$margin_bottom.	';', $file_text );
			$file_text		=	str_replace('/*CARD-LEFT*/',	'margin-left: '.	$margin_left.	';', $file_text );
			$file_text		=	str_replace('/*CARD-RIGHT*/',	'margin-right: '.	$margin_right.	';', $file_text );

			// img 要素のスタイルをリセット
			if (isset($prop['flg-style-reset'] ) ) {
				$file_text	=	str_replace('/*RESET-IMG*/',	'margin: 0 !important; padding: 0; border: none;', $file_text );
				$file_text	=	str_replace('/*STATIC*/',		'position: static !important;', $file_text );
				$file_text	=	str_replace('/*IMPORTANT*/',	'!important', $file_text );
			} else {
				$file_text	=	str_replace('/*IMPORTANT*/',	'', $file_text );
			}

			// 中央寄せ
			if (isset($prop['centering'] ) && $prop['centering'] == '1' ) {
				$file_text	=	str_replace('/*WRAP-MARGIN*/',			'margin: 0 auto;',		$file_text );
			} else {
				$file_text	=	str_replace('/*WRAP-MARGIN*/', 			'margin: 0;',			$file_text );
			}

			// 共通の影
			if (isset($this->options['shadow']) && $this->options['shadow'] == '1') {
				if (isset($this->options['shadow-inset']) && $this->options['shadow-inset'] == '1') {
					$file_text = str_replace('/*SHADOW*/',		'box-shadow: 8px 8px 8px rgba(0, 0, 0, 0.4) , 0 0 16px rgba(0, 0, 0, 0.3) inset;', $file_text );
				} else {
					$file_text = str_replace('/*SHADOW*/',		'box-shadow: 8px 8px 8px rgba(0, 0, 0, 0.4);', $file_text );
				}
			} else {
				if (isset($this->options['shadow-inset']) && $this->options['shadow-inset'] == '1') {
					$file_text = str_replace('/*SHADOW*/',		'box-shadow: 0 0 16px rgba(0, 0, 0, 0.5) inset;', $file_text );
				}
			}

			// サムネイルの位置とサイズ
			$thumbnail_adjust		=	2;
			$thumbnail_width	= intval($prop['thumbnail-width'] );
			$thumbnail_height	= intval($prop['thumbnail-height'] );
			switch ($prop['thumbnail-position'] ) {
			case '1':			// 右側にサムネイル
				$file_text	=	str_replace('/*THUMBNAIL-POSITION*/',		'float: right;', $file_text );
				$file_text	=	str_replace('/*THUMBNAIL-MARGIN*/',			'margin: 0 0 0 8px;', $file_text );
				$file_text	=	str_replace('/*THUMBNAIL-WIDTH*/',			'width: '.($thumbnail_width + $thumbnail_adjust ).'px;', $file_text );
				$file_text	=	str_replace('/*THUMBNAIL-IMG-WIDTH*/',		'width: '.$thumbnail_width.'px !important;', $file_text );
				$file_text	=	str_replace('/*THUMBNAIL-IMG-HEIGHT*/',		'height: '.$thumbnail_height.'px !important;', $file_text );
				break;
			case '2':			// 左側にサムネイル
				$file_text	=	str_replace('/*THUMBNAIL-POSITION*/',		'float: left;', $file_text );
				$file_text	=	str_replace('/*THUMBNAIL-MARGIN*/',			'margin: 0 8px 0 0;', $file_text );
				$file_text	=	str_replace('/*THUMBNAIL-WIDTH*/',			'width: '.($thumbnail_width + $thumbnail_adjust ).'px;', $file_text );
				$file_text	=	str_replace('/*THUMBNAIL-IMG-WIDTH*/',		'width: '.$thumbnail_width .'px !important;', $file_text );
				$file_text	=	str_replace('/*THUMBNAIL-IMG-HEIGHT*/',		'height: '.$thumbnail_height.'px !important;', $file_text );
				break;
			case '3':			// 上側にサムネイル
				$file_text	=	str_replace('/*THUMBNAIL-POSITION*/',		'display: block;', $file_text );
				$file_text	=	str_replace('/*THUMBNAIL-MARGIN*/',			'margin: 0 0 8px 0;', $file_text );
				$file_text	=	str_replace('/*THUMBNAIL-IMG-WIDTH*/',		'width: calc(100% - 2px) !important;', $file_text );
				$file_text	=	str_replace('/*THUMBNAIL-IMG-HEIGHT*/',		'height: '.$thumbnail_height.'px !important; overflow: hidden;', $file_text );
				break;
			}

			// サムネイルと文字サイズのレスポンシブ調整
			if (isset($prop['flg-resize'] ) && $prop['flg-resize'] ) {
				$size_title			=	intval(preg_replace('/[^0-9]/', '', $prop['title-size'] ) );
				$size_excerpt		=	intval(preg_replace('/[^0-9]/', '', $prop['excerpt-size'] ) );
				$height_title		=	intval(preg_replace('/[^0-9]/', '', $prop['title-height'] ) );
				$height_excerpt		=	intval(preg_replace('/[^0-9]/', '', $prop['excerpt-height'] ) );
				$thumbnail_height	=	intval($prop['thumbnail-height'] );
				$thumbnail_width	=	intval($prop['thumbnail-width'] );
				$file_text	=	str_replace('/*RESIZE*/',
					'@media screen and ( max-width: 600px )  { .lkc-title { font-size: '.intval($size_title * 0.9).'px; line-height: '.intval($height_title * 0.9).'px; } .lkc-excerpt { font-size: '.intval($size_excerpt * 0.95).'px; } .lkc-thumbnail { width: '.intval($thumbnail_width * 0.9).'px !important; } img.lkc-thumbnail-img { height: '.intval($thumbnail_height * 0.9).'px !important; width: '.intval($thumbnail_width * 0.9).'px !important; } }'.
					'@media screen and ( max-width: 480px )  { .lkc-title { font-size: '.intval($size_title * 0.8).'px; line-height: '.intval($height_title * 0.8).'px; } .lkc-excerpt { font-size: '.intval($size_excerpt * 0.8 ).'px; } .lkc-thumbnail { width: '.intval($thumbnail_width * 0.7).'px !important; } img.lkc-thumbnail-img { height: '.intval($thumbnail_height * 0.7).'px !important; width: '.intval($thumbnail_width * 0.7).'px !important; } }'.
					'@media screen and ( max-width: 320px )  { .lkc-title { font-size: '.intval($size_title * 0.7).'px; line-height: '.intval($height_title * 0.7).'px; } .lkc-excerpt { font-size: '.intval($size_excerpt * 0.6 ).'px; } .lkc-thumbnail { width: '.intval($thumbnail_width * 0.5).'px !important; } img.lkc-thumbnail-img { height: '.intval($thumbnail_height * 0.5).'px !important; width: '.intval($thumbnail_width * 0.5).'px !important; } }', $file_text );
			}

			// カード幅
			if	($prop['width']	===	null || $prop['width'] === '' ) {
				$width_unit	=	null;
				$file_text	=	str_replace('/*WIDTH*/',			'',										$file_text );
			} else {
				$width_value	=	intval($prop['width'] );
				$width_unit		=	isset($prop['width-unit'] ) && $prop['width-unit'] === '%' ? '%' : 'px';
				if	($width_unit	==	'%' ) {
					$file_text	=	str_replace('/*WIDTH*/',		'width: '.$width_value.$width_unit.';',			$file_text );
				} else {
					$file_text	=	str_replace('/*WIDTH*/',		'max-width: '.$width_value.$width_unit.';',		$file_text );
				}
			}

			// 記事情報エリアの高さ
			$content_height	=	$prop['content-height'];
			if	($content_height	==	'' ) {
			} else {
				$content_height	=	intval($content_height );
				if	($prop['thumbnail-position']	==	'3' ) {
					$content_height	=	$content_height + intval($thumbnail_height );
				}
				if	($content_height	>	0 ) {
					$content_height	.=	'px';
				}
				$file_text	=	str_replace('/*CONTENT-HEIGHT*/',	'height: '.$content_height.';',				$file_text );
			}

			// 抜粋エリアを内側に見せる
			// 記事情報エリアの余白
			if (isset($prop['content-inset'] ) && $prop['content-inset'] == '1' ) {
				$file_text	=	str_replace('/*CONTENT-PADDING*/',	'padding: 6px;', $file_text );
				$file_text	=	str_replace('/*CONTENT-INSET*/',	'box-shadow:  inset 4px 4px 4px rgba(255,255,255,1);', $file_text );
				$file_text	=	str_replace('/*CONTENT-BG-COLOR*/',	'background-color: rgba(255, 255, 255, 0.8 );', $file_text );
			}

			switch ($prop['info-position'] ) {
			case 1:				// サイト情報を上に表示
				$file_text	=	str_replace('/*CONTENT-MARGIN*/',	'margin: 6px 0 0 0;', $file_text );
				break;
			case 2:				// サイト情報を下に表示
				$file_text	=	str_replace('/*CONTENT-MARGIN*/',	'margin: 0 0 8px 0;', $file_text );
				break;
			default:
				$file_text	=	str_replace('/*CONTENT-MARGIN*/',	'margin: 0;', $file_text );
				break;
			}

			// 抜粋の余白
			$file_text	=	str_replace('/*MARGIN-EXCERPT*/',		'margin: 0;', $file_text );

			// サイトアイコン
			$file_text	=	str_replace('/*FAVICON-HEIGHT*/',		'height: 16px;', $file_text );
			$file_text	=	str_replace('/*FAVICON-WIDTH*/',		'width: 16px;', $file_text );

			// サイト情報の区切り線
			if (isset($prop['separator'] ) && $prop['separator'] == '1' ) {
				switch ($prop['info-position'] ) {
				case '1':
					$file_text	=	str_replace('/*SEPARATOR*/',	'border-top: 1px solid '.$prop['info-color'].';', $file_text );
					break;
				case '2':
					$file_text	=	str_replace('/*SEPARATOR*/',	'border-bottom: 1px solid '.$prop['info-color'].';', $file_text );
					break;
				}
			}

			// リンク種別ごとの設定
			$replace_part_style	=	function($prefix, $placeholder_prefix, $placeholder_suffix = '' ) use (&$file_text, $prop ) {
				if	(!empty($prop[$prefix.'-transform-enabled'] ) ) {
					$value_transform_x		= isset($prop[$prefix.'-transform-x'] ) ? intval($prop[$prefix.'-transform-x'] ) : 0;
					$value_transform_y		= isset($prop[$prefix.'-transform-y'] ) ? intval($prop[$prefix.'-transform-y'] ) : 0;
					$value_transform_rotate	= isset($prop[$prefix.'-transform-rotate'] ) ? intval($prop[$prefix.'-transform-rotate'] ) : 0;
					$value_transform_scale	= isset($prop[$prefix.'-transform-scale'] ) ? intval($prop[$prefix.'-transform-scale'] ) : 100;
					if	($value_transform_x || $value_transform_y || $value_transform_rotate || $value_transform_scale != 100 ) {
						$file_text	=	str_replace('/*'.$placeholder_prefix.'-TRANSFORM'.$placeholder_suffix.'*/',		'transform: translate('.$value_transform_x.'px, '.$value_transform_y.'px) rotate('.$value_transform_rotate.'deg) scale('.($value_transform_scale / 100).');', $file_text );
					}
				}

				if	(!empty($prop[$prefix.'-bg-enabled'] ) && !empty($prop[$prefix.'-bg-color'] ) ) {
					$file_text	=	str_replace('/*'.$placeholder_prefix.'-BG-COLOR'.$placeholder_suffix.'*/',		'background-color: '.$prop[$prefix.'-bg-color'].';', $file_text );
				}

				if	(!empty($prop[$prefix.'-border-enabled'] ) ) {
					$value_style		=	isset($prop[$prefix.'-border-style'] ) ? $prop[$prefix.'-border-style'] : 'solid';
					$value_width		=	isset($prop[$prefix.'-border-width'] ) ? $prop[$prefix.'-border-width'] : '1px';
					$value_width_num	=	strval(intval(preg_replace('/[^0-9]/', '', $value_width ) ) );
					$value_color		=	isset($prop[$prefix.'-border-color'] ) ? $prop[$prefix.'-border-color'] : '';
					$value_radius		=	isset($prop[$prefix.'-border-radius'] ) ? intval($prop[$prefix.'-border-radius'] ) : 0;
					if	($value_style ) {
						$file_text	=	str_replace('/*'.$placeholder_prefix.'-BORDER'.$placeholder_suffix.'*/',		'border: '.($value_color ? $value_color : '' ).' '.($value_style ? $value_style : '' ).' '.($value_width_num ? $value_width_num.'px' : '' ).' !important;', $file_text );
					}
					if	($value_radius > 0 ) {
						$file_text	=	str_replace('/*'.$placeholder_prefix.'-RADIUS'.$placeholder_suffix.'*/',		'border-radius: '.$value_radius.'px; -webkit-border-radius: '.$value_radius.'px; -moz-border-radius: '.$value_radius.'px;', $file_text );
					}
				}

				if	(!empty($prop[$prefix.'-shadow-enabled'] ) ) {
					$value_shadow_color		= !empty($prop[$prefix.'-shadow-color'] ) ? $prop[$prefix.'-shadow-color'] : 'rgba(0,0,0,0.3)';
					$value_shadow_x			= isset($prop[$prefix.'-shadow-x'] ) ? intval($prop[$prefix.'-shadow-x'] ) : 8;
					$value_shadow_y			= isset($prop[$prefix.'-shadow-y'] ) ? intval($prop[$prefix.'-shadow-y'] ) : 8;
					$value_shadow_blur		= isset($prop[$prefix.'-shadow-blur'] ) ? intval($prop[$prefix.'-shadow-blur'] ) : 8;
					$value_shadow_spread	= isset($prop[$prefix.'-shadow-spread'] ) ? intval($prop[$prefix.'-shadow-spread'] ) : 0;
					$value_shadow_inset		= isset($prop[$prefix.'-shadow-inset'] ) ? $prop[$prefix.'-shadow-inset'] : 0;
					$file_text	=	str_replace('/*'.$placeholder_prefix.'-SHADOW'.$placeholder_suffix.'*/',			'box-shadow: '.($value_shadow_inset ? 'inset ' : '' ).$value_shadow_x.'px '.$value_shadow_y.'px '.$value_shadow_blur.'px '.$value_shadow_spread.'px '.$value_shadow_color.';', $file_text );
				}
			};
			foreach		(array('ex', 'in' )	as	$t ) {
				$T		=	strtoupper($t );

				$value_transform_enabled	= isset($prop[$t.'-transform-enabled'] ) ? $prop[$t.'-transform-enabled'] : 1;
				if	($value_transform_enabled ) {
					$value_transform_x		= isset($prop[$t.'-transform-x'] ) ? intval($prop[$t.'-transform-x'] ) : 0;
					$value_transform_y		= isset($prop[$t.'-transform-y'] ) ? intval($prop[$t.'-transform-y'] ) : 0;
					$value_transform_rotate	= isset($prop[$t.'-transform-rotate'] ) ? intval($prop[$t.'-transform-rotate'] ) : 0;
					$value_transform_scale	= isset($prop[$t.'-transform-scale'] ) ? intval($prop[$t.'-transform-scale'] ) : 100;
					if	($value_transform_x || $value_transform_y || $value_transform_rotate || $value_transform_scale != 100 ) {
						$file_text	=	str_replace('/*'.$T.'-WRAP-TRANSFORM*/',		'transform: translate('.$value_transform_x.'px, '.$value_transform_y.'px) rotate('.$value_transform_rotate.'deg) scale('.($value_transform_scale / 100).');', $file_text );
					}
				}
				$value_opacity	= isset($prop[$t.'-opacity'] ) ? max(0, min(100, intval($prop[$t.'-opacity'] ) ) ) : 100;
				if	($value_opacity != 100 ) {
					$file_text	=	str_replace('/*'.$T.'-OPACITY*/',			'opacity: '.($value_opacity / 100).';', $file_text );
				}
				$value_transition	= isset($prop[$t.'-transition'] ) ? floatval($prop[$t.'-transition'] ) : 0;
				if	($value_transition > 0 ) {
					$file_text	=	str_replace('/*'.$T.'-WRAP-TRANSITION*/',	'transition: all '.$value_transition.'s ease;', $file_text );
				}
				// 背景色
				$key				=	$t.'-bg-color';
				$value				=	$prop[$key];
				$pname				=	strtoupper($key );
				$value_bg_enabled	=	isset($prop[$t.'-bg-enabled'] ) ? $prop[$t.'-bg-enabled'] : 1;
				if		($value_bg_enabled && $value ) {
					$file_text		=	str_replace('/*'.$pname.'*/',	'background-color: '.$value.';',	$file_text );
				}

				// 背景画像
				$key				=	$t.'-bg-image';
				$value				=	$prop[$key];
				$pname				=	strtoupper($key );
				if		($value_bg_enabled && $value ) {
					if	(preg_match('/https?(:\/\/[-_.!~*\'()a-zA-Z0-9;\/?:\@&=+\$,%#]+)$/',	$value ) ) {
						$file_text	=	str_replace('/*'.$pname.'*/',	'background-image: url("'.esc_url($value ).'");',		$file_text );
					} else {
						$file_text	=	str_replace('/*'.$pname.'*/',	'background-image: '.esc_html($value ).';',				$file_text );
					}
				}

				// リンクカードの枠線
				$value_style		=	isset($prop[$t.'-border-style'] ) ? $prop[$t.'-border-style'] : $prop['border-style'];
				$value_width		=	isset($prop[$t.'-border-width'] ) ? $prop[$t.'-border-width'] : $prop['border-width'];
				$value_width_num	=	strval(intval(preg_replace('/[^0-9]/', '', $value_width ) ) );
				$value_radius		=	isset($prop[$t.'-border-radius'] ) ? intval($prop[$t.'-border-radius'] ) : 0;
				$value_border_enabled	=	isset($prop[$t.'-border-enabled'] ) ? $prop[$t.'-border-enabled'] : 1;
				if	($value_border_enabled && $value_style ) {
					$value_color	=	$prop[$t.'-border-color'];
					$border			=	'border: '.
										($value_color		?	$value_color		:	'' ).' '.
										($value_style		?	$value_style		:	'' ).' '.
										($value_width_num	?	$value_width_num.'px'	:	'' ).';';
					$file_text		=	str_replace('/*'.strtoupper($t ).'-BORDER*/',	$border, $file_text );
				}

				// 影
				$param				=	'';
				// '4px 4px 8px rgba(0,0,0,0.5)'
				// '8px 8px 8px rgba(0,0,0,0.5)'
				// '16px 16px 8px rgba(0,0,0,0.5)'	
				// '32px 32px 16px rgba(0,0,0,0.2)'
				// 'inset 8px 8px 8px rgba(0,0,0,0.5)'
				// 'inset 4px 4px 4px rgba(255,255,255,0.5), inset -4px -4px 4px rgba(0,0,0,0.5)'
				// 'inset 4px 4px 4px rgba(255,255,255,0.5), inset -4px -4px 4px rgba(0,0,0,0.5)'
				$value_shadow_enabled	=	isset($prop[$t.'-shadow-enabled'] ) ? $prop[$t.'-shadow-enabled'] : $prop['shadow'];
				if	($value_shadow_enabled ) {
					$value_shadow_color		=	!empty($prop[$t.'-shadow-color'] ) ? $prop[$t.'-shadow-color'] : 'rgba(0,0,0,0.3)';
					$value_shadow_x			=	isset($prop[$t.'-shadow-x'] ) ? intval($prop[$t.'-shadow-x'] ) : 8;
					$value_shadow_y			=	isset($prop[$t.'-shadow-y'] ) ? intval($prop[$t.'-shadow-y'] ) : 8;
					$value_shadow_blur		=	isset($prop[$t.'-shadow-blur'] ) ? intval($prop[$t.'-shadow-blur'] ) : 8;
					$value_shadow_spread	=	isset($prop[$t.'-shadow-spread'] ) ? intval($prop[$t.'-shadow-spread'] ) : 0;
					$value_shadow_inset		=	isset($prop[$t.'-shadow-inset'] ) ? $prop[$t.'-shadow-inset'] : 0;
					$param					=	($value_shadow_inset ? 'inset ' : '' ).
												$value_shadow_x.'px '.$value_shadow_y.'px '.$value_shadow_blur.'px '.$value_shadow_spread.'px '.$value_shadow_color;
				}
				if	($param ) {
					$file_text		=	str_replace('/*'.$T.'-SHADOW*/',		'box-shadow: '.$param.';',	$file_text );
				}

				// 角丸
				if		($value_radius > 0 ) {
					$file_text	=	str_replace('/*'.$T.'-RADIUS*/',			'border-radius: '.$value_radius.'px; -webkit-border-radius: '.$value_radius.'px; -moz-border-radius: '.$value_radius.'px;',		$file_text );
				} else {
					$file_text	=	str_replace('/*'.$T.'-RADIUS*/',			'',		$file_text );
				}

				$hover_css		=	array();
				$value_hover_transform_enabled	= isset($prop[$t.'-hover-transform-enabled'] ) ? $prop[$t.'-hover-transform-enabled'] : 1;
				if	($value_hover_transform_enabled ) {
					$value_hover_transform_x		= isset($prop[$t.'-hover-transform-x'] ) ? intval($prop[$t.'-hover-transform-x'] ) : 0;
					$value_hover_transform_y		= isset($prop[$t.'-hover-transform-y'] ) ? intval($prop[$t.'-hover-transform-y'] ) : 0;
					$value_hover_transform_rotate	= isset($prop[$t.'-hover-transform-rotate'] ) ? intval($prop[$t.'-hover-transform-rotate'] ) : 0;
					$value_hover_transform_scale	= isset($prop[$t.'-hover-transform-scale'] ) ? intval($prop[$t.'-hover-transform-scale'] ) : 100;
					if	($value_hover_transform_x || $value_hover_transform_y || $value_hover_transform_rotate || $value_hover_transform_scale != 100 ) {
						$hover_css[]	=	'transform: translate('.$value_hover_transform_x.'px, '.$value_hover_transform_y.'px) rotate('.$value_hover_transform_rotate.'deg) scale('.($value_hover_transform_scale / 100).');';
					}

					$value_hover_opacity	= isset($prop[$t.'-hover-opacity'] ) ? max(0, min(100, intval($prop[$t.'-hover-opacity'] ) ) ) : 100;
					if	($value_hover_opacity != 100 ) {
						$hover_css[]	=	'opacity: '.($value_hover_opacity / 100).';';
					}
				}

				$value_hover_bg_enabled	= isset($prop[$t.'-hover-bg-enabled'] ) ? $prop[$t.'-hover-bg-enabled'] : 0;
				if	($value_hover_bg_enabled ) {
					$value_hover_bg_color	= isset($prop[$t.'-hover-bg-color'] ) ? $prop[$t.'-hover-bg-color'] : '';
					if	($value_hover_bg_color ) {
						$hover_css[]	=	'background-color: '.$value_hover_bg_color.';';
					}
					$value_hover_image	= isset($prop[$t.'-hover-bg-image'] ) ? $prop[$t.'-hover-bg-image'] : '';
					if	($value_hover_image ) {
						if	(preg_match('/https?(:\/\/[-_.!~*\'()a-zA-Z0-9;\/?:\@&=+\$,%#]+)$/',	$value_hover_image ) ) {
							$hover_css[]	=	'background-image: url("'.esc_url($value_hover_image ).'");';
						} else {
							$hover_css[]	=	'background-image: '.esc_html($value_hover_image ).';';
						}
					}
				}
				$value_hover_border_enabled	= isset($prop[$t.'-hover-border-enabled'] ) ? $prop[$t.'-hover-border-enabled'] : 0;
				if	($value_hover_border_enabled ) {
					$value_hover_border_style	= isset($prop[$t.'-hover-border-style'] ) ? $prop[$t.'-hover-border-style'] : 'solid';
					$value_hover_border_width	= isset($prop[$t.'-hover-border-width'] ) ? $prop[$t.'-hover-border-width'] : '1px';
					$value_hover_border_width_num	= strval(intval(preg_replace('/[^0-9]/', '', $value_hover_border_width ) ) );
					$value_hover_border_color	= isset($prop[$t.'-hover-border-color'] ) ? $prop[$t.'-hover-border-color'] : '';
					if	($value_hover_border_style ) {
						$hover_css[]	=	'border: '.($value_hover_border_color ? $value_hover_border_color : '' ).' '.($value_hover_border_style ? $value_hover_border_style : '' ).' '.($value_hover_border_width_num ? $value_hover_border_width_num.'px' : '' ).';';
					}
					$value_hover_border_radius	= isset($prop[$t.'-hover-border-radius'] ) ? intval($prop[$t.'-hover-border-radius'] ) : 4;
					if	($value_hover_border_radius > 0 ) {
						$hover_css[]	=	'border-radius: '.$value_hover_border_radius.'px; -webkit-border-radius: '.$value_hover_border_radius.'px; -moz-border-radius: '.$value_hover_border_radius.'px;';
					}
				}
				$value_hover_shadow_enabled	= isset($prop[$t.'-hover-shadow-enabled'] ) ? $prop[$t.'-hover-shadow-enabled'] : 0;
				if	($value_hover_shadow_enabled ) {
					$value_hover_shadow_color	= !empty($prop[$t.'-hover-shadow-color'] ) ? $prop[$t.'-hover-shadow-color'] : 'rgba(0,0,0,0.3)';
					$value_hover_shadow_x		= isset($prop[$t.'-hover-shadow-x'] ) ? intval($prop[$t.'-hover-shadow-x'] ) : 8;
					$value_hover_shadow_y		= isset($prop[$t.'-hover-shadow-y'] ) ? intval($prop[$t.'-hover-shadow-y'] ) : 8;
					$value_hover_shadow_blur	= isset($prop[$t.'-hover-shadow-blur'] ) ? intval($prop[$t.'-hover-shadow-blur'] ) : 8;
					$value_hover_shadow_spread	= isset($prop[$t.'-hover-shadow-spread'] ) ? intval($prop[$t.'-hover-shadow-spread'] ) : 0;
					$value_hover_shadow_inset	= isset($prop[$t.'-hover-shadow-inset'] ) ? $prop[$t.'-hover-shadow-inset'] : 0;
					$hover_css[]	=	'box-shadow: '.($value_hover_shadow_inset ? 'inset ' : '' ).$value_hover_shadow_x.'px '.$value_hover_shadow_y.'px '.$value_hover_shadow_blur.'px '.$value_hover_shadow_spread.'px '.$value_hover_shadow_color.';';
				}
				$value_hover_transition	= isset($prop[$t.'-hover-transition'] ) ? floatval($prop[$t.'-hover-transition'] ) : 0;
				if	($value_hover_transition > 0 ) {
					$hover_css[]	=	'transition: all '.$value_hover_transition.'s ease;';
				}
				if	($hover_css ) {
					$file_text	=	str_replace('/*'.$T.'-HOVER-OPTION*/',		implode(' ', $hover_css ), $file_text );
				}

				// ヘッダーの位置
				$pos					=	intval($prop['heading-height'] ) / 2 + ($value_border_enabled ? intval($value_width_num ) : 0 ) * 1;
				$file_text	=	str_replace('/*'.$T.'-HEADING*/',					'position: absolute; top: -'.$pos.'px; left: 20px; padding: 0 '.$pos.'px; ',		$file_text );



				// ヘッダーの枠線
				if	($value_border_enabled && $value_style ) {
					$param				=	'border: '
											.($prop[$t.'-border-color']  	?	$prop[$t.'-border-color'].' '	:	'' )
											.($value_style  				?	$value_style.' '					:	'' )
											.($value_width_num > 0			?	$value_width_num.'px '				:	'' ).' /*IMPORTANT*/;';
					$file_text			=	str_replace('/*'.$T.'-HEADING-BORDER*/',			$param,		$file_text );
				}

				// ヘッダーの角丸
				if	($value_radius > 0 ) {
					$file_text			=	str_replace('/*'.$T.'-HEADING-RADIUS*/',			'border-radius: '.$value_radius.'px;',			$file_text );
				}

				// ヘッダーの影
				if	($value_shadow_enabled ) {
					$file_text			=	str_replace('/*'.$T.'-HEADING-SHADOW*/',			'box-shadow: 8px 8px 8px rgba(0,0,0,0.3);',		$file_text );
				}

				// ヘッダーの背景色
				if	((isset($prop['heading-bg-color'] ) && $prop['heading-bg-color'] ) || $prop[$t.'-bg-color'] ) {
					$param				=	'background-color: '
											.((isset($prop['heading-bg-color'] ) && $prop['heading-bg-color'] )		?	$prop['heading-bg-color']	:	
											 ($prop[$t.'-bg-color']			?	$prop[$t.'-bg-color']			:	'' ) ).';';
					$file_text			=	str_replace('/*'.$T.'-HEADING-BG-COLOR*/',			$param,		$file_text );
				}

				// 続きを読むボタン
				$border					=	'border: none;';
				$position08				=	'position: absolute; bottom:  8px; right:  8px; padding: 0 12px; ';
				$file_text			=	str_replace('/*'.$T.'-MOREBTN*/',		$position08.$border,			$file_text );

				$replace_part_style($t.'-heading',	$T.'-HEADING',		'-OPTION' );
				if (empty($prop[$t.'-heading-bg-enabled'])) {
					$file_text = str_replace('/*'.$T.'-HEADING-BG-COLOR-OPTION*/', 'background-color: transparent !important;', $file_text);
				}
				if (empty($prop[$t.'-heading-border-enabled'])) {
					$file_text = str_replace('/*'.$T.'-HEADING-BORDER-OPTION*/', 'border-color: transparent !important;', $file_text);
				}
				$replace_part_style($t.'-more',		$T.'-MORE',		'-OPTION' );
				$replace_part_style($t.'-thumbnail',	$T.'-THUMBNAIL' );
				if	(!empty($prop[$t.'-thumbnail-transform-enabled'] ) || !empty($prop[$t.'-thumbnail-shadow-enabled'] ) ) {
					$file_text	=	str_replace('/*'.$T.'-THUMBNAIL-OVERFLOW*/',	'overflow: visible;', $file_text );
				}
			}

			// 追加CSS
			if (isset($prop['css-add'] ) ) {
				$file_text	=	str_replace('/*CSS-ADD*/',			esc_html($prop['css-add'] ), $file_text );
			} else {
				$file_text	=	str_replace('/*CSS-ADD*/',			'', $file_text );
			}

			// クレジットリンクを表示する
			if (isset($prop['plugin-link'] ) && $prop['plugin-link'] == '1' ) {
				$file_text	=	str_replace('/*CREDIT*/',			'display: block;', $file_text );
			} else {
				$file_text	=	str_replace('/*CREDIT*/',			'display: none;', $file_text );
			}

			// 文字セットと生成情報
			$charset		=	'@charset "'.$this->charset.'";';											// 文字セット
			$info_text		=	'/* '.self::PLUGIN_NAME.' ver.'.PZLKC_PLUGIN_VERSION.' CSS #'.$this->now.' */';	// プラグイン名とバージョン
			$info_text_comp	=	'/*'.self::PLUGIN_ACRONYM.PZLKC_PLUGIN_VERSION.'#'.$this->now.'*/';				// 圧縮版の生成情報

			// テンプレート内に残ったコメントを削除
			$file_text		=	preg_replace('/\s*\/\*[^*]*\*+([^\/][^*]*\*+)*\//', '', $file_text );		// コメントの削除
			$css_text		=	$charset.PHP_EOL.$info_text.PHP_EOL.PHP_EOL.$file_text;
			$css_text_comp	=	$charset.$this->pz_CompressCSS($file_text ).$info_text_comp;

			// CSSファイルを書き出す
			$result			=	file_put_contents(PZLKC_DIR_STYLE.$filename.'.css',		$css_text );
			$result_comp	=	file_put_contents(PZLKC_DIR_STYLE.$filename.'.min.css',	$css_text_comp );

			if ($result || $result_comp ) {
				$result		=	1;
			} else {
				$result		=	2;
			}
		} else {
			$result			=	9;
		}
	}

function txt_color($prop,       $attr ) {
	return	($attr ? $prop.$attr.';' : '' );
}
