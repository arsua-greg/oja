<?php
/**
 * 
 * 送信フォーム関連設定値 (備菊様)
 *
 * LICENSE: 
 *
 * @copyright  25, May, 2013  NK.
 * @license
 * @version    CVS: $Id:$
 * @link
 *
*/


/*-------------------------------------------------------*/

define('INQ_TO_MAIL', "contact@oja.jp");//管理者宛先
define('INQ_BCC_MAIL', "nabuchi@n-effect.co.jp");//BCC
//define('INQ_SPAM_FOWORD_MAIL', "");//スパム可能性の場合に転送する宛先

define('INQ_FROM_MAIL', "contact@oja.jp");//ユーザー宛サンキューメールFROM

define('INQ_ADMIN_FROM_NAME', "OSAKA JAPANESE LANGUAGE ACADEMY");

define('INQ_ADMIN_MAIL_SUBJECT1', "ホームページからの問い合わせ from 英語ページ");//管理者メールタイトル
define('INQ_USER_MAIL_SUBJECT1', "Thank you for contacting us");//ユーザーメールタイトル

define('INQ_ADMIN_MAIL_SUBJECT2', "ホームページからの資料請求 from 英語ページ");//管理者メールタイトル
define('INQ_USER_MAIL_SUBJECT2', "Thank you for contacting us");//ユーザーメールタイトル

define('INQ_USER_COPYMAIL', 1);//0:NO 1:YES

define('LIB_DIR', 'formlib/');

define('DEBUG_MODE' ,'off');

define('RANDOM_TOKEN', 'nijf4r8hu43586g7werfijo95046'.rand());
define('COOKIE_DOMAIN', 'oja.jp');

error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE );
ini_set("display_errors", DEBUG_MODE);
ini_set("display_startup_errors", DEBUG_MODE);
//ini_set("log_errors", 'on');
//ini_set("error_log", 'phperr.dat');

define ('MOJI_CODE','UTF-8');
define ('OUTPUT_MOJI_CODE','UTF-8');
define ('MOJI_CODE_DETECT','UTF-8, SJIS');
define ('CRLF', "\n");

ini_set("default_charset", '');
mb_language("Japanese");
mb_internal_encoding (MOJI_CODE);
mb_regex_encoding (MOJI_CODE);
mb_detect_order (MOJI_CODE_DETECT);
mb_http_output(OUTPUT_MOJI_CODE);
//ob_start("mb_output_handler"); 




