<?php defined('ABSPATH' ) || wp_die; ?>
<?php
	// エラー
	$flg_error					=	false;
	$test_item					=	$this->options;

	// API URL
	$check_item					=	array('favicon-api', 'thumbnail-api' );
	foreach($check_item			as	$key ) {
		$temp_value				=	isset($this->options[$key] )	?	$this->options[$key]	:	'' ;
		$temp_value				=	$this->pz_EncodeURL($temp_value );
		$temp_value				=	preg_replace( array('/%DOMAIN%/i', '/%DOMAIN_URL%/i', '/%URL%/i' ), array('%DOMAIN%', '%DOMAIN_URL%', '%URL%'), $temp_value );	// パラメータ文字を大文字にする
		$temp_value				=	wp_http_validate_url($temp_value );
		$this->options[$key]	=	$temp_value;
		unset($test_item[$key] );
	}

	// 追加CSS用URL
	$check_item					=	array('css-add-url' );
	foreach($check_item			as	$key ) {
		$temp_value				=	isset($this->options[$key] )	?	$this->options[$key]	:	'' ;
		$temp_value				=	$this->pz_EncodeURL($temp_value );
		$temp_value				=	wp_http_validate_url($temp_value );
		$this->options[$key]	=	$temp_value;
		unset($test_item[$key] );
	}

	// 英数字
	$check_item					=	array('code1', 'code2', 'code3' );
	foreach($check_item			as	$key ) {
		$temp_value				=	isset($this->options[$key] )	?	$this->options[$key]	:	'' ;
		$this->options[$key]	=	preg_replace('/[^0-9a-zA-Z]/', '', $temp_value );
		unset($test_item[$key] );
	}

	// 数値
	$check_item					=	array('title-length', 'excerpt-length', 'info-length' );
	foreach($check_item			as	$key ) {
		$temp_value				=	isset($this->options[$key] )	?	$this->options[$key]	:	'' ;
		$this->options[$key]	=	preg_replace('/[^0-9]/', '', $temp_value );
		unset($test_item[$key] );
	}

	// Signed numeric
	$check_item					=	array('ex-transform-x', 'ex-transform-y', 'ex-transform-rotate', 'ex-transform-scale', 'ex-shadow-x', 'ex-shadow-y', 'ex-shadow-blur', 'ex-shadow-spread', 'in-transform-x', 'in-transform-y', 'in-transform-rotate', 'in-transform-scale', 'in-shadow-x', 'in-shadow-y', 'in-shadow-blur', 'in-shadow-spread', 'th-transform-x', 'th-transform-y', 'th-transform-rotate', 'th-transform-scale', 'th-shadow-x', 'th-shadow-y', 'th-shadow-blur', 'th-shadow-spread' );
	foreach	(array('ex', 'in', 'th' ) as $link_type ) {
		foreach	(array('', '-hover' ) as $state ) {
			foreach	(array('x', 'y', 'rotate', 'scale' ) as $part ) {
				$check_item[]		=	$link_type.$state.'-transform-'.$part;
			}
			foreach	(array('x', 'y', 'blur', 'spread' ) as $part ) {
				$check_item[]		=	$link_type.$state.'-shadow-'.$part;
			}
		}
	}
	$check_item					=	array_unique($check_item );
	foreach($check_item			as	$key ) {
		$temp_value				=	isset($this->options[$key] )	?	$this->options[$key]	:	'' ;
		$temp_value				=	intval($temp_value );
		if	(preg_match('/-(blur|spread)$/', $key ) ) {
			$temp_value			=	max(0, $temp_value );
		}
		if	(preg_match('/-scale$/', $key ) ) {
			$temp_value			=	max(1, $temp_value );
		}
		$this->options[$key]	=	strval($temp_value );
		unset($test_item[$key] );
	}
	
	// 数値（px/%）
	// Decimal numeric
	$check_item					=	array();
	foreach	(array('ex', 'in', 'th' ) as $link_type ) {
		foreach	(array('', '-hover' ) as $state ) {
			$check_item[]		=	$link_type.$state.'-transition';
		}
	}
	foreach($check_item			as	$key ) {
		$temp_value				=	isset($this->options[$key] )	?	$this->options[$key]	:	0 ;
		$temp_value				=	floatval($temp_value );
		$temp_value				=	min(10, max(0, $temp_value ) );
		$this->options[$key]	=	number_format($temp_value, 1, '.', '' );
		unset($test_item[$key] );
	}
		$check_item					=	array('width', 'thumbnail-height', 'thumbnail-width', 'ex-border-radius', 'in-border-radius', 'th-border-radius', 'ex-hover-border-radius', 'in-hover-border-radius', 'th-hover-border-radius' );
	foreach($check_item			as	$key ) {
		$temp_value				=	isset($this->options[$key] )	?	$this->options[$key]	:	'' ;
		$this->options[$key]	=	pz_TrimNumPx($temp_value, true );
		unset($test_item[$key] );
	}
	
	// 数値（px）
	$check_item					=	array('content-height', 'border-width', 'ex-border-width', 'in-border-width', 'th-border-width', 'ex-hover-border-width', 'in-hover-border-width', 'th-hover-border-width', 'title-size', 'url-size', 'excerpt-size', 'more-size', 'info-size', 'added-size', 'title-height', 'url-height', 'excerpt-height', 'more-height', 'info-height', 'added-height' );
	foreach($check_item			as	$key ) {
		$temp_value				=	isset($this->options[$key] )	?	$this->options[$key]	:	'' ;
		$this->options[$key]	=	pz_TrimNumPx($temp_value );
		unset($test_item[$key] );
	}

	// Border style
	$check_item					=	array('border-style', 'ex-border-style', 'in-border-style', 'th-border-style', 'ex-hover-border-style', 'in-hover-border-style', 'th-hover-border-style' );
	foreach($check_item			as	$key ) {
		$temp_value				=	isset($this->options[$key] )	?	$this->options[$key]	:	'' ;
		if	(!array_key_exists($temp_value, LIST_BORDER ) ) {
			$temp_value			=	'solid';
		}
		$this->options[$key]	=	$temp_value;
		unset($test_item[$key] );
	}
	
	// 色コード
	$check_item					=	array('title-color', 'title-outline-color', 'url-color', 'url-outline-color', 'excerpt-color', 'excerpt-outline-color', 'more-color', 'more-outline-color', 'info-color', 'info-outline-color', 'added-color', 'added-outline-color', 'ex-border-color', 'ex-bg-color', 'ex-shadow-color', 'ex-hover-border-color', 'ex-hover-bg-color', 'ex-hover-shadow-color', 'in-border-color', 'in-bg-color', 'in-shadow-color', 'in-hover-border-color', 'in-hover-bg-color', 'in-hover-shadow-color', 'th-border-color', 'th-bg-color', 'th-shadow-color', 'th-hover-border-color', 'th-hover-bg-color', 'th-hover-shadow-color' );
	foreach($check_item as $key ) {
		$temp_value				=	isset($this->options[$key] )	?	$this->options[$key]	:	'' ;
		$temp_value				=	preg_replace('/^#([0-9a-f])([0-9a-f])([0-9a-f])$/i', '#$1$1$2$2$3$3', $temp_value );
		if	(preg_match('/^#[0-9a-f]{6}$/i', $temp_value ) ) {
			$temp_value			=	strtolower($temp_value );
		}
		$this->options[$key]	=	$temp_value;
		unset($test_item[$key] );
	}

	// 除外URL
	$check_item					=	array('exclude-url' );
	foreach($check_item as $key ) {
		$temp_value				=	isset($this->options[$key] )	?	$this->options[$key]	:	'' ;
		$temp_value				=	preg_replace('/^\s*$/m', '', $temp_value );		// 空行削除
		$temp_value				=	preg_replace("/\n{2,}/", "\n", $temp_value );	// 連続改行削除
		$this->options[$key]	=	$temp_value;
		unset($test_item[$key] );
	}

	$key						=	'enclose-tag';
	$temp_value					=	isset($this->options[$key] ) ? strtolower($this->options[$key] ) : 'div';
	if	(!in_array($temp_value, array('div', 'blockquote', 'figure', 'article', 'section', 'nav', 'aside' ), true ) ) {
		$temp_value				=	'div';
	}
	$this->options[$key]		=	$temp_value;
	unset($test_item[$key] );

	// エラー状態のチェック
	$temp		=	$this->options['error-time'];
	if	(!is_numeric($temp ) ) {
		$temp	=	@strtotime($temp );
	}
	if	($temp	<	946728000 ) {
		$temp	=	'';
	}
	$this->options['error-time']		=	$temp;
