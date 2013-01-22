<?php
if(file_exists('cfg/config.cfg.php'))
{
   include_once('cfg/config.cfg.php');
}
else
{
   include_once('../cfg/config.cfg.php');
}
class DB
{
	private $connection;
	function __construct()
	{
		$connection=mysql_connect(DB_SERVER,DB_USERNAME,DB_PASSWORD) or die('We are facing some technical issue.Please try later on.');
		mysql_select_db(DB_DATABASE,$connection) or die('We are facing some technical issue.Please try later on.');
	}
	function __destruct()
	{
		//mysql_close($connection);
	}
}
?>