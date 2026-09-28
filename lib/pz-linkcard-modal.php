<?php defined('ABSPATH' ) || wp_die; ?>
<div id="pz-modal">
  <div id="pz-close">
    <a><?php esc_html_e('×', 'pz-linkcard' ); ?></a>
  </div>
  <div id="pz-content">
    <form method="post">
      <label><?php esc_html_e('Input URL', 'pz-linkcard' ); ?></label><br>
      <input id="pz-code" type="hidden" value="<?php echo esc_attr($this->options['code1'] ); ?>">
      <span class="pz-post-search-combobox">
        <input id="pz-url" type="text" inputmode="url" size="60" autocomplete="off" placeholder="<?php esc_attr_e('Enter a URL or search keyword', 'pz-linkcard' ); ?>" aria-autocomplete="list" aria-controls="pz-post-search-results">
        <div id="pz-post-search-results" role="listbox" aria-label="<?php esc_attr_e('Search results', 'pz-linkcard' ); ?>" data-ajax-url="<?php echo esc_url(admin_url('admin-ajax.php' ) ); ?>" data-nonce="<?php echo esc_attr(wp_create_nonce('pz_lkc_mce_post_search' ) ); ?>"></div>
      </span>
      <input id="pz-insert" type="submit" value="<?php esc_attr_e('Insert Linkcard', 'pz-linkcard' ); ?>" onClick="return false;" disabled>
    </form>
  </div>
</div>
<div id="pz-overlay"></div>
