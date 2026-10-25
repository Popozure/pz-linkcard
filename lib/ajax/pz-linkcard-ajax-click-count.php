<?php
defined('ABSPATH' ) || wp_die;

	// nonce チェック
	if	(!check_ajax_referer('pz_lkc_nonce', 'nonce', false ) ) {
		wp_send_json_error('invalid nonce');
	}

	// 入力チェック
	$lkc_id	=	isset($_POST['lkc_id'])	?	intval($_POST['lkc_id'] )		:	'';
	$post_id	=	isset($_POST['post_id'])	?	absint($_POST['post_id'] )		:	0;
	$click_token	=	isset($_POST['click_token'] ) ? sanitize_text_field(wp_unslash($_POST['click_token'] ) ) : '';
	if	(empty($lkc_id)) {
		wp_send_json_error('unknown Data ID.');
		return;
	}
	$expected_click_token	=	$this->pz_GetClickToken($lkc_id, $post_id );
	if	($click_token === '' || !hash_equals($expected_click_token, $click_token ) ) {
		wp_send_json_error('invalid click token', 403 );
		return;
	}

	// カウントをインクリメント
	global $wpdb;
	$click_time	=	current_time('timestamp', false );
	$user_ip	=	isset($_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field(wp_unslash($_SERVER['REMOTE_ADDR'] ) ) : '';
	if	(!filter_var($user_ip, FILTER_VALIDATE_IP ) ) {
		$user_ip	=	'';
	}
	$ip_hash	=	$user_ip ? hash_hmac('sha256', 'pz-linkcard-click|'.date_i18n('Y-m-d', $click_time ).'|'.$user_ip, wp_salt('auth') ) : '';
	$user_agent	=	isset($_SERVER['HTTP_USER_AGENT'] ) ? sanitize_text_field(wp_unslash($_SERVER['HTTP_USER_AGENT'] ) ) : '';
	$user_agent	=	function_exists('mb_substr' ) ? mb_substr($user_agent, 0, 1024 ) : substr($user_agent, 0, 1024 );
	$click_log_enabled	=	!empty($this->options['flg-click-count'] ) && !empty($this->options['flg-click-log'] );

	// 同じカード・記事・IPハッシュの30秒以内のクリックは重複とする
	if	($click_log_enabled ) {
		$wpdb->query('START TRANSACTION' );
		$url_key	= (string) $wpdb->get_var($wpdb->prepare("SELECT url_key FROM $this->db_card WHERE id = %d FOR UPDATE", $lkc_id ) );
		if	(!$url_key ) {
			$wpdb->query('ROLLBACK' );
			wp_send_json_error('DB update failed');
			return;
		}
		$duplicate_click_id	=	$wpdb->get_var($wpdb->prepare(
			"SELECT click_id FROM $this->db_click
			WHERE url_key = %s
				AND click_time >= %d
				AND COALESCE(post_id, 0) = %d
				AND COALESCE(ip_hash, '') = %s
			ORDER BY click_time DESC
			LIMIT 1 FOR UPDATE",
			$url_key,
			$click_time - 30,
			$post_id,
			$ip_hash
		) );
		if	($duplicate_click_id ) {
			$wpdb->query('COMMIT' );
			wp_send_json_success('Click already counted');
			return;
		}
	}

	$updated = $wpdb->query($wpdb->prepare("UPDATE $this->db_card SET click_count = click_count + 1 WHERE id = %d;", $lkc_id ) );
	if	($updated && $click_log_enabled ) {
		$logged		=	$wpdb->insert(
			$this->db_click,
			array(
				'click_time'	=>	$click_time,
				'url_key'		=>	$url_key,
				'post_id'		=>	$post_id ?: null,
				'ip_hash'		=>	$ip_hash ?: null,
				'user_agent'	=>	$user_agent ?: null,
			),
			array('%d', '%s', '%d', '%s', '%s' )
		);
		if	($logged === false ) {
			$wpdb->query('ROLLBACK' );
			wp_send_json_error('Click log failed');
			return;
		}
		$wpdb->query('COMMIT' );
	}

	// 更新結果
	if	($updated ) {
		wp_send_json_success('Click counted');
	} else {
		if	($click_log_enabled ) {
			$wpdb->query('ROLLBACK' );
		}
		wp_send_json_error('DB update failed');
	}
