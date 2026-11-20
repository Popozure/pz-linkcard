<?php
defined('ABSPATH') || exit;

if (!current_user_can('manage_options')) {
	wp_die(esc_html__('Sorry, you are not allowed to access this page.', 'pz-linkcard'));
}
if ($this->is_cacheman_detail_request()) {
	require __DIR__.'/pz-linkcard-oldman.php';
	return;
}
if (!class_exists('WP_List_Table')) {
	require_once ABSPATH.'wp-admin/includes/class-wp-list-table.php';
}
require_once __DIR__.'/pz-linkcard-search-query-parser.php';

$operation_notice = '';
$bulk_operations = array(
	'renew' => __('Renew Cache', 'pz-linkcard'),
	'renew_thumbnail' => __('Renew Thumbnail Image', 'pz-linkcard'),
	'renew_sns' => __('Renew SNS Count', 'pz-linkcard'),
	'renew_postid' => __('Renew Post ID', 'pz-linkcard'),
	'alive' => __('Check Status', 'pz-linkcard'),
	'delete' => __('Delete from Cache', 'pz-linkcard'),
);
if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pz_linkcard_list_action'])) {
	check_admin_referer('pz-linkcard-list-action');
	$operation = isset($_POST['bulk_action']) && is_string($_POST['bulk_action']) ? sanitize_key(wp_unslash($_POST['bulk_action'])) : '';
	$ids = isset($_POST['card_ids']) && is_array($_POST['card_ids']) ? array_unique(array_filter(array_map('absint', $_POST['card_ids']))) : array();
	foreach (array('renew', 'delete') as $single_action) {
		if (isset($_POST['single_'.$single_action]) && is_scalar($_POST['single_'.$single_action])) {
			$operation = $single_action;
			$ids = array_filter(array(absint($_POST['single_'.$single_action])));
			break;
		}
	}
	if (isset($bulk_operations[$operation])) {
		global $wpdb;
		$success = 0;
		foreach ($ids as $id) {
			$result = false;
			$data = $this->pz_GetCache(array('id' => $id));
			if (!is_array($data)) { continue; }
			switch ($operation) {
				case 'renew':
					$result = $this->pz_QueueCardRefresh($id);
					break;
				case 'renew_thumbnail':
					$result = !empty($data['thumbnail']) && $this->pz_GetImage($data['thumbnail'], true);
					break;
				case 'renew_sns':
					$data['sns_nexttime'] = 0;
					$data = $this->pz_SetCache($data);
					$result = is_array($data) ? $this->pz_RenewSNSCount($data) : false;
					break;
				case 'renew_postid':
					$post_ids = !empty($data['url']) ? $this->pz_FindUsedPostIDs($data['url']) : null;
					if ($post_ids !== null) {
						$fields = array();
						for ($index = 1; $index <= 6; $index++) { $fields['use_post_id'.$index] = $post_ids[$index - 1] ?? null; }
						$result = $wpdb->update($this->db_card, $fields, array('id' => $id), array_fill(0, 6, '%d'), array('%d')) !== false;
						if ($result) { $this->request_cache = array(); }
					}
					break;
				case 'alive':
					$url_info = $this->pz_GetURLInfo($data['url']);
					$after = !empty($url_info['is_internal']) ? $this->pz_GetPost($data) : $this->pz_GetRemote($data);
					if (is_array($after) && isset($after['update_result'])) {
						$data['alive_result'] = $after['update_result'];
						$data['update_result'] = $data['alive_result'];
						$data['alive_time'] = $this->now;
						$data['alive_nexttime'] = (!empty($url_info['is_internal']) ? null : ($after['alive_nexttime'] ?? null)) ?? $this->pz_GetNextAliveTime($data['alive_result'], $this->now);
						$result = $this->pz_SetCache($data);
					}
					break;
				case 'delete':
					$result = $this->pz_DelCache(array('id' => $id));
					break;
			}
			if ($result) { $success++; }
		}
		$operation_notice = !$ids ? __('Not selected', 'pz-linkcard') : ($operation === 'renew' ? __('Content retrieval queued', 'pz-linkcard') : $bulk_operations[$operation]).' — '.__('Success:', 'pz-linkcard').' '.number_format_i18n($success).' / '.number_format_i18n(count($ids));
	}
}

