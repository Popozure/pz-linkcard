<?php defined('ABSPATH' ) || wp_die; ?>
<?php
	// WP-CRONスケジュール（存在チェック）
	// DBの宣言
	global	$wpdb;

	// 次回生存確認日時を越えているものを古い順に必要件数だけ抽出
	$max_count	=	max(1, intval($this->options['alive-period-num'] ) );
	$candidate_count	=	$max_count * 3;
	$proc_datas	=	$wpdb->get_results($wpdb->prepare(
		"SELECT id,url,domain,alive_nexttime FROM $this->db_card WHERE alive_nexttime < %d ORDER BY alive_nexttime ASC, id ASC LIMIT %d",
		$this->now,
		$candidate_count
	) );
	if	(is_array($proc_datas ) && count($proc_datas ) > 1 ) {
		usort($proc_datas, function($a, $b ) {
			$domain_order	=	strcmp((string) $a->domain, (string) $b->domain );
			if	($domain_order !== 0 ) {
				return	$domain_order;
			}
			$time_order	=	intval($a->alive_nexttime ) <=> intval($b->alive_nexttime );
			return	$time_order !== 0 ? $time_order : intval($a->id ) <=> intval($b->id );
		} );
	}

	// 生存確認
	$proc_count	=	0;
	$retry_at = null;
	if (isset($proc_datas ) && is_array($proc_datas ) && count($proc_datas) > 0) {
		foreach($proc_datas as $data ) {
			$next_access = $this->pz_ClaimCronSiteAccess($data->url, $data->domain ?? '');
			if ($next_access > 0) {
				$retry_at = $retry_at === null ? $next_access : min($retry_at, $next_access);
				$wpdb->update(
					$this->db_card,
					array('alive_nexttime' => $next_access ),
					array('id' => intval($data->id ) ),
					array('%d' ),
					array('%d' )
				);
				$message	=	sprintf(__('[SKIP] URL=%s', 'pz-linkcard' ), $data->url ).PHP_EOL;
				$message	.=	sprintf(__('&ensp;&ensp;&ensp;&ensp;The scheduled check time (%s) has passed.', 'pz-linkcard' ), date('Y-m-d H:i', intval($data->alive_nexttime ) ) ).PHP_EOL;
				$message	.=	sprintf(__('&ensp;&ensp;&ensp;&ensp;Skipping this URL because its domain is the same as the previous one. The next check is scheduled for %s.', 'pz-linkcard' ), date('Y-m-d H:i', $next_access ) );
				$log		.=	$message.PHP_EOL;
				continue;
			}
			$proc_count++;
			$message	=	sprintf(__('[%1$d] URL=%2$s', 'pz-linkcard' ), $proc_count, $data->url ).PHP_EOL;
			$message	.=	sprintf(__('&ensp;&ensp;&ensp;&ensp;The scheduled check time (%s) has passed.', 'pz-linkcard' ), date('Y-m-d H:i', intval($data->alive_nexttime ) ) );
			$log		.=	$message.PHP_EOL;

			// リンク先を取得
			if (isset($data ) && isset($data->id ) ) {
				$before	=	$this->pz_GetCache(array('id' => intval($data->id ) ) );
				$after	=	$this->pz_GetRemote($before, true );
				$after_result	=	isset($after['alive_result'] ) ? intval($after['alive_result'] ) : 0;
				switch	(true ) {
				case	($after_result >= 200 && $after_result < 300 ):
					$next_check_days	=	168;
					break;
				case	($after_result >= 300 && $after_result < 400 ):
					$next_check_days	=	56;
					break;
				case	($after_result >= 400 && $after_result < 500 ):
					$next_check_days	=	28;
					break;
				case	($after_result >= 500 && $after_result < 600 ):
					$next_check_days	=	3;
					break;
				default:
					$next_check_days	=	84;
					break;
				}
				$before['alive_nexttime']	=	$this->now + DAY_IN_SECONDS * $next_check_days;
				$before['alive_result']		=	$after['alive_result'];
				$before['alive_time']		=	$this->now;

				// if	(!$before['thumbnail'] ) {
				// 	$before['thumbnail']	=	$after['thumbnail'];
				// }
				// if	(!$before['favicon'] ) {
				// 	$before['favicon']		=	$after['favicon'];
				// }
				$result		=	$this->pz_SetCache($before );

				// 実行ログ
				$message	=	sprintf(__('&ensp;&ensp;&ensp;&ensp;HTTP status code %1$d was returned. The next check is scheduled for %2$s.', 'pz-linkcard' ), intval($result['alive_result'] ), date('Y-m-d H:i', intval($result['alive_nexttime'] ) ) );
				$log		.=	$message.PHP_EOL;
			}

			if	($proc_count >= $max_count ) {
				break;
			}
		}
	}
	if ($retry_at !== null && !empty($this->options['alive-period'])) {
		$this->pz_ScheduleCronRetry('pz_linkcard_alive_retry', $retry_at + 1);
	}
