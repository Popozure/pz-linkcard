<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>
<div id="pz-infobar">
	<div class="pz-infobar-left">
		<a href="<?php echo esc_url(admin_url('tools.php?page=pz-linkcard-cacheman')); ?>" class="pz-infobar-plugin-logo"><img src="<?php echo esc_url($this->plugin_dir_url.'img/pz-linkcard_logo.svg'); ?>" width="156" height="28" alt="<?php echo esc_attr(self::PLUGIN_NAME); ?>"></a>
		<span class="pz-infobar-plugin-ver pz-monospace">ver.<?php echo esc_html(PZLKC_PLUGIN_VERSION); ?></span>
		<?php
		foreach (array('debug-mode' => array('debug', __('Debug Mode', 'pz-linkcard')), 'admin-mode' => array('admin', __('Admin Mode', 'pz-linkcard'))) as $key => $badge) {
			if (!empty($this->options[$key])) { echo '<span class="pz-infobar-env pz-infobar-env-'.esc_attr($badge[0]).'">'.esc_html($badge[1]).'</span>'; }
		}
		$environment = (int) ($this->options['develop-mode'] ?? 0);
		if ($environment === 1 || $environment === 2) {
			echo '<span class="pz-infobar-env pz-infobar-env-'.($environment === 1 ? 'develop' : 'product').'">'.esc_html($environment === 1 ? __('Development Environment', 'pz-linkcard') : __('Production Environment', 'pz-linkcard')).'</span>';
		}
		?>
	</div>
	<div class="pz-infobar-right">
		<form method="post" class="pz-infobar-filemenu" action="<?php echo esc_url(admin_url('tools.php?page=pz-linkcard-cacheman')); ?>">
			<?php wp_nonce_field('pz-cacheman'); ?>
			<button type="submit" name="action" value="show-import" class="pz-man-infobar-filemenu-button"><span class="dashicons dashicons-download" aria-hidden="true"></span><span class="pz-infobar-filemenu-label"><?php esc_html_e('Import', 'pz-linkcard'); ?></span></button>
			<button type="submit" name="action" value="show-export" class="pz-man-infobar-filemenu-button"><span class="dashicons dashicons-upload" aria-hidden="true"></span><span class="pz-infobar-filemenu-label"><?php esc_html_e('Export', 'pz-linkcard'); ?></span></button>
		</form>
		<a href="<?php echo esc_url($this->settings_url); ?>" class="pz-infobar-switch" title="<?php echo esc_attr(__('Settings', 'pz-linkcard').' F4'); ?>"><span class="pz-infobar-switch-icon dashicons dashicons-admin-generic" aria-hidden="true"></span><span class="pz-infobar-switch-label"><?php esc_html_e('Settings', 'pz-linkcard'); ?></span></a>
	</div>
</div>
