<?php

require_once C_ROOT_DIR.'classes/csDbManager.php';
require_once C_ROOT_DIR.'classes/csErrorHandler.php';
require_once C_ROOT_DIR.'lib/functions.php';

set_error_handler('customErrorHandler');
register_shutdown_function('fatal_handler');


Class csUsuario {
	
	function hasRole($pIdRol) {
		try {
			
			$roles = "'".str_replace(",", "','", $pIdRol)."'";
			$roles = str_replace(" ", "", $roles);
			$query = "Select count(1) From usuarios_roles Where id_usuario = '".$_SESSION['login']['id_usuario']."' and id_rol in ($roles)";
			$db = new DbSentencia();
			$value = $db->gpScalarQuery($query);
			return ($value>0);

		} catch(Exception $e) {
			return false;
		}
	}  //function hasRole

	function getUsuarios($pId = '') {
		try {
			
			$query = "Select * From usuarios";
			if($pId!='') { $query=$query." Where id_usuario = '$pId'"; }
			$query=$query." Order by nombre"; 
			
			$db = new DbSentencia();
			$result = $db->gpQuery($query);
			return $result;

		} catch(Exception $e) {
			return "";
		}
	}  //function getUsuarios
	
	function Grabar($pDatosUsuario) {

		$arrDatos = json_decode($pDatosUsuario,true);

		try {

			$db = new DbSentencia();
			$cnn = $db->gpStartTransaction();

			try{

				if (empty($arrDatos['id_usuario'])) {  //si no trae es insert
					$query = "insert into usuarios (nombre,                            email,                    password)";
					$query = $query . "     values ('".fixSQL($arrDatos['nombre'])."', '".$arrDatos['email']."', '".$arrDatos['contrasena']."')"; 
				} else {  // si trae es un update
					$query = "update usuarios set nombre = '".fixSQL($arrDatos['nombre'])."', email = '".$arrDatos['email']."', password = '".$arrDatos['contrasena']."'";
					$query = $query . " where id_usuario = ".$arrDatos['id_usuario'];
				}
				
				$result = $db->gpSentence($query, $cnn);
				if (is_bool($result) and $result) {
					$last_id = empty($arrDatos['id_usuario']) ? $cnn->insert_id : $arrDatos['id_usuario'];
					$db->gpCommitTransaction($cnn);
				} else {
					$last_id = $result;
					$db->gpRollbackTransaction($cnn);
				} 				
										
			} catch(Exception $e) {
				$db->gpRollbackTransaction($cnn);
				$last_id = -1;
			}

		} catch(Exception $e) {
			$last_id = -2;
		}

		return $last_id;

	}  //Grabar

	function Borrar($pIdUsuario) {

		$returnValue = false;

		try {

			if ($pIdUsuario!=$_SESSION['login']['id_usuario']) {  //no puede borrarse a el mismo

				$db = new DbSentencia();
				$cnn = $db->gpStartTransaction();

				try{

					$query = "delete from usuarios_roles where id_usuario = $pIdUsuario";
					$result = $db->gpSentence($query, $cnn);
					if (is_bool($result) and $result) {

						$query = "delete from usuarios_vendedores where id_usuario = $pIdUsuario";
						$result = $db->gpSentence($query, $cnn);							
						if (is_bool($result) and $result) {

							$query = "delete from usuarios where id_usuario = $pIdUsuario";
							$result = $db->gpSentence($query, $cnn);							
							if (is_bool($result) and $result) {  //no hay error
								$db->gpCommitTransaction($cnn);
								$returnValue = true;
							} else {
								$db->gpRollbackTransaction($cnn);
								$returnValue = $result;
							}

						} else {
							$db->gpRollbackTransaction($cnn);
							$returnValue = $result;
						}

					} else {
						$db->gpRollbackTransaction($cnn);
						$returnValue = $result;
					}

				} catch(Exception $e) {
					$db->gpRollbackTransaction($cnn);
					$returnValue = $e->getMessage();
				}

			} else {  //ya lo tomo la interfaz para sincronizar, no se puede borrar
				$returnValue = 'Usted mismo no puede eliminarse del sistema.';
			}  //lo tomo la interfaz si o no
	
		} catch(Exception $e) {
			$returnValue = $e->getMessage();
		}

		return $returnValue;
		
	} //Borrar

} //csUsuario

?>