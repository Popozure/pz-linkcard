<?php
defined('ABSPATH' ) || wp_die;

trait Pz_LinkCard_Widget {

	private	function	pz_GetDashboardWidgetReturnUrl() {
		return	add_query_arg('pz_lkc_focus', 'dashboard_widget', admin_url('index.php' ) ).'#pz_linkcard_dashboard_widget';
	}

	private	function	pz_IsDashboardWidgetReturnUrl($url ) {
		$query	= wp_parse_url((string) $url, PHP_URL_QUERY );
		if	(!is_string($query ) || '' === $query ) {
			return	false;
		}
		$params	= array();
		parse_str($query, $params );
		return	isset($params['pz_lkc_focus'] ) && 'dashboard_widget' === $params['pz_lkc_focus'];
	}

	// カード編集画面などから戻ったときにダッシュボードウィジェットへ移動
	public	function	action_dashboard_widget_focus() {
		$focus	= isset($_GET['pz_lkc_focus'] ) ? sanitize_key(wp_unslash($_GET['pz_lkc_focus'] ) ) : '';
		if	('dashboard_widget' !== $focus ) {
			return;
		}
		?>
		<script>
		(function() {
			if ('scrollRestoration' in window.history) {
				window.history.scrollRestoration = 'manual';
			}
			var attempts = 0;
			var timer = window.setInterval(function() {
				var widget = document.getElementById('pz_linkcard_dashboard_widget');
				attempts++;
				if (widget) {
					var adminBar = document.getElementById('wpadminbar');
					var offset = (adminBar ? adminBar.getBoundingClientRect().height : 0) + 12;
					var top = window.scrollY + widget.getBoundingClientRect().top - offset;
					window.scrollTo(0, Math.max(0, top));
				}
				if (attempts >= 10) {
					window.clearInterval(timer);
				}
			}, 100);
		})();
		</script>
		<?php
	}

	// WordPressダッシュボードに概要を追加
	public	function	action_wp_dashboard_setup() {
		if	(!current_user_can('manage_options' ) ) {
			return;
		}
		add_meta_box(
			'pz_linkcard_dashboard_widget',
			self::PLUGIN_NAME,
			array($this, 'dashboard_widget' ),
			get_current_screen(),
			'normal',
			'core',
			array('__widget_basename' => self::PLUGIN_NAME )
		);
	}

