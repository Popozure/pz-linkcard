<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>
<?php
	// DB使用
	global		$wpdb;

	// DBテーブル存在チェック
	$exists_table = $wpdb->get_var( $wpdb->prepare( "SHOW TABLES LIKE %s", $this->db_card ) );
	if ($exists_table <> $this->db_card ) {
		$this->hook_activate();
	}

	// 詳細画面の表示と編集に必要な値だけを受け取る。
	$page = 'pz-linkcard-cacheman';
	$debug_mode = !empty($this->options['debug-mode']) ? 1 : 0;
	$admin_mode = !empty($this->options['admin-mode']) ? 1 : 0;
	$develop_mode = intval($this->options['develop-mode'] ?? 0);
	if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
		check_admin_referer('pz-cacheman');
	}
	$action = isset($_POST['action']) && is_string($_POST['action']) ? sanitize_key(wp_unslash($_POST['action']))
		: (isset($_GET['action']) && is_string($_GET['action']) ? sanitize_key(wp_unslash($_GET['action'])) : '');
	// Card fields need their original text; pz_SetCache() normalizes URLs, types, lengths, and database columns.
	// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
	$data = isset($_POST['data']) && is_array($_POST['data']) ? wp_unslash($_POST['data']) : null;
	$scroll_now = isset($_POST['scroll_now']) ? absint($_POST['scroll_now']) : 0;
	$return_url = isset($_POST['return_url']) && is_string($_POST['return_url']) ? esc_url_raw(wp_unslash($_POST['return_url']))
		: (isset($_GET['return_url']) && is_string($_GET['return_url']) ? esc_url_raw(wp_unslash($_GET['return_url'])) : '');
	$return_url = wp_validate_redirect($return_url, '');
	$cache_id = absint($_POST['cache_id'] ?? ($_GET['cache_id'] ?? 0));
	if (isset($_POST['click-period']) || isset($_POST['click-page']) || isset($_POST['click-range-select'])) {
		$action = 'click-log';
	}

	// 出力するHTML
	$html_style		=	'';
	$html_plugin	=	'';
	$html_title		=	'';
	$html_input		=	'';
	$html_notice	=	'';
	$content_file	=	null;

	// リスト表示の有無
	$show_list		=	true;

	// プラグイン名・バージョン・環境表示
	$html_mode		=	($debug_mode			?	'<span class="pz-infobar-env pz-infobar-env-debug">'.__('Debug Mode', 'pz-linkcard' ).'</span>'				:	'' ).
			($admin_mode	?	'<span class="pz-infobar-env pz-infobar-env-admin">'.__('Admin Mode', 'pz-linkcard' ).'</span>'	:	'' ).
			($develop_mode	==	1	?	'<span class="pz-infobar-env pz-infobar-env-develop">'.__('Development Environment', 'pz-linkcard' ).'</span>'	:	'' ).
			($develop_mode	==	2	?	'<span class="pz-infobar-env pz-infobar-env-product">'.__('Production Environment', 'pz-linkcard' ).'</span>'	:	'' );

	// ページの見出し表示（設定）
	$page_class	=	' pz-cacheman';
	$switch_link	=	esc_url($this->settings_url );
	$switch_icon	=	'<span class="dashicons dashicons-admin-generic" style="vertical-align: text-bottom;"></span>';
	$switch_label	=	__('Settings', 'pz-linkcard' );
	$html_filemenu	=	'<form method="post" class="pz-infobar-filemenu">'.wp_nonce_field('pz-cacheman', '_wpnonce', false, false ).
			'<button type="submit" name="action" value="show-import" class="pz-man-infobar-filemenu-button" data-no-overlay="1"><span class="dashicons dashicons-download"></span><span class="pz-infobar-filemenu-label">'.esc_html__('Import', 'pz-linkcard' ).'</span></button>'.
			'<button type="submit" name="action" value="show-export" class="pz-man-infobar-filemenu-button" data-no-overlay="1"><span class="dashicons dashicons-upload"></span><span class="pz-infobar-filemenu-label">'.esc_html__('Export', 'pz-linkcard' ).'</span></button></form>';
	$html_plugin	=	'<div id="pz-infobar"><div class="pz-infobar-left"><a href="'.esc_url($this->cacheman_url ).'" class="pz-infobar-plugin-logo"><img src="'.esc_url($this->plugin_dir_url.'img/pz-linkcard_logo.svg' ).'" width="156px" height="28px" alt="'.esc_attr(self::PLUGIN_NAME ).'"></a><span class="pz-infobar-plugin-ver pz-monospace">ver.'.esc_html(PZLKC_PLUGIN_VERSION ).'</span>'.$html_mode.'</div><div class="pz-infobar-right">'.$html_filemenu.'<a href="'.$switch_link.'" class="pz-infobar-switch" title="Pz カード設定画面へ切り替える F4"><span class="pz-infobar-switch-icon">'.$switch_icon.'</span><span class="pz-infobar-switch-label">'.$switch_label.'</span></a></div></div>';
	$title_icon		=	'<span class="dashicons dashicons-archive" style="vertical-align: bottom; width: 32px; height: 32px; font-size: 32px;"></span>';
	$title_label	=	__('Pz-LinkCard Manager', 'pz-linkcard' );
	$help_page		=	self::AUTHOR_URL.'/pz-linkcard-manager';
	$is_dashboard_return	=	$this->pz_IsDashboardWidgetReturnUrl($return_url );
	$header_back_link	=	'';
	if	($return_url ) {
		$back_label	=	$is_dashboard_return ? __('Return to Dashboard', 'pz-linkcard' ) : __('Return to Previous Page', 'pz-linkcard' );
		$header_back_link	=	'<a class="pz-header-dashboard-back" href="'.esc_url($return_url ).'" title="'.esc_attr($back_label ).'"><span class="dashicons dashicons-exit" aria-hidden="true"></span></a>';
	}
	$html_title		=	'<div class="pz-header"><h1>'.$header_back_link.'<span class="pz-header-title"><a class="pz-header-title-link" href="'.esc_url($this->cacheman_url ).'"><span class="pz-header-title-icon">'.$title_icon.'</span><span class="pz-header-title-text">'.$title_label.'</span></a><a class="pz-help-icon" href="'.$help_page.'" rel="external noopener noreferrer help" referrerpolicy="no-referrer" target="_blank"><img src="'.$this->plugin_dir_url.'img/help.png" width="16" height="16" title="'.__('Help', 'pz-linkcard' ).'" alt="help" /></a></span></h1></div>';

	// 一覧の検索条件は return_url にまとめて保持する。
	$temp_param = array(
		'page' => $page,
		'scroll_now' => $scroll_now,
		'return_url' => $return_url,
	);
	foreach		($temp_param		as	$temp_name => $temp_value ) {
		$html_input	.=	'<input type="hidden" name="'.esc_attr($temp_name ).'" value="'.esc_attr($temp_value ).'" title="'.esc_attr($temp_name ).'" size="4" />';
	}

	// モードによって表示させる
	$html_style		.=	$debug_mode		==	0	?	'.pz-cacheman .pz-debug-only { display: none !important; } '	:	'';
	$html_style		.=	$admin_mode		==	0	?	'.pz-admin-only { display: none; } '	:	'';
	$html_style		.=	$develop_mode	==	0	?	'.pz-develop-only { display: none; } '	:	'';
	if	($html_style ) {
		$html_style	=	'<style>'.$html_style.'</style>';
	}

	// 画面描画
	if	($scroll_now > 0 && $action !== 'click-log' ) {
		echo	'<style>html.pz-cacheman-restoring .pz-cacheman{visibility:hidden}</style>';
		echo	'<script>document.documentElement.classList.add("pz-cacheman-restoring");</script>';
	}
	echo	'<div id="pz-overlay-proc" style="display: none;"><div class="pz-loader"></div></div>';
	echo	'<div class="pz-dashboard'.$page_class.' wrap">';
	echo	$html_style;
	$infobar_allowed_html	=	wp_kses_allowed_html('post' );
	$infobar_allowed_html['a']['referrerpolicy']	=	true;
	$infobar_allowed_html['form']	=	array('method' => true, 'class' => true );
	$infobar_allowed_html['input']	=	array('type' => true, 'name' => true, 'value' => true );
	$infobar_allowed_html['button']	=	array('type' => true, 'name' => true, 'value' => true, 'class' => true, 'data-no-overlay' => true );
	echo	wp_kses($html_plugin, $infobar_allowed_html );
	echo	wp_kses($html_title, $infobar_allowed_html );

	echo	'<form action="" method="post" enctype="multipart/form-data">';
	wp_nonce_field('pz-cacheman' );			// nonce

	// 記述エラー
	if	($this->options['error-mode'] ) {
		if	(!$this->options['error-mode-hide'] ) {
			$error_url		=	$this->options['error-url'];
			$html_notice	.=	'<div class="notice notice-error is-dismissible pz-lkc-error-mode-notice"><p><strong>'.self::PLUGIN_NAME.': '.__('Invalid URL parameter in ', 'pz-linkcard' ).'<a href="'.esc_url($error_url ).'#lkc-error" target="_blank" rel="external noopener noreferrer" referrerpolicy="no-referrer">'.esc_html($this->pz_DecodeURL($error_url ) ).'</a></strong><br>'.__('*', 'pz-linkcard' ).' '.__('You can dismiss this message from <a href="./options-general.php?page=pz-linkcard-settings&tab=pz-error#pz-error-reset-settings">the settings screen</a>.', 'pz-linkcard' ).'</p></div>';
		}
	}

	// アクションの指示があったとき
	if	($action ) {
		switch	($action ) {
		case	'cancel':					// 編集画面キャンセル
			$redirect_url		=	$return_url ?: $this->cacheman_url;
			echo	'<script>window.location.replace('.wp_json_encode($redirect_url ).');</script>';
			$show_list			=	false;
			break;

		case	'edit':						// 編集画面
			$edit_id				=	$cache_id;
			$data					=	$this->pz_GetCache(array('id' => $edit_id ) );
			if	(isset($data ) && is_array($data ) ) {
				$content_file		=	'pz-linkcard-cacheman-edit.php';
			}
			$show_list				=	false;		// リストを表示しない
			break;

		case	'click-log':				// クリックログ一覧
			if	($cache_id ) {
				$data				=	$this->pz_GetCache(array('id' => $cache_id ) );
			}
			if	(!$cache_id || !empty($data['id'] ) ) {
				$data				=	$cache_id ? $data : array('id' => 0 );
				$content_file		=	'pz-linkcard-cacheman-click.php';
			} else {
				$html_notice		.=	'<div class="notice notice-error"><p><strong>'.esc_html__('The specified link card was not found.', 'pz-linkcard' ).'</strong></p></div>';
			}
			$show_list				=	false;
			break;

		case	'update':
			$success_count			=	0;
			$skip_count				=	0;
			if	(!isset($data ) || !is_array($data ) || !isset($data['id'] ) ) {
				$html_notice		.=	'<div class="notice notice-info is-dismissible"><p><strong>'.__('Not selected', 'pz-linkcard' ).'</strong></p></div>';
				break;
			}
			foreach	($data			as	$key => $value ) {
				if (!is_string($value)) { unset($data[$key]); }
			}
			$data	=	$this->pz_SetCache($data );
			if	(isset($data ) && is_array($data ) && isset($data['id'] ) ) {
				$success_count++;
			}
			$html_notice			.=	'<div class="notice '.($success_count ? 'notice-success' : 'notice-error' ).' is-dismissible"><p><strong>'.__('Cache Update Result', 'pz-linkcard' ).__('...', 'pz-linkcard' ).__('(', 'pz-linkcard' ).__('Success:', 'pz-linkcard' ).$success_count.' '.__('Skip:', 'pz-linkcard' ).$skip_count.__(')', 'pz-linkcard' ).'</strong></p></div>';
			$redirect_url		=	$return_url ?: $this->cacheman_url;
			echo	'<script>window.location.replace('.wp_json_encode($redirect_url ).');</script>';
			$show_list			=	false;
			break;

		case	'exec-import':			// インポート実行
			require ('pz-linkcard-file-import-csv.php');
			$show_list				=	false;
			break;

		case	'show-import':			// ファイルのインポートボタンを表示
			$content_file		=	'pz-linkcard-file-import-menu.php';
			$show_list				=	false;
			break;

		case	'show-export':			// ファイルのエクスポートボタンを表示
			$content_file		=	'pz-linkcard-file-export-menu.php';
			$show_list				=	false;
			break;

		default:
			$html_notice			.=	'<div class="notice notice-info is-dismissible"><p><strong>'.__('An undefined action was selected.', 'pz-linkcard' ).'</strong></p></div>';
		}
	}

	// 画面描画
	if	(!$show_list ) {
		echo	$html_notice;
	}
	echo	$html_input;
	if	($content_file ) {
		require ($content_file );
	}

	// キャッシュ一覧
	if	($show_list ) {
		$redirect_url = $return_url ?: $this->cacheman_url;
		echo '<script>window.location.replace('.wp_json_encode($redirect_url).');</script>';
	}

	echo	'</form>';
