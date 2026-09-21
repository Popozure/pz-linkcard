<?php defined('ABSPATH' ) || wp_die; ?>
<?php
	define('LIST_ENCLOSE_TAG',	array(
		'div'			=>		__('DIV Tag',			'pz-linkcard' ),
		'blockquote'	=>		__('BLOCKQUOTE Tag',	'pz-linkcard' ),
		'figure'		=>		__('FIGURE Tag',		'pz-linkcard' ),
		'article'		=>		__('ARTICLE Tag',		'pz-linkcard' ),
		'section'		=>		__('SECTION Tag',		'pz-linkcard' ),
		'nav'			=>		__('NAV Tag',			'pz-linkcard' ),
		'aside'			=>		__('ASIDE Tag',			'pz-linkcard' ),
	) );

	// 「内部リンクの設定を参照」
	define('LIST_INTERNAL',	array(''	=>	__('Use the same setting as Internal Link', 'pz-linkcard' ), ) );

	// 「枠線の幅」
	define('LIST_PX',		array('1px' => __('1px', 'pz-linkcard' ), '2px' => __('2px', 'pz-linkcard' ), '3px' => __('3px', 'pz-linkcard' ), '4px' => __('4px', 'pz-linkcard' ), '5px' => __('5px', 'pz-linkcard' ), '6px' => __('6px', 'pz-linkcard' ), '7px' => __('7px', 'pz-linkcard' ), '8px' => __('8px', 'pz-linkcard' ), '9px' => __('9px', 'pz-linkcard' ), '10px' => __('10px', 'pz-linkcard' ), '11px' => __('11px', 'pz-linkcard' ), '12px' => __('12px', 'pz-linkcard' ), '13px' => __('13px', 'pz-linkcard' ), '14px' => __('14px', 'pz-linkcard' ), '15px' => __('15px', 'pz-linkcard' ), '16px' => __('16px', 'pz-linkcard' ), '17px' => __('17px', 'pz-linkcard' ), '18px' => __('18px', 'pz-linkcard' ), '19px' => __('19px', 'pz-linkcard' ), '20px' => __('20px', 'pz-linkcard' ), '21px' => __('21px', 'pz-linkcard' ), '22px' => __('22px', 'pz-linkcard' ), '23px' => __('23px', 'pz-linkcard' ), '24px' => __('24px', 'pz-linkcard' ), '25px' => __('25px', 'pz-linkcard' ), '26px' => __('26px', 'pz-linkcard' ), '27px' => __('27px', 'pz-linkcard' ), '28px' => __('28px', 'pz-linkcard' ), '29px' => __('29px', 'pz-linkcard' ), '30px' => __('30px', 'pz-linkcard' ), '31px' => __('31px', 'pz-linkcard' ), '32px' => __('32px', 'pz-linkcard' ), '33px' => __('33px', 'pz-linkcard' ), '34px' => __('34px', 'pz-linkcard' ), '35px' => __('35px', 'pz-linkcard' ), '36px' => __('36px', 'pz-linkcard' ), '37px' => __('37px', 'pz-linkcard' ), '38px' => __('38px', 'pz-linkcard' ), '39px' => __('39px', 'pz-linkcard' ), '40px' => __('40px', 'pz-linkcard' ), '41px' => __('41px', 'pz-linkcard' ), '42px' => __('42px', 'pz-linkcard' ), '43px' => __('43px', 'pz-linkcard' ), '44px' => __('44px', 'pz-linkcard' ), '45px' => __('45px', 'pz-linkcard' ), '46px' => __('46px', 'pz-linkcard' ), '47px' => __('47px', 'pz-linkcard' ), '48px' => __('48px', 'pz-linkcard' ), '49px' => __('49px', 'pz-linkcard' ), '50px' => __('50px', 'pz-linkcard' ), '51px' => __('51px', 'pz-linkcard' ), '52px' => __('52px', 'pz-linkcard' ), '53px' => __('53px', 'pz-linkcard' ), '54px' => __('54px', 'pz-linkcard' ), '55px' => __('55px', 'pz-linkcard' ), '56px' => __('56px', 'pz-linkcard' ), '57px' => __('57px', 'pz-linkcard' ), '58px' => __('58px', 'pz-linkcard' ), '59px' => __('59px', 'pz-linkcard' ), '60px' => __('60px', 'pz-linkcard' ), '61px' => __('61px', 'pz-linkcard' ), '62px' => __('62px', 'pz-linkcard' ), '63px' => __('63px', 'pz-linkcard' ), '64px' => __('64px', 'pz-linkcard' ), ) );
	
	// 「余白」の書式
	define('LIST_MARGIN',	array(
		''				=>		__('Not defined',		'pz-linkcard' ),
		'0'				=>		__('0',					'pz-linkcard' ),
		'4px'			=>		__('4px',				'pz-linkcard' ),
		'8px'			=>		__('8px',				'pz-linkcard' ),
		'12px'			=>		__('12px',				'pz-linkcard' ),
		'16px'			=>		__('16px',				'pz-linkcard' ),
		'20px'			=>		__('20px',				'pz-linkcard' ),
		'24px'			=>		__('24px',				'pz-linkcard' ),
		'32px'			=>		__('32px',				'pz-linkcard' ),
		'40px'			=>		__('40px',				'pz-linkcard' ),
		'48px'			=>		__('48px',				'pz-linkcard' ),
		'56px'			=>		__('56px',				'pz-linkcard' ),
		'64px'			=>		__('64px',				'pz-linkcard' ),
	) );

	// 「角丸め」の書式
	define('LIST_RADIUS',	array(
		''				=>		__('None',				'pz-linkcard' ),
		'2px'			=>		__('2px',				'pz-linkcard' ),
		'4px'			=>		__('4px',				'pz-linkcard' ),
		'6px'			=>		__('6px',				'pz-linkcard' ),
		'8px'			=>		__('8px',				'pz-linkcard' ),
		'12px'			=>		__('12px',				'pz-linkcard' ),
		'16px'			=>		__('16px',				'pz-linkcard' ),
		'20px'			=>		__('20px',				'pz-linkcard' ),
		'24px'			=>		__('24px',				'pz-linkcard' ),
		'32px'			=>		__('32px',				'pz-linkcard' ),
		'64px'			=>		__('64px',				'pz-linkcard' ),
		'50%'			=>		__('50%',				'pz-linkcard' ),
	) );

	// 「枠線」の書式
	define('LIST_BORDER',	array(
		'none'			=>		__('None',				'pz-linkcard' ),
		'solid'			=>		__('Solid',				'pz-linkcard' ),
		'dotted'		=>		__('Dotted',			'pz-linkcard' ),
		'dashed'		=>		__('Dashed',			'pz-linkcard' ),
		'double'		=>		__('Double',			'pz-linkcard' ),
		'groove'		=>		__('Groove',			'pz-linkcard' ),
		'ridge'			=>		__('Ridge',				'pz-linkcard' ),
		'inset'			=>		__('Inset',				'pz-linkcard' ),
		'outset'		=>		__('Outset',			'pz-linkcard' ),
	) );

	// 「新しいタブで開く」の書式
	define('LIST_NEWTAB',	array(
		''				=>		__('None',				'pz-linkcard' ),
		'1'				=>		__('All Devices',		'pz-linkcard' ),
		'2'				=>		__('Non-mobile devices',	'pz-linkcard' ),
	) );

	// 引数・変数の設定
	$page				=	'pz-linkcard-settings';						// 設定画面のページ
	if	(isset($_SERVER['REQUEST_METHOD'] ) && $_SERVER['REQUEST_METHOD'] === 'POST' ) {
		check_admin_referer('pz-settings' );
	}
	$action				=	isset($_POST['action'] )					?	esc_attr($_POST['action'] )					:	null ;
	$submit				=	isset($_POST['submit'] )					?	esc_attr($_POST['submit'] )					:	null ;
	$tab_now			=	isset($_POST['tab-now'] )					?	esc_attr($_POST['tab-now'] )				:	'pz-error' ;
	$scroll_now			=	isset($_POST['scroll-now'] )				?	esc_attr($_POST['scroll-now'] )				:	null ;

	// 変更の保存ボタンを押したとき
	if	(!$action	&&	$submit ) {
		$action			=	'save-changed';
	}

	// デバグモード・管理モード
	$debug_mode			=	isset($this->options['debug-mode'] )		?	intval($this->options['debug-mode'] )		:	0 ;
	$survey_mode		=	isset($this->options['survey-mode'] )		?	intval($this->options['survey-mode'] )		:	0 ;
	$admin_mode			=	isset($this->options['admin-mode'] )		?	intval($this->options['admin-mode'] )		:	0 ;
	$develop_mode		=	isset($this->options['develop-mode'] )		?	intval($this->options['develop-mode'] )		:	0 ;
	$menu_error			=	isset($this->options['error-mode'] )		?	intval($this->options['error-mode'] )		:	0 ;
	$menu_multi			=	isset($this->options['multi-mode'] )		?	intval($this->options['multi-mode'] )		:	0 ;
	$menu_initialize	=	isset($this->options['flg-initialize'] )	?	intval($this->options['flg-initialize'] )	:	0 ;
	$inhibit			=	isset($this->options['flg-inhibit'] )		?	intval($this->options['flg-inhibit'] )		:	0 ;

	// 入力値
	$prop		=	null;
	if	(isset($_POST['properties'] ) ) {
		$prop		=	array_merge(self::pz_GetDefaultOptions(), is_array($this->options ) ? $this->options : array() );
		foreach	($_POST['properties']	as	$key => $value ) {
			$prop[$key]	=	stripslashes($value );
		}
		if	(!array_key_exists('enclose-tag', $_POST['properties'] ) ) {
			$prop['enclose-tag']	=	!empty($this->options['blockquote'] ) ? 'blockquote' : 'div';
		}
		ksort($prop );

		// 管理者モードの解除
		if	(!$prop['debug-mode'] ) {
			$prop['survey-mode']			=	0;
			$prop['admin-mode']				=	0;
		}
		if	(!$prop['admin-mode'] ) {
			$prop['initialize-exception']	=	0;
		}
	} 

	// 画面入力値で修正
	$debug_mode			=	isset($prop['debug-mode'] )					?	intval($prop['debug-mode'] )				:	$debug_mode ;
	$survey_mode		=	isset($prop['survey-mode'] )				?	intval($prop['survey-mode'] )				:	$survey_mode ;
	$admin_mode			=	isset($prop['admin-mode'] )					?	intval($prop['admin-mode'] )				:	$admin_mode ;
	$develop_mode		=	isset($prop['develop-mode'] )				?	intval($prop['develop-mode'] )				:	$develop_mode ;
	$menu_error			=	isset($prop['error-mode'] )					?	intval($prop['error-mode'] )				:	$menu_error ;
	$menu_multi			=	isset($prop['multi-mode'] )					?	intval($prop['multi-mode'] )				:	$menu_multi ;
	$menu_initialize	=	isset($prop['flg-initialize'] )				?	intval($prop['flg-initialize'] )			:	$menu_initialize ;
	$inhibit			=	isset($prop['flg-inhibit'] )				?	intval($prop['flg-inhibit'] )				:	$inhibit ;

	$visible_tabs		=	array(
		'pz-error'			=>		($menu_error			!=	0 ),
		'pz-basic'			=>		true,
		'pz-position'		=>		true,
		'pz-display'		=>		true,
		'pz-letter'			=>		true,
		'pz-external'		=>		true,
		'pz-internal'		=>		true,
		'pz-check'			=>		true,
		'pz-editor'			=>		true,
		'pz-multisite'		=>		($menu_multi			!=	0 ),
		'pz-advanced'		=>		true,
		'pz-etc'			=>		true,
		'pz-initialize'		=>		($menu_initialize		!=	0 ),
		'pz-admin'			=>		($admin_mode			!=	0 ),
	);
	if	(empty($visible_tabs[$tab_now] ) ) {
		$tab_now		=	'pz-basic';
	}
	$pz_tab_active	=	function($name ) use (&$tab_now ) {
		return		($tab_now === $name )	?	' pz-tab-active'	:	'';
	};
	$pz_page_active	=	function($name ) use (&$tab_now ) {
		return		($tab_now === $name )	?	' pz-page-active'	:	'';
	};

	// 暗転（準備中）
	if	($inhibit ) {
		echo		'<div id="pz-overlay-proc" style="display: flex;"><div class="pz-loader"></div></div>';
	} else {
		echo		'<div id="pz-overlay-proc" style="display: none;"><div class="pz-loader"></div></div>';
	}
	if	($scroll_now !== null && is_numeric($scroll_now ) && intval($scroll_now ) > 0 ) {
		echo		'<script>(function(y){if("scrollRestoration" in history){history.scrollRestoration="manual";}var n=0;function r(){window.scrollTo(0,y);if(++n<30&&Math.abs(window.scrollY-y)>2){requestAnimationFrame(r);}}r();document.addEventListener("DOMContentLoaded",r,{once:true});window.addEventListener("load",r,{once:true});})('.intval($scroll_now ).');</script>';
	}

	// マルチサイト
	$is_multisite	=	function_exists('is_multisite' )			?	is_multisite()								:	false ;
	$is_subdomain	=	function_exists('is_subdomain_install' )	?	is_subdomain_install()						:	false ;
	$multi_myid		=	function_exists('get_current_blog_id' )		?	get_current_blog_id()						:	0 ;
	$multi_count	=	0;
	if	($is_multisite ) {
		$menu_multi		=	1;
		$j				=	0;
		for ($i = 1; $i <= 1000; $i++ ) {
			$multi_detail	=	get_blog_details($i );
			if	(!$multi_detail ) {
				break;
			}
			if	(!$multi_detail->deleted ) {
				$j++;
				$multi_count			=	$j;
				$multi[$j]['id']		=	$multi_detail->blog_id;		// ブログID
				$multi[$j]['name']		=	$multi_detail->blogname;	// ブログ名
				$multi[$j]['url']		=	$multi_detail->home;		// ブログURL（Home_URL）
				$multi[$j]['domain']	=	preg_replace('/.*\/\/(.*)/', '$1', $multi_detail->home );	// ドメイン名
			}
		}
	}
	if	(!$is_multisite && $menu_multi &&  (!$debug_mode || !$admin_mode ) ) {
		$menu_multi	=	0;
	}

	// 出力するHTML
	$html_style		=	'';
	$html_plugin	=	'';
	$html_title		=	'';
	$html_input		=	'';
	$html_notice	=	'';
	$html_standard_notice	=	'';

	// スタイルシート再生成の有無
	$flg_style		=	false;			// スタイルシートを再生成するか

	// プラグイン名・バージョン・環境表示
	$html_mode		=	($debug_mode			?	'<span class="pz-infobar-env pz-infobar-env-debug">'.	__('Debug Mode', 'pz-linkcard' ).'</span>'				:	'' ).
						($survey_mode			?	'<span class="pz-infobar-env pz-infobar-env-survey">'.	__('Survey Mode', 'pz-linkcard' ).'</span>'				:	'' ).
						($admin_mode			?	'<span class="pz-infobar-env pz-infobar-env-admin">'.	__('Admin Mode', 'pz-linkcard' ).'</span>'				:	'' ).
						($develop_mode	==	1	?	'<span class="pz-infobar-env pz-infobar-env-develop">'.	__('Development Environment', 'pz-linkcard' ).'</span>'	:	'' ).
						($develop_mode	==	2	?	'<span class="pz-infobar-env pz-infobar-env-product">'.	__('Production Environment', 'pz-linkcard' ).'</span>'	:	'' );

	// ページの見出し表示（設定）
	$page_class		=	' pz-settings';
	$switch_link	=	esc_url($this->cacheman_url );
	$switch_icon	=	'<span class="dashicons dashicons-archive" style="vertical-align: text-bottom;"></span>';
	$switch_label	=	__('Manager', 'pz-linkcard' );
	$html_plugin	=	'<div id="pz-infobar"><div class="pz-infobar-left"><a href="'.esc_url($this->settings_url ).'" class="pz-infobar-plugin-logo"><img src="'.esc_url($this->plugin_dir_url.'img/pz-linkcard_logo.svg' ).'" width="156px" height="28px" alt="'.esc_attr(self::PLUGIN_NAME ).'"></a><span class="pz-infobar-plugin-ver pz-monospace">ver.'.esc_html(PZLKC_PLUGIN_VERSION ).'</span>'.$html_mode.'</div><div class="pz-infobar-right"><a href="'.$switch_link.'" class="pz-infobar-switch" title="'.esc_attr($switch_label ).'"><span class="pz-infobar-switch-icon">'.$switch_icon.'</span><span class="pz-infobar-switch-label">'.$switch_label.'</span></a></div></div>';
	$title_icon		=	'<span class="dashicons dashicons-admin-generic" style="vertical-align: bottom; width: 32px; height: 32px; font-size: 32px;"></span>';
	$title_label	=	__('Pz-LinkCard Settings', 'pz-linkcard' );
	$help_page		=	self::AUTHOR_URL.'/pz-linkcard-manager';
	$html_title		=	'<div class="pz-header"><h1><span class="pz-header-title"><span class="pz-header-title-icon">'.$title_icon.'</span><span class="pz-header-title-text">'.$title_label.'</span><a class="pz-help-icon" href="'.$help_page.'" rel="external noopener help" target="_blank"><img src="'.$this->plugin_dir_url.'img/help.png" width="16" height="16" title="'.__('Help', 'pz-linkcard' ).'" alt="help" /></a></span></h1></div>';

	// POSTする値 INPUT要素
	$temp_param		=
		array(
			'debug-mode'		=>		intval($debug_mode ),
			'survey-mode'		=>		intval($survey_mode ),
			'admin-mode'		=>		intval($admin_mode ),
			'develop-mode'		=>		intval($develop_mode ),
			'multi-mode'		=>		intval($menu_multi ),
			'tab-now'			=>		esc_attr($tab_now ),
			'scroll-now'		=>		esc_attr($scroll_now ),
		);
	foreach		($temp_param		as	$temp_name => $temp_value ) {
		$html_input	.=	'<input type="hidden" name="'.$temp_name.'" value="'.$temp_value.'" title="'.$temp_name.'" size="4" />';
	}
	foreach		(array('preview-mode', 'preview-left', 'preview-top', 'preview-width', 'preview-height', 'preview-docked-height', 'preview-right-docked-width', 'preview-two-cards' ) as $temp_name ) {
		$temp_value		=	array_key_exists($temp_name, $this->options ) ? $this->options[$temp_name] : null;
		$html_input		.=	'<input type="hidden" name="properties['.$temp_name.']" value="'.esc_attr($temp_value ).'" data-pz-preview-state="'.esc_attr($temp_name ).'" />';
	}

	// モードによって表示させる
	$html_style		.=	$debug_mode		==	0	?	'.pz-debug-only { display: none; } '	:	'';
	$html_style		.=	$admin_mode		==	0	?	'.pz-admin-only { display: none; } '	:	'';
	$html_style		.=	$develop_mode	==	0	?	'.pz-develop-only { display: none; } '	:	'';
	if	($html_style ) {
		$html_style	=	'<style>'.$html_style.'</style>';
	}

	// 記述エラー
	if	($this->options['error-mode'] ) {
		if	(!$this->options['error-mode-hide'] ) {
			$error_url		=	$this->options['error-url'];
			$html_standard_notice	.=	'<div class="notice notice-error is-dismissible pz-lkc-error-mode-notice"><p><strong>'.self::PLUGIN_NAME.': '.__('Invalid URL parameter in ', 'pz-linkcard' ).'<a href="'.esc_url($error_url ).'#lkc-error" target="_blank">'.esc_url($error_url ).'</a></strong><br>'.__('*', 'pz-linkcard' ).' '.__('You can dismiss this message from <a href="./options-general.php?page=pz-linkcard-settings">the settings screen</a>.', 'pz-linkcard' ).'</p></div>';
		}
	}

	// プラグインのバージョンが違っている
	if	($this->options['plugin-version']	<>	PZLKC_PLUGIN_VERSION ) {
		$html_notice		.=	'<div class="notice notice-info is-dismissible"><p><strong>'.__('The plugin may have been updated.', 'pz-linkcard' ).'</strong></p></div>';
		$flg_style			=	true;
	}

	// 定義漏れチェック
	if	( ($this->options['admin-mode'] ) && ($prop ) && ($action !== 'init-plugin' ) ) {
		$default_check_exceptions	=	array(
			'debug-dir',
			'debug-url',
			'thumbnail-dir',
			'thumbnail-url',
			'blockquote',
		);
		$default_definitions	=	self::pz_GetOptionDefinitions();
		$checked_prop		=	self::pz_RemoveThisLinkFallback($prop );
		foreach	($default_definitions as $key => $value ) {
			if	(!array_key_exists($key, $checked_prop ) ) {
				$html_notice	.=	'<div class="notice notice-error is-dismissible">'.sprintf(__('Undefined key "%s" in Properties.<br>It may be a glitch. Please inform the developer. (%s)', 'pz-linkcard' ), $key, '<a href="https://x.com/'. self::AUTHOR_TWITTER .'" target="_blank">@'.self::AUTHOR_TWITTER.'</a>' ).'</div>';
			}
		}
		// foreach	($checked_prop as $key => $value ) {
		// 	if	(in_array($key, $default_check_exceptions, true ) ) {
		// 		continue;
		// 	}
		// 	if	(!array_key_exists($key, $default_definitions ) ) {
		// 		$html_notice	.=	'<div class="notice notice-error is-dismissible">'.sprintf(__('Undefined key "%1$s" in DEFAULTS.<br>It may be a glitch. Please inform the developer. (%2$s)', 'pz-linkcard' ), $key, '<a href="https://x.com/'. self::AUTHOR_TWITTER .'" target="_blank">'.self::AUTHOR_TWITTER.'</a>' ).'</div>';
		// 	}
		// }
	}

	// アクションの指示があったとき
	if	($action ) {
		switch	($action ) {
		case	'save-changed':								// 変更を保存ボタン
			$flg_change			=	false;
			if	(isset($_POST['properties'] ) ) {
				$prop	=	array_merge(self::pz_GetDefaultOptions(), is_array($this->options ) ? $this->options : array() );
				foreach	($_POST['properties']	as	$key => $value ) {
					$prop[$key]	=	stripslashes($value );
					if	(array_key_exists($key, $this->options ) ) {
						if	($value		!==	$this->options[$key] ) {
							$flg_change	=	true;			// 変更あり
						}
					}
				}
				unset($prop['url-length'], $this->options['url-length'], $prop['blockquote'], $this->options['blockquote'] );
			} else {
				$html_notice	.=	'<div class="notice notice-error is-dismissible"><p><strong>'.__('Could not retrieve the content to be changed.', 'pz-linkcard' ).'</strong></p></div>';
			}
			
			// パラメーターに変更があった場合
			if	($flg_change ) {
				$this->options			=	$prop;
				$flg_error				=	false;						// エラーの有無
				require_once ('pz-linkcard-settings-validate.php' );	// 値の検証
				if	(!$flg_error ) {
					$result	=	$this->pz_SaveOptions();				// オプションの更新
					if	($result ) {
						$settings_saved_date	=	!empty($this->options['saved-date'] ) ? '（'.esc_html(date('Y/m/d H:i:s', $this->options['saved-date'] ) ).'）' : '';
						$html_notice	.=	'<div class="notice notice-success is-dismissible"><p><strong>'.__('Successfully saved the settings.', 'pz-linkcard' ).$settings_saved_date.'</strong></p></div>';
					} else {
						if	(function_exists('wp_cache_delete' ) ) {
							wp_cache_delete(self::OPTION_NAME, 'options' );
							wp_cache_delete('alloptions', 'options' );
						}
						global	$wpdb;
						$saved_value	=	$wpdb->get_var($wpdb->prepare("SELECT option_value FROM {$wpdb->options} WHERE option_name = %s LIMIT 1", self::OPTION_NAME ) );
						$saved_options	=	($saved_value !== null ) ? maybe_unserialize($saved_value ) : array();
						if	($saved_options != $this->options ) {
							$html_notice	.=	'<div class="notice notice-error is-dismissible"><p><strong>'.__('Failed to save the settings.', 'pz-linkcard' ).'</strong></p></div>';
						} else {
							$html_notice	.=	'<div class="notice notice-info is-dismissible"><p><strong>'.__('The settings have not changed.', 'pz-linkcard' ).'</strong></p></div>';
						}
					}
				}
			}
			$flg_style			=	true;				// スタイルシートの再生成
			break;

		case	'init-plugin':							// プラグインの再起動
			$this->activate_now	=	false;
			$this->hook_activate();
			$flg_style			=	true;				// スタイルシートの再生成
			break;

		case	'init-settings':						// 設定の初期化
			$result		=	$this->pz_InitializeOptions();
			if	($result ) {
				$flg_style		=	true;				// スタイルシートの再生成
				$prop		=	$this->options;
				$html_notice	.=	'<div class="notice notice-success is-dismissible"><p><strong>'.__('Successfully initialized the settings.', 'pz-linkcard' ).'</strong></p></div>';
			} else {
				$html_notice	.=	'<div class="notice notice-error is-dismissible"><p><strong>'.__('Failed to initialize the settings.', 'pz-linkcard' ).'</strong></p></div>';
			}
			break;

		case	'clear-error':
			$flg_style			=	false;
			$menu_error			=	0;		
			$this->options['error-mode']	=	0;
			$result	=	$this->pz_SaveOptions();		// オプションの更新
			break;

		case	'run-pz_linkcard_check':
			$flg_style			=	false;
			break;

		case	'run-pz_linkcard_alive':
			$flg_style			=	false;
			break;

		default:

		}
	}

	// スタイルシート生成
	if	($flg_style ) {
		$result		=	$this->pz_SetStyle();
		switch		($result ) {
		case	1:
			$style_file		=	PZLKC_DIR_STYLE.'style.css';
			$style_date		=	file_exists($style_file ) ? filemtime($style_file ) : null;
			$saved_date		=	($action == 'save-changed' && !empty($style_date ) ) ? '（'.esc_html(wp_date('Y/m/d H:i:s', $style_date ) ).'）' : '';
			$html_notice	.=	'<div class="notice notice-success is-dismissible"><p><strong>'.__('Updated the appearance of the LinkCard.', 'pz-linkcard').$saved_date.'</strong></p></div>';
			break;
		case	2:
			$html_notice	.=	'<div class="notice notice-error is-dismissible"><p><strong>'.__('Failed to save the CSS file.', 'pz-linkcard').'</strong></p></div>';
			break;
		case	9:
			$html_notice	.=	'<div class="notice notice-error is-dismissible"><p><strong>'.__('Failed to load the CSS template.', 'pz-linkcard').'</strong></p></div>';
		default:
		}
	}

	global	$wpdb;
	$option_id	=	$wpdb->get_var($wpdb->prepare("SELECT option_id FROM {$wpdb->options} WHERE option_name = %s LIMIT 1", self::OPTION_NAME ) );
	if	(!$option_id ) {
		$html_notice	.=	'<div class="notice notice-warning is-dismissible"><p><strong>'.esc_html(self::PLUGIN_NAME).': '.esc_html__('Settings option was not found in the database.', 'pz-linkcard' ).'</strong><br>'.
			esc_html__('Option name', 'pz-linkcard' ).': <code>'.esc_html(self::OPTION_NAME).'</code><br>'.
			esc_html__('Table', 'pz-linkcard' ).': <code>'.esc_html($wpdb->options).'</code>'.
			(!empty($GLOBALS['pz_lkc_option_error'] ) ? '<br>'.esc_html__('Database error', 'pz-linkcard' ).': <code>'.esc_html($GLOBALS['pz_lkc_option_error'] ).'</code>' : '' ).
			'</p></div>';
	}

	// ぽぽづれ。のURL
	$pz_url			=	self::AUTHOR_URL;
	$pz_url_info	=	$this->Pz_GetURLInfo($pz_url );
	$pz_domain		=	$pz_url_info['domain'];				// ドメイン名
	$pz_domain_url	=	$pz_url_info['domain_url'];			// ドメインURL

	// Pz-LinkCardのURL
	$plugin_url			=	self::AUTHOR_URL.self::PLUGIN_PATH;

	// HELPアイコン
	$help_open			=	'&nbsp;<a href="'.$pz_url.'/pz-linkcard/pz-linkcard-settings-';
	$help_close			=	'" rel="external noopener help" target="_blank"><img src="'.$this->plugin_dir_url.'img/help.png" class="pz-help-icon" title="'.__('Help', 'pz-linkcard' ).'" alt="help"></a>';

	// 各種ロゴ
	$logo_pz		=	'<img src="'.$this->plugin_dir_url.'img/icon_popozure.ico"    width="16" height="16" alt="'.__('Popozure Logo',		'pz-linkcard' ).'">';
	$logo_pz_lkc	=	'<img src="'.$this->plugin_dir_url.'img/icon-pz-linkcard.png" width="16" height="16" alt="'.__('Pz-LinkCard Logo',	'pz-linkcard' ).'">';
	$logo_pz3		=	'<img src="'.$this->plugin_dir_url.'img/icon_pz3.png"         width="16" height="16" alt="'.__('Pz-LinkCard3 Logo',	'pz-linkcard' ).'">';
	$logo_wp		=	'<img src="'.$this->plugin_dir_url.'img/icon_WordPress.png"   width="16" height="16" alt="'.__('WordPress.org Logo','pz-linkcard' ).'">';
	$logo_tw		=	'<img src="'.$this->plugin_dir_url.'img/icon_tw.png"          width="16" height="16" alt="'.__('Twitter Logo',		'pz-linkcard' ).'">';
	$logo_x			=	'<img src="'.$this->plugin_dir_url.'img/icon_x.png"           width="16" height="16" alt="'.__('X Logo',			'pz-linkcard' ).'">';
	$logo_az		=	'<img src="'.$this->plugin_dir_url.'img/icon_amazon.png"      width="16" height="16" alt="'.__('Amazon Logo',		'pz-linkcard' ).'">';

	// 修正履歴
	$html_preview	=	'';
	if	(true ) {
		$preview_css		=	'';
		$preview_css_file	=	PZLKC_DIR_STYLE.'preview.css';
		if	(file_exists($preview_css_file ) ) {
			$preview_css	=	file_get_contents($preview_css_file );
		}
		$preview_image	=	esc_url($this->plugin_dir_url.'img/logo_pz-linkcard.png' );
		$preview_icon	=	esc_url($this->plugin_dir_url.'img/icon-pz-linkcard.png' );
		$make_preview_card	=	function($prefix, $url, $site_name, $title, $excerpt) use ($preview_image, $preview_icon) {
			return	$this->pz_GetHTML(array(
				'url'			=>	$url,
				'title'			=>	$title,
				'excerpt'		=>	$excerpt,
				'preview-card'	=>	$prefix,
				'preview-data'	=>	array(
					'url'				=>	$url,
					'site_name'			=>	$site_name,
					'title'				=>	$title,
					'excerpt'			=>	$excerpt,
					'thumbnail'			=>	$preview_image,
					'favicon'			=>	$preview_icon,
					'sns_twitter'		=>	1234,
					'sns_facebook'		=>	1234,
					'sns_hatena'		=>	1234,
					'post_date'			=>	'2026-09-12 00:00:00',
					'post_modified'		=>	'2026-09-13 00:00:00',
					'update_result'		=>	200,
					'alive_result'		=>	200,
					'no_failure'		=>	true,
				),
			) );
		};
		$html_preview	=	'<style id="pz-linkcard-preview-css" type="text/css">'.$preview_css.'</style>'.
							'<section class="pz-settings-preview-window" role="dialog" aria-labelledby="pz-settings-preview-title">'.
							'<div class="pz-settings-preview-handle" data-pz-preview-handle>'.
							'<span id="pz-settings-preview-title" class="pz-settings-preview-title">'.esc_html__('Preview', 'pz-linkcard' ).'</span>'.
							'<span class="pz-settings-preview-controls">'.
							'<button type="button" class="pz-settings-preview-button" data-pz-preview-mode data-no-overlay="1" aria-label="'.esc_attr__('Dock preview', 'pz-linkcard' ).'">_</button>'.
							'<button type="button" class="pz-settings-preview-button" data-pz-preview-close data-no-overlay="1" aria-label="'.esc_attr__('Close preview', 'pz-linkcard' ).'">×</button>'.
							'</span>'.
							'</div>'.
							'<div class="pz-settings-preview-body">'.
							'<div class="pz-settings-preview-palette">'.
							'<button type="button" class="pz-settings-preview-background pz-settings-preview-background-white" data-pz-preview-background="white" aria-label="'.esc_attr__('White background', 'pz-linkcard' ).'" title="'.esc_attr__('White background', 'pz-linkcard' ).'"></button>'.
							'<button type="button" class="pz-settings-preview-background pz-settings-preview-background-gray" data-pz-preview-background="gray" aria-label="'.esc_attr__('Gray background', 'pz-linkcard' ).'" title="'.esc_attr__('Gray background', 'pz-linkcard' ).'"></button>'.
							'<button type="button" class="pz-settings-preview-background pz-settings-preview-background-black" data-pz-preview-background="black" aria-label="'.esc_attr__('Black background', 'pz-linkcard' ).'" title="'.esc_attr__('Black background', 'pz-linkcard' ).'"></button>'.
							'<button type="button" class="pz-settings-preview-background pz-settings-preview-background-red" data-pz-preview-background="red" aria-label="'.esc_attr__('Red background', 'pz-linkcard' ).'" title="'.esc_attr__('Red background', 'pz-linkcard' ).'"></button>'.
							'<button type="button" class="pz-settings-preview-background pz-settings-preview-background-green" data-pz-preview-background="green" aria-label="'.esc_attr__('Green background', 'pz-linkcard' ).'" title="'.esc_attr__('Green background', 'pz-linkcard' ).'"></button>'.
							'<button type="button" class="pz-settings-preview-background pz-settings-preview-background-blue" data-pz-preview-background="blue" aria-label="'.esc_attr__('Blue background', 'pz-linkcard' ).'" title="'.esc_attr__('Blue background', 'pz-linkcard' ).'"></button>'.
							'<button type="button" class="pz-settings-preview-background pz-settings-preview-background-rectangles" data-pz-preview-background="rectangles" aria-label="'.esc_attr__('Rectangle pattern background', 'pz-linkcard' ).'" title="'.esc_attr__('Rectangle pattern background', 'pz-linkcard' ).'"></button>'.
							'<button type="button" class="pz-settings-preview-background pz-settings-preview-background-diagonal" data-pz-preview-background="diagonal" aria-label="'.esc_attr__('Diagonal pattern background', 'pz-linkcard' ).'" title="'.esc_attr__('Diagonal pattern background', 'pz-linkcard' ).'"></button>'.
							'<button type="button" class="pz-settings-preview-background pz-settings-preview-background-crosshatch" data-pz-preview-background="crosshatch" aria-label="'.esc_attr__('Crosshatch pattern background', 'pz-linkcard' ).'" title="'.esc_attr__('Crosshatch pattern background', 'pz-linkcard' ).'"></button>'.
							'</div>'.
							'<div class="pz-settings-preview-list">'.
							'<div class="pz-settings-preview-item">'.$make_preview_card('ex', 'https://popozure.info/pz-linkcard-preview', __('External Link Preview', 'pz-linkcard' ), __('External Link Preview', 'pz-linkcard' ), __('This is a sample of an external link card.', 'pz-linkcard' ) ).'</div>'.
							'<div class="pz-settings-preview-item pz-settings-preview-extra">'.$make_preview_card('ex', 'https://popozure.info/pz-linkcard-preview-2', __('External Link Preview', 'pz-linkcard' ), __('External Link Preview', 'pz-linkcard' ), __('This is a sample of an external link card.', 'pz-linkcard' ) ).'</div>'.
							'<div class="pz-settings-preview-item">'.$make_preview_card('in', home_url('/pz-linkcard-preview/' ), get_bloginfo('name' ), __('Internal Link Preview', 'pz-linkcard' ), __('This is a sample of an internal link card.', 'pz-linkcard' ) ).'</div>'.
							'<div class="pz-settings-preview-item pz-settings-preview-extra">'.$make_preview_card('in', home_url('/pz-linkcard-preview-2/' ), get_bloginfo('name' ), __('Internal Link Preview', 'pz-linkcard' ), __('This is a sample of an internal link card.', 'pz-linkcard' ) ).'</div>'.
							'</div>'.
							'<label class="pz-settings-preview-two-cards-control"><input type="checkbox" data-pz-preview-two-cards data-no-overlay="1" /> '.esc_html__('Show two cards for each type', 'pz-linkcard' ).'</label>'.
							'</div>'.
							'</section>';
	}

	$changelog		=	'';
	if	(!function_exists('wp_is_mobile' ) || !wp_is_mobile() ) {
		$changelog	=	file_get_contents($this->plugin_dir_path.'/readme.txt' );											// readme.txt を読み込み
		preg_match('/^== Changelog ==\s*(?<entries>(?:^= [^=\r\n]+ =\s*(?:(?!^= [^=\r\n]+ =|^== ).*\R?)*){1,5})/m', $changelog, $m );
		$changelog	=	$m['entries'] ?? '';
		$changelog	=	trim($changelog );
		$changelog	=	esc_html($changelog );
		$changelog	=	preg_replace('/^\* (.*)$/mi',				'<span class="pz-log-ja">*&ensp;$1</span>',								$changelog);	// 日本語文の行のインデント調整
		$changelog	=	preg_replace('/^  (.*)$/mi',				'<span class="pz-log-en">&ensp;&ensp;$1</span>',						$changelog);	// 英文の行のインデント調整
		$changelog	=	preg_replace('/= (.*) =\n/i',				'<h4>'.__('Version', 'pz-linkcard' ).' $1</h4>',						$changelog);	// バージョン番号の表記調整

		$changelog	=	preg_replace('/\[added\]\s*/i',				'<span class="pz-log-added">Added</span>&ensp;',						$changelog);	// 追加
		$changelog	=	preg_replace('/\[fixed\]\s*/i',				'<span class="pz-log-fixed">Fixed</span>&ensp;',						$changelog);	// 修正
		$changelog	=	preg_replace('/\[modified\]\s*/i',			'<span class="pz-log-modified">Modified</span>&ensp;',					$changelog);	// 変更
		$changelog	=	preg_replace('/\[removed\]\s*/i',			'<span class="pz-log-removed">Removed</span>&ensp;',					$changelog);	// 修正
		$changelog	=	preg_replace('/\[tested\]\s*/i',			'<span class="pz-log-tested">Tested</span>&ensp;',						$changelog);	// テスト
		$changelog	=	preg_replace('/\[pending\]\s*/i',			'<span class="pz-log-pending">Pending</span>&ensp;',					$changelog);	// 保留事項

		$changelog	=	preg_replace('/&ensp;&ensp;added:\s*/i',	'&ensp;&ensp;<span class="pz-log-added">Added</span>&ensp;',			$changelog);	// 追加
		$changelog	=	preg_replace('/&ensp;&ensp;fixed:\s*/i',	'&ensp;&ensp;<span class="pz-log-fixed">Fixed</span>&ensp;',			$changelog);	// 修正
		$changelog	=	preg_replace('/&ensp;&ensp;modified:\s*/i',	'&ensp;&ensp;<span class="pz-log-modified">Modified</span>&ensp;',		$changelog);	// 変更
		$changelog	=	preg_replace('/&ensp;&ensp;removed:\s*/i',	'&ensp;&ensp;<span class="pz-log-removed">Removed</span>&ensp;',		$changelog);	// 修正
		$changelog	=	preg_replace('/&ensp;&ensp;tested:\s*/i',	'&ensp;&ensp;<span class="pz-log-tested">Tested</span>&ensp;',			$changelog);	// テスト
		$changelog	=	preg_replace('/&ensp;&ensp;pending:\s*/i',	'&ensp;&ensp;<span class="pz-log-pending">Pending</span>&ensp;',		$changelog);	// テスト
		$changelog	=	preg_replace('/\[Pz3\]*/i',					$logo_pz3,																$changelog);	// テスト
		$changelog	=	preg_replace('/（Thanks\s+(?:(.*?)\s+)?@([^\s）]+)\s+on x\.com）/iu',				'<a href="https://x.com/$2" class="pz-thx" rel="external noopener noreferrer" target="_blank">'.						'Thanks<span class="pz-thx-name">$1</span>'.$logo_x. '<span class="pz-thx-account">@$2</span></a>', $changelog);	
		$changelog	=	preg_replace('/（Thanks\s+(?:(.*?)\s+)?@([^\s）]+)\s+on twitter\.com）/iu',			'<a href="https://twitter.com/$2" class="pz-thx" rel="external noopener noreferrer" target="_blank">'.					'Thanks<span class="pz-thx-name">$1</span>'.$logo_tw.'<span class="pz-thx-account">@$2</span></a>', $changelog);	
		$changelog	=	preg_replace('/（Thanks\s+(?:(.*?)\s+)?@([^\s）]+)\s+on wordpress\.org）/iu',		'<a href="https://wordpress.org/support/users/$2" class="pz-thx" rel="external noopener noreferrer" target="_blank">'.	'Thanks<span class="pz-thx-name">$1</span>'.$logo_wp.'<span class="pz-thx-account">@$2</span></a>', $changelog);	
		$changelog	=	preg_replace('/（Thanks\s+(?:(.*?)\s+)?(#[^\s）]+)\s+on popozure\.info）/iu',		'<a href="'.$pz_url.'" class="pz-thx" rel="external noopener noreferrer" target="_blank">'.								'Thanks<span class="pz-thx-name">$1</span>'.$logo_pz.'<span class="pz-thx-account">$2</span></a>', $changelog);	
		$changelog	=	str_replace("\r\n",		'<br>',	$changelog );															// 改行をBRタグに変換
		$changelog	=	str_replace("\r",		'<br>',	$changelog );															// 改行をBRタグに変換
		$changelog	=	str_replace("\n",		'<br>',	$changelog );															// 改行をBRタグに変換
		$changelog	=	'<div class="pz-basic-changelog">'.$changelog.'</div>';
	}

	// カスタムフィールドの一覧
	global	$wpdb;
	$query			=	"SELECT meta_key FROM $wpdb->postmeta WHERE SUBSTRING(meta_key, 1, 1 ) <> '_'  GROUP BY meta_key ORDER BY meta_key ASC";
	$result			=	$wpdb->get_results($query, ARRAY_N );
	$meta_list		=	null;
	$meta_list['']	=	'';
	foreach	($result as $item ) {
		$meta_list[$item[0]]	=	$item[0];
	}
	ksort($meta_list );

	// プロパティーズにコピー
	$prop		=	$this->options;
	if	(!array_key_exists('enclose-tag', $prop ) ) {
		$prop['enclose-tag']	=	!empty($prop['blockquote'] ) ? 'blockquote' : 'div';
	}

	$show_error			=	($menu_error		==	0	?	'style="display: none;"' : '' );
	$show_basic			=	'';
	$show_position		=	'';
	$show_display		=	'';
	$show_letter		=	'';
	$show_external		=	'';
	$show_internal		=	'';
	$show_check			=	'';
	$show_editor		=	'';
	$show_multisite		=	($menu_multi		==	0	?	'style="display: none;"' : '' );
	$show_advanced		=	'';
	$show_etc			=	'';
	$show_initialize	=	($menu_initialize	==	0	?	'style="display: none;"' : '' );
	$show_admin			=	($admin_mode		==	0	?	'style="display: none;"' : '' );


