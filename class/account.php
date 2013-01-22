<?php
include_once('db.php');
include_once('user.php');
include_once ('functions.php');
session_name('financeLogin');
// Starting the session
session_set_cookie_params(2*7*24*60*60);
// Making the cookie live for 2 weeks
session_start();
class Account
{
	public function __construct()
	{
		$db=new DB();
	}
	public function addDetails($invdate,$time,$client_id,$amount,$contactperson,$note,$discussion,$outcome)
	{
		$function=new Functions();
		$user=new User();
		$userid=$user->getCurrenUserId();
		$invdate = $function->date_php2sql($function->dbCheckValues($invdate));
		$time=$function->dbCheckValues($time);
		$client_id=$function->dbCheckValues($client_id);
		$amount=$function->dbCheckValues($amount);
		$contactperson=$function->dbCheckValues($contactperson);
		$note=$function->dbCheckValues($note);
		$discussion=$function->dbCheckValues($discussion);
		$outcome=$function->dbCheckValues($outcome);
		$userip = $function->ip_address_to_number($_SERVER['REMOTE_ADDR']);
		$result=mysql_query("INSERT INTO ".INV_TABLE."(invdate,amount,note,userip,userid,registerDate,discussion,time,outcome,contactperson,client_id) VALUES('$invdate','$amount','$note','$userip','$userid',now(),'$discussion','$time','$outcome','$contactperson','$client_id')");
		if($result)
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
	public function editDetails($invdate,$time,$client_id,$amount,$contactperson,$note,$discussion,$outcome,$id)
	{
		$function=new Functions();
		$user=new User();
		$userid=$user->getCurrenUserId();
		$invdate = $function->date_php2sql($function->dbCheckValues($invdate));
		$time=$function->dbCheckValues($time);
		$client_id=$function->dbCheckValues($client_id);
		$amount=$function->dbCheckValues($amount);
		$contactperson=$function->dbCheckValues($contactperson);
		$note=$function->dbCheckValues($note);
		$discussion=$function->dbCheckValues($discussion);
		$outcome=$function->dbCheckValues($outcome);
		$id=$function->dbCheckValues($id);
		$userip = $function->ip_address_to_number($_SERVER['REMOTE_ADDR']);
		mysql_query("UPDATE ".INV_TABLE." SET invdate='$invdate',time='$time',client_id='$client_id',amount='$amount',contactperson='$contactperson',note='$note',discussion='$discussion',outcome='$outcome',userip='$userip' WHERE id='$id' AND userid='$userid'");
		if(mysql_affected_rows()>=1)
		{
			mysql_query("COMMIT");
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
	public function deleteDetails($id)
	{
		$function=new Functions();
		$user=new User();
		$id=$function->dbCheckValues($id);
		$userid=$user->getCurrenUserId();
		mysql_query("DELETE FROM ".INV_TABLE." WHERE id='".$id."' AND userid='$userid'");
		if(mysql_affected_rows()>=1)
		{
			mysql_query("COMMIT");
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
	public function getDetails($toDate,$fromDate,$page,$limit,$sidx,$sord,$wh="")
	{
		$function=new Functions();
		$toDate = $function->date_php2sql($function->dbCheckValues($toDate));
		$fromDate = $function->date_php2sql($function->dbCheckValues($fromDate));
		$page=$function->dbCheckValues($page);
		$limit=$function->dbCheckValues($limit);
		$sidx=$function->dbCheckValues($sidx);
		$sord=$function->dbCheckValues($sord);
		$wh=$function->dbCheckValues($wh);
		$result = mysql_query("SELECT COUNT(*) AS count FROM ".INV_TABLE." a WHERE invdate between '".$toDate."' and '".$fromDate."' ".$wh);
		$row = mysql_fetch_array($result,MYSQL_ASSOC);
		$count = $row['count'];
		if( $count >0 ) 
		{
			$total_pages = ceil($count/$limit);
		} else 
		{
			$total_pages = 0;
		}
		if ($page > $total_pages) $page=$total_pages;
		$start = $limit*$page - $limit; // do not put $limit*($page - 1)
		if ($start<0) $start = 0;
		$SQL = "SELECT a.id, a.invdate, a.amount, a.note, a.registerDate, a.updateDate, c.firstname, a.discussion, a.time, a.outcome, d.contact_number, b.name as expense_client FROM ".INV_TABLE." a,".CLIENT." b,".USER_TABLE." c,".CONTACT." d WHERE a.userid=c.uid AND a.client_id=b.client_id AND a.contactperson=d.contact_id AND a.invdate between '".$toDate."' and '".$fromDate."' ".$wh." ORDER BY ".$sidx." ". $sord." LIMIT ".$start." , ".$limit;
		$result = mysql_query( $SQL ) or die("Could not execute query.".mysql_error());
        $responce->page = $page;
        $responce->total = $total_pages;
        $responce->records = $count;
        $i=0; $amttot=0; $taxtot=0; $total=0;
		while($row = mysql_fetch_array($result,MYSQL_ASSOC)) {
			$amttot += $row[amount];
			$responce->rows[$i]['id']=$row[id];
			if($row[updateDate]=='0000-00-00 00:00:00')
			{
				$updateDate=$row[registerDate];
			}
			else
			{
				$updateDate=$row[updateDate];
			}
            $responce->rows[$i]['cell']=array($row[id], $row[invdate],$row['time'],$row[expense_client],$row['amount'],$row[contact_number],$row[note],$row[discussion],$row[outcome],$row[firstname],$updateDate);
            $i++;
		}
		$responce->userdata['amount'] = $amttot;
		$responce->userdata['invdate'] = 'Total';
		return $responce;
	}
	public function getCurrentUserDetails($toDate,$fromDate,$page,$limit,$sidx,$sord,$wh="")
	{
		$function=new Functions();
		$user=new User();
		$userid=$user->getCurrenUserId();
		$toDate = $function->date_php2sql($function->dbCheckValues($toDate));
		$fromDate = $function->date_php2sql($function->dbCheckValues($fromDate));
		$page=$function->dbCheckValues($page);
		$limit=$function->dbCheckValues($limit);
		$sidx=$function->dbCheckValues($sidx);
		$sord=$function->dbCheckValues($sord);
		$wh=$function->dbCheckValues($wh);
		$result = mysql_query("SELECT COUNT(*) AS count FROM ".INV_TABLE." a WHERE invdate between '".$toDate."' and '".$fromDate."' AND userid='$userid' ".$wh);
		$row = mysql_fetch_array($result,MYSQL_ASSOC);
		$count = $row['count'];
		if( $count >0 ) 
		{
			$total_pages = ceil($count/$limit);
		} else 
		{
			$total_pages = 0;
		}
		if ($page > $total_pages) $page=$total_pages;
		$start = $limit*$page - $limit; // do not put $limit*($page - 1)
		if ($start<0) $start = 0;
		$SQL = "SELECT a.id, a.invdate, a.amount, a.note, a.registerDate, a.updateDate, c.firstname, a.discussion, a.time, a.outcome, d.contact_number, b.name as expense_client FROM ".INV_TABLE." a,".CLIENT." b,".USER_TABLE." c,".CONTACT." d WHERE a.userid='$userid' AND a.userid=c.uid AND a.client_id=b.client_id AND a.contactperson=d.contact_id AND a.invdate between '".$toDate."' and '".$fromDate."' ".$wh." ORDER BY ".$sidx." ". $sord." LIMIT ".$start." , ".$limit;
		$result = mysql_query( $SQL ) or die("Could not execute query.".mysql_error());
        $responce->page = $page;
        $responce->total = $total_pages;
        $responce->records = $count;
        $i=0; $amttot=0; $taxtot=0; $total=0;
		while($row = mysql_fetch_array($result,MYSQL_ASSOC)) {
			$amttot += $row[amount];
			$responce->rows[$i]['id']=$row[id];
			if($row[updateDate]=='0000-00-00 00:00:00')
			{
				$updateDate=$row[registerDate];
			}
			else
			{
				$updateDate=$row[updateDate];
			}
            $responce->rows[$i]['cell']=array($row[id], $row[invdate],$row['time'],$row[expense_client],$row['amount'],$row[contact_number],$row[note],$row[discussion],$row[outcome],$row[firstname],$updateDate);
            $i++;
		}
		$responce->userdata['amount'] = $amttot;
		$responce->userdata['invdate'] = 'Total';
		return $responce;
	}
}
?>