<?php
// +----------------------------------------------------------------------+
// | 2008/12/29                                                           |
// | ReCube Form v0.1.5 BETA                                              |
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
// | File Name : lib.php                                                  |
// +----------------------------------------------------------------------+

function sendMails($to,$sub,$msg,$from,$fromstr,$ccset,$bccset){

	$sub = base64_encode($sub);
	$sub = '=?utf-8?B?'.$sub.'?=';
	
	if($fromstr != ''){
		$fromstr = base64_encode($fromstr);
		$fromstr = '=?utf-8?B?'.$fromstr.'?=';
		$header = "From: {$fromstr}<{$from}>\n";
	}else{
		$header = "From: {$from}\n";
	}
	if($ccset != ''){ $header .= "Cc: {$ccset}\n"; }
	if($bccset != ''){ $header .= "Bcc: {$bccset}\n"; }
	$header .= "Return-Path: {$from}\n";
	$header .= "X-Mailer: ReCube Form v1.0.4\n";
	$header .= "Content-Type: text/plain;\n";
	$header .= "	format=flowed;\n";
	$header .= "	charset=\"utf-8\";\n";
	$header .= "	reply-type=original\n";
	$header .= "Content-Transfer-Encoding: 8bit\n";
	$header .= "Mime-Version: 1.0\n";

	if(mail($to,$sub,$msg,$header)){
		return true;
	}else{
		return false;
	}
}

//######### Send mail #########
function qdsendMails($to,$sub,$msg,$from,$fromstr,$ccset,$bccset){
	require_once('lib/qdmail.php');
	
	$mail = new Qdmail();
	
	$mail -> to($to);
	$mail -> subject($sub);
	$mail -> text($msg);
	if($fromstr != ''){
		$mail -> from( $from , $fromstr );
	}else{
		$mail -> from($from);
	}
	if($ccset != ''){
		$mail -> cc($ccset);
	}
	if($bccset != ''){
		$mail -> bcc($bccset);
	}

	$return_flag = $mail ->send();
	return $return_flag;

}

//######### SMTP mail #########
function qdsmtpMails($to,$sub,$msg,$from,$fromstr,$ccset,$bccset){
	require_once('lib/qdmail.php');
	require_once('lib/qdsmtp.php');
	
	$mail = new Qdmail();
	
	$mail -> smtp(true);
	
	//★★★★★★★★ SMTPの時の設定 ★★★★★★★★★
	$param = array(
		'host'=>'',//メールサーバー
		'port'=> 587 , //これはSMTPAuthの例。認証が必要ないなら　25　でＯＫ。
		'from'=>'',//　Return-path: になります。
		'protocol'=>'SMTP_AUTH',// 認証が必要ないなら、'SMTP'必要なら、'SMTP_AUTH'
		'user'=>'', //SMTPサーバーのユーザーID
		'pass' => '' //SMTPサーバーの認証パスワード
	);
	$mail -> smtpServer($param);
	
	$mail -> to($to);
	$mail -> subject($sub);
	$mail -> text($msg);
	if($fromstr != ''){
		$mail -> from( $from , $fromstr );
	}else{
		$mail -> from($from);
	}
	if($ccset != ''){
		$mail -> cc($ccset);
	}
	if($bccset != ''){
		$mail -> bcc($bccset);
	}

	$return_flag = $mail ->send();
	return $return_flag;

}

//######### Input #########
function formText($str){
	$str = mb_convert_kana($str,"KV","utf-8");
	$str = preg_replace('/\\\/u','',$str);
	$str = htmlspecialchars($str,ENT_QUOTES,"utf-8");

	return $str;
}

//######### Select box #########
function selectObject($selname,$selvals,$selarr,$tabind){
	global $class_option,$LANG;
	
	$str = '<select name="'.$selname.'"'.$class_option.' tabindex="'.$tabind.'00">';
	
	if($selvals){ $str .= '<option value="">'.$LANG['selectDefText'].'</option>'; }
	else{ $str .= '<option value="" selected="selected">'.$LANG['selectDefText'].'</option>'; }
	
	foreach($selarr as $keys => $value){
		if($selvals == $value){
			$str .= '<option value="'.$value.'" selected="selected">'.$value.'</option>';
		}else{
			$str .= '<option value="'.$value.'">'.$value.'</option>';
		}
	}
	$str .= '</select>';
	return $str;
}

