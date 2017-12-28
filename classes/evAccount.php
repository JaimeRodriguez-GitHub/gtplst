<?php
require_once 'evDbSentences.php';
require_once 'evConstants.php';
require_once 'evAdminSettings.php';

// Clase encargada de gestionar datos de la cuenta del usuario 
Class evAccount{

	//Obtiene Sitename
	function getSiteName () {
			
		try {
	
			$db = new sentencia();
			
			$qy="select site_name from user_account where id_user = ".$_SESSION['login']["id_user"];
			$result = $db->Query($qy);
			
			//*********NO ESTA BIEN MODELADO USAR ROWCOUNT*********
			//$rowcount=mysqli_num_rows($resultUsers);
			//return $rowcount;
			
			$existe='';
			foreach($result as $row){
				$existe = $row['site_name'];
			}
			//return $existe;
	
			if ($existe=='') {
				return '';
			} else {
				return $existe;
			}
				
		} catch (Exception $e) {
			return "ERROR: ".$e->getMessage();
		}
	
	} //function getSiteName
	
	//actualiza sitename para el usuario
	function setSiteName ($pSiteName) {
	
		try {
	
			$db = new sentencia();
			$affected_rows=0;
				
			//averigua primero si existe para hacer Update o Insert
			$qy="select count(1) cuantos from user_account where id_user = ".$_SESSION['login']["id_user"];
			$result = $db->Query($qy);
			
			//*********NO ESTA BIEN MODELADO USAR ROWCOUNT*********
			//$rowcount=mysqli_num_rows($resultUsers);
			//return $rowcount;
			
			$existe=0;
			foreach($result as $row){
				$existe = $row['cuantos'];
			}
			
			if ($existe>=1) { //si existe hace un update
					
				$query="update user_account set site_name = '".$pSiteName."' where id_user = ".$_SESSION['login']["id_user"];
				$result = $db->sentenceDML($query);
				if ($result==1){
					return "OK";
				}else{
					return "ERROR: Not saved";
				}
				
			} else { //si NO existe hace un insert
			
				$query="insert into user_account (id_user, site_name)
						values (".$_SESSION['login']["id_user"].", '".$pSiteName."')";
				$result = $db->sentenceDML($query,$affected_rows);
			
				if ($result){
					return "OK";
				}else{
					return "ERROR: Not saved";
				}
			
			}  //update o insert		
	
		} catch (Exception $e) {
			return "ERROR: ".$e->getMessage();
		}
	
	} //function setSiteName

	//obtiene informacion de la cuenta
	function getAccountInfo () {
			
		try {
	
			$db = new sentencia();
			
			$qy="select * from user_account where id_user = ".$_SESSION['login']["id_user"];
				
			$result = $db->Query($qy);
			
			//*********NO ESTA BIEN MODELADO USAR ROWCOUNT*********
			//$rowcount=mysqli_num_rows($resultUsers);
			//return $rowcount;
			
			$existe='';
			$list = array();
			foreach($result as $row){
				$existe = $row['id_user'];
				$list['beneficiary_name'] = $row['beneficiary_name'];
				$list['beneficiary_address1'] = $row['beneficiary_address1'];
				$list['beneficiary_state'] = $row['beneficiary_state'];
				$list['beneficiary_id_country'] = $row['beneficiary_id_country'];
				$list['beneficiary_id_currency'] = $row['beneficiary_id_currency'];
				$list['beneficiary_account_number'] = $row['beneficiary_account_number'];
				$list['beneficiary_bank_name'] = $row['beneficiary_bank_name'];
				$list['beneficiary_bank_swift'] = $row['beneficiary_bank_swift'];
				$list['beneficiary_bank_address1'] = $row['beneficiary_bank_address1'];
				$list['beneficiary_bank_id_country'] = $row['beneficiary_bank_id_country'];
				$list['intermediary_bank_name'] = $row['intermediary_bank_name'];
				$list['intermediary_bank_swift'] = $row['intermediary_bank_swift'];
				$list['intermediary_bank_account_number'] = $row['intermediary_bank_account_number'];
				$list['intermediary_bank_address1'] = $row['intermediary_bank_address1'];
				$list['intermediary_bank_id_country'] = $row['intermediary_bank_id_country'];
			}
			//return $existe;
	
			if ($existe=='') {  //si no existe devuelve un string con valores null porque asi lo necesita la pagina que lo llama
				$list['beneficiary_name'] = '';
				$list['beneficiary_address1'] = '';
				$list['beneficiary_state'] = '';
				$list['beneficiary_id_country'] = '';
				$list['beneficiary_id_currency'] = '';
				$list['beneficiary_account_number'] = '';
				$list['beneficiary_bank_name'] = '';
				$list['beneficiary_bank_swift'] = '';
				$list['beneficiary_bank_address1'] = '';
				$list['beneficiary_bank_id_country'] = '';
				$list['intermediary_bank_name'] = '';
				$list['intermediary_bank_swift'] = '';
				$list['intermediary_bank_account_number'] = '';
				$list['intermediary_bank_address1'] = '';
				$list['intermediary_bank_id_country'] = '';
				return $list;
			} else {
				return $list;
			}
				
		} catch (Exception $e) {
			return "ERROR: ".$e->getMessage();
		}
	
	} //function getAccountInfo

	//actualiza account info para el usuario
	function setAccountInfo ($pArrData) {
	
		try {
	
			$db = new sentencia();
			$affected_rows=0;
			
			//averigua primero si existe para hacer Update o Insert
			$qy="select count(1) cuantos from user_account where id_user = ".$_SESSION['login']["id_user"];
			$result = $db->Query($qy);
				
			//*********NO ESTA BIEN MODELADO USAR ROWCOUNT*********
			//$rowcount=mysqli_num_rows($resultUsers);
			//return $rowcount;
				
			$existe=0;
			foreach($result as $row){
				$existe = $row['cuantos'];
			}

			if ($existe>=1) { //si existe hace un update

				$query="update user_account set
						beneficiary_name = '".$pArrData['beneficiary_name']."',
						beneficiary_address1 = '".$pArrData['beneficiary_address1']."',
						beneficiary_state = '".$pArrData['beneficiary_state']."',
						beneficiary_id_country = '".$pArrData['beneficiary_id_country']."',
						beneficiary_id_currency = '".$pArrData['beneficiary_id_currency']."',
						beneficiary_account_number = '".$pArrData['beneficiary_account_number']."',
						beneficiary_bank_name = '".$pArrData['beneficiary_bank_name']."',
						beneficiary_bank_swift = '".$pArrData['beneficiary_bank_swift']."',
						beneficiary_bank_address1 = '".$pArrData['beneficiary_bank_address1']."',
						beneficiary_bank_id_country = '".$pArrData['beneficiary_bank_id_country']."',
						intermediary_bank_name = '".$pArrData['intermediary_bank_name']."',
						intermediary_bank_swift = '".$pArrData['intermediary_bank_swift']."',
						intermediary_bank_account_number = '".$pArrData['intermediary_bank_account_number']."',
						intermediary_bank_address1 = '".$pArrData['intermediary_bank_address1']."',
						intermediary_bank_id_country = '".$pArrData['intermediary_bank_id_country']."'
			 		where id_user = ".$_SESSION['login']["id_user"];
				$result = $db->sentenceDML($query,$affected_rows);
					
				if ($result){
					return "OK";
				}else{
					return "ERROR: Not saved";
				}
				
			} else { //si NO existe hace un insert

				$query="insert into user_account (id_user, beneficiary_name, beneficiary_address1, beneficiary_state, beneficiary_id_country, beneficiary_id_currency, beneficiary_account_number, beneficiary_bank_name, beneficiary_bank_swift, beneficiary_bank_address1, beneficiary_bank_id_country, intermediary_bank_name, intermediary_bank_swift, intermediary_bank_account_number, intermediary_bank_address1, intermediary_bank_id_country)
							values (".$_SESSION['login']["id_user"].", '".$pArrData['beneficiary_name']."', '".$pArrData['beneficiary_address1']."', '".$pArrData['beneficiary_state']."', '".$pArrData['beneficiary_id_country']."', '".$pArrData['beneficiary_id_currency']."', '".$pArrData['beneficiary_account_number']."', '".$pArrData['beneficiary_bank_name']."', '".$pArrData['beneficiary_bank_swift']."', '".$pArrData['beneficiary_bank_address1']."', '".$pArrData['beneficiary_bank_id_country']."', '".$pArrData['intermediary_bank_name']."', '".$pArrData['intermediary_bank_swift']."', '".$pArrData['intermediary_bank_account_number']."', '".$pArrData['intermediary_bank_address1']."', '".$pArrData['intermediary_bank_id_country']."')";
				$result = $db->sentenceDML($query,$affected_rows);
				
				if ($result){
					return "OK";
				}else{
					return "ERROR: Not saved";
				}
				
			} //update o insert
				
		} catch (Exception $e) {
			return "ERROR: ".$e->getMessage();
		}
	
	} //function setAccountInfo

	//obtiene informacion de la configuracion de reportes por usuario
	function getAccountReportsSettings () {
			
		try {
	
			$db = new sentencia();
			$qy="select * from user_account where id_user = ".$_SESSION['login']["id_user"];
			$result = $db->Query($qy);
				
			//*********NO ESTA BIEN MODELADO USAR ROWCOUNT*********
			//$rowcount=mysqli_num_rows($resultUsers);
			//return $rowcount;
				
			$existe='';
			$list = array();
			foreach($result as $row){
				$existe = $row['id_user'];
				$list['reports_id_currency'] = $row['reports_id_currency'];
			}
			//return $existe;
	
			if ($existe=='') {  //si no existe devuelve un string con valores null porque asi lo necesita la pagina que lo llama
				$list['reports_id_currency'] = "";
				return $list;
			} else {
				return $list;
			}
	
		} catch (Exception $e) {
			return "ERROR: ".$e->getMessage();
		}
	
	} //function getAccountReportsSettings

	//actualiza account info de la configuraicon de reportes para el usuario
	function setAccountReportsInfo ($pArrData) {
			
		try {
	
			$db = new sentencia();
			$affected_rows=0;
				
			//averigua primero si existe para hacer Update o Insert
			$qy="select count(1) cuantos from user_account where id_user = ".$_SESSION['login']["id_user"];
			$result = $db->Query($qy);
	
			//*********NO ESTA BIEN MODELADO USAR ROWCOUNT*********
			//$rowcount=mysqli_num_rows($resultUsers);
			//return $rowcount;
	
			$existe=0;
			foreach($result as $row){
				$existe = $row['cuantos'];
			}
	
			if ($existe>=1) { //si existe hace un update
	
				$query="update user_account set
						reports_id_currency = '".$pArrData['reports_id_currency']."'
			 			where id_user = ".$_SESSION['login']["id_user"];
				$result = $db->sentenceDML($query,$affected_rows);
					
				if ($result){
					//refresca los datos de currency para los reportes
					$this->setSessionCurrency($pArrData['reports_id_currency']);
					return "OK";
				}else{
					return "ERROR: Not saved";
				}
	
			} else { //si NO existe hace un insert
	
				$query="insert into user_account (id_user, reports_id_currency)
							values (".$_SESSION['login']["id_user"].", '".$pArrData['reports_id_currency']."')";
				$result = $db->sentenceDML($query,$affected_rows);
	
				if ($result){
					//refresca los datos de currency para los reportes
					$this->setSessionCurrency($pArrData['reports_id_currency']);
					return "OK";
				}else{
					return "ERROR: Not saved";
				}
	
			} //update o insert
			
		} catch (Exception $e) {
			return "ERROR: ".$e->getMessage();
		}
	
	} //function setAccountInfo
	
	function setSessionCurrency($idCurrency=1) {
		$db = new sentencia();
		$evConstants = new evConstants();
		$reports_currency_symbol=$evConstants::CONSTANT_DEFAULT_CURRENCY_SYMBOL;
		$reports_exchange_rate=$evConstants::CONSTANT_DEFAULT_EXCHANGE_RATE;
	
		$qy="select IFNULL(symbol,'$') symbol from currencies where id_currency = ".$idCurrency;
		$resultCurrency = $db->Query($qy);
		foreach($resultCurrency as $row){
			$reports_currency_symbol=$row['symbol'];
			if ($idCurrency!=$evConstants::CONSTANT_DEFAULT_CURRENCY_ID) {
				$evAdminSettings = new evAdminSettings();
				$reports_exchange_rate=$evAdminSettings->getEffectiveExchangeRate();
			}
		}
		$_SESSION['login']['reports_currency_symbol'] = $reports_currency_symbol;
		$_SESSION['login']['reports_exchange_rate'] = $reports_exchange_rate;
	} //setSessionCurrency
	
}
?>