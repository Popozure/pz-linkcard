<?php
defined('ABSPATH' ) || wp_die;

trait Pz_LinkCard_Options {

	// 設定を取得する
	private	function	pz_LoadOptions() {
		// パラメーターを取得
		$this->options			=	get_option(self::OPTION_NAME );			// オプション値を取得

		if		(!$this->options || !is_array($this->options ) ) {
			$this->options	=	self::pz_GetDefaultOptions();
			$this->options['saved-date']	=	$this->now;		// 保存日時をセット
			$GLOBALS['pz_lkc_option_error']	=	'';

			$result			=	add_option(self::OPTION_NAME, $this->options, '', 'yes' );
			if	(!$result ) {
				$result		=	update_option(self::OPTION_NAME, $this->options );
			}

			if	(function_exists('wp_cache_delete' ) ) {
				wp_cache_delete(self::OPTION_NAME, 'options' );
				wp_cache_delete('alloptions', 'options' );
			}

			global	$wpdb;
			$saved_value	=	$wpdb->get_var($wpdb->prepare("SELECT option_value FROM {$wpdb->options} WHERE option_name = %s LIMIT 1", self::OPTION_NAME ) );
			if	($saved_value === null ) {
				$result		=	$wpdb->insert($wpdb->options, array(
					'option_name'	=>	self::OPTION_NAME,
					'option_value'	=>	maybe_serialize($this->options ),
					'autoload'		=>	'yes',
				), array('%s', '%s', '%s' ) );
				if	(!$result ) {
					$GLOBALS['pz_lkc_option_error']	=	trim($wpdb->last_error.' '.$wpdb->last_query );
				}
				if	(function_exists('wp_cache_delete' ) ) {
					wp_cache_delete(self::OPTION_NAME, 'options' );
					wp_cache_delete('alloptions', 'options' );
				}
			}
		}
		if (array_key_exists('get-domain-delay', $this->options ) ) {
			if (!array_key_exists('get-period-delay', $this->options ) ) {
				$this->options['get-period-delay'] = $this->options['get-domain-delay'];
			}
			unset($this->options['get-domain-delay'] );
		}
		$this->options	=	$this->pz_MigrateCardItemOrder($this->options );
		$this->options	=	array_merge(self::pz_GetDefaultOptions(), $this->options );
		$legacy_flg_agent	=	array_key_exists('flg-agent', $this->options ) ? $this->options['flg-agent'] : null;
		$this->options['user-agent']	=	$this->pz_GetUserAgentSelection($this->options['user-agent'], $legacy_flg_agent );
		$this->options['user-agent-text']	=	$this->pz_GetUserAgent($this->options['user-agent'] );
		unset($this->options['flg-agent'] );

		return	true;
	}

	private function pz_MigrateCardItemOrder($options ) {
		$legacy = array_merge(self::pz_GetDefaultOptions(), $options );
		foreach (array('ex', 'in' ) as $prefix ) {
			$date_has_priority = $prefix === 'in' && intval($legacy['display-date'] ) > 0;
			foreach (array('info', 'content' ) as $section ) {
				$key_prefix = $prefix.'-'.$section.'-type-';
				$has_order = false;
				for ($index = 1; $index <= 5; $index++ ) {
					if (array_key_exists($key_prefix.$index, $options ) ) {
						$has_order = true;
						break;
					}
				}
				if ($has_order ) {
					for ($index = 1; $index <= 5; $index++ ) {
						if (!array_key_exists($key_prefix.$index, $options ) ) {
							$options[$key_prefix.$index] = '';
						}
					}
					continue;
				}
				if ($section === 'info' ) {
					$items = array('i', !empty($legacy['flg-use-sitename'] ) ? 'n' : 'd', 'a' );
					if (intval($legacy['sns-position'] ) === 2 ) $items[] = 's';
					if (intval($legacy['display-url'] ) === 2 && !$date_has_priority ) $items[] = 'u';
				} else {
					$items = array('t' );
					if (intval($legacy['sns-position'] ) === 1 ) $items[] = 's';
					if (intval($legacy['display-url'] ) === 1 && !$date_has_priority ) $items[] = 'u';
					if ($date_has_priority ) $items[] = 'p';
					if (!empty($legacy['display-excerpt'] ) ) $items[] = 'e';
				}
				$items = array_pad(array_slice($items, 0, 5 ), 5, '' );
				for ($index = 1; $index <= 5; $index++ ) {
					$options[$key_prefix.$index] = $items[$index - 1];
				}
			}
		}
		return $options;
	}

