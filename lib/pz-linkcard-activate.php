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

	// 項目名称変更
	$rename_key	=	array(
		'old_key_name'			=>		'new_key_name',
		'anker'					=>		'anchor',					// パラメータ名変更のため
		'opacity'				=>		'hover',					// パラメータ名変更のため
		'border-color'			=>		'ex-border-color',			// パラメータ細分化のため
		'border-color'			=>		'in-border-color',			// パラメータ細分化のため
		'border-color'			=>		'th-border-color',			// パラメータ細分化のため
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
		'title-trim'			=>		'title-length',				// Ver.2.5.6 パラメータ名変更のため
		'excerpt-trim'			=>		'excerpt-length',			// Ver.2.5.6 パラメータ名変更のため
		'flg-get-pid'			=>		'in-get-url',				// Ver.2.5.6 パラメータ名変更のため
		);
	foreach ($rename_key		as	$old => $new ) {
		if	(array_key_exists($old, $this->options ) && !array_key_exists($new, $this->options ) ) {
			$this->options[$new]	=	$this->options[$old];
			unset($this->options[$old] );
		}
	}

	// 足りない項目
	foreach	(Self::DEFAULTS		as	$key => $value ) {
		if	(!array_key_exists($key, $this->options ) ) {
			$this->options[$key]	=	Self::DEFAULTS[$key];
		}
	}

	// 個別に設定しなおす
	if		(version_compare($this->options['plugin-version'],	'2.5.6', '<' ) ) {
		// 角の丸め
		switch	($this->options['radius'] ) {
		case	'2':
			$this->options['radius']			=	'4px';
			break;
		case	'1':
			$this->options['radius']			=	'8px';
			break;
		case	'3':
			$this->options['radius']			=	'16px';
			break;
		case	'4':
			$this->options['radius']			=	'32px';
			break;
		case	'5':
			$this->options['radius']			=	'64px';
			break;
		}
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
			$this->options['width']				=	'500px';
		}

		// 縁取りの色をクリアする
		foreach		(array('title', 'excerpt', 'url', 'date', 'heading', 'more', 'info', 'added', 'cat' ) as $t ) {
			if	(array_key_exists($t.'-outline', $this->options ) && !$this->options[$t.'-outline'] ) {
				$this->options[$t.'-outline-color']	=	null;
			}
		}
	}

	// プラグインバージョンの更新とCSSの補助バージョンのリセット
	if		($this->options['plugin-version']	<>	PZLKC_PLUGIN_VERSION ) {
		if	($this->options['css-count']		>	5 ) {
			$this->options['css-count']		=	0;
		}
		$this->options['plugin-version']	=	PZLKC_PLUGIN_VERSION;
	}

	// DBテーブル作成・更新＆メンテナンス
	require_once ('pz-linkcard-activate-db.php');

	// テンプレート側でMCEプラグイン一覧を上書きする場合があるため、実行優先度を下げる
	if	(empty($this->options['mce-priority'] ) && (get_template() == 'jin' ) ) {
		$this->options['mce-priority']	=	11;
	}

	// オプションの更新
	$result		=	$this->pz_SaveOptions();
	if	($result		==	false ) {
		return	false;
	}

	// スタイルシート生成
	$this->pz_SetStyle();
