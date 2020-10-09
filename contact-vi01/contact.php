<?php
/**
 * 
 * お問合せ PHP5.2版 (大阪日本語アカデミー様)
 *
 * LICENSE: NK STUDIO.
 *
 * @copyright  26, Jun, 2013  NK.
 * @license
 * @version    CVS: $Id:$
 * @link
 *
 * 
*/


/*-------------------------------------------------------*/
require_once('./formlib/config.ini.php');
require_once(LIB_DIR.'formutil.class.php');

/*-------------------------------------------------------*/
$req = new RequestSanitize(MOJI_CODE,'');


$mode     = $req->getdata('M');
$formtype = $req->getdata('T');

$model = new FormModel($formtype);

switch ( $mode )
{
	case 'check':	$model->check();

	case 'send':	$model->mailsend();

	default:		die();

	exit;
}

/*-------------------------------------------------------*/

class constData{

	public static $const;
	
	function __construct(){
		$this->const['reference']['check']           = LIB_DIR.'view_reference.html';
		$this->const['reference']['error']           = LIB_DIR.'error_reference.html';
		$this->const['reference']['ok']              = 'thanks_reference.html';
		$this->const['reference']['mail_body_admin'] = LIB_DIR.'mail_body_admin_reference.tpl';
		$this->const['reference']['mail_body_user']  = LIB_DIR.'mail_body_user_reference.tpl';
		$this->const['reference']['mail_subject_admin']  = INQ_ADMIN_MAIL_SUBJECT1;
		$this->const['reference']['mail_subject_user']   = INQ_USER_MAIL_SUBJECT1;

		$this->const['shiryou']['check']             = LIB_DIR.'view_shiryou.html';
		$this->const['shiryou']['error']             = LIB_DIR.'error_shiryou.html';
		$this->const['shiryou']['ok']                = 'thanks_shiryou.html';
		$this->const['shiryou']['mail_body_admin']   = LIB_DIR.'mail_body_admin_shiryou.tpl';
		$this->const['shiryou']['mail_body_user']    = LIB_DIR.'mail_body_user_shiryou.tpl';
		$this->const['shiryou']['mail_subject_admin']  = INQ_ADMIN_MAIL_SUBJECT2;
		$this->const['shiryou']['mail_subject_user']   = INQ_USER_MAIL_SUBJECT2;

		$this->const['val']                          = array('company','name1','name2','kana1','kana2','sex','age','country','add','email','email2','tel','comment','TOKEN');
	}
}

/*--------------------------------------------------------*/
class FormModel{

	public $id;
	public $type;
	public $constdata;
	public $post;
	public $maildata;
	public $formtype;

	function __construct($formtype){

		if($formtype == 'reference' || $formtype == 'shiryou'){
			$this->formtype = $formtype;
		}else{
			die('Form Type Error.');
		}

		

		$this->constdata = new constData();
		$req = new RequestSanitize(MOJI_CODE,'',1);
		$req->inquiry();
		$this->post = $req->getdata($this->constdata->const['val']);

	}

	public function form()
	{
		/*ob_start(); 
		include($this->constdata->const['form']);
		$result = ob_get_contents();
		$result = explode(CRLF, $result);
		ob_end_clean();*/

		if(!file_exists($this->constdata->const[$this->formtype]['form'])) {die('file not found.'.__LINE__);}
		$result = file($this->constdata->const[$this->formtype]['form']);

		setcookie('TOKEN', RANDOM_TOKEN);
		$this->post['TOKEN'] = RANDOM_TOKEN;


		foreach($result as $line){
			preg_match_all('/_%(.+?)%_/u', $line, $m, PREG_SET_ORDER);
			if(is_array($m)){
				for ($i=0;$i<sizeof($m);$i++) {
					if($m[$i][1] == 'errormsg' ){
						$replacement = $this->post[$m[$i][1]];
					}else{
						$replacement =  htmlspecialchars($this->post[$m[$i][1]], ENT_QUOTES, 'UTF-8');
					}
					$line = preg_replace('/_%'.$m[$i][1].'%_/u', $replacement, $line);
				}
			}
			echo $line;
		}
		exit;
	}


