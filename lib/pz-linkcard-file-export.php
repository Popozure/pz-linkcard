<?php defined('ABSPATH' ) || wp_die; ?>
<?php
	check_admin_referer('pz_export_file_action' );

	global	$wpdb;

	$column_omit	=	array('id', 'scheme', 'domain' );
	$result	=	$wpdb->get_results($wpdb->prepare('SELECT * FROM %i LIMIT 1', $this->db_name ), ARRAY_A );
	if	(!$result ) {
		wp_die(esc_html__('No export data was found.', 'pz-linkcard' ) );
	}

	$column_all	=	array_keys($result[0] );
	$column_output	=	array_values(array_diff($column_all, $column_omit ) );
	$data_all	=	$wpdb->get_results($wpdb->prepare('SELECT * FROM %i ORDER BY domain, url', $this->db_name ), ARRAY_A );

	$filename	=	'pz_linkcard_export_utf8_'.gmdate('Ymd_His').'.csv';
	$wp_filesystem	=	$this->pz_GetFilesystem();
	if	(!$wp_filesystem ) {
		wp_die(esc_html__('Failed to open the export file.', 'pz-linkcard' ) );
	}

	header('Content-Type: text/csv; charset=UTF-8' );
	header('Content-Disposition: attachment; filename="'.sanitize_file_name($filename ).'"' );
	header('Cache-Control: no-cache, no-store, must-revalidate' );
	header('Pragma: no-cache' );

	$build_csv_line	=	function($row ) {
		$values	=	array();
		foreach	($row as $value ) {
			$values[]	=	'"'.str_replace('"', '""', (string) $value ).'"';
		}
		return	implode(',', $values )."\r\n";
	};

	$csv_output	=	$build_csv_line($column_output );
	foreach	($data_all as $data ) {
		foreach	($column_omit as $column_name ) {
			unset($data[$column_name] );
		}
		foreach	($data as &$item ) {
			$item	=	str_replace(array("\r", "\n", "\t" ), ' ', ($item ?? '' ) );
		}
		unset($item );
		$csv_output	.=	$build_csv_line($data );
	}

	if	(!$wp_filesystem->put_contents('php://output', $csv_output ) ) {
		wp_die(esc_html__('Failed to write the export file.', 'pz-linkcard' ) );
	}
