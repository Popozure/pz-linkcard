<?php
defined('ABSPATH' ) || wp_die;

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
