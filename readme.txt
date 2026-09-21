=== Pz-LinkCard ===
Contributors: Poporon
Tags: LinkCard, BlogCard, Internal Link, External Link
Requires at least: 6.0
Tested up to: 7.0.2
Requires PHP: 8.0
Stable tag: trunk
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

= 2.6.1 =
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

= 2.5.2 =
* 欠番。

= 2.5.1 =
* デバグ用の機能（強制リンク先取得）を追加。
  Added: Added function for debugging.
* 文字コードの判定に失敗すると異常終了になってしまう不具合を修正。（Thanks 足じゃんけん @ASHIJANKEN on x.com）
  Fixed: Fixed a bug that caused abnormal termination when character code determination failed.

= 2.5.0.1 =
* カード管理画面にて外部サイトへのリンクを外しました。
  Fixed: Fixed to remove the link from the URL as a response to the vulnerability.
* サイトアイコンが取得出来ない不具合を修正。(Thanks 澤田 芳弘 @SWD on x.com)
  Fixed: Fixed a bug that site icons could not be retrieved.

= 2.5.0 =
* WordPress 6.4.2 での動作確認。
  Tested: Compatible with WordPress 6.4.2.
* Pzカード管理の画面のセキュリティを強化。(Thanks 山下午壱 @fiveoneinc_jp on x.com)(Thanks 山田彩乃 @happyjotoku on x.com)
  Fixed: Enhanced security of the Pz Card Management screen.
* URLにシングルクォートが含まれる場合、リンクされなかった不具合を再度修正。（Thanks てんちゃん #comment-6603 on popozure.info）
  Fixed: Fixed again the bug that the link did not work if the URL contained single quotes.
* 文字コードの判定に失敗すると異常終了になってしまう不具合を修正。（Thanks 足じゃんけん @ASHIJANKEN on x.com）
  Fixed: Fixed a bug that caused abnormal termination when character code determination failed.
* 設定画面の「外部リンク」タブ及び「内部リンク」タブの「代替テキスト」にパラメータを追加。(Thanks 澤田 芳弘 @SWD on x.com)
  Added: Added a parameter to "Alternative Text" in the "External Links" and "Internal Links" tabs of the Settings screen.

= 2.4.8.1 =
* WordPress 6.4.1 での動作確認。
  Tested: Compatible with WordPress 6.4.1.
* URLにシングルクォートが含まれる場合、リンクされなかった不具合を修正。（Thanks てんちゃん #comment-6603 on popozure.info）
  Fixed: Fixed no link when single quotes are included in the URL.

= 2.4.8 =
* WordPress 6.3.1 での動作確認。
  Tested: Compatible with WordPress 6.3.1.
* カード管理画面にてSQLの構文エラーがあったのを修正。(Thanks @in_seki on x.com)
  Fixed: Fixed SQL syntax error in card management screen.
* 設定画面の「外部リンク」タブに「サムネイル」と「サイトアイコン」の「代替テキスト」の設定を追加。(Thanks @SWD on x.com)
  Added: Added "ALT Text" settings for "Thumbnail" and "Site Icon" to the "External Links" tab of the Settings page.
* 設定画面の「内部リンク」タブに「サムネイル」と「サイトアイコン」の「代替テキスト」の設定を追加。(Thanks @SWD on x.com)
  Added: Added "ALT Text" settings for "Thumbnail" and "Site Icon" to the "Internal Links" tab of the Settings page.
* 設定画面の「Twitter」のシェア数の単位を「Posts」と「Tweets」から選べる設定を追加。
  Added: Added a setting that allows users to choose between "Posts" and "Tweets" as the unit for the number of shares for "Twitter" on the settings screen.
* X (Twitter)のシェア数が取得出来ないため、WebAPIの利用を停止。
  Modified: Stopped using WebAPI because the number of X (Twitter) shares cannot be obtained.

= 2.4.7 =
* WordPress 6.0.2 での動作確認。
  Tested: Compatible with WordPress 6.0.2.
* ビジュアル エディタの「リンクカード挿入」ボタンが表示されない不具合を修正。(Thanks @hutagoparadise on Twitter)
  Fixed: Fixed a bug that prevented the "Insert Link Card" button in the Visual Editor from appearing.

