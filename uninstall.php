<?php defined('WP_UNINSTALL_PLUGIN' ) || exit; ?>
<?php
	// アンインストーラー

	////////////////////////////////////////////////////////////////////////////////
	$settings = get_option('pz_linkcard_options', array() );

	// ディレクトリの削除（画像キャッシュ、スタイルシート）
	if (!empty($settings['flg-delete-image'] ) ) {
		$result		=	remove_directory_pre('pz-linkcard' );
	}

	// DBテーブルの削除 
	if (!empty($settings['flg-delete-db'] ) ) {
		global	$wpdb;
		$result		=	drop_table($wpdb->prefix.'pz_linkcard' );
	}

	// 設定の削除
	if (!empty($settings['flg-delete-settings'] ) ) {
		$result		=	delete_option('pz_linkcard_options' );
	}

	////////////////////////////////////////////////////////////////////////////////

	// ディレクトリの削除（準備）
	function remove_directory_pre($dir_name ) {
		$wp_upload_dir		=	wp_upload_dir();
		$upload_dir_path	=	$wp_upload_dir['basedir'].'/'.$dir_name;
		if (file_exists($upload_dir_path ) ) {
			remove_directory($upload_dir_path );
		}
	}

	// ディレクトリの削除（処理）
	function remove_directory($dir ) {
		if	(mb_substr($dir, -1, 1) <> '/' ) {
			$dir	=	$dir.'/';
		}
		$files = array_diff(scandir($dir), array('.','..'));
		foreach ($files as $file ) {
			if (is_dir($dir.$file ) ) {
				remove_directory($dir.$file);
			} else {
				unlink($dir.$file );
			}
		}
		return rmdir($dir );
	}

	// DBテーブルの削除
	function drop_table($table_name ) {
		global	$wpdb;
		$sql	=	"DROP TABLE IF EXISTS ".$table_name;
		$wpdb->query($sql );
	}
