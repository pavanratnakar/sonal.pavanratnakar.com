<?php
include_once ('../class/file.php');
include_once ('../class/user.php');
include_once ('../class/functions.php');
include_once ('../class/mailClass.php');
$function=new Functions();
$user=new User();
$fullName=$user->getFirstName($_SESSION['uid']).'_'.$user->getLastName($_SESSION['uid']);
$type=$function->checkValues($_REQUEST['ref']);
$toDate=$function->checkValues($_REQUEST['toDate']);
$fromDate=$function->checkValues($_REQUEST['fromDate']);
$fileName=null;
if($type=='generateTimeSheetFile')
{
	$content=$_REQUEST['timesheetExpenseBuffer'];
	$file=new File();
	if($toDate===$fromDate)
	{
		$fileName=$fullName.'_timesheet_report_'.$toDate.'.xls';
	}
	else
	{
		$fileName=$fullName.'_timesheet_report_'.$toDate.'_'.$fromDate.'.xls';
	}
	echo $file->createFile($content,$fileName);
	exit;
}
else if($type=='generateExpenseFile')
{
	$content=$_REQUEST['expense01Buffer'];
	$file=new File();
	if($toDate===$fromDate)
	{
		$fileName=$fullName.'_expense_report_'.$toDate.'.xls';
	}
	else
	{
		$fileName=$fullName.'_expense_report_'.$toDate.'_'.$fromDate.'.xls';
	}
	echo $file->createFile($content,$fileName);
}
else if($type=='mailTimeSheetFile')
{
	$content=$_REQUEST['timesheetExpenseBuffer'];
	$file=new File();
	if($doDate===$fromDate)
	{
		$fileName=$fullName.'_expense_report_'.$toDate.'.xls';
		$dateString=$toDate;
	}
	else
	{
		$fileName=$fullName.'_expense_report_'.$toDate.'_'.$fromDate.'.xls';
		$dateString=$toDate.'_'.$fromDate;
	}
	$fullName=str_replace("_"," ",$fullName);
	$dateString=str_replace("_"," - ",$dateString);
	$mailClass=new MailClass();
	$file->saveFile($content,$fileName);
	if($mailClass->sendMailFunction($user->getEmail($_SESSION['uid']),$fullName,$fullName.' : Report | '.$dateString,'Hi Sunitha,<br/><br/>Please find attached report for <b>'.$dateString.'</b>.<br/><br/>Regards,<br/>'.$fullName,$fileName))
	{
		
		echo 'Mail Successfully Sent';
	}
	else
	{
		echo 'Mail could not be sent';
	}
	$file->removeFile($fileName);
}
else if($type=='mailExpenseFile')
{
	$content=$_REQUEST['mailExpenseBuffer'];
	$file=new File();
	if($doDate===$fromDate)
	{
		$fileName=$fullName.'_expense_report_'.$toDate.'.xls';
		$dateString=$toDate;
	}
	else
	{
		$fileName=$fullName.'_expense_report_'.$toDate.'_'.$fromDate.'.xls';
		$dateString=$toDate.'_'.$fromDate;
	}
	$fullName=str_replace("_"," ",$fullName);
	$dateString=str_replace("_"," - ",$dateString);
	$mailClass=new MailClass();
	$file->saveFile($content,$fileName);
	if($mailClass->sendMailFunction($user->getEmail($_SESSION['uid']),$fullName,$fullName.' : Report | '.$dateString,'Hi '.RECEIPT_NAME.',<br/><br/>Please find attached report for <b>'.$dateString.'</b>.<br/><br/>Regards,<br/>'.$fullName,$fileName))
	{
		
		echo 'Mail Successfully Sent';
	}
	else
	{
		echo 'Mail could not be sent';
	}
	$file->removeFile($fileName);

}
?>