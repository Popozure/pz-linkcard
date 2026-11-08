<?php defined('ABSPATH' ) || wp_die; ?>
<div class="pz-page<?php echo $pz_page_active('pz-advanced' ); ?>" id="pz-advanced">
	<div class="pz-submit-float"><?php submit_button(); ?></div>

	<h2><?php echo __('Advanced Settings', 'pz-linkcard' ).$help_open.'advanced'.$help_close; ?></h2>
	<table class="form-table">
		<?php
			// 末尾のスラッシュの除去
			$item_name		=	'trail-slash';
			$item_list		=	array(
				''			=>		__('As is',							'pz-linkcard' ),
				'1'			=> 		__('Remove only when the URL contains just a domain name',	'pz-linkcard' ),
				'2'			=>		__('Always remove',					'pz-linkcard' ),
			);
			$item_descript		=	__('Trailing Slash',				'pz-linkcard' );
			echo_list($item_name, $prop[$item_name], $item_list, $item_descript, $item_notice );
		?>
		<tr>
			<th scope="row"><?php esc_html_e('CSS Class to Add (Desktop)',	'pz-linkcard' ); ?></th>
			<td><input name="properties[class-pc]"			type="text" size="40" value="<?php echo	(isset($this->options['class-pc'] ) ? esc_attr($this->options['class-pc'] ) : '' ); ?>" /></td>
		</tr>
		<tr>
			<th scope="row"><?php esc_html_e('CSS Class to Add (Mobile)',	'pz-linkcard' ); ?></th>
			<td><input name="properties[class-mobile]"		type="text" size="40" value="<?php echo	(isset($this->options['class-mobile'] ) ? esc_attr($this->options['class-mobile'] ) : '' ); ?>" /><br>
		</tr>
		<tr>
			<th scope="row"><?php esc_html_e('Visual Editor Filter Priority', 'pz-linkcard' ); ?></th>
			<td>
				<label>
					<input name="properties[mce-priority]" type="number" min="0" max="9999" size="80" value="<?php echo esc_attr($this->options['mce-priority'] ); ?>" /><?php esc_html_e('(Null or 0-9999)',  'pz-linkcard' ); ?>
					<?php esc_html_e('If the insert button does not appear in the editor, setting this value to 11 or higher may help.', 'pz-linkcard' ); ?>
				</label>
			</td>
		</tr>
		<tr>
			<th scope="row"><?php esc_html_e('CSS Compression', 'pz-linkcard' ); ?></th>
			<td>
				<label>
					<input type="hidden"   name="properties[flg-compress]" value="" />
					<input type="checkbox" name="properties[flg-compress]" value="1" <?php checked($this->options['flg-compress'] ); ?> />
					<?php esc_html_e('Use the compressed stylesheet for LinkCards.', 'pz-linkcard' ); ?>
				</label>
			</td>
		</tr>
		<tr>
			<th scope="row"><?php esc_html_e('Date Format (Manager)',	'pz-linkcard' ); ?></th>
			<td><input name="properties[date-format-man]"		type="text" size="40" value="<?php echo	(isset($this->options['date-format-man'] ) ? esc_attr($this->options['date-format-man'] ) : '' ); ?>" list="date-dormat-man-default" /></td>
			<datalist id="date-dormat-man-default"><option value="Y\<\b\r\/\>m/d\<\b\r\/\>H:i">Y\<\b\r\/\>m/d\<\b\r\/\>H:i</option><option value="d-M\<\b\r\/\>Y\<\b\r\/\>h:i\<\b\r\/\>a">d-M\<\b\r\/\>Y\<\b\r\/\>h:i\<\b\r\/\>a</option></datalist>
		</tr>
		<tr>
			<th scope="row"><?php esc_html_e('Input Lock', 'pz-linkcard' ); ?></th>
			<td>
				<label>
					<input type="hidden"   name="properties[flg-inhibit]" value="" />
					<input type="checkbox" name="properties[flg-inhibit]" value="1" <?php checked($this->options['flg-inhibit'] ); ?> />
					<?php esc_html_e('Show a processing overlay while the settings page loads to prevent accidental clicks.', 'pz-linkcard' ); ?>
				</label>
			</td>
		</tr>
		<tr>
			<th scope="row"><?php esc_html_e('Preview', 'pz-linkcard' ); ?></th>
			<td>
				<label>
					<input type="hidden"   name="properties[flg-preview]" value="" />
					<input type="checkbox" name="properties[flg-preview]" value="1" <?php checked(!array_key_exists('flg-preview', $this->options ) || !empty($this->options['flg-preview'] ) ); ?> />
					<?php esc_html_e('Display the LinkCard preview window on the settings screen.', 'pz-linkcard' ); ?>
				</label>
			</td>
		</tr>
		<tr>
			<th scope="row"><?php esc_html_e('Quick Menu', 'pz-linkcard' ); ?></th>
			<td>
				<label>
					<input type="hidden"   name="properties[flg-quickmenu]" value="" />
					<input type="checkbox" name="properties[flg-quickmenu]" value="1" <?php checked($this->options['flg-quickmenu'] ?? 0 ); ?> />
					<?php esc_html_e('Display a three-dot menu on the right side of LinkCards for administrators.', 'pz-linkcard' ); ?>
				</label>
			</td>
		</tr>

		<tr>
			<th scope="row"><?php esc_html_e('Google AMP Detection', 'pz-linkcard' ); ?></th>
			<td>
				<p>
					<label>
						<input type="hidden"   name="properties[flg-amp-url]" value="" />
						<input type="checkbox" name="properties[flg-amp-url]" value="1" <?php checked($this->options['flg-amp-url'] ); ?> />
						<?php echo __('Use a simplified display if the URL ends with "/amp", "/amp/", or "/?amp=1".', 'pz-linkcard' ).__('(Deprecated)', 'pz-linkcard' ); ?>
					</label>
				</p>
			</td>
		</tr>
		<tr>
			<th scope="row"><?php esc_html_e('Hide URL Error', 'pz-linkcard' ); ?></th>
			<td>
				<label>
					<input type="hidden"   name="properties[error-mode-hide]" value="" />
					<input type="checkbox" name="properties[error-mode-hide]" value="1" class="pz-tab-show" <?php checked($this->options['error-mode-hide'] ); ?> />
					<?php echo __('Do not display an error on the admin page.', 'pz-linkcard' ).__('(Deprecated)', 'pz-linkcard' ); ?>
				</label>
			</td>
		</tr>
		<tr>
			<th scope="row"><?php esc_html_e('Only retrieve publicly viewable posts', 'pz-linkcard' ); ?></th>
			<td>
				<label>
					<input type="hidden"   name="properties[flg-public-only]" value="" />
					<input type="checkbox" name="properties[flg-public-only]" value="1" <?php checked($this->options['flg-public-only'] ?? null ); ?> />
					<?php echo esc_html__('Do not retrieve non-public or password-protected internal posts, even when the current user can view them.', 'pz-linkcard' ).__('(Recommended)', 'pz-linkcard' ); ?>
				</label>
			</td>
		</tr>
	</table>
	<?php submit_button(); ?>

	<h2><?php echo	__('Individual Processing', 'pz-linkcard' ).$help_open.'deprecation'.$help_close; ?></h2>
	<table class="form-table">
		<tr>
			<th scope="row"><?php esc_html_e('OGP', 'pz-linkcard' ); ?></th>
			<td>
				<p>
					<label>
						<input type="hidden"   name="properties[flg-special-ogp]" value="" />
						<input type="checkbox" name="properties[flg-special-ogp]" value="1" <?php checked($this->options['flg-special-ogp'] ); ?> />
						<?php echo esc_html__('Retrieve Publication and Update Dates.', 'pz-linkcard' ); ?>
					</label>
				</p>
			</td>
		</tr>
		<tr>
			<th scope="row"><?php esc_html_e('Amazon.co.jp', 'pz-linkcard' ); ?></th>
			<td>
				<p>
					<label>
						<input type="hidden"   name="properties[flg-special-amazon]" value="" />
						<input type="checkbox" name="properties[flg-special-amazon]" value="1" <?php checked($this->options['flg-special-amazon'] ); ?> />
						<?php echo __('Retrieve Product Images', 'pz-linkcard' ); ?>
					</label>
				</p>
			</td>
		</tr>
		<tr>
			<th scope="row"><?php esc_html_e('X.com', 'pz-linkcard' ); ?></th>
			<td>
				<p>
					<label>
						<input type="hidden"   name="properties[flg-special-twitter]" value="" />
						<input type="checkbox" name="properties[flg-special-twitter]" value="1" <?php checked($this->options['flg-special-twitter'] ); ?> />
						<?php echo esc_html__('Retrieve the Publication Date and Profile Images.', 'pz-linkcard' ); ?>
					</label>
				</p>
			</td>
		</tr>
		<tr>
			<th scope="row"><?php esc_html_e('Jmty.jp', 'pz-linkcard' ); ?></th>
			<td>
				<p>
					<label>
						<input type="hidden"   name="properties[flg-special-jmty]" value="" />
						<input type="checkbox" name="properties[flg-special-jmty]" value="1" <?php checked($this->options['flg-special-jmty'] ); ?> />
						<?php echo esc_html__('Retrieve Created and Updated Dates', 'pz-linkcard' ); ?>
					</label>
				</p>
			</td>
		</tr>
		<tr>
			<th scope="row"><?php esc_html_e('note.com', 'pz-linkcard' ); ?></th>
			<td>
				<p>
					<label>
						<input type="hidden"   name="properties[flg-special-note]" value="" />
						<input type="checkbox" name="properties[flg-special-note]" value="1" <?php checked($this->options['flg-special-note'] ); ?> />
						<?php echo esc_html__('Retrieve Post Date', 'pz-linkcard' ); ?>
					</label>
				</p>
			</td>
		</tr>
		<tr>
			<th scope="row"><?php esc_html_e('Qiita', 'pz-linkcard' ); ?></th>
			<td>
				<p>
					<label>
						<input type="hidden"   name="properties[flg-special-qiita]" value="" />
						<input type="checkbox" name="properties[flg-special-qiita]" value="1" <?php checked($this->options['flg-special-qiita'] ); ?> />
						<?php echo esc_html__('Retrieve Publication and Update Dates', 'pz-linkcard' ); ?>
					</label>
				</p>
			</td>
		</tr>
		<tr>
			<th scope="row"><?php esc_html_e('Yahoo! Auctions', 'pz-linkcard' ); ?></th>
			<td>
				<p>
					<label>
						<input type="hidden"   name="properties[flg-special-yahoo-auc]" value="" />
						<input type="checkbox" name="properties[flg-special-yahoo-auc]" value="1" <?php checked($this->options['flg-special-yahoo-auc'] ); ?> />
						<?php echo esc_html__('Retrieve Title and Start Date', 'pz-linkcard' ); ?>
					</label>
				</p>
			</td>
		</tr>
		<tr>
			<th scope="row"><?php esc_html_e('Microsoft', 'pz-linkcard' ); ?></th>
			<td>
				<p>
					<label>
						<input type="hidden"   name="properties[flg-special-microsoft]" value="" />
						<input type="checkbox" name="properties[flg-special-microsoft]" value="1" <?php checked($this->options['flg-special-microsoft'] ); ?> />
						<?php echo esc_html__('Use User-Agent', 'pz-linkcard' ).' (pzlkc)'; ?>
					</label>
				</p>
			</td>
		</tr>
	</table>
	<?php submit_button(); ?>

	<h2 id="pz-schedule"><?php echo	__('Schedule', 'pz-linkcard' ).$help_open.'schedule'.$help_close; ?></h2>
	<?php
	global $wpdb;
	$pending_counts = $wpdb->get_row($wpdb->prepare(
		"SELECT (SELECT COUNT(*) FROM $this->db_card WHERE sns_nexttime <= %d) AS sns,
		(SELECT COUNT(*) FROM $this->db_card WHERE alive_nexttime <= %d) AS alive,
		(SELECT COUNT(*) FROM $this->db_card WHERE regist_time IS NULL OR regist_time = 0) AS unregistered",
		$this->now, $this->now
	));
	$pending_label = static function($count, $url = '') {
		$number = esc_html(number_format_i18n(intval($count)));
		if ($url !== '') {
			$number = '<a href="'.esc_url($url).'">'.$number.'</a>';
		}
		return '<span class="pz-schedule-pending">'.sprintf(
			esc_html__('Currently, %s items are waiting to be processed.', 'pz-linkcard'),
			$number
		).'</span>';
	};
	$next_run_label = static function($hook) {
		$timestamp = wp_next_scheduled($hook);
		$text = $timestamp
			? sprintf(__('The next run is scheduled for %s.', 'pz-linkcard'), wp_date('Y/m/d H:i:s', $timestamp))
			: __('No run is scheduled.', 'pz-linkcard');
		return '<span class="pz-schedule-next-run">'.esc_html($text).'</span>';
	};
	?>
	<table class="form-table pz-schedule-table">
		<tr id="pz-sns-recurrence-settings">
			<th rowspan="2"><?php esc_html_e('SNS Count', 'pz-linkcard' ); ?></th>
			<th scope="row"><?php esc_html_e('Recurrence', 'pz-linkcard' ); ?></th>
			<td><?php pz_echo_schedule_select('sns-period', $prop['sns-period'], LIST_PERIOD ); echo $next_run_label(self::CRON_CHECK); ?></td>
		</tr>
		<tr>
			<th scope="row"><?php esc_html_e('Items per Run', 'pz-linkcard' ); ?></th>
			<td><?php pz_echo_schedule_select('sns-period-num', $prop['sns-period-num'], LIST_PERIOD_NUMBER ); echo $pending_label($pending_counts->sns ?? 0, add_query_arg(array('filter' => 'all', 'keyword' => rawurlencode('sns_nexttime<=now')), $this->cacheman_url)); ?></td>
		</tr>
		<tr id="pz-alive-recurrence-settings">
			<th rowspan="2"><?php esc_html_e('Link Availability Check', 'pz-linkcard' ); ?></th>
			<th scope="row"><?php esc_html_e('Recurrence', 'pz-linkcard' ); ?></th>
			<td><?php pz_echo_schedule_select('alive-period', $prop['alive-period'], LIST_PERIOD ); echo $next_run_label(self::CRON_ALIVE); ?></td>
		</tr>
		<tr>
			<th scope="row"><?php esc_html_e('Items per Run', 'pz-linkcard' ); ?></th>
			<td><?php pz_echo_schedule_select('alive-period-num', $prop['alive-period-num'], LIST_PERIOD_NUMBER ); echo $pending_label($pending_counts->alive ?? 0, add_query_arg(array('filter' => 'all', 'keyword' => rawurlencode('alive_nexttime<=now')), $this->cacheman_url)); ?></td>
		</tr>
		<tr>
			<th rowspan="3"><?php esc_html_e('Article Content Retrieval', 'pz-linkcard'); ?></th>
			<th scope="row"><?php esc_html_e('Recurrence', 'pz-linkcard'); ?></th>
			<td><?php esc_html_e('As needed', 'pz-linkcard'); ?></td>
		</tr>
		<tr>
			<th scope="row"><?php esc_html_e('Access Interval per Site', 'pz-linkcard'); ?></th>
			<td><?php pz_echo_schedule_select('cron-site-interval', $prop['cron-site-interval'], array(
				10 => __('10 seconds', 'pz-linkcard'), 30 => __('30 seconds', 'pz-linkcard'),
				60 => __('1 minute', 'pz-linkcard'), 180 => __('3 minutes', 'pz-linkcard'),
				300 => __('5 minutes', 'pz-linkcard'), 600 => __('10 minutes', 'pz-linkcard'),
				1800 => __('30 minutes', 'pz-linkcard'), 3600 => __('1 hour', 'pz-linkcard'),
				21600 => __('6 hours', 'pz-linkcard'),
			)); ?></td>
		</tr>
		<tr>
			<th scope="row"><?php esc_html_e('Items per Run', 'pz-linkcard'); ?></th>
			<td><?php pz_echo_schedule_select('get-period-num', $prop['get-period-num'], array(
				5 => __('5 cards', 'pz-linkcard'), 10 => __('10 cards', 'pz-linkcard'),
				20 => __('20 cards', 'pz-linkcard'), 50 => __('50 cards', 'pz-linkcard'),
				100 => __('100 cards', 'pz-linkcard'), 0 => __('Unlimited', 'pz-linkcard'),
			)); echo $pending_label($pending_counts->unregistered ?? 0, add_query_arg('filter', 'unregistered', $this->cacheman_url)); ?></td>
		</tr>
		<tr>
			<th rowspan="2"><?php esc_html_e('Click Log Cleanup', 'pz-linkcard'); ?></th>
			<th scope="row"><?php esc_html_e('Recurrence', 'pz-linkcard'); ?></th>
			<td>
				<?php
				$cleanup_time_disabled = true;
				for ($day = 0; $day < 7; $day++) {
					if (!empty($prop['click-cleanup-day-'.$day])) {
						$cleanup_time_disabled = false;
						break;
					}
				}
				?>
				<select name="properties[click-cleanup-hour]" <?php disabled($cleanup_time_disabled); ?> aria-label="<?php esc_attr_e('Hour', 'pz-linkcard'); ?>">
					<?php for ($hour = 0; $hour < 24; $hour++) : ?>
						<option value="<?php echo esc_attr($hour); ?>" <?php selected($prop['click-cleanup-hour'], $hour); ?>><?php echo esc_html(sprintf('%02d', $hour)); ?></option>
					<?php endfor; ?>
				</select> :
				<select name="properties[click-cleanup-minute]" <?php disabled($cleanup_time_disabled); ?> aria-label="<?php esc_attr_e('Minute', 'pz-linkcard'); ?>">
					<?php for ($minute = 0; $minute < 60; $minute++) : ?>
						<option value="<?php echo esc_attr($minute); ?>" <?php selected($prop['click-cleanup-minute'], $minute); ?>><?php echo esc_html(sprintf('%02d', $minute)); ?></option>
					<?php endfor; ?>
				</select>
				<?php echo $next_run_label(self::CRON_CLICK_CLEANUP); ?>
				<div class="pz-click-cleanup-weekdays">
				<?php global $wp_locale; for ($day = 0; $day < 7; $day++) : $day_key = 'click-cleanup-day-'.$day; ?>
					<input type="hidden" name="properties[<?php echo esc_attr($day_key); ?>]" value="">
					<label><input type="checkbox" name="properties[<?php echo esc_attr($day_key); ?>]" value="1" <?php checked(!empty($prop[$day_key])); ?>><?php echo esc_html($wp_locale->get_weekday_initial($wp_locale->get_weekday($day))); ?></label>
				<?php endfor; ?>
				</div>
			</td>
		</tr>
		<tr id="pz-click-cleanup-retention-settings">
			<th scope="row"><?php esc_html_e('Retention Period', 'pz-linkcard'); ?></th>
			<td>
				<select name="properties[click-log-retention]">
					<option value="1w" <?php selected('1w', $this->options['click-log-retention'] ); ?>><?php esc_html_e('1 week', 'pz-linkcard' ); ?></option>
					<option value="1m" <?php selected('1m', $this->options['click-log-retention'] ); ?>><?php esc_html_e('1 month', 'pz-linkcard' ); ?></option>
					<option value="2m" <?php selected('2m', $this->options['click-log-retention'] ); ?>><?php esc_html_e('2 months', 'pz-linkcard' ); ?></option>
					<option value="3m" <?php selected('3m', $this->options['click-log-retention'] ); ?>><?php esc_html_e('3 months', 'pz-linkcard' ); ?></option>
					<option value="6m" <?php selected('6m', $this->options['click-log-retention'] ); ?>><?php esc_html_e('6 months', 'pz-linkcard' ); ?></option>
					<option value="1y" <?php selected('1y', $this->options['click-log-retention'] ); ?>><?php esc_html_e('1 year', 'pz-linkcard' ); ?></option>
					<option value="2y" <?php selected('2y', $this->options['click-log-retention'] ); ?>><?php esc_html_e('2 years (long-term)', 'pz-linkcard' ); ?></option>
					<option value="3y" <?php selected('3y', $this->options['click-log-retention'] ); ?>><?php esc_html_e('3 years (long-term)', 'pz-linkcard' ); ?></option>
					<option value="5y" <?php selected('5y', $this->options['click-log-retention'] ); ?>><?php esc_html_e('5 years (long-term)', 'pz-linkcard' ); ?></option>
					<option value="forever" <?php selected('forever', $this->options['click-log-retention'] ); ?>><?php esc_html_e('Permanent (not recommended)', 'pz-linkcard' ); ?></option>
				</select>
				<p class="description"><?php esc_html_e('Detailed click logs older than the retention period are deleted on the configured schedule. The cumulative click count is retained.', 'pz-linkcard' ); ?></p>
				<p class="description"><?php esc_html_e('Long-term retention increases database usage according to the number of clicks.', 'pz-linkcard' ); ?></p>
			</td>
		</tr>
	</table>
	<?php submit_button(); ?>

	<h2><?php echo	__('Extension Settings', 'pz-linkcard' ).$help_open.'extension'.$help_close; ?></h2>
	<table class="form-table">
		<tr>
			<th scope="row"><?php esc_html_e('Admin Bar', 'pz-linkcard' ); ?></th>
			<td>
				<label>
					<input type="hidden"   name="properties[flg-adminbar]" value="" />
					<input type="checkbox" name="properties[flg-adminbar]" value="1" <?php checked($this->options['flg-adminbar'] ?? 0 ); ?> />
					<?php esc_html_e('Display the menu in the admin bar.', 'pz-linkcard' ); ?>
				</label>
			</td>
		</tr>
		<tr  class="pz-develop-only">
			<th scope="row" style="color: #0a8 !important; background-color: #b0bbbb !important;"><?php esc_html_e('Development Mode', 'pz-linkcard' ); ?></th>
			<td style="background-color: #fffff8 !important;">
				<label>
					<input type="hidden"   name="properties[develop-mode]" value="" />
					<input type="checkbox" name="properties[develop-mode]" value="1" <?php checked($this->options['develop-mode'] ); ?> readonly="readonly" />
					<span style="color: #0a8 !important;"><?php esc_html_e('Currently working in a development environment.', 'pz-linkcard' ); ?></span>
				</label>
			</td>
		</tr>
	</table>
	<?php submit_button(); ?>
</div>
