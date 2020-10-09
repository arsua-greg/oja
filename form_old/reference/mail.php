<?php header("Content-Type:text/html;charset=utf-8"); ?>
<?php
#######################################################################################
##
#  PHPメールプログラム
#　改造や改変は自己責任で行ってください。
#
#  今のところ特に問題点はありませんが、不具合等がありましたら下記までご連絡ください。
#  MailAddress: support@kens-web.com
#  name: kenji.numata
#  HP: http://www.kens-web.com/
#  超重要！！サイトでチェックボックスを使用する場合のみですが。。。
#  チェックボックスを使用する場合はinputタグに記述するname属性の値を必ず配列の形にしてください。
#  例　name="当サイトをしったきっかけ[]"  として下さい。
#  nameの値の最後に[と]を付ける。じゃないと複数の値を取得できません！
##
#######################################################################################

//-----------------以下基本設定　必ず設定してください。--------------------------

//サイトのトップページのURL　※送信完了後に「トップページへ戻る」ボタンが表示されますので
$site_top = "http://ojla.jp/";

// このPHPファイルの名前 ※ファイル名を変更した場合は必ずここも変更してください。
$file_name ="mail.php";

// メールを受け取るメールアドレス(複数指定する場合は「,」で区切ってください)
$to = "contact@ojla.jp";

// 送信完了後に自動的に指定のページに移動する(する=1, しない=0)
// 0にすると、送信終了画面が表示されます。
$jpage = 0;

// 送信完了後に表示するページ（上記で1を設定した場合のみ）
$next = "http://www.xxx/xxxxx";

//--------- 以下は必要に応じて設定してください --------------

// 送信されるメールのタイトル（件名）
$sbj = "ホームページからの問い合わせ";

// 送信確認画面の表示(する=1, しない=0)
$chmail = 1;

// 差出人は、送信者のメールアドレスにする(する=1, しない=0)
// する場合は、メール入力欄のname属性の値を「Email」にしてください。
$from_add = 1;

// 差出人に送信内容確認メールを送る(送る=1, 送らない=0)
// 送る場合は、メール入力欄のname属性の値を「Email」にしてください。
//また差出人に送るメール本文の文頭に「○○様」と表示させるには名前入力欄のname属性を name="名前" としてください
$remail = 1;

// 差出人に送信確認メールを送る場合のメールのタイトル（上記で1を設定した場合のみ）
$resbj = "送信ありがとうございました";


// 必須入力項目を設定する(する=1, しない=0)
$esse = 1;

/* 必須入力項目(入力フォームで指定したname属性の値を指定してください。（上記で1を設定した場合のみ）
日本語はシングルクォーテーションで囲んで下さい。複数指定する場合は「,」で区切ってください)*/
$eles = array('お名前','苗字','メールアドレス','お問い合わせ内容');


//--------------------- 基本設定ここまで -----------------------------------

// 以下の変更は知識のある方のみ自己責任でお願いします。

//--------------------- デバッグ -----------------------------------
// 自動的に初期化しないバージョンのための修正
$errm = "";
$err_message = "";
$rebody = "";

//--------------------- 本体 -----------------------------------
$sendmail = 0;
foreach($_POST as $key=>$val) {
  if($val == "submit") $sendmail = 1;
}

// 文字の置き換え
$string_from = "＼";
$string_to = "ー";

// 未入力項目のチェック
if($esse == 1) {
  $flag = 0;
  $length = count($eles) - 1;
  foreach($_POST as $key=>$val) {
    $key = strtr($key, $string_from, $string_to);
    if($val == "submit") ;
    else {
      for($i=0; $i<=$length; $i++) {
        if($key == $eles[$i] && empty($val)) {
          $errm .= "<FONT color=#ff0000>「".$key."」は必須入力項目です。</FONT><br>\n";
          $flag = 1;
        }
      }
    }
  }
  foreach($_POST as $key=>$val) {
    $key = strtr($key, $string_from, $string_to);
    for($i=0; $i<=$length; $i++) {
      if($key == $eles[$i]) {
        $eles[$i] = "check_ok";
      }
    }
  }
  for($i=0; $i<=$length; $i++) {
    if($eles[$i] != "check_ok") {
      $errm .= "<FONT color=#ff0000>「".$eles[$i]."」が未選択です。</FONT><br>\n";
      $eles[$i] = "check_ok";
      $flag = 1;
    }
  }
  if($flag == 1){
    htmlHeader();
?>
<!--- 未入力があった時の画面 --- 開始 --------------------->
           
<p class="color-a">未入力・未選択項目があります。入力画面に戻り、訂正してください。</p>

<?php echo $errm; ?>

<div class="inline-center">
<input class="return" type="button" value="前画面に戻る" onClick="history.back()">
</div>
<!--- 終了 --->

<?php
    htmlFooter();
    exit(0);
  }
}
// 届くメールのレイアウトの編集

