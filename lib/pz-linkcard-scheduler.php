<?php defined('ABSPATH' ) || wp_die; ?>
<?php
	$action	=	isset($_POST['action'] ) ? sanitize_key(wp_unslash($_POST['action'] ) ) : '';
	if	($action ) {
		check_admin_referer('pz-linkcard-scheduler' );
		switch	($action ) {
		case	'run-'.self::CRON_CHECK:
			wp_clear_scheduled_hook(self::CRON_CHECK );
			$cron_log	=	'* execute: '.self::CRON_CHECK.PHP_EOL.PHP_EOL;
			$cron_log	.=	$this->schedule_hook_check();
			break;
		case	'run-'.self::CRON_ALIVE:
			wp_clear_scheduled_hook(self::CRON_ALIVE );
			$cron_log	=	'* execute: '.self::CRON_ALIVE.PHP_EOL.PHP_EOL;
			$cron_log	.=	$this->schedule_hook_alive();
			break;
		case	'run-'.self::CRON_CLICK_CLEANUP:
			wp_clear_scheduled_hook(self::CRON_CLICK_CLEANUP );
			$cron_log	=	'* execute: '.self::CRON_CLICK_CLEANUP.PHP_EOL.PHP_EOL;
			$cron_log	.=	$this->schedule_hook_click_cleanup();
			break;
		}
	}
	if	(isset($cron_log ) ) {
		$cron_log	=	esc_attr(esc_html($cron_log ) );
		$cron_log	=	str_replace('&amp;ensp;', '&ensp;', $cron_log );
		$cron_log	=	str_replace(PHP_EOL, '<br>', $cron_log );
	}

	$debug_mode	=	!empty($this->options['debug-mode'] );
	$survey_mode	=	!empty($this->options['survey-mode'] );
	$admin_mode	=	!empty($this->options['admin-mode'] );
	$develop_mode	=	isset($this->options['develop-mode'] ) ? intval($this->options['develop-mode'] ) : 0;
	$html_mode	=	($debug_mode ? '<span class="pz-infobar-env pz-infobar-env-debug">'.esc_html__('Debug Mode', 'pz-linkcard' ).'</span>' : '' ).
		($survey_mode ? '<span class="pz-infobar-env pz-infobar-env-survey">'.esc_html__('Survey Mode', 'pz-linkcard' ).'</span>' : '' ).
		($admin_mode ? '<span class="pz-infobar-env pz-infobar-env-admin">'.esc_html__('Admin Mode', 'pz-linkcard' ).'</span>' : '' ).
		($develop_mode === 1 ? '<span class="pz-infobar-env pz-infobar-env-develop">'.esc_html__('Development Environment', 'pz-linkcard' ).'</span>' : '' ).
		($develop_mode === 2 ? '<span class="pz-infobar-env pz-infobar-env-product">'.esc_html__('Production Environment', 'pz-linkcard' ).'</span>' : '' );
	$scheduler_url	=	admin_url('admin.php?page='.self::SCHEDULER_PAGE );
	$html_plugin	=	'<div id="pz-infobar"><div class="pz-infobar-left"><a href="'.esc_url($scheduler_url ).'" class="pz-infobar-plugin-logo"><img src="'.esc_url($this->plugin_dir_url.'img/pz-linkcard_logo.svg' ).'" width="156" height="28" alt="'.esc_attr(self::PLUGIN_NAME ).'"></a><span class="pz-infobar-plugin-ver pz-monospace">ver.'.esc_html(PZLKC_PLUGIN_VERSION ).'</span>'.$html_mode.'</div><div class="pz-infobar-right"><a href="'.esc_url($this->settings_url ).'" class="pz-infobar-switch" title="'.esc_attr__('Settings', 'pz-linkcard' ).'"><span class="pz-infobar-switch-icon"><span class="dashicons dashicons-admin-generic" style="vertical-align: text-bottom;"></span></span><span class="pz-infobar-switch-label">'.esc_html__('Settings', 'pz-linkcard' ).'</span></a></div></div>';
?>
<div id="pz-overlay-proc" class="pz-overlay-proc-active" style="display: flex;"><div class="pz-loader"></div></div>
<div class="wrap pz-dashboard pz-scheduler">
	<?php echo wp_kses_post($html_plugin ); ?>
	<h1><?php esc_html_e('Scheduler', 'pz-linkcard' ); ?></h1>
	<?php if (isset($cron_log ) ) { ?>
		<h2><?php esc_html_e('Execution Result', 'pz-linkcard' ); ?></h2>
		<div class="pz-cron-log"><?php echo wp_kses_post($cron_log ); ?></div>
	<?php } ?>
	<h2><?php esc_html_e('Hook List', 'pz-linkcard' ); ?></h2>
	<p>
		<label>
			<input type="checkbox" value="1" class="pz-cron-all" />
			<?php esc_html_e('View all schedules.', 'pz-linkcard' ); ?>
		</label>
	</p>
	<form method="post" class="pz-cron-list-wrap">
		<?php wp_nonce_field('pz-linkcard-scheduler' ); ?>
		<table class="pz-cron-list widefat striped">
			<thead>
				<tr>
					<th scope="col" class="pz-cron-head-run"><?php esc_html_e('Run', 'pz-linkcard' ); ?></th>
					<th scope="col" class="pz-cron-head-hook"><?php esc_html_e('Hook', 'pz-linkcard' ); ?></th>
					<th scope="col" class="pz-cron-head-next-time"><?php esc_html_e('Next Time', 'pz-linkcard' ); ?></th>
					<th scope="col" class="pz-cron-head-schedule"><?php esc_html_e('Schedule', 'pz-linkcard' ); ?></th>
				</tr>
			</thead>
			<tbody><?php echo $this->pz_RenderSchedulerRows(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></tbody>
		</table>
	</form>
</div>
