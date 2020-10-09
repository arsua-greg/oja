$config{'about'} = 'Mailform Pro 4.0.9';

## 確認画面のタイプ
## 0: オーバーレイ / 1:フラット / 2: システムダイアログ
$config{'ConfirmationMode'} = 1;

## sendmailのパス
#$config{'sendmail'} = 'C:\sendmail\sendmail.exe';
$config{'sendmail'} = '/usr/sbin/sendmail';

## フォームの送信先
push @mailto,'nabuchi@n-effect.co.jp';

## 自動返信メールの差出人名
$config{'fromname'} = '大阪日本語アカデミー';

## 自動返信メールの差出人メールアドレス
$config{'mailfrom'} = $mailto[0];

## 念のためBCC送信宛先 (解除する場合は下記1行をコメントアウト)
$config{'bcc'} = $mailto[0];

## メールの差出人を固定 (0:無効 / 1:固定)
$config{'fixed'} = 0;

## 通し番号の書式
$config{'SerialFormat'} = '<date>%04d';

## 通し番号への加算値
$config{'SerialBoost'} = 0;

## サンクスページのURL(URLかsend.cgiから見た相対パス)
$config{'ThanksPage'} = '../thanks.html?no=%s';

## 設置者に届くメールの件名
$config{'subject'} = '[ %s ] ホームページからのお問い合わせ';

## 設置者に届くメールの本文整形
$_TEXT{'posted'} = <<'__posted_body__';
<_mfp_jssemantics_>
<_mfp_date_>
お問い合せフォームより以下のメールを受付ました。
──────────────────────────
受付番号：<_mfp_serial_>
入力時間：<_mfp_input_time_>
確認時間：<_mfp_confirm_time_>
　送信元：<_mfp_referrer_>
支払金額：<_mfp_cartprice_>

<_resbody_>
──────────────────────────

##<_mfp_env_>

━━━━━━━━━━━━━━━━━━━━━━━━━━
　大阪日本語アカデミー　OJA
　547-0015 大阪市平野区長吉長原西2-2-12
　TEL：06-6707-2227 E-mail：contact@ojla.jp
　URL：http://www.oja.jp/
━━━━━━━━━━━━━━━━━━━━━━━━━━

__posted_body__

## 自動返信メールの件名 (有効にする場合は下記の行頭#を外してください。)
$config{"ReturnSubject"} = '[ %s ] お問い合せありがとうございました';

## 自動返信メールの本文
$_TEXT{'responder'} = <<'__return_body__';
<_姓_> 様
──────────────────────────

この度はお問い合せ頂き誠にありがとうございました。
改めて担当者よりご連絡をさせていただきます。

─ご送信内容の確認─────────────────
受付番号：<_mfp_serial_>
<_resbody_>
──────────────────────────

このメールに心当たりの無い場合は、お手数ですが
下記連絡先までお問い合わせください。

この度はお問い合わせ重ねてお礼申し上げます。

━━━━━━━━━━━━━━━━━━━━━━━━━━
　大阪日本語アカデミー　OJA
　547-0015 大阪市平野区長吉長原西2-2-12
　TEL：06-6707-2227 E-mail：contact@ojla.jp
　URL：http://www.oja.jp/
━━━━━━━━━━━━━━━━━━━━━━━━━━
__return_body__



####################################################
## スパム対策関連
####################################################

## Javascriptが無効の場合は送信を許可しない(1:許可しない / 0:許可する)
$config{'DisabledJs'} = 0;

## リファラードメインチェック / 有効にする場合は行頭んお#を削除
#$config{'PostDomain'} = $ENV{'HTTP_HOST'};

## 全文英語のスパム候補を除外(1:除外 / 0:除外しない)
$config{'EnglishSpamBlock'} = 1;

## リンク系スパム候補を除外(1:除外 / 0:除外しない)
$config{'LinkSpamBlock'} = 1;

## URLの送信を許可しない(1:許可しない / 0:許可する)
$config{'DisableURI'} = 0;


####################################################
## 有効期限など
####################################################

## 送信数制限
#$config{'limit'} = 100;

## 期限の書式は YYYY-MM-DD HH:MM:SS です。
## 受付開始日時
#$config{'OpenDate'} = '2013-01-01 06:21:00';

## 受付終了日時
#$config{'CloseDate'} = '2013-03-09 00:00:00';


####################################################
## アドオン(Javascriptの追加機能)
####################################################

$config{'dir.AddOns'} = './add-ons/';

@AddOns = ();
#push @AddOns,'OperationCheck.js'; ## 動作チェック
push @AddOns,'charactercheck.js'; ## 文字校正
push @AddOns,'prefcode/prefcode.js'; ## 郵便番号からの住所入力
push @AddOns,'furigana.js'; ## フリガナ(Firefox非対応)
push @AddOns,'charformat.js'; ## テキスト整形
#push @AddOns,'cart/cart.js'; ## ショッピングカート機能
push @AddOns,'phase.js'; ## 段階的入力機能
#push @AddOns,'drilldown.js'; ## ドリルダウン機能
push @AddOns,'datelist.js'; ## 日付リストの生成機能
#push @AddOns,'switching.js'; ## スイッチング機能サンプル
#push @AddOns,'prevention.js'; ## イタズラ防止
#push @AddOns,'wellcome.js'; ## (技術デモ)ウェルカムメッセージ
#push @AddOns,'speechAPI.js'; ## (技術デモ)音声入力
#push @AddOns,'WadaVoiceDemo.js'; ## (技術デモ)音声ガイダンス
#push @AddOns,'ResponsiveWeb.js'; ## レスポンシブWeb対応(要カスタマイズ)
#push @AddOns,'progress.js'; ## プログレスバー表示
#push @AddOns,'WTKConnect.js'; ## WebsiteToolKit.jsとの連動


####################################################
## モジュール(CGIの追加機能)
####################################################

@Modules = ();
#push @Modules,'MultiConfig'; ## 複数の設定ファイルを分岐させる
push @Modules,'check'; ## CGI動作環境チェック
#push @Modules,'cart'; ## ショッピングカート機能
#push @Modules,'ISO-2022-JP'; ## メールをJISで送信
#push @Modules,'HTMLMail'; ## 自動返信メールにHTMLメールを追加
#push @Modules,'CSVExport'; ## CSV保存機能
#push @Modules,'SMTP'; ## SMTP送信
#push @Modules,'SQLExport'; ## SQL発行機能
#push @Modules,'vCard'; ## vCard機能
#push @Modules,'iCal'; ## iCal連携機能
#push @Modules,'IPLogs'; ## IPログトラッキング機能
#push @Modules,'PayPal'; ## PayPal決済


####################################################
## 高度な設定的な感じのもの
####################################################

## 連続送信対応
$config{'seek'} = 0;

## 各種データを格納しているファイル

$config{'data.dir'} = './data/';

## [0] Serial, [1] InputTime, [2] ConfirmTime, [3] UniqueUser
$config{'file.data'} = "$config{'data.dir'}dat.mailformpro.cgi";

## ドロップ検知
$config{'file.drop'} = "$config{'data.dir'}dat.drop.cgi";

## jsキャッシュ
$config{'file.cache'} = "$config{'data.dir'}mfp.cache.js";

## 言語設定ファイル
$config{'lang'} = 'lang.ja';
#$config{'lang'} = 'lang.en';

## スクリプトのURL / ※基本的にここは変更しなくてOKです
$config{'uri'} = 'http://' . $ENV{'SERVER_NAME'} . $ENV{'SCRIPT_NAME'};

## Prefix
$config{'prefix'} = '_MFP';
