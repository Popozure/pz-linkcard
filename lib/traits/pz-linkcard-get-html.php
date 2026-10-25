<?php
defined('ABSPATH' ) || wp_die;

trait Pz_LinkCard_Get_HTML {

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

		// 抜粋文整形（抜粋文非表示の場合、空欄にする）
		if	(!$this->options['display-excerpt'] ) {
			$excerpt	=	'';
		} else {
			$temp		=	$excerpt;											// 抜粋文
			$temp		=	strip_tags($temp );									// HTMLタグ除去
			$temp		=	str_replace(array("\r", "\n"), '', $temp );			// 改行を除去
			$temp		=	preg_replace('/<!--more-->.+/is', '', $temp );		// moreタグ以降削除
			$temp		=	preg_replace('/\[[^]]*\]/', '', $temp );			// ショートコードすべて除去
			$temp	=	mb_strimwidth($temp, 0, 500 , '...' );
			$temp		=	esc_html($temp );									// HTMLエスケープ
			$excerpt	=	$temp;
		}
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
		if	(($this->options['info-position'] || $is_preview ) && empty($atts['pz-pending'] ) ) {
			if	($sw_siteicon == 1 || $sw_siteicon == 13 ) {							// 直接取得
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
			}
		}

		// リンク先URL
		$click_card_id		= absint($data_id );
		$click_post_id		= absint(get_the_ID() );
		$click_token			= $click_card_id ? $this->pz_GetClickToken($click_card_id, $click_post_id ) : '';
		$html_click_data		= ' data-lkc-id="'.esc_attr($data_id ).'" data-post-id="'.esc_attr($click_post_id ).'" data-click-token="'.esc_attr($click_token ).'"';
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
		$html_sns_title		=	null;
		$html_sns_info		=	null;
		if	($this->options['sns-position'] ) {
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
			if	($sns ) {
				if	($this->options['sns-position'] == 1 ) {
					$html_sns_title	=	'<div class="lkc-share">'.$sns.'</div>';
				} else {
					$html_sns_info	=	'<div class="lkc-share">'.$sns.'</div>';
				}
			}
		}

		// サムネイル
		if	($html_thumbnail ) {
			$html_thumbnail	=	'<figure class="lkc-thumbnail">'.$html_thumbnail.'</figure>';
		}

		// 表示用のURL
		$disp_url = '';
		if ($this->options['display-url'] || ($this->options['special-format'] ?? null ) === 'browser' ) {
			$disp_url		=	esc_html($this->pz_DecodeURL($url, true ) );
		}

		// リンク先URL
		$html_url1			=	null;
		$html_url2			=	null;
		switch	($this->options['display-url'] ) {
		case	1:
			$html_url1	=	'<div class="lkc-url" title="'.esc_attr($url ).'">'.$html_a_op.$html_st_op.$disp_url.$html_st_cl.$html_a_cl.'</div>';
			break;
		case	2:
			$html_url2	=	'&nbsp;<div class="lkc-url-info">'.	$html_a_op.$html_st_op.$disp_url.$html_st_cl.$html_a_cl.'</div>';
			break;
		}

		// 投稿日
		$html_date	=	null;
		$display_date_mode	=	$this->options['display-date'] ?: ($is_preview ? 1 : 0);
		if	($is_internal && $display_date_mode ) {
			$html_url1	=	null;
			$html_url2	=	null;
			$post_date_timestamp		=	is_numeric($post_date ) ? intval($post_date ) : strtotime($post_date );
			$post_modified_timestamp	=	is_numeric($post_modified ) ? intval($post_modified ) : strtotime($post_modified );
			switch		($display_date_mode ) {
			case	1:
				$html_date	=	'<div class="lkc-date">'.__('&#x1f552;&#xfe0f;', 'pz-linkcard' ).$this->pz_date(PZLKC_DATE_FORMAT, $post_date_timestamp ).'</div>';
				break;
			case	2:
				if	($post_modified_timestamp > $post_date_timestamp ) {
					$html_date	=	'<div class="lkc-date">'.__('&#x1F501;&#xfe0f;', 'pz-linkcard' ).$this->pz_date(PZLKC_DATE_FORMAT, $post_modified_timestamp ).'</div>';
				} else {
					$html_date	=	'<div class="lkc-date">'.__('&#x1f552;&#xfe0f;', 'pz-linkcard' ).$this->pz_date(PZLKC_DATE_FORMAT, $post_date_timestamp ).'</div>';
				}
				break;
			case	3:
				$html_date	=	'<div class="lkc-date">'.__('&#x1f552;&#xfe0f;', 'pz-linkcard' ).$this->pz_date(PZLKC_DATE_FORMAT, $post_date_timestamp );
				if	($post_modified_timestamp !== $post_date_timestamp ) {
					$html_date	.=	'&ensp;'.__('&#x1F501;&#xfe0f;', 'pz-linkcard' ).$this->pz_date(PZLKC_DATE_FORMAT, $post_modified_timestamp );
				}
				$html_date	.=	'</div>';
				break;
			}
		}

