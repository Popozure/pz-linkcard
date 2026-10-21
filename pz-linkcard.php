<?php defined('ABSPATH' ) || wp_die; ?>
<?php
/*
Plugin Name:	Pz-LinkCard
Plugin URI:		http://popozure.info/pz-linkcard
Description:	Displays links in card format.
Version:		2.6.3.2.TEST
Author:			Poporon
Author URI:		http://popozure.info
Text Domain:	pz-linkcard
Domain Path:	/languages
License:		GPLv2 or later
*/

class class_pz_linkcard {

	// 設定値
	private		const	DEFAULTS	=
		array(
			'special-format'					=>	['type'	=>	'string',		'null'	=>	true,	'default'	=>	null,			'reset-format'	=>	true, ],
			'saved-date'						=>	['type'	=>	'numeric',		'null'	=>	true,	'default'	=>	null, ],

			'margin-top'						=>	['type'	=>	'pixel',		'null'	=>	true,	'default'	=>	'16px',			'reset-format'	=>	true, ],
			'margin-left'						=>	['type'	=>	'pixel',		'null'	=>	true,	'default'	=>	'16px',			'reset-format'	=>	true, ],
			'card-top'							=>	['type'	=>	'pixel',		'null'	=>	true,	'default'	=>	'8px',			'reset-format'	=>	true, ],
			'info-position'						=>	['type'	=>	'numeric',		'null'	=>	true,	'default'	=>	1,				'reset-format'	=>	true, ],
			'flg-use-sitename'					=>	['type'	=>	'flag',			'null'	=>	true,	'default'	=>	1,				'reset-format'	=>	true, ],
			'card-left'							=>	['type'	=>	'pixel',		'null'	=>	true,	'default'	=>	'8px',			'reset-format'	=>	true, ],
			'thumbnail-position'				=>	['type'	=>	'numeric',		'null'	=>	true,	'default'	=>	2,				'reset-format'	=>	true, ],
			'thumbnail-width'					=>	['type'	=>	'numeric',		'null'	=>	true,	'default'	=>	100,			'reset-format'	=>	true, ],
			'thumbnail-height'					=>	['type'	=>	'numeric',		'null'	=>	true,	'default'	=>	100,			'reset-format'	=>	true, ],
			'width'								=>	['type'	=>	'numeric',		'null'	=>	true,	'default'	=>	500,			'reset-format'	=>	true, ],
			'width-unit'						=>	['type'	=>	'unit',			'null'	=>	true,	'default'	=>	'px',			'reset-format'	=>	true, ],
			'content-height'					=>	['type'	=>	'numeric',		'null'	=>	true,	'default'	=>	104,			'reset-format'	=>	true, ],
			'card-right'						=>	['type'	=>	'pixel',		'null'	=>	true,	'default'	=>	'8px',			'reset-format'	=>	true, ],
			'card-bottom'						=>	['type'	=>	'pixel',		'null'	=>	true,	'default'	=>	'8px',			'reset-format'	=>	true, ],
			'margin-right'						=>	['type'	=>	'pixel',		'null'	=>	true,	'default'	=>	'16px',			'reset-format'	=>	true, ],
			'centering'							=>	['type'	=>	'flag',			'null'	=>	true,	'default'	=>	null,			'reset-format'	=>	true, ],
			'margin-bottom'						=>	['type'	=>	'pixel',		'null'	=>	true,	'default'	=>	'16px',			'reset-format'	=>	true, ],
			'enclose-tag'						=>	['type'	=>	'html_tag',		'null'	=>	false,	'default'	=>	'div',			'reset-format'	=>	true, ],
			'flg-linkall'						=>	['type'	=>	'flag',			'null'	=>	true,	'default'	=>	1,				'reset-format'	=>	true, ],
			'flg-anti-select'					=>	['type'	=>	'flag',			'null'	=>	true,	'default'	=>	null,			'reset-format'	=>	true, ],
			'flg-resize'						=>	['type'	=>	'flag',			'null'	=>	true,	'default'	=>	1,				'reset-format'	=>	true, ],

			'flg-style-reset'					=>	['type'	=>	'flag',			'null'	=>	true,	'default'	=>	1,				'reset-format'	=>	true, ],
			'flg-style-important'				=>	['type'	=>	'flag',			'null'	=>	true,	'default'	=>	1,				'reset-format'	=>	true, ],
			'display-url'						=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	1,				'reset-format'	=>	true, ],
			'display-date'						=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	1,				'reset-format'	=>	true, ],
			'separator'							=>	['type'	=>	'flag',			'null'	=>	true,	'default'	=>	null,			'reset-format'	=>	true, ],
			'display-excerpt'					=>	['type'	=>	'flag',			'null'	=>	true,	'default'	=>	1,				'reset-format'	=>	true, ],
			'content-inset'						=>	['type'	=>	'flag',			'null'	=>	true,	'default'	=>	null,			'reset-format'	=>	true, ],
			'unlink-border-color'				=>	['type'	=>	'color',		'null'	=>	true,	'default'	=>	'#dd3333',		'reset-format'	=>	true, ],
			'sns-position'						=>	['type'	=>	'numeric',		'null'	=>	true,	'default'	=>	2,				'reset-format'	=>	true, ],
			'sns-tw'							=>	['type'	=>	'flag',			'null'	=>	true,	'default'	=>	1,				'reset-format'	=>	true, ],
			'sns-tw-x'							=>	['type'	=>	'flag',			'null'	=>	true,	'default'	=>	null,			'reset-format'	=>	true, ],
			'sns-fb'							=>	['type'	=>	'flag',			'null'	=>	true,	'default'	=>	1,				'reset-format'	=>	true, ],
			'sns-hb'							=>	['type'	=>	'flag',			'null'	=>	true,	'default'	=>	1,				'reset-format'	=>	true, ],

			'title-color'						=>	['type'	=>	'color',		'null'	=>	true,	'default'	=>	'#111111',		'reset-format'	=>	true, ],
			'title-outline-color'				=>	['type'	=>	'color',		'null'	=>	true,	'default'	=>	null,			'reset-format'	=>	true, ],
			'title-bg-color'					=>	['type'	=>	'color',		'null'	=>	true,	'default'	=>	null,			'reset-format'	=>	true, ],
			'title-size'						=>	['type'	=>	'numeric',		'null'	=>	true,	'default'	=>	18,				'reset-format'	=>	true, ],
			'title-height'						=>	['type'	=>	'numeric',		'null'	=>	true,	'default'	=>	23,				'reset-format'	=>	true, ],
			'title-maxline'						=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	2,				'reset-format'	=>	true, ],
			'title-bold'						=>	['type'	=>	'flag',			'null'	=>	true,	'default'	=>	1,				'reset-format'	=>	true, ],
			'title-italic'						=>	['type'	=>	'flag',			'null'	=>	true,	'default'	=>	null,			'reset-format'	=>	true, ],
			'title-underline'					=>	['type'	=>	'flag',			'null'	=>	true,	'default'	=>	null,			'reset-format'	=>	true, ],
			'title-hover'						=>	['type'	=>	'flag',			'null'	=>	true,	'default'	=>	1,				'reset-format'	=>	true, ],

			'excerpt-color'						=>	['type'	=>	'color',		'null'	=>	true,	'default'	=>	'#444444',		'reset-format'	=>	true, ],
			'excerpt-outline-color'				=>	['type'	=>	'color',		'null'	=>	true,	'default'	=>	null,			'reset-format'	=>	true, ],
			'excerpt-bg-color'					=>	['type'	=>	'color',		'null'	=>	true,	'default'	=>	null,			'reset-format'	=>	true, ],
			'excerpt-size'						=>	['type'	=>	'numeric',		'null'	=>	true,	'default'	=>	11,				'reset-format'	=>	true, ],
			'excerpt-height'					=>	['type'	=>	'numeric',		'null'	=>	true,	'default'	=>	18,				'reset-format'	=>	true, ],
			'excerpt-maxline'					=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	3,				'reset-format'	=>	true, ],
			'excerpt-bold'						=>	['type'	=>	'flag',			'null'	=>	true,	'default'	=>	null,			'reset-format'	=>	true, ],
			'excerpt-italic'					=>	['type'	=>	'flag',			'null'	=>	true,	'default'	=>	null,			'reset-format'	=>	true, ],
			'excerpt-underline'					=>	['type'	=>	'flag',			'null'	=>	true,	'default'	=>	null,			'reset-format'	=>	true, ],
			'excerpt-hover'						=>	['type'	=>	'flag',			'null'	=>	true,	'default'	=>	null,			'reset-format'	=>	true, ],

			'url-color'							=>	['type'	=>	'color',		'null'	=>	true,	'default'	=>	'#4466ff',		'reset-format'	=>	true, ],
			'url-outline-color'					=>	['type'	=>	'color',		'null'	=>	true,	'default'	=>	null,			'reset-format'	=>	true, ],
			'url-bg-color'						=>	['type'	=>	'color',		'null'	=>	true,	'default'	=>	null,			'reset-format'	=>	true, ],
			'url-size'							=>	['type'	=>	'numeric',		'null'	=>	true,	'default'	=>	12,				'reset-format'	=>	true, ],
			'url-height'						=>	['type'	=>	'numeric',		'null'	=>	true,	'default'	=>	17,				'reset-format'	=>	true, ],
			'url-bold'							=>	['type'	=>	'flag',			'null'	=>	true,	'default'	=>	null,			'reset-format'	=>	true, ],
			'url-italic'						=>	['type'	=>	'flag',			'null'	=>	true,	'default'	=>	null,			'reset-format'	=>	true, ],
			'url-underline'						=>	['type'	=>	'flag',			'null'	=>	true,	'default'	=>	1,				'reset-format'	=>	true, ],
			'url-hover'							=>	['type'	=>	'flag',			'null'	=>	true,	'default'	=>	1,				'reset-format'	=>	true, ],

			'date-color'						=>	['type'	=>	'color',		'null'	=>	true,	'default'	=>	'#444444',		'reset-format'	=>	true, ],
			'date-outline-color'				=>	['type'	=>	'color',		'null'	=>	true,	'default'	=>	null,			'reset-format'	=>	true, ],
			'date-bg-color'						=>	['type'	=>	'color',		'null'	=>	true,	'default'	=>	null,			'reset-format'	=>	true, ],
			'date-size'							=>	['type'	=>	'numeric',		'null'	=>	true,	'default'	=>	11,				'reset-format'	=>	true, ],
			'date-height'						=>	['type'	=>	'numeric',		'null'	=>	true,	'default'	=>	16,				'reset-format'	=>	true, ],
			'date-bold'							=>	['type'	=>	'flag',			'null'	=>	true,	'default'	=>	null,			'reset-format'	=>	true, ],
			'date-italic'						=>	['type'	=>	'flag',			'null'	=>	true,	'default'	=>	null,			'reset-format'	=>	true, ],
			'date-underline'					=>	['type'	=>	'flag',			'null'	=>	true,	'default'	=>	null,			'reset-format'	=>	true, ],
			'date-hover'						=>	['type'	=>	'flag',			'null'	=>	true,	'default'	=>	null,			'reset-format'	=>	true, ],

			'heading-color'						=>	['type'	=>	'color',		'null'	=>	true,	'default'	=>	'#ffffff',		'reset-format'	=>	true, ],
			'heading-outline-color'				=>	['type'	=>	'color',		'null'	=>	true,	'default'	=>	null,			'reset-format'	=>	true, ],
//			'heading-bg-color'					=>	['type'	=>	'color',		'null'	=>	true,	'default'	=>	null, ],
			'heading-size'						=>	['type'	=>	'numeric',		'null'	=>	true,	'default'	=>	12,				'reset-format'	=>	true, ],
			'heading-height'					=>	['type'	=>	'numeric',		'null'	=>	true,	'default'	=>	16,				'reset-format'	=>	true, ],
			'heading-bold'						=>	['type'	=>	'flag',			'null'	=>	true,	'default'	=>	null,			'reset-format'	=>	true, ],
			'heading-italic'					=>	['type'	=>	'flag',			'null'	=>	true,	'default'	=>	null,			'reset-format'	=>	true, ],
			'heading-underline'					=>	['type'	=>	'flag',			'null'	=>	true,	'default'	=>	null,			'reset-format'	=>	true, ],
			'heading-hover'						=>	['type'	=>	'flag',			'null'	=>	true,	'default'	=>	null,			'reset-format'	=>	true, ],

			'more-color'						=>	['type'	=>	'color',		'null'	=>	true,	'default'	=>	'#ffffff',		'reset-format'	=>	true, ],
			'more-outline-color'				=>	['type'	=>	'color',		'null'	=>	true,	'default'	=>	null,			'reset-format'	=>	true, ],
