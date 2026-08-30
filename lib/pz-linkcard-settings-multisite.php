<?php defined('ABSPATH' ) || wp_die; ?>
<div class="pz-page<?php echo $pz_page_active('pz-multisite' ); ?>" id="pz-multisite">
	<div class="pz-submit-float"><?php submit_button(); ?></div>
	<h2><?php echo	__('Multisite Information', 'pz-linkcard' ).$help_open.'multisite'.$help_close; ?></h2>
	<div class="pz-multi-notice"><?php echo __('*** Cannot be changed ***', 'pz-linkcard' ); ?></div>
	<table class="form-table">
		<tr>
			<th scope="row"><?php esc_html_e('Multisite', 'pz-linkcard' ); ?></th>
			<td>
				<select>
					<option value="" <?php selected(!$is_multisite ); disabled( $is_multisite ); ?>><?php esc_html_e('Disabled',			'pz-linkcard' ); ?></option>
					<option value="1" <?php selected( $is_multisite ); disabled(!$is_multisite ); ?>><?php esc_html_e('Enabled',			'pz-linkcard' ); ?></option>
				</select>
			</td>
		</tr>
		<tr>
			<th scope="row"><?php esc_html_e('Type', 'pz-linkcard' ); ?></th>
			<td>
				<select>
					<option value="" <?php selected(!$is_subdomain ); disabled( $is_subdomain ); ?>><?php esc_html_e('Subdirectories',	'pz-linkcard' ); ?></option>
					<option value="1" <?php selected( $is_subdomain ); disabled(!$is_subdomain ); ?>><?php esc_html_e('Subdomains',		'pz-linkcard' ); ?></option>
				</select>
			</td>
		</tr>
		<tr>
			<th scope="row"><?php esc_html_e('Current Blog ID', 'pz-linkcard' ); ?></th>
			<td>
				<select name="properties[multi-myid]">
					<option value="<?php echo	esc_attr($multi_myid ); ?>" readonly="readonly" /><?php echo	esc_attr($multi_myid ); ?></option>
				</select>
			</td>
		</tr>
		<tr>
			<th scope="row"><?php esc_html_e('Number of Sites', 'pz-linkcard' ); ?></th>
			<td>
				<input name="properties[multi-count]" type="text" size="8" value="<?php echo	esc_attr($multi_count ); ?>" readonly="readonly" />
			</td>
		</tr>
		<tr>
			<th scope="row"><?php esc_html_e('Table Name', 'pz-linkcard' ); ?></th>
			<td><input type="text" size="40" value="<?php echo esc_html($this->db_name ); ?>" readonly="readonly" /></td>
		</tr>
		<tr>
			<th scope="row"><?php esc_html_e('Links to Subsites', 'pz-linkcard' ); ?></th>
			<td>
				<label>
					<input type="checkbox" value="1" checked="checked" readonly="readonly" />
					<?php esc_html_e('Treat links to subsites as external links.', 'pz-linkcard' ); ?>
				</label>
			</td>
		</tr>
	</table>
	<?php submit_button(); ?>

	<div class="pz-submit-float"><?php submit_button(); ?></div>
	<h2><?php echo	__('Site List', 'pz-linkcard' ).$help_open.'multisite'.$help_close; ?></h2>
	<div class="pz-multi-notice"><?php echo __('*** Cannot be changed ***', 'pz-linkcard' ); ?></div>
	<table class="form-table pz-multi-list widefat striped">
		<thead>
			<tr>
				<th scope="col" class="pz-multi-head-current"><?php esc_html_e('Current', 'pz-linkcard' ); ?></th>
				<th scope="col" class="pz-multi-head-blog-id"><?php esc_html_e('Blog ID', 'pz-linkcard' ); ?></th>
				<th scope="col" class="pz-multi-head-site-name"><?php esc_html_e('Site Name', 'pz-linkcard' ); ?></th>
				<th scope="col" class="pz-multi-head-url"><?php esc_html_e('URL', 'pz-linkcard' ); ?></th>
				<th scope="col" class="pz-multi-head-domain"><?php esc_html_e('Domain', 'pz-linkcard' ); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php for ($i = 1; $i <= $multi_count; $i++) { ?>
			<tr>
				<th class="pz-multi-body-current" scope="row">
					<input type="hidden"   name="" value="" />
					<input type="checkbox" name="" value="1" <?php checked($multi[$i]['id'] == $multi_myid ); ?> readonly="readonly" />
				</th>
				<td class="pz-multi-body-blog-id"	><input type="hidden" value="<?php echo	esc_attr($multi[$i]['id'] );     ?>" /><?php echo	esc_html($multi[$i]['id'] );     ?></td>
				<td class="pz-multi-body-site-name"	><input type="hidden" value="<?php echo	esc_attr($multi[$i]['name'] );   ?>" /><?php echo	esc_html($multi[$i]['name'] );   ?></td>
				<td class="pz-multi-body-url"		><input type="hidden" value="<?php echo	esc_attr($multi[$i]['url'] );    ?>" /><?php echo	esc_html($multi[$i]['url'] );    ?></td>
				<td class="pz-multi-body-domain"	><input type="hidden" value="<?php echo	esc_attr($multi[$i]['domain'] ); ?>" /><?php echo	esc_html($multi[$i]['domain'] ); ?></td>
			</tr>
			<?php } ?>
		</tbody>
	</table>
	<?php submit_button(); ?>
</div>
