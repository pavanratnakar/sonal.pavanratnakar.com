<?php
include_once('db.php');
include_once ('functions.php');
session_name('financeLogin');
// Starting the session
session_set_cookie_params(2*7*24*60*60);
// Making the cookie live for 2 weeks
session_start();
class File
{
	private $location='..\temp\\';
	public function __construct()
	{
	}
	public function createFile($content,$fileName)
	{
		header("Content-type: application/x-msdownload"); 
		header("Content-Disposition: attachment; filename=".$fileName);
		header("Pragma: no-cache");
		header("Expires: 0");
		$buffer = $content;
		try
		{
			return $buffer;
		}
		catch(Exception $e)
		{
		}
	}
	public function saveFile($content,$fileName)
	{
		$fp = fopen($fileName, 'a+') or die("can't open file");;
		fwrite($fp, $content);
		fclose($fp);
	}
	public function removeFile($myFile)
	{
		unlink($myFile);
	}
}
?>