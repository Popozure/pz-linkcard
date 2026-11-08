<?php
defined('ABSPATH' ) || wp_die;

trait Pz_LinkCard_Get_HTML {
	private function pz_GetSettingsPreviewCard($prefix, $index = 1) {
		$site_host = strtolower((string) wp_parse_url(home_url('/'), PHP_URL_HOST));
		$external_origin = preg_match('/(^|\.)popozure\.info$/', $site_host) ? 'https://example.com' : 'https://popozure.info';
		$url = $prefix === 'ex' ? $external_origin.'/pz-linkcard-preview'.($index === 2 ? '-2' : '') : home_url('/pz-linkcard-preview'.($index === 2 ? '-2' : '').'/');
		$title = $prefix === 'ex' ? __('External Link Preview', 'pz-linkcard') : __('Internal Link Preview', 'pz-linkcard');
		return $this->pz_GetHTML(array(
			'url' => $url, 'preview-card' => $prefix,
			'preview-data' => array(
				'url' => $url, 'site_name' => $prefix === 'ex' ? __('External Link Preview', 'pz-linkcard') : get_bloginfo('name'),
				'title' => $title, 'excerpt' => $prefix === 'ex' ? __('This is a sample of an external link card.', 'pz-linkcard') : __('This is a sample of an internal link card.', 'pz-linkcard'),
				'thumbnail' => $this->plugin_dir_url.'img/logo_pz-linkcard.png',
				'favicon' => $prefix === 'ex' ? $this->plugin_dir_url.'img/icon_popozure.ico' : (get_site_icon_url(16) ?: $this->plugin_dir_url.'img/siteicon_dummy.png'),
				'sns_twitter' => 1234, 'sns_facebook' => 1234, 'sns_hatena' => 1234, 'sns_qiita_likes' => 1234, 'sns_qiita_stock' => 1234,
				'post_date' => current_datetime()->modify('-1 day')->format('Y-m-d H:i:s'),
				'post_modified' => current_datetime()->format('Y-m-d H:i:s'),
				'update_result' => 200, 'alive_result' => 200, 'no_failure' => true,
			),
		));
	}

