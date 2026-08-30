<?php defined('ABSPATH' ) || wp_die; ?>
<?php
	// ログのディレクトリの用意
	$dir			=	PZLKC_DIR_UPLOAD.'debug/';
	$dir_url		=	PZLKC_URL_UPLOAD.'debug/';
	if	(!is_dir($dir ) ) {
		if	(!wp_mkdir_p($dir ) ) {
			$dir	=	null;
			$url	=	null;
		}
	}
	if	($dir ) {
		$dir_url						=	preg_replace('/(http|https):(\/\/.*)/', '$2', $dir_url );	// スキームを外す
		$this->options['debug-dir']		=	$dir;
		$this->options['debug-url']		=	$dir_url;
	}


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
	$crawler	=	'Pz-LinkCard-Crawler/';
	if	(!$this->options['user-agent'] || mb_substr($this->options['user-agent'], 0, mb_strlen($crawler ) ) == $crawler ) {
		$this->options['user-agent']	=	$crawler.PZLKC_PLUGIN_VERSION;
	}

	// 管理者モード解除
	if ($this->options['admin-mode'] && !$this->options['debug-mode'] ) {
		$this->options['admin-mode']	=	0;
	}