//			'more-bg-color'						=>	['type'	=>	'color',		'null'	=>	true,	'default'	=>	null, ],
			'more-size'							=>	['type'	=>	'numeric',		'null'	=>	true,	'default'	=>	12,				'reset-format'	=>	true, ],
			'more-height'						=>	['type'	=>	'numeric',		'null'	=>	true,	'default'	=>	24,				'reset-format'	=>	true, ],
			'more-bold'							=>	['type'	=>	'flag',			'null'	=>	true,	'default'	=>	null,			'reset-format'	=>	true, ],
			'more-italic'						=>	['type'	=>	'flag',			'null'	=>	true,	'default'	=>	null,			'reset-format'	=>	true, ],
			'more-underline'					=>	['type'	=>	'flag',			'null'	=>	true,	'default'	=>	null,			'reset-format'	=>	true, ],
			'more-hover'						=>	['type'	=>	'flag',			'null'	=>	true,	'default'	=>	null,			'reset-format'	=>	true, ],

			'info-color'						=>	['type'	=>	'color',		'null'	=>	true,	'default'	=>	'#222222',		'reset-format'	=>	true, ],
			'info-outline-color'				=>	['type'	=>	'color',		'null'	=>	true,	'default'	=>	null,			'reset-format'	=>	true, ],
			'info-bg-color'						=>	['type'	=>	'color',		'null'	=>	true,	'default'	=>	null,			'reset-format'	=>	true, ],
			'info-size'							=>	['type'	=>	'numeric',		'null'	=>	true,	'default'	=>	12,				'reset-format'	=>	true, ],
			'info-height'						=>	['type'	=>	'numeric',		'null'	=>	true,	'default'	=>	16,				'reset-format'	=>	true, ],
			'info-bold'							=>	['type'	=>	'flag',			'null'	=>	true,	'default'	=>	null,			'reset-format'	=>	true, ],
			'info-italic'						=>	['type'	=>	'flag',			'null'	=>	true,	'default'	=>	null,			'reset-format'	=>	true, ],
			'info-underline'					=>	['type'	=>	'flag',			'null'	=>	true,	'default'	=>	null,			'reset-format'	=>	true, ],
			'info-hover'						=>	['type'	=>	'flag',			'null'	=>	true,	'default'	=>	null,			'reset-format'	=>	true, ],

			'added-color'						=>	['type'	=>	'color',		'null'	=>	true,	'default'	=>	'#ffffff',		'reset-format'	=>	true, ],
			'added-outline-color'				=>	['type'	=>	'color',		'null'	=>	true,	'default'	=>	null,			'reset-format'	=>	true, ],
			'added-bg-color'					=>	['type'	=>	'color',		'null'	=>	true,	'default'	=>	'#365cd9',		'reset-format'	=>	true, ],
			'added-size'						=>	['type'	=>	'numeric',		'null'	=>	true,	'default'	=>	9,				'reset-format'	=>	true, ],
			'added-height'						=>	['type'	=>	'numeric',		'null'	=>	true,	'default'	=>	10,				'reset-format'	=>	true, ],
			'added-bold'						=>	['type'	=>	'flag',			'null'	=>	true,	'default'	=>	null,			'reset-format'	=>	true, ],
			'added-italic'						=>	['type'	=>	'flag',			'null'	=>	true,	'default'	=>	null,			'reset-format'	=>	true, ],
			'added-underline'					=>	['type'	=>	'flag',			'null'	=>	true,	'default'	=>	null,			'reset-format'	=>	true, ],
			'added-hover'						=>	['type'	=>	'flag',			'null'	=>	true,	'default'	=>	null,			'reset-format'	=>	true, ],

			'cat-color'							=>	['type'	=>	'color',		'null'	=>	true,	'default'	=>	'#ffffff',		'reset-format'	=>	true, ],
			'cat-outline-color'					=>	['type'	=>	'color',		'null'	=>	true,	'default'	=>	null,			'reset-format'	=>	true, ],
			'cat-bg-color'						=>	['type'	=>	'color',		'null'	=>	true,	'default'	=>	'#ffcc88',		'reset-format'	=>	true, ],
			'cat-size'							=>	['type'	=>	'numeric',		'null'	=>	true,	'default'	=>	12,				'reset-format'	=>	true, ],
			'cat-height'						=>	['type'	=>	'numeric',		'null'	=>	true,	'default'	=>	24,				'reset-format'	=>	true, ],
			'cat-bold'							=>	['type'	=>	'flag',			'null'	=>	true,	'default'	=>	null,			'reset-format'	=>	true, ],
			'cat-italic'						=>	['type'	=>	'flag',			'null'	=>	true,	'default'	=>	null,			'reset-format'	=>	true, ],
			'cat-underline'						=>	['type'	=>	'flag',			'null'	=>	true,	'default'	=>	null,			'reset-format'	=>	true, ],
			'cat-hover'							=>	['type'	=>	'flag',			'null'	=>	true,	'default'	=>	null,			'reset-format'	=>	true, ],

			'ex-target'							=>	['type'	=>	'numeric',		'null'	=>	true,	'default'	=>	2,				'reset-format'	=>	true, ],
			'ex-transform-enabled'				=>	['type'	=>	'flag',			'null'	=>	true,	'default'	=>	null,			'reset-format'	=>	true, ],
			'ex-transform-x'					=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	0,				'reset-format'	=>	true, ],
			'ex-transform-y'					=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	0,				'reset-format'	=>	true, ],
			'ex-transform-rotate'				=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	0,				'reset-format'	=>	true, ],
			'ex-transform-scale'				=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	100,			'reset-format'	=>	true, ],
			'ex-opacity'						=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	100,			'reset-format'	=>	true, ],
			'ex-bg-enabled'						=>	['type'	=>	'flag',			'null'	=>	true,	'default'	=>	1,				'reset-format'	=>	true, ],
			'ex-bg-color'						=>	['type'	=>	'color',		'null'	=>	true,	'default'	=>	'#ddeeff',		'reset-format'	=>	true, ],
			'ex-bg-image'						=>	['type'	=>	'string',		'null'	=>	true,	'default'	=>	null,			'reset-format'	=>	true, ],
			'ex-border-enabled'					=>	['type'	=>	'flag',			'null'	=>	true,	'default'	=>	1,				'reset-format'	=>	true, ],
			'ex-border-color'					=>	['type'	=>	'color',		'null'	=>	true,	'default'	=>	'#114488',		'reset-format'	=>	true, ],
			'ex-border-style'					=>	['type'	=>	'border',		'null'	=>	true,	'default'	=>	'solid',		'reset-format'	=>	true, ],
			'ex-border-width'					=>	['type'	=>	'numeric',		'null'	=>	true,	'default'	=>	2,				'reset-format'	=>	true, ],
			'ex-border-radius'					=>	['type'	=>	'numeric',		'null'	=>	true,	'default'	=>	4,				'reset-format'	=>	true, ],
			'ex-shadow-enabled'					=>	['type'	=>	'flag',			'null'	=>	true,	'default'	=>	1,				'reset-format'	=>	true, ],
			'ex-shadow-color'					=>	['type'	=>	'color',		'null'	=>	true,	'default'	=>	'#00336644',	'reset-format'	=>	true, ],
			'ex-shadow-x'						=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	8,				'reset-format'	=>	true, ],
			'ex-shadow-y'						=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	8,				'reset-format'	=>	true, ],
			'ex-shadow-blur'					=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	8,				'reset-format'	=>	true, ],
			'ex-shadow-spread'					=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	0,				'reset-format'	=>	true, ],
			'ex-shadow-inset'					=>	['type'	=>	'flag',			'null'	=>	true,	'default'	=>	null,			'reset-format'	=>	true, ],
			'ex-transition'						=>	['type'	=>	'float',		'null'	=>	true,	'default'	=>	0.5,			'reset-format'	=>	true, ],
			'ex-hover-transform-enabled'		=>	['type'	=>	'flag',			'null'	=>	true,	'default'	=>	1,				'reset-format'	=>	true, ],
			'ex-hover-transform-x'				=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	-4,				'reset-format'	=>	true, ],
			'ex-hover-transform-y'				=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	-4,				'reset-format'	=>	true, ],
			'ex-hover-transform-rotate'			=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	0,				'reset-format'	=>	true, ],
			'ex-hover-transform-scale'			=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	100,			'reset-format'	=>	true, ],
			'ex-hover-opacity'					=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	100,			'reset-format'	=>	true, ],
			'ex-hover-bg-enabled'				=>	['type'	=>	'flag',			'null'	=>	true,	'default'	=>	1,				'reset-format'	=>	true, ],
			'ex-hover-bg-color'					=>	['type'	=>	'color',		'null'	=>	true,	'default'	=>	'#ddeeff88',	'reset-format'	=>	true, ],
			'ex-hover-bg-image'					=>	['type'	=>	'string',		'null'	=>	true,	'default'	=>	null,			'reset-format'	=>	true, ],
			'ex-hover-border-enabled'			=>	['type'	=>	'flag',			'null'	=>	true,	'default'	=>	null,			'reset-format'	=>	true, ],
			'ex-hover-border-color'				=>	['type'	=>	'color',		'null'	=>	true,	'default'	=>	'#114488',		'reset-format'	=>	true, ],
			'ex-hover-border-style'				=>	['type'	=>	'border',		'null'	=>	true,	'default'	=>	'solid',		'reset-format'	=>	true, ],
			'ex-hover-border-width'				=>	['type'	=>	'numeric',		'null'	=>	true,	'default'	=>	2,				'reset-format'	=>	true, ],
			'ex-hover-border-radius'			=>	['type'	=>	'numeric',		'null'	=>	true,	'default'	=>	4,				'reset-format'	=>	true, ],
			'ex-hover-shadow-enabled'			=>	['type'	=>	'flag',			'null'	=>	true,	'default'	=>	1,				'reset-format'	=>	true, ],
			'ex-hover-shadow-color'				=>	['type'	=>	'color',		'null'	=>	true,	'default'	=>	'#00336644',	'reset-format'	=>	true, ],
			'ex-hover-shadow-x'					=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	12,				'reset-format'	=>	true, ],
			'ex-hover-shadow-y'					=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	12,				'reset-format'	=>	true, ],
			'ex-hover-shadow-blur'				=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	8,				'reset-format'	=>	true, ],
			'ex-hover-shadow-spread'			=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	0,				'reset-format'	=>	true, ],
			'ex-hover-shadow-inset'				=>	['type'	=>	'flag',			'null'	=>	true,	'default'	=>	null,			'reset-format'	=>	true, ],
			'ex-hover-transition'				=>	['type'	=>	'float',		'null'	=>	true,	'default'	=>	0.2,			'reset-format'	=>	true, ],
			'ex-get-from'						=>	['type'	=>	'string',		'null'	=>	true,	'default'	=>	2,				'reset-format'	=>	true, ],
			'ex-heading-text'					=>	['type'	=>	'string',		'null'	=>	true,	'default'	=>	null,			'reset-format'	=>	true, ],
			'ex-heading-transform-enabled'		=>	['type'	=>	'flag',			'null'	=>	true,	'default'	=>	null,			'reset-format'	=>	true, ],
			'ex-heading-transform-x'			=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	0,				'reset-format'	=>	true, ],
			'ex-heading-transform-y'			=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	0,				'reset-format'	=>	true, ],
			'ex-heading-transform-rotate'		=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	0,				'reset-format'	=>	true, ],
			'ex-heading-transform-scale'		=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	100,			'reset-format'	=>	true, ],
			'ex-heading-bg-enabled'				=>	['type'	=>	'flag',			'null'	=>	true,	'default'	=>	1,				'reset-format'	=>	true, ],
			'ex-heading-bg-color'				=>	['type'	=>	'color',		'null'	=>	true,	'default'	=>	'#003366',		'reset-format'	=>	true, ],
			'ex-heading-border-enabled'			=>	['type'	=>	'flag',			'null'	=>	true,	'default'	=>	null,			'reset-format'	=>	true, ],
			'ex-heading-border-color'			=>	['type'	=>	'color',		'null'	=>	true,	'default'	=>	'#003366',		'reset-format'	=>	true, ],
			'ex-heading-border-style'			=>	['type'	=>	'border',		'null'	=>	true,	'default'	=>	'solid',		'reset-format'	=>	true, ],
			'ex-heading-border-width'			=>	['type'	=>	'numeric',		'null'	=>	true,	'default'	=>	1,				'reset-format'	=>	true, ],
			'ex-heading-border-radius'			=>	['type'	=>	'numeric',		'null'	=>	true,	'default'	=>	4,				'reset-format'	=>	true, ],
			'ex-heading-shadow-enabled'			=>	['type'	=>	'flag',			'null'	=>	true,	'default'	=>	null,			'reset-format'	=>	true, ],
			'ex-heading-shadow-color'			=>	['type'	=>	'color',		'null'	=>	true,	'default'	=>	'#00336688',	'reset-format'	=>	true, ],
			'ex-heading-shadow-x'				=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	8,				'reset-format'	=>	true, ],
			'ex-heading-shadow-y'				=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	8,				'reset-format'	=>	true, ],
			'ex-heading-shadow-blur'			=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	8,				'reset-format'	=>	true, ],
			'ex-heading-shadow-spread'			=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	0,				'reset-format'	=>	true, ],
			'ex-heading-shadow-inset'			=>	['type'	=>	'flag',			'null'	=>	true,	'default'	=>	null,			'reset-format'	=>	true, ],
			'ex-more-text'						=>	['type'	=>	'string',		'null'	=>	true,	'default'	=>	null,			'reset-format'	=>	true, ],
			'ex-more-transform-enabled'			=>	['type'	=>	'flag',			'null'	=>	true,	'default'	=>	null,			'reset-format'	=>	true, ],
			'ex-more-transform-x'				=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	0,				'reset-format'	=>	true, ],
			'ex-more-transform-y'				=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	0,				'reset-format'	=>	true, ],
			'ex-more-transform-rotate'			=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	0,				'reset-format'	=>	true, ],
			'ex-more-transform-scale'			=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	100,			'reset-format'	=>	true, ],
			'ex-more-bg-enabled'				=>	['type'	=>	'flag',			'null'	=>	true,	'default'	=>	1,				'reset-format'	=>	true, ],
			'ex-more-bg-color'					=>	['type'	=>	'color',		'null'	=>	true,	'default'	=>	'#446688',		'reset-format'	=>	true, ],
			'ex-more-border-enabled'			=>	['type'	=>	'flag',			'null'	=>	true,	'default'	=>	1,				'reset-format'	=>	true, ],
			'ex-more-border-color'				=>	['type'	=>	'color',		'null'	=>	true,	'default'	=>	'#446688',		'reset-format'	=>	true, ],
			'ex-more-border-style'				=>	['type'	=>	'border',		'null'	=>	true,	'default'	=>	'solid',		'reset-format'	=>	true, ],
			'ex-more-border-width'				=>	['type'	=>	'numeric',		'null'	=>	true,	'default'	=>	1,				'reset-format'	=>	true, ],
			'ex-more-border-radius'				=>	['type'	=>	'numeric',		'null'	=>	true,	'default'	=>	4,				'reset-format'	=>	true, ],
			'ex-more-shadow-enabled'			=>	['type'	=>	'flag',			'null'	=>	true,	'default'	=>	null,			'reset-format'	=>	true, ],
			'ex-more-shadow-color'				=>	['type'	=>	'color',		'null'	=>	true,	'default'	=>	'#44668888',	'reset-format'	=>	true, ],
			'ex-more-shadow-x'					=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	4,				'reset-format'	=>	true, ],
			'ex-more-shadow-y'					=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	4,				'reset-format'	=>	true, ],
			'ex-more-shadow-blur'				=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	8,				'reset-format'	=>	true, ],
			'ex-more-shadow-spread'				=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	0,				'reset-format'	=>	true, ],
			'ex-more-shadow-inset'				=>	['type'	=>	'flag',			'null'	=>	true,	'default'	=>	null,			'reset-format'	=>	true, ],
			'ex-added-text'						=>	['type'	=>	'string',		'null'	=>	true,	'default'	=>	null,			'reset-format'	=>	true, ],
			'ex-siteicon'						=>	['type'	=>	'string',		'null'	=>	true,	'default'	=>	3,				'reset-format'	=>	true, ],
			'ex-siteicon-alt'					=>	['type'	=>	'string',		'null'	=>	true,	'default'	=>	null,			'reset-format'	=>	true, ],
			'ex-thumbnail'						=>	['type'	=>	'string',		'null'	=>	true,	'default'	=>	13,				'reset-format'	=>	true, ],
			'ex-thumbnail-size'					=>	['type'	=>	'string',		'null'	=>	true,	'default'	=>	'thumbnail',	'reset-format'	=>	true, ],
			'ex-thumbnail-alt'					=>	['type'	=>	'string',		'null'	=>	true,	'default'	=>	null,			'reset-format'	=>	true, ],
			'ex-thumbnail-transform-enabled' 	=>	['type'	=>	'flag',			'null'	=>	true,	'default'	=>	null,			'reset-format'	=>	true, ],
			'ex-thumbnail-transform-x'			=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	0,				'reset-format'	=>	true, ],
			'ex-thumbnail-transform-y'			=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	0,				'reset-format'	=>	true, ],
			'ex-thumbnail-transform-rotate'		=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	0,				'reset-format'	=>	true, ],
			'ex-thumbnail-transform-scale'		=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	100,			'reset-format'	=>	true, ],
			'ex-thumbnail-bg-enabled'			=>	['type'	=>	'flag',			'null'	=>	true,	'default'	=>	1,				'reset-format'	=>	true, ],
			'ex-thumbnail-bg-color'				=>	['type'	=>	'color',		'null'	=>	true,	'default'	=>	'#ffffff',		'reset-format'	=>	true, ],
			'ex-thumbnail-border-enabled'		=>	['type'	=>	'flag',			'null'	=>	true,	'default'	=>	1,				'reset-format'	=>	true, ],
			'ex-thumbnail-border-color'			=>	['type'	=>	'color',		'null'	=>	true,	'default'	=>	'#888888',		'reset-format'	=>	true, ],
			'ex-thumbnail-border-style'			=>	['type'	=>	'border',		'null'	=>	true,	'default'	=>	'solid',		'reset-format'	=>	true, ],
			'ex-thumbnail-border-width'			=>	['type'	=>	'numeric',		'null'	=>	true,	'default'	=>	1,				'reset-format'	=>	true, ],
			'ex-thumbnail-border-radius'		=>	['type'	=>	'numeric',		'null'	=>	true,	'default'	=>	4,				'reset-format'	=>	true, ],
			'ex-thumbnail-shadow-enabled'		=>	['type'	=>	'flag',			'null'	=>	true,	'default'	=>	null,			'reset-format'	=>	true, ],
			'ex-thumbnail-shadow-color'			=>	['type'	=>	'color',		'null'	=>	true,	'default'	=>	'#aaaacc',		'reset-format'	=>	true, ],
			'ex-thumbnail-shadow-x'				=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	8,				'reset-format'	=>	true, ],
			'ex-thumbnail-shadow-y'				=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	8,				'reset-format'	=>	true, ],
			'ex-thumbnail-shadow-blur'			=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	8,				'reset-format'	=>	true, ],
			'ex-thumbnail-shadow-spread'		=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	0,				'reset-format'	=>	true, ],
			'ex-thumbnail-shadow-inset'			=>	['type'	=>	'flag',			'null'	=>	true,	'default'	=>	null,			'reset-format'	=>	true, ],

			'in-target'							=>	['type'	=>	'numeric',		'null'	=>	true,	'default'	=>	null,			'reset-format'	=>	true, ],
			'in-transform-enabled'				=>	['type'	=>	'flag',			'null'	=>	true,	'default'	=>	null,			'reset-format'	=>	true, ],
			'in-transform-x'					=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	0,				'reset-format'	=>	true, ],
			'in-transform-y'					=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	0,				'reset-format'	=>	true, ],
			'in-transform-rotate'				=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	0,				'reset-format'	=>	true, ],
			'in-transform-scale'				=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	100,			'reset-format'	=>	true, ],
			'in-opacity'						=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	100,			'reset-format'	=>	true, ],
			'in-bg-enabled'						=>	['type'	=>	'flag',			'null'	=>	true,	'default'	=>	1,				'reset-format'	=>	true, ],
			'in-bg-color'						=>	['type'	=>	'color',		'null'	=>	true,	'default'	=>	'#fffaf0',		'reset-format'	=>	true, ],
			'in-bg-image'						=>	['type'	=>	'string',		'null'	=>	true,	'default'	=>	null,			'reset-format'	=>	true, ],
			'in-border-enabled'					=>	['type'	=>	'flag',			'null'	=>	true,	'default'	=>	1,				'reset-format'	=>	true, ],
			'in-border-color'					=>	['type'	=>	'color',		'null'	=>	true,	'default'	=>	'#998888',		'reset-format'	=>	true, ],
			'in-border-style'					=>	['type'	=>	'border',		'null'	=>	true,	'default'	=>	'solid',		'reset-format'	=>	true, ],
			'in-border-width'					=>	['type'	=>	'numeric',		'null'	=>	true,	'default'	=>	2,				'reset-format'	=>	true, ],
			'in-border-radius'					=>	['type'	=>	'numeric',		'null'	=>	true,	'default'	=>	4,				'reset-format'	=>	true, ],
			'in-shadow-enabled'					=>	['type'	=>	'flag',			'null'	=>	true,	'default'	=>	1,				'reset-format'	=>	true, ],
			'in-shadow-color'					=>	['type'	=>	'color',		'null'	=>	true,	'default'	=>	'#66330044',	'reset-format'	=>	true, ],
			'in-shadow-x'						=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	8,				'reset-format'	=>	true, ],
			'in-shadow-y'						=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	8,				'reset-format'	=>	true, ],
			'in-shadow-blur'					=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	8,				'reset-format'	=>	true, ],
			'in-shadow-spread'					=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	0,				'reset-format'	=>	true, ],
			'in-shadow-inset'					=>	['type'	=>	'flag',			'null'	=>	true,	'default'	=>	null,			'reset-format'	=>	true, ],
			'in-transition'						=>	['type'	=>	'float',		'null'	=>	true,	'default'	=>	0.5,			'reset-format'	=>	true, ],
			'in-hover-transform-enabled'		=>	['type'	=>	'flag',			'null'	=>	true,	'default'	=>	1,				'reset-format'	=>	true, ],
			'in-hover-transform-x'				=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	-4,				'reset-format'	=>	true, ],
			'in-hover-transform-y'				=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	-4,				'reset-format'	=>	true, ],
			'in-hover-transform-rotate'			=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	0,				'reset-format'	=>	true, ],
			'in-hover-transform-scale'			=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	100,			'reset-format'	=>	true, ],
			'in-hover-opacity'					=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	100,			'reset-format'	=>	true, ],
			'in-hover-bg-enabled'				=>	['type'	=>	'flag',			'null'	=>	true,	'default'	=>	1,				'reset-format'	=>	true, ],
			'in-hover-bg-color'					=>	['type'	=>	'color',		'null'	=>	true,	'default'	=>	'#fffaf088',	'reset-format'	=>	true, ],
			'in-hover-bg-image'					=>	['type'	=>	'string',		'null'	=>	true,	'default'	=>	null,			'reset-format'	=>	true, ],
			'in-hover-border-enabled'			=>	['type'	=>	'flag',			'null'	=>	true,	'default'	=>	null,			'reset-format'	=>	true, ],
			'in-hover-border-color'				=>	['type'	=>	'color',		'null'	=>	true,	'default'	=>	'#998888',		'reset-format'	=>	true, ],
			'in-hover-border-style'				=>	['type'	=>	'border',		'null'	=>	true,	'default'	=>	'solid',		'reset-format'	=>	true, ],
			'in-hover-border-width'				=>	['type'	=>	'numeric',		'null'	=>	true,	'default'	=>	2,				'reset-format'	=>	true, ],
			'in-hover-border-radius'			=>	['type'	=>	'numeric',		'null'	=>	true,	'default'	=>	4,				'reset-format'	=>	true, ],
			'in-hover-shadow-enabled'			=>	['type'	=>	'flag',			'null'	=>	true,	'default'	=>	1,				'reset-format'	=>	true, ],
			'in-hover-shadow-color'				=>	['type'	=>	'color',		'null'	=>	true,	'default'	=>	'#66330044',	'reset-format'	=>	true, ],
			'in-hover-shadow-x'					=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	12,				'reset-format'	=>	true, ],
			'in-hover-shadow-y'					=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	12,				'reset-format'	=>	true, ],
			'in-hover-shadow-blur'				=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	8,				'reset-format'	=>	true, ],
			'in-hover-shadow-spread'			=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	0,				'reset-format'	=>	true, ],
			'in-hover-shadow-inset'				=>	['type'	=>	'flag',			'null'	=>	true,	'default'	=>	null,			'reset-format'	=>	true, ],
			'in-hover-transition'				=>	['type'	=>	'float',		'null'	=>	true,	'default'	=>	0.2,			'reset-format'	=>	true, ],
			'in-get-from'						=>	['type'	=>	'string',		'null'	=>	true,	'default'	=>	null,			'reset-format'	=>	true, ],
			'in-field-title'					=>	['type'	=>	'string',		'null'	=>	true,	'default'	=>	null,			'reset-format'	=>	true, ],
			'in-field-excerpt'					=>	['type'	=>	'string',		'null'	=>	true,	'default'	=>	null,			'reset-format'	=>	true, ],
			'in-get-url'						=>	['type'	=>	'string',		'null'	=>	true,	'default'	=>	0,				'reset-format'	=>	true, ],
			'in-heading-text'					=>	['type'	=>	'string',		'null'	=>	true,	'default'	=>	null,			'reset-format'	=>	true, ],
			'in-heading-transform-enabled'		=>	['type'	=>	'flag',			'null'	=>	true,	'default'	=>	null,			'reset-format'	=>	true, ],
			'in-heading-transform-x'			=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	0,				'reset-format'	=>	true, ],
			'in-heading-transform-y'			=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	0,				'reset-format'	=>	true, ],
			'in-heading-transform-rotate'		=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	0,				'reset-format'	=>	true, ],
			'in-heading-transform-scale'		=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	100,			'reset-format'	=>	true, ],
			'in-heading-bg-enabled'				=>	['type'	=>	'flag',			'null'	=>	true,	'default'	=>	1,				'reset-format'	=>	true, ],
			'in-heading-bg-color'				=>	['type'	=>	'color',		'null'	=>	true,	'default'	=>	'#998888',		'reset-format'	=>	true, ],
			'in-heading-border-enabled'			=>	['type'	=>	'flag',			'null'	=>	true,	'default'	=>	null,			'reset-format'	=>	true, ],
			'in-heading-border-color'			=>	['type'	=>	'color',		'null'	=>	true,	'default'	=>	'#998888',		'reset-format'	=>	true, ],
			'in-heading-border-style'			=>	['type'	=>	'border',		'null'	=>	true,	'default'	=>	'solid',		'reset-format'	=>	true, ],
			'in-heading-border-width'			=>	['type'	=>	'numeric',		'null'	=>	true,	'default'	=>	1,				'reset-format'	=>	true, ],
			'in-heading-border-radius'			=>	['type'	=>	'numeric',		'null'	=>	true,	'default'	=>	4,				'reset-format'	=>	true, ],
			'in-heading-shadow-enabled'			=>	['type'	=>	'flag',			'null'	=>	true,	'default'	=>	null,			'reset-format'	=>	true, ],
			'in-heading-shadow-color'			=>	['type'	=>	'color',		'null'	=>	true,	'default'	=>	'#aaaacc',		'reset-format'	=>	true, ],
			'in-heading-shadow-x'				=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	8,				'reset-format'	=>	true, ],
			'in-heading-shadow-y'				=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	8,				'reset-format'	=>	true, ],
			'in-heading-shadow-blur'			=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	8,				'reset-format'	=>	true, ],
			'in-heading-shadow-spread'			=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	0,				'reset-format'	=>	true, ],
			'in-heading-shadow-inset'			=>	['type'	=>	'flag',			'null'	=>	true,	'default'	=>	null,			'reset-format'	=>	true, ],
			'in-more-text'						=>	['type'	=>	'string',		'null'	=>	true,	'default'	=>	null,			'reset-format'	=>	true, ],
			'in-more-transform-enabled'			=>	['type'	=>	'flag',			'null'	=>	true,	'default'	=>	null,			'reset-format'	=>	true, ],
			'in-more-transform-x'				=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	0,				'reset-format'	=>	true, ],
			'in-more-transform-y'				=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	0,				'reset-format'	=>	true, ],
			'in-more-transform-rotate'			=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	0,				'reset-format'	=>	true, ],
			'in-more-transform-scale'			=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	100,			'reset-format'	=>	true, ],
			'in-more-bg-enabled'				=>	['type'	=>	'flag',			'null'	=>	true,	'default'	=>	1,				'reset-format'	=>	true, ],
			'in-more-bg-color'					=>	['type'	=>	'color',		'null'	=>	true,	'default'	=>	'#8c8c70',		'reset-format'	=>	true, ],
			'in-more-border-enabled'			=>	['type'	=>	'flag',			'null'	=>	true,	'default'	=>	1,				'reset-format'	=>	true, ],
			'in-more-border-color'				=>	['type'	=>	'color',		'null'	=>	true,	'default'	=>	'#8c8c70',		'reset-format'	=>	true, ],
			'in-more-border-style'				=>	['type'	=>	'border',		'null'	=>	true,	'default'	=>	'solid',		'reset-format'	=>	true, ],
			'in-more-border-width'				=>	['type'	=>	'numeric',		'null'	=>	true,	'default'	=>	1,				'reset-format'	=>	true, ],
			'in-more-border-radius'				=>	['type'	=>	'numeric',		'null'	=>	true,	'default'	=>	4,				'reset-format'	=>	true, ],
			'in-more-shadow-enabled'			=>	['type'	=>	'flag',			'null'	=>	true,	'default'	=>	null,			'reset-format'	=>	true, ],
			'in-more-shadow-color'				=>	['type'	=>	'color',		'null'	=>	true,	'default'	=>	'#66330088',	'reset-format'	=>	true, ],
			'in-more-shadow-x'					=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	4,				'reset-format'	=>	true, ],
			'in-more-shadow-y'					=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	4,				'reset-format'	=>	true, ],
			'in-more-shadow-blur'				=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	8,				'reset-format'	=>	true, ],
			'in-more-shadow-spread'				=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	0,				'reset-format'	=>	true, ],
			'in-more-shadow-inset'				=>	['type'	=>	'flag',			'null'	=>	true,	'default'	=>	null,			'reset-format'	=>	true, ],
			'in-added-text'						=>	['type'	=>	'string',		'null'	=>	true,	'default'	=>	null,			'reset-format'	=>	true, ],
			'in-siteicon'						=>	['type'	=>	'string',		'null'	=>	true,	'default'	=>	3,				'reset-format'	=>	true, ],
			'in-siteicon-alt'					=>	['type'	=>	'string',		'null'	=>	true,	'default'	=>	null,			'reset-format'	=>	true, ],
			'in-thumbnail'						=>	['type'	=>	'string',		'null'	=>	true,	'default'	=>	1,				'reset-format'	=>	true, ],
			'in-thumbnail-size'					=>	['type'	=>	'string',		'null'	=>	true,	'default'	=>	'thumbnail',	'reset-format'	=>	true, ],
			'in-thumbnail-alt'					=>	['type'	=>	'string',		'null'	=>	true,	'default'	=>	null,			'reset-format'	=>	true, ],
			'in-thumbnail-transform-enabled' 	=>	['type'	=>	'flag',			'null'	=>	true,	'default'	=>	null,			'reset-format'	=>	true, ],
			'in-thumbnail-transform-x'			=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	0,				'reset-format'	=>	true, ],
			'in-thumbnail-transform-y'			=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	0,				'reset-format'	=>	true, ],
			'in-thumbnail-transform-rotate'		=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	0,				'reset-format'	=>	true, ],
			'in-thumbnail-transform-scale'		=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	100,			'reset-format'	=>	true, ],
			'in-thumbnail-bg-enabled'			=>	['type'	=>	'flag',			'null'	=>	true,	'default'	=>	1,				'reset-format'	=>	true, ],
			'in-thumbnail-bg-color'				=>	['type'	=>	'color',		'null'	=>	true,	'default'	=>	'#ffffff',		'reset-format'	=>	true, ],
			'in-thumbnail-border-enabled'		=>	['type'	=>	'flag',			'null'	=>	true,	'default'	=>	1,				'reset-format'	=>	true, ],
			'in-thumbnail-border-color'			=>	['type'	=>	'color',		'null'	=>	true,	'default'	=>	'#888888',		'reset-format'	=>	true, ],
			'in-thumbnail-border-style'			=>	['type'	=>	'border',		'null'	=>	true,	'default'	=>	'solid',		'reset-format'	=>	true, ],
			'in-thumbnail-border-width'			=>	['type'	=>	'numeric',		'null'	=>	true,	'default'	=>	1,				'reset-format'	=>	true, ],
			'in-thumbnail-border-radius'		=>	['type'	=>	'numeric',		'null'	=>	true,	'default'	=>	4,				'reset-format'	=>	true, ],
			'in-thumbnail-shadow-enabled'		=>	['type'	=>	'flag',			'null'	=>	true,	'default'	=>	null,			'reset-format'	=>	true, ],
			'in-thumbnail-shadow-color'			=>	['type'	=>	'color',		'null'	=>	true,	'default'	=>	'#aaaacc',		'reset-format'	=>	true, ],
			'in-thumbnail-shadow-x'				=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	8,				'reset-format'	=>	true, ],
			'in-thumbnail-shadow-y'				=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	8,				'reset-format'	=>	true, ],
			'in-thumbnail-shadow-blur'			=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	8,				'reset-format'	=>	true, ],
			'in-thumbnail-shadow-spread'		=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	0,				'reset-format'	=>	true, ],
			'in-thumbnail-shadow-inset'			=>	['type'	=>	'flag',			'null'	=>	true,	'default'	=>	null,			'reset-format'	=>	true, ],

			'flg-nofollow'						=>	['type'	=>	'flag',			'null'	=>	true,	'default'	=>	null, ],
			'flg-noopener'						=>	['type'	=>	'flag',			'null'	=>	true,	'default'	=>	1, ],
			'flg-referer'						=>	['type'	=>	'flag',			'null'	=>	true,	'default'	=>	1, ],
			'flg-relative-url'					=>	['type'	=>	'flag',			'null'	=>	true,	'default'	=>	1, ],
			'flg-unlink'						=>	['type'	=>	'flag',			'null'	=>	true,	'default'	=>	1, ],
			'flg-sslverify'						=>	['type'	=>	'flag',			'null'	=>	true,	'default'	=>	1, ],
			'flg-robots'						=>	['type'	=>	'flag',			'null'	=>	true,	'default'	=>	null, ],
			'flg-local-check'					=>	['type'	=>	'flag',			'null'	=>	true,	'default'	=>	1, ],
			'flg-redir'							=>	['type'	=>	'flag',			'null'	=>	true,	'default'	=>	1, ],
			'user-agent'						=>	['type'	=>	'string',		'null'	=>	true,	'default'	=>	'pzlkc', ],
			'user-agent-text'					=>	['type'	=>	'string',		'null'	=>	true,	'default'	=>	null, ],
			'flg-click-count'					=>	['type'	=>	'flag',			'null'	=>	true,	'default'	=>	1, ],
			'flg-click-log'						=>	['type'	=>	'flag',			'null'	=>	true,	'default'	=>	null, ],
			'click-log-retention'				=>	['type'	=>	'string',		'null'	=>	false,	'default'	=>	'3m', ],
			'flg-alive-count'					=>	['type'	=>	'flag',			'null'	=>	true,	'default'	=>	null, ],

			'auto-atag'							=>	['type'	=>	'flag',			'null'	=>	true,	'default'	=>	null, ],
			'auto-url'							=>	['type'	=>	'flag',			'null'	=>	true,	'default'	=>	null, ],
			'flg-do-shortcode'					=>	['type'	=>	'flag',			'null'	=>	true,	'default'	=>	1, ],
			'auto-external'						=>	['type'	=>	'flag',			'null'	=>	true,	'default'	=>	null, ],
			'exclude-url'						=>	['type'	=>	'textarea',		'null'	=>	true,	'default'	=>	null, ],
			'flg-edit-block'					=>	['type'	=>	'flag',			'null'	=>	true,	'default'	=>	1, ],
			'flg-edit-insert'					=>	['type'	=>	'flag',			'null'	=>	true,	'default'	=>	1, ],
			'flg-edit-preview'					=>	['type'	=>	'flag',			'null'	=>	true,	'default'	=>	null, ],
			'flg-edit-qtag'						=>	['type'	=>	'flag',			'null'	=>	true,	'default'	=>	1, ],
			'flg-clear-excerpt'					=>	['type'	=>	'flag',			'null'	=>	true,	'default'	=>	1, ],
			'code1'								=>	['type'	=>	'code',			'null'	=>	true,	'default'	=>	'blogcard', ],
			'use-inline'						=>	['type'	=>	'flag',			'null'	=>	true,	'default'	=>	null, ],
			'code2'								=>	['type'	=>	'code',			'null'	=>	true,	'default'	=>	null, ],
			'code3'								=>	['type'	=>	'code',			'null'	=>	true,	'default'	=>	null, ],

			'multi-myid'						=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	0, ],
			'multi-count'						=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	0, ],

			'trail-slash'						=>	['type'	=>	'flag',			'null'	=>	true,	'default'	=>	1, ],
			'class-pc'							=>	['type'	=>	'string',		'null'	=>	true,	'default'	=>	null, ],
			'class-mobile'						=>	['type'	=>	'string',		'null'	=>	true,	'default'	=>	null, ],
			'mce-priority'						=>	['type'	=>	'numeric',		'null'	=>	true,	'default'	=>	null, ],
			'flg-compress'						=>	['type'	=>	'flag',			'null'	=>	true,	'default'	=>	1, ],
			'date-format-man'					=>	['type'	=>	'string',		'null'	=>	true,	'default'	=>	'Y\<\b\r\>m/d\<\b\r\>H:i', ],
			'flg-inhibit'						=>	['type'	=>	'flag',			'null'	=>	true,	'default'	=>	1, ],
			'flg-preview'						=>	['type'	=>	'flag',			'null'	=>	true,	'default'	=>	1, ],
			'preview-mode'						=>	['type'	=>	'string',		'null'	=>	true,	'default'	=>	'icon', ],
			'preview-left'						=>	['type'	=>	'numeric',		'null'	=>	true,	'default'	=>	null, ],
			'preview-top'						=>	['type'	=>	'numeric',		'null'	=>	true,	'default'	=>	null, ],
			'preview-width'						=>	['type'	=>	'numeric',		'null'	=>	true,	'default'	=>	null, ],
			'preview-height'					=>	['type'	=>	'numeric',		'null'	=>	true,	'default'	=>	null, ],
			'preview-docked-height'				=>	['type'	=>	'numeric',		'null'	=>	true,	'default'	=>	null, ],
			'preview-right-docked-width'		=>	['type'	=>	'numeric',		'null'	=>	true,	'default'	=>	null, ],
			'preview-two-cards'					=>	['type'	=>	'flag',			'null'	=>	true,	'default'	=>	null, ],
			'flg-quickmenu'						=>	['type'	=>	'flag',			'null'	=>	true,	'default'	=>	1, ],
			'flg-amp-url'						=>	['type'	=>	'flag',			'null'	=>	true,	'default'	=>	null, ],
			'flg-public-only'					=>	['type'	=>	'flag',			'null'	=>	true,	'default'	=>	1, ],

			'flg-special-amazon'				=>	['type'	=>	'flag',			'null'	=>	true,	'default'	=>	1, ],
			'flg-special-twitter'				=>	['type'	=>	'flag',			'null'	=>	true,	'default'	=>	1, ],
			'flg-special-jmty'					=>	['type'	=>	'flag',			'null'	=>	true,	'default'	=>	1, ],
			'flg-special-yahoo-auc'				=>	['type'	=>	'flag',			'null'	=>	true,	'default'	=>	1, ],
			'flg-special-microsoft'				=>	['type'	=>	'flag',			'null'	=>	true,	'default'	=>	1, ],

			'alive-period'						=>	['type'	=>	'schedule',		'null'	=>	false,	'default'	=>	'hourly', ],
			'alive-period-num'					=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	5, ],
			'sns-period'						=>	['type'	=>	'schedule',		'null'	=>	false,	'default'	=>	'hourly', ],
			'sns-period-num'					=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	5, ],

			'flg-adminbar'						=>	['type'	=>	'flag',			'null'	=>	true,	'default'	=>	null, ],
			'debug-mode'						=>	['type'	=>	'flag',			'null'	=>	true,	'default'	=>	null, ],
			'survey-mode'						=>	['type'	=>	'flag',			'null'	=>	true,	'default'	=>	null, ],
			'admin-mode'						=>	['type'	=>	'flag',			'null'	=>	true,	'default'	=>	null, ],
			'multi-mode'						=>	['type'	=>	'flag',			'null'	=>	true,	'default'	=>	null, ],
			'flg-initialize'					=>	['type'	=>	'flag',			'null'	=>	true,	'default'	=>	1, ],
			'develop-mode'						=>	['type'	=>	'flag',			'null'	=>	true,	'default'	=>	null, ],

			'css-add-url'						=>	['type'	=>	'url',			'null'	=>	true,	'default'	=>	null, ],
			'css-add'							=>	['type'	=>	'string',		'null'	=>	true,	'default'	=>	null, ],
			'css-count'							=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	0, ],
			'siteicon-api'						=>	['type'	=>	'url_template',	'null'	=>	true,	'default'	=>	'https://www.google.com/s2/favicons?domain=%DOMAIN%', ],
			'thumbnail-api'						=>	['type'	=>	'url_template',	'null'	=>	true,	'default'	=>	'https://s.wordpress.com/mshots/v1/%URL%?w=200', ],

			'initialize-exception'				=>	['type'	=>	'string',		'null'	=>	true,	'default'	=>	0, ],
			'flg-delete-settings'				=>	['type'	=>	'flag',			'null'	=>	true,	'default'	=>	1, ],
			'flg-delete-image'					=>	['type'	=>	'flag',			'null'	=>	true,	'default'	=>	1, ],
			'flg-delete-db'						=>	['type'	=>	'flag',			'null'	=>	true,	'default'	=>	1, ],

			'plugin-version'					=>	['type'	=>	'version',		'null'	=>	false,	'default'	=>	null, ],
			'db-version'						=>	['type'	=>	'string',		'null'	=>	true,	'default'	=>	null, ],
			'db-click-version'					=>	['type'	=>	'string',		'null'	=>	true,	'default'	=>	null, ],
			'debug-nocache'						=>	['type'	=>	'flag',			'null'	=>	true,	'default'	=>	null, ],

			'error-mode'						=>	['type'	=>	'flag',			'null'	=>	true,	'default'	=>	null, ],
			'error-mode-hide'					=>	['type'	=>	'flag',			'null'	=>	true,	'default'	=>	null, ],
			'error-postid'						=>	['type'	=>	'numeric',		'null'	=>	true,	'default'	=>	null, ],
			'error-url'							=>	['type'	=>	'url',			'null'	=>	true,	'default'	=>	null, ],
			'error-time'						=>	['type'	=>	'timestamp',	'null'	=>	true,	'default'	=>	null, ],
			'error-hide'						=>	['type'	=>	'flag',			'null'	=>	true,	'default'	=>	null, ],
		);

	// 定数・プラグイン情報
	private		const	PLUGIN_NAME			=	'Pz-LinkCard';
	private		const	PLUGIN_SLUG			=	'pz-linkcard';

	private		const	PLUGIN_ACRONYM		=	'Pz-LkC';
	private		const	PLUGIN_PATH			=	'/pz-linkcard';
	private		const	OPTION_NAME			=	'pz_linkcard_options';
	private		const	AUTHOR_URL			=	'https://popozure.info';
	private		const	AUTHOR_NAME			=	'Popozure';
	private		const	AUTHOR_TWITTER		=	'@popozure';
	private		const	AUTHOR_TWITTER_URL	=	'https://x.com/popozure';
	private		const	AUTHOR_DONATE_URL	=	'https://www.amazon.jp/hz/wishlist/ls/12LL2TX9147CY?ref_=wl_share&tag=popozure-22';
	private		const	CRON_ALIVE			=	'pz_linkcard_alive';
	private		const	CRON_CHECK			=	'pz_linkcard_check';
	private		const	CRON_CLICK_CLEANUP	=	'pz_linkcard_click_cleanup';
	private		const	CRON_GET			=	'pz_linkcard_get';
	private		const	AJAX_GET_ENABLED	=	true;
	private		const	CACHEMAN_PAGE		=	'pz-linkcard-cacheman';								// Pzカード管理のページ名
	private		const	CACHEMAN_URL		=	'/tools.php?page=pz-linkcard-cacheman';				// Pzカード管理のURL
	private		const	SETTINGS_PAGE		=	'pz-linkcard-settings';								// Pzカード設定のページ名
	private		const	SETTINGS_URL		=	'/options-general.php?page=pz-linkcard-settings';	// Pzカード設定のURL

	private		const	ENV_PRODUCT_URL		=	'https://popozure.info/';
	private		const	ENV_DEVELOP_URL		=	'https://popozure.xsrv.jp/develop/';

	// 変数
	private		$slug;					// スラッグ
	private		$charset;				// 文字セット
	private		$amp;					// Google AMP 0:不明 1:AMP 2:通常
	private		$now;					// 現在日時（ローカル時間）

	private		$home_url;				// 自サイトのURL
	private		$scheme;				// 自サイトのスキーム
	private		$domain;				// 自サイトのドメイン名
	private		$domain_url;			// 自サイトのドメインURL

	private		$now_url;				// 現在開いているURL

	private		$plugin_basename;		// プラグイン ディレクトリの名前
	private		$plugin_dir_path;		// プラグイン ディレクトリのパス
	private		$plugin_dir_url;		// プラグイン ディレクトリのURL
	private		$plugin_link;			// プラグインページのURL
	private		$db_card;				// DBのテーブル名
	private		$db_click;				// DBのテーブル名
	private		$activate_now;			// 二重実行防止
	private		$suppression;			// 出力抑制
	private		$now_page;				// 表示中のページ（1:Pzカード設定 2:Pzカード管理）
	private		$options;				// パラメータ
	private		$settings_page;			// 設定画面のパス
	private		$settings_url;			// 設定画面のURL
	private		$cacheman_page;			// 管理画面のパス
	private		$cacheman_url;			// 管理画面のURL

	private		$test_count;			// テスト用

	private		$request_cache	=	array();

	private	static	function	pz_GetOptionDefinitions() {
		return	self::DEFAULTS;
	}

	private	static	function	pz_GetDefaultOptions() {
		$options	=	array();
		foreach	(self::pz_GetOptionDefinitions() as $key => $default ) {
			$options[$key]	=	$default['default'];
		}
		return	$options;
	}

	private	static	function	pz_GetDefaultOption($key ) {
		$definitions	=	self::pz_GetOptionDefinitions();
		return	array_key_exists($key, $definitions ) ? $definitions[$key]['default'] : null;
	}

	private	static	function	pz_RemoveThisLinkFallback($options ) {
		$definitions	=	self::pz_GetOptionDefinitions();
		foreach	(array_keys($options ) as $key ) {
			if	(substr($key, 0, 3 ) === 'th-' && !array_key_exists($key, $definitions ) ) {
				unset($options[$key] );
			}
		}
		return	$options;
	}

	public	function	__construct() {
		global						$wpdb;													// DBの宣言

		// プラグイン情報
		$default_headers = array(
			'Version'			=>	'Version',
			'Author'			=>	'Author',
			'AuthorURI'			=>	'Author URI',
			'TextDomain'		=>	'Text Domain',
		);
		$plugin_info			=	get_file_data(__FILE__, $default_headers );
		define('PZLKC_PLUGIN_VERSION',	$plugin_info['Version'] );									// バージョン

		// 定数
		define('PZLKC_URL_ADMIN_JS',			plugins_url('js/admin-settings.js', __FILE__ ) );		// 管理画面のJSのURL（設定画面）
		define('PZLKC_URL_ADMIN_SEARCH',		plugins_url('js/admin-search.js', __FILE__ ) );			// 管理画面のJSのURL（設定画面検索）
		define('PZLKC_URL_PREVIEW_JS',			plugins_url('js/pz-linkcard-preview.js', __FILE__ ) );	// 管理画面のJSのURL（プレビュー）
		define('PZLKC_URL_ADMIN_TAB',			plugins_url('js/admin-tabs.js', __FILE__ ) );			// 管理画面のJSのURL（設定画面タブ）
		define('PZLKC_URL_COLOR_PICKER_JS',		plugins_url('js/color-picker.js', __FILE__ ) );			// 管理画面のJSのURL（カラーピッカー）
		define('PZLKC_JS_COUNT',				plugins_url('js/click-count.js', __FILE__ ) );			// 管理画面のJSのURL（クリックカウント）
		define('PZLKC_URL_ADMIN_CSS',			plugins_url('css/admin.css', __FILE__ ) );				// 管理画面のCSSのURL
		define('PZLKC_URL_COLOR_PICKER_CSS',	plugins_url('css/color-picker.css', __FILE__ ) );		// 管理画面のCSSのURL（カラーピッカー）

		define('PZLKC_DIR_UPLOAD',			wp_upload_dir()['basedir']. '/'.self::PLUGIN_SLUG.'/' );	// アップロード ディレクトリのパス
		define('PZLKC_URL_UPLOAD',			preg_replace('/(http|https):(\/\/.*)/', '$2', wp_upload_dir()['baseurl'] ).'/'.self::PLUGIN_SLUG.'/' );		// アップロード ディレクトリのURL

		define('PZLKC_DIR_STYLE',			PZLKC_DIR_UPLOAD.'style/' );							// CSSディレクトリのパス
		define('PZLKC_URL_STYLE',			PZLKC_URL_UPLOAD.'style/' );							// CSSディレクトリのURL

		define('PZLKC_DIR_CACHE',			PZLKC_DIR_UPLOAD.'cache/' );							// 画像キャッシュのパス
		define('PZLKC_URL_CACHE',			PZLKC_URL_UPLOAD.'cache/' );							// 画像キャッシュのURL

		define('PZLKC_DIR_DEBUG',			PZLKC_DIR_UPLOAD.'debug/' );							// ログファイル ディレクトリのパス
		define('PZLKC_URL_DEBUG',			PZLKC_URL_UPLOAD.'debug/' );							// ログファイル ディレクトリのURL

		define('PZLKC_DATE_FORMAT',			get_option('date_format' ) );
		define('PZLKC_TIME_FORMAT',			get_option('time_format' ) );
		define('PZLKC_DATETIME_FORMAT',		PZLKC_DATE_FORMAT.' '.PZLKC_TIME_FORMAT );

		define('PZLKC_FILE_TEMPLATE',		plugin_dir_path(__FILE__ ).'template/pz-linkcard-template.css' );	// 元となるテンプレート

		// 定数
		$this->slug					=	basename(dirname(__FILE__ ) );						// スラッグ
		$this->charset				=	get_bloginfo('charset' );							// 文字セット
		$this->amp					=	0;													// 今がAMP表示かどうか判定

		$this->now					=	current_time('timestamp', false );					// 現在日時（ローカル時間）
		$this->db_card				=	$wpdb->prefix.'pz_linkcard';						// DBのテーブル名
		$this->db_click				=	$wpdb->prefix.'pz_linkcard_click';					// DBのテーブル名
		$this->plugin_basename		=	plugin_basename(__FILE__ );							// プラグイン ディレクトリの名前
		$this->plugin_dir_path		=	plugin_dir_path(__FILE__ );							// プラグイン ディレクトリのパス
		$this->plugin_dir_url		=	plugin_dir_url (__FILE__ );							// プラグイン ディレクトリのURL
		$this->settings_url			=	admin_url(self::SETTINGS_URL );						// Pzカード設定のURL
		$this->cacheman_url			=	admin_url(self::CACHEMAN_URL );						// Pzカード管理のURL

		// オプション取得
		$this->suppression			=	false;												// 出力抑制（header出力前かどうか）
		$result						=	$this->pz_LoadOptions();
		define('PZLKC_URL_CSS_ADD',		$this->options['css-add-url'] );					// 追加CSSのURL

		// ログ出力
		if	($this->options['survey-mode'] ) { $this->pz_OutputLog(__FUNCTION__, 'is_admin='.is_admin(), true ); }

		// バージョンが違う場合、初期処理を実行する
		if	($this->options['plugin-version']	<>	PZLKC_PLUGIN_VERSION ) {
			$this->hook_activate();															// プラグインの再起動
		}

		// 環境情報
		$this->now_url		=	(is_ssl() ? 'https' : 'http' ).'://'.$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"];
		$this->home_url		=	esc_url(home_url().(substr(home_url(), -1, 1 ) == '/' ? '' : '/' ) );
		switch	(true ) {
		case	(substr($this->now_url, 0, strlen(Self::ENV_PRODUCT_URL ) ) == Self::ENV_PRODUCT_URL ):
			$this->options['develop-mode']		=	2;
			break;
		case	(substr($this->now_url, 0, strlen(Self::ENV_DEVELOP_URL ) ) == Self::ENV_DEVELOP_URL ):
			$this->options['develop-mode']		=	1;
			break;
		default:
			$this->options['develop-mode']		=	0;
		}

		// URL解析（自サイトチェック）（Optionsを読み込んでから）
		$this->home_url				=	esc_url(home_url() );
		$url_info					=	$this->Pz_GetURLInfo($this->home_url );
		$this->scheme				=	$url_info['scheme'];		// 自サイトのスキーム
		$this->domain				=	$url_info['domain'];		// 自サイトのドメイン名
		$this->domain_url			=	$url_info['domain_url'];	// 自サイトのドメインURL

		// 言語の国際化（日本語化）
		load_plugin_textdomain('pz-linkcard', false, $this->slug.'/languages' );

		// 有効化・無効化フック
		register_activation_hook(__FILE__, [$this, 'hook_activate' ] );
		register_deactivation_hook(__FILE__, [$this, 'hook_deactivate' ] );

		// 共通アクション（実行順）
		add_action('init', [$this, 'action_register_block' ] );
		add_action(self::CRON_ALIVE, [$this, 'schedule_hook_alive' ] );
		add_action(self::CRON_CHECK, [$this, 'schedule_hook_check' ] );
		add_action(self::CRON_CLICK_CLEANUP, [$this, 'schedule_hook_click_cleanup' ] );
		add_action(self::CRON_GET, [$this, 'schedule_hook_get' ] );

		// WP-CRONスケジュール登録
		$this->pz_SyncCronSchedules();
		$this->pz_ScheduleClickLogCleanup();

		// 管理画面のとき
		if	(is_admin() ) {
			// 現在の管理ページ
			$request_uri		=	isset($_SERVER['REQUEST_URI'] ) ? wp_unslash($_SERVER['REQUEST_URI'] ) : '';
			$this->now_page	=	'';
			if	(strpos($request_uri, self::SETTINGS_URL ) !== false ) {
				$this->now_page	=	1;		// Pz カード設定
			} elseif	(strpos($request_uri, self::CACHEMAN_URL ) !== false ) {
				$this->now_page	=	2;		// Pz カード管理
			}

			// 管理画面用アクション
			add_action		('init',									[$this, 'action_init' ],						10, 1 );	// プラグイン初期化
			add_action		('upgrader_process_complete',				[$this, 'action_upgrader_process_complete' ],	10, 2 );	// アップデートしたときの処理
			add_action		('admin_post_pz_export_file',				[$this, 'action_export_file' ],					10, 1 );	// エクスポート処理
			add_action		('enqueue_block_editor_assets',				[$this, 'action_enqueue_block_editor_assets' ],	10, 1 );	// ブロックエディタ用スクリプト
			add_action		('enqueue_block_assets',					[$this, 'action_enqueue_block_assets' ],		10, 1 );	// ブロックエディタ本文

			// カード設定画面用Ajaxアクション
			add_action		('wp_ajax_pz_lkc_clear_error_mode',			[$this, 'action_ajax_pz_lkc_error_mode_clear'] );
			add_action		('wp_ajax_pz_lkc_preview_render',			[$this, 'action_ajax_pz_lkc_preview_render'] );
			add_action		('wp_ajax_pz_lkc_preview_state',			[$this, 'action_ajax_pz_lkc_preview_state'] );

			// カード管理画面用Ajaxアクション
			add_action		('wp_ajax_pz_lkc_save_cacheman_columns',	[$this, 'action_ajax_pz_lkc_save_cacheman_columns'] );
			add_action		('wp_ajax_pz_lkc_find_used_posts',			[$this, 'action_ajax_pz_lkc_find_used_posts'] );
			add_action		('wp_ajax_pz_lkc_cacheman_pending',		[$this, 'action_ajax_pz_lkc_cacheman_pending'] );

			// ビジュアルエディター用Ajaxアクション
			add_action		('wp_ajax_pz_lkc_mce_post_search',			[$this, 'action_ajax_pz_lkc_mce_post_search'] );
			add_action		('wp_ajax_pz_lkc_mce_render_card',			[$this, 'action_ajax_pz_lkc_mce_render_card'] );
		} else {
			// 通常表示用フィルター
			if	($this->options['auto-atag'] || $this->options['auto-url'] ) {										// 自動置き換え
				add_filter('the_content', [$this, 'auto_replace' ] );
			}

			// ショートコード
			if	($this->options['auto-atag'] || $this->options['auto-url'] ) {
				add_shortcode(self::PLUGIN_SLUG.'-auto-replace', [$this, 'shortcode' ] );
			}
			foreach	(array('code1', 'code2', 'code3' ) as $key ) {
				$code	=	preg_replace('/[^a-zA-Z0-9]/', '', $this->options[$key] );
				if	($code ) {
					add_shortcode($code, [$this, 'shortcode' ] );
				}
			}

			// 通常表示用アクション
			add_action('wp_enqueue_scripts', [$this, 'action_wp_enqueue_scripts' ] );
		}

		// 投稿表示側でも使用するAjaxアクション
		add_action		('wp_ajax_pz_lkc_refresh_card',				[$this, 'action_ajax_pz_lkc_refresh_card'] );
		add_action		('wp_ajax_pz_lkc_refresh_thumbnail',		[$this, 'action_ajax_pz_lkc_refresh_thumbnail'] );
		add_action		('wp_ajax_pz_lkc_click_count', 				[$this, 'action_ajax_pz_lkc_click_count'] );	// ログインユーザー用Ajaxアクション
		add_action		('wp_ajax_nopriv_pz_lkc_click_count',		[$this, 'action_ajax_pz_lkc_click_count'] );	// 未ログインユーザー用Ajaxアクション
		if (self::AJAX_GET_ENABLED ) {
			add_action	('wp_ajax_pz_lkc_ajax_get',			[$this, 'action_ajax_pz_lkc_ajax_get'] );
			add_action	('wp_ajax_nopriv_pz_lkc_ajax_get',	[$this, 'action_ajax_pz_lkc_ajax_get'] );
		}
	}

	// テキストリンクの行とURLのみの行をリンクカードへ置き換える処理（直接HTMLタグにするのでは無くショートコードに変換する。）
	public	function	auto_replace($content ) {
		if	($this->options['survey-mode'] ) { $this->pz_OutputLog(__FUNCTION__ ); }

		if		(!$this->options['auto-external'] && !$this->options['exclude-url'] ) {
			// 内部リンクも外部リンクも変換する
			if	($this->options['auto-atag'] ) {
				$content	=	preg_replace('/(^|<br ?\/?>)(<p.*>)?<a\s.*href\s*=\s*[\'"]?((https?):\/\/[^\s<>\'"]+)[\'"]?[^<]*<\/a>(<\/p>)?$/im', '[pz-linkcard-auto-replace url="$3"]', $content );
			}
			if	($this->options['auto-url'] ) {
				$content	=	preg_replace('/(^|<br ?\/?>)(<p.*>)?((https?):\/\/[^\s<>]+)(<\/p>|<br ?\/?>)?$/im', '[pz-linkcard-auto-replace url="$3"]', $content );
			}
			if	($this->options['flg-do-shortcode'] && ($this->options['auto-atag'] || $this->options['auto-url'] ) ) {
				$content	=	do_shortcode($content );
			}
			return	$content;
		} else {
			// 「外部リンクのみを変換する」または「除外URLが設定されている」場合
			$exclude	=	preg_split('/\R/', $this->options['exclude-url'] );	// 除外URLリスト

			// テキストリンク置き換え
			if	($this->options['auto-atag'] ) {
				preg_match_all('/(^|<br ?\/?>)(<p.*>)?(<a\s.*href\s*=\s*[\'"]?((https?):\/\/[^\s<>\'"]+)[\'"]?[^<]*<\/a>)(<\/p>)?$/im', $content, $m );
				for ($i = 0 ; $i < count($m[0]) ; $i++ ) {
					$url			=	$m[4][$i];
					$is_exclude		=	false;
					// 外部リンクのみ
					if	($this->options['auto-external'] ) {
						$url_info		=	$this->Pz_GetURLInfo($url );	// URL解析（自サイトチェック）
						if	(!$url_info['is_external'] ) {	// 外部リンクじゃない場合、
							$is_exclude	=	true;			// 除外
						}
					}

					// 除外URLチェック
					if	($this->options['exclude-url'] ) {
						foreach	($exclude as $ex) {
							$ex			=	trim($ex);
							// ワイルドカード対応（* を正規表現に変換）
							$pattern	=	preg_quote($ex, '/' );
							$pattern	=	str_replace('\*', '.*', $pattern );
							// 前方一致判定（^で行頭固定）
							if	(preg_match('/^' . $pattern . '/', $url ) ) {
								$is_exclude	=	true;	// 除外
								break;
							}
						}
					}

					// 外部リンクかつ除外URLにマッチしない場合、置き換え
					if ($is_exclude === false ) {
						$tag_from	=	$m[0][$i];
						$tag_to		=	'[pz-linkcard-auto-replace url="'.$url.'"]';
						$content	=	str_replace($tag_from, $tag_to, $content );
					}
				}
			}

			// URLのみ置き換え
			if	($this->options['auto-url'] ) {
				preg_match_all('/(^|<br ?\/?>)(<p.*>)?((https?):\/\/[^\s<>]+)(<\/p>|<br ?\/?>)?$/im', $content, $m );
				for ($i	= 0 ; $i < count($m[0]) ; $i++ ) {
					$url	=	$m[3][$i];
					$is_exclude		=	false;

					// 外部リンクのみ
					if	($this->options['auto-external'] ) {
						$url_info		=	$this->Pz_GetURLInfo($url );	// URL解析（自サイトチェック）
						if	(!$url_info['is_external'] ) {	// 外部リンクじゃない場合、
							$is_exclude	=	true;			// 除外
						}
					}

					// 除外URLチェック
					if	($this->options['exclude-url'] ) {
						foreach	($exclude as $ex) {
							$ex			=	trim($ex);
							// ワイルドカード対応（* を正規表現に変換）
							$pattern	=	preg_quote($ex, '/' );
							$pattern	=	str_replace('\*', '.*', $pattern );
							// 前方一致判定（^で行頭固定）
							if	(preg_match('/^' . $pattern . '/', $url ) ) {
								$is_exclude	=	true;	// 除外
								break;
							}
						}
					}

					// 外部リンクかつ除外URLにマッチしない場合、置き換え
					if ($is_exclude === false ) {
						$tag_from	=	$m[0][$i];
						$tag_to		=	'[pz-linkcard-auto-replace url="'.$url.'"]';
						$content	=	str_replace($tag_from, $tag_to, $content );
					}
				}
			}

			if	($this->options['flg-do-shortcode'] && ($this->options['auto-atag'] || $this->options['auto-url'] ) ) {
				$content	=	do_shortcode($content );
			}
			return	$content;
		}
	}

	// ショートコード処理
	public	function	shortcode($atts, $content = null, $shortcode = null ) {
		if	($this->options['survey-mode'] ) { $this->pz_OutputLog(__FUNCTION__ ); }

		// // 実行時間
		// if	($this->options['debug-mode'] ) {
		// 	if	(function_exists('hrtime' ) ) {
		// 		$start_time		=	hrtime(true ) / 1000;
		// 	} else {
		// 		$start_time		=	microtime(true );
		// 	}
		// }

		// キーをすべて小文字にする
		// $atts = array_change_key_case($atts, CASE_LOWER);

		// URLパラメータ
		switch	(true) {
		case	(!empty($atts['url'] ) ) :
			$url	=	$atts['url'];
			break;
		case	(!empty($atts['href'] ) ) :				// Aタグのようにhrefパラメータも有効にする
			$url	=	$atts['href'];
			break;
		case	(!empty($atts['uri'] ) ) :				// 密かに記述ミス対応（uriやurIでもurlとして判定する）
			$url	=	$atts['uri'];
			break;
		case	(!empty($atts['ur1'] ) ) :				// 密かに記述ミス対応（ur1でもurlとして判定する）
			$url	=	$atts['ur1'];
			break;
//		case	(!empty($atts[0] ) ) :					// 謎の記述ミスに対応
//			$url	=	$atts[0];
//			break;
//		case	(!empty($atts[1] ) ) :					// 謎の記述ミスに対応
//			$url	=	$atts[1];
//			break;
		default:
			$url	=	'';
			break;
		}

		// 最初にあるURLっぽいのを持ってくる
		if	(preg_match('/(https?:\/\/[^\s<>]+)/sui', $url, $m ) ) {
			$url	=	$m[1];
		}

		// 指定されたurlパラメータ（エラー表示用）
		$url_org	=	$url;

		// 相対URLを絶対URLに変換（ショートコードのURLで相対パス表記の場合、内部リンクと見なす）
		if	($url && $this->options['flg-relative-url'] && !mb_strpos($url, '://' ) ) {
			$url	=	$this->pz_RelToURL(esc_url(home_url() ), $url );
		}

		// URLのサニタイズ＆エンティティ化
		if	($url ) {
			$url	=	$this->pz_EncodeURL($url ,true );
		}

		// URLエラー
		if	(!$url ) {
			if	(!$this->options['error-mode'] ) {
				$post_id								=	get_the_ID();
				if	($post_id ) {
					$this->options['error-mode']		=	true;
					$this->options['error-postid']		=	$post_id;
					$this->options['error-url']			=	get_permalink();
					$this->options['error-time']		=	$this->now;
					// オプション更新
					$result	=	$this->pz_SaveOptions();
				}
			}
			$tag		=	'<div class="linkcard"><a id="lkc-error" class="lkc-error" style="display:block;scroll-margin-top:33vh;"></a><div class="lkc-internal-wrap lkc-unlink"><div class="lkc-info">'.self::PLUGIN_NAME.'</div><div class="lkc-excerpt">'.__('-', 'pz-linkcard' ).' '.__('Incorrect URL specification.', 'pz-linkcard' ).'<br>'.__('-', 'pz-linkcard' ).' '.__('URL', 'pz-linkcard' ).'='.esc_url($url_org ).'</div></div></div>';
			return			PHP_EOL.$tag.PHP_EOL;
		}

		// URLパラメータに編集後のURLを返す
		$atts['url']	=	$url;

		// titleパラメータが無かったらNULLにする
		if	(!isset($atts['title'] ) ) {
			$atts['title']	=	null;
		}

		// excerptパラメータが無かったらNULLにする
		if	(!isset($atts['excerpt'] ) ) {
			if			(isset($atts['content'] ) ) {
				$atts['excerpt']	=	$atts['content'];
			} elseif	(isset($atts['contents'] ) ) {
				$atts['excerpt']	=	$atts['contents'];
			} elseif	(isset($atts['description'] ) ) {
				$atts['excerpt']	=	$atts['description'];
			} else {
				$atts['excerpt']	=	null;
			}
		}

		// 囲まれ文字（ショートコード1のみ有効）
		if	($shortcode == $this->options['code1'] ) {
			switch	($this->options['use-inline'] ) {
			case	1:
				$atts['excerpt']	=	isset($content ) ? $content : null;
				break;
			case	2:
				$atts['title']		=	isset($content ) ? $content : null;
				break;
			}
		}

// ここまでは現在の処理のまま





		// 記事内容取得
		$tag	=	$this->pz_GetLinkCard($atts );

		// // 実行時間
		// if	($this->options['debug-mode'] ) {
		// 	if	(function_exists('hrtime' ) ) {
		// 		$end_time		=	hrtime(true ) / 1000;
		// 	} else {
		// 		$end_time		=	microtime(true );
		// 	}
		// 	$elasped_time	=	$end_time - $start_time;
		// 	$format_time	=	number_format($elasped_time / 1000, 8, '.', ',' );
		// }
		return	$tag;
	}

	// キャッシュやリンク先からリンクカードの表示データを作成
	private function pz_GetLinkCard($atts ) {
		$url = $atts['url'] ?? null;
		if (!$url ) {
			return null;
		}
		$url_info = $this->Pz_GetURLInfo($url );
		if (isset($atts['preview-data'] ) && is_array($atts['preview-data'] ) ) {
			if (!empty($this->options['flg-public-only'] ) && !empty($url_info['is_internal'] ) && !$this->pz_IsInternalCardPubliclyViewable($url, $atts['preview-data'] ) ) {
				return null;
			}
			return $this->pz_GetHTML($atts );
		}

		if (!empty($url_info['is_internal'] ) ) {
			if (!$this->pz_IsInternalCardPubliclyViewable($url ) ) {
				return null;
			}
		}
		$data = $this->pz_GetCache(array('url' => $url ) );
		$data = is_array($data ) && !empty($data['url'] ) ? $data : array('url' => $url );
		if (!empty($url_info['is_internal'] ) && !$this->pz_IsInternalCardPubliclyViewable($url, $data ) ) {
			return null;
		}
		$data_id = !empty($data['id'] ) ? intval($data['id'] ) : 0;
		$force = isset($atts['force'] ) && $atts['force'] == true;

		if (!empty($url_info['is_internal'] ) ) {
			if ($this->options['in-get-from'] == 2 ) {
				if (!$data_id || $force ) {
					$data = $this->pz_GetPost($data );
					$saved = $this->pz_SetCache($data );
					if (is_array($saved ) ) {
						$data = $saved;
					}
				}
			} else {
				$data = $this->pz_GetPost($data );
				if (!$data_id ) {
					$saved = $this->pz_SetCache($data );
					if (is_array($saved ) ) {
						$data = $saved;
					}
				}
			}
		} elseif (!empty($url_info['is_external'] ) ) {
			if (!$data_id && !$force ) {
				$data = $this->pz_SetCache(array(
					'url'			=> $url,
					'title'			=> '',
					'charset'		=> null,
					'regist_title'	=> null,
					'regist_excerpt'	=> null,
					'regist_charset'	=> null,
					'regist_result'	=> null,
					'regist_time'	=> null,
				) );
				$data = is_array($data ) ? $data : array('url' => $url );
				$data_id = !empty($data['id'] ) ? intval($data['id'] ) : 0;
			}
			if ($force || ($this->options['debug-mode'] == true && $this->options['debug-nocache'] == true ) ) {
				$remote = $this->pz_GetRemote($data );
				if (is_array($remote ) && !empty($remote['url'] ) ) {
					$saved = $this->pz_SetCache($remote );
					$data = is_array($saved ) ? $saved : $remote;
				}
			} elseif (array_key_exists('regist_time', $data ) && (is_null($data['regist_time'] ) || intval($data['regist_time'] ) === 0 ) ) {
				$atts['title'] = __('Retrieving...', 'pz-linkcard' );
				$atts['pz-pending'] = true;
				if (!wp_next_scheduled(self::CRON_GET ) ) {
					wp_schedule_single_event(time() + 3, self::CRON_GET );
				}
			}
		}

		$atts['pz-card-data'] = $data;
		return $this->pz_GetHTML($atts );
	}

	// $attsと収集済みデータからリンクカードのHTMLを生成
	private	function	pz_GetHTML($atts ) {
		if	($this->options['survey-mode'] ) { $this->pz_OutputLog(__FUNCTION__, '$atts='.print_r($atts, true ) ); }

		// リンク先URL
		$url			=	isset($atts['url'] ) ? $atts['url'] : null ;

		// URL指定なし
		if	(!$url ) {
			return	null;
		}

		// 変数の用意
		$is_internal	=	false;
		$is_mobile		=	false;
		$data_id		=	'';
		$site_name		=	'';
		$title			=	'';
		$excerpt		=	'';
		$thumbnail_url	=	'';
		$thumbnail_alt	=	'';
		$siteicon_url	=	'';
		$siteicon_alt	=	'';
		$post_date		=	'';
		$post_modified	=	'';
		$update_result	=	'';
		$sns_tw			=	'';
		$sns_fb			=	'';
		$sns_hb			=	'';
		$alive_result	=	'';

		// モバイルチェック
		if	(function_exists('wp_is_mobile' ) && wp_is_mobile() ) {
			$is_mobile	=	true;
		}

		// URL解析（自サイトチェック）
		$url_info		=	$this->Pz_GetURLInfo($url );
		$scheme			=	$url_info['scheme'];		// スキーム
		$domain			=	$url_info['domain'];		// ドメイン名
		$domain_url		=	$url_info['domain_url'];	// ドメインURL
		$is_external	=	$url_info['is_external'];	// 外部リンク
		$is_internal	=	$url_info['is_internal'];	// 内部リンク

		// モバイルかPCかのクラス名を追加
		$class_id		=	'linkcard';
		if	($is_mobile && $this->options['class-mobile'] ) {
			$class_id	.=	' '.esc_attr($this->options['class-mobile'] );
		} elseif	($this->options['class-pc'] ) {
			$class_id	.=	' '.esc_attr($this->options['class-pc'] );
		}
		if (!empty($atts['pz-pending'] ) ) {
			$class_id .= ' pz-lkc-pending';
		}

		// キャッシュから取得
		$is_preview		=	isset($atts['preview-data'] ) && is_array($atts['preview-data'] );
		$is_prepared	=	isset($atts['pz-card-data'] ) && is_array($atts['pz-card-data'] );
		$data			=	array('url' => $url );
		if	($is_preview ) {
			$data		=	array_merge($data, $atts['preview-data'] );
			$data_id	=	'preview';
			$class_id	.=	' pz-preview-linkcard';
		} elseif ($is_prepared ) {
			$data		=	array_merge($data, $atts['pz-card-data'] );
			$data_id	=	$data['id'] ?? '';
			$url		=	$data['url'] ?? $url;
		} else {
			return null;
		}

		// 内部リンクの処理
		if	($is_internal ) {
			// リンクターゲットの設定
			$target			=	null;									// 同じタブに開く
			if	(isset($this->options['in-target'] ) ) {
				if	($this->options['in-target'] == 1 || ($this->options['in-target'] == 2 && !$is_mobile ) ) {
					$target	=	' target="_blank"';						// 新しいタブで開く
				}
			}

			// nofollowの指定
			$rel			=	null;
			if	((isset($atts['follow'] ) && mb_strtolower($atts['follow'] ) == 'no' ) || (isset($atts['nofollow'] ) && mb_strtolower($atts['nofollow'] ) == 'true' ) ) {
				$rel		=	' rel="nofollow"';						// 要望により内部リンクでもnofollow可能（ショートコードのパラメータで指定時のみ）
			}

			// 記事の取得方法
			if	($is_preview || $is_prepared ) {
				// 設定画面プレビューでは取得・保存を行わず、指定されたサンプルデータを使う。
			} elseif	($this->options['in-get-from'] == 2 ) {	// 常にカード管理から
				if	(!$data_id || (isset($atts['force'] ) && $atts['force'] == true ) ) {	// キャッシュに無いとき
					$data		=	$this->pz_GetPost($data );		// 最新記事内容を取得
					$result		=	$this->pz_SetCache($data );		// 保存
				}
			} else {								// 常に最新記事から or 抜粋優先
				$data			=	$this->pz_GetPost($data );		// 最新記事内容を取得（抜粋判断はpz_GetPostで行う）
				if (!$data_id ) {
					$result		=	$this->pz_SetCache($data );		// 保存
				}
			}
		}

		// 外部リンクの処理
		if	($is_external ) {
			// リンクターゲットの設定
			$target			=	null;								// 同じタブに開く
			if	(isset($this->options['ex-target'] ) ) {
				if	($this->options['ex-target'] == 1 || ($this->options['ex-target'] == 2 && !$is_mobile ) ) {
					$target	=	' target="_blank"';					// 新しいタブで開く
				}
			}

			// noopenerとnofollowの指定
			$rel			=	'external';
			if	($this->options['flg-nofollow'] || (isset($atts['follow'] ) && mb_strtolower($atts['follow'] ) == 'no' ) || (isset($atts['nofollow'] ) && mb_strtolower($atts['nofollow'] ) == 'true' ) ) {
				$rel		.=	' nofollow';						// nofollow指定。趣味の問題？
			}
			if	($this->options['flg-noopener'] ) {
				$rel		.=	' noopener';
			}
			$rel			=	' rel="'.$rel.'"';

			if	(!$is_preview && !$is_prepared && (($this->options['debug-mode']	==	true  && $this->options['debug-nocache']	==	true ) || (isset($atts['force'] ) && $atts['force'] == true ) ) ) {
				$result		=	$this->pz_GetRemote($data );			// 記事内容を強制取得
				if	(isset($result ) && is_array($result ) && isset($result['url'] ) ) {
					$data	=	$result;
					$result	=	$this->pz_SetCache($data );
				}
			}
		}
		if	(!$data_id && isset($result ) && is_array($result ) && !empty($result['id'] ) ) {
			$data_id	=	$result['id'];
		}

		// 記事内容をセット
		$url_redir		=	$data['url_redir']		??	'';
		$title			=	$data['title']			??	'';
		$excerpt		=	$data['excerpt']		??	'';
		$site_name		=	$data['site_name']		??	'';
		$thumbnail_url	=	$data['thumbnail']		??	'';
		$siteicon_url	=	$data['favicon']		??	'';
		$post_id		=	$data['post_id']		??	'';
		$post_date		=	$data['post_date']		??	'';
		$post_modified	=	$data['post_modified']	??	'';
		$update_result	=	$data['update_result']	??	'';
		$alive_result	=	$data['alive_result']	??	'';
		$no_failure		=	!empty($data['no_failure'] )	?	true					:	false ;
		$sns_tw			=	$data['sns_twitter']	??	'';
		$sns_fb			=	$data['sns_facebook']	??	'';
		$sns_hb			=	$data['sns_hatena']		??	'';
		$html_thumbnail	=	null;
		$html_siteicon	=	null;

		// リダイレクトURL
		if	($url_redir ) {
			$url		=	$url_redir;
		}

		// ラッピング
		if	($is_internal ) {
			$wrap_class			=	'lkc-internal-wrap';
			$html_wrap_op		=	'<div class="'.$wrap_class.'">';
			$html_wrap_cl		=	'</div>';
			$added_text			=	isset($this->options['in-added-text'] )		?	esc_attr($this->options['in-added-text'] )		:	null ;
			$heading_text		=	isset($this->options['in-heading-text'] )	?	esc_attr($this->options['in-heading-text'] )	:	null ;
			$more_text			=	isset($this->options['in-more-text'] )		?	esc_attr($this->options['in-more-text'] )		:	null ;
			$thumbnail_alt		=	isset($this->options['in-thumbnail-alt'] )	?	esc_attr($this->options['in-thumbnail-alt'] )	:	null ;
			$siteicon_alt		=	isset($this->options['in-siteicon-alt'] )	?	esc_attr($this->options['in-siteicon-alt'] )		:	null ;
			$sw_thumbnail		=	isset($this->options['in-thumbnail'] )		?	esc_attr($this->options['in-thumbnail'] )		:	0 ;
			$sw_siteicon			=	isset($this->options['in-siteicon'] )		?	esc_attr($this->options['in-siteicon'] )			:	0 ;
		} else {
			$wrap_class				=	'lkc-external-wrap';
			$html_pending_loader	=	!empty($atts['pz-pending'] ) ? '<span class="pz-lkc-pending-loader" aria-hidden="true"></span>' : '';
			$html_wrap_op			=	'<div class="'.$wrap_class.'">'.$html_pending_loader;
			$html_wrap_cl			=	'</div>';
			$added_text				=	isset($this->options['ex-added-text'] )		?	esc_attr($this->options['ex-added-text'] )		:	null ;
			$heading_text			=	isset($this->options['ex-heading-text'] )	?	esc_attr($this->options['ex-heading-text'] )	:	null ;
			$more_text				=	isset($this->options['ex-more-text'] )		?	esc_attr($this->options['ex-more-text'] )		:	null ;
			$thumbnail_alt			=	isset($this->options['ex-thumbnail-alt'] )	?	esc_attr($this->options['ex-thumbnail-alt'] )	:	null ;
			$siteicon_alt			=	isset($this->options['ex-siteicon-alt'] )	?	esc_attr($this->options['ex-siteicon-alt'] )		:	null ;
			$sw_thumbnail			=	isset($this->options['ex-thumbnail'] )		?	esc_attr($this->options['ex-thumbnail'] )		:	0 ;
			$sw_siteicon				=	isset($this->options['ex-siteicon'] )		?	esc_attr($this->options['ex-siteicon'] )			:	0 ;
		}

		// ドメイン名の準備
		$domain_name			=	$domain;
		if	(function_exists('idn_to_utf8' ) && substr($domain, 0, 4 ) == 'xn--' ) {	// 国際ドメイン対応（日本語ドメイン対応）
			$domain_name		=	(function_exists('idn_to_utf8' ) && defined('INTL_IDNA_VARIANT_UTS46' ) ) ? idn_to_utf8($domain, 0, INTL_IDNA_VARIANT_UTS46 ) : $domain;
		}

		// サイト名の準備
		$site_name				=	$site_name;

		// 表示用サイト名
		if	(($this->options['flg-use-sitename'] ) && ($site_name ) ) {
			$disp_sitename		=	$site_name;
		} else {
			$disp_sitename		=	$domain_name;
		}
		$disp_sitename			=	esc_html($disp_sitename );

		// 表示用サイト名の文字数
		$title_sitename			=	'';
		$disp_sitename		=	mb_strimwidth($disp_sitename, 0, 100 , '...' );

		// タイトル
		if	(!$title ) {
			$title			=	$this->pz_DecodeURL($url, true );				// タイトル取得できていなかったらURLをセットする
		}

		// パラメータ取得（タイトル）
		if	(isset($atts['title'] ) && $atts['title'] ) {						// title パラメータ
			$title			=	$atts['title'];
			if	(isset($this->options['flg-clear-excerpt'] ) && $this->options['flg-clear-excerpt'] ) {	// 概要文をクリアする
				$excerpt		=	'';
			}
		}

		// パラメータ取得（抜粋文）
		if	(isset($atts['excerpt'] ) && $atts['excerpt'] ) {					// title パラメータ
			$excerpt		=	$atts['excerpt'] ?? '';							// excerpt パラメータ
		}

		// タイトル整形
		$temp			=	$title;												// タイトル
		$temp			=	strip_tags($temp );									// HTMLタグ除去
		$temp			=	str_replace(array("\r", "\n"), '', $temp );			// 改行を除去
		$temp			=	mb_strimwidth($temp, 0, 200 , '...' );
		$title			=	esc_html($temp );
		$html_title		=	'<div class="lkc-title">'.$title.'</div>';

		// 抜粋文整形（抜粋文非表示の場合、空欄にする）
		if	(!$this->options['display-excerpt'] ) {
			$excerpt	=	'';
		} else {
			$temp		=	$excerpt;											// 抜粋文
			$temp		=	strip_tags($temp );									// HTMLタグ除去
			$temp		=	str_replace(array("\r", "\n"), '', $temp );			// 改行を除去
			$temp		=	preg_replace('/<!--more-->.+/is', '', $temp );		// moreタグ以降削除
			$temp		=	preg_replace('/\[[^]]*\]/', '', $temp );			// ショートコードすべて除去
			$temp	=	mb_strimwidth($temp, 0, 500 , '...' );
			$temp		=	esc_html($temp );									// HTMLエスケープ
			$excerpt	=	$temp;
		}
		if	($excerpt ) {
			$html_excerpt		=	'<div class="lkc-excerpt">'.$excerpt.'</div>';
		} else {
			$html_excerpt		=	'';
		}

		// 代替テキスト（サムネイル）
		if	(($thumbnail_alt	<>	'' )	&&		(strstr($thumbnail_alt, '%' )	<>		'' ) ) {
			$temp				=	$thumbnail_alt;
			$temp				=	preg_replace('/%TITLE%/',		$title,					$temp );
			$temp				=	preg_replace('/%EXCERPT%/',		$excerpt,				$temp );
			$temp				=	preg_replace('/%SITE_NAME%/',	$site_name,				$temp );
			$temp				=	preg_replace('/%DOMAIN_URL%/',	$domain_url,			$temp );
			$temp				=	preg_replace('/%DOMAIN%/',		$domain,				$temp );
			$temp				=	preg_replace('/%URL%/',			rawurlencode($url ),	$temp );
			$thumbnail_alt		=	esc_html($temp );
		}

		// 代替テキスト（サイトアイコン）
		if	(($siteicon_alt		<>	'' )	&&		(strstr($siteicon_alt, '%' )		<>		'' ) )	{
			$temp				=	$siteicon_alt;
			$temp				=	preg_replace('/%DOMAIN_URL%/',	$domain_url,			$temp );
			$temp				=	preg_replace('/%DOMAIN%/',		$domain,				$temp );
			$temp				=	preg_replace('/%URL%/',			rawurlencode($url ),	$temp );
			$siteicon_alt		=	esc_html($temp );
		}

		// サムネイル取得
		if	($this->options['thumbnail-position'] ) {
			if	($sw_thumbnail == 1 || $sw_thumbnail == 13 ) {						// 直接取得
				if	($is_external && !$is_preview ) {
					$thumbnail_url	=	$this->pz_GetImage($thumbnail_url );		// 外部サイトのサムネイルをキャッシュ
				}
				if	($thumbnail_url ) {
					$html_thumbnail		=	'<img class="lkc-thumbnail-img" src="'.esc_url($thumbnail_url ).'" width="'.esc_attr($this->options['thumbnail-width'] ).'" height="'.esc_attr($this->options['content-height'] ).'" alt="'.esc_attr($thumbnail_alt ).'" />';
				} elseif	($sw_thumbnail == 13 ) {								// 直接取得に失敗
					$sw_thumbnail	=	3;
				}
			}
			if	($sw_thumbnail == 3 ) {												// WebAPIを利用
				// サムネイル取得WebAPI
				if	($this->options['thumbnail-api'] ) {
					$temp					=	$this->options['thumbnail-api'];
					if	(strstr($temp, '%' )	<>	'' ) {
						$temp				=	preg_replace('/%TITLE%/',		$title,					$temp );
						$temp				=	preg_replace('/%SITE_NAME%/',	$site_name,				$temp );
						$temp				=	preg_replace('/%DOMAIN_URL%/',	$domain_url,			$temp );
						$temp				=	preg_replace('/%DOMAIN%/',		$domain,				$temp );
						$temp				=	preg_replace('/%URL%/',			rawurlencode($url ),	$temp );
					}
					$html_thumbnail	=	'<img class="lkc-thumbnail-img" src="'.esc_url($temp ).'" width="'.esc_attr($this->options['thumbnail-width'] ).'" height="'.esc_attr($this->options['content-height'] ).'" alt="'.esc_attr($thumbnail_alt ).'" />';
				}
			}
		}

		// サイトアイコン取得
		if	($this->options['info-position'] || $is_preview ) {
			if	($sw_siteicon == 1 || $sw_siteicon == 13 ) {							// 直接取得
				if	($is_internal ) {
					$siteicon_url	=	get_site_icon_url(16 );						// 自サイトのサイトアイコン
				} elseif	(!$is_preview ) {
					$siteicon_url	=	$this->pz_GetImage($siteicon_url );			// 外部サイトのサイトアイコンをキャッシュ
				}
				if	($siteicon_url ) {
					$html_siteicon	=	'<div  class="lkc-siteicon"><img src="'.esc_url($siteicon_url ).'" alt="'.esc_attr($siteicon_alt ).'" width="16" height="16" /></div>';
				} elseif	($sw_siteicon == 13 ) {									// 直接取得に失敗
					$sw_siteicon	=	3;
				}
			}
			if	($sw_siteicon == 3 ) {												// WebAPIを利用
				if	($is_preview && $siteicon_url ) {
					$html_siteicon	=	'<div class="lkc-siteicon"><img src="'.esc_url($siteicon_url ).'" alt="'.esc_attr($siteicon_alt ).'" width="16" height="16" /></div>';
				// サイトアイコン取得WebAPI
				} elseif	($this->options['siteicon-api'] ) {
					$temp					=	$this->options['siteicon-api'];
					if	(strstr($temp, '%' )	<>	'' ) {
						$temp				=	preg_replace('/%TITLE%/',		$title,					$temp );
						$temp				=	preg_replace('/%SITE_NAME%/',	$site_name,				$temp );
						$temp				=	preg_replace('/%DOMAIN_URL%/',	$domain_url,			$temp );
						$temp				=	preg_replace('/%DOMAIN%/',		$domain,				$temp );
						$temp				=	preg_replace('/%URL%/',			rawurlencode($url ),	$temp );
					}
					$html_siteicon	=	'<div class="lkc-siteicon"><img src="'.esc_url($temp ).'" alt="'.esc_attr($siteicon_alt ).'" width="16" height="16" /></div>';
				}
			}
		}

		// リンク先URL
		$click_card_id		= absint($data_id );
		$click_post_id		= absint(get_the_ID() );
		$click_token			= $click_card_id ? $this->pz_GetClickToken($click_card_id, $click_post_id ) : '';
		$html_click_data		= ' data-lkc-id="'.esc_attr($data_id ).'" data-post-id="'.esc_attr($click_post_id ).'" data-click-token="'.esc_attr($click_token ).'"';
		if	(!$no_failure && $this->options['flg-unlink'] && in_array(intval($alive_result ), array(403, 404, 410 ), true ) ) {
			// Not Found の時は見え消ししてリンクしない
			$html_wrap_op	=	'<div class="'.$wrap_class.' lkc-unlink">';
			$html_a_op_all	=	null;
			$html_a_cl_all	=	null;
			$html_a_op		=	null;
			$html_a_cl		=	null;
			$html_st_op		=	'<strike>';
			$html_st_cl		=	'</strike>';
		} elseif	($this->options['flg-linkall'] ) {
			// カード全体をリンク（どこをクリックしても良いのが分かり易い）
			$html_a_op_all	=	'<a class="lkc-link no_icon" href="'.esc_url($url ).'"'.$html_click_data.$target.$rel.'>';
			$html_a_cl_all	=	'</a>';
			$html_a_op		=	null;
			$html_a_cl		=	null;
			$html_st_op		=	null;
			$html_st_cl		=	null;
		} else {
			// タイトルとかURLとかを個別でリンク（タイトルや抜粋文などの文字を範囲指定をしてコピー等がし易い）
			$html_a_op_all	=	null;
			$html_a_cl_all	=	null;
			$html_a_op		=	'<a class="lkc-link no_icon" href="'.esc_url($url ).'"'.$html_click_data.$target.$rel.'>';
			$html_a_cl		=	'</a>';
			$html_st_op		=	null;
			$html_st_cl		=	null;
		}

		// ソーシャルカウントの表示
		$sns				=	null;
		$html_sns_title		=	null;
		$html_sns_info		=	null;
		if	($this->options['sns-position'] ) {
			$url_noscheme	=	preg_replace('/^https?:\/\//i', '', $url );	// スキームと「//」を外す
			// カード全体をリンクにするときもSNSボタンをクリック可能にする
			if	($this->options['flg-linkall'] ) {
				if	($this->options['sns-tw'] && $sns_tw > 0 ) {
					if	($this->options['sns-tw-x'] ) {
						$sns	.=	' <object><a class="lkc-sns-tw no_icon" href="'.esc_url('https://twitter.com/search?q='.$url_noscheme.'&text='.$title ).'" target="_blank">'.sprintf(($sns_tw == 1 ? __('%d tweet', 'pz-linkcard' ) : __('%d tweets', 'pz-linkcard' ) ), $sns_tw ).'</a></object>';
					} else {
						$sns	.=	' <object><a class="lkc-sns-x no_icon" href="'.esc_url('https://x.com/search?q='.$url_noscheme.'&text='.$title ).'" target="_blank">'.sprintf(($sns_tw == 1 ? __('%d post',  'pz-linkcard' ) : __('%d posts',  'pz-linkcard' ) ), $sns_tw ).'</a></object>';
					}
				}
				if	($this->options['sns-fb'] && $sns_fb > 0 ) {
					$sns	.=	' <object><a class="lkc-sns-fb no_icon" href="https://www.facebook.com/" target="_blank">'.sprintf(($sns_fb == 1 ? __('%d share',  'pz-linkcard' ) : __('%d shares',  'pz-linkcard' ) ), $sns_fb ).'</a></object>';
				}
				if	($this->options['sns-hb'] && $sns_hb > 0 ) {
					$sns	.=	' <object><a class="lkc-sns-hb no_icon" href="'.esc_url('https://b.hatena.ne.jp/entry/s/'.$url_noscheme ).'" target="_blank">'.sprintf(($sns_hb == 1 ? __('%d user',   'pz-linkcard' ) : __('%d users',   'pz-linkcard' ) ), $sns_hb ).'</a></object>';
				}
			} else {
				// 外部リンクアイコンを表示させるプラグイン対応のため no_icon を付与
				if	($this->options['sns-tw'] && $sns_tw > 0 ) {
					if	($this->options['sns-tw-x'] ) {
						$sns	.=	' <a class="lkc-sns-tw no_icon" href="'.esc_url('https://twitter.com/search?q='.$url_noscheme.'&text='.$title ).'" target="_blank">'.intval($sns_tw ).'&nbsp;tweet'.(($sns_tw > 1 ) ? 's' : null ).'</a>';
					} else {
						$sns	.=	' <a class="lkc-sns-tw no_icon" href="'.esc_url('https://x.com/search?q='.$url_noscheme.'&text='.$title ).'" target="_blank">'.intval($sns_tw ).'&nbsp;post'.(($sns_tw > 1 ) ? 's' : null ).'</a>';
					}
				}
				if	($this->options['sns-fb'] && $sns_fb > 0 ) {
					$sns	.=	' <a class="lkc-sns-fb no_icon" href="https://www.facebook.com/" target="_blank">'.intval($sns_fb ).'&nbsp;share'.(($sns_fb > 1 ) ? 's' : null ).'</a>';
				}
				if	($this->options['sns-hb'] && $sns_hb > 0 ) {
					$sns	.=	' <a class="lkc-sns-hb no_icon" href="'.esc_url('https://b.hatena.ne.jp/entry/s/'.$url_noscheme ).'" target="_blank">'.intval($sns_hb ).'&nbsp;user'.(($sns_hb > 1 ) ? 's' : null ).'</a>';
				}
			}
			if	($sns ) {
				if	($this->options['sns-position'] == 1 ) {
					$html_sns_title	=	'<div class="lkc-share">'.$sns.'</div>';
				} else {
					$html_sns_info	=	'<div class="lkc-share">'.$sns.'</div>';
				}
			}
		}

		// サムネイル
		if	($html_thumbnail ) {
			$html_thumbnail	=	'<figure class="lkc-thumbnail">'.$html_thumbnail.'</figure>';
		}

		// 表示用のURL
		if	($url_redir ) {
			$disp_url		=	esc_html($this->pz_DecodeURL($url_redir, true ) );
		} else {
			$disp_url		=	esc_html($this->pz_DecodeURL($url, true ) );
		}

		// リンク先URL
		$html_url1			=	null;
		$html_url2			=	null;
		switch	($this->options['display-url'] ) {
		case	1:
			$html_url1	=	'<div class="lkc-url" title="'.esc_attr($url ).'">'.$html_a_op.$html_st_op.$disp_url.$html_st_cl.$html_a_cl.'</div>';
			break;
		case	2:
			$html_url2	=	'&nbsp;<div class="lkc-url-info">'.	$html_a_op.$html_st_op.$disp_url.$html_st_cl.$html_a_cl.'</div>';
			break;
		}

		// 投稿日
		$html_date	=	null;
		$display_date_mode	=	$this->options['display-date'] ?: ($is_preview ? 1 : 0);
		if	($is_internal && $display_date_mode ) {
			$html_url1	=	null;
			$html_url2	=	null;
			$post_date_timestamp		=	is_numeric($post_date ) ? intval($post_date ) : strtotime($post_date );
			$post_modified_timestamp	=	is_numeric($post_modified ) ? intval($post_modified ) : strtotime($post_modified );
			switch		($display_date_mode ) {
			case	1:
				$html_date	=	'<div class="lkc-date">'.__('&#x1f552;&#xfe0f;', 'pz-linkcard' ).$this->pz_date(PZLKC_DATE_FORMAT, $post_date_timestamp ).'</div>';
				break;
			case	2:
				if	($post_modified_timestamp > $post_date_timestamp ) {
					$html_date	=	'<div class="lkc-date">'.__('&#x1F501;&#xfe0f;', 'pz-linkcard' ).$this->pz_date(PZLKC_DATE_FORMAT, $post_modified_timestamp ).'</div>';
				} else {
					$html_date	=	'<div class="lkc-date">'.__('&#x1f552;&#xfe0f;', 'pz-linkcard' ).$this->pz_date(PZLKC_DATE_FORMAT, $post_date_timestamp ).'</div>';
				}
				break;
			case	3:
				$html_date	=	'<div class="lkc-date">'.__('&#x1f552;&#xfe0f;', 'pz-linkcard' ).$this->pz_date(PZLKC_DATE_FORMAT, $post_date_timestamp );
				if	($post_modified_timestamp !== $post_date_timestamp ) {
					$html_date	.=	'&ensp;'.__('&#x1F501;&#xfe0f;', 'pz-linkcard' ).$this->pz_date(PZLKC_DATE_FORMAT, $post_modified_timestamp );
				}
				$html_date	.=	'</div>';
				break;
			}
		}

		// 見出し情報
		if (($this->options['special-format'] ?? null) === 'JIN' && ($heading_text === null || $heading_text === '')) {
			$heading_text = $is_internal ? __('You may also like', 'pz-linkcard' ) : __('Referenced', 'pz-linkcard' );
		}
		if	($heading_text || $is_preview ) {
			$html_heading	=	'<div class="lkc-heading"'.($is_preview ? ' data-pz-preview-heading' : '').'>'.$heading_text.'</div>';
		} else {
			$html_heading	=	null;
		}

		// 続きを読むボタン
		if	($more_text || $is_preview ) {
			$html_moretag	=	$html_a_op.'<div class="lkc-more"'.($is_preview ? ' data-pz-preview-more' : '').'>'.$more_text.'</div>'.$html_a_cl;

		} else {
			$html_moretag	=	null;
		}

		// サイト情報
		if	($added_text ) {
			$html_added	=	'<div class="lkc-added">'.$added_text.'</div>';
		} else {
			$html_added	=	null;
		}

		$html_domain	=	'<div class="lkc-domain"'.$title_sitename.'>'.$disp_sitename.'</div>';
		$html_info		=	'<div class="lkc-info"'.($is_preview ? ' data-pz-preview-info' : '').'>'.$html_a_op.$html_siteicon.$html_domain.$html_added.$html_a_cl.$html_sns_info.$html_url2.'</div>';

		// Google AMP用 簡易タグ作成
		if	($this->amp <> 2 ) {
			if	($this->amp === 0 ) {
				$this->amp			=	2;		// 仮に 2:通常（非AMP）とする
				if	((function_exists('ampforwp_is_amp_endpoint' ) && ampforwp_is_amp_endpoint() ) || (function_exists('is_amp_endpoint' ) && is_amp_endpoint() ) || (function_exists('is_amp' ) && is_amp() ) ) {
					$this->amp		=	1;		// 1:AMP
				} else {
					if ($this->options['flg-amp-url'] ) {
						$url_now = $_SERVER["REQUEST_URI"];
						if ((substr($url_now, 4 ) === '/amp' ) || (substr($url_now, 5 ) === '/amp/' ) || (substr($url_now, 6 ) === '?amp=1' ) || (substr($url_now, 8 ) === 'type=AMP' ) ) {
							$this->amp	=	1;		// 1:AMP
						}
					}
				}
			}
			if	($this->amp === 1 ) {
				$html_tag		=	'<div class="lkc-external amp"><table border="1" cellspacing="0" cellpadding="4"><tr><td>'.$excerpt.'<br><a href="'.esc_url($url ).'"'.$target.$rel.'>'.$title.'</a>&nbsp;-&nbsp;'.esc_html($site_name ).'</td></tr></table></div>';
				return	$html_tag;		// タグを出力して終了
			}
		}

		// HTMLタグ作成
		switch	($this->options['info-position'] ) {
		case	1:		// 上側
			$html_tag	=	$html_wrap_op.$html_a_op_all.$html_heading.'<div class="lkc-card">'.$html_info.'<div class="lkc-content"'.($is_preview ? ' data-pz-preview-content' : '').'>'.$html_a_op.$html_thumbnail.$html_title.$html_a_cl.$html_sns_title.$html_url1.$html_date.$html_excerpt.$html_moretag.'</div>'.'<div class="clear"></div>'.'</div>'.$html_a_cl_all.$html_wrap_cl;
			break;
		case	2:		// 下側
			$html_tag	=	$html_wrap_op.$html_heading.$html_a_op_all.'<div class="lkc-card">'.'<div class="lkc-content"'.($is_preview ? ' data-pz-preview-content' : '').'>'.$html_a_op.$html_thumbnail.$html_title.$html_a_cl.$html_sns_title.$html_url1.$html_date.$html_excerpt.$html_moretag.'</div>'.$html_info.'<div class="clear">'.'</div>'.'</div>'.$html_a_cl_all.$html_wrap_cl;
			break;
		case	3:		// タイトルの上側
			$html_tag	=	$html_wrap_op.$html_heading.$html_a_op_all.'<div class="lkc-card">'.'<div class="lkc-content"'.($is_preview ? ' data-pz-preview-content' : '').'>'.$html_a_op.$html_thumbnail.$html_title.$html_a_cl.$html_sns_title.$html_url1.$html_date.$html_excerpt.$html_moretag.'</div>'.'<div class="clear">'.'</div>'.'</div>'.$html_a_cl_all.$html_wrap_cl;
			break;
		default:
			$html_tag	=	$html_wrap_op.$html_heading.$html_a_op_all.'<div class="lkc-card">'.($is_preview ? $html_info : '').'<div class="lkc-content"'.($is_preview ? ' data-pz-preview-content' : '').'>'.$html_a_op.$html_thumbnail.$html_title.$html_a_cl.$html_sns_title.$html_url1.$html_date.$html_excerpt.$html_moretag.'</div>'.'<div class="clear">'.'</div>'.'</div>'.$html_a_cl_all.$html_wrap_cl;
		}
		$enclose_tag	=	isset($this->options['enclose-tag'] ) ? strtolower($this->options['enclose-tag'] ) : (!empty($this->options['blockquote'] ) ? 'blockquote' : 'div');
		if (intval($this->options['info-position'] ?? 0 ) === 3 && $html_info && strpos($html_tag, 'lkc-info' ) === false ) {
			$html_tag = str_replace($html_title, $html_info.$html_title, $html_tag);
		}
		if	(!in_array($enclose_tag, array('div', 'blockquote', 'figure', 'article', 'section', 'nav', 'aside' ), true ) ) {
			$enclose_tag	=	'div';
		}
		$enclose_tag	=	tag_escape($enclose_tag ) ?: 'div';
		$html_quickmenu_icon	=	'';
		$html_data_id			=	'';
		static $html_anchor_ids	=	array();
		if	(!$is_preview && !empty($data_id ) ) {
			$html_data_id		=	$html_click_data;
			if	(empty($html_anchor_ids[$data_id] ) ) {
				$html_data_id		=	' id="pz-lkc-'.esc_attr($data_id ).'"'.$html_data_id;
				$html_anchor_ids[$data_id]	=	true;
			}
			if	(!empty($this->options['flg-quickmenu'] ) && is_user_logged_in() && current_user_can('manage_options' ) ) {
				$html_quickmenu_icon	=	'<span class="pz-lkc-quickmenu-indicator dashicons dashicons-ellipsis" aria-hidden="true"></span>';
			}
		}
		$html_tag		=	'<'.$enclose_tag.' class="'.esc_attr($class_id ).'"'.$html_data_id.(!empty($atts['pz-pending'] ) ? ' data-pz-lkc-pending="1"' : '').($is_preview && !empty($atts['preview-card'] ) ? ' data-pz-preview-card="'.esc_attr($atts['preview-card'] ).'"' : '').'>'.$html_tag.$html_quickmenu_icon.'</'.$enclose_tag.'>';

		return	$html_tag;
	}

	// URLのエンコード（DB格納用のURL作成）
	private	function	pz_GetClickToken($card_id, $post_id ) {
		$payload	= absint($card_id ).'|'.absint($post_id );
		return	hash_hmac('sha256', $payload, wp_salt('auth') );
	}

	private	function	pz_EncodeURL($url = null, $sanitize = false ) {
		if	($this->options['survey-mode'] ) { $this->pz_OutputLog(__FUNCTION__, '$url='.$url ); }

		// URLのサニタイズ
		if	($sanitize ) {
			$url	=	$this->pz_SanitizeURL($url );
		}

		// URL指定なし
		if	(!$url ) {
			return	null;
		}

		// 日本語がある
		if	(!preg_match("/^[\x20-\x7E]+$/", $url ) ) {
			// 国際ドメイン対応（日本語ドメイン対応）
			$url_info			=	$this->pz_GetURLInfo($url );
			if	(function_exists('idn_to_utf8' ) && !preg_match("/^[\x20-\x7E]+$/", $url_info['domain'] ) ) {
				$domain_before	=	(isset($url_info['scheme'] ) ? $url_info['scheme'] : null).'://'.(isset($url_info['domain'] ) ? $url_info['domain'] : null);
				$domain_after	=	(isset($url_info['scheme'] ) ? $url_info['scheme'] : null).'://'.(isset($url_info['domain'] ) ? idn_to_ascii($url_info['domain'], 0, INTL_IDNA_VARIANT_UTS46 ) : null);
				$url			=	$domain_after.mb_substr($url, mb_strlen($domain_before ) );		// URLのスキーム＋ドメイン部分だけ入れ替え
			}

			// 日本語がある
			if	(!preg_match("/^[\x20-\x7E]+$/", $url ) ) {
				$url	=	$this->pz_EncodeURI($url );			// エンティティ化
			}
		}

		// エンコードしたURLを返却
		return		$url;
	}

	// URLのデコード（表示用URL作成）
	private	function	pz_DecodeURL($url = null, $sanitize = false ) {
		if	($this->options['survey-mode'] ) { $this->pz_OutputLog(__FUNCTION__, '$url='.$url ); }

		// URLのサニタイズ
		if	($sanitize ) {
			$url	=	$this->pz_SanitizeURL($url );
		}

		// URL指定なし
		if	(!$url ) {
			return	null;
		}

		// 国際ドメイン対応（日本語ドメイン対応）
		$url_info			=	$this->pz_GetURLInfo($url );
		if	(function_exists('idn_to_utf8' ) && substr($url_info['domain'], 0, 4 ) == 'xn--' ) {
			$domain_before	=	(isset($url_info['scheme'] ) ? $url_info['scheme'] : null).'://'.(isset($url_info['domain'] ) ? $url_info['domain'] : null);
			$domain_after	=	(isset($url_info['scheme'] ) ? $url_info['scheme'] : null).'://'.(isset($url_info['domain'] ) ? ((function_exists('idn_to_utf8' ) && defined('INTL_IDNA_VARIANT_UTS46' ) ) ? idn_to_utf8($url_info['domain'], 0, INTL_IDNA_VARIANT_UTS46 ) : $url_info['domain']) : null);
			$url			=	$domain_after.mb_substr($url, mb_strlen($domain_before ) );		// URLのスキーム＋ドメイン部分だけ入れ替え
		}

		// エンティティ文字のデコード
		do {
			$url			=	rawurldecode($url );
		} while (mb_strpos($url, '%25' ) !== false );	// %25 = % が残っていたら、再度デコード

		// 半角空白があったらエンティティ化（エンコード）
		$url				=	str_replace(' ', '%20', $url );
		$url				=	str_replace("'", '%27', $url );

		// HTMLタグをエスケープ
		$url				=	htmlspecialchars($url );

		// デコードしたURLを返却
		return		$url;
	}

	// URLのサニタイズ
	private	function	pz_SanitizeURL($url = null ) {
		if	($this->options['survey-mode'] ) { $this->pz_OutputLog(__FUNCTION__, '$url='.$url ); }

		// URL指定なし
		if	(!$url ) {
			return	null;
		}

		// Aタグがあったら最初にあるAタグのhrefを持ってくる
		//if	(preg_match('/<a .*href\s*=\s*[\'"]?([^ \'"<>$]+)/sui', $url, $m ) ) {
		//	$url	=	$m[1];
		//}

		// 前後のクォート文字を除去する
		$url	=	preg_replace('/^[\'"‘’“”″]+|[\'"‘’“”″]+$/', '', $url );
		for	($i = 0; $i < 5; $i++ ) {
			$url_decoded	=	html_entity_decode($url, ENT_QUOTES, get_bloginfo('charset' ) ?: 'UTF-8' );
			if	($url_decoded === $url ) {
				break;
			}
			$url	=	$url_decoded;
		}
		$url	=	str_replace(' ', '+', $url );

		// 最初にあるURLっぽいのを持ってくる
		if	(preg_match('/(https?:\/\/[^\s<>]+)/sui', $url, $m ) ) {
			$url	=	$m[1];
		}

		// エスケープ
		$url	=	str_replace(array(' ', "'" ), array('+', '%27' ), $url );
		$url	=	esc_url($url );

		// 最後のスラッシュの除去
		switch	($this->options['trail-slash'] ) {
		case	1:							// URLがドメイン名だけの場合、最後のスラッシュを除外する
			$url_info			=	$this->pz_GetURLInfo($url );
			if	(!isset($url_info['path'] ) || $url_info['path'] == '/' ) {
				$url	=	rtrim($url, '/' );
			}
			break;
		case	2:							// 常に最後のスラッシュを除外する
			$url	=	rtrim($url, '/' );
			break;
		}

		// エンティティ文字がある
		if	(mb_strpos($url, '%' ) !== false) {
			$url	=	$this->pz_DecodeURL($url ,false );
		}

		// 日本語がある
		if	(!preg_match('/^[\x20-\x7E]+$/', $url ) ) {
			$url		=	$this->pz_EncodeURL($url, false);
		}

		// サニタイズしたURLを返却する（エンティティ化済）
		return	$url;
	}

	// 相対パスをURLにする
	private	function	pz_RelToURL($base_url = null, $rel_path = null ) {
		if	($this->options['survey-mode'] ) { $this->pz_OutputLog(__FUNCTION__, '$base_url='.esc_html($base_url ).' $rel_path="'.esc_html($rel_path ) ); }

		if	(!$base_url || !$rel_path ) {
			return	$rel_path;
		}

		// ベースURLをパース
		$base_url	=	$this->Pz_SanitizeURL($base_url );					// 念のためサニタイズ
		$info_base	=	$this->Pz_GetURLInfo($base_url );
		$info_rel	=	$this->Pz_GetURLInfo($rel_path );
		$base_domain_url	=	$info_base['domain_url'];
		if	(!empty($info_base['port'] ) ) {
			$base_domain_url	.=	':'.$info_base['port'];
		}

		// 絶対パスだった場合（スキームあり）
		if	($info_rel['scheme'] ) {
			$return_url	=	$rel_path;
			return			$return_url;
		}

		// 絶対パスだった場合（スキーム省略）
		if	(substr($rel_path, 0, 2 )	==	'//' ) {
			$return_url	=	$info_base['scheme'].':'.$rel_path;
			return			$return_url;
		}

		// ルート指定
		if	(substr($rel_path, 0, 1 )	==	'/' ) {
			$return_url	=	$base_domain_url.$rel_path;
			return			$return_url;
		}

		// ベースURLのディレクトリを基準に相対パスを解決
		$base_path	=	$info_base['path'] ?? '/';
		if	(!$base_path ) {
			$base_path	=	'/';
		}
		if	(substr($base_path, -1 ) <> '/' ) {
			$base_path	=	preg_replace('/\/[^\/]*$/', '/', $base_path );
		}
		$rel_query		=	'';
		$rel_fragment	=	'';
		$rel_path_only	=	$rel_path;
		$fragment_pos	=	strpos($rel_path_only, '#' );
		if	($fragment_pos !== false ) {
			$rel_fragment	=	substr($rel_path_only, $fragment_pos );
			$rel_path_only	=	substr($rel_path_only, 0, $fragment_pos );
		}
		$query_pos		=	strpos($rel_path_only, '?' );
		if	($query_pos !== false ) {
			$rel_query		=	substr($rel_path_only, $query_pos );
			$rel_path_only	=	substr($rel_path_only, 0, $query_pos );
		}
		if	($rel_path_only === '' ) {
			$target_path	=	$info_base['path'] ?? '/';
		} else {
			$target_path	=	$base_path.$rel_path_only;
		}
		$path_parts		=	explode('/', $target_path );
		$resolved_parts	=	array();
		foreach	($path_parts as $path_part ) {
			if	($path_part === '' || $path_part === '.' ) {
				continue;
			}
			if	($path_part === '..' ) {
				array_pop($resolved_parts );
				continue;
			}
			$resolved_parts[]	=	$path_part;
		}
		$return_url		=	$base_domain_url.'/'.implode('/', $resolved_parts ).$rel_query.$rel_fragment;
		return				$return_url;
	}

	// 日本語URLをHTMLエンコードする
	private	function	pz_EncodeURI($url ) {
		if	($this->options['survey-mode'] ) { $this->pz_OutputLog(__FUNCTION__, 'url='.$url ); }

		$pattern	=
			array(
				// UnEscaped
				'%2D'=>'-', '%5F'=>'_', '%2E'=>'.', '%21'=>'!', '%25'=>'%', '%7E'=>'~', '%2A'=>'*', '%28'=>'(', '%29'=>')',
				// Reserved
				'%3B'=>';', '%2C'=>',', '%2F'=>'/', '%3F'=>'?', '%3A'=>':', '%40'=>'@', '%26'=>'&', '%3D'=>'=', '%2B'=>'+', '%24'=>'$',
				// Score
				'%23'=>'#'
			);
		$url		=	rawurlencode($url );
		$url		=	strtr($url, $pattern);
		return		$url;
	}

	// ソーシャルカウント取得
	private	function	pz_RenewSNSCount($data ) {
		if	($this->options['survey-mode'] ) { $this->pz_OutputLog(__FUNCTION__, '$data='.print_r($data, true ) ); }

		// ソーシャルカウントを表示しない設定の場合は終了
		if	(!$this->options['sns-position'] ) {
			return	null;
		}
		if	(!isset($data ) || !is_array($data ) ) {
			return	null;
		}

		$data	=	$this->pz_GetCache($data );
		if	(!isset($data ) || !is_array($data ) ) {
			return	null;
		}

		// ソーシャルカウント
		$sns_renew	= false;
		$update_cnt	= false;
		$hatena_response_count	= null;

		// タイムオーバー
		$opt	=	array('timeout' => 30 );

		// 保存期間満了でソーシャルカウントをリセット
		$update_succeeded	= isset($data['update_result'] ) && (intval($data['update_result'] ) === 0 || (intval($data['update_result'] ) >= 100 && intval($data['update_result'] ) < 400 ) );
		if	($this->now > $data['sns_nexttime'] && $update_succeeded ) {
			$sns_renew		=	true;
		}

		// エンコードURL
		$url_raw	=	rawurlencode($data['url'] );

		// Twitter Digitminimiのcount.jsoonを使用
		//if	(isset($this->options['sns-tw'] ) && !is_null($this->options['sns-tw'] ) ) {
		//	$count_before	=	$data['sns_twitter'] ?? null;
		//	if	($sns_renew || is_null($count_before ) ) {
		//		$result	=	wp_safe_remote_get('https://jsoon.digitiminimi.com/twitter/count.json?url=' .$url_raw, $opt );
		//		if	(isset($result ) && !is_wp_error($result ) && $result['response']['code'] == 200 ) {
		//			$json 	=	json_decode($result['body'] );
		//			$count	=	intval($json->count );
		//			if	(is_null($count_before ) || $count > $count_before ) {
		//				$data['sns_twitter']	=	$count;
		//				$update_cnt	=	true;
		//			}
		//		}
		//	}
		//}

		// facebook
		//if	(isset($this->options['sns-fb'] ) && !is_null($this->options['sns-fb'] ) ) {
		//	$count_before	=	$data['sns_facebook'] ?? null;
		//	if	($sns_renew || is_null($count_before ) ) {
		//		$result	=	wp_safe_remote_get('https://graph.facebook.com?fields=og_object{engagement}&id=' .$url_raw, $opt );
		//		if	(isset($result ) && !is_wp_error($result ) && $result['response']['code'] == 200 ) {
		//			$json 	=	json_decode($result['body'] );
		//			$count	=	intval($json->{'og_object'}->{'engagement'}->{'count'});
		//			if	(is_null($count_before ) || $count > $count_before ) {
		//				$data['sns_facebook']	=	$count;
		//				$update_cnt	=	true;
		//			}
		//		}
		//	}
		//}

		// はてなブックマーク
		if	(isset($this->options['sns-hb'] ) && !is_null($this->options['sns-hb'] ) ) {
			$count_before	=	$data['sns_hatena'] ?? null;
			if	($sns_renew || is_null($count_before ) ) {
				$result	=	wp_safe_remote_get('http://api.b.st-hatena.com/entry.count?url=' .$url_raw, $opt );
				if	(isset($result ) && !is_wp_error($result ) && $result['response']['code'] == 200 ) {
					$count	=	intval($result['body'] );
					$hatena_response_count	=	$count;
					if	(is_null($count_before ) || $count > $count_before ) {
						$data['sns_hatena']	=	$count;
						$update_cnt	=	$count > intval($count_before );
					}
				}
			}
		}

		// 登録してから一週間までは毎日、それ以降は週一回更新（取得が固まらないようにランダム時間付与）
		$data['sns_time']			=	$this->now;
		if	($update_cnt ) {
			$data['sns_nexttime']	=	$this->now + DAY_IN_SECONDS;
		} else {
			$data['sns_nexttime']	=	$this->now + MONTH_IN_SECONDS;
		}
		// MINUTE_IN_SECONDS	= 60
		// HOUR_IN_SECONDS		= 60	*	MINUTE_IN_SECONDS	= 3600
		// DAY_IN_SECONDS		= 24	*	HOUR_IN_SECONDS		= 86400
		// WEEK_IN_SECONDS		= 7		*	DAY_IN_SECONDS		= 604800
		// YEAR_IN_SECONDS		= 365	*	DAY_IN_SECONDS

		// DBの宣言
		global	$wpdb;

		// DB更新
		$result	=	$wpdb->update(
			$this->db_card,
			array(
				'sns_hatena'	=>	$data['sns_hatena'],
				'sns_time'		=>	$data['sns_time'],
				'sns_nexttime'	=>	$data['sns_nexttime'],
				'update_time'	=>	$data['update_time'],
			),
			array('id' => intval($data['id'] ) ),
			array('%d', '%d', '%d', '%d' ),
			array('%d' )
		);
		$this->request_cache	=	array();
		$data['sns_hatena_response']	=	$hatena_response_count;
		$data['sns_count_increased']	=	$update_cnt;

		return	$data;
	}

	// キャッシュデータを取得
	private	function	pz_GetCache($data ) {
		if	($this->options['survey-mode'] ) { $this->pz_OutputLog(__FUNCTION__, '$data='.print_r($data, true ) ); }

		if	(!isset($data ) || !is_array($data ) ) {
			return	null;
		}

		global	$wpdb;
		if	(!empty($data['url'] ) ) {
			$url		=	$this->pz_EncodeURL($data['url'], true );
			$cache_key	=	'url:'.md5($url );
			if	(array_key_exists($cache_key, $this->request_cache ) ) {
				return	$this->request_cache[$cache_key];
			}
			$data		=	$wpdb->get_row($wpdb->prepare("SELECT * FROM $this->db_card WHERE url_key=%s AND url=%s", md5($url ), $url ) );
		} elseif	(isset($data['id'] ) && !is_null($data['id'] ) ) {
			$data_id	=	intval($data['id'] );
			$cache_key	=	'id:'.$data_id;
			if	(array_key_exists($cache_key, $this->request_cache ) ) {
				return	$this->request_cache[$cache_key];
			}
			$data		=	$wpdb->get_row($wpdb->prepare("SELECT * FROM $this->db_card WHERE id=%d", $data_id ) );
		} else {
			return	null;
		}
		if	($wpdb->last_error ) {			// DBエラーのとき、初期化する
			$this->hook_activate();
		}
		if	(is_wp_error($data ) ) {
			return	null;
		}
		$data	=	(array) $data;
		$this->request_cache[$cache_key]	=	$data;
		if	(!empty($data['id'] ) ) {
			$this->request_cache['id:'.intval($data['id'] )]	=	$data;
		}
		return (array) $data;				// Arrayに直して返す
	}

	// キャッシュデータを保存
	private	function	pz_SetCache($data ) {
		if	($this->options['survey-mode'] ) { $this->pz_OutputLog(__FUNCTION__, '$data='.print_r($data, true ) ); }

		// 項目が空っぽ
		if	(!isset($data ) || !is_array($data ) ) {
			return	null;
		}
		if	(!isset($data['url'] ) || !$data['url'] ) {
			return	null;
		}
		$is_pending	=	array_key_exists('regist_time', $data ) && (is_null($data['regist_time'] ) || intval($data['regist_time'] ) === 0 )
			&& array_key_exists('charset', $data ) && is_null($data['charset'] );

		// リンク先URL
		$url					=	$this->pz_EncodeURL($data['url'] ,true );
		if	(!$url ) {
			return	null;
		}

		// URL戻す
		$data['url']			=	$url;
		$data['url_key']		=	md5($url );

		// ID
		if	(isset($data['id'] ) && !$data['id'] ) {
			unset($data['id']);
		}

		// スキームとドメイン
		$url_m = parse_url($url);
		$data['scheme']				=	isset($url_m['scheme'])			?	$url_m['scheme']		: null;
		$data['domain']				=	isset($url_m['host'])			?	$url_m['host']			: null;

		// 記事内容等
		$data['url_redir']			=	isset($data['url_redir'] )		? $data['url_redir']		: null;			// リンク先：リダイレクト
		$data['site_name']			=	isset($data['site_name'] )		? $data['site_name']		: null;			// リンク先：サイト名称
		$data['title']				=	isset($data['title'] )			? $data['title']			: null;			// リンク先：タイトル
		$data['excerpt']			=	isset($data['excerpt'] )		? $data['excerpt']			: null;			// リンク先：抜粋文
		$data['thumbnail']			=	isset($data['thumbnail'] )		? $data['thumbnail']		: null;			// リンク先：サムネイルURL
		$data['favicon']			=	isset($data['favicon'] )		? $data['favicon']			: null;			// リンク先：サイトアイコンURL
		$has_charset				=	array_key_exists('charset', $data ) && !is_null($data['charset'] );
		$data['charset']			=	isset($data['charset'] )		? $data['charset']			: 'Unknown';	// リンク先：文字コード
		foreach	(array('regist_result', 'update_result', 'alive_result' ) as $result_key ) {
			if	(!array_key_exists($result_key, $data ) || $data[$result_key] === '' || is_null($data[$result_key] ) || intval($data[$result_key] ) < 0 ) {
				$data[$result_key]	= null;
			} else {
				$data[$result_key]	= intval($data[$result_key] );
			}
		}
		$data['no_failure']			=	isset($data['no_failure'] )		? $data['no_failure']		: 0 ;			// 結果コードがエラーでも成功と見なす

		// 登録時情報
		if ((!isset($data['regist_time'] ) || intval($data['regist_time'] ) === 0 ) && $has_charset ) {
			$data['regist_title']	=	$data['title'];
			$data['regist_excerpt']	=	$data['excerpt'];
			$data['regist_charset']	=	$data['charset'];
			$data['regist_result']	=	$data['update_result'];
			$data['regist_time']	=	$this->now;
		}
		// 日本語項目のエンティティ文字を出コード
		$data['title']				=	html_entity_decode($data['title'] );										// リンク先：タイトル
		$data['excerpt']			=	html_entity_decode($data['excerpt'] );										// リンク先：抜粋文
		if (isset($data['regist_title'] ) ) {
			$data['regist_title']	=	html_entity_decode($data['regist_title'] );									// 登録時：タイトル
		}
		if (isset($data['regist_excerpt'] ) ) {
			$data['regist_excerpt']	=	html_entity_decode($data['regist_excerpt'] );								// 登録時：抜粋文
		}

		// 生存確認
		if	(!isset($data['alive_time'] ) || !$data['alive_time'] ) {
			$data['alive_result']	=	$data['update_result'];
			$data['alive_time']		=	$this->now;
			$data['alive_nexttime']	=	$this->now + WEEK_IN_SECONDS * 4 + rand(0, DAY_IN_SECONDS );
		}

		// SNS関連
		foreach	(array('sns_twitter', 'sns_facebook', 'sns_hatena' ) as $sns_count_key ) {
			$sns_count_value	= $data[$sns_count_key] ?? null;
			$data[$sns_count_key]	= ($sns_count_value === null || $sns_count_value === '' || intval($sns_count_value ) < 0 )
				? null
				: intval($sns_count_value );
		}
		$data['sns_nexttime']		=	isset($data['sns_nexttime'] )	? $data['sns_nexttime']		: $this->now;	// SNS：次回取得日時
		$data['sns_time']			=	isset($data['sns_time'] )		? $data['sns_time']			: $this->now;	// SNS：最終取得日時

		// 使われている記事ID
		global	$wpdb;
		$use_post_ids	=	array();
		for	($use_post_index = 1; $use_post_index <= 6; $use_post_index++ ) {
			$use_post_key	=	'use_post_id'.$use_post_index;
			if	(!empty($data[$use_post_key] ) ) {
				$use_post_ids[]	=	intval($data[$use_post_key] );
			}
		}
		$post_id_t	=	$is_pending
			? array()
			: $wpdb->get_col($wpdb->prepare(
				"SELECT ID FROM {$wpdb->posts} WHERE post_type = 'post' AND post_content LIKE %s ORDER BY ID ASC",
				'%'.$wpdb->esc_like($data['url'] ).'%'
			) );
		$use_post_ids	=	array_values(array_unique(array_filter(array_map('intval', array_merge($use_post_ids, $post_id_t ) ) ) ) );
		for	($use_post_index = 1; $use_post_index <= 6; $use_post_index++ ) {
			$data['use_post_id'.$use_post_index]	=	$use_post_ids[$use_post_index - 1] ?? null;
		}

		// 最終更新日時
		$data['update_time']		=	$this->now;

		// DB更新キー取得
		if	(!$is_pending && (!isset($data['id'] ) || !$data['id']) ) {
			$now	=	$this->pz_GetCache(array('url' => $data['url'] ) );
			if	(isset($now['id'] ) ) {
				$data['id']	=	$now['id'];
			}
		}

		// 桁数チェック
		global	$wpdb;
		static	$columns_by_table	=	array();
		if	(!array_key_exists($this->db_card, $columns_by_table ) ) {
			$columns_by_table[$this->db_card]	=	$wpdb->get_results("DESCRIBE $this->db_card", ARRAY_A );
		}
		$columns	=	$columns_by_table[$this->db_card];
		if	(isset($columns ) && is_array($columns ) ) {
			foreach	($columns as $column ) {
				$field	=	$column['Field'] ?? null;
				$type	=	$column['Type']  ?? null;
				if	(!$field || !$type || !array_key_exists($field, $data ) || is_null($data[$field] ) ) {
					continue;
				}
				if	(!preg_match('/^(?:var)?char\((\d+)\)/i', $type, $m ) ) {
					continue;
				}
				$max_length		=	intval($m[1] );
				$value			=	(string) $data[$field];
				$value_length	=	function_exists('mb_strlen' ) ? mb_strlen($value ) : strlen($value );
				if	($max_length > 0 && $value_length > $max_length ) {
					$data[$field]	=	function_exists('mb_substr' ) ? mb_substr($value, 0, $max_length ) : substr($value, 0, $max_length );
				}
			}
		}

		// DB更新
		$result		=	null;
		$insert_attempted	=	false;
		if	(isset($data['id'] ) && $data['id'] ) {
			$result	=	$wpdb->update($this->db_card, $data, array('id' => $data['id'] ) );
		} else {
			$insert_attempted	=	true;
			$result	=	$wpdb->insert($this->db_card, $data );
			if ($is_pending && $result !== false && $wpdb->insert_id ) {
				$data['id']	=	intval($wpdb->insert_id );
				$this->request_cache	=	array();
				return $data;
			}
		}

		// DB更新失敗の場合、挿入
		if	($result === false ) {
			// 同時処理ですでに同じURLが追加されていた場合は、そのレコードを使用する
			if	($insert_attempted ) {
				$this->request_cache	=	array();
				$existing	=	$this->pz_GetCache(array('url' => $data['url'] ) );
				if	(!empty($existing['id'] ) ) {
					return	$existing;
				}
			}

			unset($data['id'] );
			// DB挿入失敗の場合、日本語項目（サイト名）をクリアして挿入
			unset($data['site_name'] );
			$result =	$wpdb->insert($this->db_card, $data );
			// DB挿入失敗の場合、日本語項目（概要文）をクリアして挿入
			if	($result === false ) {
				unset($data['excerpt'] );
				$result =	$wpdb->insert($this->db_card, $data );
				// DB挿入失敗の場合、日本語項目（タイトル）をクリアして挿入
				if	($result === false ) {
					unset($data['title'] );
					$result =	$wpdb->insert($this->db_card, $data );
					// DB挿入失敗の場合、諦める
					if	($result === false ) {
						return	null;
					}
				}
			}
		}
		$this->request_cache	=	array();
		return	$this->pz_GetCache($data );
	}

	// キャッシュデータを削除
	private	function	pz_DelCache($data ) {
		if	($this->options['survey-mode'] ) { $this->pz_OutputLog(__FUNCTION__, '$data='.print_r($data, true ) ); }

		global	$wpdb;
		$this->request_cache	=	array();
		if	(!isset($data ) || !is_array($data ) ) {
			return	null;
		}
		if	(isset($data['id'] ) ) {
			$card_id	=	intval($data['id'] );
			$result		=	$wpdb->delete($this->db_card, array('id' => $card_id ), array('%d' ) );
			if	($result ) {
				$wpdb->delete($this->db_click, array('card_id' => $card_id ), array('%d' ) );
				return	true;
			}
		}
		if	(isset($data['url'] ) ) {
			$url		=	$this->pz_EncodeURL($data['url'], true );
			$card_id	=	intval($wpdb->get_var($wpdb->prepare("SELECT id FROM $this->db_card WHERE url = %s LIMIT 1", $url ) ) );
			$result		=	$wpdb->delete($this->db_card, array('url' => $url ), array('%s' ) );
			if	($result ) {
				if	($card_id ) {
					$wpdb->delete($this->db_click, array('card_id' => $card_id ), array('%d' ) );
				}
				return	true;
			}
		}
		return	null;
	}

	// 内部リンク・記事情報取得
	private	function	pz_GetPost($data, $allow_non_public = false ) {
		if	($this->options['survey-mode'] ) { $this->pz_OutputLog(__FUNCTION__, '$data='.print_r($data, true ) ); }

		// 初期化
		$url			=	'';
		$link_post_id	=	0;
		$site_name		=	'';
		$domain_url		=	'';
		$domain			=	'';
		$title			=	'';
		$excerpt		=	'';
		$thumbnail		=	'';
		$siteicon		=	'';
		$post_date		=	0;
		$post_modified	=	0;

		// サイト名取得
		$site_name		=	get_bloginfo('name' );

		// ドメイン名
		$domain			=	$this->domain;
		$domain_url		=	$this->domain_url;

		// サイトアイコン
		if	(function_exists('has_site_icon' ) && has_site_icon() ) {
			$siteicon			=	get_site_icon_url(16, null, 0 );
		}

		// 記事内容
		$url					=	$data['url'];
		$link_post_id			=	url_to_postid($url );					// リンク先の記事IDを取得
		// 記事IDが取得できた場合
		if	($link_post_id ) {
			$post				=	get_post($link_post_id );				// 記事情報
			if	(!$post || ((!$allow_non_public || !empty($this->options['flg-public-only'] ) ) && !$this->pz_IsPostPubliclyViewable($post ) ) ) {
				$data['title']			=	'';
				$data['excerpt']		=	'';
				$data['thumbnail']		=	'';
				$data['favicon']		=	'';
				$data['post_id']		=	0;
				$data['post_date']		=	0;
				$data['post_modified']	=	0;
				$data['update_result']	=	403;
				$data['alive_result']	=	403;
				return	$data;
			}
			// 記事IDが取得できた場合
			$update_result		=	200;									// 外部取得と同じコードをセット
			$title				=	$post->post_title;						// 記事タイトル
			$excerpt			=	$post->post_content;					// 記事内容から抜粋

			// 「抜粋」優先
			if	($this->options['in-get-from'] == 1 && $post->post_excerpt ) {	// 記事取得方法：「抜粋文」があった場合、優先する
				$excerpt		=	$post->post_excerpt;					// 抜粋文
			}

			// 「カスタムフィールド」優先
			if	($this->options['in-get-from'] == 3 ) {						// 記事取得方法：「カスタムフィールド」があった場合、優先する
				$meta_title		=	get_post_meta($link_post_id, $this->options['in-field-title'] );
				if	(array($meta_title ) && array_key_exists(0, $meta_title ) ) {
					$title		=	$meta_title[0];
				}
				$meta_excerpt	=	get_post_meta($link_post_id, $this->options['in-field-excerpt'] );
				if	(array($meta_excerpt ) && array_key_exists(0, $meta_excerpt ) ) {
					$excerpt	=	$meta_excerpt[0];
				}
			}

			$post_date			=	get_post_timestamp($post, 'date' );		// 投稿日
			$post_modified		=	get_post_timestamp($post, 'modified' );	// 更新日
			$thumbnail_id		=	get_post_thumbnail_id($link_post_id );	// サムネイル
			if	($thumbnail_id ) {
				$thumbnail_size		=	$this->options['in-thumbnail-size'] ? $this->options['in-thumbnail-size'] : 'thumbnail' ;
				$attach				=	wp_get_attachment_image_src($thumbnail_id, $thumbnail_size, true );
				if	(isset($attach ) && count($attach ) > 3 && isset($attach[0] ) ) {
					$thumbnail		=	$attach[0];
					if	(preg_match('/.*(\/\/.*)/', $thumbnail, $m ) ) {	// スキームを外す
						$thumbnail	=	$m[1];
					}
				}
			}
		} else {
			// 記事IDが取得できなかった場合
			$update_result		=	404;
			$title				=	get_bloginfo('name' );
			$excerpt			=	get_bloginfo('description' );
			$post_date			=	0;
			$post_modified		=	0;
			$thumbnail			=	null;

			if	(rtrim($this->pz_DecodeURL($url ), '/' ) == rtrim($this->pz_DecodeURL(home_url() ), '/' ) ) {
				// トップページの場合
				$update_result		=	200;
			} else {
				// カテゴリ ページのディレクトリ名を取得
				$default_cat_id		=	get_option('default_category' );
				$default_cat_link	=	rtrim(get_category_link($default_cat_id ), '/' );
				$default_cat_slug	=	rtrim(get_category( $default_cat_id )->slug, '/' );

				// カテゴリーページかどうかチェック
				$url_decoded		=	$this->pz_DecodeURL($url ,false );
				$cat_base_url		=	rtrim(mb_substr($default_cat_link, 0, mb_strlen($default_cat_link ) - mb_strlen($default_cat_slug ) ), '/' );	// ベース部分だけ抽出
				if (mb_substr($url, 0, mb_strlen($cat_base_url ) ).'/' == $cat_base_url.'/' ) {
					$cat_slug		=	mb_substr($url_decoded, mb_strlen($cat_base_url ) + 1 );
					$cat_last_slug	=	basename($cat_slug );
					$cat_data		=	get_category_by_slug($cat_last_slug );
					if	($cat_data ) {
						$cat_count		=	($cat_data->count - 0 );
						$title			=	__('Category', 'pz-linkcard' ).' '.__('‘', 'pz-linkcard' ).$cat_data->name.__('’', 'pz-linkcard' );
						$excerpt		=	__('(', 'pz-linkcard' ).__('Count', 'pz-linkcard' ).':'.($cat_data->count - 0 ).__(')', 'pz-linkcard' ).' '.$cat_data->description;
						$update_result	=	200;
					} else {
						$title			=	__('Category', 'pz-linkcard' ).' '.__('‘', 'pz-linkcard' ).$cat_slug.__('’', 'pz-linkcard' );
						$excerpt		=	__('Not Found', 'pz-linkcard' );
						$update_result	=	403;
					}
				} else {
					// タグ ページの処理
					$cat_dir			=	get_option('tag_base' );
					$cat_url			=	$this->domain_url.'/'.($cat_dir ? $cat_dir : 'tag' ).'/';
					$cat_len			=	mb_strlen($cat_url );
					if	(mb_substr($url, 0, $cat_len ) == $cat_url ) {
						$cat_slug		=	mb_substr($url, $cat_len );
						$cat_data		=	get_tags(array('slug' => $cat_slug ) );
						if	($cat_data ) {
							$title			=	__('Tag', 'pz-linkcard' ).' '.__('‘', 'pz-linkcard' ).$cat_data[0]->name.__('’', 'pz-linkcard' );
							$excerpt		=	__('(', 'pz-linkcard' ).__('Count', 'pz-linkcard' ).':'.($cat_data[0]->count - 0 ).__(')', 'pz-linkcard' ).' '.$cat_data[0]->description;
							$update_result	=	200;
						} else {
							$title			=	__('Tag', 'pz-linkcard' ).' '.__('‘', 'pz-linkcard' ).rawurldecode($cat_slug ).__('’', 'pz-linkcard' );
							$excerpt		=	__('Not Found', 'pz-linkcard' );
							$update_result	=	403;
						}
					} else {
						if	($this->options['in-get-url'] ) {
							$result			=	$this->pz_GetRemote($data );		// 外部サイトとして読み込み
							if	(isset($result ) && is_array($result ) && isset($result['url'] ) ) {
								$data		=	$result;
								$result		=	$this->pz_SetCache($data );
							}
							return			$result;
						}
					}
				}
			}
		}

		// タイトル整形
		if				($str	=	$title ) {										// 代入しながら判定
			if			($str	=	strip_tags($str ) ) {							// HTMLタグ除去
				if		($str	=	esc_html($str ) ) {								// HTMLエスケープ
					if	($str	=	str_replace(array("\r", "\n"), '', $str ) ) {	// 改行を除去
						$str	=	mb_strimwidth($str, 0, 200, '...' );			// 200文字制限
					}
				}
			}
			$title			=	$str;
		}

		// 抜粋文整形
		if						($str	=	$excerpt ) {										// 代入しながら判定
			if					($str	=	strip_tags($str ) ) {								// HTMLタグ除去
				if				($str	=	esc_html($str ) ) {									// HTMLエスケープ
					if			($str	=	str_replace(array("\r", "\n"), '', $str ) ) {		// 改行を除去
						if		($str	=	preg_replace('/<!--more-->.+/is', '', $str ) ) {	// moreタグ以降削除
							if	($str	=	preg_replace('/\[[^]]*\]/', '', $str ) ) {			// ショートコードすべて除去
								$str	=	mb_strimwidth($str, 0, 500, '...' );				// 500文字制限
							}
						}
					}
				}
			}
			$excerpt		=	$str;
		}

		// データセット
		$url_info					=	$this->Pz_GetURLInfo($url );	// URL解析（自サイトチェック）
		$data['scheme']				=	$url_info['scheme'] ?? '';		// スキーム
		$data['domain']				=	$url_info['domain'] ?? '';		// ドメイン名
		$data['site_name']			=	$site_name ?? '';
		$data['title']				=	$title ?? '';
		$data['excerpt']			=	$excerpt ?? '';
		$data['thumbnail']			=	$thumbnail ?? '';
		$data['favicon']			=	$siteicon ?? '';
		$data['charset']			=	'UTF-8';
		$data['update_result']		=	$update_result ?? null;
		$data['alive_result']		=	$update_result ?? null;
		$post_id_t	=	intval(get_the_ID() );
		if	(!isset($data['use_post_id1'] ) && $post_id_t > 0 ) {
			$data['use_post_id1']	=	$post_id_t;
		}
		$data['post_id']			=	$link_post_id;
		$data['post_date']			=	$post_date ?? '';
		$data['post_modified']		=	$post_modified ?? '';
		return	$data;
	}

	private	function	pz_IsPostPubliclyViewable($post ) {
		if	(!empty($post->post_password ) ) {
			return	false;
		}
		if	(function_exists('is_post_publicly_viewable' ) ) {
			return	is_post_publicly_viewable($post );
		}
		$post_type		=	get_post_type_object($post->post_type ?? '' );
		$post_status	=	get_post_status_object($post->post_status ?? '' );
		return	$post_type && !empty($post_type->publicly_queryable ) && $post_status && !empty($post_status->public );
	}

	private	function	pz_IsInternalCardPubliclyViewable($url, $data = array() ) {
		$post_id	=	url_to_postid($url );
		if	(!$post_id && !empty($data['post_id'] ) ) {
			$post_id	=	absint($data['post_id'] );
		}
		if	(!$post_id ) {
			return	true;
		}
		$post	=	get_post($post_id );
		return	$post && $this->pz_IsPostPubliclyViewable($post );
	}

	// 外部リンク・記事情報取得
	private	function	pz_GetRemote($data, $headers_only = false ) {
		if	($this->options['survey-mode'] ) { $this->pz_OutputLog(__FUNCTION__, '$data='.print_r($data, true ) ); }

		return	require('lib/pz-linkcard-get-remote.php' );
	}

	// 内部サイト・外部サイトの判断
	private	function	pz_GetURLInfo($url ) {
		if	($this->options['survey-mode'] ) { $this->pz_OutputLog(__FUNCTION__, '$url='.$url ); }

		// URLの指定なし
		if	(!isset($url ) ) {
			return	null;
		}

		// 内部リンク判定
		$domain_url			=	$this->home_url;									// ドメインURL
		if	(mb_substr($url, 0, mb_strlen($domain_url ) ) == $domain_url ) {
			$is_external	=	false;
			$is_internal	=	true;		// 内部リンク
			$url_m			=	parse_url($domain_url );							// URLパース（ドメイン名などを抽出）
			$scheme			=	isset($url_m['scheme'] ) ? $url_m['scheme']	: null;	// スキーム
			$domain			=	mb_substr($domain_url, mb_strlen($scheme ) + 3 );	// ドメイン
		} else {
			$is_external	=	true;		// 外部リンク
			$is_internal	=	false;
			$url_m			=	parse_url($url );									// URLパース（ドメイン名などを抽出）
			$scheme			=	isset($url_m['scheme'] )	? $url_m['scheme']				: null;		// スキーム
			$scheme_c		=	$scheme						? $scheme.':'					: null;
			$domain			=	isset($url_m['host'] )		? $url_m['host']				: null;		// ドメイン名
			$domain_url		=	isset($url_m['host'] )		? $scheme_c.'//'.$url_m['host']	: null;		// ドメインURL
		}

		// サブディレクトリ型マルチサイト対応（内部リンク判定の場合のみ）
		if	($is_internal && function_exists('is_multisite' ) && is_multisite() && function_exists('is_subdomain_install' ) && !is_subdomain_install() && function_exists('is_main_site' ) && is_main_site() ) {
			$blog_myid		=	get_current_blog_id();
			$blog_id		=	0;
			for ($i = 1; $i <= 1000; $i++ ) {
				$blog_url	=	get_site_url($i );
				if	(!$blog_url ) {
					break;
				}
				if	($i <> $blog_myid ) {
					if (mb_substr($url, 0, mb_strlen($blog_url ) ) == $blog_url ) {
						$domain_url		=	$blog_url;
						$domain			=	preg_replace('/.*\/\/(.*)/', '$1', $blog_url );
						$is_external	=	true;		// 外部リンク
						$is_internal	=	false;
						break;
					}
				}
			}
		}

		// 返り値
		$ret_arr['is_external']	=	$is_external;					// 外部リンク
		$ret_arr['is_internal']	=	$is_internal;					// 内部リンク
		$ret_arr['scheme']		=	$scheme;						// スキーム
		$ret_arr['domain']		=	$domain;						// ドメイン
		$ret_arr['domain_url']	=	$domain_url;					// ドメインURL
		$ret_arr['port']		=	$url_m['port']		??	null;	// ポート
		$ret_arr['user']		=	$url_m['user']		??	null;	// ユーザー名
		$ret_arr['pass']		=	$url_m['pass']		??	null;	// パスワード
		$ret_arr['path']		=	$url_m['path']		??	null;	// パス（ドメイン名以降）
		$ret_arr['query']		=	$url_m['query']		??	null;	// クエスチョンマーク ? 以降
		$ret_arr['fragment']	=	$url_m['fragment']	??	null;	// ハッシュマーク # 以降

		return		$ret_arr;
	}

	// 設定が有効なときだけローカルアドレスを判定
	private	function	pz_ShouldBlockLocalAddress($url ) {
		return	!empty($this->options['flg-local-check'] ) && $this->pz_IsLocalAddress($url );
	}

	private	function	pz_GetUserAgentSelection($value = null, $legacy_flg_agent = null ) {
		if	($value === 'pzlkc' || $value === 'mysite' || $value === '' ) {
			return	$value;
		}
		if	($legacy_flg_agent !== null && !$legacy_flg_agent ) {
			return	'mysite';
		}
		return	'pzlkc';
	}

	private	function	pz_GetUserAgent($selection = null ) {
		global	$wp_version;

		if	($selection === null ) {
			$selection	=	$this->options['user-agent'] ?? 'pzlkc';
		}
		$selection	=	$this->pz_GetUserAgentSelection($selection );
		switch	($selection ) {
		case	'mysite':
			return	'Mozilla/5.0 (WordPress/'.$wp_version.';) '.home_url();
		case	'':
			return	isset($_SERVER['HTTP_USER_AGENT'] ) ? sanitize_text_field(wp_unslash($_SERVER['HTTP_USER_AGENT'] ) ) : '';
		case	'pzlkc':
		default:
			return	'Mozilla/5.0 (WordPress/'.$wp_version.';) '.self::PLUGIN_NAME.'-Crawler/'.PZLKC_PLUGIN_VERSION;
		}
	}

	private	function	pz_RobotsPathMatches($pattern, $path ) {
		if	($pattern === '' ) {
			return	false;
		}
		$regex	=	preg_quote($pattern, '#');
		$regex	=	str_replace('\\*', '.*', $regex );
		if	(substr($regex, -2 ) === '\\$' ) {
			$regex	=	substr($regex, 0, -2 ).'$';
		} else {
			$regex	.=	'.*';
		}
		return	preg_match('#^'.$regex.'#', $path ) === 1;
	}

	private	function	pz_GetRobotsTxt($url, $user_agent ) {
		$scheme	=	wp_parse_url($url, PHP_URL_SCHEME );
		$host	=	wp_parse_url($url, PHP_URL_HOST );
		$port	=	wp_parse_url($url, PHP_URL_PORT );
		if	(!$scheme || !$host ) {
			return	null;
		}

		$robots_url		=	$scheme.'://'.$host.($port ? ':'.intval($port ) : '' ).'/robots.txt';
		$transient_key	=	'pz_lkc_robots_'.md5($robots_url );
		$cached			=	get_transient($transient_key );
		if	(is_array($cached ) && array_key_exists('body', $cached ) ) {
			return	$cached['body'];
		}

		$response	=	wp_safe_remote_get($robots_url, array(
			'timeout'				=>	5,
			'redirection'			=>	3,
			'reject_unsafe_urls'	=>	true,
			'limit_response_size'	=>	1024 * 128,
			'user-agent'			=>	sanitize_text_field($user_agent ),
			'sslverify'				=>	$this->options['flg-sslverify'] ? true : false,
		) );
		if	(is_wp_error($response ) ) {
			set_transient($transient_key, array('body' => null ), HOUR_IN_SECONDS );
			return	null;
		}

		$code	=	wp_remote_retrieve_response_code($response );
		if	($code < 200 || $code >= 300 ) {
			set_transient($transient_key, array('body' => null ), HOUR_IN_SECONDS );
			return	null;
		}

		$body	=	wp_remote_retrieve_body($response );
		set_transient($transient_key, array('body' => $body ), DAY_IN_SECONDS );
		return	$body;
	}

	private	function	pz_IsRobotsAllowed($url, $user_agent ) {
		$robots	=	$this->pz_GetRobotsTxt($url, $user_agent );
		if	($robots === null || $robots === '' ) {
			return	true;
		}

		$ua			=	strtolower($user_agent );
		$path		=	wp_parse_url($url, PHP_URL_PATH );
		$query		=	wp_parse_url($url, PHP_URL_QUERY );
		$path		=	($path ? $path : '/' ).($query ? '?'.$query : '' );
		$groups		=	array();
		$agents		=	array();
		$rules		=	array();
		$has_rule	=	false;

		$flush_group = function() use (&$groups, &$agents, &$rules, &$has_rule) {
			if	($agents ) {
				$groups[]	=	array(
					'agents'	=>	$agents,
					'rules'		=>	$rules,
				);
			}
			$agents		=	array();
			$rules		=	array();
			$has_rule	=	false;
		};

		foreach	(preg_split('/\r\n|\r|\n/', $robots ) as $line ) {
			$line	=	preg_replace('/^\xEF\xBB\xBF/', '', $line );
			$line	=	trim(preg_replace('/#.*/', '', $line ) );
			if	($line === '' ) {
				$flush_group();
				continue;
			}
			if	(strpos($line, ':' ) === false ) {
				continue;
			}
			list($field, $value ) = array_map('trim', explode(':', $line, 2 ) );
			$field	=	strtolower($field );
			if	($field === 'user-agent' ) {
				if	($has_rule ) {
					$flush_group();
				}
				$agents[]	=	strtolower($value );
				continue;
			}
			if	(($field === 'allow' || $field === 'disallow' ) && $agents ) {
				$has_rule	=	true;
				$rules[]	=	array(
					'type'	=>	$field,
					'path'	=>	$value,
				);
			}
		}
		$flush_group();

		$best_agent_length	=	-1;
		$matched_rules		=	array();
		foreach	($groups as $group ) {
			$group_agent_length	=	-1;
			foreach	($group['agents'] as $agent ) {
				if	($agent === '*' || ($agent !== '' && strpos($ua, $agent ) !== false ) ) {
					$group_agent_length	=	max($group_agent_length, $agent === '*' ? 0 : strlen($agent ) );
				}
			}
			if	($group_agent_length < 0 ) {
				continue;
			}
			if	($group_agent_length > $best_agent_length ) {
				$best_agent_length	=	$group_agent_length;
				$matched_rules		=	$group['rules'];
			} elseif ($group_agent_length === $best_agent_length ) {
				$matched_rules	=	array_merge($matched_rules, $group['rules'] );
			}
		}

		$best_rule	=	null;
		foreach	($matched_rules as $rule ) {
			if	($rule['type'] === 'disallow' && $rule['path'] === '' ) {
				continue;
			}
			if	(!$this->pz_RobotsPathMatches($rule['path'], $path ) ) {
				continue;
			}
			$length	=	strlen($rule['path'] );
			if	(!$best_rule || $length > $best_rule['length'] || ($length === $best_rule['length'] && $rule['type'] === 'allow' ) ) {
				$best_rule	=	array(
					'type'		=>	$rule['type'],
					'length'	=>	$length,
				);
			}
		}

		return	!$best_rule || $best_rule['type'] !== 'disallow';
	}

	// ローカルアドレスかどうかの判定
	private	function	pz_IsLocalAddress($url ) {
		if	($this->options['survey-mode'] ) { $this->pz_OutputLog(__FUNCTION__, '$url='.$url ); }

		$parts	=	parse_url($url );
		if	(!is_array($parts ) || empty($parts['host'] ) ) {
			return	true;
		}

		$scheme	=	isset($parts['scheme'] )	?	strtolower($parts['scheme'] )	:	'';
		if	(!in_array($scheme, array('http', 'https' ), true ) ) {
			return	true;
		}

		if	(!empty($parts['user'] ) || !empty($parts['pass'] ) ) {
			return	true;
		}

		$port	=	isset($parts['port'] ) ? intval($parts['port'] ) : null;
		if	(isset($port ) && !in_array($port, array(80, 443 ), true ) ) {
			return	true;
		}

		$host	=	strtolower(trim($parts['host'], "[] \t\n\r\0\x0B." ) );
		if	(!$host ) {
			return	true;
		}

		if	(in_array($host, array('localhost', 'localhost.localdomain' ), true ) || preg_match('/\.(local|localhost)$/i', $host ) ) {
			return	true;
		}

		$is_public_ip = function($ip ) {
			return	filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE ) !== false;
		};

		if	(filter_var($host, FILTER_VALIDATE_IP ) !== false ) {
			return	!$is_public_ip($host );
		}

		$ips	=	array();
		if	(function_exists('dns_get_record' ) ) {
			$records	=	@dns_get_record($host, DNS_A + DNS_AAAA );
			if	(is_array($records ) ) {
				foreach	($records as $record ) {
					if	(!empty($record['ip'] ) ) {
						$ips[]	=	$record['ip'];
					}
					if	(!empty($record['ipv6'] ) ) {
						$ips[]	=	$record['ipv6'];
					}
				}
			}
		}
		if	(function_exists('gethostbynamel' ) ) {
			$ipv4s	=	@gethostbynamel($host );
			if	(is_array($ipv4s ) ) {
				$ips	=	array_merge($ips, $ipv4s );
			}
		}

		$ips	=	array_values(array_unique(array_filter($ips ) ) );
		if	(empty($ips ) ) {
			return	true;
		}

		foreach	($ips as $ip ) {
			if	(!$is_public_ip($ip ) ) {
				return	true;
			}
		}

		return	false;
	}

	// TITLEとMETAタグを分解
	private	function	pz_GetMeta($html, $tags	=	null, $clear	=	false ) {
		if	($this->options['survey-mode'] ) { $this->pz_OutputLog(__FUNCTION__ ); }

		if	($clear == true || !isset($tags ) ) {
			$tags	=	null;
			$tags	=	array('none' => 'none' );
		}

		// TITLEタグ
		if	(preg_match('~<title\b[^>]*>([^<]*)</title\s*>~i', $html, $m ) ) {
			$tags['title']	=	esc_html(trim($m[1] ) );
		}

		// metaタグ パース
		$match	=	null;
		preg_match_all('/<\s*meta\b[^>]*>/is', $html, $match );
		if	(isset($match ) && is_array($match ) && count($match ) == 1 && count($match[0] ) > 0 ) {
			$attr_value_pattern	=	'(?|"\s*([^"]*?)\s*"|\'\s*([^\']*?)\s*\'|([^"\'\s>]*))';
			foreach	($match[0] as $meta_tag ) {
				if	(preg_match('/\b(?:name|property)\s*=\s*'.$attr_value_pattern.'/is', $meta_tag, $match_name ) && preg_match('/\bcontent\s*=\s*'.$attr_value_pattern.'/is', $meta_tag, $match_content ) ) {
					$tags[strtolower($match_name[1] )]	=	$match_content[1];
				}
			}
		}

		// linkタグ パース
		$match	=	null;
		preg_match_all('/<\s*link\s(?=[^>]*?\brel\s*=\s*(?|"\s*([^"]*?)\s*"|\'\s*([^\']*?)\s*\'|([^"\'>]*?)(?=\s*\/?\s*>|\s\w+\s*=) ))[^>]*?\bhref\s*=\s*(?|"\s*([^"]*?)\s*"|\'\s*([^\']*?)\s*\'|([^"\'>]*?)(?=\s*\/?\s*>|\s\w+\s*=) )[^>]*>/is', $html, $match );
		if	(isset($match ) && is_array($match ) && count($match ) == 3 && count($match[1] ) > 0 ) {
			foreach($match[1] as &$m ) {
				$m	=	strtolower($m );
			}
			unset($m );
			$tags	+=	array_combine($match[1], $match[2] );
		}

		return	$tags;
	}

	// サムネイル取得（外部リンクOGP画像取得）
	private	function	pz_GetImage($thumbnail_url, $force = false, $stamp = false ) {
		if	($this->options['survey-mode'] ) { $this->pz_OutputLog(__FUNCTION__, '$thumbnail_url='.$thumbnail_url.' $force='.$force.' $stamp='.$stamp ); }

		return	require('lib/pz-linkcard-get-image.php' );
	}

	// 設定を取得する
	private	function	pz_LoadOptions() {
		// パラメーターを取得
		$this->options			=	get_option(self::OPTION_NAME );			// オプション値を取得

		if		(!$this->options || !is_array($this->options ) ) {
			$this->options	=	self::pz_GetDefaultOptions();
			$this->options['saved-date']	=	$this->now;		// 保存日時をセット
			$GLOBALS['pz_lkc_option_error']	=	'';
	
			$result			=	add_option(self::OPTION_NAME, $this->options, '', 'yes' );
			if	(!$result ) {
				$result		=	update_option(self::OPTION_NAME, $this->options );
			}
	
			if	(function_exists('wp_cache_delete' ) ) {
				wp_cache_delete(self::OPTION_NAME, 'options' );
				wp_cache_delete('alloptions', 'options' );
			}
	
			global	$wpdb;
			$saved_value	=	$wpdb->get_var($wpdb->prepare("SELECT option_value FROM {$wpdb->options} WHERE option_name = %s LIMIT 1", self::OPTION_NAME ) );
			if	($saved_value === null ) {
				$result		=	$wpdb->insert($wpdb->options, array(
					'option_name'	=>	self::OPTION_NAME,
					'option_value'	=>	maybe_serialize($this->options ),
					'autoload'		=>	'yes',
				), array('%s', '%s', '%s' ) );
				if	(!$result ) {
					$GLOBALS['pz_lkc_option_error']	=	trim($wpdb->last_error.' '.$wpdb->last_query );
				}
				if	(function_exists('wp_cache_delete' ) ) {
					wp_cache_delete(self::OPTION_NAME, 'options' );
					wp_cache_delete('alloptions', 'options' );
				}
			}
		}
		$this->options	=	array_merge(self::pz_GetDefaultOptions(), $this->options );
		$legacy_flg_agent	=	array_key_exists('flg-agent', $this->options ) ? $this->options['flg-agent'] : null;
		$this->options['user-agent']	=	$this->pz_GetUserAgentSelection($this->options['user-agent'], $legacy_flg_agent );
		$this->options['user-agent-text']	=	$this->pz_GetUserAgent($this->options['user-agent'] );
		unset($this->options['flg-agent'] );

		if	($this->options['survey-mode'] ) { $this->pz_OutputLog(__FUNCTION__ ); }
		return	true;
	}

	// 設定を更新する
	private	function	pz_SaveOptions($increment_css_count = true ) {
		if	($this->options['survey-mode'] ) { $this->pz_OutputLog(__FUNCTION__ ); }
		$this->options	=	self::pz_RemoveThisLinkFallback($this->options );

		// 変更前
		$return_status	=	false;
		$before			=	get_option(self::OPTION_NAME, self::pz_GetDefaultOptions() );

		// 変更有無チェック
		if	($before <> $this->options ) {
			$return_status					=	true;			// 更新あり
			$this->options['saved-date']	=	$this->now;		// 保存日時をセット
		}

		// CSSバージョン（CSSキャッシュ対策）
		if	($increment_css_count ) {
			$this->options['css-count']			+=	1;
		}

		// プラグインバージョン
		$this->options['plugin-version']	=	PZLKC_PLUGIN_VERSION;

		// 必要ディレクトリが無い場合、作り直す
		require_once('lib/pz-linkcard-settings-setup.php' );

		// 設定の更新
		$result				=	update_option(self::OPTION_NAME, $this->options );
		if	(function_exists('wp_cache_delete' ) ) {
			wp_cache_delete(self::OPTION_NAME, 'options' );
			wp_cache_delete('alloptions', 'options' );
		}
		global	$wpdb;
		$saved_value		=	$wpdb->get_var($wpdb->prepare("SELECT option_value FROM {$wpdb->options} WHERE option_name = %s LIMIT 1", self::OPTION_NAME ) );
		$saved_options		=	($saved_value !== null ) ? maybe_unserialize($saved_value ) : array();
		if	($saved_options		!=	$this->options ) {
			$serialized_options	=	maybe_serialize($this->options );
			if	($saved_value === null ) {
				$result		=	$wpdb->insert($wpdb->options, array(
					'option_name'	=>	self::OPTION_NAME,
					'option_value'	=>	$serialized_options,
					'autoload'		=>	'yes',
				), array('%s', '%s', '%s' ) );
			} else {
				$result		=	$wpdb->update($wpdb->options, array(
					'option_value'	=>	$serialized_options,
				), array(
					'option_name'	=>	self::OPTION_NAME,
				), array('%s' ), array('%s' ) );
			}
			if	(function_exists('wp_cache_delete' ) ) {
				wp_cache_delete(self::OPTION_NAME, 'options' );
				wp_cache_delete('alloptions', 'options' );
			}
			$saved_value	=	$wpdb->get_var($wpdb->prepare("SELECT option_value FROM {$wpdb->options} WHERE option_name = %s LIMIT 1", self::OPTION_NAME ) );
			$saved_options	=	($saved_value !== null ) ? maybe_unserialize($saved_value ) : array();
		}
		if	($saved_options		!=	$this->options ) {
			$return_status	=	false;
		} else {
			$this->options	=	$saved_options;
			$this->pz_SyncCronSchedules();
		}
		// 返却
		return	$return_status;
	}

	// 設定を初期化する
	private	function	pz_InitializeOptions() {
		if	($this->options['survey-mode'] ) { $this->pz_OutputLog(__FUNCTION__ ); }

		// 引き継ぐ設定値
		$takeover			=	array('saved-date', 'db-version', 'db-click-version' );
		if	(!empty($this->options['initialize-exception'] ) ) {
			// 初期化例外が有効の時に引き継ぐ設定値
			array_push($takeover, 'initialize-exception', 'debug-mode', 'survey-mode', 'admin-mode' );
		}

		// 引き継ぐ設定値を一時保存
		$takeover_options	=	array();
		foreach	($takeover as $key ) {
			if	(array_key_exists($key, $this->options ) ) {
				$takeover_options[$key]	=	$this->options[$key];
			}
		}

		// DEFAULTSに存在する項目を初期値で再構築
		$this->options	=	array();
		foreach	(self::DEFAULTS as $key => $value ) {
			$this->options[$key]	=	$value['default'];
		}

		// 一時保存した設定値を戻す
		foreach	($takeover_options as $key => $value ) {
			$this->options[$key]	=	$value;
		}
		
		// ブログID
		$this->options['multi-myid']		=	get_current_blog_id();
		
		// CSS更新用カウント
		$this->options['css-count']			=	self::pz_GetDefaultOption('css-count' );
		
		// プラグインのバージョン
		$this->options['plugin-version']	=	PZLKC_PLUGIN_VERSION;
		
		// 設定を更新する
		$result	=	$this->pz_SaveOptions();
		return	$result;
	}

	// 書式に関する設定を初期化する
	private	function	pz_InitializeFormatOptions() {
		if	($this->options['survey-mode'] ) { $this->pz_OutputLog(__FUNCTION__ ); }

		$changed	=	false;
		foreach	(self::DEFAULTS as $key => $definition ) {
			if	(empty($definition['reset-format'] ) ) {
				continue;
			}
			if	(!array_key_exists($key, $this->options ) || $this->options[$key] != $definition['default'] ) {
				$changed	=	true;
			}
			$this->options[$key]	=	$definition['default'];
		}

		if	(!$changed ) {
			return	true;
		}
		return	$this->pz_SaveOptions();
	}

	// スタイルシート生成
	private	function	pz_SetStyle($filename = 'style' ) {
		if	($this->options['survey-mode'] ) { $this->pz_OutputLog(__FUNCTION__, '$filename='.$filename ); }

		$result		=	0;
		require_once('lib/pz-linkcard-style.php' );
		return	$result;
	}

	// スタイルシート圧縮
	private	function	pz_CompressCSS($style ) {
		if	($this->options['survey-mode'] ) { $this->pz_OutputLog(__FUNCTION__ ); }

		// 参考：https://shimotsuki.wwwxyz.jp/20200930-650
		$replaces	=	[];
		//$replaces['/@charset [^;]+;/' ] = '';
		$replaces['/([\s:]url\()[\"\']([^\"\']+)[\"\'](\)[\s;}])/' ] = '${1}${2}${3}';
		$replaces['/(\/\*(?=[!]).*?\*\/|\"(?:(?!(?<!\\\)\").)*\"|\'(?:(?!(?<!\\\)\').)*\')|\s+/' ] = '${1} ';
		$replaces['/(\/\*(?=[!]).*?\*\/|\"(?:(?!(?<!\\\)\").)*\"|\'(?:(?!(?<!\\\)\').)*\')|\/\*.*?\*\/|\s+([:])\s+|\s+([)])|([(:])\s+/s' ] = '${1}${2}${3}${4}';
		$replaces['/\s*(\/\*(?=[!]).*?\*\/|\"(?:(?!(?<!\\\)\").)*\"|\'(?:(?!(?<!\\\)\').)*\'|[ :]calc\([^;}]+\)[ ;}]|[!$&+,\/;<=>?@^_{|}~]|\A|\z)\s*/s' ] = '${1}';
		$style		=	preg_replace(array_keys($replaces ), array_values($replaces ), $style );
		do {
			$style	=	preg_replace('/(})[^{]*{}/', '$1', $style );		// 空の要素除去
		} while (preg_match('/;[^{]*{}/', $style ) );
		$style		=	trim($style );
		return		$style;
	}

	// デバグ用の文字列表示
	private	function	pz_HTTPMessage($result ) {
		if	($this->options['survey-mode'] ) { $this->pz_OutputLog(__FUNCTION__ ); }

		$http_message	=	array();
		require('lib/pz-linkcard-error-code.php' );
		if	(isset($http_message[$result] ) ) {
			return	$http_message[$result];
		}
		return		null;
	}

	// 日付・時刻の書式変換
	private	function	pz_Date($format, $value ) {
		if	($this->options['survey-mode'] ) { $this->pz_OutputLog(__FUNCTION__ ); }

		if	(!$value ) {
			return	null;
		}
		$format	=	preg_replace('/<br\s*\/?>/', '\<\b\r\>', $format );
		$temp	=	date($format, $value );
		$temp	=	preg_replace('/<br\s*\/?>/', PHP_EOL, $temp );
		$temp	=	esc_html($temp );
		$temp	=	str_replace(PHP_EOL, '<br>', $temp );
		return		$temp;
	}

	// プラグインを有効化
	public	function	hook_activate() {
		if	($this->options['survey-mode'] ) { $this->pz_OutputLog(__FUNCTION__ ); }

		require_once('lib/pz-linkcard-activate.php' );
	}

	// プラグインを無効化
	public	function	hook_deactivate() {
		if	($this->options['survey-mode'] ) { $this->pz_OutputLog(__FUNCTION__ ); }

		wp_clear_scheduled_hook(self::CRON_ALIVE );		// WP-CRONスケジュール停止（リンク先存在チェック）
		wp_clear_scheduled_hook(self::CRON_CHECK );		// WP-CRONスケジュール停止（SNSカウント取得）
		wp_clear_scheduled_hook(self::CRON_CLICK_CLEANUP );	// WP-CRONスケジュール停止（クリックログ整理）
		wp_clear_scheduled_hook(self::CRON_GET );
	}

	// プラグインの初期化
	public	function	action_init() {
		if	($this->options['survey-mode'] ) { $this->pz_OutputLog(__FUNCTION__ ); }

		// 管理画面用フィルター
		add_filter		('plugin_action_links_'.$this->plugin_basename,	array($this, 'filter_plugin_action_links' ),		10, 1 );		// プラグイン画面
		add_filter		('mce_external_plugins',						array($this, 'filter_mce_external_plugins' ),		$this->options['mce-priority'], 1 );	// ビジュアルエディタ用ボタン
		add_filter		('mce_buttons',									array($this, 'filter_mce_buttons' ),				$this->options['mce-priority'], 1 );	// ビジュアルエディタ用ボタン

		// 管理画面用アクション（実行順）
		add_action		('admin_menu',									array($this, 'action_admin_menu' ) );						// 設定メニュー
		add_action		('admin_menu',									array($this, 'action_sort_pz_submenus' ), PHP_INT_MAX );	// [Pz] サブメニューの並べ替え
		add_action		('wp_dashboard_setup',						array($this, 'action_wp_dashboard_setup' ) );			// ダッシュボードウィジェット
		add_action		('admin_footer-index.php',				array($this, 'action_dashboard_widget_focus' ) );		// ダッシュボードウィジェットへ移動
		add_action		('admin_enqueue_scripts',						array($this, 'action_admin_enqueue_scripts' ) );			// 設定メニュー用スクリプト
		add_action		('admin_print_styles',							array($this, 'action_admin_print_styles' ) );				// スタイルシートの追加
		add_action		('admin_print_scripts',							array($this, 'action_admin_print_scripts' ) );				// スクリプトの追加
		add_action		('admin_notices',								array($this, 'action_admin_notices' ) );					// 注意書き
		add_action		('admin_print_footer_scripts',					array($this, 'action_admin_print_footer_scripts' ) );		// テキストエディタ用クイックタグ
		add_action		('wp_before_admin_bar_render',					array($this, 'action_wp_before_admin_bar_render' ), 11 );	// 管理バー
	}

	private	function	pz_GetDashboardWidgetReturnUrl() {
		return	add_query_arg('pz_lkc_focus', 'dashboard_widget', admin_url('index.php' ) ).'#pz_linkcard_dashboard_widget';
	}

	private	function	pz_IsDashboardWidgetReturnUrl($url ) {
		$query	= wp_parse_url((string) $url, PHP_URL_QUERY );
		if	(!is_string($query ) || '' === $query ) {
			return	false;
		}
		$params	= array();
		parse_str($query, $params );
		return	isset($params['pz_lkc_focus'] ) && 'dashboard_widget' === $params['pz_lkc_focus'];
	}

	// カード編集画面などから戻ったときにダッシュボードウィジェットへ移動
	public	function	action_dashboard_widget_focus() {
		$focus	= isset($_GET['pz_lkc_focus'] ) ? sanitize_key(wp_unslash($_GET['pz_lkc_focus'] ) ) : '';
		if	('dashboard_widget' !== $focus ) {
			return;
		}
		?>
		<script>
		(function() {
			if ('scrollRestoration' in window.history) {
				window.history.scrollRestoration = 'manual';
			}
			var attempts = 0;
			var timer = window.setInterval(function() {
				var widget = document.getElementById('pz_linkcard_dashboard_widget');
				attempts++;
				if (widget) {
					var adminBar = document.getElementById('wpadminbar');
					var offset = (adminBar ? adminBar.getBoundingClientRect().height : 0) + 12;
					var top = window.scrollY + widget.getBoundingClientRect().top - offset;
					window.scrollTo(0, Math.max(0, top));
				}
				if (attempts >= 10) {
					window.clearInterval(timer);
				}
			}, 100);
		})();
		</script>
		<?php
	}

	// WordPressダッシュボードに概要を追加
	public	function	action_wp_dashboard_setup() {
		if	(!current_user_can('manage_options' ) ) {
			return;
		}
		add_meta_box(
			'pz_linkcard_dashboard_widget',
			self::PLUGIN_NAME,
			array($this, 'dashboard_widget' ),
			get_current_screen(),
			'normal',
			'core',
			array('__widget_basename' => self::PLUGIN_NAME )
		);
	}

	// ダッシュボードウィジェット
	public	function	dashboard_widget() {
		global	$wpdb;

		$dashboard_return_url	= $this->pz_GetDashboardWidgetReturnUrl();
		$count_list	= array_fill_keys(array('all', 'internal', 'external', 'modify', 'unlink', 'unregistered' ), 0 );
		$recent_registrations	= array();
		$recent_clicks	= array();
		$card_table_exists	= ($wpdb->get_var($wpdb->prepare('SHOW TABLES LIKE %s', $this->db_card ) ) === $this->db_card );
		if	($card_table_exists ) {
			$counts	= $wpdb->get_row($wpdb->prepare(
				"SELECT COUNT(*) AS count_all,
					COUNT(CASE WHEN domain = %s THEN 1 END) AS count_internal,
					COUNT(CASE WHEN domain <> %s THEN 1 END) AS count_external,
					COUNT(CASE WHEN alive_result <> update_result THEN 1 END) AS count_modify,
					COUNT(CASE WHEN alive_result IS NOT NULL AND alive_result <> 0 AND (alive_result < 100 OR alive_result >= 400) THEN 1 END) AS count_unlink,
					COUNT(CASE WHEN regist_time IS NULL OR regist_time = 0 THEN 1 END) AS count_unregistered
				FROM $this->db_card",
				$this->domain,
				$this->domain
			) );
			if	($counts ) {
				$count_list['all']		= intval($counts->count_all );
				$count_list['internal']	= intval($counts->count_internal );
				$count_list['external']	= intval($counts->count_external );
				$count_list['modify']	= intval($counts->count_modify );
				$count_list['unlink']	= intval($counts->count_unlink );
				$count_list['unregistered']	= intval($counts->count_unregistered );
			}
			$recent_registrations	= $wpdb->get_results(
				"SELECT id AS card_id, regist_time, title, url
				FROM $this->db_card
				WHERE regist_time > 0
				ORDER BY regist_time DESC, id DESC
				LIMIT 5"
			);

			$click_table_exists	= ($wpdb->get_var($wpdb->prepare('SHOW TABLES LIKE %s', $this->db_click ) ) === $this->db_click );
			if	($click_table_exists ) {
				$recent_clicks	= $wpdb->get_results(
					"SELECT click.click_time, card.id AS card_id, card.title, card.url
					FROM $this->db_click AS click
					INNER JOIN $this->db_card AS card ON card.id = click.card_id
					ORDER BY click.click_time DESC, click.click_id DESC
					LIMIT 5"
				);
			}
		}

		$labels	= array(
			'all'		=> __('All', 'pz-linkcard' ),
			'external'	=> __('External', 'pz-linkcard' ),
			'internal'	=> __('Internal', 'pz-linkcard' ),
			'modify'	=> __('Modify', 'pz-linkcard' ),
			'unlink'	=> __('Unlink', 'pz-linkcard' ),
			'unregistered'	=> __('Unregistered', 'pz-linkcard' ),
		);
		$label_icons	= array(
			'all'		=> 'admin-page',
			'external'	=> 'external',
			'internal'	=> 'admin-home',
			'modify'	=> 'update',
			'unlink'	=> 'editor-unlink',
			'unregistered'	=> 'welcome-add-page',
		);
		?>
		<style>
			#pz_linkcard_dashboard_widget { scroll-margin-top: 46px; }
			#pz_linkcard_dashboard_widget .inside { margin-top: 0; container-type: inline-size; }
			.pz-dashboard-widget-version { margin: 0 0 10px; }
			.pz-dashboard-widget-error { display: block; margin: 0 0 10px; padding: 9px 12px; background: #ff6868; color: #fff; font-weight: 600; text-decoration: none; }
			.pz-dashboard-widget-error:hover,
			.pz-dashboard-widget-error:focus { background: #e85c5c; color: #fff; text-decoration: underline; }
			.pz-dashboard-widget-counts { display: grid; grid-template-columns: repeat(3, max-content minmax(3em, 1fr)); gap: 5px 10px; margin: 0; }
			.pz-dashboard-widget-counts dt, .pz-dashboard-widget-counts dd { margin: 0; }
			.pz-dashboard-widget-counts dt { display: inline-flex; align-items: center; }
			.pz-dashboard-widget-counts dt .dashicons { width: 18px; height: 18px; margin-right: 4px; color: #646970; font-size: 18px; line-height: 18px; }
			.pz-dashboard-widget-counts dd { font-variant-numeric: tabular-nums; text-align: right; }
			@container (max-width: 420px) {
				.pz-dashboard-widget-counts { grid-template-columns: repeat(2, max-content minmax(3em, 1fr)); }
			}
			@container (max-width: 360px) {
				.pz-dashboard-widget-count-label { display: none; }
				.pz-dashboard-widget-counts dt .dashicons { margin-right: 0; }
				.pz-dashboard-widget-counts dd { white-space: nowrap; }
			}
			@container (min-width: 640px) {
				.pz-dashboard-widget-counts { grid-template-columns: repeat(4, max-content minmax(3em, 1fr)); }
			}
			.pz-dashboard-widget-recent { margin-top: 16px; padding-top: 12px; border-top: 1px solid #dcdcde; }
			.pz-dashboard-widget-recent-header { display: flex; align-items: baseline; gap: 12px; margin-bottom: 8px; }
			.pz-dashboard-widget-recent h3 { margin: 0; font-size: 14px; }
			.pz-dashboard-widget-recent ul { margin: 0; }
			.pz-dashboard-widget-recent li { display: flex; gap: 10px; margin: 0; padding: 6px 4px; }
			.pz-dashboard-widget-recent li:nth-child(odd) { background: #f6f7f7; }
			.pz-dashboard-widget-recent time { flex: 0 0 96px; color: #646970; font-variant-numeric: tabular-nums; }
			.pz-dashboard-widget-recent a { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
			.pz-dashboard-widget-more { margin-left: auto; white-space: nowrap; }
			.pz-dashboard-widget-item-link { display: inline-flex; flex: 1 1 auto; align-items: center; min-width: 0; }
			.pz-dashboard-widget-item-title { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
			.pz-dashboard-widget-item-link .dashicons { flex: 0 0 0; width: 0; height: 18px; margin-left: 0; overflow: hidden; color: #50575e; font-size: 18px; line-height: 18px; opacity: 0; }
			.pz-dashboard-widget-item-link:hover .dashicons,
			.pz-dashboard-widget-item-link:focus .dashicons { flex-basis: 18px; width: 18px; margin-left: 5px; opacity: 1; }
			.pz-dashboard-widget-notice { margin: 0; padding: 6px 4px; background: #f6f7f7; color: #8c8f94; }
			.pz-dashboard-widget-actions { display: flex; gap: 22px; margin: 14px -12px -12px; padding: 10px 12px; border-top: 1px solid #dcdcde; border-radius: 0 0 8px 8px; background: #f6f7f7; }
			.pz-dashboard-widget-action { display: inline-flex; align-items: center; }
			.pz-dashboard-widget-action .dashicons { margin-right: 4px; color: #2271b1; font-size: 18px; line-height: 20px; }
		</style>
		<p class="pz-dashboard-widget-version"><?php esc_html_e('Version', 'pz-linkcard' ); ?> <?php echo esc_html(PZLKC_PLUGIN_VERSION ); ?></p>
		<?php if	(!empty($this->options['error-mode'] ) ) {
			$error_settings_url	= add_query_arg(array('tab' => 'pz-error', 'return_url' => $dashboard_return_url ), $this->settings_url );
		?>
			<a href="<?php echo esc_url($error_settings_url ); ?>" class="pz-dashboard-widget-error"><?php esc_html_e('There is a URL parameter error.', 'pz-linkcard' ); ?></a>
		<?php } ?>
		<dl class="pz-dashboard-widget-counts">
			<?php foreach	($labels as $key => $label ) {
				$manager_url	= add_query_arg(array('filter' => $key, 'return_url' => $dashboard_return_url ), $this->cacheman_url );
			?>
				<dt><span class="dashicons dashicons-<?php echo esc_attr($label_icons[$key] ); ?>" aria-hidden="true"></span><span class="pz-dashboard-widget-count-label"><?php echo esc_html($label ); ?></span></dt><dd><a href="<?php echo esc_url($manager_url ); ?>"><?php echo esc_html(sprintf(_n('%s card', '%s cards', $count_list[$key], 'pz-linkcard' ), number_format_i18n($count_list[$key] ) ) ); ?></a></dd>
			<?php } ?>
		</dl>
		<div class="pz-dashboard-widget-recent pz-dashboard-widget-registrations">
			<div class="pz-dashboard-widget-recent-header">
				<h3><?php esc_html_e('Recent Registrations', 'pz-linkcard' ); ?></h3>
				<a class="pz-dashboard-widget-more" href="<?php echo esc_url(add_query_arg(array('orderby' => 'regist_time', 'order' => 'desc', 'return_url' => $dashboard_return_url ), $this->cacheman_url ) ); ?>"><?php esc_html_e('View List', 'pz-linkcard' ); ?></a>
			</div>
			<?php if	($recent_registrations ) { ?>
				<ul>
					<?php foreach	($recent_registrations as $registration ) {
						$title	= $registration->title ? $registration->title : $this->pz_DecodeURL($registration->url );
						$edit_url	= add_query_arg(array(
							'action'		=> 'edit',
							'cache_id'		=> intval($registration->card_id ),
							'return_url'	=> $dashboard_return_url,
						), $this->cacheman_url );
					?>
						<li><time datetime="<?php echo esc_attr(date('c', intval($registration->regist_time ) ) ); ?>"><?php echo esc_html(date_i18n(__('M j, H:i', 'pz-linkcard' ), intval($registration->regist_time ) ) ); ?></time><a href="<?php echo esc_url($edit_url ); ?>" title="<?php echo esc_attr($title ); ?>" class="pz-dashboard-widget-item-link"><span class="pz-dashboard-widget-item-title"><?php echo esc_html($title ); ?></span><span class="dashicons dashicons-welcome-write-blog" aria-hidden="true"></span></a></li>
					<?php } ?>
				</ul>
			<?php } else { ?>
				<p>—</p>
			<?php } ?>
		</div>
		<div class="pz-dashboard-widget-recent pz-dashboard-widget-clicks">
			<div class="pz-dashboard-widget-recent-header">
				<h3><?php esc_html_e('Recent Clicks', 'pz-linkcard' ); ?></h3>
				<?php if	(!empty($this->options['flg-click-log'] ) ) { ?>
					<a class="pz-dashboard-widget-more" href="<?php echo esc_url(add_query_arg(array('action' => 'click-log', 'return_url' => $dashboard_return_url ), $this->cacheman_url ) ); ?>"><?php esc_html_e('View List', 'pz-linkcard' ); ?></a>
				<?php } ?>
			</div>
			<?php if	(empty($this->options['flg-click-log'] ) ) {
				$click_settings_url	= add_query_arg(array('tab' => 'pz-check', 'return_url' => $dashboard_return_url ), $this->settings_url ).'#pz-click-log-settings';
				$click_settings_link	= '<a href="'.esc_url($click_settings_url ).'">'.esc_html__('Click count recording', 'pz-linkcard' ).'</a>';
			?>
				<p class="pz-dashboard-widget-notice"><?php echo wp_kses_post(sprintf(__('Enable %s to display recent clicks.', 'pz-linkcard' ), $click_settings_link ) ); ?></p>
			<?php } elseif	($recent_clicks ) { ?>
				<ul>
					<?php foreach	($recent_clicks as $click ) {
						$title	= $click->title ? $click->title : $this->pz_DecodeURL($click->url );
						$click_url	= add_query_arg(array(
							'action'		=> 'click-log',
							'cache_id'		=> intval($click->card_id ),
							'return_url'	=> $dashboard_return_url,
						), $this->cacheman_url );
					?>
						<li><time datetime="<?php echo esc_attr(date('c', intval($click->click_time ) ) ); ?>"><?php echo esc_html(date_i18n(__('M j, H:i', 'pz-linkcard' ), intval($click->click_time ) ) ); ?></time><a href="<?php echo esc_url($click_url ); ?>" title="<?php echo esc_attr($title ); ?>" class="pz-dashboard-widget-item-link"><span class="pz-dashboard-widget-item-title"><?php echo esc_html($title ); ?></span><span class="dashicons dashicons-chart-bar" aria-hidden="true"></span></a></li>
					<?php } ?>
				</ul>
			<?php } else { ?>
				<p>—</p>
			<?php } ?>
		</div>
		<div class="pz-dashboard-widget-actions">
			<span class="pz-dashboard-widget-action"><span class="dashicons dashicons-archive" aria-hidden="true"></span><a href="<?php echo esc_url(add_query_arg('return_url', $dashboard_return_url, $this->cacheman_url ) ); ?>"><?php esc_html_e('Manager', 'pz-linkcard' ); ?></a></span>
			<span class="pz-dashboard-widget-action"><span class="dashicons dashicons-admin-generic" aria-hidden="true"></span><a href="<?php echo esc_url(add_query_arg('return_url', $dashboard_return_url, $this->settings_url ) ); ?>"><?php esc_html_e('Settings', 'pz-linkcard' ); ?></a></span>
		</div>
		<?php
	}

	// 管理画面のサブメニュー追加
	public	function	action_admin_menu() {
		if	($this->options['survey-mode'] ) { $this->pz_OutputLog(__FUNCTION__ ); }

		if	((function_exists('is_plugin_active' ) && is_plugin_active('pz-linkcard3/pz-linkcard3.php' ) ) || (function_exists('is_plugin_active_for_network' ) && is_plugin_active_for_network('pz-linkcard3/pz-linkcard3.php' ) ) ) {
			$menu_manager	=	__('[Pz] LinkCard2 Manager',	'pz-linkcard' );
			$menu_settings	=	__('[Pz] LinkCard2 Settings',	'pz-linkcard' );
		} else {
			$menu_manager	=	__('[Pz] LinkCard Manager',		'pz-linkcard' );
			$menu_settings	=	__('[Pz] LinkCard Settings',	'pz-linkcard' );
		}
		$sns_period		=	$this->pz_GetRequestOption('sns-period', $this->options['sns-period'] );
		$flg_alive_count	=	$this->pz_GetRequestOption('flg-alive-count', $this->options['flg-alive-count'] );
		if	($sns_period && $flg_alive_count ) {
			global	$wpdb;
			$result		=	$wpdb->get_row("SELECT COUNT(*) AS count FROM $this->db_card WHERE alive_result IS NOT NULL AND alive_result <> 0 AND (alive_result < 100 OR alive_result >= 400)");
			if	(isset($result ) && isset($result->count ) ) {
				$menu_manager	.=	'&nbsp;<span class="update-plugins"><span class="update-count lkc-menu-count">'.$result->count.'</span></span>';
			}
		}
		add_management_page	('pz-linkcard-manager',		$menu_manager,		'manage_options', 	self::CACHEMAN_PAGE,	array($this, 'page_cacheman' ) );
		add_options_page	('pz-linkcard-settings',	$menu_settings,		'manage_options', 	self::SETTINGS_PAGE,	array($this, 'page_settings' ) );
	}

	// 設定・ツール内の [Pz] サブメニューを名前順に並べ替え
	public	function	action_sort_pz_submenus() {
		global	$submenu;

		foreach	(array('options-general.php', 'tools.php' ) as $parent_slug ) {
			if	(empty($submenu[$parent_slug] ) || !is_array($submenu[$parent_slug] ) ) {
				continue;
			}

			$pz_items		=	array();
			$other_items	=	array();
			$insert_at		=	null;
			foreach	($submenu[$parent_slug] as $item ) {
				$menu_name	=	isset($item[0] ) ? trim(wp_strip_all_tags(html_entity_decode((string) $item[0], ENT_QUOTES | ENT_HTML5, get_bloginfo('charset' ) ) ) ) : '';
				if	(strpos($menu_name, '[Pz]' ) !== 0 ) {
					$other_items[]	=	$item;
					continue;
				}
				if	($insert_at === null ) {
					$insert_at	=	count($other_items );
				}
				$pz_items[]	=	array(
					'name'	=>	$menu_name,
					'item'	=>	$item,
					'order'	=>	count($pz_items ),
				);
			}

			if	(count($pz_items ) < 2 ) {
				continue;
			}

			usort($pz_items, function($a, $b ) {
				$result	=	strnatcasecmp($a['name'], $b['name'] );
				return	$result !== 0 ? $result : $a['order'] <=> $b['order'];
			} );
			$sorted_pz_items	=	array_map(function($pz_item ) {
				return	$pz_item['item'];
			}, $pz_items );
			array_splice($other_items, $insert_at, 0, $sorted_pz_items );
			$submenu[$parent_slug]	=	$other_items;
		}
	}
	
	// 管理画面＞Pz カード管理
	public	function	page_cacheman() {
		if	($this->options['survey-mode'] ) { $this->pz_OutputLog(__FUNCTION__ ); }

		if	(!current_user_can('manage_options' ) ) {
			wp_die(__('Sorry, you are not allowed to access this page.', 'pz-linkcard' ) );
		}

		require_once('lib/pz-linkcard-cacheman.php' );
	}

	// 管理画面＞Pz カード設定
	public	function	page_settings() {
		if	($this->options['survey-mode'] ) { $this->pz_OutputLog(__FUNCTION__ ); }

		if	(!current_user_can('manage_options' ) ) {
			wp_die(__('Sorry, you are not allowed to access this page.', 'pz-linkcard' ) );
		}

		require_once('lib/pz-linkcard-settings.php' );
	}

	// ファイルエクスポート
	private	function	pz_GetFilesystem() {
		global	$wp_filesystem;

		if	($wp_filesystem instanceof WP_Filesystem_Base ) {
			return	$wp_filesystem;
		}
		require_once ABSPATH.'wp-admin/includes/file.php';
		if	(!WP_Filesystem() ) {
			return	false;
		}
		return	$wp_filesystem;
	}

	function action_export_file() {
		if	($this->options['survey-mode'] ) { $this->pz_OutputLog(__FUNCTION__ ); }

		if	(!current_user_can('manage_options' ) ) {
			wp_die(__('Sorry, you are not allowed to access this page.', 'pz-linkcard' ) );
		}

		require_once('lib/pz-linkcard-file-export.php' );
	}

	// 管理画面のスタイルシート、スクリプト設定
	public	function	action_admin_enqueue_scripts($hook ) {
		if	($this->options['survey-mode'] ) { $this->pz_OutputLog(__FUNCTION__ ); }

		$admin_css_version	=	PZLKC_PLUGIN_VERSION.'.'.filemtime($this->plugin_dir_path.'css/admin.css' );
		if	($this->is_editor_modal_screen($hook ) ) {
			wp_enqueue_style	(self::PLUGIN_SLUG.'-admin-css',	PZLKC_URL_ADMIN_CSS,			array(),			$admin_css_version );
			return;
		}

		$allowed_hooks	=	array(
			'settings_page_'.self::SETTINGS_PAGE,
			'tools_page_'.self::CACHEMAN_PAGE,
		);
		if	(!in_array($hook, $allowed_hooks, true ) ) {
			return;
		}

		$numeric_options	=	array();
		foreach	(self::pz_GetOptionDefinitions() as $key => $definition ) {
			if	(($definition['type'] ?? '' ) === 'numeric' ) {
				$numeric_options[]	=	$key;
			}
		}
		$admin_js_version	=	PZLKC_PLUGIN_VERSION.'.'.filemtime($this->plugin_dir_path.'js/admin-settings.js' );
		wp_enqueue_script	(self::PLUGIN_SLUG.'-admin-js',		PZLKC_URL_ADMIN_JS,			array(),			$admin_js_version, true );
		wp_localize_script	(self::PLUGIN_SLUG.'-admin-js',		'pzLinkCardAdmin', array(
			'ajaxUrl'		=>	admin_url('admin-ajax.php' ),
			'settingsUrl'	=>	$this->settings_url,
			'cachemanUrl'	=>	$this->cacheman_url,
			'noticeNonce'	=>	wp_create_nonce('pz_lkc_clear_error_mode' ),
			'cachemanColumnsNonce'	=>	wp_create_nonce('pz_lkc_cacheman_columns' ),
			'findUsedPostsNonce'	=>	wp_create_nonce('pz_lkc_find_used_posts' ),
			'cachemanPendingNonce'	=>	wp_create_nonce('pz_lkc_cacheman_pending' ),
			'numericOptions'	=>	$numeric_options,
			'mediaTitle'	=>	__('Select Image', 'pz-linkcard' ),
			'mediaButton'	=>	__('Use this image', 'pz-linkcard' ),
			'discardChanges'	=>	__('Discard changes?', 'pz-linkcard' ),
			'previousHeading'	=>	__('Go to previous heading', 'pz-linkcard' ),
			'nextHeading'	=>	__('Go to next heading', 'pz-linkcard' ),
		) );
		wp_enqueue_style	(self::PLUGIN_SLUG.'-admin-css',	PZLKC_URL_ADMIN_CSS,			array(),			$admin_css_version );

		if	($hook === 'tools_page_'.self::CACHEMAN_PAGE || $hook === 'settings_page_'.self::SETTINGS_PAGE ) {
			wp_enqueue_media();
		}
		if	($hook === 'settings_page_'.self::SETTINGS_PAGE ) {
			$admin_tabs_version	=	PZLKC_PLUGIN_VERSION.'.'.filemtime($this->plugin_dir_path.'js/admin-tabs.js' );
			$admin_search_version	=	PZLKC_PLUGIN_VERSION.'.'.filemtime($this->plugin_dir_path.'js/admin-search.js' );
			$preview_js_version	=	PZLKC_PLUGIN_VERSION.'.'.filemtime($this->plugin_dir_path.'js/pz-linkcard-preview.js' );
			$preview_today		=	current_datetime();
			$preview_yesterday	=	$preview_today->modify('-1 day' );
			wp_enqueue_script	(self::PLUGIN_SLUG.'-admin-tabs',	PZLKC_URL_ADMIN_TAB,	array(),	$admin_tabs_version, true );
			wp_enqueue_script	(self::PLUGIN_SLUG.'-admin-search', PZLKC_URL_ADMIN_SEARCH, array(self::PLUGIN_SLUG.'-admin-js' ), $admin_search_version, true );
			wp_enqueue_script	(self::PLUGIN_SLUG.'-preview',		PZLKC_URL_PREVIEW_JS,	array(),	$preview_js_version, true );
			wp_localize_script	(self::PLUGIN_SLUG.'-preview',		'pzLinkCardPreview',		array(
					'ajaxUrl'		=>	admin_url('admin-ajax.php' ),
					'nonce'			=>	wp_create_nonce('pz_lkc_preview_render' ),
					'action'		=>	'pz_lkc_preview_render',
					'stateNonce'	=>	wp_create_nonce('pz_lkc_preview_state' ),
					'stateAction'	=>	'pz_lkc_preview_state',
					'labels'		=>	array(
						'restorePreview'		=>	__('Preview', 'pz-linkcard' ),
						'restorePreviewAria'	=>	__('Preview', 'pz-linkcard' ),
						'dockPreviewBottom'	=>	__('Dock preview bottom', 'pz-linkcard' ),
						'dockPreviewRight'	=>	__('Dock preview right', 'pz-linkcard' ),
						'windowPreview'		=>	__('Window preview', 'pz-linkcard' ),
						'referenced'			=>	__('Referenced', 'pz-linkcard' ),
						'youMayAlsoLike'		=>	__('You may also like', 'pz-linkcard' ),
						'previewPostDate'		=>	wp_date(PZLKC_DATE_FORMAT, $preview_yesterday->getTimestamp(), wp_timezone() ),
						'previewModifiedDate'	=>	wp_date(PZLKC_DATE_FORMAT, $preview_today->getTimestamp(), wp_timezone() ),
					),
			) );
			wp_enqueue_script	(self::PLUGIN_SLUG.'-color-picker',	PZLKC_URL_COLOR_PICKER_JS,	array(),	PZLKC_PLUGIN_VERSION, true );
			wp_localize_script	(self::PLUGIN_SLUG.'-color-picker',	'pz_lkc_color_picker', array(
				'labels'	=>	array(
					'clear'			=>	__('Clear', 'pz-linkcard' ),
					'selectColor'	=>	__('Select color', 'pz-linkcard' ),
					'saveColor'		=>	__('Save current color', 'pz-linkcard' ),
					'resetOffset'	=>	__('Reset to offset', 'pz-linkcard' ),
				),
			) );
			wp_enqueue_style	(self::PLUGIN_SLUG.'-color-picker',	PZLKC_URL_COLOR_PICKER_CSS,	array(),	PZLKC_PLUGIN_VERSION );
		}
	}

	// Pz-LinkCard挿入ダイアログを表示する投稿編集画面か
	private	function	is_editor_modal_screen($hook = null ) {
		if	(empty($this->options['flg-edit-insert'] ) && empty($this->options['flg-edit-preview'] ) ) {
			return	false;
		}
		if	($hook && !in_array($hook, array('post.php', 'post-new.php' ), true ) ) {
			return	false;
		}
		if	(!function_exists('get_current_screen' ) ) {
			return	false;
		}

		$screen	=	get_current_screen();
		if	(!$screen || $screen->base !== 'post' ) {
			return	false;
		}

		$post_type	=	$screen->post_type;
		if	(!$post_type && isset($_GET['post'] ) ) {
			$post_type	=	get_post_type((int)$_GET['post'] );
		}
		if	(!$post_type && isset($_GET['post_type'] ) ) {
			$post_type	=	sanitize_key(wp_unslash($_GET['post_type'] ) );
		}
		if	(!$post_type ) {
			$post_type	=	'post';
		}

		return	post_type_supports($post_type, 'editor' );
	}

	// ブロック登録
	public	function	action_register_block() {
		if	(!function_exists('register_block_type' ) ) {
			return;
		}
		if	((string)($this->options['flg-edit-block'] ?? '') !== '1' ) {
			return;
		}

		$shortcodes	=	array();
		foreach	(array('code1', 'code2', 'code3' ) as $key ) {
			$code	=	preg_replace('/[^a-zA-Z0-9]/', '', $this->options[$key] ?? '' );
			if	($code ) {
				$shortcodes[]	=	$code;
			}
		}
		if	(!$shortcodes ) {
			$shortcodes[]	=	self::pz_GetDefaultOption('code1' );
		}
		$shortcodes		=	array_values(array_unique($shortcodes ) );
		$editor_script	=	self::PLUGIN_SLUG.'-block-editor';
		wp_register_script(
			$editor_script,
			$this->plugin_dir_url.'js/block-editor.js',
			array('wp-blocks', 'wp-block-editor', 'wp-editor', 'wp-components', 'wp-element', 'wp-i18n', 'wp-data', 'wp-hooks', 'wp-compose', 'wp-server-side-render' ),
			PZLKC_PLUGIN_VERSION,
			true
		);
		wp_localize_script($editor_script, 'pz_lkc_block_icon', array(
			'blockName'		=>	'pz-linkcard/linkcard',
			'iconUrl'		=>	$this->plugin_dir_url.'img/icon_lkc_block.svg',
			'previewUrl'	=>	$this->plugin_dir_url.'img/pz-lkc_block_preview.png',
			'shortcode'		=>	$shortcodes[0],
			'shortcodes'	=>	$shortcodes,
			'title'			=>	'Pz-LinkCard',
			'placeholder'	=>	__('Enter a URL or search keyword', 'pz-linkcard' ),
			'description'	=>	__('Create a Pz-LinkCard shortcode.', 'pz-linkcard' ),
			'ajaxUrl'		=>	admin_url('admin-ajax.php' ),
			'searchNonce'	=>	wp_create_nonce('pz_lkc_mce_post_search' ),
			'searchResults'	=>	__('Search results', 'pz-linkcard' ),
			'postDate'		=>	__('Post Date', 'pz-linkcard' ),
			'modifiedDate'	=>	__('Modified Date', 'pz-linkcard' ),
		) );

		$block_args	=	array(
			'title'				=>	'Pz-LinkCard',
			'description'		=>	__('Create a Pz-LinkCard shortcode.', 'pz-linkcard' ),
			'category'			=>	'widgets',
			'icon'				=>	'admin-links',
			'supports'			=>	array(
				'inserter'		=>	false,
			),
			'attributes'		=>	array(
				'url'			=>	array(
					'type'		=>	'string',
					'default'	=>	'',
				),
				'shortcode'		=>	array(
					'type'		=>	'string',
					'default'	=>	'',
				),
			),
			'render_callback'	=>	array($this, 'render_block_linkcard' ),
		);
		$block_args['editor_script']	=	$editor_script;
		register_block_type('pz-linkcard/linkcard', $block_args );
	}

	// Pz-LinkCard ブロック描画
	public	function	render_block_linkcard($attributes, $content = '' ) {
		$url	=	esc_url_raw($attributes['url'] ?? '' );
		if	(!$url ) {
			return	'<div class="linkcard"><div class="lkc-internal-wrap"><div class="lkc-info">'.esc_html(self::PLUGIN_NAME).'</div><div class="lkc-excerpt">'.esc_html__('No URL was specified.', 'pz-linkcard' ).'</div></div></div>';
		}
		$shortcode	=	preg_replace('/[^a-zA-Z0-9]/', '', $attributes['shortcode'] ?? '' );
		$available_shortcodes	=	array();
		foreach	(array('code1', 'code2', 'code3' ) as $key ) {
			$code	=	preg_replace('/[^a-zA-Z0-9]/', '', $this->options[$key] ?? '' );
			if	($code ) {
				$available_shortcodes[]	=	$code;
			}
		}
		if	(!$available_shortcodes ) {
			$available_shortcodes[]	=	self::pz_GetDefaultOption('code1' );
		}
		if	(!$shortcode || !in_array($shortcode, $available_shortcodes, true ) ) {
			$shortcode	=	$available_shortcodes[0];
		}

		$atts	=	array();
		$atts['url']	=	$url;
		if	(!empty($attributes['title'] ) ) {
			$atts['title']	=	sanitize_text_field($attributes['title'] );
		}
		if	(!empty($attributes['content'] ) ) {
			$atts['content']	=	sanitize_textarea_field($attributes['content'] );
		}
		return	$this->shortcode($atts, null, $shortcode );
	}

	// ブロックエディタ用スクリプト
	public	function	action_enqueue_block_editor_assets() {
		if	($this->options['survey-mode'] ) { $this->pz_OutputLog(__FUNCTION__ ); }

		wp_enqueue_style(self::PLUGIN_SLUG.'-block-editor', PZLKC_URL_ADMIN_CSS, array(), PZLKC_PLUGIN_VERSION.'.'.filemtime($this->plugin_dir_path.'css/admin.css' ) );
		$this->enqueue_block_card_styles();
	}

	// ブロックエディタ本文用スタイルシート
	public	function	action_enqueue_block_assets() {
		if	(!is_admin() ) {
			return;
		}
		if	($this->options['survey-mode'] ) { $this->pz_OutputLog(__FUNCTION__ ); }

		$this->enqueue_block_card_styles();
	}

	// ブロックエディタ内プレビュー用カードスタイル
	private	function	enqueue_block_card_styles() {
		$css_version	=	PZLKC_PLUGIN_VERSION.'.'.$this->options['css-count'];
		$pending_loader_css	= '.pz-lkc-pending .lkc-external-wrap{position:relative}.pz-lkc-pending-loader{position:absolute;z-index:10;top:50%;left:50%;display:flex;width:66px;height:66px;margin:-33px 0 0 -33px;border-radius:4px;background:rgba(0,0,0,.45);align-items:center;justify-content:center;pointer-events:none}.pz-lkc-pending-loader::after{content:"";width:50px;aspect-ratio:1;border-radius:50%;background:radial-gradient(farthest-side,#fff 94%,#0000) top/8px 8px no-repeat,conic-gradient(#0000 30%,#fff);mask:radial-gradient(farthest-side,#0000 calc(100% - 8px),#000 0);-webkit-mask:radial-gradient(farthest-side,#0000 calc(100% - 8px),#000 0);animation:pz-lkc-pending-loader-spin 1s infinite linear}@keyframes pz-lkc-pending-loader-spin{100%{transform:rotate(1turn)}}';
		if	($this->options['flg-compress'] ) {
			wp_enqueue_style	(self::PLUGIN_SLUG.'-block-card-css',	PZLKC_URL_STYLE.'style.min.css',	array(),	$css_version );
		} else {
			wp_enqueue_style	(self::PLUGIN_SLUG.'-block-card-css',	PZLKC_URL_STYLE.'style.css',		array(),	$css_version );
		}
		wp_add_inline_style(self::PLUGIN_SLUG.'-block-card-css', $pending_loader_css );
		if	($this->options['css-add-url'] ) {
			wp_enqueue_style	(self::PLUGIN_SLUG.'-block-card-css-add',	$this->options['css-add-url'],	array(),	$css_version );
		}
		$this->enqueue_excerpt_fit_script();
	}

	private	function	enqueue_excerpt_fit_script() {
		wp_enqueue_script	(
			self::PLUGIN_SLUG.'-excerpt-fit',
			plugin_dir_url(__FILE__).'js/excerpt-fit.js',
			array(),
			PZLKC_PLUGIN_VERSION,
			true
		);
	}

	// 通常時のスタイルシート
	public	function	action_wp_enqueue_scripts($hook ) {
		if	($this->options['survey-mode'] ) { $this->pz_OutputLog(__FUNCTION__ ); }

		$this->amp		=	null;
		$css_version	=	PZLKC_PLUGIN_VERSION.'.'.$this->options['css-count'];
		if	($this->options['flg-compress'] ) {
			wp_enqueue_style	(self::PLUGIN_SLUG.'-css',			PZLKC_URL_STYLE.'style.min.css',		array(),	$css_version );
		} else {
			wp_enqueue_style	(self::PLUGIN_SLUG.'-css',			PZLKC_URL_STYLE.'style.css',			array(),	$css_version );
		}
		wp_add_inline_style(self::PLUGIN_SLUG.'-css', '.linkcard[id^="pz-lkc-"]{scroll-margin-top:96px}.linkcard[id^="pz-lkc-"]:target{animation:pz-lkc-target-highlight 5s steps(1,end)}.lkc-error:target+.lkc-internal-wrap{animation:pz-lkc-error-highlight 5s steps(1,end)}body:has(.lkc-error:target) .lkc-error+.lkc-internal-wrap{animation:pz-lkc-error-highlight 5s steps(1,end)}.pz-lkc-pending .lkc-external-wrap{position:relative}.pz-lkc-pending-loader{position:absolute;z-index:10;top:50%;left:50%;display:flex;width:66px;height:66px;margin:-33px 0 0 -33px;border-radius:4px;background:rgba(0,0,0,.45);align-items:center;justify-content:center;pointer-events:none}.pz-lkc-pending-loader::after{content:"";width:50px;aspect-ratio:1;border-radius:50%;background:radial-gradient(farthest-side,#fff 94%,#0000) top/8px 8px no-repeat,conic-gradient(#0000 30%,#fff);mask:radial-gradient(farthest-side,#0000 calc(100% - 8px),#000 0);-webkit-mask:radial-gradient(farthest-side,#0000 calc(100% - 8px),#000 0);animation:pz-lkc-pending-loader-spin 1s infinite linear}@keyframes pz-lkc-target-highlight{0%,20%,40%,60%,80%{outline:4px solid rgba(0,140,255,.95);outline-offset:4px}10%,30%,50%,70%,90%,100%{outline:4px solid rgba(0,140,255,0);outline-offset:8px}}@keyframes pz-lkc-error-highlight{0%,20%,40%,60%,80%{outline:4px solid rgba(214,54,56,.9);outline-offset:4px}10%,30%,50%,70%,90%,100%{outline:4px solid rgba(214,54,56,0);outline-offset:8px}}@keyframes pz-lkc-pending-loader-spin{100%{transform:rotate(1turn)}}' );
		if	($this->options['css-add-url'] ) {
			wp_enqueue_style	(self::PLUGIN_SLUG.'-css-add',		$this->options['css-add-url'],			array(),	$css_version );
		}
		$this->enqueue_excerpt_fit_script();
		if (self::AJAX_GET_ENABLED ) {
			$ajax_get_script = self::PLUGIN_SLUG.'-ajax-get';
			$ajax_get_path = $this->plugin_dir_path.'js/pz-linkcard-ajax-get.js';
			wp_enqueue_script(
				$ajax_get_script,
				$this->plugin_dir_url.'js/pz-linkcard-ajax-get.js',
				array(),
				PZLKC_PLUGIN_VERSION.'.'.(file_exists($ajax_get_path ) ? filemtime($ajax_get_path ) : '0' ),
				true
			);
			wp_localize_script($ajax_get_script, 'pzLinkCardAjaxGet', array(
				'ajaxUrl'	=> admin_url('admin-ajax.php' ),
				'nonce'		=> wp_create_nonce('pz_lkc_ajax_get' ),
				'interval'	=> 3000,
				'maxTries'	=> 40,
			) );
		}
		if	(!empty($this->options['flg-quickmenu'] ) && is_user_logged_in() && current_user_can('manage_options' ) ) {
			$quickmenu_script	=	self::PLUGIN_SLUG.'-quickmenu';
			$quickmenu_path		=	$this->plugin_dir_path.'js/pz-linkcard-quickmenu.js';
			wp_enqueue_style	('dashicons' );
			wp_enqueue_script	(
				$quickmenu_script,
				$this->plugin_dir_url.'js/pz-linkcard-quickmenu.js',
				array(),
				PZLKC_PLUGIN_VERSION.'.'.(file_exists($quickmenu_path ) ? filemtime($quickmenu_path ) : '0' ),
				true
			);
			wp_localize_script	($quickmenu_script,	'pz_lkc_quickmenu', array(
				'ajax_url'		=>	admin_url('admin-ajax.php' ),
				'nonce'			=>	wp_create_nonce('pz_lkc_refresh_card' ),
				'edit_url'		=>	$this->cacheman_url,
				'edit_nonce'	=>	wp_create_nonce('pz-cacheman' ),
				'settings_url'	=>	$this->settings_url,
				'logo_url'		=>	add_query_arg(
					'ver',
					file_exists($this->plugin_dir_path.'img/pz-linkcard_logo.svg' ) ? filemtime($this->plugin_dir_path.'img/pz-linkcard_logo.svg' ) : PZLKC_PLUGIN_VERSION,
					$this->plugin_dir_url.'img/pz-linkcard_logo.svg'
				),
				'labels'		=>	array(
					'copyTitle'			=>	__('Copy title', 'pz-linkcard' ),
					'copyExcerpt'		=>	__('Copy excerpt', 'pz-linkcard' ),
					'copyLink'			=>	__('Copy link address', 'pz-linkcard' ),
					'edit'				=>	__('Edit', 'pz-linkcard' ),
					'refreshContent'	=>	__('Refresh post content', 'pz-linkcard' ),
					'refreshThumbnail'	=>	__('Refresh thumbnail image', 'pz-linkcard' ),
					'cacheManager'		=>	__('Pz-LinkCard Manager', 'pz-linkcard' ),
					'cardSettings'		=>	__('Pz-LinkCard Settings', 'pz-linkcard' ),
					'reload'			=>	__('Reload page', 'pz-linkcard' ),
					'refreshing'		=>	__('Retrieving...', 'pz-linkcard' ),
					'failed'			=>	__('Failed to retrieve the post content.', 'pz-linkcard' ),
				),
			) );
		}
		// クリック回数
		if	($this->options['flg-click-count'] ) {
			$click_script_path = $this->plugin_dir_path.'js/click-counter.js';
			wp_enqueue_script	(
				'pz-lkc-click',	
				plugin_dir_url(__FILE__) . 'js/click-counter.js',	
				[],		
				PZLKC_PLUGIN_VERSION.'.'.(file_exists($click_script_path ) ? filemtime($click_script_path ) : '0' ),
				true );
			wp_localize_script	('pz-lkc-click',	'pz_lkc_ajax', [
				'ajax_url'		=>	admin_url('admin-ajax.php' ),
				'nonce'			=>	wp_create_nonce('pz_lkc_nonce' ),
			] );
		}
	}

	// 管理画面時の設定（スタイルシートの追加）
	public	function	action_admin_print_styles() {
		if	($this->options['survey-mode'] ) { $this->pz_OutputLog(__FUNCTION__ ); }

		global	$_wp_admin_css_colors;
		$admin_color_scheme	= get_user_option('admin_color' );
		$admin_secondary_color	= '';
		if	(isset($_wp_admin_css_colors[$admin_color_scheme]->colors[1] ) ) {
			$admin_secondary_color	= sanitize_hex_color($_wp_admin_css_colors[$admin_color_scheme]->colors[1] );
		}
		if	($admin_secondary_color ) {
			echo	'<style>:root{--pz-admin-color-secondary:'.esc_attr($admin_secondary_color).';}</style>';
		}

	}

	// 管理画面時の設定（スクリプトの追加）
	public	function	action_admin_print_scripts() {
		if	($this->options['survey-mode'] ) { $this->pz_OutputLog(__FUNCTION__ ); }

	}

	// 管理画面時の注意書き設定
	public	function	action_admin_notices() {
		if	($this->options['survey-mode'] ) { $this->pz_OutputLog(__FUNCTION__ ); }

		$dbdelta_error	=	$GLOBALS['pz_lkc_dbdelta_error'] ?? '';
		if	(!$dbdelta_error && function_exists('get_transient' ) ) {
			$dbdelta_error	=	get_transient('pz_lkc_dbdelta_error' );
			if	($dbdelta_error && function_exists('delete_transient' ) ) {
				delete_transient('pz_lkc_dbdelta_error' );
			}
		}
		if	($dbdelta_error ) {
			echo '<div class="notice notice-error is-dismissible"><p><strong>'.esc_html(self::PLUGIN_NAME).': '.esc_html__('Database error', 'pz-linkcard' ).'</strong><br><code>'.esc_html($dbdelta_error).'</code></p></div>';
		}

	//	if	($this->options['error-mode'] ) {
	//		if	(!$this->options['error-mode-hide'] ) {
	//			echo '<div class="notice notice-error is-dismissible"><p><strong>'.self::PLUGIN_NAME.': '.__('Invalid URL parameter in ', 'pz-linkcard' ).'<a href="'.$this->options['error-url'].'#lkc-error" target="_blank">'.$this->options['error-url'].'</a></strong><br>'.__('*', 'pz-linkcard' ).' '.__('You can cancel this message from <a href=".'.self::SETTINGS_URL.'">the setting screen</a>.', 'pz-linkcard' ).'</p></div>';
	//		}
	//	}
	}

	// 管理画面時の設定（フッター）
	public	function	action_admin_print_footer_scripts() {
		if	($this->options['survey-mode'] ) { $this->pz_OutputLog(__FUNCTION__ ); }

		// テキスト エディタ用のクイックタグ
		if	($this->options['flg-edit-qtag'] ) {
			if	(wp_script_is('quicktags' ) ) {
				echo '<script>QTags.addButton(\'pz-lkc\',\''.esc_js(__('Linkcard', 'pz-linkcard' ) ).'\',\'['.esc_js($this->options['code1'] ).' url="\',\'"]\',\'\',\''.esc_js(__('Make Linkcard', 'pz-linkcard' ) ).'\' );</script>';
			}
		}
		// ビジュアル エディタ用の挿入ダイアログ
		if	(!$this->is_editor_modal_screen() ) {
			return;
		}
		require_once('lib/pz-linkcard-modal.php' );
	}

	// プラグインロード後（プラガブル関数用）
	public	function	action_plugins_loaded() {
		if	($this->options['survey-mode'] ) { $this->pz_OutputLog(__FUNCTION__ ); }
	}

	// 更新完了
	public	function	action_upgrader_process_complete($upgrader_object, $options ) {
		if	($this->options['survey-mode'] ) { $this->pz_OutputLog(__FUNCTION__, '$upgrader_object='.html_entity_decode(print_r($upgrader_object, true ) ) ); }

	//	// 参考：https://club.jidaikobo.com/knowledge/177.html
	//	if			($options['action'] == 'update' && $options['type'] == 'plugin' ) {
	//		if		(isset($options['plugins'] ) && is_array($options['plugins'] ) ) {
	//			foreach	($options['plugins'] as $plugin ) {
	//				// $plugin
	//			}
	//		} else {
	//			if	(isset($options['plugin'] ) ) {
	//				// $options['plugin']
	//			}
	//		}
	//	}
	}

	private	function	pz_GetAdminBarIconSvg() {
		return	'<svg class="pz-lkc-adminbar-icon" width="20" height="20" viewBox="0 0 920 720" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false" style="width: 20px; height: 20px; margin-right: 4px; vertical-align: sub; color: currentColor;">'.
				'<path d="m 212.20737,246.02307 h 363.915" stroke="currentColor" stroke-width="46.9792" stroke-linecap="round" stroke-miterlimit="8" fill="none" fill-rule="evenodd" />'.
				'<path d="m 212.20737,379.02307 h 206.796" stroke="currentColor" stroke-width="46.9792" stroke-linecap="round" stroke-miterlimit="8" fill="none" fill-rule="evenodd" />'.
				'<path d="m 759.5039,252.24389 c -25.0068,0.18279 -50.0786,8.39512 -70.6035,25.02343 l -52.4609,42.5293 a 73.737587,75.212334 0 0 1 7.8105,-0.42383 73.737587,75.212334 0 0 1 47.5313,17.71094 l 28.791,-23.33984 c 13.2574,-10.74062 29.5717,-15.28849 45.3555,-13.95508 h 0.01 c 15.7837,1.33337 31.0307,8.55424 42.1504,21.35156 22.2351,25.59039 18.8419,63.01751 -7.6699,84.49609 L 658.4219,520.74193 c -26.511,21.47395 -65.2546,18.20002 -87.4941,-7.38281 -16.1178,-18.57162 -18.7691,-43.38191 -8.8829,-63.80273 l -53.1757,43.70312 c 1e-4,6.4e-4 -10e-5,0.001 0,0.002 3.2097,18.12781 11.2461,35.66986 24.2812,50.68945 v 0.008 c 39.3609,45.29988 110.0193,51.26928 156.9336,13.26172 L 832.0703,442.11303 v -0.008 C 878.9804,404.09382 885.1768,335.83074 845.8145,290.52904 826.1343,267.87912 798.6261,255.0598 770.1758,252.656 h 0.027 c -3.5563,-0.30047 -7.1269,-0.43823 -10.6993,-0.41211 z" fill="currentColor" stroke="currentColor" stroke-width="3.54463" fill-rule="evenodd" />'.
				'<path d="m 483.2719,669.21419 c 25.0068,-0.18279 50.0786,-8.39512 70.6035,-25.02343 l 52.4609,-42.5293 a 73.737587,75.212334 0 0 1 -7.8105,0.42383 73.737587,75.212334 0 0 1 -47.5313,-17.71094 l -28.791,23.33984 c -13.2574,10.74062 -29.5717,15.28849 -45.3555,13.95508 h -0.01 c -15.7837,-1.33337 -31.0307,-8.55424 -42.1504,-21.35156 -22.2351,-25.59039 -18.8419,-63.01751 7.6699,-84.49609 L 584.3539,400.71616 c 26.511,-21.47395 65.2546,-18.20002 87.4941,7.38281 16.1178,18.57162 18.7691,43.38191 8.8829,63.80273 l 53.1757,-43.70313 c -10e-5,-6.3e-4 10e-5,-9.9e-4 0,-0.002 -3.2097,-18.12781 -11.2461,-35.66986 -24.2812,-50.68945 v -0.008 C 670.2645,332.19925 599.6061,326.22985 552.6918,364.23741 L 410.7055,479.34485 v 0.008 c -46.9101,38.0114 -53.1065,106.27448 -13.7442,151.57618 19.6802,22.64992 47.1884,35.46924 75.6387,37.87304 h -0.027 c 3.5563,0.30047 7.1269,0.43823 10.6993,0.41211 z" fill="currentColor" stroke="currentColor" stroke-width="3.54463" fill-rule="evenodd" />'.
				'<path d="m 838.28841,490.2264 10e-6,69.2172 c 0,24.97305 -20.71999,45.07772 -46.45737,45.07772 l -89.85895,-10e-6" fill="none" stroke="currentColor" stroke-width="45.7624" stroke-linecap="round" stroke-linejoin="miter" stroke-miterlimit="50" />'.
				'<path d="m 336.05804,601.11245 h -208.8833 c -27.7,0 -50.000004,-22.3 -50.000004,-50 V 151.88977 c 0,-27.7 22.300004,-50 50.000004,-50 v 0 h 657.30431 c 27.7,0 50,22.3 50,50 l -10e-5,74.25782" fill="none" stroke="currentColor" stroke-width="50" stroke-linecap="round" stroke-linejoin="miter" stroke-miterlimit="50" />'.
				'</svg>';
	}

	// 管理バーのメニュー追加（記述エラーやリンク切れなど）（未実装）
	public	function	action_wp_before_admin_bar_render() {
		if	($this->options['survey-mode'] ) { $this->pz_OutputLog(__FUNCTION__ ); }

		if	(empty($this->pz_GetRequestOption('flg-adminbar', $this->options['flg-adminbar'] ) ) ) {
			return;
		}

		global $wp_admin_bar;
		$wp_admin_bar->add_menu(array('id' => 'pz-lkc',									'title' => $this->pz_GetAdminBarIconSvg().esc_html__('Pz Card', 'pz-linkcard' ),				'href' => '#' ) );
		$wp_admin_bar->add_menu(array('id' => 'pz-settings',	'parent' => 'pz-lkc',	'title' => __('Pz-LinkCard Manager',	'pz-linkcard' ),	'href' => $this->cacheman_url,	'meta' => array('target' => '_parent' ) ) );
		$wp_admin_bar->add_menu(array('id' => 'pz-cacheman',	'parent' => 'pz-lkc',	'title' => __('Pz-LinkCard Settings',	'pz-linkcard' ),	'href' => $this->settings_url,	'meta' => array('target' => '_parent' ) ) );
	}

	// 現在のリクエストで送信された設定値を優先して返す
	private	function	pz_GetRequestOption($key, $default = null ) {
		if	(isset($_POST['properties'] ) && is_array($_POST['properties'] ) && array_key_exists($key, $_POST['properties'] ) ) {
			return	stripslashes($_POST['properties'][$key] );
		}
		return		(array_key_exists($key, $this->options ) ? $this->options[$key] : $default );
	}

	// 設定画面プレビュー用CSS生成
	public	function	action_ajax_pz_lkc_preview_render() {
		if	(!current_user_can('manage_options' ) ) {
			wp_send_json_error('forbidden', 403 );
		}
		if	(!check_ajax_referer('pz_lkc_preview_render', 'nonce', false ) ) {
			wp_send_json_error('invalid nonce', 403 );
		}

		$original_options	=	$this->options;
		$properties			=	isset($_POST['properties'] ) && is_array($_POST['properties'] ) ? wp_unslash($_POST['properties'] ) : array();
		$definitions		=	self::pz_GetOptionDefinitions();
		$preview_options	=	array_merge(self::pz_GetDefaultOptions(), is_array($this->options ) ? $this->options : array() );

		foreach	($definitions as $key => $definition ) {
			if	(array_key_exists($key, $properties ) ) {
				$preview_options[$key]	=	$properties[$key];
			}
		}
		$this->options	=	$preview_options;
		if	(!defined('LIST_BORDER' ) ) {
			define('LIST_BORDER', array(
				'none'		=>	'None',
				'solid'		=>	'Solid',
				'dotted'	=>	'Dotted',
				'dashed'	=>	'Dashed',
				'double'	=>	'Double',
				'groove'	=>	'Groove',
				'ridge'		=>	'Ridge',
				'inset'		=>	'Inset',
				'outset'	=>	'Outset',
			) );
		}
		if	(!function_exists('pz_TrimNumPx' ) ) {
			function	pz_TrimNumPx($val, $unit_percent = false ) {
				$val	=	mb_convert_kana($val, 'n' );
				$val	=	strtolower($val );
				$unit	=	'px';
				if	(($unit_percent == true ) && (substr($val, -1 ) == '%' ) ) {
					$unit	=	'%';
				}
				$val	=	preg_replace('/[^0-9]/', '', $val );
				switch	($val ) {
				case	null:
				case	0:
					return	$val;
				}
				return	$val.$unit;
			}
		}
		require('lib/pz-linkcard-settings-validate.php' );
		$this->pz_SetStyle('preview' );

		$css_file	=	PZLKC_DIR_STYLE.'preview.css';
		$css		=	file_exists($css_file ) ? file_get_contents($css_file ) : '';
		$this->options	=	$original_options;

		wp_send_json_success(array(
			'css'	=>	$css,
		) );
	}

	// 設定画面プレビュー位置保存
	public	function	action_ajax_pz_lkc_preview_state() {
		if	(!current_user_can('manage_options' ) ) {
			wp_send_json_error('forbidden', 403 );
		}
		if	(!check_ajax_referer('pz_lkc_preview_state', 'nonce', false ) ) {
			wp_send_json_error('invalid nonce', 403 );
		}

		$mode	=	isset($_POST['preview-mode'] ) ? sanitize_key(wp_unslash($_POST['preview-mode'] ) ) : 'window';
		if	(!in_array($mode, array('window', 'docked', 'right' ), true ) ) {
			$mode	=	'window';
		}
		$this->options['preview-mode']	=	$mode;
		$this->options['preview-two-cards']	=	isset($_POST['preview-two-cards'] ) && intval(wp_unslash($_POST['preview-two-cards'] ) ) ? 1 : 0;

		foreach	(array('preview-left', 'preview-top', 'preview-width', 'preview-height', 'preview-docked-height', 'preview-right-docked-width' ) as $key ) {
			if	(!isset($_POST[$key] ) || $_POST[$key] === '' ) {
				$this->options[$key]	=	null;
				continue;
			}
			$value	=	intval(wp_unslash($_POST[$key] ) );
			if	(in_array($key, array('preview-width', 'preview-height', 'preview-docked-height', 'preview-right-docked-width' ), true ) ) {
				$value	=	max(0, $value );
			}
			$this->options[$key]	=	$value;
		}

		$options	=	get_option(self::OPTION_NAME, self::pz_GetDefaultOptions() );
		if	(!is_array($options ) ) {
			$options	=	self::pz_GetDefaultOptions();
		}
		foreach	(array('preview-mode', 'preview-left', 'preview-top', 'preview-width', 'preview-height', 'preview-docked-height', 'preview-right-docked-width', 'preview-two-cards' ) as $key ) {
			$options[$key]	=	$this->options[$key] ?? null;
		}
		update_option(self::OPTION_NAME, $options );
		$this->options	=	$options;

		wp_send_json_success();
	}

	// URLパラメーターエラー通知を閉じたときにエラー状態を解除
	public	function	action_ajax_pz_lkc_error_mode_clear() {
		if	(!current_user_can('manage_options' ) ) {
			wp_send_json_error('forbidden', 403 );
		}
		if	(!check_ajax_referer('pz_lkc_clear_error_mode', 'nonce', false ) ) {
			wp_send_json_error('invalid nonce', 403 );
		}

		$this->options['error-mode']	=	0;
		$this->pz_SaveOptions();

		wp_send_json_success();
	}

	// 管理画面・表示オプション保存
	public	function	action_ajax_pz_lkc_save_cacheman_columns() {
		if	(!current_user_can('manage_options' ) ) {
			wp_send_json_error('forbidden', 403 );
		}
		if	(!check_ajax_referer('pz_lkc_cacheman_columns', 'nonce', false ) ) {
			wp_send_json_error('invalid nonce', 403 );
		}

		$allowed_columns	=	$this->pz_GetCachemanColumnKeys();
		$columns			=	isset($_POST['columns'] ) && is_array($_POST['columns'] ) ? map_deep(wp_unslash($_POST['columns'] ), 'sanitize_text_field' ) : array();
		$save_columns		=	array();

		foreach	($allowed_columns as $column ) {
			$save_columns[$column]	=	isset($columns[$column] ) && ('1' === (string) $columns[$column] || 1 === $columns[$column] );
		}
		update_user_meta(get_current_user_id(), 'pz_lkc_cacheman_columns', $save_columns );

		$allowed_per_page	=	$this->pz_GetCachemanPerPageChoices();
		$per_page			=	isset($_POST['per_page'] ) ? absint(wp_unslash($_POST['per_page'] ) ) : 0;
		if	(in_array($per_page, $allowed_per_page, true ) ) {
			update_user_meta(get_current_user_id(), 'pz_lkc_cacheman_per_page', $per_page );
		} else {
			$per_page	=	intval(get_user_meta(get_current_user_id(), 'pz_lkc_cacheman_per_page', true ) );
			if	(!in_array($per_page, $allowed_per_page, true ) ) {
				$per_page	=	10;
			}
		}

		wp_send_json_success(array(
			'columns'	=>	$save_columns,
			'per_page'	=>	$per_page,
		) );
	}

	public	function	action_ajax_pz_lkc_find_used_posts() {
		if	(!current_user_can('manage_options' ) ) {
			wp_send_json_error(__('You do not have permission to update LinkCard data.', 'pz-linkcard' ), 403 );
		}
		if	(!check_ajax_referer('pz_lkc_find_used_posts', 'nonce', false ) ) {
			wp_send_json_error(__('Invalid request.', 'pz-linkcard' ), 403 );
		}

		$card_id	=	isset($_POST['card_id'] ) ? absint(wp_unslash($_POST['card_id'] ) ) : 0;
		$data		=	$card_id > 0 ? $this->pz_GetCache(array('id' => $card_id ) ) : null;
		if	(!is_array($data ) || empty($data['url'] ) ) {
			wp_send_json_error(__('Invalid card ID', 'pz-linkcard' ), 400 );
		}

		global	$wpdb;
		$post_ids	=	$wpdb->get_col($wpdb->prepare(
			"SELECT ID FROM {$wpdb->posts} WHERE post_type = 'post' AND post_content LIKE %s ORDER BY ID ASC LIMIT 6",
			'%'.$wpdb->esc_like($data['url'] ).'%'
		) );

		wp_send_json_success(array('post_ids' => array_values(array_map('intval', $post_ids ) ) ) );
	}

	public	function	action_ajax_pz_lkc_cacheman_pending() {
		if	(!current_user_can('manage_options' ) ) {
			wp_send_json_error(__('You do not have permission to update LinkCard data.', 'pz-linkcard' ), 403 );
		}
		if	(!check_ajax_referer('pz_lkc_cacheman_pending', 'nonce', false ) ) {
			wp_send_json_error(__('Invalid request.', 'pz-linkcard' ), 403 );
		}

		$card_ids	= isset($_POST['card_ids'] ) && is_array($_POST['card_ids'] )
			? array_values(array_unique(array_filter(array_map('absint', wp_unslash($_POST['card_ids'] ) ) ) ) )
			: array();
		$ready_ids	= array();
		$ready_cards	= array();
		foreach	($card_ids as $card_id ) {
			$data = $this->pz_GetCache(array('id' => $card_id ) );
			if	(is_array($data ) && isset($data['regist_time'] ) && intval($data['regist_time'] ) > 0 ) {
				$ready_ids[] = $card_id;
				$ready_cards[] = array(
					'id'	=> $card_id,
					'title'	=> wp_strip_all_tags(stripslashes((string) ($data['title'] ?? '' ) ) ),
				);
			}
		}

		wp_send_json_success(array(
			'ready_ids'	=> $ready_ids,
			'ready_cards'	=> $ready_cards,
		) );
	}

	private	function	pz_GetCachemanColumnKeys() {
		return	array('id', 'excerpt', 'charset', 'domain', 'sns', 'post_id', 'post_date', 'post_modified', 'regist_time', 'update_time', 'sns_time', 'alive_time', 'sns_nexttime', 'alive_nexttime', 'use_post_id', 'click_count', 'result' );
	}

	private	function	pz_GetCachemanPerPageChoices() {
		return	array(10, 20, 50, 100);
	}

	public	function	action_ajax_pz_lkc_refresh_card() {
		if	(!current_user_can('manage_options' ) ) {
			wp_send_json_error(__('You do not have permission to update LinkCard data.', 'pz-linkcard' ), 403 );
		}
		if	(!check_ajax_referer('pz_lkc_refresh_card', 'nonce', false ) ) {
			wp_send_json_error(__('Invalid request.', 'pz-linkcard' ), 403 );
		}

		$card_id	=	isset($_POST['card_id'] ) ? absint(wp_unslash($_POST['card_id'] ) ) : 0;
		if	($card_id <= 0 ) {
			wp_send_json_error(__('Invalid card ID', 'pz-linkcard' ), 400 );
		}

		$data	=	$this->pz_GetCache(array('id' => $card_id ) );
		if	(!isset($data ) || !is_array($data ) || empty($data['url'] ) ) {
			wp_send_json_error(__('Not selected', 'pz-linkcard' ), 404 );
		}

		$html	=	$this->pz_GetLinkCard(array('url' => $data['url'], 'force' => true ) );
		if	(!$html ) {
			wp_send_json_error(__('Failed to retrieve the post content.', 'pz-linkcard' ), 500 );
		}

		wp_send_json_success(array(
			'card_id'	=>	$card_id,
			'html'		=>	$html,
		) );
	}

	public	function	action_ajax_pz_lkc_refresh_thumbnail() {
		if	(!current_user_can('manage_options' ) ) {
			wp_send_json_error(__('You do not have permission to update LinkCard data.', 'pz-linkcard' ), 403 );
		}
		if	(!check_ajax_referer('pz_lkc_refresh_card', 'nonce', false ) ) {
			wp_send_json_error(__('Invalid request.', 'pz-linkcard' ), 403 );
		}

		$card_id	=	isset($_POST['card_id'] ) ? absint(wp_unslash($_POST['card_id'] ) ) : 0;
		if	($card_id <= 0 ) {
			wp_send_json_error(__('Invalid card ID', 'pz-linkcard' ), 400 );
		}

		$data	=	$this->pz_GetCache(array('id' => $card_id ) );
		if	(!isset($data ) || !is_array($data ) || empty($data['url'] ) ) {
			wp_send_json_error(__('Not selected', 'pz-linkcard' ), 404 );
		}
		if	(!empty($data['thumbnail'] ) ) {
			$this->pz_GetImage($data['thumbnail'], true );
		}

		$html	=	$this->pz_GetLinkCard(array('url' => $data['url'] ) );
		if	(!$html ) {
			wp_send_json_error(__('Failed to retrieve the post content.', 'pz-linkcard' ), 500 );
		}

		wp_send_json_success(array(
			'card_id'	=>	$card_id,
			'html'		=>	$html,
		) );
	}

	// ビジュアルエディターのリンクカード挿入ダイアログから記事タイトルを検索
	public	function	action_ajax_pz_lkc_mce_post_search() {
		if	(!current_user_can('edit_posts' ) ) {
			wp_send_json_error(__('You do not have permission to update LinkCard data.', 'pz-linkcard' ), 403 );
		}
		if	(!check_ajax_referer('pz_lkc_mce_post_search', 'nonce', false ) ) {
			wp_send_json_error(__('Invalid request.', 'pz-linkcard' ), 403 );
		}

		$keyword	=	isset($_POST['keyword'] ) ? sanitize_text_field(wp_unslash($_POST['keyword'] ) ) : '';
		if	($keyword === '' ) {
			wp_send_json_success(array() );
		}

		$post_types	=	array_values(get_post_types(array('public' => true ), 'names' ) );
		$post_types	=	array_values(array_diff($post_types, array('attachment' ) ) );
		if	(!$post_types ) {
			wp_send_json_success(array() );
		}

		global	$wpdb;
		$type_placeholders	=	implode(', ', array_fill(0, count($post_types ), '%s' ) );
		$sql	=	"SELECT ID FROM {$wpdb->posts} WHERE post_status = 'publish' AND post_type IN ({$type_placeholders}) AND post_title LIKE %s ORDER BY post_date DESC LIMIT 50";
		$query_args	=	array_merge($post_types, array('%'.$wpdb->esc_like($keyword ).'%' ) );
		$post_ids	=	$wpdb->get_col($wpdb->prepare($sql, $query_args ) );
		$results	=	array();
		foreach	($post_ids as $post_id ) {
			$results[]	=	array(
				'title'	=>	get_the_title($post_id ),
				'date'	=>	get_the_date(get_option('date_format' ), $post_id ),
				'published_date'	=>	get_the_date(get_option('date_format' ), $post_id ),
				'modified_date'	=>	get_the_modified_date(get_option('date_format' ), $post_id ),
				'excerpt'	=>	preg_replace('/\s+/u', ' ', wp_strip_all_tags(get_the_excerpt($post_id ) ) ),
				'thumbnail'	=>	get_the_post_thumbnail_url($post_id, 'thumbnail' ) ?: '',
				'url'	=>	get_permalink($post_id ),
			);
		}

		wp_send_json_success($results );
	}

	// ビジュアルエディター内のリンクカードプレビュー
	// クリックカウント
	public	function	action_ajax_pz_lkc_mce_render_card() {
		if	(!current_user_can('edit_posts' ) ) {
			wp_send_json_error(__('You do not have permission to update LinkCard data.', 'pz-linkcard' ), 403 );
		}
		if	(!check_ajax_referer('pz_lkc_mce_render_card', 'nonce', false ) ) {
			wp_send_json_error(__('Invalid request.', 'pz-linkcard' ), 403 );
		}
		$shortcode	= isset($_POST['shortcode'] ) ? trim(wp_unslash($_POST['shortcode'] ) ) : '';
		$codes		= array_values(array_filter(array_unique(array_map('strval', array(
			$this->options['code1'] ?? '', $this->options['code2'] ?? '', $this->options['code3'] ?? '',
		) ) ) ) );
		$quoted		= array_map(function($code ) { return preg_quote($code, '/' ); }, $codes );
		$pattern	= $quoted ? '/^\[('.implode('|', $quoted ).')\b([^\]]*)\]$/i' : '';
		if	(!$pattern || !preg_match($pattern, $shortcode, $matches ) ) {
			wp_send_json_error(__('Invalid request.', 'pz-linkcard' ), 400 );
		}
		$atts	= shortcode_parse_atts($matches[2] );
		$atts	= is_array($atts ) ? $atts : array();
		$url	= $atts['url'] ?? $atts['href'] ?? $atts['uri'] ?? $atts['ur1'] ?? '';
		if	(is_string($url ) && preg_match('/(https?:\/\/[^\s<>]+)/sui', $url, $url_match ) ) {
			$url	= $url_match[1];
		}
		if	(is_string($url ) && $url && $this->options['flg-relative-url'] && !mb_strpos($url, '://' ) ) {
			$url	= $this->pz_RelToURL(esc_url(home_url() ), $url );
		}
		if	(is_string($url ) && $url ) {
			$url	= $this->pz_EncodeURL($url, true );
		}
		$url_info	= $url ? $this->Pz_GetURLInfo($url ) : null;
		if	(!empty($url_info['is_internal'] ) ) {
			$post_id	= url_to_postid($url );
			$post		= $post_id ? get_post($post_id ) : null;
			if	($post_id && (!$post || !$this->pz_IsPostPubliclyViewable($post ) ) ) {
				if	(!empty($this->options['flg-public-only'] ) ) {
					wp_send_json_error(__('Only publicly viewable posts can be previewed with the current setting.', 'pz-linkcard' ), 403 );
				}
				if	(!$post ) {
					wp_send_json_error(__('Post not found.', 'pz-linkcard' ), 404 );
				}
				if	(post_password_required($post ) ) {
					wp_send_json_error(__('You do not have permission to preview this post.', 'pz-linkcard' ), 403 );
				}
				if	(!current_user_can('read_post', $post_id ) ) {
					wp_send_json_error(__('You do not have permission to preview this post.', 'pz-linkcard' ), 403 );
				}
				$preview_data	= $this->pz_GetPost(array('url' => $url ), true );
				if	(!is_array($preview_data ) ) {
					wp_send_json_error(__('Failed to retrieve the post content.', 'pz-linkcard' ), 500 );
				}
				$atts['preview-data']	= $preview_data;
			}
		}
		$html	= $this->shortcode($atts, null, $matches[1] );
		if	(!$html ) {
			wp_send_json_error(__('Failed to retrieve the post content.', 'pz-linkcard' ), 500 );
		}
		wp_send_json_success(array('html' => $html ) );
	}

	public	function	action_ajax_pz_lkc_click_count() {
		if	(array_key_exists('debug-mode', $this->options ) && $this->options['debug-mode'] && array_key_exists('survey-mode', $this->options ) && $this->options['survey-mode'] ) { $this->pz_OutputLog(__FUNCTION__ ); }

		// nonce チェック
		if	(!check_ajax_referer('pz_lkc_nonce', 'nonce', false ) ) {
			wp_send_json_error('invalid nonce');
		}

		// 入力チェック
		$lkc_id	=	isset($_POST['lkc_id'])	?	intval($_POST['lkc_id'] )		:	'';
		$post_id	=	isset($_POST['post_id'])	?	absint($_POST['post_id'] )		:	0;
		$click_token	=	isset($_POST['click_token'] ) ? sanitize_text_field(wp_unslash($_POST['click_token'] ) ) : '';
		if	(empty($lkc_id)) {
			wp_send_json_error('unknown Data ID.');
			return;
		}
		$expected_click_token	=	$this->pz_GetClickToken($lkc_id, $post_id );
		if	($click_token === '' || !hash_equals($expected_click_token, $click_token ) ) {
			wp_send_json_error('invalid click token', 403 );
			return;
		}

		// カウントをインクリメント
		global $wpdb;
		$click_time	=	current_time('timestamp', false );
		$user_ip	=	isset($_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field(wp_unslash($_SERVER['REMOTE_ADDR'] ) ) : '';
		if	(!filter_var($user_ip, FILTER_VALIDATE_IP ) ) {
			$user_ip	=	'';
		}
		$ip_hash	=	$user_ip ? hash_hmac('sha256', 'pz-linkcard-click|'.date_i18n('Y-m-d', $click_time ).'|'.$user_ip, wp_salt('auth') ) : '';
		$user_agent	=	isset($_SERVER['HTTP_USER_AGENT'] ) ? sanitize_text_field(wp_unslash($_SERVER['HTTP_USER_AGENT'] ) ) : '';
		$user_agent	=	function_exists('mb_substr' ) ? mb_substr($user_agent, 0, 1024 ) : substr($user_agent, 0, 1024 );
		$click_log_enabled	=	!empty($this->options['flg-click-count'] ) && !empty($this->options['flg-click-log'] );

		// 同じカード・記事・IPハッシュの30秒以内のクリックは重複とする
		if	($click_log_enabled ) {
			$wpdb->query('START TRANSACTION' );
			$card_exists	=	$wpdb->get_var($wpdb->prepare("SELECT id FROM $this->db_card WHERE id = %d FOR UPDATE", $lkc_id ) );
			if	(!$card_exists ) {
				$wpdb->query('ROLLBACK' );
				wp_send_json_error('DB update failed');
				return;
			}
			$duplicate_click_id	=	$wpdb->get_var($wpdb->prepare(
				"SELECT click_id FROM $this->db_click
				WHERE card_id = %d
					AND click_time >= %d
					AND COALESCE(post_id, 0) = %d
					AND COALESCE(ip_hash, '') = %s
				ORDER BY click_time DESC
				LIMIT 1 FOR UPDATE",
				$lkc_id,
				$click_time - 30,
				$post_id,
				$ip_hash
			) );
			if	($duplicate_click_id ) {
				$wpdb->query('COMMIT' );
				wp_send_json_success('Click already counted');
				return;
			}
		}

		$updated = $wpdb->query($wpdb->prepare("UPDATE $this->db_card SET click_count = click_count + 1 WHERE id = %d;", $lkc_id ) );
		if	($updated && $click_log_enabled ) {
			$logged		=	$wpdb->insert(
				$this->db_click,
				array(
					'click_time'	=>	$click_time,
					'card_id'		=>	$lkc_id,
					'post_id'		=>	$post_id ?: null,
					'ip_hash'		=>	$ip_hash ?: null,
					'user_agent'	=>	$user_agent ?: null,
				),
				array('%d', '%d', '%d', '%s', '%s' )
			);
			if	($logged === false ) {
				$wpdb->query('ROLLBACK' );
				wp_send_json_error('Click log failed');
				return;
			}
			$wpdb->query('COMMIT' );
		}

		// 更新結果
		if	($updated ) {
			wp_send_json_success('Click counted');
		} else {
			if	($click_log_enabled ) {
				$wpdb->query('ROLLBACK' );
			}
			wp_send_json_error('DB update failed');
		}
	}

	// 管理画面＞プラグイン＞一覧＞クイックメニュー
	public	function	filter_plugin_action_links($links ) {
		if	($this->options['survey-mode'] ) { $this->pz_OutputLog(__FUNCTION__, '$links='.print_r($links, true ) ); }

		$links['manager']	=	'<a href="'.$this->cacheman_url.'">'.__('Manager' , 'pz-linkcard' ).'</a>';
		$links['settings']	=	'<a href="'.$this->settings_url.'">'.__('Settings', 'pz-linkcard' ).'</a>';
		return	$links;
	}

	// 管理画面時のスタイルシート、スクリプト設定
	public	function	filter_mce_external_plugins($plugins ) {
		if	($this->options['survey-mode'] ) { $this->pz_OutputLog(__FUNCTION__, '$plugins='.print_r($plugins, true ) ); }

		if	($this->options['flg-edit-insert'] || $this->options['flg-edit-preview'] ) {
			$mce_script	=	$this->plugin_dir_path.'js/mce-button.js';
			$plugins[ "pz_linkcard_tinymce" ]	=	add_query_arg('ver', file_exists($mce_script ) ? filemtime($mce_script ) : PZLKC_PLUGIN_VERSION, $this->plugin_dir_url.'js/mce-button.js' );
		}
		return	$plugins;
	}

	// 管理画面時のスタイルシート、スクリプト設定
	public	function	filter_mce_buttons($buttons ) {
		if	($this->options['survey-mode'] ) { $this->pz_OutputLog(__FUNCTION__, '$buttons='.print_r($buttons, true ) ); }

		if	($this->options['flg-edit-insert'] ) {
			$buttons[]							=	'pz_linkcard_insert_shortcode';
		}
		return	$buttons;
	}

	// クリックログ整理の次回実行を、サイトのタイムゾーンの午前4時に予約
	private	function	pz_ScheduleClickLogCleanup() {
		if	(wp_next_scheduled(self::CRON_CLICK_CLEANUP ) ) {
			return;
		}
		$now	= new DateTimeImmutable('now', wp_timezone() );
		$next	= $now->setTime(4, 0, 0 );
		if	($next <= $now ) {
			$next	= $next->modify('+1 day' );
		}
		wp_schedule_single_event($next->getTimestamp(), self::CRON_CLICK_CLEANUP );
	}

	// 保存期間から期限日時を求める（存在しない月末日は対象月の末日に丸める）
	private	function	pz_GetClickLogCutoff($retention ) {
		$period_months	= array('1m' => 1, '2m' => 2, '3m' => 3, '6m' => 6, '1y' => 12, '2y' => 24, '3y' => 36, '5y' => 60 );
		$now	= new DateTimeImmutable('now', wp_timezone() );
		if	($retention === '1w' ) {
			$cutoff	= $now->modify('-7 days' );
		} elseif (isset($period_months[$retention] ) ) {
			$total_month	= (intval($now->format('Y' ) ) * 12 ) + intval($now->format('n' ) ) - 1 - $period_months[$retention];
			$target_year	= intdiv($total_month, 12 );
			$target_month	= ($total_month % 12 ) + 1;
			$target_month_first	= new DateTimeImmutable(sprintf('%04d-%02d-01', $target_year, $target_month ), wp_timezone() );
			$target_day	= min(intval($now->format('j' ) ), intval($target_month_first->format('t' ) ) );
			$cutoff	= $now->setDate($target_year, $target_month, $target_day );
		} else {
			return	null;
		}

		// click_time は current_time('timestamp', false) と同じローカル時刻形式で保存されている
		return	$cutoff->getTimestamp() + $cutoff->getOffset();
	}

	// 期限切れ・孤立クリックログを一度に最大5,000件ずつ削除
	private	function	pz_DeleteExpiredClickLogs() {
		global $wpdb;
		$result	= array('expired' => 0, 'orphaned' => 0 );
		if	($wpdb->get_var($wpdb->prepare("SHOW TABLES LIKE %s", $this->db_click ) ) !== $this->db_click ) {
			return	$result;
		}

		$retention	= strval($this->options['click-log-retention'] ?? '3m' );
		if	(!in_array($retention, array('1w', '1m', '2m', '3m', '6m', '1y', '2y', '3y', '5y', 'forever' ), true ) ) {
			$retention	= '3m';
		}
		if	($retention !== 'forever' ) {
			$cutoff	= $this->pz_GetClickLogCutoff($retention );
			$deleted	= $wpdb->query($wpdb->prepare("DELETE FROM $this->db_click WHERE click_time < %d ORDER BY click_time ASC LIMIT 5000", $cutoff ) );
			$result['expired']	= $deleted === false ? 0 : intval($deleted );
		}

		$deleted	= $wpdb->query("DELETE FROM $this->db_click WHERE NOT EXISTS (SELECT 1 FROM $this->db_card WHERE $this->db_card.id = $this->db_click.card_id) LIMIT 5000" );
		$result['orphaned']	= $deleted === false ? 0 : intval($deleted );
		return	$result;
	}

	// クリックログをすべて削除（累計クリック数は維持）
	private	function	pz_DeleteAllClickLogs() {
		global $wpdb;
		if	($wpdb->get_var($wpdb->prepare("SHOW TABLES LIKE %s", $this->db_click ) ) !== $this->db_click ) {
			return	0;
		}
		$deleted	= $wpdb->query("DELETE FROM $this->db_click" );
		return	$deleted === false ? false : intval($deleted );
	}

	// Delete regular files under an upload subdirectory, optionally limited by extension.
	private	function	pz_DeleteUploadDirectoryFiles($directory, $extension = null ) {
		$directory	= trim((string) $directory );
		$real_directory	= $directory !== '' ? realpath($directory ) : false;
		$upload_directory	= defined('PZLKC_DIR_UPLOAD' ) ? realpath(PZLKC_DIR_UPLOAD ) : false;
		$result	= array('valid' => false, 'deleted' => 0, 'failed' => 0 );
		if	($real_directory === false || $upload_directory === false || !is_dir($real_directory ) ) {
			return	$result;
		}

		$normalized_directory	= strtolower(wp_normalize_path($real_directory ) );
		$normalized_upload	= strtolower(wp_normalize_path($upload_directory ) );
		$upload_root	= trailingslashit($normalized_upload );
		if	($normalized_directory === $normalized_upload || strpos(trailingslashit($normalized_directory ), $upload_root ) !== 0 ) {
			return	$result;
		}
		$result['valid']	= true;
		$file_root	= trailingslashit(wp_normalize_path($real_directory ) );

		try {
			$iterator	= new RecursiveIteratorIterator(
				new RecursiveDirectoryIterator($real_directory, FilesystemIterator::SKIP_DOTS )
			);
			foreach	($iterator as $file ) {
				if	(!$file->isFile() || $file->isLink() ) {
					continue;
				}
				if	($extension !== null && strtolower($file->getExtension() ) !== strtolower((string) $extension ) ) {
					continue;
				}
				$real_file	= realpath($file->getPathname() );
				if	($real_file === false || strpos(strtolower(wp_normalize_path($real_file ) ), strtolower($file_root ) ) !== 0 ) {
					$result['failed']++;
					continue;
				}
				wp_delete_file($real_file );
				if	(!file_exists($real_file ) ) {
					$result['deleted']++;
				} else {
					$result['failed']++;
				}
			}
		} catch (Throwable $exception ) {
			$result['failed']++;
		}
		return	$result;
	}

	// WP-CRONスケジュール（SNSカウント取得）
	public	function	schedule_hook_check() {
		if	($this->options['survey-mode'] ) { $this->pz_OutputLog(__FUNCTION__ ); }

		$log	=	null;
		require_once('lib/pz-linkcard-cron-sns.php' );
		return		$log;
	}

	private	function	pz_SyncCronSchedules() {
		$cron_schedules		=	wp_get_schedules();
		$sns_period			=	isset($this->options['sns-period'] ) ? (string) $this->options['sns-period'] : '';
		$alive_period		=	isset($this->options['alive-period'] ) ? (string) $this->options['alive-period'] : '';
		$sns_interval		=	isset($cron_schedules[$sns_period]['interval'] ) ? intval($cron_schedules[$sns_period]['interval'] ) : 0;
		$alive_interval		=	isset($cron_schedules[$alive_period]['interval'] ) ? intval($cron_schedules[$alive_period]['interval'] ) : 0;
		$sns_first_delay	=	30 * MINUTE_IN_SECONDS;
		$alive_first_delay	=	intdiv($sns_interval + $alive_interval, 4 ) + $sns_first_delay;

		if	(!$sns_period ) {
			wp_clear_scheduled_hook(self::CRON_CHECK );
		} elseif	(wp_get_schedule(self::CRON_CHECK ) !== $sns_period ) {
			wp_clear_scheduled_hook(self::CRON_CHECK );
			wp_schedule_event(time() + $sns_first_delay, $sns_period, self::CRON_CHECK );
		}

		if	(!$alive_period ) {
			wp_clear_scheduled_hook(self::CRON_ALIVE );
		} elseif	(wp_get_schedule(self::CRON_ALIVE ) !== $alive_period ) {
			wp_clear_scheduled_hook(self::CRON_ALIVE );
			wp_schedule_event(time() + $alive_first_delay, $alive_period, self::CRON_ALIVE );
		}
	}

	// WP-CRONスケジュール（存在チェック）
	public	function	schedule_hook_alive() {
		if	($this->options['survey-mode'] ) { $this->pz_OutputLog(__FUNCTION__ ); }

		$log	=	null;
		require_once('lib/pz-linkcard-cron-alive.php' );
		return		$log;
	}

	// WP-CRONスケジュール（クリックログ整理）
	public	function	schedule_hook_click_cleanup() {
		$result	= $this->pz_DeleteExpiredClickLogs();
		$this->pz_ScheduleClickLogCleanup();
		return	'Expired click logs: '.$result['expired'].PHP_EOL.'Orphaned click logs: '.$result['orphaned'].PHP_EOL;
	}

	public function schedule_hook_get() {
		require('lib/pz-linkcard-cron-get.php' );
	}

	public function action_ajax_pz_lkc_ajax_get() {
		require('lib/pz-linkcard-ajax-get.php' );
	}

	// デバグ用の文字列表示
	private	function	pz_OutputLog($function, $user_message = '', $separate = '' ) {
		if	(empty($function ) && empty($user_message ) && empty($separate ) ) {
			return;
		}

		if	(is_dir(PZLKC_DIR_DEBUG ) ) {
			$filename		=	PZLKC_DIR_DEBUG.$this->slug.'_'.date('Ymd', current_time('timestamp', false ) ).'.log';
			if	(function_exists('microtime' ) ) {
				$timestamp	=	microtime(true );
				$dt			=	intval($timestamp );
				$ms			=	substr(intval($timestamp * 1000 ), -3, 3 );
				$timestamp	=	wp_date('Y-m-d H:i:s', $dt ).'.'.$ms;
			} else {
				$timestamp	=	date('Y-m-d H:i:s', current_time('timestamp', false ) );
			}
			$count			=	sprintf('%03d', $this->test_count++ );
			$message		=	($separate ? PHP_EOL : '' ).$timestamp.' '.$count.' ['.$function.'] '.$user_message.(mb_substr($user_message, -1, 1) == PHP_EOL ? '' : PHP_EOL );

			$result			=	file_put_contents($filename, $message, FILE_APPEND );
			return			$result;
		}
	}
}
$class_pz_linkcard	=	new class_pz_linkcard;
