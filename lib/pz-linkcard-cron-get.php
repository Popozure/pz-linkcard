<?php
defined('ABSPATH' ) || wp_die;

global $wpdb;
$pending_rows = $wpdb->get_results(
	"SELECT id, url, url_key, domain
	FROM $this->db_card
	WHERE regist_time IS NULL OR regist_time = 0
	ORDER BY CASE
		WHEN domain IS NULL OR domain = '' OR LOWER(domain) = '(unknown)' THEN COALESCE(NULLIF(url, ''), url_key)
		ELSE domain
	END ASC, id ASC"
);
$retry_at = null;
$limit = intval($this->options['get-period-num'] ?? 50);
if (!in_array($limit, array(0, 5, 10, 20, 50, 100), true)) $limit = 50;
$processed = 0;
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
	if (!is_array($data ) || empty($data['url'] ) || !array_key_exists('regist_time', $data ) || (!is_null($data['regist_time'] ) && intval($data['regist_time'] ) !== 0 ) ) {
		continue;
	}
	$next_access = $this->pz_ClaimCronSiteAccess($data['url'], $domain);
	if ($next_access > 0) {
		$retry_at = $retry_at === null ? $next_access : min($retry_at, $next_access);
		continue;
	}
	$processed++;
	$result = $this->pz_GetRemote($data );
	if (is_array($result ) && !empty($result['url'] ) ) {
		$this->pz_SetCache($result );
		if (!empty($result['thumbnail'] ) && in_array((string) ($this->options['ex-thumbnail'] ?? '' ), array('1', '13' ), true ) ) {
			$this->pz_GetImage($result['thumbnail'] );
		}
		if (!empty($result['favicon'] ) && in_array((string) ($this->options['ex-siteicon'] ?? '' ), array('1', '13' ), true ) ) {
			$this->pz_GetImage($result['favicon'] );
		}
	}
}

if ($retry_at !== null) {
	$this->pz_ScheduleCronRetry(self::CRON_GET, $retry_at);
}
