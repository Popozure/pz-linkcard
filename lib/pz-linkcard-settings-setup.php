<?php defined('ABSPATH' ) || wp_die; ?>
<?php
	// サムネイルのキャッシュディレクトリの用意
	$dir			=	PZLKC_DIR_UPLOAD.'cache/';
	$dir_url		=	PZLKC_URL_UPLOAD.'cache/';
	if	(!is_dir($dir ) ) {
		if	(!wp_mkdir_p($dir ) ) {
			$dir	=	null;
			$url	=	null;
		}
	}
	if	($dir ) {
		$dir_url						=	preg_replace('/(http|https):(\/\/.*)/', '$2', $dir_url );	// スキームを外す
		$this->options['thumbnail-dir']	=	$dir;
		$this->options['thumbnail-url']	=	$dir_url;
	}

	// ユーザーエージェントの設定
	$this->options['user-agent']		=	$this->pz_GetUserAgentSelection($this->options['user-agent'] ?? 'pzlkc' );
	$this->options['user-agent-text']	=	$this->pz_GetUserAgent($this->options['user-agent'] );

	// 管理者モード解除
	if ($this->options['admin-mode'] && !$this->options['debug-mode'] ) {
		$this->options['admin-mode']	=	0;
	}
