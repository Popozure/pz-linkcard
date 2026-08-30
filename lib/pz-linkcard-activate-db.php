<?php defined('ABSPATH' ) || wp_die; ?>
<?php
	// DB作成
	global	$wpdb;
	$wpdb->hide_errors();

	// CREATE TABLE
	$sql = "CREATE TABLE $this->db_name (
				id				BIGINT			UNSIGNED	NOT NULL	AUTO_INCREMENT,
				url				VARCHAR(3000)							DEFAULT NULL,
				url_redir		VARCHAR(3000)							DEFAULT NULL,
				scheme			VARCHAR(20)								DEFAULT NULL,
				domain			VARCHAR(200)							DEFAULT NULL,
				site_name		VARCHAR(200)							DEFAULT NULL,
				title			VARCHAR(200)							DEFAULT NULL,
				excerpt			VARCHAR(500)							DEFAULT NULL,
				charset			VARCHAR(30)								DEFAULT NULL,
				thumbnail		VARCHAR(2000)							DEFAULT NULL,
				favicon			VARCHAR(2000)							DEFAULT NULL,
				post_date		BIGINT			UNSIGNED	NOT NULL	DEFAULT 0,
				post_modified	BIGINT			UNSIGNED	NOT NULL	DEFAULT 0,
				no_failure		INT				UNSIGNED				DEFAULT 0,
				click_count		BIGINT			UNSIGNED	NOT NULL	DEFAULT 0,
				alive_result	INT										DEFAULT -1,
				alive_time		BIGINT			UNSIGNED	NOT NULL	DEFAULT 0,
				alive_nexttime	BIGINT			UNSIGNED	NOT NULL	DEFAULT 0,
				sns_twitter		INT										DEFAULT -1,
				sns_facebook	INT										DEFAULT -1,
				sns_hatena		INT										DEFAULT -1,
				sns_time		BIGINT			UNSIGNED	NOT NULL	DEFAULT 0,
				sns_nexttime	BIGINT			UNSIGNED	NOT NULL	DEFAULT 0,
				use_post_id1	INT				UNSIGNED,
				use_post_id2	INT				UNSIGNED,
				use_post_id3	INT				UNSIGNED,
				use_post_id4	INT				UNSIGNED,
				use_post_id5	INT				UNSIGNED,
				use_post_id6	INT				UNSIGNED,
				regist_title	VARCHAR(200)							DEFAULT NULL,
				regist_excerpt	VARCHAR(500)							DEFAULT NULL,
				regist_charset	VARCHAR(30)								DEFAULT NULL,
				regist_result 	INT										DEFAULT 0,
				regist_time		BIGINT			UNSIGNED	NOT NULL	DEFAULT 0,
				mod_title		INT				UNSIGNED	NOT NULL	DEFAULT 0,
				mod_excerpt		INT				UNSIGNED	NOT NULL	DEFAULT 0,
				update_result	INT										DEFAULT 0,
				update_time		BIGINT			UNSIGNED	NOT NULL	DEFAULT 0,
				PRIMARY KEY		(id)
			) ".$wpdb->get_charset_collate()." ;";

	// SQL更新チェック（クエリーから求まったMD5の値を比較）
	$db_version		=	md5($sql, false );

	// DBテーブルの存在確認
	if ( $wpdb->get_var( $wpdb->prepare( "SHOW TABLES LIKE %s", $this->db_name ) ) === $this->db_name ) {
		if	($this->options['db-version']	==	$db_version	) {		// 前回使用したSQLと変更が無ければ抜ける
			return;
		}
	}

	// DBテーブル作成・更新
	require_once(ABSPATH.'wp-admin/includes/upgrade.php' );
	$result		=	dbDelta($sql, true );
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

	// 旧バージョンのURLハッシュキーを削除
	$column_url_key	=	$wpdb->get_var("SHOW COLUMNS FROM $this->db_name LIKE 'url_key'" );
	if	($column_url_key ) {
		$index_url_key	=	$wpdb->get_var("SHOW INDEX FROM $this->db_name WHERE Key_name = 'url_key'" );
		if	($index_url_key ) {
			$result		=	$wpdb->query("ALTER TABLE $this->db_name DROP INDEX url_key" );
		}
		$result			=	$wpdb->query("ALTER TABLE $this->db_name DROP COLUMN url_key" );
	}

////////////////////////////////////////////////////////////////////////////////

	// バグデータのメンテナンス（重複URLの削除）
	$result_datas	=	(array) $wpdb->get_results("SELECT url,id FROM $this->db_name ORDER BY url,id" );
	$last_url		=	null;
	$last_id		=	null;
	if	(isset($result_datas ) && is_array($result_datas ) && count($result_datas ) > 0 ) {
		foreach($result_datas as $data ) {
			if ($data->url == $last_url && $data->id <> $last_id ) {
				$result		=	$wpdb->delete($this->db_name, array('id' => $data->id ), array('%d' ) );
			}
			$last_url		=	$data->url;
			$last_id		=	$data->id;
		}
	}

	// バグデータのメンテナンス（ドメイン名が空欄のもの）
	$result_datas	=	(array) $wpdb->get_results("SELECT id,url,domain FROM $this->db_name WHERE domain = '' ORDER BY id" );
	if	(isset($result_datas ) && is_array($result_datas ) && count($result_datas ) > 0 ) {
		foreach($result_datas as $data ) {
			$domain		=	'(Unknown)';
			$result		=	$wpdb->update($this->db_name, array('domain' => $domain ) , array('id' => $data->id ) );
		}
	}

	// 文字コードの表記ぶれを修正
	$result		=	$wpdb->get_results("UPDATE $this->db_name SET charset = 'UTF-8'      WHERE charset like 'UTF-8%'" );
	$result		=	$wpdb->get_results("UPDATE $this->db_name SET charset = 'EUC-JP'     WHERE charset like 'EUC-JP%'" );
	$result		=	$wpdb->get_results("UPDATE $this->db_name SET charset = 'ISO-8859-1' WHERE charset like 'ISO-8859-1%'" );
	$result		=	$wpdb->get_results("UPDATE $this->db_name SET charset = 'JIS'        WHERE charset like 'JIS%'" );
	$result		=	$wpdb->get_results("UPDATE $this->db_name SET charset = 'Shift_JIS'  WHERE charset like 'SJIS%'" );
	$result		=	$wpdb->get_results("UPDATE $this->db_name SET charset = 'Shift_JIS'  WHERE charset like 'Shift_JIS%'" );
	$result		=	$wpdb->get_results("UPDATE $this->db_name SET charset = 'US-ASCII'   WHERE charset like 'US-ASCII%'" );
	$result		=	$wpdb->get_results("UPDATE $this->db_name SET charset = 'Unknown'    WHERE charset IS NULL" );
