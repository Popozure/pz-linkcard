<?php
defined('ABSPATH' ) || wp_die;

	if	(!current_user_can('edit_posts' ) ) {
		wp_send_json_error(__('You do not have permission to update LinkCard data.', 'pz-linkcard' ), 403 );
	}
	if	(!check_ajax_referer('pz_lkc_mce_post_search', 'nonce', false ) ) {
		wp_send_json_error(__('Invalid request.', 'pz-linkcard' ), 403 );
	}

	$keyword	=	isset($_POST['keyword'] ) ? sanitize_text_field(wp_unslash($_POST['keyword'] ) ) : '';
	if	($keyword === '' ) {
		wp_send_json_success(array() );
	}

	$post_types	=	array_values(get_post_types(array('public' => true ), 'names' ) );
	$post_types	=	array_values(array_diff($post_types, array('attachment' ) ) );
	if	(!$post_types ) {
		wp_send_json_success(array() );
	}

	global	$wpdb;
	$type_placeholders	=	implode(', ', array_fill(0, count($post_types ), '%s' ) );
	$sql	=	"SELECT ID FROM {$wpdb->posts} WHERE post_status = 'publish' AND post_type IN ({$type_placeholders}) AND post_title LIKE %s ORDER BY post_date DESC LIMIT 50";
	$query_args	=	array_merge($post_types, array('%'.$wpdb->esc_like($keyword ).'%' ) );
	$post_ids	=	$wpdb->get_col($wpdb->prepare($sql, $query_args ) );
	$results	=	array();
	foreach	($post_ids as $post_id ) {
		$results[]	=	array(
			'title'	=>	get_the_title($post_id ),
			'date'	=>	get_the_date(get_option('date_format' ), $post_id ),
			'published_date'	=>	get_the_date(get_option('date_format' ), $post_id ),
			'modified_date'	=>	get_the_modified_date(get_option('date_format' ), $post_id ),
			'excerpt'	=>	preg_replace('/\s+/u', ' ', wp_strip_all_tags(get_the_excerpt($post_id ) ) ),
			'thumbnail'	=>	get_the_post_thumbnail_url($post_id, 'thumbnail' ) ?: '',
			'url'	=>	get_permalink($post_id ),
		);
	}

	wp_send_json_success($results );