// 画面描画
echo	$html_style;
?>
<div class="pz-dashboard<?php echo $page_class; ?> wrap">
	<header class="pz-header">
		<?php
			echo	$html_plugin;
			echo	$html_title;
			echo	$html_standard_notice;
			if	($html_notice ) {
				echo	'<div class="pz-toast-container" role="status" aria-live="polite" style="display:none;">'.$html_notice.'</div>';
			}
		?>
		<div id="pz-tabbar-wrapper" class="pz-tabbar-wrapper">
			<button type="button" class="pz-tab-scroll pz-tab-left" aria-label="<?php esc_attr_e('Scroll tabs left', 'pz-linkcard' ); ?>"><span class="dashicons dashicons-arrow-left-alt2"></span></button>
			<div id="pz-tabbar" class="pz-tabs">
			<a class="pz-tab pz-red<?php echo $pz_tab_active('pz-error' ); ?>"			name="pz-error"			href="#pz-error"		<?php echo $show_error;			?>><?php esc_html_e('Error', 'pz-linkcard' ); ?></a>
			<a class="pz-tab pz-hide<?php echo $pz_tab_active('pz-basic' ); ?>"			name="pz-basic"			href="#pz-basic"		<?php echo $show_basic;			?>><?php esc_html_e('Basic', 'pz-linkcard' ); ?></a>
			<a class="pz-tab<?php echo $pz_tab_active('pz-position' ); ?>"				name="pz-position"		href="#pz-position"		<?php echo $show_position;		?>><?php esc_html_e('Position', 'pz-linkcard' ); ?></a>
			<a class="pz-tab<?php echo $pz_tab_active('pz-display' ); ?>"				name="pz-display"		href="#pz-display"		<?php echo $show_display;		?>><?php esc_html_e('Display', 'pz-linkcard' ); ?></a>
			<a class="pz-tab<?php echo $pz_tab_active('pz-letter' ); ?>"				name="pz-letter"		href="#pz-letter"		<?php echo $show_letter;		?>><?php esc_html_e('Letter', 'pz-linkcard' ); ?></a>
			<a class="pz-tab<?php echo $pz_tab_active('pz-external' ); ?>"				name="pz-external"		href="#pz-external"		<?php echo $show_external;		?>><?php esc_html_e('External Link', 'pz-linkcard' ); ?></a>
			<a class="pz-tab<?php echo $pz_tab_active('pz-internal' ); ?>"				name="pz-internal"		href="#pz-internal"		<?php echo $show_internal;		?>><?php esc_html_e('Internal Link', 'pz-linkcard' ); ?></a>
			<a class="pz-tab<?php echo $pz_tab_active('pz-check' ); ?>"					name="pz-check"			href="#pz-check"		<?php echo $show_check;			?>><?php esc_html_e('Link Check', 'pz-linkcard' ); ?></a>
			<a class="pz-tab<?php echo $pz_tab_active('pz-editor' ); ?>"				name="pz-editor"		href="#pz-editor"		<?php echo $show_editor;		?>><?php esc_html_e('Editor', 'pz-linkcard' ); ?></a>
			<a class="pz-tab pz-orange<?php echo $pz_tab_active('pz-multisite' ); ?>"	name="pz-multisite"		href="#pz-multisite"	<?php echo $show_multisite;		?>><?php esc_html_e('Multisite', 'pz-linkcard' ); ?></a>
			<a class="pz-tab<?php echo $pz_tab_active('pz-advanced' ); ?>"				name="pz-advanced"		href="#pz-advanced"		<?php echo $show_advanced;		?>><?php esc_html_e('Advanced', 'pz-linkcard' ); ?></a>
			<a class="pz-tab<?php echo $pz_tab_active('pz-etc' ); ?>"					name="pz-etc"			href="#pz-etc"			<?php echo $show_etc;			?>><?php esc_html_e('etc.', 'pz-linkcard' ); ?></a>
			<a class="pz-tab<?php echo $pz_tab_active('pz-initialize' ); ?>"			name="pz-initialize"	href="#pz-initialize"	<?php echo $show_initialize;	?>><?php esc_html_e('Initialize', 'pz-linkcard' ); ?></a>
			<a class="pz-tab pz-purple<?php echo $pz_tab_active('pz-admin' ); ?>"		name="pz-admin"			href="#pz-admin"		<?php echo $show_admin;			?>><?php esc_html_e('Admin', 'pz-linkcard' ); ?></a>
			</div>
			<button type="button" class="pz-tab-scroll pz-tab-right" aria-label="<?php esc_attr_e('Scroll tabs right', 'pz-linkcard' ); ?>"><span class="dashicons dashicons-arrow-right-alt2"></span></button>
		</div>
	</header>
	<article>
		<form action="" method="post">
			<?php wp_nonce_field('pz-settings' ); ?>
			<?php echo $html_input; ?>
			<div class="pz-submit-hide"><?php submit_button(); ?></div>
			<?php
				require_once('pz-linkcard-settings-error.php' );			// 「エラー」タブ
				require_once('pz-linkcard-settings-basic.php' );			// 「基本」タブ
				require_once('pz-linkcard-settings-position.php' );			// 「配置」タブ
				require_once('pz-linkcard-settings-display.php' );			// 「表示」タブ
				require_once('pz-linkcard-settings-letter.php' );			// 「文字」タブ
				require_once('pz-linkcard-settings-card.php' );				// 「外部リンク」「内部リンク」タブ
				require_once('pz-linkcard-settings-check.php' );			// 「リンク先の検査」タブ
				require_once('pz-linkcard-settings-editor.php' );			// 「エディター」タブ
				require_once('pz-linkcard-settings-multisite.php' );		// 「マルチサイト」タブ
				require_once('pz-linkcard-settings-advanced.php' );			// 「上級者向け」タブ
				require_once('pz-linkcard-settings-etc.php' );				// 「その他」タブ
				require_once('pz-linkcard-settings-initialize.php' );		// 「初期化」タブ
				require_once('pz-linkcard-settings-admin.php' );			// 「管理者」タブ
			?>
			<div class="pz-indicator"><div class="pz-button-top" title="<?php esc_attr_e('Scroll to the top', 'pz-linkcard' ); ?>"><?php echo wp_kses_post(__('^<br>Top', 'pz-linkcard' ) ); ?></div><div class="pz-tab-name">&nbsp;</div></div>
		</form>
		<?php echo $html_preview; ?>
		</article>
		<?php require_once dirname(__DIR__).'/includes/pz-color-picker.php'; ?>