$body="「".$sbj."」からメールが届きました\n\n";
$body.="■□■□■□■□■□■□■□■□■□■□■□■□■□\n\n";
foreach($_POST as $key=>$val) {
  $key = strtr($key, $string_from, $string_to);

  //※numata追記　チェックボックス（配列）の場合は以下の処理で複数の値を取得するように変更した。　HTML側のname属性の値に[と]を追加する。
  $out = '';
  if(is_array($val)){
  foreach($val as $item){
  $out .= $item . ',';
  }
  if(substr($out,strlen($out) - 1,1) == ',') {
  $out = substr($out, 0 ,strlen($out) - 1);
  }
 }
    else {
        $out = $val;
}
  //チェックボックス（配列）追記ここまで
  if(get_magic_quotes_gpc()) { $out = stripslashes($out); }
  if($out == "submit") ;
  else $body.="【 ".$key." 】 ".$out."\n";
}
$body.="\n■□■□■□■□■□■□■□■□■□■□■□■□■□\n";
$body.="送信された日時：".date( "Y/m/d (D) H:i:s", time() )."\n";
$body.="送信者のIPアドレス：".$_SERVER["REMOTE_ADDR"]."\n";
$body.="送信者のホスト名：".getHostByAddr(getenv('REMOTE_ADDR'))."\n";
//--- 終了 --->


if($remail == 1) {
//--- 差出人への送信確認メールのレイアウト

if(isset($_POST['お名前'])){ $rebody = "{$_POST['苗字']}{$_POST['お名前']} 様\n\n";}
$rebody.="お問い合わせありがとうございました。\n";
$rebody.="早急にご返信致しますので今しばらくお待ちください。\n\n";
/*
$rebody.="送信内容は以下になります。\n\n";
$rebody.="■□■□■□■□■□■□■□■□■□■□■□■□■□\n\n";
foreach($_POST as $key=>$val) {
  $key = strtr($key, $string_from, $string_to);


  //※numata追記　チェックボックス（配列）の場合は以下の処理で複数の値を取得するように変更　HTML側のname属性の値に[と]を追加する。
  $out = '';
  if(is_array($val)){
  foreach($val as $item){
  $out .= $item . ',';
  }
  if(substr($out,strlen($out) - 1,1) == ',') {
  $out = substr($out, 0 ,strlen($out) - 1);
  }
 }
    else {
        $out = $val;
}
  //チェックボックス（配列）追記ここまで
  if(get_magic_quotes_gpc()) { $out = stripslashes($out); }
  if($out == "submit") ;

  else $rebody.="【 ".$key." 】 ".$out."\n";
}
$rebody.="\n■□■□■□■□■□■□■□■□■□■□■□■□■□\n\n";
*/
$rebody.="送信日時：".date( "Y/m/d (D) H:i:s", time() )."\n\n";
$rebody.="----------------------------------------------\n";
$rebody.="〒547-0015 大阪市平野区長吉長原西2-2-12\nTEL : 06-6707-2227 E-MAIL contact@ojla.jp\nURL：http://www.ojia.jp\n";
$rebody.="----------------------------------------------\n";
$reto = $_POST['メールアドレス'];
$rebody=mb_convert_encoding($rebody,"JIS","utf-8");
$resbj="=?iso-2022-jp?B?".base64_encode(mb_convert_encoding($resbj,"JIS","utf-8"))."?=";
$reheader="From: $to\nReply-To: ".$to."\nContent-Type: text/plain;charset=iso-2022-jp\nX-Mailer: PHP/".phpversion();

}

