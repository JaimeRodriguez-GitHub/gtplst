<script>
doWait();
</script>

<?php 
error_reporting(E_ALL & ~E_NOTICE);
//error_reporting(0);
session_start();
require_once("phpGrid_Lite/conf.php");  

//$dg = new C_DataGrid("SELECT upc, codigo, cantidad, ue, descripcion, precio, total FROM testorders", "upc", "orders");
$qry = "select * from (select llave, upc, codigo_producto, cantidad, unidades_empaque, descripcion, precio, total from vw_detalle_productos_nuevos_pedidos where id_campania = ".$_POST['p'].") tabla";
$dg = new C_DataGrid($qry, "llave", "new_order");

//$dg = new C_DataGrid("select llave, upc, codigo_producto, cantidad, unidades_empaque, descripcion, precio, total from vw_detalle_productos_nuevas_ordenes where id_campania = ".$_POST['p'], "llave", "new_order");

$dg -> set_col_title("upc", "UPC");
$dg -> set_col_title("codigo_producto", "CODIGO");
$dg -> set_col_title("cantidad", "CANTIDAD");
$dg -> set_col_title("unidades_empaque", "U/E");
$dg -> set_col_title("descripcion", "DESCRIPCION");
$dg -> set_col_title("precio", "PRECIO");
$dg -> set_col_title("total", "TOTAL");

$dg -> set_col_align('cantidad', 'right');
$dg -> set_col_align('unidades_empaque', 'center');
$dg -> set_col_align('precio', 'right');
$dg -> set_col_align('total', 'right');

$dg -> set_col_width("upc",30);
$dg -> set_col_width("codigo_producto",40);
$dg -> set_col_width("cantidad",20);
$dg -> set_col_width("unidades_empaque",15);
$dg -> set_col_width("descripcion",100);
$dg -> set_col_width("precio",30);
$dg -> set_col_width("total",30);

$dg->set_col_hidden('llave');

//$dg -> set_col_frozen('upc');

$dg -> set_col_format("cantidad", "number", array("thousandsSeparator" => ",",
	                                               "decimalPlaces" => '0',
                                                   "defaultValue" => ""
                                                   ));  
//$dg -> set_col_format("cantidad", "integer", array("thousandsSeparator" => ","));
$dg -> set_col_format("precio", 'currency', array("prefix" => "Q",
                                                  "suffix" => '',
                                                  "thousandsSeparator" => ",",
                                                  "decimalSeparator" => ".",
                                                  "decimalPlaces" => '2',
                                                  "defaultValue" => '0.00'));
$dg -> set_col_format("total", 'currency', array("prefix" => "Q",
                                                 "suffix" => '',
                                                 "thousandsSeparator" => ",",
                                                 "decimalSeparator" => ".",
                                                 "decimalPlaces" => '2',
                                                 "defaultValue" => '0.00'));

//$qry = "select * from (select llave, upc, codigo_producto, cantidad, unidades_empaque, descripcion, precio, total from vw_detalle_productos_nuevos_pedidos where id_campania = ".$_POST['p'].") tabla";

//se deshabilita el sorting porque sino refresca el grid y borra las cantidades ingresadas por el usuario
$dg -> set_col_property("llave", array("sortable" => false));
$dg -> set_col_property("upc", array("sortable" => false));
$dg -> set_col_property("codigo_producto", array("sortable" => false));
$dg -> set_col_property("cantidad", array("sortable" => false));
$dg -> set_col_property("unidades_empaque", array("sortable" => false));
$dg -> set_col_property("descripcion", array("sortable" => false));
$dg -> set_col_property("precio", array("sortable" => false));
$dg -> set_col_property("total", array("sortable" => false));

$dg -> set_col_customrule('cantidad', 'validacion_cantidad');
$dg -> set_col_readonly("llave, upc, codigo_producto, precio, unidades_empaque, descripcion, total"); 
$dg->set_conditional_format("cantidad","CELL",array("condition"=>"ge",
                                                      "value"=>"0",
                                                      "css"=> array("background-color"=>"#c5e980")));

//$dg -> set_dimension(500, 600);
//$dg -> enable_autowidth(true);
$dg -> set_grid_property(array("footerrow"=>true));
$dg -> enable_autoheight(true);
$dg -> set_scroll(true);
$dg -> set_pagesize(2000);
$dg -> set_caption("");
$dg -> enable_edit("INLINE", "RU");

