<?php defined('ABSPATH' ) || wp_die; ?>
<?php
	check_admin_referer('pz_export_file_action' );

	// 出力除外する項目（カラム名）
	$column_omit	=	array();
	
	// テーブルの項目を確認
	global				$wpdb;
	$result			=	$wpdb->get_results("SELECT * FROM $this->db_name LIMIT 1", ARRAY_A );
	if	(!$result ) {
		return;
	}
	$column_all		=	array_keys($result[0] );
	$column_diff	=	array_diff($column_all, $column_omit );
	$column_output	=	implode(',', $column_diff );

	// 出力する項目をDBから取得
	$data_all		=	$wpdb->get_results("SELECT $column_output FROM $this->db_name ORDER BY domain , url", ARRAY_A );

	// ディレクトリ名とファイル名に付ける日時の文字列
	$datetime		=	date('Ymd_His');

	// エクスポートするファイル名
	$filename	=	'pz_linkcard_export_utf8_'.$datetime.'.csv';
	
	// エクスポートファイルを開く（書き込み）
	$handle			=	fopen('php://output', 'w');

	if	(!$handle ) {
		wp_die(__('Failed to open the export file.', 'pz-linkcard' ) );
	}

	header('Content-Type: text/csv; charset=UTF-8' );
	header('Content-Disposition: attachment; filename="'.$filename.'"' );
	header('Cache-Control: no-cache, no-store, must-revalidate' );
	header('Pragma: no-cache' );

	// CSVファイル出力
	$record_count	=	0;

	if	($handle ) {

		// ヘッダー行出力
		fputs($handle, $column_output."\n");

		// データ行出力
		foreach($data_all as &$data ) {
			foreach($data as &$item ) {
				$item	=	str_replace(array("\r", "\n", "\t" ), ' ', ($item ?? '' ) );
			}
			// fputcsv($handle, ($data ?? '' ), ',', '"' );
			fputcsv($handle, $data, ',', '"', '\\' );
			$record_count++;
		}

		// ファイルを閉じる
		fclose($handle );

	}
