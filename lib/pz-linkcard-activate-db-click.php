<?php defined('ABSPATH' ) || wp_die; ?>
<?php
	// クリックログDB作成
	global	$wpdb;
	$wpdb->hide_errors();
	require_once(ABSPATH.'wp-admin/includes/upgrade.php' );

	$sql	=	"CREATE TABLE $this->db_click (
				click_id		BIGINT			UNSIGNED	NOT NULL	AUTO_INCREMENT,
				click_time		BIGINT			UNSIGNED	NOT NULL,
				url_key			CHAR(32)					NOT NULL,
				post_id			BIGINT			UNSIGNED	DEFAULT NULL,
				ip_hash			CHAR(64)					DEFAULT NULL,
				user_agent		VARCHAR(1024)				DEFAULT NULL,
				PRIMARY KEY		(click_id),
				KEY url_key (url_key),
				KEY url_click (url_key, click_id),
				KEY url_time (url_key, click_time),
				KEY ip_hash (ip_hash),
				KEY click_time (click_time)
			) ".$wpdb->get_charset_collate()." ;";

	dbDelta($sql, true );

	// 旧バージョンのクリックテーブルが残っている場合は url_key へ変換する
	$click_table_exists = ($wpdb->get_var($wpdb->prepare('SHOW TABLES LIKE %s', $this->db_click)) === $this->db_click);
	$has_url_key = $click_table_exists && (bool) $wpdb->get_var("SHOW COLUMNS FROM $this->db_click LIKE 'url_key'");
	$has_card_id = $click_table_exists && (bool) $wpdb->get_var("SHOW COLUMNS FROM $this->db_click LIKE 'card_id'");
	if ($click_table_exists && $has_card_id) {
		if (!$has_url_key) {
			$wpdb->query("ALTER TABLE $this->db_click ADD COLUMN url_key CHAR(32) NULL AFTER click_time");
		}
		$wpdb->query("UPDATE $this->db_click AS click INNER JOIN $this->db_card AS card ON card.id = click.card_id SET click.url_key = BINARY card.url_key");
		$wpdb->query("ALTER TABLE $this->db_click DROP INDEX card_id, DROP INDEX card_click, DROP INDEX card_time");
		$wpdb->query("ALTER TABLE $this->db_click DROP COLUMN card_id");
		$wpdb->query("ALTER TABLE $this->db_click MODIFY url_key CHAR(32) NOT NULL");
	}

	$this->options['db-click-version']	=	md5($sql, false );