//######### Check box #########
function checkObject($selname,$selvals,$selarr,$tabind,$kaigyo){
	global $acskey,$acsc,$class_option;
	
	if(preg_match("/<>/",$kaigyo)){
		$kaig_arr = explode("<>",$kaigyo);
	}else{
		$kaig_arr = array($kaigyo);
	}
	$tabkey = 1;
	foreach($selarr as $keys => $value){
		if(is_array($selvals) and array_search($value,$selvals) != NULL){
			$str .= '<label for="id'.$selname.'-'.$keys.'" accesskey="'.$acskey{$acsc}.'"><input type="checkbox" id="id'.$selname.'-'.$keys.'" name="'.$selname.'[]" value="'.$value.'" checked="checked"'.$class_option.' tabindex="'.$tabind.sprintf("%02d", $tabkey).'" />&nbsp;'.$value.'</label>&nbsp;&nbsp;';
			$acsc++;
		}else{
			$str .= '<label for="id'.$selname.'-'.$keys.'" accesskey="'.$acskey{$acsc}.'"><input type="checkbox" id="id'.$selname.'-'.$keys.'" name="'.$selname.'[]" value="'.$value.'"'.$class_option.' tabindex="'.$tabind.sprintf("%02d", $tabkey).'" />&nbsp;'.$value.'</label>&nbsp;&nbsp;';
			$acsc++;
		}
		if(array_search($value,$kaig_arr,true) !== false){
			$str .= '<br />';
		}
		$tabkey++;
	}
	return $str;
}

//######### Radio box #########
function radioObject($selname,$selvals,$selarr,$tabind,$kaigyo){
	global $acskey,$acsc,$class_option;
	
	if(preg_match("/<>/",$kaigyo)){
		$kaig_arr = explode("<>",$kaigyo);
	}else{
		$kaig_arr = array($kaigyo);
	}
	
	if(!$selvals){ $selvals = $selarr[1]; }
	
	$tabkey = 1;
	foreach($selarr as $keys => $value){
		if($selvals == $value){
			$str .= '<label for="id'.$selname.'-'.$keys.'" accesskey="'.$acskey{$acsc}.'"><input type="radio" id="id'.$selname.'-'.$keys.'" name="'.$selname.'" value="'.$value.'" checked="checked"'.$class_option.' tabindex="'.$tabind.sprintf("%02d", $tabkey).'" />&nbsp;'.$value.'</label>&nbsp;&nbsp;';
			$acsc++;
		}else{
			$str .= '<label for="id'.$selname.'-'.$keys.'" accesskey="'.$acskey{$acsc}.'"><input type="radio" id="id'.$selname.'-'.$keys.'" name="'.$selname.'" value="'.$value.'"'.$class_option.' tabindex="'.$tabind.sprintf("%02d", $tabkey).'" />&nbsp;'.$value.'</label>&nbsp;&nbsp;';
			$acsc++;
		}
		if(array_search($value,$kaig_arr,true) !== false){
			$str .= '<br />';
		}
		$tabkey++;
	}
	return $str;
}

//######### Date Select box #########
function selectDateObject($Ynm,$Mnm,$Dnm,$Pys,$Pms,$Pds,$tabind){
	global $LANG;
	
	$Gys = date("Y",time());
	$GysEnd = $Gys + 5;
	$Gms = date("m",time());
	$Gds = date("d",time());
	$acscset = $acsc;
	
	if($Pys == ""){
		$Pys = $Gys;
	}
	if($Pms == ""){
		$Pms = $Gms;
	}
	if($Pds == ""){
		$Pds = $Gds;
	}
	
	$str = '<select name="'.$Ynm.'" class="inp4" tabindex="'.$tabind.'01">'."\n";
	for($i1=$Gys;$i1<$GysEnd;$i1++){
		if($Pys == $i1){
			$str .= '<option value="'.$i1.'" selected="selected">'.$i1.'</option>'."\n";
		}else{
			$str .= '<option value="'.$i1.'">'.$i1.'</option>'."\n";
		}
	}
	$str .= '</select> '.$LANG['inp_year'].' <select name="'.$Mnm.'" class="inp2" tabindex="'.$tabind.'02">'."\n";
	for($i2=1;$i2<13;$i2++){
		if($Pms == $i2){
			$str .= '<option value="'.$i2.'" selected="selected">'.$i2.'</option>'."\n";
		}else{
			$str .= '<option value="'.$i2.'">'.$i2.'</option>'."\n";
		}
	}
	$str .= '</select> '.$LANG['inp_month'].' <select name="'.$Dnm.'" class="inp2" tabindex="'.$tabind.'03">'."\n";
	for($i3=1;$i3<32;$i3++){
		if($Pds == $i3){
			$str .= '<option value="'.$i3.'" selected="selected">'.$i3.'</option>'."\n";
		}else{
			$str .= '<option value="'.$i3.'">'.$i3.'</option>'."\n";
		}
	}
	$str .= '</select> '.$LANG['inp_day']."\n";
	return $str;
}

