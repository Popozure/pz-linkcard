=== Pz-LinkCard ===
Contributors: Poporon
Tags: LinkCard, BlogCard, Internal Link, External Link
Requires at least: 6.0
Tested up to: 7.1.3
Requires PHP: 8.0
Stable tag: 2.6.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Donate link: https://www.amazon.co.jp/gp/registry/wishlist/2KIBQLC1VLA9X

This plugin is intended to display a link in a blog card format. The goodbye to the text-only link.

== Description ==

This plugin is intended to display a link in a blog card format.

Easy to use. Just to write a short code.

You can change the appearance in the settings screen.

You can edit or delete the cache on the manage screen.

The goodbye to the text-only link.

* It will access to WebAPI for the thumbnail image acquisition and site icon of acquisition. In addition , it will save the title and excerpt statement to the database. For more information you want to read the item of arbitrary section about this.


このプラグインはショートコードでURLを指定する事で、リンクをブログカード形式で表示させるものです。

外部リンクと内部リンクで、カードの色や新しくウィンドウを開くか等、設定を変更する事ができます。

リンク先の情報はキャッシュされるため、ソーシャルカウント等も表示されるカード形式のリンクとしては高速に表示されます。

カード管理画面から、キャッシュされた情報の再取得や編集、削除等が行えます。

テキストにリンク設定しただけでは物足りないと感じていたら、ぜひお試しください。

※このプラグインはサムネイル取得やサイトアイコン取得のためにリンク先URLをWebAPIに送信します。
※タイトルや抜粋文等をDBへ保存します。詳細は「Arbitrary section」をお読みください。


== Installation ==

Install from WordPress admin panel

1. From the WordPress admin panel, click "Plugins" -> "Add new".
2. In the browser input box, type "Pz-LinkCard".
3. Select the "Pz-LinkCard" plugin and click "Install".
4. Activate the plugin.


Install from .zip file

1. Download the plugin from this page.
2. Save the .zip file to a location on your computer.
3. Open the WordPress admin panel, and click "Plugins" -> "Add new".
4. Click "upload" then browse to the .zip file downloaded from this page.
5. Click "Install" and then "Activate plugin".


WordPressダッシュボードからのインストール

1. WordPress管理画面から「プラグイン」→「新規追加」とクリックしていきます。
2. 検索ボックスに「Pz-LinkCard」を入力して検索します。
3. プラグイン名「Pz-LinkCard」と作者「poporon」を確認して「今すぐインストール」をクリックします。
4. 「有効化する」をクリックして有効化します。


WordPress.org からのダウンロードおよびインストール

1. WordPress.orgのプラグイン一覧から「Pz-LinkCard」を検索します。
2. プラグイン名「Pz-LinkCard」と作者「poporon」を確認してダウンロードします。
3. WordPress管理画面から「プラグイン」→「新規追加」とクリックしていきます。
4. 「プラグインのアップロード」をクリックしてダウンロードしてあるZIPファイルを選択します。
5. プラグイン一覧から「有効化」をクリックして有効化します。


新しいバージョンを有効化したら動作しなくなった場合

1. FTPソフトやサーバーのコントロールパネルからWordPressのインストールされているディレクトリを確認します。
2. 「/wp-content/plugins/pz-linkcard」のディレクトリ名を変更します。（例．pz-linkcard-disable ）
2. WordPressダッシュボードに入ると、プラグインが無効化されたというメッセージが表示されます。
3. 先ほど変更したディレクトリ名を元に戻します（戻してもプラグインは勝手に有効化されません）
4. プラグインのページから古い安定版をダウンロードします
5. 上書きコピーを行ったあと、有効化を選んで、有効化します
6. 不具合が起きた状態や状況、テストサイトであれば、アクセスするためのURLを教えていただけると早急に修正出来る場合があります


== Frequently asked questions ==

= データベースの容量を圧迫しませんか？ =

リンク先URL、タイトル、抜粋文等を取得してデータベースに格納します。
URLごとに保存されるため、別の記事でも同一URLを指定した場合にデータは増えません。
結果として記事内にタイトルや抜粋文を記述してリンクを設定するのと大きな差は無いと思います。
記事からショートコードを消してもデータベースからキャッシュ情報は削除されません。
キャッシュ情報を削除する場合、カード管理画面から個別で削除してください。
また、アンインストール時にプラグインと設定内容、キャッシュ用のデータベースを削除します。
外部リンクのサムネイルに「直接取得」を指定すると、リンク先サイトの画像を取得してサイズを拡大・縮小したファイルを作成します。
画像サイズを大きくするとファイル容量が増えるのでご注意ください。

= 内部リンクでの場合も新しいウィンドウで開きたいのですが？ =

