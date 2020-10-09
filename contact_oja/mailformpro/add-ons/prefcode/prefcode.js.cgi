
if($_GET{'zip'} ne $null){
	$path = sprintf("$config{'dir.AddOns'}/prefcode/%02d.cgi",substr($_GET{'zip'},0,2));
	if(-f $path){
		@zip = &_DB($path);
		@zip = grep(/\"$_GET{'zip'}\"/,@zip);
		if(@zip > 0){
			$zip[0] =~ s/\"//ig;
			@zip = split(/\,/,$zip[0]);
			$js =  "callbackMFPZip(true,\"$_GET{'a1'}\",\"$_GET{'a2'}\",\"$_GET{'a3'}\",\"${zip[6]}\",\"${zip[7]}\",\"${zip[8]}\")\n";
		}
	}
}
