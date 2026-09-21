<?php defined('ABSPATH' ) || wp_die; ?>
<?php
	require_once __DIR__.'/pz-linkcard-search-query-parser.php';

	// ドメイン一覧
	$mydomain			=	null;
	if	(preg_match('{https?://(.*)/}i', $this->home_url.'/',$m ) ) {
		$mydomain_url	=	$m[0];
		$mydomain		=	$m[1];
	}

	// ドメイン一覧作成
	$domain_list		=	$wpdb->get_results("SELECT domain, count(*) AS count FROM $this->db_name GROUP BY domain ORDER BY domain ASC", ARRAY_A );

	// ドメイン存在チェック
	$refine		=	null;
	if	($param_refine ) {
		foreach	($domain_list as $item ) {
			if	($param_refine	===	$item['domain'] ) {
				$refine			=	$item['domain'];
				break;
			}
		}
	}

	// ソート項目パラメータ
	$column_rec			=	$wpdb->get_results("SELECT * FROM $this->db_name LIMIT 1", ARRAY_A );	// 項目名を取得
	if	(isset($column_rec[0] ) && array_key_exists($orderby, $column_rec[0] ) ) {					// 項目名に存在するかチェック
		$orderby		=	$orderby;																// 存在したら項目名にセットする
	} else {
		$orderby		=	'id';																	// 存在しない項目名の場合 'id' をセットする
	}

	// ソート順パラメータ
	if	($order			===	'asc' ) {
		$order			=	'asc';		// 昇順
	} else {
		$order			=	'desc';		// 降順
	}

	// 抽出条件
	$cond				=	(string) $keyword;

	switch	($filter ) {
	case	'all':
		break;
	case	'internal':
		$cond			.=	' domain='.$this->domain;
		break;
	case	'external':
		$cond			.=	' -domain='.$this->domain;
		break;
	case	'modify':
		$cond			.=	' alive_result<>update_result';
		break;
	case	'unlink':
		$cond			.=	' -alive_result:100..399';
		break;
	default:
		if	($this->options['flg-alive'] && $this->options['flg-alive-count']) {
			$filter		=	'unlink';
			$cond		.=	' -alive_result:100..399';
		} else {
			$filter		=	'all';
		}
	}	

	// キーワード指定
	$param				=	array();

	// ドメイン指定
	if	($refine ) {
		$cond			.=	' domain='.$refine;
	}

	$parser				=	new pz_LinkCard_Search_Query_Parser($wpdb );
	$parsed_search		=	$parser->parse($cond );
	$where				=	$parsed_search['where'];
	$param				=	$parsed_search['params'];

	// 表示オプション
	$screen_option_columns	=	array(
		'id'			=>	array('label' => __('ID', 'pz-linkcard' ) ),
		'excerpt'		=>	array('label' => __('Excerpt', 'pz-linkcard' ) ),
		'charset'		=>	array('label' => __('Charset', 'pz-linkcard' ) ),
		'domain'		=>	array('label' => __('Domain', 'pz-linkcard' ) ),
		'sns'			=>	array('label' => __('SNS', 'pz-linkcard' ) ),
		'regist_time'	=>	array('label' => __('Registered Date', 'pz-linkcard' ) ),
		'update_time'	=>	array('label' => __('Update Date', 'pz-linkcard' ) ),
		'sns_time'		=>	array('label' => __('SNS Check Date', 'pz-linkcard' ) ),
		'alive_time'	=>	array('label' => __('Alive Check Date', 'pz-linkcard' ) ),
		'post_id'		=>	array('label' => __('Post ID', 'pz-linkcard' ) ),
		'click_count'	=>	array('label' => __('Click Count', 'pz-linkcard' ) ),
		'result'		=>	array('label' => __('Result code', 'pz-linkcard' ) ),
	);
	$screen_option_defaults	=	array(
		'id'			=>	true,
		'excerpt'		=>	true,
		'charset'		=>	false,
		'domain'		=>	true,
		'sns'			=>	true,
		'regist_time'	=>	false,
		'update_time'	=>	true,
		'sns_time'		=>	false,
		'alive_time'	=>	false,
		'post_id'		=>	true,
		'click_count'	=>	true,
		'result'		=>	true,
	);
	$screen_option_saved	=	get_user_meta(get_current_user_id(), 'pz_lkc_cacheman_columns', true );
	if	(!is_array($screen_option_saved ) ) {
		$screen_option_saved	=	array();
	}
	$screen_option_is_visible = function($column_key) use ($screen_option_defaults, $screen_option_saved) {
		if	(array_key_exists($column_key, $screen_option_saved ) ) {
			return	(bool) $screen_option_saved[$column_key];
		}
		return	!empty($screen_option_defaults[$column_key] );
	};
	$screen_option_hidden_class = function($column_key) use ($screen_option_is_visible) {
		return	$screen_option_is_visible($column_key ) ? '' : ' pz-man-column-hidden';
	};
	$screen_option_per_page_choices	=	method_exists($this, 'pz_GetCachemanPerPageChoices' ) ? $this->pz_GetCachemanPerPageChoices() : array(10, 20, 50, 100);
	$screen_option_per_page			=	intval(get_user_meta(get_current_user_id(), 'pz_lkc_cacheman_per_page', true ) );
	if	(!in_array($screen_option_per_page, $screen_option_per_page_choices, true ) ) {
		$screen_option_per_page	=	10;
	}

	// 検索SQL作成
	$sql				=	"SELECT COUNT(*) FROM $this->db_name";
	if	($where ) {
		$sql			.=	" WHERE $where";
	}
	if	(strpos($sql, 'UPDATE' ) || strpos($sql, 'UNION' ) ) { // 気持ち程度のインジェクション対策
		$sql			=	null;
	}

	// データ件数
	$count_now		=	intval(!empty($param ) ? $wpdb->get_var(call_user_func_array(array($wpdb, 'prepare' ), array_merge(array($sql ), $param ) ) ) : $wpdb->get_var($sql ) );

	// ページ数
	$page_limit		=	$screen_option_per_page;																	// ページ内の行数
	$page_min		=	($count_now > 0 ? 1 : 0 );																// 最初のページ
	$page_max		=	ceil($count_now /	$page_limit );														// 最後のページ
	$page_now		=	$page_now		<	$page_min	?	$page_min		:	
						($page_now		>	$page_max	?	$page_max		:	$page_now );					// 現在のページ
	$page_prev		=	$page_now		>	$page_min	?	$page_now - 1	:	null;							// 前のページ
	$page_next		=	$page_now		<	$page_max	?	$page_now + 1	:	null;							// 次のページ
	$page_top		=	$page_now		<	1			?	0				:	($page_now - 1 ) * $page_limit;	// 表示中のページの最初に表示するのが何件目か

	// データ抽出
	$sql				=	"SELECT * FROM $this->db_name";
	if	($where ) {
		$sql			.=	" WHERE $where";
	}
	if	($orderby ) {
		$sql			.=	" ORDER BY $orderby $order";
	}
	$sql				.=	" LIMIT %d OFFSET %d";
	$data_param			=	array_merge($param, array($page_limit, $page_top ) );
	$data_now			=	$wpdb->get_results(call_user_func_array(array($wpdb, 'prepare' ), array_merge(array($sql ), $data_param ) ) );

	// 件数確認
	$sql			=	"SELECT COUNT( * ) AS count_all, ";
	$sql			.=	"COUNT( CASE WHEN domain = '" .$this->domain."' THEN 1 END ) AS count_internal, ";
	$sql			.=	"COUNT( CASE WHEN domain <> '".$this->domain."' THEN 1 END ) AS count_external, ";
	$sql			.=	"COUNT( CASE WHEN alive_result <> update_result THEN 1 END ) AS count_modify, ";
	$sql			.=	"COUNT( CASE WHEN ( alive_result < 100 OR alive_result >= 400 ) THEN 1 END ) AS count_unlink ";
	$sql			.=	"FROM $this->db_name";
	$result			=	$wpdb->get_row($sql );
	$count_list['all'	  ]	=	$result->count_all		??	0;
	$count_list['internal']	=	$result->count_internal ??	0;
	$count_list['external']	=	$result->count_external ??	0;
	$count_list['modify'  ]	=	$result->count_modify	??	0;
	$count_list['unlink'  ]	=	$result->count_unlink	??	0;

	// ページネーション
	$temp_button	=	'&nbsp;<button type="submit" name="page_button" value="%d" class="button tablenav-pages-navspan" %s>%s</button>';
	$temp_text		=	'<span class="paging-input"><input type="text" name="page_trans" value="%d" id="current-page-selector" class="pz-sync-text current-page" size="2" aria-describedby="table-paging" /><span class="total-pages">&nbsp;/&nbsp;%d</span></span>';
	$temp_text_btm	=	'<span class="paging-input">%d<span class="total-pages">&nbsp;/&nbsp;%d</span></span>';

	$paging			=
		'<div class="pz-man-pages tablenav-pages"><span class="displaying-num">'.sprintf(($count_now === 1 ? __('%s item', 'pz-linkcard' ) : __('%s items', 'pz-linkcard' ) ), number_format($count_now ) ).'</span><span class="pagination-links">'.
		sprintf($temp_button,	($page_min ),		(($page_now > $page_min ) ? '' : 'disabled="disabled"' ),	__('&laquo;', 'pz-linkcard' ) ).		// 最初のページ
		sprintf($temp_button,	($page_now - 1 ),	(($page_now > $page_min ) ? '' : 'disabled="disabled"' ),	__('&lsaquo;', 'pz-linkcard' ) ).		// 前のページ
		'&nbsp;'.
		sprintf($temp_text,		$page_now,			$page_max ).																						// 今のページ
		sprintf($temp_button,	($page_now + 1 ),	(($page_now < $page_max ) ? '' : 'disabled="disabled"' ),	__('&rsaquo;', 'pz-linkcard' ) ).		// 次のページ
		sprintf($temp_button,	($page_max ),		(($page_now < $page_max ) ? '' : 'disabled="disabled"' ),	__('&raquo;', 'pz-linkcard' ) ).		// 最後のページ
		'</span></div>';
	$paging_bottom			=
		'<div class="pz-man-pages-bottom tablenav-pages"><span class="displaying-num">'.sprintf(($count_now === 1 ? __('%s item', 'pz-linkcard' ) : __('%s items', 'pz-linkcard' ) ), number_format($count_now ) ).'</span><span class="pagination-links">'.
		sprintf($temp_button,	($page_min ),		(($page_now > $page_min ) ? '' : 'disabled="disabled"' ),	__('&laquo;', 'pz-linkcard' ) ).		// 最初のページ
		sprintf($temp_button,	($page_now - 1 ),	(($page_now > $page_min ) ? '' : 'disabled="disabled"' ),	__('&lsaquo;', 'pz-linkcard' ) ).		// 前のページ
		'&nbsp;'.
		sprintf($temp_text_btm,	$page_now,		$page_max ).																						// 今のページ
		sprintf($temp_button,	($page_now + 1 ),	(($page_now < $page_max ) ? '' : 'disabled="disabled"' ),	__('&rsaquo;', 'pz-linkcard' ) ).		// 次のページ
		sprintf($temp_button,	($page_max ),		(($page_now < $page_max ) ? '' : 'disabled="disabled"' ),	__('&raquo;', 'pz-linkcard' ) ).		// 最後のページ
		'</span></div>';
