<?php defined('ABSPATH' ) || wp_die; ?>
<div class="pz-page<?php echo $pz_page_active('pz-initialize' ); ?>" id="pz-initialize">
	<div class="pz-submit-float"><?php submit_button(); ?></div>
	<h2><?php echo	__('Initialize', 'pz-linkcard' ).$help_open.'initialize'.$help_close; ?></h2>
	<table class="form-table">
		<tr>
			<th scope="row"><?php esc_html_e('Initialize Settings', 'pz-linkcard' ); ?></th>
			<td>
				<button type="submit" name="action" value="init-settings" class="pz-button-sure" onclick="return confirm('<?php echo esc_js(__('Are you sure?', 'pz-linkcard' ) ); ?>');"><?php esc_html_e('Run', 'pz-linkcard' ); ?></button>
				&ensp;<span><?php esc_html_e('Reset the "Settings" to the initial value.', 'pz-linkcard' ); ?></span>
			</td>
		</tr>
		<tr class="pz-admin-only">
			<th scope="row"><?php esc_html_e('Initialization Exception', 'pz-linkcard' ); ?></th>
			<td>
				<label>
					<input type="hidden"   name="properties[initialize-exception]" value="" />
					<input type="checkbox" name="properties[initialize-exception]" value="1" <?php checked($this->options['initialize-exception'] ); ?> />
					<?php esc_html_e('Do not initialize "Survey Mode" and "Administrator Mode".', 'pz-linkcard' ); ?>
				</label>
			</td>
		</tr>
	</table>
	<div class="pz-admin-only">
		<?php submit_button(); ?>
	</div>

	<h2><?php echo	__('Deletion Settings', 'pz-linkcard' ); ?></h2>
	<table class="form-table">
		<tr>
			<th scope="row"><?php esc_html_e('Delete Settings', 'pz-linkcard' ); ?></th>
			<td>
				<label>
					<input type="hidden"   name="properties[flg-delete-settings]" value="" />
					<input type="checkbox" name="properties[flg-delete-settings]" value="1" <?php checked($this->options['flg-delete-settings'] ); ?> />
					<?php echo __('Delete the settings when deleting the plugin.', 'pz-linkcard' ); ?>
				</label>
			</td>
		</tr>
		<tr>
			<th scope="row"><?php esc_html_e('Delete Image Cache', 'pz-linkcard' ); ?></th>
			<td>
				<label>
					<input type="hidden"   name="properties[flg-delete-image]" value="" />
					<input type="checkbox" name="properties[flg-delete-image]" value="1" <?php checked($this->options['flg-delete-image'] ); ?> />
					<?php echo __('Delete the image cache when deleting the plugin.', 'pz-linkcard' ); ?>
				</label>
			</td>
		</tr>
		<tr>
			<th scope="row"><?php esc_html_e('Delete Database', 'pz-linkcard' ); ?></th>
			<td>
				<label>
					<input type="hidden"   name="properties[flg-delete-db]" value="" />
					<input type="checkbox" name="properties[flg-delete-db]" value="1" <?php checked($this->options['flg-delete-db'] ); ?> />
					<?php echo __('Delete the LinkCard data when deleting the plugin.', 'pz-linkcard' ); ?>
				</label>
			</td>
		</tr>
	</table>
	<?php submit_button(); ?>
</div>
