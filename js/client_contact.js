$(document).ready(function()
{
	var clients = $.ajax({url: "include/clientController.php?ref=clientSelect", dataType: "json", cache: true, async: false, success: function(data, result) {if (!result) alert('Failure to retrieve the Products.');}}).responseText;
	jQuery("#client03").jqGrid({
		url:'include/contactController.php?ref=contactDetails',
		datatype: "json",
		width:770,
		colNames: ['Id','Contact Name','Contact Number','Customer'],
		colModel:[
			{name:"contact_id",index:"contact_id",hidden:true,key:true,align:"center",editable:false,sorttype:'int',key:true},
			{name:"name",index:"name",formoptions:{label: 'Contact Name *'},align:"center",editable: true,editrules: { required: true }},
			{name:"contact_number",index:"contact_number",formoptions:{label: 'Contact Number *'},width:70,align:"center",editable: true,editrules: { required: true ,number:true}},
			{name:"client_id",formoptions:{label: 'Customer *'},index:"client_id",align:"center",editable: true, edittype: "select", editrules: { required: true }, editoptions: { size: 71,value: (clients.replace('"','')).replace('"','') } },
		],
		rowNum:20,
		rowList:[20,40,60,100],
		height: 'auto',
		pager: '#pclient03',
		sortname: 'contact_id',
		viewrecords: true,
		sortorder: "asc",
		caption:"Customer Contact Details",
		editurl:"include/contactController.php?ref=contactOperation"
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
	jQuery("#client03").jqGrid('navGrid','#pclient03',
		{add:true, view:true, del:true,edit:true}, 
		{top:0,closeAfterEdit:true,reloadAfterSubmit:true,closeOnEscape:true,afterSubmit: fn_replyResponse,bottominfo:'* Mandatory fields.'}, // edit options
		{top:0,clearAfterAdd:true,reloadAfterSubmit:true,closeOnEscape:true}, // add options
		{top:0,reloadAfterSubmit:true,closeOnEscape:true,afterSubmit: fn_replyResponse,bottominfo:'* Mandatory fields.'}, // del options
		{}, // search options
		{closeOnEscape:true} 
	);
});