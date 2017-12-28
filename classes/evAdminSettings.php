<?php

require_once 'evDbSentences.php';
		
// Clase encargada de gestionar datos de la cuenta del usuario 
Class evAdminSettings{

	//cada vez que el usuario cambia su moneda se actualiza la info de la moneda en la session para usar en los reportes 
	//obtiene la tasa de cambio vigente a la fecha
	function getEffectiveExchangeRate() {

		try {
	
			$db = new sentencia();
			$qy="select IFNULL(exchange_rate,0) exchange_rate from exchange_rates where effective_date <= DATE_FORMAT(SYSDATE(),'%Y-%m-%d') order by effective_date desc limit 1";
			$result = $db->Query($qy);

			//*********NO ESTA BIEN MODELADO USAR ROWCOUNT*********
			//$rowcount=mysqli_num_rows($resultUsers);
			//return $rowcount;

			$existe='';
			foreach($result as $row){
				$existe = $row['exchange_rate'];
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
		
	//Obtiene ExchangeRate para una fecha determinada
	function getExchangeRate($pEffectiveDate) {

		try {
	
			$db = new sentencia();
			$qy="select exchange_rate from exchange_rates where effective_date = '".$pEffectiveDate."'";
			$result = $db->Query($qy);

			//*********NO ESTA BIEN MODELADO USAR ROWCOUNT*********
			//$rowcount=mysqli_num_rows($resultUsers);
			//return $rowcount;

			$existe='';
			foreach($result as $row){
				$existe = $row['exchange_rate'];
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
	} //function getExchangeRate
	
	//actualiza sitename para el usuario
	function setExchangeRate ($pEffectiveDate, $pExchangeRate) {
	
		try {
	
			$db = new sentencia();
			$affected_rows=0;
				
			//averigua primero si existe para hacer Update o Insert
			$qy="select count(1) cuantos from exchange_rates where effective_date = '".$pEffectiveDate."'";
			$result = $db->Query($qy);
			
			//*********NO ESTA BIEN MODELADO USAR ROWCOUNT*********
			//$rowcount=mysqli_num_rows($resultUsers);
			//return $rowcount;
			
			$existe=0;
			foreach($result as $row){
				$existe = $row['cuantos'];
			}
			
			if ($existe>=1) { //si existe hace un update
					
				$query="update exchange_rates set exchange_rate = '".$pExchangeRate."' where effective_date = '".$pEffectiveDate."'";
				$result = $db->sentenceDML($query);
				if ($result==1){
					return "OK";
				}else{
					return "ERROR: Not saved";
				}
				
			} else { //si NO existe hace un insert
			
				$query="insert into exchange_rates (effective_date, exchange_rate)
						values ('".$pEffectiveDate."', ".$pExchangeRate.")";
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
	
	//Obtiene usuarios
	function getUsers() {
		
		try {
			$db = new sentencia();
			
			$qy="select a.id_user, a.username, a.password, a.last_login_date, a.last_login_ip, a.recovery_password_token, a.id_site_default, a.is_admin, b.id_site, b.name, b.revenue_percent_type, b.revenue_percent_custom, b.fixed_cpm, b.is_default
				 from users a left outer join user_sites b on a.id_user = b.id_user and b.is_default = 1
				 order by a.username"; //a.id_site_default = b.id_site
				
			$result = $db->Query($qy);
			
			//*********NO ESTA BIEN MODELADO USAR ROWCOUNT*********
			//$rowcount=mysqli_num_rows($resultUsers);
			//return $rowcount;
			
			$list = array();
			$i=0;
			foreach($result as $row){
				$list[$i]['id_user'] = $row['id_user'];
				$list[$i]['username'] = $row['username'];
				//////////////////////////////////////////////////$list[$i]['site_name'] = $row['site_name'];
				$list[$i]['site_name'] = $row['name'];
				$list[$i]['revenue_percent_custom'] = $row['revenue_percent_custom'];
				$list[$i]['fixed_cpm'] = $row['fixed_cpm'];
				$list[$i]['is_admin'] = $row['is_admin'];
				$i++;
			}
			return $list;
		} catch (Exception $e) {
			return "ERROR: ".$e->getMessage();
		}
	
	} //function getUsers

	//actualiza account info para el usuario (admin)
	function setUserInfo ($pArrData) {
	
		try {
	
			$db = new sentencia();
			$affected_rows=0;
			
			//averigua primero si existe para hacer Update o Insert
			$qy="select count(1) cuantos from users where id_user = ".$pArrData['iduser'];
			$result = $db->Query($qy);
				
			//*********NO ESTA BIEN MODELADO USAR ROWCOUNT*********
			//$rowcount=mysqli_num_rows($resultUsers);
			//return $rowcount;
				
			$existe=0;
			foreach($result as $row){
				$existe = $row['cuantos'];
			}

			if ($pArrData['revenuepercentcustom']=='' || $pArrData['revenuepercentcustom']=='0'){
				$revenue_percent_type = 'G';
				$revenue_percent_custom = 'NULL';
			} else {				
				$revenue_percent_type = 'C';
				$revenue_percent_custom = $pArrData['revenuepercentcustom'];
			}			

			if ($pArrData['fixedcpm']=='' || $pArrData['fixedcpm']=='0'){
				$fixedcpm = 'NULL';
			} else {
				$fixedcpm = $pArrData['fixedcpm'];
			}
				
			if ($pArrData['isadmin']=='Yes'){
				$is_admin = 1;
			} else {
				$is_admin = 'NULL';
			}
				
			if ($existe>=1) { //si existe hace un update
				$query="update users set
							is_admin = ".$is_admin."
						where id_user = ".$pArrData["iduser"].";";
				
				if ($pArrData['id_site']=='') { //nuevo site
					$query.="insert into user_sites (name,                        id_user,                 revenue_percent_type,        revenue_percent_custom,      fixed_cpm)
								 			values  ('".$pArrData['sitename']."', ".$pArrData['iduser'].", '".$revenue_percent_type."', ".$revenue_percent_custom.", ".$fixedcpm.")";
				} else { //site existente
					$query.="update user_sites set
								name = '".$pArrData['sitename']."',
								revenue_percent_type = '".$revenue_percent_type."',
								revenue_percent_custom = ".$revenue_percent_custom.",
								fixed_cpm = ".$pArrData['fixedcpm']."
							where id_site = ".$pArrData['id_site'];
				}
				//return $query;
				$result = $db->multiSentenceDML($query);
				
				if ($result){
					return "OK";
				}else{
					return "ERROR: Not saved";
				}
								
			} else { //si NO existe hace un insert

				//$query="insert into user_account (id_user, beneficiary_name, beneficiary_address1, beneficiary_state, beneficiary_id_country, beneficiary_id_currency, beneficiary_account_number, beneficiary_bank_name, beneficiary_bank_swift, beneficiary_bank_address1, beneficiary_bank_id_country, intermediary_bank_name, intermediary_bank_swift, intermediary_bank_account_number, intermediary_bank_address1, intermediary_bank_id_country)
				//			values (".$_SESSION['login']["id_user"].", '".$pArrData['beneficiary_name']."', '".$pArrData['beneficiary_address1']."', '".$pArrData['beneficiary_state']."', '".$pArrData['beneficiary_id_country']."', '".$pArrData['beneficiary_id_currency']."', '".$pArrData['beneficiary_account_number']."', '".$pArrData['beneficiary_bank_name']."', '".$pArrData['beneficiary_bank_swift']."', '".$pArrData['beneficiary_bank_address1']."', '".$pArrData['beneficiary_bank_id_country']."', '".$pArrData['intermediary_bank_name']."', '".$pArrData['intermediary_bank_swift']."', '".$pArrData['intermediary_bank_account_number']."', '".$pArrData['intermediary_bank_address1']."', '".$pArrData['intermediary_bank_id_country']."')";
				//$result = $db->sentenceDML($query,$affected_rows);
				
				//if ($result){
				//	return "OK";
				//}else{
				//	return "ERROR: Not saved";
				//}
				
			} //update o insert
				
		} catch (Exception $e) {
			return "ERROR: ".$e->getMessage();
		}
	
	} //function setUserInfo

	function deleteUserInfo ($pArrData) {
	
		try {
	
			$db = new sentencia();
			$affected_rows=0;

			//borra usuario y tablas linkeadas
			$query="delete from users where id_user = ".$pArrData["iduser"].";";
			$query.="delete from user_sites where id_user = ".$pArrData["iduser"].";";
			$result = $db->multiSentenceDML($query);
			if ($result){
				return "OK";
			}else{
				return "ERROR: ".$result;
			}
							
		} catch (Exception $e) {
			return "ERROR: ".$e->getMessage();
		}
	
	} //function deleteUserInfo
	
	//agrega un nuevo usuario
	function addUser ($pArrData) {
	
		try {
	
			$db = new sentencia();
			$affected_rows=0;
			
			if ($pArrData['revenuepercentcustom']=='' || $pArrData['revenuepercentcustom']=='0'){
				$revenue_percent_type = 'G';
				$revenue_percent_custom = 'NULL';
			} else {
				$revenue_percent_type = 'C';
				$revenue_percent_custom = $pArrData['revenuepercentcustom'];
			}
				
			if ($pArrData['isadmin']=='Yes'){
				$is_admin = 1;
			} else {
				$is_admin = 'NULL';
			}
				
			$query="insert into users (username,                    password,                    is_admin)
						       values ('".$pArrData['username']."', '".$pArrData['password']."', ".$is_admin.");";
			$query.="SET @last_id_in_table1 = LAST_INSERT_ID();";
			$query.="insert into user_sites (id_user,          name,                        revenue_percent_type,        revenue_percent_custom,      fixed_cpm,                   is_default)
						       		 values (LAST_INSERT_ID(), '".$pArrData['sitename']."', '".$revenue_percent_type."', ".$revenue_percent_custom.", '".$pArrData['fixedcpm']."', 1);";
			$query.="update users set id_site_default = LAST_INSERT_ID() where id_user = @last_id_in_table1"; 
				
			$result = $db->multiSentenceDML($query);
			
			if ($result){
				return "OK";
			}else{
				return "ERROR: Not saved";
			}
			
		} catch (Exception $e) {
			return "ERROR: ".$e->getMessage();
		}
	
	} //function addUser
	
	//Obtiene la lista de sites de un usuario
	function getUserSites($p_idUser=0) {
	
		try {
			
			$v_idUser=$p_idUser;
			if ($v_idUser==0)
				$v_idUser=$_SESSION['login']["id_user"];
							
			$db = new sentencia();
				
			//$qy="select a.id_site, a.name,  if(a.id_site = b.id_site_default,1,0) is_default from user_sites a left outer join users b on a.id_user = b.id_user where b.id_user = " . $v_idUser . " order by is_default DESC, a.name";
			$qy="select a.id_site, a.name, IFNULL(a.is_default,0) is_default from user_sites a left outer join users b on a.id_user = b.id_user where b.id_user = " . $v_idUser . " order by a.name";
			$result = $db->Query($qy);
				
			//*********NO ESTA BIEN MODELADO USAR ROWCOUNT*********
			//$rowcount=mysqli_num_rows($resultUsers);
			//return $rowcount;
				
			$list = array();
			$i=0;
			foreach($result as $row){
				$list[$i]['id_site'] = $row['id_site'];
				$list[$i]['name'] = $row['name'];
				$list[$i]['is_default'] = $row['is_default'];
				$i++;
			}
			return $list;
		} catch (Exception $e) {
			return "ERROR: ".$e->getMessage();
		}
	
	} //function getUserSites
	
	//Obtiene el sitename default del cliente
	function getSiteName($p_idSite=0) {
	
		try {
				
			$db = new sentencia();	
			$qy="select name from user_sites where id_site = " . $p_idSite;
			$result = $db->Query($qy);
	
			//*********NO ESTA BIEN MODELADO USAR ROWCOUNT*********
			//$rowcount=mysqli_num_rows($resultUsers);
			//return $rowcount;
	
			$existe='';
			foreach($result as $row){
				$existe = $row['name'];
			}
			return $existe;
				
			
		} catch (Exception $e) {
			return "ERROR: ".$e->getMessage();
		}
			
	} //function getSiteName
	
	//Obtiene los datos de un sitename especifico
	function getSiteInfo($p_idSite=0) {
	
		try {
				
			$db = new sentencia();	
			$qy="select * from user_sites where id_site = " . $p_idSite;
			$result = $db->Query($qy);
	
			//*********NO ESTA BIEN MODELADO USAR ROWCOUNT*********
			//$rowcount=mysqli_num_rows($resultUsers);
			//return $rowcount;
	
			$list = array();
			$i=0;
			foreach($result as $row){
				$list[$i]['id_site'] = $row['id_site'];
				$list[$i]['name'] = $row['name'];
				$list[$i]['id_user'] = $row['id_user'];
				$list[$i]['revenue_percent_type'] = $row['revenue_percent_type'];
				$list[$i]['revenue_percent_custom'] = $row['revenue_percent_custom'];
				$list[$i]['fixed_cpm'] = $row['fixed_cpm'];
				$list[$i]['is_default'] = $row['is_default'];
				$i++;
			}
			return $list;
			
		} catch (Exception $e) {
			return "ERROR: ".$e->getMessage();
		}
			
	} //function getSiteInfo
	
	//actualiza el Site Default para un usuario)
	function setUserDefaultSite ($pIdSite, $pIdUser) {
	
		try {
	
			$affected_rows=0;
			$db = new sentencia();
			$query="update users set id_site_default = '".$pIdSite."' where id_user = ".$pIdUser.";";
			$query.="update user_sites set is_default = NULL where id_user = ".$pIdUser.";";
			$query.="update user_sites set is_default = 1 where id_site = '".$pIdSite."';";
			$result = $db->multiSentenceDML($query);

			if ($result){
				
				$_SESSION['login']["id_site_default"] = $pIdSite;

				$revenue_percent = 0;
				$qy="select * from settings where name in ('revenue_percent')";
				$resultSettings = $db->Query($qy);
				foreach($resultSettings as $row){
					$revenue_percent = $row['value'];
				}

				$qy="select * from user_sites where id_site = " . $pIdSite;
				$result2 = $db->Query($qy);
				foreach($result2 as $row){
					$_SESSION['login']["id_site_default"] = $pIdSite;
					$_SESSION['login']["site_name"] = $row['name'];
					//if 'C' uses custom settings in 'Users' table, otherwise uses global settings table
					if($row['revenue_percent_type']=='C'){ $revenue_percent = $row['revenue_percent_custom']; }
					$_SESSION['login']["revenue_percent"] = $revenue_percent;
					$_SESSION['login']["fixed_cpm"] = $row['fixed_cpm'];
				}
				
				return "OK";
			}else{
				return "ERROR: Not saved ".$query;
			}
				
		} catch (Exception $e) {
			return "ERROR: ".$e->getMessage();
		}
	
	} //function setUserDefaultSite
	
	//actualiza el Site Default para un usuario)
	function removeUserSite ($pArrData) {
	
		try {
			
			if ($pArrData['isdefault']!=1) {
				
				
				$db = new sentencia();
				$affected_rows=0;
					
				//averigua primero si existe para hacer Update o Insert
				$qy="select count(1) cuantos from user_sites where id_user = ".$pArrData['iduser'];
				$result = $db->Query($qy);
				
				//*********NO ESTA BIEN MODELADO USAR ROWCOUNT*********
				//$rowcount=mysqli_num_rows($resultUsers);
				//return $rowcount;
				
				$existe=0;
				foreach($result as $row){
					$existe = $row['cuantos'];
				}
				
				if ($existe>1) { //si existe mas de uno entonces puede borrar
					$query="delete from user_sites where id_site = '".$pArrData['idsite']."';";
					$result = $db->sentenceDML($query, $affected_rows);
					if ($result){
						return "OK";
					}else{
						return "ERROR: Not saved ".$query;
					}
				} else {
					return 'At least one site must be defined per user, cannot delete this site';
				}
				
			} else {
				return 'Cannot delete the user\'s default site';
			}
			
			
		} catch (Exception $e) {
			return "ERROR: ".$e->getMessage();
		}
	
	} //function removeUserSite
	
}
?>