<?php defined('ABSPATH' ) || wp_die; ?>
<?php
	if	(!isset($cacheman_allowed_html ) || !is_array($cacheman_allowed_html ) ) {
		$cacheman_allowed_html	=	wp_kses_allowed_html('post' );
	}
	$cacheman_allowed_html['input']		=	array(
		'accesskey'		=>	true,
		'class'			=>	true,
		'checked'		=>	true,
		'disabled'		=>	true,
		'id'			=>	true,
		'max'			=>	true,
		'name'			=>	true,
		'ondblclick'	=>	true,
		'readonly'		=>	true,
		'size'			=>	true,
		'style'			=>	true,
		'title'			=>	true,
		'type'			=>	true,
		'value'			=>	true,
	);
	$cacheman_allowed_html['button']	=	array(
		'aria-label'	=>	true,
		'class'			=>	true,
		'data-card-id'	=>	true,
		'data-target'	=>	true,
		'hidden'		=>	true,
		'formnovalidate'	=>	true,
		'name'			=>	true,
		'title'			=>	true,
		'type'			=>	true,
		'value'			=>	true,
	);
	$cacheman_allowed_html['a']			=	array(
		'class'			=>	true,
		'href'			=>	true,
		'referrerpolicy'	=>	true,
		'rel'			=>	true,
		'target'		=>	true,
		'title'			=>	true,
	);
	$cacheman_allowed_html['textarea']	=	array(
		'accesskey'		=>	true,
		'class'			=>	true,
		'id'			=>	true,
		'name'			=>	true,
		'ondblclick'	=>	true,
		'readonly'		=>	true,
		'rows'			=>	true,
		'wrap'			=>	true,
	);
	$cacheman_get_value = function($key, $default = '') use ($data) {
		return $data[$key] ?? $default;
	};
	$cacheman_url_info = $this->pz_GetURLInfo((string) $cacheman_get_value('url'));
	$cacheman_is_internal = !empty($cacheman_url_info['is_internal']);
	$cacheman_admin_edit_attr = function() {
		return !empty($this->options['admin-mode'] ) ? ' ondblclick="this.readOnly=false;"' : '';
	};
	$cacheman_text_input = function($key, $args = array() ) use ($cacheman_get_value, $cacheman_admin_edit_attr) {
		$name		=	$args['name'] ?? 'data['.$key.']';
		$value		=	$args['value'] ?? $cacheman_get_value($key );
		$size		=	$args['size'] ?? 8;
		$class		=	$args['class'] ?? '';
		$title		=	isset($args['title'] ) ? ' title="'.esc_attr($args['title'] ).'"' : '';
		$style		=	isset($args['style'] ) ? ' style="'.esc_attr($args['style'] ).'"' : '';
		$readonly	=	!empty($args['readonly'] ) ? ' readonly="readonly"' : '';
		$admin_edit	=	!empty($args['editable'] ) ? ' ondblclick="this.readOnly=false;"' : (!empty($args['admin_edit'] ) ? $cacheman_admin_edit_attr() : '');
		$class_attr	=	$class ? ' class="'.esc_attr($class ).'"' : '';
		return '<input name="'.esc_attr($name ).'" type="text" value="'.esc_attr($value ).'" size="'.esc_attr($size ).'"'.$title.$class_attr.$readonly.$admin_edit.$style.'>';
	};
	$cacheman_static_value = function($key, $args = array() ) use ($cacheman_get_value) {
		$name		=	$args['name'] ?? 'data['.$key.']';
		$value		=	$args['value'] ?? $cacheman_get_value($key );
		$display	=	$args['display'] ?? $value;
		$class		=	$args['class'] ?? '';
		$title		=	isset($args['title'] ) ? $args['title'] : $display;
		$class_attr	=	$class ? ' '.esc_attr($class ) : '';
		$html		=	'<input name="'.esc_attr($name ).'" type="hidden" value="'.esc_attr($value ).'">';
		if	(!empty($args['href'] ) && '' !== $display ) {
			$rel				=	$args['rel'] ?? 'external noopener noreferrer';
			$referrerpolicy		=	$args['referrerpolicy'] ?? 'no-referrer';
			return $html.'<a href="'.esc_url($args['href'] ).'" target="_blank" rel="'.esc_attr($rel ).'" referrerpolicy="'.esc_attr($referrerpolicy ).'" class="pz-man-cache-static-value pz-man-cache-static-link'.$class_attr.'" title="'.esc_attr($title ).'">'.esc_html($display ).'</a>';
		}
		return $html.'<span class="pz-man-cache-static-value'.$class_attr.'" title="'.esc_attr($title ).'">'.esc_html($display !== '' ? $display : '-' ).'</span>';
	};
	$cacheman_action_buttons = function() {
		return '<button type="submit" name="action" value="update" class="button button-primary button-large">'.esc_html(__('Update', 'pz-linkcard' ) ).'</button><button type="submit" name="action" value="cancel" class="button button-large" formnovalidate>'.esc_html(__('Cancel', 'pz-linkcard' ) ).'</button>';
	};
	$cacheman_field_label = function($label) {
		return sprintf(esc_html__('%s: ', 'pz-linkcard'), $label);
	};
	$cacheman_field_row = function($label, $field, $class = '') use ($cacheman_field_label) {
		if (preg_match('/\A(<input\b[^>]*class="pz-debug-only"[^>]*>)\s+(.+)\z/s', $field, $matches)) {
			$field = $matches[2].'<br class="pz-debug-only">'.$matches[1];
		}
		return '<tr'.($class ? ' class="'.esc_attr($class ).'"' : '' ).'><td colspan="2"><strong>'.$cacheman_field_label($label).'</strong> '.$field.'</td></tr>';
	};
	$cacheman_postbox_open = function($id, $title, $class = '', $header_action = '') {
		$box_class	=	'pz-man-cache-postbox'.($class ? ' '.esc_attr($class ) : '' );
		return '<div id="pz-man-cache-'.esc_attr($id).'" class="postbox '.$box_class.'"><div class="postbox-header"><h2>'.esc_html($title ).'</h2>'.$header_action.'</div><div class="inside">';
	};
	$cacheman_postbox_close = function() {
		return '</div></div>';
	};
	$cacheman_character_length = function($text) {
		$text = str_replace(array("\r\n", "\r"), "\n", (string) $text);
		return function_exists('mb_strlen' ) ? mb_strlen($text, 'UTF-8') : preg_match_all('/./us', $text);
	};
	$cacheman_character_count = function($text, $limit) use ($cacheman_character_length) {
		$count = $cacheman_character_length($text);
		return sprintf(esc_html__('Character count: %1$s / %2$s', 'pz-linkcard'), esc_html((string) $count), esc_html((string) $limit));
	};
	$cacheman_character_counter = function($key, $id, $limit, $byte_limit = 0) use ($cacheman_get_value, $cacheman_character_length, $cacheman_character_count) {
		$text = $cacheman_get_value($key);
		$over_limit = $cacheman_character_length($text) > $limit || ($byte_limit > 0 && strlen((string) $text) > $byte_limit);
		$byte_attrs = $byte_limit > 0
			? ' data-pz-character-count-byte-limit="'.esc_attr($byte_limit).'" title="'.esc_attr__('URLs can contain up to 65,535 bytes. Non-ASCII characters may use more than one byte.', 'pz-linkcard').'"'
			: '';
		return '<div class="pz-man-cache-character-count'.($over_limit ? ' pz-character-count-over-limit' : '').'" data-pz-character-count-for="'.esc_attr($id).'" data-pz-character-count-limit="'.esc_attr($limit).'" data-pz-character-count-template="'.esc_attr__('Character count: %1$s / %2$s', 'pz-linkcard').'"'.$byte_attrs.'>'.$cacheman_character_count($text, $limit).'</div>';
	};
	$cacheman_image_preview = function($key, $class = '') use ($cacheman_get_value) {
		$image_url			=	$cacheman_get_value($key );
		$image_cache_url	=	$image_url ? $this->pz_GetImage($image_url ) : '';
		$image_preview_url	=	$image_cache_url ?: $image_url;
		if	(!$image_preview_url ) {
			return '<div class="pz-man-cache-image-preview pz-man-cache-image-empty">-</div>';
		}
		return '<div class="pz-man-cache-image-preview'.($class ? ' '.esc_attr($class ) : '' ).'"><a href="'.esc_url($image_preview_url ).'" target="_blank" rel="external noopener noreferrer" referrerpolicy="no-referrer" class="pz-man-image-box-trigger"><div><img src="'.esc_url($image_preview_url ).'" alt="" loading="lazy" /></div></a></div>';
	};
	$cacheman_set_image_link = function($target_name, $label, $has_value) use ($cacheman_is_internal) {
		if ($cacheman_is_internal) return '';
		return '<button type="button" class="button-link pz-media-select-image pz-man-cache-set-image" data-target="'.esc_attr($target_name).'"'.($has_value ? ' hidden' : '').'>'.esc_html($label).'</button>';
	};
	$cacheman_clear_image_button = function($target_name, $label, $has_value) use ($cacheman_is_internal) {
		if ($cacheman_is_internal) {
			return '';
		}
		return '<button type="button" class="button-link pz-man-cache-clear-image" data-target="'.esc_attr($target_name ).'"'.($has_value ? '' : ' hidden' ).'>'.esc_html($label ).'</button>';
	};
	$cacheman_category_labels = function($post_id) {
		$post_id = intval($post_id);
		if ($post_id <= 0) {
			return '-';
		}
		$categories = get_the_category($post_id);
		if (empty($categories)) {
			return '-';
		}
		$labels = array();
		foreach ($categories as $category) {
			$labels[] = '<span class="pz-man-cache-category-label">'.esc_html($category->name).'</span>';
		}
		return implode(' ', $labels);
	};
	$stored_post_id		=	intval($cacheman_get_value('post_id' ) );
	$link_post_id_value	=	intval(url_to_postid((string) $cacheman_get_value('url' ) ) );
	$link_post_id_class	=	$link_post_id_value !== $stored_post_id ? 'pz-man-cache-post-id-changed' : '';
