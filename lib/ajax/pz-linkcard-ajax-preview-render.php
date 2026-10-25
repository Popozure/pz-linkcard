<?php
defined('ABSPATH' ) || wp_die;

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
	require(__DIR__.'/../pz-linkcard-settings-validate.php' );
	$this->pz_SetStyle('preview' );

	$css_file	=	PZLKC_DIR_STYLE.'preview.css';
	$css		=	file_exists($css_file ) ? file_get_contents($css_file ) : '';
	$this->options	=	$original_options;

	wp_send_json_success(array(
		'css'	=>	$css,
	) );