$list = new class($this->db_card, $this->domain, function ($timestamp) {
	return wp_kses($this->pz_Date($this->options['date-format-man'], $timestamp), array('br' => array()));
}, $this->plugin_dir_url.'img/') extends WP_List_Table {
	private $card_table;
	private $site_domain;
	private $conditions;
	private $counts = array();
	private $filter;
	private $search;
	private $domain_filter;
	private $format_date;
	private $sort_icon_url;

	public function __construct($table, $domain, $format_date, $sort_icon_url) {
		parent::__construct(array('singular' => 'linkcard', 'plural' => 'linkcards', 'ajax' => false));
		global $wpdb;
		$this->card_table = $table;
		$this->site_domain = $domain;
		$this->format_date = $format_date;
		$this->sort_icon_url = $sort_icon_url;
		$this->conditions = array(
			'all' => '1=1',
			'external' => $wpdb->prepare('domain <> %s', $domain),
			'internal' => $wpdb->prepare('domain = %s', $domain),
			'modified' => 'alive_result <> update_result',
			'error' => 'alive_result IS NOT NULL AND alive_result <> 0 AND (alive_result < 100 OR alive_result >= 400)',
			'unregistered' => '(regist_time IS NULL OR regist_time = 0)',
		);
		$filter = isset($_GET['filter']) && is_string($_GET['filter']) ? sanitize_key(wp_unslash($_GET['filter'])) : 'all';
		$this->filter = isset($this->conditions[$filter]) ? $filter : 'all';
		$this->search = isset($_GET['s']) && is_string($_GET['s']) ? trim(preg_replace('/[\r\n\t ]+/u', ' ', wp_check_invalid_utf8(wp_unslash($_GET['s'])))) : '';
		$refine = $_GET['refine'] ?? ($_GET['domain'] ?? '');
		$this->domain_filter = is_string($refine) ? sanitize_text_field(wp_unslash($refine)) : '';
	}

	public function get_columns() {
		return array(
			'cb' => '<input type="checkbox">',
			'id' => __('Card<br>ID', 'pz-linkcard'),
			'title' => __('URL', 'pz-linkcard').' / '.__('Title', 'pz-linkcard'),
			'excerpt' => __('Excerpt', 'pz-linkcard'),
			'post_id' => __('Post<br>ID', 'pz-linkcard'),
			'post_date' => __('Post<br>Date and Time', 'pz-linkcard'),
			'post_modified' => __('Post<br>Modified Date', 'pz-linkcard'),
			'charset' => __('Charset', 'pz-linkcard'),
			'domain' => __('Domain', 'pz-linkcard'),
			'sns' => __('SNS', 'pz-linkcard'),
			'regist_time' => __('Registered<br>Date', 'pz-linkcard'),
			'update_time' => __('Update<br>Date', 'pz-linkcard'),
			'sns_time' => __('SNS<br>Check<br>Date', 'pz-linkcard'),
			'sns_nexttime' => __('Next<br>Share Count<br>Date', 'pz-linkcard'),
			'alive_time' => __('Alive<br>Check<br>Date', 'pz-linkcard'),
			'alive_nexttime' => __('Next<br>Status<br>Date', 'pz-linkcard'),
			'use_post_id1' => __('Published<br>Post<br>ID', 'pz-linkcard'),
			'click_count' => __('Click<br>Count', 'pz-linkcard'),
			'update_result' => __('Result<br>code', 'pz-linkcard'),
		);
	}

	public function print_column_headers($with_id = true) {
		static $checkbox_index = 0;
		$hidden = get_hidden_columns($this->screen);
		foreach ($this->get_columns() as $column => $label) {
			$attributes = ($with_id ? ' id="pz-card-list-heading-'.esc_attr($column).'"' : '').' class="manage-column column-'.esc_attr($column).(in_array($column, $hidden, true) ? ' hidden' : '').($column === 'cb' ? ' check-column' : ($column === 'title' ? ' column-primary' : '')).'"';
			if ($column === 'cb' && !$with_id) {
				$html = '';
			} elseif ($column === 'cb') {
				$checkbox_id = 'cb-select-all-'.(++$checkbox_index);
				$html = '<input type="checkbox" id="'.esc_attr($checkbox_id).'"><label class="screen-reader-text" for="'.esc_attr($checkbox_id).'">'.esc_html__('Select All').'</label>';
			} elseif ($column === 'title') {
				$html = $this->card_sort_heading('url', __('URL', 'pz-linkcard')).' / '.$this->card_sort_heading('title', __('Title', 'pz-linkcard'));
			} elseif ($column === 'sns') {
				$links = array();
				foreach (array('sns_twitter' => '𝕏', 'sns_facebook' => 'fb', 'sns_hatena' => 'B!', 'sns_qiita_likes' => 'Qiita') as $key => $name) {
					$links[] = $this->card_sort_heading($key, $name);
				}
				$html = implode('<br>', $links);
			} else {
				$html = $this->card_sort_heading($column, $label);
				if ($column === 'update_result') { $html .= '<br>'.$this->card_sort_heading('alive_result', __('(Last)', 'pz-linkcard')); }
			}
			echo '<th scope="col"'.$attributes.'>'.$html.'</th>';
		}
	}

	private function card_sort_heading($column, $label) {
		$current = isset($_GET['orderby']) && is_string($_GET['orderby']) ? sanitize_key(wp_unslash($_GET['orderby'])) : 'id';
		$order = isset($_GET['order']) && is_string($_GET['order']) && strtolower(wp_unslash($_GET['order'])) === 'asc' ? 'asc' : 'desc';
		$active = $current === $column;
		$next_order = !$active || $order === 'asc' ? 'desc' : 'asc';
		$url = add_query_arg(array(
			'page' => 'pz-linkcard-cacheman',
			'filter' => $this->filter,
			'refine' => $this->domain_filter,
			's' => $this->search,
			'orderby' => $column,
			'order' => $next_order,
		), admin_url('tools.php'));
		$icon = !$active ? 'sort_none.svg' : ($order === 'asc' ? 'sort_asc.svg' : 'sort_desc.svg');
		return '<a class="pz-card-list-sort" href="'.esc_url($url).'" aria-label="'.esc_attr(wp_strip_all_tags($label).' — '.($next_order === 'asc' ? __('Ascending') : __('Descending'))).'"'.($active ? ' aria-current="true"' : '').'><span>'.wp_kses($label, array('br' => array())).'</span><img src="'.esc_url($this->sort_icon_url.$icon).'" alt="" width="16" height="16"></a>';
	}

	public function column_cb($item) {
		return '<input type="checkbox" name="card_ids[]" form="pz-linkcard-actions" value="'.esc_attr($item['id']).'" aria-label="'.esc_attr($item['url']).'">';
	}

	public function single_row($item) {
		echo '<tr data-pz-card-id="'.esc_attr($item['id']).'" data-pz-regist-title="'.esc_attr($item['regist_title'] ?? '').'"'.(!empty($item['_pending']) ? ' data-pz-pending-id="'.esc_attr($item['id']).'"' : '').'>';
		$this->single_row_columns($item);
		echo '</tr>';
	}

	protected function pagination($which) {
		ob_start();
		parent::pagination($which);
		// WordPress hides navigation controls with this class when there is one page.
		echo str_replace(' one-page', '', ob_get_clean());
	}

	public function search_box($text, $input_id) {
		ob_start();
		parent::search_box($text, $input_id);
		$html = ob_get_clean();
		echo str_replace('type="search"', 'type="search" placeholder="'.esc_attr__('Search', 'pz-linkcard').'"', $html);
	}

	protected function bulk_actions($which = '') {
		if ($which !== 'top') { return; }
		echo '<div class="alignleft actions bulkactions"><label class="screen-reader-text" for="pz-linkcard-bulk">'.esc_html__('Bulk Actions', 'pz-linkcard').'</label><select id="pz-linkcard-bulk" name="bulk_action" form="pz-linkcard-actions">';
		foreach (array('' => __('Bulk Actions', 'pz-linkcard'), 'renew' => __('Renew Cache', 'pz-linkcard'), 'renew_thumbnail' => __('Renew Thumbnail Image', 'pz-linkcard'), 'renew_sns' => __('Renew SNS Count', 'pz-linkcard'), 'renew_postid' => __('Renew Post ID', 'pz-linkcard'), 'alive' => __('Check Status', 'pz-linkcard'), 'delete' => __('Delete from Cache', 'pz-linkcard')) as $value => $label) {
			echo '<option value="'.esc_attr($value).'">'.esc_html($label).'</option>';
		}
		echo '</select><button type="submit" form="pz-linkcard-actions" class="button action">'.esc_html__('Search', 'pz-linkcard').'</button></div>';
	}

	protected function extra_tablenav($which) {
		if ($which !== 'top') { return; }
		global $wpdb;
		$condition = $this->conditions[$this->filter];
		$domains = $wpdb->get_results("SELECT domain, COUNT(*) AS card_count FROM `{$this->card_table}` WHERE ($condition) AND domain IS NOT NULL AND domain <> '' GROUP BY domain ORDER BY domain", ARRAY_A);
		echo '<div class="alignleft actions"><label class="screen-reader-text" for="pz-linkcard-domain">'.esc_html__('All Domains', 'pz-linkcard').'</label><select name="refine" id="pz-linkcard-domain"><option value="">'.esc_html__('All Domains', 'pz-linkcard').'</option>';
		foreach ($domains as $domain) {
			echo '<option value="'.esc_attr($domain['domain']).'"'.selected($this->domain_filter, $domain['domain'], false).'>'.esc_html($domain['domain'].' ('.number_format_i18n((int) $domain['card_count']).')').'</option>';
		}
		echo '</select><button type="submit" class="button">'.esc_html__('Filter Search', 'pz-linkcard').'</button></div>';
	}

	protected function get_sortable_columns() {
		$sortable = array();
		foreach (array('id', 'excerpt', 'domain', 'post_id', 'post_date', 'post_modified', 'charset', 'regist_time', 'update_time', 'sns_time', 'sns_nexttime', 'alive_time', 'alive_nexttime', 'use_post_id1', 'click_count', 'update_result') as $column) {
			$sortable[$column] = array($column, false);
		}
		$sortable['sns'] = array('sns_twitter', false);
		return $sortable;
	}

	protected function get_views() {
		$labels = array('all' => __('All', 'pz-linkcard'), 'external' => __('External Links', 'pz-linkcard'), 'internal' => __('Internal Links', 'pz-linkcard'), 'modified' => __('HTTP Status Changed', 'pz-linkcard'), 'error' => __('Broken Link', 'pz-linkcard'), 'unregistered' => __('Unregistered', 'pz-linkcard'));
		$views = array();
		foreach ($labels as $key => $label) {
			$url = add_query_arg(array('page' => 'pz-linkcard-cacheman', 'filter' => $key), admin_url('tools.php'));
			$views[$key] = '<a href="'.esc_url($url).'"'.($key === $this->filter ? ' class="current" aria-current="page"' : '').'>'.esc_html($label).'<span class="count">('.esc_html(number_format_i18n($this->counts[$key])).')</span></a>';
		}
		return $views;
	}

	public function prepare_items() {
		global $wpdb;
		$aggregates = array();
		foreach ($this->conditions as $key => $condition) {
			$aggregates[] = "COUNT(CASE WHEN $condition THEN 1 END) AS `$key`";
		}
		$this->counts = (array) $wpdb->get_row('SELECT '.implode(', ', $aggregates)." FROM `{$this->card_table}`");
		$this->counts = array_merge(array_fill_keys(array_keys($this->conditions), 0), $this->counts);
		$where = $this->conditions[$this->filter];
		if ($this->domain_filter !== '') {
			$where .= $wpdb->prepare(' AND domain = %s', $this->domain_filter);
		}
		if ($this->search !== '') {
			$query = $this->search;
			if (preg_match('/^[0-9]+$/D', trim($query))) {
				$number = trim($query);
				$query = '('.$number.' id:'.$number.' post:'.$number.')';
			}
			$parser = new pz_LinkCard_Search_Query_Parser($wpdb);
			$parsed = $parser->parse($query);
			if ($parsed['where'] !== '') {
				$where .= ' AND ('.($parsed['params'] ? $wpdb->prepare($parsed['where'], $parsed['params']) : $parsed['where']).')';
			}
		}
		$orderby = isset($_GET['orderby']) && is_string($_GET['orderby']) ? sanitize_key(wp_unslash($_GET['orderby'])) : 'id';
		$allowed_orderby = array('id', 'title', 'url', 'alive_result', 'sns_twitter', 'sns_facebook', 'sns_hatena', 'sns_qiita_likes');
		foreach ($this->get_sortable_columns() as $sortable) {
			$allowed_orderby[] = $sortable[0];
		}
		if (!in_array($orderby, $allowed_orderby, true)) {
			$orderby = 'id';
		}
		$order = isset($_GET['order']) && is_string($_GET['order']) && strtolower(wp_unslash($_GET['order'])) === 'asc' ? 'ASC' : 'DESC';
		if (isset($_GET['pz_refresh_ids']) && is_string($_GET['pz_refresh_ids'])) {
			$refresh_ids = array_slice(array_filter(array_map('absint', explode(',', wp_unslash($_GET['pz_refresh_ids'])))), 0, 100);
			$where = $refresh_ids ? 'id IN ('.implode(',', $refresh_ids).')' : '1=0';
		}
		if ($orderby === 'post_id') {
			$missing = $wpdb->get_results($wpdb->prepare("SELECT id, url FROM `{$this->card_table}` WHERE domain = %s AND (post_id IS NULL OR post_id = 0)", $this->site_domain));
			foreach ($missing as $card) {
				$post_id = url_to_postid($card->url);
				if ($post_id) { $wpdb->update($this->card_table, array('post_id' => $post_id), array('id' => $card->id), array('%d'), array('%d')); }
			}
		}
		$total = (int) $wpdb->get_var("SELECT COUNT(*) FROM `{$this->card_table}` WHERE $where");
		$per_page = (int) (get_user_option('pz_linkcard_cards_per_page') ?: get_user_meta(get_current_user_id(), 'pz_lkc_cacheman_per_page', true));
		if (!in_array($per_page, array(10, 20, 50, 100), true)) { $per_page = 10; }
		if (isset($refresh_ids)) { $per_page = 100; }
		$page = min(max(1, $this->get_pagenum()), max(1, (int) ceil($total / $per_page)));
		if (isset($refresh_ids)) { $page = 1; }
		$sort_expression = $orderby === 'sns_qiita_likes' ? 'CASE WHEN sns_qiita_likes IS NULL AND sns_qiita_stock IS NULL THEN NULL ELSE COALESCE(sns_qiita_likes, 0) + COALESCE(sns_qiita_stock, 0) END' : "`$orderby`";
		if ($orderby === 'post_id') { $sort_expression = $wpdb->prepare('CASE WHEN domain = %s THEN COALESCE(post_id, 0) ELSE 0 END', $this->site_domain); }
		$this->items = $wpdb->get_results($wpdb->prepare("SELECT * FROM `{$this->card_table}` WHERE $where ORDER BY $sort_expression $order, id $order LIMIT %d OFFSET %d", $per_page, ($page - 1) * $per_page), ARRAY_A);
		$this->_column_headers = array($this->get_columns(), get_hidden_columns($this->screen), $this->get_sortable_columns(), 'title');
		$this->set_pagination_args(array('total_items' => $total, 'per_page' => $per_page));
	}

	public function column_default($item, $column_name) {
		if ($column_name === 'id') {
			$html = '<a class="pz-card-list-id" href="'.esc_url($this->list_filter_url(array('s' => 'id:'.absint($item['id'])))).'">'.esc_html((string) $item['id']).'</a>';
			if (!empty($item['_thumbnail_url'])) {
				$html .= '<a class="pz-card-list-thumbnail" href="'.esc_url($item['_thumbnail_url']).'" target="_blank" rel="noopener noreferrer"><img src="'.esc_url($item['_thumbnail_url']).'" alt="" loading="lazy"></a>';
			}
			return $html;
		}
		if ($column_name === 'title') {
			$return_url = add_query_arg(array(
				'page' => 'pz-linkcard-cacheman',
				'filter' => $this->filter,
				's' => rawurlencode($this->search),
				'refine' => rawurlencode($this->domain_filter),
				'paged' => max(1, $this->get_pagenum()),
				'orderby' => isset($_GET['orderby']) && is_string($_GET['orderby']) ? sanitize_key(wp_unslash($_GET['orderby'])) : 'id',
				'order' => isset($_GET['order']) && is_string($_GET['order']) && strtolower(wp_unslash($_GET['order'])) === 'asc' ? 'asc' : 'desc',
			), admin_url('tools.php'));
			$url = add_query_arg(array('page' => 'pz-linkcard-cacheman', 'action' => 'edit', 'cache_id' => (int) $item['id'], 'return_url' => rawurlencode($return_url)), admin_url('tools.php'));
			$actions = array(
				'edit' => '<a '.(!empty($item['_pending']) ? 'data-pz-disabled-href="'.esc_url($url).'" aria-disabled="true" tabindex="-1"' : 'href="'.esc_url($url).'"').'>'.esc_html__('Edit', 'pz-linkcard').'</a>',
				'renew' => '<button type="submit" class="button-link" form="pz-linkcard-actions" name="single_renew" value="'.esc_attr($item['id']).'"'.(!empty($item['_pending']) ? ' disabled' : '').'>'.esc_html__('Renew', 'pz-linkcard').'</button>',
				'delete' => '<button type="submit" class="button-link button-link-delete" form="pz-linkcard-actions" name="single_delete" value="'.esc_attr($item['id']).'"'.(!empty($item['_pending']) ? ' disabled' : '').'>'.esc_html__('Delete', 'pz-linkcard').'</button>',
			);
			$title_html = !empty($item['_pending']) ? esc_html__('Retrieving...', 'pz-linkcard').'<span class="pz-man-pending-loader" aria-hidden="true"></span>' : esc_html($item['title'] ?: __('(no title)', 'pz-linkcard'));
			$title_class = (string) ($item['title'] ?? '') !== (string) ($item['regist_title'] ?? '') ? ' pz-card-list-changed' : '';
			$error_icon = '';
			foreach (array('alive_result', 'update_result') as $status_key) {
				$status = isset($item[$status_key]) ? (int) $item[$status_key] : null;
				if ($status !== null && $status !== 0 && ($status < 100 || $status >= 400)) {
					$ignored = !empty($item['no_failure']);
					$icon_class = $ignored ? 'pz-man-body-url-error-ignore dashicons dashicons-warning' : 'pz-man-body-url-error dashicons dashicons-dismiss';
					$message = $ignored ? __('A recorded HTTP status code indicates an error, but the error is being ignored for this card.', 'pz-linkcard') : __('A recorded HTTP status code indicates an error. You can choose to ignore the error on the card editing screen.', 'pz-linkcard');
					$error_icon = '<span class="'.esc_attr($icon_class).'" title="'.esc_attr($message).'" role="img" aria-label="'.esc_attr($message).'"></span>';
					break;
				}
			}
			return '<div class="pz-card-list-url-line">'.$error_icon.'<a class="row-title pz-card-list-url" href="'.esc_url($item['url']).'" target="_blank" rel="noopener noreferrer">'.esc_html($item['_display_url'] ?? $item['url']).'</a></div><span class="pz-card-list-title'.$title_class.'" title="'.esc_attr($item['title']).'"><span class="pz-card-list-title-text">'.$title_html.'</span></span>'.$this->row_actions($actions);
		}
		if ($column_name === 'url') {
			return '<a href="'.esc_url($item['url']).'" target="_blank" rel="noopener noreferrer">'.esc_html($item['_display_url'] ?? $item['url']).'</a>';
		}
		if (in_array($column_name, array('post_date', 'post_modified', 'regist_time', 'update_time', 'sns_time', 'sns_nexttime', 'alive_time', 'alive_nexttime'), true)) {
			return !empty($item[$column_name]) ? call_user_func($this->format_date, (int) $item[$column_name]) : '-';
		}
		if ($column_name === 'domain') {
			$domain = (string) ($item['domain'] ?? '');
			$site_icon = !empty($item['_favicon_url']) ? '<img class="pz-card-list-site-icon" src="'.esc_url($item['_favicon_url']).'" alt="" width="14" height="14" loading="lazy">' : '';
			$domain_link = $domain !== '' ? '<a href="'.esc_url($this->list_filter_url(array('refine' => $domain))).'">'.$site_icon.esc_html($domain).'</a>' : '-';
			return '<div class="pz-card-list-lines4 pz-card-list-domain" title="'.esc_attr($domain.' / '.($item['site_name'] ?? '')).'"><span class="pz-card-list-domain-line">'.$domain_link.'</span>'.(!empty($item['site_name']) ? '<br><span class="pz-card-list-site-name">'.esc_html((string) $item['site_name']).'</span>' : '').'</div>';
		}
		if ($column_name === 'excerpt') {
			$changed_class = (string) ($item['excerpt'] ?? '') !== (string) ($item['regist_excerpt'] ?? '') ? ' pz-card-list-changed' : '';
			return '<div class="pz-card-list-lines4'.$changed_class.'" title="'.esc_attr($item['excerpt'] ?? '').'">'.esc_html((string) ($item['excerpt'] ?? '')).'</div>';
		}
		if ($column_name === 'post_id') {
			$post_id = absint($item['post_id'] ?? 0);
			$link = $post_id ? get_permalink($post_id) : false;
			return $link ? '<a href="'.esc_url($link).'" target="_blank" rel="noopener noreferrer">'.esc_html((string) $post_id).'</a>' : ($post_id ? esc_html((string) $post_id) : '-');
		}
		if ($column_name === 'sns') {
			$counts = array();
			foreach (array('sns_twitter', 'sns_facebook', 'sns_hatena') as $key) {
				$counts[] = $this->format_share_count($item[$key] ?? null);
			}
			$qiita_total = !isset($item['sns_qiita_likes']) && !isset($item['sns_qiita_stock']) ? null : (int) ($item['sns_qiita_likes'] ?? 0) + (int) ($item['sns_qiita_stock'] ?? 0);
			$counts[] = $this->format_share_count($qiita_total);
			return '<div class="pz-card-list-lines4">'.implode('<br>', $counts).'</div>';
		}
		if ($column_name === 'use_post_id1') {
			$posts = array();
			for ($index = 1; $index <= 6; $index++) {
				$post_id = absint($item['use_post_id'.$index] ?? 0);
				if ($post_id) { $posts[$post_id] = $post_id; }
			}
			$links = array();
			foreach (array_slice(array_values($posts), 0, 4) as $post_id) {
				$links[] = '<a href="'.esc_url($this->list_filter_url(array('s' => 'post:'.$post_id))).'">'.esc_html((string) $post_id).'</a>';
			}
			return $links ? '<div class="pz-card-list-lines4">'.implode('<br>', $links).'</div>' : '-';
		}
		if ($column_name === 'update_result') {
			$result = (string) ($item['update_result'] ?? '-');
			$latest_result = (string) ($item['alive_result'] ?? '-');
			return esc_html($result).($result !== $latest_result ? '<br><span>('.esc_html($latest_result).')</span>' : '');
		}
		if ($column_name === 'click_count') {
			$url = add_query_arg(array('page' => 'pz-linkcard-cacheman', 'action' => 'click-log', 'cache_id' => absint($item['id'])), admin_url('tools.php'));
			return '<a href="'.esc_url($url).'" title="'.esc_attr__('History', 'pz-linkcard').'">'.esc_html(number_format_i18n((int) ($item['click_count'] ?? 0))).'</a>';
		}
		return esc_html((string) ($item[$column_name] ?? ''));
	}

	private function format_share_count($value) {
		if ($value === null) { return '-'; }
		$count = (int) $value;
		if ($count >= 1000000) { return '<span title="'.esc_attr(number_format_i18n($count)).'">'.esc_html(number_format_i18n(intdiv($count, 1000000)).' m').'</span>'; }
		if ($count >= 1000) { return '<span title="'.esc_attr(number_format_i18n($count)).'">'.esc_html(number_format_i18n(intdiv($count, 1000)).' k').'</span>'; }
		return esc_html(number_format_i18n($count));
	}

	private function list_filter_url($args) {
		return add_query_arg(array_merge(array('page' => 'pz-linkcard-cacheman', 'filter' => 'all'), $args), admin_url('tools.php'));
	}
};
$list->prepare_items();
// Resolve display-only thumbnails and internal post IDs in the plugin's scope.
foreach ($list->items as &$item) {
	$item['_pending'] = $this->pz_IsCardRetrievalPending($item);
	$item['_display_url'] = $this->pz_DecodeURL($item['url']);
	$item['_favicon_url'] = !empty($item['favicon']) ? $this->pz_GetImage($item['favicon']) : '';
	$url_info = $this->Pz_GetURLInfo($item['url']);
	$item['_thumbnail_url'] = '';
	if (!empty($url_info['is_internal'])) {
		$item['post_id'] = absint($item['post_id'] ?? 0) ?: url_to_postid($item['url']);
		$thumbnail_id = $item['post_id'] ? get_post_thumbnail_id($item['post_id']) : 0;
		if ($thumbnail_id) {
			$attachment = wp_get_attachment_image_src($thumbnail_id, !empty($this->options['in-thumbnail-size']) ? $this->options['in-thumbnail-size'] : 'thumbnail', true);
			$item['_thumbnail_url'] = $attachment ? $attachment[0] : '';
		}
	} else {
		$item['post_id'] = 0;
		if (!empty($item['thumbnail'])) {
			$item['_thumbnail_url'] = $this->pz_GetImage($item['thumbnail']);
		}
	}
}
unset($item);
if (isset($_GET['pz_refresh_ids']) && is_string($_GET['pz_refresh_ids'])) {
	echo '<table><tbody>';
	foreach ($list->items as $item) { $list->single_row($item); }
	echo '</tbody></table>';
	return;
}
require __DIR__.'/pz-linkcard-cacheman-infobar.php';
?>
<div class="wrap pz-card-list">
	<div id="pz-overlay-proc" style="display: none;" role="status" aria-live="polite">
		<div class="pz-loader" aria-hidden="true"></div>
		<span class="screen-reader-text"><?php esc_html_e('Loading…'); ?></span>
	</div>
	<div class="pz-header">
		<h1><span class="pz-header-title"><a class="pz-header-title-link" href="<?php echo esc_url(admin_url('tools.php?page=pz-linkcard-cacheman')); ?>"><span class="pz-header-title-icon dashicons dashicons-archive" aria-hidden="true"></span><span class="pz-header-title-text"><?php esc_html_e('Pz-LinkCard Manager', 'pz-linkcard'); ?></span></a><a class="pz-help-icon" href="<?php echo esc_url(self::AUTHOR_URL.'/pz-linkcard-manager'); ?>" rel="external noopener noreferrer help" referrerpolicy="no-referrer" target="_blank"><img src="<?php echo esc_url($this->plugin_dir_url.'img/help.png'); ?>" width="16" height="16" title="<?php esc_attr_e('Help', 'pz-linkcard'); ?>" alt="<?php esc_attr_e('Help', 'pz-linkcard'); ?>"></a></span></h1>
	</div>
	<hr class="wp-header-end">
	<?php if (!empty($this->options['error-mode']) && empty($this->options['error-mode-hide'])) : ?>
		<div class="notice notice-error is-dismissible pz-lkc-error-mode-notice">
			<p><strong><?php echo esc_html(self::PLUGIN_NAME.': '.__('Invalid URL parameter in ', 'pz-linkcard')); ?><a href="<?php echo esc_url(($this->options['error-url'] ?? '').'#lkc-error'); ?>" target="_blank" rel="external noopener noreferrer" referrerpolicy="no-referrer"><?php echo esc_html($this->pz_DecodeURL($this->options['error-url'] ?? '')); ?></a></strong><br><?php echo esc_html__('*', 'pz-linkcard').' '.wp_kses_post(__('You can dismiss this message from <a href="./options-general.php?page=pz-linkcard-settings&tab=pz-error#pz-error-reset-settings">the settings screen</a>.', 'pz-linkcard')); ?></p>
		</div>
	<?php endif; ?>
	<?php if ($operation_notice !== '') { echo '<div class="notice notice-info is-dismissible"><p>'.esc_html($operation_notice).'</p></div>'; } ?>
	<form id="pz-linkcard-actions" method="post" action="<?php echo esc_url(remove_query_arg(array('_wpnonce', '_wp_http_referer'))); ?>" data-delete-confirm="<?php esc_attr_e('Are you sure?', 'pz-linkcard'); ?>">
		<input type="hidden" name="pz_linkcard_list_action" value="1">
		<?php wp_nonce_field('pz-linkcard-list-action'); ?>
	</form>
	<form class="pz-card-list-form" method="get" action="<?php echo esc_url(admin_url('tools.php')); ?>">
		<input type="hidden" name="page" value="pz-linkcard-cacheman">
		<input type="hidden" name="filter" value="<?php echo esc_attr(isset($_GET['filter']) && is_string($_GET['filter']) ? sanitize_key(wp_unslash($_GET['filter'])) : 'all'); ?>">
		<div class="pz-card-list-heading-row">
			<?php $list->views(); ?>
			<?php $list->search_box(__('Search Link Cards', 'pz-linkcard'), 'pz-linkcard-search'); ?>
		</div>
		<div class="pz-card-list-scroll"><?php $list->display(); ?></div>
	</form>
</div>