	// ダッシュボードウィジェット
	public	function	dashboard_widget() {
		global	$wpdb;

		$dashboard_return_url	= $this->pz_GetDashboardWidgetReturnUrl();
		$count_list	= array_fill_keys(array('all', 'internal', 'external', 'modify', 'unlink', 'unregistered' ), 0 );
		$recent_registrations	= array();
		$recent_clicks	= array();
		$card_table_exists	= ($wpdb->get_var($wpdb->prepare('SHOW TABLES LIKE %s', $this->db_card ) ) === $this->db_card );
		if	($card_table_exists ) {
			$counts	= $wpdb->get_row($wpdb->prepare(
				"SELECT COUNT(*) AS count_all,
					COUNT(CASE WHEN domain = %s THEN 1 END) AS count_internal,
					COUNT(CASE WHEN domain <> %s THEN 1 END) AS count_external,
					COUNT(CASE WHEN alive_result <> update_result THEN 1 END) AS count_modify,
					COUNT(CASE WHEN alive_result IS NOT NULL AND alive_result <> 0 AND (alive_result < 100 OR alive_result >= 400) THEN 1 END) AS count_unlink,
					COUNT(CASE WHEN regist_time IS NULL OR regist_time = 0 THEN 1 END) AS count_unregistered
				FROM $this->db_card",
				$this->domain,
				$this->domain
			) );
			if	($counts ) {
				$count_list['all']		= intval($counts->count_all );
				$count_list['internal']	= intval($counts->count_internal );
				$count_list['external']	= intval($counts->count_external );
				$count_list['modify']	= intval($counts->count_modify );
				$count_list['unlink']	= intval($counts->count_unlink );
				$count_list['unregistered']	= intval($counts->count_unregistered );
			}
			$recent_registrations	= $wpdb->get_results(
				"SELECT id AS card_id, regist_time, title, url
				FROM $this->db_card
				WHERE regist_time > 0
				ORDER BY regist_time DESC, id DESC
				LIMIT 5"
			);

			$click_table_exists	= ($wpdb->get_var($wpdb->prepare('SHOW TABLES LIKE %s', $this->db_click ) ) === $this->db_click );
			if	($click_table_exists ) {
				$recent_clicks	= $wpdb->get_results(
					"SELECT click.click_time, card.id AS card_id, card.title, card.url
					FROM $this->db_click AS click
					 LEFT JOIN $this->db_card AS card ON BINARY card.url_key = BINARY click.url_key
					ORDER BY click.click_time DESC, click.click_id DESC
					LIMIT 5"
				);
			}
		}

		$labels	= array(
			'all'		=> __('All', 'pz-linkcard' ),
			'external'	=> __('External', 'pz-linkcard' ),
			'internal'	=> __('Internal', 'pz-linkcard' ),
			'modify'	=> __('HTTP Status Changed', 'pz-linkcard' ),
			'unlink'	=> __('Broken Link', 'pz-linkcard' ),
			'unregistered'	=> __('Unregistered', 'pz-linkcard' ),
		);
		$label_icons	= array(
			'all'		=> 'admin-page',
			'external'	=> 'external',
			'internal'	=> 'admin-home',
			'modify'	=> 'update',
			'unlink'	=> 'editor-unlink',
			'unregistered'	=> 'welcome-add-page',
		);
		?>
		<p class="pz-dashboard-widget-version"><?php esc_html_e('Version', 'pz-linkcard' ); ?> <?php echo esc_html(PZLKC_PLUGIN_VERSION ); ?></p>
		<?php if	(!empty($this->options['error-mode'] ) ) {
			$error_settings_url	= add_query_arg(array('tab' => 'pz-error', 'return_url' => $dashboard_return_url ), $this->settings_url );
		?>
			<a href="<?php echo esc_url($error_settings_url ); ?>" class="pz-dashboard-widget-error"><?php esc_html_e('There is a URL parameter error.', 'pz-linkcard' ); ?></a>
		<?php } ?>
		<dl class="pz-dashboard-widget-counts">
			<?php foreach	($labels as $key => $label ) {
				$manager_url	= add_query_arg(array('filter' => $key, 'return_url' => $dashboard_return_url ), $this->cacheman_url );
			?>
				<dt><span class="dashicons dashicons-<?php echo esc_attr($label_icons[$key] ); ?>" aria-hidden="true"></span><span class="pz-dashboard-widget-count-label"><?php echo esc_html($label ); ?></span></dt><dd><a href="<?php echo esc_url($manager_url ); ?>"><?php echo esc_html(sprintf('%s件', number_format_i18n($count_list[$key] ) ) ); ?></a></dd>
			<?php } ?>
		</dl>
		<div class="pz-dashboard-widget-recent pz-dashboard-widget-registrations">
			<div class="pz-dashboard-widget-recent-header">
				<h3><?php esc_html_e('Recent Registrations', 'pz-linkcard' ); ?></h3>
				<a class="pz-dashboard-widget-more" href="<?php echo esc_url(add_query_arg(array('orderby' => 'regist_time', 'order' => 'desc', 'return_url' => $dashboard_return_url ), $this->cacheman_url ) ); ?>"><?php esc_html_e('View List', 'pz-linkcard' ); ?></a>
			</div>
			<?php if	($recent_registrations ) { ?>
				<ul>
					<?php foreach	($recent_registrations as $registration ) {
						$title	= $registration->title ? $registration->title : $this->pz_DecodeURL($registration->url );
						$edit_url	= add_query_arg(array(
							'action'		=> 'edit',
							'cache_id'		=> intval($registration->card_id ),
							'return_url'	=> $dashboard_return_url,
						), $this->cacheman_url );
					?>
						<li><time datetime="<?php echo esc_attr(date('c', intval($registration->regist_time ) ) ); ?>"><?php echo esc_html(date_i18n(__('M j, H:i', 'pz-linkcard' ), intval($registration->regist_time ) ) ); ?></time><a href="<?php echo esc_url($edit_url ); ?>" title="<?php echo esc_attr($title ); ?>" class="pz-dashboard-widget-item-link"><span class="pz-dashboard-widget-item-title"><?php echo esc_html($title ); ?></span><span class="dashicons dashicons-welcome-write-blog" aria-hidden="true"></span></a></li>
					<?php } ?>
				</ul>
			<?php } else { ?>
				<p>—</p>
			<?php } ?>
		</div>
		<div class="pz-dashboard-widget-recent pz-dashboard-widget-clicks">
			<div class="pz-dashboard-widget-recent-header">
				<h3><?php esc_html_e('Recent Clicks', 'pz-linkcard' ); ?></h3>
				<?php if	(!empty($this->options['flg-click-log'] ) ) { ?>
					<a class="pz-dashboard-widget-more" href="<?php echo esc_url(add_query_arg(array('action' => 'click-log', 'return_url' => $dashboard_return_url ), $this->cacheman_url ) ); ?>"><?php esc_html_e('View List', 'pz-linkcard' ); ?></a>
				<?php } ?>
			</div>
			<?php if	(empty($this->options['flg-click-log'] ) ) {
				$settings_return_url = preg_replace('/#.*$/', '', $dashboard_return_url);
				$click_settings_url	= add_query_arg(array('tab' => 'pz-check', 'return_url' => $settings_return_url ), $this->settings_url ).'#pz-click-log-settings';
				$click_settings_link	= '<a href="'.esc_url($click_settings_url ).'">'.esc_html__('Click count recording', 'pz-linkcard' ).'</a>';
			?>
				<p class="pz-dashboard-widget-notice"><?php echo wp_kses_post(sprintf(__('Enable %s to display recent clicks.', 'pz-linkcard' ), $click_settings_link ) ); ?></p>
			<?php } elseif	($recent_clicks ) { ?>
				<ul>
					<?php foreach	($recent_clicks as $click ) {
						$title	= $click->title ? $click->title : $this->pz_DecodeURL($click->url );
						$click_url	= add_query_arg(array(
							'action'		=> 'click-log',
							'cache_id'		=> intval($click->card_id ),
							'return_url'	=> $dashboard_return_url,
						), $this->cacheman_url );
					?>
						<li><time datetime="<?php echo esc_attr(date('c', intval($click->click_time ) ) ); ?>"><?php echo esc_html(date_i18n(__('M j, H:i', 'pz-linkcard' ), intval($click->click_time ) ) ); ?></time><a href="<?php echo esc_url($click_url ); ?>" title="<?php echo esc_attr($title ); ?>" class="pz-dashboard-widget-item-link"><span class="pz-dashboard-widget-item-title"><?php echo esc_html($title ); ?></span><span class="dashicons dashicons-chart-bar" aria-hidden="true"></span></a></li>
					<?php } ?>
				</ul>
			<?php } else { ?>
				<p>—</p>
			<?php } ?>
		</div>
		<div class="pz-dashboard-widget-actions">
			<span class="pz-dashboard-widget-action"><span class="dashicons dashicons-archive" aria-hidden="true"></span><a href="<?php echo esc_url(add_query_arg('return_url', $dashboard_return_url, $this->cacheman_url ) ); ?>"><?php esc_html_e('Manager', 'pz-linkcard' ); ?></a></span>
			<span class="pz-dashboard-widget-action"><span class="dashicons dashicons-admin-generic" aria-hidden="true"></span><a href="<?php echo esc_url(add_query_arg('return_url', $dashboard_return_url, $this->settings_url ) ); ?>"><?php esc_html_e('Settings', 'pz-linkcard' ); ?></a></span>
			<a class="pz-dashboard-widget-credit" href="https://popozure.info/pz-linkcard" target="_blank" rel="noopener">popozure.info</a>
		</div>
		<?php
	}
}
