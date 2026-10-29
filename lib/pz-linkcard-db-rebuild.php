<?php
defined('ABSPATH' ) || wp_die;

// Copy only when every old column and value can be retained.
function pz_lkc_rebuild_card_table($wpdb, $table, $schema_sql ) {
	$suffix = substr(str_replace('-', '', wp_generate_uuid4() ), 0, 16 );
	$base = substr($table, 0, 40 );
	$new_table = $base.'_new_'.$suffix;
	$backup_table = $base.'_old_'.$suffix;
	$old_id = $wpdb->prepare('%i', $table );
	$new_id = $wpdb->prepare('%i', $new_table );
	$backup_id = $wpdb->prepare('%i', $backup_table );
	$create_sql = preg_replace('/^CREATE TABLE\s+\S+/', 'CREATE TABLE '.$new_id, $schema_sql, 1 );
	if ($wpdb->query($create_sql ) === false ) return false;

	$locked = false;
	$swapped = false;
	try {
		$old_columns = $wpdb->get_results("SHOW COLUMNS FROM $old_id", ARRAY_A );
		if (!$old_columns || $wpdb->last_error ) return false;
		$new_columns = $wpdb->get_results("SHOW COLUMNS FROM $new_id", ARRAY_A );
		if (!$new_columns || $wpdb->last_error ) return false;
		$new_names = array_column($new_columns, 'Field' );
		$old_names = array_column($old_columns, 'Field' );
		if (!in_array('id', $old_names, true ) || array_diff($old_names, $new_names ) ) return false;

		$columns = array_map(function($name ) use ($wpdb ) { return $wpdb->prepare('%i', $name ); }, $old_names );
		$column_sql = implode(', ', $columns );
		if ($wpdb->query("LOCK TABLES $old_id WRITE, $new_id WRITE" ) === false ) return false;
		$locked = true;
		if ($wpdb->query("INSERT INTO $new_id ($column_sql) SELECT $column_sql FROM $old_id" ) === false ) return false;
		$old_count = $wpdb->get_var("SELECT COUNT(*) FROM $old_id" );
		if ($old_count === null || $wpdb->last_error ) return false;
		$new_count = $wpdb->get_var("SELECT COUNT(*) FROM $new_id" );
		if ($new_count === null || $wpdb->last_error || (string) $old_count !== (string) $new_count ) return false;
		$differences = array();
		foreach ($columns as $column ) {
			$differences[] = "NOT (BINARY $old_id.$column <=> BINARY $new_id.$column)";
		}
		$diff_sql = "SELECT COUNT(*) FROM $old_id LEFT JOIN $new_id ON $old_id.`id` = $new_id.`id` WHERE $new_id.`id` IS NULL OR ".implode(' OR ', $differences );
		$diff_count = $wpdb->get_var($diff_sql );
		if ($diff_count === null || $wpdb->last_error || intval($diff_count ) !== 0 ) return false;
		if ($wpdb->query("RENAME TABLE $old_id TO $backup_id, $new_id TO $old_id" ) === false ) return false;
		$swapped = true;
		return true;
	} finally {
		if ($locked ) $wpdb->query('UNLOCK TABLES' );
		if (!$swapped ) $wpdb->query("DROP TABLE IF EXISTS $new_id" );
	}
}
