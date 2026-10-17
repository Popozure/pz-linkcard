<?php defined('ABSPATH' ) || wp_die; ?>
<?php
	// WP-CRONスケジュール（SNSカウント取得）
	if (!$this->options['sns-position'] || !$this->options['sns-period'] ) {
		$log	.=	'Clear schedule "SNS Count Check".'.PHP_EOL;
		wp_clear_scheduled_hook(self::CRON_CHECK );
		return	null;
	}

	// DBの宣言
	global	$wpdb;

	// SNS次回取得日時を越えているものを古い順に必要件数だけ抽出
	$max_count	=	max(1, intval($this->options['sns-period-num'] ) );
	$proc_datas	=	$wpdb->get_results($wpdb->prepare(
		"SELECT id FROM $this->db_card WHERE sns_nexttime < %d ORDER BY sns_nexttime ASC, id ASC LIMIT %d",
		$this->now,
		$max_count
	) );

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

			// SNSカウントを取得
			$result		=	$this->pz_RenewSNSCount(array('id' => intval($data->id ) ) );	// SNS取得＆キャッシュ更新

			// 実行ログ
			$message	=	'['.$proc_count.'] '.'Confirmed the "Check SNS Count". (NextTime='.date('Y-m-d H:i:s', $result['sns_nexttime'] ).' URL='.$result['url'].')';
			$log		.=	$message.PHP_EOL;
			if	($this->options['survey-mode'] ) {
				$this->pz_OutputLOG(__FUNCTION__, $message );
			}
		}
	}
