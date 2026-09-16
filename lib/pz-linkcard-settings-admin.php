<?php defined('ABSPATH' ) || wp_die; ?>
<?php
	switch	($action) {
	case	'run-'.self::CRON_CHECK:
		wp_clear_scheduled_hook(self::CRON_CHECK );
		$cron_log		=	'* execute: '.self::CRON_CHECK.PHP_EOL.PHP_EOL;
		$cron_log		.=	$this->schedule_hook_check();
		break;
	case	'run-'.self::CRON_ALIVE:
		wp_clear_scheduled_hook(self::CRON_ALIVE );
		$cron_log		=	'* execute: '.self::CRON_ALIVE.PHP_EOL.PHP_EOL;
		$cron_log		.=	$this->schedule_hook_alive();
		break;
	}

	// WP-Cron の実行結果
	if (isset($cron_log ) ) {
		$cron_log			=	esc_attr(esc_html($cron_log ) );
		$cron_log			=	str_replace(PHP_EOL, '<br>', $cron_log );
	}

	// WP-Cronスケジュールを取得
	$cron_schedule	=	_get_cron_array();
	$schedules		=	wp_get_schedules();		// タイミングの定数（604800→週1回 など）
	foreach			($cron_schedule	as $timestamp	=> $cronhooks ) {
		foreach		($cronhooks		as $hook		=> $dings ) {
			foreach	($dings			as $signature	=> $data ) {
				if	(($hook == self::CRON_ALIVE ) || ($hook == self::CRON_CHECK ) ) {
					$myjob		=	true;
					$button		=	'class="pz-button-sure" value="run-'.$hook.'"';
					$display	=	'class="pz-cron-list-lkc"';
				} else {
					$myjob		=	false;
					$button		=	'class="pz-button-disabled" disabled="disabled"';
					$display	=	'class="pz-cron-list-other"';
				}
				$schedule		=	isset($schedules[$data['schedule']]['display'] ) ? $schedules[$data['schedule']]['display'] : $data['schedule'] ;
				$interval		=	isset($data['interval'] ) ? $data['interval'].' '.__('Sec.', 'pz-linkcard' ) : null ;
				$cron_list[]	=	array(
					//'key'			=>	($myjob ? '1' : '2' ).$hook,
					'key'			=>	$timestamp,
					'hook'			=>	esc_html($hook ),
					'myjob'			=>	$myjob,
					'next_time'		=>	esc_html(get_date_from_gmt( date( 'Y-m-d H:i:s', $timestamp ), PZLKC_DATETIME_FORMAT ) ),
					'schedule'		=>	esc_html($schedule ),
					'interval'		=>	$interval,
					'button'		=>	'<button type="submit" name="action" '.$button.' onclick="return confirm(\''.esc_js(__('Are you sure?', 'pz-linkcard' ) ).'\' );">'.esc_html__('Run Now', 'pz-linkcard' ).'</button>',
					'display'		=>	$display
					);
			}
		}
	}
	asort($cron_list );

