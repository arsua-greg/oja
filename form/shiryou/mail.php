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
$site_top = "http://oja.jp/";

// このPHPファイルの名前 ※ファイル名を変更した場合は必ずここも変更してください。
$file_name ="mail.php";

// メールを受け取るメールアドレス(複数指定する場合は「,」で区切ってください)
$to = "nabuchi@n-effect.co.jp";

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
$eles = array('お名前','苗字','メールアドレス','住所');


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
$rebody.="資料のご請求ありがとうございました。\n";
$rebody.="近日中に資料をお送りいたします。今しばらくお待ちください。\n\n";
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
$rebody.="〒547-0015 大阪市平野区長吉長原西2-2-12\nTEL : 06-6707-2227 E-MAIL contact@oja.jp\nURL：http://www.oja.jp\n";
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
<p class="alert">以下の項目をご確認ください</p>
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
<li class="here">送信完了</li>
</ul>

<div class="block">
<h4>メール送信完了</h4>
<p>資料のご請求ありがとうございました。<br />
ご記入のアドレス宛てにてメールをお送りいたしますので、ご確認ください。</p>
<p><a href="../../index.html">トップに戻る</a></p>

</div>

<?php htmlFooter(); } else if(($jpage == 1 && $sendmail == 1) || $chmail == 0) { header("Location: ".$next); } function htmlHeader() { ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/oja.dwt" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<script src="../../js-global/FancyZoom.js" type="text/javascript"></script>
<script src="../../js-global/FancyZoomHTML.js" type="text/javascript"></script>
<!-- InstanceBeginEditable name="doctitle" -->
<title>留学するなら大阪にある国籍不問の日本語学校「大阪日本語アカデミー」</title>
<!-- InstanceEndEditable -->
<!-- InstanceBeginEditable name="head" -->
<link href="../../css2/submain.css" rel="stylesheet" type="text/css" />
<!-- InstanceEndEditable -->
<link href="../../css2/oja_second.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">
function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}

function MM_swapImgRestore() { //v3.0
  var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}

function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function MM_swapImage() { //v3.0
  var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
   if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
}
</script>
</head>

<body onLoad="setupZoom();MM_preloadImages('../../index_img/oja_top_ov_r1_c18.jpg','../../index_img/oja_top_ov_r1_c26.jpg','../../index_img/oja_top_ov_r7_c26.jpg','../../index_img/oja_top_ov_r16_c26.jpg','../../index_img/oja_top_ov_r20_c26.jpg','../../index_img/shisetu_ov.jpg','../../index_img/oja_top_ov_r24_c26.jpg','../../index_img/oja_top_ov_r27_c26.jpg','../../index_img/oja_top_ov_r34_c26.jpg','../../index_img/oja_top_ov_r37_c26.jpg','../../index_img/oja_top_ov_r46_c26.jpg','../../index_img/oja_top_ov_r51_c26.jpg','../../index_img/oja_top_ov_r54_c26.jpg','../../index_img/city_02.jpg','../../index_img/oneday_04.jpg','../../index_img/student_02.jpg','../../index_img/teacher_02.jpg','../../index_img/supporter_02.jpg','../../left_navi_img/home_02.jpg','../../left_navi_img/japanese-culture_02.jpg','../../left_navi_img/message-a_02.jpg','../../left_navi_img/schedule_02.jpg','../../second_img/glnavi-profile01.jpg','../../second_img/second_menu_02_r2_c3.jpg','../../second_img/second_menu_02_r2_c4.jpg','../../second_img/second_menu_02_r2_c5.jpg','../../second_img/second_menu_02_r2_c6.jpg','../../second_img/second_menu_02_r2_c7.jpg')">
<a name="top" id="top"></a>
<div id="header">
  <table width="950" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td width="290" align="left" valign="top"><a href="../../index.html"><img src="../../second_img/header_logo.jpg" alt="大阪日本語アカデミー" width="193" height="107" border="0" /></a></td>
      <td width="660" valign="top"><table width="660" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="299"><img src="../../second_img/header_tel.jpg" width="299" height="72" /></td>
          <td width="181" align="left"><a href="http://oja.jp/contact01/reference.html"><img src="../../second_img/header_reference.jpg" alt="お問い合わせ" name="Image2" width="180" height="72" border="0" id="Image2" onMouseOver="MM_swapImage('Image2','','../../index_img/oja_top_ov_r1_c18.jpg',1)" onMouseOut="MM_swapImgRestore()" /></a></td>
          <td width="180"><a href="http://oja.jp/contact02/shiryou.html"><img src="../../second_img/header_pamphlet.jpg" alt="資料請求" name="Image3" width="180" height="72" border="0" id="Image3" onMouseOver="MM_swapImage('Image3','','../../index_img/oja_top_ov_r1_c26.jpg',1)" onMouseOut="MM_swapImgRestore()" /></a></td>
        </tr>
      </table>
        <table width="660" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="193">&nbsp;</td>
            <td width="91"><img src="../../second_img/header_th.jpg" width="91" height="35" /></td>
            <td width="110"><a href="../../index-vi.html"><img src="../../second_img/header_vi.jpg" width="110" height="35" border="0" /></a></td>
            <td width="93"><a href="../../index-ch.html"><img src="../../second_img/header_ch.jpg" width="93" height="35" border="0" /></a></td>
            <td width="91"><a href="../../index-en.html"><img src="../../second_img/header_en.jpg" width="91" height="35" border="0" /></a></td>
            <td width="82"><a href="../../index.html"><img src="../../second_img/header_jp.jpg" width="82" height="35" border="0" /></a></td>
          </tr>
      </table></td>
    </tr>
  </table>