= 2.4.6.1 =
* WordPress 6.0.1 での動作確認。
  Tested: Compatible with WordPress 6.0.1.
* 国際化ドメイン（日本語ドメイン）の記事が取得出来ない不具合を修正。(Thanks @kozy_1919 on Twitter)
  Fixed: Fixed a bug that prevented the acquisition of articles with an IDNA-ASCII-Domain.

= 2.4.6 =
* WordPress 6.0 での動作確認。
  Tested: Compatible with WordPress 6.0.
* 画像のリダイレクトに対応。（CDN対応）(Thanks @in_seki on Twitter)
  Modified: Support for image URL redirects in CDNs, etc.
* 特定のテーマでAMPと誤判定してしまう不具合を修正。(Thanks @360bpk on Twitter)
  Fixed: Fixed a bug that caused misidentification as AMP when combined with certain themes.

= 2.4.5.4 =
* WordPress 5.9.5 での動作確認。
  Tested: Compatible with WordPress 5.9.5.
* プラグインのアンインストールが失敗する不具合を修正。
  Fixed: Fixed a bug that caused uninstallation to fail.
* 管理画面に記事情報が登録されない不具合を修正。(Thanks @OrganicRelife on Twitter)
  Fixed: Fixed a bug that article information could not be cached.
* 管理画面で入力したページ数に移動しなかったのを修正。
  Fixed: Fixed a bug that prevented moving by specifying the number of pages.

= 2.4.5.3 =
* 相対指定のURLを使用した場合に致命的エラーが出る不具合を修正。(Thanks @wiashia on Twitter)
  Fixed: Fixed a bug that caused a fatal error when using a relative URL.

= 2.4.5.2 =
* カード管理画面のエスケープ処理を追加。（XSS脆弱性の対応）(Thanks tkusagaya on WordPress.org)
  Fixed: Added escaping of display items to address XSS vulnerability.

= 2.4.5.1 =
* 内部リンクの投稿日・更新日が表示されるように修正。(Thanks A-Jin)
  Fixed: Fixed internal links not displaying posted and updated dates.

= 2.4.5 =
* 最低動作環境を WordPress 5.7 に変更。
  Changed the minimum system requirements to WordPress 5.7.
* WordPress 5.8.2 での動作確認。
  Tested: Compatible with WordPress 5.8.2.
* PHP 7.3 での動作確認。
  Tested: Compatible with PHP 7.3.
* PHP 7.4 での動作確認。
  Tested: Compatible with PHP 7.4.
* PHP 8.0 に対応。PHPの仕様変更により出るようになったエラーを修正。
  Tested: Compatible with PHP 8.0.
* PHP 8.1 での動作確認。（WordPress本体や他のプラグインでエラーが出る状態です）
  Tested: Compatible with PHP 8.1.
* リンクカードのタイトルと抜粋文のエスケープ処理を追加。（XSS脆弱性の対応）
  Fixed: Added escaping of display items to address XSS vulnerability.
* リンクカードのHTMLの"noopener"の表記ミスを修正。(Thanks @jh4vaj on Twitter)
  Fixed: Corrected a spelling error in "noopener".
* カード情報のエクスポート時、改行が入らないように修正。
  Fixed: Fixed that line feeds are not inserted when exporting card information.

= 2.4.4.4 =
* WordPress 5.8.1 での動作確認。
  Tested: Compatible with WordPress 5.8.1.
* カード管理画面の一覧画面に表示される文字列にエスケープ処理を追加。（XSS脆弱性の対応）
  Fixed: Added escaping of display items to address XSS vulnerability.
* カード管理画面の編集画面に表示される文字列にエスケープ処理を追加。（XSS脆弱性の対応）
  Fixed: Added escaping of display items to address XSS vulnerability.
* リンクカードを表示する際の文字列にエスケープ処理を追加。（XSS脆弱性の対応）
  Fixed: Added escaping of display items to address XSS vulnerability.

= 2.4.4.3 =
* 設定画面の「エディタ」タブの「テキストリンク行を変換」を有効にした場合、行の始めから終わりまでAタグで囲われている場合、画像等があっても変換してしまう不具合を修正。(Thanks @magemagemaaage)
  Fixed: Fixed a bug that "Convert text link line" on the "Editor" tab of the setting screen converts even if there is something other than text.
