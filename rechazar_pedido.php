<?php
error_reporting( E_ALL );
if(!isset($_SESSION)) { session_start(); }
try {
  require_once ('lib/constants.php');
  require_once (C_ROOT_DIR.'classes/csPedidos.php');
  $csPedidos = new csPedidos();
  $numPedidosNoRechazados = $csPedidos->Rechazar($_POST['ids'], $_POST['razon']);
  echo json_encode($numPedidosNoRechazados);
} catch (Exception $e) {
  echo $e;
}
