<?php
defined('ABSPATH' ) || wp_die;

trait Pz_LinkCard_Options {

	private static function pz_GetCardItemChoices($group) {
		$items = array(
			'' => __('None', 'pz-linkcard'),
			'n' => __('Site Name', 'pz-linkcard'),
			'd' => __('Domain', 'pz-linkcard'),
			't' => __('Title', 'pz-linkcard'),
			'u' => __('URL', 'pz-linkcard'),
			'p' => __('Date', 'pz-linkcard'),
			'q' => __('Date (right aligned)', 'pz-linkcard'),
			'c' => __('Category', 'pz-linkcard'),
			's' => __('Social Counts', 'pz-linkcard'),
		);
		if ($group === 'info') {
			$items['i'] = __('Site Icon', 'pz-linkcard');
			$items['a'] = __('Text', 'pz-linkcard');
		} else {
			$items['e'] = __('Excerpt', 'pz-linkcard');
			$items['i'] = __('Site Information', 'pz-linkcard');
		}
		return $items;
	}


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
		// Preserve the selected date pattern from the former numeric setting.
		if (!array_key_exists('date-pattern', $this->options) && array_key_exists('display-date', $this->options)) {
			$date_patterns = array('' => '', '0' => '', '1' => 'p', '2' => 'u', '3' => 'pu');
			$this->options['date-pattern'] = $date_patterns[(string) $this->options['display-date']] ?? 'p';
		}
		unset($this->options['display-date']);
		foreach (array('post-date-icon1' => 'date-icon-post', 'post-date-icon2' => 'date-icon-update') as $old_key => $new_key) {
			if (!array_key_exists($new_key, $this->options) && array_key_exists($old_key, $this->options)) {
				$this->options[$new_key] = $this->options[$old_key];
			}
			unset($this->options[$old_key]);
		}
		// Preserve existing visibility and placement when introducing the five slots.
		foreach (array('ex', 'in') as $prefix) {
			$info = array('i', 'n', 'a', ($this->options['sns-position'] ?? '') == 2 ? 's' : '', ($this->options['display-url'] ?? '') == 2 ? 'u' : '');
			$content = array('t', ($this->options['sns-position'] ?? '') == 1 ? 's' : '', ($this->options['display-url'] ?? '') == 1 ? 'u' : '', 'p', 'e');
			foreach (array('info' => $info, 'content' => $content) as $group => $items) {
				foreach ($items as $index => $item) {
					$key = $prefix.'-'.$group.'-type-'.($index + 1);
					if (!array_key_exists($key, $this->options)) $this->options[$key] = $item;
				}
			}
		}
		$this->options	=	array_merge(self::pz_GetDefaultOptions(), $this->options );
		$legacy_flg_agent	=	array_key_exists('flg-agent', $this->options ) ? $this->options['flg-agent'] : null;
		$this->options['user-agent']	=	$this->pz_GetUserAgentSelection($this->options['user-agent'], $legacy_flg_agent );
		$this->options['user-agent-text']	=	$this->pz_GetUserAgent($this->options['user-agent'] );
		unset($this->options['flg-agent'] );

		return	true;
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
			$this->pz_ScheduleClickLogCleanup();
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
