<?php defined('ABSPATH' ) || wp_die; ?>
<?php
	// DBの宣言
	global	$wpdb;

	// DBの列名取得
	$col_name	=	$wpdb->get_col("DESC $this->db_name" );
	if	(!$col_name || $wpdb->last_error ) {
		echo	'<div class="notice notice-error is-dismissible"><p><strong>'.__('DB Access Error.', 'pz-linkcard' ).__('(', 'pz-linkcard' ).$wpdb->last_error.__(')', 'pz-linkcard' ).'</strong></p></div>';
		return	null;
	}

	// アップロードされたファイルの一時保存先を取得
	$temp_path		=	isset($_FILES['import_file']['tmp_name'] )	? $_FILES['import_file']['tmp_name'] : null;

	// キャッシュDBクリア
	$clear			=	isset($_POST['import_clear'] ) ? $_POST['import_clear'] : false;

	// カウンター
	$read_count		=	0;
	$skip_count		=	0;
	$success_count	=	0;

	// アップロードされたファイルの存在チェック
	if	(!is_uploaded_file($temp_path ) ) {
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

	// 項目数
	$item_count	=	count($csv_header );

	// DBの削除
	if	($clear ) {
		// DBクリア
		$result	=	$wpdb->query("DELETE FROM $this->db_name" );
		if	($wpdb->last_error ) {
			echo	'<div class="notice notice-error is-dismissible"><p><strong>'.__('DB Access Error.', 'pz-linkcard' ).__('(', 'pz-linkcard' ).$wpdb->last_error.__(')', 'pz-linkcard' ).'</strong></p></div>';
			return	null;
		}

		// AUTO INCLIMENTのリセット
		$result	=	$wpdb->query("ALTER TABLE $this->db_name AUTO_INCREMENT=1;" );
		if	($wpdb->last_error ) {
			echo	'<div class="notice notice-error is-dismissible"><p><strong>'.__('DB Access Error.', 'pz-linkcard' ).__('(', 'pz-linkcard' ).$wpdb->last_error.__(')', 'pz-linkcard' ).'</strong></p></div>';
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
	while	(($record = fgetcsv($handle ) ) !== false ) {
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
			$result			=	$this->pz_SetCache($import );
			if	(!isset($result['url'] ) || $result['url'] <> $import['url'] ) {
				$skip_count++;
			} else {
				$success_count++;
			}
		} else {
			$skip_count++;
		}
	}
	// ファイルを閉じる
	fclose($handle );

	if	($success_count ) {
		echo	'<div class="notice notice-success is-dismissible"><p><strong>'.__('Import Successful.', 'pz-linkcard' ).__('(', 'pz-linkcard' ).__('Read:', 'pz-linkcard' ).$read_count.' '.__('Success:', 'pz-linkcard' ).$success_count.' '.__('Skip:', 'pz-linkcard' ).$skip_count.__(')', 'pz-linkcard' ).'</strong></p></div>';
	} else {
		echo	'<div class="notice notice-error is-dismissible"><p><strong>'.__('Import Failure.', 'pz-linkcard' ).__('(', 'pz-linkcard' ).__('Read:', 'pz-linkcard' ).$read_count.' '.__('Skip:', 'pz-linkcard' ).$skip_count.__(')', 'pz-linkcard' ).'</strong></p></div>';
	}
