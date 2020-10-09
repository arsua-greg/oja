
use Net::SMTP;
if($config{'POPserver'}){
	use Net::POP3;
}

sub _SENDMAIL {
	my($to,$from,$name,$subject,$body,$attached,$htmlmail) = @_;
	if($config{'POPserver'}){
		$POP = Net::POP3->new($config{'POPserver'});
		$POP->login($config{'POPuser'},$config{'POPpasswd'});
		$POP->quit;
	}
	($sec,$min,$hour,$mday,$mon,$year,$wday) = localtime(time);
	@week = ('Sun','Mon','Tue','Wed','Thu','Fri','Sat');
	@month = ('Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec');
	$smtpdate = sprintf("%s, %d %s %04d %02d:%02d:%02d +0900 (JST)",$week[$wday],$mday,$month[$mon],$year+1900,$hour,$min,$sec);
	$SMTP = Net::SMTP->new($config{'SMTPserver'}, Hello=>$config{'SMTPserver'});
	$SMTP->mail($from);
	$SMTP->to($to);
	$SMTP->data();
	$SMTP->datasend("Date: ${smtpdate}\n");
	$SMTP->datasend(&_MAILHEADER($to,$from,$name,$subject,$body,$attached,$htmlmail));
	$SMTP->dataend();
	$SMTP->quit;
}

$about = 'process';