</div>
<?php

// 数値にする
function	pz_TrimNum($val ) {
	$val		=	mb_convert_kana($val, 'n' );
	$val		=	strtolower($val );
	$val		=	preg_replace('/[^0-9]/', '', $val );
	if	($val	<>	null) {
		$val	=	intval($val );
	}
	return	$val;
}

// 数値にする
function	pz_TrimNumPx($val, $unit_percent = false ) {
	$val		=	mb_convert_kana($val, 'n' );			// 半角にする
	$val		=	strtolower($val );						// 小文字にする
	$unit		=	'px';									// 単位 px
	if (($unit_percent == true ) && (substr($val, -1 ) == '%' ) ) {
		$unit	=	'%';									// 単位 %
	}
	$val		=	preg_replace('/[^0-9]/', '', $val );	// 数字だけにする

	switch	($val ) {
	case	null:
	case	0:
		return	$val;
		break;
	}
	return		$val.$unit;
}

// ディレクトリ配下の使用サイズ
function	pz_GetDirSize($dir ) {
	$size		=	0;
	$handle		=	opendir($dir );
	if	(!$handle ) {
		return	null;
	}
	while ($file = readdir($handle ) ) {
		$fullpath = $dir.'/'.$file;
		if	(is_dir($fullpath ) ) {
			if	($file	!==	'..' && $file !== '.' ) {
				$size	+=	pz_GetDirSize($fullpath );
			}
		} else {
			$size		+=	filesize($fullpath );
		}
	}
	return	$size;
}

