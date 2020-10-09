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
// | File Name : setting.php                                              |
// +----------------------------------------------------------------------+

//############### SETTING ###############
//■titl = 項目名                         → HTMLで項目名として表示されます。
//■name = INPUT名                        → inputタグのname属性で使用します。
//■type                                  → 項目のタイプを設定します。
//  text = テキストボックス              
//  date = 日付入力用ボックス            
//  textarea = 複数行入力エリア          
//    textareaの場合のみ、rowsとcolsで縦横を指定
//  select = セレクトボックス            
//  checkbox = チェックボックス          
//  radio = ラジオボックス               
//■hiss                                  → 1を設定すると必須項目となります。必須にしない場合は0にするか、その行を消します。
//  1 = 必須項目にする                   
//■subj                                  → 1を設定するとメールの件名として使われます。0にするか、その行を削除すると、無効となります。
//  1 = 送信メールの件名にする           
//■rtml                                  → お客様にリターンメールを送る場合に使います。その項目はメールアドレスが入力されるように設定します。
//  1 = 控えを送る場合のアドレスにする   
//■reml メールの再入力用 元メールの番号  → 再入力用のテキストボックスに設定します。値は本メールのボックスの番号【$FORM[4]、この場合は4】を設定します。
//■chck チェックする内容                 → 設定すると、入力項目が正しいかチェックします。
//  1 = メールアドレス                   
//  2 = URL                              
//  3 = 電話番号                         
//  4 = 郵便番号                         
//  5 = 半角英数字                       
//  6 = 半角英字                         
//  7 = 半角数字                         
//  8 = カタカナ                         
//  9 = ひらがな                         
//■lens 文字数制限                       → 文字数の制限を与えます。
//■pint 注意文                           → 項目入力のエリアに、判りやすいように説明を入れることができます。
//■clss CSSクラス名                      → inputタグのクラス名を指定します。
//■arry select checkbox radio の際の項目 → 選択系のタグに与える項目を設定できます。
//■retn checkbox radioの場合のみ、改行ポイントを与える。『<>』で区切ると複数可能
//#######################################

$FORM[1]['titl'] = "件名";
$FORM[1]['name'] = "kubn";
$FORM[1]['type'] = "select";
$FORM[1]['hiss'] = 1;
$FORM[1]['subj'] = 1;
$FORM[1]['clss'] = "input-m";
$FORM[1]['arry'][1] = "ReCubeFormについて";
$FORM[1]['arry'][2] = "バグ報告";
$FORM[1]['arry'][3] = "サイト制作の依頼";
$FORM[1]['arry'][4] = "要望など";
$FORM[1]['arry'][5] = "ライセンスについて";
$FORM[1]['arry'][6] = "その他";

$FORM[2]['titl'] = "会社名";
$FORM[2]['name'] = "comp";
$FORM[2]['type'] = "text";
$FORM[2]['lens'] = 20;
$FORM[2]['clss'] = "input-m";

$FORM[3]['titl'] = "氏名";
$FORM[3]['name'] = "name";
$FORM[3]['type'] = "text";
$FORM[3]['hiss'] = 1;
$FORM[3]['lens'] = 20;
$FORM[3]['pint'] = "このようにコメントが入れられます。";
$FORM[3]['clss'] = "input-m";

$FORM[4]['titl'] = "E-MAIL";
$FORM[4]['name'] = "mail";
$FORM[4]['type'] = "text";
$FORM[4]['hiss'] = 1;
$FORM[4]['rtml'] = 1;
$FORM[4]['chck'] = 1;
$FORM[4]['lens'] = 100;
$FORM[4]['clss'] = "input-l";

$FORM[5]['titl'] = "MAIL確認";
$FORM[5]['name'] = "kmail";
$FORM[5]['type'] = "text";
$FORM[5]['hiss'] = 1;
$FORM[5]['reml'] = 4;
$FORM[5]['chck'] = 1;
$FORM[5]['lens'] = 100;
$FORM[5]['clss'] = "input-l";

$FORM[6]['titl'] = "サイト";
$FORM[6]['name'] = "URL";
$FORM[6]['type'] = "text";
$FORM[6]['chck'] = 2;
$FORM[6]['lens'] = 255;
$FORM[6]['clss'] = "input-l";

$FORM[7]['titl'] = "内容";
$FORM[7]['name'] = "naiyo";
$FORM[7]['type'] = "textarea";
$FORM[7]['hiss'] = 1;
$FORM[7]['lens'] = 3000;
$FORM[7]['rows'] = 6;
$FORM[7]['cols'] = 70;
$FORM[7]['clss'] = "input-l";

$FORM[8]['titl'] = "ご連絡方法";
$FORM[8]['name'] = "retn";
$FORM[8]['type'] = "radio";
$FORM[8]['hiss'] = 1;
$FORM[8]['clss'] = "input-ck";
$FORM[8]['arry'][1] = "電話でご連絡";
$FORM[8]['arry'][2] = "メールでご連絡";
$FORM[8]['arry'][3] = "連絡はいらない";

$FORM[9]['titl'] = "ご連絡日時";
$FORM[9]['name'] = "dat";
$FORM[9]['type'] = "date";
$FORM[9]['clss'] = "input-m";

$FORM[10]['titl'] = "用意できるもの";
$FORM[10]['name'] = "retns";
$FORM[10]['type'] = "checkbox";
$FORM[10]['clss'] = "input-ck";
$FORM[10]['arry'][1] = "履歴書";
$FORM[10]['arry'][2] = "印鑑";
$FORM[10]['arry'][3] = "筆記用具";
$FORM[10]['arry'][4] = "心意気";
$FORM[10]['arry'][5] = "執念";
$FORM[10]['arry'][6] = "勇気";
$FORM[10]['retn'] = "印鑑<>心意気";//印鑑と心意気の後で改行

?>