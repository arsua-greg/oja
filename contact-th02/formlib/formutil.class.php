<?php
/*
 * 
 * 
 * License NK STUDIO.
 * NK STUDIO Original Class
 * @2013 Last Update
**/

class RequestSanitize
{
	private  $request;
	private  $clean;
	private  $mojicode;
	private  $error;
	private  $sanitize_obj;

	/**
	*コンストラクタ
	*/
	function __construct($mojicode, $mojicode2='', $mode = 0)
	{
		$this->mojicode = $mojicode;

		$this->sanitize_obj = new Datasanitize($this->mojicode);

		if($mode == 1)
		{
			$this->request  = $_POST;
		}else{
			$this->request  = $_REQUEST;
		}

		/*全てのリクエストデータの文字コードを統一*/
		//mb_convert_variables("EUC-JP", "UTF-8", $this->request);
		//mb_convert_variables($this->mojicode, $mojicode2, $this->request);


		//共通項目のサニタイズ
		$this->common();

	}

	/*
	*共通項目サニタイズ
	*
	*/
	private function common()
	{
		/*モード*/
		if (isset($this->request['M']))
		{
			$this->request['M'] = $this->sanitize_obj->alphaSeikei($this->request['M']);
			$this->request['M'] = $this->sanitize_obj->stripHtmltags($this->request['M']);
			$this->request['M'] = $this->sanitize_obj->eraseLineFied($this->request['M']);
			if(!preg_match('/^[a-zA-Z_]{0,20}$/',$this->request['M'])){ $this->request['M']=''; }
			$this->clean['M'] = $this->request['M'];
		}

		/*フォームタイプ*/
		if (isset($this->request['T']))
		{
			$this->request['T'] = $this->sanitize_obj->alphaSeikei($this->request['T']);
			$this->request['T'] = $this->sanitize_obj->stripHtmltags($this->request['T']);
			$this->request['T'] = $this->sanitize_obj->eraseLineFied($this->request['T']);
			if(!preg_match('/^[a-zA-Z_]{0,20}$/',$this->request['T'])){ $this->request['T']=''; }
			$this->clean['T'] = $this->request['T'];
		}

		if (isset($this->request['TOKEN']))
		{
			$this->request['TOKEN'] = $this->sanitize_obj->alphaSeikei($this->request['TOKEN']);
			$this->request['TOKEN'] = $this->sanitize_obj->stripHtmltags($this->request['TOKEN']);
			$this->request['TOKEN'] = $this->sanitize_obj->eraseLineFied($this->request['TOKEN']);
			if(!preg_match('/^[0-9a-zA-Z_]{0,50}$/',$this->request['TOKEN'])){ $this->request['TOKEN']=''; }
			$this->clean['TOKEN'] = $this->request['TOKEN'];
		}
	}

