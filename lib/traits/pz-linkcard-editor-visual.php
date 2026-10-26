<?php
defined('ABSPATH' ) || wp_die;

trait Pz_LinkCard_Editor_Visual {

	// Pz-LinkCard挿入ダイアログを表示する投稿編集画面か
	private	function	is_editor_modal_screen($hook = null ) {
		if	(empty($this->options['flg-edit-insert'] ) && empty($this->options['flg-edit-preview'] ) ) {
			return	false;
		}
		if	($hook && !in_array($hook, array('post.php', 'post-new.php' ), true ) ) {
			return	false;
		}
		if	(!function_exists('get_current_screen' ) ) {
			return	false;
		}

		$screen	=	get_current_screen();
		if	(!$screen || $screen->base !== 'post' ) {
			return	false;
		}

		$post_type	=	$screen->post_type;
		if	(!$post_type && isset($_GET['post'] ) ) {
			$post_type	=	get_post_type((int)$_GET['post'] );
		}
		if	(!$post_type && isset($_GET['post_type'] ) ) {
			$post_type	=	sanitize_key(wp_unslash($_GET['post_type'] ) );
		}
		if	(!$post_type ) {
			$post_type	=	'post';
		}

		return	post_type_supports($post_type, 'editor' );
	}

	// 管理画面時の設定（フッター）
	public	function	action_admin_print_footer_scripts() {

		// テキスト エディタ用のクイックタグ
		if	($this->options['flg-edit-qtag'] ) {
			if	(wp_script_is('quicktags' ) ) {
				echo '<script>QTags.addButton(\'pz-lkc\',\''.esc_js(__('Linkcard', 'pz-linkcard' ) ).'\',\'['.esc_js($this->options['code1'] ).' url="\',\'"]\',\'\',\''.esc_js(__('Make Linkcard', 'pz-linkcard' ) ).'\' );</script>';
			}
		}
		// ビジュアル エディタ用の挿入ダイアログ
		if	(!$this->is_editor_modal_screen() ) {
			return;
		}
		$this->render_visual_editor_modal();
	}

	// ビジュアルエディターのリンクカード挿入ダイアログから記事タイトルを検索
	public	function	action_ajax_pz_lkc_mce_post_search() {
		return	require(dirname(__DIR__).'/ajax/pz-linkcard-ajax-mce-post-search.php' );
	}

	// ビジュアルエディター内のリンクカードプレビュー
	// クリックカウント
	public	function	action_ajax_pz_lkc_mce_render_card() {
		return	require(dirname(__DIR__).'/ajax/pz-linkcard-ajax-mce-render-card.php' );
	}

	// 管理画面時のスタイルシート、スクリプト設定
	public	function	filter_mce_external_plugins($plugins ) {

		if	($this->options['flg-edit-insert'] || $this->options['flg-edit-preview'] ) {
			$mce_script	=	$this->plugin_dir_path.'js/mce-button.js';
			$plugins[ "pz_linkcard_tinymce" ]	=	add_query_arg('ver', file_exists($mce_script ) ? filemtime($mce_script ) : PZLKC_PLUGIN_VERSION, $this->plugin_dir_url.'js/mce-button.js' );
		}
		return	$plugins;
	}

	// 管理画面時のスタイルシート、スクリプト設定
	public	function	filter_mce_buttons($buttons ) {

		if	($this->options['flg-edit-insert'] ) {
			$buttons[]							=	'pz_linkcard_insert_shortcode';
		}
		return	$buttons;
	}

	private function render_visual_editor_modal() {
?>
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
			?>
			<input id="pz-code" type="hidden" value="<?php echo esc_attr($this->options['code1'] ); ?>" data-shortcodes="<?php echo esc_attr(wp_json_encode($pz_mce_shortcodes ) ); ?>" data-preview-enabled="<?php echo !empty($this->options['flg-edit-preview'] ) ? '1' : '0'; ?>" data-ajax-url="<?php echo esc_url(admin_url('admin-ajax.php' ) ); ?>" data-render-nonce="<?php echo esc_attr(wp_create_nonce('pz_lkc_mce_render_card' ) ); ?>" data-style-url="<?php echo esc_url($pz_mce_style_url ); ?>" data-additional-style-url="<?php echo esc_url($this->options['css-add-url'] ?? '' ); ?>" data-post-date-label="<?php echo esc_attr__('Post Date', 'pz-linkcard' ); ?>" data-modified-date-label="<?php echo esc_attr__('Modified Date', 'pz-linkcard' ); ?>">
			<span class="pz-post-search-combobox">
				<input id="pz-url" type="text" inputmode="url" size="60" autocomplete="off" placeholder="<?php esc_attr_e('Enter a URL or search keyword', 'pz-linkcard' ); ?>" aria-autocomplete="list" aria-controls="pz-post-search-results">
				<div id="pz-post-search-results" role="listbox" aria-label="<?php esc_attr_e('Search results', 'pz-linkcard' ); ?>" data-ajax-url="<?php echo esc_url(admin_url('admin-ajax.php' ) ); ?>" data-nonce="<?php echo esc_attr(wp_create_nonce('pz_lkc_mce_post_search' ) ); ?>"></div>
			</span>
			<input id="pz-insert" type="submit" value="<?php esc_attr_e('Insert Linkcard', 'pz-linkcard' ); ?>" onClick="return false;" disabled>
		</form>
	</div>
</div>
<div id="pz-overlay"></div>
<?php
	}
}
