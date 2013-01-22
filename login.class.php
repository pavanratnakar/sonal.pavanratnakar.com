<?php
if(!defined('VALID_ACL_')) exit('direct access is not allowed.');
include_once('class/user.php');
class Authorization
{
	public function check_status()
	{
		if(empty($_SESSION['exp_user']) || @$_SESSION['exp_user']['expires'] < time())
		{
			return false;
		}
		else
		{
			return true;
		}
	}
	public function	form()
	{
		global $ACL_LANG;
		$htmlForm =	'<form id="frmlogin">'.'<label>';
		switch(LOGIN_METHOD)
		{
			case 'both':
				$htmlForm .= $ACL_LANG['USERNAME'].'/'.$ACL_LANG['EMAIL'];
				break;
			case 'email':
				$htmlForm .= $ACL_LANG['EMAIL'];
				break;
			default:
				$htmlForm .= $ACL_LANG['USERNAME'];
				break;
		}						
		$htmlForm .= ':</label>'.
						 '<input type="text" name="u" id="u" class="textfield" />'.
						 '<label>'.$ACL_LANG['PASSWORD'].'</label>'.
						 '<input type="password" name="p" id="p" class="textfield" />'.
						 '<input type="submit" name="btn" id="btn" class="buttonfield" value="'.$ACL_LANG['LOGIN'].'" />'.
						 '</form>';
		return $htmlForm;
	}
	public function signin($u,$p)
	{
		$user=new User();
		return $user->loginUser($u,$p);
	}
}
?>