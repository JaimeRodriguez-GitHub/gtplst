<?php

session_start();
error_reporting(E_ALL & ~E_NOTICE);

require_once 'constants.php';
require_once 'functions.php';

switch ($_GET['action']) {

	case 'getProductosCampania':
		require_once (C_ROOT_DIR.'classes/csProductos.php');
		$csDatos = new csProductos();
		$rsDatos = $csDatos->getProductosPorCampania($_GET['idCampania']);
		$json = array();
		if ($rsDatos) {
		  while ($row = $rsDatos->fetch_assoc()) {
			$json[] = array('id' => $row["upc"].'~'.$row["codigo_producto"].'~'.$row["unidades_empaque"].'~'.utf8_encode($row["descripcion"]).'~'.$row["precio"], 'reg' => utf8_encode($row["descripcion"]).' - '.$row["upc"].' - '.$row["codigo_producto"]);
		  }    
		}
		echo json_encode($json);
		break;

	case 'getClientesUsuario':
        require_once (C_ROOT_DIR.'classes/csClientes.php');
        $csDatos = new csClientes();
        $rsDatos = $csDatos->getClientes();
        if ($rsDatos) {
          while ($row = $rsDatos->fetch_assoc()) {
			$json[] = array('id' => $row["id_cliente"].'~'.$row["limite_credito"].'~'.fixSQL2(utf8_encode($row["direccion_entrega"])).'~'.$row["dias_entrega"].'~'.fixSQL2(utf8_encode($row["nombre"])).'~'.fixSQL2(utf8_encode($row["estatus_cliente"])), 'reg' => $row["id_cliente"].' - '.utf8_encode($row["nombre"]));
          }
        }
		echo json_encode($json);
		break;

	case 'getDatosPedido':
		require_once (C_ROOT_DIR.'classes/csPedidos.php');
		$csDatos = new csPedidos();
		$rsDatos = $csDatos->getDatosPedido($_GET['p'],false);
		echo json_encode($rsDatos);
		break;
		
		// $json = array();
		// $json['observaciones'] = "estas son mis observaciones";
		// $json['pedido'] = $_GET['p'];
		// $json[] = array('codigo_producto'=>'AR23423', 'descripcion'=>'pachon verde', 'precio'=>'Q33.32');
		// $json[] = array('codigo_producto'=>'AR23424', 'descripcion'=>'pachon azul', 'precio'=>'Q44.42');
		// echo json_encode($json);
		// break;

	default:
		echo 'invalid function';
		break;
}
?>