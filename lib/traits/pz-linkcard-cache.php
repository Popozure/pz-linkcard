<?php
defined('ABSPATH' ) || wp_die;

trait Pz_LinkCard_Cache {
	private function pz_GetCardTableSQL() {
		global $wpdb;
		return require __DIR__.'/../pz-linkcard-schema-card.php';
	}

	// Atomically share the last site access between both cron workers.
	private function pz_ClaimCronSiteAccess($url, $domain = '') {
		global $wpdb;
		$host = wp_parse_url((string) $url, PHP_URL_HOST);
		$site = strtolower(rtrim(trim(is_string($host) && $host !== '' ? $host : (string) $domain), '.'));
		$key = 'pz_lkc_cron_site_'.md5($site !== '' ? $site : (string) $url);
		$interval = intval($this->options['cron-site-interval'] ?? 30);
		if (!in_array($interval, array(10, 30, 60, 180, 300, 600, 1800, 3600, 21600), true)) $interval = 30;
		$now = time();
		if (add_option($key, $now, '', false)) return 0;
		$claimed = $wpdb->query($wpdb->prepare(
			"UPDATE {$wpdb->options} SET option_value = %s WHERE option_name = %s AND CAST(option_value AS UNSIGNED) <= %d",
			(string) $now, $key, $now - $interval
		));
		if ($claimed === 1) {
			wp_cache_delete($key, 'options');
			return 0;
		}
		$last = intval($wpdb->get_var($wpdb->prepare("SELECT option_value FROM {$wpdb->options} WHERE option_name = %s", $key)));
		return max($now + 1, $last + $interval);
	}

	private function pz_ScheduleCronRetry($hook, $timestamp) {
		$next = wp_next_scheduled($hook);
		if (!$next || $next > $timestamp) {
			if ($next) wp_clear_scheduled_hook($hook);
			wp_schedule_single_event(max(time() + 1, $timestamp), $hook);
		}
	}

	// キャッシュデータを取得
	private	function	pz_GetCache($data, &$error = null ) {
		$error	=	null;

		if	(!isset($data ) || !is_array($data ) ) {
			return	null;
		}

		global	$wpdb;
		if	(!empty($data['url'] ) ) {
			$url		=	$this->pz_EncodeURL($data['url'], true );
			$cache_key	=	'url:'.md5($url );
			if	(array_key_exists($cache_key, $this->request_cache ) ) {
				return	$this->request_cache[$cache_key];
			}
			$data		=	$wpdb->get_row($wpdb->prepare("SELECT * FROM $this->db_card WHERE url_key=%s AND url=%s", md5($url ), $url ) );
		} elseif	(isset($data['id'] ) && !is_null($data['id'] ) ) {
			$data_id	=	intval($data['id'] );
			$cache_key	=	'id:'.$data_id;
			if	(array_key_exists($cache_key, $this->request_cache ) ) {
				return	$this->request_cache[$cache_key];
			}
			$data		=	$wpdb->get_row($wpdb->prepare("SELECT * FROM $this->db_card WHERE id=%d", $data_id ) );
		} else {
			return	null;
		}
		if	($wpdb->last_error ) {			// DBエラーのとき、初期化する
			$error	=	$wpdb->last_error;
			$this->hook_activate();
		}
		if	(is_wp_error($data ) ) {
			$error	=	$data->get_error_message();
			return	null;
		}
		$data	=	(array) $data;
		$this->request_cache[$cache_key]	=	$data;
		if	(!empty($data['id'] ) ) {
			$this->request_cache['id:'.intval($data['id'] )]	=	$data;
		}
		return (array) $data;				// Arrayに直して返す
	}

