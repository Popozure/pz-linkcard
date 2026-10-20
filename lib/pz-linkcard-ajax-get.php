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

<<<<<<< HEAD
$url_info = $this->Pz_GetURLInfo($data['url'] );
if (!empty($url_info['is_internal'] ) ) {
	$post_id = url_to_postid($data['url'] );
	$post_id = $post_id ?: absint($data['post_id'] ?? 0 );
	if ($post_id && !$this->pz_IsInternalCardPubliclyViewable($data['url'], $data ) ) {
		$post = get_post($post_id );
		if (!$post || !empty($this->options['flg-public-only'] ) || post_password_required($post ) || !is_user_logged_in() || !current_user_can('read_post', $post_id ) ) {
			wp_send_json_error(array('message' => 'Card not found.' ), 404 );
		}
	}
}

=======
>>>>>>> 29350b1902f8f563b828997fe39df1021589f71e
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
