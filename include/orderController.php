<?php
include_once ('../class/order.php');
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
if(isset($_REQUEST['_search']) && $searchOn!='false' && $type=='orderDetails')
{
	$fld =  $function->checkValues($_REQUEST['searchField']);
	if( $fld=='id'  || $fld=='client' || $fld=='invoiceId' || $fld=='invoiceDate' || $fld=='item' || $fld=='qty' || $fld=='unit' || $fld=='tax' || $fld=='extra'|| $fld=='firstname') 
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
	$order=new Order();
	$response=$order->getDetails($toDate,$fromDate,$page,$limit,$sidx,$sord,$wh);
	echo json_encode($response);
	unset($response);
}
else if($type=='orderDetails')
{
	$order=new Order();
	$id=$function->checkValues($_REQUEST['id']);
	if(!$sidx) 
	{
		$sidx =1;
	}
	$totalrows = isset($_REQUEST['totalrows']) ? $function->checkValues($_REQUEST['totalrows']): false;
	if($totalrows) 
	{	
		$limit = $totalrows;
	}
	$response=$order->getDetails($toDate,$fromDate,$page,$limit,$sidx,$sord);
	echo json_encode($response);
	unset($response);
}
else if($type=='orderOperation')
{
	$oper=$function->checkValues($_REQUEST['oper']);
	if($oper=='add')
	{
		$order=new Order();
		$response=$order->addDetails(
			$function->checkValues($_POST['client']),
			$function->checkValues($_POST['item']),
			$function->checkValues($_POST['qty']),
			$function->checkValues($_POST['invoiceId']),
			$function->checkValues($_POST['invoiceDate']),
			$function->checkValues($_POST['total'])
		);
		if($response)
		{
			$status=TRUE;
			$message="Order Added";
		}
		else
		{
			$status=FALSE;
			$message="Order could not be added";
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
		$order=new Order();
		$response=$order->editDetails(
			$function->checkValues($_POST['id']),
			$function->checkValues($_POST['client']),
			$function->checkValues($_POST['item']),
			$function->checkValues($_POST['qty']),
			$function->checkValues($_POST['invoiceId']),
			$function->checkValues($_POST['invoiceDate']),
			$function->checkValues($_POST['total'])
		);
		if($response)
		{
			$status=TRUE;
			$message="Order Edited";
		}
		else
		{
			$status=FALSE;
			$message="Sorry! You cannot edit order created by someone else.";
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
		$order=new Order();
		$response=$order->deleteDetails($function->checkValues($_POST['id']));
		if($response)
		{
			$status=TRUE;
			$message="Order Deleted";
		}
		else
		{
			$status=FALSE;
			$message="Sorry! You cannot delete order created by someone else.";
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