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
				user_ip			VARCHAR(45)					DEFAULT NULL,
				user_agent		VARCHAR(1024)					DEFAULT NULL,
				PRIMARY KEY		(click_id),
				KEY card_id (card_id),
				KEY user_ip (user_ip),
				KEY click_time (click_time)
			) ".$wpdb->get_charset_collate()." ;";

	dbDelta($sql_click, true );
