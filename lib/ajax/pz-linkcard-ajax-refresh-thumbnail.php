<?php
defined('ABSPATH' ) || wp_die;

	if	(!current_user_can('manage_options' ) ) {
		wp_send_json_error(__('You do not have permission to update LinkCard data.', 'pz-linkcard' ), 403 );
	}
	if	(!check_ajax_referer('pz_lkc_refresh_card', 'nonce', false ) ) {
		wp_send_json_error(__('Invalid request.', 'pz-linkcard' ), 403 );
	}

	$card_id	=	isset($_POST['card_id'] ) ? absint(wp_unslash($_POST['card_id'] ) ) : 0;
	if	($card_id <= 0 ) {
		wp_send_json_error(__('Invalid card ID', 'pz-linkcard' ), 400 );
	}

	$data	=	$this->pz_GetCache(array('id' => $card_id ) );
	if	(!isset($data ) || !is_array($data ) || empty($data['url'] ) ) {
		wp_send_json_error(__('Not selected', 'pz-linkcard' ), 404 );
	}
	if	(!empty($data['thumbnail'] ) ) {
		$this->pz_GetImage($data['thumbnail'], true );
	}

	$html	=	$this->pz_GetLinkCard(array('url' => $data['url'] ) );
	if	(!$html ) {
		wp_send_json_error(__('Failed to retrieve the post content.', 'pz-linkcard' ), 500 );
	}

	wp_send_json_success(array(
		'card_id'	=>	$card_id,
		'html'		=>	$html,
	) );
