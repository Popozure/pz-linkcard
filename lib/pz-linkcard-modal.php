<?php defined('ABSPATH' ) || wp_die; ?>
<div id="pz-modal">
  <div id="pz-close">
    <a><?php esc_html_e('×', 'pz-linkcard' ); ?></a>
  </div>
  <div id="pz-content">
    <form method="post">
      <label><?php esc_html_e('Input URL', 'pz-linkcard' ); ?></label><br>
      <input id="pz-code" type="hidden" value="<?php echo esc_attr($this->options['code1'] ); ?>">
      <input id="pz-url" type="url" size="60">
      <input id="pz-insert" type="submit" value="<?php esc_attr_e('Insert Linkcard', 'pz-linkcard' ); ?>" onClick="return false;" >
    </form>
  </div>
</div>
<div id="pz-overlay"></div>