?>
	<div class="pz-man-screen-tabs pz-man-cache-screen-tabs">
		<div class="pz-man-screen-options pz-man-cache-screen-options">
			<button type="button" id="pz-man-cache-screen-options-toggle" class="pz-man-screen-options-toggle" title="<?php esc_attr_e('Screen Options', 'pz-linkcard'); ?>" aria-expanded="false" aria-controls="pz-man-cache-screen-options-panel" data-no-overlay="1">
				<span class="pz-screen-toggle-label-wide"><?php esc_html_e('Screen Options', 'pz-linkcard'); ?></span><span class="pz-screen-toggle-label-compact dashicons dashicons-forms" aria-hidden="true"></span><span class="dashicons dashicons-arrow-down-alt2"></span>
			</button>
			<div id="pz-man-cache-screen-options-panel" class="pz-man-screen-options-panel pz-man-cache-screen-options-panel" hidden>
				<fieldset>
					<legend><?php esc_html_e('Screen elements', 'pz-linkcard'); ?></legend>
					<div class="pz-man-cache-block-options">
						<?php
						$cacheman_blocks = array(
							'pz-man-cache-excerpt-box' => __('Excerpt', 'pz-linkcard'),
							'pz-man-cache-site' => __('Site Data', 'pz-linkcard'),
							'pz-man-cache-post' => __('Post Data', 'pz-linkcard'),
							'pz-man-cache-status' => __('Status', 'pz-linkcard'),
							'pz-man-cache-registration' => __('Registration Data', 'pz-linkcard'),
							'pz-man-cache-thumbnail' => __('Thumbnail Image', 'pz-linkcard'),
							'pz-man-cache-siteicon' => __('Site Icon', 'pz-linkcard'),
							'pz-man-cache-sns' => __('SNS', 'pz-linkcard'),
						);
						foreach ($cacheman_blocks as $block_id => $block_label) :
							$block_visible = $block_id !== 'pz-man-cache-registration';
						?>
							<label class="<?php echo $block_visible ? 'pz-man-screen-option-default' : 'pz-man-screen-option-default-hidden'; ?>"><input type="checkbox" data-pz-cache-block="<?php echo esc_attr($block_id); ?>" <?php checked($block_visible); ?> /><?php echo esc_html($block_label); ?></label>
						<?php endforeach; ?>
					</div>
				</fieldset>
				<button type="button" id="pz-man-cache-layout-reset" class="button" data-no-overlay="1"><?php esc_html_e('Reset positions', 'pz-linkcard'); ?></button>
			</div>
		</div>
	<div class="pz-page-help">
		<button type="button" class="pz-page-help-toggle" title="<?php echo esc_attr(__('Help', 'pz-linkcard' ).' F1'); ?>" aria-expanded="false" aria-controls="pz-cache-edit-help-panel" data-no-overlay="1">
			<?php esc_html_e('Help', 'pz-linkcard' ); ?><span class="dashicons dashicons-arrow-down-alt2"></span>
		</button>
		<div id="pz-cache-edit-help-panel" class="pz-page-help-panel" hidden>
			<h3><?php esc_html_e('Keyboard Shortcuts'); ?></h3>
			<dl class="pz-man-shortcut-list">
				<div><dt><kbd>F1</kbd></dt><dd><?php esc_html_e('Show Help', 'pz-linkcard' ); ?></dd></div>
				<div><dt><kbd>F2</kbd></dt><dd><?php esc_html_e('Edit title', 'pz-linkcard' ); ?></dd></div>
				<div><dt><kbd>F4</kbd></dt><dd><?php esc_html_e('Switch between Card Manager and Settings', 'pz-linkcard' ); ?></dd></div>
				<div><dt><kbd>Alt</kbd>+<kbd>S</kbd> / <kbd>Ctrl</kbd>+<kbd>S</kbd></dt><dd><?php esc_html_e('Update', 'pz-linkcard' ); ?></dd></div>
				<div><dt><kbd>Esc</kbd></dt><dd><?php esc_html_e('Cancel', 'pz-linkcard' ); ?></dd></div>
			</dl>
		</div>
	</div>
	</div>

	<button type="submit" name="action" value="update" class="pz-man-cache-default-submit" aria-hidden="true" tabindex="-1" style="position:absolute;left:-9999px;width:1px;height:1px;overflow:hidden;padding:0;border:0;"></button>

	<div id="poststuff" class="pz-man-cache-dirty-check pz-man-cache-poststuff" data-pz-cache-layout-key="<?php echo esc_attr('pz-linkcard-cacheman-layout:'.md5(home_url('/')).':'.get_current_user_id()); ?>">
		<div id="post-body" class="metabox-holder columns-2">
			<div id="post-body-content" class="pz-man-cache-main">
				<div id="titlediv" class="pz-man-cache-titlediv">
					<div id="titlewrap">
						<label class="screen-reader-text" for="pz-man-cache-title"><?php esc_html_e('Title', 'pz-linkcard' ); ?></label>
						<input id="pz-man-cache-title" name="data[title]" type="text" value="<?php echo esc_attr($cacheman_get_value('title' ) ); ?>" size="80" accesskey="2" placeholder="<?php esc_attr_e('Title', 'pz-linkcard' ); ?>" />
					</div>
					<?php echo wp_kses($cacheman_character_counter('title', 'pz-man-cache-title', 200), $cacheman_allowed_html); ?>
					<div class="inside pz-man-cache-permalink">
						<div class="pz-man-cache-permalink-row">
							<strong><?php echo $cacheman_field_label(esc_html__('URL', 'pz-linkcard')); ?></strong>
							<?php echo wp_kses($cacheman_static_value('url', array('class' => 'pz-monospace', 'display' => $this->pz_DecodeURL($cacheman_get_value('url' ) ), 'title' => $this->pz_DecodeURL($cacheman_get_value('url' ) ), 'href' => $cacheman_get_value('url' ) ) ), $cacheman_allowed_html ); ?>
						</div>
						<div class="pz-man-cache-permalink-row">
							<strong><?php echo $cacheman_field_label(esc_html__('Redirect URL', 'pz-linkcard')); ?></strong>
							<?php echo wp_kses($cacheman_static_value('url_redir', array('class' => 'pz-monospace', 'display' => $this->pz_DecodeURL($cacheman_get_value('url_redir' ) ), 'title' => $this->pz_DecodeURL($cacheman_get_value('url_redir' ) ), 'href' => $cacheman_get_value('url_redir' ) ) ), $cacheman_allowed_html ); ?>
						</div>
					</div>
				</div>

				<div id="pz-man-cache-excerpt-box" class="postbox pz-man-cache-postbox pz-man-cache-editor-box"><div class="inside">
					<textarea id="pz-man-cache-excerpt" name="data[excerpt]" rows="6" wrap="soft" accesskey="3" aria-label="<?php esc_attr_e('Excerpt', 'pz-linkcard'); ?>"><?php echo esc_textarea($cacheman_get_value('excerpt' ) ); ?></textarea>
					<?php echo wp_kses($cacheman_character_counter('excerpt', 'pz-man-cache-excerpt', 500), $cacheman_allowed_html); ?>
				<?php echo wp_kses($cacheman_postbox_close(), $cacheman_allowed_html ); ?>

				<div class="pz-man-cache-sortables" data-pz-cache-column="main">
				<?php echo wp_kses($cacheman_postbox_open('site', __('Site Data', 'pz-linkcard' ) ), $cacheman_allowed_html ); ?>
					<table class="form-table pz-man-cache-form-table">
						<?php
							$site_name_field = '<br><input id="pz-man-cache-site-name" name="data[site_name]" type="text" value="'.esc_attr($cacheman_get_value('site_name')).'" size="80" accesskey="1">';
							$site_name_field .= $cacheman_character_counter('site_name', 'pz-man-cache-site-name', 200);
							echo wp_kses($cacheman_field_row(esc_html__('Site Name', 'pz-linkcard'), $site_name_field), $cacheman_allowed_html);
						?>
						<?php
							$domain = (string) $cacheman_get_value('domain');
							$domain_display = esc_html($domain ?: '-');
							if (function_exists('idn_to_utf8') && defined('INTL_IDNA_VARIANT_UTS46')) {
								$decoded_domain = idn_to_utf8($domain, 0, INTL_IDNA_VARIANT_UTS46);
								if ($decoded_domain !== false && $decoded_domain !== '' && $decoded_domain !== $domain) {
									$domain_display = esc_html($decoded_domain).' ('.esc_html($domain).')';
								}
							}
							echo wp_kses($cacheman_field_row(esc_html__('Domain', 'pz-linkcard' ), $domain_display.'<br class="pz-debug-only">'.$cacheman_text_input('domain', array('class' => 'pz-debug-only pz-monospace', 'size' => 30, 'readonly' => true, 'admin_edit' => true)) ), $cacheman_allowed_html );
						?>
					</table>
				<?php echo wp_kses($cacheman_postbox_close(), $cacheman_allowed_html ); ?>

				<?php echo wp_kses($cacheman_postbox_open('post', __('Post Data', 'pz-linkcard' ) ), $cacheman_allowed_html ); ?>
					<table class="form-table pz-man-cache-form-table">
						<?php echo wp_kses($cacheman_field_row(esc_html__('Post ID', 'pz-linkcard' ), '<input name="data[post_id]" type="text" value="'.esc_attr($link_post_id_value > 0 ? $link_post_id_value : '' ).'" size="8" readonly="readonly" ondblclick="this.readOnly=false;" class="pz-debug-only"> <span class="'.esc_attr($link_post_id_class).'">'.esc_html($link_post_id_value > 0 ? $link_post_id_value : '-' ).'</span>' ), $cacheman_allowed_html ); ?>
						<?php echo wp_kses($cacheman_field_row(esc_html__('Post Date', 'pz-linkcard' ), '<input name="data[post_date]" type="text" value="'.esc_attr($cacheman_get_value('post_date' ) ).'" size="8" readonly="readonly" ondblclick="this.readOnly=false;" class="pz-debug-only"> '.esc_html($this->pz_Date(PZLKC_DATETIME_FORMAT, $cacheman_get_value('post_date' ) ) ) ), $cacheman_allowed_html ); ?>
						<?php echo wp_kses($cacheman_field_row(esc_html__('Update Date', 'pz-linkcard' ), '<input name="data[post_modified]" type="text" value="'.esc_attr($cacheman_get_value('post_modified' ) ).'" size="8" readonly="readonly" ondblclick="this.readOnly=false;" class="pz-debug-only"> '.esc_html($this->pz_Date(PZLKC_DATETIME_FORMAT, $cacheman_get_value('post_modified' ) ) ) ), $cacheman_allowed_html ); ?>
						<?php echo wp_kses($cacheman_field_row(esc_html__('Category', 'pz-linkcard' ), $cacheman_category_labels($link_post_id_value ) ), $cacheman_allowed_html ); ?>
					</table>
				<?php echo wp_kses($cacheman_postbox_close(), $cacheman_allowed_html ); ?>

				<?php echo wp_kses($cacheman_postbox_open('status', __('Status', 'pz-linkcard' ) ), $cacheman_allowed_html ); ?>
					<table class="form-table pz-man-cache-form-table pz-man-cache-status-table">

						<tr class="pz-man-cache-side-single-row">
							<td colspan="2"><label><input name="data[no_failure]" type="hidden" value="0" /><input name="data[no_failure]" type="checkbox" value="1" <?php checked(!empty($data['no_failure'] ), true ); ?> accesskey="4" /><?php esc_html_e('Ignore the result code when it indicates an error.', 'pz-linkcard' ); ?></label></td>
						</tr>
						<?php
							$rs = esc_attr($cacheman_get_value('alive_result' ) );
							echo wp_kses('<tr class="pz-man-cache-side-meta-row"><td colspan="2"><strong>'.$cacheman_field_label(esc_html__('Alive Check Result Code', 'pz-linkcard' )).'</strong> '.esc_html($rs ).' '.esc_html($this->pz_HTTPMessage($rs ) ).'<br class="pz-debug-only"><input name="data[alive_result]" type="text" value="'.esc_attr($cacheman_get_value('alive_result' ) ).'" class="pz-debug-only" size="2" readonly="readonly"'.$cacheman_admin_edit_attr().'></td></tr>', $cacheman_allowed_html );
						?>
						<tr class="pz-man-cache-side-meta-row">
							<td colspan="2"><strong><?php echo $cacheman_field_label(esc_html__('Last Alive Check', 'pz-linkcard' )); ?></strong> <?php echo esc_html($this->pz_Date(PZLKC_DATETIME_FORMAT, $cacheman_get_value('alive_time' ) ) ); ?><br class="pz-debug-only"><input name="data[alive_time]" type="text" value="<?php echo esc_attr($cacheman_get_value('alive_time' ) ); ?>" class="pz-debug-only" size="9" readonly="readonly"<?php if (!empty($this->options['admin-mode'] ) ) : ?> ondblclick="this.readOnly=false;"<?php endif; ?> /></td>
						</tr>
						<tr class="pz-debug-only pz-man-cache-side-meta-row">
							<td colspan="2"><strong><?php echo $cacheman_field_label(esc_html__('Next Alive Check', 'pz-linkcard' )); ?></strong> <?php echo esc_html($this->pz_Date(PZLKC_DATETIME_FORMAT, $cacheman_get_value('alive_nexttime' ) ) ); ?><br><input name="data[alive_nexttime]" type="text" value="<?php echo esc_attr($cacheman_get_value('alive_nexttime' ) ); ?>" size="9" readonly="readonly"<?php if (!empty($this->options['admin-mode'] ) ) : ?> ondblclick="this.readOnly=false;"<?php endif; ?> /></td>
						</tr>

						<?php
							$use_post_id_fields = '';
							$use_post_id_values = array();
							for	($use_post_id_index = 1; $use_post_id_index <= 6; $use_post_id_index++ ) {
								$use_post_id_key	=	'use_post_id'.$use_post_id_index;
								$use_post_id_value	=	intval($cacheman_get_value($use_post_id_key ) );
								if	($use_post_id_value > 0 ) {
									$use_post_id_values[] = $use_post_id_value;
								}
								$use_post_id_fields	.=	$cacheman_text_input($use_post_id_key, array(
									'value'			=>	$use_post_id_value > 0 ? $use_post_id_value : '',
									'size'			=>	5,
									'class'			=>	'pz-man-cache-post-id-input pz-debug-only',
									'readonly'		=>	true,
									'editable'		=>	true,
								) );
							}
							echo wp_kses('<tr class="pz-man-cache-side-meta-row pz-man-cache-post-id-summary-row"><td colspan="2"><strong>'.$cacheman_field_label(esc_html__('Used Post ID', 'pz-linkcard' )).'</strong> <span class="pz-man-cache-post-id-summary-value">'.esc_html(implode(__(', ', 'pz-linkcard' ), $use_post_id_values ) ?: '-' ).'</span> <button type="button" class="button-link pz-man-cache-post-id-refresh" data-card-id="'.intval($cache_id ).'" title="'.esc_attr__('Renew Post ID', 'pz-linkcard' ).'">↻</button><br class="pz-debug-only">'.$use_post_id_fields.'</td></tr>', $cacheman_allowed_html );
						?>
					</table>
				<?php echo wp_kses($cacheman_postbox_close(), $cacheman_allowed_html ); ?>

				<?php echo wp_kses($cacheman_postbox_open('registration', __('Registration Data', 'pz-linkcard' ), 'pz-man-cache-block-hidden' ), $cacheman_allowed_html ); ?>
					<table class="form-table pz-man-cache-form-table">
						<?php echo wp_kses($cacheman_field_row(esc_html__('Registered Title', 'pz-linkcard' ), '<br><input id="pz-man-cache-regist-title" name="data[regist_title]" type="text" value="'.esc_attr($cacheman_get_value('regist_title' ) ).'" size="80" readonly="readonly"'.$cacheman_admin_edit_attr().'>'.$cacheman_character_counter('regist_title', 'pz-man-cache-regist-title', 200) ), $cacheman_allowed_html ); ?>
						<?php echo wp_kses($cacheman_field_row(esc_html__('Registered Excerpt', 'pz-linkcard' ), '<br><textarea id="pz-man-cache-regist-excerpt" name="data[regist_excerpt]" rows="5" wrap="soft" readonly="readonly"'.$cacheman_admin_edit_attr().'>'.esc_textarea($cacheman_get_value('regist_excerpt' ) ).'</textarea>'.$cacheman_character_counter('regist_excerpt', 'pz-man-cache-regist-excerpt', 500) ), $cacheman_allowed_html ); ?>
						<?php echo wp_kses($cacheman_field_row(esc_html__('Registered Character Set', 'pz-linkcard' ), esc_html($cacheman_get_value('regist_charset' ) ).'<br class="pz-debug-only"><input name="data[regist_charset]" type="text" value="'.esc_attr($cacheman_get_value('regist_charset' ) ).'" class="pz-debug-only" size="8" readonly="readonly"'.$cacheman_admin_edit_attr().'>'), $cacheman_allowed_html ); ?>
						<?php
							$rs = esc_attr($cacheman_get_value('regist_result' ) );
							echo wp_kses($cacheman_field_row(esc_html__('Registered Result Code', 'pz-linkcard' ), esc_html($rs ).' '.esc_html($this->pz_HTTPMessage($rs ) ).'<br class="pz-debug-only"><input name="data[regist_result]" type="text" value="'.esc_attr($cacheman_get_value('regist_result' ) ).'" class="pz-debug-only" size="2" readonly="readonly"'.$cacheman_admin_edit_attr().'>'), $cacheman_allowed_html );
						?>
						<?php echo wp_kses($cacheman_field_row(esc_html__('Registered Date', 'pz-linkcard' ), esc_html($this->pz_Date(PZLKC_DATETIME_FORMAT, $cacheman_get_value('regist_time' ) ) ).'<br class="pz-debug-only"><input name="data[regist_time]" type="text" value="'.esc_attr($cacheman_get_value('regist_time' ) ).'" size="8" readonly="readonly"'.$cacheman_admin_edit_attr().' class="pz-debug-only">'), $cacheman_allowed_html ); ?>
					</table>
				<?php echo wp_kses($cacheman_postbox_close(), $cacheman_allowed_html ); ?>
				</div>
				<?php if (!empty($debug_mode)) : ?>
				<label class="pz-man-cache-debug-toggle"><input type="checkbox" id="pz-man-cache-debug-mode" checked="checked" /><?php esc_html_e('Debug Mode', 'pz-linkcard'); ?></label>
				<?php endif; ?>
			</div>

			<div id="postbox-container-1" class="postbox-container pz-man-cache-side pz-man-cache-sortables" data-pz-cache-column="side">
				<?php
					$cacheman_reload_button	=	'<button type="submit" name="single-edit" value="'.esc_attr($cacheman_get_value('id' ) ).'" class="pz-man-cache-reload-button pz-debug-only" title="'.esc_attr__('Reload', 'pz-linkcard' ).'" aria-label="'.esc_attr__('Reload', 'pz-linkcard' ).'" formnovalidate><span class="pz-man-cache-reload-icon" aria-hidden="true">↻</span></button>';
					echo wp_kses($cacheman_postbox_open('submit', __('Cache Editor', 'pz-linkcard' ), 'pz-man-cache-submitbox', $cacheman_reload_button ), $cacheman_allowed_html );
				?>
					<div class="pz-man-cache-submit-meta">
						<input name="data[id]" type="hidden" value="<?php echo esc_attr($cacheman_get_value('id' ) ); ?>" />
						<div class="pz-man-cache-submit-meta-row"><span><?php echo $cacheman_field_label(esc_html__('ID', 'pz-linkcard' )); ?></span> <span class="pz-man-cache-submit-meta-value"><?php echo esc_html($cacheman_get_value('id' ) ); ?></span></div>
						<div class="pz-man-cache-submit-meta-row"><span><?php echo $cacheman_field_label(esc_html__('Character Set', 'pz-linkcard' )); ?></span> <span class="pz-man-cache-submit-meta-value"><?php echo esc_html($cacheman_get_value('regist_charset' ) ); ?></span></div>
						<div class="pz-debug-only"><input name="data[charset]" type="text" value="<?php echo esc_attr($cacheman_get_value('charset')); ?>" class="pz-debug-only" readonly="readonly"<?php echo $cacheman_admin_edit_attr(); ?>></div>
						<div class="pz-man-cache-submit-meta-row"><span><?php echo $cacheman_field_label(esc_html__('Result Code', 'pz-linkcard')); ?></span> <span class="pz-man-cache-submit-meta-value"><?php $rs = $cacheman_get_value('update_result'); echo esc_html($rs).' '.esc_html($this->pz_HTTPMessage($rs)); ?><br class="pz-debug-only"><input name="data[update_result]" type="text" value="<?php echo esc_attr($rs); ?>" class="pz-debug-only" size="5" readonly="readonly"<?php echo $cacheman_admin_edit_attr(); ?>></span></div>
						<div class="pz-man-cache-submit-meta-row"><span><?php echo $cacheman_field_label(esc_html__('Click Count', 'pz-linkcard' )); ?></span> <span class="pz-man-cache-submit-meta-value"><?php echo esc_html(number_format_i18n(intval($cacheman_get_value('click_count' ) ) ) ); ?><br class="pz-debug-only"><input name="data[click_count]" type="text" value="<?php echo esc_attr($cacheman_get_value('click_count' ) ); ?>" class="pz-debug-only" size="9" readonly="readonly" ondblclick="this.readOnly=false;" /></span></div>
						<div class="pz-man-cache-submit-meta-row"><span><?php echo $cacheman_field_label(esc_html__('Registered Date', 'pz-linkcard' )); ?></span> <span class="pz-man-cache-submit-meta-value"><?php echo esc_html($this->pz_Date(PZLKC_DATETIME_FORMAT, $cacheman_get_value('regist_time' ) ) ); ?></span></div>
						<div class="pz-man-cache-submit-meta-row"><span><?php echo $cacheman_field_label(esc_html__('Update Date', 'pz-linkcard' )); ?></span> <span class="pz-man-cache-submit-meta-value"><?php echo esc_html($this->pz_Date(PZLKC_DATETIME_FORMAT, $cacheman_get_value('update_time' ) ) ); ?><br class="pz-debug-only"><input name="data[update_time]" type="text" value="<?php echo esc_attr($cacheman_get_value('update_time' ) ); ?>" class="pz-debug-only" size="9" readonly="readonly"<?php if (!empty($this->options['admin-mode'] ) ) : ?> ondblclick="this.readOnly=false;"<?php endif; ?> /></span></div>
					</div>
					<div id="major-publishing-actions">
						<div class="pz-man-cache-actions">
							<?php echo wp_kses($cacheman_action_buttons(), $cacheman_allowed_html ); ?>
						</div>
						<div class="clear"></div>
					</div>
				<?php echo wp_kses($cacheman_postbox_close(), $cacheman_allowed_html ); ?>

				<?php echo wp_kses($cacheman_postbox_open('thumbnail', __('Thumbnail Image', 'pz-linkcard' ), 'pz-man-cache-image-box' ), $cacheman_allowed_html ); ?>
					<?php echo wp_kses($cacheman_image_preview('thumbnail', 'pz-man-cache-thumbnail-preview' ), $cacheman_allowed_html ); ?>
					<?php echo wp_kses($cacheman_set_image_link('data[thumbnail]', __('Set thumbnail image', 'pz-linkcard'), $cacheman_get_value('thumbnail') !== ''), $cacheman_allowed_html); ?>
					<?php echo wp_kses($cacheman_clear_image_button('data[thumbnail]', __('Clear thumbnail image', 'pz-linkcard' ), $cacheman_get_value('thumbnail' ) !== '' ), $cacheman_allowed_html ); ?>
					<div class="pz-man-cache-image-url">
						<label><?php echo $cacheman_field_label(esc_html__('URL', 'pz-linkcard')); ?></label>
						<div class="pz-man-cache-image-url-control">
							<input id="pz-man-cache-thumbnail-url" name="data[thumbnail]" type="text" value="<?php echo esc_attr($cacheman_get_value('thumbnail' ) ); ?>" size="80" class="pz-monospace" readonly="readonly" ondblclick="this.readOnly=false;" />
						</div>
						<?php echo wp_kses($cacheman_character_counter('thumbnail', 'pz-man-cache-thumbnail-url', 65535, 65535), $cacheman_allowed_html); ?>
					</div>
				<?php echo wp_kses($cacheman_postbox_close(), $cacheman_allowed_html ); ?>

				<?php echo wp_kses($cacheman_postbox_open('siteicon', __('Site Icon', 'pz-linkcard' ), 'pz-man-cache-image-box' ), $cacheman_allowed_html ); ?>
					<?php echo wp_kses($cacheman_image_preview('favicon', 'pz-man-cache-siteicon-preview' ), $cacheman_allowed_html ); ?>
					<?php echo wp_kses($cacheman_set_image_link('data[favicon]', __('Set site icon', 'pz-linkcard'), $cacheman_get_value('favicon') !== ''), $cacheman_allowed_html); ?>
					<?php echo wp_kses($cacheman_clear_image_button('data[favicon]', __('Clear site icon', 'pz-linkcard' ), $cacheman_get_value('favicon' ) !== '' ), $cacheman_allowed_html ); ?>
					<div class="pz-man-cache-image-url">
						<label><?php echo $cacheman_field_label(esc_html__('URL', 'pz-linkcard')); ?></label>
						<div class="pz-man-cache-image-url-control">
							<input id="pz-man-cache-favicon-url" name="data[favicon]" type="text" value="<?php echo esc_attr($cacheman_get_value('favicon' ) ); ?>" size="80" class="pz-monospace" readonly="readonly" ondblclick="this.readOnly=false;" />
						</div>
						<?php echo wp_kses($cacheman_character_counter('favicon', 'pz-man-cache-favicon-url', 65535, 65535), $cacheman_allowed_html); ?>
					</div>
				<?php echo wp_kses($cacheman_postbox_close(), $cacheman_allowed_html ); ?>

				<?php echo wp_kses($cacheman_postbox_open('sns', __('SNS', 'pz-linkcard' ) ), $cacheman_allowed_html ); ?>
					<?php
						$sns_fields	=	array(
							'sns_twitter'	=>	__('X', 'pz-linkcard' ),
							'sns_facebook'	=>	__('facebook', 'pz-linkcard' ),
							'sns_hatena'	=>	__('Hatena', 'pz-linkcard' ),
							'sns_qiita_likes'	=>	__('Qiita likes', 'pz-linkcard' ),
							'sns_qiita_stock'	=>	__('Qiita stocks', 'pz-linkcard' ),
						);
					?>
					<table class="form-table pz-man-cache-form-table">
						<?php
							foreach	($sns_fields as $sns_key => $sns_label ) {
								$sns_count	=	$cacheman_get_value($sns_key, null );
								echo wp_kses('<tr><td colspan="2"><strong>'.$cacheman_field_label(esc_html($sns_label )).'</strong> <span class="pz-man-cache-sns-count">'.(!is_null($sns_count ) ? esc_html(number_format_i18n(intval($sns_count ) ) ) : esc_html(__('(Not yet acquired)', 'pz-linkcard' ) ) ).'</span><br class="pz-debug-only">'.$cacheman_text_input($sns_key, array(
									'size'			=>	5,
									'class'			=>	'pz-debug-only',
									'readonly'		=>	true,
									'admin_edit'	=>	true,
								) ).'</td></tr>', $cacheman_allowed_html );
							}
						?>

						<tr class="pz-man-cache-side-meta-row">
							<td colspan="2"><strong><?php echo $cacheman_field_label(esc_html__('Last SNS Check', 'pz-linkcard' )); ?></strong> <?php echo esc_html($this->pz_Date(PZLKC_DATETIME_FORMAT, $cacheman_get_value('sns_time' ) ) ); ?><br class="pz-debug-only"><input name="data[sns_time]" type="text" value="<?php echo esc_attr($cacheman_get_value('sns_time' ) ); ?>" class="pz-debug-only" size="9" readonly="readonly"<?php if (!empty($this->options['admin-mode'] ) ) : ?> ondblclick="this.readOnly=false;"<?php endif; ?> /></td>
						</tr>
						<tr class="pz-debug-only pz-man-cache-side-meta-row">
							<td colspan="2"><strong><?php echo $cacheman_field_label(esc_html__('Next SNS Check', 'pz-linkcard' )); ?></strong> <?php echo esc_html($this->pz_Date(PZLKC_DATETIME_FORMAT, $cacheman_get_value('sns_nexttime' ) ) ); ?><br><input name="data[sns_nexttime]" type="text" value="<?php echo esc_attr($cacheman_get_value('sns_nexttime' ) ); ?>" size="9" readonly="readonly"<?php if (!empty($this->options['admin-mode'] ) ) : ?> ondblclick="this.readOnly=false;"<?php endif; ?> /></td>
						</tr>
					</table>
				<?php echo wp_kses($cacheman_postbox_close(), $cacheman_allowed_html ); ?>
			</div>
		</div>
		<br class="clear">
	</div>
