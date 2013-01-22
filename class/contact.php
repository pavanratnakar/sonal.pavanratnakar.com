<?php
include_once('db.php');
include_once ('functions.php');
session_name('financeLogin');
// Starting the session
session_set_cookie_params(2*7*24*60*60);
// Making the cookie live for 2 weeks
session_start();
class Contact
{
	public function __construct()
	{
		$db=new DB();
	}
	public function getContacts()
	{
		$sql = mysql_query("SELECT * FROM ".CONTACT." order by name");
		$i=1;
		while($row=mysql_fetch_assoc($sql))
		{
			$client_array .= $row['contact_id'].':'.$row['name'];
			if(mysql_num_rows($sql)!=$i)
			{
				$client_array .=';';
			}
			$i++;
		}
		return $client_array;
	}
	public function getDetails($page,$limit,$sidx,$sord,$wh="")
	{
		$function=new Functions();
		$page=$function->dbCheckValues($page);
		$limit=$function->dbCheckValues($limit);
		$sidx=$function->dbCheckValues($sidx);
		$sord=$function->dbCheckValues($sord);
		$wh=$function->dbCheckValues($wh);
		$result = mysql_query("SELECT COUNT(*) AS count FROM ".CONTACT." WHERE 1=1 ".$wh);
		$row = mysql_fetch_array($result,MYSQL_ASSOC);
		$count = $row['count'];
		if( $count >0 ) 
		{
			$total_pages = ceil($count/$limit);
		} 
		else 
		{
			$total_pages = 0;
		}
        if ($page > $total_pages) $page=$total_pages;
		$start = $limit*$page - $limit; // do not put $limit*($page - 1)
        if ($start<0) $start = 0;
		$SQL = "SELECT a.contact_id, a.name, a.contact_number, b.name as client_name FROM ".CONTACT." a,".CLIENT." b WHERE a.client_id=b.client_id ".$wh." ORDER BY ".$sidx." ". $sord." LIMIT ".$start." , ".$limit;
		$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
        $responce->page = $page;
        $responce->total = $total_pages;
        $responce->records = $count;
        $i=0;
		while($row = mysql_fetch_array($result,MYSQL_ASSOC)) 
		{
			$responce->rows[$i]['contact_id']=$row[contact_id];
            $responce->rows[$i]['cell']=array($row[contact_id],$row['name'],$row['contact_number'],$row['client_name']);
            $i++;
		}
		return $responce;
	}
	public function addDetails($name,$contact_number,$client_id)
	{
		$function=new Functions();
		$name=$function->dbCheckValues($name);
		$contact_number=$function->dbCheckValues($contact_number);
		$client_id=$function->dbCheckValues($client_id);
		$result=mysql_query("INSERT INTO ".CONTACT."(name,contact_number,client_id) VALUES('$name','$contact_number','$client_id')");
		if($result)
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
	public function editDetails($name,$contact_number,$client_id,$contact_id)
	{
		$function=new Functions();
		$name=$function->dbCheckValues($name);
		$contact_number=$function->dbCheckValues($contact_number);
		$client_id=$function->dbCheckValues($client_id);
		$contact_id=$function->dbCheckValues($contact_id);
		mysql_query("UPDATE ".CONTACT." SET name='$name',contact_number='$contact_number',client_id='$client_id' WHERE contact_id='$contact_id'");
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
	public function deleteDetails($contact_id)
	{
		$function=new Functions();
		$contact_id=$function->dbCheckValues($contact_id);
		mysql_query("DELETE FROM ".CONTACT." WHERE contact_id='".$contact_id."'");
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