設定画面から「外部リンク」と「内部リンク」、それぞれ「新しいタブで開く」の設定項目があります。
「モバイルのみ」と設定すると、パソコン等では別タブで開き、スマートフォンでは同一タブに開く事もできます。
新しいウィンドウで開くか、新しいタブで開くかは、ブラウザ側の制御となるため、ほとんどの場合新しいタブで開く事となります。

= WordPressのピンバックがリンク先へ飛びません。 =

WordPressピンバックは記事中にリンクを直接記述しないと飛びません。
ショートコード内にURLを記述した場合、ピンバックは飛びません。
直接URLを記述し、設定画面の「エディタ」タブから「URL行の変換」を有効にする事で対応できます。

= SSLサイトの内容が取得できません。 =

WordPressが動作しているサーバー側のSSL証明書が更新されていない場合等の場合、SSL検証が失敗されるのが原因と思われます。
「SSL検証を行わない」を有効にする事で改善される場合があります。（非推奨）

= nofollowの設定は重要ですか？ =

外部リンクにのみnofollowが設定できます。
Googleではnofollowを指定したリンク先はリンク元のサイトの評価に使用しないとしています。
通常は使用しない事をお勧めしますが、サイトの運用ポリシーによって使用する事は差し支えありません。

= noopenerの設定は重要ですか？ =

外部リンクにnoopenerを設定する事で、悪意を持ったサイトからリンク元のタブを保護する事が出来るため設定する事を推奨します。
最近のウェブブラウザでは指定が無くてもnoopenerの動作をする物が増えています。

= 直接取得したサムネイルが粗い。 =

外部リンクのサムネイルの直接取得は著作権法上の引用の範囲を超える事のないように低解像度（100px四方）としていました。
Ver.2.1.2から200px四方に変更、Ver.2.4.1から自由に指定出来るように変更しました。
イラストや写真が載っているサイトの画像を高画質で取得し、ギャラリーのような形で表示した場合等は違法と判断される恐れがあります。

= 「続きを読む」ボタンが表示されない。 =

カードの「高さ」を空欄にして記事内容が全て表示されるように指定しないと表示されない事があります。


== Screenshots ==

1. "Options screen"
2. "Cache manager screen"
3. "Edit cache data"
4. "The appearance of the 'LinkCard'"
5. "Write shortcode and url"


== Changelog ==

= 2.6.2 =
* [Tested] WordPress 7.1.2 での動作確認を行いました。
* [Tested] WordPress 7.1.3 での動作確認を行いました。
* [Fixed] 設定画面のテキストボックス上でシフト＋ホイールまたは右クリック＋ホイールで数値が入ってしまうのを修正しました。
* [Fixed] 設定画面の「かんたん書式設定」を選択したとき、実際の表示とプレビューが違ったのを修正しました。
* [Fixed] 管理画面でウィンドウ幅によってタイトルが右側に寄ってしまうのを修正しました。
* [Fixed] 管理画面でインポート／エクスポートをクリックした際、noticeの位置が下に行ったり上に行ったりするのを修正しました。
* [Added] 設定画面に「設定項目の検索」を追加しました。設定画面の項目名や説明文を検索出来ます。[Pz3]
* [Added] 設定画面で左右スワイプしたときに前/次のタブに移動する機能を追加しました。[Pz3]
* [Added] 設定画面の「上級者向け」タブにクイックメニューを追加しました。管理者の場合のみリンクカードの右側に編集画面に直接入れる三点メニューが表示されます。[Pz3]
* [Added] 設定画面の「エディター」タブにビジュアルエディターのプレビューを追加しました。リンクカードタグをリンクカードのプレビューで表示します。
* [Added] 設定画面の「表示」タブにスタイルの重要度を追加しました。リンクカードのCSSを優先させます。
* [Added] 設定画面の「リンク先の検査」タブに「robots.txtを参照する」を追加。外部リンクを取得するときにrobots.txtに従います。[Pz3]
* [Added] 設定画面の「リンク先の検査」タブに「ローカルIPアドレスをブロック」を追加。外部リンクを取得するときにローカルIPをブロックします。[Pz3]
* [Added] 設定画面の「その他」タブをデバグモードで開いたときログファイル全削除ボタンでログを削除出来るようにしました。
* [Added] 管理画面の一覧で「記事ID」の数値の右側にリンクボタンを追加しました。投稿を開き、該当のリンクカードを青枠で表示します。
* [Added] 設定画面、管理画面、編集画面の右上にヘルプを追加しました。使用出来るショートカットキーなどが表示されます。
* [Added] リンクカード全体をリンク化している場合でも、SNSやカテゴリのリンクを個別に開けるようになりました。[Pz3]
* [Added] ビジュアルエディタで使用するリンクカード挿入ダイアログで、内部リンクのタイトルによる検索が出来るようにしました。
* [Added] ブロックエディタのPz-LinkCardブロックの入力欄で、内部リンクのタイトルによる検索が出来るようにしました。
* [Modified] アクションやフィルターの記述を最適化しました。
* [Modified] 設定画面のレイアウトを一部修正しました。
* [Modified] 設定画面の「かんたん書式設定」の設定値を調整しました。
* [Modified] 設定画面の「リンク先の検査」タブの「ユーザーエージェント」を入力式から選択式へ変更しました。[Pz3]
* [Modified] 設定画面の「エラー」タブからエラーのある投稿を表示した際、エラーのある部分を赤枠で表示するように修正。
* [Modified] 管理画面のレイアウトを一部修正しました。
* [Modified] 管理画面の一覧で「記事ID」をクリックした際、同じ記事IDで検索するように変更しました。
* [Modified] 管理画面の一覧の右上に「検索ヘルプ」を追加しました。検索で使えるワードが書かれています。
* [Modified] 管理画面の編集画面右上の更新アイコンを変更しました。
* [Modified] ブロックエディタのPz-LinkCardブロックにフォーカスが当たっていない場合、入力欄を非表示にしました。
* [Modified] リンクカードの表示時、1行が完全に入らない場合はその行を非表示にしました。
* [Modified] リンク先がAmazon.co.jpの場合、1つ目の商品写真を取得するように修正しました。

