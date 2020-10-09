// Thanks 
var mfpGET = new Object();

function mfpSanitizing(str){
	var before = new Array('&','"',"'","<",">","\n","\t","\\n");
	var after = new Array('&amp;','&quot;','&rsquo;',"&lt;","&gt;","<br />"," ","<br />");
	for(var i=0;i<before.length;i++)
		str = str.replace(new RegExp(before[i],'g'), after[i]);
	return str;
}
function mfpClearCookie(){
	var current_dir = location.pathname;
	var current_dirs = new Array();
	current_dirs = current_dir.split("/");
	if(current_dirs[current_dirs.length-1] != ""){
		current_dirs[current_dirs.length-1] = "";
		current_dir = current_dirs.join("/");
	}
	document.cookie = '_MFP=; path=' + current_dir + '; expires=';
}
mfpClearCookie();
if(location.search){
	var gets = new Array();
	gets = (location.search.substring(1,location.search.length)).split('&');
	for(var i=0;i<gets.length;i++){
		var get = new Array();
		get = gets[i].split('=');
		mfpGET[decodeURI(get[0])] = mfpSanitizing(decodeURI(get[1]));
	}
}

if(mfpGET['no']){
	document.write('<div id="mfp_thanks">受付番号&nbsp;<strong>'+mfpGET['no']+'</strong>&nbsp;を受け付けました</div>');
}
// Thanks 
