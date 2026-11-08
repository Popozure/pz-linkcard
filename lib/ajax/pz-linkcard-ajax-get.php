<?php
defined('ABSPATH' ) || wp_die;

if (!check_ajax_referer('pz_lkc_ajax_get', 'nonce', false ) ) {
	wp_send_json_error(array('message' => 'Invalid nonce.' ), 403 );
}

$card_id = isset($_POST['card_id'] ) ? absint(wp_unslash($_POST['card_id'] ) ) : 0;
if (!$card_id ) {
	wp_send_json_error(array('message' => 'Invalid card ID.' ), 400 );
}

$source_post_id = isset($_POST['source_post_id']) ? absint(wp_unslash($_POST['source_post_id'])) : 0;
$expires = isset($_POST['expires']) ? absint(wp_unslash($_POST['expires'])) : 0;
$card_token = isset($_POST['card_token']) && is_string($_POST['card_token']) ? wp_unslash($_POST['card_token']) : '';
if (!$expires || $expires <= time() || $card_token === ''
	|| !hash_equals($this->pz_GetAjaxCardToken($card_id, $source_post_id, $expires), $card_token)) {
	wp_send_json_error(array('message' => 'Invalid or expired card token.'), 403);
}
if ($source_post_id) {
	$source_post = get_post($source_post_id);
	if (!$source_post || (!$this->pz_IsPostPubliclyViewable($source_post)
		&& (post_password_required($source_post) || !current_user_can('read_post', $source_post_id)))) {
		wp_send_json_error(array('message' => 'Card not found.'), 404);
	}
}

$data = $this->pz_GetCache(array('id' => $card_id ) );
if (!is_array($data ) || empty($data['url'] ) ) {
	wp_send_json_error(array('message' => 'Card not found.' ), 404 );
}

$url_info = $this->Pz_GetURLInfo($data['url'] );
if (!empty($url_info['is_internal'] ) ) {
	if (!$this->pz_IsInternalCardPubliclyViewable($data['url'], $data)) {
		wp_send_json_error(array('message' => 'Card not found.'), 404);
	}
}

if (!array_key_exists('regist_time', $data ) || is_null($data['regist_time'] ) || intval($data['regist_time'] ) === 0 ) {
	wp_send_json_success(array('ready' => false ) );
}

$html = $this->pz_GetHTML(array(
	'url'			=> $data['url'],
	'pz-card-data'	=> $data,
), $url_info );
if (!$html) {
	wp_send_json_error(array('message' => 'Card not found.'), 404);
}
wp_send_json_success(array(
	'ready'	=> true,
	'html'	=> $html,
) );
