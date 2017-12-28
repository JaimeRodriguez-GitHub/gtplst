<?php

//require_once 'lib/constants.php';

require_once C_ROOT_DIR.'classes/csDbManager.php';


Class csProductos {
	
	function getProductosPorCampania($pIdCampania = '') {
		try {			
			$query = "select llave, upc, codigo_producto, cantidad, unidades_empaque, descripcion, precio, total from vw_detalle_productos_nuevos_pedidos where id_campania = ".$pIdCampania;
			$query=$query." Order by descripcion"; 			
			$db = new DbSentencia();
			$result = $db->gpQuery($query);
			return $result;
		} catch(Exception $e) {
			return "";
		}
	}  //function getProductosPorCampania

    function getProductosGeneral() {
		try {			
			$query = "select * from productos_general";
			$db = new DbSentencia();
			$result = $db->gpQuery($query);
			return $result;
		} catch(Exception $e) {
			return "";
		}
	}  //function getProductosGeneral
}
?>