	public function check()
	{
	
		setcookie('TOKEN', RANDOM_TOKEN, time()+3600, "/contact-vi01/", COOKIE_DOMAIN);
		$this->post['TOKEN'] = RANDOM_TOKEN;


		$error = $this->errorcheck($this->post);

		if($error)
		{
			$this->post['errormsg'] = $error;
			$this->errorform();
			exit;
		}

		/*ob_start(); 
		include($this->constdata->const['check']);
		$result = ob_get_contents();
		$result = explode(CRLF, $result);
		ob_end_clean();*/

		if(!file_exists($this->constdata->const[$this->formtype]['check'])) {die('file not found.'.__LINE__);}
		$result = file($this->constdata->const[$this->formtype]['check']);

		foreach($this->constdata->const['val'] as $name){
			$this->post['hidden'] .= '<input type="hidden" name="'.$name.'" value="'.htmlspecialchars($this->post[$name], ENT_QUOTES, "UTF-8").'">';
		}

		foreach($result as $line){
			preg_match_all('/_%(.+?)%_/u', $line, $m, PREG_SET_ORDER);
			if(is_array($m)){
				for ($i=0;$i<sizeof($m);$i++) {
					if($m[$i][1] == 'hidden' || $m[$i][1] == 'errormsg'){
						$replacement = $this->post[$m[$i][1]];
					}elseif($m[$i][1] == 'comment'){ 
						$replacement =  preg_replace("(\r\n|\n|\r)", "<br />", htmlspecialchars($this->post[$m[$i][1]], ENT_QUOTES, "UTF-8"));
					}else{
						$replacement =  htmlspecialchars($this->post[$m[$i][1]], ENT_QUOTES, 'UTF-8');
					}
					$line = preg_replace('/_%'.$m[$i][1].'%_/u', $replacement, $line);
				}
			}

			echo $line;
		}
		exit;
	}


	public function mailsend()
	{

		if($_COOKIE['TOKEN'] != $this->post['TOKEN']){ die('Access Error'); }

		$error = $this->errorcheck($this->post);
	
		if($error)
		{
			$this->post['errormsg'] = $error;
			$this->form();
			exit;
		}
	
		//wordrap
		$this->post['comment'] = MySnedMail::mb_wordwrap($this->post['comment'], 300, "\n", true);

		if(!file_exists($this->constdata->const[$this->formtype]['mail_body_admin'])) {die('file not found.'.__LINE__);}
		$result = file($this->constdata->const[$this->formtype]['mail_body_admin']) or die();

		//REMOTE_ADDR
		$this->post['REMOTE_ADDR'] = $_SERVER["REMOTE_ADDR"];
		//DATE
		$this->post['DATE'] = date( "Y/m/d (D) H:i:s", time() );
		//REMOTE_HOST
		$this->post['REMOTE_HOST'] = getHostByAddr(getenv('REMOTE_ADDR'));

		foreach($result as $line){
			preg_match_all('/_%(.+?)%_/u', $line, $m, PREG_SET_ORDER);
			if(is_array($m)){
				for ($i=0;$i<sizeof($m);$i++) {
					$line = preg_replace('/_%'.$m[$i][1].'%_/u', $this->post[$m[$i][1]], $line);
				}
			}
			$mailline .= $line;
		}

		$TO_MAIL = INQ_TO_MAIL;

		$mail = new MySnedMail();
		//$mail->check_spam(array($this->post['comment']));
		$mail->set_to($TO_MAIL);
		$mail->set_bcc(INQ_BCC_MAIL);
		$mail->set_from($this->post['email'], $this->post['name']);
		$mail->set_env_from(INQ_TO_MAIL);
		$mail->set_body($mailline);
		$mail->set_header($this->constdata->const[$this->formtype]['mail_subject_admin']);
		$ok1 = $mail->send_mail();
		//$ok1 = $mail->send_mail_smtp();
		unset ($mail);
	
		if($ok1 == FALSE){
			$this->post['errormsg'] = "The mail could not be delivered to the recipient.";
			$this->form();
			exit;
		}

		/*To User*/
		if(INQ_USER_COPYMAIL){

			$mailline = '';

			if(!file_exists($this->constdata->const[$this->formtype]['mail_body_user'])) {die('file not found.'.__LINE__);}
			$result = file($this->constdata->const[$this->formtype]['mail_body_user']) or die();
			$tmp = $this;
			foreach($result as $line){
			preg_match_all('/_%(.+?)%_/u', $line, $m, PREG_SET_ORDER);
				if(is_array($m)){
					for ($i=0;$i<sizeof($m);$i++) {
						$line = preg_replace('/_%'.$m[$i][1].'%_/u', $this->post[$m[$i][1]], $line);
					}
				}
				$mailline .= $line;
			}

			$mail = new MySnedMail();
			$mail->set_to($this->post['email']);
			//$mail->set_bcc();
			$mail->set_from(INQ_FROM_MAIL, INQ_ADMIN_FROM_NAME);
			$mail->set_env_from(INQ_TO_MAIL);
			$mail->set_body($mailline);
			$mail->set_header($this->constdata->const[$this->formtype]['mail_subject_user']);
			$ok1 = $mail->send_mail();
			//$ok1 = $mail->send_mail_smtp();
			unset ($mail,$mailline);
		}

		setcookie('TOKEN', RANDOM_TOKEN, time()-9000, "/contact-vi01/", COOKIE_DOMAIN);
		header("Location: ".$this->constdata->const[$this->formtype]['ok']);
	
		exit;
	
	}
	
	
	private function errorform()
	{
		if(!file_exists($this->constdata->const[$this->formtype]['error'])) {die('file not found.'.__LINE__);}
		$result = file($this->constdata->const[$this->formtype]['error']);

		//setcookie('TOKEN', RANDOM_TOKEN);
		//$this->post['TOKEN'] = RANDOM_TOKEN;

		foreach($result as $line){
			preg_match_all('/_%(.+?)%_/u', $line, $m, PREG_SET_ORDER);
			if(is_array($m)){
				for ($i=0;$i<sizeof($m);$i++) {
					if($m[$i][1] == 'errormsg' || $m[$i][1] == 'error_js'){
						$replacement = $this->post[$m[$i][1]];
					}else{
						$replacement =  htmlspecialchars($this->post[$m[$i][1]], ENT_QUOTES, 'UTF-8');
					}
					$line = preg_replace('/_%'.$m[$i][1].'%_/u', $replacement, $line);
				}
			}
			echo $line;
		}
		exit;
	}