	// $attsと収集済みデータからリンクカードのHTMLを生成
	private	function	pz_GetHTML($atts, $url_info = null ) {
		static $html_anchor_ids	=	array();

		// リンク先URL
		$url			=	isset($atts['url'] ) ? $atts['url'] : null ;

		// URL指定なし
		if	(!$url ) {
			return	null;
		}
		$is_preview		=	isset($atts['preview-data'] ) && is_array($atts['preview-data'] );
		$is_prepared	=	isset($atts['pz-card-data'] ) && is_array($atts['pz-card-data'] );
		if (!$is_preview && !$is_prepared ) {
			return null;
		}

		$is_mobile = function_exists('wp_is_mobile' ) && wp_is_mobile();

		// URL解析（自サイトチェック）
		$url_info		=	$url_info ?? $this->Pz_GetURLInfo($url );
		$domain			=	$url_info['domain'];		// ドメイン名
		$domain_url		=	$url_info['domain_url'];	// ドメインURL
		$is_external	=	$url_info['is_external'];	// 外部リンク
		$is_internal	=	$url_info['is_internal'];	// 内部リンク

		// モバイルかPCかのクラス名を追加
		$class_id		=	'linkcard';
		if	($is_mobile && $this->options['class-mobile'] ) {
			$class_id	.=	' '.esc_attr($this->options['class-mobile'] );
		} elseif	($this->options['class-pc'] ) {
			$class_id	.=	' '.esc_attr($this->options['class-pc'] );
		}
		if (!empty($atts['pz-pending'] ) ) {
			$class_id .= ' pz-lkc-pending';
		}

		// キャッシュから取得
		$data			=	array('url' => $url );
		if	($is_preview ) {
			$data		=	array_merge($data, $atts['preview-data'] );
			$data_id	=	'preview';
			$class_id	.=	' pz-preview-linkcard';
		} elseif ($is_prepared ) {
			$data		=	array_merge($data, $atts['pz-card-data'] );
			$data_id	=	$data['id'] ?? '';
			$url		=	$data['url'] ?? $url;
		}

		// Apply the same visibility rule to prepared data and editor previews.
		if ($is_internal && !$this->pz_IsInternalCardPubliclyViewable($url, $data)) {
			return null;
		}

		// 内部リンクの処理
		if	($is_internal ) {
			// リンクターゲットの設定
			$target			=	null;									// 同じタブに開く
			if	(isset($this->options['in-target'] ) ) {
				if	($this->options['in-target'] == 1 || ($this->options['in-target'] == 2 && !$is_mobile ) ) {
					$target	=	' target="_blank"';						// 新しいタブで開く
				}
			}

			// nofollowの指定
			$rel			=	null;
			if	((isset($atts['follow'] ) && mb_strtolower($atts['follow'] ) == 'no' ) || (isset($atts['nofollow'] ) && mb_strtolower($atts['nofollow'] ) == 'true' ) ) {
				$rel		=	' rel="nofollow"';						// 要望により内部リンクでもnofollow可能（ショートコードのパラメータで指定時のみ）
			}

		}

		// 外部リンクの処理
		if	($is_external ) {
			// リンクターゲットの設定
			$target			=	null;								// 同じタブに開く
			if	(isset($this->options['ex-target'] ) ) {
				if	($this->options['ex-target'] == 1 || ($this->options['ex-target'] == 2 && !$is_mobile ) ) {
					$target	=	' target="_blank"';					// 新しいタブで開く
				}
			}

			// noopenerとnofollowの指定
			$rel			=	'external';
			if	($this->options['flg-nofollow'] || (isset($atts['follow'] ) && mb_strtolower($atts['follow'] ) == 'no' ) || (isset($atts['nofollow'] ) && mb_strtolower($atts['nofollow'] ) == 'true' ) ) {
				$rel		.=	' nofollow';						// nofollow指定。趣味の問題？
			}
			if	($this->options['flg-noopener'] ) {
				$rel		.=	' noopener';
			}
			$rel			=	' rel="'.$rel.'"';

		}

		// 記事内容をセット
		$url_redir		=	$data['url_redir']		??	'';
		$title			=	$data['title']			??	'';
		$excerpt		=	$data['excerpt']		??	'';
		$site_name		=	$data['site_name']		??	'';
		$thumbnail_url	=	$data['thumbnail']		??	'';
		$siteicon_url	=	$data['favicon']		??	'';
		$post_date		=	$data['post_date']		??	'';
		$post_modified	=	$data['post_modified']	??	'';
		$alive_result	=	$data['alive_result']	??	'';
		$no_failure		=	!empty($data['no_failure'] )	?	true	:	false ;
		$sns_tw			=	$data['sns_twitter']	??	'';
		$sns_fb			=	$data['sns_facebook']	??	'';
		$sns_hb			=	$data['sns_hatena']		??	'';
		$sns_qiita_likes	=	$data['sns_qiita_likes']	??	'';
		$sns_qiita_stock	=	$data['sns_qiita_stock']	??	'';
		if ($is_preview) {
			$sns_tw = $sns_fb = $sns_hb = 1234;
		}
		$html_thumbnail	=	null;
		$html_siteicon	=	null;

		// リダイレクトURL
		if	($url_redir ) {
			$url		=	$url_redir;
		}

		// ラッピング
		if	($is_internal ) {
			$wrap_class			=	'lkc-internal-wrap';
			$html_wrap_op		=	'<div class="'.$wrap_class.'">';
			$html_wrap_cl		=	'</div>';
			$added_text			=	isset($this->options['in-added-text'] )		?	esc_attr($this->options['in-added-text'] )		:	null ;
			$heading_text		=	isset($this->options['in-heading-text'] )	?	esc_attr($this->options['in-heading-text'] )	:	null ;
			$more_text			=	isset($this->options['in-more-text'] )		?	esc_attr($this->options['in-more-text'] )		:	null ;
			$thumbnail_alt		=	isset($this->options['in-thumbnail-alt'] )	?	esc_attr($this->options['in-thumbnail-alt'] )	:	null ;
			$siteicon_alt		=	isset($this->options['in-siteicon-alt'] )	?	esc_attr($this->options['in-siteicon-alt'] )	:	null ;
			$sw_thumbnail		=	isset($this->options['in-thumbnail'] )		?	esc_attr($this->options['in-thumbnail'] )		:	0 ;
			$sw_siteicon		=	isset($this->options['in-siteicon'] )		?	esc_attr($this->options['in-siteicon'] )		:	0 ;
		} else {
			$wrap_class				=	'lkc-external-wrap';
			$html_pending_loader	=	!empty($atts['pz-pending'] ) ? '<span class="pz-lkc-pending-loader" aria-hidden="true"></span>' : '';
			$html_wrap_op			=	'<div class="'.$wrap_class.'">'.$html_pending_loader;
			$html_wrap_cl			=	'</div>';
			$added_text				=	isset($this->options['ex-added-text'] )		?	esc_attr($this->options['ex-added-text'] )		:	null ;
			$heading_text			=	isset($this->options['ex-heading-text'] )	?	esc_attr($this->options['ex-heading-text'] )	:	null ;
			$more_text				=	isset($this->options['ex-more-text'] )		?	esc_attr($this->options['ex-more-text'] )		:	null ;
			$thumbnail_alt			=	isset($this->options['ex-thumbnail-alt'] )	?	esc_attr($this->options['ex-thumbnail-alt'] )	:	null ;
			$siteicon_alt			=	isset($this->options['ex-siteicon-alt'] )	?	esc_attr($this->options['ex-siteicon-alt'] )	:	null ;
			$sw_thumbnail			=	isset($this->options['ex-thumbnail'] )		?	esc_attr($this->options['ex-thumbnail'] )		:	0 ;
			$sw_siteicon			=	isset($this->options['ex-siteicon'] )		?	esc_attr($this->options['ex-siteicon'] )		:	0 ;
		}

		// ドメイン名の準備
		$domain_name			=	$domain;
		if	(function_exists('idn_to_utf8' ) && substr($domain, 0, 4 ) == 'xn--' ) {	// 国際ドメイン対応（日本語ドメイン対応）
			$domain_name		=	(function_exists('idn_to_utf8' ) && defined('INTL_IDNA_VARIANT_UTS46' ) ) ? idn_to_utf8($domain, 0, INTL_IDNA_VARIANT_UTS46 ) : $domain;
		}

		// 表示用サイト名
		if	(($this->options['flg-use-sitename'] ) && ($site_name ) ) {
			$disp_sitename		=	$site_name;
		} else {
			$disp_sitename		=	$domain_name;
		}
		$disp_sitename			=	esc_html($disp_sitename );

		// 表示用サイト名の文字数
		$title_sitename			=	'';
		$disp_sitename		=	mb_strimwidth($disp_sitename, 0, 100 , '...' );

		// タイトル
		if	(!$title && empty($atts['title'] ) ) {
			$title			=	$this->pz_DecodeURL($url, true );				// タイトル取得できていなかったらURLをセットする
		}

		// パラメータ取得（タイトル）
		if	(isset($atts['title'] ) && $atts['title'] ) {						// title パラメータ
			$title			=	$atts['title'];
			if	(isset($this->options['flg-clear-excerpt'] ) && $this->options['flg-clear-excerpt'] ) {	// 概要文をクリアする
				$excerpt		=	'';
			}
		}

		// パラメータ取得（抜粋文）
		if	(isset($atts['excerpt'] ) && $atts['excerpt'] ) {					// title パラメータ
			$excerpt		=	$atts['excerpt'] ?? '';							// excerpt パラメータ
		}

		// タイトル整形
		$temp			=	$title;												// タイトル
		$temp			=	strip_tags($temp );									// HTMLタグ除去
		$temp			=	str_replace(array("\r", "\n"), '', $temp );			// 改行を除去
		$temp			=	mb_strimwidth($temp, 0, 200 , '...' );
		$title			=	esc_html($temp );
		$html_title		=	'<div class="lkc-title">'.$title.'</div>';

		// 抜粋文整形（表示は記事内容の選択で制御する）
		$temp		=	$excerpt;											// 抜粋文
		$temp		=	strip_tags($temp );									// HTMLタグ除去
		$temp		=	str_replace(array("\r", "\n"), '', $temp );			// 改行を除去
		$temp		=	preg_replace('/<!--more-->.+/is', '', $temp );		// moreタグ以降削除
		$temp		=	preg_replace('/\[[^]]*\]/', '', $temp );			// ショートコードすべて除去
		$temp		=	mb_strimwidth($temp, 0, 500 , '...' );
		$temp		=	esc_html($temp );									// HTMLエスケープ
		$excerpt	=	$temp;
		if	($excerpt ) {
			$html_excerpt		=	'<div class="lkc-excerpt">'.$excerpt.'</div>';
		} else {
			$html_excerpt		=	'';
		}

		// Google AMP用 簡易タグ作成
		if	($this->amp <> 2 ) {
			if	($this->amp === 0 ) {
				$this->amp			=	2;		// 仮に 2:通常（非AMP）とする
				if	((function_exists('ampforwp_is_amp_endpoint' ) && ampforwp_is_amp_endpoint() ) || (function_exists('is_amp_endpoint' ) && is_amp_endpoint() ) || (function_exists('is_amp' ) && is_amp() ) ) {
					$this->amp		=	1;		// 1:AMP
				} else {
					if ($this->options['flg-amp-url'] ) {
						$url_now = $_SERVER["REQUEST_URI"];
						if ((substr($url_now, 4 ) === '/amp' ) || (substr($url_now, 5 ) === '/amp/' ) || (substr($url_now, 6 ) === '?amp=1' ) || (substr($url_now, 8 ) === 'type=AMP' ) ) {
							$this->amp	=	1;		// 1:AMP
						}
					}
				}
			}
			if	($this->amp === 1 ) {
				$html_tag		=	'<div class="lkc-external amp"><table border="1" cellspacing="0" cellpadding="4"><tr><td>'.$excerpt.'<br><a href="'.esc_url($url ).'"'.$target.$rel.'>'.$title.'</a>&nbsp;-&nbsp;'.esc_html($site_name ).'</td></tr></table></div>';
				return	$html_tag;		// タグを出力して終了
			}
		}

		// 代替テキスト（サムネイル）
		if	(($thumbnail_alt	<>	'' )	&&		(strstr($thumbnail_alt, '%' )	<>		'' ) ) {
			$temp				=	$thumbnail_alt;
			$temp				=	preg_replace('/%TITLE%/',		$title,					$temp );
			$temp				=	preg_replace('/%EXCERPT%/',		$excerpt,				$temp );
			$temp				=	preg_replace('/%SITE_NAME%/',	$site_name,				$temp );
			$temp				=	preg_replace('/%DOMAIN_URL%/',	$domain_url,			$temp );
			$temp				=	preg_replace('/%DOMAIN%/',		$domain,				$temp );
			$temp				=	preg_replace('/%URL%/',			rawurlencode($url ),	$temp );
			$thumbnail_alt		=	esc_html($temp );
		}

		// 代替テキスト（サイトアイコン）
		if	(($siteicon_alt		<>	'' )	&&		(strstr($siteicon_alt, '%' )		<>		'' ) )	{
			$temp				=	$siteicon_alt;
			$temp				=	preg_replace('/%DOMAIN_URL%/',	$domain_url,			$temp );
			$temp				=	preg_replace('/%DOMAIN%/',		$domain,				$temp );
			$temp				=	preg_replace('/%URL%/',			rawurlencode($url ),	$temp );
			$siteicon_alt		=	esc_html($temp );
		}

		// サムネイル取得
		if	($this->options['thumbnail-position'] && empty($atts['pz-pending'] ) ) {
			if	($sw_thumbnail == 1 || $sw_thumbnail == 13 ) {						// 直接取得
				if	($is_external && !$is_preview ) {
					$thumbnail_url	=	$this->pz_GetImage($thumbnail_url );		// 外部サイトのサムネイルをキャッシュ
				}
				if	($thumbnail_url ) {
					$html_thumbnail		=	'<img class="lkc-thumbnail-img" src="'.esc_url($thumbnail_url ).'" width="'.esc_attr($this->options['thumbnail-width'] ).'" height="'.esc_attr($this->options['content-height'] ).'" alt="'.esc_attr($thumbnail_alt ).'" />';
				} elseif	($sw_thumbnail == 13 ) {								// 直接取得に失敗
					$sw_thumbnail	=	3;
				}
			}
			if	($sw_thumbnail == 3 ) {												// WebAPIを利用
				// サムネイル取得WebAPI
				if	($this->options['thumbnail-api'] ) {
					$temp					=	$this->options['thumbnail-api'];
					if	(strstr($temp, '%' )	<>	'' ) {
						$temp				=	preg_replace('/%TITLE%/',		$title,					$temp );
						$temp				=	preg_replace('/%SITE_NAME%/',	$site_name,				$temp );
						$temp				=	preg_replace('/%DOMAIN_URL%/',	$domain_url,			$temp );
						$temp				=	preg_replace('/%DOMAIN%/',		$domain,				$temp );
						$temp				=	preg_replace('/%URL%/',			rawurlencode($url ),	$temp );
					}
					if ($this->pz_IsWebAPIImageAvailable($temp ) ) {
						$html_thumbnail	=	'<img class="lkc-thumbnail-img" src="'.esc_url($temp ).'" width="'.esc_attr($this->options['thumbnail-width'] ).'" height="'.esc_attr($this->options['content-height'] ).'" alt="'.esc_attr($thumbnail_alt ).'" />';
					}
				}
			}
		}

		// サイトアイコン取得
		$has_embedded_info = false;
		for ($i = 1; $i <= 5; $i++) {
			if (($this->options[($is_internal ? 'in' : 'ex').'-content-type-'.$i] ?? '') === 'i') $has_embedded_info = true;
		}
		if	(($this->options['info-position'] || $has_embedded_info || $is_preview || ($this->options['special-format'] ?? '') === 'browser') && empty($atts['pz-pending'] ) ) {
			if	($sw_siteicon == 1 || $sw_siteicon == 13 ) {						// 直接取得
				if	($is_internal ) {
					$siteicon_url	=	get_site_icon_url(16 );						// 自サイトのサイトアイコン
				} elseif	(!$is_preview ) {
					$siteicon_url	=	$this->pz_GetImage($siteicon_url );			// 外部サイトのサイトアイコンをキャッシュ
				}
				if	($siteicon_url ) {
					$html_siteicon	=	'<div  class="lkc-siteicon"><img src="'.esc_url($siteicon_url ).'" alt="'.esc_attr($siteicon_alt ).'" width="16" height="16" /></div>';
				} elseif	($sw_siteicon == 13 ) {									// 直接取得に失敗
					$sw_siteicon	=	3;
				}
			}
			if	($sw_siteicon == 3 ) {												// WebAPIを利用
				if	($is_preview && $siteicon_url ) {
					$html_siteicon	=	'<div class="lkc-siteicon"><img src="'.esc_url($siteicon_url ).'" alt="'.esc_attr($siteicon_alt ).'" width="16" height="16" /></div>';
				// サイトアイコン取得WebAPI
				} elseif	($this->options['siteicon-api'] ) {
					$temp					=	$this->options['siteicon-api'];
					if	(strstr($temp, '%' )	<>	'' ) {
						$temp				=	preg_replace('/%TITLE%/',		$title,					$temp );
						$temp				=	preg_replace('/%SITE_NAME%/',	$site_name,				$temp );
						$temp				=	preg_replace('/%DOMAIN_URL%/',	$domain_url,			$temp );
						$temp				=	preg_replace('/%DOMAIN%/',		$domain,				$temp );
						$temp				=	preg_replace('/%URL%/',			rawurlencode($url ),	$temp );
					}
					if ($this->pz_IsWebAPIImageAvailable($temp ) ) {
						$html_siteicon	=	'<div class="lkc-siteicon"><img src="'.esc_url($temp ).'" alt="'.esc_attr($siteicon_alt ).'" width="16" height="16" /></div>';
					}
				}
				if (!$html_siteicon) {
					$html_siteicon = '<div class="lkc-siteicon"><img src="'.esc_url($this->plugin_dir_url.'img/siteicon_dummy.png').'" alt="'.esc_attr($siteicon_alt).'" width="16" height="16" /></div>';
				}
			}
		}

		// リンク先URL
		$click_card_id		= absint($data_id );
		$click_post_id		= absint(get_the_ID() );
		$click_token			= $click_card_id ? $this->pz_GetClickToken($click_card_id, $click_post_id ) : '';
		$html_click_data		= ' data-lkc-id="'.esc_attr($data_id ).'" data-post-id="'.esc_attr($click_post_id ).'" data-click-token="'.esc_attr($click_token ).'"';
		if (!$is_preview && !empty($atts['pz-pending']) && $click_card_id) {
			$expires = time() + DAY_IN_SECONDS;
			$ajax_token = $this->pz_GetAjaxCardToken($click_card_id, $click_post_id, $expires);
			$html_click_data .= ' data-pz-lkc-expires="'.esc_attr($expires).'" data-pz-lkc-token="'.esc_attr($ajax_token).'"';
		}
		if	(!$no_failure && $this->options['flg-unlink'] && in_array(intval($alive_result ), array(403, 404, 410 ), true ) ) {
			// Not Found の時は見え消ししてリンクしない
			$html_wrap_op	=	'<div class="'.$wrap_class.' lkc-unlink">';
			$html_a_op_all	=	null;
			$html_a_cl_all	=	null;
			$html_a_op		=	null;
			$html_a_cl		=	null;
			$html_st_op		=	'<strike>';
			$html_st_cl		=	'</strike>';
		} elseif	($this->options['flg-linkall'] ) {
			// カード全体をリンク（どこをクリックしても良いのが分かり易い）
			$html_a_op_all	=	'<a class="lkc-link no_icon" href="'.esc_url($url ).'"'.$html_click_data.$target.$rel.'>';
			$html_a_cl_all	=	'</a>';
			$html_a_op		=	null;
			$html_a_cl		=	null;
			$html_st_op		=	null;
			$html_st_cl		=	null;
		} else {
			// タイトルとかURLとかを個別でリンク（タイトルや抜粋文などの文字を範囲指定をしてコピー等がし易い）
			$html_a_op_all	=	null;
			$html_a_cl_all	=	null;
			$html_a_op		=	'<a class="lkc-link no_icon" href="'.esc_url($url ).'"'.$html_click_data.$target.$rel.'>';
			$html_a_cl		=	'</a>';
			$html_st_op		=	null;
			$html_st_cl		=	null;
		}

		// ソーシャルカウントの表示
		$sns				=	null;
		$html_sns = null;
		$url_noscheme	=	preg_replace('/^https?:\/\//i', '', $url );	// スキームと「//」を外す
		// カード全体をリンクにするときもSNSボタンをクリック可能にする
		if	($this->options['flg-linkall'] ) {
			if	($this->options['sns-tw'] && $sns_tw > 0 ) {
				if	($this->options['sns-tw-x'] ) {
					$sns	.=	' <object><a class="lkc-sns-tw no_icon" href="'.esc_url('https://twitter.com/search?q='.$url_noscheme.'&text='.$title ).'" target="_blank">'.sprintf(($sns_tw == 1 ? __('%d tweet', 'pz-linkcard' ) : __('%d tweets', 'pz-linkcard' ) ), $sns_tw ).'</a></object>';
				} else {
					$sns	.=	' <object><a class="lkc-sns-x no_icon" href="'.esc_url('https://x.com/search?q='.$url_noscheme.'&text='.$title ).'" target="_blank">'.sprintf(($sns_tw == 1 ? __('%d post',  'pz-linkcard' ) : __('%d posts',  'pz-linkcard' ) ), $sns_tw ).'</a></object>';
				}
			}
			if	($this->options['sns-fb'] && $sns_fb > 0 ) {
				$sns	.=	' <object><a class="lkc-sns-fb no_icon" href="https://www.facebook.com/" target="_blank">'.sprintf(($sns_fb == 1 ? __('%d share',  'pz-linkcard' ) : __('%d shares',  'pz-linkcard' ) ), $sns_fb ).'</a></object>';
			}
			if	($this->options['sns-hb'] && $sns_hb > 0 ) {
				$sns	.=	' <object><a class="lkc-sns-hb no_icon" href="'.esc_url('https://b.hatena.ne.jp/entry/s/'.$url_noscheme ).'" target="_blank">'.sprintf(($sns_hb == 1 ? __('%d user',   'pz-linkcard' ) : __('%d users',   'pz-linkcard' ) ), $sns_hb ).'</a></object>';
			}
		} else {
			// 外部リンクアイコンを表示させるプラグイン対応のため no_icon を付与
			if	($this->options['sns-tw'] && $sns_tw > 0 ) {
				if	($this->options['sns-tw-x'] ) {
					$sns	.=	' <a class="lkc-sns-tw no_icon" href="'.esc_url('https://twitter.com/search?q='.$url_noscheme.'&text='.$title ).'" target="_blank">'.intval($sns_tw ).'&nbsp;tweet'.(($sns_tw > 1 ) ? 's' : null ).'</a>';
				} else {
					$sns	.=	' <a class="lkc-sns-tw no_icon" href="'.esc_url('https://x.com/search?q='.$url_noscheme.'&text='.$title ).'" target="_blank">'.intval($sns_tw ).'&nbsp;post'.(($sns_tw > 1 ) ? 's' : null ).'</a>';
				}
			}
			if	($this->options['sns-fb'] && $sns_fb > 0 ) {
				$sns	.=	' <a class="lkc-sns-fb no_icon" href="https://www.facebook.com/" target="_blank">'.intval($sns_fb ).'&nbsp;share'.(($sns_fb > 1 ) ? 's' : null ).'</a>';
			}
			if	($this->options['sns-hb'] && $sns_hb > 0 ) {
				$sns	.=	' <a class="lkc-sns-hb no_icon" href="'.esc_url('https://b.hatena.ne.jp/entry/s/'.$url_noscheme ).'" target="_blank">'.intval($sns_hb ).'&nbsp;user'.(($sns_hb > 1 ) ? 's' : null ).'</a>';
			}
		}
		if (!empty($this->options['sns-qiita'] )
			&& ($is_preview || (strtolower(wp_parse_url($url, PHP_URL_HOST ) ?? '') === 'qiita.com'
			&& preg_match('~^/[^/]+/items/[0-9a-f]{20}/?$~i', wp_parse_url($url, PHP_URL_PATH ) ?? '' ) ) ) ) {
			if ($sns_qiita_likes > 0 ) {
				$qiita_link = '<a class="lkc-sns-qiita lkc-sns-qiita-likes no_icon" href="'.esc_url($url ).'" target="_blank">'.sprintf(($sns_qiita_likes == 1 ? __('%d like', 'pz-linkcard' ) : __('%d likes', 'pz-linkcard' ) ), intval($sns_qiita_likes ) ).'</a>';
				$sns .= ' '.($this->options['flg-linkall'] ? '<object>'.$qiita_link.'</object>' : $qiita_link);
			}
			if ($sns_qiita_stock > 0 ) {
				$qiita_link = '<a class="lkc-sns-qiita lkc-sns-qiita-stock no_icon" href="'.esc_url($url ).'" target="_blank">'.sprintf(($sns_qiita_stock == 1 ? __('%d stock', 'pz-linkcard' ) : __('%d stocks', 'pz-linkcard' ) ), intval($sns_qiita_stock ) ).'</a>';
				$sns .= ' '.($this->options['flg-linkall'] ? '<object>'.$qiita_link.'</object>' : $qiita_link);
			}
		}
		if ($sns) {
			$html_sns = '<div class="lkc-share">'.$sns.'</div>';
		}

		// サムネイル
		if	($html_thumbnail ) {
			$html_thumbnail	=	'<figure class="lkc-thumbnail">'.$html_thumbnail.'</figure>';
		}

		// 投稿日
		$html_date	=	null;
		$card_date_format = ($this->options['date-format'] ?? 'Y年n月j日') === 'custom'
			? ($this->options['date-format-custom'] ?? 'Y年n月j日') : ($this->options['date-format'] ?? 'Y年n月j日');
		$render_date_icon = static function($icon) {
			if (strpos($icon, 'dashicons-') === 0) {
				return '';
			}
			return esc_html(html_entity_decode($icon, ENT_QUOTES | ENT_HTML5, 'UTF-8'));
		};
		$post_date_icon = $render_date_icon($this->options['date-icon-post'] ?? '&#x1F4C5;&#xFE0F;');
		$post_modified_icon = $render_date_icon($this->options['date-icon-update'] ?? '&#x1F504;&#xFE0F;');
		$date_pattern	=	$this->options['date-pattern'];
		$post_date_timestamp		=	is_numeric($post_date ) ? intval($post_date ) : strtotime($post_date );
		$post_modified_timestamp	=	is_numeric($post_modified ) ? intval($post_modified ) : strtotime($post_modified );
		$has_display_date = $date_pattern === 'p' ? $post_date_timestamp > 0 : ($post_date_timestamp > 0 || $post_modified_timestamp > 0);
		if	($date_pattern && $has_display_date ) {
			switch		($date_pattern ) {
			case	'p':
				$html_date	=	'<div class="lkc-date">'.$post_date_icon.$this->pz_date($card_date_format, $post_date_timestamp ).'</div>';
				break;
			case	'u':
				if	($post_modified_timestamp > $post_date_timestamp ) {
					$html_date	=	'<div class="lkc-date">'.$post_modified_icon.$this->pz_date($card_date_format, $post_modified_timestamp ).'</div>';
				} else {
					$html_date	=	'<div class="lkc-date">'.$post_date_icon.$this->pz_date($card_date_format, $post_date_timestamp ).'</div>';
				}
				break;
			case	'pu':
				$html_date	=	'<div class="lkc-date">';
				if ($post_date_timestamp > 0 ) {
					$html_date .= $post_date_icon.$this->pz_date($card_date_format, $post_date_timestamp );
				}
				if	($post_modified_timestamp > 0 && $post_modified_timestamp !== $post_date_timestamp ) {
					$html_date	.=	($post_date_timestamp > 0 ? '&ensp;' : '').$post_modified_icon.$this->pz_date($card_date_format, $post_modified_timestamp );
				}
				$html_date	.=	'</div>';
				break;
			}
		}

		// 見出し情報
		if (($this->options['special-format'] ?? null) === 'JIN' && ($heading_text === null || $heading_text === '')) {
			$heading_text = $is_internal ? __('You may also like', 'pz-linkcard' ) : __('Referenced', 'pz-linkcard' );
		}
		if	($heading_text || $is_preview ) {
			$html_heading	=	'<div class="lkc-heading"'.($is_preview ? ' data-pz-preview-heading'.(trim((string) $heading_text) === '' ? ' style="display:none !important"' : '') : '').'>'.$heading_text.'</div>';
		} else {
			$html_heading	=	null;
		}

		// 続きを読むボタン
		if	($more_text || $is_preview ) {
			$html_moretag	=	$html_a_op.'<div class="lkc-more"'.($is_preview ? ' data-pz-preview-more'.(trim((string) $more_text) === '' ? ' style="display:none !important"' : '') : '').'>'.$more_text.'</div>'.$html_a_cl;

		} else {
			$html_moretag	=	null;
		}

		// サイト情報
		if	($added_text ) {
			$html_added	=	'<div class="lkc-added">'.$added_text.'</div>';
		} else {
			$html_added	=	null;
		}

		$html_domain = '<div class="lkc-domain"'.$title_sitename.'>'.$disp_sitename.'</div>';
		$html_sitename = '<div class="lkc-domain">'.esc_html($site_name ?: $domain_name).'</div>';
		$html_domain_name = '<div class="lkc-domain">'.esc_html($domain_name).'</div>';
		$html_selected_url = '<div class="lkc-url" title="'.esc_attr($url).'">'.$html_a_op.$html_st_op.esc_html($this->pz_DecodeURL($url, true)).$html_st_cl.$html_a_cl.'</div>';
		$html_selected_info_url = str_replace('class="lkc-url"', 'class="lkc-url-info"', $html_selected_url);
		$prefix = $is_internal ? 'in' : 'ex';

		$defaults = self::pz_GetDefaultOptions();

		$html_date_r = str_replace('class="lkc-date"', 'class="lkc-date lkc-date-right" style="margin-left:auto;float:right"', $html_date ?? '');
		$html_cat_list = '';
		$selected_items = array();
		for ($i = 1; $i <= 5; $i++) {
			foreach (array('info', 'content') as $group) {
				$key = $prefix.'-'.$group.'-type-'.$i;
				$selected_items[] = $this->options[$key] ?? $defaults[$key];
			}
		}
		if (in_array('c', $selected_items, true)) {
			if ($is_preview) {
				$html_cat_list = '<div class="lkc-cat-list"><span class="lkc-cat">'.esc_html__('Category', 'pz-linkcard').'</span></div>';
			} elseif ($is_internal && !empty($data['post_id'])) {
				$is_cocoon = wp_get_theme(get_template())->get('TextDomain') === 'cocoon';
				foreach (get_the_category(absint($data['post_id'])) as $category) {
					$category_url = get_category_link($category->term_id);
					if (is_wp_error($category_url)) continue;
					$category_style = '';
					if ($is_cocoon && function_exists('get_the_category_color')) {
						$category_bg = sanitize_hex_color(get_the_category_color($category->term_id) ?? '');
						if ($category_bg) $category_style .= 'background-color:'.$category_bg.' !important;';
					}
					if ($is_cocoon && function_exists('get_the_category_text_color')) {
						$category_color = sanitize_hex_color(get_the_category_text_color($category->term_id) ?? '');
						if ($category_color) $category_style .= 'color:'.$category_color.' !important;';
					}
					if (!$is_cocoon && function_exists('get_field')) {
						$acf_color = get_field('color', 'category_'.$category->term_id);
						$category_bg = is_string($acf_color) ? sanitize_hex_color($acf_color) : null;
						if ($category_bg) $category_style .= 'background-color:'.$category_bg.' !important;';
					}
					$html_cat_list .= '<object><a class="lkc-cat no_icon" href="'.esc_url($category_url).'"'.($category_style !== '' ? ' style="'.esc_attr($category_style).'"' : '').'>'.esc_html($category->name).'</a></object>';
				}
				if ($html_cat_list) $html_cat_list = '<div class="lkc-cat-list">'.$html_cat_list.'</div>';
			}
		}

		$html_separator = !empty($this->options['separator']) ? '<div class="lkc-separator" aria-hidden="true"></div>' : '';
		$html_info_items = '';
		for ($i = 1; $i <= 5; $i++) {
			$key = $prefix.'-info-type-'.$i;
			switch ($this->options[$key] ?? $defaults[$key]) {
			case 'i': $html_info_items .= $html_a_op.$html_siteicon.$html_a_cl; break;
			case 'n': $html_info_items .= $html_a_op.$html_domain.$html_a_cl; break;
			case 'd': $html_info_items .= $html_a_op.$html_domain_name.$html_a_cl; break;
			case 't': $html_info_items .= $html_a_op.$html_title.$html_a_cl; break;
			case 'a': $html_info_items .= $html_a_op.$html_added.$html_a_cl; break;
			case 'p': $html_info_items .= $html_date; break;
			case 'q': $html_info_items .= $html_date_r; break;
			case 'c': $html_info_items .= $html_cat_list; break;
			case 's': $html_info_items .= $html_sns; break;
			case 'u': $html_info_items .= $html_selected_info_url; break;
			}
		}
		$html_info = '<div class="lkc-info"'.($is_preview ? ' data-pz-preview-info' : '').'>'.$html_info_items.'</div>';
		$html_contents = '';
		for ($i = 1; $i <= 5; $i++) {
			$key = $prefix.'-content-type-'.$i;
			$html_item = '';
			$item_type = $this->options[$key] ?? $defaults[$key];
			switch ($item_type) {
			case 'n': $html_item = $html_a_op.$html_sitename.$html_a_cl; break;
			case 'd': $html_item = $html_a_op.$html_domain_name.$html_a_cl; break;
			case 't': $html_item = $html_a_op.$html_title.$html_a_cl; break;
			case 'u': $html_item = $html_selected_url; break;
			case 'e': $html_item = $html_excerpt; break;
			case 's': $html_item = $html_sns; break;
			case 'p': $html_item = $html_date; break;
			case 'q': $html_item = $html_date_r; break;
			case 'c': $html_item = $html_cat_list; break;
			case 'i': $html_item = str_replace('class="lkc-info"', 'class="lkc-info lkc-info-embedded"', $html_info).$html_separator; break;
			}
			if ($html_item !== null && $html_item !== '') {
				$html_contents .= '<div class="lkc-content-item'.($item_type === 'c' ? ' lkc-content-item-category' : '').'">'.$html_item.'</div>';
			}
		}
		if (($this->options['special-format'] ?? '') === 'browser') {
			$html_contents = str_replace('class="lkc-url"', 'class="lkc-browser-url"', $html_selected_url).$html_contents;
		}
		$html_contents = $html_a_op.$html_thumbnail.$html_a_cl.$html_contents.$html_moretag;


		if (strpos($html_contents, 'lkc-info') !== false) $html_info = '';
		$info_position = intval($this->options['info-position']);
		$html_browser_tab = '';
		if (($this->options['special-format'] ?? '') === 'browser') {
			$html_info = '';
			$tab_icon_url = $siteicon_url;
			if (!$tab_icon_url && $is_internal) $tab_icon_url = get_site_icon_url(16);
			$tab_icon = $html_siteicon
				? str_replace('class="lkc-siteicon"', 'class="lkc-browser-siteicon"', $html_siteicon)
				: '<div class="lkc-browser-siteicon"><img src="'.esc_url($tab_icon_url ?: $this->plugin_dir_url.'img/siteicon_dummy.png').'" alt="'.esc_attr($siteicon_alt).'" width="16" height="16" /></div>';
			$html_browser_tab = '<div class="lkc-browser-tab">'.$tab_icon.'<div class="lkc-browser-site-name">'.esc_html($site_name ?: $domain_name).'</div></div>';
		}
		$html_info_separator = $html_info !== '' && $html_info_items !== '' ? $html_separator : '';
		if ($info_position === 3) $html_contents = $html_info.$html_info_separator.$html_contents;
		$html_content = '<div class="lkc-content"'.($is_preview ? ' data-pz-preview-content' : '').'>'.$html_contents.'</div>';
		if ($info_position === 1) {
			$html_tag = $html_wrap_op.$html_a_op_all.$html_heading.'<div class="lkc-card">'.$html_browser_tab.$html_info.$html_info_separator.$html_content.'<div class="clear"></div></div>'.$html_a_cl_all.$html_wrap_cl;
		} else {
			$html_top_info = $is_preview && $info_position === 0 ? $html_info : '';
			$html_bottom_info = $info_position === 2 ? $html_info_separator.$html_info : '';
			$html_tag = $html_wrap_op.$html_heading.$html_a_op_all.'<div class="lkc-card">'.$html_browser_tab.$html_top_info.$html_content.$html_bottom_info.'<div class="clear"></div></div>'.$html_a_cl_all.$html_wrap_cl;
		}
		$enclose_tag	=	isset($this->options['enclose-tag'] ) ? strtolower($this->options['enclose-tag'] ) : (!empty($this->options['blockquote'] ) ? 'blockquote' : 'div');
		if	(!in_array($enclose_tag, array('div', 'blockquote', 'figure', 'article', 'section', 'nav', 'aside' ), true ) ) {
			$enclose_tag	=	'div';
		}
		$enclose_tag	=	tag_escape($enclose_tag ) ?: 'div';
		$html_quickmenu_icon	=	'';
		$html_data_id			=	'';
		if	(!$is_preview && !empty($data_id ) ) {
			$html_data_id		=	$html_click_data;
			if	(empty($html_anchor_ids[$data_id] ) ) {
				$html_data_id		=	' id="pz-lkc-'.esc_attr($data_id ).'"'.$html_data_id;
				$html_anchor_ids[$data_id]	=	true;
			}
			if	(!empty($this->options['flg-quickmenu'] ) && is_user_logged_in() && current_user_can('manage_options' ) ) {
				$html_quickmenu_icon	=	'<span class="pz-lkc-quickmenu-indicator dashicons dashicons-ellipsis" aria-hidden="true"></span>';
			}
		}
		$html_tag		=	'<'.$enclose_tag.' class="'.esc_attr($class_id ).'"'.$html_data_id.(!empty($atts['pz-pending'] ) ? ' data-pz-lkc-pending="1"' : '').($is_preview && !empty($atts['preview-card'] ) ? ' data-pz-preview-card="'.esc_attr($atts['preview-card'] ).'"' : '').'>'.$html_tag.$html_quickmenu_icon.'</'.$enclose_tag.'>';

		return	$html_tag;
	}

