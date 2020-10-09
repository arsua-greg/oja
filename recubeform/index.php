<?php
ini_set("display_errors",1);
error_reporting(E_ALL & ~E_NOTICE);
header("Content-Type: text/html;charset=UTF-8");
mb_language("uni");
mb_internal_encoding("UTF-8");
// PHP version 4 - 5
// When using it in Japanese, "mb module" is indispensable.
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
// | File Name : index.php - main script                                  |
// +----------------------------------------------------------------------+

	require_once("lib/config.php");
	require_once("lib/setting.php");
	require_once("lib/language.php");
	require_once("lib/lib.php");
	
	$F = array();
	$E = array();
	$V = array();

	$ERR = 0;
	$rtnmail_add = "";

if(isset($_GET['sos'])){

	$form_inp = $endMessage;

}else{

	if($_POST['submit'] == $inputBtn or $_POST['submit'] == $returnBtn or $_POST['submit'] == $registBtn){
	
		foreach($FORM as $fmFkey => $fmFval){
			$fname = $fmFval['name'];
			if($fmFval['type'] == "text" or $fmFval['type'] == "textarea"){
				$F[$fname] = formText($_POST[$fname]);
			}else{
				if($fmFval['type'] == "checkbox"){
					if(is_array($_POST[$fname])){
						$i = 1;
						foreach($_POST[$fname] as $kky => $vvl){
							$F[$fname][$i] = $vvl;
							$i++;
						}
					}
				}elseif($fmFval['type'] == "date"){
					$F[$fname.'_y'] = $_POST[$fname.'_y'];
					$F[$fname.'_m'] = $_POST[$fname.'_m'];
					$F[$fname.'_d'] = $_POST[$fname.'_d'];
				}else{
					$F[$fname] = $_POST[$fname];
				}
			}
			
			if($fmFval['rtml']){ $rtnmail_add = $F[$fname]; }
			
			//Error Check text.
			if($fmFval['chck'] == 1 and $F[$fname] != "" and !email_check($F[$fname])){ $ERR = 1; $E[$fname] = '<br /><span class="'.$errorClass.'">'.$fmFval['titl'].$LANG['errNotMailAddress'].'</span>'; }
			elseif($fmFval['chck'] == 2 and $F[$fname] != "" and !url_check($F[$fname])){ $ERR = 1; $E[$fname] = '<br /><span class="'.$errorClass.'">'.$fmFval['titl'].$LANG['errNotUrl'].'</span>'; }
			elseif($fmFval['chck'] == 3 and $F[$fname] != "" and !tel_check($F[$fname])){ $ERR = 1; $E[$fname] = '<br /><span class="'.$errorClass.'">'.$fmFval['titl'].$LANG['errNotTelephone'].'</span>'; }
			elseif($fmFval['chck'] == 4 and $F[$fname] != "" and !yubin_check($F[$fname])){ $ERR = 1; $E[$fname] = '<br /><span class="'.$errorClass.'">'.$fmFval['titl'].$LANG['errNotZipCode'].'</span>'; }
			elseif($fmFval['chck'] == 5 and $F[$fname] != "" and !eisu_check($F[$fname])){ $ERR = 1; $E[$fname] = '<br /><span class="'.$errorClass.'">'.$fmFval['titl'].$LANG['errNotHanEiSu'].'</span>'; }
			elseif($fmFval['chck'] == 6 and $F[$fname] != "" and !eiji_check($F[$fname])){ $ERR = 1; $E[$fname] = '<br /><span class="'.$errorClass.'">'.$fmFval['titl'].$LANG['errNotHanEi'].'</span>'; }
			elseif($fmFval['chck'] == 7 and $F[$fname] != "" and !suji_check($F[$fname])){ $ERR = 1; $E[$fname] = '<br /><span class="'.$errorClass.'">'.$fmFval['titl'].$LANG['errNotHanSu'].'</span>'; }
			elseif($fmFval['chck'] == 8 and $F[$fname] != "" and !kana_check($F[$fname])){ $ERR = 1; $E[$fname] = '<br /><span class="'.$errorClass.'">'.$fmFval['titl'].$LANG['errNotKataKana'].'</span>'; }
			elseif($fmFval['chck'] == 9 and $F[$fname] != "" and !hira_check($F[$fname])){ $ERR = 1; $E[$fname] = '<br /><span class="'.$errorClass.'">'.$fmFval['titl'].$LANG['errNotHiraGana'].'</span>'; }
			
			//Error Check number characters Restriction.
			if($fmFval['lens'] and $fmFval['lens'] < mb_strlen($F[$fname],'utf-8') and $fmFval['type'] == "text"){ $ERR = 1; $E[$fname] = '<br /><span class="'.$errorClass.'">'.$fmFval['titl'].$LANG['errNotCharaNumber'].'</span>'; }
			elseif($fmFval['lens'] and $fmFval['lens'] < mb_strlen($F[$fname],'utf-8') and $fmFval['type'] == "textarea"){ $ERR = 1; $E[$fname] = '<span class="'.$errorClass.'">'.$fmFval['titl'].$LANG['errNotCharaNumber'].'</span><br />'; }
			
			//Error Check indispensable.
			if($fmFval['hiss'] == 1 and $F[$fname] == "" and $fmFval['type'] == "text"){ $ERR = 1; $E[$fname] = '<br /><span class="'.$errorClass.'">'.$fmFval['titl'].$LANG['errNotText'].'</span>'; }
			elseif($fmFval['hiss'] == 1 and $F[$fname] == "" and $fmFval['type'] == "checkbox"){ $ERR = 1; $E[$fname] = '<span class="'.$errorClass.'">'.$fmFval['titl'].$LANG['errNotCheckBox'].'</span><br />'; }
			elseif($fmFval['hiss'] == 1 and $F[$fname] == "" and $fmFval['type'] == "radio"){ $ERR = 1; $E[$fname] = '<span class="'.$errorClass.'">'.$fmFval['titl'].$LANG['errNotRadio'].'</span><br />'; }
			elseif($fmFval['hiss'] == 1 and $F[$fname] == "" and $fmFval['type'] == "textarea"){ $ERR = 1; $E[$fname] = '<span class="'.$errorClass.'">'.$fmFval['titl'].$LANG['errNotTextArea'].'</span><br />'; }
			elseif($fmFval['hiss'] == 1 and $F[$fname] == $fmFval['titl'].$LANG['textareaDefText'] and $fmFval['type'] == "textarea"){ $ERR = 1; $E[$fname] = '<span class="'.$errorClass.'">'.$fmFval['titl'].$LANG['errNotTextArea'].'</span><br />'; }
			elseif($fmFval['hiss'] == 1 and $F[$fname] == "" and $fmFval['type'] == "select"){ $ERR = 1; $E[$fname] = '<span class="'.$errorClass.'">'.$fmFval['titl'].$LANG['errNotSelect'].'</span><br />'; }
		
			if(isset($fmFval['reml'])){
				$remlnom = $fmFval['reml'];
				$motomail = $FORM[$remlnom]['name'];
				if($F[$motomail] != $F[$fname]){
					$ERR = 1; $E[$fname] = '<br /><span class="'.$errorClass.'">'.$fmFval['titl'].$LANG['errNotValueConfirm'].'</span>';
				}
			}
		}
		
		if($_POST['submit'] == $registBtn){
			
			$ima_time = time();
			$maildate = $LANG['sendTime']."\n".date("Y{$LANG['inp_year']}m{$LANG['inp_month']}d{$LANG['inp_day']} H:i:s",$ima_time)."\n\n";
			
			//Subject
			$mailSubject1 = $subject;
			$mailSubject2 = $resubject;
			
			//send user information
			$useragents = $_SERVER['HTTP_USER_AGENT'];
			$ip = $_SERVER['REMOTE_ADDR'];
			$host = gethostbyaddr($ip);
			
			$sosinjoho = $LANG['sendUserInfo']."\n{$ip}\n{$host}\n{$useragents}\n";
			
			$mail_cont = "";
			foreach($FORM as $fmMkey => $fmMval){
				$mname = $fmMval['name'];
				if($F[$mname] == "" and !$fmMval['hiss'] and $fmMval['type'] != "date"){ continue; }
				if($fmMval['type'] == "checkbox"){
					$mail_cont .= $LANG['sendIcon'].$fmMval['titl']."\n".implode("\n",$F[$mname])."\n\n";
				}elseif($fmMval['type'] == "date"){
					$mail_cont .= $LANG['sendIcon'].$fmMval['titl']."\n".$F[$mname.'_y'].$LANG['inp_year'].$F[$mname.'_m'].$LANG['inp_month'].$F[$mname.'_d'].$LANG['inp_day']."\n\n";
				}else{
					if(!$fmMval['reml']){
						$mail_cont .= $LANG['sendIcon'].$fmMval['titl']."\n".$F[$mname]."\n\n";
					}
					if($fmMval['subj']){
						$mailSubject1 = $F[$mname];
						$mailSubject2 = $F[$mname].$LANG['returnMailTitle'];
					}
				}
			}
			
			$froms = ($rtnmail_add != "")? $rtnmail_add : $mailFrom;
			
			$mailMessage1 = $mailHeader."\n".$mail_cont."\n".$maildate.$sosinjoho.$mailFooter;
			$mailMessage2 = $mailReturnHeader."\n".$mail_cont."\n".$maildate.$mailReturnFooter;
			
			$mailMessage1 = preg_replace("/\r\n/ui","\n",$mailMessage1);
			$mailMessage2 = preg_replace("/\r\n/ui","\n",$mailMessage2);

			if(file_exists("lib/qdsmtp.php")){
				
				if(!qdsmtpMails($mailTo,$mailSubject1,$mailMessage1,$froms,'',$ccAddress,$bccAddress)){
					exit($LANG['errSend']);
				}
				if($mailReturn and $rtnmail_add != ""){
					if(!qdsmtpMails($rtnmail_add,$mailSubject2,$mailMessage2,$mailFrom,$FromStr,'','')){
						exit($LANG['errSend']);
					}
				}

			}else{
				
				if(!sendMails($mailTo,$mailSubject1,$mailMessage1,$froms,'',$ccAddress,$bccAddress)){
					exit($LANG['errSend']);
				}
				if($mailReturn and $rtnmail_add != ""){
					if(!sendMails($rtnmail_add,$mailSubject2,$mailMessage2,$mailFrom,$FromStr,'','')){
						exit($LANG['errSend']);
					}
				}
			}
			header("location:{$selfile}?sos=1");
		}
	}
	
	if(!$ERR and $_POST['submit'] == $inputBtn){
		
		foreach($FORM as $fmVkey => $fmVval){
			$ckstr = "";
			$vname = $fmVval['name'];
			if($F[$vname] == "" and !$fmVval['hiss'] and $fmVval['type'] != "date"){ continue; }
			if($fmVval['type'] == "date"){
				$V[$vname] = $F[$vname.'_y'].$LANG['inp_year'].$F[$vname.'_m'].$LANG['inp_month'].$F[$vname.'_d'].$LANG['inp_day'].'<input type="hidden" name="'.$vname.'_y" value="'.$F[$vname.'_y'].'" /><input type="hidden" name="'.$vname.'_m" value="'.$F[$vname.'_m'].'" /><input type="hidden" name="'.$vname.'_d" value="'.$F[$vname.'_d'].'" />';
			}elseif($fmVval['type'] == "checkbox"){
				foreach($F[$vname] as $kyy => $vll){
					$ckstr .= '<input type="hidden" name="'.$vname.'['.$kyy.']" value="'.$F[$vname][$kyy].'" />';
				}
				$V[$vname] = implode("<br />",$F[$vname]).$ckstr;
			}elseif($fmVval['type'] == "textarea"){
				$V[$vname] = nl2br($F[$vname]).'<input type="hidden" name="'.$vname.'" value="'.$F[$vname].'">';
			}else{
				if($fmVval['reml']){
					$V[$vname] = 'OK<input type="hidden" name="'.$vname.'" value="'.$F[$vname].'">';
				}else{
					$V[$vname] = $F[$vname].'<input type="hidden" name="'.$vname.'" value="'.$F[$vname].'">';
				}
			}
		}
		$Vsubmit = '<input type="submit" name="submit" value="'.$registBtn.'" class="'.$registBtnClass.'" />&nbsp;<input type="submit" name="submit" value="'.$returnBtn.'" class="'.$returnBtnClass.'" />';
		$setcomments = $registMessage;
		
	}else{
		
		$acsc = 0;
		foreach($FORM as $fmPkey => $fmPval){
			$pname = $fmPval['name'];
			$points = ($fmPval['pint'])? '<div class="point">'.$fmPval['pint'].'</div>' : '';
			$class_option = ($fmPval['clss'])? ' class="'.$fmPval['clss'].'"' : '';
			if($fmPval['type'] == "text"){
				if($fmPval['lens']){ $maxlength = ' maxlength="'.$fmPval['lens'].'"'; }else{ $maxlength = ''; }
				$V[$pname] = $points.'<input type="text" name="'.$pname.'" value="'.$F[$pname].'"'.$class_option.' tabindex="'.$fmPkey.'00"'.$maxlength.' accesskey="'.$acskey{$acsc}.'" />'.$E[$pname];
				$acsc++;
			
			}elseif($fmPval['type'] == "date"){
				$V[$pname] = $points.selectDateObject($pname.'_y',$pname.'_m',$pname.'_d',$F[$pname.'_y'],$F[$pname.'_m'],$F[$pname.'_d'],$fmPkey)."\n";
			
			}elseif($fmPval['type'] == "textarea"){
				$valsset = ($F[$pname])? $F[$pname] : $fmPval['titl'].$LANG['textareaDefText'];
				$rows_option = ($fmPval['rows'])? ' rows="'.$fmPval['rows'].'"' : '';
				$cols_option = ($fmPval['cols'])? ' cols="'.$fmPval['cols'].'"' : '';
				$V[$pname] = $points.$E[$pname].'<textarea onfocus="if(this.value==\''.$fmPval['titl'].$LANG['textareaDefText'].'\'){this.value=\'\';}"'.$rows_option.$cols_option.' name="'.$pname.'"'.$class_option.' tabindex="'.$fmPkey.'00" accesskey="'.$acskey{$acsc}.'">'.$valsset.'</textarea>';
				$acsc++;
			
			}elseif($fmPval['type'] == "select"){
				$V[$pname] = $points.$E[$pname].selectObject($pname,$F[$pname],$fmPval['arry'],$fmPkey);
			
			}elseif($fmPval['type'] == "checkbox"){
				$V[$pname] = $points.$E[$pname].checkObject($pname,$F[$pname],$fmPval['arry'],$fmPkey,$fmPval['retn']);
			
			}elseif($fmPval['type'] == "radio"){
				$V[$pname] = $points.$E[$pname].radioObject($pname,$F[$pname],$fmPval['arry'],$fmPkey,$fmPval['retn']);
			
			}
			$submtabind = $fmPkey+1;
		}
		$Vsubmit = '<input type="submit" name="submit" value="'.$inputBtn.'" class="'.$inputBtnClass.'" tabindex="'.$submtabind.'00" accesskey="'.$acskey{$acsc}.'" />'."\n";
		$setcomments = $inputMessage;
	}

	$form_inp = '<div class="'.$wrapClass.'">'.$setcomments.'<form action="'.$selfile.'" method="post">';

	foreach($FORM as $fmDkey => $fmDval){
		$dname = $fmDval['name'];
		if($V[$dname] == "" and !$ERR and $_POST['submit'] == $inputBtn and !$fmDval['hiss']){
			continue;
		}else{
			$hisskmk = ($fmDval['hiss'])? '<b class="hiss">'.$hissMoji.'</b>' : '';
			$form_inp .= '<dl><dt>'.$fmDval['titl'].$hisskmk.'</dt><dd>'.$V[$dname].'</dd></dl>'."\n";
		}
	}

	$form_inp .= $form_foot.'<div class="align-c">'.$Vsubmit.'</div></form></div>';

}

	$value_html = viewset();
	print $value_html;

?>