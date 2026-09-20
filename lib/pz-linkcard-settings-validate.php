<?php defined('ABSPATH' ) || wp_die; ?>
<?php
	// Error
	$flg_error					=	false;
	$test_item					=	$this->options;

	foreach	(self::pz_GetOptionDefinitions() as $key => $definition ) {
		$temp_value				=	array_key_exists($key, $this->options ) ? $this->options[$key] : $definition['default'];
		$type					=	$definition['type'];
		$allow_null				=	!empty($definition['null'] );

		if	($temp_value === null ) {
			$this->options[$key]	=	$allow_null ? null : ($definition['default'] ?? '' );
			unset($test_item[$key] );
			continue;
		}

		switch	($type ) {
		case	'version':
		case	'string':
			$this->options[$key]	=	strval($temp_value );
			break;

		case	'textarea':
			$temp_value				=	strval($temp_value );
			$temp_value				=	preg_replace('/^\s*$/m', '', $temp_value );		// Remove blank lines.
			$this->options[$key]	=	preg_replace("/\n{2,}/", "\n", $temp_value );	// Collapse consecutive line breaks.
			break;

		case	'code':
			$this->options[$key]	=	preg_replace('/[^0-9a-zA-Z]/', '', $temp_value );
			break;

		case	'html_tag':
			$temp_value				=	strtolower($temp_value );
			if	(!in_array($temp_value, array('div', 'blockquote', 'figure', 'article', 'section', 'nav', 'aside' ), true ) ) {
				$temp_value			=	$definition['default'];
			}
			$this->options[$key]	=	$temp_value;
			break;

		case	'flag':
			$this->options[$key]	=	$temp_value ? '1' : '';
			break;

		case	'numeric':
			if	(($temp_value === '' || $temp_value === null ) && $allow_null ) {
				$this->options[$key]	=	null;
				break;
			}
			$temp_value				=	intval($temp_value );
			if	(preg_match('/-(blur|spread)$/', $key ) ) {
				$temp_value			=	max(0, $temp_value );
			}
			if	(preg_match('/-scale$/', $key ) ) {
				$temp_value			=	max(1, $temp_value );
			}
			if	(preg_match('/-opacity$/', $key ) ) {
				$temp_value			=	min(100, max(0, $temp_value ) );
			}
			$this->options[$key]	=	$temp_value;
			break;

		case	'timestamp':
			if	(!is_numeric($temp_value ) ) {
				$temp_value			=	@strtotime($temp_value );
			}
			if	($temp_value < 946728000 ) {
				$temp_value			=	$allow_null ? null : '';
			}
			$this->options[$key]	=	$temp_value;
			break;

		case	'float':
			$temp_value				=	floatval($temp_value );
			$temp_value				=	min(10, max(0, $temp_value ) );
			$this->options[$key]	=	number_format($temp_value, 1, '.', '' );
			break;

		case	'pixel':
			$this->options[$key]	=	pz_TrimNumPx($temp_value );
			break;

		case	'pixel_per':
			$this->options[$key]	=	pz_TrimNumPx($temp_value, true );
			break;

		case	'unit':
			$temp_value				=	strtolower($temp_value );
			$this->options[$key]	=	in_array($temp_value, array('px', '%' ), true ) ? $temp_value : $definition['default'];
			break;

		case	'url':
			$temp_value				=	$this->pz_EncodeURL($temp_value );
			$this->options[$key]	=	wp_http_validate_url($temp_value );
			break;

		case	'url_template':
			$temp_value				=	$this->pz_EncodeURL($temp_value );
			$temp_value				=	preg_replace( array('/%DOMAIN%/i', '/%DOMAIN_URL%/i', '/%URL%/i' ), array('%DOMAIN%', '%DOMAIN_URL%', '%URL%'), $temp_value );
			$this->options[$key]	=	wp_http_validate_url($temp_value );
			break;

		case	'color':
			$temp_value				=	preg_replace('/^#([0-9a-f])([0-9a-f])([0-9a-f])$/i', '#$1$1$2$2$3$3', $temp_value );
			if	(preg_match('/^#[0-9a-f]{6}$/i', $temp_value ) ) {
				$temp_value			=	strtolower($temp_value );
			}
			$this->options[$key]	=	$temp_value;
			break;

		case	'border':
			if	(!array_key_exists($temp_value, LIST_BORDER ) ) {
				$temp_value			=	'solid';
			}
			$this->options[$key]	=	$temp_value;
			break;

		default:
			$this->options[$key]	=	strval($temp_value );
			break;
		}

		unset($test_item[$key] );
	}

	if	($this->options['width'] === null ) {
		$this->options['width']		=	null;
		$this->options['width-unit']	=	null;
	} elseif	(empty($this->options['width-unit'] ) ) {
		$this->options['width-unit']	=	self::pz_GetDefaultOption('width-unit' );
	}
