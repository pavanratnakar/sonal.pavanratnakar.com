<?php
include_once ('../class/client.php');
include_once ('../class/functions.php');
$function=new Functions();
$type=$function->checkValues($_REQUEST['ref']);
$page = $function->checkValues($_REQUEST['page']); // get the requested page
$limit = $function->checkValues($_REQUEST['rows']); // get how many rows we want to have into the grid
$sidx = $function->checkValues($_REQUEST['sidx']); // get index row - i.e. user click to sort
$sord =$function->checkValues( $_REQUEST['sord']); // get the direction
$searchOn = $function->checkValues($_REQUEST['_search']);
if(isset($_REQUEST['_search']) && $searchOn!='false' && $type=='clientDetails')
{
	$fld =  $function->checkValues($_REQUEST['searchField']);
	if( $fld=='client_id' || $fld=='name') 
	{
		$fldata =  $function->checkValues($_REQUEST['searchString']);
		$foper =  $function->checkValues($_REQUEST['searchOper']);
		// costruct where
		$wh .= " AND ".$fld;
		switch ($foper) {
			case "bw":
				$fldata .= "%";
				$wh .= " LIKE '".$fldata."'";
				break;
			case "eq":
				if(is_numeric($fldata)) {
					$wh .= " = ".$fldata;
				} else {
					$wh .= " = '".$fldata."'";
				}
				break;
			case "ne":
				if(is_numeric($fldata)) {
					$wh .= " <> ".$fldata;
				} else {
					$wh .= " <> '".$fldata."'";
				}
				break;
			case "lt":
				if(is_numeric($fldata)) {
					$wh .= " < ".$fldata;
				} else {
					$wh .= " < '".$fldata."'";
				}
				break;
			case "le":
				if(is_numeric($fldata)) {
					$wh .= " <= ".$fldata;
				} else {
					$wh .= " <= '".$fldata."'";
				}
				break;
			case "gt":
				if(is_numeric($fldata)) {
					$wh .= " > ".$fldata;
				} else {
					$wh .= " > '".$fldata."'";
				}
				break;
			case "ge":
				if(is_numeric($fldata)) {
					$wh .= " >= ".$fldata;
				} else {
					$wh .= " >= '".$fldata."'";
				}
				break;
			case "ew":
				$wh .= " LIKE '%".$fldata."'";
				break;
			case "ew":
				$wh .= " LIKE '%".$fldata."%'";
				break;
			default :
				$wh = "";
		}
	}
	$client=new Client();
	$response=$client->getDetails($page,$limit,$sidx,$sord,$wh);
	echo json_encode($response);
	unset($response);
}
else if($type=='clientSelect')
{
	$client=new Client();
	$clientArray= $client->getClients();
	$response = json_encode($clientArray);
	echo $response;
}
else if($type=='clientDetails')
{
	$client=new Client();
	if(!$sidx) 
	{
		$sidx =1;
	}
	$totalrows = isset($_REQUEST['totalrows']) ? $function->checkValues($_REQUEST['totalrows']): false;
	if($totalrows) 
	{	
		$limit = $totalrows;
	}
	$response=$client->getDetails($page,$limit,$sidx,$sord);
	echo json_encode($response);
	unset($response);
}
else if($type=='clientOperation')
{
	$oper=$function->checkValues($_REQUEST['oper']);
	if($oper=='add')
	{
		$client=new Client();
		$response=$client->addDetails(
			$function->checkValues($_POST['name'])
		);
		if($response)
		{
			$status=TRUE;
			$message="Details Added";
		}
		else
		{
			$status=FALSE;
			$message="Details could not be added";
		}
		$addOperation= array(
		"status" => $status,
		"message" => $message
		);
		$response = $_POST["jsoncallback"] . "(" . json_encode($addOperation) . ")";
		echo $response;
		unset($response);
	}
	else if($oper=='edit')
	{
		$client=new Client();
		$response=$client->editDetails(
			$function->checkValues($_POST['name']),
			$function->checkValues($_POST['id'])
		);
		if($response)
		{
			$status=TRUE;
			$message="Details Edited";
		}
		else
		{
			$status=FALSE;
			$message="Details could not be edited";
		}
		$addOperation= array(
		"status" => $status,
		"message" => $message
		);
		$response = $_POST["jsoncallback"] . "(" . json_encode($addOperation) . ")";
		echo $response;
		unset($response);
	}
	else if($oper=='del')
	{
		$client=new Client();
		$response=$client->deleteDetails($function->checkValues($_POST['id']));
		if($response)
		{
			$status=TRUE;
			$message="Details Deleted";
		}
		else
		{
			$status=FALSE;
			$message="Details could not be deleted";
		}
		$deleteOperation= array(
		"status" => $status,
		"message" => $message
		);
		$response = $_POST["jsoncallback"] . "(" . json_encode($deleteOperation) . ")";
		echo $response;
		unset($response);
	}
}
?>