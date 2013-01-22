<?php
include_once('db.php');
include_once('functions.php');
session_name('financeLogin');
// Starting the session
session_set_cookie_params(2*7*24*60*60);
// Making the cookie live for 2 weeks
session_start();
class User
{
	public function __construct()
	{
		$db=new DB();
	}
	public function loginUser($email,$password)
	{
		$function=new Functions();
		$email=$function->dbCheckValues($email);
		$password=$function->dbCheckValues(md5($password));
		$sql=mysql_query("SELECT uid FROM ".USER_TABLE." WHERE email='$email' and password='$password'");
		$row = mysql_fetch_assoc($sql);
		if(mysql_num_rows($sql)==1)
		{
			$_SESSION['uid'] = $row['uid'];
			// Store some data in the session
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
	public function logoutUser()
	{
		$userid=$this->getCurrenUserId();
		$_SESSION = array();
		session_destroy();
	}
	public function getCurrenUserId()
	{
		$userid=$_SESSION['uid'];
		return $userid;
	}
	public function getFirstName($uid)
	{
		$function=new Functions();
		$uid=$function->dbCheckValues($uid);
		$sql=mysql_query("SELECT firstname FROM ".USER_TABLE." WHERE uid='$uid'");
		$row = mysql_fetch_assoc($sql);
		if(mysql_num_rows($sql)==1)
		{
			return $row['firstname'];
		}
		else
		{
			return FALSE;
		}
	}
	public function getLastName($uid)
	{
		$function=new Functions();
		$uid=$function->dbCheckValues($uid);
		$sql=mysql_query("SELECT lastname FROM ".USER_TABLE." WHERE uid='$uid'");
		$row = mysql_fetch_assoc($sql);
		if(mysql_num_rows($sql)==1)
		{
			return $row['lastname'];
		}
		else
		{
			return FALSE;
		}
	}
	public function getEmail($uid)
	{
		$function=new Functions();
		$uid=$function->dbCheckValues($uid);
		$sql=mysql_query("SELECT email FROM ".USER_TABLE." WHERE uid='$uid'");
		$row = mysql_fetch_assoc($sql);
		if(mysql_num_rows($sql)==1)
		{
			return $row['email'];
		}
		else
		{
			return FALSE;
		}
	}
}
?>