?>
<div class="pz-page pz-page-admin<?php echo $pz_page_active('pz-admin' ); ?>" id="pz-admin">
	<div class="pz-admin-notice"><?php esc_html_e('Do not use normally as it can be set to incapacitate.', 'pz-linkcard' ); ?></div>
	<div class="pz-submit-float"><?php submit_button(); ?></div>

	<h2><?php esc_html_e('Information', 'pz-linkcard' ); ?></h2>
	<table class="form-table">
		<tr>
			<th scope="row"><?php esc_html_e('WordPress Version', 'pz-linkcard' ); ?></th>
			<td><input type="text" size="20" value="<?php echo esc_attr(get_bloginfo('version' ) ); ?>" readonly="readonly" ?></td>
		</tr>
		<tr>
			<th scope="row"><?php esc_html_e('PHP Version', 'pz-linkcard' ); ?></th>
			<td><input type="text" size="20" value="<?php echo esc_attr(phpversion() ); ?>" readonly="readonly" ?></td>
		</tr>
		<tr>
			<th scope="row"><?php esc_html_e('DBMS Version', 'pz-linkcard' ); ?></th>
			<td><input type="text" size="20" value="<?php global $wpdb; echo esc_attr($wpdb->db_version() ); ?>" readonly="readonly" ?></td>
		</tr>
		<tr>
			<th scope="row"><?php esc_html_e('Table Name', 'pz-linkcard' ); ?></th>
			<td><input type="text" size="40" value="<?php echo esc_attr($this->db_name ); ?>" readonly="readonly" /></td>
		</tr>
		<tr>
			<th scope="row"><?php esc_html_e('Plugin Version', 'pz-linkcard' ); ?></th>
			<td>
				<input type="text" name="properties[plugin-version]" value="<?php echo esc_attr(PZLKC_PLUGIN_VERSION ); ?>" size="10" readonly="readonly" <?php if ($prop['admin-mode'] ) { echo	'ondblclick="this.readOnly=false;" '; }?>/>
			</td>
		</tr>
		<tr>
			<th scope="row"><?php esc_html_e('Plugin DB Version', 'pz-linkcard' ); ?></th>
			<td>
				<input type="text" name="properties[db-version]"     value="<?php echo esc_attr($prop['db-version'] ); ?>"     size="40" readonly="readonly" <?php if ($prop['admin-mode'] ) { echo	'ondblclick="this.readOnly=false;" '; }?>/>
			</td>
		</tr>
		<tr>
			<th scope="row"><?php esc_html_e('Size of the options', 'pz-linkcard' ); ?></th>
			<td>
				<input type="text" size="10" value="<?php echo strlen(serialize($this->options) ); ?>"     size="40" readonly="readonly" <?php if ($prop['admin-mode'] ) { echo	'ondblclick="this.readOnly=false;" '; }?>/>
			</td>
		</tr>
	</table>

	<h2><?php esc_html_e('for Debug', 'pz-linkcard' ); ?></h2>
	<table class="form-table">
		<tr>
			<th scope="row"><?php esc_html_e('Reboot This Plugin', 'pz-linkcard' ); ?></th>
			<td>
				<button type="submit" name="action" value="init-plugin" class="pz-button-sure" onclick="return confirm('<?php echo esc_js(__('Are you sure?', 'pz-linkcard' ) ); ?>');"><?php esc_html_e('Run', 'pz-linkcard' ); ?></button>
				&ensp;<span><?php echo	__('Perform initial setup.', 'pz-linkcard' ).'&nbsp;'.__('"Settings" will not be initialized.', 'pz-linkcard' ); ?></span>
			</td>
		</tr>
		<tr>
			<th scope="row"><?php esc_html_e('DB Update Mode', 'pz-linkcard' ); ?></span></th>
			<td>
				<label>
					<input type="hidden"   name="properties[debug-nocache]" value="" />
					<input type="checkbox" name="properties[debug-nocache]" value="1" <?php checked($prop['debug-nocache'] ); ?> class="pz-tab-show" />
					<?php esc_html_e('Forced access to links even if they are recorded in DB.', 'pz-linkcard' ); ?>
				</label>
			</td>
		</tr>
	</table>
	<?php submit_button(); ?>

	<h2><?php esc_html_e('Error Settings', 'pz-linkcard' ); ?></h2>
	<table class="form-table">
		<tr>
			<th scope="row"><?php esc_html_e('Error Conditions', 'pz-linkcard' ); ?></th>
			<td>
				<label>
					<input type="hidden"   name="properties[error-mode]" value="" />
					<input type="checkbox" name="properties[error-mode]" value="1" <?php checked($prop['error-mode'] ); ?> />
					<?php esc_html_e('Check to enable error conditions.', 'pz-linkcard' ); ?>
				</label>
			</td>
		</tr>
		<tr>
			<th scope="row"><?php esc_html_e('Post ID', 'pz-linkcard' ); ?></th>
			<td><input name="properties[error-postid]" type="number" min="0" step="1" size="5" value="<?php echo esc_attr($prop['error-postid'] ); ?>" /></td>
		</tr>
		<tr>
			<th scope="row"><?php esc_html_e('Post URL', 'pz-linkcard' ); ?></th>
			<td><input name="properties[error-url]" type="url" size="80" value="<?php echo esc_attr($prop['error-url'] ); ?>" /></td>
		</tr>
		<tr>
			<th scope="row"><?php esc_html_e('Occurrence Time', 'pz-linkcard' ); ?></th>
			<td>
				<input name="properties[error-time]" type="text" value="<?php echo esc_attr($prop['error-time'] ); ?>" class="pz-ad______min-only" />
				<span><?php echo esc_attr(is_numeric($prop['error-time'] ) ? $this->pz_Date(PZLKC_DATETIME_FORMAT, $prop['error-time'] ) : $prop['error-time'] ); ?></span>
			</td>
		</tr>
	</table>
	<?php submit_button(); ?>

	<?php if (isset($cron_log ) ) { ?>
		<h2><?php esc_html_e('Execution Result', 'pz-linkcard' ); ?></h2>
		<div>
			<?php esc_html_e('Execution Result', 'pz-linkcard' ); ?>
		</div>
		<div class="pz-cron-log">
			<?php echo $cron_log; ?>
		</div>
	<?php } ?>

	<h2><?php esc_html_e('WP-Cron Information', 'pz-linkcard' ); ?></h2>
	<div class="pz-cron-margin">
		<label>
			<input type="checkbox" value="1" class="pz-cron-all" />
			<?php esc_html_e('View all schedules.', 'pz-linkcard' ); ?>
		</label>
	</div>
	<div class="pz-cron-list-wrap">
		<table class="pz-cron-list widefat striped">
			<thead>
				<tr>
					<th scope="col" class="pz-cron-head-run"><?php esc_html_e('Run', 'pz-linkcard' ); ?></th>
					<th scope="col" class="pz-cron-head-hook"><?php esc_html_e('Hook', 'pz-linkcard' ); ?></th>
					<th scope="col" class="pz-cron-head-next-time"><?php echo __('Next Time', 'pz-linkcard' ).__('▼', 'pz-linkcard' ); ?></th>
					<th scope="col" class="pz-cron-head-schedule"><?php esc_html_e('Schedule', 'pz-linkcard' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($cron_list as $key => $cron ) { ?>
					<tr <?php echo $cron['display']; ?>>
						<td class="pz-cron-body-run"><?php echo $cron['button']; ?></td>
						<td class="pz-cron-body-hook"><?php echo $cron['hook']; ?></td>
						<td class="pz-cron-body-next-time"><?php echo $cron['next_time']; ?></td>
						<td class="pz-cron-body-schedule"><?php echo $cron['schedule']; ?></td>
					</tr>
				<?php } ?>
			</tbody>
		</table>
	</div>
</div>
