<?php defined('ABSPATH' ) || wp_die; ?>
<?php
	// DB作成
	global	$wpdb;
	$wpdb->hide_errors();
	require_once(ABSPATH.'wp-admin/includes/upgrade.php' );

	// CREATE TABLE
	$sql	=	"CREATE TABLE $this->db_card (
				id				BIGINT			UNSIGNED	NOT NULL	AUTO_INCREMENT,
				url				VARCHAR(3000)							DEFAULT NULL,
				url_key			CHAR(32)								DEFAULT NULL,
				url_redir		VARCHAR(3000)							DEFAULT NULL,
				scheme			VARCHAR(20)								DEFAULT NULL,
				domain			VARCHAR(200)							DEFAULT NULL,
				site_name		VARCHAR(200)							DEFAULT NULL,
				title			VARCHAR(200)							DEFAULT NULL,
				excerpt			VARCHAR(500)							DEFAULT NULL,
				charset			VARCHAR(30)								DEFAULT NULL,
				thumbnail		VARCHAR(2000)							DEFAULT NULL,
				favicon			VARCHAR(2000)							DEFAULT NULL,
				post_id			BIGINT			UNSIGNED				DEFAULT 0,
				post_date		BIGINT			UNSIGNED				DEFAULT 0,
				post_modified	BIGINT			UNSIGNED				DEFAULT 0,
				no_failure		INT				UNSIGNED				DEFAULT 0,
				click_count		BIGINT			UNSIGNED				DEFAULT 0,
				alive_result	INT										DEFAULT NULL,
				alive_time		BIGINT			UNSIGNED				DEFAULT 0,
				alive_nexttime	BIGINT			UNSIGNED				DEFAULT 0,
				sns_twitter		INT										DEFAULT NULL,
				sns_facebook	INT										DEFAULT NULL,
				sns_hatena		INT										DEFAULT NULL,
				sns_time		BIGINT			UNSIGNED				DEFAULT 0,
				sns_nexttime	BIGINT			UNSIGNED				DEFAULT 0,
				use_post_id1	INT				UNSIGNED				DEFAULT NULL,
				use_post_id2	INT				UNSIGNED				DEFAULT NULL,
				use_post_id3	INT				UNSIGNED				DEFAULT NULL,
				use_post_id4	INT				UNSIGNED				DEFAULT NULL,
				use_post_id5	INT				UNSIGNED				DEFAULT NULL,
				use_post_id6	INT				UNSIGNED				DEFAULT NULL,
				regist_title	VARCHAR(200)							DEFAULT NULL,
				regist_excerpt	VARCHAR(500)							DEFAULT NULL,
				regist_charset	VARCHAR(30)								DEFAULT NULL,
				regist_result 	INT										DEFAULT NULL,
				regist_time		BIGINT			UNSIGNED				DEFAULT 0,
				update_result	INT										DEFAULT NULL,
				update_time		BIGINT			UNSIGNED				DEFAULT 0,
				PRIMARY KEY		(id),
				UNIQUE KEY url_key (url_key),
				KEY alive_nexttime (alive_nexttime),
				KEY sns_nexttime (sns_nexttime)
			) ".$wpdb->get_charset_collate()." ;";

	// SQL更新チェック（クエリーから求まったMD5の値を比較）
	$db_version		=	md5($sql, false );

	// DBテーブルの存在確認
	$db_card_exists	=	($wpdb->get_var($wpdb->prepare("SHOW TABLES LIKE %s", $this->db_card ) ) === $this->db_card );
	if	($db_card_exists ) {
		if	($this->options['db-version']	==	$db_version	) {		// 前回使用したSQLと変更が無ければ抜ける
//			return;
		}

		// 旧仕様のバイナリハッシュは派生データなので、文字列型へ安全に作り直す
		$column_url_key	=	$wpdb->get_row("SHOW COLUMNS FROM $this->db_card LIKE 'url_key'", ARRAY_A );
		if	(isset($column_url_key['Type'] ) && preg_match('/^(?:var)?binary\b/i', $column_url_key['Type'] ) ) {
			$index_url_key	=	$wpdb->get_var("SHOW INDEX FROM $this->db_card WHERE Key_name = 'url_key'" );
			if	($index_url_key ) {
				$wpdb->query("ALTER TABLE $this->db_card DROP INDEX url_key" );
			}
			$wpdb->query("ALTER TABLE $this->db_card DROP COLUMN url_key" );
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
				$wpdb->update($this->db_click, array('card_id' => $keep_id ), array('card_id' => $delete_id ), array('%d' ), array('%d' ) );
			}
			if	(intval($data->click_count ) !== 0 ) {
				$wpdb->query($wpdb->prepare("UPDATE $this->db_card SET click_count = click_count + %d WHERE id = %d", intval($data->click_count ), $keep_id ) );
			}
			$wpdb->delete($this->db_card, array('id' => $delete_id ), array('%d' ) );
		}
	}

	// DBテーブル作成・更新
	$result		=	dbDelta($sql, true );

	// 旧仕様で未取得を表していた負数をNULLへ統一
	$wpdb->query(
		"UPDATE $this->db_card SET
			sns_twitter = CASE WHEN sns_twitter < 0 THEN NULL ELSE sns_twitter END,
			sns_facebook = CASE WHEN sns_facebook < 0 THEN NULL ELSE sns_facebook END,
			sns_hatena = CASE WHEN sns_hatena < 0 THEN NULL ELSE sns_hatena END,
			alive_result = CASE WHEN alive_result < 0 THEN NULL ELSE alive_result END,
			regist_result = CASE WHEN regist_result < 0 THEN NULL ELSE regist_result END,
			update_result = CASE WHEN update_result < 0 THEN NULL ELSE update_result END
		WHERE sns_twitter < 0
			OR sns_facebook < 0
			OR sns_hatena < 0
			OR alive_result < 0
			OR regist_result < 0
			OR update_result < 0"
	);

	// 内部リンクはリンク先投稿のID・投稿日・更新日を取得する
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
	if	(isset($url_key_index->Non_unique ) && intval($url_key_index->Non_unique ) !== 0 ) {
		$wpdb->query("ALTER TABLE $this->db_card DROP INDEX url_key, ADD UNIQUE KEY url_key (url_key)" );
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
