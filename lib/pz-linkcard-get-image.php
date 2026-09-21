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
	$use_webp		=	function_exists('imagewebp' );
	$use_jpeg		=	!$use_webp && function_exists('imagejpeg' );
	$file_extension	=	$use_webp ? '.webp' : '.jpg';
	$file_path		=	$file_dir.$file_name.$file_extension;
	$file_url		=	$file_dir_url.$file_name.$file_extension;
	$is_ico_url		=	(bool) preg_match('/\.ico(?:[?#].*)?$/i', $thumbnail_url );
	$create_null_file	=	function() use ($file_path ) {
		file_put_contents($file_path, '' );
	};

	if	($this->pz_IsLocalAddress($thumbnail_url ) ) {
		$create_null_file();
		return	null;
	}

	if	(!$force ) {
		if	(file_exists($file_path ) ) {
			if	(filesize($file_path ) < 12 ) {
				return	null;
			} else {
				if	($stamp === true ) {
					$file_url	.=	'?'.date('yyyymmdd-his', filemtime($file_path ) );
				}
				return	$file_url;
			}
		}
	}

	// ここから画像取得処理
	global	$wp_version;

	$thumbnail_url	=	$this->pz_EncodeURL($thumbnail_url, true );
	$rget_args							=	array();
	$rget_args['timeout']				=	10;
	$redirect_limit						=	$this->options['flg-redir'] ? 8 : 0;
	$rget_args['redirection']			=	0;
	$rget_args['limit_response_size']	=	defined('MB_IN_BYTES' ) ? MB_IN_BYTES * 5 : 5242880;
	$rget_args['user-agent']			=	$this->options['flg-agent'] ? $this->options['user-agent'] : 'WordPress/'.$wp_version.'; '.get_bloginfo('url' );
	$rget_args['sslverify']				=	$this->options['flg-sslverify'] ? true : false;

	$get_response_url	=	function($response ) {
		if	(is_wp_error($response ) || !isset($response['http_response'] ) || !is_object($response['http_response'] ) || !method_exists($response['http_response'], 'get_response_object' ) ) {
			return	null;
		}
		$response_object	=	$response['http_response']->get_response_object();
		if	(isset($response_object->url ) && $response_object->url ) {
			return	$response_object->url;
		}
		return	null;
	};
	$get_location_url	=	function($response, $base_url ) {
		if	(is_wp_error($response ) ) {
			return	null;
		}
		$location	=	wp_remote_retrieve_header($response, 'location' );
		if	(is_array($location ) ) {
			$location	=	end($location );
		}
		if	(!$location ) {
			return	null;
		}
		$location	=	trim($location );
		if	(!preg_match('/^https?:\/\//i', $location ) ) {
			$location	=	$this->pz_RelToURL($base_url, $location );
		}
		return	$this->pz_EncodeURL($location, true );
	};
	$trace_redirect_url	=	function($start_url ) use ($rget_args, $get_response_url, $get_location_url, $redirect_limit ) {
		$current_url	=	$start_url;
		$trace_args		=	$rget_args;
		$trace_args['redirection']	=	0;

		for	($i = 0; $i < $redirect_limit; $i++ ) {
			if	($this->pz_IsLocalAddress($current_url ) ) {
				return	$current_url;
			}
			$head_args				=	$trace_args;
			$head_args['method']	=	'HEAD';
			$response				=	wp_safe_remote_head($current_url, $head_args );

			if	(is_wp_error($response ) ) {
				$get_args							=	$trace_args;
				$get_args['limit_response_size']	=	1;
				$response							=	wp_safe_remote_get($current_url, $get_args );
			}

			$response_url	=	$get_response_url($response );
			if	($response_url && $response_url !== $current_url ) {
				$current_url	=	$this->pz_EncodeURL($response_url, true );
				if	($this->pz_IsLocalAddress($current_url ) ) {
					return	$current_url;
				}
				continue;
			}

			$http_code		=	intval(wp_remote_retrieve_response_code($response ) );
			$location_url	=	$get_location_url($response, $current_url );
			if	($http_code >= 300 && $http_code < 400 && $location_url && $location_url !== $current_url ) {
				$current_url	=	$location_url;
				if	($this->pz_IsLocalAddress($current_url ) ) {
					return	$current_url;
				}
				continue;
			}

			break;
		}
		return	$current_url;
	};
	if	($redirect_limit > 0 ) {
		$last_url	=	$trace_redirect_url($thumbnail_url );
		if	($last_url ) {
			$thumbnail_url	=	$this->pz_EncodeURL($last_url, true );
		}
	}
	if	($this->pz_IsLocalAddress($thumbnail_url ) ) {
		$create_null_file();
		return	null;
	}

	$rget_data	=	wp_safe_remote_get($thumbnail_url, $rget_args );
	if	(is_wp_error($rget_data ) ) {
		$create_null_file();
		return	null;
	}

	$http_code	=	intval(wp_remote_retrieve_response_code($rget_data ) );
	$body		=	wp_remote_retrieve_body($rget_data );
	if	($http_code >= 400 || !$body ) {
		$create_null_file();
		return	null;
	}

	$ico_image_body	=	function($ico_body ) {
		if	(strlen($ico_body ) < 22 || strncmp($ico_body, "\x00\x00\x01\x00", 4 ) !== 0 ) {
			return	null;
		}

		$read_u16	=	function($data, $offset ) {
			$value	=	unpack('v', substr($data, $offset, 2 ) );
			return	$value ? intval($value[1] ) : 0;
		};
		$read_u32	=	function($data, $offset ) {
			$value	=	unpack('V', substr($data, $offset, 4 ) );
			return	$value ? intval($value[1] ) : 0;
		};

		$count		=	$read_u16($ico_body, 4 );
		$best		=	null;
		$best_score	=	-1;
		for	($i = 0; $i < $count; $i++ ) {
			$entry_offset	=	6 + ($i * 16 );
			if	(strlen($ico_body ) < $entry_offset + 16 ) {
				break;
			}
			$width			=	ord($ico_body[$entry_offset] ) ?: 256;
			$height			=	ord($ico_body[$entry_offset + 1] ) ?: 256;
			$bit_count		=	$read_u16($ico_body, $entry_offset + 6 );
			$image_size		=	$read_u32($ico_body, $entry_offset + 8 );
			$image_offset	=	$read_u32($ico_body, $entry_offset + 12 );
			if	($image_size <= 0 || $image_offset <= 0 || strlen($ico_body ) < $image_offset + $image_size ) {
				continue;
			}
			$score	=	($width * $height * max(1, $bit_count ) );
			if	($score > $best_score ) {
				$best_score	=	$score;
				$best		=	substr($ico_body, $image_offset, $image_size );
			}
		}
		if	($best === null ) {
			return	null;
		}

		if	(strncmp($best, "\x89PNG\x0d\x0a\x1a\x0a", 8 ) === 0 || strncmp($best, "\xff\xd8", 2 ) === 0 ) {
			return	$best;
		}
		if	(strlen($best ) < 40 ) {
			return	null;
		}

		$dib_header_size	=	$read_u32($best, 0 );
		if	($dib_header_size < 40 || strlen($best ) < $dib_header_size ) {
			return	null;
		}

		$width			=	$read_u32($best, 4 );
		$height_all		=	$read_u32($best, 8 );
		$bit_count		=	$read_u16($best, 14 );
		$color_count	=	$read_u32($best, 32 );
		$height			=	max(1, intval($height_all / 2 ) );
		$palette_count	=	($color_count > 0 ) ? $color_count : (($bit_count > 0 && $bit_count <= 8 ) ? (1 << $bit_count ) : 0);
		$pixel_offset	=	$dib_header_size + ($palette_count * 4 );
		if	($width <= 0 || $height <= 0 || $bit_count <= 0 || strlen($best ) <= $pixel_offset ) {
			return	null;
		}

		$stride			=	intval(floor((($width * $bit_count ) + 31 ) / 32 ) * 4 );
		$pixel_size		=	$stride * $height;
		if	($pixel_size <= 0 || strlen($best ) < $pixel_offset + $pixel_size ) {
			return	null;
		}

		$dib_header		=	substr($best, 0, $dib_header_size );
		$dib_header		=	substr_replace($dib_header, pack('V', $height ), 8, 4 );
		$bitmap_data	=	$dib_header.substr($best, $dib_header_size, ($palette_count * 4 ) + $pixel_size );
		$file_offset	=	14 + $pixel_offset;
		$file_size		=	14 + strlen($bitmap_data );
		return	'BM'.pack('VvvV', $file_size, 0, 0, $file_offset ).$bitmap_data;
	};

	$content_type	=	wp_remote_retrieve_header($rget_data, 'content-type' );
	$content_type	=	is_array($content_type ) ? reset($content_type ) : $content_type;
	$content_type	=	strtolower((string) $content_type );
	$is_ico			=	$is_ico_url || preg_match('/(?:^|[; ])image\/(?:x-icon|vnd\.microsoft\.icon|ico)(?:[; ]|$)/i', $content_type ) || strncmp($body, "\x00\x00\x01\x00", 4 ) === 0;
	if	($is_ico ) {
		$ico_body	=	$ico_image_body($body );
		if	($ico_body !== null ) {
			$body	=	$ico_body;
		} else {
			$create_null_file();
			return	null;
		}
	}

	if	(!function_exists('imagecreatefromstring' ) || !function_exists('imagecreatetruecolor' ) || !function_exists('imagecopyresampled' ) || (!$use_webp && !$use_jpeg ) ) {
		$create_null_file();
		return	null;
	}

	$image	=	@imagecreatefromstring($body );
	if	($image === false ) {
		$create_null_file();
		return	null;
	}

	$image_width	=	@imagesx($image );
	$image_height	=	@imagesy($image );
	if	($image_width === false || $image_height === false || $image_width <= 0 || $image_height <= 0 ) {
		imagedestroy($image );
		$create_null_file();
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
		$create_null_file();
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
		$create_null_file();
		return	null;
	}

	imagealphablending($image_pallet, !$use_webp );
	imagesavealpha($image_pallet, $use_webp );
	$image_pallet_bg	=	$use_webp
		?	imagecolorallocatealpha($image_pallet, 0, 0, 0, 127 )
		:	imagecolorallocate($image_pallet, 255, 255, 255 );
	imagefill($image_pallet, 0, 0, $image_pallet_bg );
	imagecopyresampled($image_pallet, $image, 0, 0, 0, 0, $new_width, $new_height, $image_width, $image_height );
	$save_result	=	$use_webp
		?	imagewebp($image_pallet, $file_path )
		:	imagejpeg($image_pallet, $file_path, 85 );
	if	(!$save_result ) {
		imagedestroy($image_pallet );
		imagedestroy($image );
		$create_null_file();
		return	null;
	}
	imagedestroy($image_pallet );
	imagedestroy($image );
	if	(!file_exists($file_path ) || filesize($file_path ) < 12 ) {
		$create_null_file();
		return	null;
	}
	if	($stamp === true ) {
		$file_url	.=	'?'.date('yyyymmdd-his', filemtime($file_path ) );
	}
	return	$file_url;
