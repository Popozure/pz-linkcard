<?php
defined('ABSPATH' ) || wp_die;

	if	(!current_user_can('manage_options' ) ) {
		wp_send_json_error('forbidden', 403 );
	}
	if	(!check_ajax_referer('pz_lkc_clear_error_mode', 'nonce', false ) ) {
		wp_send_json_error('invalid nonce', 403 );
	}

	$this->options['error-mode']	=	0;
	$this->pz_SaveOptions();

	wp_send_json_success();
