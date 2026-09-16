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
	$result			=	$this->pz_LoadOptions();
	$stored_version	=	isset($this->options['plugin-version'] ) ? $this->options['plugin-version'] : null;

	// 項目名称変更
	$rename_key	=	array(
		'old_key_name'			=>		'new_key_name',
		'anker'					=>		'anchor',					// パラメータ名変更のため
		'opacity'				=>		'hover',					// パラメータ名変更のため
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
		);
	foreach ($rename_key		as	$old => $new ) {
		if	(array_key_exists($old, $this->options ) ) {
			if	(!array_key_exists($new, $this->options ) ) {
				$this->options[$new]	=	$this->options[$old];
			}
			unset($this->options[$old] );
		}
	}

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

		foreach	(array('ex', 'in', 'th' ) as $t ) {
			foreach	(array('color', 'style', 'width') as $item ) {
				if	(array_key_exists('border-'.$item, $this->options ) && !array_key_exists($t.'-border-'.$item, $this->options ) ) {
					$this->options[$t.'-border-enabled']	=	1;
					$this->options[$t.'-border-'.$item]		=	$this->options['border-'.$item];
				}
			}
			if	($old_radius !== null && !array_key_exists($t.'-border-radius', $this->options ) ) {
				$this->options[$t.'-border-enabled']	=	1;
				$this->options[$t.'-border-radius']		=	$old_radius;
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
					$this->options[$t.'-thumbnail-border-width']	=	!empty($old_thumbnail_border ) ? '1px' : '0px';
				}
				if	(!array_key_exists($t.'-thumbnail-border-color', $this->options ) ) {
					$this->options[$t.'-thumbnail-border-color']	=	!empty($old_thumbnail_border ) ? 'rgba(0, 0, 0, 0.4)' : '';
				}
				if	($old_thumbnail_radius !== null && !array_key_exists($t.'-thumbnail-border-radius', $this->options ) ) {
					$this->options[$t.'-thumbnail-border-radius']	=	$old_thumbnail_radius;
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
	foreach	(array('ex', 'in', 'th' ) as $t ) {
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

	// 個別に設定しなおす
	if		(version_compare($this->options['plugin-version'],	'2.5.6', '<' ) ) {
		// 続きを読むボタン
		if	(isset($this->options['flg-more'] ) ) {
			switch	($this->options['flg-more'] ) {
			case	'0':
				$this->options['more-style']		=	'';
				break;
			case	'1':
				$this->options['more-style']		=	'SMP';
				break;
			case	'3':
				$this->options['more-style']		=	'BTN';
				break;
			case	'4':
				$this->options['more-style']		=	'PSH';
				break;
			case	'5':
				$this->options['more-style']		=	'PSH';
				break;
			}
			unset($this->options['flg-more'] );
		}
			
		if	(intval($this->options['width'] ) == 0 ) {
			$this->options['width']					=	500;
			$this->options['width-unit']			=	'px';
		}

		// 縁取りの色をクリアする
		foreach		(array('title', 'excerpt', 'url', 'date', 'heading', 'more', 'info', 'added', 'cat' ) as $t ) {
			if	(array_key_exists($t.'-outline', $this->options ) && !$this->options[$t.'-outline'] ) {
				$this->options[$t.'-outline-color']	=	null;
			}
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

	// 2.6.1
	if	(array_key_exists('flg-ssl', $this->options ) ) {
		$this->options['flg-sslverify']	=	$this->options['flg-ssl'] ? 0 : 1 ;
		unset($this->options['flg-ssl'] );
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

	// オプションの更新
	$result		=	$this->pz_SaveOptions(!$plugin_version_changed );
	if	($result		==	false ) {
		return	false;
	}

	// スタイルシート生成
	$this->pz_SetStyle();
