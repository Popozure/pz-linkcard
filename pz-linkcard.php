<?php defined('ABSPATH' ) || wp_die; ?>
<?php
/*
Plugin Name:	Pz-LinkCard
Plugin URI:		http://popozure.info/pz-linkcard
Description:	Displays links in card format.
Version:		2.6.1
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
			'plugin-version'					=>	['type'	=>	'version',		'null'	=>	false,	'default'	=>	null, ],
			'db-version'						=>	['type'	=>	'string',		'null'	=>	true,	'default'	=>	null, ],

			'error-mode'						=>	['type'	=>	'flag',			'null'	=>	false,	'default'	=>	0, ],
			'error-hide'						=>	['type'	=>	'flag',			'null'	=>	false,	'default'	=>	0, ],
			'error-mode-hide'					=>	['type'	=>	'flag',			'null'	=>	false,	'default'	=>	0, ],
			'error-url'							=>	['type'	=>	'url',			'null'	=>	true,	'default'	=>	null, ],
			'error-postid'						=>	['type'	=>	'numeric',		'null'	=>	true,	'default'	=>	null, ],
			'error-time'						=>	['type'	=>	'timestamp',	'null'	=>	true,	'default'	=>	null, ],

			'special-format'					=>	['type'	=>	'string',		'null'	=>	true,	'default'	=>	null, ],

			'flg-linkall'						=>	['type'	=>	'flag',			'null'	=>	false,	'default'	=>	1, ],
			'flg-resize'						=>	['type'	=>	'flag',			'null'	=>	false,	'default'	=>	1, ],
			'margin-top'						=>	['type'	=>	'pixel',		'null'	=>	true,	'default'	=>	'16px', ],
			'margin-bottom'						=>	['type'	=>	'pixel',		'null'	=>	true,	'default'	=>	'16px', ],
			'margin-left'						=>	['type'	=>	'pixel',		'null'	=>	true,	'default'	=>	'16px', ],
			'margin-right'						=>	['type'	=>	'pixel',		'null'	=>	true,	'default'	=>	'16px', ],
			'card-top'							=>	['type'	=>	'pixel',		'null'	=>	true,	'default'	=>	'8px', ],
			'card-bottom'						=>	['type'	=>	'pixel',		'null'	=>	true,	'default'	=>	'8px', ],
			'card-left'							=>	['type'	=>	'pixel',		'null'	=>	true,	'default'	=>	'8px', ],
			'card-right'						=>	['type'	=>	'pixel',		'null'	=>	true,	'default'	=>	'8px', ],
			'thumbnail-position'				=>	['type'	=>	'numeric',		'null'	=>	true,	'default'	=>	2, ],
			'thumbnail-width'					=>	['type'	=>	'numeric',		'null'	=>	true,	'default'	=>	100, ],
			'thumbnail-height'					=>	['type'	=>	'numeric',		'null'	=>	true,	'default'	=>	100, ],
			'width'								=>	['type'	=>	'numeric',		'null'	=>	true,	'default'	=>	500, ],
			'width-unit'						=>	['type'	=>	'unit',			'null'	=>	true,	'default'	=>	'px', ],
			'content-height'					=>	['type'	=>	'numeric',		'null'	=>	true,	'default'	=>	104, ],
			'centering'							=>	['type'	=>	'flag',			'null'	=>	false,	'default'	=>	0, ],
			'enclose-tag'						=>	['type'	=>	'html_tag',		'null'	=>	false,	'default'	=>	'div', ],
			'flg-use-sitename'					=>	['type'	=>	'flag',			'null'	=>	false,	'default'	=>	1, ],
			'info-position'						=>	['type'	=>	'numeric',		'null'	=>	true,	'default'	=>	1, ],

			'flg-style-reset'					=>	['type'	=>	'flag',			'null'	=>	false,	'default'	=>	1, ],
			'display-url'						=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	1, ],
			'display-date'						=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	1, ],
			'separator'							=>	['type'	=>	'flag',			'null'	=>	false,	'default'	=>	0, ],
			'display-excerpt'					=>	['type'	=>	'flag',			'null'	=>	false,	'default'	=>	1, ],
			'content-inset'						=>	['type'	=>	'flag',			'null'	=>	false,	'default'	=>	0, ],
			'sns-position'						=>	['type'	=>	'numeric',		'null'	=>	true,	'default'	=>	2, ],
			'sns-tw'							=>	['type'	=>	'flag',			'null'	=>	false,	'default'	=>	1, ],
			'sns-tw-x'							=>	['type'	=>	'flag',			'null'	=>	false,	'default'	=>	0, ],
			'sns-fb'							=>	['type'	=>	'flag',			'null'	=>	false,	'default'	=>	1, ],
			'sns-hb'							=>	['type'	=>	'flag',			'null'	=>	false,	'default'	=>	1, ],

			'title-color'						=>	['type'	=>	'color',		'null'	=>	true,	'default'	=>	'#111111', ],
			'title-outline-color'				=>	['type'	=>	'color',		'null'	=>	true,	'default'	=>	null, ],
			'title-bg-color'					=>	['type'	=>	'color',		'null'	=>	true,	'default'	=>	null, ],
			'title-size'						=>	['type'	=>	'numeric',		'null'	=>	true,	'default'	=>	18, ],
			'title-height'						=>	['type'	=>	'numeric',		'null'	=>	true,	'default'	=>	23, ],
			'title-maxline'						=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	2, ],
			'title-bold'						=>	['type'	=>	'flag',			'null'	=>	false,	'default'	=>	1, ],
			'title-italic'						=>	['type'	=>	'flag',			'null'	=>	false,	'default'	=>	0, ],
			'title-underline'					=>	['type'	=>	'flag',			'null'	=>	false,	'default'	=>	0, ],
			'title-hover'						=>	['type'	=>	'flag',			'null'	=>	false,	'default'	=>	1, ],

			'url-color'							=>	['type'	=>	'color',		'null'	=>	true,	'default'	=>	'#4466ff', ],
			'url-outline-color'					=>	['type'	=>	'color',		'null'	=>	true,	'default'	=>	null, ],
			'url-bg-color'						=>	['type'	=>	'color',		'null'	=>	true,	'default'	=>	null, ],
			'url-size'							=>	['type'	=>	'numeric',		'null'	=>	true,	'default'	=>	12, ],
			'url-height'						=>	['type'	=>	'numeric',		'null'	=>	true,	'default'	=>	17, ],
			'url-bold'							=>	['type'	=>	'flag',			'null'	=>	false,	'default'	=>	0, ],
			'url-italic'						=>	['type'	=>	'flag',			'null'	=>	false,	'default'	=>	0, ],
			'url-underline'						=>	['type'	=>	'flag',			'null'	=>	false,	'default'	=>	1, ],
			'url-hover'							=>	['type'	=>	'flag',			'null'	=>	false,	'default'	=>	1, ],

			'excerpt-color'						=>	['type'	=>	'color',		'null'	=>	true,	'default'	=>	'#444444', ],
			'excerpt-outline-color'				=>	['type'	=>	'color',		'null'	=>	true,	'default'	=>	null, ],
			'excerpt-bg-color'					=>	['type'	=>	'color',		'null'	=>	true,	'default'	=>	null, ],
			'excerpt-size'						=>	['type'	=>	'numeric',		'null'	=>	true,	'default'	=>	11, ],
			'excerpt-height'					=>	['type'	=>	'numeric',		'null'	=>	true,	'default'	=>	18, ],
			'excerpt-maxline'					=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	3, ],
			'excerpt-bold'						=>	['type'	=>	'flag',			'null'	=>	false,	'default'	=>	0, ],
			'excerpt-italic'					=>	['type'	=>	'flag',			'null'	=>	false,	'default'	=>	0, ],
			'excerpt-underline'					=>	['type'	=>	'flag',			'null'	=>	false,	'default'	=>	0, ],
			'excerpt-hover'						=>	['type'	=>	'flag',			'null'	=>	false,	'default'	=>	0, ],

			'date-color'						=>	['type'	=>	'color',		'null'	=>	true,	'default'	=>	'#444444', ],
			'date-outline-color'				=>	['type'	=>	'color',		'null'	=>	true,	'default'	=>	null, ],
			'date-bg-color'						=>	['type'	=>	'color',		'null'	=>	true,	'default'	=>	null, ],
			'date-size'							=>	['type'	=>	'numeric',		'null'	=>	true,	'default'	=>	11, ],
			'date-height'						=>	['type'	=>	'numeric',		'null'	=>	true,	'default'	=>	16, ],
			'date-bold'							=>	['type'	=>	'flag',			'null'	=>	false,	'default'	=>	0, ],
			'date-italic'						=>	['type'	=>	'flag',			'null'	=>	false,	'default'	=>	0, ],
			'date-underline'					=>	['type'	=>	'flag',			'null'	=>	false,	'default'	=>	0, ],
			'date-hover'						=>	['type'	=>	'flag',			'null'	=>	false,	'default'	=>	0, ],

			'info-color'						=>	['type'	=>	'color',		'null'	=>	true,	'default'	=>	'#222222', ],
			'info-outline-color'				=>	['type'	=>	'color',		'null'	=>	true,	'default'	=>	null, ],
			'info-bg-color'						=>	['type'	=>	'color',		'null'	=>	true,	'default'	=>	null],
			'info-size'							=>	['type'	=>	'numeric',		'null'	=>	true,	'default'	=>	12],
			'info-height'						=>	['type'	=>	'numeric',		'null'	=>	true,	'default'	=>	16],
			'info-bold'							=>	['type'	=>	'flag',			'null'	=>	false,	'default'	=>	0, ],
			'info-italic'						=>	['type'	=>	'flag',			'null'	=>	false,	'default'	=>	0, ],
			'info-underline'					=>	['type'	=>	'flag',			'null'	=>	false,	'default'	=>	0, ],
			'info-hover'						=>	['type'	=>	'flag',			'null'	=>	false,	'default'	=>	0, ],

			'added-color'						=>	['type'	=>	'color',		'null'	=>	true,	'default'	=>	'#ffffff', ],
			'added-outline-color'				=>	['type'	=>	'color',		'null'	=>	true,	'default'	=>	null, ],
			'added-bg-color'					=>	['type'	=>	'color',		'null'	=>	true,	'default'	=>	'#365cd9', ],
			'added-size'						=>	['type'	=>	'numeric',		'null'	=>	true,	'default'	=>	9, ],
			'added-height'						=>	['type'	=>	'numeric',		'null'	=>	true,	'default'	=>	10, ],
			'added-bold'						=>	['type'	=>	'flag',			'null'	=>	false,	'default'	=>	0, ],
			'added-italic'						=>	['type'	=>	'flag',			'null'	=>	false,	'default'	=>	0, ],
			'added-underline'					=>	['type'	=>	'flag',			'null'	=>	false,	'default'	=>	0, ],
			'added-hover'						=>	['type'	=>	'flag',			'null'	=>	false,	'default'	=>	0, ],

			'heading-color'						=>	['type'	=>	'color',		'null'	=>	true,	'default'	=>	'#ffffff', ],
			'heading-outline-color'				=>	['type'	=>	'color',		'null'	=>	true,	'default'	=>	null, ],
//			'heading-bg-color'					=>	['type'	=>	'color',		'null'	=>	true,	'default'	=>	null, ],
			'heading-size'						=>	['type'	=>	'numeric',		'null'	=>	true,	'default'	=>	12, ],
			'heading-height'					=>	['type'	=>	'numeric',		'null'	=>	true,	'default'	=>	16, ],
			'heading-bold'						=>	['type'	=>	'flag',			'null'	=>	false,	'default'	=>	0, ],
			'heading-italic'					=>	['type'	=>	'flag',			'null'	=>	false,	'default'	=>	0, ],
			'heading-underline'					=>	['type'	=>	'flag',			'null'	=>	false,	'default'	=>	0, ],
			'heading-hover'						=>	['type'	=>	'flag',			'null'	=>	false,	'default'	=>	0, ],

			'more-color'						=>	['type'	=>	'color',		'null'	=>	true,	'default'	=>	'#ffffff', ],
			'more-outline-color'				=>	['type'	=>	'color',		'null'	=>	true,	'default'	=>	null, ],
//			'more-bg-color'						=>	['type'	=>	'color',		'null'	=>	true,	'default'	=>	null, ],
			'more-size'							=>	['type'	=>	'numeric',		'null'	=>	true,	'default'	=>	12, ],
			'more-height'						=>	['type'	=>	'numeric',		'null'	=>	true,	'default'	=>	24, ],
			'more-bold'							=>	['type'	=>	'flag',			'null'	=>	false,	'default'	=>	0, ],
			'more-italic'						=>	['type'	=>	'flag',			'null'	=>	false,	'default'	=>	0, ],
			'more-underline'					=>	['type'	=>	'flag',			'null'	=>	false,	'default'	=>	0, ],
			'more-hover'						=>	['type'	=>	'flag',			'null'	=>	false,	'default'	=>	0, ],

			'cat-color'							=>	['type'	=>	'color',		'null'	=>	true,	'default'	=>	'#ffffff', ],
			'cat-outline-color'					=>	['type'	=>	'color',		'null'	=>	true,	'default'	=>	null, ],
			'cat-bg-color'						=>	['type'	=>	'color',		'null'	=>	true,	'default'	=>	'#ffcc88', ],
			'cat-size'							=>	['type'	=>	'numeric',		'null'	=>	true,	'default'	=>	12, ],
			'cat-height'						=>	['type'	=>	'numeric',		'null'	=>	true,	'default'	=>	24, ],
			'cat-bold'							=>	['type'	=>	'flag',			'null'	=>	false,	'default'	=>	0, ],
			'cat-italic'						=>	['type'	=>	'flag',			'null'	=>	false,	'default'	=>	0, ],
			'cat-underline'						=>	['type'	=>	'flag',			'null'	=>	false,	'default'	=>	0, ],
			'cat-hover'							=>	['type'	=>	'flag',			'null'	=>	false,	'default'	=>	0, ],

			'ex-target'							=>	['type'	=>	'numeric',		'null'	=>	true,	'default'	=>	2, ],
			'ex-transform-enabled'				=>	['type'	=>	'flag',			'null'	=>	false,	'default'	=>	1, ],
			'ex-transform-x'					=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	0, ],
			'ex-transform-y'					=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	0, ],
			'ex-transform-rotate'				=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	0, ],
			'ex-transform-scale'				=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	100, ],
			'ex-opacity'						=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	100, ],
			'ex-bg-enabled'						=>	['type'	=>	'flag',			'null'	=>	false,	'default'	=>	1, ],
			'ex-bg-color'						=>	['type'	=>	'color',		'null'	=>	true,	'default'	=>	'#ddeeff', ],
			'ex-bg-image'						=>	['type'	=>	'string',		'null'	=>	true,	'default'	=>	null, ],
			'ex-border-enabled'					=>	['type'	=>	'flag',			'null'	=>	false,	'default'	=>	1, ],
			'ex-border-color'					=>	['type'	=>	'color',		'null'	=>	true,	'default'	=>	'#114488', ],
			'ex-border-style'					=>	['type'	=>	'border',		'null'	=>	true,	'default'	=>	'solid', ],
			'ex-border-width'					=>	['type'	=>	'numeric',		'null'	=>	true,	'default'	=>	2, ],
			'ex-border-radius'					=>	['type'	=>	'numeric',		'null'	=>	true,	'default'	=>	4, ],
			'ex-shadow-enabled'					=>	['type'	=>	'flag',			'null'	=>	false,	'default'	=>	0, ],
			'ex-shadow-color'					=>	['type'	=>	'color',		'null'	=>	true,	'default'	=>	'#00336688', ],
			'ex-shadow-x'						=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	8, ],
			'ex-shadow-y'						=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	8, ],
			'ex-shadow-blur'					=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	8, ],
			'ex-shadow-spread'					=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	0, ],
			'ex-shadow-inset'					=>	['type'	=>	'flag',			'null'	=>	false,	'default'	=>	0, ],
			'ex-transition'						=>	['type'	=>	'float',		'null'	=>	true,	'default'	=>	0.2, ],
			'ex-hover-transform-enabled'		=>	['type'	=>	'flag',			'null'	=>	false,	'default'	=>	0, ],
			'ex-hover-transform-x'				=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	-4, ],
			'ex-hover-transform-y'				=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	-4, ],
			'ex-hover-transform-rotate'			=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	0, ],
			'ex-hover-transform-scale'			=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	100, ],
			'ex-hover-opacity'					=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	100, ],
			'ex-hover-bg-enabled'				=>	['type'	=>	'flag',			'null'	=>	false,	'default'	=>	1, ],
			'ex-hover-bg-color'					=>	['type'	=>	'color',		'null'	=>	true,	'default'	=>	'#ddeeff88', ],
			'ex-hover-bg-image'					=>	['type'	=>	'string',		'null'	=>	true,	'default'	=>	null, ],
			'ex-hover-border-enabled'			=>	['type'	=>	'flag',			'null'	=>	false,	'default'	=>	0, ],
			'ex-hover-border-color'				=>	['type'	=>	'color',		'null'	=>	true,	'default'	=>	'#114488', ],
			'ex-hover-border-style'				=>	['type'	=>	'border',		'null'	=>	true,	'default'	=>	'solid', ],
			'ex-hover-border-width'				=>	['type'	=>	'numeric',		'null'	=>	true,	'default'	=>	2, ],
			'ex-hover-border-radius'			=>	['type'	=>	'numeric',		'null'	=>	true,	'default'	=>	4, ],
			'ex-hover-shadow-enabled'			=>	['type'	=>	'flag',			'null'	=>	false,	'default'	=>	0, ],
			'ex-hover-shadow-color'				=>	['type'	=>	'color',		'null'	=>	true,	'default'	=>	'#00336688', ],
			'ex-hover-shadow-x'					=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	8, ],
			'ex-hover-shadow-y'					=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	8, ],
			'ex-hover-shadow-blur'				=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	8, ],
			'ex-hover-shadow-spread'			=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	0, ],
			'ex-hover-shadow-inset'				=>	['type'	=>	'flag',			'null'	=>	false,	'default'	=>	0, ],
			'ex-hover-transition'				=>	['type'	=>	'float',		'null'	=>	true,	'default'	=>	0.2, ],
			'ex-get-from'						=>	['type'	=>	'string',		'null'	=>	true,	'default'	=>	2, ],
			'ex-heading-text'					=>	['type'	=>	'string',		'null'	=>	true,	'default'	=>	null, ],
			'ex-heading-transform-enabled'		=>	['type'	=>	'flag',			'null'	=>	false,	'default'	=>	0, ],
			'ex-heading-transform-x'			=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	0, ],
			'ex-heading-transform-y'			=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	0, ],
			'ex-heading-transform-rotate'		=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	0, ],
			'ex-heading-transform-scale'		=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	100, ],
			'ex-heading-bg-enabled'				=>	['type'	=>	'flag',			'null'	=>	false,	'default'	=>	1, ],
			'ex-heading-bg-color'				=>	['type'	=>	'color',		'null'	=>	true,	'default'	=>	'#003366', ],
			'ex-heading-border-enabled'			=>	['type'	=>	'flag',			'null'	=>	false,	'default'	=>	0, ],
			'ex-heading-border-color'			=>	['type'	=>	'color',		'null'	=>	true,	'default'	=>	'#003366', ],
			'ex-heading-border-style'			=>	['type'	=>	'border',		'null'	=>	true,	'default'	=>	'solid', ],
			'ex-heading-border-width'			=>	['type'	=>	'numeric',		'null'	=>	true,	'default'	=>	1, ],
			'ex-heading-border-radius'			=>	['type'	=>	'numeric',		'null'	=>	true,	'default'	=>	4, ],
			'ex-heading-shadow-enabled'			=>	['type'	=>	'flag',			'null'	=>	false,	'default'	=>	0, ],
			'ex-heading-shadow-color'			=>	['type'	=>	'color',		'null'	=>	true,	'default'	=>	'#00336688', ],
			'ex-heading-shadow-x'				=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	8, ],
			'ex-heading-shadow-y'				=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	8, ],
			'ex-heading-shadow-blur'			=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	8, ],
			'ex-heading-shadow-spread'			=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	0, ],
			'ex-heading-shadow-inset'			=>	['type'	=>	'flag',			'null'	=>	false,	'default'	=>	0, ],
			'ex-more-text'						=>	['type'	=>	'string',		'null'	=>	true,	'default'	=>	null, ],
			'ex-more-transform-enabled'			=>	['type'	=>	'flag',			'null'	=>	false,	'default'	=>	0, ],
			'ex-more-transform-x'				=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	0, ],
			'ex-more-transform-y'				=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	0, ],
			'ex-more-transform-rotate'			=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	0, ],
			'ex-more-transform-scale'			=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	100, ],
			'ex-more-bg-enabled'				=>	['type'	=>	'flag',			'null'	=>	false,	'default'	=>	0, ],
			'ex-more-bg-color'					=>	['type'	=>	'color',		'null'	=>	true,	'default'	=>	'#446688', ],
			'ex-more-border-enabled'			=>	['type'	=>	'flag',			'null'	=>	false,	'default'	=>	0, ],
			'ex-more-border-color'				=>	['type'	=>	'color',		'null'	=>	true,	'default'	=>	'#446688', ],
			'ex-more-border-style'				=>	['type'	=>	'border',		'null'	=>	true,	'default'	=>	'solid', ],
			'ex-more-border-width'				=>	['type'	=>	'numeric',		'null'	=>	true,	'default'	=>	1, ],
			'ex-more-border-radius'				=>	['type'	=>	'numeric',		'null'	=>	true,	'default'	=>	4, ],
			'ex-more-shadow-enabled'			=>	['type'	=>	'flag',			'null'	=>	false,	'default'	=>	0, ],
			'ex-more-shadow-color'				=>	['type'	=>	'color',		'null'	=>	true,	'default'	=>	'#44668888', ],
			'ex-more-shadow-x'					=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	4, ],
			'ex-more-shadow-y'					=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	4, ],
			'ex-more-shadow-blur'				=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	8, ],
			'ex-more-shadow-spread'				=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	0, ],
			'ex-more-shadow-inset'				=>	['type'	=>	'flag',			'null'	=>	false,	'default'	=>	0, ],
			'ex-added-text'						=>	['type'	=>	'string',		'null'	=>	true,	'default'	=>	null, ],
			'ex-siteicon'						=>	['type'	=>	'string',		'null'	=>	true,	'default'	=>	3, ],
			'ex-siteicon-alt'					=>	['type'	=>	'string',		'null'	=>	true,	'default'	=>	null, ],
			'ex-thumbnail'						=>	['type'	=>	'string',		'null'	=>	true,	'default'	=>	13, ],
			'ex-thumbnail-size'					=>	['type'	=>	'string',		'null'	=>	true,	'default'	=>	'thumbnail', ],
			'ex-thumbnail-alt'					=>	['type'	=>	'string',		'null'	=>	true,	'default'	=>	null, ],
			'ex-thumbnail-transform-enabled' 	=>	['type'	=>	'flag',			'null'	=>	false,	'default'	=>	0, ],
			'ex-thumbnail-transform-x'			=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	0, ],
			'ex-thumbnail-transform-y'			=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	0, ],
			'ex-thumbnail-transform-rotate'		=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	0, ],
			'ex-thumbnail-transform-scale'		=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	100, ],
			'ex-thumbnail-bg-enabled'			=>	['type'	=>	'flag',			'null'	=>	false,	'default'	=>	1, ],
			'ex-thumbnail-bg-color'				=>	['type'	=>	'color',		'null'	=>	true,	'default'	=>	'#ffffff', ],
			'ex-thumbnail-border-enabled'		=>	['type'	=>	'flag',			'null'	=>	false,	'default'	=>	1, ],
			'ex-thumbnail-border-color'			=>	['type'	=>	'color',		'null'	=>	true,	'default'	=>	'#888888', ],
			'ex-thumbnail-border-style'			=>	['type'	=>	'border',		'null'	=>	true,	'default'	=>	'solid', ],
			'ex-thumbnail-border-width'			=>	['type'	=>	'numeric',		'null'	=>	true,	'default'	=>	1, ],
			'ex-thumbnail-border-radius'		=>	['type'	=>	'numeric',		'null'	=>	true,	'default'	=>	4, ],
			'ex-thumbnail-shadow-enabled'		=>	['type'	=>	'flag',			'null'	=>	false,	'default'	=>	0, ],
			'ex-thumbnail-shadow-color'			=>	['type'	=>	'color',		'null'	=>	true,	'default'	=>	'#aaaacc', ],
			'ex-thumbnail-shadow-x'				=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	8, ],
			'ex-thumbnail-shadow-y'				=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	8, ],
			'ex-thumbnail-shadow-blur'			=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	8, ],
			'ex-thumbnail-shadow-spread'		=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	0, ],
			'ex-thumbnail-shadow-inset'			=>	['type'	=>	'flag',			'null'	=>	false,	'default'	=>	0, ],

			'in-target'							=>	['type'	=>	'numeric',		'null'	=>	true,	'default'	=>	null, ],
			'in-transform-enabled'				=>	['type'	=>	'flag',			'null'	=>	false,	'default'	=>	1, ],
			'in-transform-x'					=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	0, ],
			'in-transform-y'					=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	0, ],
			'in-transform-rotate'				=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	0, ],
			'in-transform-scale'				=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	100, ],
			'in-opacity'						=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	100, ],
			'in-bg-enabled'						=>	['type'	=>	'flag',			'null'	=>	false,	'default'	=>	1, ],
			'in-bg-color'						=>	['type'	=>	'color',		'null'	=>	true,	'default'	=>	'#fffaf0', ],
			'in-bg-image'						=>	['type'	=>	'string',		'null'	=>	true,	'default'	=>	null, ],
			'in-border-enabled'					=>	['type'	=>	'flag',			'null'	=>	false,	'default'	=>	1, ],
			'in-border-color'					=>	['type'	=>	'color',		'null'	=>	true,	'default'	=>	'#998888', ],
			'in-border-style'					=>	['type'	=>	'border',		'null'	=>	true,	'default'	=>	'solid', ],
			'in-border-width'					=>	['type'	=>	'numeric',		'null'	=>	true,	'default'	=>	2, ],
			'in-border-radius'					=>	['type'	=>	'numeric',		'null'	=>	true,	'default'	=>	4, ],
			'in-shadow-enabled'					=>	['type'	=>	'flag',			'null'	=>	false,	'default'	=>	0, ],
			'in-shadow-color'					=>	['type'	=>	'color',		'null'	=>	true,	'default'	=>	'#66330088', ],
			'in-shadow-x'						=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	8, ],
			'in-shadow-y'						=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	8, ],
			'in-shadow-blur'					=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	8, ],
			'in-shadow-spread'					=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	0, ],
			'in-shadow-inset'					=>	['type'	=>	'flag',			'null'	=>	false,	'default'	=>	0, ],
			'in-transition'						=>	['type'	=>	'float',		'null'	=>	true,	'default'	=>	0.2, ],
			'in-hover-transform-enabled'		=>	['type'	=>	'flag',			'null'	=>	false,	'default'	=>	0, ],
			'in-hover-transform-x'				=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	-4, ],
			'in-hover-transform-y'				=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	-4, ],
			'in-hover-transform-rotate'			=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	0, ],
			'in-hover-transform-scale'			=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	100, ],
			'in-hover-opacity'					=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	100, ],
			'in-hover-bg-enabled'				=>	['type'	=>	'flag',			'null'	=>	false,	'default'	=>	1, ],
			'in-hover-bg-color'					=>	['type'	=>	'color',		'null'	=>	true,	'default'	=>	'#fffaf088', ],
			'in-hover-bg-image'					=>	['type'	=>	'string',		'null'	=>	true,	'default'	=>	null, ],
			'in-hover-border-enabled'			=>	['type'	=>	'flag',			'null'	=>	false,	'default'	=>	0, ],
			'in-hover-border-color'				=>	['type'	=>	'color',		'null'	=>	true,	'default'	=>	'#998888', ],
			'in-hover-border-style'				=>	['type'	=>	'border',		'null'	=>	true,	'default'	=>	'solid', ],
			'in-hover-border-width'				=>	['type'	=>	'numeric',		'null'	=>	true,	'default'	=>	2, ],
			'in-hover-border-radius'			=>	['type'	=>	'numeric',		'null'	=>	true,	'default'	=>	4, ],
			'in-hover-shadow-enabled'			=>	['type'	=>	'flag',			'null'	=>	false,	'default'	=>	0, ],
			'in-hover-shadow-color'				=>	['type'	=>	'color',		'null'	=>	true,	'default'	=>	'#66330088', ],
			'in-hover-shadow-x'					=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	8, ],
			'in-hover-shadow-y'					=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	8, ],
			'in-hover-shadow-blur'				=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	8, ],
			'in-hover-shadow-spread'			=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	0, ],
			'in-hover-shadow-inset'				=>	['type'	=>	'flag',			'null'	=>	false,	'default'	=>	0, ],
			'in-hover-transition'				=>	['type'	=>	'float',		'null'	=>	true,	'default'	=>	0.2, ],
			'in-get-from'						=>	['type'	=>	'string',		'null'	=>	true,	'default'	=>	null, ],
			'in-field-title'					=>	['type'	=>	'string',		'null'	=>	true,	'default'	=>	null, ],
			'in-field-excerpt'					=>	['type'	=>	'string',		'null'	=>	true,	'default'	=>	null, ],
			'in-get-url'						=>	['type'	=>	'string',		'null'	=>	true,	'default'	=>	0, ],
			'in-heading-text'					=>	['type'	=>	'string',		'null'	=>	true,	'default'	=>	null, ],
			'in-heading-transform-enabled'		=>	['type'	=>	'flag',			'null'	=>	false,	'default'	=>	0, ],
			'in-heading-transform-x'			=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	0, ],
			'in-heading-transform-y'			=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	0, ],
			'in-heading-transform-rotate'		=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	0, ],
			'in-heading-transform-scale'		=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	100, ],
			'in-heading-bg-enabled'				=>	['type'	=>	'flag',			'null'	=>	false,	'default'	=>	1, ],
			'in-heading-bg-color'				=>	['type'	=>	'color',		'null'	=>	true,	'default'	=>	'#998888', ],
			'in-heading-border-enabled'			=>	['type'	=>	'flag',			'null'	=>	false,	'default'	=>	0, ],
			'in-heading-border-color'			=>	['type'	=>	'color',		'null'	=>	true,	'default'	=>	null, ],
			'in-heading-border-style'			=>	['type'	=>	'border',		'null'	=>	true,	'default'	=>	'solid', ],
			'in-heading-border-width'			=>	['type'	=>	'numeric',		'null'	=>	true,	'default'	=>	1, ],
			'in-heading-border-radius'			=>	['type'	=>	'numeric',		'null'	=>	true,	'default'	=>	4, ],
			'in-heading-shadow-enabled'			=>	['type'	=>	'flag',			'null'	=>	false,	'default'	=>	0, ],
			'in-heading-shadow-color'			=>	['type'	=>	'color',		'null'	=>	true,	'default'	=>	'#aaaacc', ],
			'in-heading-shadow-x'				=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	8, ],
			'in-heading-shadow-y'				=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	8, ],
			'in-heading-shadow-blur'			=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	8, ],
			'in-heading-shadow-spread'			=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	0, ],
			'in-heading-shadow-inset'			=>	['type'	=>	'flag',			'null'	=>	false,	'default'	=>	0, ],
			'in-more-text'						=>	['type'	=>	'string',		'null'	=>	true,	'default'	=>	null, ],
			'in-more-transform-enabled'			=>	['type'	=>	'flag',			'null'	=>	false,	'default'	=>	0, ],
			'in-more-transform-x'				=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	0, ],
			'in-more-transform-y'				=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	0, ],
			'in-more-transform-rotate'			=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	0, ],
			'in-more-transform-scale'			=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	100, ],
			'in-more-bg-enabled'				=>	['type'	=>	'flag',			'null'	=>	false,	'default'	=>	0, ],
			'in-more-bg-color'					=>	['type'	=>	'color',		'null'	=>	true,	'default'	=>	'#8c8c70', ],
			'in-more-border-enabled'			=>	['type'	=>	'flag',			'null'	=>	false,	'default'	=>	0, ],
			'in-more-border-color'				=>	['type'	=>	'color',		'null'	=>	true,	'default'	=>	'#444444', ],
			'in-more-border-style'				=>	['type'	=>	'border',		'null'	=>	true,	'default'	=>	'solid', ],
			'in-more-border-width'				=>	['type'	=>	'numeric',		'null'	=>	true,	'default'	=>	1, ],
			'in-more-border-radius'				=>	['type'	=>	'numeric',		'null'	=>	true,	'default'	=>	4, ],
			'in-more-shadow-enabled'			=>	['type'	=>	'flag',			'null'	=>	false,	'default'	=>	0, ],
			'in-more-shadow-color'				=>	['type'	=>	'color',		'null'	=>	true,	'default'	=>	'#66330088', ],
			'in-more-shadow-x'					=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	4, ],
			'in-more-shadow-y'					=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	4, ],
			'in-more-shadow-blur'				=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	8, ],
			'in-more-shadow-spread'				=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	0, ],
			'in-more-shadow-inset'				=>	['type'	=>	'flag',			'null'	=>	false,	'default'	=>	0, ],
			'in-added-text'						=>	['type'	=>	'string',		'null'	=>	true,	'default'	=>	null, ],
			'in-siteicon'						=>	['type'	=>	'string',		'null'	=>	true,	'default'	=>	3, ],
			'in-siteicon-alt'					=>	['type'	=>	'string',		'null'	=>	true,	'default'	=>	null, ],
			'in-thumbnail'						=>	['type'	=>	'string',		'null'	=>	true,	'default'	=>	1, ],
			'in-thumbnail-size'					=>	['type'	=>	'string',		'null'	=>	true,	'default'	=>	'thumbnail', ],
			'in-thumbnail-alt'					=>	['type'	=>	'string',		'null'	=>	true,	'default'	=>	null, ],
			'in-thumbnail-transform-enabled' 	=>	['type'	=>	'flag',			'null'	=>	false,	'default'	=>	0, ],
			'in-thumbnail-transform-x'			=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	0, ],
			'in-thumbnail-transform-y'			=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	0, ],
			'in-thumbnail-transform-rotate'		=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	0, ],
			'in-thumbnail-transform-scale'		=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	100, ],
			'in-thumbnail-bg-enabled'			=>	['type'	=>	'flag',			'null'	=>	false,	'default'	=>	1, ],
			'in-thumbnail-bg-color'				=>	['type'	=>	'color',		'null'	=>	true,	'default'	=>	'#ffffff', ],
			'in-thumbnail-border-enabled'		=>	['type'	=>	'flag',			'null'	=>	false,	'default'	=>	1, ],
			'in-thumbnail-border-color'			=>	['type'	=>	'color',		'null'	=>	true,	'default'	=>	'#888888', ],
			'in-thumbnail-border-style'			=>	['type'	=>	'border',		'null'	=>	true,	'default'	=>	'solid', ],
			'in-thumbnail-border-width'			=>	['type'	=>	'numeric',		'null'	=>	true,	'default'	=>	1, ],
			'in-thumbnail-border-radius'		=>	['type'	=>	'numeric',		'null'	=>	true,	'default'	=>	4, ],
			'in-thumbnail-shadow-enabled'		=>	['type'	=>	'flag',			'null'	=>	false,	'default'	=>	0, ],
			'in-thumbnail-shadow-color'			=>	['type'	=>	'color',		'null'	=>	true,	'default'	=>	'#aaaacc', ],
			'in-thumbnail-shadow-x'				=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	8, ],
			'in-thumbnail-shadow-y'				=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	8, ],
			'in-thumbnail-shadow-blur'			=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	8, ],
			'in-thumbnail-shadow-spread'		=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	0, ],
			'in-thumbnail-shadow-inset'			=>	['type'	=>	'flag',			'null'	=>	false,	'default'	=>	0, ],

			'flg-nofollow'						=>	['type'	=>	'flag',			'null'	=>	false,	'default'	=>	0, ],
			'flg-noopener'						=>	['type'	=>	'flag',			'null'	=>	false,	'default'	=>	1, ],
			'flg-referer'						=>	['type'	=>	'flag',			'null'	=>	false,	'default'	=>	1, ],
			'flg-relative-url'					=>	['type'	=>	'flag',			'null'	=>	false,	'default'	=>	1, ],
			'flg-unlink'						=>	['type'	=>	'flag',			'null'	=>	false,	'default'	=>	1, ],
			'flg-sslverify'						=>	['type'	=>	'flag',			'null'	=>	false,	'default'	=>	1, ],
			'flg-redir'							=>	['type'	=>	'flag',			'null'	=>	false,	'default'	=>	1, ],
			'flg-agent'							=>	['type'	=>	'flag',			'null'	=>	false,	'default'	=>	1, ],
			'user-agent'						=>	['type'	=>	'string',		'null'	=>	true,	'default'	=>	null, ],
			'user-agent-text'					=>	['type'	=>	'string',		'null'	=>	true,	'default'	=>	null, ],
			'flg-alive'							=>	['type'	=>	'flag',			'null'	=>	false,	'default'	=>	1, ],
			'flg-alive-count'					=>	['type'	=>	'flag',			'null'	=>	false,	'default'	=>	0, ],
			'flg-click-count'					=>	['type'	=>	'flag',			'null'	=>	false,	'default'	=>	0, ],

			'code1'								=>	['type'	=>	'code',			'null'	=>	true,	'default'	=>	'blogcard', ],
			'code2'								=>	['type'	=>	'code',			'null'	=>	true,	'default'	=>	null, ],
			'code3'								=>	['type'	=>	'code',			'null'	=>	true,	'default'	=>	null, ],
			'use-inline'						=>	['type'	=>	'flag',			'null'	=>	false,	'default'	=>	null, ],
			'auto-atag'							=>	['type'	=>	'flag',			'null'	=>	false,	'default'	=>	0, ],
			'auto-url'							=>	['type'	=>	'flag',			'null'	=>	false,	'default'	=>	0, ],
			'auto-external'						=>	['type'	=>	'flag',			'null'	=>	false,	'default'	=>	0, ],
			'flg-do-shortcode'					=>	['type'	=>	'flag',			'null'	=>	false,	'default'	=>	1, ],
			'exclude-url'						=>	['type'	=>	'textarea',		'null'	=>	true,	'default'	=>	null, ],
			'flg-edit-block'					=>	['type'	=>	'flag',			'null'	=>	false,	'default'	=>	1, ],
			'flg-edit-insert'					=>	['type'	=>	'flag',			'null'	=>	false,	'default'	=>	1, ],
			'mce-priority'						=>	['type'	=>	'numeric',		'null'	=>	true,	'default'	=>	null, ],
			'flg-edit-qtag'						=>	['type'	=>	'flag',			'null'	=>	false,	'default'	=>	1, ],
			'flg-clear-excerpt'					=>	['type'	=>	'flag',			'null'	=>	false,	'default'	=>	1, ],

			'multi-mode'						=>	['type'	=>	'flag',			'null'	=>	false,	'default'	=>	0, ],
			'multi-myid'						=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	0, ],
			'multi-count'						=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	0, ],

			'trail-slash'						=>	['type'	=>	'flag',			'null'	=>	false,	'default'	=>	1, ],
			'class-pc'							=>	['type'	=>	'string',		'null'	=>	true,	'default'	=>	null, ],
			'class-mobile'						=>	['type'	=>	'string',		'null'	=>	true,	'default'	=>	null, ],
			'date-format-man'					=>	['type'	=>	'string',		'null'	=>	true,	'default'	=>	'Y\<\b\r\/\>m/d\<\b\r\/\>H:i', ],
			'flg-preview'						=>	['type'	=>	'flag',			'null'	=>	false,	'default'	=>	1, ],
			'preview-mode'						=>	['type'	=>	'string',		'null'	=>	true,	'default'	=>	null, ],
			'preview-left'						=>	['type'	=>	'numeric',		'null'	=>	true,	'default'	=>	null, ],
			'preview-top'						=>	['type'	=>	'numeric',		'null'	=>	true,	'default'	=>	null, ],
			'preview-width'						=>	['type'	=>	'numeric',		'null'	=>	true,	'default'	=>	null, ],
			'preview-height'					=>	['type'	=>	'numeric',		'null'	=>	true,	'default'	=>	null, ],
			'preview-docked-height'				=>	['type'	=>	'numeric',		'null'	=>	true,	'default'	=>	null, ],
			'preview-right-docked-width'		=>	['type'	=>	'numeric',		'null'	=>	true,	'default'	=>	null, ],
			'preview-two-cards'					=>	['type'	=>	'flag',			'null'	=>	false,	'default'	=>	0, ],
			'flg-anti-select'					=>	['type'	=>	'flag',			'null'	=>	false,	'default'	=>	0, ],
			'flg-adminbar'						=>	['type'	=>	'flag',			'null'	=>	false,	'default'	=>	0, ],
			'flg-initialize'					=>	['type'	=>	'flag',			'null'	=>	false,	'default'	=>	1, ],
			'flg-compress'						=>	['type'	=>	'flag',			'null'	=>	false,	'default'	=>	1, ],
			'flg-amp-url'						=>	['type'	=>	'flag',			'null'	=>	false,	'default'	=>	0, ],
			'flg-inhibit'						=>	['type'	=>	'flag',			'null'	=>	false,	'default'	=>	1, ],
			'saved-date'						=>	['type'	=>	'numeric',		'null'	=>	true,	'default'	=>	null, ],

			'develop-mode'						=>	['type'	=>	'flag',			'null'	=>	false,	'default'	=>	0, ],
			'admin-mode'						=>	['type'	=>	'flag',			'null'	=>	false,	'default'	=>	0, ],
			'debug-mode'						=>	['type'	=>	'flag',			'null'	=>	false,	'default'	=>	0, ],
			'debug-nocache'						=>	['type'	=>	'flag',			'null'	=>	false,	'default'	=>	0, ],
			'survey-mode'						=>	['type'	=>	'flag',			'null'	=>	false,	'default'	=>	0, ],

			'css-add-url'						=>	['type'	=>	'url',			'null'	=>	true,	'default'	=>	null, ],
			'css-add'							=>	['type'	=>	'string',		'null'	=>	true,	'default'	=>	null, ],
			'css-count'							=>	['type'	=>	'numeric',		'null'	=>	false,	'default'	=>	0, ],
			'favicon-api'						=>	['type'	=>	'url_template',	'null'	=>	true,	'default'	=>	'https://www.google.com/s2/favicons?domain=%DOMAIN%', ],
			'thumbnail-api'						=>	['type'	=>	'url_template',	'null'	=>	true,	'default'	=>	'https://s.wordpress.com/mshots/v1/%URL%?w=200', ],
			'initialize-exception'				=>	['type'	=>	'string',		'null'	=>	true,	'default'	=>	0, ],
			'flg-delete-db'						=>	['type'	=>	'flag',			'null'	=>	false,	'default'	=>	1, ],
			'flg-delete-image'					=>	['type'	=>	'flag',			'null'	=>	false,	'default'	=>	1, ],
			'flg-delete-settings'				=>	['type'	=>	'flag',			'null'	=>	false,	'default'	=>	1, ],
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
	private		const	AUTHOR_DONATE_URL	=	'https://www.amazon.co.jp/gp/registry/wishlist/2KIBQLC1VLA9X';
	private		const	CRON_PREFIX			=	'pz_linkcard_';
	private		const	CRON_ALIVE			=	self::CRON_PREFIX.'alive';
	private		const	CRON_CHECK			=	self::CRON_PREFIX.'check';
	private		const	CACHEMAN_PAGE		=	self::PLUGIN_SLUG.'-cacheman';						// Pzカード管理のページ名
	private		const	CACHEMAN_URL		=	'/tools.php?page='.self::CACHEMAN_PAGE;				// Pzカード管理のURL
	private		const	SETTINGS_PAGE		=	self::PLUGIN_SLUG.'-settings';						// Pzカード設定のページ名
	private		const	SETTINGS_URL		=	'/options-general.php?page='.self::SETTINGS_PAGE;	// Pzカード設定のURL

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
	private		$db_name;				// DBのテーブル名
	private		$activate_now;			// 二重実行防止
	private		$suppression;			// 出力抑制
	private		$now_page;				// 表示中のページ（1:Pzカード設定 2:Pzカード管理）
	private		$options;				// パラメータ
	private		$settings_page;			// 設定画面のパス
	private		$settings_url;			// 設定画面のURL
	private		$cacheman_page;			// 管理画面のパス
	private		$cacheman_url;			// 管理画面のURL

	private		$test_count;			// テスト用

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
		define('PZLKC_PZLKC_URL_ADMIN_JS',		plugins_url('js/admin-settings.js', __FILE__ ) );	// 管理画面のJSのURL（設定画面）
		define('PZLKC_PZLKC_URL_PREVIEW_JS',	plugins_url('js/pz-linkcard-preview.js', __FILE__ ) );	// 管理画面のJSのURL（プレビュー）
		define('PZLKC_PZLKC_URL_ADMIN_TAB',		plugins_url('js/admin-tabs.js', __FILE__ ) );		// 管理画面のJSのURL（設定画面タブ）
		define('PZLKC_PZLKC_URL_COLOR_PICKER_JS',	plugins_url('js/color-picker.js', __FILE__ ) );	// 管理画面のJSのURL（カラーピッカー）
		define('PZLKC_JS_COUNT',		plugins_url('js/click-count.js', __FILE__ ) );				// 管理画面のJSのURL（クリックカウント）
		define('PZLKC_PZLKC_URL_ADMIN_CSS',		plugins_url('css/admin.css', __FILE__ ) );			// 管理画面のCSSのURL
		define('PZLKC_PZLKC_URL_COLOR_PICKER_CSS',	plugins_url('css/color-picker.css', __FILE__ ) );	// 管理画面のCSSのURL（カラーピッカー）

		define('PZLKC_DIR_UPLOAD',		wp_upload_dir()['basedir']. '/'.self::PLUGIN_SLUG.'/' );	// アップロード ディレクトリのパス
		define('PZLKC_URL_UPLOAD',		preg_replace('/(http|https):(\/\/.*)/', '$2', wp_upload_dir()['baseurl'] ).'/'.self::PLUGIN_SLUG.'/' );		// アップロード ディレクトリのURL

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
		$this->db_name				=	$wpdb->prefix.'pz_linkcard';						// DBのテーブル名
		$this->plugin_basename		=	plugin_basename(__FILE__ );							// プラグイン ディレクトリの名前
		$this->plugin_dir_path		=	plugin_dir_path(__FILE__ );							// プラグイン ディレクトリのパス
		$this->plugin_dir_url		=	plugin_dir_url (__FILE__ );							// プラグイン ディレクトリのURL
		$this->settings_url			=	admin_url(self::SETTINGS_URL );						// Pzカード設定のURL
		$this->cacheman_url			=	admin_url(self::CACHEMAN_URL );						// Pzカード管理のURL

		// オプション取得
		$this->suppression		=	false;													// 出力抑制（header出力前かどうか）
		$result					=	$this->pz_LoadOptions();
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

		// 管理画面のとき
		if	(is_admin() ) {
			switch	(true) {				// 現在のページ
			case	(strpos($_SERVER['REQUEST_URI'], self::SETTINGS_URL ) <> '' ):
				$this->now_page	=	1;		// Pz カード設定
			case	(strpos($_SERVER['REQUEST_URI'], self::CACHEMAN_URL ) <> '' ):
				$this->now_page	=	2;		// Pz カード管理
			default:
				$this->now_page	=	'';
			}
			register_activation_hook	(__FILE__,						[$this, 'hook_activate' ],						10, 1 );	// プラグインを有効化するときの処理
			register_deactivation_hook	(__FILE__,						[$this, 'hook_deactivate' ],					10, 1 );	// プラグインを無効化するときの処理
			add_action		('init',									[$this, 'action_init' ],						10, 1 );	// プラグイン初期化
			add_action		('plugins_loaded',							[$this, 'action_plugins_loaded' ],				10, 1 );	// WordPressロード後
			add_action		('upgrader_process_complete',				[$this, 'action_upgrader_process_complete' ],	10, 2 );	// アップデートしたときの処理
			add_action		('admin_post_pz_export_file',				[$this, 'action_export_file' ],					10, 1 );	// エクスポート処理
			add_action		('enqueue_block_editor_assets',				[$this, 'action_enqueue_block_editor_assets' ],	10, 1 );	// ブロックエディタ用スクリプト
			add_action		('enqueue_block_assets',					[$this, 'action_enqueue_block_assets' ],		10, 1 );	// ブロックエディタ本文

			if ($this->options['flg-alive'] ) {
				add_action(self::CRON_ALIVE,	[$this, 'schedule_hook_alive' ] );
				if	(!wp_next_scheduled(self::CRON_ALIVE ) ) {
					wp_schedule_event(time() + 1800	, 'daily',	self::CRON_ALIVE );
				}
			}

			// WP-CRONスケジュール登録（SNSカウント取得）
			if ($this->options['sns-position'] ) {
				add_action(self::CRON_CHECK,	[$this, 'schedule_hook_check' ] );
				if	(!wp_next_scheduled(self::CRON_CHECK ) ) {
					wp_schedule_event(time() + 10	, 'hourly',	self::CRON_CHECK );
				}
			}

		} else {
			add_action		('wp_enqueue_scripts',						[$this, 'action_wp_enqueue_scripts'] );		// スタイルシート呼び出し

			add_action		('plugins_loaded',							[$this, 'action_plugins_loaded' ], 10 );
			if	($this->options['auto-atag'] || $this->options['auto-url'] ) {										// 自動置き換え
				add_filter		('the_content',					array($this, 'auto_replace' ) );
				add_shortcode	(self::PLUGIN_SLUG.'-auto-replace',		[$this, 'shortcode' ], 10 );
			}
			$code	=	preg_replace("/[^a-zA-Z0-9]/", "", $this->options['code1'] );								// ショートコード1
			if	($code ) {						
				add_shortcode($code, array($this, 'shortcode' ), 10 );
			}
			$code	=	preg_replace("/[^a-zA-Z0-9]/", "", $this->options['code2'] );								// ショートコード2
			if	($code ) {
				add_shortcode($code, array($this, 'shortcode' ), 10 );
			}
			$code	=	preg_replace("/[^a-zA-Z0-9]/", "", $this->options['code3'] );								// ショートコード3
			if	($code ) {
				add_shortcode($code, array($this, 'shortcode' ), 10 );
			}
		}
		add_action		('init',								[$this, 'action_register_block' ],	10, 1 );		// ブロック登録
		add_action		('wp_ajax_pz_lkc_clear_error_mode',		[$this, 'action_ajax_pz_lkc_error_mode_clear'] );
		add_action		('wp_ajax_pz_lkc_save_cacheman_columns',	[$this, 'action_ajax_pz_lkc_save_cacheman_columns'] );
		add_action		('wp_ajax_pz_lkc_preview_render',		[$this, 'action_ajax_pz_lkc_preview_render'] );
		add_action		('wp_ajax_pz_lkc_preview_state',		[$this, 'action_ajax_pz_lkc_preview_state'] );
		add_action		('wp_ajax_pz_lkc_click_count', 			[$this, 'action_ajax_pz_lkc_click_count'] );
		add_action		('wp_ajax_nopriv_pz_lkc_click_count',	[$this, 'action_ajax_pz_lkc_click_count'] );
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
		if	(preg_match('/((https?|file|ftp|data|ogg):\/\/[^\s<>]+)/sui', $url, $m ) ) {
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
			$tag		=	'<div class="linkcard"><a id="lkc-error" class="lkc-error" style="display:block;scroll-margin-top:33vh;"></a><div class="lkc-internal-wrap"><div class="lkc-info">'.self::PLUGIN_NAME.'</div><div class="lkc-excerpt">'.__('-', 'pz-linkcard' ).' '.__('Incorrect URL specification.', 'pz-linkcard' ).'<br>'.__('-', 'pz-linkcard' ).' '.__('URL', 'pz-linkcard' ).'='.esc_url($url_org ).'</div></div></div>';
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

		// 記事内容取得
		$tag	=	$this->pz_GetHTML($atts );

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

	// キャッシュやリンク先からリンクカードのHTMLを生成
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
		$favicon_url	=	'';
		$favicon_alt	=	'';
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

		// キャッシュから取得
		$is_preview		=	isset($atts['preview-data'] ) && is_array($atts['preview-data'] );
		$data			=	array('url' => $url );
		if	($is_preview ) {
			$data		=	array_merge($data, $atts['preview-data'] );
			$data_id	=	'preview';
			$class_id	.=	' pz-preview-linkcard';
		} else {
			$result		=	$this->pz_GetCache($data );
			if	(isset($result ) && is_array($result ) && isset($result['url'] ) ) {
				$data	=	$result;
				$data_id	=	$data['id'];
				$url	=	$data['url'];
			}
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
			if	($is_preview ) {
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

			// キャッシュが無い、もしくは強制取得
			if	(!$is_preview && ((!$data_id ) || ($this->options['debug-mode']	==	true  && $this->options['debug-nocache']	==	true ) || (isset($atts['force'] ) && $atts['force'] == true ) ) ) {
				$result		=	$this->pz_GetRemote($data );			// 記事内容を強制取得
				if	(isset($result ) && is_array($result ) && isset($result['url'] ) ) {
					$data	=	$result;
					$result	=	$this->pz_SetCache($data );
				}
			}
		}

		// 記事内容をセット
		$url_redir		=	$data['url_redir']		??	'';
		$title			=	$data['title']			??	'';
		$excerpt		=	$data['excerpt']		??	'';
		$site_name		=	$data['site_name']		??	'';
		$thumbnail_url	=	$data['thumbnail']		??	'';
		$favicon_url	=	$data['favicon']		??	'';
		$post_date		=	$data['post_date']		??	'';
		$post_modified	=	$data['post_modified']	??	'';
		$update_result	=	$data['update_result']	??	'';
		$alive_result	=	$data['alive_result']	??	'';
		$no_failure		=	!empty($data['no_failure'] )	?	true					:	false ;
		$sns_tw			=	$data['sns_twitter']	??	'';
		$sns_fb			=	$data['sns_facebook']	??	'';
		$sns_hb			=	$data['sns_hatena']		??	'';
		$html_thumbnail	=	null;
		$html_favicon	=	null;

		// リダイレクトURL
		if	($url_redir ) {
			$url		=	$url_redir;
		}

		// ラッピング
		if	($is_internal ) {
			$html_wrap_op		=	'<div class="lkc-internal-wrap">';
			$html_wrap_cl		=	'</div>';
			$added_text			=	isset($this->options['in-added-text'] )		?	esc_attr($this->options['in-added-text'] )		:	null ;
			$heading_text		=	isset($this->options['in-heading-text'] )	?	esc_attr($this->options['in-heading-text'] )	:	null ;
			$more_text			=	isset($this->options['in-more-text'] )		?	esc_attr($this->options['in-more-text'] )		:	null ;
			$thumbnail_alt		=	isset($this->options['in-thumbnail-alt'] )	?	esc_attr($this->options['in-thumbnail-alt'] )	:	null ;
			$favicon_alt		=	isset($this->options['in-siteicon-alt'] )	?	esc_attr($this->options['in-siteicon-alt'] )		:	null ;
			$sw_thumbnail		=	isset($this->options['in-thumbnail'] )		?	esc_attr($this->options['in-thumbnail'] )		:	0 ;
			$sw_favicon			=	isset($this->options['in-siteicon'] )		?	esc_attr($this->options['in-siteicon'] )			:	0 ;
		} else {
			$html_wrap_op			=	'<div class="lkc-external-wrap">';
			$html_wrap_cl			=	'</div>';
			$added_text				=	isset($this->options['ex-added-text'] )		?	esc_attr($this->options['ex-added-text'] )		:	null ;
			$heading_text			=	isset($this->options['ex-heading-text'] )	?	esc_attr($this->options['ex-heading-text'] )	:	null ;
			$more_text				=	isset($this->options['ex-more-text'] )		?	esc_attr($this->options['ex-more-text'] )		:	null ;
			$thumbnail_alt			=	isset($this->options['ex-thumbnail-alt'] )	?	esc_attr($this->options['ex-thumbnail-alt'] )	:	null ;
			$favicon_alt			=	isset($this->options['ex-siteicon-alt'] )	?	esc_attr($this->options['ex-siteicon-alt'] )		:	null ;
			$sw_thumbnail			=	isset($this->options['ex-thumbnail'] )		?	esc_attr($this->options['ex-thumbnail'] )		:	0 ;
			$sw_favicon				=	isset($this->options['ex-siteicon'] )		?	esc_attr($this->options['ex-siteicon'] )			:	0 ;
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
		if	(($favicon_alt		<>	'' )	&&		(strstr($favicon_alt, '%' )		<>		'' ) )	{
			$temp				=	$favicon_alt;
			$temp				=	preg_replace('/%DOMAIN_URL%/',	$domain_url,			$temp );
			$temp				=	preg_replace('/%DOMAIN%/',		$domain,				$temp );
			$temp				=	preg_replace('/%URL%/',			rawurlencode($url ),	$temp );
			$favicon_alt		=	esc_html($temp );
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

		// ファビコン取得
		if	($this->options['info-position'] ) {
			if	($sw_favicon == 1 || $sw_favicon == 13 ) {							// 直接取得
				if	($is_internal ) {
					$favicon_url	=	get_site_icon_url(16 );						// 自サイトのサイトアイコン
				} elseif	(!$is_preview ) {
					$favicon_url	=	$this->pz_GetImage($favicon_url );			// 外部サイトのファビコンをキャッシュ
				}
				if	($favicon_url ) {
					$html_favicon	=	'<div  class="lkc-favicon"><img src="'.esc_url($favicon_url ).'" alt="'.esc_attr($favicon_alt ).'" width="16" height="16" /></div>';
				} elseif	($sw_favicon == 13 ) {									// 直接取得に失敗
					$sw_favicon	=	3;
				}
			}
			if	($sw_favicon == 3 ) {												// WebAPIを利用
				if	($is_preview && $favicon_url ) {
					$html_favicon	=	'<div class="lkc-favicon"><img src="'.esc_url($favicon_url ).'" alt="'.esc_attr($favicon_alt ).'" width="16" height="16" /></div>';
				// サイトアイコン取得WebAPI
				} elseif	($this->options['favicon-api'] ) {
					$temp					=	$this->options['favicon-api'];
					if	(strstr($temp, '%' )	<>	'' ) {
						$temp				=	preg_replace('/%TITLE%/',		$title,					$temp );
						$temp				=	preg_replace('/%SITE_NAME%/',	$site_name,				$temp );
						$temp				=	preg_replace('/%DOMAIN_URL%/',	$domain_url,			$temp );
						$temp				=	preg_replace('/%DOMAIN%/',		$domain,				$temp );
						$temp				=	preg_replace('/%URL%/',			rawurlencode($url ),	$temp );
					}
					$html_favicon	=	'<div class="lkc-favicon"><img src="'.esc_url($temp ).'" alt="'.esc_attr($favicon_alt ).'" width="16" height="16" /></div>';
				}
			}
		}

		// リンク先URL
		if	(!$no_failure && $this->options['flg-unlink'] && ($alive_result < 100 || $alive_result >= 400 ) ) {
			// Not Found の時は見え消ししてリンクしない
			$html_a_op_all	=	'<div class="lkc-unlink">';
			$html_a_cl_all	=	'</div>';
			$html_a_op		=	null;
			$html_a_cl		=	null;
			$html_st_op		=	'<strike>';
			$html_st_cl		=	'</strike>';
		} elseif	($this->options['flg-linkall'] ) {
			// カード全体をリンク（どこをクリックしても良いのが分かり易い）
			$html_a_op_all	=	'<a class="lkc-link no_icon" href="'.esc_url($url ).'" data-lkc-id="'.esc_attr($data_id ).'"'.$target.$rel.'>';
			$html_a_cl_all	=	'</a>';
			$html_a_op		=	null;
			$html_a_cl		=	null;
			$html_st_op		=	null;
			$html_st_cl		=	null;
		} else {
			// タイトルとかURLとかを個別でリンク（タイトルや抜粋文などの文字を範囲指定をしてコピー等がし易い）
			$html_a_op_all	=	null;
			$html_a_cl_all	=	null;
			$html_a_op		=	'<a class="lkc-link no_icon" href="'.esc_url($url ).'" data-lkc-id="'.esc_attr($data_id ).'"'.$target.$rel.'>';
			$html_a_cl		=	'</a>';
			$html_st_op		=	null;
			$html_st_cl		=	null;
		}

		// ソーシャルカウントの表示
		$sns				=	null;
		$html_sns_title		=	null;
		$html_sns_info		=	null;
		if	($this->options['sns-position'] ) {
			// カード全体をリンクにするときは表示のみ
			if	($this->options['flg-linkall'] ) {
				if	($this->options['sns-tw'] && $sns_tw > 0 ) {
					if	($this->options['sns-tw-x'] ) {
						$sns	.=	' <div class="lkc-sns-tw">'.sprintf(($sns_tw == 1 ? __('%d tweet', 'pz-linkcard' ) : __('%d tweets', 'pz-linkcard' ) ), $sns_tw ).'</div>';
					} else {
						$sns	.=	' <div class="lkc-sns-x">'.sprintf(($sns_tw == 1 ? __('%d post',  'pz-linkcard' ) : __('%d posts',  'pz-linkcard' ) ), $sns_tw ).'</div>';
					}
				}
				if	($this->options['sns-fb'] && $sns_fb > 0 ) {
					$sns	.=	' <div class="lkc-sns-fb">'.sprintf(($sns_fb == 1 ? __('%d share',  'pz-linkcard' ) : __('%d shares',  'pz-linkcard' ) ), $sns_fb ).'</div>';
				}
				if	($this->options['sns-hb'] && $sns_hb > 0 ) {
					$sns	.=	' <div class="lkc-sns-hb">'.sprintf(($sns_hb == 1 ? __('%d user',   'pz-linkcard' ) : __('%d users',   'pz-linkcard' ) ), $sns_hb ).'</div>';
				}
			} else {
				// 外部リンクアイコンを表示させるプラグイン対応のため no_icon を付与
				$url_noscheme	=	preg_replace('/(http|https):(\/\/.*)/', '$2', $url );	// スキームを外す

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
			switch		($display_date_mode ) {
			case	1:
				$html_date	=	'<div class="lkc-date">'.__('&#x1f552;&#xfe0f;', 'pz-linkcard' ).$this->pz_date(PZLKC_DATE_FORMAT, strtotime($post_date ) ).'</div>';
				break;
			case	2:
				$html_date	=	'<div class="lkc-date">'.__('&#x1f552;&#xfe0f;', 'pz-linkcard' ).$this->pz_date(PZLKC_DATE_FORMAT, strtotime($post_modified ) ).'</div>';
				break;
			case	3:
				$html_date	=	'<div class="lkc-date">'.__('&#x1f552;&#xfe0f;', 'pz-linkcard' ).$this->pz_date(PZLKC_DATE_FORMAT, strtotime($post_date ) ).'&ensp;'.__('&#x1F501;&#xfe0f;', 'pz-linkcard' ).$this->pz_date(PZLKC_DATE_FORMAT, strtotime($post_modified ) ).'</div>';
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
		$html_info		=	'<div class="lkc-info"'.($is_preview ? ' data-pz-preview-info' : '').'>'.$html_a_op.$html_favicon.$html_domain.$html_added.$html_a_cl.$html_sns_info.$html_url2.'</div>';

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
			$html_tag	=	$html_wrap_op.$html_heading.$html_a_op_all.'<div class="lkc-card">'.'<div class="lkc-content"'.($is_preview ? ' data-pz-preview-content' : '').'>'.$html_a_op.$html_thumbnail.$html_title.$html_a_cl.$html_sns_title.$html_url1.$html_date.$html_excerpt.$html_moretag.'</div>'.'<div class="clear">'.'</div>'.'</div>'.$html_a_cl_all.$html_wrap_cl;
		}
		$enclose_tag	=	isset($this->options['enclose-tag'] ) ? strtolower($this->options['enclose-tag'] ) : (!empty($this->options['blockquote'] ) ? 'blockquote' : 'div');
		if	(!in_array($enclose_tag, array('div', 'blockquote', 'figure', 'article', 'section', 'nav', 'aside' ), true ) ) {
			$enclose_tag	=	'div';
		}
		$enclose_tag	=	tag_escape($enclose_tag ) ?: 'div';
		$html_tag		=	'<'.$enclose_tag.' class="'.esc_attr($class_id ).'"'.($is_preview && !empty($atts['preview-card'] ) ? ' data-pz-preview-card="'.esc_attr($atts['preview-card'] ).'"' : '').'>'.$html_tag.'</'.$enclose_tag.'>';

		return	$html_tag;
	}

	// URLのエンコード（DB格納用のURL作成）
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
		if	(preg_match('/((https?|file|ftp|data|ogg):\/\/[^\s<>]+)/sui', $url, $m ) ) {
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

		// タイムオーバー
		$opt	=	array('timeout' => 30 );

		// 保存期間満了でソーシャルカウントをリセット
		if	($this->now > $data['sns_nexttime'] && $data['update_result'] >= 100 && $data['update_result'] < 400 ) {
			$sns_renew		=	true;
		}

		// エンコードURL
		$url_raw	=	rawurlencode($data['url'] );

		// Twitter Digitminimiのcount.jsoonを使用
		//if	(isset($this->options['sns-tw'] ) && !is_null($this->options['sns-tw'] ) ) {
		//	$count_before	=	isset($data['sns_twitter'] ) ? $data['sns_twitter'] : -1;
		//	if	($sns_renew || $count_before < 0 ) {
		//		$result	=	wp_safe_remote_get('https://jsoon.digitiminimi.com/twitter/count.json?url=' .$url_raw, $opt );
		//		if	(isset($result ) && !is_wp_error($result ) && $result['response']['code'] == 200 ) {
		//			$json 	=	json_decode($result['body'] );
		//			$count	=	intval($json->count );
		//			if	($count > $count_before ) {
		//				$data['sns_twitter']	=	$count;
		//				$update_cnt	=	true;
		//			}
		//		}
		//	}
		//}

		// facebook
		//if	(isset($this->options['sns-fb'] ) && !is_null($this->options['sns-fb'] ) ) {
		//	$count_before	=	intval(isset($data['sns_facebook'] ) ? $data['sns_facebook'] : -1 );
		//	if	($sns_renew || $count_before < 0 ) {
		//		$result	=	wp_safe_remote_get('https://graph.facebook.com?fields=og_object{engagement}&id=' .$url_raw, $opt );
		//		if	(isset($result ) && !is_wp_error($result ) && $result['response']['code'] == 200 ) {
		//			$json 	=	json_decode($result['body'] );
		//			$count	=	intval($json->{'og_object'}->{'engagement'}->{'count'});
		//			if	($count > $count_before ) {
		//				$data['sns_facebook']	=	$count;
		//				$update_cnt	=	true;
		//			}
		//		}
		//	}
		//}

		// はてなブックマーク
		if	(isset($this->options['sns-hb'] ) && !is_null($this->options['sns-hb'] ) ) {
			$count_before	=	isset($data['sns_hatena'] ) ? $data['sns_hatena'] : -1;
			if	($sns_renew || $count_before < 0 ) {
				$result	=	wp_safe_remote_get('http://api.b.st-hatena.com/entry.count?url=' .$url_raw, $opt );
				if	(isset($result ) && !is_wp_error($result ) && $result['response']['code'] == 200 ) {
					$count	=	intval($result['body'] );
					if	($count > $count_before ) {
						$data['sns_hatena']	=	$count;
						$update_cnt	=	true;
					}
				}
			}
		}

		// 登録してから一週間までは毎日、それ以降は週一回更新（取得が固まらないようにランダム時間付与）
		$data['sns_time']			=	$this->now;
		if	($update_cnt || ($this->now - $data['regist_time'] < WEEK_IN_SECONDS ) ) {
			$data['sns_nexttime']	=	$this->now + DAY_IN_SECONDS + rand(0, DAY_IN_SECONDS );	// 1day + 0-24h
		} else {
			$data['sns_nexttime']	=	$this->now + WEEK_IN_SECONDS + rand(0, DAY_IN_SECONDS );	// 7days + 0-24h
		}
		// MINUTE_IN_SECONDS	= 60
		// HOUR_IN_SECONDS		= 60	*	MINUTE_IN_SECONDS	= 3600
		// DAY_IN_SECONDS		= 24	*	HOUR_IN_SECONDS		= 86400
		// WEEK_IN_SECONDS		= 7		*	DAY_IN_SECONDS		= 604800
		// YEAR_IN_SECONDS		= 365	*	DAY_IN_SECONDS

		// DBの宣言
		global	$wpdb;

		// DB更新
		$result	=	$wpdb->update($this->db_name, $data, array('id' => $data['id'] ) );

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
			$data		=	$wpdb->get_row($wpdb->prepare("SELECT * FROM $this->db_name WHERE url=%s", $url ) );
		} elseif	(isset($data['id'] ) && !is_null($data['id'] ) ) {
			$data_id	=	intval($data['id'] );
			$data		=	$wpdb->get_row($wpdb->prepare("SELECT * FROM $this->db_name WHERE id=%d", $data_id ) );
		} else {
			return	null;
		}
		if	($wpdb->last_error ) {			// DBエラーのとき、初期化する
			$this->hook_activate();
		}
		if	(is_wp_error($data ) ) {
			return	null;
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

		// リンク先URL
		$url					=	$this->pz_EncodeURL($data['url'] ,true );
		if	(!$url ) {
			return	null;
		}

		// URL戻す
		$data['url']			=	$url;

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
		$data['charset']			=	isset($data['charset'] )		? $data['charset']			: 'Unknown';	// リンク先：文字コード
		if	(!isset($data['update_result'] ) || $data['update_result'] <= 0 ) {
			$data['update_result']	=	200;
		}
		$data['no_failure']			=	isset($data['no_failure'] )		? $data['no_failure']		: 0 ;			// 結果コードがエラーでも成功と見なす

		// 登録時情報
		if	(!isset($data['regist_time'] ) || !$data['regist_time'] ) {
			$data['regist_title']	=	$data['title'];																// 登録時：タイトル
			$data['regist_excerpt']	=	$data['excerpt'];															// 登録時：抜粋文
			$data['regist_charset']	=	$data['charset'];															// 登録時：文字コード
			$data['regist_result']	=	$data['update_result'];														// 登録時：結果コード
			$data['regist_time']	=	$this->now;
		}

		// 日本語項目のエンティティ文字を出コード
		$data['title']				=	html_entity_decode($data['title'] );										// リンク先：タイトル
		$data['excerpt']			=	html_entity_decode($data['excerpt'] );										// リンク先：抜粋文
		$data['regist_title']		=	html_entity_decode($data['regist_title'] );									// 登録時：タイトル
		$data['regist_excerpt']		=	html_entity_decode($data['regist_excerpt'] );								// 登録時：抜粋文

		// 生存確認
		if	(!isset($data['alive_time'] ) || !$data['alive_time'] ) {
			$data['alive_result']	=	$data['update_result'];
			$data['alive_time']		=	$this->now;
			$data['alive_nexttime']	=	$this->now + WEEK_IN_SECONDS * 4 + rand(0, DAY_IN_SECONDS );
		}

		// SNS関連
		$data['sns_twitter']		=	isset($data['sns_twitter']	)	? $data['sns_twitter']		: -1;			// SNS：Twitter
		$data['sns_facebook']		=	isset($data['sns_facebook'] )	? $data['sns_facebook']		: -1;			// SNS：facebook
		$data['sns_hatena']			=	isset($data['sns_hatena'] )		? $data['sns_hatena']		: -1;			// SNS：はてなブックマーク
		$data['sns_nexttime']		=	isset($data['sns_nexttime'] )	? $data['sns_nexttime']		: $this->now;	// SNS：次回取得日時
		$data['sns_time']			=	isset($data['sns_time'] )		? $data['sns_time']			: $this->now;	// SNS：最終取得日時

		// 使われている記事ID
		global	$wpdb;
		$use_post_id_t				=	$wpdb->get_results($wpdb->prepare("SELECT id FROM $wpdb->prefix"."posts WHERE post_type = 'post' AND post_content LIKE '%%%s%%' ORDER BY id ASC", $data['url'] ) );
		if	($use_post_id_t ) {
			$use_post_id_t			=	(array) $use_post_id_t[0];
			$use_post_id_t			=	array_unique($use_post_id_t );
			$use_post_id_t			=	array_values($use_post_id_t );
		} else {
			$use_post_id_t			=	array();
		}
		$data['use_post_id1']		=	isset($use_post_id_t[0])		? $use_post_id_t[0]			: null;
		$data['use_post_id2']		=	isset($use_post_id_t[1])		? $use_post_id_t[1]			: null;
		$data['use_post_id3']		=	isset($use_post_id_t[2])		? $use_post_id_t[2]			: null;
		$data['use_post_id4']		=	isset($use_post_id_t[3])		? $use_post_id_t[3]			: null;
		$data['use_post_id5']		=	isset($use_post_id_t[4])		? $use_post_id_t[4]			: null;
		$data['use_post_id6']		=	isset($use_post_id_t[5])		? $use_post_id_t[5]			: null;

		// 更新内容
		$data['mod_title']			=	($data['title'] <> $data['regist_title'] ? true : false );					// 更新：登録後からタイトル変更有無
		$data['mod_excerpt']		=	($data['excerpt'] <> $data['regist_excerpt'] ? true : false );				// 更新：登録後から抜粋文変更有無

		// 最終更新日時
		$data['update_time']		=	$this->now;

		// DB更新キー取得
		if	(!isset($data['id'] ) || !$data['id']) {
			$now	=	$this->pz_GetCache(array('url' => $data['url'] ) );
			if	(isset($now['id'] ) ) {
				$data['id']	=	$now['id'];
			}
		}

		// 桁数チェック
		global	$wpdb;
		$columns	=	$wpdb->get_results("DESCRIBE $this->db_name", ARRAY_A );
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
		if	(isset($data['id'] ) && $data['id'] ) {
			$result	=	$wpdb->update($this->db_name, $data, array('id' => $data['id'] ) );
		} else {
			$result	=	$wpdb->insert($this->db_name, $data );
		}

		// DB更新失敗の場合、挿入
		if	($result === false ) {
			unset($data['id'] );
			// DB挿入失敗の場合、日本語項目（サイト名）をクリアして挿入
			unset($data['site_name'] );
			$result =	$wpdb->insert($this->db_name, $data );
			// DB挿入失敗の場合、日本語項目（概要文）をクリアして挿入
			if	($result === false ) {
				unset($data['excerpt'] );
				$result =	$wpdb->insert($this->db_name, $data );
				// DB挿入失敗の場合、日本語項目（タイトル）をクリアして挿入
				if	($result === false ) {
					unset($data['title'] );
					$result =	$wpdb->insert($this->db_name, $data );
					// DB挿入失敗の場合、諦める
					if	($result === false ) {
						return	null;
					}
				}
			}
		}
		return	$this->pz_GetCache($data );
	}

	// キャッシュデータを削除
	private	function	pz_DelCache($data ) {
		if	($this->options['survey-mode'] ) { $this->pz_OutputLog(__FUNCTION__, '$data='.print_r($data, true ) ); }

		global	$wpdb;
		if	(!isset($data ) || !is_array($data ) ) {
			return	null;
		}
		if	(isset($data['id'] ) ) {
			$result		=	$wpdb->delete($this->db_name, array('id' => $data['id'] ), array('%d' ) );
			if	($result ) {
				return	true;
			}
		}
		if	(isset($data['url'] ) ) {
			$url		=	$this->pz_EncodeURL($data['url'], true );
			$result		=	$wpdb->delete($this->db_name, array('url' => $url ), array('%s' ) );
			if	($result ) {
				return	true;
			}
		}
		return	null;
	}

	// 内部リンク・記事情報取得
	private	function	pz_GetPost($data ) {
		if	($this->options['survey-mode'] ) { $this->pz_OutputLog(__FUNCTION__, '$data='.print_r($data, true ) ); }

		// 初期化
		$url			=	'';
		$post_id		=	'';
		$site_name		=	'';
		$domain_url		=	'';
		$domain			=	'';
		$title			=	'';
		$excerpt		=	'';
		$thumbnail		=	'';
		$favicon		=	'';
		$post_date		=	0;
		$post_fodified	=	0;

		// サイト名取得
		$site_name		=	get_bloginfo('name' );

		// ドメイン名
		$domain			=	$this->domain;
		$domain_url		=	$this->domain_url;

		// サイトアイコン
		if	(function_exists('has_site_icon' ) && has_site_icon() ) {
			$favicon			=	get_site_icon_url(16, null, 0 );
		}

		// 記事内容
		$url					=	$data['url'];
		$post_id				=	url_to_postid($url );					// 記事IDを取得
		// 記事IDが取得できた場合
		if	($post_id ) {
			// 記事IDが取得できた場合
			$update_result		=	200;									// 外部取得と同じコードをセット
			$post				=	get_post($post_id );					// 記事情報
			$title				=	$post->post_title;						// 記事タイトル
			$excerpt			=	$post->post_content;					// 記事内容から抜粋

			// 「抜粋」優先
			if	($this->options['in-get-from'] == 1 && $post->post_excerpt ) {	// 記事取得方法：「抜粋文」があった場合、優先する
				$excerpt		=	$post->post_excerpt;					// 抜粋文
			}

			// 「カスタムフィールド」優先
			if	($this->options['in-get-from'] == 3 ) {						// 記事取得方法：「カスタムフィールド」があった場合、優先する
				$meta_title		=	get_post_meta($post_id, $this->options['in-field-title'] );
				if	(array($meta_title ) && array_key_exists(0, $meta_title ) ) {
					$title		=	$meta_title[0];
				}
				$meta_excerpt	=	get_post_meta($post_id, $this->options['in-field-excerpt'] );
				if	(array($meta_excerpt ) && array_key_exists(0, $meta_excerpt ) ) {
					$excerpt	=	$meta_excerpt[0];
				}
			}

			$post_date			=	$post->post_date;						// 投稿日
			$post_modified		=	$post->post_modified;					// 更新日
			$thumbnail_id		=	get_post_thumbnail_id($post_id );		// サムネイル
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
		if	(isset($data['title'] ) && $data['title'] == $title ) {
			$before['mod_title']	=	false;
		} else {
			$before['mod_title']	=	true;
		}
		if	(isset($data['excerpt'] ) && $data['excerpt'] == $excerpt ) {
			$before['mod_excerpt']	=	false;
		} else {
			$before['mod_excerpt']	=	true;
		}
		if	(!isset($data['use_post_id1'] ) ) {
			$data['use_post_id1']	=	get_the_ID();
		}
		$url_info					=	$this->Pz_GetURLInfo($url );	// URL解析（自サイトチェック）
		$data['scheme']				=	$url_info['scheme'] ?? '';		// スキーム
		$data['domain']				=	$url_info['domain'] ?? '';		// ドメイン名
		$data['site_name']			=	$site_name ?? '';
		$data['title']				=	$title ?? '';
		$data['excerpt']			=	$excerpt ?? '';
		$data['thumbnail']			=	$thumbnail ?? '';
		$data['favicon']			=	$favicon ?? '';
		$data['charset']			=	'UTF-8';
		$data['update_result']		=	$update_result ?? '';
		$data['alive_result']		=	$update_result ?? '';
		$data['favicon']			=	$favicon ?? '';
		$data['use_post_id1']		=	$post_id ?? '';
		$data['post_date']			=	$post_date ?? '';
		$data['post_modified']		=	$post_modified ?? '';
		return	$data;
	}

	// 外部リンク・記事情報取得
	private	function	pz_GetRemote($data ) {
		global	$wp_version;

		if	($this->options['survey-mode'] ) { $this->pz_OutputLog(__FUNCTION__, '$data='.print_r($data, true ) ); }

		// リンク先URL
		$url			=	isset($data['url']) ? $data['url'] : '' ;

		// URL指定なし
		if	(!$url ) {
			return	null;
		}

		// wp_safe_remote_get/wp_safe_remote_headのオプション
		$rget_args					=	[];
		$rget_args['user-agent']	=	$this->options['flg-agent']		?	$this->options['user-agent']	// ユーザーエージェントにPz-LinkCard-Crawlerを使う
																		:	'WordPress/'.$wp_version.'; '.get_bloginfo( 'url' );
		$rget_args['sslverify']		=	$this->options['flg-sslverify']		?	true	:	false ;
		$redirect_limit				=	$this->options['flg-redir']			?	8		:	0;
		$rget_args['redirection']	=	0;

		// URLエンコード
		$url			=	$this->pz_EncodeURL($url ,true );
		$url_redir		=	'';
		$url_access		=	$url;
		$get_response_url	=	function($response ) {
				if	(is_wp_error($response ) || !isset($response['http_response'] ) || !is_object($response['http_response'] ) || !method_exists($response['http_response'], 'get_response_object' ) ) {
					return	null;
				}
				$response_object	=	$response['http_response']->get_response_object();
				if	(isset($response_object->url ) && $response_object->url ) {
					return	$response_object->url;
				}
				return	null;
			};
		$get_location_url	=	function($response, $base_url ) {
			if	(is_wp_error($response ) ) {
				return	null;
			}
			$location	=	wp_remote_retrieve_header($response, 'location' );
			if	(is_array($location ) ) {
				$location	=	end($location );
			}
			if	(!$location ) {
				return	null;
			}
			$location	=	trim($location );
			if	(!preg_match('/^https?:\/\//i', $location ) ) {
				$location	=	$this->pz_RelToURL($base_url, $location );
			}
			return	$this->pz_EncodeURL($location, true );
		};
		$trace_redirect_url	=	function($start_url ) use ($rget_args, $get_response_url, $get_location_url, $redirect_limit ) {
			$current_url	=	$start_url;
			$trace_args		=	$rget_args;
			$trace_args['redirection']	=	0;

			for	($i = 0; $i < $redirect_limit; $i++ ) {
				if	($this->pz_IsLocalAddress($current_url ) ) {
					return	$current_url;
				}
				$head_args				=	$trace_args;
				$head_args['method']	=	'HEAD';
				$response				=	wp_safe_remote_head($current_url, $head_args );

				if	(is_wp_error($response ) ) {
					$get_args							=	$trace_args;
					$get_args['limit_response_size']	=	1;
					$response							=	wp_safe_remote_get($current_url, $get_args );
				}

				$response_url	=	$get_response_url($response );
				if	($response_url && $response_url !== $current_url ) {
					$current_url	=	$this->pz_EncodeURL($response_url, true );
					if	($this->pz_IsLocalAddress($current_url ) ) {
						return	$current_url;
					}
					continue;
				}

				$http_code		=	intval(wp_remote_retrieve_response_code($response ) );
				$location_url	=	$get_location_url($response, $current_url );
				if	($http_code >= 300 && $http_code < 400 && $location_url && $location_url !== $current_url ) {
					$current_url	=	$location_url;
					if	($this->pz_IsLocalAddress($current_url ) ) {
						return	$current_url;
					}
					continue;
				}

				break;
			}

			return	$current_url;
		};

		// リンク先サイトのアクセス
		if	($redirect_limit > 0 ) {
			$last_url	=	$trace_redirect_url($url );
			if	($last_url && $url !== $last_url ) {
				$url_redir	=	$this->pz_EncodeURL($last_url, true );
				$url_access	=	$url_redir;
			}
		}
		$blocked_local_url	=	$this->pz_IsLocalAddress($url ) || ($url_redir && $this->pz_IsLocalAddress($url_redir ) );
		$rget_data			=	$blocked_local_url ? new WP_Error('pz_lkc_local_redirect', 'Local address blocked' ) : wp_safe_remote_get($url_access, $rget_args );	// wp_remote_get実行

		// 初期化
		$domain			=	'';
		$sitename		=	'';
		$author			=	'';
		$type			=	'';
		$title			=	'';
		$excerpt		=	'';
		$thumbnail_url	=	'';
		$favicon_url	=	'';
		$charset		=	'';
		$http_code		=	'';
		$error			=	false;

		// URL解析（自サイトチェック）
		$url_info		=	$this->Pz_GetURLInfo($url_access );
		$scheme			=	$url_info['scheme'];		// スキーム
		$domain			=	$url_info['domain'];		// ドメイン名
		$domain_url		=	$url_info['domain_url'];	// ドメインURL

		// ローカルアドレス確認
		if	($this->pz_IsLocalAddress($url ) || ($url_redir && $this->pz_IsLocalAddress($url_redir ) ) ) {
			$data['id']					=	$data['id'] ?? '';					// リンクカードID
			$data['url']				=	$url;								// リンク先：URL
			$data['url_redir']			=	$url_redir;							// リンク先：リダイレクト先
			$data['scheme']				=	$data['scheme'] ?? '';				// リンク先：URLスキーム
			$data['domain']				=	$data['domain'] ?? '';				// リンク先：URLドメイン
			$data['title']				=	__('Invalid URL', 'pz-linkcard' );	// リンク先：タイトル
			$data['excerpt']			=	'';									// リンク先：抜粋文
			$data['regist_title']		=	$data['title'] ?? '';				// リンク先：タイトル
			$data['regist_excerpt']		=	'';									// リンク先：抜粋文
			$data['sns_nexttime']		=	253402300799;						// SNS：次回取得日時
			$data['alive_nexttime']		=	253402300799;						// 生存確認：次回確認日時
			$data['regist_time']		=	$this->now;							// 登録時：登録日時
			$data['regist_result']		=	403;								// 登録時：HTTPレスポンス
			$data['update_time']		=	$this->now;							// 更新：最終更新日
			$data['update_result']		=	403;								// 更新：HTTPレスポンス

			return	$data;
		}

		$err_no						=	is_wp_error($rget_data );						// wp_remote_getエラー有無

		// エラーチェック
		if ($err_no ) {
			$http_type		=	'';
			$http_body		=	'';
			$http_code		=	-1;
		} else {
			$http_type		=	$rget_data['headers']['content-type'];		// Content_Type
			$http_body		=	$rget_data['body'];							// HTTPボディー
			$http_code		=	$rget_data['response']['code'];				// HTTPステータス
		}

		// Bodyが取得出来ていたらCharset判定
		if	((strtolower(substr($http_type, 0, 9) ) === 'text/html' || $http_code == '200' ) && $http_body ) {
			$charset		=	mb_detect_encoding($http_body, ['UTF-8', 'eucJP-win', 'SJIS-win', 'ASCII', 'EUC-JP', 'SJIS', 'JIS'], false );
			if	($charset ) {
				$http_body	=	mb_convert_encoding($http_body, $this->charset, $charset );
			}
			if	(preg_match('/charset\s*=\s*[\'"]*([A-Za-z0-9\-_]*)/si', $http_body, $m ) ) {
				$charset	=	strtolower($m[1] );
			}
		}

		// Charset判定出来ていたら
		if	($charset ) {
			// HEADタグ（METAタグ解析）
			$html_head		=	null;
			$tags			=	null;
			if	(preg_match('/<\s*head[^>]*>(.*)<\s*\/head\s*>/si', $http_body, $m ) ) {
				$html_head	=	$m[1];
				$tags		=	$this->pz_GetMeta($html_head );
			} else {
				$tags		=	$this->pz_GetMeta($http_body );
			}

			// Open Graph Protcol
			$og_url			=	$tags['og:url']					??	'';
			$og_type		=	$tags['og:type']				??	'';
			$og_sitename	=	$tags['og:site_name']			??	'';
			$og_author		=	null;
			$og_title		=	$tags['og:title']				??	'';
			$og_excerpt		=	$tags['og:description']			??	'';
			$og_image		=	$tags['og:image']				??	'';
			$og_favicon		=	null;

			// Twitter card
			$tw_url			=	null;
			$tw_sitename	=	$tags['twitter:site']			??	'';
			$tw_author		=	$tags['twitter:creator']		??	'';
			$tw_type		=	$tags['twitter:card']			??	'';
			$tw_title		=	$tags['twitter:title']			??	'';
			$tw_excerpt		=	$tags['twitter:description']	??	'';
			$tw_image		=	$tags['twitter:image']			??	'';
			$tw_favicon		=	null;

			// タイトル＆概要文
			$title			=	$tags['title']				??	'';
			$excerpt		=	$tags['description']		??	'';
			if				(!$title ) {
				if			($og_title ) {
					$title			=	$og_title;
					$excerpt		=	$og_excerpt;
				} elseif	($tw_title ) {
					$title			=	$tw_title;
					$excerpt		=	$tw_excerpt;
				}
			}
			
			// サムネイル画像
			if				(!$thumbnail_url ) {
				if			($og_image ) {
					$thumbnail_url =	$og_image;
				} elseif	($tw_image ) {
					$thumbnail_url =	$tw_image;
				}
			}
			if			($thumbnail_url	&& !preg_match('/^https*:\/\//i', $thumbnail_url, $m ) ) {
				$thumbnail_url	=	$this->pz_RelToURL($url_access, $thumbnail_url );
			}
			$thumbnail_url		=	$this->pz_EncodeURL($thumbnail_url, true );

			// サイト名
			if				(!$sitename ) {
				if			($og_sitename ) {
					$sitename		=	$og_sitename;
				} elseif	($tw_sitename ) {
					$sitename		=	$tw_sitename;
				}
			}

			// サイトアイコンURL取得
			if			(isset(	$tags['icon'] )				&& $tags['icon'] ) {
				$favicon_url	=	$tags['icon'];
			} elseif	(isset(	$tags['shortcut icon'] )	&& $tags['shortcut icon'] ) {
				$favicon_url	=	$tags['shortcut icon'];
			} elseif	(isset(	$tags['apple-touch-icon'] )	&& $tags['apple-touch-icon'] ) {
				$favicon_url	=	$tags['apple-touch-icon'];
			}
			if			($favicon_url && !preg_match('/^https*:\/\//i', $favicon_url, $m ) ) {
				$favicon_url	=	$this->pz_RelToURL($url, $favicon_url );
			}
			$favicon_url		=	$this->pz_EncodeURL($favicon_url, true );

			// タイトル整形
			$title				=	mb_strimwidth($title, 0, 500, '...' );		// 500文字制限

			// 抜粋文整形
			$excerpt			=	mb_strimwidth($excerpt, 0, 1000, '...' );	// 1000文字制限
		}

		// 呼ばれている記事
		if	(!isset($data['use_post_id1'] ) ) {
			$data['use_post_id1']	=	get_the_ID();
		}

		// リダイレクト先URL
		if	(!$url_redir || $url == $url_redir ) {
			$url_redir	=	null;
		}

		// データセット
		$data['id']					=	$data['id']				??	null;				// リンクカードID
		$data['url']				=	$url;											// リンク先：URL
		$data['url_redir']			=	$url_redir;										// リンク先：リダイレクト先URL
		$data['scheme']				=	$scheme;										// リンク先：URLスキーム
		$data['domain']				=	$domain;										// リンク先：URLドメイン
		$data['site_name']			=	$sitename;										// リンク先：サイト名称
		$data['title']				=	$title;											// リンク先：タイトル
		$data['excerpt']			=	$excerpt;										// リンク先：抜粋文
		$data['thumbnail']			=	$thumbnail_url;									// リンク先：サムネイルURL
		$data['favicon']			=	$favicon_url;									// リンク先：サイトアイコンURL
		$data['charset']			=	$charset;										// リンク先：文字コード
		$data['alive_time']			=	$this->now;														// 生存確認：確認日時
		$data['alive_nexttime']		=	$this->now + WEEK_IN_SECONDS * 4 + rand(0, DAY_IN_SECONDS );	// 生存確認：次回確認日時
		$data['alive_result']		=	$http_code;														// 生存確認：HTTPレスポンス
		$data['sns_twitter']		=	$data['sns_twitter']	??	-1;					// SNS：Twitter
		$data['sns_facebook']		=	$data['sns_facebook']	??	-1;					// SNS：facebook
		$data['sns_hatena']			=	$data['sns_hatena']		??	-1;					// SNS：はてなブックマーク
		$data['sns_time']			=	$data['sns_time']		??	0;					// SNS：最終取得日時
		$data['sns_nexttime']		=	$data['sns_nexttime']	??	0;					// SNS：次回取得日時
		$data['use_post_id1']		=	$data['use_post_id1']	??	null;				// 呼ばれている記事
		$data['use_post_id2']		=	$data['use_post_id2']	??	null;				// 呼ばれている記事
		$data['use_post_id3']		=	$data['use_post_id3']	??	null;				// 呼ばれている記事
		$data['use_post_id4']		=	$data['use_post_id4']	??	null;				// 呼ばれている記事
		$data['use_post_id5']		=	$data['use_post_id5']	??	null;				// 呼ばれている記事
		$data['use_post_id6']		=	$data['use_post_id6']	??	null;				// 呼ばれている記事
		$data['regist_title']		=	$data['regist_title']	??	$title;				// 登録時：タイトル
		$data['regist_excerpt']		=	$data['regist_excerpt']	??	$excerpt;			// 登録時：抜粋文
		$data['regist_charset']		=	$data['regist_charset']	??	$charset;			// 登録時：文字コード
		$data['regist_time']		=	$data['regist_time']	??	0;					// 登録時：登録日時
		$data['regist_result']		=	$data['regist_result']	??	$http_code;			// 登録時：HTTPレスポンス
		$data['mod_title']			=	false;											// 更新：登録後からタイトル変更有無
		$data['mod_excerpt']		=	false;											// 更新：登録後から抜粋文変更有無
		$data['update_time']		=	$this->now;										// 更新：最終更新日
		$data['update_result']		=	$http_code;										// 更新：HTTPレスポンス
		return	$data;
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
		if	(preg_match('/<\s*title\s*[^>]*>\s*([^<]*)\s*<\s*\/title\s*[^>]*>/si', $html, $m ) ) {
			$tags['title']	=	esc_html($m[1]);
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
		}
		// 返却
		return	$return_status;
	}

	// 設定を初期化する
	private	function	pz_InitializeOptions() {
		if	($this->options['survey-mode'] ) { $this->pz_OutputLog(__FUNCTION__ ); }

		// 引き継ぐ設定値
		$takeover			=	array('saved-date', 'db-version' );
		if	(!empty($this->options['initialize-exception'] ) ) {
			// 初期化例外が有効の時に引き継ぐ設定値
			array_push($takeover, 'initialize-exception', 'admin-mode', 'debug-mode' );
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
		$format	=	preg_replace('/<br\s*\/?>/', '\<\b\r\/\>', $format );
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
	}

	// プラグインの初期化
	public	function	action_init() {
		if	($this->options['survey-mode'] ) { $this->pz_OutputLog(__FUNCTION__ ); }

		add_action		('admin_menu',									array($this, 'action_admin_menu' ),					10, 1 );		// 設定メニュー
		add_action		('admin_enqueue_scripts',						array($this, 'action_admin_enqueue_scripts' ),		10, 1 );		// 設定メニュー用スクリプト
		add_action		('admin_print_styles',							array($this, 'action_admin_print_styles' ),			10, 1 );		// スタイルシートの追加
		add_action		('admin_print_scripts',							array($this, 'action_admin_print_scripts' ),		10, 1 );		// スクリプトの追加
		add_action		('wp_before_admin_bar_render',					array($this, 'action_wp_before_admin_bar_render' ),	11,	1 );		// 管理バー
		add_action		('admin_notices',								array($this, 'action_admin_notices' ),				10, 1 );		// 注意書き
		add_action		('admin_print_footer_scripts',					array($this, 'action_admin_print_footer_scripts' ),	10, 1 );		// テキストエディタ用クイックタグ
		add_filter		('plugin_action_links_'.$this->plugin_basename,	array($this, 'filter_plugin_action_links' ),		10, 1 );		// プラグイン画面
		add_filter		('mce_external_plugins',						array($this, 'filter_mce_external_plugins' ),		$this->options['mce-priority'], 1 );	// ビジュアルエディタ用ボタン
		add_filter		('mce_buttons',									array($this, 'filter_mce_buttons' ),				$this->options['mce-priority'], 1 );	// ビジュアルエディタ用ボタン
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
		$flg_alive		=	$this->pz_GetRequestOption('flg-alive', $this->options['flg-alive'] );
		$flg_alive_count	=	$this->pz_GetRequestOption('flg-alive-count', $this->options['flg-alive-count'] );
		if	($flg_alive && $flg_alive_count ) {
			global	$wpdb;
			$result		=	$wpdb->get_row("SELECT COUNT(*) AS count FROM $this->db_name WHERE alive_result < 100 OR alive_result >= 400");
			if	(isset($result ) && isset($result->count ) ) {
				$menu_manager	.=	'&nbsp;<span class="update-plugins"><span class="update-count lkc-menu-count">'.$result->count.'</span></span>';
			}
		}
		add_management_page	('pz-linkcard-manager',		$menu_manager,		'manage_options', 	self::CACHEMAN_PAGE,	array($this, 'page_cacheman' ) );
		add_options_page	('pz-linkcard-settings',	$menu_settings,		'manage_options', 	self::SETTINGS_PAGE,	array($this, 'page_settings' ) );
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
			wp_enqueue_style	(self::PLUGIN_SLUG.'-admin-css',	PZLKC_PZLKC_URL_ADMIN_CSS,			array(),			$admin_css_version );
			return;
		}

		$allowed_hooks	=	array(
			'settings_page_'.self::SETTINGS_PAGE,
			'tools_page_'.self::CACHEMAN_PAGE,
		);
		if	(!in_array($hook, $allowed_hooks, true ) ) {
			return;
		}

		wp_enqueue_script	(self::PLUGIN_SLUG.'-admin-js',		PZLKC_PZLKC_URL_ADMIN_JS,			array('jquery' ),	PZLKC_PLUGIN_VERSION, true );
		wp_localize_script	(self::PLUGIN_SLUG.'-admin-js',		'pzLinkCardAdmin', array(
			'ajaxUrl'		=>	admin_url('admin-ajax.php' ),
			'noticeNonce'	=>	wp_create_nonce('pz_lkc_clear_error_mode' ),
			'cachemanColumnsNonce'	=>	wp_create_nonce('pz_lkc_cacheman_columns' ),
			'mediaTitle'	=>	__('Select Image', 'pz-linkcard' ),
			'mediaButton'	=>	__('Use this image', 'pz-linkcard' ),
			'discardChanges'	=>	__('Discard changes?', 'pz-linkcard' ),
		) );
		wp_enqueue_style	(self::PLUGIN_SLUG.'-admin-css',	PZLKC_PZLKC_URL_ADMIN_CSS,			array(),			$admin_css_version );

		if	($hook === 'tools_page_'.self::CACHEMAN_PAGE || $hook === 'settings_page_'.self::SETTINGS_PAGE ) {
			wp_enqueue_media();
		}
		if	($hook === 'settings_page_'.self::SETTINGS_PAGE ) {
			$admin_tabs_version	=	PZLKC_PLUGIN_VERSION.'.'.filemtime($this->plugin_dir_path.'js/admin-tabs.js' );
			$preview_js_version	=	PZLKC_PLUGIN_VERSION.'.'.filemtime($this->plugin_dir_path.'js/pz-linkcard-preview.js' );
			wp_enqueue_script	(self::PLUGIN_SLUG.'-admin-tabs',	PZLKC_PZLKC_URL_ADMIN_TAB,	array('jquery' ),	$admin_tabs_version, true );
			wp_enqueue_script	(self::PLUGIN_SLUG.'-preview',		PZLKC_PZLKC_URL_PREVIEW_JS,	array(),	$preview_js_version, true );
			wp_localize_script	(self::PLUGIN_SLUG.'-preview',		'pzLinkCardPreview',		array(
				'ajaxUrl'	=>	admin_url('admin-ajax.php' ),
				'nonce'		=>	wp_create_nonce('pz_lkc_preview_render' ),
				'action'	=>	'pz_lkc_preview_render',
				'stateNonce'	=>	wp_create_nonce('pz_lkc_preview_state' ),
				'stateAction'	=>	'pz_lkc_preview_state',
				'labels'	=>	array(
					'restorePreview'		=>	__('Preview', 'pz-linkcard' ),
					'restorePreviewAria'	=>	__('Preview', 'pz-linkcard' ),
					'referenced'			=>	__('Referenced', 'pz-linkcard' ),
					'youMayAlsoLike'		=>	__('You may also like', 'pz-linkcard' ),
					'previewPostDate'		=>	$this->pz_date(PZLKC_DATE_FORMAT, strtotime('2026-09-12 00:00:00' ) ),
					'previewModifiedDate'	=>	$this->pz_date(PZLKC_DATE_FORMAT, strtotime('2026-09-13 00:00:00' ) ),
				),
			) );
			wp_enqueue_script	(self::PLUGIN_SLUG.'-color-picker',	PZLKC_PZLKC_URL_COLOR_PICKER_JS,	array(),	PZLKC_PLUGIN_VERSION, true );
			wp_localize_script	(self::PLUGIN_SLUG.'-color-picker',	'pz_lkc_color_picker', array(
				'labels'	=>	array(
					'clear'			=>	__('Clear', 'pz-linkcard' ),
					'selectColor'	=>	__('Select color', 'pz-linkcard' ),
				),
			) );
			wp_enqueue_style	(self::PLUGIN_SLUG.'-color-picker',	PZLKC_PZLKC_URL_COLOR_PICKER_CSS,	array(),	PZLKC_PLUGIN_VERSION );
		}
	}

	// Pz-LinkCard挿入ダイアログを表示する投稿編集画面か
	private	function	is_editor_modal_screen($hook = null ) {
		if	(empty($this->options['flg-edit-insert'] ) ) {
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
			'shortcode'		=>	$shortcodes[0],
			'shortcodes'	=>	$shortcodes,
			'title'			=>	'Pz-LinkCard',
			'placeholder'	=>	__('Enter the URL and press Enter', 'pz-linkcard' ),
			'description'	=>	__('Create a Pz-LinkCard shortcode.', 'pz-linkcard' ),
		) );

		$block_args	=	array(
			'title'				=>	'Pz-LinkCard',
			'description'		=>	__('Create a Pz-LinkCard shortcode.', 'pz-linkcard' ),
			'category'			=>	'widgets',
			'icon'				=>	'admin-links',
			'supports'			=>	array(
				'inserter'	=>	false,
			),
			'attributes'		=>	array(
				'url'		=>	array(
					'type'		=>	'string',
					'default'	=>	'',
				),
				'shortcode'	=>	array(
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

		wp_enqueue_style(self::PLUGIN_SLUG.'-block-editor', PZLKC_PZLKC_URL_ADMIN_CSS, array(), PZLKC_PLUGIN_VERSION.'.'.filemtime($this->plugin_dir_path.'css/admin.css' ) );
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
		if	($this->options['flg-compress'] ) {
			wp_enqueue_style	(self::PLUGIN_SLUG.'-block-card-css',	PZLKC_URL_STYLE.'style.min.css',	array(),	$css_version );
		} else {
			wp_enqueue_style	(self::PLUGIN_SLUG.'-block-card-css',	PZLKC_URL_STYLE.'style.css',		array(),	$css_version );
		}
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
		if	($this->options['css-add-url'] ) {
			wp_enqueue_style	(self::PLUGIN_SLUG.'-css-add',		$this->options['css-add-url'],			array(),	$css_version );
		}
		$this->enqueue_excerpt_fit_script();
		// クリック回数
		if	($this->options['flg-click-count'] ) {
			wp_enqueue_script	(
				'pz-lkc-click',	
				plugin_dir_url(__FILE__) . 'js/click-counter.js',	
				[],		
				PZLKC_PLUGIN_VERSION,
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

		if		(is_user_logged_in() ) {
			$user_data	=	wp_get_current_user();
			if		(property_exists($user_data, 'caps' ) && array_key_exists('administrator', $user_data->caps ) ) {
				if	($user_data->caps['administrator'] ) {
					// 管理者
				}
			}
		}
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

	private	function	pz_GetCachemanColumnKeys() {
		return	array('id', 'excerpt', 'charset', 'domain', 'sns', 'regist_time', 'update_time', 'sns_time', 'alive_time', 'post_id', 'click_count', 'result' );
	}

	private	function	pz_GetCachemanPerPageChoices() {
		return	array(10, 20, 50, 100);
	}

	// クリックカウント
	public	function	action_ajax_pz_lkc_click_count() {
		if	(array_key_exists('debug-mode', $this->options ) && $this->options['debug-mode'] && array_key_exists('survey-mode', $this->options ) && $this->options['survey-mode'] ) { $this->pz_OutputLog(__FUNCTION__ ); }

		// nonce チェック
		if	(!check_ajax_referer('pz_lkc_nonce', 'nonce', false ) ) {
			wp_send_json_error('invalid nonce');
		}

		// 入力チェック
		$lkc_id	=	isset($_POST['lkc_id'])	?	intval($_POST['lkc_id'] )		:	'';
		if	(empty($lkc_id)) {
			wp_send_json_error('unknown Data ID.');
			return;
		}

		// カウントをインクリメント
		global $wpdb;
		$updated = $wpdb->query($wpdb->prepare("UPDATE $this->db_name SET click_count = click_count + 1 WHERE id = %s;", $lkc_id ) );

		// 更新結果
		if	($updated ) {
			wp_send_json_success('Click counted');
		} else {
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

		if	($this->options['flg-edit-insert'] ) {
			$plugins[ "pz_linkcard_tinymce" ]	=	$this->plugin_dir_url.'js/mce-button.js';
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

	// WP-CRONスケジュール（SNSカウント取得）
	public	function	schedule_hook_check() {
		if	($this->options['survey-mode'] ) { $this->pz_OutputLog(__FUNCTION__ ); }

		$log	=	null;
		require_once('lib/pz-linkcard-cron-sns.php' );
		return		$log;
	}

	// WP-CRONスケジュール（存在チェック）
	public	function	schedule_hook_alive() {
		if	($this->options['survey-mode'] ) { $this->pz_OutputLog(__FUNCTION__ ); }

		$log	=	null;
		require_once('lib/pz-linkcard-cron-alive.php' );
		return		$log;
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
