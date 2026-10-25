<?php
defined('ABSPATH' ) || wp_die;

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