	/*
	*お問い合わせ関連サニタイズ
	*
	*/
	public function inquiry()
	{
		//文字列（改行なし）:タグ除去、改行除去
		$strArray = array('company','name1','name2','kana1','kana2','sex','age','country','add','tel');
		for($i=0; $i<count($strArray); $i++)
		{
			if (isset($this->request[$strArray[$i]]))
			{
				if(is_array($this->request[$strArray[$i]])){
					foreach( $this->request[$strArray[$i]] as $key => $val ){
						$this->request[$strArray[$i]][$key] = $this->sanitize_obj->strSeikei($this->request[$strArray[$i]][$key]);
						$this->request[$strArray[$i]][$key] = $this->sanitize_obj->stripJavascript($this->request[$strArray[$i]][$key]);
						$this->request[$strArray[$i]][$key] = $this->sanitize_obj->eraseLineFied($this->request[$strArray[$i]][$key]);
						$this->clean[$strArray[$i]][$key] = $this->request[$strArray[$i]][$key];
					}
				}else{
					$this->request[$strArray[$i]] = $this->sanitize_obj->strSeikei($this->request[$strArray[$i]]);
					$this->request[$strArray[$i]] = $this->sanitize_obj->stripJavascript($this->request[$strArray[$i]]);
					$this->request[$strArray[$i]] = $this->sanitize_obj->eraseLineFied($this->request[$strArray[$i]]);
					$this->clean[$strArray[$i]] = $this->request[$strArray[$i]];
				}
			}
		}

		//文字列（改行あり）:タグ除去
		$strArray2 = array('comment');
		for($i=0; $i<count($strArray2); $i++)
		{
			if (isset($this->request[$strArray2[$i]]))
			{
				$this->request[$strArray2[$i]] = $this->sanitize_obj->strSeikei($this->request[$strArray2[$i]]);
				$this->request[$strArray2[$i]] = $this->sanitize_obj->stripJavascript($this->request[$strArray2[$i]]);
				$this->request[$strArray2[$i]] = $this->sanitize_obj->lineFied($this->request[$strArray2[$i]]);
				$this->clean[$strArray2[$i]] = $this->request[$strArray2[$i]];
			}
		}

		//半角英数:タグ除去、改行除去
		$strArray2 = array('email','email2');
		for($i=0; $i<count($strArray2); $i++)
		{
			if (isset($this->request[$strArray2[$i]]))
			{
				$this->request[$strArray2[$i]] = $this->sanitize_obj->alphaSeikei($this->request[$strArray2[$i]]);
				$this->request[$strArray2[$i]] = $this->sanitize_obj->stripJavascript($this->request[$strArray2[$i]]);
				$this->request[$strArray2[$i]] = $this->sanitize_obj->eraseLineFied($this->request[$strArray2[$i]]);
				$this->clean[$strArray2[$i]] = $this->request[$strArray2[$i]];
			}
		}

	}


	public function getdata ( $var )
	{
		if ( is_array( $var ) ) {
			foreach ( $var as $val ){
				$returnArray[$val] = $this->clean[$val];
			}
			return $returnArray;
		}else{
			return $this->clean[$var];
		}
	}


}


/*
* NK STUDIO Original Class
* @2009 Last Update
**/
class Datasanitize
{

	private  $mojicode;

	function __construct($val)
	{
		$this->mojicode = $val;
	}


	public function mojiConvert($str)
	{
		if (get_magic_quotes_gpc()) $str = stripslashes($str);
		$str = $this->nullErase($str);
		$str = mb_convert_encoding($str, $this->mojicode, 'auto');
		return $str;
	}

	/* 文字を整形 */
	public function strSeikei($str)
	{
		if (get_magic_quotes_gpc()) $str = stripslashes($str);
		$str = $this->nullErase($str);
		$str = $this->tabErase($str);
		$str = mb_convert_kana($str,'KV',$this->mojicode);
		return $str;
	}

	/* 全角数字を半角に整形 */
	public function numSeikei($str)
	{
		if (get_magic_quotes_gpc()) $str = stripslashes($str);
		$str = $this->nullErase($str);
		$str = $this->tabErase($str);
		$str = mb_convert_kana($str,'n',$this->mojicode);
		return $str;
	}

	/* 全角英数字を半角英数字へ整形 */
	public function alphaSeikei($str)
	{
		if (get_magic_quotes_gpc()) $str = stripslashes($str);
		$str = $this->nullErase($str);
		$str = $this->tabErase($str);
		$str = mb_convert_kana($str,'a',$this->mojicode);
		return $str;
	}

	/* タブ変換 */
	public function tabErase($str)
	{
		$str = str_replace( "\t", "    ", $str);
		return $str;
	}

	/* カンマ変換 */
	public function commaSeikei($str)
	{
		$str = str_replace( ",", "，", $str);
		return $str;
	}

	/* NULL除去 */
	public function nullErase($str)
	{
		$str = str_replace( "\0", "", $str);
		return $str;
	}