= 2.6.1 =
* [Tested] WordPress 7.1.1 での動作確認を行いました。
* [Fixed] metaタグのcontentとpropertyの順番によって上手く取得出来ない不具合を修正しました。
* [Removed] 設定画面の「同ページへのリンク」を削除しました。同ページへのリンクは内部リンクとして表示されます。
* [Removed] 設定画面の「配置」タブから「BLOCKQUOTEで囲む」を削除しました。「領域を囲うタグ」で同じことが出来るようになりました。[Pz3]
* [Removed] 設定画面の「上級者向け」タブから「ファイルメニュー」を削除しました。
* [Modified] 設定画面をモバイル端末からの見た目を修正しました。
* [Modified] 設定画面で変更を保存を押したときの画面スクロールしてしまうを緩和しました。[Pz3]
* [Modified] 設定画面のタブの上でホイールをまわすと前/次のタブを選べるようになりました。[Pz3]
* [Modified] 設定画面の数値を入力する一部の項目から単位（px等）を外しました。
* [Modified] 設定画面のカラーピッカーを少しコンパクトに変更しました。
* [Modified] 設定画面のメッセージをトースト型に変更しました。
* [Modified] 管理画面の検索機能を強化しました。[Pz3]
* [Modified] 管理画面のレイアウトを一部整理しました。
* [Modified] ブロックエディタでのブロックにリンクカードを表示させるように修正しました。[Pz3]
* [Modified] リンクカードの編集画面のレイアウトを変更しました。[Pz3]
* [Modified] 画像キャッシュを作成する際、WebPが使用出来ない場合JPEGで保存するように変更しました。
* [Added] 管理画面の一番上に情報バーを追加しました。インポートとエクスポート、設定画面へ切り替えるのボタンがあります。[Pz3]
* [Added] 管理画面の一覧に「表示オプション」を追加しました。表示項目を選択出来ます。[Pz3]
* [Added] 管理画面の一覧でサムネイル画像をクリックしたとき拡大表示等が出来るように変更しました。[Pz3]
* [Added] 編集画面のサムネイル画像とサイトアイコン画像に「メディア」ボタンを追加して、メディアから画像を選択出来るようになりました。
* [Added] 編集画面のサムネイル画像とサイトアイコン画像に「解除」ボタンを追加して、画像を解除出来るようになりました。
* [Added] 管理画面の一番上に情報バーを追加しました。管理画面へ切り替えるのボタンがあります。[Pz3]
* [Added] 設定画面にプレビューを追加しました。ウィンドウ状態、ドッキング状態で確認出来ます。[Pz3]
* [Added] 設定画面の「外部リンク」「内部リンク」の項目を追加しました。通常時とホバー時等で個別に背景や枠線を設定出来ます。[Pz3]
* [Added] 設定画面の「外部リンク」「内部リンク」の見出しに「🔺」「🔻」を追加しました。前/次の項目をウィンドウの一番上になるようにスクロールさせます。
* [Added] 設定画面の「外部リンク」と「内部リンク」で共有していた設定の一部を各タブに追加しました。
* [Added] 設定画面の「一番上へ戻る」ボタン下に、現在開いているタブ名を表示するようになりました。[Pz3]
* [Added] 設定画面の「配置」タブに「領域を囲うタグ」を追加しました。DIVの他、ARTICLE、BLOCKQUOTEなどから選択でき、モバイル用クラス名も設定可能です。[Pz3]
* [Added] 設定画面の「配置」タブに「テキストの選択」を追加しました。マウス等でリンクカード中のテキストを選択できないように選択出来ます。[Pz3]
* [Added] 設定画面の「エラー」タブに記事タイトルを追加しました。
* [Added] 設定画面のタブの上でシフト＋ホイールまたは右クリックしながらホイール操作で前/次のタブを選択できるようにしました。[Pz3]
* [Added] 設定画面の項目の上でシフト＋ホイールまたは右クリックしながらホイール操作で値の増減を可能にしました。[Pz3]