</div>
<div id="upline"><img src="../../index_img/no_image.gif" width="10" height="4" /></div>
<!-- InstanceBeginEditable name="image" -->
<div class="image02">
  <div id="contactstage02">
    <div id="mainstage03">
      <table width="950" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td><img src="../../contact_img/oja_contact_image002.jpg" width="950" height="200" /></td>
        </tr>
        <tr>
          <td align="left"><table width="695" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td><a href="../../profile.html"><img src="../../second_img/glnavi-guide01.jpg" width="112" height="60" border="0" id="Image191" onMouseOver="MM_swapImage('Image191','','../../second_img/glnavi-profile01.jpg',1)" onMouseOut="MM_swapImgRestore()" /></a></td>
              <td><a href="../../school-institution.html"><img src="../../second_img/glnavi-facilities01.jpg" width="110" height="60" border="0" id="Image201" onMouseOver="MM_swapImage('Image201','','../../second_img/second_menu_02_r2_c3.jpg',1)" onMouseOut="MM_swapImgRestore()" /></a></td>
              <td><a href="../../curriculum.html"><img src="../../second_img/glnavi-curriculum01.jpg" width="126" height="60" border="0" id="Image211" onMouseOver="MM_swapImage('Image211','','../../second_img/second_menu_02_r2_c4.jpg',1)" onMouseOut="MM_swapImgRestore()" /></a></td>
              <td><a href="../../life-support.html"><img src="../../second_img/glnavi-lifesupport01.jpg" width="133" height="60" border="0" id="Image221" onMouseOver="MM_swapImage('Image221','','../../second_img/second_menu_02_r2_c5.jpg',1)" onMouseOut="MM_swapImgRestore()" /></a></td>
              <td><a href="../../recruit.html"><img src="../../second_img/glnavi-recruitment01.jpg" width="110" height="60" border="0" id="Image23" onMouseOver="MM_swapImage('Image23','','../../second_img/second_menu_02_r2_c6.jpg',1)" onMouseOut="MM_swapImgRestore()" /></a></td>
              <td><img src="../../second_img/glnavi-pamphlet01.jpg" width="104" height="60" border="0" id="Image24" onMouseOver="MM_swapImage('Image24','','../../second_img/second_menu_02_r2_c7.jpg',1)" onMouseOut="MM_swapImgRestore()" /></td>
            </tr>
          </table></td>
        </tr>
      </table>
    </div>
  </div>
