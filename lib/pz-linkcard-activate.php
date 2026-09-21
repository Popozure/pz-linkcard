<?php defined('ABSPATH' ) || wp_die; ?>
<?php
	if	($this->activate_now	==	true) {
		return;
	}
	$this->activate_now			=	true;

	// WP-CRONの割り込みを停止
	if	(wp_next_scheduled(self::CRON_CHECK ) ) {
		wp_clear_scheduled_hook(self::CRON_CHECK );
	}
	if	(wp_next_scheduled(self::CRON_ALIVE ) ) {
		wp_clear_scheduled_hook(self::CRON_ALIVE );
	}

	// オプション取得
	$stored_options	=	get_option(self::OPTION_NAME );
	$result			=	$this->pz_LoadOptions();
	$stored_version	=	isset($this->options['plugin-version'] ) ? $this->options['plugin-version'] : null;
	if	(is_array($stored_options ) && isset($stored_options['plugin-version'] ) ) {
		$stored_version	=	$stored_options['plugin-version'];
	}

	// 項目名称変更
	$rename_key	=	array(
		'old_key_name'			=>		'new_key_name',
		'flg-invalid'			=>		'error-mode',				// Ver.2.4.4 パラメータ名変更のため：エラー状態
		'invalid-url'			=>		'error-url',				// Ver.2.4.4 パラメータ名変更のため：エラーURL
		'invalid-time'			=>		'error-time',				// Ver.2.4.4 パラメータ名変更のため：エラー発生日時
		'color-title'			=>		'title-color',				// Ver.2.5.5 パラメータ名変更のため
		'outline-title'			=>		'title-outline',			// Ver.2.5.5 パラメータ名変更のため
		'outline-color-title'	=>		'title-outline-color',		// Ver.2.5.5 パラメータ名変更のため
		'size-title'			=>		'title-size',				// Ver.2.5.5 パラメータ名変更のため
		'height-title'			=>		'title-height',				// Ver.2.5.5 パラメータ名変更のため
		'trim-title'			=>		'title-trim',				// Ver.2.5.5 パラメータ名変更のため
		'nowrap-title'			=>		'title-nowrap',				// Ver.2.5.5 パラメータ名変更のため
		'color-url'				=>		'url-color',				// Ver.2.5.5 パラメータ名変更のため
		'outline-url'			=>		'url-outline',				// Ver.2.5.5 パラメータ名変更のため
		'outline-color-url'		=>		'url-outline-color',		// Ver.2.5.5 パラメータ名変更のため
		'size-url'				=>		'url-size',					// Ver.2.5.5 パラメータ名変更のため
		'height-url'			=>		'url-height',				// Ver.2.5.5 パラメータ名変更のため
		'trim-url'				=>		'url-trim',					// Ver.2.5.5 パラメータ名変更のため
		'nowrap-url'			=>		'url-nowrap',				// Ver.2.5.5 パラメータ名変更のため
		'color-excerpt'			=>		'excerpt-color',			// Ver.2.5.5 パラメータ名変更のため
		'outline-excerpt'		=>		'excerpt-outline',			// Ver.2.5.5 パラメータ名変更のため
		'outline-color-excerpt'	=>		'excerpt-outline-color',	// Ver.2.5.5 パラメータ名変更のため
		'size-excerpt'			=>		'excerpt-size',				// Ver.2.5.5 パラメータ名変更のため
		'height-excerpt'		=>		'excerpt-height',			// Ver.2.5.5 パラメータ名変更のため
		'trim-excerpt'			=>		'excerpt-trim',				// Ver.2.5.5 パラメータ名変更のため
		'color-more'			=>		'more-color',				// Ver.2.5.5 パラメータ名変更のため
		'outline-more'			=>		'more-outline',				// Ver.2.5.5 パラメータ名変更のため
		'outline-color-more'	=>		'more-outline-color',		// Ver.2.5.5 パラメータ名変更のため
		'size-more'				=>		'more-size',				// Ver.2.5.5 パラメータ名変更のため
		'height-more'			=>		'more-height',				// Ver.2.5.5 パラメータ名変更のため
		'color-info'			=>		'info-color',				// Ver.2.5.5 パラメータ名変更のため
		'outline-info'			=>		'info-outline',				// Ver.2.5.5 パラメータ名変更のため
		'outline-color-info'	=>		'info-outline-color',		// Ver.2.5.5 パラメータ名変更のため
		'size-info'				=>		'info-size',				// Ver.2.5.5 パラメータ名変更のため
		'height-info'			=>		'info-height',				// Ver.2.5.5 パラメータ名変更のため
		'trim-info'				=>		'info-trim',				// Ver.2.5.5 パラメータ名変更のため
		'color-added'			=>		'added-color',				// Ver.2.5.5 パラメータ名変更のため
		'outline-added'			=>		'added-outline',			// Ver.2.5.5 パラメータ名変更のため
		'outline-color-added'	=>		'added-outline-color',		// Ver.2.5.5 パラメータ名変更のため
		'size-added'			=>		'added-size',				// Ver.2.5.5 パラメータ名変更のため
		'height-added'			=>		'added-height',				// Ver.2.5.5 パラメータ名変更のため
		'css-url-add'			=>		'css-add-url',				// Ver.2.5.5 パラメータ名変更のため
		'nofollow'				=>		'flg-nofollow',				// Ver.2.5.6 パラメータ名変更のため
		'noopener'				=>		'flg-noopener',				// Ver.2.5.6 パラメータ名変更のため
		'ex-get'				=>		'ex-get-from',				// パラメータ名変更のため
		'in-get'				=>		'in-get-from',				// パラメータ名変更のため
		'flg-get-pid'			=>		'in-get-url',				// Ver.2.5.6 パラメータ名変更のため
		'ex-image'				=>		'ex-bg-image',				// Ver.2.6.1 パラメータ名変更のため
		'ex-hover-image'		=>		'ex-hover-bg-image',		// Ver.2.6.1 パラメータ名変更のため
		'in-image'				=>		'in-bg-image',				// Ver.2.6.1 パラメータ名変更のため
		'in-hover-image'		=>		'in-hover-bg-image',		// Ver.2.6.1 パラメータ名変更のため
		);
	foreach ($rename_key		as	$old => $new ) {
		if	(array_key_exists($old, $this->options ) ) {
			if	(!array_key_exists($new, $this->options ) ) {
				$this->options[$new]	=	$this->options[$old];
			}
			unset($this->options[$old] );
		}
	}

	// Read stored keys before defaults are merged so existing values survive the rename.
	foreach (array(
		'ex-favicon' => 'ex-siteicon',
		'ex-favicon-alt' => 'ex-siteicon-alt',
		'in-favicon' => 'in-siteicon',
		'in-favicon-alt' => 'in-siteicon-alt',
	) as $old => $new ) {
		if (is_array($stored_options ) && array_key_exists($old, $stored_options ) && !array_key_exists($new, $stored_options ) ) {
			$this->options[$new] = $stored_options[$old];
		}
		unset($this->options[$old] );
	}

	// Ver.2.6.0.5以降に名称変更した項目は、初期値をマージする前の保存値から転記する。
	foreach (array(
		'link-all'          => 'flg-linkall',
		'thumbnail-resize'  => 'flg-resize',
		'use-sitename'      => 'flg-use-sitename',
		'style-reset-img'   => 'flg-style-reset',
		'ex-image'          => 'ex-bg-image',
		'ex-get'            => 'ex-get-from',
		'in-image'          => 'in-bg-image',
		'in-get'            => 'in-get-from',
		'flg-unti-select'   => 'flg-anti-select',
	) as $old => $new ) {
		if	(is_array($stored_options ) && array_key_exists($old, $stored_options ) && !array_key_exists($new, $stored_options ) ) {
			$this->options[$new]	=	$stored_options[$old];
		}
		unset($this->options[$old] );
	}

	// 真偽値だった囲みタグ設定をタグ名へ移行する。
	if	(is_array($stored_options ) && array_key_exists('blockquote', $stored_options ) ) {
		if	(!array_key_exists('enclose-tag', $stored_options ) ) {
			$this->options['enclose-tag']	=	!empty($stored_options['blockquote'] ) ? 'blockquote' : 'div';
		}
		unset($this->options['blockquote'] );
	}

	// 共通のホバー効果をリンク種別ごとの設定へ移行
	$old_hover	=	is_array($stored_options ) && array_key_exists('hover', $stored_options )
		?	$stored_options['hover']
		:	($this->options['hover'] ?? null );
	$hover_migrated	=	false;
	if	($old_hover !== null ) {
		$add_alpha	=	function($color, $alpha = '88' ) {
			if	(preg_match('/^#([0-9a-f]{3})$/i', (string)$color, $matches ) ) {
				$hex	=	$matches[1];
				return	'#'.$hex[0].$hex[0].$hex[1].$hex[1].$hex[2].$hex[2].$alpha;
			}
			if	(preg_match('/^#([0-9a-f]{6})(?:[0-9a-f]{2})?$/i', (string)$color, $matches ) ) {
				return	'#'.$matches[1].$alpha;
			}
			return	$color;
		};

		foreach	(array('ex', 'in' ) as $t ) {
			switch	((string)$old_hover ) {
			case	'1':
				$this->options[$t.'-hover-bg-enabled']		=	1;
				$this->options[$t.'-hover-bg-color']		=	$add_alpha($this->options[$t.'-bg-color'] ?? '' );
				break;
			case	'2':
			case	'3':
			case	'4':
				$is_dark								=	(string)$old_hover === '3';
				$is_retract								=	(string)$old_hover === '4';
				$this->options[$t.'-hover-transform-enabled']	=	1;
				$this->options[$t.'-hover-transform-x']		=	$is_retract ? 4 : -4;
				$this->options[$t.'-hover-transform-y']		=	$is_retract ? 4 : -4;
				$this->options[$t.'-hover-shadow-enabled']	=	1;
				$this->options[$t.'-hover-shadow-color']	=	$is_dark ? 'rgba(0, 0, 0, 0.5)' : 'rgba(0, 0, 0, 0.25)';
				$this->options[$t.'-hover-shadow-x']		=	$is_dark ? 16 : ($is_retract ? 1 : 4 );
				$this->options[$t.'-hover-shadow-y']		=	$is_dark ? 16 : 4;
				$this->options[$t.'-hover-shadow-blur']		=	$is_dark ? 16 : 8;
				$this->options[$t.'-hover-shadow-spread']	=	0;
				$this->options[$t.'-hover-shadow-inset']	=	0;
				$this->options[$t.'-hover-transition']		=	0.3;
				break;
			case	'7':
				$this->options[$t.'-hover-border-enabled']	=	1;
				$this->options[$t.'-hover-border-style']	=	'none';
				$this->options[$t.'-hover-border-width']	=	0;
				$this->options[$t.'-hover-border-radius']	=	40;
				$this->options[$t.'-hover-transition']		=	0.3;
				break;
			}
		}
		unset($this->options['hover'] );
		$hover_migrated	=	true;
	}

	// 共通の続きを読むボタン形式をリンク種別ごとの設定へ移行
	$more_style_exists	=	is_array($stored_options ) && array_key_exists('more-style', $stored_options );
	$old_more_style		=	$more_style_exists ? $stored_options['more-style'] : null;
	if	(!$more_style_exists && array_key_exists('flg-more', $this->options ) ) {
		$more_style_exists	=	true;
		$old_more_style		=	array(
			'0'	=>	'',
			'1'	=>	'SMP',
			'3'	=>	'BTN',
			'4'	=>	'PSH',
			'5'	=>	'PSH',
		)[(string)$this->options['flg-more']] ?? null;
	}
	if	($more_style_exists ) {
		foreach	(array('ex', 'in' ) as $t ) {
			$this->options[$t.'-more-transform-enabled']	=	0;
			$this->options[$t.'-more-bg-enabled']			=	0;
			$this->options[$t.'-more-border-enabled']		=	0;
			$this->options[$t.'-more-shadow-enabled']		=	0;

			switch	((string)$old_more_style ) {
			case	'':
				$this->options[$t.'-more-text']			=	null;
				break;
			case	'SMP':
			case	'BTN':
			case	'PSH':
				$this->options[$t.'-more-bg-enabled']	=	1;
				$this->options[$t.'-more-bg-color']		=	!empty($this->options['more-bg-color'] )
					?	$this->options['more-bg-color']
					:	($this->options[$t.'-bg-color'] ?? '' );
				if	($old_more_style === 'BTN' || $old_more_style === 'PSH' ) {
					$this->options[$t.'-more-shadow-enabled']	=	1;
					$this->options[$t.'-more-shadow-color']		=	'rgba(0, 0, 0, 0.5)';
					$this->options[$t.'-more-shadow-x']			=	4;
					$this->options[$t.'-more-shadow-y']			=	4;
					$this->options[$t.'-more-shadow-blur']		=	4;
					$this->options[$t.'-more-shadow-spread']	=	0;
					$this->options[$t.'-more-shadow-inset']		=	0;
				}
				break;
			}
		}
	}
	unset($this->options['more-style'], $this->options['flg-more'] );

	// Ver.2.6.1で共通指定からリンク種別ごとの指定に変わった項目を移行
	if	(!$stored_version || version_compare($stored_version, '2.6.1', '<=' ) ) {
		foreach	(array(
			'link-all'			=>	'flg-linkall',
			'thumbnail-resize'	=>	'flg-resize',
			'use-sitename'		=>	'flg-use-sitename',
			'style-reset-img'	=>	'flg-style-reset',
		) as $old => $new ) {
			if	(array_key_exists($old, $this->options ) && !array_key_exists($new, $this->options ) ) {
				$this->options[$new]	=	$this->options[$old];
			}
			unset($this->options[$old] );
		}

		$old_radius	=	array_key_exists('radius', $this->options ) ? $this->options['radius'] : null;
		switch	((string)$old_radius ) {
		case	'1':
			$old_radius	=	'8px';
			break;
		case	'2':
			$old_radius	=	'4px';
			break;
		case	'3':
			$old_radius	=	'16px';
			break;
		case	'4':
			$old_radius	=	'32px';
			break;
		case	'5':
			$old_radius	=	'64px';
			break;
		}
		$old_thumbnail_border	=	array_key_exists('thumbnail-border', $this->options ) ? $this->options['thumbnail-border'] : null;
		$old_thumbnail_shadow	=	array_key_exists('thumbnail-shadow', $this->options ) ? $this->options['thumbnail-shadow'] : null;
		$old_thumbnail_radius	=	array_key_exists('thumbnail-radius', $this->options ) ? $this->options['thumbnail-radius'] : null;
		$old_shadow_inset		=	array_key_exists('shadow-inset', $this->options ) ? $this->options['shadow-inset'] : null;

		foreach	(array('ex', 'in' ) as $t ) {
			foreach	(array('color', 'style', 'width') as $item ) {
				if	(array_key_exists('border-'.$item, $this->options ) && !array_key_exists($t.'-border-'.$item, $this->options ) ) {
					$this->options[$t.'-border-enabled']	=	1;
					$this->options[$t.'-border-'.$item]		=	$this->options['border-'.$item];
				}
			}
			if	($old_radius !== null && !array_key_exists($t.'-border-radius', $this->options ) ) {
				$this->options[$t.'-border-enabled']	=	1;
				$this->options[$t.'-border-radius']		=	intval($old_radius );
			}
			if	(!empty($this->options['shadow'] ) ) {
				$this->options[$t.'-shadow-enabled']	=	1;
				$this->options[$t.'-shadow-color']	=	'#888888';
				$this->options[$t.'-shadow-x']		=	8;
				$this->options[$t.'-shadow-y']		=	8;
				$this->options[$t.'-shadow-blur']	=	8;
				$this->options[$t.'-shadow-spread']	=	0;
				if	(!array_key_exists($t.'-shadow-inset', $this->options ) ) {
					$this->options[$t.'-shadow-inset']	=	!empty($this->options['shadow-inset'] ) ? 1 : 0;
				}
			}
			if	($old_shadow_inset !== null ) {
				$this->options[$t.'-shadow-inset']	=	!empty($old_shadow_inset ) ? 1 : 0;
				if	(!empty($old_shadow_inset ) && !array_key_exists($t.'-shadow-enabled', $this->options ) ) {
					$this->options[$t.'-shadow-enabled']	=	1;
				}
			}
			if	($old_thumbnail_border !== null || $old_thumbnail_radius !== null ) {
				if	(!array_key_exists($t.'-thumbnail-border-enabled', $this->options ) ) {
					$this->options[$t.'-thumbnail-border-enabled']	=	!empty($old_thumbnail_border ) || !empty($old_thumbnail_radius ) ? 1 : 0;
				}
				if	(!array_key_exists($t.'-thumbnail-border-style', $this->options ) ) {
					$this->options[$t.'-thumbnail-border-style']	=	'solid';
				}
				if	(!array_key_exists($t.'-thumbnail-border-width', $this->options ) ) {
					$this->options[$t.'-thumbnail-border-width']	=	!empty($old_thumbnail_border ) ? 1 : 0;
				}
				if	(!array_key_exists($t.'-thumbnail-border-color', $this->options ) ) {
					$this->options[$t.'-thumbnail-border-color']	=	!empty($old_thumbnail_border ) ? 'rgba(0, 0, 0, 0.4)' : '';
				}
				if	($old_thumbnail_radius !== null && !array_key_exists($t.'-thumbnail-border-radius', $this->options ) ) {
					$this->options[$t.'-thumbnail-border-radius']	=	intval($old_thumbnail_radius );
				}
			}
			if	($old_thumbnail_shadow !== null ) {
				if	(!array_key_exists($t.'-thumbnail-shadow-enabled', $this->options ) ) {
					$this->options[$t.'-thumbnail-shadow-enabled']	=	!empty($old_thumbnail_shadow ) ? 1 : 0;
				}
				if	(!array_key_exists($t.'-thumbnail-shadow-color', $this->options ) ) {
					$this->options[$t.'-thumbnail-shadow-color']	=	'rgba(0, 0, 0, 0.7)';
				}
				if	(!array_key_exists($t.'-thumbnail-shadow-x', $this->options ) ) {
					$this->options[$t.'-thumbnail-shadow-x']	=	4;
				}
				if	(!array_key_exists($t.'-thumbnail-shadow-y', $this->options ) ) {
					$this->options[$t.'-thumbnail-shadow-y']	=	4;
				}
				if	(!array_key_exists($t.'-thumbnail-shadow-blur', $this->options ) ) {
					$this->options[$t.'-thumbnail-shadow-blur']	=	8;
				}
				if	(!array_key_exists($t.'-thumbnail-shadow-spread', $this->options ) ) {
					$this->options[$t.'-thumbnail-shadow-spread']	=	0;
				}
				if	(!array_key_exists($t.'-thumbnail-shadow-inset', $this->options ) ) {
					$this->options[$t.'-thumbnail-shadow-inset']	=	0;
				}
			}
		}
		unset($this->options['border-color'] );
		unset($this->options['border-style'] );
		unset($this->options['border-width'] );
		unset($this->options['border'] );
		unset($this->options['radius'] );
		unset($this->options['thumbnail-border'] );
		unset($this->options['thumbnail-shadow'] );
		unset($this->options['thumbnail-radius'] );
		unset($this->options['shadow-inset'] );
	}

	// 足りない項目
	foreach	(array('ex', 'in' ) as $t ) {
		if	(!array_key_exists($t.'-bg-enabled', $this->options ) ) {
			$this->options[$t.'-bg-enabled']		=	1;
		}
		if	(!array_key_exists($t.'-transform-enabled', $this->options ) ) {
			$this->options[$t.'-transform-enabled']	=	1;
		}
		if	(!array_key_exists($t.'-hover-bg-enabled', $this->options ) ) {
			$this->options[$t.'-hover-bg-enabled']	=	!empty($this->options[$t.'-hover-bg-color'] ) ? 1 : 0;
		}
		if	(!array_key_exists($t.'-border-enabled', $this->options ) ) {
			$this->options[$t.'-border-enabled']	=	1;
		}
		if	(array_key_exists('shadow', $this->options ) && !array_key_exists($t.'-shadow-enabled', $this->options ) ) {
			$this->options[$t.'-shadow-enabled']	=	$this->options['shadow'];
		}
		if	(array_key_exists('shadow-inset', $this->options ) && !array_key_exists($t.'-shadow-inset', $this->options ) ) {
			$this->options[$t.'-shadow-inset']	=	$this->options['shadow-inset'];
		}
	}

	foreach	(self::pz_GetOptionDefinitions()	as	$key => $value ) {
		if	(!array_key_exists($key, $this->options ) ) {
			$this->options[$key]	=	self::pz_GetDefaultOption($key );
		}
	}

	// DEFAULTSから削除された旧項目を参照する移行処理
	if	(version_compare($this->options['plugin-version'], '2.5.6', '<' ) ) {
		foreach	(array('title', 'excerpt', 'url', 'date', 'heading', 'more', 'info', 'added', 'cat' ) as $t ) {
			if	(array_key_exists($t.'-outline', $this->options ) && !$this->options[$t.'-outline'] ) {
				$this->options[$t.'-outline-color']	=	null;
			}
		}
	}
	if	(is_array($stored_options ) && array_key_exists('flg-ssl', $stored_options ) ) {
		if	(!array_key_exists('flg-sslverify', $stored_options ) ) {
			$this->options['flg-sslverify']	=	$stored_options['flg-ssl'] ? 0 : 1;
		}
		unset($this->options['flg-ssl'] );
	}

	// DEFAULTSに存在しない項目を削除
	$definitions	=	self::pz_GetOptionDefinitions();
	foreach	(array_keys($this->options ) as $key ) {
		if	(!array_key_exists($key, $definitions ) ) {
			unset($this->options[$key] );
		}
	}

	// 個別に設定しなおす
	if		(version_compare($this->options['plugin-version'],	'2.5.6', '<' ) ) {
		if	(intval($this->options['width'] ) == 0 ) {
			$this->options['width']					=	500;
			$this->options['width-unit']			=	'px';
		}
	}

	if	(array_key_exists('width', $this->options ) ) {
		$old_width	=	$this->options['width'];
		if	($old_width === null || $old_width === '' ) {
			$this->options['width']		=	null;
			$this->options['width-unit']	=	null;
		} else {
			$old_width	=	trim((string)$old_width );
			if	(substr($old_width, -1 ) === '%' ) {
				$this->options['width-unit']	=	'%';
			} elseif	(strtolower(substr($old_width, -2 ) ) === 'px' ) {
				$this->options['width-unit']	=	'px';
			} elseif	(!isset($this->options['width-unit'] ) || !in_array($this->options['width-unit'], array('px', '%' ), true ) ) {
				$this->options['width-unit']	=	self::pz_GetDefaultOption('width-unit' );
			}
			$this->options['width']	=	intval($old_width );
		}
	}

	foreach	(array('thumbnail-width', 'thumbnail-height', 'content-height' ) as $key ) {
		if	(array_key_exists($key, $this->options ) ) {
			$this->options[$key]	=	($this->options[$key] === null || $this->options[$key] === '' ) ? null : intval($this->options[$key] );
		}
	}

	// プラグインバージョンの更新
	$plugin_version_changed	=	($this->options['plugin-version']	<>	PZLKC_PLUGIN_VERSION );
	if		($plugin_version_changed ) {
		$this->options['plugin-version']	=	PZLKC_PLUGIN_VERSION;
		$this->options['css-count']			=	0;
	}

	// DBテーブル作成・更新＆メンテナンス
	require_once ('pz-linkcard-activate-db.php');

	// テンプレート側でMCEプラグイン一覧を上書きする場合があるため、実行優先度を下げる
	if	(empty($this->options['mce-priority'] ) && (get_template() == 'jin' ) ) {
		$this->options['mce-priority']	=	11;
	}
	if	($hover_migrated ) {
		unset($this->options['hover'] );
	}
	unset($this->options['more-style'], $this->options['flg-more'] );

	// オプションの更新
	$result		=	$this->pz_SaveOptions(!$plugin_version_changed );
	if	($result		==	false ) {
		return	false;
	}

	// スタイルシート生成
	$this->pz_SetStyle();