= 2.6.0.5 =
* [Modified] ブロックエディタでの表示を調整しました。（Thanks Senri Miura @senribb on wordpress.org）
* [Added] 設定画面の「外部リンク」「内部リンク」に「ホバー時の背景色」を追加しました。（Thanks さくら工作室/工作系YouTuber @skrdtrt on x.com）

= 2.6.0.4 =
* [Fixed] サムネイル画像とサイトアイコンの直接取得しても表示されない不具合を修正しました。
* [Modified] URLエラーが表示されているとき右側の×をクリックするとエラーが解除されるように修正しました。（Thanks さくら工作室/工作系YouTuber @skrdtrt on x.com）

= 2.6.0.3 =
* [Fixed] 警告が表示されてしまう不具合を修正しました。

= 2.6.0.2 =
* [Fixed] URL挿入のテキストボックスとボタンが関係無い画面にも表示されていた不具合を修正しました。（Thanks 澤田芳弘 @SWD on x.com）

= 2.6.0.1 =
* [Fixed] DBテーブルの作成が失敗する不具合を修正しました。

= 2.6.0 =
* [Tested] WordPress 7.0.2 での動作確認を行いました。
* [Tested] WordPress 7.0.4 での動作確認を行いました。
* [Fixed] 「XSS（クロスサイトスクリプティング）」に対する脆弱性があったため、対策を行いました。
* [Fixed] 環境によって設定が保存出来ない不具合を修正しました。
* [Fixed] 保存する記事内容の項目が長すぎたときに保存に失敗してしまうのを修正しました。
* [Fixed] キャッシュされていてもキャッシュから読まないURLがあったのを修正しました。
* [Added] ブロックエディタ用のPz-LinkCardブロックを追加しました。URLを入力するとショートコードになります。（プレビューはされません）
* [Added] 設定画面の「外部リンク」タブのサイトアイコンの取得方法で「直接取得」が選べるように修正しました。
* [Added] 管理画面のドメイン欄にサイトアイコン（直接取得した物のみ）の表示を追加しました。
* [Modified] セキュリティ対策のため、外部サイトへのアクセスに使用するのをcURLからwp_safe_remote_getに変更しました。
* [Modified] セキュリティ対策のため、リダイレクトURLの判定方法を修正しました。
* [Modified] かんたん書式設定の「押しピン」の画像を変更しました。
* [Modified] かんたん書式設定を調整しました。
* [Removed] 設定画面の「リンク元の検査」タブから「リファラーの通知」を削除しました。

= 2.5.9.3 =
* [Fixed] 「XSS（クロスサイトスクリプティング）」に対する脆弱性があったため、対策を行いました。
* [Added] 設定画面の「初期化」タブにプラグイン削除時に設定・キャッシュ画像・記事内容を削除する設定を追加しました。

= 2.5.9.2 =
* [Fixed] 管理画面のエクスポートが失敗する不具合を修正しました。

= 2.5.9.1 =
* [Fixed] ブロックエディタで記事投稿時にJSONエラーが出るのを修正しました。（Thanks さくら工作室/工作系YouTuber @skrdtrt on x.com）

= 2.5.9 =
* [Tested] WordPress 7.0 での動作確認を行いました。
* [Tested] WordPress 7.0.1 での動作確認を行いました。
* [Fixed] 再インストール後、DBテーブルが作成されない不具合を修正しました。（Thanks さくら工作室/工作系YouTuber @skrdtrt on x.com）
* [Modified] サムネイル画像をJPEG形式からWEBP形式へ変更しました。（Thanks さくら工作室/工作系YouTuber @skrdtrt on x.com）

= 2.5.8.1 =
* [Tested] WordPress 6.9.1 での動作確認を行いました。
* [Fixed] URLエラー時に指定したパラメータをそのまま表示していたのを修正しました。

= 2.5.8 =
* [Tested] WordPress 6.8.3 での動作確認を行いました。
* [Added] 設定画面の「エディタ」に「除外URL」を追加しました。リンクカードに変換しないURLを指定出来ます。（Thanks 雑学のあしあと @zatsugaku_ashi on x.com）

= 2.5.7.2 =
* [Fixed] カード管理画面のインラインメニューが動作しない不具合を修正しました。
* [Added] カード管理画面の一覧の下側にあるページ数を復活しました。

