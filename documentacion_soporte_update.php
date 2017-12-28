<?php
  $page = 'pedido'; require_once("header.php");
  error_reporting(E_ALL & ~E_NOTICE);
  if(!isset($_POST['id_pedido'])) {
  	echo ('<blockquote><p>Pedido Especial no encontrado!!</p></blockquote>');
  	die();
  }

//error_reporting(E_ALL & ~E_NOTICE);
error_reporting(E_ALL);

//require_once 'constants.php';
//require_once 'functions.php';
require_once (C_ROOT_DIR.'classes/csPedidos.php');
$csDatos = new csPedidos();
$rsResult = $csDatos->setDocumentacionSoporte($_POST['id_pedido'], $_POST['nombre_archivo']);

?>

<blockquote>
	<h2>Carga de documentacion de soporte para el pedido # <strong><?php echo $_POST['id_pedido']; ?></strong></h2>
    <p>La documentacion ha sido cargada exitosamente!!!</p>
</blockquote>  
<br>
<a href="pedido.php" class="btn btn-info" role="button">Crear Nuevo Pedido</a>

<?php $page = 'pedido'; require_once("footer.php"); ?>
