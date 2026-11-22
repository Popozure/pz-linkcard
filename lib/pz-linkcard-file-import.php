<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>
<?php
	// This importer is included by pz-linkcard-cacheman-detail.php after the pz-cacheman POST nonce is verified.
	// phpcs:disable WordPress.Security.NonceVerification.Missing, WordPress.Security.NonceVerification.Recommended
	// DBの宣言
	global	$wpdb;

	// DBの列名取得
	$col_name	=	$wpdb->get_col($wpdb->prepare("DESC %i", $this->db_name));
	if	(!$col_name || $wpdb->last_error ) {
		echo	'<div class="notice notice-error is-dismissible"><p><strong>'.esc_html__('DB Access Error.', 'pz-linkcard' ).esc_html__('(', 'pz-linkcard' ).esc_html($wpdb->last_error).esc_html__(')', 'pz-linkcard' ).'</strong></p></div>';
		return	null;
	}

	// アップロードされたファイルの一時保存先を取得
	$temp_path		=	isset($_FILES['import_file']['tmp_name'] ) && is_string($_FILES['import_file']['tmp_name'] ) ? sanitize_text_field($_FILES['import_file']['tmp_name'] ) : '';

	// キャッシュDBクリア
	$clear			=	isset($_POST['import_clear'] ) && is_scalar($_POST['import_clear'] ) ? (bool) sanitize_text_field(wp_unslash($_POST['import_clear'] ) ) : false;

	// カウンター
	$read_count		=	0;
	$skip_count		=	0;
	$success_count	=	0;
	$skipped_records	=	array();

	// アップロードされたファイルの存在チェック
	if	(!is_uploaded_file($temp_path ) ) {
		echo	'<div class="notice notice-error is-dismissible"><p><strong>'.esc_html__('Import File Not Found.', 'pz-linkcard' ).'</strong></p></div>';
		return	null;
	}

	// ファイルを開く（読み込み）
	try {
		$handle = new SplFileObject($temp_path, 'r');
		$csv_size = $handle->getSize();
	} catch (RuntimeException $exception) {
		echo	'<div class="notice notice-error is-dismissible"><p><strong>'.esc_html__('Import File Open Error.', 'pz-linkcard' ).'</strong></p></div>';
		return	null;
	}

	// ヘッダー行入力
	if	(($csv_header = ($handle->ftell() < $csv_size ? $handle->fgetcsv() : false) ) == false ) {
		echo	'<div class="notice notice-error is-dismissible"><p><strong>'.esc_html__('Import File Read Error.', 'pz-linkcard' ).'</strong></p></div>';
		return	null;
	}

	// 項目数
	$item_count	=	count($csv_header );

	// DBの削除
	if	($clear ) {
		// DBクリア
		$result	=	$wpdb->query($wpdb->prepare("DELETE FROM %i", $this->db_name));
		if	($wpdb->last_error ) {
			echo	'<div class="notice notice-error is-dismissible"><p><strong>'.esc_html__('DB Access Error.', 'pz-linkcard' ).esc_html__('(', 'pz-linkcard' ).esc_html($wpdb->last_error).esc_html__(')', 'pz-linkcard' ).'</strong></p></div>';
			return	null;
		}

		// AUTO INCLIMENTのリセット
		$result	=	$wpdb->query($wpdb->prepare("ALTER TABLE %i AUTO_INCREMENT=1;", $this->db_name));
		if	($wpdb->last_error ) {
			echo	'<div class="notice notice-error is-dismissible"><p><strong>'.esc_html__('DB Access Error.', 'pz-linkcard' ).esc_html__('(', 'pz-linkcard' ).esc_html($wpdb->last_error).esc_html__(')', 'pz-linkcard' ).'</strong></p></div>';
			return	null;
		}
	}

	foreach ($csv_header as $key => $value ) {
		if (!in_array($value, $col_name ) ) {
			unset($csv_header[$key] );
		}
	}

	$skip_key	= 	array_flip(array('id' ) );

	// データ行入力
	while	(($record = ($handle->ftell() < $csv_size ? $handle->fgetcsv() : false) ) !== false ) {
		$read_count++;
		if (count($record ) == $item_count) {
			$import	= 	array();
			foreach ($csv_header as $key => $value ) {
				if (isset($skip_key[$value] ) ) {
					continue;
				}
				$import[$value]	= 	$record[$key];
			}

			// DB更新
			$result			=	$this->pz_SetCache($import, $save_error );
			if	(!isset($result['url'] ) || $result['url'] <> $import['url'] ) {
				$skip_count++;
				$reason	=	$save_error ?: (isset($result['url'] )
					? sprintf(esc_html__('The saved URL differs from the imported URL: %s', 'pz-linkcard' ), $result['url'] )
					: esc_html__('The cache record could not be saved.', 'pz-linkcard' ) );
				if	(!array_key_exists('url', $import ) ) {
					$reason	=	esc_html__('No URL column was found.', 'pz-linkcard' );
				}
				$skipped_records[]	=	array('row' => $read_count, 'url' => $import['url'] ?? '', 'reason' => $reason );
			} else {
				$success_count++;
			}
		} else {
			$skip_count++;
			$skipped_records[]	=	array(
				'row' => $read_count,
				'url' => '',
				'reason' => sprintf(esc_html__('CSV column count differs from the header (expected %1$d, found %2$d).', 'pz-linkcard' ), $item_count, count($record ) ),
			);
		}
	}
	// ファイルを閉じる
	unset($handle );

	if	($success_count ) {
		echo	'<div class="notice notice-success is-dismissible"><p><strong>'.esc_html__('Import Successful.', 'pz-linkcard' ).esc_html__('(', 'pz-linkcard' ).esc_html__('Read:', 'pz-linkcard' ).intval($read_count).' '.esc_html__('Success:', 'pz-linkcard' ).intval($success_count).' '.esc_html__('Skip:', 'pz-linkcard' ).intval($skip_count).esc_html__(')', 'pz-linkcard' ).'</strong></p></div>';
	} else {
		echo	'<div class="notice notice-error is-dismissible"><p><strong>'.esc_html__('Import Failure.', 'pz-linkcard' ).esc_html__('(', 'pz-linkcard' ).esc_html__('Read:', 'pz-linkcard' ).intval($read_count).' '.esc_html__('Skip:', 'pz-linkcard' ).intval($skip_count).esc_html__(')', 'pz-linkcard' ).'</strong></p></div>';
	}
	require __DIR__.'/pz-linkcard-file-import-result.php';
