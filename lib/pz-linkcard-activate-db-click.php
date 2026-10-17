<?php defined('ABSPATH' ) || wp_die; ?>
<?php
	// クリックログDB作成
	global	$wpdb;
	$wpdb->hide_errors();
	require_once(ABSPATH.'wp-admin/includes/upgrade.php' );

	$sql	=	"CREATE TABLE $this->db_click (
				click_id		BIGINT			UNSIGNED	NOT NULL	AUTO_INCREMENT,
				click_time		BIGINT			UNSIGNED	NOT NULL,
				card_id			BIGINT			UNSIGNED	NOT NULL,
				post_id			BIGINT			UNSIGNED	DEFAULT NULL,
				ip_hash			CHAR(64)					DEFAULT NULL,
				user_agent		VARCHAR(1024)				DEFAULT NULL,
				PRIMARY KEY		(click_id),
				KEY card_id (card_id),
				KEY card_click (card_id, click_id),
				KEY card_time (card_id, click_time),
				KEY ip_hash (ip_hash),
				KEY click_time (click_time)
			) ".$wpdb->get_charset_collate()." ;";

	dbDelta($sql, true );
	$this->options['db-click-version']	=	md5($sql, false );
