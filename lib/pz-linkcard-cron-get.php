<?php
defined('ABSPATH' ) || wp_die;

global $wpdb;
$pending_rows = $wpdb->get_results(
	"SELECT id, url, url_key, domain
	FROM $this->db_card
	WHERE regist_time IS NULL OR regist_time = 0 OR EXISTS (SELECT 1 FROM {$wpdb->options} refresh_queue WHERE refresh_queue.option_name = CONCAT('pz_lkc_refresh_', {$this->db_card}.id))
	ORDER BY CASE
		WHEN domain IS NULL OR domain = '' OR LOWER(domain) = '(unknown)' THEN COALESCE(NULLIF(url, ''), url_key)
		ELSE domain
	END ASC, id ASC"
);
$retry_at = null;
$limit = intval($this->options['get-period-num'] ?? 50);
if (!in_array($limit, array(0, 5, 10, 20, 50, 100), true)) $limit = 50;
$processed = 0;
$interval = intval($this->options['cron-site-interval'] ?? 30);
if (!in_array($interval, array(10, 30, 60, 180, 300, 600, 1800, 3600, 21600), true)) $interval = 30;
$get_start_key = static function($row) {
	$host = wp_parse_url((string) $row->url, PHP_URL_HOST);
	$site = strtolower(rtrim(trim(is_string($host) && $host !== '' ? $host : (string) ($row->domain ?? '')), '.'));
	return 'pz_lkc_get_start_'.md5($site !== '' ? $site : (string) $row->url);
};
$site_pending_counts = array();
foreach ((array) $pending_rows as $pending_row) {
	$start_key = $get_start_key($pending_row);
	$site_pending_counts[$start_key] = ($site_pending_counts[$start_key] ?? 0) + 1;
}
foreach ((array) $pending_rows as $pending_row ) {
	if ($limit > 0 && $processed >= $limit) {
		$retry_at = $retry_at === null ? time() + 3 : min($retry_at, time() + 3);
		break;
	}
	$domain = strtolower(trim((string) ($pending_row->domain ?? '' ) ) );
	if ($domain === '' || $domain === '(unknown)' ) {
		$url_host = wp_parse_url((string) ($pending_row->url ?? '' ), PHP_URL_HOST );
		$domain = is_string($url_host ) ? strtolower(rtrim($url_host, '.' ) ) : '';
	}
	$data = $this->pz_GetCache(array('id' => intval($pending_row->id ) ) );
	$start_key = $get_start_key($pending_row);
	if (!is_array($data ) || empty($data['url'] ) || !$this->pz_IsCardRetrievalPending($data) ) {
		$site_pending_counts[$start_key]--;
		continue;
	}
	// Persist the first-item delay across cron retries, until this site's queue drains.
	add_option($start_key, time() + intdiv($interval, 2), '', false);
	$start_at = intval(get_option($start_key, 0));
	if ($start_at > time()) {
		$retry_at = $retry_at === null ? $start_at : min($retry_at, $start_at);
		continue;
	}
	$next_access = $this->pz_ClaimCronSiteAccess($data['url'], $domain);
	if ($next_access > 0) {
		$retry_at = $retry_at === null ? $next_access : min($retry_at, $next_access);
		continue;
	}
	$processed++;
	$url_info = $this->pz_GetURLInfo($data['url']);
	if (!empty($url_info['is_internal'])) {
		$result = $this->pz_GetPost($data);
		$result = is_array($result) ? $this->pz_SetCache($result) : null;
	} else {
		$result = $this->pz_RefreshExternalCard($data);
	}
	if (is_array($result)) {
		delete_option('pz_lkc_refresh_'.intval($pending_row->id));
		$site_pending_counts[$start_key]--;
	}
	else $retry_at = $retry_at === null ? time() + 60 : min($retry_at, time() + 60);
}

foreach ($site_pending_counts as $start_key => $remaining) {
	if ($remaining === 0) delete_option($start_key);
}

if ($retry_at !== null) {
	$this->pz_ScheduleCronRetry(self::CRON_GET, $retry_at);
}
