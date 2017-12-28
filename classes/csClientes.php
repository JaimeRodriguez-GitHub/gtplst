<?php

require_once C_ROOT_DIR.'classes/csDbManager.php';

Class csClientes {
	
	function getClientes($pId = '') {
		try {
			
			$query = "Select c.*, p.descripcion as estatus_cliente From clientes c left outer join parametros p on IFNULL(c.p_estatus,'1') = p.id_parametro";
			if($pId!='') { $query=$query." Where c.id_cliente = '$pId'"; }
			else { $query=$query." Where c.id_vendedor in (".$_SESSION['login']['id_vendedor'].")"; }
			$query=$query." Order by c.nombre"; 
			
			$db = new DbSentencia();
			$result = $db->gpQuery($query);
			return $result;
			// $cnn = $db->getConnection();
			// $result = $db->gpQuery($query, $cnn);



			// return $result;

		} catch(Exception $e) {
			return "";
		}
	}  //function getClientes
	
} //class csClientes

?>