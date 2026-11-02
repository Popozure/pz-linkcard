<?php defined('ABSPATH' ) || wp_die; ?>
<?php
	// DB作成
	global	$wpdb;
	$wpdb->hide_errors();
	require_once(ABSPATH.'wp-admin/includes/upgrade.php' );

	// 新バージョンのカードテーブル名を、旧バージョンへ戻す際に復元する
	$future_card_table = $wpdb->prefix.'pz_linkcard_card';
	$old_card_exists = ($wpdb->get_var($wpdb->prepare('SHOW TABLES LIKE %s', $this->db_card)) === $this->db_card);
	$future_card_exists = ($wpdb->get_var($wpdb->prepare('SHOW TABLES LIKE %s', $future_card_table)) === $future_card_table);
	if (!$old_card_exists && $future_card_exists) {
		$old_card_sql = str_replace('`', '``', $this->db_card);
		$future_card_sql = str_replace('`', '``', $future_card_table);
		$wpdb->query("RENAME TABLE `$future_card_sql` TO `$old_card_sql`");
	}

	// CREATE TABLE
	$sql	=	$this->pz_GetCardTableSQL();

	// SQL更新チェック（クエリーから求まったMD5の値を比較）
	$db_version		=	md5($sql, false );

	// DBテーブルの存在確認
	$db_card_exists	=	($wpdb->get_var($wpdb->prepare("SHOW TABLES LIKE %s", $this->db_card ) ) === $this->db_card );
	if ($db_card_exists && !empty($upgrade_from_264) && ($this->options['db-version'] ?? '') !== $db_version) {
		$rebuild_table = require __DIR__.'/pz-linkcard-activate-db-rebuild.php';
		$migration_card_backup = $rebuild_table($this->db_card, $sql);
		if ($migration_card_backup === false) {
			return false;
		}
		// The replacement already has the current columns and indexes.
		$db_card_exists = false;
	}
	if	($db_card_exists ) {
		// Rename the legacy Qiita likes column without discarding its counts.
		$legacy_qiita_column = $wpdb->get_var("SHOW COLUMNS FROM $this->db_card LIKE 'sns_qiita'");
		if ($legacy_qiita_column) {
			$qiita_likes_column = $wpdb->get_var("SHOW COLUMNS FROM $this->db_card LIKE 'sns_qiita_likes'");
			if (!$qiita_likes_column) {
				$qiita_renamed = $wpdb->query("ALTER TABLE $this->db_card CHANGE COLUMN sns_qiita sns_qiita_likes INT DEFAULT NULL");
				if ($qiita_renamed === false) return;
			} else {
				$qiita_copied = $wpdb->query("UPDATE $this->db_card SET sns_qiita_likes = sns_qiita WHERE sns_qiita_likes IS NULL AND sns_qiita IS NOT NULL");
				if ($qiita_copied === false) return;
				if ($wpdb->query("ALTER TABLE $this->db_card DROP COLUMN sns_qiita") === false) return;
			}
		}
		$column_url_key = $wpdb->get_row("SHOW COLUMNS FROM $this->db_card LIKE 'url_key'", ARRAY_A );
		$binary_url_key = isset($column_url_key['Type'] ) && preg_match('/^(?:var)?binary\b/i', $column_url_key['Type'] );
		$url_key_index = $wpdb->get_row("SHOW INDEX FROM $this->db_card WHERE Key_name = 'url_key'" );
		$unique_url_key = isset($url_key_index->Non_unique ) && intval($url_key_index->Non_unique ) === 0;
		if ($this->options['db-version'] == $db_version && !$binary_url_key && !$unique_url_key ) {
			return;
		}

		// dbDelta does not remove an existing UNIQUE index.
		if ($unique_url_key ) {
			$wpdb->query("ALTER TABLE $this->db_card DROP INDEX url_key" );
		}

		// Remove obsolete modification flags.
		foreach	(array('mod_title', 'mod_excerpt' ) as $obsolete_column ) {
			$column_exists	=	$wpdb->get_var($wpdb->prepare("SHOW COLUMNS FROM $this->db_card LIKE %s", $obsolete_column ) );
			if	($column_exists ) {
				$wpdb->query("ALTER TABLE $this->db_card DROP COLUMN `$obsolete_column`" );
			}
		}

		// 同じURLは最も古いIDへ統合し、クリック数とクリックログを引き継ぐ
		$result_datas	=	(array) $wpdb->get_results("SELECT id,url,click_count FROM $this->db_card WHERE url IS NOT NULL AND url <> '' ORDER BY id ASC" );
		$kept_ids		=	array();
		$db_click_exists	=	($wpdb->get_var($wpdb->prepare("SHOW TABLES LIKE %s", $this->db_click ) ) === $this->db_click );
		foreach	($result_datas as $data ) {
			$url	=	(string) $data->url;
			if	(!array_key_exists($url, $kept_ids ) ) {
				$kept_ids[$url]	=	intval($data->id );
				continue;
			}

			$keep_id	=	$kept_ids[$url];
			$delete_id	=	intval($data->id );
			if	($db_click_exists ) {
				$keep_key = (string) $wpdb->get_var($wpdb->prepare("SELECT url_key FROM $this->db_card WHERE id = %d", $keep_id));
				$delete_key = (string) $wpdb->get_var($wpdb->prepare("SELECT url_key FROM $this->db_card WHERE id = %d", $delete_id));
				if ($keep_key && $delete_key) {
					$wpdb->update($this->db_click, array('url_key' => $keep_key), array('url_key' => $delete_key), array('%s'), array('%s'));
				}
			}
			if	(intval($data->click_count ) !== 0 ) {
				$wpdb->query($wpdb->prepare("UPDATE $this->db_card SET click_count = click_count + %d WHERE id = %d", intval($data->click_count ), $keep_id ) );
			}
			$wpdb->delete($this->db_card, array('id' => $delete_id ), array('%d' ) );
		}
	}

	// DBテーブル作成・更新
	if (!empty($binary_url_key ) ) {
		// Recreate the derived key after reading the old keys above.
		$wpdb->query("ALTER TABLE $this->db_card DROP COLUMN url_key" );
	}

	$result		=	dbDelta($sql, true );

	// 旧定義の NOT NULL DEFAULT 0 を外し、取得待ちをNULLで保存できるようにする
	$regist_time_column = $wpdb->get_row("SHOW COLUMNS FROM $this->db_card LIKE 'regist_time'", ARRAY_A );
	if (isset($regist_time_column['Null'] ) && strtoupper($regist_time_column['Null'] ) !== 'YES' ) {
		$wpdb->query("ALTER TABLE $this->db_card MODIFY regist_time BIGINT UNSIGNED NULL DEFAULT NULL" );
	}

	// 旧仕様で未取得を表していた負数をNULLへ統一
	$wpdb->query(
		"UPDATE $this->db_card SET
			sns_twitter = CASE WHEN sns_twitter < 0 THEN NULL ELSE sns_twitter END,
			sns_facebook = CASE WHEN sns_facebook < 0 THEN NULL ELSE sns_facebook END,
			sns_hatena = CASE WHEN sns_hatena < 0 THEN NULL ELSE sns_hatena END,
			sns_qiita_likes = CASE WHEN sns_qiita_likes < 0 THEN NULL ELSE sns_qiita_likes END,
			sns_qiita_stock = CASE WHEN sns_qiita_stock < 0 THEN NULL ELSE sns_qiita_stock END,
			alive_result = CASE WHEN alive_result < 0 THEN NULL ELSE alive_result END,
			regist_result = CASE WHEN regist_result < 0 THEN NULL ELSE regist_result END,
			update_result = CASE WHEN update_result < 0 THEN NULL ELSE update_result END
		WHERE sns_twitter < 0
			OR sns_facebook < 0
			OR sns_hatena < 0
			OR sns_qiita_likes < 0
			OR sns_qiita_stock < 0
			OR alive_result < 0
			OR regist_result < 0
			OR update_result < 0"
	);

	// 内部リンクはリンク先投稿のID・投稿日・更新日を取得する。
	// コンストラクターから更新処理が呼ばれた場合、url_to_postid() が使用する
	// $wp_rewrite はまだ生成されていないため、初期化後までこの処理だけ遅延する。
	$update_internal_posts	=	function() use ($wpdb ) {
		$internal_link_cards	=	(array) $wpdb->get_results(
			"SELECT id, url FROM $this->db_card WHERE url IS NOT NULL AND url <> '' ORDER BY id ASC"
		);
		foreach	($internal_link_cards as $internal_link_card ) {
			$url_info	=	$this->Pz_GetURLInfo($internal_link_card->url );
			if	(empty($url_info['is_internal'] ) ) {
				continue;
			}

			$post_id	=	url_to_postid($internal_link_card->url );
			$post		=	$post_id ? get_post($post_id ) : null;
			if	(!$post ) {
				continue;
			}

			$wpdb->update(
				$this->db_card,
				array(
					'post_id'		=>	intval($post_id ),
					'post_date'		=>	intval(get_post_timestamp($post, 'date' ) ),
					'post_modified'	=>	intval(get_post_timestamp($post, 'modified' ) ),
				),
				array('id' => intval($internal_link_card->id ) ),
				array('%d', '%d', '%d' ),
				array('%d' )
			);
		}
	};

	global $wp_rewrite;
	if	(is_object($wp_rewrite ) ) {
		$update_internal_posts();
	} else {
		add_action('init', $update_internal_posts, 20 );
	}
	// if	($wpdb->last_error ) {
	// 	$error_code	=	'';
	// 	if	(function_exists('mysqli_errno' ) && isset($wpdb->dbh ) && $wpdb->dbh instanceof mysqli ) {
	// 		$error_code	=	mysqli_errno($wpdb->dbh );
	// 	}
	// 	$error_message	=	trim(
	// 		'dbDelta'.
	// 		($error_code !== '' ? ' ['.$error_code.']' : '' ).
	// 		' '.$wpdb->last_error.
	// 		' '.$wpdb->last_query
	// 	);
	// 	if	(function_exists('set_transient' ) ) {
	// 		set_transient('pz_lkc_dbdelta_error', $error_message, MINUTE_IN_SECONDS * 5 );
	// 	}
	// 	$GLOBALS['pz_lkc_dbdelta_error']	=	$error_message;
	// }

	// フィールドを追加したらエクスポート項目も見直すこと

	// SQLのMD5を保存しておく
	$this->options['db-version']	=	$db_version;