	private function pz_GetCardItemOrder($prefix, $section ) {
		$allowed = $section === 'info'
			? array('', 'i', 'n', 'd', 'c', 's', 'a', 'u', 't', 'p', 'q' )
			: array('', 't', 'e', 'u', 'p', 'q', 'c', 's', 'n', 'i' );
		if (!in_array($prefix, array('ex', 'in' ), true ) || !in_array($section, array('info', 'content' ), true ) ) {
			return array_fill(0, 5, '' );
		}
		$order = array();
		for ($index = 1; $index <= 5; $index++ ) {
			$key = $prefix.'-'.$section.'-type-'.$index;
			$value = array_key_exists($key, $this->options ) ? $this->options[$key] : self::pz_GetDefaultOption($key );
			$order[] = is_string($value ) && in_array($value, $allowed, true ) ? $value : '';
		}
		return $order;
	}

	// 設定を更新する
	private	function	pz_SaveOptions($increment_css_count = true ) {
		$this->options	=	self::pz_RemoveThisLinkFallback($this->options );

		// 変更前
		$return_status	=	false;
		$before			=	get_option(self::OPTION_NAME, self::pz_GetDefaultOptions() );

		// 変更有無チェック
		if	($before <> $this->options ) {
			$return_status					=	true;			// 更新あり
			$this->options['saved-date']	=	$this->now;		// 保存日時をセット
		}

		// CSSバージョン（CSSキャッシュ対策）
		if	($increment_css_count ) {
			$this->options['css-count']			+=	1;
		}

		// プラグインバージョン
		$this->options['plugin-version']	=	PZLKC_PLUGIN_VERSION;

		// 必要ディレクトリが無い場合、作り直す
		require_once(__DIR__.'/../pz-linkcard-settings-setup.php' );

		// 設定の更新
		$result				=	update_option(self::OPTION_NAME, $this->options );
		if	(function_exists('wp_cache_delete' ) ) {
			wp_cache_delete(self::OPTION_NAME, 'options' );
			wp_cache_delete('alloptions', 'options' );
		}
		global	$wpdb;
		$saved_value		=	$wpdb->get_var($wpdb->prepare("SELECT option_value FROM {$wpdb->options} WHERE option_name = %s LIMIT 1", self::OPTION_NAME ) );
		$saved_options		=	($saved_value !== null ) ? maybe_unserialize($saved_value ) : array();
		if	($saved_options		!=	$this->options ) {
			$serialized_options	=	maybe_serialize($this->options );
			if	($saved_value === null ) {
				$result		=	$wpdb->insert($wpdb->options, array(
					'option_name'	=>	self::OPTION_NAME,
					'option_value'	=>	$serialized_options,
					'autoload'		=>	'yes',
				), array('%s', '%s', '%s' ) );
			} else {
				$result		=	$wpdb->update($wpdb->options, array(
					'option_value'	=>	$serialized_options,
				), array(
					'option_name'	=>	self::OPTION_NAME,
				), array('%s' ), array('%s' ) );
			}
			if	(function_exists('wp_cache_delete' ) ) {
				wp_cache_delete(self::OPTION_NAME, 'options' );
				wp_cache_delete('alloptions', 'options' );
			}
			$saved_value	=	$wpdb->get_var($wpdb->prepare("SELECT option_value FROM {$wpdb->options} WHERE option_name = %s LIMIT 1", self::OPTION_NAME ) );
			$saved_options	=	($saved_value !== null ) ? maybe_unserialize($saved_value ) : array();
		}
		if	($saved_options		!=	$this->options ) {
			$return_status	=	false;
		} else {
			$this->options	=	$saved_options;
			$this->pz_SyncCronSchedules();
		}
		// 返却
		return	$return_status;
	}

	// 設定を初期化する
	private	function	pz_InitializeOptions() {

		// 引き継ぐ設定値
		$takeover			=	array('saved-date', 'db-version', 'db-click-version' );
		if	(!empty($this->options['initialize-exception'] ) ) {
			// 初期化例外が有効の時に引き継ぐ設定値
			array_push($takeover, 'initialize-exception', 'debug-mode', 'admin-mode' );
		}

		// 引き継ぐ設定値を一時保存
		$takeover_options	=	array();
		foreach	($takeover as $key ) {
			if	(array_key_exists($key, $this->options ) ) {
				$takeover_options[$key]	=	$this->options[$key];
			}
		}

		// DEFAULTSに存在する項目を初期値で再構築
		$this->options	=	array();
		foreach	(self::DEFAULTS as $key => $value ) {
			$this->options[$key]	=	$value['default'];
		}

		// 一時保存した設定値を戻す
		foreach	($takeover_options as $key => $value ) {
			$this->options[$key]	=	$value;
		}

		// ブログID
		$this->options['multi-myid']		=	get_current_blog_id();

		// CSS更新用カウント
		$this->options['css-count']			=	self::pz_GetDefaultOption('css-count' );

		// プラグインのバージョン
		$this->options['plugin-version']	=	PZLKC_PLUGIN_VERSION;

		// 設定を更新する
		$result	=	$this->pz_SaveOptions();
		return	$result;
	}

}
