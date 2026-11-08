<?php
defined('ABSPATH' ) || wp_die;

trait Pz_LinkCard_Editor_Block {

	// ブロック登録
	public	function	action_register_block() {
		if	(!function_exists('register_block_type' ) ) {
			return;
		}
		if	((string)($this->options['flg-edit-block'] ?? '') !== '1' ) {
			return;
		}

		$shortcodes	=	array();
		foreach	(array('code1', 'code2', 'code3' ) as $key ) {
			$code	=	preg_replace('/[^a-zA-Z0-9]/', '', $this->options[$key] ?? '' );
			if	($code ) {
				$shortcodes[]	=	$code;
			}
		}
		if	(!$shortcodes ) {
			$shortcodes[]	=	self::pz_GetDefaultOption('code1' );
		}
		$shortcodes		=	array_values(array_unique($shortcodes ) );
		$editor_script	=	'pz-linkcard-block-editor';
		wp_register_script(
			$editor_script,
			$this->plugin_dir_url.'js/block-editor.js',
			array('wp-blocks', 'wp-block-editor', 'wp-editor', 'wp-components', 'wp-element', 'wp-i18n', 'wp-data', 'wp-hooks', 'wp-compose', 'wp-server-side-render' ),
			PZLKC_PLUGIN_VERSION,
			true
		);
		wp_localize_script($editor_script, 'pz_lkc_block_icon', array(
			'blockName'		=>	'pz-linkcard/linkcard',
			'iconUrl'		=>	$this->plugin_dir_url.'img/icon_lkc_block.svg',
			'previewUrl'	=>	$this->plugin_dir_url.'img/pz-lkc_block_preview.png',
			'shortcode'		=>	$shortcodes[0],
			'shortcodes'	=>	$shortcodes,
			'title'			=>	'Pz-LinkCard',
			'placeholder'	=>	__('Enter a URL or search keyword', 'pz-linkcard' ),
			'description'	=>	__('Display the linked page as a LinkCard.', 'pz-linkcard' ),
			'ajaxUrl'		=>	admin_url('admin-ajax.php' ),
			'searchNonce'	=>	wp_create_nonce('pz_lkc_mce_post_search' ),
			'searchResults'	=>	__('Search results', 'pz-linkcard' ),
			'postDate'		=>	__('Post Date', 'pz-linkcard' ),
			'modifiedDate'	=>	__('Modified Date', 'pz-linkcard' ),
		) );

		$block_args	=	array(
			'title'				=>	'Pz-LinkCard',
			'description'		=>	__('Display the linked page as a LinkCard.', 'pz-linkcard' ),
			'category'			=>	'widgets',
			'icon'				=>	'admin-links',
			'supports'			=>	array(
				'inserter'		=>	false,
			),
			'attributes'		=>	array(
				'url'			=>	array(
					'type'		=>	'string',
					'default'	=>	'',
				),
				'shortcode'		=>	array(
					'type'		=>	'string',
					'default'	=>	'',
				),
			),
			'render_callback'	=>	array($this, 'render_block_linkcard' ),
		);
		$block_args['editor_script']	=	$editor_script;
		register_block_type('pz-linkcard/linkcard', $block_args );
	}

	// Pz-LinkCard ブロック描画
	public	function	render_block_linkcard($attributes, $content = '' ) {
		$url	=	esc_url_raw($attributes['url'] ?? '' );
		if	(!$url ) {
			return	'<div class="linkcard"><div class="lkc-internal-wrap"><div class="lkc-info">'.esc_html(self::PLUGIN_NAME).'</div><div class="lkc-excerpt">'.esc_html__('No URL was specified.', 'pz-linkcard' ).'</div></div></div>';
		}
		$shortcode	=	preg_replace('/[^a-zA-Z0-9]/', '', $attributes['shortcode'] ?? '' );
		$available_shortcodes	=	array();
		foreach	(array('code1', 'code2', 'code3' ) as $key ) {
			$code	=	preg_replace('/[^a-zA-Z0-9]/', '', $this->options[$key] ?? '' );
			if	($code ) {
				$available_shortcodes[]	=	$code;
			}
		}
		if	(!$available_shortcodes ) {
			$available_shortcodes[]	=	self::pz_GetDefaultOption('code1' );
		}
		if	(!$shortcode || !in_array($shortcode, $available_shortcodes, true ) ) {
			$shortcode	=	$available_shortcodes[0];
		}

		$atts	=	array();
		$atts['url']	=	$url;
		if	(!empty($attributes['title'] ) ) {
			$atts['title']	=	sanitize_text_field($attributes['title'] );
		}
		if	(!empty($attributes['content'] ) ) {
			$atts['content']	=	sanitize_textarea_field($attributes['content'] );
		}
		return	$this->shortcode($atts, null, $shortcode );
	}

	// ブロックエディタ用スクリプト
	public	function	action_enqueue_block_editor_assets() {

		wp_enqueue_style('pz-linkcard-block-editor', $this->plugin_dir_url.'css/admin.css', array(), PZLKC_PLUGIN_VERSION.'.'.filemtime($this->plugin_dir_path.'css/admin.css' ) );
		$this->enqueue_block_card_styles();
	}

	// ブロックエディタ本文用スタイルシート
	public	function	action_enqueue_block_assets() {
		if	(!is_admin() ) {
			return;
		}

		$this->enqueue_block_card_styles();
	}

	// ブロックエディタ内プレビュー用カードスタイル
	private	function	enqueue_block_card_styles() {
		$css_version	=	PZLKC_PLUGIN_VERSION.'.'.$this->options['css-count'];
		$pending_loader_css	= '.pz-lkc-pending .lkc-external-wrap{position:relative}.pz-lkc-pending-loader{position:absolute;z-index:10;top:50%;left:50%;display:flex;width:66px;height:66px;margin:-33px 0 0 -33px;border-radius:4px;background:rgba(0,0,0,.45);align-items:center;justify-content:center;pointer-events:none}.pz-lkc-pending-loader::after{content:"";width:50px;aspect-ratio:1;border-radius:50%;background:radial-gradient(farthest-side,#fff 94%,#0000) top/8px 8px no-repeat,conic-gradient(#0000 30%,#fff);mask:radial-gradient(farthest-side,#0000 calc(100% - 8px),#000 0);-webkit-mask:radial-gradient(farthest-side,#0000 calc(100% - 8px),#000 0);animation:pz-lkc-pending-loader-spin 1s infinite linear}@keyframes pz-lkc-pending-loader-spin{100%{transform:rotate(1turn)}}';
		if	($this->options['flg-compress'] ) {
			wp_enqueue_style	('pz-linkcard-block-card-css',	PZLKC_URL_STYLE.'style.min.css',	array(),	$css_version );
		} else {
			wp_enqueue_style	('pz-linkcard-block-card-css',	PZLKC_URL_STYLE.'style.css',		array(),	$css_version );
		}
		wp_add_inline_style('pz-linkcard-block-card-css', $pending_loader_css );
		if	($this->options['css-add-url'] ) {
			wp_enqueue_style	('pz-linkcard-block-card-css-add',	$this->options['css-add-url'],	array(),	$css_version );
		}
		$this->enqueue_excerpt_fit_script();
		$this->enqueue_star_field_script();
		$this->enqueue_snowfall_script();
		$this->enqueue_pointer_tracking_script();
	}

}