= 2.5.7.1 =
* [Fixed] 設定画面に入った際にエラーになり画面表示されない不具合を修正しました。（Thanks jh4vaj @jh4vaj on x.com）
* [Fixed] PHP8を使用したときに文字コード判定が誤ってしまう不具合を修正しました。
* [Fixed] PHP8を使用したときにエクスポート時に警告が出てしまうのを修正しました。
* [Fixed] カード管理画面のページ数を直接入力してもページが変更出来ない不具合を修正しました。
* [Removed] カード管理画面の一覧の下側にあるページ数を削除しました。

= 2.5.7 =
* [Tested] WordPress 6.8.2 での動作確認を行いました。
* [Tested] PHP 8.1.29 での動作確認を行いました。PHPの最低要件を同バージョンとしました。
* [Tested] PHP 8.4.10 での動作確認を行いました。
* [Removed] Pocketのサービス終了（2025/07/08）に伴い、Pocketのカウント取得・表示の機能を削除しました。
* [Added] 設定画面の「リンク先の検査」タブに「クリック件数」を追加しました。クリックされた件数を取り、カード管理画面に表示します。
* [Added] カード管理画面に「クリック件数」を追加しました。
* [Added] ローカルプライベートアドレス、ループバックアドレスを指定禁止にしました。
* [Fixed] 内部リンクでカテゴリーページを指定した際の処理を見直しました。

= 2.5.6.5 =
* [Tested] WordPress 6.8 での動作確認を行いました。
* [Tested] WordPress 6.8.1 での動作確認を行いました。
* [Fixed] 設定画面からプラグインの再起動を行った際、エラーが発生するのを修正しました。
* [Fixed] 設定画面の「基本」タブの「更新履歴」で正しく改行がされていないのを修正しました。
* [Fixed] プラグインを有効化したとき、uninstallのフックを定義しようとしてエラーが表示されるのを修正しました。（Thanks ほんみや @hontonomiyazaki on x.com）

= 2.5.6.4 =
* [Fixed] クラシックエディタで挿入ボタンからショートコードを挿入した後、フォーカスが自動で戻るように修正しました。
* [Modified] 投稿編集画面で使用している挿入ボタンのjsファイルにについて、jQueryだったものをJavaScriptに変更しました。
* [Modified] 設定画面で使用しているjsファイルについて、一部の機能を分割しました。また一部、jQueryだったものをJavaScriptに変更しました。
* [Modified] 設定画面の「管理者」タブで実行出来る処理を一部変更しました。※一般に解放していません。

= 2.5.6.3 =
* [Fixed] 設定画面でタブの移動などが出来なくなる不具合を修正しました。（Thanks HidetatsuTsuji @hakitukai on x.com）
* [Fixed] 設定画面で処理中にエラーが発生したまま固まる不具合を修正しました。（Thanks ゴルフや投資の配信 @piyofumin4 on x.com）
* [Fixed] 投稿編集画面にて挿入ボタンが起因したエラーが表示されていたのを修正しました。（Thanks マーージ＠ブログ中毒 @maagemagemaaage on x.com）
* [Fixed] 投稿編集画面にて挿入ボタンが動作しない不具合を修正しました。（Thanks yukkun20 #comment-12876 on popozure.info）
* [Fixed] 管理者権限の無いログインユーザーがサイトを見た際、PHPのWarningが出てしまう不具合を修正しました。（Thanks @kikorin55 on wordpress.org）
* [Fixed] 軽微なバグを修正しました。
* [Modified] 設定画面の「基本」タブの「変更履歴」の表示方法を修正しました。（行の先頭のバッジに保留（PENDING）を追加しました）
* [Modified] 設定画面の「上級者向け」タブの「調査モード」をログファイルを出力する機能のみにしました。
* [Modified] 設定画面の「上級者向け」タブに「デバッグモード」を追加しました。調査モードの一部の機能（非表示項目の表示）を移しました。
* [Modified] 内部処理を一部見直しました。

= 2.5.6.2 =
* [Added] 設定画面の「上級者向け」タブに「入力禁止」を追加しました。「変更を保存」をクリックした際に誤入力を避けるため暗転して入力禁止にします。
* [Fixed] 設定画面で「変更を保存」をクリックした際、暗転するようにしましたが初期値では暗転なしにしました。
* [Fixed] 設定画面の「配置」の「幅」を空欄にしていた場合、「0px」として扱っていたのを「100%」として扱うように修正しました。（Thanks KAI #comment-12912 on popozure.info）
* [Modified] 内部処理を一部見直しました。

= 2.5.6.1 =
* [Fixed] クラシック エディターで挿入ボタンが動作しない場合があったため、スクリプトを修正しました。
* [Fixed] 「テキストリンク行を変換」を有効にした際、リンクカードが表示されずURLエラーの表示になってしまう不具合を修正しました。
* [Fixed] マルチサイトを利用している際、設定画面に「初期設定値に〇〇が定義されていません」というエラーが表示されるのを修正しました。（Thanks ふりっぷ @flip365 on x.com）

