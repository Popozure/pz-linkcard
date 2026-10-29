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
$last_domain_fetches = array();
$max_count = intval($this->options['get-period-num'] ?? 50 );
if (!in_array($max_count, array(1, 5, 10, 20, 50 ), true ) ) {
	$max_count = 50;
}
$domain_delay = intval($this->options['get-period-delay'] ?? 30 );
if (!in_array($domain_delay, array(0, 5, 10, 30, 60, 300, 600, 1800 ), true ) ) {
	$domain_delay = 30;
}
$processed_count = 0;
$next_time = null;
foreach ((array) $pending_rows as $pending_row ) {
	$domain = strtolower(trim((string) ($pending_row->domain ?? '' ) ) );
	if ($domain === '' || $domain === '(unknown)' ) {
		$url_host = wp_parse_url((string) ($pending_row->url ?? '' ), PHP_URL_HOST );
		$domain = is_string($url_host ) ? strtolower(rtrim($url_host, '.' ) ) : '';
	}
	$domain_key = $domain !== ''
		? 'domain:'.$domain
		: (!empty($pending_row->url_key ) ? 'url_key:'.$pending_row->url_key : 'url:'.$pending_row->url );
	$transient_key = 'pz_lkc_get_domain_'.md5($domain_key );
	if ($domain_delay > 0 ) {
		if (!array_key_exists($domain_key, $last_domain_fetches ) ) {
			$last_domain_fetches[$domain_key] = intval(get_transient($transient_key ) );
		}
		$last_fetch = $last_domain_fetches[$domain_key];
		$remaining = $last_fetch > 0 ? $last_fetch + $domain_delay - time() : 0;
		if ($remaining > 0 ) {
			$eligible_time = $last_fetch + $domain_delay;
			$next_time = $next_time === null ? $eligible_time : min($next_time, $eligible_time );
			continue;
		}
	}
	if ($processed_count >= $max_count ) {
		$retry_time = time() + 30;
		$next_time = $next_time === null ? $retry_time : min($next_time, $retry_time );
		break;
	}

	$data = $this->pz_GetCache(array('id' => intval($pending_row->id ) ) );
	if (!is_array($data ) || empty($data['url'] ) || !array_key_exists('regist_time', $data ) || (!is_null($data['regist_time'] ) && intval($data['regist_time'] ) !== 0 ) ) {
		continue;
	}
	$processed_count++;
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
	$last_domain_fetches[$domain_key] = time();
	set_transient($transient_key, $last_domain_fetches[$domain_key], 1800 );
}

if ($next_time !== null && !wp_next_scheduled(self::CRON_GET ) ) {
	wp_schedule_single_event(max(time() + 1, $next_time ), self::CRON_GET );
}
