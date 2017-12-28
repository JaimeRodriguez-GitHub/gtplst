<?php

require_once C_ROOT_DIR.'classes/csDbManager.php';

Class csCampanias {
	
	function getCampanias($pId = '', $pIncludeInactive = false) {
		try {
			
			$query = "Select * From cam_encabezado Where 1=1";
			if ($pId!='') { $query = $query." and id_campania = '$pId'"; }
			else if (!$pIncludeInactive) { $query = $query." and activo = 1"; }

			$query=$query." Order by cast(id_campania as UNSIGNED)"; 

			$db = new DbSentencia();
			$result = $db->gpQuery($query);
			return $result;

		} catch(Exception $e) {
			return "";
		}
	}  //function getCampanias
}

?>