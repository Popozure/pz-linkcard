<?php
defined('ABSPATH' ) || wp_die;

$error_post_id    = intval($prop['error-postid'] ?? 0);
$error_post_title = $error_post_id ? get_the_title($error_post_id ) : '';
$error_post_edit_url = $error_post_id ? get_edit_post_link($error_post_id, '' ) : '';
$error_post_url = isset($prop['error-url'] ) ? (string) $prop['error-url'] : '';
?>
<div class="pz-page<?php echo $pz_page_active('pz-error' ); ?>" id="pz-error">
	<div class="pz-submit-float"><?php submit_button(); ?></div>
	<h2><?php echo	__('Error Settings', 'pz-linkcard' ).$help_open.'error'.$help_close; ?></h2>
	<div class="pz-error-text">
		<?php esc_html_e('The shortcode description is incorrect. Please open the "Linked Articles" section and correct it.', 'pz-linkcard' ); ?>
	</div>
	<table class="pz-set-table form-table">
		<tr>
			<th scope="row"><?php esc_html_e('Post ID', 'pz-linkcard' ); ?></th>
			<td>
				<span class="pz-admin-only">
					<input name="properties[error-postid]" type="text" size="5" value="<?php echo esc_attr($prop['error-postid'] ); ?>" /><br/>
				</span>
				<?php if ($error_post_edit_url ) : ?>
					<a href="<?php echo esc_url($error_post_edit_url ); ?>" class="pz-error-url pz-error-post-link" title="<?php esc_attr_e('Edit post', 'pz-linkcard' ); ?>"><span><?php echo esc_html($error_post_id ); ?></span><span class="dashicons dashicons-welcome-write-blog" aria-hidden="true"></span></a>
				<?php else : ?>
					<span>-</span>
				<?php endif; ?>
			</td>
		</tr>
		<tr>
			<th scope="row"><?php esc_html_e('Post Title', 'pz-linkcard' ); ?></th>
			<td>
				<span><?php echo $error_post_title ? esc_html($error_post_title ) : '-'; ?></span>
			</td>
		</tr>
		<tr>
			<th scope="row"><?php esc_html_e('Post URL', 'pz-linkcard' ); ?></th>
			<td>
				<span class="pz-admin-only">
					<input name="properties[error-url]" type="url" size="80" value="<?php echo esc_attr($prop['error-url'] ); ?>" /><br/>
				</span>
				<?php if ($error_post_url ) : ?>
					<a href="<?php echo esc_url($error_post_url ); ?>#lkc-error" class="pz-error-url pz-error-post-link" title="<?php esc_attr_e('View post', 'pz-linkcard' ); ?>"><span><?php echo esc_html($this->pz_DecodeURL($error_post_url ) ); ?></span><span class="dashicons dashicons-format-aside" aria-hidden="true"></span></a>
				<?php else : ?>
					<span>-</span>
				<?php endif; ?>
			</td>
		</tr>
		<tr>
			<th scope="row"><?php esc_html_e('Occurrence Time', 'pz-linkcard' ); ?></th>
			<td>
				<span class="pz-admin-only">
					<input name="properties[error-time]" type="text" value="<?php echo esc_attr($prop['error-time'] ); ?>" /><br/>
				</span>
				<span><?php echo is_numeric($prop['error-time'] ) ? esc_html($this->pz_Date(PZLKC_DATETIME_FORMAT, esc_attr($prop['error-time'] ) ) ) : esc_attr($prop['error-time'] ); ?></span>
			</td>
		</tr>
		<tr id="pz-error-reset-settings">
			<th scope="row"><?php esc_html_e('Error Reset', 'pz-linkcard' ); ?></th>
			<td>
				<button type="submit" name="action" value="clear-error" class="pz-button"><?php esc_html_e('Reset', 'pz-linkcard' ); ?></button>
				&ensp;<span><?php esc_html_e('Cancel the error condition.', 'pz-linkcard' ); ?></span>
				<br><span class="pz-warning"><?php esc_html_e('* If you have not corrected the error, you may still get an error even if you cancel the error.', 'pz-linkcard' ); ?></span>
			</td>
		</tr>
	</table>
	<?php submit_button(); ?>
</div>
