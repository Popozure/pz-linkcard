<?php defined('ABSPATH' ) || wp_die; ?>
<?php
	// DB使用
	global		$wpdb;

	// DBテーブル存在チェック
	$exists_table = $wpdb->get_var( $wpdb->prepare( "SHOW TABLES LIKE %s", $this->db_name ) );
	if ($exists_table <> $this->db_name ) {
		$this->hook_activate();
	}

	// デバグモード・管理モード
	$debug_mode		=	isset($this->options['debug-mode'] )	?	intval($this->options['debug-mode'] )	:	0;
	$admin_mode		=	isset($this->options['admin-mode'] )	?	intval($this->options['admin-mode'] )	:	0;
	$develop_mode	=	isset($this->options['develop-mode'] )	?	intval($this->options['develop-mode'] )	:	0;
	$inhibit		=	isset($this->options['flg-inhibit'] )	?	intval($this->options['flg-inhibit'] )	:	0;

	// 引数・変数の設定
	$page			=	'pz-linkcard-cacheman';			// ツール画面のページ
	if	(isset($_SERVER['REQUEST_METHOD'] ) && $_SERVER['REQUEST_METHOD'] === 'POST' ) {
		check_admin_referer('pz-cacheman' );
	}
	$action			=	isset($_POST['action'] )		?	esc_attr($_POST['action'] )					:	null;
	$select_id		=	isset($_POST['select_id'] )		?	$_POST['select_id']							:	null;
	$bulk_action	=	isset($_POST['bulk_action'] )	?	esc_attr($_POST['bulk_action'] )			:	null;
	$data			=	(isset($_POST['data'] ) && is_array($_POST['data'] ) )		?	$_POST['data']	:	null;
	$param_refine	=	isset($_POST['refine'] )		?	esc_attr($_POST['refine'] )					:	null;
	$keyword		=	isset($_POST['keyword'] )		?	esc_attr(stripslashes($_POST['keyword'] ) )	:	null;
	$filter			=	isset($_POST['filter'] )		?	esc_attr($_POST['filter'] )					:	'all';
	$header			=	isset($_POST['header'] )		?	esc_attr(strtolower($_POST['header'] ) )	:	null;
	$orderby		=	isset($_POST['orderby'] )		?	esc_attr(strtolower($_POST['orderby'] ) )	:	'id';
	$order			=	isset($_POST['order'] )			?	esc_attr(strtolower($_POST['order'] ) )		:	'desc';
	$scroll_now		=	isset($_POST['scroll_now'] )	?	intval($_POST['scroll_now'] )				:	0;
	$page_now		=	(isset($_POST['page_button'] )	?	intval($_POST['page_button'] )				:	
						(isset($_POST['page_trans'] )	?	intval($_POST['page_trans'] )				:	
						(isset($_POST['page_now'] )		?	intval($_POST['page_now'] )					:	0 ) ) );
	$param_url		=	isset($_POST['url'] )			?	esc_attr($_POST['url'] )					:	null;
	$cache_id		=	isset($_POST['cache_id'] )		?	esc_attr($_POST['cache_id'] )				:	null;
	$confirm		=	isset($_POST['confirm'] )		?	esc_attr($_POST['confirm'] )				:	null;
	$update_result	=	isset($_POST['update_result'] )	?	esc_attr($_POST['update_result'] )			:	null;
	$alive_result	=	isset($_POST['alive_result'] )	?	esc_attr($_POST['alive_result'] )			:	null;

	// インラインメニュー
	$single_edit	=	isset($_POST['single-edit'] )	?	esc_attr($_POST['single-edit'] )			:	null;
	$single_renew	=	isset($_POST['single-renew'] )	?	esc_attr($_POST['single-renew'] )			:	null;
	$single_delete	=	isset($_POST['single-delete'] )	?	esc_attr($_POST['single-delete'] )			:	null;
	if			($single_edit ) {
		$action		=	'edit';
		$select_id	=	array(intval($single_edit ) );
	} elseif	($single_renew ) {
		$action		=	'renew';
		$select_id	=	array(intval($single_renew ) );
	} elseif	($single_delete ) {
		$action		=	'delete';
		$select_id	=	array(intval($single_delete ) );
	}

	// 表示されている項目名がクリックされたら並び順を逆にする
	if	($header ) {
		if	($orderby	===	$header ) {
			$order		=	($order	=== 'desc') ? 'asc' : 'desc' ;
		} else {
			$orderby	=	$header;
			$order		=	'desc';
		}
	}

	// インラインメニュー（編集・再取得・削除）
	if		 (isset($_POST['single-edit'] ) ) {
		$action		=	'edit';
		$select_id	=	array( intval($_POST['single-edit'] ) );
	} elseif (isset($_POST['single-renew'] ) ) {
		$action		=	'renew';
		$select_id	=	array(intval($_POST['single-renew'] ) );
	} elseif (isset($_POST['single-delete'] ) ) {
		$action		=	'delete';
		$select_id	=	array(intval($_POST['single-delete'] ) );
	}

	// 処理する連番
	if	(!is_array($select_id ) ) {
		$select_id	=	$select_id	?	array($select_id ) : null ;
	} else {
		foreach	($select_id			as	$key => $value ) {
			$select_id[$key]		=	intval($value );
		}
	}

	// バッチ処理
	if	($action === 'exec-batch' ) {
		$action			=	$bulk_action;
	}

	// 出力するHTML
	$html_style		=	'';
	$html_plugin	=	'';
	$html_title		=	'';
	$html_input		=	'';
	$html_notice	=	'';

	// リスト表示の有無
	$show_list		=	true;

	// プラグイン名・バージョン・環境表示
	$html_mode		=	($debug_mode			?	'<span class="pz-infobar-env pz-infobar-env-debug">'.__('Debug Mode', 'pz-linkcard' ).'</span>'				:	'' ).
			($develop_mode	==	1	?	'<span class="pz-infobar-env pz-infobar-env-develop">'.__('Development Environment', 'pz-linkcard' ).'</span>'	:	'' ).
			($develop_mode	==	2	?	'<span class="pz-infobar-env pz-infobar-env-product">'.__('Production Environment', 'pz-linkcard' ).'</span>'	:	'' );

	// ページの見出し表示（設定）
	$page_class	=	' pz-cacheman';
	$switch_link	=	esc_url($this->settings_url );
	$switch_icon	=	'<span class="dashicons dashicons-admin-generic" style="vertical-align: text-bottom;"></span>';
	$switch_label	=	__('Settings', 'pz-linkcard' );
	$html_filemenu	=	'<form method="post" class="pz-infobar-filemenu">'.wp_nonce_field('pz-cacheman', '_wpnonce', false, false ).
			'<button type="submit" name="action" value="show-import" class="pz-man-infobar-filemenu-button" data-no-overlay="1"><span class="dashicons dashicons-download"></span><span>'.esc_html__('Import', 'pz-linkcard' ).'</span></button>'.
			'<button type="submit" name="action" value="show-export" class="pz-man-infobar-filemenu-button" data-no-overlay="1"><span class="dashicons dashicons-upload"></span><span>'.esc_html__('Export', 'pz-linkcard' ).'</span></button></form>';
	$html_plugin	=	'<div id="pz-infobar"><div class="pz-infobar-left"><a href="'.esc_url($this->cacheman_url ).'" class="pz-infobar-plugin-logo"><img src="'.esc_url($this->plugin_dir_url.'img/pz-linkcard_logo.svg' ).'" width="156px" height="28px" alt="'.esc_attr(self::PLUGIN_NAME ).'"></a><span class="pz-infobar-plugin-ver pz-monospace">ver.'.esc_html(PZLKC_PLUGIN_VERSION ).'</span>'.$html_mode.'</div><div class="pz-infobar-right">'.$html_filemenu.'<a href="'.$switch_link.'" class="pz-infobar-switch" title="'.esc_attr($switch_label ).'"><span class="pz-infobar-switch-icon">'.$switch_icon.'</span><span class="pz-infobar-switch-label">'.$switch_label.'</span></a></div></div>';
	$title_icon		=	'<span class="dashicons dashicons-archive" style="vertical-align: bottom; width: 32px; height: 32px; font-size: 32px;"></span>';
	$title_label	=	__('Pz-LinkCard Manager', 'pz-linkcard' );
	$help_page		=	self::AUTHOR_URL.'/pz-linkcard-manager';
	$html_title		=	'<div class="pz-header"><h1><span class="pz-header-title"><span class="pz-header-title-icon">'.$title_icon.'</span><span class="pz-header-title-text">'.$title_label.'</span><a class="pz-help-icon" href="'.$help_page.'" rel="external noopener help" target="_blank"><img src="'.$this->plugin_dir_url.'img/help.png" width="16" height="16" title="'.__('Help', 'pz-linkcard' ).'" alt="help" /></a></span></h1></div>';

	// POSTする値 INPUT要素
	$temp_param		=
		array(
			'page'				=>		$page,
			'page_now'			=>		intval($page_now ),
			'scroll_now'		=>		$scroll_now,
			'refine'			=>		$param_refine,
			'filter'			=>		$filter,
			'orderby'			=>		$orderby,
			'order'				=>		$order,
			'debug-mode'		=>		$debug_mode,
			'admin-mode'		=>		$admin_mode,
			'develop-mode'		=>		$develop_mode,
			'flg-inhibit'		=>		$inhibit,
		);
	foreach		($temp_param		as	$temp_name => $temp_value ) {
		$html_input	.=	'<input type="hidden" name="'.$temp_name.'" value="'.$temp_value.'" title="'.$temp_name.'" size="4" />';
	}

	// モードによって表示させる
	$html_style		.=	$debug_mode		==	0	?	'.pz-debug-only { display: none; } '	:	'';
	$html_style		.=	$admin_mode		==	0	?	'.pz-admin-only { display: none; } '	:	'';
	$html_style		.=	$develop_mode	==	0	?	'.pz-develop-only { display: none; } '	:	'';
	if	($html_style ) {
		$html_style	=	'<style>'.$html_style.'</style>';
	}

	// 画面描画
	if	($inhibit ) {
		echo	'<div id="pz-overlay-proc" style="display: flex;"><div class="pz-loader"></div></div>';
	} else {
		echo	'<div id="pz-overlay-proc" style="display: none;"><div class="pz-loader"></div></div>';
	}
	echo	'<div class="pz-dashboard'.$page_class.' wrap">';
	echo	$html_style;
	$infobar_allowed_html	=	wp_kses_allowed_html('post' );
	$infobar_allowed_html['form']	=	array('method' => true, 'class' => true );
	$infobar_allowed_html['input']	=	array('type' => true, 'name' => true, 'value' => true );
	$infobar_allowed_html['button']	=	array('type' => true, 'name' => true, 'value' => true, 'class' => true, 'data-no-overlay' => true );
	echo	wp_kses($html_plugin, $infobar_allowed_html );
	echo	wp_kses_post($html_title );

	echo	'<form action="" method="post" enctype="multipart/form-data">';
	wp_nonce_field('pz-cacheman' );			// nonce

	// 記述エラー
	if	($this->options['error-mode'] ) {
		if	(!$this->options['error-mode-hide'] ) {
			$error_url		=	$this->options['error-url'];
			$html_notice	.=	'<div class="notice notice-error is-dismissible pz-lkc-error-mode-notice"><p><strong>'.self::PLUGIN_NAME.': '.__('Invalid URL parameter in ', 'pz-linkcard' ).'<a href="'.esc_url($error_url ).'#lkc-error" target="_blank">'.esc_html($error_url ).'</a></strong><br>'.__('*', 'pz-linkcard' ).' '.__('You can dismiss this message from <a href="./options-general.php?page=pz-linkcard-settings">the settings screen</a>.', 'pz-linkcard' ).'</p></div>';
		}
	}

	// アクションの指示があったとき
	if	($action ) {
		switch	($action ) {
		case	'jump-page':				// ページ数
			$page_now				=	(isset($_POST['page_now'] ) ? intval($_POST['page_now'] ) : 1 );
			break;

		case	'search':					// 検索ボタン
			break;
		
		case	'select-domain':			// 絞り込み検索
			break;
		
		case	'cancel':					// 編集画面キャンセル
			break;

		case	'edit':						// 編集画面
			$data					=	$this->pz_GetCache(array('id' => $select_id[0] ) );
			if	(isset($data ) && is_array($data ) ) {
				require_once ('pz-linkcard-cacheman-edit.php');
			}
			$show_list				=	false;		// リストを表示しない
			break;

		case	'update':
			$success_count			=	0;
			$skip_count				=	0;
			if	(!isset($data ) || !is_array($data ) || !isset($data['id'] ) ) {
				$html_notice		.=	'<div class="notice notice-info is-dismissible"><p><strong>'.__('Not selected', 'pz-linkcard' ).'</strong></p></div>';
				break;
			}
			foreach	($data			as	$key => $value ) {
				$data[$key]			=	stripslashes($value );
			}
			$data['mod_title']		=	($data['title']   <> $data['regist_title'] );
			$data['mod_excerpt']	=	($data['excerpt'] <> $data['regist_excerpt'] );
			$data	=	$this->pz_SetCache($data );
			if	(isset($data ) && is_array($data ) && isset($data['id'] ) ) {
				$success_count++;
			}
			$html_notice			.=	'<div class="notice '.($success_count ? 'notice-success' : 'notice-error' ).' is-dismissible"><p><strong>'.__('Update Cache', 'pz-linkcard' ).__('...', 'pz-linkcard' ).__('(', 'pz-linkcard' ).__('Success:', 'pz-linkcard' ).$success_count.' '.__('Skip:', 'pz-linkcard' ).$skip_count.__(')', 'pz-linkcard' ).'</strong></p></div>';
			break;

		case	'renew':					// 記事内容の再取得
			$success_count			=	0;
			$skip_count				=	0;
			if	(!isset($select_id ) || !is_array($select_id ) ) {
				$html_notice		.=	'<div class="notice notice-info is-dismissible"><p><strong>'.__('Not selected', 'pz-linkcard' ).'</strong></p></div>';
				break;
			}
			foreach	($select_id as $data_id ) {
				$data		= $this->pz_GetCache(array('id' => $data_id ) );
				if	(isset($data ) && is_array($data ) ) {
					$data			=	$this->pz_GetHTML( array('url' => $data['url'], 'force' => true ) );
					$data			=	$this->pz_SetCache( $data );
					$success_count++;
				} else {
					$skip_count++;
				}
			}
			$html_notice			.=	'<div class="notice '.($success_count ? 'notice-success' : 'notice-error' ).' is-dismissible"><p><strong>'.__('Renew Cache', 'pz-linkcard' ).__('...', 'pz-linkcard' ).__('(', 'pz-linkcard' ).__('Success:', 'pz-linkcard' ).$success_count.' '.__('Skip:', 'pz-linkcard' ).$skip_count.__(')', 'pz-linkcard' ).'</strong></p></div>';
			break;

		case	'renew_thumbnail':			// サムネイルの再取得
			$success_count			=	0;
			$skip_count				=	0;
			if	(!isset($select_id ) || !is_array($select_id ) ) {
				$html_notice		.=	'<div class="notice notice-info is-dismissible"><p><strong>'.__('Not selected', 'pz-linkcard' ).'</strong></p></div>';
				break;
			}
			$success_count			=	0;
			$skip_count				=	0;
			foreach	($select_id as $data_id ) {
				$data				=	$this->pz_GetCache(array('id' => $data_id ) );
				if	(isset($data ) && is_array($data ) ) {
					$data			=	$this->pz_GetImage($data['thumbnail'] , true );
					$success_count++;
				} else {
					$skip_count++;
				}
				$html_notice		.=	'..';
			}
			$html_notice			.=	'<div class="notice '.($success_count ? 'notice-success' : 'notice-error' ).' is-dismissible"><p><strong>'.__('Renew Thumbnail Image', 'pz-linkcard' ).__('...', 'pz-linkcard' ).__('(', 'pz-linkcard' ).__('Success:', 'pz-linkcard' ).$success_count.' '.__('Skip:', 'pz-linkcard' ).$skip_count.__(')', 'pz-linkcard' ).'</strong></p></div>';
			break;

		case	'renew_sns':				// ソーシャルカウントの再取得
			$success_count			=	0;
			$skip_count				=	0;
			if	(!isset($select_id ) || !is_array($select_id ) ) {
				$html_notice		.=	'<div class="notice notice-info is-dismissible"><p><strong>'.__('Not selected', 'pz-linkcard' ).'</strong></p></div>';
				break;
			}
			foreach	($select_id as $data_id ) {
				$data				=	$this->pz_GetCache(array('id' => $data_id ) );
				if	(isset($data ) && is_array($data ) ) {
					$data['sns_nexttime']	=	0;
					$data			=	$this->pz_SetCache($data );
					$data			=	$this->pz_RenewSNSCount($data );
					$success_count++;
				} else {
					$skip_count++;
				}
			}
			$html_notice			.=	'<div class="notice '.($success_count ? 'notice-success' : 'notice-error' ).' is-dismissible"><p><strong>'.__('Renew SNS Count', 'pz-linkcard' ).__('...', 'pz-linkcard' ).__('(', 'pz-linkcard' ).__('Success:', 'pz-linkcard' ).$success_count.' '.__('Skip:', 'pz-linkcard' ).$skip_count.__(')', 'pz-linkcard' ).'</strong></p></div>';
			break;

		case	'renew_postid':				// 記事IDの再取得
			$success_count			=	0;
			$skip_count				=	0;
			if	(!isset($select_id ) || !is_array($select_id ) ) {
				$html_notice		.=	'<div class="notice notice-info is-dismissible"><p><strong>'.__('Not selected', 'pz-linkcard' ).'</strong></p></div>';
				break;
			}
			foreach	($select_id as $data_id ) {
				$data				=	$this->pz_GetCache(array('id' => $data_id ) );
				$result				=	null;
				if	(isset($data ) && is_array($data ) ) {
					$result			=	$this->pz_SetCache($data );
				}
				if	($result ) {
					$success_count++;
				} else {
					$skip_count++;
				}
			}
			$html_notice			.=	'<div class="notice '.($success_count ? 'notice-success' : 'notice-error' ).' is-dismissible"><p><strong>'.__('Renew Post ID', 'pz-linkcard' ).__('...', 'pz-linkcard' ).__('(', 'pz-linkcard' ).__('Success:', 'pz-linkcard' ).$success_count.' '.__('Skip:', 'pz-linkcard' ).$skip_count.__(')', 'pz-linkcard' ).'</strong></p></div>';
			break;

		case	'alive':
			$success_count			=	0;
			$skip_count				=	0;
			if	(!isset($select_id ) || !is_array($select_id ) ) {
				$html_notice		.=	'<div class="notice notice-info is-dismissible"><p><strong>'.__('Not selected', 'pz-linkcard' ).'</strong></p></div>';
				break;
			}
			foreach	($select_id as $data_id ) {
				$data				=	$this->pz_GetCache(array('id' => $data_id ) );
				if	(isset($data ) && is_array($data ) ) {
					$data						=	$this->pz_GetCache($data );
					$after						=	$this->pz_GetRemote($data );
					$data['alive_result']		=	$after['update_result'];
					$data['alive_time']			=	$this->now;
					$data['alive_nexttime']		=	$this->now + WEEK_IN_SECONDS * 4;
					if	($data['title']			==	$after['title'] ) {
						$data['mod_title']		=	false;
					} else {
						$data['mod_title']		=	true;
					}
					if	($data['excerpt']		==	$after['excerpt'] ) {
						$data['mod_excerpt']	=	false;
					} else {
						$data['mod_excerpt']	=	true;
					}
					$data						=	$this->pz_SetCache($data );
					if	($data ) {
						$success_count++;
					} else {
						$skip_count++;
					}
				}
			}
			$html_notice			.=	'<div class="notice '.($success_count ? 'notice-success' : 'notice-error' ).' is-dismissible"><p><strong>'.__('Alive check', 'pz-linkcard' ).__('...', 'pz-linkcard' ).__('(', 'pz-linkcard' ).__('Success:', 'pz-linkcard' ).$success_count.' '.__('Skip:', 'pz-linkcard' ).$skip_count.__(')', 'pz-linkcard' ).'</strong></p></div>';
			break;

		case	'delete':
			$success_count			=	0;
			$skip_count				=	0;
			if	(!isset($select_id ) || !is_array($select_id ) ) {
				$html_notice		.=	'<div class="notice notice-info is-dismissible"><p><strong>'.__('Not selected', 'pz-linkcard' ).'</strong></p></div>';
				break;
			}
			foreach	($select_id as $data_id ) {
 				$result				=	$this->pz_DelCache(array('id' => $data_id ) );
 				if	($result ) {
 					$success_count++;
 				} else {
 					$skip_count++;
 				}
			}
			$html_notice			.=	'<div class="notice '.($success_count ? 'notice-success' : 'notice-error' ).' is-dismissible"><p><strong>'.__('Delete Cache', 'pz-linkcard' ).__('...', 'pz-linkcard' ).__('(', 'pz-linkcard' ).__('Success:', 'pz-linkcard' ).$success_count.' '.__('Skip:', 'pz-linkcard' ).$skip_count.__(')', 'pz-linkcard' ).'</strong></p></div>';
			break;

		case	'exec-import':			// インポート実行
			require ('pz-linkcard-file-import-csv.php');
			$show_list				=	false;
			break;

		case	'show-import':			// ファイルのインポートボタンを表示
			require ('pz-linkcard-file-import-menu.php');
			$show_list				=	false;
			break;

		case	'show-export':			// ファイルのエクスポートボタンを表示
			require ('pz-linkcard-file-export-menu.php');
			$show_list				=	false;
			break;

		default:
			$html_notice			.=	'<div class="notice notice-info is-dismissible"><p><strong>'.__('An undefined action was selected.', 'pz-linkcard' ).'</strong></p></div>';
		}
	}

	// 画面描画
	echo	$html_notice;
	echo	$html_input;

	// キャッシュ一覧
	if	($show_list ) {
		require_once ('pz-linkcard-cacheman-list.php');
	}

	echo	'</form>';
//	echo	'</div>';
	// echo	'<div id="pz-overlay-proc"></div>';
