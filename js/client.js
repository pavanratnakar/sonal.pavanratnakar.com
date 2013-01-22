jQuery("#client01").jqGrid({
   	url:'include/clientController.php?ref=clientDetails',
	datatype: "json",
	height: 'auto',
	width:770,
   	colNames:['Id', 'Customer'],
	colModel:[
		{name:'client_id',index:'client_id', hidden:true,width:40, align:'center', editable:false,key:true},
		{name:'name',index:'name', formoptions:{label: 'Customer *'},width:400,align:'center',sortable: true, editable: true, editrules: { required: true } }
	],
   	rowNum:20,
   	rowList:[20,40,60,80,100,120,140],
   	pager: '#pclient01',
   	sortname: 'client_id',
    viewrecords: true,
    sortorder: "asc",
	multiselect: false,
	subGrid: false,
	caption: "Customer List",
	editurl:"include/clientController.php?ref=clientOperation"
});
jQuery("#client01").jqGrid('navGrid','#pclient01', 
	{add:true, view:true, del:true,edit:true}, 
	{top:0,closeAfterEdit:true,reloadAfterSubmit:true,closeOnEscape:true,bottominfo:'* Mandatory fields.'}, // edit options
	{top:0,clearAfterAdd:true,reloadAfterSubmit:true,closeOnEscape:true,bottominfo:'* Mandatory fields.'}, // add options
	{top:0,reloadAfterSubmit:true,closeOnEscape:true}, // del options
	{}, // search options
	{closeOnEscape:true} 
);