= 2.5.6 =
* [Tested] WordPress 6.7.1 での動作確認を行いました。
* [Tested] WordPress 6.7.2 での動作確認を行いました。
* [Tested] WordPress 6.7.3-alpha-59811 での動作確認を行いました。
* [Tested] PHP 7.4.33 での動作確認を行いました。PHPの最低要件を同バージョンとしました。
* [Tested] PHP 8.2.22 での動作確認を行いました。
* [Removed] 設定画面の「表示」タブから「リンク文字の下線を除去」を削除しました。「文字」タブで同様の設定が出来るようになったため。
* [Fixed] 「サイズの変更」を有効にしているときの画像の縦横比が崩れてしまっていたのを修正しました。（Thanks ささのは @sasanohasan on x.com）
* [Fixed] サムネイルに強制的に枠線が表示されてしまったのを修正しました。（Thanks さくら工作室/工作系YouTuber @skrdtrt on x.com）
* [Fixed] プラグインを有効化したときに「プラグインの有効化中にxxx文字の予期しない出力が生成されました」のエラーが表示されてしまうのを修正しました。
* [Fixed] リンク先の画像取得に失敗した際、管理画面でエラーが表示されてしっていたのを修正しました。（Thanks 足じゃんけん @ASHIJANKEN on x.com）
* [Modified] 設定画面の「基本」タブの「変更履歴」を日本語のみにしました。
* [Modified] 設定画面の「基本」タブの「変更履歴」の表示方法を修正しました。（行の先頭に追加（Added）・修正（Fixed）・変更（Modified）・削除（Removed）のバッジが付きます）
* [Modified] 「かんたん書式設定」を選んだときの表示方法を調整しました。
* [Modified] 設定画面の「文字」タブの構成を表形式に変更しました。
* [Modified] 設定画面の「文字」タブに「ヘッダー文字列」「カテゴリー」を追加しました。ただしカテゴリー表示は未実装のため変更できません。
* [Modified] 設定画面の「外部リンク」「内部リンク」「同ページへのリンク」に「ヘッダー」を追加しました。
* [Modified] 設定画面の「サイズの変更」を「表示」タブから「配置」タブへ移動しました。
* [Modified] 設定画面の「BLOCKQUOTEで囲む」を「配置」タブから「上級者向け」タブへ移動しました。
* [Modified] 設定画面の「シェア数の表示」の「タイトルの後ろ」を「タイトルの下」へ変更しました。
* [Modified] 設定画面の「表示」にある「投稿日を表示」の機能について、指定した場合、URLの代わりに表示するように修正。
* [Modified] スタイルシートを生成する際、軽量化したファイルも生成するように修正しました。
* [Modified] 設定画面の「上級者向け」タブに「圧縮」を追加しました。軽量化したスタイルシートを使用するようになります。
* [Modified] ショートコードのURLパラメータの指定が誤っている場合の判定を厳しくしました。エクスポート時に不正データになってしまうため。（Thanks さくら工作室/工作系YouTuber @skrdtrt on x.com）
* [Modified] 設定の初期値をいくつか変更しました。（リンクカードの影の初期値が無しから有りになったなど）
* [Modified] 設定画面で「変更を保存」を押した際、誤入力を防ぐため暗転するように修正しました。
* [Added] 設定画面の「内部リンク」タブの「記事取得方法」にカスタムフィールドを優先する設定を追加しました。（Thanks Goshi #comment-7728 on popozure.info）
* [Added] 設定画面の「内部リンク」タブに「タイトルにするカスタムフィールド」「抜粋文にするカスタムフィールド」を追加しました。（Thanks Goshi #comment-7728 on popozure.info）
* [Added] 設定画面の「表示」にサムネイルの枠線を追加しました。
* [Added] 設定画面の「エディター」タブに「抜粋文をクリア」を追加。titleパラメーターを指定したときに抜粋文をクリアします。
* [Added] 設定画面の「上級者向け」タブに「テキストの選択」を追加。カード内のテキストを選択禁止に出来ます。

= 2.5.5 =
* WordPress 6.5.4 での動作確認。
  Tested: Compatible with WordPress 6.5.4.
* スタイルシートのテンプレートを一部修正しました。
  Modified: Some modifications have been made to the style sheet template.
* 設定画面でチェックボックスの一部でチェックが外れなくなる不具合を修正しました。
  Fixed: Fixed a problem in which some checkboxes were checked when settings were saved on the settings screen.
* 設定画面でフォーカスがあたっている項目の背景色を水色にするように修正しました。
  Modified: Changed the background color of the focused item in the settings screen to light blue.
* カード管理画面の編集画面に一覧を表示しないように変更しました。
  Modified: Changed so that the list is not displayed on the edit screen of the administration page.
* カード管理画面で「適用」ボタンでのみ一括処理が実行されるように変更しました。
  Modified: Changed so that batch processing is executed only with the “Apply” button on the card management screen.
