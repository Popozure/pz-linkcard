<?php defined('ABSPATH' ) || wp_die; ?>
<?php
	$import_start_time = microtime(true );
	check_admin_referer('pz-cacheman' );
	require_once(__DIR__.'/pz-linkcard-file-datetime.php' );

	// DBの宣言
	global	$wpdb;

	// DBの列名取得
	$col_name	=	$wpdb->get_col($wpdb->prepare('DESC %i', $this->db_card ), 0 );
	if	(!$col_name || $wpdb->last_error ) {
		echo	'<div class="notice notice-error is-dismissible"><p><strong>'.__('DB Access Error.', 'pz-linkcard' ).__('(', 'pz-linkcard' ).$wpdb->last_error.__(')', 'pz-linkcard' ).'</strong></p></div>';
		return	null;
	}
	$skip_key			= 	array_flip(array('id', 'scheme', 'domain', 'use_post_id1', 'use_post_id2', 'use_post_id3', 'use_post_id4', 'use_post_id5', 'use_post_id6' ) );
	$import_columns		=	array_values(array_diff($col_name, array_keys($skip_key ) ) );
	$col_key			=	array_flip($col_name );
	$import_key			=	array_flip($import_columns );
	$column_aliases		=	array(
		'url'				=>	array('url', 'link_url', 'link', 'href' ),
		'url_redir'			=>	array('url_redir', 'redirect_url', 'redir_url', 'redirect', 'final_url' ),
		'site_name'			=>	array('site_name', 'sitename', 'site', 'site_title', 'blogname' ),
		'title'				=>	array('title', 'post_title', 'page_title' ),
		'excerpt'			=>	array('excerpt', 'description', 'description_text', 'summary' ),
		'charset'			=>	array('charset', 'encoding' ),
		'thumbnail'			=>	array('thumbnail', 'thumbnail_url', 'image', 'image_url', 'og_image' ),
		'favicon'			=>	array('favicon', 'favicon_url', 'site_icon', 'siteicon', 'siteicon_url' ),
		'post_date'			=>	array('post_date', 'published_at', 'published_time' ),
		'post_modified'		=>	array('post_modified', 'modified_at', 'modified_time' ),
		'no_failure'		=>	array('no_failure' ),
		'click_count'		=>	array('click_count', 'click', 'clicks', 'count_click' ),
		'alive_result'		=>	array('alive_result', 'alive_code', 'http_code', 'status_code' ),
		'alive_time'		=>	array('alive_time', 'alive_date', 'checked_at' ),
		'alive_nexttime'	=>	array('alive_nexttime', 'alive_next_time', 'alive_next_at' ),
		'sns_twitter'		=>	array('sns_twitter', 'twitter_count', 'tweet_count' ),
		'sns_facebook'		=>	array('sns_facebook', 'facebook_count', 'fb_count' ),
		'sns_hatena'		=>	array('sns_hatena', 'hatena_count', 'hatebu_count' ),
		'sns_time'			=>	array('sns_time', 'sns_date' ),
		'sns_nexttime'		=>	array('sns_nexttime', 'sns_next_time', 'sns_next_at', 'sns_next_date' ),
		'regist_title'		=>	array('regist_title' ),
		'regist_excerpt'	=>	array('regist_excerpt' ),
		'regist_charset'	=>	array('regist_charset' ),
		'regist_result'		=>	array('regist_result' ),
		'regist_time'		=>	array('regist_time' ),
		'update_result'		=>	array('update_result', 'result', 'http_code', 'status_code' ),
		'update_time'		=>	array('update_time', 'updated_time', 'update_date', 'update_datetime', 'updated_at' ),
	);
	$normalize_column	=	function ($name ) {
		$name	=	preg_replace('/^\xEF\xBB\xBF/', '', (string) $name );
		$name	=	trim($name );
		$name	=	strtolower($name );
		$name	=	str_replace(array('-', ' ', '.', ':' ), '_', $name );
		$name	=	preg_replace('/_+/', '_', $name );
		return	trim($name, '_' );
	};
	$get_value			=	function ($record, $names ) use ($normalize_column ) {
		foreach	($names as $name ) {
			$key	=	$normalize_column($name );
			if	(array_key_exists($key, $record ) ) {
				return	$record[$key];
			}
		}
		return	null;
	};
	$normalize_timestamp	=	function ($value ) {
		if	($value === null || $value === '' ) {
			return	0;
		}
		if	(is_numeric($value ) ) {
			return	intval($value );
		}
		$timestamp	=	strtotime($value );
		return	$timestamp ? $timestamp : 0;
	};

	// アップロードされたファイルの一時保存先を取得
	$upload_error	=	isset($_FILES['import_file']['error'] ) ? absint($_FILES['import_file']['error'] ) : UPLOAD_ERR_NO_FILE;
	$temp_path		=	(isset($_FILES['import_file']['tmp_name'] ) && is_string($_FILES['import_file']['tmp_name'] ) )
		?	$_FILES['import_file']['tmp_name']
		:	'';

	// キャッシュDBクリア
	$clear			=	isset($_POST['import_clear'] ) ? (bool) sanitize_text_field(wp_unslash($_POST['import_clear'] ) ) : false;

	// カウンター
	$read_count		=	0;
	$skip_count		=	0;
	$success_count	=	0;
	$skip_details	=	array();

	// アップロードされたファイルの存在チェック
	if	($upload_error !== UPLOAD_ERR_OK || !$temp_path || !is_uploaded_file($temp_path ) ) {
		echo	'<div class="notice notice-error is-dismissible"><p><strong>'.__('Import File Not Found.', 'pz-linkcard' ).'</strong></p></div>';
		return	null;
	}

	// ファイルを開く（読み込み）
	$handle			=	fopen($temp_path, 'r');
	if	(!$handle ) {
		echo	'<div class="notice notice-error is-dismissible"><p><strong>'.__('Import File Open Error.', 'pz-linkcard' ).'</strong></p></div>';
		return	null;
	}

	// ヘッダー行入力
	if	(($csv_header = fgetcsv($handle ) ) == false ) {
		echo	'<div class="notice notice-error is-dismissible"><p><strong>'.__('Import File Read Error.', 'pz-linkcard' ).'</strong></p></div>';
		return	null;
	}

	$csv_columns	=	array();
	foreach	($csv_header as $key => $value ) {
		$csv_columns[$key]	=	$normalize_column($value );
	}

	// DBの削除
	if	($clear ) {
		// DBクリア
		$result	=	$wpdb->query($wpdb->prepare('DELETE FROM %i', $this->db_card ) );
		if	($wpdb->last_error ) {
			echo	'<div class="notice notice-error is-dismissible"><p><strong>'.__('DB Access Error.', 'pz-linkcard' ).__('(', 'pz-linkcard' ).$wpdb->last_error.__(')', 'pz-linkcard' ).'</strong></p></div>';
			return	null;
		}

		// AUTO INCRIMENTのリセット
		$result	=	$wpdb->query($wpdb->prepare('ALTER TABLE %i AUTO_INCREMENT = 1', $this->db_card ) );
		if	($wpdb->last_error ) {
			echo	'<div class="notice notice-error is-dismissible"><p><strong>'.__('DB Access Error.', 'pz-linkcard' ).__('(', 'pz-linkcard' ).$wpdb->last_error.__(')', 'pz-linkcard' ).'</strong></p></div>';
			return	null;
		}
	}

	$date_columns	=	array('post_date', 'post_modified', 'alive_time', 'alive_nexttime', 'sns_time', 'sns_nexttime', 'regist_time', 'update_time' );

	// データ行入力
	while	(($record = fgetcsv($handle ) ) !== false ) {
		$read_count++;
		$csv_record	=	array();
		foreach	($csv_columns as $key => $value ) {
			if	($value === '' ) {
				continue;
			}
			$csv_record[$value]	=	isset($record[$key] ) ? $record[$key] : '';
		}

		$import	= 	array();
		foreach	($import_columns as $target_column ) {
			if	(array_key_exists($target_column, $csv_record ) ) {
				$import[$target_column]	=	$csv_record[$target_column];
				continue;
			}
			if	(isset($column_aliases[$target_column] ) ) {
				$value	=	$get_value($csv_record, $column_aliases[$target_column] );
				if	($value !== null ) {
					$import[$target_column]	=	$value;
				}
			}
		}
		foreach	($date_columns as $date_column ) {
			if	(array_key_exists($date_column, $import ) ) {
				$value = trim((string) $import[$date_column] );
				if ($value === '' && in_array($date_column, array('post_date', 'post_modified' ), true ) ) {
					$import[$date_column] = null;
				} elseif ($value === '' || ctype_digit($value ) || preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/', $value ) ) {
					$converted = pz_lkc_csv_datetime_import($date_column, $value );
					if ($converted === false ) {
						$skip_count++;
						$skip_details[] = array('record' => $read_count, 'field' => $date_column, 'reason' => __('Invalid date and time.', 'pz-linkcard' ) );
						continue 2;
					}
					$import[$date_column] = $converted;
				} else {
					$import[$date_column] = $normalize_timestamp($value );
				}
			}
		}
		foreach (array('post_id', 'no_failure', 'click_count' ) as $nullable_column ) {
			if (array_key_exists($nullable_column, $import ) && $import[$nullable_column] === '' ) {
				$import[$nullable_column] = null;
			}
		}
		$import	=	array_intersect_key($import, $col_key );
		$import	=	array_intersect_key($import, $import_key );

		if	(empty($import['url'] ) ) {
			$skip_count++;
			$skip_details[] = array('record' => $read_count, 'field' => 'url', 'reason' => __('URL is missing.', 'pz-linkcard' ) );
			continue;
		}

		// DB更新
		$result			=	$this->pz_SetCache($import, true );
		if	(!isset($result['url'] ) || $result['url'] <> $import['url'] ) {
			$skip_count++;
			if (isset($result['url'] ) ) {
				$skip_details[] = array('record' => $read_count, 'field' => 'url', 'reason' => __('The URL changed during normalization; the record may have been saved.', 'pz-linkcard' ) );
			} else {
				$reason = __('Could not save the record; the responsible field could not be identified.', 'pz-linkcard' );
				if (!empty($wpdb->last_error ) ) $reason .= ' '.$wpdb->last_error;
				$skip_details[] = array('record' => $read_count, 'field' => '', 'reason' => $reason );
			}
		} else {
			$success_count++;
		}
	}
	// ファイルを閉じる
	fclose($handle );
	$import_duration = sprintf(__('Import took %s seconds.', 'pz-linkcard' ), number_format_i18n(microtime(true ) - $import_start_time, 2 ) );

	if	($success_count ) {
		echo	'<div class="notice notice-success is-dismissible"><p><strong>'.__('Import Successful.', 'pz-linkcard' ).__('(', 'pz-linkcard' ).__('Read:', 'pz-linkcard' ).$read_count.' '.__('Success:', 'pz-linkcard' ).$success_count.' '.__('Skip:', 'pz-linkcard' ).$skip_count.__(')', 'pz-linkcard' ).'</strong> &hellip;'.esc_html($import_duration ).'</p></div>';
	} else {
		echo	'<div class="notice notice-error is-dismissible"><p><strong>'.__('Import Failure.', 'pz-linkcard' ).__('(', 'pz-linkcard' ).__('Read:', 'pz-linkcard' ).$read_count.' '.__('Skip:', 'pz-linkcard' ).$skip_count.__(')', 'pz-linkcard' ).'</strong> &hellip;'.esc_html($import_duration ).'</p></div>';
	}
	if ($skip_details ) {
		echo '<div class="notice notice-warning"><p><strong>'.esc_html__('Skipped record details', 'pz-linkcard' ).'</strong></p><ul>';
		foreach ($skip_details as $detail ) {
			$record_label = sprintf(__('Record %s', 'pz-linkcard' ), number_format_i18n($detail['record'] ) );
			echo '<li>'.esc_html($record_label ).($detail['field'] !== '' ? ' &mdash; <code>'.esc_html($detail['field'] ).'</code>' : '').': '.esc_html($detail['reason'] ).'</li>';
		}
		echo '</ul></div>';
	}
	echo	'<p><a href="'.esc_url($this->cacheman_url ).'" class="pz-man-return-button button">'.esc_html__('Return to Cache Manager', 'pz-linkcard' ).'</a></p>';