// 数値をKB、MB、TBの単位に変換
function	pz_GetSizeStringSi($val ) {
	$label	=	array('B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB' );
	for($i = 0; $val >= 1024 && $i < (count($label ) -1 ); $val /= 1024, $i++ );
	return	(round($val, 2 ).' '.$label[$i] );
}

// 数値をKB、MB、TBの単位に変換
function	pz_GetStringBytes($val ) {
	if	($val	==	1 ) {
		return	number_format($val ).' byte';
	} else {
		return	number_format($val ).' bytes';
	}
}

// 
function		pz_Select($prop, $name, $items ) {
	echo		'<select name="properties['.$name.']">';
	foreach		($items				as	$key => $value ) {
		echo	'<option value="'.$key.'" '.($prop[$name] == $key ? 'selected=selected' : '' ).'>'.$value.'</option>';
	}
	echo		'</select>';
}

//
function		pz_Checkbox($prop, $name, $explanatory_text ) {
	echo		'<label>';
	echo		'<input type="hidden"   name="properties['.$name.']" value="" />';
	echo		'<input type="checkbox" name="properties['.$name.']" value="1" '.($prop[$name] == '1' ? 'checked="checked" ' : '' ).'/>';
	echo		$explanatory_text;
	echo		'</label>';
}

//
function		pz_Option($prop, $name, $explanatory_text, $item_list ) {
	echo		'<label>'.$explanatory_text.'<select name="properties['.$name.']">';
	foreach		($item_list		as $item_value => $item_desc ) {
		echo	'<option value="'.$item_value.'"'.($prop[$name] == $item_value ? ' selected="selected"' : '' ).'">'.$item_desc.'</option>';
	}
	echo		'</select></label>';
}
// テキスト項目
function		echo_text($item_name, $item_value, $item_title, $item_notice, $item_class , $item_maxlength , $item_disabled, $item_only = false ) {
	$item_maxlength			=	intval(esc_attr($item_maxlength ) );
	if		($item_maxlength	<=	0 ) {
		$item_maxlength		=	'';
	}
	$html_result	=	'<input type="text" name="properties['.esc_attr($item_name ).']" value="'.esc_attr($item_value ).'" maxlength="'.esc_attr($item_maxlength ).'" class="'.esc_attr($item_class ).'" '.esc_attr($item_disabled ).' /><br><span class="pz-note">'.esc_attr($item_notice ).'</span></td>';
	$html_result	=	'<tr><th scope="row">'.$item_title.'</th><td>'.$html_result.'</tr>';
	echo			$html_result;
}

