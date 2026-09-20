<?php defined('ABSPATH' ) || wp_die; ?>
<?php
	global	$wpdb;
	$export_count	=	intval($wpdb->get_var("SELECT COUNT(*) FROM $this->db_name" ) );
	$export_count_label	=	sprintf(
		$export_count === 1 ? __('%s item', 'pz-linkcard' ) : __('%s items', 'pz-linkcard' ),
		number_format_i18n($export_count )
	);
	$export_url	=	wp_nonce_url(admin_url('admin-post.php?action=pz_export_file' ), 'pz_export_file_action' );
?>
<p><a href="<?php echo esc_url($this->cacheman_url ); ?>" class="pz-man-return-button button"><?php esc_html_e('Return to Cache Manager', 'pz-linkcard' ); ?></a></p>

<h2><?php esc_html_e('Export LinkCard Data', 'pz-linkcard' ); ?></h2>
<p><?php esc_html_e('Export the cache contents to a CSV file. All registered items will be exported.', 'pz-linkcard' ); ?></p>
<table class="pz-man-filemenu" style="width: 100%;">
	<tr style="vertical-align: middle; height: 40px;">
		<td><a href="<?php echo esc_url($export_url ); ?>" class="pz-man-file-button button button-primary" data-no-overlay="1"><?php echo esc_html__('Download the export file', 'pz-linkcard' ).' ('.esc_html($export_count_label ).')'; ?></a></td>
	</tr>
</table>
