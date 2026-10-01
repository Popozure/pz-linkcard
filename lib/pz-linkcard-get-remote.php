<?php defined('ABSPATH' ) || wp_die;

    // リンク先URL
    $url			=	isset($data['url']) ? $data['url'] : '' ;

    // URL指定なし
    if	(!$url ) {
        return	null;
    }

    // wp_safe_remote_get/wp_safe_remote_headのオプション
    $rget_args					=	[];
    $rget_args['user-agent']	=	$this->pz_GetUserAgent();
    $rget_args['sslverify']		=	$this->options['flg-sslverify']		?	true	:	false ;
    $redirect_limit				=	$this->options['flg-redir']			?	8		:	0;
    $rget_args['redirection']	=	0;
    $browser_user_agent			=	$this->pz_GetUserAgent('');
    if	(!$browser_user_agent ) {
        $browser_user_agent		=	'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36';
    }
    $is_amazon_url	=	function($check_url ) {
        $check_host	=	strtolower((string) wp_parse_url($check_url, PHP_URL_HOST ) );
        return	preg_match('/(^|\.)amazon\.co\.jp$/i', $check_host ) === 1;
    };
    $is_twitter_url	=	function($check_url ) {
        $check_host	=	strtolower((string) wp_parse_url($check_url, PHP_URL_HOST ) );
        return	preg_match('/(^|\.)x\.com$/i', $check_host ) === 1;
    };
    $get_request_args	=	function($check_url, $request_args ) use ($browser_user_agent, $is_amazon_url, $is_twitter_url ) {
        if	($is_amazon_url($check_url ) || $is_twitter_url($check_url ) ) {
            $request_args['user-agent']	=	$browser_user_agent;
        }
        return	$request_args;
    };

    // URLエンコード
    $url			=	$this->pz_EncodeURL($url ,true );
    $url_redir		=	'';
    $url_access		=	$url;
    $robots_blocked_url	=	'';
    $is_robots_allowed	=	function($check_url ) use ($rget_args, $get_request_args ) {
        if	(empty($this->options['flg-robots'] ) ) {
            return	true;
        }
        $url_info	=	$this->pz_GetURLInfo($check_url );
        if	(empty($url_info['is_external'] ) ) {
            return	true;
        }
        $robots_args	=	$get_request_args($check_url, $rget_args );
        return	$this->pz_IsRobotsAllowed($check_url, $robots_args['user-agent'] );
    };
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
    $trace_redirect_url	=	function($start_url ) use ($rget_args, $get_request_args, $get_response_url, $get_location_url, $redirect_limit, $is_robots_allowed, &$robots_blocked_url ) {
        $current_url	=	$start_url;
        $trace_args		=	$rget_args;
        $trace_args['redirection']	=	0;

        for	($i = 0; $i < $redirect_limit; $i++ ) {
            if	(!$is_robots_allowed($current_url ) ) {
                $robots_blocked_url	=	$current_url;
                return	$current_url;
            }
            if	($this->pz_ShouldBlockLocalAddress($current_url ) ) {
                return	$current_url;
            }
            $head_args				=	$get_request_args($current_url, $trace_args );
            $head_args['method']	=	'HEAD';
            $response				=	wp_safe_remote_head($current_url, $head_args );

            if	(is_wp_error($response ) ) {
                $get_args							=	$get_request_args($current_url, $trace_args );
                $get_args['limit_response_size']	=	1;
                $response							=	wp_safe_remote_get($current_url, $get_args );
            }

            $response_url	=	$get_response_url($response );
            if	($response_url && $response_url !== $current_url ) {
                $current_url	=	$this->pz_EncodeURL($response_url, true );
                if	($this->pz_ShouldBlockLocalAddress($current_url ) ) {
                    return	$current_url;
                }
                continue;
            }

            $http_code		=	intval(wp_remote_retrieve_response_code($response ) );
            $location_url	=	$get_location_url($response, $current_url );
            if	($http_code >= 300 && $http_code < 400 && $location_url && $location_url !== $current_url ) {
                $current_url	=	$location_url;
                if	($this->pz_ShouldBlockLocalAddress($current_url ) ) {
                    return	$current_url;
                }
                continue;
            }

            break;
        }

        return	$current_url;
    };

    // リンク先サイトのアクセス
    if	($redirect_limit > 0 ) {
        $last_url	=	$trace_redirect_url($url );
        if	($last_url && $url !== $last_url ) {
            $url_redir	=	$this->pz_EncodeURL($last_url, true );
            $url_access	=	$url_redir;
        }
    }
    if	(!$robots_blocked_url && !$is_robots_allowed($url_access ) ) {
        $robots_blocked_url	=	$url_access;
    }
    $is_amazon_access	=	$is_amazon_url($url_access );
    $is_twitter_access	=	$is_twitter_url($url_access );
    $blocked_local_url	=	$this->pz_ShouldBlockLocalAddress($url ) || ($url_redir && $this->pz_ShouldBlockLocalAddress($url_redir ) );
    if	($blocked_local_url ) {
        $rget_data	=	new WP_Error('pz_lkc_local_redirect', 'Local address blocked' );
    } elseif ($robots_blocked_url ) {
        $rget_data	=	new WP_Error('pz_lkc_robots_disallowed', 'Blocked by robots.txt' );
    } else {
        if	($is_amazon_access || $is_twitter_access ) {
            $rget_args	=	$get_request_args($url_access, $rget_args );
        }
        $rget_data	=	wp_safe_remote_get($url_access, $rget_args );	// wp_remote_get実行
    }

    // 初期化
    $domain			=	'';
    $sitename		=	'';
    $author			=	'';
    $type			=	'';
    $title			=	'';
    $excerpt		=	'';
    $thumbnail_url	=	'';
    $siteicon_url	=	'';
    $charset		=	'';
    $http_code		=	'';
    $error			=	false;
    $amazon_image	=	'';
    $twitter_icon	=	'';

    // URL解析（自サイトチェック）
    $url_info		=	$this->Pz_GetURLInfo($url_access );
    $scheme			=	$url_info['scheme'];		// スキーム
    $domain			=	$url_info['domain'];		// ドメイン名
    $domain_url		=	$url_info['domain_url'];	// ドメインURL

    // ローカルアドレス確認
    if	($blocked_local_url ) {
        $data['id']					=	$data['id'] ?? '';					// リンクカードID
        $data['url']				=	$url;								// リンク先：URL
        $data['url_redir']			=	$url_redir;							// リンク先：リダイレクト先
        $data['scheme']				=	$data['scheme'] ?? '';				// リンク先：URLスキーム
        $data['domain']				=	$data['domain'] ?? '';				// リンク先：URLドメイン
        $data['title']				=	__('Invalid URL', 'pz-linkcard' );	// リンク先：タイトル
        $data['excerpt']			=	'';									// リンク先：抜粋文
        $data['regist_title']		=	$data['title'] ?? '';				// リンク先：タイトル
        $data['regist_excerpt']		=	'';									// リンク先：抜粋文
        $data['sns_nexttime']		=	253402300799;						// SNS：次回取得日時
        $data['alive_nexttime']		=	253402300799;						// 生存確認：次回確認日時
        $data['regist_time']		=	$this->now;							// 登録時：登録日時
        $data['regist_result']		=	403;								// 登録時：HTTPレスポンス
        $data['update_time']		=	$this->now;							// 更新：最終更新日
        $data['update_result']		=	403;								// 更新：HTTPレスポンス

        return	$data;
    }

    $err_no						=	is_wp_error($rget_data );						// wp_remote_getエラー有無

    // エラーチェック
    if ($err_no ) {
        $http_type		=	'';
        $http_body		=	'';
        if	($rget_data->get_error_code() === 'pz_lkc_robots_disallowed' ) {
            $http_code	=	403;
            $title		=	__('Blocked by robots.txt.', 'pz-linkcard' );
        } else {
            $http_code	=	-1;
        }
    } else {
        $http_type		=	$rget_data['headers']['content-type'];		// Content_Type
        $http_body		=	$rget_data['body'];							// HTTPボディー
        $http_code		=	$rget_data['response']['code'];				// HTTPステータス
    }

    // Bodyが取得出来ていたらCharset判定
    if	((strtolower(substr($http_type, 0, 9) ) === 'text/html' || $http_code == '200' ) && $http_body ) {
        $charset		=	mb_detect_encoding($http_body, ['UTF-8', 'eucJP-win', 'SJIS-win', 'ASCII', 'EUC-JP', 'SJIS', 'JIS'], false );
        if	($charset ) {
            $http_body	=	mb_convert_encoding($http_body, $this->charset, $charset );
        }
        if	(preg_match('/charset\s*=\s*[\'"]*([A-Za-z0-9\-_]*)/si', $http_body, $m ) ) {
            $charset	=	strtolower($m[1] );
        }
    }

    // Charset判定出来ていたら
    if	($charset ) {
        // HEADタグ（METAタグ解析）
        $html_head		=	null;
        $tags			=	null;
        if	(preg_match('/<\s*head[^>]*>(.*)<\s*\/head\s*>/si', $http_body, $m ) ) {
            $html_head	=	$m[1];
            $tags		=	$this->pz_GetMeta($html_head );
        } else {
            $tags		=	$this->pz_GetMeta($http_body );
        }

        // Amazon専用処理
        if	($this->options['flg-special-amazon'] ) {
            // Amazonの商品画像
            if	($is_amazon_access && class_exists('DOMDocument' ) ) {
                $previous_libxml_errors	=	libxml_use_internal_errors(true );
                $document	=	new DOMDocument();
                if	($document->loadHTML($http_body, LIBXML_NOERROR | LIBXML_NOWARNING | LIBXML_NONET ) ) {
                    $xpath	=	new DOMXPath($document );
                    $images	=	$xpath->query('(//*[@id="main-image-container"]//img[@src])[1]' );
                    if	($images && $images->length > 0 ) {
                        $amazon_image	=	html_entity_decode(trim($images->item(0 )->getAttribute('src' ) ), ENT_QUOTES | ENT_HTML5, $this->charset );
                    }
                }
                libxml_clear_errors();
                libxml_use_internal_errors($previous_libxml_errors );
            }
        }

        // Twitter（X）専用処理：プロフィールアイコン
        if	($this->options['flg-special-twitter'] ) {
            if	($is_twitter_access ) {
                $decoded_body = html_entity_decode($http_body, ENT_QUOTES | ENT_HTML5, $this->charset );
                foreach (array('profile_image_url_https', 'profile_image_url' ) as $image_key ) {
                    if	(!preg_match('/["\']'.preg_quote($image_key, '/' ).'["\']\s*:\s*["\']([^"\']+)["\']/i', $decoded_body, $matches ) ) {
                        continue;
                    }
                    $image_url = json_decode('"'.$matches[1].'"' );
                    if	(!is_string($image_url ) ) {
                        $image_url = str_replace(array('\\/', '\\u002F' ), '/', $matches[1] );
                    }
                    $image_host = strtolower((string) wp_parse_url($image_url, PHP_URL_HOST ) );
                    $image_path = (string) wp_parse_url($image_url, PHP_URL_PATH );
                    if	($image_host === 'pbs.twimg.com' && strpos($image_path, '/profile_images/' ) === 0 && wp_http_validate_url($image_url ) ) {
                        $twitter_icon = $image_url;
                        break;
                    }
                }
                if	(!$twitter_icon && class_exists('DOMDocument' ) ) {
                    $previous_libxml_errors = libxml_use_internal_errors(true );
                    $document = new DOMDocument();
                    if	($document->loadHTML($http_body, LIBXML_NOERROR | LIBXML_NOWARNING | LIBXML_NONET ) ) {
                        $xpath = new DOMXPath($document );
                        $images = $xpath->query('//img[contains(@src, "pbs.twimg.com/profile_images/")]' );
                        if	($images && $images->length > 0 ) {
                            $twitter_icon = html_entity_decode(trim($images->item(0 )->getAttribute('src' ) ), ENT_QUOTES | ENT_HTML5, $this->charset );
                        }
                    }
                    libxml_clear_errors();
                    libxml_use_internal_errors($previous_libxml_errors );
                }
            }
        }

        // Open Graph Protcol
        $og_url			=	$tags['og:url']					??	'';
        $og_type		=	$tags['og:type']				??	'';
        $og_sitename	=	$tags['og:site_name']			??	'';
        $og_author		=	null;
        $og_title		=	$tags['og:title']				??	'';
        $og_excerpt		=	$tags['og:description']			??	'';
        $og_image		=	$tags['og:image']				??	'';
        $og_siteicon	=	null;

        // Twitter card
        $tw_url			=	null;
        $tw_sitename	=	$tags['twitter:site']			??	'';
        $tw_author		=	$tags['twitter:creator']		??	'';
        $tw_type		=	$tags['twitter:card']			??	'';
        $tw_title		=	$tags['twitter:title']			??	'';
        $tw_excerpt		=	$tags['twitter:description']	??	'';
        $tw_image		=	$tags['twitter:image']			??	'';
        $tw_siteicon	=	null;

        // タイトル＆概要文
        $title			=	$tags['title']			    	??	'';
        $excerpt		=	$tags['description']		    ??	'';
        if				(!$title ) {
            if			($og_title ) {
                $title			=	$og_title;
                $excerpt		=	$og_excerpt;
            } elseif	($tw_title ) {
                $title			=	$tw_title;
                $excerpt		=	$tw_excerpt;
            }
        }
        
        // サムネイル画像
        if				(!$thumbnail_url ) {
            if			($amazon_image ) {
                $thumbnail_url =	$amazon_image;
            } elseif	($twitter_icon ) {
                $thumbnail_url =	$twitter_icon;
            } elseif	($og_image ) {
                $thumbnail_url =	$og_image;
            } elseif	($tw_image ) {
                $thumbnail_url =	$tw_image;
            }
        }
        if			($thumbnail_url	&& !preg_match('/^https*:\/\//i', $thumbnail_url, $m ) ) {
            $thumbnail_url	=	$this->pz_RelToURL($url_access, $thumbnail_url );
        }
        $thumbnail_url		=	$this->pz_EncodeURL($thumbnail_url, true );

        // サイト名
        if				(!$sitename ) {
            if			($og_sitename ) {
                $sitename		=	$og_sitename;
            } elseif	($tw_sitename ) {
                $sitename		=	$tw_sitename;
            }
        }

        // サイトアイコンURL取得
        if			(isset(	$tags['icon'] )				&& $tags['icon'] ) {
            $siteicon_url	=	$tags['icon'];
        } elseif	(isset(	$tags['shortcut icon'] )	&& $tags['shortcut icon'] ) {
            $siteicon_url	=	$tags['shortcut icon'];
        } elseif	(isset(	$tags['apple-touch-icon'] )	&& $tags['apple-touch-icon'] ) {
            $siteicon_url	=	$tags['apple-touch-icon'];
        }
        if			($siteicon_url && !preg_match('/^https*:\/\//i', $siteicon_url, $m ) ) {
            $siteicon_url	=	$this->pz_RelToURL($url, $siteicon_url );
        }
        $siteicon_url		=	$this->pz_EncodeURL($siteicon_url, true );

        // タイトル整形
        $title				=	mb_strimwidth($title, 0, 500, '...' );		// 500文字制限

        // 抜粋文整形
        $excerpt			=	mb_strimwidth($excerpt, 0, 1000, '...' );	// 1000文字制限
    }

    // 呼ばれている記事
    if	(!isset($data['use_post_id1'] ) ) {
        $data['use_post_id1']	=	get_the_ID();
    }

    // リダイレクト先URL
    if	(!$url_redir || $url == $url_redir ) {
        $url_redir	=	null;
    }

    // データセット
    $data['id']					=	$data['id']				??	null;				// リンクカードID
    $data['url']				=	$url;											// リンク先：URL
    $data['url_redir']			=	$url_redir;										// リンク先：リダイレクト先URL
    $data['scheme']				=	$scheme;										// リンク先：URLスキーム
    $data['domain']				=	$domain;										// リンク先：URLドメイン
    $data['site_name']			=	$sitename;										// リンク先：サイト名称
    $data['title']				=	$title;											// リンク先：タイトル
    $data['excerpt']			=	$excerpt;										// リンク先：抜粋文
    $data['thumbnail']			=	$thumbnail_url;									// リンク先：サムネイルURL
    $data['favicon']			=	$siteicon_url;									// リンク先：サイトアイコンURL
    $data['charset']			=	$charset;										// リンク先：文字コード
    $data['alive_time']			=	$this->now;														// 生存確認：確認日時
    $data['alive_nexttime']		=	$this->now + WEEK_IN_SECONDS * 4 + rand(0, DAY_IN_SECONDS );	// 生存確認：次回確認日時
    $data['alive_result']		=	$http_code;														// 生存確認：HTTPレスポンス
    $data['sns_twitter']		=	$data['sns_twitter']	??	-1;					// SNS：Twitter
    $data['sns_facebook']		=	$data['sns_facebook']	??	-1;					// SNS：facebook
    $data['sns_hatena']			=	$data['sns_hatena']		??	-1;					// SNS：はてなブックマーク
    $data['sns_time']			=	$data['sns_time']		??	0;					// SNS：最終取得日時
    $data['sns_nexttime']		=	$data['sns_nexttime']	??	0;					// SNS：次回取得日時
    $data['use_post_id1']		=	$data['use_post_id1']	??	null;				// 呼ばれている記事
    $data['use_post_id2']		=	$data['use_post_id2']	??	null;				// 呼ばれている記事
    $data['use_post_id3']		=	$data['use_post_id3']	??	null;				// 呼ばれている記事
    $data['use_post_id4']		=	$data['use_post_id4']	??	null;				// 呼ばれている記事
    $data['use_post_id5']		=	$data['use_post_id5']	??	null;				// 呼ばれている記事
    $data['use_post_id6']		=	$data['use_post_id6']	??	null;				// 呼ばれている記事
    $data['regist_title']		=	$data['regist_title']	??	$title;				// 登録時：タイトル
    $data['regist_excerpt']		=	$data['regist_excerpt']	??	$excerpt;			// 登録時：抜粋文
    $data['regist_charset']		=	$data['regist_charset']	??	$charset;			// 登録時：文字コード
    $data['regist_time']		=	$data['regist_time']	??	0;					// 登録時：登録日時
    $data['regist_result']		=	$data['regist_result']	??	$http_code;			// 登録時：HTTPレスポンス
    $data['mod_title']			=	false;											// 更新：登録後からタイトル変更有無
    $data['mod_excerpt']		=	false;											// 更新：登録後から抜粋文変更有無
    $data['update_time']		=	$this->now;										// 更新：最終更新日
    $data['update_result']		=	$http_code;										// 更新：HTTPレスポンス
    return	$data;