	private function errorcheck(&$post)
	{

		if ($post['name1'] == '' || $post['name2'] == '')
		{
			$error .= "Please input your name.<br />\n";
		}
		else
		{
			$strmaxlenByte = 50;
			if(mb_strlen($post['name1']) > $strmaxlenByte ){
				$error .= "Please input \"Name\" within ".$strmaxlenByte." charactors.<br />\n";
			}
			if(mb_strlen($post['name2']) > $strmaxlenByte ){
				$error .= "Please input \"Name\" within ".$strmaxlenByte." charactors.<br />\n";
			}
		}

		$strmaxlenByte = 50;
		if(mb_strlen($post['kana1']) > $strmaxlenByte ){
			$error .= "Please input \"Furigana\" within ".$strmaxlenByte." charactors.<br />\n";
		}
		if(mb_strlen($post['kana2']) > $strmaxlenByte ){
			$error .= "Please input \"Furigana\" within ".$strmaxlenByte." charactors.<br />\n";
		}
		if(mb_strlen($post['sex']) > $strmaxlenByte ){
			$error .= "Please input \"Gender\" within ".$strmaxlenByte." charactors.<br />\n";
		}
		if(mb_strlen($post['country']) > $strmaxlenByte ){
			$error .= "Please input \"Nationality\" within ".$strmaxlenByte." charactors.<br />\n";
		}


		$strmaxlenByte = 100;
		if(mb_strlen($post['add']) > $strmaxlenByte ){
			$error .= "Please input \"Address\" within ".$strmaxlenByte." charactors.<br />\n";
		}


		if ($post['tel'] != '')
		{
			$strmaxlenByte = 30;
			$str = $post['tel'];
			if(mb_strlen($post['tel']) > $strmaxlenByte){
				$error .= "Please input \"phone number\" within ".$strmaxlenByte." charactors.<br />\n";
			}
		}

		if (!isset($post['email']) || $post['email'] == '')
		{
			$error .= "Please input your email address.<br />\n";
		}
		else
		{
			$strmaxlenByte = 100;
			if(mb_strlen($post['email']) > $strmaxlenByte){
				$error .= "Please input \"email address\" within ".$strmaxlenByte." charactors.<br />\n";
			}
	
			if (!preg_match('/^([a-z0-9_]|\-|\.|\+)+@(([a-z0-9_]|\-)+\.)+[a-z]{2,6}$/i',$post['email']))
			{
				$error .= "There is an error in the email address format.<br />\n";
			}
		}

		if($post['email'] != $post['email2']){
			$error .= "The confirmation email address does not match your email address.<br />\n";
		}

		if (!isset($post['comment']) || $post['comment'] == '')
		{
			$error .= "Please input the content of your inquiry.<br />\n";
		}
		else
		{
			$strmaxlenByte = 3000;
			if(mb_strlen($post['comment']) > $strmaxlenByte){
				$error .= "Please input \"content of inquiry\" within ".$strmaxlenByte." charactors.<br />\n";
			}
		}

		return $error;
	
	}
}

