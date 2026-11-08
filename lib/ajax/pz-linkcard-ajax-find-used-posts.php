<?php
defined('ABSPATH' ) || wp_die;

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

	$post_ids = $this->pz_FindUsedPostIDs($data['url']);
	if ($post_ids === null) {
		wp_send_json_error(__('Database error', 'pz-linkcard'), 500);
	}

	wp_send_json_success(array('post_ids' => array_values(array_map('intval', $post_ids ) ) ) );
