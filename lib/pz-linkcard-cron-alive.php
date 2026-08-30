<?php defined('ABSPATH' ) || wp_die; ?>
<?php
	// WP-CRONスケジュール（存在チェック）
	if (!$this->options['flg-alive'] ) {
		$log	.=	'Clear schedule "Site Alive Check".'.PHP_EOL;
		wp_clear_scheduled_hook(self::CRON_ALIVE );
		//return	null;
	}

	// DBの宣言
	global	$wpdb;

	// 次回生存確認日時を越えているものを抽出
	$proc_datas	=	$wpdb->get_results($wpdb->prepare("SELECT url,alive_time FROM $this->db_name WHERE alive_nexttime < %d ORDER BY alive_time ASC, id ASC", $this->now ) );

	// 実行ログ
	$message	=	sprintf('There were %d links that passed the next "Link Alive Check" confirmation date and time.', count($proc_datas ) );
	$log		.=	$message.PHP_EOL;
	if	($this->options['survey-mode'] ) {
		$this->pz_OutputLOG(__FUNCTION__, $message );
	}

	// 生存確認
	$proc_count	=	0;
	if (isset($proc_datas ) && is_array($proc_datas ) && count($proc_datas) > 0) {
		foreach($proc_datas as $data ) {
			$proc_count++;

			// 5件を超えたら、1時間後に続きを処理する
			if ($proc_count > 5) {
				$log	.=	'Break.'.PHP_EOL;
				wp_schedule_single_event(time() + 3600, self::CRON_ALIVE );
				break;
			}

			// リンク先を取得
			if (isset($data ) && isset($data->url ) ) {
				$before	=	$this->pz_GetCache( array( 'url' => $data->url ) );
				$after	=	$this->pz_GetCURL( $before );
				if	($before['title']   == $after['title'] ) {
					$before['mod_title']	=	false;
				} else {
					$before['mod_title']	=	true;
				}
				if	($before['excerpt'] == $after['excerpt'] ) {
					$before['mod_excerpt']	=	false;
				} else {
					$before['mod_excerpt']	=	true;
				}

				if	($before['alive_result'] < 400 && $after['alive_result'] >= 400 ) {
					$before['alive_nexttime']	=	$this->now + DAY_IN_SECONDS  * 2 + rand(0, HOUR_IN_SECONDS );		// 次回チェックは2日後
				} else {
					$before['alive_nexttime']	=	$this->now + WEEK_IN_SECONDS * 4 + rand(0, DAY_IN_SECONDS );		// 次回チェックは4週間後
				}
				$before['alive_result']		=	$after['alive_result'];
				$before['alive_time']		=	$this->now;

				if	(!$before['thumbnail'] ) {
					$before['thumbnail']	=	$after['thumbnail'];
				}
				if	(!$before['favicon'] ) {
					$before['favicon']		=	$after['favicon'];
				}
				$result		=	$this->pz_SetCache($before );

				// 実行ログ
				$message	=	'['.$proc_count.'] Confirmed the "Link Alive Check". (NextTime='.date('Y-m-d H:i:s', $result['alive_nexttime'] ).' Result='.$result['alive_result'].' URL='.$result['url'].')';
				$log		.=	$message.PHP_EOL;
				if	($this->options['survey-mode'] ) {
					$this->pz_OutputLOG(__FUNCTION__, $message );
				}
			}
		}
	}
