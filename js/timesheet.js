$(document).ready(function()
{
	var toDate = $("#timesheetToDate").val(); 
	var fromDate = $("#timesheetFromDate").val();
	var contacts = $.ajax({url: "include/contactController.php?ref=contactSelect", dataType: "json", cache: true, async: false, success: function(data, result) {if (!result) alert('Failure to retrieve the Products.');}}).responseText;
	var clients = $.ajax({url: "include/clientController.php?ref=clientSelect", dataType: "json", cache: true, async: false, success: function(data, result) {if (!result) alert('Failure to retrieve the Products.');}}).responseText;
	/*$('#expenseTime').timepicker({});*/
	$( "#timesheetToDate" ).datepicker({
		changeMonth: true,
		changeYear: true,
		yearRange: '1900:2011',
		showAnim: 'bounce',
		dateFormat:'yy-mm-dd'
	});
	$( "#timesheetFromDate" ).datepicker({
		changeMonth: true,
		changeYear: true,
		yearRange: '1900:2011',
		showAnim: 'bounce',
		dateFormat:'yy-mm-dd'
	});
	$("#timesheet01").jqGrid({
		url:'include/timesheetController.php?ref=expenseTimesheet&toDate='+toDate+'&fromDate='+fromDate,
		datatype: "json",
		width:770,
		colNames:['Id', 'Date', 'Time','Customer','CTC','Mobile','Purpose','Discussion','Outcome','By'],
		colModel:[
			{name:'id',index:'id',hidden:true,align:'center',editable:false, sorttype:'int',key:true},
			{name:'invdate',index:'invdate',formoptions:{label: 'Date *'}, align:'center',editable:true,editrules: { required: true,date:true },summaryType:'count', summaryTpl : '({0}) Sum', datefmt:'yyyy-mm-dd',editoptions:{size:20,dataInit:function(el){$(el).datepicker({dateFormat:'yy-mm-dd'}); }}},
			{name:'time',index:'time',formoptions:{label: 'Time *'}, align:'center',editable:true,editrules: { required: true}, editoptions:{size:20,dataInit:function(el){$(el).timepicker({showSecond: true,timeFormat: 'hh:mm:ss'});}}},
			{name:"expense_client",index:"expense_client",formoptions:{label: 'Customer *'},align:"center",editable: true, edittype: "select", editrules: { required: true }, editoptions: { size: 71,value: (clients.replace('"','')).replace('"','') } },
			{name:'amount',index:'amount',align:"center",formoptions:{label: 'CTC *'},editable:true,editoptions:{size:10}, editrules: { required: true,number: true } ,sorttype:'number',formatter:'number',summaryType:'sum'},
			{name:"contactperson",index:"contactperson",formoptions:{label: 'Person *'},align:"center",editable: true, edittype: "select", editrules: { required: true }, editoptions: { size: 71,value: (contacts.replace('"','')).replace('"','') } },
			{name:'note',index:'note', formoptions:{label: 'Purpose *'},align:"center", sortable:true,editable: true,editrules: { required: true } ,edittype:"textarea", editoptions:{rows:"2",cols:"20"}},
			{name:'discussion',index:'discussion', formoptions:{label: 'Discussion'},align:"center", sortable:true,editable: true,editrules: { required: false, } ,edittype:"textarea", editoptions:{rows:"2",cols:"20"}},
			{name:'outcome',index:'outcome', align:"center", formoptions:{label: 'Outcome'},sortable:true,editable: true,editrules: { required: false, } ,edittype:"textarea", editoptions:{rows:"2",cols:"20"}},
			{name:'firstname',index:'firstname', align:"center",editable:false,hidden:true}
		],
		rowNum:20,
		rowList:[20,40,60,100],
		height: 'auto',
		pager: '#ptimesheet01',
		sortname: 'invdate',
		viewrecords: true,
		sortorder: "desc",
		caption:"Self Expense Time Sheet",
		editurl:"include/timesheetController.php?ref=expenseTimesheetOperation",
		grouping: false,
		groupingView : {
			groupField : ['firstname'],
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
	$("#timesheet01").jqGrid('navGrid','#ptimesheet01',
		{view:true, del:false, add:false, edit:false, excel:true},
		{top:0,closeAfterEdit:true,reloadAfterSubmit:true,closeOnEscape:true,afterSubmit: fn_replyResponse,bottominfo:'* Mandatory fields.<br/>Task can only be edited by the one who created it.'}, // edit options
		{top:0,clearAfterAdd:true,reloadAfterSubmit:true,closeOnEscape:true,afterSubmit: fn_replyResponse,bottominfo:'* Mandatory fields.'},  // add options
		{top:0,reloadAfterSubmit:true,closeOnEscape:true,afterSubmit: fn_replyResponse,bottominfo:'Task can only be deleted by the one who created it.'}, // del options
		{}, // search options
		{closeOnEscape:true} 
	)	
	$("#timesheet01").jqGrid('navButtonAdd','#ptimesheet01',{
		caption:"Save Excel",
		buttonicon:"ui-icon-save", 
		onClickButton : function () 
		{ 
			exportExcel();
		}
	});
	$("#timesheet01").jqGrid('navButtonAdd','#ptimesheet01',{
		caption:"Send Email",
		buttonicon:"ui-icon-newwin", 
		onClickButton : function () 
		{ 
			sendMailExcel();
		}
	});
	$("#chngroup").change(function()
	{
		var vl = $(this).val();
		if(vl) 
		{
			if(vl == "clear") {
				$("#timesheet01").jqGrid('groupingRemove',true);
			} else {
				$("#timesheet01").jqGrid('groupingGroupBy',vl);
			}
		}
	});
	$("#timesheetSearchButton").click(function()
	{
		var toDate = $("#timesheetToDate").val();
		var fromDate = $("#timesheetFromDate").val();
		$("#timesheet01").jqGrid('setGridParam',{url:"include/timesheetController.php?ref=expenseTimesheet&toDate="+toDate+"&fromDate="+fromDate,page:1}).trigger("reloadGrid");
	});

	function exportExcel()
	{
		var mya=new Array();
		var toDate = $("#timesheetToDate").val();
		var fromDate = $("#timesheetFromDate").val();
		mya=$("#timesheet01").jqGrid('getDataIDs');		// Get All IDs
		var data=jQuery("#timesheet01").jqGrid('getRowData', mya[0]);
		var colNames=new Array(); 
		var ii=0;
		for (var i in data){colNames[ii++]=i;}    // capture col names
		var html="<table border='1' cellpadding='0' cellspacing='0'>";
		html=html+"<tr><th style='font-size:100%;color:#fff;background-color:#000'><b>Date</b></th><th style='font-size:100%;color:#fff;background-color:#000'><b>Time</b></th><th style='font-size:100%;color:#fff;background-color:#000'><b>Customer</b></th><th style='font-size:100%;color:#fff;background-color:#000'><b>CTC</b></th><th style='font-size:100%;color:#fff;background-color:#000'><b>Mobile</b></th><th style='font-size:100%;color:#fff;background-color:#000'><b>Purpose</b></th><th style='font-size:100%;color:#fff;background-color:#000'><b>Discussion</b></th><th style='font-size:100%;color:#fff;background-color:#000'><b>Outcome</b></th></tr>";
		for(i=0;i<mya.length;i++)
		{
			data=jQuery("#timesheet01").jqGrid('getRowData', mya[i]);
			html=html+"<tr>";
			for(j=1;j<colNames.length-1;j++)
			{
				html=html+"<td style='font-size:100%;text-align:center;' align='center'>"+data[colNames[j]]+"</td>"; // output each Row as tab delimited
			}
			html=html+"</tr>";
		}
		html=html+'</table>';
		document.forms["timesheetForm"].timesheetExpenseBuffer.value=html;
		document.forms["timesheetForm"].method='POST';
		document.forms["timesheetForm"].action='include/fileController.php?ref=generateTimeSheetFile&toDate='+toDate+'&fromDate='+fromDate, // send it to server which will open this contents in excel file
		document.forms["timesheetForm"].target='_blank';
		document.forms["timesheetForm"].submit();
	}
	function sendMailExcel()
	{
		var mya=new Array();
		var toDate = $("#timesheetToDate").val();
		var fromDate = $("#timesheetFromDate").val();
		mya=$("#timesheet01").jqGrid('getDataIDs');		// Get All IDs
		var data=jQuery("#timesheet01").jqGrid('getRowData', mya[0]);
		var colNames=new Array(); 
		var ii=0;
		for (var i in data){colNames[ii++]=i;}    // capture col names
		var html_content="<table border='1' cellpadding='0' cellspacing='0'>";
		html_content=html_content+"<tr><th style='font-size:100%;color:#fff;background-color:#000'><b>Date</b></th><th style='font-size:100%;color:#fff;background-color:#000'><b>Time</b></th><th style='font-size:100%;color:#fff;background-color:#000'><b>Customer</b></th><th style='font-size:100%;color:#fff;background-color:#000'><b>CTC</b></th><th style='font-size:100%;color:#fff;background-color:#000'><b>Mobile</b></th><th style='font-size:100%;color:#fff;background-color:#000'><b>Purpose</b></th><th style='font-size:100%;color:#fff;background-color:#000'><b>Discussion</b></th><th style='font-size:100%;color:#fff;background-color:#000'><b>Outcome</b></th></tr>";
		for(i=0;i<mya.length;i++)
		{
			data=jQuery("#timesheet01").jqGrid('getRowData', mya[i]);
			html_content=html_content+"<tr>";
			for(j=1;j<colNames.length-1;j++)
			{
				html_content=html_content+"<td style='font-size:100%;text-align:center;' align='center'>"+data[colNames[j]]+"</td>"; // output each Row as tab delimited
			}
			html_content=html_content+"</tr>";
		}
		html_content=html_content+'</table>';
		var sendMail = $.manageAjax.create('sendMail'); 
		sendMail.add(
		{
			success: function(html) 
			{
				jQuery.ajax(
				{
					url: 'include/fileController.php?ref=mailTimeSheetFile&toDate='+toDate+'&fromDate='+fromDate,
					dataType: "html",
					type: "POST",
					cache: true,
					data: "timesheetExpenseBuffer="+html_content,
					beforeSend: function()
					{
					},
					success:function(data)
					{
						alert(data);
					},
					error:function (xhr, ajaxOptions, thrownError)
					{
						
					}  
				});
			}
		});
	}
});