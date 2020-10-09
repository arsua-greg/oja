mfp.extend.event('ready',
	function(){
		var elm = mfp.d.createElement('div');
		elm.id = 'mfp_OperationCheck';
		mfp.Mfp.parentNode.insertBefore(elm,mfp.$('mfp_warning'));
		var src = mfp.$('mfpjs').src + '?module=check';
		if(mfp.$('mfpjs').src.indexOf('?') > -1)
			src = mfp.$('mfpjs').src + '&module=check';
		elm.innerHTML = 'mailformpro.cgi は正常に動作しています。<br /><a href="'+src+'" target="_blank" style="color: #FFF;">[ CGI動作チェックモジュールを実行する ]</a><br />この表示はconfig.cgiの設定により消すことができます。っていうか消して。';
		
		mfp.css(mfp.$('mfp_OperationCheck'),{
			"borderRadius": "5px",
			"fontSize": "16px",
			"lineHeight": "1.5em",
			"color": "#FFF",
			"margin": "10px auto",
			"boxShadow": "0px 2px 10px #666",
			"textAlign": "center",
			"padding": "10px 0px",
			"backgroundColor": '#C00'
		});
	}
);
