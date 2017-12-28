<?php
error_reporting( E_ALL );
if(!isset($_SESSION)) { session_start(); }
try {
  require_once ('lib/constants.php');
  require_once (C_ROOT_DIR.'classes/csPedidos.php');
  $csPedidos = new csPedidos();
  $numPedidosNoAprobados = $csPedidos->Aprobar($_POST['ids']);
  echo json_encode($numPedidosNoAprobados);

  //ejemplo 
  // $json = array();
  // $json['observaciones'] = "estas son mis observaciones";
  // $json['pedido'] = '11212';
  // $json[] = array('codigo_producto'=>'AR23423', 'descripcion'=>'pachon verde', 'precio'=>'Q33.32');
  // $json[] = array('codigo_producto'=>'AR23424', 'descripcion'=>'pachon azul', 'precio'=>'Q44.42');
  // echo json_encode($json);

} catch (Exception $e) {
  echo $e;
}
