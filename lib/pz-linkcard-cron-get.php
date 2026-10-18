<?php
defined('ABSPATH' ) || wp_die;

global $wpdb;
$pending_ids = $wpdb->get_col("SELECT id FROM $this->db_card WHERE regist_time IS NULL OR regist_time = 0 ORDER BY update_time ASC, id ASC" );
foreach ((array) $pending_ids as $pending_id ) {
	$data = $this->pz_GetCache(array('id' => intval($pending_id ) ) );
	if (!is_array($data ) || empty($data['url'] ) || !array_key_exists('regist_time', $data ) || (!is_null($data['regist_time'] ) && intval($data['regist_time'] ) !== 0 ) ) {
		continue;
	}
	$result = $this->pz_GetRemote($data );
	if (is_array($result ) && !empty($result['url'] ) ) {
		$this->pz_SetCache($result );
	}
}
