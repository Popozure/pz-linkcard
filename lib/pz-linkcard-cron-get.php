<?php
defined('ABSPATH' ) || wp_die;

global $wpdb;
<<<<<<< HEAD
$pending_rows = $wpdb->get_results(
	"SELECT id, url, url_key, domain
	FROM $this->db_card
	WHERE regist_time IS NULL OR regist_time = 0
	ORDER BY CASE
		WHEN domain IS NULL OR domain = '' OR LOWER(domain) = '(unknown)' THEN COALESCE(NULLIF(url, ''), url_key)
		ELSE domain
	END ASC, id ASC"
);
$processed_domains = array();
$skipped = false;
foreach ((array) $pending_rows as $pending_row ) {
	$domain = strtolower(trim((string) ($pending_row->domain ?? '' ) ) );
	if ($domain === '' || $domain === '(unknown)' ) {
		$url_host = wp_parse_url((string) ($pending_row->url ?? '' ), PHP_URL_HOST );
		$domain = is_string($url_host ) ? strtolower(rtrim($url_host, '.' ) ) : '';
	}
	$domain_key = $domain !== ''
		? 'domain:'.$domain
		: (!empty($pending_row->url_key ) ? 'url_key:'.$pending_row->url_key : 'url:'.$pending_row->url );
	if (isset($processed_domains[$domain_key] ) ) {
		$skipped = true;
		continue;
	}
	$processed_domains[$domain_key] = true;

	$data = $this->pz_GetCache(array('id' => intval($pending_row->id ) ) );
=======
$pending_ids = $wpdb->get_col("SELECT id FROM $this->db_card WHERE regist_time IS NULL OR regist_time = 0 ORDER BY update_time ASC, id ASC" );
foreach ((array) $pending_ids as $pending_id ) {
	$data = $this->pz_GetCache(array('id' => intval($pending_id ) ) );
>>>>>>> 29350b1902f8f563b828997fe39df1021589f71e
	if (!is_array($data ) || empty($data['url'] ) || !array_key_exists('regist_time', $data ) || (!is_null($data['regist_time'] ) && intval($data['regist_time'] ) !== 0 ) ) {
		continue;
	}
	$result = $this->pz_GetRemote($data );
	if (is_array($result ) && !empty($result['url'] ) ) {
		$this->pz_SetCache($result );
	}
}
<<<<<<< HEAD

if ($skipped && !wp_next_scheduled(self::CRON_GET ) ) {
	wp_schedule_single_event(time() + 30, self::CRON_GET );
}
=======
>>>>>>> 29350b1902f8f563b828997fe39df1021589f71e