</div>
<!-- InstanceEndEditable -->
<div id="gomi"><!-- InstanceBeginEditable name="gomi" --><a href="../../index.html">Home</a> &gt; 資料請求<!-- InstanceEndEditable --></div>
<div id="mainstage">
  <div id="rightstage">
    <div class="sidemenu01"><a href="../../index.html"><img src="../../left_navi_img/home_01.jpg" alt="ホーム" name="Image1" width="180" height="70" border="0" id="Image1" onMouseOver="MM_swapImage('Image1','','../../left_navi_img/home_02.jpg',1)" onMouseOut="MM_swapImgRestore()" /></a></div>
    <div class="sidemenu02u10"><img src="../../left_navi_img/title01.jpg" width="180" height="34" /></div>
    <div class="sidemenu03"><a href="../../schedule.html"><img src="../../left_navi_img/schedule_01.jpg" alt="年間スケジュール" width="180" height="70" border="0" id="Image19" onMouseOver="MM_swapImage('Image19','','../../left_navi_img/schedule_02.jpg',1)" onMouseOut="MM_swapImgRestore()" /></a></div>
    <div class="sidemenu03"><a href="../../teache-profile.html"><img src="../../left_navi_img/teacher_01.jpg" alt="講師紹介" width="180" height="70" border="0" id="Image20" onMouseOver="MM_swapImage('Image20','','../../left_navi_img/teacher_02.jpg',1)" onMouseOut="MM_swapImgRestore()" /></a></div>
    <div class="sidemenu03"><a href="../../life-supportstaff.html"><img src="../../left_navi_img/support_staff_01.jpg" alt="生活サポートスタッフ" name="Image9" width="180" height="70" border="0" id="Image9" onMouseOver="MM_swapImage('Image9','','../../left_navi_img/support_staff_02.jpg',1)" onMouseOut="MM_swapImgRestore()" /></a><a href="philosophy.html"></a></div>
    <div class="sidemenu03"><a href="../../japan-life.html"><img src="../../left_navi_img/japan_life_01.jpg" alt="日本での住まい" name="Image10" width="180" height="70" border="0" id="Image10" onMouseOver="MM_swapImage('Image10','','../../left_navi_img/japan_life_02.jpg',1)" onMouseOut="MM_swapImgRestore()" /></a><a href="philosophy.html"></a></div>
    <div class="sidemenu01"><a href="../../everyday-life.html"><img src="../../left_navi_img/life_support_01.jpg" alt="日常生活サポート" name="Image11" width="180" height="70" border="0" id="Image11" onMouseOver="MM_swapImage('Image11','','../../left_navi_img/life_support_02.jpg',1)" onMouseOut="MM_swapImgRestore()" /></a><a href="philosophy.html"></a></div>    
    <div class="sidemenu02u10"><img src="../../left_navi_img/title02.jpg" width="180" height="34" /></div>
    <div class="sidemenu03"><a href="../../one-day.html"><img src="../../left_navi_img/oneday_03.jpg" alt="学生の１日" width="180" height="70" border="0" id="Image21" onMouseOver="MM_swapImage('Image21','','../../left_navi_img/oneday_04.jpg',1)" onMouseOut="MM_swapImgRestore()" /></a></div>
    <div class="sidemenu01"><a href="../../student.html"><img src="../../left_navi_img/student_01.jpg" alt="学生紹介" width="180" height="70" border="0" id="Image22" onMouseOver="MM_swapImage('Image22','','../../left_navi_img/student_02.jpg',1)" onMouseOut="MM_swapImgRestore()" /></a></div>
    <div class="sidemenu02u10"><img src="../../left_navi_img/title03.jpg" width="180" height="34" /></div>
    <div class="sidemenu03"><a href="../../city.html"><img src="../../left_navi_img/city_01.jpg" alt="周辺情報" width="180" height="70" border="0" id="Image6" onMouseOver="MM_swapImage('Image6','','../../left_navi_img/city_02.jpg',1)" onMouseOut="MM_swapImgRestore()" /></a></div>
    <div class="sidemenu01"><a href="../../japanese-culture.html"><img src="../../left_navi_img/japanese-culture_01.jpg" alt="日本文化の体験" width="180" height="70" border="0" id="Image8" onMouseOver="MM_swapImage('Image8','','../../left_navi_img/japanese-culture_02.jpg',1)" onMouseOut="MM_swapImgRestore()" /></a></div>
    <div class="sidemenu02u10"><img src="../../left_navi_img/title04.jpg" width="180" height="34" /></div>
    <div class="sidemenu03"><a href="../../profile.html#riji"><img src="../../left_navi_img/message-a_01.jpg" alt="理事長メッセージ" width="180" height="70" border="0" id="Image13" onMouseOver="MM_swapImage('Image13','','../../left_navi_img/message-a_02.jpg',1)" onMouseOut="MM_swapImgRestore()" /></a></div>
    <div class="sidemenu01"><a href="../../suppoter.html"><img src="../../left_navi_img/supporter_01.jpg" alt="生活サポーター" width="180" height="70" border="0" id="Image14" onMouseOver="MM_swapImage('Image14','','../../left_navi_img/supporter_02.jpg',1)" onMouseOut="MM_swapImgRestore()" /></a></div>

    <div class="sidemenu02u10"><a href="../../access.html"><img src="../../left_navi_img/map.jpg" alt="地図・アクセス" width="180" height="150" border="0" /></a></div>
    <div class="sidemenu02u10"><a href="../../city.html"><img src="../../left_navi_img/city.jpg" alt="周辺案内" width="180" height="159" border="0" /></a></div>
    <div class="sidemenu02u10"><a href="../../philosophy.html"><img src="../../left_navi_img/philosophy.jpg" alt="教育理念" width="180" height="150" border="0" /></a></div>
    <div class="sidemenu02u10"><a href="http://ojla.cocolog-nifty.com/blog/" target="_blank"><img src="../../left_navi_img/blog.jpg" alt="大阪日本語アカデミーブログ「ココログ」" width="180" height="150" border="0" /></a></div>
    <div class="sidemenu02u10"><a href="https://www.facebook.com/oja.ojla" target="_blank"><img src="../../left_navi_img/facebook.jpg" alt="Facebook" width="180" height="150" border="0" /></a></div>
    <div class="sidemenu02u10"><a href="http://nichiryukyo.com/" target="_blank"><img src="../../left_navi_img/nichiryukyo.jpg" width="180" height="150" border="0" /></a></div>
   <div class="sidemenu02u10"><a href="http://oja.jp/work.html" target="_blank"><img src="../../workimage/Workplace.jpg" width="180" height="111" border="0" /></a></div>
  </div>
  <div id="leftstage"><!-- InstanceBeginEditable name="main" -->
    <div class="uecopysub"></div>
    <table width="710" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td align="left"><div class="tomail02"> 情報の入力</div>
        <div class="tomail"> 情報の確認</div>
        <div class="tomail02">送信完了</div></td>
      </tr>
      <tr>
        <td><img src="../../index_img/no_image.gif" width="2" height="15" /></td>
      </tr>
    </table>
    <div class="hyodai"><img src="../../contact_img/copy_001.jpg" width="710" height="44" /></div>
    <div class="gaiyouspace">

