<?php
defined('ABSPATH' ) || wp_die;

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