* 設定画面とカード管理画面のアイコンをemojiスタイルに変更。（U+FE0F VARIATION SELECTOR-16 (EPVS) を付与。）
  Modified: Change the icons on the setting screen and card management screen to emoji style.

= 2.4.4.2 =
* URLに%20（半角スペース）が含まれていた場合、除去しないように修正。
  Modified: Corresponds to the case where the URL contains a half-width space.
* ショートコードの閉じに全角ダブルクォートを指定してもリンクが機能するように修正。（「“」と「”」は対応していましたが「″」の対応が抜けていました）
  Modified: Corresponds to the case of using double-byte quotes for shortcodes.

= 2.4.4.1 =
* WordPress 5.7 での動作確認。
  Tested: Compatible with WordPress 5.7.
* 設定画面の「内部リンク」タブの「記事取得方法」で「抜粋文を優先」を選んでも抜粋文が取得できていない不具合を修正。(Thanks @OrganicRelife)
  Fixed: Fixed a bug that excerpts could not be obtained even if "Prioritize excerpts" was selected in "Article acquisition method" on the "Internal link" tab of the setting screen.

= 2.4.4 =
* URLパラメーターにクォート文字が残ってしまう不具合を修正。(Thanks @OrganicRelife)
  Fixed: Fixed a bug that quotes remained in URL parameters.
* URLパラメーターで許容するスキーム（プロトコル）を追加。（http: / https: / file: / ftp: / data: / ogg: を許容します。）
  Modified: Added protocols allowed by URL parameters.
* URLパラメーターが不正な場合、カード管理画面に登録されないように修正。
  Fixed: Corrected so that it will not be registered if the URL parameter is incorrect.
* ソーシャルカウント数が表示されていなかった不具合を修正。（Ver.2.4.2.1から発生）(Thanks @san5w)
  Fixed: Fixed a bug where the social count was not displayed.
* ソーシャルカウントの取得が出来ない不具合を修正。(Thanks @san5w)
  Fixed: Fixed a bug that social count could not be obtained.
* 旧バージョンからのオプション引継ぎの処理を一部変更。
  Modified: Changed the method of inheriting options from the previous version.
* 設定画面のレイアウトを一部見直し。
  Modified: Review the layout of the setting screen.
* 設定画面の見出しに「ギア」のアイコンを追加。
  Added: Added an icon to the heading of the settings screen.
* 設定画面の各設定の見出しの書式を変更。
  Modified: Add format to each setting heading on the settings screen.
* 設定画面のスタイルシートを直書きしていたものをCSSファイルへ移動。
  Modified: Move stylesheets from HTML to CSS.
* 設定画面の右上に「カード管理画面」へのリンクを追加。
  Added: Added a link to the management screen at the top right of the setting screen.
* 設定画面の「エラー」タブのエラー解除方法をチェックを外す方法からボタンを押す方法へ変更。
  Modified: Modified the error cancellation method on the setting screen.
* 設定画面の「外部リンク」タブの「記事取得方法」が正しく表示されない不具合を修正。
  Fixed: Fixed a bug that could not be selected in "Get Contents" on the "External link" tab of the setting screen.
* 設定画面の「内部リンク」タブの「記事取得方法」を選んでも反映されない不具合を修正。(Thanks @OrganicRelife)
  Fixed: Fixed a bug that could not be selected in "Get Contents" on the "Internal link" tab of the setting screen.
* 設定画面の「リンク先の検査」タブの「ユーザーエージェント」を書き換え可能項目に変更。
  Modified: Modified "User Agent" on the "Link Check" tab of the setting screen to be changeable.
* 設定画面の「エディタ」タブの「テキストリンク行を変換」を有効にした場合、URLが認識されず変換されないパターンがあったため修正。（マルチバイト文字が入っている場合、?や&といったパラメーターがある場合等）
  Fixed: Fixed a bug that URL is not converted in "Convert text link line" on the "Editor" tab of the setting screen.
* 設定画面の「エディタ」タブの「URL行を変換」を有効にした場合、URLが認識されず変換されないパターンがあったため修正。（マルチバイト文字が入っている場合、?や&といったパラメーターがある場合等）
  Fixed: Fixed a bug that URL is not converted in "Convert URL line" on the "Editor" tab of the setting screen.
