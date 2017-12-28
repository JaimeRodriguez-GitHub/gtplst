<?php
error_reporting( E_ALL );
if(!isset($_SESSION)) { session_start(); }
try {
  require_once ('lib/constants.php');
  require_once (C_ROOT_DIR.'classes/csUsuario.php');
  $csUsuario = new csUsuario();
  $borroUsuario = $csUsuario->Borrar($_POST['n']);
  echo $borroUsuario;

} catch (Exception $e) {
  echo $e;
}
