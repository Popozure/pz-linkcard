<?php defined('ABSPATH' ) || wp_die; ?>
<?php
	// WP-CRONスケジュール（SNSカウント取得）
	// DBの宣言
	global	$wpdb;

	// SNS次回取得日時を越えているものを古い順に必要件数だけ抽出
	$max_count	=	max(1, intval($this->options['sns-period-num'] ) );
	$proc_datas	=	$wpdb->get_results($wpdb->prepare(
		"SELECT id,sns_nexttime FROM $this->db_card WHERE sns_nexttime < %d ORDER BY sns_nexttime ASC, id ASC LIMIT %d",
		$this->now,
		$max_count
	) );

	// SNSカウント取得
	$proc_count	=	0;
	if (isset($proc_datas ) && is_array($proc_datas ) && count($proc_datas ) > 0) {
		foreach($proc_datas as $data ) {
			$proc_count++;

			// SNSカウントを取得
			$result		=	$this->pz_RenewSNSCount(array('id' => intval($data->id ) ) );	// SNS取得＆キャッシュ更新

			// 実行ログ
			$hatena_count	=	isset($result['sns_hatena_response'] ) && $result['sns_hatena_response'] !== null ? number_format_i18n(intval($result['sns_hatena_response'] ) ) : '-';
			$message	=	sprintf(__('[%1$d] URL=%2$s', 'pz-linkcard' ), $proc_count, $result['url'] ).PHP_EOL;
			$message	.=	sprintf(__('&ensp;&ensp;&ensp;&ensp;The next check date and time=%s has passed.', 'pz-linkcard' ), date('Y-m-d H:i', intval($data->sns_nexttime ) ) ).PHP_EOL;
			$message	.=	sprintf(__('&ensp;&ensp;&ensp;&ensp;Hatena Bookmark=%1$s was returned. The next check date and time=%2$s was set.', 'pz-linkcard' ), $hatena_count, date('Y-m-d H:i', intval($result['sns_nexttime'] ) ) );
			$log		.=	$message.PHP_EOL;
			if	($this->options['survey-mode'] ) {
				$this->pz_OutputLOG(__FUNCTION__, $message );
			}
		}
	}
