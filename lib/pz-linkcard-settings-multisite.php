<?php defined('ABSPATH' ) || wp_die; ?>
<?php
	global	$wpdb;

	$multi_disabled_attr	=	$is_multisite ? '' : ' disabled="disabled"';
	$multi_readonly_attr	=	$is_multisite ? ' readonly="readonly" aria-readonly="true"' : '';
	$multi_subsite_checked	=	$is_multisite && (int)$multi_myid === 1;
	$multi_subsite_attr		=	$is_multisite ? ' aria-readonly="true" data-pz-locked-checkbox="1"' : $multi_disabled_attr;
	$multi_site_list_attr	=	$is_multisite ? ' aria-readonly="true" data-pz-locked-checkbox="1"' : ' disabled="disabled"';
	$db_table_exists	=	false;
	if	(isset($wpdb ) && $this->db_name ) {
		$db_table_exists	=	($wpdb->get_var($wpdb->prepare('SHOW TABLES LIKE %s', $this->db_name ) ) === $this->db_name );
	}
?>
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
				<select<?php echo $multi_disabled_attr.$multi_readonly_attr; ?>>
					<option value="" <?php  selected(!$is_subdomain ); disabled( $is_subdomain ); ?>><?php esc_html_e('Subdirectories',	'pz-linkcard' ); ?></option>
					<option value="1" <?php selected( $is_subdomain ); disabled(!$is_subdomain ); ?>><?php esc_html_e('Subdomains',		'pz-linkcard' ); ?></option>
				</select>
			</td>
		</tr>
		<tr>
			<th scope="row"><?php esc_html_e('Current Blog ID', 'pz-linkcard' ); ?></th>
			<td>
				<?php if (!$is_multisite ) { ?><input type="hidden" name="properties[multi-myid]" value="<?php echo esc_attr($multi_myid ); ?>" /><?php } ?>
				<select name="properties[multi-myid]"<?php echo $multi_disabled_attr; ?>>
					<?php for ($i = 1; $i <= $multi_count; $i++) { ?>
					<option value="<?php echo esc_attr($i ); ?>" <?php selected($i, $multi_myid ); disabled($i !== (int)$multi_myid ); ?>><?php echo esc_html($i ); ?></option>
					<?php } ?>
				</select>
			</td>
		</tr>
		<tr>
			<th scope="row"><?php esc_html_e('Number of Sites', 'pz-linkcard' ); ?></th>
			<td>
				<?php if (!$is_multisite ) { ?><input type="hidden" name="properties[multi-count]" value="<?php echo esc_attr($multi_count ); ?>" /><?php } ?>
				<select name="properties[multi-count]"<?php echo $multi_disabled_attr; ?>>
					<?php for ($i = 0; $i <= $multi_count; $i++) { ?>
					<option value="<?php echo esc_attr($i ); ?>" <?php selected($i, $multi_count ); disabled($i !== (int)$multi_count ); ?>><?php echo esc_html($i ); ?></option>
					<?php } ?>
				</select>
			</td>
		</tr>
		<tr>
			<th scope="row"><?php esc_html_e('Table Name', 'pz-linkcard' ); ?></th>
			<td>
				<input type="text" size="40" value="<?php echo esc_attr($this->db_name ); ?>" class="pz-multi-normal-control" readonly="readonly" />
				<span class="pz-table-exists-badge <?php echo $db_table_exists ? 'pz-table-exists' : 'pz-table-missing'; ?>">
					<?php echo $db_table_exists ? wp_kses_post(__('&#x2705;&#xFE0F;', 'pz-linkcard' ) ).esc_html__('Exists', 'pz-linkcard' ) : wp_kses_post(__('&#x1F6AB;&#xFE0F;', 'pz-linkcard' ) ).esc_html__('Not Found.', 'pz-linkcard' ); ?>
				</span>
			</td>
		</tr>
		<tr>
			<th scope="row"><?php esc_html_e('Links to Subsites', 'pz-linkcard' ); ?></th>
			<td>
				<label class="pz-multi-normal-label">
					<input type="checkbox" value="1" class="pz-multi-normal-control"<?php checked($multi_subsite_checked ); echo $multi_subsite_attr; ?> />
					<?php esc_html_e('Treat links to subsites as external links.', 'pz-linkcard' ); ?>
				</label>
			</td>
		</tr>
	</table>

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
					<input type="checkbox" name="" value="1" <?php checked($multi[$i]['id'] == $multi_myid ); echo $multi_site_list_attr; ?> />
				</th>
				<td class="pz-multi-body-blog-id"	><input type="hidden" value="<?php echo	esc_attr($multi[$i]['id'] );     ?>" /><?php echo	esc_html($multi[$i]['id'] );     ?></td>
				<td class="pz-multi-body-site-name"	><input type="hidden" value="<?php echo	esc_attr($multi[$i]['name'] );   ?>" /><?php echo	esc_html($multi[$i]['name'] );   ?></td>
				<td class="pz-multi-body-url"		><input type="hidden" value="<?php echo	esc_attr($multi[$i]['url'] );    ?>" /><?php echo	esc_html($multi[$i]['url'] );    ?></td>
				<td class="pz-multi-body-domain"	><input type="hidden" value="<?php echo	esc_attr($multi[$i]['domain'] ); ?>" /><?php echo	esc_html($multi[$i]['domain'] ); ?></td>
			</tr>
			<?php } ?>
		</tbody>
	</table>
</div>