* 設定画面の「エディタ」タブの「パラメータ（表記例）」を修正。
  Modified: Added a description example of "Parameter" on the "Editor" tab of the setting screen.
* 設定画面に「管理者」タブを追加。※一般に解放していません。
  Added: Added "Admin" tab to the setting screen. (Available only in admin mode)
* 設定画面に「管理者」タブに「WP-Cronの設定」を追加。※一般に解放していません。
  Added: Added "WP-Cron Settings" setting to "Admin" tab of the setting screen. (Available only in admin mode)
* 設定画面の「上級者向け」タブ内に「上級者向けの設定」を追加。
  Added: Added "Senior Settings" setting to "Advanced" tab of the setting screen.
* 設定画面の「上級者向け」タブ内に「拡張機能の設定」を追加。
  Added: Added "Extension Settings" setting to "Advanced" tab of the setting screen.
* 設定画面の「上級者向け」タブ内の「上級者向けの設定」から一部項目を「上級者向けの設定」へ移動。
  Modified: Moved any setting item on the setting screen from the "Advanced Settings" to the "Senior Settings".
* 設定画面の「上級者向け」タブ内の「上級者向けの設定」から一部項目を「拡張機能の設定」へ移動。
  Modified: Moved any setting item on the setting screen from the "Advanced Settings" to the "Extension Settings".
* 設定画面の「マルチサイト」タブに設定が変更出来ない旨のメッセージを追加。
  Added: Added a message to the "Multi-site" tab of the setting screen that it cannot be changed.
* 設定画面の「マルチサイト」タブに「テーブル名称」を追加。
  Added: Added "Multi-site" tab "Table name" on the setting screen.
* 設定画面の「マルチサイト」タブに「サブサイトへのリンク」の設定を追加。
  Added: Added the setting of "Multi-site" tab "Link to sub-site" on the setting screen.
* 設定画面の「初期化タブ」「調査モード」「管理者モード」「開発者モード」を変更した際、「変更を保存」を押さなくても即時反映されるように修正。
  Modified: Changed some check items on the setting screen to reflect immediately.
* 設定画面の「設定を保存した日時」の表示項目を「上級者向け」タブから「基本」タブへ移動。
  Modified: Moved the display item of "Saved Time" on the setting screen from the "Advanced" tab to the "Basic" tab.
* カード管理画面のレイアウトを一部見直し。
  Modified: Review the layout of the manager screen.
* カード管理画面の見出しに「カードボックス」のアイコンを追加。
  Added: Added an icon to the heading of the mamager screen.
* カード管理画面の検索窓の左側に「虫眼鏡」のアイコンを追加。
  Added: Added an icon to the search-box of the mamager screen.
* カード管理画面のスタイルシートを直書きしていたものをCSSファイルへ移動。
  Modified: Move stylesheets from HTML to CSS.
* カード管理画面の右上に「設定画面」へのリンクを追加。
  Added: Added a link to the settings screen at the top right of the management screen.
* カード管理画面のエディタ画面のレイアウトを一部見直し。
  Modified: Review the layout of the editor screen of the card management screen.
* カード管理画面のエディタ画面に見出しを追加。
  Modified: Added heading to the editor screen of the card management screen.
* カード管理画面のエディタ画面の上側にも保存とキャンセルのボタンを追加。
  Modified: Added save and cancel buttons at the top of the editor screen of the card management screen.
* カード管理画面の一覧のサムネイル画像をクリックすると新しいタブで画像を表示するように変更。
  Modified: Click the thumbnail image in the list on the management screen to display the image in a new tab.
* カード管理画面の一覧のURLの先頭に、アクセス不能な場合「⛔（通行禁止）」のアイコンを表示するように変更。
  Modified: Added an icon at the beginning of the URL of the management screen list when inaccessible.
* カード管理画面の一覧のURLの先頭に、アクセス不能だがエラー無視の設定をしている場合「⚠（警告）」のアイコンを表示するように変更。
  Modified: Added an icon at the beginning of the URL in the list of management screens when "Inaccessible but ignored"
