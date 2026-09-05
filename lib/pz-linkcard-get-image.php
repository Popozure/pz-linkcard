<?php defined('ABSPATH' ) || wp_die; ?>
<?php

	if	(!isset($thumbnail_url ) || !$thumbnail_url || $thumbnail_url == 'https://s0.wp.com/i/blank.jpg' ) {
		return	null;
	}

	$file_dir		=	PZLKC_DIR_CACHE;
	$file_dir_url	=	PZLKC_URL_CACHE;
	if	(!$file_dir || !$file_dir_url ) {
		return	null;
	}

	$url_info		=	$this->pz_GetURLInfo($thumbnail_url );
	$is_internal	=	$url_info['is_internal'] ?? false;

	if	($is_internal ) {
		return	$thumbnail_url;
	}

	$file_name		=	bin2hex(hash('sha256', esc_url($thumbnail_url ), true ) );
	$file_path_webp	=	$file_dir.$file_name.'.webp';
	$file_path_jpeg	=	$file_dir.$file_name.'.jpeg';
	$file_url_webp	=	$file_dir_url.$file_name.'.webp';
	$file_url_jpeg	=	$file_dir_url.$file_name.'.jpeg';

	if	($this->pz_IsLocalAddress($thumbnail_url ) ) {
		touch($file_path_webp );
		return	null;
	}

	if	(!$force ) {
		if	(file_exists($file_path_webp ) ) {
			if	(filesize($file_path_webp ) < 12 ) {
				return	null;
			}
			if	($stamp === true ) {
				$file_url_webp	.=	'?'.date('yyyymmdd-his', filemtime($file_path_webp ) );
			}
			return	$file_url_webp;
		}

		if	(file_exists($file_path_jpeg ) ) {
			if	(filesize($file_path_jpeg ) < 12 ) {
				return	null;
			}
			if	($stamp === true ) {
				$file_url_jpeg	.=	'?'.date('yyyymmdd-his', filemtime($file_path_jpeg ) );
			}
			return	$file_url_jpeg;
		}
	}

	// ここから画像取得処理
	global	$wp_version;

	$thumbnail_url	=	$this->pz_EncodeURL($thumbnail_url, true );
	$rget_args							=	array();
	$rget_args['timeout']				=	10;
	$rget_args['redirection']			=	$this->options['flg-redir'] ? 8 : 0;
	$rget_args['limit_response_size']	=	defined('MB_IN_BYTES' ) ? MB_IN_BYTES * 5 : 5242880;
	$rget_args['user-agent']			=	$this->options['flg-agent'] ? $this->options['user-agent'] : 'WordPress/'.$wp_version.'; '.get_bloginfo('url' );
	$rget_args['sslverify']				=	$this->options['flg-sslverify'] ? true : false;

	$rget_data	=	wp_safe_remote_get($thumbnail_url, $rget_args );
	if	(is_wp_error($rget_data ) ) {
		touch($file_path_webp );
		return	null;
	}

	$http_code	=	intval(wp_remote_retrieve_response_code($rget_data ) );
	$body		=	wp_remote_retrieve_body($rget_data );
	if	($http_code >= 400 || !$body ) {
		touch($file_path_webp );
		return	null;
	}

	if	(!function_exists('imagecreatefromstring' ) || !function_exists('imagecreatetruecolor' ) || !function_exists('imagecopyresampled' ) ) {
		touch($file_path_webp );
		return	null;
	}

	$image	=	@imagecreatefromstring($body );
	if	($image === false ) {
		touch($file_path_webp );
		return	null;
	}

	$image_width	=	@imagesx($image );
	$image_height	=	@imagesy($image );
	if	($image_width === false || $image_height === false || $image_width <= 0 || $image_height <= 0 ) {
		imagedestroy($image );
		touch($file_path_webp );
		return	null;
	}

	switch	($this->options['ex-thumbnail-size'] ) {
	case	'thumbnail':
		$max_width	=	150;
		$max_height	=	150;
		break;
	case	'medium':
		$max_width	=	300;
		$max_height	=	300;
		break;
	case	'large':
		$max_width	=	1024;
		$max_height	=	1024;
		break;
	case	'full':
		$max_width	=	$image_width;
		$max_height	=	$image_height;
		break;
	default:
		$max_width	=	150;
		$max_height	=	150;
		break;
	}

	$scale		=	min($max_width / $image_width, $max_height / $image_height );
	$new_width	=	intval($image_width * $scale );
	$new_height	=	intval($image_height * $scale );
	if	($new_width <= 1 || $new_height <= 1 ) {
		imagedestroy($image );
		touch($file_path_webp );
		return	null;
	}

	if	(function_exists('imagepalettetotruecolor' ) ) {
		imagepalettetotruecolor($image );
	}
	imagealphablending($image, false );
	imagesavealpha($image, true );

	$image_pallet	=	imagecreatetruecolor($new_width, $new_height );
	if	(!$image_pallet ) {
		imagedestroy($image );
		touch($file_path_webp );
		return	null;
	}

	if	(function_exists('imagewebp' ) ) {
		imagealphablending($image_pallet, false );
		imagesavealpha($image_pallet, true );
		$image_pallet_bg	=	imagecolorallocatealpha($image_pallet, 0, 0, 0, 127 );
		imagefill($image_pallet, 0, 0, $image_pallet_bg );
		imagecopyresampled($image_pallet, $image, 0, 0, 0, 0, $new_width, $new_height, $image_width, $image_height );
		if	(!imagewebp($image_pallet, $file_path_webp ) ) {
			imagedestroy($image_pallet );
			imagedestroy($image );
			touch($file_path_webp );
			return	null;
		}
		imagedestroy($image_pallet );
		imagedestroy($image );
		if	($stamp === true ) {
			$file_url_webp	.=	'?'.date('yyyymmdd-his', filemtime($file_path_webp ) );
		}
		return	$file_url_webp;
	}

	if	(!function_exists('imagejpeg' ) ) {
		imagedestroy($image_pallet );
		imagedestroy($image );
		touch($file_path_webp );
		return	null;
	}

	$image_pallet_bg	=	imagecolorallocate($image_pallet, 255, 255, 255 );
	imagefill($image_pallet, 0, 0, $image_pallet_bg );
	imagecopyresampled($image_pallet, $image, 0, 0, 0, 0, $new_width, $new_height, $image_width, $image_height );
	if	(!imagejpeg($image_pallet, $file_path_jpeg, 90 ) ) {
		imagedestroy($image_pallet );
		imagedestroy($image );
		touch($file_path_webp );
		return	null;
	}
	imagedestroy($image_pallet );
	imagedestroy($image );
	if	($stamp === true ) {
		$file_url_jpeg	.=	'?'.date('yyyymmdd-his', filemtime($file_path_jpeg ) );
	}
	return	$file_url_jpeg;
