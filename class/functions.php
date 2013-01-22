<?php
class Functions
{
	public function __construct()
	{
	}
	public function checkValues($value)
	{
		$value= nl2br($value);
		$value = trim($value);
		if (get_magic_quotes_gpc()) 
		{
			$value = stripslashes($value);
		}
		$value = strtr($value,array_flip(get_html_translation_table(HTML_ENTITIES)));
		$value = strip_tags($value,'<br>');
		return $value;
	}
	public function dbCheckValues($value)
	{
		$value = mysql_real_escape_string($value);
		return $value;
	}
	public function waveTime($date_created)
	{
		/*$t = strtotime($t);
		if(date('d')==date('d',$t)) return date('h:i A',$t);
		return date('F jS Y h:i A',$t);
		*/
		$date_created= strtotime($date_created);
		$TimeSpent=time()-$date_created;
		$days = floor($TimeSpent / (60 * 60 * 24));
		$remainder = $TimeSpent % (60 * 60 * 24);
		$hours = floor($remainder / (60 * 60));
		$remainder = $remainder % (60 * 60);
		$minutes = floor($remainder / 60);
		$seconds = $remainder % 60;
		if($days<0)
			$days = 0;
		if($remainder<0)
			$remainder = 0;
		if($hours<0)
			$hours = 0;
		if($remainder<0)
			$remainder = 0;
		if($minutes<0)
			$minutes = 0;
		if($seconds<0)
			$seconds = 0;
		if($days == 0 && $hours == 0 && $minutes == 0)
		{
			$time="few seconds ago";		
		}
		elseif($days == 0 && $hours == 0)
		{
			if($minutes==1)
				$time=$minutes.' minute ago';
			else
				$time=$minutes.' minutes ago';
		}
		elseif($days == 0)
		{
			if($hours==1)
				$time='about an hour ago';	
			else
				$time=$hours.' hours ago';	
		}
		else
		{
			if($days<=7)
			{
				$time=date('l \a\t g:i a', $date_created);
			}
			else
			{
				$time=date('F j \a\t g:i a', $date_created);
			}
		}
		return $time;
	}
	public function ip_address_to_number($IPaddress)
	{
		if ($IPaddress == "") {
			return 0;
		} else 
		{
			return ip2long($IPaddress);
		}
	}
	public function date_php2sql($date)
	{
		return date('Y-m-d', strtotime($date));
	}
	public function checkSize($word,$lower,$upper)
	{
		if(strlen($word)<(int)$lower || strlen($word)>(int)$upper)
		{
			return TRUE;
		}
		return FALSE;
	}
	public function checkEmail($email)
	{
		return preg_match("/^[\.A-z0-9_\-\+]+[@][A-z0-9_\-]+([.][A-z0-9_\-]+)+[A-z]{1,4}$/", $email);
	}
	public function checkSecuredPage()
	{
		if(!$_SESSION['uid'])
		{
			header("Location: home");
			exit;
		}
	}
	public function curPageURL() 
	{
		$pageURL = 'http';
		if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
			$pageURL .= "://";
		if ($_SERVER["SERVER_PORT"] != "80") 
		{
			list($shorturl) = explode('?logoff', $_SERVER["REQUEST_URI"]);
			$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$shorturl;
		} else 
		{
			list($shorturl) = explode('?logoff', $_SERVER["REQUEST_URI"]);
			$pageURL .= $_SERVER["SERVER_NAME"].$shorturl;
		}
		return $pageURL;
	}
	/* FUNCTION FOR HYPERLINKING URLS */
	public function urlConverter($url,$targetBlank=true,$linkMaxLen=250)
	{
		$target=$targetBlank ? " target=\"_blank\" " : "";
		$url = preg_replace("#(^|[\n ])([\w]+?://[\w]+[^ \"\n\r\t<]*)#ise", "'\\1<a $target  href=\"\\2\" >\\2</a>'", $url);
		$url = preg_replace("#(^|[\n ])((www|ftp)\.[^ \"\t\n\r<]*)#ise", "'\\1<a $target  href=\"http://\\2\" >\\2</a>'", $url);
		return $url;
	}
	/* FUNCTION FOR HYPERLINKING URLS */
	/* Converting a PHP array into a javascript object */
	public function formatJSON($arr)
	{
		$ret = array();
		foreach($arr as $k=>$v)
		{
			if(is_numeric($v))
				$ret[]=$k.':'.$v;
			else
				$ret[]=$k.':"'.htmlspecialchars($v).'"';
		}
		return '{'.join(',',$ret).'}';
	}
	/* CHECK BETWEEN TWO DATES */
	public function dateBetween($date1,$date2)
	{
		$firstDate1 = strtotime($date1) ;
		$firstDate2 = strtotime($date2) ;
		return $firstDate1-$firstDate2;
	}
	public function array_is_associative ($array)
	{
		if ( is_array($array) && ! empty($array) )
		{
			for ( $iterator = count($array) - 1; $iterator; $iterator-- )
			{
				if ( ! array_key_exists($iterator, $array) ) { return true; }
			}
			return ! array_key_exists(0, $array);
		}
		return false;
	}
	public function firstOfMonth() 
	{
		return date("Y-m-d", strtotime(date('m').'/01/'.date('Y').' 00:00:00'));
	}
	public function lastOfMonth() 
	{
		return date("Y-m-d", strtotime('-1 second',strtotime('+1 month',strtotime(date('m').'/01/'.date('Y').' 00:00:00'))));
	}
}
?>