* カード管理画面での画面遷移がGETのままだった部分を追加でPOSTへ修正しました。
  Fixed: The screen transition on the card management screen that was still GET has been additionally corrected to POST.
* カード管理画面の内部リンクの件数と外部リンクの件数が合わない不具合を修正しました。
  Fixed: Fixed a problem in which the number of internal links on the card management screen did not match the number of external links.
* 設定画面の一番上にプラグインの名前とバージョンを追加しました。
  Added: Added plugin name and version at the top of the settings screen.
* 設定画面のエディタータブの中の項目を機能の種類によって分けました。
  Modified: Items in the Editor tab of the Settings screen are now divided by function type.
* 管理画面の一番上にプラグインの名前とバージョンを追加しました。
  Added: Added plugin name and version at the top of the admin page.
* リンクカードが記事の幅を越えて表示されてしまう不具合を修正しました。
  Fixed: Fixed a bug that caused Link Cards to appear beyond the width of the article.
* サムネイル画像の領域を変更したが、テーマの設定に引っ張られ正しく表示されない現象を修正しました。（Thanks さくら工作室/工作系YouTuber @skrdtrt on x.com）
  Modified: Improved display of thumbnails with small vertical size. (Additional Corrections.)
* 管理画面からキャッシュ内容のインポート／エクスポートが正常に出来ない不具合を修正しました。
  Fixed: Fixed a problem that prevented import/export of cache contents from the administration screen.

= 2.5.4 =
* WordPress 6.5.3 での動作確認。
  Tested: Compatible with WordPress 6.5.3.
* 設定画面で [Ctrl]+[←] でひとつ前のタブ、[Ctrl]+[→]でひとつ後のタブを選択出来るように機能追加。
  Added: Press CTRL+RIGHT on the settings screen to move to the next tab, or CTRL+LEFT to move to the previous tab.
* 設定画面で [Ctrl]+[S] で「変更を保存」をクリックした動作をするように機能追加。
  Added: Press CTRL+S in the settings screen to save changes.
* 設定画面で「一番上へ戻る」ボタンを追加。
  Added:  A "Back to Top" button has been added to the settings screen.
* 設定画面のレイアウトを一部見直し。
  Modified: Review the layout of the setting screen.
* 設定画面にスクロールに追従する「変更を保存」ボタンを追加しました。
  Added: A “Save Changes” button that follows scrolling has been added to the settings screen.
* 設定画面で「変更を保存」を押したときに画面を暗くするように修正しました。
  Modified: Fixed to darken the screen when the 'Save Changes' button is pressed.
* 設定画面の「外部リンク」から「はてなブログカードを使用する」を削除しました。
  Removed: Removed "Use HatenaBlogCard" from "External Links" on the Settings screen.
* カード管理画面での画面遷移をGETからPOSTに変更しました。
  Modified: Screen transitions on the card management screen have been changed from GET to POST.
* カード管理画面にて外部サイトのURLにリンクを追加しました。
  Added: Added links to external site URLs on the card management screen.
* カード管理画面で内部リンクのサムネイルのURLからスキーム部分（http、httpsなど）を削除しました。
  Modified: Removed scheme part (http, https, etc.) from URLs of thumbnails of internal links in the card management screen.
* リンクカードの表示を一部調整しました。今までと表示がずれる可能性があります。
  Modified: Some adjustments have been made to the display of Link Cards. There is a possibility that the display may be shifted from the previous version.
* サムネイル画像のIMGタグにwidthとheightを追加しました。（サイトアイコンには既に付いていました）（Thanks Jack Ryan @ryan_j23 on x.com）
  Added: Added width and height to IMG tags for thumbnail images.
* サムネイル画像が小さい場合、サムネイルの領域いっぱいに表示するように修正。（Thanks さくら工作室/工作系YouTuber @skrdtrt on x.com）
  Modified: Improved display of thumbnails with small vertical size.
* 「かんたん書式設定」を選んだときの表示方法を調整しました。
  Modified: The display method when “Easy formatting” is selected has been adjusted.

= 2.5.3.1 =
* カード管理画面に「現在、開発環境で作業中です」というメッセージが誤って表示されていたのを修正。（Thanks あおいぷちゅ @aoipuchu on x.com）
  Fixed: Fixed an incorrect message on the manager screen.
* 設定画面に「現在、開発環境で作業中です」というメッセージが誤って表示されていたのを修正。（Thanks あおいぷちゅ @aoipuchu on x.com）
  Fixed: Fixed an incorrect message on the settings screen.
* リンク先が取得出来ない場合に誤って「ショートコードの記述エラー」と表示されてしまう不具合を修正。
  Fixed: Fixed incorrect message displayed when acquisition was not possible.
