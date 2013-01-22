jQuery("#product01").jqGrid({
   	url:'include/productController.php?ref=productDetails',
	datatype: "json",
	height: 'auto',
	width:770,
   	colNames:['Id', 'Product Name'],
	colModel:[
		{name:'product_id',index:'product_id', width:40, hidden:true,align:'center', editable:false,key:true},
		{name:'name',index:'name',formoptions:{label: 'Product Name *'}, width:400,align:'center',sortable: true, editable: true, editrules: { required: true } }
	],
   	rowNum:20,
   	rowList:[20,40,60,80,100,120,140],
   	pager: '#pproduct01',
   	sortname: 'product_id',
    viewrecords: true,
    sortorder: "asc",
	multiselect: false,
	subGrid: false,
	caption: "Product List",
	editurl:"include/productController.php?ref=productOperation"
});
jQuery("#product01").jqGrid('navGrid','#pproduct01',
	{add:true, view:true, del:true,edit:true}, 
	{top:0,closeAfterEdit:true,reloadAfterSubmit:true,closeOnEscape:true,bottominfo:'* Mandatory fields.'}, // edit options
	{top:0,clearAfterAdd:true,reloadAfterSubmit:true,closeOnEscape:true,bottominfo:'* Mandatory fields.'}, // add options
	{top:0,reloadAfterSubmit:true,closeOnEscape:true}, // del options
	{}, // search options
	{closeOnEscape:true} 
);