?>
	<div class="pz-man-filter-row">
	<div class="pz-man-count-list">
		<?php
			$items	=
				array(
					'all'		=>	__('All', 'pz-linkcard' ),
					'internal'	=>	__('Internal', 'pz-linkcard' ),
					'external'	=>	__('External', 'pz-linkcard' ),
					'modify'	=>	__('Modify', 'pz-linkcard' ),
					'unlink'	=>	__('Unlink', 'pz-linkcard' ),
				);
			$sep		=	'';
			foreach	($items as $i_code => $i_name ) {
				echo	$sep;
				echo	'<button type="submit" name="filter" value="'.$i_code.'" class="pz-filter-item"><span class="pz-filter-label'.($filter === $i_code ? ' pz-current' : '').'">'.$i_name.'</span><span class="pz-filter-count">'.esc_attr('('.number_format($count_list[$i_code] ).')' ).'</span></button>';
				$sep	=	' | ';
			}
		?>
	</div>

	<div class="pz-man-screen-options">
		<button type="button" id="pz-man-screen-options-toggle" class="pz-man-screen-options-toggle" aria-expanded="false" aria-controls="pz-man-screen-options-panel" data-no-overlay="1">
			<?php echo esc_html(__('Screen Options', 'pz-linkcard' ) ); ?><span class="dashicons dashicons-arrow-down-alt2"></span>
		</button>
		<div id="pz-man-screen-options-panel" class="pz-man-screen-options-panel" hidden>
			<fieldset>
				<legend><?php echo esc_html(__('Columns', 'pz-linkcard' ) ); ?></legend>
				<?php
					foreach	($screen_option_columns as $column_key => $column ) {
						$checked		=	$screen_option_is_visible($column_key) ? ' checked="checked"' : '';
						$default_class	=	!empty($screen_option_defaults[$column_key] ) ? ' class="pz-man-screen-option-default"' : '';
						echo	'<label'.wp_kses_post($default_class ).'><input type="checkbox" class="pz-man-screen-column-toggle" data-pz-man-column="'.esc_attr($column_key ).'"'.$checked.'>'.esc_html($column['label'] ).'</label>';
					}
				?>
			</fieldset>
			<fieldset class="pz-man-screen-options-pagination">
				<legend><?php echo esc_html(__('Pagination', 'pz-linkcard' ) ); ?></legend>
				<label class="pz-man-screen-option-per-page">
					<?php echo esc_html(__('Number of items per page:', 'pz-linkcard' ) ); ?>
					<select id="pz-man-screen-option-per-page">
						<?php
							foreach	($screen_option_per_page_choices as $choice ) {
								echo	'<option value="'.intval($choice ).'"'.selected($screen_option_per_page, $choice, false ).'>'.intval($choice ).'</option>';
							}
						?>
					</select>
				</label>
			</fieldset>
		</div>
	</div>
	
	<div class="pz-man-search">
		<p class="search-box" title="<?php esc_attr_e('Text or field search. Examples: post:1234, id:10, domain:popozure.info', 'pz-linkcard' ); ?>">
			<label>
				<span class="dashicons dashicons-search" style="vertical-align: text-bottom;"></span>
				<input  type="search"  id="post-search-input" name="keyword" value="<?php echo esc_attr($keyword ); ?>" />
				<button type="submit"  id="search-submit"     name="action"  value="search" class="button action"><?php esc_html_e('Search', 'pz-linkcard' ); ?></button>
			</label>
		</p>
	</div>
	</div>
	
	<div class="pz-man-navi tablenav top">
		<div class="pz-man-batch-list alignleft actions bulkactions">
			<select name="bulk_action" id="bulk-action-selector-top">
				<option value="" selected="selected"><?php esc_html_e('Bulk Actions', 'pz-linkcard' ); ?></option>
				<option value="renew"><?php esc_html_e('Renew Cache', 'pz-linkcard' ); ?></option>
				<option value="renew_thumbnail"><?php esc_html_e('Renew Thumbnail Image', 'pz-linkcard' ); ?></option>
				<option value="renew_sns"><?php esc_html_e('Renew SNS Count', 'pz-linkcard' ); ?></option>
				<option value="renew_postid"><?php esc_html_e('Renew Post ID', 'pz-linkcard' ); ?></option>
				<option value="alive"><?php esc_html_e('Check Status', 'pz-linkcard' ); ?></option>
				<option value="delete"><?php esc_html_e('Delete from Cache', 'pz-linkcard' ); ?></option>
			</select>
			<button type="submit" name="action" value="exec-batch" class="button action" onclick="return confirm('<?php echo esc_js(__('Are you sure?', 'pz-linkcard' ) ); ?>' );"><?php esc_html_e('Apply', 'pz-linkcard' ); ?></button>
			&nbsp;
		</div>
		
		<div class="pz-man-domain-list alignleft actions bulkactions">
			<select name="refine" id="bulk-action-selector-top">
				<option value="" selected="selected"><?php esc_html_e('All Domains', 'pz-linkcard' ); ?></option>
					<?php
						foreach	($domain_list as $rec ) {
							if (isset($rec['domain'] ) === true && isset($rec['count'] ) === true) {
								$disp_domain	=	(function_exists('idn_to_utf8' ) && defined('INTL_IDNA_VARIANT_UTS46' ) && mb_substr($rec['domain'], 0, 4) === 'xn--') ? idn_to_utf8($rec['domain'], 0, INTL_IDNA_VARIANT_UTS46 ) : $rec['domain'] ;
								$selected		=	($rec['domain'] === $refine) ? ' selected="selected"' : null ;
								echo	'<option value="'.htmlspecialchars($rec['domain'] ).'"'.$selected.'>'.htmlspecialchars($disp_domain ).' ('.$rec['count'].')</option>';
							}
						}
					?>
				</select>
			<button type="submit" name="action" value="select-domain" class="button action"><?php esc_html_e('Refine Search', 'pz-linkcard' ); ?></button>
		</div>
		<?php /* ページネーション */ echo $paging; ?>
		<br class="clear">
	</div>

	<table class="pz-man-cache-list widefat striped">
		<thead>
			<tr>
				<td id="cb" class="pz-man-head-check manage-column column-cb check-column"><input id="cb-select-all-1" type="checkbox" /></td>
