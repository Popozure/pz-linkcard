<?php defined('ABSPATH' ) || wp_die; ?>
<?php
	global	$wpdb;

	$card_id		=	intval($data['id'] ?? 0 );
	$click_page	=	isset($_GET['click_page'] ) ? max(1, absint($_GET['click_page'] ) ) : 1;
	$page_limit	=	50;
	$count_now	=	0;
	$click_logs	=	array();
	$db_click_exists	=	($wpdb->get_var($wpdb->prepare("SHOW TABLES LIKE %s", $this->db_click ) ) === $this->db_click );

	if	($db_click_exists && $card_id ) {
		$count_now	=	intval($wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $this->db_click WHERE card_id = %d", $card_id ) ) );
		$page_max	=	max(1, intval(ceil($count_now / $page_limit ) ) );
		$click_page	=	min($click_page, $page_max );
		$offset		=	($click_page - 1 ) * $page_limit;
		$click_logs	=	(array) $wpdb->get_results($wpdb->prepare(
			"SELECT click_id,click_time,card_id,post_id,ip_hash,user_agent FROM $this->db_click WHERE card_id = %d ORDER BY click_id DESC LIMIT %d OFFSET %d",
			$card_id,
			$page_limit,
			$offset
		) );
	} else {
		$page_max	=	1;
	}

	$base_url	=	add_query_arg(array('action' => 'click-log', 'cache_id' => $card_id ), $this->cacheman_url );
	$click_settings_url	=	add_query_arg('tab', 'pz-check', $this->settings_url ).'#pz-click-count-settings';
	$click_retention_settings_url	=	add_query_arg('tab', 'pz-check', $this->settings_url ).'#pz-click-log-retention-settings';
	$click_log_enabled	=	!empty($this->options['flg-click-count'] ) && !empty($this->options['flg-click-log'] );
	$click_log_retention	=	intval($this->options['click-log-retention'] ?? 90 );
	$pagination	=	paginate_links(array(
		'base'		=>	add_query_arg('click_page', '%#%', $base_url ),
		'format'	=>	'',
		'current'	=>	$click_page,
		'total'		=>	$page_max,
		'type'		=>	'list',
		'prev_text'	=>	__('&lsaquo;', 'pz-linkcard' ),
		'next_text'	=>	__('&rsaquo;', 'pz-linkcard' ),
	) );
?>
<div class="pz-man-click-log">
	<h2><?php esc_html_e('Click Log', 'pz-linkcard' ); ?></h2>
	<p>
		<strong><?php esc_html_e('ID', 'pz-linkcard' ); ?>:</strong> <?php echo esc_html($card_id ); ?><br>
		<strong><?php esc_html_e('Title', 'pz-linkcard' ); ?>:</strong> <?php echo esc_html($data['title'] ?? '' ); ?><br>
		<strong><?php esc_html_e('URL', 'pz-linkcard' ); ?>:</strong>
		<a href="<?php echo esc_url($data['url'] ?? '' ); ?>" target="_blank" rel="external noopener noreferrer" referrerpolicy="no-referrer"><?php echo esc_html($data['url'] ?? '' ); ?></a>
	</p>
	<?php if	(!$click_log_enabled ) : ?>
		<div class="notice notice-warning inline"><p><?php
			echo wp_kses(
				sprintf(__('To collect click logs, enable click count recording <a href="%s">here</a>.', 'pz-linkcard' ), esc_url($click_settings_url ) ),
				array('a' => array('href' => true ) )
			);
		?></p></div>
	<?php endif; ?>
	<p class="description">
		<?php if	($click_log_retention === 0 ) : ?>
			<?php esc_html_e('The click count is cumulative. Detailed logs are retained permanently.', 'pz-linkcard' ); ?>
		<?php else : ?>
			<?php
				echo wp_kses(
					sprintf(__('The click count is cumulative. Detailed logs are retained for <a href="%1$s">%2$s days</a>.', 'pz-linkcard' ), esc_url($click_retention_settings_url ), number_format_i18n($click_log_retention ) ),
					array('a' => array('href' => true ) )
				);
			?>
		<?php endif; ?>
	</p>
	<p><?php echo esc_html(sprintf(_n('%s item', '%s items', $count_now, 'pz-linkcard' ), number_format_i18n($count_now ) ) ); ?></p>

	<?php if	($pagination ) : ?>
		<nav class="tablenav-pages" aria-label="<?php echo esc_attr__('Click Log', 'pz-linkcard' ); ?>"><?php echo wp_kses_post($pagination ); ?></nav>
	<?php endif; ?>

	<table class="widefat striped">
		<thead>
			<tr>
				<th scope="col"><?php esc_html_e('ID', 'pz-linkcard' ); ?></th>
				<th scope="col"><?php esc_html_e('Click Date', 'pz-linkcard' ); ?></th>
				<th scope="col"><?php esc_html_e('Post ID', 'pz-linkcard' ); ?></th>
				<th scope="col"><?php esc_html_e('IP Hash', 'pz-linkcard' ); ?></th>
				<th scope="col"><?php esc_html_e('User-Agent', 'pz-linkcard' ); ?></th>
			</tr>
		</thead>
		<tbody>
		<?php if	(!$click_logs ) : ?>
			<tr><td colspan="5"><?php esc_html_e('No click logs found.', 'pz-linkcard' ); ?></td></tr>
		<?php else : ?>
			<?php foreach	($click_logs as $click_log ) : ?>
				<?php
					$log_post_id	=	intval($click_log->post_id );
					$post_url		=	$log_post_id ? get_permalink($log_post_id ) : '';
					$post_title	=	$log_post_id ? get_the_title($log_post_id ) : '';
					$ip_hash		=	(string) $click_log->ip_hash;
					$ip_hash_display	=	$ip_hash !== '' ? substr($ip_hash, 0, 8 ).'*' : '';
				?>
				<tr>
					<td><?php echo esc_html(intval($click_log->click_id ) ); ?></td>
					<td><?php echo esc_html(date_i18n(PZLKC_DATE_FORMAT.' H:i:s', intval($click_log->click_time ) ) ); ?></td>
					<td>
						<?php if	($post_url ) : ?>
							<a href="<?php echo esc_url($post_url ); ?>" target="_blank" rel="noopener noreferrer" referrerpolicy="no-referrer" title="<?php echo esc_attr($post_title ); ?>"><?php echo esc_html($log_post_id ); ?></a>
						<?php else : ?>
							<?php echo esc_html($log_post_id ?: '' ); ?>
						<?php endif; ?>
					</td>
					<td><?php echo esc_html($ip_hash_display ); ?></td>
					<td><?php echo esc_html($click_log->user_agent ); ?></td>
				</tr>
			<?php endforeach; ?>
		<?php endif; ?>
		</tbody>
	</table>

	<?php if	($pagination ) : ?>
		<nav class="tablenav-pages" aria-label="<?php echo esc_attr__('Click Log', 'pz-linkcard' ); ?>"><?php echo wp_kses_post($pagination ); ?></nav>
	<?php endif; ?>
	<p><a href="<?php echo esc_url($this->cacheman_url ); ?>" class="button"><?php esc_html_e('Return to Cache Manager', 'pz-linkcard' ); ?></a></p>
</div>
