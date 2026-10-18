<?php
defined('ABSPATH' ) || wp_die;

if (!check_ajax_referer('pz_lkc_ajax_get', 'nonce', false ) ) {
	wp_send_json_error(array('message' => 'Invalid nonce.' ), 403 );
}

$card_id = isset($_POST['card_id'] ) ? absint(wp_unslash($_POST['card_id'] ) ) : 0;
if (!$card_id ) {
	wp_send_json_error(array('message' => 'Invalid card ID.' ), 400 );
}

$data = $this->pz_GetCache(array('id' => $card_id ) );
if (!is_array($data ) || empty($data['url'] ) ) {
	wp_send_json_error(array('message' => 'Card not found.' ), 404 );
}

if (!array_key_exists('regist_time', $data ) || is_null($data['regist_time'] ) || intval($data['regist_time'] ) === 0 ) {
	wp_send_json_success(array('ready' => false ) );
}

$html = $this->pz_GetHTML(array(
	'url'			=> $data['url'],
	'pz-card-data'	=> $data,
) );
wp_send_json_success(array(
	'ready'	=> true,
	'html'	=> $html,
) );