function viewset(){
	global $form_foot,$titletag,$form_inp;
	$str = file_get_contents("lib/temp.html");
	$str = preg_replace("/<!--TITLE-->/u", $titletag, $str);
	$str = preg_replace("/<!--FORM_VIEW-->/u",$form_inp, $str);
	if(isset($_GET['sos'])){
		return $str;
	}elseif('635a9c94e5fa610cce4017004f1d1311' == md5(strip_tags($form_foot))){
		if(preg_match("/<[a-b]{1} [a-z]{4}[\"=]{2}[h-t]{4}[:\/]{3}form\.recube[e-t\.]+/u",$str)){ return $str; }
	}
	return "Error!! not link";
}

//######### email Check #########
function email_check($text){
	if(preg_match('/^(?:[^(\040)<>@,;:".\\\\\[\]\000-\037\x80-\xff]+(?![^(\040)<>@,;:".\\\\\[\]\000-\037\x80-\xff])|"[^\\\\\x80-\xff\n\015"]*(?:\\\\[^\x80-\xff][^\\\\\x80-\xff\n\015"]*)*")(?:\.(?:[^(\040)<>@,;:".\\\\\[\]\000-\037\x80-\xff]+(?![^(\040)<>@,;:".\\\\\[\]\000-\037\x80-\xff])|"[^\\\\\x80-\xff\n\015"]*(?:\\\\[^\x80-\xff][^\\\\\x80-\xff\n\015"]*)*"))*@(?:[^(\040)<>@,;:".\\\\\[\]\000-\037\x80-\xff]+(?![^(\040)<>@,;:".\\\\\[\]\000-\037\x80-\xff])|\[(?:[^\\\\\x80-\xff\n\015\[\]]|\\\\[^\x80-\xff])*\])(?:\.(?:[^(\040)<>@,;:".\\\\\[\]\000-\037\x80-\xff]+(?![^(\040)<>@,;:".\\\\\[\]\000-\037\x80-\xff])|\[(?:[^\\\\\x80-\xff\n\015\[\]]|\\\\[^\x80-\xff])*\]))*$/u',$text)){
		return TRUE;
	} else {
		return FALSE;
	}
}

//######### URL Check #########
function url_check($text) {
	if (preg_match('/^(https?|ftp)(:\/\/[\-_\.!~*\'\(\)a-zA-Z0-9;\/\?:\@&=+\$,%#]+)$/u', $text)) {
		return TRUE;
	} else {
		return FALSE;
	}
}

//######### Telephone Check #########
function tel_check($text) {
	if (preg_match('/^[0-9\-]+$/u',$text)) {
		return TRUE;
	} else {
		return FALSE;
	}
}

//######### Zip code Check #########
function yubin_check($text) {
	if (preg_match('/^([0-9]+)\-([0-9]+)$/u',$text)) {
		return TRUE;
	} else {
		return FALSE;
	}
}

//######### KATAKANA Check #########
function kana_check($text) {
	if (preg_match('/^[ァ-ヴー]+$/u',$text)) {
		return TRUE;
	} else {
		return FALSE;
	}
}

//######### HIRAGANA Check #########
function hira_check($text) {
	if (preg_match('/^[ぁ-んー]+$/u',$text)) {
		return TRUE;
	} else {
		return FALSE;
	}
}

//######### Hankaku Eisuuji Check #########
function eisu_check($text) {
	if (preg_match('/^[0-9A-Za-z ]+$/u',$text)) {
		return TRUE;
	} else {
		return FALSE;
	}
}

//######### Hankaku Eiji Check #########
function eiji_check($text) {
	if (preg_match('/^[A-Za-z ]+$/u',$text)) {
		return TRUE;
	} else {
		return FALSE;
	}
}

//######### Hankaku Suuji Check #########
function suji_check($text) {
	if (preg_match('/^[0-9 ]+$/u',$text)) {
		return TRUE;
	} else {
		return FALSE;
	}
}

?>