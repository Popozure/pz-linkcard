<?php defined('ABSPATH' ) || wp_die; ?>
<div id="pz-modal">
  <div id="pz-close">
    <a><?php esc_html_e('×', 'pz-linkcard' ); ?></a>
  </div>
  <div id="pz-content">
    <form method="post">
      <label><?php esc_html_e('Input URL', 'pz-linkcard' ); ?></label><br>
      <?php
		$pz_mce_shortcodes = array_values(array_filter(array_unique(array_map('strval', array(
			$this->options['code1'] ?? '', $this->options['code2'] ?? '', $this->options['code3'] ?? '',
		) ) ) ) );
		$pz_mce_style_url = PZLKC_URL_STYLE.(!empty($this->options['flg-compress'] ) ? 'style.min.css' : 'style.css' );
		$pz_mce_labels = array(
			'loading'       => __('Loading Pz-LinkCard...', 'pz-linkcard' ),
			'previewError'  => __('The preview could not be displayed. Click to check the URL.', 'pz-linkcard' ),
			'postDate'      => __('Post Date', 'pz-linkcard' ),
			'modifiedDate'  => __('Modified Date', 'pz-linkcard' ),
			'urlPrompt'     => __('Enter a URL or search keyword.', 'pz-linkcard' ),
			'searchResults' => __('Search results', 'pz-linkcard' ),
		);
	  ?>
      <input id="pz-code" type="hidden" value="<?php echo esc_attr($this->options['code1'] ); ?>" data-shortcodes="<?php echo esc_attr(wp_json_encode($pz_mce_shortcodes ) ); ?>" data-labels="<?php echo esc_attr(wp_json_encode($pz_mce_labels ) ); ?>" data-preview-enabled="<?php echo !empty($this->options['flg-edit-preview'] ) ? '1' : '0'; ?>" data-ajax-url="<?php echo esc_url(admin_url('admin-ajax.php' ) ); ?>" data-render-nonce="<?php echo esc_attr(wp_create_nonce('pz_lkc_mce_render_card' ) ); ?>" data-search-nonce="<?php echo esc_attr(wp_create_nonce('pz_lkc_mce_post_search' ) ); ?>" data-style-url="<?php echo esc_url($pz_mce_style_url ); ?>" data-additional-style-url="<?php echo esc_url($this->options['css-add-url'] ?? '' ); ?>">
      <span class="pz-post-search-combobox">
        <input id="pz-url" type="text" inputmode="url" size="60" autocomplete="off" placeholder="<?php esc_attr_e('Enter a URL or search keyword', 'pz-linkcard' ); ?>" aria-autocomplete="list" aria-controls="pz-post-search-results">
        <div id="pz-post-search-results" role="listbox" aria-label="<?php esc_attr_e('Search results', 'pz-linkcard' ); ?>" data-ajax-url="<?php echo esc_url(admin_url('admin-ajax.php' ) ); ?>" data-nonce="<?php echo esc_attr(wp_create_nonce('pz_lkc_mce_post_search' ) ); ?>"></div>
      </span>
      <input id="pz-insert" type="submit" value="<?php esc_attr_e('Insert Linkcard', 'pz-linkcard' ); ?>" onClick="return false;" disabled>
    </form>
  </div>
</div>
<div id="pz-overlay"></div>
