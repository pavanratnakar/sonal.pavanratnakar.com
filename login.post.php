<?php
ini_set("display_errors", 1);
error_reporting(E_ALL);
define('VALID_ACL_',		true);
define('LOGIN_METHOD',	'email');
define('SUCCESS_URL',	'index.php');
$ACL_LANG = array 
(
	'USERNAME'			=>	'Username',
	'EMAIL'				=>	'E-mail',
	'PASSWORD'			=>	'Password',
	'LOGIN'				=>	'Login',
	'SESSION_ACTIVE'	=>	'Your Session is already active, click <a href="'.SUCCESS_URL.'">here</a> to continue.',
	'LOGIN_SUCCESS'		=>	'You have successfuly authorized, click <a href="'.SUCCESS_URL.'">here</a> to continue.',
	'LOGIN_FAILED'		=>	'Login Failed: wrong combination of '.((LOGIN_METHOD=='user'||LOGIN_METHOD=='both')?'Username':''). 
								((LOGIN_METHOD=='both')?'/':'').
								((LOGIN_METHOD=='email'||LOGIN_METHOD=='both')?'email':'').
								' and password.',
);
require('login.class.php');
$acl = new Authorization;
$status = $acl->check_status();
if($status)
{
	$login_detail = array(
			"status" => true,
			"message" => $ACL_LANG['SESSION_ACTIVE'],
			"url" => SUCCESS_URL
			);
	$response = $_GET["jsoncallback"] . "(" . json_encode($login_detail) . ")";
	echo $response;
}
else
{
	if($_SERVER['REQUEST_METHOD']=='GET')
	{
		$login_detail = array(
			"status" => false,
			"message" => $acl->form()
			);
		$response = $_GET["jsoncallback"] . "(" . json_encode($login_detail) . ")";
		echo $response;
	}
	else
	{
		$u = (!empty($_POST['u']))?trim($_POST['u']):false;	// retrive user var
		$p = (!empty($_POST['p']))?trim($_POST['p']):false;	// retrive password var
		$is_auth = $acl->signin($u,$p);
		if($is_auth)
		{
			$login_detail = array(
				"status" => true,
				"message" => $ACL_LANG['LOGIN_SUCCESS'],
				"url" => SUCCESS_URL
				);
			$response = $_GET["jsoncallback"] . "(" . json_encode($login_detail) . ")";
			echo $response;
		}
		else
		{
			$login_detail = array(
				"status" => false,
				"message" => $ACL_LANG['LOGIN_FAILED']
				);
			$response = $_GET["jsoncallback"] . "(" . json_encode($login_detail) . ")";
			echo $response;
		}
	}
}
unset($acl);
?>