	/* タグ除去 および危険なjavascript除去*/
	public function stripHtmltags($str)
	{
		$str = strip_tags($str);
		$str = $this->stripJavascript($str);
		return $str;
	}

	/* 危険なjavascript除去*/
	public function stripJavascript($str)
	{
		$str = mb_eregi_replace("script", '' ,$str);
		$str = mb_eregi_replace("javascript", '' ,$str);
		$str = mb_eregi_replace("onmouseover", '' ,$str);
		$str = mb_eregi_replace("onmouseout", '' ,$str);
		$str = mb_eregi_replace("onmouseenter", '' ,$str);
		$str = mb_eregi_replace("onmousedown", '' ,$str);
		$str = mb_eregi_replace("onmouseup", '' ,$str);
		$str = mb_eregi_replace("onchange", '' ,$str);
		$str = mb_eregi_replace("onload", '' ,$str);
		$str = mb_eregi_replace("onunload", '' ,$str);
		$str = mb_eregi_replace("onclick", '' ,$str);
		$str = mb_eregi_replace("document.cookie", '' ,$str);
		$str = mb_eregi_replace("window.open", '' ,$str);
		return $str;
	}

	/* 改行コードを統一 */
	public function lineFied($str)
	{
		$str = mb_ereg_replace("\r\n",CRLF,$str);
		$str = mb_ereg_replace("\r",CRLF,$str);
		$str = mb_ereg_replace("\n",CRLF,$str);
		return $str;
	}

	/* 改行を消す */
	public function eraseLineFied($str)
	{
		$str = mb_ereg_replace("\r\n",'',$str);
		$str = mb_ereg_replace("\r",'',$str);
		$str = mb_ereg_replace("\n",'',$str);
		return $str;
	}


}

/*
* NK STUDIO Original Class
* @2009 Last Update
**/
class MySnedMail{

	private  $mail_body;
	private  $mail_header;
	private  $to_mail;
	private  $from_mail;
	private  $env_from_mail;
	private  $from_name;
	private  $bcc_mail;
	private  $mail_title;
	private  $spam_flg;

	/*
		コンストラクタ
	*/
	function __construct(){
		$this->mail_body   = '';
		$this->mail_header = '';
		$this->to_mail     = '';
		$this->from_mail   = '';
		$this->env_from_mail   = '';
		$this->from_name   = '';
		$this->bcc_mail    = '';
		$this->mail_title  = '';
		$this->spam_flg    = 0;
	}

	/*
		送信先のセット
		引数：メールアドレス
		複数セット可
	*/
	public function set_to($val){
		$val = preg_replace("(\r\n|\n|\r)", "", $val);
		$val = strip_tags($val);
		if($this->to_mail!=''){
			$this->to_mail .= ',';
		}
		$this->to_mail .= $val;
	}

	/*
		BCC送信先のセット
		引数：メールアドレス
	*/
	public function set_bcc($val){
		$val = preg_replace("(\r\n|\n|\r)", "", $val);
		$val = strip_tags($val);
		$this->bcc_mail = $val;
	}

	/*
		送信元のセット
		引数：メールアドレス，名前
	*/
	public function set_from($val,$val2){
		$val = preg_replace("(\r\n|\n|\r)", "", $val);
		$val = strip_tags($val);
		$val2 = preg_replace("(\r\n|\n|\r)", "", $val2);
		$val2 = strip_tags($val2);
		$this->from_mail = $val;
		$this->from_name = $val2;
	}

	/*
		ENV FROMのセット
		引数：メールアドレス
	*/
	public function set_env_from($val){
		$val = preg_replace("(\r\n|\n|\r)", "", $val);
		$val = strip_tags($val);
		$this->env_from_mail = $val;
	}

	/*
		メール本文セット
		引数：メール本文
	*/
	public function set_body($val){
		$this->mail_body = preg_replace("(\r\n|\n|\r)", "\n", $val);
		$this->mail_body  = mb_convert_encoding($this->mail_body,"utf-8",MOJI_CODE);
	}

