<?php
include_once('db.php');
include_once ('functions.php');
session_name('financeLogin');
// Starting the session
session_set_cookie_params(2*7*24*60*60);
// Making the cookie live for 2 weeks
session_start();
class Client
{
	public function __construct()
	{
		$db=new DB();
	}
	public function getClients()
	{
		$sql = mysql_query("SELECT * FROM ".CLIENT." order by name");
		$i=1;
		while($row=mysql_fetch_assoc($sql))
		{
			$client_array .= $row['client_id'].':'.$row['name'];
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
		$result = mysql_query("SELECT COUNT(*) AS count FROM ".CLIENT." WHERE 1=1 ".$wh);
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
		$SQL = "SELECT client_id, name FROM ".CLIENT." WHERE 1=1 ".$wh." ORDER BY ".$sidx." ". $sord." LIMIT ".$start." , ".$limit;
		$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
        $responce->page = $page;
        $responce->total = $total_pages;
        $responce->records = $count;
        $i=0;
		while($row = mysql_fetch_array($result,MYSQL_ASSOC)) 
		{
			$responce->rows[$i]['client_id']=$row[client_id];
            $responce->rows[$i]['cell']=array($row[client_id],$row[name]);
            $i++;
		}
		return $responce;
	}
	public function addDetails($name)
	{
		$function=new Functions();
		$name=$function->dbCheckValues($name);
		$result=mysql_query("INSERT INTO ".CLIENT."(name) VALUES('$name')");
		if($result)
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
	public function editDetails($name,$id)
	{
		$function=new Functions();
		$name=$function->dbCheckValues($name);
		$id=$function->dbCheckValues($id);
		mysql_query("UPDATE ".CLIENT." SET name='$name' WHERE client_id='$id'");
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
		$id=$function->dbCheckValues($id);
		mysql_query("DELETE FROM ".CLIENT." WHERE client_id='".$id."'");
		if(mysql_affected_rows()>=1)
		{
			mysql_query("COMMIT");
			return TRUE;
			/*mysql_query("DELETE FROM ".ORDERS." WHERE id='$id'");
			if(mysql_affected_rows()>=1)
			{
				mysql_query("COMMIT");
				return TRUE;
			}
			else
			{
				return FALSE;
			}
			*/
		}
		else
		{
			return FALSE;
		}
	}
}
?>