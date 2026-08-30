<?php defined('ABSPATH' ) || wp_die; ?>
<?php
	// スタイルシートのパスを用意
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
		// テンプレートファイルの読み込み
		$file_text	=	file_get_contents(PZLKC_FILE_TEMPLATE );
		if ($file_text ) {
			// かんたん書式設定
			switch ($prop['special-format'] ) {
			case 'LkC': // Pz-LkC Default
				$file_text		=	str_replace('/*EX-IMAGE*/',			'background-image: linear-gradient(#78f 0%, #78f 10%, #fff 30%);', $file_text );
				$file_text		=	str_replace('/*IN-IMAGE*/',			'background-image: linear-gradient(#ca4 0%, #ca4 10%, #fff 30%);', $file_text );
				$file_text		=	str_replace('/*TH-IMAGE*/',			'background-image: linear-gradient(#ca4 0%, #ca4 10%, #eee 30%);', $file_text );
				break;
			case 'hbc': // ノーマル（はてなブログカード風）
				$file_text	=	str_replace('/*EX-BORDER*/',			'border: 1px solid rgba(0,0,0,0.1);', $file_text );
				$file_text	=	str_replace('/*IN-BORDER*/',			'border: 1px solid rgba(0,0,0,0.1);', $file_text );
				$file_text	=	str_replace('/*TH-BORDER*/',			'border: 1px solid rgba(0,0,0,0.1);', $file_text );
				$file_text	=	str_replace('/*EX-RADIUS*/',			'border-radius: 3px; -webkit-border-radius: 3px; -moz-border-radius: 3px;', $file_text );
				$file_text	=	str_replace('/*IN-RADIUS*/',			'border-radius: 3px; -webkit-border-radius: 3px; -moz-border-radius: 3px;', $file_text );
				$file_text	=	str_replace('/*TH-RADIUS*/',			'border-radius: 3px; -webkit-border-radius: 3px; -moz-border-radius: 3px;', $file_text );
				$file_text	=	str_replace('/*SHADOW*/',				'box-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);', $file_text );
				break;
			case 'smp': // Simple（サムネイルとタイトル）
				$file_text	=	str_replace('/*EX-BORDER*/',			'border: none;', $file_text );
				$file_text	=	str_replace('/*IN-BORDER*/',			'border: none;', $file_text );
				$file_text	=	str_replace('/*TH-BORDER*/',			'border: none;', $file_text );
				$file_text	=	str_replace('/*NONE-INFO*/',			'display: none !important;', $file_text );
				$file_text	=	str_replace('/*NONE-EXCERPT*/',			'display: none !important;', $file_text );
				break;
			case 'cmp': // コンパクト（Twitter風）
				$file_text	=	str_replace('/*EX-BORDER*/',			'border: 1px solid rgba(0,0,0,0.1);', $file_text );
				$file_text	=	str_replace('/*IN-BORDER*/',			'border: 1px solid rgba(0,0,0,0.1);', $file_text );
				$file_text	=	str_replace('/*TH-BORDER*/',			'border: 1px solid rgba(0,0,0,0.1);', $file_text );
				$file_text	=	str_replace('/*CONTENT-HEIGHT*/',		'height: 108px;', $file_text );
				$file_text	=	str_replace('/*WRAP-MARGIN*/',			'margin: 0;', $file_text );
				$file_text	=	str_replace('/*PADDING*/',				'padding: 0;', $file_text );
				$file_text	=	str_replace('/*EX-RADIUS*/',			'border-radius: 16px; -webkit-border-radius: 16px; -moz-border-radius: 16px;', $file_text );
				$file_text	=	str_replace('/*IN-RADIUS*/',			'border-radius: 16px; -webkit-border-radius: 16px; -moz-border-radius: 16px;', $file_text );
				$file_text	=	str_replace('/*TH-RADIUS*/',			'border-radius: 16px; -webkit-border-radius: 16px; -moz-border-radius: 16px;', $file_text );
				$file_text	=	str_replace('/*CARD-TOP*/',				'margin: 0;', $file_text );
				$file_text	=	str_replace('/*CARD-BOTTOM*/',			'', $file_text );
				$file_text	=	str_replace('/*CARD-LEFT*/',			'', $file_text );
				$file_text	=	str_replace('/*CARD-RIGHT*/',			'', $file_text );
				$file_text	=	str_replace('/*MARGIN-TITLE*/',			'margin: 30px 0 0 108px;', $file_text );
				$file_text	=	str_replace('/*MARGIN-URL*/',			'margin: 0 0 0 108px;', $file_text );
				$file_text	=	str_replace('/*MARGIN-EXCERPT*/',		'margin: 0 0 0 108px;', $file_text );
				$file_text	=	str_replace('/*CONTENT-PADDING*/',		'padding: 0;', $file_text );
				$file_text	=	str_replace('/*CONTENT-MARGIN*/',		'margin: 0;', $file_text );
				//$content_height		= intval(preg_replace('/[^0-9]/', '', isset($prop['content-height'] ) ? $prop['content-height']  : self::DEFAULTS['content-height']  ) );
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
			case 'JIN': // 見出し（テーマJIN風）
				$file_text	=	str_replace('/*MARGIN-TOP*/',			'margin: 24px auto 30px auto;', $file_text );
				$file_text	=	str_replace('/*MARGIN-BOTTOM*/',		'', $file_text );
				$file_text	=	str_replace('/*MARGIN-LEFT*/',			'', $file_text );
				$file_text	=	str_replace('/*MARGIN-RIGHT*/',			'', $file_text );
				$file_text	=	str_replace('/*CARD-TOP*/',				'margin: 24px 20px 20px 20px;', $file_text );
				$file_text	=	str_replace('/*CARD-BOTTOM*/',			'', $file_text );
				$file_text	=	str_replace('/*CARD-LEFT*/',			'', $file_text );
				$file_text	=	str_replace('/*CARD-RIGHT*/',			'', $file_text );
				$file_text	=	str_replace('/*WIDTH*/',				'max-width: 96%;', $file_text );
				$file_text	=	str_replace('/*EX-RADIUS*/',			'border-radius: 4px; -webkit-border-radius: 4px; -moz-border-radius: 4px;', $file_text );
				$file_text	=	str_replace('/*IN-RADIUS*/',			'border-radius: 4px; -webkit-border-radius: 4px; -moz-border-radius: 4px;', $file_text );
				$file_text	=	str_replace('/*TH-RADIUS*/',			'border-radius: 4px; -webkit-border-radius: 4px; -moz-border-radius: 4px;', $file_text );
				$file_text	=	str_replace('/*WRAP-MARGIN*/',			'margin: 0 auto;', $file_text );
				$file_text	=	str_replace('/*THUMBNAIL- WIDTH*/',		'max-width: 150px;', $file_text );
				$file_text	=	str_replace('/*THUMBNAIL- HEIGHT*/',	'height: 108px; overflow: hidden;', $file_text );
				$file_text	=	str_replace('/*THUMBNAIL- IMG-WIDTH*/',	'width: 150px;', $file_text );
				$file_text	=	str_replace('/*HOVER*/',				'opacity: 0.8;', $file_text );
				$file_text	=	str_replace('/*OPTION*/',				'.linkcard p { display: none; }', $file_text );
				$file_text	=	str_replace('/*ADDED-COLOR*/',			'color: #fff;', $file_text );
				$file_text	=	str_replace('/*ADDED-SIZE*/',			'font-size: 12px;', $file_text );
				$file_text	=	str_replace('/*ADDED-HEIGHT*/',			'line-height: 30px;', $file_text );
				$added_height	=	intval(preg_replace('/[^0-9]/', '', isset($prop['added-height'] ) ? $prop['added-height']  : self::DEFAULTS['added-height']  ) );
				$file_text		=	str_replace('/*EX-HEADING*/',		'padding: 0 16px !important; position: absolute; top: -15px; left: 20px; padding: 0 10px; '.txt_color('background-color: ', $prop['ex-border-color'] ).' border-radius: 2px;', $file_text );
				$file_text		=	str_replace('/*IN-HEADING*/',		'padding: 0 16px !important; position: absolute; top: -15px; left: 20px; padding: 0 10px; '.txt_color('background-color: ', $prop['in-border-color'] ).' border-radius: 2px;', $file_text );
				$file_text		=	str_replace('/*TH-HEADING*/',		'padding: 0 16px !important; position: absolute; top: -15px; left: 20px; padding: 0 10px; '.txt_color('background-color: ', $prop['th-border-color'] ).' border-radius: 2px;', $file_text );
				if (isset($prop['thumbnail-resize'] ) && $prop['thumbnail-resize'] == '1' ) {
					$size_title			=	intval(preg_replace('/[^0-9]/', '', isset($prop['title-size'] ) ? $prop['title-size'] : self::DEFAULTS['title-size'] ) );
					$size_excerpt		=	intval(preg_replace('/[^0-9]/', '', isset($prop['excerpt-size'] ) ? $prop['excerpt-size'] : self::DEFAULTS['excerpt-size'] ) );
					$height_title		=	intval(preg_replace('/[^0-9]/', '', isset($prop['title-height'] ) ? $prop['title-height'] : self::DEFAULTS['title-height'] ) );
					$height_excerpt		=	intval(preg_replace('/[^0-9]/', '', isset($prop['excerpt-height'] ) ? $prop['excerpt-height'] : self::DEFAULTS['excerpt-height'] ) );
					$thumbnail_width	=	150;
					$file_text	=	str_replace('/*RESIZE*/',
						'@media screen and (max-width: 767px)  { .lkc-internal-wrap { max-width: 100% } .lkc-external-wrap { max-width: 100% } .lkc-this-wrap { max-width: 100% } .lkc-title { font-size: '.intval($size_title * 0.9).'px; line-height: '.intval($height_title * 0.9).'px; } .lkc-excerpt { font-size: '.intval($size_excerpt * 0.95).'px; } .lkc-thumbnail { max-width: '.intval($thumbnail_width * 0.9).'px; } .lkc-thumbnail-img { max-width: '.intval($thumbnail_width * 0.9).'px; } }'.
						'@media screen and (max-width: 512px)  { .lkc-internal-wrap { max-width: 100% } .lkc-external-wrap { max-width: 100% } .lkc-this-wrap { max-width: 100% } .lkc-title { font-size: '.intval($size_title * 0.8).'px; line-height: '.intval($height_title * 0.8).'px; } .lkc-excerpt { font-size: '.intval($size_excerpt * 0.80).'px; } .lkc-thumbnail { max-width: '.intval($thumbnail_width * 0.7).'px; } .lkc-thumbnail-img { max-width: '.intval($thumbnail_width * 0.7).'px; } }'.
						'@media screen and (max-width: 320px)  { .lkc-internal-wrap { max-width: 100% } .lkc-external-wrap { max-width: 100% } .lkc-this-wrap { max-width: 100% } .lkc-title { font-size: '.intval($size_title * 0.7).'px; line-height: '.intval($height_title * 0.7).'px; } .lkc-excerpt { font-size: '.intval($size_excerpt * 0.60).'px; } .lkc-thumbnail { max-width: '.intval($thumbnail_width * 0.5).'px; } .lkc-thumbnail-img { max-width: '.intval($thumbnail_width * 0.5).'px; } }', $file_text );
				}
				$file_text		=	str_replace('/*SCALE*/',		'transform: scale(1.1);', $file_text );
				$file_text		=	str_replace('/*TRANSFORM*/',	'-webkit-transition: color 0.4s ease, background 0.4s ease, transform 0.4s ease, opacity 0.4s ease, border 0.4s ease, padding 0.4s ease, left 0.4s ease, box-shadow 0.4s ease; transition: color 0.4s ease, background 0.4s ease, transform 0.4s ease, opacity 0.4s ease, border 0.4s ease, padding 0.4s ease, left 0.4s ease, box-shadow 0.4s ease;', $file_text );
				break;
			case 'ecl': // 囲み
				$css	=	'.lkc-external-wrap         , .lkc-internal-wrap         , .lkc-this-wrap         { transition: all 0.7s ease-in-out; border-width: 2px; }';
				$css	.=	'.lkc-external-wrap::before , .lkc-internal-wrap::before , .lkc-this-wrap::before { content: ""; display: block; position: absolute; border: 2px solid #888888; box-sizing: border-box; width: 24px; height: 24px; transition: all 0.7s ease-in-out; top: -6px; left: -6px; border-width: 2px 0 0 2px; }';
				$css	.=	'.lkc-external-wrap::after  , .lkc-internal-wrap::after  , .lkc-this-wrap::after  { content: ""; display: block; position: absolute; border: 2px solid #888888; box-sizing: border-box; width: 24px; height: 24px; transition: all 0.7s ease-in-out; bottom: -6px; right: -6px; border-width: 0 2px 2px 0; }';
				$css	.=	'.lkc-external-wrap:hover         { '.txt_color('border-color: ', $prop['ex-bg-color'] ).' }';
				$css	.=	'.lkc-internal-wrap:hover         { '.txt_color('border-color: ', $prop['in-bg-color'] ).' }';
				$css	.=	'.lkc-this-wrap:hover             { '.txt_color('border-color: ', $prop['th-bg-color'] ).' }';
				$css	.=	'.lkc-external-wrap:hover::before { width: calc(100% + 12px); height: calc(100% + 12px); '.txt_color('border-color: ', $prop['ex-bg-color'] ).' }';
				$css	.=	'.lkc-internal-wrap:hover::before { width: calc(100% + 12px); height: calc(100% + 12px); '.txt_color('border-color: ', $prop['in-bg-color'] ).' }';
				$css	.=	'.lkc-this-wrap:hover::before     { width: calc(100% + 12px); height: calc(100% + 12px); '.txt_color('border-color: ', $prop['th-bg-color'] ).' }';
				$css	.=	'.lkc-external-wrap:hover::after  { width: calc(100% + 12px); height: calc(100% + 12px); '.txt_color('border-color: ', $prop['ex-bg-color'] ).' }';
				$css	.=	'.lkc-internal-wrap:hover::after  { width: calc(100% + 12px); height: calc(100% + 12px); '.txt_color('border-color: ', $prop['in-bg-color'] ).' }';
				$css	.=	'.lkc-this-wrap:hover::after      { width: calc(100% + 12px); height: calc(100% + 12px); '.txt_color('border-color: ', $prop['th-bg-color'] ).' }';
				$file_text	=	str_replace('/*OPTION*/',			$css, $file_text );
				break;
			case 'ref': // 反射
				$css	=	'.lkc-external-wrap               , .lkc-internal-wrap               , .lkc-this-wrap               { overflow: hidden; }';
				$css	.=	'.lkc-external-wrap:hover::before , .lkc-internal-wrap:hover::before , .lkc-this-wrap:hover::before { margin-left: 300% ; }';
				$css	.=	'.lkc-external-wrap::before       , .lkc-internal-wrap::before       , .lkc-this-wrap::before       { content: ""; display: block; width: 500px; height: 120px; position: absolute; top: -10px; left: -500px; transform: rotate(-45deg); transition: all .3s ease-in-out; }';
				$css	.=	'.lkc-external-wrap::before { '.txt_color('background-color: ',  $prop['ex-border-color'] ).' }';
				$css	.=	'.lkc-internal-wrap::before { '.txt_color('background-color: ',  $prop['in-border-color'] ).' }';
				$css	.=	'.lkc-this-wrap::before { '.	txt_color('background-color: ',  $prop['th-border-color'] ).' }';
				$file_text	=	str_replace('/*OPTION*/', $css, $file_text );
				break;
			case 'wxp': // Windows XP
				$file_text	=	str_replace('/*EX-BORDER*/',		'border: none;',	$file_text );
				$file_text	=	str_replace('/*IN-BORDER*/',		'border: none;',	$file_text );
				$file_text	=	str_replace('/*TH-BORDER*/',		'border: none;',	$file_text );
				$file_text	=	str_replace('/*THUMBNAIL-MARGIN*/',	'margin: 0 8px;',	$file_text );
				$file_text	=	str_replace('/*CONTENT-MARGIN*/',	'margin: 8px 0;',	$file_text );
				$css	=	'.lkc-external-wrap a , .lkc-internal-wrap a , .lkc-this-wrap a { cursor: default; }';
				$css	.=	'.lkc-unlink *	{ color: #888; }';
				$css	.=	'.lkc-card		{ margin: 16px; padding: 0; border: 3px #1f61e3 solid; border-radius: 5px; background: #eeecdf; }';
				$css	.=	'.lkc-info		{ margin: 0; padding: 4px; background: linear-gradient(to bottom, #2790ff, #1f61e3); background: -webkit-linear-gradient(top, #2790ff, #1f61e3); font-weight: bold; font-size: 11px; line-height: 16px; }';
				$css	.=	'.lkc-info *	{ color: #fff; }';
				$css	.=	'.lkc-title		{ padding: 0; }';
				$css	.=	'.lkc-url		{ padding: 4px; cursor: pointer; }';
				$css	.=	'.lkc-excerpt	{ padding: 4px; }';
				$file_text	=	str_replace('/*OPTION*/', $css, $file_text );
				break;
			case 'w95': // Windows 95
				$file_text	=	str_replace('/*EX-BORDER*/',		'border: none;', $file_text );
				$file_text	=	str_replace('/*IN-BORDER*/',		'border: none;', $file_text );
				$file_text	=	str_replace('/*TH-BORDER*/',		'border: none;', $file_text );
				$file_text	=	str_replace('/*CONTENT-MARGIN*/',	'margin: 4px 0;', $file_text );
				$css	=	'.lkc-external-wrap a , .lkc-internal-wrap a , .lkc-this-wrap a { cursor: default; }';
				$css	.=	'.lkc-unlink *	{ color: #888; }';
				$css	.=	'.lkc-card		{ margin: 16px; padding: 4px; border: 3px #c0c7c8 solid; background: #e0e0e0; border: 1px #87888f solid; }';
				$css	.=	'.lkc-info		{ margin: 0; padding: 4px; border: 1px #87888f solid; background: #0000a8; font-weight: bold; font-size: 11px; line-height: 16px; }';
				$css	.=	'.lkc-info *	{ color: #fff; font-weight: normal; }';
				$css	.=	'.lkc-title		{ padding: 0; }';
				$css	.=	'.lkc-url		{ padding: 4px; cursor: pointer; }';
				$css	.=	'.lkc-excerpt	{ padding: 4px; }';
				$file_text	=	str_replace('/*OPTION*/', $css, $file_text );
				break;
			case 'ct1': // セロファンテープ（中央）
				$file_text	=	str_replace('/*WRAP-BEFORE*/',		'content: ""; display: block; position: absolute; left:   40%; top: -16px; width: 95px; height: 25px; z-index: 2; background-color: rgba(243,245,228,0.5); border: 2px solid rgba(255,255,255,0.5); -webkit-box-shadow: 1px 1px 4px rgba(200,200,180,0.8); -moz-box-shadow: 1px 1px 4px rgba(200,200,180,0.8); box-shadow: 1px 1px 4px rgba(200,200,180,0.8); -webkit-transform: rotate(3deg); -moz-transform: rotate(3deg); -o-transform: rotate(3deg);', $file_text );
				$file_text	=	str_replace('/*SHADOW*/',			'box-shadow: 0px 0px 2px rgba(0, 0, 0, 0.2);', $file_text );
				break;
			case 'ct2': // セロファンテープ（左右）
				$file_text	=	str_replace('/*MARGIN-LEFT*/',		'padding-left: 40px;', $file_text );
				$file_text	=	str_replace('/*MARGIN-RIGHT*/',		'padding-right: 25px;', $file_text );
				$file_text	=	str_replace('/*WRAP-BEFORE*/',		'content: ""; display: block; position: absolute; left:  -40px; top: -4px; width: 75px; height: 25px; z-index: 2; background-color: rgba(243,245,228,0.5); border: 2px solid rgba(255,255,255,0.5); -webkit-box-shadow: 1px 1px 4px rgba(200,200,180,0.8); -moz-box-shadow: 1px 1px 4px rgba(200,200,180,0.8); box-shadow: 1px 1px 4px rgba(200,200,180,0.8); -webkit-transform: rotate(-45deg); -moz-transform: rotate(-45deg); -o-transform: rotate(-45deg);', $file_text );
				$file_text	=	str_replace('/*WRAP-AFTER*/',		'content: ""; display: block; position: absolute; right: -20px; top: -2px; width: 75px; height: 25px; z-index: 2; background-color: rgba(243,245,228,0.5); border: 2px solid rgba(255,255,255,0.5); -webkit-box-shadow: 1px 1px 4px rgba(200,200,180,0.8); -moz-box-shadow: 1px 1px 4px rgba(200,200,180,0.8); box-shadow: 1px 1px 4px rgba(200,200,180,0.8); -webkit-transform: rotate(16deg); -moz-transform: rotate(16deg); -o-transform: rotate(16deg); transform: rotate(16deg);', $file_text );
				$file_text	=	str_replace('/*SHADOW*/',			'box-shadow: 0px 0px 2px rgba(0, 0, 0, 0.2);', $file_text );
				break;
			case 'ct3': // セロファンテープ（長め）
				$file_text	=	str_replace('/*MARGIN-LEFT*/',		'padding-left: 32px;', $file_text );
				$file_text	=	str_replace('/*MARGIN-RIGHT*/',		'padding-right: 32px;', $file_text );
				$file_text	=	str_replace('/*WRAP-BEFORE*/', 		'content: ""; display: block; position: absolute; left:   -5%; top: -12px; width: 110%; height: 25px; z-index: 2; background-color: rgba(243,245,228,0.5); border: 2px solid rgba(255,255,255,0.5); -webkit-box-shadow: 1px 1px 4px rgba(200,200,180,0.8); -moz-box-shadow: 1px 1px 4px rgba(200,200,180,0.8); box-shadow: 1px 1px 4px rgba(200,200,180,0.8); -webkit-transform: rotate(-3deg); -moz-transform: rotate(-3deg); -o-transform: rotate(-3deg);', $file_text );
				$file_text	=	str_replace('/*SHADOW*/',			'box-shadow: 0px 0px 2px rgba(0, 0, 0, 0.2);', $file_text );
				break;
			case 'ct4': // セロファンテープ（斜め）
				$file_text	=	str_replace('/*MARGIN-LEFT*/',		'padding-left: 24px;', $file_text );
				$file_text	=	str_replace('/*WRAP-BEFORE*/',		'content: ""; display: block; position: absolute; left:  -24px; top: 0px; width: 200px; height: 25px; z-index: 2; background-color: rgba(243,245,228,0.5); border: 2px solid rgba(255,255,255,0.5); -webkit-box-shadow: 1px 1px 4px rgba(200,200,180,0.8); -moz-box-shadow: 1px 1px 4px rgba(200,200,180,0.8); box-shadow: 1px 1px 4px rgba(200,200,180,0.8); -webkit-transform: rotate(-8deg); -moz-transform: rotate(-8deg); -o-transform: rotate(-8deg);', $file_text );
				$file_text	=	str_replace('/*SHADOW*/',			'box-shadow: 0px 0px 2px rgba(0, 0, 0, 0.2);', $file_text );
				break;
			case 'ppc': // 紙めくれ
				$file_text	=	str_replace('/*WRAP-AFTER*/',		'z-index: -1; content:""; height: 10px; width: 60%; position: absolute; right: 16px; bottom: 14px; left: auto; transform: skew(5deg) rotate(3deg); -webkit-transform: skew(5deg) rotate(3deg); -moz-transform: skew(5deg) rotate(3deg); box-shadow: 0 16px 16px rgba(0,0,0,1); -webkit-box-shadow: 0 16px 16px rgba(0,0,0,1); -moz-box-shadow: 0 16px 12px rgba(0,0,0,1);', $file_text );
				$file_text	=	str_replace('/*SHADOW*/',			'box-shadow: 0px 2px 6px rgba(0, 0, 0, 0.8);', $file_text );
				$file_text	=	str_replace('/*OPTION*/',			'article { position: relative; z-index: 0; } article blockquote { position: relative; z-index: 0; }', $file_text );
				break;
			case 'tac': // テープと紙めくれ
				$file_text	=	str_replace('/*MARGIN-LEFT*/',		'padding-left: 24px;', $file_text );
				$file_text	=	str_replace('/*WRAP-BEFORE*/',		'content: ""; display: block; position: absolute; left:  -24px; top: 0px; width: 200px; height: 25px; z-index: 2; background-color: rgba(243,245,228,0.5); border: 2px solid rgba(255,255,255,0.5); -webkit-box-shadow: 1px 1px 4px rgba(200,200,180,0.8); -moz-box-shadow: 1px 1px 4px rgba(200,200,180,0.8); box-shadow: 1px 1px 4px rgba(200,200,180,0.8); -webkit-transform: rotate(-8deg); -moz-transform: rotate(-8deg); -o-transform: rotate(-8deg);', $file_text );
				$file_text	=	str_replace('/*WRAP-AFTER*/',		'z-index: -1; content:""; height: 10px; width: 60%; position: absolute; right: 16px; bottom: 14px; left: auto; transform: skew(5deg) rotate(3deg); -webkit-transform: skew(5deg) rotate(3deg); -moz-transform: skew(5deg) rotate(3deg); box-shadow: 0 16px 16px rgba(0,0,0,1); -webkit-box-shadow: 0 16px 16px rgba(0,0,0,1); -moz-box-shadow: 0 16px 12px rgba(0,0,0,1);', $file_text );
				$file_text	=	str_replace('/*SHADOW*/',			'box-shadow: 0px 2px 6px rgba(0, 0, 0, 0.8);', $file_text );
				$file_text	=	str_replace('/*OPTION*/',			'article { position: relative; z-index: 0; } article blockquote { position: relative; z-index: 0; }', $file_text );
				break;
			case 'sBR': // 縫い目（青＆赤）
				$file_text	=	str_replace('/*EX-BORDER*/',		'border: 2px dashed rgba(255,255,255,0.5);', $file_text );
				$file_text	=	str_replace('/*IN-BORDER*/',		'border: 2px dashed rgba(255,255,255,0.5);', $file_text );
				$file_text	=	str_replace('/*TH-BORDER*/',		'border: 2px dashed rgba(255,255,255,0.5);', $file_text );
				$file_text	=	str_replace('/*EX-BG-COLOR*/',		'background: #bcddff; box-shadow: 0 0 0 5px #aabbee, 3px 3px 6px 4px rgba(0,0,0,0.6); -moz-box-shadow: 0 0 0 5px #aabbee, 3px 3px 6px 4px rgba(0,0,0,0.6); -webkit-box-shadow: 0 0 0 5px #aabbee, 3px 3px 6px 4px rgba(0,0,0,0.6);', $file_text );
				$file_text	=	str_replace('/*IN-BG-COLOR*/',		'background: #f8d0d0; box-shadow: 0 0 0 5px #e8a8a8, 3px 3px 6px 4px rgba(0,0,0,0.6); -moz-box-shadow: 0 0 0 5px #e8a8a8, 3px 3px 6px 4px rgba(0,0,0,0.6); -webkit-box-shadow: 0 0 0 5px #e8a8a8, 3px 3px 6px 4px rgba(0,0,0,0.6);', $file_text );
				$file_text	=	str_replace('/*TH-BG-COLOR*/',		'background: #f29db0; box-shadow: 0 0 0 5px #de8899, 3px 3px 6px 4px rgba(0,0,0,0.6); -moz-box-shadow: 0 0 0 5px #de8899, 3px 3px 6px 4px rgba(0,0,0,0.6); -webkit-box-shadow: 0 0 0 5px #de8899, 3px 3px 6px 4px rgba(0,0,0,0.6);', $file_text );
				break;
			case 'sGY': // 縫い目（緑＆黄）
				$file_text	=	str_replace('/*EX-BORDER*/',		'border: 2px dashed rgba(255,255,255,0.5);', $file_text );
				$file_text	=	str_replace('/*IN-BORDER*/',		'border: 2px dashed rgba(255,255,255,0.5);', $file_text );
				$file_text	=	str_replace('/*TH-BORDER*/',		'border: 2px dashed rgba(255,255,255,0.5);', $file_text );
				$file_text	=	str_replace('/*EX-BG-COLOR*/',		'background: #acefdd; box-shadow: 0 0 0 5px #8abecb, 3px 3px 6px 4px rgba(0,0,0,0.6); -moz-box-shadow: 0 0 0 5px #8abecb, 3px 3px 6px 4px rgba(0,0,0,0.6); -webkit-box-shadow: 0 0 0 5px #8abecb, 3px 3px 6px 4px rgba(0,0,0,0.6);', $file_text );
				$file_text	=	str_replace('/*IN-BG-COLOR*/',		'background: #ffde51; box-shadow: 0 0 0 5px #fbca4d, 3px 3px 6px 4px rgba(0,0,0,0.6); -moz-box-shadow: 0 0 0 5px #fbca4d, 3px 3px 6px 4px rgba(0,0,0,0.6); -webkit-box-shadow: 0 0 0 5px #fbca4d, 3px 3px 6px 4px rgba(0,0,0,0.6);', $file_text );
				$file_text	=	str_replace('/*TH-BG-COLOR*/',		'background: #f0e0b0; box-shadow: 0 0 0 5px #decca0, 3px 3px 6px 4px rgba(0,0,0,0.6); -moz-box-shadow: 0 0 0 5px #decca0, 3px 3px 6px 4px rgba(0,0,0,0.6); -webkit-box-shadow: 0 0 0 5px #decca0, 3px 3px 6px 4px rgba(0,0,0,0.6);', $file_text );
				break;
			case 'pin': // 押しピン（綺麗な画像募集中）
				$file_text	=	str_replace('/*WRAP-AFTER*/',		'content: ""; display: block; position: absolute; background-image: url("'.$this->plugin_dir_url.'img/pin.png"); background-repeat: no-repeat; background-position: center; left: 47%; top: -16px; width: 40px; height: 40px; z-index: 1; pointer-events: none;', $file_text );
				break;
			case 'inN': // 中立青緑（イングレス風）
				$color		=	'#59fbea';
				$file_text	=	str_replace('/*EX-BORDER*/', 		'border: 4px solid '.$color.';', $file_text );
				$file_text	=	str_replace('/*IN-BORDER*/', 		'border: 4px solid '.$color.';', $file_text );
				$file_text	=	str_replace('/*TH-BORDER*/', 		'border: 4px solid '.$color.';', $file_text );
				$file_text	=	str_replace('/*TITLE-COLOR*/',		'color: '.$color.';', $file_text );
				$file_text	=	str_replace('/*URL-COLOR*/',		'color: '.$color.';', $file_text );
				$file_text	=	str_replace('/*EXCERPT-COLOR*/',	'color: '.$color.';', $file_text );
				$file_text	=	str_replace('/*MORE-COLOR*/',		'color: '.$color.';', $file_text );
				$file_text	=	str_replace('/*INFO-COLOR*/',		'color: '.$color.';', $file_text );
				$file_text	=	str_replace('/*ADDED-COLOR*/',		'color: '.$color.';', $file_text );
				$file_text	=	str_replace('/*EX-BG-COLOR*/',		'background-color: rgba(  35 , 100 ,  93 , 0.90 );', $file_text );
				$file_text	=	str_replace('/*IN-BG-COLOR*/',		'background-color: rgba(   8 ,  25 ,  23 , 0.90 );', $file_text );
				$file_text	=	str_replace('/*TH-BG-COLOR*/',		'background-color: rgba(  89 , 251 , 234 , 0.05 );', $file_text );
				break;
			case 'inI': // 情報オレンジ（イングレス風）
				$color		=	'#ebbc4a';
				$file_text	=	str_replace('/*EX-BORDER*/', 		'border: 4px solid '.$color.';', $file_text );
				$file_text	=	str_replace('/*IN-BORDER*/', 		'border: 4px solid '.$color.';', $file_text );
				$file_text	=	str_replace('/*TH-BORDER*/', 		'border: 4px solid '.$color.';', $file_text );
				$file_text	=	str_replace('/*TITLE-COLOR*/',		'color: '.$color.';', $file_text );
				$file_text	=	str_replace('/*URL-COLOR*/',		'color: '.$color.';', $file_text );
				$file_text	=	str_replace('/*EXCERPT-COLOR*/',	'color: '.$color.';', $file_text );
				$file_text	=	str_replace('/*MORE-COLOR*/',		'color: '.$color.';', $file_text );
				$file_text	=	str_replace('/*INFO-COLOR*/',		'color: '.$color.';', $file_text );
				$file_text	=	str_replace('/*ADDED-COLOR*/',		'color: '.$color.';', $file_text );
				$file_text	=	str_replace('/*EX-BG-COLOR*/',		'background-color: rgba(  94 ,  75, 29 , 0.90 );', $file_text );
				$file_text	=	str_replace('/*IN-BG-COLOR*/',		'background-color: rgba(  23 ,  18,  7 , 0.90 );', $file_text );
				$file_text	=	str_replace('/*TH-BG-COLOR*/',		'background-color: rgba( 235 , 188, 74 , 0.05 );', $file_text );
				break;
			case 'inE': // エンライテッドカラー（イングレス風）
				$color		=	'#28f428';
				$file_text	=	str_replace('/*EX-BORDER*/', 		'border: 4px solid '.$color.';', $file_text );
				$file_text	=	str_replace('/*IN-BORDER*/', 		'border: 4px solid '.$color.';', $file_text );
				$file_text	=	str_replace('/*TH-BORDER*/', 		'border: 4px solid '.$color.';', $file_text );
				$file_text	=	str_replace('/*TITLE-COLOR*/',		'color: '.$color.';', $file_text );
				$file_text	=	str_replace('/*URL-COLOR*/',		'color: '.$color.';', $file_text );
				$file_text	=	str_replace('/*EXCERPT-COLOR*/',	'color: '.$color.';', $file_text );
				$file_text	=	str_replace('/*MORE-COLOR*/',		'color: '.$color.';', $file_text );
				$file_text	=	str_replace('/*INFO-COLOR*/',		'color: '.$color.';', $file_text );
				$file_text	=	str_replace('/*ADDED-COLOR*/',		'color: '.$color.';', $file_text );
				$file_text	=	str_replace('/*EX-BG-COLOR*/',		'background-color: rgba(  16 ,  97 ,  16 , 0.90 );', $file_text );
				$file_text	=	str_replace('/*IN-BG-COLOR*/',		'background-color: rgba(   4 ,  24 ,   4 , 0.90 );', $file_text );
				$file_text	=	str_replace('/*TH-BG-COLOR*/',		'background-color: rgba(  40 , 244 ,  40 , 0.05 );', $file_text );
				break;
			case 'inR': // レジスタンスカラー（イングレス風）
				$color		=	'#00c2ff';
				$file_text	=	str_replace('/*EX-BORDER*/', 		'border: 4px solid '.$color.';',	$file_text );
				$file_text	=	str_replace('/*IN-BORDER*/', 		'border: 4px solid '.$color.';',	$file_text );
				$file_text	=	str_replace('/*TH-BORDER*/', 		'border: 4px solid '.$color.';',	$file_text );
				$file_text	=	str_replace('/*TITLE-COLOR*/',		'color: '.$color.';',	$file_text );
				$file_text	=	str_replace('/*URL-COLOR*/',		'color: '.$color.';',	$file_text );
				$file_text	=	str_replace('/*EXCERPT-COLOR*/',	'color: '.$color.';',	$file_text );
				$file_text	=	str_replace('/*MORE-COLOR*/',		'color: '.$color.';',	$file_text );
				$file_text	=	str_replace('/*INFO-COLOR*/',		'color: '.$color.';',	$file_text );
				$file_text	=	str_replace('/*ADDED-COLOR*/',		'color: '.$color.';',	$file_text );
				$file_text	=	str_replace('/*EX-BG-COLOR*/',		'background-color: rgba(   0 ,  77 , 102 , 0.90 );',	$file_text );
				$file_text	=	str_replace('/*IN-BG-COLOR*/',		'background-color: rgba(   0 ,  19 ,  25 , 0.90 );',	$file_text );
				$file_text	=	str_replace('/*TH-BG-COLOR*/',		'background-color: rgba(   0 , 194 , 255 , 0.05 );',	$file_text );
				break;
			case 'slt': // ネタ？：斜め
				$file_text	=	str_replace('/*WRAP*/',					'transform:skew(-10deg) rotate(1deg); -webkit-transform: skew(-10deg) rotate(1deg); -moz-transform:skew(-10deg) rotate(1deg);', $file_text );
				$file_text	=	str_replace('/*MARGIN-LEFT*/',			'padding-left: 12px;', $file_text );
				$file_text	=	str_replace('/*MARGIN-RIGHT*/',			'padding-right: 30px;', $file_text );
				break;
			case '3Dr': // ネタ？：立体
				$file_text	=	str_replace('/*WRAP*/',					'-webkit-transform:perspective(150px) scale3d(0.84,0.9,1) rotate3d(1,0,0,12deg);',			$file_text );
				$file_text	=	str_replace('/*SHADOW*/',				'box-shadow: 0 20px 16px rgba(0, 0, 0, 0.6) , 0px 32px 32px rgba(0, 0, 0, 0.2) inset;',		$file_text );
				break;
			case 'sqr': // スクエア（WordPress標準風）
				$file_text	=	str_replace('/*HEIGHT*/',				'height: 340px;',	$file_text );
				$file_text	=	str_replace('/*CONTENT-HEIGHT*/',		'height: 340px;',	$file_text );
				$file_text	=	str_replace('/*THUMBNAIL-POSITION*/',	'display: block;',	$file_text );
				$file_text	=	str_replace('/*THUMBNAIL-MARGIN*/',		'margin: 0;',		$file_text );
				$file_text	=	str_replace('/*THUMBNAIL-WIDTH*/',		'',					$file_text );
				$file_text	=	str_replace('/*THUMBNAIL-HEIGHT*/',		'',					$file_text );
				$file_text	=	str_replace('/*THUMBNAIL-IMG-WIDTH*/',	'width: calc(100% - 2px);',				$file_text );
				$file_text	=	str_replace('/*THUMBNAIL-IMG-HEIGHT*/',	'height: 200px; overflow: hidden;',		$file_text );
				break;
			}

			// テキストの選択禁止
			if		($prop['flg-unti-select'] ) {
				$file_text			=	str_replace('/*SELECTION*/',		'user-select: none;',		$file_text );
			}

			// 文字色
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
					//$after		=	'-webkit-text-stroke-width: 3px; -webkit-text-stroke-color: '.$prop[$item_name].';';
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

			// カードの周りへの余白
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

			// カードの余白等調整
			$file_text	=	str_replace('/*PADDING*/',				'padding: 0;', $file_text );

			// カード内側の余白
			$margin_top		=	$prop['card-top']		== ''	? '8px' : $prop['card-top'];
			$margin_bottom	=	$prop['card-bottom']	== ''	? '8px' : $prop['card-bottom'];
			$margin_left	=	$prop['card-left']		== ''	? '8px' : $prop['card-left'];
			$margin_right	=	$prop['card-right']		== ''	? '8px' : $prop['card-right'];
			$file_text		=	str_replace('/*CARD-TOP*/',		'margin-top: '.		$margin_top.	';', $file_text );
			$file_text		=	str_replace('/*CARD-BOTTOM*/',	'margin-bottom: '.	$margin_bottom.	';', $file_text );
			$file_text		=	str_replace('/*CARD-LEFT*/',	'margin-left: '.	$margin_left.	';', $file_text );
			$file_text		=	str_replace('/*CARD-RIGHT*/',	'margin-right: '.	$margin_right.	';', $file_text );

			// img のスタイルを強制リセット
			if (isset($prop['style-reset-img'] ) ) {
				$file_text	=	str_replace('/*RESET-IMG*/',	'margin: 0 !important; padding: 0; border: none;', $file_text );
				$file_text	=	str_replace('/*STATIC*/',		'position: static !important;', $file_text );
				$file_text	=	str_replace('/*IMPORTANT*/',	'!important', $file_text );
			} else {
				$file_text	=	str_replace('/*IMPORTANT*/',	'', $file_text );
			}

			// センタリング指定あり	
			if (isset($prop['centering'] ) && $prop['centering'] == '1' ) {
				$file_text	=	str_replace('/*WRAP-MARGIN*/',			'margin: 0 auto;',		$file_text );
			} else {
				$file_text	=	str_replace('/*WRAP-MARGIN*/', 			'margin: 0;',			$file_text );
			}

			// 角まる指定あり
			switch ($this->options['radius']) {
			case null:
				$file_text = str_replace('/*EX-RADIUS*/',				'', $file_text );
				$file_text = str_replace('/*IN-RADIUS*/',				'', $file_text );
				$file_text = str_replace('/*TH-RADIUS*/',				'', $file_text );
				break;
			case '2':
				$file_text = str_replace('/*EX-RADIUS*/',				'border-radius: 4px; -webkit-border-radius: 4px; -moz-border-radius: 4px;', $file_text );
				$file_text = str_replace('/*IN-RADIUS*/',				'border-radius: 4px; -webkit-border-radius: 4px; -moz-border-radius: 4px;', $file_text );
				$file_text = str_replace('/*TH-RADIUS*/',				'border-radius: 4px; -webkit-border-radius: 4px; -moz-border-radius: 4px;', $file_text );
				break;
			case '1':
				$file_text = str_replace('/*EX-RADIUS*/',				'border-radius: 8px; -webkit-border-radius: 8px; -moz-border-radius: 8px;', $file_text );
				$file_text = str_replace('/*IN-RADIUS*/',				'border-radius: 8px; -webkit-border-radius: 8px; -moz-border-radius: 8px;', $file_text );
				$file_text = str_replace('/*TH-RADIUS*/',				'border-radius: 8px; -webkit-border-radius: 8px; -moz-border-radius: 8px;', $file_text );
				break;
			case '3':
				$file_text = str_replace('/*EX-RADIUS*/',				'border-radius: 16px; -webkit-border-radius: 16px; -moz-border-radius: 16px;', $file_text );
				$file_text = str_replace('/*IN-RADIUS*/',				'border-radius: 16px; -webkit-border-radius: 16px; -moz-border-radius: 16px;', $file_text );
				$file_text = str_replace('/*TH-RADIUS*/',				'border-radius: 16px; -webkit-border-radius: 16px; -moz-border-radius: 16px;', $file_text );
				break;
			case '4':
				$file_text = str_replace('/*EX-RADIUS*/',				'border-radius: 32px; -webkit-border-radius: 32px; -moz-border-radius: 32px;', $file_text );
				$file_text = str_replace('/*IN-RADIUS*/',				'border-radius: 32px; -webkit-border-radius: 32px; -moz-border-radius: 32px;', $file_text );
				$file_text = str_replace('/*TH-RADIUS*/',				'border-radius: 32px; -webkit-border-radius: 32px; -moz-border-radius: 32px;', $file_text );
				break;
			case '5':
				$file_text = str_replace('/*EX-RADIUS*/',				'border-radius: 64px; -webkit-border-radius: 64px; -moz-border-radius: 64px;', $file_text );
				$file_text = str_replace('/*IN-RADIUS*/',				'border-radius: 64px; -webkit-border-radius: 64px; -moz-border-radius: 64px;', $file_text );
				$file_text = str_replace('/*TH-RADIUS*/',				'border-radius: 64px; -webkit-border-radius: 64px; -moz-border-radius: 64px;', $file_text );
				break;
			}

			// 影あり
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

			// マウスを乗せたとき
			$file_text		=	str_replace('/*EX-HOVER*/',		'background-color:'.$this->options['ex-hover-bg-color'], $file_text );
			$file_text		=	str_replace('/*IN-HOVER*/',		'background-color:'.$this->options['in-hover-bg-color'], $file_text );
			$file_text		=	str_replace('/*TH-HOVER*/',		'background-color:'.$this->options['in-hover-bg-color'], $file_text );
			switch ($prop['hover'] ) {
			case '1':
				$file_text	=	str_replace('/*HOVER*/',		'opacity: 0.8;', $file_text );
				break;
			case '2':
				$file_text	=	str_replace('/*HOVER*/',		'box-shadow: 4px 4px 8px rgba(0, 0, 0, 0.25); transform: translateX(-4px) translateY(-4px);', $file_text );
				$file_text	=	str_replace('/*WRAP*/',			'transition: all 0.3s ease 0s;', $file_text );
				break;
			case '3':
				$file_text	=	str_replace('/*WRAP*/',			'transition: all 0.3s ease 0s;', $file_text );
				$file_text	=	str_replace('/*HOVER*/',		'box-shadow: 16px 16px 16px rgba(0, 0, 0, 0.5); transform: translateX(-4px) translateY(-4px);', $file_text );
				break;
			case '4':
				$file_text	=	str_replace('/*WRAP*/',			'transition: all 0.3s ease 0s;', $file_text );
				$file_text	=	str_replace('/*HOVER*/',		'box-shadow: 1px 4px 8px rgba(0, 0, 0, 0.25); transform: translateX(4px) translateY(4px);', $file_text );
				break;
			case '7':
				$file_text	=	str_replace('/*WRAP*/',			'transition: all 0.3s ease 0s;', $file_text );
				$file_text	=	str_replace('/*HOVER*/',		'border-radius: 40px;', $file_text );
				break;
			}

			// サムネイルの枠線と影
			if (isset($prop['thumbnail-border'] ) && $prop['thumbnail-border'] ) {
				$file_text	=	str_replace('/*THUMBNAIL-BORDER*/',			'border: 1px solid rgba(0, 0, 0, 0.4) !IMPORTANT;', $file_text );
			}

			// サムネイル影あり
			$thumbnail_adjust		=	2;
			if (isset($prop['thumbnail-shadow'] ) && $prop['thumbnail-shadow'] == '1' ) {
				$file_text			=	str_replace('/*THUMBNAIL-SHADOW*/',			'box-shadow: 4px 4px 8px rgba(0, 0, 0, 0.7);', $file_text );
				$thumbnail_adjust	=	10;		// 影が増えた分、記事の領域を狭める
			}

			// サムネイル角まる指定あり
			if	(isset($prop['thumbnail-radius'] ) && $prop['thumbnail-radius'] ) {
				$file_text	=	str_replace('/*THUMBNAIL-RADIUS*/',			'border-radius: '.$prop['thumbnail-radius'].'; -webkit-border-radius: '.$prop['thumbnail-radius'].'; -moz-border-radius: '.$prop['thumbnail-radius'].';',		$file_text );
			} else {
				$file_text	=	str_replace('/*THUMBNAIL-RADIUS*/',			'',		$file_text );
			}

			// サムネイルの位置とサイズ
			$thumbnail_width	= intval(preg_replace('/[^0-9]/', '',		$prop['thumbnail-width'] ) );
			$thumbnail_height	= intval(preg_replace('/[^0-9]/', '',		$prop['thumbnail-height'] ) );
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

			// サムネイルのリサイズ
			if (isset($prop['thumbnail-resize'] ) && $prop['thumbnail-resize'] ) {
				$size_title			=	intval(preg_replace('/[^0-9]/', '', $prop['title-size'] ) );
				$size_excerpt		=	intval(preg_replace('/[^0-9]/', '', $prop['excerpt-size'] ) );
				$height_title		=	intval(preg_replace('/[^0-9]/', '', $prop['title-height'] ) );
				$height_excerpt		=	intval(preg_replace('/[^0-9]/', '', $prop['excerpt-height'] ) );
				$thumbnail_height	=	intval(preg_replace('/[^0-9]/', '', $prop['thumbnail-height'] ) );
				$thumbnail_width	=	intval(preg_replace('/[^0-9]/', '', $prop['thumbnail-width'] ) );
				$file_text	=	str_replace('/*RESIZE*/',
					'@media screen and ( max-width: 600px )  { .lkc-title { font-size: '.intval($size_title * 0.9).'px; line-height: '.intval($height_title * 0.9).'px; } .lkc-excerpt { font-size: '.intval($size_excerpt * 0.95).'px; } .lkc-thumbnail { width: '.intval($thumbnail_width * 0.9).'px !important; } img.lkc-thumbnail-img { height: '.intval($thumbnail_height * 0.9).'px !important; width: '.intval($thumbnail_width * 0.9).'px !important; } }'.
					'@media screen and ( max-width: 480px )  { .lkc-title { font-size: '.intval($size_title * 0.8).'px; line-height: '.intval($height_title * 0.8).'px; } .lkc-excerpt { font-size: '.intval($size_excerpt * 0.8 ).'px; } .lkc-thumbnail { width: '.intval($thumbnail_width * 0.7).'px !important; } img.lkc-thumbnail-img { height: '.intval($thumbnail_height * 0.7).'px !important; width: '.intval($thumbnail_width * 0.7).'px !important; } }'.
					'@media screen and ( max-width: 320px )  { .lkc-title { font-size: '.intval($size_title * 0.7).'px; line-height: '.intval($height_title * 0.7).'px; } .lkc-excerpt { font-size: '.intval($size_excerpt * 0.6 ).'px; } .lkc-thumbnail { width: '.intval($thumbnail_width * 0.5).'px !important; } img.lkc-thumbnail-img { height: '.intval($thumbnail_height * 0.5).'px !important; width: '.intval($thumbnail_width * 0.5).'px !important; } }', $file_text );
			}

			// 横幅
			if		($prop['width']	==	null ) {
				$prop['width']		=	'100%';
			}
			$width_value	=	intval($prop['width'] );
			$width_unit		=	substr($prop['width'], -1 ) == '%'	?	'%'		:	'px';
			if	($width_unit	==	'%' ) {
				$file_text	=	str_replace('/*WIDTH*/',			'width: '.$width_value.$width_unit.';',			$file_text );
			} else {
				$file_text	=	str_replace('/*WIDTH*/',			'max-width: '.$width_value.$width_unit.';',		$file_text );
			}

			// 記事情報の高さ
			$content_height	=	$prop['content-height'];
			if	($content_height	==	'' ) {
			} else {
				$content_height		=	intval($content_height );
				if	($content_height	>	0 ) {
					$content_height	.=	'px';
				}
				$file_text	=	str_replace('/*CONTENT-HEIGHT*/',		'height: '.$content_height.';',				$file_text );
			}

			// 抜粋文の部分を凹ませる
			if (isset($prop['content-inset'] ) && $prop['content-inset'] == '1' ) {
				$file_text	=	str_replace('/*CONTENT-PADDING*/',	'padding: 6px;', $file_text );
				$file_text	=	str_replace('/*CONTENT-INSET*/',	'box-shadow:  inset 4px 4px 4px rgba(255,255,255,1);', $file_text );
				$file_text	=	str_replace('/*CONTENT-BG-COLOR*/',	'background-color: rgba(255, 255, 255, 0.8 );', $file_text );
			}

			// 記事情報のマージン（上下）
			switch ($prop['info-position'] ) {
			case 1:				// サイト情報が上（記事内容の上に余白を設定）
				$file_text	=	str_replace('/*CONTENT-MARGIN*/',		'margin: 6px 0 0 0;', $file_text );
				break;
			case 2:				// サイト情報が下（記事内容の下に余白を設定）
				$file_text	=	str_replace('/*CONTENT-MARGIN*/',		'margin: 0 0 8px 0;', $file_text );
				break;
			default:
				$file_text	=	str_replace('/*CONTENT-MARGIN*/',		'margin: 0;', $file_text );
				break;
			}

			// 抜粋文のマージン
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




			// リンクタイプごとの設定
			foreach		(array('ex', 'in', 'th' )	as	$t ) {
				$T		=	strtoupper($t );

				// 背景色
				$key				=	$t.'-bg-color';
				$value				=	$prop[$key];
				$pname				=	strtoupper($key );
				if		($value ) {
					$file_text		=	str_replace('/*'.$pname.'*/',	'background-color: '.$value.';',	$file_text );
				}

				// 背景画像
				$key				=	$t.'-image';
				$value				=	$prop[$key];
				$pname				=	strtoupper($key );
				if		($value ) {
					if	(preg_match('/https?(:\/\/[-_.!~*\'()a-zA-Z0-9;\/?:\@&=+\$,%#]+)$/',	$$value ) ) {
						$file_text	=	str_replace('/*'.$pname.'*/',	'background-image: url("'.esc_url($value ).'");',		$file_text );
					} else {
						$file_text	=	str_replace('/*'.$pname.'*/',	'background-image: '.esc_html($value ).';',				$file_text );
					}
				}

				// 外部リンクの枠線
				$value_style		=	$prop['border-style'];
				if	($value_style ) {
					$value_color	=	$prop[$t.'-border-color'];
					$value_width	=	$prop['border-width'];
					$value_width	=	strval(intval(preg_replace('/[^0-9]/', '', $value_width ) ) );
					$border			=	'border: '.
										($value_color		?	$value_color		:	'' ).' '.
										($value_style		?	$value_style		:	'' ).' '.
										($value_width		?	$value_width.'px'	:	'' ).';';
					$file_text		=	str_replace('/*'.strtoupper($t ).'-BORDER*/',	$border, $file_text );
				}

				// 影あり
				$param				=	'';
				// '4px 4px 8px rgba(0,0,0,0.5)'
				// '8px 8px 8px rgba(0,0,0,0.5)'
				// '16px 16px 8px rgba(0,0,0,0.5)'	
				// '32px 32px 16px rgba(0,0,0,0.2)'
				// 'inset 8px 8px 8px rgba(0,0,0,0.5)'
				// 'inset 4px 4px 4px rgba(255,255,255,0.5), inset -4px -4px 4px rgba(0,0,0,0.5)'
				// 'inset 4px 4px 4px rgba(255,255,255,0.5), inset -4px -4px 4px rgba(0,0,0,0.5)'
				if (isset($prop['shadow'] ) && ($prop['shadow'] == '1' ) ) {
					$param			.=	'8px 8px 8px rgba(0,0,0,0.3)';
				}
				if (isset($prop['shadow-inset'] ) && $prop['shadow-inset'] == '1' ) {
					if		($param ) {
						$param		.=	' , ';
					}
					$param			.=	'inset -8px -8px 16px rgba(0,0,0,0.5)';
				}
				if	($param ) {
					$file_text		=	str_replace('/*'.$T.'-SHADOW*/',		'box-shadow: '.$param.';',	$file_text );
				}

				// 角まる指定あり
				if		($prop['radius'] ) {
					$file_text	=	str_replace('/*'.$T.'-RADIUS*/',				'border-radius: '.$prop['radius'].'; -webkit-border-radius: '.$prop['radius'].'; -moz-border-radius: '.$prop['radius'].';',		$file_text );
				} else {
					$file_text	=	str_replace('/*'.$T.'-RADIUS*/',				'',		$file_text );
				}

				// ヘッダーの位置
				$pos					=	intval($prop['heading-height'] ) / 2 + intval($prop['border-width'] ) * 1;
				$file_text	=	str_replace('/*'.$T.'-HEADING*/',					'position: absolute; top: -'.$pos.'px; left: 20px; padding: 0 '.$pos.'px; ',		$file_text );
// 				$pos					=	-intval($prop['border-width'] );
//				$file_text	=	str_replace('/*'.$T.'-HEADING*/',					'position: absolute; top: '.$pos.'px; left: '.$pos.'px; padding: 0 8px;',				$file_text );

				// ヘッダーの枠線
				if	($prop['border-style'] ) {
					$param				=	'border: '
											.($prop[$t.'-border-color']  	?	$prop[$t.'-border-color'].' '	:	'' )
											.($prop['border-style']  		?	$prop['border-style'].' '		:	'' )
											.($prop['border-width']  		?	$prop['border-width'].' '		:	'' ).' /*IMPORTANT*/;';
					$file_text			=	str_replace('/*'.$T.'-HEADING-BORDER*/',			$param,		$file_text );
				}

				// ヘッダーの枠の角丸
				if	($prop['radius'] ) {
					$file_text			=	str_replace('/*'.$T.'-HEADING-RADIUS*/',			'border-radius: '.$prop['radius'].';',			$file_text );
				}

				// ヘッダーの影
				if	($prop['shadow'] ) {
					$file_text			=	str_replace('/*'.$T.'-HEADING-SHADOW*/',			'box-shadow: 8px 8px 8px rgba(0,0,0,0.3);',		$file_text );
				}

				// ヘッダーの背景色
				if	($prop['heading-bg-color'] || $prop[$t.'-bg-color'] ) {
					$param				=	'background-color: '
											.($prop['heading-bg-color']		?	$prop['heading-bg-color']	:	
											 ($prop[$t.'-bg-color']			?	$prop[$t.'-bg-color']			:	'' ) ).';';
					$file_text			=	str_replace('/*'.$T.'-HEADING-BG-COLOR*/',			$param,		$file_text );
				}

				// 続きを読むボタンの書式
				if	($prop['border-style'] ) {
					$border				=	'border: '
											.($prop[$t.'-border-color']  	?	$prop[$t.'-border-color'].' '	:	'' )
											.($prop['border-style']  		?	$prop['border-style'].' '		:	'' )
											.($prop['border-width']  		?	$prop['border-width'].' '		:	'' ).' /*IMPORTANT*/; '
											.'border-radius: 4px;';
				} else {
					$border				=	'';
				}
				$position12				=	'position: absolute; bottom: 12px; right: 12px; padding: 0 12px; ';
				$position10				=	'position: absolute; bottom: 10px; right: 10px; padding: 0 12px; ';
				$position08				=	'position: absolute; bottom:  8px; right:  8px; padding: 0 12px; ';
				$bg_color				=	'background-color: '.
											($prop['more-bg-color']			?	$prop['more-bg-color']			:
											($prop[$t.'-bg-color']	 	 	?	$prop[$t.'-bg-color']			:	'' ) ).'; ';
				switch	($prop['more-style'] ) {
				case	'TXT':
					$file_text			=	str_replace('/*'.$T.'-MOREBTN*/',		$position08,						$file_text );
					break;
				case	'SMP':
					$file_text			=	str_replace('/*'.$T.'-MOREBTN*/',		$position08.$border.$bg_color,		$file_text );
					break;
				case	'BTN':
					$file_text			=	str_replace('/*'.$T.'-MOREBTN*/',		$position12.$border.$bg_color.'box-shadow: 4px 4px 4px rgba(0,0,0,0.5 );',		$file_text );
					break;
				case	'PSH':
					$file_text			=	str_replace('/*'.$T.'-MOREBTN*/',		$position12.$border.$bg_color.'box-shadow: 4px 4px 4px rgba(0,0,0,0.5 ); transition: all 0.2s ease-in-out;',		$file_text );
					$file_text			=	str_replace('/*MOREBTN-HOVER*/',		$position10.                  'box-shadow: 2px 2px 4px rgba(0,0,0,0.5 ); transition: all 0.2s ease-in-out;',		$file_text );
					$file_text			=	str_replace('/*MOREBTN-ACTIVE*/',		$position08.                  'box-shadow: 0   0   4px rgba(0,0,0,0.5 ); transition: all 0.2s ease-in-out;',		$file_text );
					break;
				default:
					$file_text			=	str_replace('/*'.$T.'-MOREBTN*/',		'display: none;',		$file_text );
					break;
				}
			}

			// 追加CSS
			if (isset($prop['css-add'] ) ) {
				$file_text	=	str_replace('/*CSS-ADD*/',			esc_html($prop['css-add'] ), $file_text );
			} else {
				$file_text	=	str_replace('/*CSS-ADD*/',			'', $file_text );
			}

			// ぽぽづれ。へのリンクを表示する
			if (isset($prop['plugin-link'] ) && $prop['plugin-link'] == '1' ) {
				$file_text	=	str_replace('/*CREDIT*/',			'display: block;', $file_text );
			} else {
				$file_text	=	str_replace('/*CREDIT*/',			'display: none;', $file_text );
			}

			// 文字セット
			$charset		=	'@charset "'.$this->charset.'";';											// 文字セット
			$info_text		=	'/* '.self::PLUGIN_NAME.' ver.'.PZLKC_PLUGIN_VERSION.' CSS #'.$this->now.' */';	// プラグイン名＋バージョン
			$info_text_comp	=	'/*'.self::PLUGIN_ACRONYM.PZLKC_PLUGIN_VERSION.'#'.$this->now.'*/';				// プラグイン名＋バージョン（圧縮時）

			// ファイルの圧縮
			$file_text		=	preg_replace('/\s*\/\*[^*]*\*+([^\/][^*]*\*+)*\//', '', $file_text );		// コメント除去
			$css_text		=	$charset.PHP_EOL.$info_text.PHP_EOL.PHP_EOL.$file_text;
			$css_text_comp	=	$charset.$this->pz_CompressCSS($file_text ).$info_text_comp;

			// ファイル出力
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