// チェックボックス
function		echo_checkbox($item_name, $item_value, $item_list, $item_title, $item_notice, $item_enabled = true ) {
	if			($item_enabled		==	false ) {
		echo		'<label>';
		echo		'<input type="checkbox" disabled="disabled" />';
		echo		$item_notice;
		echo		'</label>';
	} else {
		echo		'<label>';
		echo		'<input type="hidden"   name="properties['.$item_name.']" value="" />';
		echo		'<input type="checkbox" name="properties['.$item_name.']" value="1" '.($item_value == '1' ? 'checked="checked" ' : '' ).'/>';
		echo		$item_notice;
		echo		'</label>';
	}
}

// リスト項目
function		echo_list($item_name, $item_value, $item_list, $item_title, $item_notice, $item_enabled = true ) {
	$html_result		=	'<tr><th scope="row">'.esc_attr($item_title ).'</th><td>';
	if	($item_enabled	==	true ) {
		$html_result	.=	'<select name="properties['.esc_attr($item_name ).']">';
	} else {
		$html_result	.=	'<select name="" disabled="disabled">';
	}
	foreach		($item_list	as	$key => $value ) {
		$html_result	.=	'<option value="'.esc_attr($key ).'" '.(esc_attr($key ) == esc_attr($item_value ) ? 'selected="selected"' : '' ).'>'.esc_attr($value ).'</option>';
	}
	$html_result		.=	'</select>';
	if	($item_notice ) {
		$html_result	.=	'<br><span class="pz-note">'.esc_attr($item_notice ).'</span>';
	}
	$html_result		.=	'</td></tr>';
	echo					$html_result;
}

