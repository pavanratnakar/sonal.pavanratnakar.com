<?php
include_once ('../class/account.php');
include_once ('../class/functions.php');
$function=new Functions();
$type=$function->checkValues($_REQUEST['ref']);
$page = $function->checkValues($_REQUEST['page']); // get the requested page
$limit = $function->checkValues($_REQUEST['rows']); // get how many rows we want to have into the grid
$sidx = $function->checkValues($_REQUEST['sidx']); // get index row - i.e. user click to sort
$sord =$function->checkValues( $_REQUEST['sord']); // get the direction
$searchOn = $function->checkValues($_REQUEST['_search']);
$toDate=$function->checkValues($_REQUEST['toDate']);
$fromDate=$function->checkValues($_REQUEST['fromDate']);
if(isset($_REQUEST['_search']) && $searchOn!='false' && $type=='expense')
{
	$fld =  $function->checkValues($_REQUEST['searchField']);
	if( $fld=='id' || $fld =='invdate' || $fld=='time' || $fld=='expense_client' || $fld=='contactperson' || $fld=='note' || $fld=='discussion' || $fld=='outcome' || $fld=='by') 
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
	$account=new Account();
	$response=$account->getDetails($toDate,$fromDate,$page,$limit,$sidx,$sord,$wh);
	echo json_encode($response);
	unset($response);
}
else if($type=='expenseOperation')
{
	$oper=$function->checkValues($_REQUEST['oper']);
	if($oper=='add')
	{
		$account=new Account();
		$response=$account->addDetails(
			$function->checkValues($_POST['invdate']),
			$function->checkValues($_POST['time']),
			$function->checkValues($_POST['expense_client']),
			$function->checkValues($_POST['amount']),
			$function->checkValues($_POST['contactperson']),
			$function->checkValues($_POST['note']),
			$function->checkValues($_POST['discussion']),
			$function->checkValues($_POST['outcome'])
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
		$account=new Account();
		$response=$account->editDetails(
			$function->checkValues($_POST['invdate']),
			$function->checkValues($_POST['time']),
			$function->checkValues($_POST['expense_client']),
			$function->checkValues($_POST['amount']),
			$function->checkValues($_POST['contactperson']),
			$function->checkValues($_POST['note']),
			$function->checkValues($_POST['discussion']),
			$function->checkValues($_POST['outcome']),
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
			$message="Sorry! You cannot edit details created by someone else.";
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
		$account=new Account();
		$response=$account->deleteDetails($function->checkValues($_POST['id']));
		if($response)
		{
			$status=TRUE;
			$message="Details Deleted";
		}
		else
		{
			$status=FALSE;
			$message="Sorry! You cannot delete details created by someone else.";
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
else if($type=='expense')
{
	$account=new Account();
	if(!$sidx) 
	{
		$sidx =1;
	}
	$totalrows = isset($_REQUEST['totalrows']) ? $function->checkValues($_REQUEST['totalrows']): false;
	if($totalrows) 
	{	
		$limit = $totalrows;
	}
	$response=$account->getDetails($toDate,$fromDate,$page,$limit,$sidx,$sord);
	echo json_encode($response);
	unset($response);
}
?>