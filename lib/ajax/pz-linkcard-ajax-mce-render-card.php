<?php
defined('ABSPATH' ) || wp_die;

	if	(!current_user_can('edit_posts' ) ) {
		wp_send_json_error(__('You do not have permission to update LinkCard data.', 'pz-linkcard' ), 403 );
	}
	if	(!check_ajax_referer('pz_lkc_mce_render_card', 'nonce', false ) ) {
		wp_send_json_error(__('Invalid request.', 'pz-linkcard' ), 403 );
	}
	$shortcode	= isset($_POST['shortcode'] ) ? trim(wp_unslash($_POST['shortcode'] ) ) : '';
	$codes		= array_values(array_filter(array_unique(array_map('strval', array(
		$this->options['code1'] ?? '', $this->options['code2'] ?? '', $this->options['code3'] ?? '',
	) ) ) ) );
	$quoted		= array_map(function($code ) { return preg_quote($code, '/' ); }, $codes );
	$pattern	= $quoted ? '/^\[('.implode('|', $quoted ).')\b([^\]]*)\]$/i' : '';
	if	(!$pattern || !preg_match($pattern, $shortcode, $matches ) ) {
		wp_send_json_error(__('Invalid request.', 'pz-linkcard' ), 400 );
	}
	$atts	= shortcode_parse_atts($matches[2] );
	$atts	= is_array($atts ) ? $atts : array();
	$url	= $atts['url'] ?? $atts['href'] ?? $atts['uri'] ?? $atts['ur1'] ?? '';
	if	(is_string($url ) && preg_match('/(https?:\/\/[^\s<>]+)/sui', $url, $url_match ) ) {
		$url	= $url_match[1];
	}
	if	(is_string($url ) && $url && $this->options['flg-relative-url'] && !mb_strpos($url, '://' ) ) {
		$url	= $this->pz_RelToURL(esc_url(home_url() ), $url );
	}
	if	(is_string($url ) && $url ) {
		$url	= $this->pz_EncodeURL($url, true );
	}
	$url_info	= $url ? $this->Pz_GetURLInfo($url ) : null;
	if	(!empty($url_info['is_internal'] ) ) {
		$post_id	= url_to_postid($url );
		$post		= $post_id ? get_post($post_id ) : null;
		if	($post_id && (!$post || !$this->pz_IsPostPubliclyViewable($post ) ) ) {
			if	(!empty($this->options['flg-public-only'] ) ) {
				wp_send_json_error(__('Only publicly viewable posts can be previewed with the current setting.', 'pz-linkcard' ), 403 );
			}
			if	(!$post ) {
				wp_send_json_error(__('Post not found.', 'pz-linkcard' ), 404 );
			}
			if	(post_password_required($post ) ) {
				wp_send_json_error(__('You do not have permission to preview this post.', 'pz-linkcard' ), 403 );
			}
			if	(!current_user_can('read_post', $post_id ) ) {
				wp_send_json_error(__('You do not have permission to preview this post.', 'pz-linkcard' ), 403 );
			}
			$preview_data	= $this->pz_GetPost(array('url' => $url ), true );
			if	(!is_array($preview_data ) ) {
				wp_send_json_error(__('Failed to retrieve the post content.', 'pz-linkcard' ), 500 );
			}
			$atts['preview-data']	= $preview_data;
		}
	}
	$html	= $this->shortcode($atts, null, $matches[1] );
	if	(!$html ) {
		wp_send_json_error(__('Failed to retrieve the post content.', 'pz-linkcard' ), 500 );
	}
	wp_send_json_success(array('html' => $html ) );
