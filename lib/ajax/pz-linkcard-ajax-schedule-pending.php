<?php
defined('ABSPATH') || wp_die;

if (!current_user_can('manage_options')) {
	wp_send_json_error(__('You do not have permission to update LinkCard data.', 'pz-linkcard'), 403);
}
if (!check_ajax_referer('pz_lkc_schedule_pending', 'nonce', false)) {
	wp_send_json_error(__('Invalid request.', 'pz-linkcard'), 403);
}

$counts = $this->pz_GetSchedulePendingCounts();
if (!$counts) {
	wp_send_json_error(null, 500);
}
wp_send_json_success(array_map(static function($count) {
	return number_format_i18n(intval($count));
}, array($counts->sns, $counts->alive, $counts->unregistered)));