	// キャッシュデータを保存
	private	function	pz_SetCache($data, &$error = null ) {
		$error	=	null;

		// 項目が空っぽ
		if	(!isset($data ) || !is_array($data ) ) {
			$error	=	__('The cache data is invalid.', 'pz-linkcard' );
			return	null;
		}
		if	(!isset($data['url'] ) || !$data['url'] ) {
			$error	=	__('The URL is empty.', 'pz-linkcard' );
			return	null;
		}
		$is_pending	=	array_key_exists('regist_time', $data ) && (is_null($data['regist_time'] ) || intval($data['regist_time'] ) === 0 )
			&& array_key_exists('charset', $data ) && is_null($data['charset'] );

		// リンク先URL
		$url					=	$this->pz_EncodeURL($data['url'] ,true );
		if	(!$url || !preg_match('{\Ahttps?://[a-z0-9]+}i', $url ) ) {
			$error	=	__('The URL could not be normalized.', 'pz-linkcard' );
			return	null;
		}

		// URL戻す
		$data['url']			=	$url;
		$data['url_key']		=	md5($url );

		// ID
		if	(isset($data['id'] ) && !$data['id'] ) {
			unset($data['id']);
		}

		// スキームとドメイン
		$url_m = parse_url($url);
		$data['scheme']				=	isset($url_m['scheme'])			?	$url_m['scheme']		: null;
		$data['domain']				=	isset($url_m['host'])			?	$url_m['host']			: null;

		// 記事内容等
		$data['url_redir']			=	isset($data['url_redir'] )		? $data['url_redir']		: null;			// リンク先：リダイレクト
		$data['site_name']			=	isset($data['site_name'] )		? $data['site_name']		: null;			// リンク先：サイト名称
		$data['title']				=	isset($data['title'] )			? $data['title']			: null;			// リンク先：タイトル
		$data['excerpt']			=	isset($data['excerpt'] )		? $data['excerpt']			: null;			// リンク先：抜粋文
		$data['thumbnail']			=	isset($data['thumbnail'] )		? $data['thumbnail']		: null;			// リンク先：サムネイルURL
		$data['favicon']			=	isset($data['favicon'] )		? $data['favicon']			: null;			// リンク先：サイトアイコンURL
		$has_charset				=	array_key_exists('charset', $data ) && !is_null($data['charset'] );
		$data['charset']			=	$this->pz_NormalizeCharsetName(isset($data['charset'] ) ? $data['charset'] : 'Unknown' );	// リンク先：文字コード
		foreach	(array('regist_result', 'update_result', 'alive_result' ) as $result_key ) {
			if	(!array_key_exists($result_key, $data ) || $data[$result_key] === '' || is_null($data[$result_key] ) || intval($data[$result_key] ) < 0 ) {
				$data[$result_key]	= null;
			} else {
				$data[$result_key]	= intval($data[$result_key] );
			}
		}
		$data['no_failure']			=	isset($data['no_failure'] )		? $data['no_failure']		: 0 ;			// 結果コードがエラーでも成功と見なす

		// 登録時情報
		if ((!isset($data['regist_time'] ) || intval($data['regist_time'] ) === 0 ) && $has_charset ) {
			$data['regist_title']	=	$data['title'];
			$data['regist_excerpt']	=	$data['excerpt'];
			$data['regist_charset']	=	$data['charset'];
			$data['regist_result']	=	$data['update_result'];
			$data['regist_time']	=	$this->now;
		}
		// 日本語項目のエンティティ文字を出コード
		$data['title']				=	html_entity_decode($data['title'] );										// リンク先：タイトル
		$data['excerpt']			=	html_entity_decode($data['excerpt'] );										// リンク先：抜粋文
		if (isset($data['regist_title'] ) ) {
			$data['regist_title']	=	html_entity_decode($data['regist_title'] );									// 登録時：タイトル
		}
		if (isset($data['regist_excerpt'] ) ) {
			$data['regist_excerpt']	=	html_entity_decode($data['regist_excerpt'] );								// 登録時：抜粋文
		}

		// 生存確認
		if	(!isset($data['alive_time'] ) || !$data['alive_time'] ) {
			$data['alive_result']	=	$data['update_result'];
			$data['alive_time']		=	$this->now;
			$data['alive_nexttime']	=	$this->now + WEEK_IN_SECONDS * 4 + rand(0, DAY_IN_SECONDS );
		}

		// SNS関連
		foreach	(array('sns_twitter', 'sns_facebook', 'sns_hatena', 'sns_qiita_likes', 'sns_qiita_stock' ) as $sns_count_key ) {
			$sns_count_value	= $data[$sns_count_key] ?? null;
			$data[$sns_count_key]	= ($sns_count_value === null || $sns_count_value === '' || intval($sns_count_value ) < 0 )
				? null
				: intval($sns_count_value );
		}
		$data['sns_nexttime']		=	isset($data['sns_nexttime'] )	? $data['sns_nexttime']		: $this->now;	// SNS：次回取得日時
		$data['sns_time']			=	isset($data['sns_time'] )		? $data['sns_time']			: $this->now;	// SNS：最終取得日時

		// 使われている記事ID
		global	$wpdb;
		$use_post_ids	=	array();
		for	($use_post_index = 1; $use_post_index <= 6; $use_post_index++ ) {
			$use_post_key	=	'use_post_id'.$use_post_index;
			if	(!empty($data[$use_post_key] ) ) {
				$use_post_ids[]	=	intval($data[$use_post_key] );
			}
		}
		$post_id_t	=	$is_pending
			? array()
			: $wpdb->get_col($wpdb->prepare(
				"SELECT ID FROM {$wpdb->posts} WHERE post_type = 'post' AND post_content LIKE %s ORDER BY ID ASC",
				'%'.$wpdb->esc_like($data['url'] ).'%'
			) );
		$use_post_ids	=	array_values(array_unique(array_filter(array_map('intval', array_merge($use_post_ids, $post_id_t ) ) ) ) );
		for	($use_post_index = 1; $use_post_index <= 6; $use_post_index++ ) {
			$data['use_post_id'.$use_post_index]	=	$use_post_ids[$use_post_index - 1] ?? null;
		}

		// 最終更新日時
		$data['update_time']		=	$this->now;

		// DB更新キー取得
		if	(!$is_pending && (!isset($data['id'] ) || !$data['id']) ) {
			$now	=	$this->pz_GetCache(array('url' => $data['url'] ) );
			if	(isset($now['id'] ) ) {
				$data['id']	=	$now['id'];
			}
		}

		// 桁数チェック
		global	$wpdb;
		static	$columns_by_table	=	array();
		if	(!array_key_exists($this->db_card, $columns_by_table ) ) {
			$columns_by_table[$this->db_card]	=	$wpdb->get_results("DESCRIBE $this->db_card", ARRAY_A );
		}
		$columns	=	$columns_by_table[$this->db_card];
		if	(isset($columns ) && is_array($columns ) ) {
			foreach	($columns as $column ) {
				$field	=	$column['Field'] ?? null;
				$type	=	$column['Type']  ?? null;
				if	(!$field || !$type || !array_key_exists($field, $data ) || is_null($data[$field] ) ) {
					continue;
				}
				if	(!preg_match('/^(?:var)?char\((\d+)\)/i', $type, $m ) ) {
					continue;
				}
				$max_length		=	intval($m[1] );
				$value			=	(string) $data[$field];
				$value_length	=	function_exists('mb_strlen' ) ? mb_strlen($value ) : strlen($value );
				if	($max_length > 0 && $value_length > $max_length ) {
					$data[$field]	=	function_exists('mb_substr' ) ? mb_substr($value, 0, $max_length ) : substr($value, 0, $max_length );
				}
			}
		}

		// DB更新
		$result		=	null;
		$insert_attempted	=	false;
		if	(isset($data['id'] ) && $data['id'] ) {
			$result	=	$wpdb->update($this->db_card, $data, array('id' => $data['id'] ) );
		} else {
			$insert_attempted	=	true;
			$result	=	$wpdb->insert($this->db_card, $data );
			if ($is_pending && $result !== false && $wpdb->insert_id ) {
				$data['id']	=	intval($wpdb->insert_id );
				$this->request_cache	=	array();
				return $data;
			}
		}

		// DB更新失敗の場合、挿入
		if	($result === false ) {
			// 同時処理ですでに同じURLが追加されていた場合は、そのレコードを使用する
			if	($insert_attempted ) {
				$this->request_cache	=	array();
				$existing	=	$this->pz_GetCache(array('url' => $data['url'] ) );
				if	(!empty($existing['id'] ) ) {
					return	$existing;
				}
			}

			unset($data['id'] );
			// DB挿入失敗の場合、日本語項目（サイト名）をクリアして挿入
			unset($data['site_name'] );
			$result =	$wpdb->insert($this->db_card, $data );
			// DB挿入失敗の場合、日本語項目（概要文）をクリアして挿入
			if	($result === false ) {
				unset($data['excerpt'] );
				$result =	$wpdb->insert($this->db_card, $data );
				// DB挿入失敗の場合、日本語項目（タイトル）をクリアして挿入
				if	($result === false ) {
					unset($data['title'] );
					$result =	$wpdb->insert($this->db_card, $data );
					// DB挿入失敗の場合、諦める
					if	($result === false ) {
						$error	=	$wpdb->last_error
							? sprintf(__('Database error: %s', 'pz-linkcard' ), $wpdb->last_error )
							: __('The cache record could not be saved.', 'pz-linkcard' );
						return	null;
					}
				}
			}
		}
		$this->request_cache	=	array();
		$saved	=	$this->pz_GetCache($data, $read_error );
		if	(empty($saved['url'] ) ) {
			$error	=	$read_error
				? sprintf(__('Database error: %s', 'pz-linkcard' ), $read_error )
				: __('The saved cache record could not be retrieved.', 'pz-linkcard' );
		}
		return	$saved;
	}

	// キャッシュデータを削除
	private	function	pz_DelCache($data ) {

		global	$wpdb;
		$this->request_cache	=	array();
		if	(!isset($data ) || !is_array($data ) ) {
			return	null;
		}
		if	(isset($data['id'] ) ) {
			$card_id	=	intval($data['id'] );
			$result		=	$wpdb->delete($this->db_card, array('id' => $card_id ), array('%d' ) );
			if	($result ) {
				return	true;
			}
		}
		if	(isset($data['url'] ) ) {
			$url		=	$this->pz_EncodeURL($data['url'], true );
			$card_id	=	intval($wpdb->get_var($wpdb->prepare("SELECT id FROM $this->db_card WHERE url = %s LIMIT 1", $url ) ) );
			$result		=	$wpdb->delete($this->db_card, array('url' => $url ), array('%s' ) );
			if	($result ) {
				return	true;
			}
		}
		return	null;
	}

}
