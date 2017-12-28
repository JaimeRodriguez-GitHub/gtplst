<?php 
/*
error_reporting(E_ALL & ~E_NOTICE);
//error_reporting(0);
session_start();

require_once 'lib/constants.php';


require_once (C_ROOT_DIR.'classes/csProductos.php');
$csProductos = new csProductos();
$rsDatos = $csProductos->getProductosPorCampania($_POST['p']);
$productos = "";
if ($rsDatos) {
  while ($row = $rsDatos->fetch_assoc()) {
    if ($productos!="")
      $productos = $productos.", ";
    //$productos = ($productos==="") ? $productos : ", ";
    $productos = $productos."{ value: '".$row["upc"]." - ".$row["codigo_producto"]." - ".$row["descripcion"]."', data: '".$row["upc"]."~".$row["codigo_producto"]."~".$row["unidades_empaque"]."~".$row["descripcion"]."~".$row["precio"]."' }";
  }    
  $productos = "[ ".$productos." ]";
}
//echo $productos;

$data2 = array( 'name' => 'God', 'age' => -1 );
echo json_encode( $data2 );
*/

$data2 = array( 'C1'   => 'Ciudad x1', 'C2'  => 'Ciudad x2', 'C3' => 'Ciudad x3' );
echo json_encode( $data2 );


?>
