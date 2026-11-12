<?php
defined('ABSPATH' ) || wp_die;

	if	(!current_user_can('manage_options' ) ) {
		wp_send_json_error(__('You do not have permission to update LinkCard data.', 'pz-linkcard' ), 403 );
	}
	if	(!check_ajax_referer('pz_lkc_cacheman_pending', 'nonce', false ) ) {
		wp_send_json_error(__('Invalid request.', 'pz-linkcard' ), 403 );
	}

	if (isset($_POST['queue_card_id'])) {
		$card_id = is_scalar($_POST['queue_card_id']) ? absint(wp_unslash($_POST['queue_card_id'])) : 0;
		if (!$card_id) wp_send_json_error(__('Invalid card ID', 'pz-linkcard'), 400);
		$data = $this->pz_GetCache(array('id' => $card_id));
		if (!is_array($data) || empty($data['url'])) wp_send_json_error(__('Not selected', 'pz-linkcard'), 404);
		if (!$this->pz_QueueCardRefresh($card_id)) wp_send_json_error(__('Failed to queue article content retrieval.', 'pz-linkcard'), 500);
		wp_send_json_success(array('queued_id' => $card_id));
	}

	$card_ids	= isset($_POST['card_ids'] ) && is_array($_POST['card_ids'] )
		? array_values(array_unique(array_filter(array_map('absint', wp_unslash($_POST['card_ids'] ) ) ) ) )
		: array();
	$ready_ids	= array();
	$ready_cards	= array();
	foreach	($card_ids as $card_id ) {
		$data = $this->pz_GetCache(array('id' => $card_id ) );
		if	(is_array($data) && !$this->pz_IsCardRetrievalPending($data) ) {
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
