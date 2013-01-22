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
		$mail->IsMail();
		$mail->SetFrom($from,$name);
		$mail->AddReplyTo($from);
		$mail->AddAddress(MAILTO);
		if($from!=ADMIN_EMAIL)
		{
			$mail->AddCC(ADMIN_EMAIL);
		}
		$mail->AddCC($from);
		$mail->AddCC(MAILCC);
		$mail->AddBCC(OWNER_EMAIL);
		$mail->Subject = $subject;
		$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test
		if($file)
		{
			$mail->AddAttachment($file,$file);
		}
		$mail->MsgHTML($message);
		$status = $mail->Send();
		if ($status) 
		{  
			return TRUE;
		} 
		else 
		{  
			return FALSE;
		}
	}
}
?>