//$onjqGridInlineEditRow = <<<ONSELECTROW
//function(status, rowid)
//{
//    //alert('event 1');
//    //console.log(rowid);
//    //console.log(status);
//    
//    // example to redirect a new URL when select a row //
//    // orderNumber = $('#orders').jqGrid('getCell',rowid,'orderNumber');
//    // customerNumber = $('#orders').jqGrid('getCell',rowid,'customerNumber');
//    // window.location = encodeURI("http://example.com/" + "?" + "orderNumber=" + orderNumber + "&customerNumber="+customerNumber);
//}
//ONSELECTROW;
////TESTING ONLY
////$dg->add_event("jqGridAfterEditCell", $onjqGridInlineEditRow);

$loadComplete = <<<LOADCOMPLETE
function ()
{

//	$('#new_order').jqGrid({
//		colModel:[
//    		{name:'codigo_producto', sortable:false }
//		]
//	});

var cm = [
    {name:'upc', sortable: false},
    {name:'codigo_producto', sortable: false},
    {name:'cantidad', sortable: false}
];

$("#new_order").jqGrid ({
    colModel: cm
});


	//$('#new_order').jqGrid.defaults.cmTemplate = { sortable:false};
//    //var colSum = $('#new_order').jqGrid('getCol', 'cantidad', false, 'sum'); // other options are: avg, count
//    //$('#orders').jqGrid('footerData', 'set', { state: 'Total:', 'cantidad': colSum });
//
//  //calcula columna 'total'
//  var ids = jQuery("#new_order").jqGrid('getDataIDs');
//  for (var i = 0; i < ids.length; i++)
//  {
//      var rowId = ids[i];
//      //var rowData = jQuery('#new_order').jqGrid ('getRowData', rowId);
//      var n1 = parseInt  ($("#new_order").jqGrid("getCell", rowId, "cantidad"));
//      var n2 = parseFloat($("#new_order").jqGrid("getCell", rowId, "precio")); 
//      var n3 = (n1 * n2).toFixed(2); 
//      $("#new_order").jqGrid("setCell", rowId, "total", n3); 
//  }
//
//  //calcula totales footer
//  var colCantidad = $('#new_order').jqGrid('getCol', 'cantidad', false, 'sum'); 
//  var colTotal    = $('#new_order').jqGrid('getCol', 'total',    false, 'sum'); 
//  $('#new_order').jqGrid('footerData', 'set', { codigo_producto: 'Total:', 'cantidad': colCantidad, 'total': colTotal });
//
}
LOADCOMPLETE;
////TESTING ONLY
//$dg->add_event("jqGridLoadComplete", $loadComplete);

$editComplete = <<<EDITCOMPLETE
function(rowid){

  //si deja en blanco, pone cero en 'cantidad'
  selRowId = $('#new_order').jqGrid('getGridParam', 'selrow'),
  celValue = $('#new_order').jqGrid('getCell', selRowId, 'cantidad');
  if (celValue==='')
  		$("#new_order").jqGrid("setCell", selRowId, "cantidad", 0);

  //calcula columna 'total'
  var ids = jQuery("#new_order").jqGrid('getDataIDs');
  for (var i = 0; i < ids.length; i++)
  {
      var rowId = ids[i];
      var n1 = parseInt  ($("#new_order").jqGrid("getCell", rowId, "cantidad"));
      var n2 = parseFloat($("#new_order").jqGrid("getCell", rowId, "precio")); 
      var n3 = (n1 * n2).toFixed(2); 
      $("#new_order").jqGrid("setCell", rowId, "total", n3); 
  }

  //calcula totales footer
  var colCantidad = $('#new_order').jqGrid('getCol', 'cantidad', false, 'sum'); 
  var colTotal    = $('#new_order').jqGrid('getCol', 'total', false, 'sum'); 
  $('#new_order').jqGrid('footerData', 'set', { codigo_producto: 'Total:', 'cantidad': colCantidad, 'total': colTotal });

}
EDITCOMPLETE;
$dg->add_event("jqGridInlineAfterSaveRow", $editComplete);

$dg -> display();

?>

<script>
doUnWait();
</script>