* 更新履歴に表示される協力者のXアカウントが数字から始まる場合、リンクされていなかったのを修正。
  Fixed: Fixed a case where the changelog was not linked to the X account.

= 2.5.3 =
* WordPress 6.4.3 での動作確認。
  Tested: Compatible with WordPress 6.4.3.
* 「SSRF（サーバーサイドリクエストフォージェリ）」に対する脆弱性があったため、対策を行いました。
  Fixed: SSRF vulnerability was discovered and countermeasures were taken.
* 「XSS（クロスサイトスクリプティング）」に対する脆弱性があったため、対策を行いました。
  Fixed: XSS vulnerability was discovered and countermeasures were taken.
* 「Reflected-XSS（反射型クロスサイトスクリプティング）」に対する脆弱性があったため、対策を行いました。
  Fixed: Reflected-XSS vulnerability was discovered and countermeasures were taken.
* ソーシャルカウントの取得方法を修正しました。
  Fixed: Modified "How to access the External Links" to be even more secure.
* URLの安全性チェックを追加しました。
  Fixed: Modified "URL Checks" to be even more secure.


== Upgrade notice ==


== Arbitrary section ==

= Display and DB cache =

This plug-in one create a DB table when you have activated. (Prefix + "pz_linkcard")

Open the pages of the article when the "For the first time it appears " , and caches by obtaining the title excerpt from the linked site to the DB.

Therefore , the display for the first time is slow , the second and subsequent display is fast.


= Create files =

CSS file are stored in a custom folder under "/wp-content/Uploads".


= Use Web API =

Number of SNS share have been acquired by the JSON request.

* Twitter ... https://jsoon.digitiminimi.com/twitter/count.json?url=[URL]

* Facebook ... https://graph.facebook.com?fields=og_object{engagement}&id=[URL]

* Hatena ... http://api.b.st-hatena.com/entry.count?url=[URL]

* Pocket ... https://widgets.getpocket.com/api/saves?url=[URL]

Displays using the "Google Favicon API" to get the site icon. This can be changed.

Displays using the "WordPress.org mshots API" to get the thumbnail. This can be changed.


= 表示とキャッシュ =

このプラグインは、有効化したときにDBテーブルを一つ作成します。（プレフィックス＋「pz_linkcard」）

外部リンクを設定した場合、記事のページを開いて「初めて表示された」ときに、リンク先のサイトからタイトル・抜粋文を取得してDBへキャッシュします。

外部リンクを設定した場合、カードの枚数分だけ外部サイトへのアクセスが発生するため多量のリンクを作成すると、記事をプレビューした時等、最初の表示に時間がかかります。

次回の表示はDBキャッシュから行うので高速に表示を行います。

（内部でのDBアクセスが発生しますが、通常は軽微なものです。カード1枚表示のたびに、取得のために1クエリ発行します。更新が発生した場合には挿入・更新のためのクエリが1回発生します。）


= ソーシャルカウントの取得 =

ソーシャルカウントについては、「Twitter（ツイッター）のツイート数」「facebook（フェイスブック）のシェア数」「はてなブックマークのブックマーク数」「Pocketの登録数」の4種類に対応しています。

それぞれWebAPIを使用して値を取得します。

バックグラウンドで取得するため、ページの表示速度には影響がありません。

取得した値はタイトルや抜粋文と同様、DBへキャッシュを行うため、直近の表示にはWebAPIアクセスが発生しません。

ソーシャルカウントの再取得は、最後の取得から4時間～36時間程度のランダムな時間で行います。

また、各WebAPIについては、仕様変更やサービス終了に伴い、正常に取得できなくなる場合があります。


= 画像取得WebAPIの利用 =

設定画面からサムネイル取得WebAPIが指定できます。

「WebAPIを利用する」にする事でページのスクリーンショット画像を取得します。

参考．画像取得WebAPIの設定について https://popozure.info/20151004/9317


設定画面からサイトアイコン取得WebAPIが指定できます。

サイトアイコンの場所はサイトによってバリエーションが多いため、WebAPIを使用する前提となります。

正式に公開されているWebAPIでは無いため、仕様変更やサービス終了に伴い、正常に取得できなくなく場合があります。


= その他 =

Pz-HatenaBlogCard からの設定引き継ぎ機能はありません。この機会に触った事のなかった設定項目にも触れていただければ幸いです。

ショートコードを変える事で、Pz-HatenaBlogCard と併用利用する事ができますが、通常はリソース消費が増えるだけなので、推奨はしません。


ショートコード内にURLを記述した場合、WordPressピンバックは飛びません。


設定項目については、WordPress標準の options に設定内容を保存します。キーは「Pz-LinkCard_options」の1レコードです。


なお、アンインストールを行う際には、キャッシュを保管するDBテーブルと、options内の設定ファイルは削除されます。

アンインストール時の削除に関してはプラグインディレクトリ内の uninstall.php で行っています。
