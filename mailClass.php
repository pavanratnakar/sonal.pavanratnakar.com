<?php
include_once ('functions.php');
include_once ("../../plugins/phpmailer/class.phpmailer.php");
include_once('../cfg/config.cfg.php');
class MailClass
{
	public function __construct()
	{
		/*$db=new DB();*/
	}
	public function sendMailFunction($from,$name,$subject,$message,$file=null)
	{
		$function=new Functions();
		$userip = $function->ip_address_to_number($_SERVER['REMOTE_ADDR']);
		$mail = new PHPMailer();
		//$mail->IsMail();
		$mail->SetFrom($from,$name);
		$mail->AddReplyTo($from);
		$mail->AddAddress(MAILTO);
		$mail->AddBCC($from);
		$mail->Subject = $subject;
		$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test
		if($file)
		{
			$mail->AddAttachment($file,$file); // attach files/invoice-user-1234.pdf, 
		}
		$mail->MsgHTML($message);
		$mail->Send();
		if(!$mail->Send()) 
		{
			return FALSE;
		}
		else 
		{
			return TRUE;
		}
	}
}
?>