<?php
// +----------------------------------------------------------------------+
// | 2008/12/29                                                           |
// | ReCube Form v0.1.5 BETA                                             |
// | http://form.recube.net                                               |
// +----------------------------------------------------------------------+
// | Copyright (c) 2008 web.funkazista.com                                |
// +----------------------------------------------------------------------+
// | License                                                              |
// | Only personal use in case of is free.                                |
// | The redistribution is bad.                                           |
// +----------------------------------------------------------------------+
// | Original Author : Hiroyuki Watanabe <info@recube.net>                |
// +----------------------------------------------------------------------+
// | File Name : config.php                                               |
// +----------------------------------------------------------------------+

//宛先（送信先のアドレス）
$mailTo = "nabuchi@n-effect.co.jp";

//送信者表示（送信者のアドレス、控えとして送る際に使用）
$FromStr = "";//送信者に日本語を設定する場合に入力
$mailFrom = "";

//CC
$ccAddress = "";

//BCC
$bccAddress = "";

//タイトルタグ
$titletag = "お問い合わせ";

//実行ファイル名
$selfile = "index.php";

//accesskeyの要素、順番
$acskey = "abcdefghijklmnopqrstuvwxyz1234567890";

//フォームを囲っているDIVのCSSとDLのクラス名
$wrapClass = "wrap-form";

//必須項目のCSSクラス名
$hissClass = "hiss";
//必須項目の文字
$hissMoji = "必須";

//エラー時のCSSクラス名
$errorClass = "err";

//控えのメールを送るか（送る=1 送らない=0）
$mailReturn = 1;

//件名が項目で設定されていない場合の件名
$subject = "お問い合わせ【MAIL】";

//控えメールの場合
$resubject = "お問い合わせの控え【MAIL】";

//入力時ボタン
$inputBtn = "確認画面へ進む";
$inputBtnClass = "input-b";

//確認時ボタン
$registBtn = "送　信";
$registBtnClass = "input-b";

//戻るボタン
$returnBtn = "戻　る";
$returnBtnClass = "input-b";

//入力時メッセージ
$inputMessage = <<<EOM
<div class="message">
	必要項目を入力の上、「{$inputBtn}」ボタンを押してください。<br />
	<b class="{$hissClass}">{$hissMoji}</b> は必須項目です。
</div>
EOM;

//確認時メッセージ
$registMessage = <<<EOM
<div class="message">
	入力内容を確認していただき、「{$registBtn}」ボタンを押してください。<br />
	訂正は「{$returnBtn}」ボタンで戻って訂正ください。
</div>
EOM;

//エラー時メッセージ
$errorMessage = <<<EOM
<div class="message {$errorClass}">
	エラーが発生しています。<br />
	指示に従って、訂正ください。
</div>
EOM;

//完了時メッセージ
$endMessage = <<<EOM
<div class="message">
	<p>送信が完了しました。</p>
	<p>確認次第ご連絡差し上げますので、お待ちください。</p>
	<p><a href="index.php">TOPへ戻る</a></p>
</div>
EOM;

//送信メールヘッダー
$mailHeader = <<<EOM
お問い合わせが届きました。
-------------------------------
EOM;

//送信メールフッター
$mailFooter = <<<EOM
-------------------------------
なるべく早めに対応してください。
EOM;

//控えメールヘッダー
$mailReturnHeader = <<<EOM
このメールはお問い合わせに送信
したメッセージの控えです。
-------------------------------
EOM;

//控えメールフッター
$mailReturnFooter = <<<EOM
-------------------------------
心当たりがない場合、以下にご返
信いただくと助かります。
{$mailTo}
EOM;


?>