// コンボボックス項目
function	echo_combo($item_name, $item_value, $item_list, $item_title, $item_notice, $item_class, $item_maxlength, $item_disabled ) {
	$html_result		=	'<tr><th scope="row">'.esc_attr($item_title ).'</th><td>';
	if	($item_disabled ) {
		$html_result	.=	'<input type="text" name="" value="'.$item_value.'" disabled="disabled" class="pz-combo" />';
	} else {
		$html_result	.=	'<input type="text" name="properties['.esc_attr($item_name ).']" value="'.esc_attr($item_value ).'" maxlength="'.esc_attr($item_maxlength ).'" list="datalist-'.esc_attr($item_name ).'" class="pz-combo" />';
	}
	$html_result		.=	'<datalist id="datalist-'.$item_name.'">';
	foreach		($item_list	as	$key => $value ) {
		$html_result	.=	'<option value="'.esc_attr($key ).'" '.(esc_attr($key ) == esc_attr($item_value ) ? 'selected="selected"' : '' ).'>'.esc_attr($value ).'</option>';
	}
	$html_result		.=	'</datalist>';
	if	($item_notice ) {
		$html_result	.=	'<br><span class="pz-note">'.esc_attr($item_notice ).'</span>';
	}
	$html_result		.=	'</td></tr>';
	echo					$html_result;
}
