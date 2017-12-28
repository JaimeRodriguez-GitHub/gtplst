<?php
error_reporting( E_ALL );
if(!isset($_SESSION)) { session_start(); }
try {
  require_once ('lib/constants.php');
  require_once (C_ROOT_DIR.'classes/csPedidos.php');
  $csPedidos = new csPedidos();
  //echo $_POST['datos_pedido'];
  //echo json_encode($_POST['datos_pedido']);
  //die();
  $borroPedido = $csPedidos->Borrar($_POST['n']);
  //var_dump($borroPedido);
  echo $borroPedido;
  //echo '0';
  

  //$numPedido = json_decode($_POST['datos_pedido'],true);
  //echo $numPedido['codigo_cliente'];


} catch (Exception $e) {
  echo $e;
}