<?php
	$sort_none	=	'<img class="pz-man-head-orderby" src="'.esc_url($this->plugin_dir_url.'img/sort_none.svg' ).'" alt="" width="16" height="16" />';
	$sort_asc	=	'<img class="pz-man-head-orderby" src="'.esc_url($this->plugin_dir_url.'img/sort_asc.svg'  ).'" alt="" width="16" height="16" />';
	$sort_desc	=	'<img class="pz-man-head-orderby" src="'.esc_url($this->plugin_dir_url.'img/sort_desc.svg' ).'" alt="" width="16" height="16" />';
	$get_sort_icon = function($item) use ($orderby, $order, $sort_none, $sort_asc, $sort_desc) {
		return	($orderby === $item ? ($order === 'desc' ? $sort_desc : $sort_asc ) : $sort_none );
	};

	$item		=	'id';
	$item_name	=	__('ID', 'pz-linkcard' );
	$add_class	=	$screen_option_hidden_class('id');
	$sort		=	$get_sort_icon($item);
	echo	'<th scope="col" class="pz-man-head-'.$item.$add_class.'"><button type="submit" name="header" value="'.$item.'">'.$item_name.$sort.'</button></th>';

	$item		=	'url';
	$item_name	=	__('URL', 'pz-linkcard' );
	$add_class	=	'';
	$sort		=	$get_sort_icon($item);
	echo	'<th scope="col" class="pz-man-head-'.$item.$add_class.'"><button type="submit" name="header" value="'.$item.'">'.$item_name.$sort.'</button></th>';

	$item		=	'title';
	$item_name	=	__('Title', 'pz-linkcard' );
	$add_class	=	'';
	$sort		=	$get_sort_icon($item);
	echo	'<th scope="col" class="pz-man-head-'.$item.$add_class.'"><button type="submit" name="header" value="'.$item.'">'.$item_name.$sort.'</button></th>';

	$item		=	'excerpt';
	$item_name	=	__('Excerpt', 'pz-linkcard' );
	$add_class	=	$screen_option_hidden_class('excerpt');
	$sort		=	$get_sort_icon($item);
	echo	'<th scope="col" class="pz-man-head-'.$item.$add_class.'"><button type="submit" name="header" value="'.$item.'">'.$item_name.$sort.'</button></th>';

	$item		=	'charset';
	$item_name	=	__('Charset', 'pz-linkcard' );
	$add_class	=	$screen_option_hidden_class('charset');
	$sort		=	$get_sort_icon($item);
	echo	'<th scope="col" class="pz-man-head-'.$item.$add_class.'"><button type="submit" name="header" value="'.$item.'">'.$item_name.$sort.'</button></th>';

	$item		=	'domain';
	$item_name	=	__('Domain', 'pz-linkcard' );
	$add_class	=	$screen_option_hidden_class('domain');
	$sort		=	$get_sort_icon($item);
	echo	'<th scope="col" class="pz-man-head-'.$item.$add_class.'"><button type="submit" name="header" value="'.$item.'">'.$item_name.$sort.'</button></th>';

	$item		=	'sns_twitter';
	$item_name	=	__('Tw', 'pz-linkcard' );
	$add_class	=	$screen_option_hidden_class('sns');
	$sort		=	$get_sort_icon($item);
	echo	'<th scope="col" class="pz-man-head-'.$item.$add_class.'">';
	echo	'<button type="submit" name="header" value="'.$item.'">'.$item_name.$sort.'</button>';
	// echo	'</th>';
	echo	'<br>';
	$item		=	'sns_facebook';
	$item_name	=	__('fb', 'pz-linkcard' );
	$add_class	=	'';
	$sort		=	$get_sort_icon($item);
	// echo	'<th scope="col" class="pz-man-head-'.$item.$add_class.'">';
	echo	'<button type="submit" name="header" value="'.$item.'">'.$item_name.$sort.'</button>';
	// echo	'</th>';
	echo	'<br>';
	$item		=	'sns_hatena';
	$item_name	=	__('B!', 'pz-linkcard' );
	$add_class	=	'';
	$sort		=	$get_sort_icon($item);
	// echo	'<th scope="col" class="pz-man-head-'.$item.$add_class.'">';
	echo	'<button type="submit" name="header" value="'.$item.'">'.$item_name.$sort.'</button>';
	echo	'</th>';

	$item		=	'regist_time';
	$item_name	=	__('Registered<br>Date', 'pz-linkcard' );
	$add_class	=	$screen_option_hidden_class('regist_time');
	$sort		=	$get_sort_icon($item);
	echo	'<th scope="col" class="pz-man-head-'.$item.$add_class.'"><button type="submit" name="header" value="'.$item.'">'.$item_name.$sort.'</button></th>';

	$item		=	'update_time';
	$item_name	=	__('Update<br>Date', 'pz-linkcard' );
	$add_class	=	$screen_option_hidden_class('update_time');
	$sort		=	$get_sort_icon($item);
	echo	'<th scope="col" class="pz-man-head-'.$item.$add_class.'"><button type="submit" name="header" value="'.$item.'">'.$item_name.$sort.'</button></th>';

	$item		=	'sns_time';
	$item_name	=	__('SNS<br>Check<br>Date', 'pz-linkcard' );
	$add_class	=	$screen_option_hidden_class('sns_time');
	$sort		=	$get_sort_icon($item);
	echo	'<th scope="col" class="pz-man-head-'.$item.$add_class.'"><button type="submit" name="header" value="'.$item.'">'.$item_name.$sort.'</button></th>';

	$item		=	'alive_time';
	$item_name	=	__('Alive<br>Check<br>Date', 'pz-linkcard' );
	$add_class	=	$screen_option_hidden_class('alive_time');
	$sort		=	$get_sort_icon($item);
	echo	'<th scope="col" class="pz-man-head-'.$item.$add_class.'"><button type="submit" name="header" value="'.$item.'">'.$item_name.$sort.'</button></th>';

	$item		=	'use_post_id1';
	$item_name	=	__('Post<br>ID', 'pz-linkcard' );
	$add_class	=	$screen_option_hidden_class('post_id');
	$sort		=	$get_sort_icon($item);
	echo	'<th scope="col" class="pz-man-head-'.$item.$add_class.'"><button type="submit" name="header" value="'.$item.'">'.$item_name.$sort.'</button></th>';

	$item		=	'click_count';
	$item_name	=	__('Click<br>Count', 'pz-linkcard' );
	$add_class	=	$screen_option_hidden_class('click_count');
	$sort		=	$get_sort_icon($item);
	echo	'<th scope="col" class="pz-man-head-'.$item.$add_class.'"><button type="submit" name="header" value="'.$item.'">'.$item_name.$sort.'</button></th>';

	$item		=	'update_result';
	$item_name	=	__('Result<br>code', 'pz-linkcard' );
	$add_class	=	$screen_option_hidden_class('result');
	$sort		=	$get_sort_icon($item);
	echo	'<th scope="col" class="pz-man-head-'.$item.$add_class.'">';
	echo	'<button type="submit" name="header" value="'.$item.'">'.$item_name.$sort.'</button>';
	// echo	'</th>';
	echo	'<br>';
	$item		=	'alive_result';
	$item_name	=	__('(Last)', 'pz-linkcard' );
	$add_class	=	'';
	$sort		=	$get_sort_icon($item);
	// echo	'<th scope="col" class="pz-man-head-'.$item.$add_class.'">';
	echo	'<button type="submit" name="header" value="'.$item.'">'.$item_name.$sort.'</button>';
	echo	'</th>';