		// 見出し情報
		if (($this->options['special-format'] ?? null) === 'browser' ) {
			$html_url1 = '<div class="lkc-url" title="'.esc_attr($url ).'">'.$html_a_op.$html_st_op.$disp_url.$html_st_cl.$html_a_cl.'</div>';
			$html_url2 = null;
		}
		if (($this->options['special-format'] ?? null) === 'JIN' && ($heading_text === null || $heading_text === '')) {
			$heading_text = $is_internal ? __('You may also like', 'pz-linkcard' ) : __('Referenced', 'pz-linkcard' );
		}
		if	($heading_text || $is_preview ) {
			$html_heading	=	'<div class="lkc-heading"'.($is_preview ? ' data-pz-preview-heading' : '').'>'.$heading_text.'</div>';
		} else {
			$html_heading	=	null;
		}

		// 続きを読むボタン
		if	($more_text || $is_preview ) {
			$html_moretag	=	$html_a_op.'<div class="lkc-more"'.($is_preview ? ' data-pz-preview-more' : '').'>'.$more_text.'</div>'.$html_a_cl;

		} else {
			$html_moretag	=	null;
		}

		// サイト情報
		if	($added_text ) {
			$html_added	=	'<div class="lkc-added">'.$added_text.'</div>';
		} else {
			$html_added	=	null;
		}

		$html_domain	=	'<div class="lkc-domain"'.$title_sitename.'>'.$disp_sitename.'</div>';
		$html_info		=	'<div class="lkc-info"'.($is_preview ? ' data-pz-preview-info' : '').'>'.$html_a_op.$html_siteicon.$html_domain.$html_added.$html_a_cl.$html_sns_info.$html_url2.'</div>';

		// HTMLタグ作成
		switch	($this->options['info-position'] ) {
		case	1:		// 上側
			$html_tag	=	$html_wrap_op.$html_a_op_all.$html_heading.'<div class="lkc-card">'.$html_info.'<div class="lkc-content"'.($is_preview ? ' data-pz-preview-content' : '').'>'.$html_a_op.$html_thumbnail.$html_title.$html_a_cl.$html_sns_title.$html_url1.$html_date.$html_excerpt.$html_moretag.'</div>'.'<div class="clear"></div>'.'</div>'.$html_a_cl_all.$html_wrap_cl;
			break;
		case	2:		// 下側
			$html_tag	=	$html_wrap_op.$html_heading.$html_a_op_all.'<div class="lkc-card">'.'<div class="lkc-content"'.($is_preview ? ' data-pz-preview-content' : '').'>'.$html_a_op.$html_thumbnail.$html_title.$html_a_cl.$html_sns_title.$html_url1.$html_date.$html_excerpt.$html_moretag.'</div>'.$html_info.'<div class="clear">'.'</div>'.'</div>'.$html_a_cl_all.$html_wrap_cl;
			break;
		case	3:		// タイトルの上側
			$html_tag	=	$html_wrap_op.$html_heading.$html_a_op_all.'<div class="lkc-card">'.'<div class="lkc-content"'.($is_preview ? ' data-pz-preview-content' : '').'>'.$html_a_op.$html_thumbnail.$html_title.$html_a_cl.$html_sns_title.$html_url1.$html_date.$html_excerpt.$html_moretag.'</div>'.'<div class="clear">'.'</div>'.'</div>'.$html_a_cl_all.$html_wrap_cl;
			break;
		default:
			$html_tag	=	$html_wrap_op.$html_heading.$html_a_op_all.'<div class="lkc-card">'.($is_preview ? $html_info : '').'<div class="lkc-content"'.($is_preview ? ' data-pz-preview-content' : '').'>'.$html_a_op.$html_thumbnail.$html_title.$html_a_cl.$html_sns_title.$html_url1.$html_date.$html_excerpt.$html_moretag.'</div>'.'<div class="clear">'.'</div>'.'</div>'.$html_a_cl_all.$html_wrap_cl;
		}
		$enclose_tag	=	isset($this->options['enclose-tag'] ) ? strtolower($this->options['enclose-tag'] ) : (!empty($this->options['blockquote'] ) ? 'blockquote' : 'div');
		if (intval($this->options['info-position'] ?? 0 ) === 3 && $html_info && strpos($html_tag, 'lkc-info' ) === false ) {
			$html_tag = str_replace($html_title, $html_info.$html_title, $html_tag);
		}
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
		$cache_key = 'pz_lkc_api_image_'.md5($url );
		$cached = get_transient($cache_key );
		if (is_array($cached ) && isset($cached['available'] ) ) {
			return (bool) $cached['available'];
		}

		$response = wp_safe_remote_get($url, array(
			'timeout' => 10,
			'redirection' => !empty($this->options['flg-redir'] ) ? 8 : 0,
			'limit_response_size' => 1,
			'user-agent' => $this->pz_GetUserAgent(),
			'sslverify' => !empty($this->options['flg-sslverify'] ),
		) );
		$available = !is_wp_error($response ) && 200 === intval(wp_remote_retrieve_response_code($response ) );
		set_transient($cache_key, array('available' => $available ), $available ? HOUR_IN_SECONDS : MINUTE_IN_SECONDS );
		return $available;
	}
}