* カード管理画面の一覧のURLの先頭のアイコンの大きさを9pxから16pxへ変更。
  Modified: Change the size of the icon at the beginning of the URL in the management screen list.
* カード管理画面の一覧の結果コードの数字にポインタを合わせると結果メッセージがツールチップで表示されるように変更。
  Modified: Added tooltip to the result code in the list of management screens.
* カード管理画面の一覧のクイックメニューを選択中の行のみに表示されるように変更。
  Modified: Changed the display method of the quick menu of the list on the management screen.
* カード管理画面の一覧の下側にもページナビを追加。
  Modified: Page navigation has also been added to the bottom of the list on the card management screen.
* カード管理画面のエディタの結果コードの横に結果メッセージを表示するように変更。
  Modified: Add a message next to the result code in the editor on the admin screen.
* カード管理画面のエディタに結果コードがエラーでもリンク切れ状態にしない「エラーを無視」の項目を追加。(Thanks @wordmovies2018)
  Added: Added "Ignore error" setting to the editor of the management screen.
* カード管理画面の「ファイルメニュー」の見出しに「書類」のアイコンを追加。
  Modified: Added an icon to the file menu of the management screen.
* カード管理画面の「エクスポート」の出力項目を現在の記録項目に合わせて修正。
  Modified: Change the output items of the export of the management screen.
* require_once を include に変更。
  Modified: Replace require_once with include.

