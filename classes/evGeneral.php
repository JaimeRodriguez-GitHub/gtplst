<?php

require_once 'evDbSentences.php';

// Clase encargada de gestionar funciones varias
Class evGeneral{

	//Obtiene countries
	function getCountries ($pIncludeBlank=false) {
		
		try {
			$db = new sentencia();
			
			$qy="select * from countries order by name";
			$result = $db->Query($qy);
			
			//*********NO ESTA BIEN MODELADO USAR ROWCOUNT*********
			//$rowcount=mysqli_num_rows($resultUsers);
			//return $rowcount;
			$list = array();
			if ($pIncludeBlank) $list[''] = '';
			foreach($result as $row){
				$list[$row['name']] = $row['id_country'];
			}
			return $list;
	
		} catch (Exception $e) {
			return "ERROR: ".$e->getMessage();
		}
	
	} //function getCountries
	
	//Obtiene Currencies
	function getCurrencies ($pIncludeBlank=false) {
		
		try {
			$db = new sentencia();
			
			$qy="select * from currencies order by name";
			$result = $db->Query($qy);
			
			//*********NO ESTA BIEN MODELADO USAR ROWCOUNT*********
			//$rowcount=mysqli_num_rows($resultUsers);
			//return $rowcount;
			
			$list = array();
			if ($pIncludeBlank) $list[''] = '';
			foreach($result as $row){
				$list[$row['name']] = $row['id_currency'];
			}
			return $list;
	
		} catch (Exception $e) {
			return "ERROR: ".$e->getMessage();
		}
	
	} //function getCurrencies

	//Obtiene Revenue Percent general del portal
	function getRevenuePercent() {
	
		try {
	
			$db = new sentencia();
			$qy="select ifnull(value,0) value from settings where name = 'revenue_percent'";
			$result = $db->Query($qy);
	
			//*********NO ESTA BIEN MODELADO USAR ROWCOUNT*********
			//$rowcount=mysqli_num_rows($resultUsers);
			//return $rowcount;
	
			$existe='';
			foreach($result as $row){
				$existe = $row['value'];
			}
			//return $existe;
	
			if ($existe=='') {
				return 0;
			} else {
				return $existe;
			}
	
		} catch (Exception $e) {
			return "ERROR: ".$e->getMessage();
		}
	} //function getExchangeRate
	
	
	//actualiza informacion general
	function setGeneralSettings ($pArrData) {
	
		try {
	
			$db = new sentencia();
			$affected_rows=0;
			
			$query="update settings set
					value = '".$pArrData['revenue_percent']."'
					where name = 'revenue_percent'";
			$result = $db->sentenceDML($query,$affected_rows);
				
			if ($result){
				return "OK";
			}else{
				return "ERROR: Not saved";
			}
								
		} catch (Exception $e) {
			return "ERROR: ".$e->getMessage();
		}
	
	} //function setGeneralSettings

}
?>