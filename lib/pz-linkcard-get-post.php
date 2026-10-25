<?php defined('ABSPATH' ) || wp_die;

	// 初期化
	$url			=	'';
	$link_post_id	=	0;
	$site_name		=	'';
	$domain_url		=	'';
	$domain			=	'';
	$title			=	'';
	$excerpt		=	'';
	$thumbnail		=	'';
	$siteicon		=	'';
	$post_date		=	0;
	$post_modified	=	0;

	// サイト名取得
	$site_name		=	get_bloginfo('name' );

	// ドメイン名
	$domain			=	$this->domain;
	$domain_url		=	$this->domain_url;

	// サイトアイコン
	if	(function_exists('has_site_icon' ) && has_site_icon() ) {
		$siteicon			=	get_site_icon_url(16, null, 0 );
	}

	// 記事内容
	$url					=	$data['url'];
	$link_post_id			=	url_to_postid($url );					// リンク先の記事IDを取得
	// 記事IDが取得できた場合
	if	($link_post_id ) {
		$post				=	get_post($link_post_id );				// 記事情報
		if	(!$post || ((!$allow_non_public || !empty($this->options['flg-public-only'] ) ) && !$this->pz_IsPostPubliclyViewable($post ) ) ) {
			$data['title']			=	'';
			$data['excerpt']		=	'';
			$data['thumbnail']		=	'';
			$data['favicon']		=	'';
			$data['post_id']		=	0;
			$data['post_date']		=	0;
			$data['post_modified']	=	0;
			$data['update_result']	=	403;
			$data['alive_result']	=	403;
			return	$data;
		}
		// 記事IDが取得できた場合
		$update_result		=	200;									// 外部取得と同じコードをセット
		$title				=	$post->post_title;						// 記事タイトル
		$excerpt			=	$post->post_content;					// 記事内容から抜粋

		// 「抜粋」優先
		if	($this->options['in-get-from'] == 1 && $post->post_excerpt ) {	// 記事取得方法：「抜粋文」があった場合、優先する
			$excerpt		=	$post->post_excerpt;					// 抜粋文
		}

		// 「カスタムフィールド」優先
		if	($this->options['in-get-from'] == 3 ) {						// 記事取得方法：「カスタムフィールド」があった場合、優先する
			$meta_title		=	get_post_meta($link_post_id, $this->options['in-field-title'] );
			if	(array($meta_title ) && array_key_exists(0, $meta_title ) ) {
				$title		=	$meta_title[0];
			}
			$meta_excerpt	=	get_post_meta($link_post_id, $this->options['in-field-excerpt'] );
			if	(array($meta_excerpt ) && array_key_exists(0, $meta_excerpt ) ) {
				$excerpt	=	$meta_excerpt[0];
			}
		}

		$post_date			=	get_post_timestamp($post, 'date' );		// 投稿日
		$post_modified		=	get_post_timestamp($post, 'modified' );	// 更新日
		$thumbnail_id		=	get_post_thumbnail_id($link_post_id );	// サムネイル
		if	($thumbnail_id ) {
			$thumbnail_size		=	$this->options['in-thumbnail-size'] ? $this->options['in-thumbnail-size'] : 'thumbnail' ;
			$attach				=	wp_get_attachment_image_src($thumbnail_id, $thumbnail_size, true );
			if	(isset($attach ) && count($attach ) > 3 && isset($attach[0] ) ) {
				$thumbnail		=	$attach[0];
				if	(preg_match('/.*(\/\/.*)/', $thumbnail, $m ) ) {	// スキームを外す
					$thumbnail	=	$m[1];
				}
			}
		}
	} else {
		// 記事IDが取得できなかった場合
		$update_result		=	404;
		$title				=	get_bloginfo('name' );
		$excerpt			=	get_bloginfo('description' );
		$post_date			=	0;
		$post_modified		=	0;
		$thumbnail			=	null;

		if	(rtrim($this->pz_DecodeURL($url ), '/' ) == rtrim($this->pz_DecodeURL(home_url() ), '/' ) ) {
			// トップページの場合
			$update_result		=	200;
		} else {
			// カテゴリ ページのディレクトリ名を取得
			$default_cat_id		=	get_option('default_category' );
			$default_cat_link	=	rtrim(get_category_link($default_cat_id ), '/' );
			$default_cat_slug	=	rtrim(get_category( $default_cat_id )->slug, '/' );

			// カテゴリーページかどうかチェック
			$url_decoded		=	$this->pz_DecodeURL($url ,false );
			$cat_base_url		=	rtrim(mb_substr($default_cat_link, 0, mb_strlen($default_cat_link ) - mb_strlen($default_cat_slug ) ), '/' );	// ベース部分だけ抽出
			if (mb_substr($url, 0, mb_strlen($cat_base_url ) ).'/' == $cat_base_url.'/' ) {
				$cat_slug		=	mb_substr($url_decoded, mb_strlen($cat_base_url ) + 1 );
				$cat_last_slug	=	basename($cat_slug );
				$cat_data		=	get_category_by_slug($cat_last_slug );
				if	($cat_data ) {
					$cat_count		=	($cat_data->count - 0 );
					$title			=	__('Category', 'pz-linkcard' ).' '.__('‘', 'pz-linkcard' ).$cat_data->name.__('’', 'pz-linkcard' );
					$excerpt		=	__('(', 'pz-linkcard' ).__('Count', 'pz-linkcard' ).':'.($cat_data->count - 0 ).__(')', 'pz-linkcard' ).' '.$cat_data->description;
					$update_result	=	200;
				} else {
					$title			=	__('Category', 'pz-linkcard' ).' '.__('‘', 'pz-linkcard' ).$cat_slug.__('’', 'pz-linkcard' );
					$excerpt		=	__('Not Found', 'pz-linkcard' );
					$update_result	=	403;
				}
			} else {
				// タグ ページの処理
				$cat_dir			=	get_option('tag_base' );
				$cat_url			=	$this->domain_url.'/'.($cat_dir ? $cat_dir : 'tag' ).'/';
				$cat_len			=	mb_strlen($cat_url );
				if	(mb_substr($url, 0, $cat_len ) == $cat_url ) {
					$cat_slug		=	mb_substr($url, $cat_len );
					$cat_data		=	get_tags(array('slug' => $cat_slug ) );
					if	($cat_data ) {
						$title			=	__('Tag', 'pz-linkcard' ).' '.__('‘', 'pz-linkcard' ).$cat_data[0]->name.__('’', 'pz-linkcard' );
						$excerpt		=	__('(', 'pz-linkcard' ).__('Count', 'pz-linkcard' ).':'.($cat_data[0]->count - 0 ).__(')', 'pz-linkcard' ).' '.$cat_data[0]->description;
						$update_result	=	200;
					} else {
						$title			=	__('Tag', 'pz-linkcard' ).' '.__('‘', 'pz-linkcard' ).rawurldecode($cat_slug ).__('’', 'pz-linkcard' );
						$excerpt		=	__('Not Found', 'pz-linkcard' );
						$update_result	=	403;
					}
				} else {
					if	($this->options['in-get-url'] ) {
						$result			=	$this->pz_GetRemote($data );		// 外部サイトとして読み込み
						if	(isset($result ) && is_array($result ) && isset($result['url'] ) ) {
							$data		=	$result;
							$result		=	$this->pz_SetCache($data );
						}
						return			$result;
					}
				}
			}
		}
	}

	// タイトル整形
	if				($str	=	$title ) {										// 代入しながら判定
		if			($str	=	strip_tags($str ) ) {							// HTMLタグ除去
			if		($str	=	esc_html($str ) ) {								// HTMLエスケープ
				if	($str	=	str_replace(array("\r", "\n"), '', $str ) ) {	// 改行を除去
					$str	=	mb_strimwidth($str, 0, 200, '...' );			// 200文字制限
				}
			}
		}
		$title			=	$str;
	}

	// 抜粋文整形
	if						($str	=	$excerpt ) {										// 代入しながら判定
		if					($str	=	strip_tags($str ) ) {								// HTMLタグ除去
			if				($str	=	esc_html($str ) ) {									// HTMLエスケープ
				if			($str	=	str_replace(array("\r", "\n"), '', $str ) ) {		// 改行を除去
					if		($str	=	preg_replace('/<!--more-->.+/is', '', $str ) ) {	// moreタグ以降削除
						if	($str	=	preg_replace('/\[[^]]*\]/', '', $str ) ) {			// ショートコードすべて除去
							$str	=	mb_strimwidth($str, 0, 500, '...' );				// 500文字制限
						}
					}
				}
			}
		}
		$excerpt		=	$str;
	}

	// データセット
	$url_info					=	$this->Pz_GetURLInfo($url );	// URL解析（自サイトチェック）
	$data['scheme']				=	$url_info['scheme'] ?? '';		// スキーム
	$data['domain']				=	$url_info['domain'] ?? '';		// ドメイン名
	$data['site_name']			=	$site_name ?? '';
	$data['title']				=	$title ?? '';
	$data['excerpt']			=	$excerpt ?? '';
	$data['thumbnail']			=	$thumbnail ?? '';
	$data['favicon']			=	$siteicon ?? '';
	$data['charset']			=	'UTF-8';
	$data['update_result']		=	$update_result ?? null;
	$data['alive_result']		=	$update_result ?? null;
	$post_id_t	=	intval(get_the_ID() );
	if	(!isset($data['use_post_id1'] ) && $post_id_t > 0 ) {
		$data['use_post_id1']	=	$post_id_t;
	}
	$data['post_id']			=	$link_post_id;
	$data['post_date']			=	$post_date ?? '';
	$data['post_modified']		=	$post_modified ?? '';
	return	$data;
