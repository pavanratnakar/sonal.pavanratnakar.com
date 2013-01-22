$(document).ready(function()
{
	var toDate = $("#productOrderToDate").val(); 
	var fromDate = $("#productOrderFromDate").val();
	var products = $.ajax({url: "include/productController.php?ref=productSelect", dataType: "json", cache: true, async: false, success: function(data, result) {if (!result) alert('Failure to retrieve the Products.');}}).responseText;
	var clients = $.ajax({url: "include/clientController.php?ref=clientSelect", dataType: "json", cache: true, async: false, success: function(data, result) {if (!result) alert('Failure to retrieve the Products.');}}).responseText;
	$( ".dateTime" ).datepicker({
		changeMonth: true,
		changeYear: true,
		yearRange: '1900:2011',
		showAnim: 'bounce',
		dateFormat:'yy-mm-dd'
	});
	jQuery("#product02").jqGrid({
		url:'include/orderController.php?ref=orderDetails&toDate='+toDate+'&fromDate='+fromDate,
		datatype: "json",
		height: 'auto',
		width:770,
		colNames: ['Id','Customer','Invoice ID','Dispatch Date','Item','Qty','By','Amount'],
		colModel:[
			{name:"id",index:"id",hidden:true,width:40,key:true,align:"center",editable:false,sorttype:'int',key:true},
			{name:"client",index:"client",formoptions:{label: 'Customer *'},width:70,align:"center",editable: true, edittype: "select", editrules: { required: true }, editoptions: { size: 71,value: (clients.replace('"','')).replace('"','') } },
			{name:"invoiceId",index:"invoiceId",formoptions:{label: 'Invoice ID *'},width:70,align:"center",editable: true,editrules: { required: true }},
			{name:"invoiceDate",index:"invoiceDate",formoptions:{label: 'Dispatch Date *'},width:70,align:"center",editable:true,editrules: { required: true,date:true }, datefmt:'yyyy-mm-dd' ,editoptions:{size:20,dataInit:function(el){$(el).datepicker({dateFormat:'yy-mm-dd'}); }}},
			{name:"item",index:"item",width:164,align:"center",formoptions:{label: 'Item *'},editable: true, edittype: "select", editrules: { required: true }, editoptions: { size: 71,value: (products.replace('"','')).replace('"','') } },
			{name:"qty",index:"qty",width:65,align:"center",formoptions:{label: 'Qty *'},editable: true,editrules: { required: true }},
			{name:"firstname",index:"firstname",width:65,align:"center",summaryType:'count', summaryTpl : '({0}) Sum'},
			{name:"total",index:"total",width:65,align:"center",formoptions:{label: 'Amount *'},editable: true,editrules: { required: true,number: true  },sortable:true,sorttype:'number',formatter:'number',summaryType:'sum'}
		],
		pager: '#pproduct02',
		sortname: 'id',
		viewrecords: true,
		sortorder: "asc",
		multiselect: false,
		subGrid: true,
		caption: "Product Invoice Details",
		editurl:"include/orderController.php?ref=orderOperation",
		rowNum:20,
		rowList:[20,40,60,100],
		height: '100%',
		grouping: true,
		groupingView : {
			groupField : ['item'],
			groupColumnShow : [true],
			groupText : ['<b>{0}</b>'],
			groupCollapse : false,
			groupOrder: ['asc'],
			groupSummary : [true],
			showSummaryOnHide: true,
			groupDataSorted : true
		},
		footerrow: true,
		userDataOnFooter: true
	});
	var fn_replyResponse=function(response,postdata)
	{
		var json=response.responseText; //in my case response text form server is "{sc:true,msg:''}"
		var result=eval("("+json+")"); //create js object from server reponse
		if(result.status==false)
		{
			alert(result.message)
		}
		return [result.sc,result.msg,null]; 
	}
	jQuery("#product02").jqGrid('navGrid','#pproduct02',
		{add:true, view:true, del:true,edit:true}, 
		{top:0,closeAfterEdit:true,reloadAfterSubmit:true,closeOnEscape:true,afterSubmit: fn_replyResponse,bottominfo:'* Mandatory fields.<br/>Order can only be edited by the one who created it.'}, // edit options
		{top:0,clearAfterAdd:true,reloadAfterSubmit:true,closeOnEscape:true,afterSubmit: fn_replyResponse,bottominfo:'* Mandatory fields.'},  // add options
		{top:0,reloadAfterSubmit:true,closeOnEscape:true,afterSubmit: fn_replyResponse,bottominfo:'Order can only be deleted by the one who created it.'}, // del options
		{}, // search options
		{closeOnEscape:true} 
	);
	jQuery("#product02Change").change(function()
	{
		var vl = $(this).val();
		if(vl) 
		{
			if(vl == "clear") {
				jQuery("#product02").jqGrid('groupingRemove',true);
			} else {
				jQuery("#product02").jqGrid('groupingGroupBy',vl);
			}
		}
	});
	$("#productOrderSearchButton").click(function()
	{
		var toDate = $("#productOrderToDate").val();
		var fromDate = $("#productOrderFromDate").val();
		$("#product02").jqGrid('setGridParam',{url:"include/orderController.php?ref=orderDetails&toDate="+toDate+"&fromDate="+fromDate,page:1}).trigger("reloadGrid");
	});
});