$body=mb_convert_encoding($body,"JIS","utf-8");
$sbj="=?iso-2022-jp?B?".base64_encode(mb_convert_encoding($sbj,"JIS","utf-8"))."?=";
if($from_add == 1) {
  $from = $_POST['メールアドレス'];
  $header="From: $from\nReply-To: ".$_POST['メールアドレス']."\nContent-Type:text/plain;charset=iso-2022-jp\nX-Mailer: PHP/".phpversion();
} else {
  $header="Reply-To: ".$to."\nContent-Type:text/plain;charset=iso-2022-jp\nX-Mailer: PHP/".phpversion();
}
if($chmail == 0 || $sendmail == 1) {
  mail($to,$sbj,$body,$header);
  if($remail == 1) { mail($reto,$resbj,$rebody,$reheader); }

}
else { htmlHeader();
?>

<!-- 送信確認画面のレイアウト　-->

           <ul class="bread">
			<li>情報の入力</li>
			<li class="here">情報の確認</li>
			<li>送信完了</li>
			</ul>

<p>入力情報を確認し、送信ボタンを押してください。</p>
<form action="<?php echo $file_name; ?>" method="POST">
<?php echo $err_message; ?>
<dl>
<?php
foreach($_POST as $key=>$val) {
  $key = strtr($key, $string_from, $string_to);

  //※numata追記　チェックボックス（配列）の場合は以下の処理で複数の値を取得するように変更　HTML側のname属性の値にも[と]を追加する。
  $out = '';
  if(is_array($val)){
  foreach($val as $item){
  $out .= $item . ',';
  }
  if(substr($out,strlen($out) - 1,1) == ',') {
  $out = substr($out, 0 ,strlen($out) - 1);
  }
 }
    else {
        $out = $val;
}
  //チェックボックス（配列）追記ここまで

  if(get_magic_quotes_gpc()) { $out = stripslashes($out); }
  $out = htmlspecialchars($out);
  $key = htmlspecialchars($key);
  print("<dt>".$key."</dt><dd>".$out);
?>
<input type="hidden" name="<?php echo $key; ?>" value="<?php echo $out; ?>">
<?php
  print("</dd>\n");
}
?>
</dl>
<ul class="undernav clearfix">
<li><input type="hidden" name="mail_set" value="submit"><input class="return" type="button" value="前画面に戻る" onClick="history.back()"></li>
<li><input class="send" type="submit" value="送信する"></li>
</ul>
</form>


<?php htmlFooter(); } if(($jpage == 0 && $sendmail == 1) || ($jpage == 0 && ($chmail == 0 && $sendmail == 0))) { htmlHeader(); ?>


<!-- 送信終了画面のレイアウト-->

<ul class="bread">
	<li>情報の入力</li>
	<li>情報の確認</li>
	<li class="here"><p>送信完了</p></li>
</ul>


<h4>メール送信完了</h4>
<p>お問い合わせありがとうございました。</p>

<p><a href="../../index.html" class="icon-link">トップに戻る</a></p>

<?php htmlFooter(); } else if(($jpage == 1 && $sendmail == 1) || $chmail == 0) { header("Location: ".$next); } function htmlHeader() { ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="ja" xml:lang="ja">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>お問い合わせフォーム｜留学するなら大阪にある国籍不問の日本語学校「大阪日本語アカデミー」</title>
	<meta name="description" content="留学するなら大阪にある国籍不問の日本語学校、大阪日本語アカデミー" />
	<meta name="keywords" content="日本語,学校,大阪,留学,進学,スクール,語学,教室" />
	<meta http-equiv="Content-Style-Type" content="text/css" />
	<meta http-equiv="Content-Script-Type" content="text/javascript" />

	<link href="../../css/common.css" rel="stylesheet" type="text/css" />
	<link href="../../css/reset.css" rel="stylesheet" type="text/css" />
	<link href="../../css/navigation.css" rel="stylesheet" type="text/css" />
	<link href="../../css/form.css" rel="stylesheet" type="text/css" />

	<link rel="index" href="http://ojla.jp/" />
	<link rel="canonical" href="http://ojla.jp/" />
	<link rel="shortcut icon" type="image/x-icon" href="../../images/common/favicon.ico" />
	
	<script language="JavaScript" type="text/javascript" src="../../js/library/jquery-1.6.2.min.js"></script>
	<script language="JavaScript" type="text/javascript" src="../../js/library/jVal.js"></script>
	<script language="JavaScript" type="text/javascript" >
	
	<!--
	$(function(){
	$("#ref_form").submit(function(){
		if ( $('#ref_form').jVal({style:'blank',padding:15,border:0,wrap:false}) )
		  return true;
	  return false;
		});
	});
	//-->
</script>

</head>
<body id="reference">
<div id="wrapper">

	<!--	Header		-->
	<div id="header">
	
		<p class="logo" id="pagetop"><a href="http://www.oja.jp/" title="留学して進学を目指すなら大阪日本語アカデミー"><img src="../../images/common/logo.gif" width="223" height="58" title="OJA Osaka Japanese Language Academy" /></a></p>
		<p class="caption">留学するなら大阪にある国籍不問の日本語学校<br /><strong>「大阪日本語アカデミー」</strong></p>
		
		
		
		<!--	luang navigation	-->
		
		<ul class="luangnavigation">
		<li><a href="http://oja.jp/" title="OJA-Japanese"><img src="../../images/common/ln-jp.gif" />Japanese</a></li>
		<li><a href="http://oja.jp/ch/"><img src="../../images/common/ln-cn.gif" />Chinese</a></li>
		<li><a href="http://oja.jp/en/"><img src="../../images/common/ln-us.gif" />English</a></li>
		<li><a href="http://oja.jp/vi/"><img src="../../images/common/ln-vn.gif" />Vietnamese</a></li>
		</ul>
		
		<!--	subnavigation	-->
		
		<ul class="subnavigation">
		<li class="tel"><img src="../../images/common/header-tel.gif" alt="お電話問い合わせ番号06-6707-2227(月)～(金)AM9時～PM5時" width="304" height="36" /></li>
		<li class="input-shiryo"><a href="../shiryou/index.html" title="資料請求フォーム、気軽にご請求ください。">資料請求フォーム</a></li>
		<li class="input-contact"><a href="index.html" title="大阪日本語アカデミーにメールフォームから問い合わせる">お問い合わせメールフォーム</a></li>
		</ul>
	
	</div>
		
	<!--	glovalnavigetionl	-->
	<div id="glovalnav">

		<ul>
			<li class="menu01"><a href="../../index.html" title="大阪日本語アカデミー">ホーム</a></li>
            <li class="menu02"><a href="../../school.html" title="学校案内">学校案内</a></li>
            <li class="menu03"><a href="../../feature/index.html" title="学校の特徴">学校の特徴</a></li>
            <li class="menu04"><a href="../../curriculum.html" title="カリキュラム">カリキュラム</a></li>
            <li class="menu05"><a href="../../recruitment.html" title="募集要項">募集要項</a></li>
            <li class="menu06"><a href="../../schedule.html" title="学校生活">学校生活</a></li>
            <li class="menu07"><a href="../../access.html" title="地図・交通アクセス">交通アクセス</a></li>
		</ul>
	
	</div>
	
	<!--	#container		-->
	<div id="container">
		
		<h1>お問い合わせフォーム</h1>
		
		<!--	#Main		-->
		<div id="main">
			
			
			<?php } function htmlFooter() { ?>
			
          
		 <!--	#Main End		-->
		</div>
		
		<hr id="main-foot" />

		
	<!--	#Container End		-->
	</div>
	
	<!--	#footer		-->
	<div id="footer">
	
		<div class="col-left">
	
		<h4>お問い合わせ</h4>
		
		<address>
			<p>547-0015 大阪市平野区長吉長原西2-2-12</p>
			<p>TEL：06-6707-2227 E-mail：<a href="mailto:contact@ojla.jp" class="icon-link">contact@ojla.jp</a></p>
			<p>URL：http://www.ojia.jp/</p>
		</address>
		
		<h4>Contact us </h4>
		
		<address>
			<p>12-2-2 nagaharanishi nagayoshi hiranoku ,Osaka City,Osaka 547-0015 Japan</p>
			<p>TEL：+81-6-6707-2227 E-mail：<a href="mailto:contact@ojla.jp" class="icon-link">contact@ojla.jp</a></p>
			<p>URL：http://www.ojia.jp/</p>
		</address>
		
		</div>
		
		<div class="col-right">
		
			<ul class="footernav">
			<li><a href="../../policy.html" title="プライバシーポリシー">プライバシーポリシー</a></li>
			<li><a href="../../sitemap.html" title="サイトマップ">サイトマップ</a></li>
			</ul>
			
			<ul class="banner-list">
			<li><a href="../../guide-around.html" title="学校周辺案内"><img src="../../images/common/footer-banner-syuhen.gif" alt="学校周辺案内" width="270" height="60" /></a></li>
			<li><a href="../../guide-osaka.html" title="大阪の観光名所"><img src="../../images/common/footer-banner-kankou.gif" alt="大阪の観光名所" width="270" height="60" /></a></li>
			
			
			</ul>
		
		
		</div>
		
		 <p class="copyright">Copyright (c) 2012 Osaka Japanese Language Academy. All Rights Reserved.</p>
		
	
	</div>

<!--	Wrapper End		-->
</div>

<!--	GoogleTag		-->

<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-29849503-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>

</body>
</html>


<?php } ?>