	private function pz_IsWebAPIImageAvailable($url ) {
		if ($this->pz_ShouldBlockLocalAddress($url)) return false;
		$cache_key = 'pz_lkc_api_image_v2_'.md5($url );
		$cached = get_transient($cache_key );
		if (is_array($cached ) && isset($cached['available'] ) ) {
			foreach (($cached['checked_urls'] ?? array($url)) as $checked_url) {
				if ($this->pz_ShouldBlockLocalAddress($checked_url)) return false;
			}
			return (bool) $cached['available'];
		}

		$response = $this->pz_CheckedRemoteGet($url, array(
			'timeout' => 10,
			'redirection' => !empty($this->options['flg-redir'] ) ? 8 : 0,
			'limit_response_size' => 1,
			'user-agent' => $this->pz_GetUserAgent(),
			'sslverify' => !empty($this->options['flg-sslverify'] ),
		) );
		$available = !is_wp_error($response ) && 200 === intval(wp_remote_retrieve_response_code($response ) );
		$checked_urls = is_wp_error($response) ? array($url) : $response['pz_lkc_checked_urls'];
		set_transient($cache_key, array('available' => $available, 'checked_urls' => $checked_urls), $available ? HOUR_IN_SECONDS : MINUTE_IN_SECONDS );
		return $available;
	}
}