////////////////////////////////////////////////////////////////////////////////

	// URL検索用のハッシュキーを32文字のテキスト形式で揃える
	$wpdb->query("UPDATE $this->db_card SET url_key = MD5(url) WHERE url IS NOT NULL AND (url_key IS NULL OR url_key <> MD5(url))" );
	$url_key_index	=	$wpdb->get_row("SHOW INDEX FROM $this->db_card WHERE Key_name = 'url_key'" );
	if	(isset($url_key_index->Non_unique ) && intval($url_key_index->Non_unique ) === 0 ) {
		$wpdb->query("ALTER TABLE $this->db_card DROP INDEX url_key, ADD KEY url_key (url_key)" );
	}

////////////////////////////////////////////////////////////////////////////////

	// バグデータのメンテナンス（ドメイン名が空欄のもの）
	$result_datas	=	(array) $wpdb->get_results("SELECT id,url,domain FROM $this->db_card WHERE domain = '' ORDER BY id" );
	if	(isset($result_datas ) && is_array($result_datas ) && count($result_datas ) > 0 ) {
		foreach($result_datas as $data ) {
			$domain		=	'(Unknown)';
			$result		=	$wpdb->update($this->db_card, array('domain' => $domain ) , array('id' => $data->id ) );
		}
	}

	// 文字コードの表記ぶれを修正
	$result		=	$wpdb->get_results("UPDATE $this->db_card SET charset = 'UTF-8'      WHERE charset like 'UTF-8%'" );
	$result		=	$wpdb->get_results("UPDATE $this->db_card SET charset = 'EUC-JP'     WHERE charset like 'EUC-JP%'" );
	$result		=	$wpdb->get_results("UPDATE $this->db_card SET charset = 'ISO-8859-1' WHERE charset like 'ISO-8859-1%'" );
	$result		=	$wpdb->get_results("UPDATE $this->db_card SET charset = 'JIS'        WHERE charset like 'JIS%'" );
	$result		=	$wpdb->get_results("UPDATE $this->db_card SET charset = 'Shift_JIS'  WHERE charset like 'SJIS%'" );
	$result		=	$wpdb->get_results("UPDATE $this->db_card SET charset = 'Shift_JIS'  WHERE charset like 'Shift_JIS%'" );
	$result		=	$wpdb->get_results("UPDATE $this->db_card SET charset = 'US-ASCII'   WHERE charset like 'US-ASCII%'" );
	$result		=	$wpdb->get_results("UPDATE $this->db_card SET charset = 'Unknown'    WHERE charset IS NULL" );
