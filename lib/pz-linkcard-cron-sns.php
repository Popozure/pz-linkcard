<?php defined('ABSPATH' ) || wp_die; ?>
<?php
	// WP-CRONスケジュール（SNSカウント取得）
	if (!$this->options['sns-position'] ) {
		$log	.=	'Clear schedule "SNS Count Check".'.PHP_EOL;
		wp_clear_scheduled_hook(self::CRON_CHECK );
		//return	null;
	}

	// DBの宣言
	global	$wpdb;

	// SNS次回取得日時を越えているものを抽出
	$proc_datas	=	$wpdb->get_results($wpdb->prepare("SELECT url,sns_nexttime FROM $this->db_name WHERE sns_nexttime < %d ORDER BY sns_nexttime ASC", $this->now ) );

	// 実行ログ
	$message	=	sprintf('There were %d links that passed the next "Check SNS Count" confirmation date and time.', count($proc_datas ) );
	$log		.=	$message.PHP_EOL;
	if	($this->options['survey-mode'] ) {
		$this->pz_OutputLOG(__FUNCTION__, $message );
	}

	// SNSカウント取得
	$proc_count	=	0;
	if (isset($proc_datas ) && is_array($proc_datas ) && count($proc_datas ) > 0) {
		foreach($proc_datas as $data ) {
			$proc_count++;

			// 10件を超えたら、5分後に続きを処理する
			if ($proc_count > 10) {
				$log	.=	'Break.'.PHP_EOL;
				wp_schedule_single_event(time() + 300, self::CRON_CHECK );
				break;
			}

			// SNSカウントを取得
			$result		=	$this->pz_RenewSNSCount(array('url' => $data->url ) );	// SNS取得＆キャッシュ更新

			// 実行ログ
			$message	=	'['.$proc_count.'] '.'Confirmed the "Check SNS Count". (NextTime='.date('Y-m-d H:i:s', $result['alive_nexttime'] ).' URL='.$result['url'].')';
			$log		.=	$message.PHP_EOL;
			if	($this->options['survey-mode'] ) {
				$this->pz_OutputLOG(__FUNCTION__, $message );
			}
		}
	}
