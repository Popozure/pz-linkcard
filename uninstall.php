<?php
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

$settings = get_option( 'pz_linkcard_options', array() );

if ( ! empty( $settings['flg-delete-image'] ) ) {
	// Work on the already verified local upload paths without requesting FTP credentials.
	if ( ! class_exists( 'WP_Filesystem_Direct', false ) ) {
		require_once ABSPATH . 'wp-admin/includes/class-wp-filesystem-base.php';
		require_once ABSPATH . 'wp-admin/includes/class-wp-filesystem-direct.php';
	}
	$delete_filesystem = new WP_Filesystem_Direct( null );
	$upload_dir = wp_upload_dir();
	$upload_root = isset( $upload_dir['basedir'] ) ? realpath( $upload_dir['basedir'] ) : false;
	$target_dir = $upload_root ? realpath( trailingslashit( $upload_root ) . 'pz-linkcard' ) : false;

	if ( $upload_root && $target_dir && is_dir( $target_dir ) && ! is_link( trailingslashit( $upload_root ) . 'pz-linkcard' ) ) {
		$normalized_root = strtolower( wp_normalize_path( $upload_root ) );
		$normalized_target = strtolower( wp_normalize_path( $target_dir ) );
		$root_prefix = trailingslashit( $normalized_root );

		// Only remove the plugin directory when it is a strict child of uploads.
		if ( $normalized_target !== $normalized_root && strpos( trailingslashit( $normalized_target ), $root_prefix ) === 0 ) {
			$iterator = new RecursiveIteratorIterator(
				new RecursiveDirectoryIterator( $target_dir, FilesystemIterator::SKIP_DOTS ),
				RecursiveIteratorIterator::CHILD_FIRST
			);

			foreach ( $iterator as $entry ) {
				$path = $entry->getPathname();
				if ( $entry->isLink() ) {
					wp_delete_file( $path );
					continue;
				}

				$real_path = realpath( $path );
				if ( false === $real_path || strpos( strtolower( wp_normalize_path( $real_path ) ), trailingslashit( $normalized_target ) ) !== 0 ) {
					continue;
				}

				if ( $entry->isDir() ) {
					$delete_filesystem->rmdir( $real_path, false );
				} elseif ( $entry->isFile() ) {
					wp_delete_file( $real_path );
				}
			}

			$delete_filesystem->rmdir( $target_dir, false );
		}
	}
}

if ( ! empty( $settings['flg-delete-db'] ) ) {
	global $wpdb;
	$tables = array(
		$wpdb->prefix . 'pz_linkcard',
		$wpdb->prefix . 'pz_linkcard_click',
	);

	foreach ( $tables as $table ) {
		$wpdb->query( $wpdb->prepare( 'DROP TABLE IF EXISTS %i', $table ) );
	}
}

if ( ! empty( $settings['flg-delete-settings'] ) ) {
	delete_option( 'pz_linkcard_options' );
}
