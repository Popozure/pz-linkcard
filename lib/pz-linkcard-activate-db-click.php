<?php defined('ABSPATH' ) || wp_die; ?>
<?php
	// クリックログDB作成
	global	$wpdb;
	require_once(ABSPATH.'wp-admin/includes/upgrade.php' );

	$sql_click = "CREATE TABLE $this->db_click (
				click_id		BIGINT			UNSIGNED	NOT NULL	AUTO_INCREMENT,
				click_time		BIGINT			UNSIGNED	NOT NULL,
				card_id			BIGINT			UNSIGNED	NOT NULL,
				post_id			BIGINT			UNSIGNED	DEFAULT NULL,
				ip_hash			CHAR(64)					DEFAULT NULL,
				user_agent		VARCHAR(1024)					DEFAULT NULL,
				PRIMARY KEY		(click_id),
				KEY card_id (card_id),
				KEY card_click (card_id, click_id),
				KEY ip_hash (ip_hash),
				KEY click_time (click_time)
			) ".$wpdb->get_charset_collate()." ;";

	dbDelta($sql_click, true );

	// 旧仕様の平文IPアドレスは引き継がず、列ごと削除する
	$user_ip_column	= $wpdb->get_row("SHOW COLUMNS FROM $this->db_click LIKE 'user_ip'", ARRAY_A );
	if	($user_ip_column ) {
		$wpdb->query("ALTER TABLE $this->db_click DROP COLUMN user_ip" );
	}
