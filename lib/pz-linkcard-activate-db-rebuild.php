<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Build and verify a replacement before atomically swapping the live table.
return function($table, $sql, $old_card_table = null) use ($wpdb) {
	$suffix = substr(md5(uniqid('', true)), 0, 10);
	$replacement = substr($table, 0, 48).'_new_'.$suffix;
	$backup = substr($table, 0, 48).'_old_'.$suffix;
	$create_sql = preg_replace('/\ACREATE TABLE\s+\S+/i', 'CREATE TABLE %i', str_replace('%', '%%', $sql), 1);
	$fail = static function() use ($wpdb, $replacement) {
		$error = $wpdb->last_error;
		$wpdb->query($wpdb->prepare('DROP TABLE IF EXISTS %i', $replacement));
		set_transient('pz_lkc_dbdelta_error', 'Table migration failed; the original table was retained. '.$error, 300);
		return false;
	};
	// Only plugin-owned schema definitions reach this closure; the replacement table uses %i.
	// phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
	if ($wpdb->query($wpdb->prepare($create_sql, $replacement)) === false) {
		return $fail();
	}
	$old_columns = $wpdb->get_col($wpdb->prepare('SHOW COLUMNS FROM %i', $table), 0);
	$new_columns = $wpdb->get_col($wpdb->prepare('SHOW COLUMNS FROM %i', $replacement), 0);
	if (!$old_columns || !$new_columns) {
		return $fail();
	}
	$columns = array();
	$values = array();
	foreach ($new_columns as $column) {
		if ($column === 'url_key' && in_array('url', $old_columns, true)) {
			$value = 'MD5(`url`)';
		} elseif ($column === 'url_key' && in_array('card_id', $old_columns, true)) {
			$value = $wpdb->prepare('(SELECT card.url_key FROM %i AS card WHERE card.id = source.card_id)', $this->db_card);
		} elseif ($column === 'url_key' && $old_card_table && in_array('url_key', $old_columns, true)) {
			$value = $wpdb->prepare('COALESCE((SELECT MD5(card.url) FROM %i AS card WHERE card.url_key = source.url_key LIMIT 1), source.url_key)', $old_card_table);
		} elseif ($column === 'sns_qiita_likes' && in_array('sns_qiita', $old_columns, true)) {
			$value = in_array('sns_qiita_likes', $old_columns, true)
				? 'COALESCE(source.sns_qiita_likes, source.sns_qiita)' : 'source.sns_qiita';
		} elseif (in_array($column, $old_columns, true)) {
			$value = $wpdb->prepare('source.%i', $column);
		} else {
			continue;
		}
		$columns[] = $column;
		$values[] = $value;
	}
	// Expressions above are fixed SQL or prepared identifiers; columns come from SHOW COLUMNS and use %i.
	// phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.PreparedSQLPlaceholders.ReplacementsWrongNumber
	if (!$columns || $wpdb->query($wpdb->prepare('INSERT INTO %i ('.implode(',', array_fill(0, count($columns), '%i')).') SELECT '.implode(',', $values).' FROM %i AS source', array_merge(array($replacement), $columns, array($table)))) === false) {
		return $fail();
	}
	$original_count = $wpdb->get_var($wpdb->prepare('SELECT COUNT(*) FROM %i', $table));
	$copied_count = $wpdb->get_var($wpdb->prepare('SELECT COUNT(*) FROM %i', $replacement));
	if ($original_count === null || $copied_count === null || (string) $original_count !== (string) $copied_count) {
		return $fail();
	}
	if ($wpdb->query($wpdb->prepare('RENAME TABLE %i TO %i, %i TO %i', $table, $backup, $replacement, $table)) === false) {
		return $fail();
	}
	return $backup;
};