?>
			</tr> 
		</thead>
		<tbody>
			<?php
				foreach	($data_now as $data ) {

					// データID
					$data_id	=	$data->id;

					// URL
					$url		=	$data->url;

					// URL解析（自サイトチェック）
					$url_info		=	$this->Pz_GetURLInfo($url );
					$scheme			=	$url_info['scheme'];		// スキーム
					$domain			=	$url_info['domain'];		// ドメイン名
					$domain_url		=	$url_info['domain_url'];	// ドメインURL
					$is_external	=	$url_info['is_external'];	// 外部リンク
					$is_internal	=	$url_info['is_internal'];	// 内部リンク

					// URLの警告マーク
					$html_url_error		=	'';
					if	($data->alive_result < 100 || $data->alive_result >= 400 || $data->update_result < 100 || $data->update_result >= 400 ) {
						if	($data->no_failure ) {		// エラー無視が有効か
							$temp_icon	=	__('&#x26a0;&#xfe0f;', 'pz-linkcard' );		// ⚠️
							$temp_class	=	'pz-man-body-url-error-ignore';				// エラー無視
							$temp_title	=	__('The latest HTTP code indicates an error, but it is ignored.', 'pz-linkcard' );
						} else {
							$temp_icon	=	__('&#x26d4;&#xfe0f;', 'pz-linkcard' );		// ⛔️エラー
							$temp_class	=	'pz-man-body-url-error';					// エラー
							$temp_title	=	__('The latest HTTP code indicates an error. You can ignore the error from the edit screen.', 'pz-linkcard' );
						}
						$html_url_error	=	'<span class="'.$temp_class.'" title="'.$temp_title.'">'.$temp_icon.'</span>';
					}

					// 表示用のURL
					if	($is_internal ) {
						$temp_href		=	esc_url($url );
						$temp_rel		=	'internal noopener';
						$temp_target	=	'_self';
					} else {
						$temp_href		=	esc_url($url );
						$temp_rel		=	'external noopener';
						$temp_target	=	'_blank';
					}
					$html_url			=	$html_url_error.'<a href="'.$temp_href.'" title="'.$temp_href.'" rel="'.$temp_rel.'" target="'.$temp_target.'" referrerpolicy="no-referrer">'.esc_url($this->pz_DecodeURL($url ) ).'</a>';

					// タイトル
					$title				=	esc_attr(stripslashes($data->title ) );		// 代入しながら判定
					$html_title			=	mb_strimwidth($title, 0, 200 , '...' );		// 200文字にする
					if	($data->title	<>	$data->regist_title ) {
						$html_title		=	'<b>'.$html_title.'</b>';
					}

					// 抜粋文
					if			($str	=	$data->excerpt ) {						// 代入しながら判定
						if		($str	=	strip_tags($str ) ) {					// HTMLタグ除去
							if	($str	=	esc_html($str ) ) {						// HTMLエスケープ
								$str	=	mb_strimwidth($str, 0, 500 , '...' );	// 500文字にする
							}
						}
					}
					$excerpt			=	$str;
					$html_excerpt		=	$str;
					if	($data->excerpt	<>	$data->regist_excerpt ) {
						$html_excerpt	=	'<b>'.$html_excerpt.'</b>';
					}

					// SNSカウント
					$html_sns	=	sns_counter($data->sns_twitter  ).'<br>';
					$html_sns	.=	sns_counter($data->sns_facebook ).'<br>';
					$html_sns	.=	sns_counter($data->sns_hatena   ).'<br>';

					// サムネイル
					$thumbnail_url				=	null;
					$html_thumbnail				=	null;
					if	($domain === $this->domain ) {
						$post_id				=	url_to_postid($data->url );											// 記事IDを取得
						$thumbnail_id			=	get_post_thumbnail_id($post_id );									// サムネイルIDを取得
						if	($thumbnail_id ) {
							$thumbnail_size		=	$this->options['in-thumbnail-size'] ? $this->options['in-thumbnail-size'] : 'thumbnail' ;
							$attach				=	wp_get_attachment_image_src($thumbnail_id, $thumbnail_size, true );	// サムネイルを取得
							if	(isset($attach ) && count($attach ) > 3 && isset($attach[0]) ) {
								$thumbnail_url		=	$attach[0];
								if	(preg_match('/.*(\/\/.*)/', $thumbnail_url, $m ) ) {		// スキームを外す
									$thumbnail_url	=	$m[1];
								}
							}
						}
					} else {
						if	($data->thumbnail ) {
							$thumbnail_url		=	$this->pz_GetImage($data->thumbnail );
						}
					}
					if	($thumbnail_url ) {
						$html_thumbnail			=	'<a href="'.esc_url($thumbnail_url ).'" target="_blank" rel="noopener" referrerpolicy="no-referrer" class="pz-man-thumbnail"><div><img src="'.esc_url($thumbnail_url ).'" alt="" class="pz-man-thumbnail-img"></div></a>';
					}

					// 記事ID
					$html_post_id		=	null;
					for	($j = 1; $j < 5; $j++ ) {
						$use_post_id	=	'use_post_id'.$j;
						$post_id		=	$data->$use_post_id;
						if	($post_id > 0 ) {
							$html_post_id	.=	'<a href="'.esc_url(get_permalink($post_id ) ).'" target="_blank" rel="noopener" referrerpolicy="no-referrer" title="'.esc_attr(get_the_title($post_id ) ).'">'.intval($post_id ).'</a><br>';
						}
					}

					// クリック数
					$html_click			=	number_format(intval($data->click_count ), 0  );

					// HTTPレスポンス
					$html_result		=	'<span class="pz-man-body-result-update">'.strHTTPCode($data->update_result, $this->pz_HTTPMessage($data->update_result ) ).'</span>';
					if	($data->update_result <> $data->alive_result ) {
						$html_result	.=	'<br><span class="pz-man-body-result-alive">('.strHTTPCode($data->alive_result, $this->pz_HTTPMessage($data->alive_result ) ).')</span>';
					}
					if	($data->no_failure ) {
						$html_result	=	'<span class="pz-man-body-result-ignore">'.__('Ignore', 'pz-linkcard' ).'</span><br>'.$html_result;
					}

					// HTML 明細行
			?>
			<tr>
				<th scope="row" class="pz-man-body-check check-column"><input id="cb-select-<?php echo intval($data_id ); ?>" type="checkbox" name="select_id[]" value="<?php echo intval($data_id ); ?>" /><div class="locked-indicator"></div></th>
				<td class="pz-man-body-id<?php echo esc_attr($screen_option_hidden_class('id') ); ?>"><button type="button" data-pz-man-search-id="<?php echo intval($data_id ); ?>" class="pz-man-inline-menu pz-man-id-search"><?php echo intval($data_id ); ?></button><?php echo $html_thumbnail; ?></td>
				<td colspan="2" class="pz-man-body-url-title-cell">
					<div class="pz-man-body-url"><?php echo $html_url; ?></div>
					<div class="pz-man-body-title"><span title="<?php echo esc_attr($title ); ?>"><?php echo $html_title; ?></span></div>
					<div id="inline_<?php echo intval($data_id ); ?>" class="pz-man-body-menu row-actions">
						<button type="submit" name="single-edit"   value="<?php echo intval($data_id ); ?>" class="pz-man-inline-menu"><?php esc_html_e('Edit','pz-linkcard' ); ?></button> | 
						<button type="submit" name="single-renew"  value="<?php echo intval($data_id ); ?>" class="pz-man-inline-menu" onclick="return confirm('<?php echo esc_js(__('Are you sure?', 'pz-linkcard' ) ); ?>' );"><?php esc_html_e('Renew','pz-linkcard' ); ?></button> | 
						<button type="submit" name="single-delete" value="<?php echo intval($data_id ); ?>" class="pz-man-inline-menu" onclick="return confirm('<?php echo esc_js(__('Are you sure?', 'pz-linkcard' ) ); ?>' );"><?php esc_html_e('Delete','pz-linkcard' ); ?></button>
					</div>
				</td>
				<td class="pz-man-body-excerpt-cell<?php echo esc_attr($screen_option_hidden_class('excerpt') ); ?>"><div class="pz-man-body-excerpt" title="<?php echo esc_attr($excerpt); ?>"><?php echo $html_excerpt; ?></div></td>
				<td class="pz-man-body-charset<?php echo esc_attr($screen_option_hidden_class('charset') ); ?>"><?php echo esc_html($data->charset ); ?></td>
				<td class="pz-man-body-domain-cell<?php echo esc_attr($screen_option_hidden_class('domain') ); ?>">
					<div class="pz-man-body-domain">
						<?php
							$disp_domain	=	(function_exists('idn_to_utf8' ) && defined('INTL_IDNA_VARIANT_UTS46' ) && mb_substr($domain, 0, 4) === 'xn--') ? idn_to_utf8($domain, 0, INTL_IDNA_VARIANT_UTS46 ) : $domain ;
							$disp_sitename	=	$data->site_name;
							$siteicon_url	=	isset($data->favicon ) && $data->favicon ? $this->pz_GetImage($data->favicon ) : null;
							$html_siteicon	=	$siteicon_url ? '<img src="'.esc_url($siteicon_url ).'" alt="" width="14" height="14" class="pz-man-body-siteicon" />' : '';
						?>
						<span class="pz-man-body-domain-line" title="<?php echo esc_attr($disp_domain ); ?>"><?php echo $html_siteicon; ?><?php echo esc_html($disp_domain ); ?></span><br>
						<span class="pz-man-body-sitename" title="<?php echo esc_attr($disp_sitename ); ?>"><?php echo esc_html($disp_sitename ); ?></span>
					</div>
				</td>
				<td class="pz-man-body-sns<?php echo esc_attr($screen_option_hidden_class('sns') ); ?>"><?php echo $html_sns; ?></td>
				<td class="pz-man-body-resist-time<?php echo esc_attr($screen_option_hidden_class('regist_time') ); ?>"><?php $dt=$data->regist_time; ?><span title="<?php echo esc_attr(date(PZLKC_DATETIME_FORMAT, $dt ) ); ?>"><?php echo $this->pz_Date($this->options['date-format-man'], $dt ); ?></span></td>
				<td class="pz-man-body-update-time<?php echo esc_attr($screen_option_hidden_class('update_time') ); ?>"><?php $dt=$data->update_time; ?><span title="<?php echo esc_attr(date(PZLKC_DATETIME_FORMAT, $dt ) ); ?>"><?php echo $this->pz_Date($this->options['date-format-man'], $dt ); ?></span></td>
				<td class="pz-man-body-sns-time<?php echo esc_attr($screen_option_hidden_class('sns_time') ); ?>"><?php $dt=$data->sns_time; ?><span title="<?php echo esc_attr(date(PZLKC_DATETIME_FORMAT, $dt ) ); ?>"><?php echo $this->pz_Date($this->options['date-format-man'], $dt ); ?></span></td>
				<td class="pz-man-body-alive-time<?php echo esc_attr($screen_option_hidden_class('alive_time') ); ?>"><?php $dt=$data->alive_time; ?><span title="<?php echo esc_attr(date(PZLKC_DATETIME_FORMAT, $dt ) ); ?>"><?php echo $this->pz_Date($this->options['date-format-man'], $dt ); ?></span></td>
				<td class="pz-man-body-post-id<?php echo esc_attr($screen_option_hidden_class('post_id') ); ?>"><?php echo $html_post_id; ?></td>
				<td class="pz-man-body-click-count<?php echo esc_attr($screen_option_hidden_class('click_count') ); ?>"><?php echo $html_click; ?></td>
				<td class="pz-man-body-result<?php echo esc_attr($screen_option_hidden_class('result') ); ?>"><?php echo $html_result; ?></td>
			</tr>
			<?php } ?>
		</tbody>
	</table>
	<?php /* ページネーション */ echo $paging_bottom; ?></div>
<?php
// 関数

// HTTP結果コード
function strHTTPCode($result, $message ) {
	if	($message ) {
		$message	=	' title="'.esc_attr($message ).'"';
	}
	$result	=	intval($result );
	if	(($result === 0 ) || ($result >= 100 && $result <= 399 ) ) {
		return	'<span class="pz-http-ok"'.$message.'>'.$result.'</span>';
	}
	return		'<span class="pz-http-error"'.$message.'">'.$result.'</span>';
}

// SNSカウントの表示（kilo → k , million → m）
function sns_counter($count ) {
	$count		=	intval($count );
	if	($count < 0) {
		return	'-';
	}
	if			($count >= 10000000 ) {
		return	number_format($count / 1000000 ).'&nbsp;m';
	} elseif	($count >= 1000 ) {
		return	number_format($count / 1000 ).'&nbsp;k';
	} else {
		return	number_format($count );
	}
}