<?php } function htmlFooter() { ?>


    </div>
  <!-- InstanceEndEditable --></div>
  <div id="gotop"><a href="#top"><img src="../../index_img/oja_top_01_r30_c30.jpg" alt="Page Top" width="90" height="30" border="0" /></a></div>
</div>
<div id="underline"><img src="../../index_img/no_image.gif" width="10" height="4" /></div>
<div id="understage">
  <div id="understage02">
    <table width="950" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="589" align="left"><img src="../../index_img/oja_top_01_r32_c2.jpg" width="313" height="71" /></td>
        <td width="181"><a href="http://oja.jp/contact01/reference.html"><img src="../../second_img/header_reference.jpg" alt="お問い合わせ" name="Image41" width="180" height="72" border="0" id="Image41" onMouseOver="MM_swapImage('Image41','','../../index_img/oja_top_ov_r1_c18.jpg',1)" onMouseOut="MM_swapImgRestore()" /></a></td>
        <td width="180"><a href="http://oja.jp/contact02/shiryou.html"><img src="../../second_img/header_pamphlet.jpg" alt="資料請求" name="Image42" width="180" height="72" border="0" id="Image42" onMouseOver="MM_swapImage('Image42','','../../index_img/oja_top_ov_r1_c26.jpg',1)" onMouseOut="MM_swapImgRestore()" /></a></td>
      </tr>
      <tr>
        <td colspan="3">&nbsp;</td>
      </tr>
    </table>
    <table width="950" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="589" align="left" valign="top"><table width="570" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="190" valign="top"><div class="copy01"> <a href="../../index.html">ホーム</a></div>
              <div class="copy01"> <a href="../../profile.html">学校案内</a></div>
              <div class="copy01"> <a href="../../school-institution.html">学校施設</a></div>
<div class="copy01"> <a href="../../curriculum.html">カリキュラム</a></div>
              <div class="copy01"> <a href="../../recruit.html">募集案内</a></div>
              <div class="copy01"> <a href="../../life-support.html">生活サポート</a></div>
              <div class="copy01"><a href="../../one-day.html">学生の１日</a></div>
              <div class="copy01"><a href="../../student.html">学生紹介</a></div></td>
            <td width="190" valign="top"><div class="copy01"> <a href="../../teache-profile.html">講師紹介</a></div>
<div class="copy01"> <a href="../../schedule.html">年間スケジュール</a></div>
<div class="copy01"> <a href="../../access.html">地図・アクセス</a></div>
<div class="copy01"> <a href="../../city.html">周辺案内</a></div>
<div class="copy01"> <a href="../../profile.html#riji">理事長メッセージ</a></div>
<div class="copy01"> <a href="http://oja.jp/contact02/shiryou.html">資料請求</a></div>
              <div class="copy01"> <a href="http://oja.jp/contact01/reference.html">お問い合わせ</a></div>
              <div class="copy01"> <a href="../../suppoter.html">OJAサポーター</a></div></td>
            <td width="190" valign="top"><div class="copy01"> <a href="../../information.html">OJAからのお知らせ</a></div>
              <div class="copy01"> <a href="../../japanese-culture.html">日本文化の体験</a></div>
              <div class="copy01"> <a href="../../policy.html">プライバシーポリシー</a></div>
              <div class="copy01"> <a href="http://ojla.cocolog-nifty.com/blog/" target="_blank">ブログ「ココログ」</a></div>
              <div class="copy01"> <a href="https://www.facebook.com/oja.ojla/" target="_blank">Facebook</a></div>
              <div class="copy01"> <a href="../../assessment.html">自己点検・自己評価報告書</a></div></td>
            </tr>
        </table></td>
        <td width="361" align="left" valign="top"><span class="font80"><strong>お問い合わせ</strong><br />
547-0015 大阪市平野区長吉長原西2-2-12<br />
TEL：06-6707-2227<br />
E-mail：<a href="mailto:contact@oja.jp">contact@oja.jp</a><br />
URL：<a href="http://www.oja.jp">http://www.oja.jp</a><br />
<br />
<strong>Contact us</strong><br />
12-2-2 nagaharanishi nagayoshi hiranoku ,Osaka City,  Osaka 547-0015 Japan<br />
TEL：+81-6-6707-2227<br />
E-mail：<a href="mailto:contact@oja.jp">contact@oja.jp</a><br />
URL：<a href="http://www.oja.jp">http://www.oja.jp</a></span></td>
      </tr>
    </table>
  </div>
</div>
<div id="underline02"><img src="../../index_img/no_image.gif" width="10" height="1" /></div>
<div class="underwhite">
  <div id="bottommenu"><img src="../../index_img/footer_th.jpg" alt="Thailand" width="88" height="41" /><a href="../../index-vi.html"><img src="../../index_img/footer_vi.jpg" alt="Vietnamese" width="110" height="41" border="0" /></a><a href="../../index-ch.html"><img src="../../index_img/footer_ch.jpg" alt="chinese" width="91" height="41" border="0" /></a><a href="../../index-en.html"><img src="../../index_img/footer_en.jpg" alt="English" width="92" height="41" border="0" /></a><a href="../../index.html"><img src="../../index_img/footer_jp_on.jpg" alt="Japanese" width="91" height="41" border="0" /></a></div>
</div>
<div id="copyright">（Ｃ）2013 OSAKA JAPANESE LANGUAGE ACADEMY. All Rights Reserved.</div>

<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-42752253-1', 'oja.jp');
  ga('send', 'pageview');

</script>

</body>
<!-- InstanceEnd --></html>
<?php } ?>