= 2.4.3.3 =
* 設定画面の「上級者向け」タブにある「URLの補正」を削除。（不具合が解消されなかったため。）(Thanks @tannpura00)
  Removed: Deleted "URL correction" setting from "Advanced" tab of the setting screen. (It wasn't the cause of the problem.)
* URL内の % が %25 とエンコードされていた際に正しいページにリンクがされなかった不具合を修正。(Thanks @tannpura00)
  Fixed: Fixed a bug that the correct page was not linked when "%" in the URL was encoded as "%25".

= 2.4.3.2 =
* 設定画面の「上級者向け」タブに「URLの補正」の設定を追加。パスに日本語を使用している場合等にリンクが大量にリンク切れになる不具合に対応。(Thanks @tannpura00)
  Added: Added "URL correction" setting to "Advanced" tab of the setting screen.

= 2.4.3.1 =
* 設定画面で「変更を保存」を実行した際、一部の設定値が正常に保存されていなかった不具合を修正。
  Fixed: Fixed a bug that some setting values ​​were not saved normally when "Save changes" was executed on the setting screen.
* 設定画面の「マルチサイト」タブの、表示が一部乱れていた不具合を修正。
  Fixed a bug that the display on the "Multi-site" tab of the setting screen was partially disturbed.
* 設定画面の「エディター」タブの、「外部リンクのみ」にチェックを付けた場合にPHPエラーが出てしまう不具合を修正。(Thanks @daigamax)
  Fixed a bug that PHP error appears when "External Link Only" is checked on the "Editor" tab of the setting screen.

= 2.4.3 =
* WordPress 5.6.2 での動作確認。
  Tested: Compatible with WordPress 5.6.2.
* ドキュメント（readme.txt）修正。
  Modified: Modify "readme.txt".
* ショートコードのURLパラメータの解釈方法を修正。(Thanks @longer_n)
  Fixed: Fixed interpretation of shortcode URL parameters.
* 外部リンクで指定された画像URLが存在しない場合、警告エラーが発生する不具合を修正。(Thanks @miya_gal_ossan)
  Fixed: Fixed a bug that the image URL specified on the external link does not exist.
* 内部リンクで存在しないカテゴリページを指定した場合、警告エラーが発生する不具合を修正。
  Fixed: Fixed a bug that a warning error occurs when a category page that does not exist is specified in the internal link.
* 内部リンクで存在しないタグページを指定した場合、警告エラーが発生する不具合を修正。
  Fixed: Fixed a bug that a warning error occurs when a tag page that does not exist is specified in the internal link.
* 内部リンクでサムネイルが無い場合、デフォルト画像が出てしまう不具合を修正。
  Fixed: Fixed a bug when there is no thumbnail in the internal link.
* 内部リンクで指定された記事が存在しない場合、警告エラーが発生する不具合を修正。
  Fixed: Fixed a bug when the article specified by the internal link does not exist.
* 相対URLが指定されている場合、分析に失敗して警告エラーが発生する不具合を修正。（Ver.2.1.9から発生）
  Fixed: Fixed a bug that an error occurs when converting relative URLs.
* リンク先のサーバーが見つからない場合にリンクを設定しないように修正。
  Fixed: Fixed not to set the link when the linked server cannot be found.
* <HEAD>や<BODY>といったHTMLタグが無いサイトでもTITLEタグやMETAタグを取得するように修正。
  Fixed: Fixed to get TITLE tag and META tag even on sites without HTML tags such as <HEAD> and <BODY>.
* 外部リンクのサムネイルのキャッシュ画像のURLからスキーム（http:やhttps:）を省略するように変更。(Thanks @miya_gal_ossan)
  Modified: Modified to omit the scheme (http: and https:) from the URL of the cache image of the thumbnail of the external link.
* 設定画面から国際化ドメイン（IDNA ASCIIドメイン）に対応する設定を削除。
  Removed: Removed the setting corresponding to the internationalized domain (IDNA ASCII domain) from the setting screen.
* 設定に関わらず国際化ドメインに対応。
  Modified: Supports internationalized domains regardless of settings.
* 設定画面で使うカラーピッカーをWordPressの物からHTML5の物に変更。
  Modified: Modified the color-picker used on the setting screen from WordPress to HTML5.
* 設定画面の「相対指定URL」の設定項目を「上級者向け」タブから「リンク先の検査」タブへ移動。
  Modified: Moved the setting item of "Relative URL" on the setting screen from the "Advanced" tab to the "Link Check" tab.
* 設定画面の「基本」タブに、「更新履歴」の表示を追加。
  Added: Added the display of "Changelog" to the "Basic" tab of the setting screen.
* 設定画面の「リンク先の検査」タブに「相対指定URL」の設定を追加。
  Added: Added "Relative URL" setting to "Link Check" tab of the setting screen.
* 設定画面の「Web API」タブ、「画像」タブ、「CSS」タブを削除。
  Removed: Removed "Web API" tab, "Image" tab, and "CSS" tab on the setting screen.
* 設定画面に「その他」タブを追加。（「Web API」「画像」「CSS」の内容を統合）
  Added: Added "etc." tab to the setting screen. (Integrates the contents of "Web API", "Image" and "CSS")
* 設定画面の「外部リンク」タブに「サムネイルサイズ」の設定を追加。
  Added: Added "Thumbnail Size" setting to "External Link" tab of the setting screen.
* 設定画面の「内部リンク」タブに「サムネイルサイズ」の設定を追加。(Thanks @in_seki on Twitter)
  Added: Added "Thumbnail Size" setting to "Internal Link" tab of the setting screen.
* 設定画面の「リンクチェック」タブの「相対指定URL」の初期選択をチェック有りに変更。
  Modified: Modified the initial selection of "Relative URL" on the "Link Check" tab of the setting screen to Checked.
* 設定画面に「マルチサイト」タブを追加。（マルチサイト設定時のみ表示されます）(Thanks @i_three_miz on Twitter)
  Added: Added "Multi Site" tab to the setting screen. (Displayed only when multi-site is set)
* 設定画面の「上級者向け」タブに「圧縮」の設定を追加。
  Added: Added settings of "Compress" to the "Advanced" tab of the setting screen.
* 設定画面の「上級者向け」タブに「ファイルメニュー」の設定を追加。
  Added: Added settings of "File Menu" to the "Advanced" tab of the setting screen.
* 設定画面の「上級者向け」タブに「初期化タブ」を表示する設定を追加。
  Added: Added a setting to display the "Initialization Tab" in the "Advanced" tab of the setting screen.
* 設定画面の「上級者向け」タブから「実行時間の表示」の設定を削除。
  Removed: Removed "Display Execution Time" setting from "Advanced" tab of the setting screen.
* 設定画面の「上級者向け」タブに「調査モード」の設定を追加。（通常は使用しないでください）
  Added a setting to display the "Survey Mode" in the "Advanced" tab of the setting screen. (Do not use normally)
* 設定画面の「上級者向け」タブに「管理者モード」の設定を追加。※一般に解放していません。
  Added a setting to display the "Administrator Mode" in the "Advanced" tab of the setting screen. (Do not use normally as it can be set to incapacitate.)
* 設定画面の「上級者向け」タブに「開発者モード」の設定を追加。※一般に解放していません。
  Added a setting to display the "Developer Mode" in the "Advanced" tab of the setting screen. (Do not use normally as it can be set to incapacitate.)
* 設定画面の「上級者向け」タブの「URLによるAMP判断」を非推奨に変更。
  Modified: Modified "AMP judgment by URL" on the "Advanced" tab of the setting screen to Deprecated.
* 設定画面で「変更を保存」を押した際、開いているタブをそのままにするように変更。
  Modified: Modified to leave open tabs when pressing "Save Changes" on the settings screen.
* 設定画面の「初期化」タブを非表示に変更。（「上級者向け」タブから表示させることができます。）
  Modified: Modified "Initialize" tab of the setting screen to hide. (It can be displayed from the "Advanced" tab.)
* 設定画面の「初期化」タブに「プラグインの再起動」を追加。※一般に解放していません。
  Added a setting to button "Restart Plugin" in the "Initialize" tab of the setting screen. (Available only in admin mode)
* 設定画面の「初期化」タブに「初期化時の例外」の設定を追加。※一般に解放していません。
  Added a setting to "Initialization Exception" in the "Initialize" tab of the setting screen. (Available only in admin mode)
* カード管理画面の一覧に全件表示されていたものを、1ページに10個表示されるように修正。
  Fixed: Fixed all the items displayed in the list on the card management screen so that 10 items are displayed on one page.
* カード管理画面の外部リンクのURLにリンクを追加。
  Added: Added a link to the URL of the external link on the card management screen.
* カード管理画面にインポート・エクスポートの機能を追加。（設定画面で「ファイルメニュー」を有効にする必要があります。）
  Added: Added import / export function to the card management screen. (You need to enable "File Menu" on the settings screen.)
* カード管理画面のドメイン名の表示を国際化ドメインに対応。
  Modified: The domain name display on the card management screen is compatible with internationalized domains.
* URL表示に<CITE>タグを使用しないように変更。（URLがイタリック表示では無くなります。）
  Modified: Modified not to use <CITE> tag in URL. (The URL is no longer displayed in italics.)

= 2.4.2.2 =
* 追加するCSSファイルが呼び出せていなかった不具合を修正。(Thanks @miya_gal_ossan on Twitter)
  Fixed: Fixed a bug that the additional CSS function does not work properly.
* CSSのURLが正しくなくてスタイルシートが適用されない不具合を修正。(Thanks @longer_n on Twitter)
  Fixed a bug that CSS could not be read correctly.

= 2.4.2.1 =
* 外部リンクのサムネイルの取得に失敗して警告エラーが発生する不具合を修正。（Ver.2.4.1から発生）(Thanks ‎@kotobatoad)
  Fixed: Fixed a bug that an error occurs when thumbnail images on external links fail.
* カード管理画面で外部リンクのサムネイルが表示されない不具合を修正。（Ver.2.4.2から発生）
  Fixed: Fixed a bug that thumbnail images of external links are not displayed on the card management screen.

= 2.4.2 =
* 設定画面の「画像」タブにて、指定した画像サイズによっては警告エラーが発生する不具合を修正。(Thanks @miya_gal_ossan on Twitter)
  Fixed: Fixed image size error on settings screen.
* 外部リンクで指定された画像URLが応答しない場合、タイムアウトまで約60秒待ってしまう不具合を修正。(Thanks @miya_gal_ossan on Twitter)
  Fixed: Fixed a bug that the image URL specified on the external link does not respond.
* 設定画面で使用するjQueryを修正。
  Fixed: Review and fix jQuery on the settings screen.
* ビジュアル エディタ（クラシック エディタ）で使用するjQueryを修正。
  Fixed: Review and fix jQuery in classic editor.
* アンインストール時にファイルが残ったままだったのを削除するように修正。
  Fixed: Fixed to delete the directory that remained at the time of uninstallation.
* ドキュメント（readme.txt）修正。
  Modified: Modify "readme.txt".
* 設定画面の項目名を一部修正。
  Modified: Corrected the item name on the setting screen.
* 設定画面の「配置」タブの「BLOCKQUOTEの設定」を非推奨に変更。
  Modified: Modified blockquote setting on the setting screen to deprecated.
* 設定画面の「外部リンク」タブの「rel="nofollow" の設定」を非推奨に変更。
  Modified: Mofified nofollow setting on the setting screen to deprecated.
* 設定画面の「エディタ」タブにて、ショートコード1を設定しなくても設定エラーとしないように変更。
  Modified: Modified shortcode to optional on the setting screen.
* 設定画面の設定保存等のメッセージを閉じられるように変更。
  Modified: Modified to close the message on the setting screen.
* 設定画面で内容が変更されていない場合、設定の保存は行わずスタイルシートの再生成のみ行うように変更。
  Modified: Modified not to save if there is no change on the setting screen.
* 設定画面でのカラーピッカーを表示したときに、レイアウトが崩れるのを修正。
  Modified: Modified the behavior of color-picker on the setting screen.
* カード管理画面の編集画面に表示される日時のフォーマットをWordPressの一般設定に合わせるように変更。
  Modified: Modified date format on card management screen.
* 設定を更新した時、ブラウザのキャッシュに関係無くCSSファイルを読み直すように修正。
  Modified: Modified to read the CSS file again when updating the settings.
* facebookのシェア数が取得出来ないため、WebAPIの利用を停止。
  Modified: Suspended because the number of shares on facebook cannot be obtained.
* 設定画面の「エディタ」タブの「ショートコード実行」の初期選択をチェック有りに変更。
  Modified: Modified the initial settings.
* 当バージョンへバージョンアップした際、「ショートコード実行」をチェック有りに強制変更。
  Modified: Forcibly set "Do shortcode" to on.
* 「ショートコード実行」がオンになっている場合の動作を「URL行の変換」もしくは「リンク行の変換」がオンになっている場合のみに変更。
  Modified: Modified the condition of "Do shortcode".
* 設定画面に「CSS」タブを追加。
  Added: Added "CSS" tab to the setting screen.
  設定画面の「エラー」タブにて、URL指定エラーのある記事の行へのリンクを追加。
  Added: Added a link to an article with an error on the settings screen.
* 設定画面の「外部リンク」タブに、「rel="noopener"」の設定を追加。
  Added: Added settings of "rel="noopener" to the "External Link" tab of the setting screen.
* 設定画面に「画像」タブを追加。
  Added: Added "Image" tab to the setting screen.
* 設定画面の「画像」タブに、キャッシュ画像のディレクトリ名と使用量の表示を追加。
  Added: Added display of cache image directory name and usage to the "Image" tab of the setting screen.
* 設定画面の「画像」タブに、キャッシュ画像のディレクトリURLの表示を追加。
  Added: Added display of cache image directory URL to the "Image" tab of the setting screen.
* 設定画面の「上級者向け」タブに、「URLエラーを非表示」の設定を追加。（URLエラー時にWordPress管理画面にエラー表示をしなくなります。）
  Added: Added settings of "Hide URL Error" to the "Advanced" tab of the setting screen.(The error will not be displayed on the WordPress administration screen when a URL error occurs.)
* 設定画面の「上級者向け」タブに、設定を保存した日時の表示を追加。
  Added: Added display of "Saved Time" to the "Advanced" tab of the setting screen.

= 2.4.1 =
* WordPress 5.6 での動作確認。
  Tested: Compatible with WordPress 5.6.
* 外部リンクの画像をキャッシュするときのサイズを選択出来る機能を追加。(Thanks @peacediner on Twitter)
  Added: Added settings to allow you to choose the size when caching images from external link.

= 2.4.0 =
* WordPress 5.5.3 での動作確認。
  Tested: Compatible with WordPress 5.5.3.
* 記事では無いページでURL指定エラーが発生する不具合を修正。（ポストIDが取得出来ないURLを除外）(Thanks @rindark on Twitter)(Thanks @Rina_sendai on Twitter)
  Fixed: Suppressed incorrect error display.
* サブディレクトリ型マルチサイトの判定において警告エラーが発生する不具合を修正。
  Fixed: Suppressed incorrect error display.



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

Displays using the "Google-favicon API" to get the favicon. This can be changed.

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
