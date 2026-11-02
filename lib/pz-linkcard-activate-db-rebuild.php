<?php
defined('ABSPATH') || wp_die;

// Build and verify a replacement before atomically swapping the live table.
return function($table, $sql, $old_card_table = null) use ($wpdb) {
	$quote = static function($name) { return '`'.str_replace('`', '``', $name).'`'; };
	$suffix = substr(md5(uniqid('', true)), 0, 10);
	$replacement = substr($table, 0, 48).'_new_'.$suffix;
	$backup = substr($table, 0, 48).'_old_'.$suffix;
	$create_sql = preg_replace('/\ACREATE TABLE\s+\S+/i', 'CREATE TABLE '.$quote($replacement), $sql, 1);
	$fail = static function() use ($wpdb, $replacement, $quote) {
		$error = $wpdb->last_error;
		$wpdb->query('DROP TABLE IF EXISTS '.$quote($replacement));
		set_transient('pz_lkc_dbdelta_error', 'Table migration failed; the original table was retained. '.$error, 300);
		return false;
	};
	if ($wpdb->query($create_sql) === false) {
		return $fail();
	}
	$old_columns = $wpdb->get_col('SHOW COLUMNS FROM '.$quote($table), 0);
	$new_columns = $wpdb->get_col('SHOW COLUMNS FROM '.$quote($replacement), 0);
	if (!$old_columns || !$new_columns) {
		return $fail();
	}
	$columns = array();
	$values = array();
	foreach ($new_columns as $column) {
		if ($column === 'url_key' && in_array('url', $old_columns, true)) {
			$value = 'MD5(`url`)';
		} elseif ($column === 'url_key' && in_array('card_id', $old_columns, true)) {
			$value = '(SELECT card.url_key FROM '.$quote($this->db_card).' AS card WHERE card.id = source.card_id)';
		} elseif ($column === 'url_key' && $old_card_table && in_array('url_key', $old_columns, true)) {
			$value = 'COALESCE((SELECT MD5(card.url) FROM '.$quote($old_card_table).' AS card WHERE card.url_key = source.url_key LIMIT 1), source.url_key)';
		} elseif ($column === 'sns_qiita_likes' && in_array('sns_qiita', $old_columns, true)) {
			$value = in_array('sns_qiita_likes', $old_columns, true)
				? 'COALESCE(source.sns_qiita_likes, source.sns_qiita)' : 'source.sns_qiita';
		} elseif (in_array($column, $old_columns, true)) {
			$value = 'source.'.$quote($column);
		} else {
			continue;
		}
		$columns[] = $quote($column);
		$values[] = $value;
	}
	if (!$columns || $wpdb->query('INSERT INTO '.$quote($replacement).' ('.implode(',', $columns).') SELECT '.implode(',', $values).' FROM '.$quote($table).' AS source') === false) {
		return $fail();
	}
	$original_count = $wpdb->get_var('SELECT COUNT(*) FROM '.$quote($table));
	$copied_count = $wpdb->get_var('SELECT COUNT(*) FROM '.$quote($replacement));
	if ($original_count === null || $copied_count === null || (string) $original_count !== (string) $copied_count) {
		return $fail();
	}
	if ($wpdb->query('RENAME TABLE '.$quote($table).' TO '.$quote($backup).', '.$quote($replacement).' TO '.$quote($table)) === false) {
		return $fail();
	}
	return $backup;
};
