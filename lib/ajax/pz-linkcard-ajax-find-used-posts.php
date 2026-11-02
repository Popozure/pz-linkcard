<?php
defined('ABSPATH' ) || wp_die;

	if	(!current_user_can('manage_options' ) ) {
		wp_send_json_error(__('You do not have permission to update LinkCard data.', 'pz-linkcard' ), 403 );
	}
	if	(!check_ajax_referer('pz_lkc_find_used_posts', 'nonce', false ) ) {
		wp_send_json_error(__('Invalid request.', 'pz-linkcard' ), 403 );
	}

	$card_id	=	isset($_POST['card_id'] ) ? absint(wp_unslash($_POST['card_id'] ) ) : 0;
	$data		=	$card_id > 0 ? $this->pz_GetCache(array('id' => $card_id ) ) : null;
	if	(!is_array($data ) || empty($data['url'] ) ) {
		wp_send_json_error(__('Invalid card ID', 'pz-linkcard' ), 400 );
	}

	global	$wpdb;
	$search_urls = array((string) $data['url']);
	$host = wp_parse_url($data['url'], PHP_URL_HOST);
	if ($host && defined('INTL_IDNA_VARIANT_UTS46')) {
		foreach (array('idn_to_utf8', 'idn_to_ascii') as $converter) {
			if (!function_exists($converter)) {
				continue;
			}
			$converted_host = $converter($host, 0, INTL_IDNA_VARIANT_UTS46);
			if ($converted_host !== false && $converted_host !== '') {
				$search_urls[] = preg_replace_callback(
					'~\A(https?://(?:[^/?#@]*@)?)'.preg_quote($host, '~').'(?=[:/?#]|$)~i',
					static function($matches) use ($converted_host) {
						return $matches[1].$converted_host;
					},
					$data['url']
				);
			}
		}
	}
	$conditions = array();
	$search_values = array();
	foreach (array_unique($search_urls) as $search_url) {
		$conditions[] = 'post_content LIKE %s';
		$search_values[] = '%'.$wpdb->esc_like($search_url).'%';
	}
	$post_ids	=	$wpdb->get_col($wpdb->prepare(
		"SELECT ID FROM {$wpdb->posts} WHERE post_type = 'post' AND (".implode(' OR ', $conditions).") ORDER BY ID ASC LIMIT 6",
		$search_values
	) );

	wp_send_json_success(array('post_ids' => array_values(array_map('intval', $post_ids ) ) ) );
