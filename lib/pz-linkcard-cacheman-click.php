<?php defined('ABSPATH' ) || wp_die; ?>
<?php
	global	$wpdb;

	$card_id		=	intval($data['id'] ?? 0 );
	$click_page	=	isset($_POST['click-page'] ) ? max(1, absint($_POST['click-page'] ) ) : 1;
	$chart_period	=	isset($_POST['click-period'] )
		? sanitize_key(wp_unslash($_POST['click-period'] ) )
		: (isset($_POST['chart_period'] ) ? sanitize_key(wp_unslash($_POST['chart_period'] ) ) : 'day' );
	$click_range	= isset($_POST['click-period'] )
		? ''
		: (isset($_POST['click-range-select'] )
			? sanitize_text_field(wp_unslash($_POST['click-range-select'] ) )
			: (isset($_POST['click-range'] ) ? sanitize_text_field(wp_unslash($_POST['click-range'] ) ) : '' ) );
	if	(!in_array($chart_period, array('day', 'week', 'month', 'year' ), true ) ) {
		$chart_period	= 'day';
	}
	$page_limit	=	50;
	$page_max	=	1;
	$count_now	=	0;
	$click_logs	=	array();
	$chart_items	=	array();
	$chart_ranges	=	array();
	$chart_title	=	'';
	$db_click_exists	=	($wpdb->get_var($wpdb->prepare("SHOW TABLES LIKE %s", $this->db_click ) ) === $this->db_click );

	if	($db_click_exists ) {
		$count_now	=	$card_id
			?	intval($wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $this->db_click WHERE card_id = %d", $card_id ) ) )
			:	intval($wpdb->get_var("SELECT COUNT(*) FROM $this->db_click" ) );
		$page_max	=	max(1, intval(ceil($count_now / $page_limit ) ) );
		$click_page	=	min($click_page, $page_max );
		$offset		=	($click_page - 1 ) * $page_limit;
		if	($card_id ) {
			$click_logs	=	(array) $wpdb->get_results($wpdb->prepare(
				"SELECT click_log.click_id,click_log.click_time,click_log.card_id,click_log.post_id,click_log.ip_hash,click_log.user_agent,card.title AS card_title
				FROM $this->db_click AS click_log
				LEFT JOIN $this->db_card AS card ON card.id = click_log.card_id
				WHERE click_log.card_id = %d ORDER BY click_log.click_id DESC LIMIT %d OFFSET %d",
				$card_id,
				$page_limit,
				$offset
			) );
		} else {
			$click_logs	=	(array) $wpdb->get_results($wpdb->prepare(
				"SELECT click_log.click_id,click_log.click_time,click_log.card_id,click_log.post_id,click_log.ip_hash,click_log.user_agent,card.title AS card_title
				FROM $this->db_click AS click_log
				LEFT JOIN $this->db_card AS card ON card.id = click_log.card_id
				ORDER BY click_log.click_id DESC LIMIT %d OFFSET %d",
				$page_limit,
				$offset
			) );
		}

		$local_now	= current_time('timestamp', false );
		if	($chart_period === 'day' ) {
			$today_bucket	= intdiv($local_now, DAY_IN_SECONDS );
			$first_bucket	= $today_bucket - 6;
			for	($bucket = $first_bucket; $bucket <= $today_bucket; $bucket++ ) {
				$chart_items[(string) $bucket]	= array('label' => date_i18n('n/j', $bucket * DAY_IN_SECONDS ), 'count' => 0 );
				$chart_ranges[]	= array('key' => (string) $bucket, 'start' => $bucket * DAY_IN_SECONDS, 'end' => ($bucket + 1 ) * DAY_IN_SECONDS );
			}
			$chart_title	= __('Clicks in the last 7 days', 'pz-linkcard' );
		} elseif ($chart_period === 'week' ) {
			$week_offset	= 4 * DAY_IN_SECONDS;
			$this_week	= intdiv($local_now - $week_offset, WEEK_IN_SECONDS );
			$first_week	= $this_week - 7;
			for	($bucket = $first_week; $bucket <= $this_week; $bucket++ ) {
				$week_start	= ($bucket * WEEK_IN_SECONDS ) + $week_offset;
				$chart_items[(string) $bucket]	= array('label' => date_i18n('n/j', $week_start ), 'count' => 0 );
				$chart_ranges[]	= array('key' => (string) $bucket, 'start' => $week_start, 'end' => $week_start + WEEK_IN_SECONDS );
			}
			$chart_title	= __('Clicks in the last 8 weeks', 'pz-linkcard' );
		} elseif ($chart_period === 'month' ) {
			$current_month	= new DateTimeImmutable(gmdate('Y-m-01', $local_now ), new DateTimeZone('UTC' ) );
			for	($index = 11; $index >= 0; $index-- ) {
				$month	= $current_month->modify('-'.$index.' months' );
				$period_key	= $month->format('Y-m' );
				$chart_items[$period_key]	= array('label' => $month->format('Y/n' ), 'count' => 0 );
				$chart_ranges[]	= array('key' => $period_key, 'start' => $month->getTimestamp(), 'end' => $month->modify('+1 month' )->getTimestamp() );
			}
			$chart_title	= __('Clicks in the last 12 months', 'pz-linkcard' );
		} else {
			$current_year	= intval(gmdate('Y', $local_now ) );
			for	($year = $current_year - 4; $year <= $current_year; $year++ ) {
				$chart_items[(string) $year]	= array('label' => (string) $year, 'count' => 0 );
				$chart_ranges[]	= array('key' => (string) $year, 'start' => gmmktime(0, 0, 0, 1, 1, $year ), 'end' => gmmktime(0, 0, 0, 1, 1, $year + 1 ) );
			}
			$chart_title	= __('Clicks in the last 5 years', 'pz-linkcard' );
		}
		$chart_selects	= array();
		$chart_query_args	= array();
		foreach	($chart_ranges as $index => $range ) {
			$chart_selects[]	= "SUM(CASE WHEN click_time >= %d AND click_time < %d THEN 1 ELSE 0 END) AS period_$index";
			$chart_query_args[]	= $range['start'];
			$chart_query_args[]	= $range['end'];
		}
		if	($card_id ) {
			$chart_query_args[]	= $card_id;
			$chart_sql	= "SELECT ".implode(', ', $chart_selects )." FROM $this->db_click WHERE card_id = %d AND click_time >= %d AND click_time < %d";
		} else {
			$chart_sql	= "SELECT ".implode(', ', $chart_selects )." FROM $this->db_click WHERE click_time >= %d AND click_time < %d";
		}
		$chart_query_args[]	= $chart_ranges[0]['start'];
		$chart_query_args[]	= $chart_ranges[count($chart_ranges ) - 1]['end'];
		$chart_counts	= $wpdb->get_row($wpdb->prepare($chart_sql, $chart_query_args ) );
		if	($chart_counts ) {
			foreach	($chart_ranges as $index => $range ) {
				$column	= 'period_'.$index;
				$chart_items[$range['key']]['count']	= intval($chart_counts->$column ?? 0 );
			}
		}

		$selected_range	= null;
		foreach	($chart_ranges as $range ) {
			if	($click_range === $range['key'] ) {
				$selected_range	= $range;
				break;
			}
		}
		if	($selected_range ) {
			$where_sql	= 'click_log.click_time >= %d AND click_log.click_time < %d';
			$where_args	= array($selected_range['start'], $selected_range['end'] );
			if	($card_id ) {
				$where_sql	= 'click_log.card_id = %d AND '.$where_sql;
				array_unshift($where_args, $card_id );
			}
			$count_now	= intval($wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $this->db_click AS click_log WHERE $where_sql", $where_args ) ) );
			$page_max	= max(1, intval(ceil($count_now / $page_limit ) ) );
			$click_page	= min($click_page, $page_max );
			$offset	= ($click_page - 1 ) * $page_limit;
			$list_args	= array_merge($where_args, array($page_limit, $offset ) );
			$click_logs	= (array) $wpdb->get_results($wpdb->prepare(
				"SELECT click_log.click_id,click_log.click_time,click_log.card_id,click_log.post_id,click_log.ip_hash,click_log.user_agent,card.title AS card_title
				FROM $this->db_click AS click_log
				LEFT JOIN $this->db_card AS card ON card.id = click_log.card_id
				WHERE $where_sql ORDER BY click_log.click_id DESC LIMIT %d OFFSET %d",
				$list_args
			) );
		} else {
			$click_range	= '';
		}
	} else {
		$page_max	=	1;
	}

	$click_settings_url	=	add_query_arg('tab', 'pz-check', $this->settings_url ).'#pz-click-count-settings';
	$click_retention_settings_url	=	add_query_arg('tab', 'pz-check', $this->settings_url ).'#pz-click-log-retention-settings';
	$click_log_enabled	=	!empty($this->options['flg-click-count'] ) && !empty($this->options['flg-click-log'] );
	$click_log_retention	=	strval($this->options['click-log-retention'] ?? '3m' );
	$click_log_retention_labels	=	array(
		'1w' => __('1 week', 'pz-linkcard' ),
		'1m' => __('1 month', 'pz-linkcard' ),
		'2m' => __('2 months', 'pz-linkcard' ),
		'3m' => __('3 months', 'pz-linkcard' ),
		'6m' => __('6 months', 'pz-linkcard' ),
		'1y' => __('1 year', 'pz-linkcard' ),
		'2y' => __('2 years', 'pz-linkcard' ),
		'3y' => __('3 years', 'pz-linkcard' ),
		'5y' => __('5 years', 'pz-linkcard' ),
	);
	$click_log_retention_label	=	$click_log_retention_labels[$click_log_retention] ?? $click_log_retention_labels['3m'];
	$chart_max	=	$chart_items ? max(array_column($chart_items, 'count' ) ) : 0;
	$chart_periods	=	array(
		'day'	=> __('Day', 'pz-linkcard' ),
		'week'	=> __('Week', 'pz-linkcard' ),
		'month'	=> __('Month', 'pz-linkcard' ),
		'year'	=> __('Year', 'pz-linkcard' ),
	);
	$card_edit_url	= $card_id ? add_query_arg(array('action' => 'edit', 'cache_id' => $card_id ), $this->cacheman_url ) : '';
?>
<div class="pz-man-click-log">
	<input type="hidden" name="cache_id" value="<?php echo intval($card_id ); ?>">
	<input type="hidden" name="chart_period" value="<?php echo esc_attr($chart_period ); ?>">
	<input type="hidden" name="click-range" value="<?php echo esc_attr($click_range ); ?>">
	<h2><?php esc_html_e('Click Log', 'pz-linkcard' ); ?></h2>
	<?php if	($card_id ) : ?>
	<p class="pz-click-log-card-details">
		<strong><?php esc_html_e('Card ID', 'pz-linkcard' ); ?>:</strong>
		<a href="<?php echo esc_url($card_edit_url ); ?>" class="pz-click-log-card-edit-link" title="<?php echo esc_attr__('Edit card', 'pz-linkcard' ); ?>"><span><?php echo esc_html($card_id ); ?></span><span class="dashicons dashicons-edit-page" aria-hidden="true"></span></a><br>
		<strong><?php esc_html_e('Title', 'pz-linkcard' ); ?>:</strong> <?php echo esc_html($data['title'] ?? '' ); ?><br>
		<strong><?php esc_html_e('URL', 'pz-linkcard' ); ?>:</strong>
		<a href="<?php echo esc_url($data['url'] ?? '' ); ?>" target="_blank" rel="external noopener noreferrer" referrerpolicy="no-referrer" class="pz-monospace"><?php echo esc_html($this->pz_DecodeURL($data['url'] ?? '' ) ); ?></a>
	</p>
	<?php endif; ?>
	<?php if	(!$click_log_enabled ) : ?>
		<div class="notice notice-warning inline"><p><?php
			echo wp_kses(
				sprintf(__('To collect click logs, enable click count recording <a href="%s">here</a>.', 'pz-linkcard' ), esc_url($click_settings_url ) ),
				array('a' => array('href' => true ) )
			);
		?></p></div>
	<?php endif; ?>
	<?php if	($chart_items ) : ?>
		<section class="pz-click-chart" aria-labelledby="pz-click-chart-title">
			<nav class="pz-click-chart-periods" aria-label="<?php echo esc_attr__('Chart period', 'pz-linkcard' ); ?>">
				<div class="pz-click-chart-period-buttons">
					<?php foreach	($chart_periods as $period_key => $period_label ) : ?>
						<button type="submit" name="click-period" value="<?php echo esc_attr($period_key ); ?>" class="<?php echo $chart_period === $period_key ? 'is-active' : ''; ?>"<?php echo $chart_period === $period_key ? ' aria-current="page"' : ''; ?>><?php echo esc_html($period_label ); ?></button>
					<?php endforeach; ?>
				</div>
				<?php if	($click_range ) : ?>
					<button type="submit" name="click-period" value="<?php echo esc_attr($chart_period ); ?>" class="button-link pz-click-chart-clear"><?php esc_html_e('Remove filter', 'pz-linkcard' ); ?></button>
				<?php endif; ?>
			</nav>
			<h3 id="pz-click-chart-title"><?php echo esc_html($chart_title ); ?></h3>
			<div class="pz-click-chart-bars">
				<?php foreach	($chart_items as $chart_key => $chart_day ) : ?>
					<?php
						$chart_count	= intval($chart_day['count'] );
						$chart_height	= $chart_max > 0 ? intval(round(($chart_count / $chart_max ) * 100 ) ) : 0;
						if	($chart_count > 0 ) {
							$chart_height	= max(4, $chart_height );
						}
					?>
					<button type="submit" name="click-range-select" value="<?php echo esc_attr((string) $chart_key ); ?>" class="pz-click-chart-item<?php echo $click_range === (string) $chart_key ? ' is-active' : ''; ?>" title="<?php echo esc_attr(sprintf(__('%s clicks', 'pz-linkcard' ), number_format_i18n($chart_count ) ) ); ?>" aria-pressed="<?php echo $click_range === (string) $chart_key ? 'true' : 'false'; ?>">
						<span class="pz-click-chart-count"><?php echo esc_html(number_format_i18n($chart_count ) ); ?></span>
						<span class="pz-click-chart-bar-wrap"><span class="pz-click-chart-bar" style="height: <?php echo esc_attr($chart_height ); ?>%;"></span></span>
						<span class="pz-click-chart-label"><?php echo esc_html($chart_day['label'] ); ?></span>
					</button>
				<?php endforeach; ?>
			</div>
		</section>
	<?php endif; ?>
	<p class="description">
		<?php if	($click_log_retention === 'forever' ) : ?>
			<?php
				echo wp_kses(
					sprintf(__('The click count is cumulative. Detailed logs are retained <a href="%s">permanently</a>.', 'pz-linkcard' ), esc_url($click_retention_settings_url ) ),
					array('a' => array('href' => true ) )
				);
			?>
		<?php else : ?>
			<?php
				echo wp_kses(
					sprintf(__('The click count is cumulative. Detailed logs are retained for <a href="%1$s">%2$s</a>.', 'pz-linkcard' ), esc_url($click_retention_settings_url ), esc_html($click_log_retention_label ) ),
					array('a' => array('href' => true ) )
				);
			?>
		<?php endif; ?>
	</p>
	<p>
		<button type="submit" name="action" value="list" class="button"><?php esc_html_e('Return to Cache Manager', 'pz-linkcard' ); ?></button>
		<?php if	($this->pz_IsDashboardWidgetReturnUrl($return_url ) ) : ?>
			<a href="<?php echo esc_url($return_url ); ?>" class="button"><?php esc_html_e('Return to Dashboard', 'pz-linkcard' ); ?></a>
		<?php endif; ?>
	</p>
	<div class="pz-click-log-list-navigation">
		<p class="pz-click-log-item-count"><?php echo esc_html(sprintf(_n('%s item', '%s items', $count_now, 'pz-linkcard' ), number_format_i18n($count_now ) ) ); ?></p>
		<nav class="tablenav-pages" aria-label="<?php echo esc_attr__('Click Log', 'pz-linkcard' ); ?>">
			<button type="submit" name="click-page" value="1" class="button"<?php disabled($click_page <= 1 ); ?>><?php echo wp_kses_post(__('&laquo;', 'pz-linkcard' ) ); ?></button>
			<button type="submit" name="click-page" value="<?php echo max(1, $click_page - 1 ); ?>" class="button"<?php disabled($click_page <= 1 ); ?>><?php echo wp_kses_post(__('&lsaquo;', 'pz-linkcard' ) ); ?></button>
			<span class="paging-input"><?php echo esc_html(number_format_i18n($click_page ).' / '.number_format_i18n($page_max ) ); ?></span>
			<button type="submit" name="click-page" value="<?php echo min($page_max, $click_page + 1 ); ?>" class="button"<?php disabled($click_page >= $page_max ); ?>><?php echo wp_kses_post(__('&rsaquo;', 'pz-linkcard' ) ); ?></button>
			<button type="submit" name="click-page" value="<?php echo intval($page_max ); ?>" class="button"<?php disabled($click_page >= $page_max ); ?>><?php echo wp_kses_post(__('&raquo;', 'pz-linkcard' ) ); ?></button>
		</nav>
	</div>

	<table class="widefat striped">
		<thead>
			<tr>
				<th scope="col"><?php esc_html_e('ID', 'pz-linkcard' ); ?></th>
				<th scope="col"><?php esc_html_e('Card ID', 'pz-linkcard' ); ?></th>
				<th scope="col"><?php esc_html_e('Click Date', 'pz-linkcard' ); ?>🔻</th>
				<th scope="col"><?php esc_html_e('Post ID', 'pz-linkcard' ); ?></th>
				<th scope="col"><?php esc_html_e('IP Hash', 'pz-linkcard' ); ?></th>
				<th scope="col"><?php esc_html_e('User-Agent', 'pz-linkcard' ); ?></th>
			</tr>
		</thead>
		<tbody>
		<?php if	(!$click_logs ) : ?>
			<tr><td colspan="6"><?php esc_html_e('No click logs found.', 'pz-linkcard' ); ?></td></tr>
		<?php else : ?>
			<?php foreach	($click_logs as $click_log ) : ?>
				<?php
					$log_card_id	= intval($click_log->card_id );
					$card_log_args	= array('action' => 'click-log', 'cache_id' => $log_card_id );
					if	($return_url ) {
						$card_log_args['return_url']	= $return_url;
					}
					$card_log_url	= add_query_arg($card_log_args, $this->cacheman_url );
					$log_post_id	=	intval($click_log->post_id );
					$post_url		=	$log_post_id ? get_permalink($log_post_id ) : '';
					$post_title	=	$log_post_id ? get_the_title($log_post_id ) : '';
					$ip_hash		=	(string) $click_log->ip_hash;
					$ip_hash_display	=	$ip_hash !== '' ? substr($ip_hash, 0, 8 ).'*' : '';
				?>
				<tr>
					<td><?php echo esc_html(intval($click_log->click_id ) ); ?></td>
					<td><a href="<?php echo esc_url($card_log_url ); ?>" title="<?php echo esc_attr((string) ($click_log->card_title ?? '' ) ); ?>" class="pz-click-log-card-link"><span><?php echo esc_html($log_card_id ); ?></span><span class="dashicons dashicons-chart-bar" aria-hidden="true"></span></a></td>
					<td><?php echo esc_html(date_i18n(PZLKC_DATE_FORMAT.' H:i:s', intval($click_log->click_time ) ) ); ?></td>
					<td>
						<?php if	($post_url ) : ?>
							<a href="<?php echo esc_url($post_url ); ?>" target="_blank" rel="noopener noreferrer" referrerpolicy="no-referrer" title="<?php echo esc_attr($post_title ); ?>" class="pz-click-log-post-link"><span><?php echo esc_html($log_post_id ); ?></span><span class="dashicons dashicons-external" aria-hidden="true"></span></a>
						<?php else : ?>
							<?php echo esc_html($log_post_id ?: '' ); ?>
						<?php endif; ?>
					</td>
					<td class="pz-monospace"><?php echo esc_html($ip_hash_display ); ?></td>
					<td class="pz-monospace"><?php echo esc_html($click_log->user_agent ); ?></td>
				</tr>
			<?php endforeach; ?>
		<?php endif; ?>
		</tbody>
	</table>

	<?php if	($page_max > 1 ) : ?>
		<nav class="tablenav-pages" aria-label="<?php echo esc_attr__('Click Log', 'pz-linkcard' ); ?>">
			<button type="submit" name="click-page" value="1" class="button"<?php disabled($click_page <= 1 ); ?>><?php echo wp_kses_post(__('&laquo;', 'pz-linkcard' ) ); ?></button>
			<button type="submit" name="click-page" value="<?php echo max(1, $click_page - 1 ); ?>" class="button"<?php disabled($click_page <= 1 ); ?>><?php echo wp_kses_post(__('&lsaquo;', 'pz-linkcard' ) ); ?></button>
			<span class="paging-input"><?php echo esc_html(number_format_i18n($click_page ).' / '.number_format_i18n($page_max ) ); ?></span>
			<button type="submit" name="click-page" value="<?php echo min($page_max, $click_page + 1 ); ?>" class="button"<?php disabled($click_page >= $page_max ); ?>><?php echo wp_kses_post(__('&rsaquo;', 'pz-linkcard' ) ); ?></button>
			<button type="submit" name="click-page" value="<?php echo intval($page_max ); ?>" class="button"<?php disabled($click_page >= $page_max ); ?>><?php echo wp_kses_post(__('&raquo;', 'pz-linkcard' ) ); ?></button>
		</nav>
	<?php endif; ?>
	<p>
		<button type="submit" name="action" value="list" class="button"><?php esc_html_e('Return to Cache Manager', 'pz-linkcard' ); ?></button>
		<?php if	($this->pz_IsDashboardWidgetReturnUrl($return_url ) ) : ?>
			<a href="<?php echo esc_url($return_url ); ?>" class="button"><?php esc_html_e('Return to Dashboard', 'pz-linkcard' ); ?></a>
		<?php endif; ?>
	</p>
</div>
