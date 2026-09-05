<?php defined('ABSPATH' ) || wp_die; ?>
<?php
	// 繧ｹ繧ｿ繧､繝ｫ繧ｷ繝ｼ繝医・繝代せ繧堤畑諢・
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
		// 繝・Φ繝励Ξ繝ｼ繝医ヵ繧｡繧､繝ｫ縺ｮ隱ｭ縺ｿ霎ｼ縺ｿ
		$file_text	=	file_get_contents(PZLKC_FILE_TEMPLATE );
		if ($file_text ) {
			// 縺九ｓ縺溘ｓ譖ｸ蠑剰ｨｭ螳・
			switch ($prop['special-format'] ) {
			case 'LkC': // Pz-LkC Default
				$file_text		=	str_replace('/*EX-IMAGE*/',			'background-image: linear-gradient(#78f 0%, #78f 10%, #fff 30%);', $file_text );
				$file_text		=	str_replace('/*IN-IMAGE*/',			'background-image: linear-gradient(#ca4 0%, #ca4 10%, #fff 30%);', $file_text );
				$file_text		=	str_replace('/*TH-IMAGE*/',			'background-image: linear-gradient(#ca4 0%, #ca4 10%, #eee 30%);', $file_text );
				break;
			case 'hbc': // 繝弱・繝槭Ν・医・縺ｦ縺ｪ繝悶Ο繧ｰ繧ｫ繝ｼ繝蛾｢ｨ・・
				$file_text	=	str_replace('/*EX-BORDER*/',			'border: 1px solid rgba(0,0,0,0.1);', $file_text );
				$file_text	=	str_replace('/*IN-BORDER*/',			'border: 1px solid rgba(0,0,0,0.1);', $file_text );
				$file_text	=	str_replace('/*TH-BORDER*/',			'border: 1px solid rgba(0,0,0,0.1);', $file_text );
				$file_text	=	str_replace('/*EX-RADIUS*/',			'border-radius: 3px; -webkit-border-radius: 3px; -moz-border-radius: 3px;', $file_text );
				$file_text	=	str_replace('/*IN-RADIUS*/',			'border-radius: 3px; -webkit-border-radius: 3px; -moz-border-radius: 3px;', $file_text );
				$file_text	=	str_replace('/*TH-RADIUS*/',			'border-radius: 3px; -webkit-border-radius: 3px; -moz-border-radius: 3px;', $file_text );
				$file_text	=	str_replace('/*SHADOW*/',				'box-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);', $file_text );
				break;
			case 'smp': // Simple・医し繝繝阪う繝ｫ縺ｨ繧ｿ繧､繝医Ν・・
				$file_text	=	str_replace('/*EX-BORDER*/',			'border: none;', $file_text );
				$file_text	=	str_replace('/*IN-BORDER*/',			'border: none;', $file_text );
				$file_text	=	str_replace('/*TH-BORDER*/',			'border: none;', $file_text );
				$file_text	=	str_replace('/*NONE-INFO*/',			'display: none !important;', $file_text );
				$file_text	=	str_replace('/*NONE-EXCERPT*/',			'display: none !important;', $file_text );
				break;
			case 'cmp': // 繧ｳ繝ｳ繝代け繝茨ｼ・witter鬚ｨ・・
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
			case 'JIN': // 隕句・縺暦ｼ医ユ繝ｼ繝曷IN鬚ｨ・・
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
			case 'ecl': // 蝗ｲ縺ｿ
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
			case 'ref': // 蜿榊ｰ・
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
			case 'ct1': // 繧ｻ繝ｭ繝輔ぃ繝ｳ繝・・繝暦ｼ井ｸｭ螟ｮ・・
				$file_text	=	str_replace('/*WRAP-BEFORE*/',		'content: ""; display: block; position: absolute; left:   40%; top: -16px; width: 95px; height: 25px; z-index: 2; background-color: rgba(243,245,228,0.5); border: 2px solid rgba(255,255,255,0.5); -webkit-box-shadow: 1px 1px 4px rgba(200,200,180,0.8); -moz-box-shadow: 1px 1px 4px rgba(200,200,180,0.8); box-shadow: 1px 1px 4px rgba(200,200,180,0.8); -webkit-transform: rotate(3deg); -moz-transform: rotate(3deg); -o-transform: rotate(3deg);', $file_text );
				$file_text	=	str_replace('/*SHADOW*/',			'box-shadow: 0px 0px 2px rgba(0, 0, 0, 0.2);', $file_text );
				break;
			case 'ct2': // 繧ｻ繝ｭ繝輔ぃ繝ｳ繝・・繝暦ｼ亥ｷｦ蜿ｳ・・
				$file_text	=	str_replace('/*MARGIN-LEFT*/',		'padding-left: 40px;', $file_text );
				$file_text	=	str_replace('/*MARGIN-RIGHT*/',		'padding-right: 25px;', $file_text );
				$file_text	=	str_replace('/*WRAP-BEFORE*/',		'content: ""; display: block; position: absolute; left:  -40px; top: -4px; width: 75px; height: 25px; z-index: 2; background-color: rgba(243,245,228,0.5); border: 2px solid rgba(255,255,255,0.5); -webkit-box-shadow: 1px 1px 4px rgba(200,200,180,0.8); -moz-box-shadow: 1px 1px 4px rgba(200,200,180,0.8); box-shadow: 1px 1px 4px rgba(200,200,180,0.8); -webkit-transform: rotate(-45deg); -moz-transform: rotate(-45deg); -o-transform: rotate(-45deg);', $file_text );
				$file_text	=	str_replace('/*WRAP-AFTER*/',		'content: ""; display: block; position: absolute; right: -20px; top: -2px; width: 75px; height: 25px; z-index: 2; background-color: rgba(243,245,228,0.5); border: 2px solid rgba(255,255,255,0.5); -webkit-box-shadow: 1px 1px 4px rgba(200,200,180,0.8); -moz-box-shadow: 1px 1px 4px rgba(200,200,180,0.8); box-shadow: 1px 1px 4px rgba(200,200,180,0.8); -webkit-transform: rotate(16deg); -moz-transform: rotate(16deg); -o-transform: rotate(16deg); transform: rotate(16deg);', $file_text );
				$file_text	=	str_replace('/*SHADOW*/',			'box-shadow: 0px 0px 2px rgba(0, 0, 0, 0.2);', $file_text );
				break;
			case 'ct3': // 繧ｻ繝ｭ繝輔ぃ繝ｳ繝・・繝暦ｼ磯聞繧・ｼ・
				$file_text	=	str_replace('/*MARGIN-LEFT*/',		'padding-left: 32px;', $file_text );
				$file_text	=	str_replace('/*MARGIN-RIGHT*/',		'padding-right: 32px;', $file_text );
				$file_text	=	str_replace('/*WRAP-BEFORE*/', 		'content: ""; display: block; position: absolute; left:   -5%; top: -12px; width: 110%; height: 25px; z-index: 2; background-color: rgba(243,245,228,0.5); border: 2px solid rgba(255,255,255,0.5); -webkit-box-shadow: 1px 1px 4px rgba(200,200,180,0.8); -moz-box-shadow: 1px 1px 4px rgba(200,200,180,0.8); box-shadow: 1px 1px 4px rgba(200,200,180,0.8); -webkit-transform: rotate(-3deg); -moz-transform: rotate(-3deg); -o-transform: rotate(-3deg);', $file_text );
				$file_text	=	str_replace('/*SHADOW*/',			'box-shadow: 0px 0px 2px rgba(0, 0, 0, 0.2);', $file_text );
				break;
			case 'ct4': // 繧ｻ繝ｭ繝輔ぃ繝ｳ繝・・繝暦ｼ域万繧・ｼ・
				$file_text	=	str_replace('/*MARGIN-LEFT*/',		'padding-left: 24px;', $file_text );
				$file_text	=	str_replace('/*WRAP-BEFORE*/',		'content: ""; display: block; position: absolute; left:  -24px; top: 0px; width: 200px; height: 25px; z-index: 2; background-color: rgba(243,245,228,0.5); border: 2px solid rgba(255,255,255,0.5); -webkit-box-shadow: 1px 1px 4px rgba(200,200,180,0.8); -moz-box-shadow: 1px 1px 4px rgba(200,200,180,0.8); box-shadow: 1px 1px 4px rgba(200,200,180,0.8); -webkit-transform: rotate(-8deg); -moz-transform: rotate(-8deg); -o-transform: rotate(-8deg);', $file_text );
				$file_text	=	str_replace('/*SHADOW*/',			'box-shadow: 0px 0px 2px rgba(0, 0, 0, 0.2);', $file_text );
				break;
			case 'ppc': // 邏吶ａ縺上ｌ
				$file_text	=	str_replace('/*WRAP-AFTER*/',		'z-index: -1; content:""; height: 10px; width: 60%; position: absolute; right: 16px; bottom: 14px; left: auto; transform: skew(5deg) rotate(3deg); -webkit-transform: skew(5deg) rotate(3deg); -moz-transform: skew(5deg) rotate(3deg); box-shadow: 0 16px 16px rgba(0,0,0,1); -webkit-box-shadow: 0 16px 16px rgba(0,0,0,1); -moz-box-shadow: 0 16px 12px rgba(0,0,0,1);', $file_text );
				$file_text	=	str_replace('/*SHADOW*/',			'box-shadow: 0px 2px 6px rgba(0, 0, 0, 0.8);', $file_text );
				$file_text	=	str_replace('/*OPTION*/',			'article { position: relative; z-index: 0; } article blockquote { position: relative; z-index: 0; }', $file_text );
				break;
			case 'tac': // 繝・・繝励→邏吶ａ縺上ｌ
				$file_text	=	str_replace('/*MARGIN-LEFT*/',		'padding-left: 24px;', $file_text );
				$file_text	=	str_replace('/*WRAP-BEFORE*/',		'content: ""; display: block; position: absolute; left:  -24px; top: 0px; width: 200px; height: 25px; z-index: 2; background-color: rgba(243,245,228,0.5); border: 2px solid rgba(255,255,255,0.5); -webkit-box-shadow: 1px 1px 4px rgba(200,200,180,0.8); -moz-box-shadow: 1px 1px 4px rgba(200,200,180,0.8); box-shadow: 1px 1px 4px rgba(200,200,180,0.8); -webkit-transform: rotate(-8deg); -moz-transform: rotate(-8deg); -o-transform: rotate(-8deg);', $file_text );
				$file_text	=	str_replace('/*WRAP-AFTER*/',		'z-index: -1; content:""; height: 10px; width: 60%; position: absolute; right: 16px; bottom: 14px; left: auto; transform: skew(5deg) rotate(3deg); -webkit-transform: skew(5deg) rotate(3deg); -moz-transform: skew(5deg) rotate(3deg); box-shadow: 0 16px 16px rgba(0,0,0,1); -webkit-box-shadow: 0 16px 16px rgba(0,0,0,1); -moz-box-shadow: 0 16px 12px rgba(0,0,0,1);', $file_text );
				$file_text	=	str_replace('/*SHADOW*/',			'box-shadow: 0px 2px 6px rgba(0, 0, 0, 0.8);', $file_text );
				$file_text	=	str_replace('/*OPTION*/',			'article { position: relative; z-index: 0; } article blockquote { position: relative; z-index: 0; }', $file_text );
				break;
			case 'sBR': // 邵ｫ縺・岼・磯搨・・ｵ､・・
				$file_text	=	str_replace('/*EX-BORDER*/',		'border: 2px dashed rgba(255,255,255,0.5);', $file_text );
				$file_text	=	str_replace('/*IN-BORDER*/',		'border: 2px dashed rgba(255,255,255,0.5);', $file_text );
				$file_text	=	str_replace('/*TH-BORDER*/',		'border: 2px dashed rgba(255,255,255,0.5);', $file_text );
				$file_text	=	str_replace('/*EX-BG-COLOR*/',		'background: #bcddff; box-shadow: 0 0 0 5px #aabbee, 3px 3px 6px 4px rgba(0,0,0,0.6); -moz-box-shadow: 0 0 0 5px #aabbee, 3px 3px 6px 4px rgba(0,0,0,0.6); -webkit-box-shadow: 0 0 0 5px #aabbee, 3px 3px 6px 4px rgba(0,0,0,0.6);', $file_text );
				$file_text	=	str_replace('/*IN-BG-COLOR*/',		'background: #f8d0d0; box-shadow: 0 0 0 5px #e8a8a8, 3px 3px 6px 4px rgba(0,0,0,0.6); -moz-box-shadow: 0 0 0 5px #e8a8a8, 3px 3px 6px 4px rgba(0,0,0,0.6); -webkit-box-shadow: 0 0 0 5px #e8a8a8, 3px 3px 6px 4px rgba(0,0,0,0.6);', $file_text );
				$file_text	=	str_replace('/*TH-BG-COLOR*/',		'background: #f29db0; box-shadow: 0 0 0 5px #de8899, 3px 3px 6px 4px rgba(0,0,0,0.6); -moz-box-shadow: 0 0 0 5px #de8899, 3px 3px 6px 4px rgba(0,0,0,0.6); -webkit-box-shadow: 0 0 0 5px #de8899, 3px 3px 6px 4px rgba(0,0,0,0.6);', $file_text );
				break;
			case 'sGY': // 邵ｫ縺・岼・育ｷ托ｼ・ｻ・ｼ・
				$file_text	=	str_replace('/*EX-BORDER*/',		'border: 2px dashed rgba(255,255,255,0.5);', $file_text );
				$file_text	=	str_replace('/*IN-BORDER*/',		'border: 2px dashed rgba(255,255,255,0.5);', $file_text );
				$file_text	=	str_replace('/*TH-BORDER*/',		'border: 2px dashed rgba(255,255,255,0.5);', $file_text );
				$file_text	=	str_replace('/*EX-BG-COLOR*/',		'background: #acefdd; box-shadow: 0 0 0 5px #8abecb, 3px 3px 6px 4px rgba(0,0,0,0.6); -moz-box-shadow: 0 0 0 5px #8abecb, 3px 3px 6px 4px rgba(0,0,0,0.6); -webkit-box-shadow: 0 0 0 5px #8abecb, 3px 3px 6px 4px rgba(0,0,0,0.6);', $file_text );
				$file_text	=	str_replace('/*IN-BG-COLOR*/',		'background: #ffde51; box-shadow: 0 0 0 5px #fbca4d, 3px 3px 6px 4px rgba(0,0,0,0.6); -moz-box-shadow: 0 0 0 5px #fbca4d, 3px 3px 6px 4px rgba(0,0,0,0.6); -webkit-box-shadow: 0 0 0 5px #fbca4d, 3px 3px 6px 4px rgba(0,0,0,0.6);', $file_text );
				$file_text	=	str_replace('/*TH-BG-COLOR*/',		'background: #f0e0b0; box-shadow: 0 0 0 5px #decca0, 3px 3px 6px 4px rgba(0,0,0,0.6); -moz-box-shadow: 0 0 0 5px #decca0, 3px 3px 6px 4px rgba(0,0,0,0.6); -webkit-box-shadow: 0 0 0 5px #decca0, 3px 3px 6px 4px rgba(0,0,0,0.6);', $file_text );
				break;
			case 'pin': // 謚ｼ縺励ヴ繝ｳ・育ｶｺ鮗励↑逕ｻ蜒丞供髮・ｸｭ・・
				$file_text	=	str_replace('/*WRAP-AFTER*/',		'content: ""; display: block; position: absolute; background-image: url("'.$this->plugin_dir_url.'img/pin.png"); background-repeat: no-repeat; background-position: center; left: 47%; top: -16px; width: 40px; height: 40px; z-index: 1; pointer-events: none;', $file_text );
				break;
			case 'inN': // 荳ｭ遶矩搨邱托ｼ医う繝ｳ繧ｰ繝ｬ繧ｹ鬚ｨ・・
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
			case 'inI': // 諠・ｱ繧ｪ繝ｬ繝ｳ繧ｸ・医う繝ｳ繧ｰ繝ｬ繧ｹ鬚ｨ・・
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
			case 'inE': // 繧ｨ繝ｳ繝ｩ繧､繝・ャ繝峨き繝ｩ繝ｼ・医う繝ｳ繧ｰ繝ｬ繧ｹ鬚ｨ・・
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
			case 'inR': // 繝ｬ繧ｸ繧ｹ繧ｿ繝ｳ繧ｹ繧ｫ繝ｩ繝ｼ・医う繝ｳ繧ｰ繝ｬ繧ｹ鬚ｨ・・
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
			case 'slt': // 繝阪ち・滂ｼ壽万繧・
				$file_text	=	str_replace('/*WRAP*/',					'transform:skew(-10deg) rotate(1deg); -webkit-transform: skew(-10deg) rotate(1deg); -moz-transform:skew(-10deg) rotate(1deg);', $file_text );
				$file_text	=	str_replace('/*MARGIN-LEFT*/',			'padding-left: 12px;', $file_text );
				$file_text	=	str_replace('/*MARGIN-RIGHT*/',			'padding-right: 30px;', $file_text );
				break;
			case '3Dr': // 繝阪ち・滂ｼ夂ｫ倶ｽ・
				$file_text	=	str_replace('/*WRAP*/',					'-webkit-transform:perspective(150px) scale3d(0.84,0.9,1) rotate3d(1,0,0,12deg);',			$file_text );
				$file_text	=	str_replace('/*SHADOW*/',				'box-shadow: 0 20px 16px rgba(0, 0, 0, 0.6) , 0px 32px 32px rgba(0, 0, 0, 0.2) inset;',		$file_text );
				break;
			case 'sqr': // 繧ｹ繧ｯ繧ｨ繧｢・・ordPress讓呎ｺ夜｢ｨ・・
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

			// 繝・く繧ｹ繝医・驕ｸ謚樒ｦ∵ｭ｢
			if		($prop['flg-unti-select'] ) {
				$file_text			=	str_replace('/*SELECTION*/',		'user-select: none;',		$file_text );
			}

			// 譁・ｭ苓牡
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

			// 繧ｫ繝ｼ繝峨・蜻ｨ繧翫∈縺ｮ菴咏區
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

			// 繧ｫ繝ｼ繝峨・菴咏區遲芽ｪｿ謨ｴ
			$file_text	=	str_replace('/*PADDING*/',				'padding: 0;', $file_text );

			// 繧ｫ繝ｼ繝牙・蛛ｴ縺ｮ菴咏區
			$margin_top		=	$prop['card-top']		== ''	? '8px' : $prop['card-top'];
			$margin_bottom	=	$prop['card-bottom']	== ''	? '8px' : $prop['card-bottom'];
			$margin_left	=	$prop['card-left']		== ''	? '8px' : $prop['card-left'];
			$margin_right	=	$prop['card-right']		== ''	? '8px' : $prop['card-right'];
			$file_text		=	str_replace('/*CARD-TOP*/',		'margin-top: '.		$margin_top.	';', $file_text );
			$file_text		=	str_replace('/*CARD-BOTTOM*/',	'margin-bottom: '.	$margin_bottom.	';', $file_text );
			$file_text		=	str_replace('/*CARD-LEFT*/',	'margin-left: '.	$margin_left.	';', $file_text );
			$file_text		=	str_replace('/*CARD-RIGHT*/',	'margin-right: '.	$margin_right.	';', $file_text );

			// img 縺ｮ繧ｹ繧ｿ繧､繝ｫ繧貞ｼｷ蛻ｶ繝ｪ繧ｻ繝・ヨ
			if (isset($prop['style-reset-img'] ) ) {
				$file_text	=	str_replace('/*RESET-IMG*/',	'margin: 0 !important; padding: 0; border: none;', $file_text );
				$file_text	=	str_replace('/*STATIC*/',		'position: static !important;', $file_text );
				$file_text	=	str_replace('/*IMPORTANT*/',	'!important', $file_text );
			} else {
				$file_text	=	str_replace('/*IMPORTANT*/',	'', $file_text );
			}

			// 繧ｻ繝ｳ繧ｿ繝ｪ繝ｳ繧ｰ謖・ｮ壹≠繧・
			if (isset($prop['centering'] ) && $prop['centering'] == '1' ) {
				$file_text	=	str_replace('/*WRAP-MARGIN*/',			'margin: 0 auto;',		$file_text );
			} else {
				$file_text	=	str_replace('/*WRAP-MARGIN*/', 			'margin: 0;',			$file_text );
			}

			// 隗偵∪繧区欠螳壹≠繧・
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

			// 蠖ｱ縺ゅｊ
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

			// 繝槭え繧ｹ繧剃ｹ励○縺溘→縺・
			$file_text		=	str_replace('/*EX-HOVER*/',		'', $file_text );
			$file_text		=	str_replace('/*IN-HOVER*/',		'', $file_text );
			$file_text		=	str_replace('/*TH-HOVER*/',		'', $file_text );
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

			// 繧ｵ繝繝阪う繝ｫ縺ｮ譫邱壹→蠖ｱ
			if (isset($prop['thumbnail-border'] ) && $prop['thumbnail-border'] ) {
				$file_text	=	str_replace('/*THUMBNAIL-BORDER*/',			'border: 1px solid rgba(0, 0, 0, 0.4) !IMPORTANT;', $file_text );
			}

			// 繧ｵ繝繝阪う繝ｫ蠖ｱ縺ゅｊ
			$thumbnail_adjust		=	2;
			if (isset($prop['thumbnail-shadow'] ) && $prop['thumbnail-shadow'] == '1' ) {
				$file_text			=	str_replace('/*THUMBNAIL-SHADOW*/',			'box-shadow: 4px 4px 8px rgba(0, 0, 0, 0.7);', $file_text );
				$thumbnail_adjust	=	10;		// 蠖ｱ縺悟｢励∴縺溷・縲∬ｨ倅ｺ九・鬆伜沺繧堤強繧√ｋ
			}

			// 繧ｵ繝繝阪う繝ｫ隗偵∪繧区欠螳壹≠繧・
			if	(isset($prop['thumbnail-radius'] ) && $prop['thumbnail-radius'] ) {
				$file_text	=	str_replace('/*THUMBNAIL-RADIUS*/',			'border-radius: '.$prop['thumbnail-radius'].'; -webkit-border-radius: '.$prop['thumbnail-radius'].'; -moz-border-radius: '.$prop['thumbnail-radius'].';',		$file_text );
			} else {
				$file_text	=	str_replace('/*THUMBNAIL-RADIUS*/',			'',		$file_text );
			}

			// 繧ｵ繝繝阪う繝ｫ縺ｮ菴咲ｽｮ縺ｨ繧ｵ繧､繧ｺ
			$thumbnail_width	= intval(preg_replace('/[^0-9]/', '',		$prop['thumbnail-width'] ) );
			$thumbnail_height	= intval(preg_replace('/[^0-9]/', '',		$prop['thumbnail-height'] ) );
			switch ($prop['thumbnail-position'] ) {
			case '1':			// 蜿ｳ蛛ｴ縺ｫ繧ｵ繝繝阪う繝ｫ
				$file_text	=	str_replace('/*THUMBNAIL-POSITION*/',		'float: right;', $file_text );
				$file_text	=	str_replace('/*THUMBNAIL-MARGIN*/',			'margin: 0 0 0 8px;', $file_text );
				$file_text	=	str_replace('/*THUMBNAIL-WIDTH*/',			'width: '.($thumbnail_width + $thumbnail_adjust ).'px;', $file_text );
				$file_text	=	str_replace('/*THUMBNAIL-IMG-WIDTH*/',		'width: '.$thumbnail_width.'px !important;', $file_text );
				$file_text	=	str_replace('/*THUMBNAIL-IMG-HEIGHT*/',		'height: '.$thumbnail_height.'px !important;', $file_text );
				break;
			case '2':			// 蟾ｦ蛛ｴ縺ｫ繧ｵ繝繝阪う繝ｫ
				$file_text	=	str_replace('/*THUMBNAIL-POSITION*/',		'float: left;', $file_text );
				$file_text	=	str_replace('/*THUMBNAIL-MARGIN*/',			'margin: 0 8px 0 0;', $file_text );
				$file_text	=	str_replace('/*THUMBNAIL-WIDTH*/',			'width: '.($thumbnail_width + $thumbnail_adjust ).'px;', $file_text );
				$file_text	=	str_replace('/*THUMBNAIL-IMG-WIDTH*/',		'width: '.$thumbnail_width .'px !important;', $file_text );
				$file_text	=	str_replace('/*THUMBNAIL-IMG-HEIGHT*/',		'height: '.$thumbnail_height.'px !important;', $file_text );
				break;
			case '3':			// 荳雁・縺ｫ繧ｵ繝繝阪う繝ｫ
				$file_text	=	str_replace('/*THUMBNAIL-POSITION*/',		'display: block;', $file_text );
				$file_text	=	str_replace('/*THUMBNAIL-MARGIN*/',			'margin: 0 0 8px 0;', $file_text );
				$file_text	=	str_replace('/*THUMBNAIL-IMG-WIDTH*/',		'width: calc(100% - 2px) !important;', $file_text );
				$file_text	=	str_replace('/*THUMBNAIL-IMG-HEIGHT*/',		'height: '.$thumbnail_height.'px !important; overflow: hidden;', $file_text );
				break;
			}

			// 繧ｵ繝繝阪う繝ｫ縺ｮ繝ｪ繧ｵ繧､繧ｺ
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

			// 讓ｪ蟷・
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

			// 險倅ｺ区ュ蝣ｱ縺ｮ鬮倥＆
			$content_height	=	$prop['content-height'];
			if	($content_height	==	'' ) {
			} else {
				$content_height		=	intval($content_height );
				if	($content_height	>	0 ) {
					$content_height	.=	'px';
				}
				$file_text	=	str_replace('/*CONTENT-HEIGHT*/',		'height: '.$content_height.';',				$file_text );
			}

			// 謚懃ｲ区枚縺ｮ驛ｨ蛻・ｒ蜃ｹ縺ｾ縺帙ｋ
			if (isset($prop['content-inset'] ) && $prop['content-inset'] == '1' ) {
				$file_text	=	str_replace('/*CONTENT-PADDING*/',	'padding: 6px;', $file_text );
				$file_text	=	str_replace('/*CONTENT-INSET*/',	'box-shadow:  inset 4px 4px 4px rgba(255,255,255,1);', $file_text );
				$file_text	=	str_replace('/*CONTENT-BG-COLOR*/',	'background-color: rgba(255, 255, 255, 0.8 );', $file_text );
			}

			// 險倅ｺ区ュ蝣ｱ縺ｮ繝槭・繧ｸ繝ｳ・井ｸ贋ｸ具ｼ・
			switch ($prop['info-position'] ) {
			case 1:				// 繧ｵ繧､繝域ュ蝣ｱ縺御ｸ奇ｼ郁ｨ倅ｺ句・螳ｹ縺ｮ荳翫↓菴咏區繧定ｨｭ螳夲ｼ・
				$file_text	=	str_replace('/*CONTENT-MARGIN*/',		'margin: 6px 0 0 0;', $file_text );
				break;
			case 2:				// 繧ｵ繧､繝域ュ蝣ｱ縺御ｸ具ｼ郁ｨ倅ｺ句・螳ｹ縺ｮ荳九↓菴咏區繧定ｨｭ螳夲ｼ・
				$file_text	=	str_replace('/*CONTENT-MARGIN*/',		'margin: 0 0 8px 0;', $file_text );
				break;
			default:
				$file_text	=	str_replace('/*CONTENT-MARGIN*/',		'margin: 0;', $file_text );
				break;
			}

			// 謚懃ｲ区枚縺ｮ繝槭・繧ｸ繝ｳ
			$file_text	=	str_replace('/*MARGIN-EXCERPT*/',		'margin: 0;', $file_text );

			// 繧ｵ繧､繝医い繧､繧ｳ繝ｳ
			$file_text	=	str_replace('/*FAVICON-HEIGHT*/',		'height: 16px;', $file_text );
			$file_text	=	str_replace('/*FAVICON-WIDTH*/',		'width: 16px;', $file_text );

			// 繧ｵ繧､繝域ュ蝣ｱ縺ｮ蛹ｺ蛻・ｊ邱・
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




			// 繝ｪ繝ｳ繧ｯ繧ｿ繧､繝励＃縺ｨ縺ｮ險ｭ螳・
			$option_css		=	'';
			foreach		(array('ex', 'in', 'th' )	as	$t ) {
				$T		=	strtoupper($t );
				$wrap_class	= ($t == 'ex' ? '.lkc-external-wrap' : ($t == 'in' ? '.lkc-internal-wrap' : '.lkc-this-wrap' ) );

				$value_transform_enabled	= isset($prop[$t.'-transform-enabled'] ) ? $prop[$t.'-transform-enabled'] : 1;
				if	($value_transform_enabled ) {
					$value_transform_x		= isset($prop[$t.'-transform-x'] ) ? intval($prop[$t.'-transform-x'] ) : 0;
					$value_transform_y		= isset($prop[$t.'-transform-y'] ) ? intval($prop[$t.'-transform-y'] ) : 0;
					$value_transform_rotate	= isset($prop[$t.'-transform-rotate'] ) ? intval($prop[$t.'-transform-rotate'] ) : 0;
					$value_transform_scale	= isset($prop[$t.'-transform-scale'] ) ? intval($prop[$t.'-transform-scale'] ) : 100;
					if	($value_transform_x || $value_transform_y || $value_transform_rotate || $value_transform_scale != 100 ) {
						$option_css			.= $wrap_class.' { transform: translate('.$value_transform_x.'px, '.$value_transform_y.'px) rotate('.$value_transform_rotate.'deg) scale('.($value_transform_scale / 100).'); }';
					}
				}
				$value_transition	= isset($prop[$t.'-transition'] ) ? floatval($prop[$t.'-transition'] ) : 0;
				if	($value_transition > 0 ) {
					$option_css		.= $wrap_class.' { transition: all '.$value_transition.'s ease; }';
				}
				// 閭梧勹濶ｲ
				$key				=	$t.'-bg-color';
				$value				=	$prop[$key];
				$pname				=	strtoupper($key );
				$value_bg_enabled	=	isset($prop[$t.'-bg-enabled'] ) ? $prop[$t.'-bg-enabled'] : 1;
				if		($value_bg_enabled && $value ) {
					$file_text		=	str_replace('/*'.$pname.'*/',	'background-color: '.$value.';',	$file_text );
				}

				// 閭梧勹逕ｻ蜒・
				$key				=	$t.'-image';
				$value				=	$prop[$key];
				$pname				=	strtoupper($key );
				if		($value_bg_enabled && $value ) {
					if	(preg_match('/https?(:\/\/[-_.!~*\'()a-zA-Z0-9;\/?:\@&=+\$,%#]+)$/',	$value ) ) {
						$file_text	=	str_replace('/*'.$pname.'*/',	'background-image: url("'.esc_url($value ).'");',		$file_text );
					} else {
						$file_text	=	str_replace('/*'.$pname.'*/',	'background-image: '.esc_html($value ).';',				$file_text );
					}
				}

				// 螟夜Κ繝ｪ繝ｳ繧ｯ縺ｮ譫邱・
				$value_style		=	isset($prop[$t.'-border-style'] ) ? $prop[$t.'-border-style'] : $prop['border-style'];
				$value_width		=	isset($prop[$t.'-border-width'] ) ? $prop[$t.'-border-width'] : $prop['border-width'];
				$value_width_num	=	strval(intval(preg_replace('/[^0-9]/', '', $value_width ) ) );
				$value_radius		=	isset($prop[$t.'-border-radius'] ) ? $prop[$t.'-border-radius'] : $prop['radius'];
				$value_border_enabled	=	isset($prop[$t.'-border-enabled'] ) ? $prop[$t.'-border-enabled'] : 1;
				if	($value_border_enabled && $value_style ) {
					$value_color	=	$prop[$t.'-border-color'];
					$border			=	'border: '.
										($value_color		?	$value_color		:	'' ).' '.
										($value_style		?	$value_style		:	'' ).' '.
										($value_width_num	?	$value_width_num.'px'	:	'' ).';';
					$file_text		=	str_replace('/*'.strtoupper($t ).'-BORDER*/',	$border, $file_text );
				}

				// 蠖ｱ縺ゅｊ
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
					$value_shadow_inset		=	isset($prop[$t.'-shadow-inset'] ) ? $prop[$t.'-shadow-inset'] : $prop['shadow-inset'];
					$param					=	($value_shadow_inset ? 'inset ' : '' ).
												$value_shadow_x.'px '.$value_shadow_y.'px '.$value_shadow_blur.'px '.$value_shadow_spread.'px '.$value_shadow_color;
				}
				if	($param ) {
					$file_text		=	str_replace('/*'.$T.'-SHADOW*/',		'box-shadow: '.$param.';',	$file_text );
				}

				// 隗偵∪繧区欠螳壹≠繧・
				if		($value_radius ) {
					$file_text	=	str_replace('/*'.$T.'-RADIUS*/',				'border-radius: '.$value_radius.'; -webkit-border-radius: '.$value_radius.'; -moz-border-radius: '.$value_radius.';',		$file_text );
				} else {
					$file_text	=	str_replace('/*'.$T.'-RADIUS*/',				'',		$file_text );
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
				}
				$value_hover_bg_enabled	= isset($prop[$t.'-hover-bg-enabled'] ) ? $prop[$t.'-hover-bg-enabled'] : 0;
				if	($value_hover_bg_enabled ) {
					$value_hover_bg_color	= isset($prop[$t.'-hover-bg-color'] ) ? $prop[$t.'-hover-bg-color'] : '';
					if	($value_hover_bg_color ) {
						$hover_css[]	=	'background-color: '.$value_hover_bg_color.';';
					}
					$value_hover_image	= isset($prop[$t.'-hover-image'] ) ? $prop[$t.'-hover-image'] : '';
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
					$value_hover_border_radius	= isset($prop[$t.'-hover-border-radius'] ) ? $prop[$t.'-hover-border-radius'] : '4px';
					if	($value_hover_border_radius ) {
						$hover_css[]	=	'border-radius: '.$value_hover_border_radius.'; -webkit-border-radius: '.$value_hover_border_radius.'; -moz-border-radius: '.$value_hover_border_radius.';';
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
					$option_css		.=	$wrap_class.':hover { '.implode(' ', $hover_css ).' }';
				}

				// 繝倥ャ繝繝ｼ縺ｮ菴咲ｽｮ
				$pos					=	intval($prop['heading-height'] ) / 2 + ($value_border_enabled ? intval($value_width_num ) : 0 ) * 1;
				$file_text	=	str_replace('/*'.$T.'-HEADING*/',					'position: absolute; top: -'.$pos.'px; left: 20px; padding: 0 '.$pos.'px; ',		$file_text );
// 				$pos					=	-intval($prop['border-width'] );
//				$file_text	=	str_replace('/*'.$T.'-HEADING*/',					'position: absolute; top: '.$pos.'px; left: '.$pos.'px; padding: 0 8px;',				$file_text );

				// 繝倥ャ繝繝ｼ縺ｮ譫邱・
				if	($value_border_enabled && $value_style ) {
					$param				=	'border: '
											.($prop[$t.'-border-color']  	?	$prop[$t.'-border-color'].' '	:	'' )
											.($value_style  				?	$value_style.' '					:	'' )
											.($value_width  				?	$value_width.' '					:	'' ).' /*IMPORTANT*/;';
					$file_text			=	str_replace('/*'.$T.'-HEADING-BORDER*/',			$param,		$file_text );
				}

				// 繝倥ャ繝繝ｼ縺ｮ譫縺ｮ隗剃ｸｸ
				if	($value_radius ) {
					$file_text			=	str_replace('/*'.$T.'-HEADING-RADIUS*/',			'border-radius: '.$value_radius.';',			$file_text );
				}

				// 繝倥ャ繝繝ｼ縺ｮ蠖ｱ
				if	($value_shadow_enabled ) {
					$file_text			=	str_replace('/*'.$T.'-HEADING-SHADOW*/',			'box-shadow: 8px 8px 8px rgba(0,0,0,0.3);',		$file_text );
				}

				// 繝倥ャ繝繝ｼ縺ｮ閭梧勹濶ｲ
				if	($prop['heading-bg-color'] || $prop[$t.'-bg-color'] ) {
					$param				=	'background-color: '
											.($prop['heading-bg-color']		?	$prop['heading-bg-color']	:	
											 ($prop[$t.'-bg-color']			?	$prop[$t.'-bg-color']			:	'' ) ).';';
					$file_text			=	str_replace('/*'.$T.'-HEADING-BG-COLOR*/',			$param,		$file_text );
				}

				// 邯壹″繧定ｪｭ繧繝懊ち繝ｳ縺ｮ譖ｸ蠑・
				if	($value_border_enabled && $value_style ) {
					$border				=	'border: '
											.($prop[$t.'-border-color']  	?	$prop[$t.'-border-color'].' '	:	'' )
											.($value_style  				?	$value_style.' '					:	'' )
											.($value_width  				?	$value_width.' '					:	'' ).' /*IMPORTANT*/; '
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

			// 霑ｽ蜉CSS
			if (isset($prop['css-add'] ) ) {
				$file_text	=	str_replace('/*CSS-ADD*/',			$option_css.esc_html($prop['css-add'] ), $file_text );
			} else {
				$file_text	=	str_replace('/*CSS-ADD*/',			$option_css, $file_text );
			}

			// 縺ｽ縺ｽ縺･繧後ゅ∈縺ｮ繝ｪ繝ｳ繧ｯ繧定｡ｨ遉ｺ縺吶ｋ
			if (isset($prop['plugin-link'] ) && $prop['plugin-link'] == '1' ) {
				$file_text	=	str_replace('/*CREDIT*/',			'display: block;', $file_text );
			} else {
				$file_text	=	str_replace('/*CREDIT*/',			'display: none;', $file_text );
			}

			// 譁・ｭ励そ繝・ヨ
			$charset		=	'@charset "'.$this->charset.'";';											// 譁・ｭ励そ繝・ヨ
			$info_text		=	'/* '.self::PLUGIN_NAME.' ver.'.PZLKC_PLUGIN_VERSION.' CSS #'.$this->now.' */';	// 繝励Λ繧ｰ繧､繝ｳ蜷搾ｼ九ヰ繝ｼ繧ｸ繝ｧ繝ｳ
			$info_text_comp	=	'/*'.self::PLUGIN_ACRONYM.PZLKC_PLUGIN_VERSION.'#'.$this->now.'*/';				// 繝励Λ繧ｰ繧､繝ｳ蜷搾ｼ九ヰ繝ｼ繧ｸ繝ｧ繝ｳ・亥悸邵ｮ譎ゑｼ・

			// 繝輔ぃ繧､繝ｫ縺ｮ蝨ｧ邵ｮ
			$file_text		=	preg_replace('/\s*\/\*[^*]*\*+([^\/][^*]*\*+)*\//', '', $file_text );		// 繧ｳ繝｡繝ｳ繝磯勁蜴ｻ
			$css_text		=	$charset.PHP_EOL.$info_text.PHP_EOL.PHP_EOL.$file_text;
			$css_text_comp	=	$charset.$this->pz_CompressCSS($file_text ).$info_text_comp;

			// 繝輔ぃ繧､繝ｫ蜃ｺ蜉・
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
