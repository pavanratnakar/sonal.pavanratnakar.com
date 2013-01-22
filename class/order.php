<?php
include_once('db.php');
include_once('user.php');
include_once ('functions.php');
session_name('financeLogin');
// Starting the session
session_set_cookie_params(2*7*24*60*60);
// Making the cookie live for 2 weeks
session_start();
class Order
{
	public function __construct()
	{
		$db=new DB();
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
		$result = mysql_query("SELECT COUNT(*) AS count FROM ".ORDERS." WHERE invoiceDate between '".$toDate."' and '".$fromDate."' ".$wh);
		$row = mysql_fetch_array($result,MYSQL_ASSOC);
		$count = $row['count'];
		if( $count >0 ) {
			$total_pages = ceil($count/$limit);
		} else {
			$total_pages = 0;
		}
        if ($page > $total_pages) $page=$total_pages;
		$start = $limit*$page - $limit; // do not put $limit*($page - 1)
		if ($start<0) $start = 0;
        $SQL = "SELECT id, d.name as client,c.name as item, qty, total, registerDate, b.firstname, invoiceId, invoiceDate FROM ".ORDERS." a,".USER_TABLE." b,".PRODUCT." c,".CLIENT." d WHERE a.userid=b.uid AND a.client=d.client_id AND invoiceDate between '".$toDate."' and '".$fromDate."' AND a.item=c.product_id".$wh." ORDER BY $sidx $sord LIMIT $start , $limit";
		$result = mysql_query( $SQL ) or die("Couldn’t execute query.".mysql_error());
		$responce->page = $page;
        $responce->total = $total_pages;
        $responce->records = $count;
		$i=0; $amttot=0; $taxtot=0; $total=0;
		while($row = mysql_fetch_array($result,MYSQL_ASSOC)) {
			$responce->rows[$i]['id']=$row[id];
			if($row[updateDate]=='0000-00-00 00:00:00')
			{
				$updateDate=$row[registerDate];
			}
			else
			{
				$updateDate=$row[updateDate];
			}
			//$total=(($row[qty]*$row[unit])*(($row[tax]+100)/100))+$row[extra];
			//$total=number_format((($row[qty]*$row[unit])*(($row[tax]+100)/100))+$row[extra],2,'.',' ');
			$total = $row[total];
			$amttot += $total;
            $responce->rows[$i]['cell']=array($row[id],$row['client'],$row[invoiceId],$row[invoiceDate],$row[item],$row[qty],$row['firstname'],$total,$updateDate);
            $i++;
		}
		$responce->userdata['total'] = $amttot;
		$responce->userdata['firstname'] = 'Total:';
		return $responce;
	}
	public function addDetails($client,$item,$qty,$invoiceId,$invoiceDate,$total)
	{
		$function=new Functions();
		$user=new User();
		$userid=$user->getCurrenUserId();
		$client=$function->dbCheckValues($client);
		$item=$function->dbCheckValues($item);
		$qty=$function->dbCheckValues($qty);
		$invoiceId=$function->dbCheckValues($invoiceId);
		$invoiceDate = $function->date_php2sql($function->dbCheckValues($invoiceDate));
		$total=$function->dbCheckValues($total);
		$userip = $function->ip_address_to_number($_SERVER['REMOTE_ADDR']);
		$result=mysql_query("INSERT INTO ".ORDERS."(client,item,qty,registerDate,userid,userip,invoiceId,invoiceDate,total) VALUES('$client','$item','$qty',now(),'$userid','$userip','$invoiceId','$invoiceDate','$total')");
		if($result)
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
	public function editDetails($id,$client,$item,$qty,$invoiceId,$invoiceDate,$total)
	{
		$function=new Functions();
		$user=new User();
		$userid=$user->getCurrenUserId();
		$id=$function->dbCheckValues($id);
		$client=$function->dbCheckValues($client);
		$item=$function->dbCheckValues($item);
		$qty=$function->dbCheckValues($qty);
		$invoiceId=$function->dbCheckValues($invoiceId);
		$invoiceDate = $function->date_php2sql($function->dbCheckValues($invoiceDate));
		$total=$function->dbCheckValues($total);
		$userip = $function->ip_address_to_number($_SERVER['REMOTE_ADDR']);
		mysql_query("UPDATE ".ORDERS." SET client='$client',item='$item',qty='$qty',total='$total',updateDate=now(),userip='$userip',invoiceId='$invoiceId',invoiceDate='$invoiceDate' WHERE id='$id' AND userid='$userid'");
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
		$user=new User();
		$userid=$user->getCurrenUserId();
		$function=new Functions();
		$id=$function->dbCheckValues($id);
		mysql_query("DELETE FROM ".ORDERS." WHERE userid='$userid' AND id='$id'");
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
}
?>