	/*
		メールヘッダーセット
		引数：メールタイトル
	*/
	public function set_header($val){

		$val = preg_replace("(\r\n|\n|\r)", "", $val);
		$val = strip_tags($val);

/*		if($this->spam_flg == 1){
			$val = "【スパムメールの可能性】".$val;
		}
*/
		$this->mail_header  = "Return-Path:".$this->to_mail."\n";
//		$this->mail_header .= "From: ".mb_encode_mimeheader(mb_convert_encoding($this->to_name,"EUC-JP",MOJI_CODE),"ISO-2022-JP",MOJI_CODE)."<".$this->to_mail.">\n";
		$this->mail_header .= "From: ".mb_encode_mimeheader($this->from_name,"utf-8","B","\n")."<".$this->from_mail.">\n";
//		$this->mail_header .= "To: ".$this->to_mail."\n";
		$this->mail_header .= "Cc: \n";
		$this->mail_header .= "Bcc: ".$this->bcc_mail."\n";
		$this->mail_header .= "MIME-Version: 1.0\n";
		$this->mail_header .= "Content-Type: text/plain; charset=utf-8\n";
		$this->mail_header .= "Content-Transfer-Encoding: 8bit";

		$this->mail_title = mb_encode_mimeheader($val,"utf-8","B","\n");
//		$this->mail_title = mb_encode_mimeheader(mb_convert_encoding($val,"EUC-JP",MOJI_CODE),"ISO-2022-JP",MOJI_CODE);
	}

	/*
		メール送信実行(SENDMAIL)
		戻り値：送信成功=TRUE  送信失敗=FLASE
	*/
	public function send_mail(){
		/*if($this->spam_flg == 1){
			$this->to_mail = INQ_SPAM_FOWORD_MAIL;
		}*/
		//if(mail($this->to_mail, $this->mail_title, $this->mail_body, $this->mail_header, ' -t -i -f '.$this->env_from_mail)){
		if(mail($this->to_mail, $this->mail_title, $this->mail_body, $this->mail_header)){
			return TRUE;
		}else{
			return FALSE;
		}
	}

	/*
		メール送信実行(SMTP)
	*/
	public function send_mail_smtp(){
 
		require_once('qdsmtp.php');

		/*if($this->spam_flg == 1){
			$this->to_mail = INQ_SPAM_FOWORD_MAIL;
		}*/
		
		$param = array(
		    'host'=>SMTP_SERVER,
		    'port'=> SMTP_PORT ,
		    'from'=>$this->from_mail,
		    'protocol'=>'SMTP_AUTH',
			'user'=> SMTP_USER,
			'pass' => SMTP_PASS,
		);
		$smtp = new QdSmtp($param);
		if($smtp->mail($this->to_mail, $this->mail_title, $this->mail_body, $this->mail_header)){
			return TRUE;
		}else{
			return FALSE;
		}

	}

	/*wordrap*/
	public function mb_wordwrap($string, $width=75, $break="\n", $cut = false) {
		if (!$cut) {
			$regexp = '#^(?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){'.$width.',}\b#U';
		} else {
			$regexp = '#^(?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){'.$width.'}#';
		}
		$string_length = mb_strlen($string,'UTF-8');
		$cut_length = ceil($string_length / $width);
		$i = 1;
		$return = '';
		while ($i < $cut_length) {
			preg_match($regexp, $string,$matches);
			$new_string = $matches[0];
			$return .= $new_string.$break;
			$string = substr($string, strlen($new_string));
			$i++;
		}
		return $return.$string;
	}

	/*SPAM判定*/
	public function check_spam($str_array){
		foreach($str_array as $val){
			if(!preg_match("/[ぁ-ん]/u", $val)){
				$this->spam_flg = 1;
			}
		}
	}

}