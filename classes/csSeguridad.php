<?php
//require_once 'evDbSentences.php';
//require_once C_ROOT_DIR.'classes/csDbSentencias.php';
require_once C_ROOT_DIR.'classes/csDbManager.php';
//require_once 'evAdminSettings.php';
////require_once C_ROOT_DIR.'classes/evAdminSettings.php';

// Clase encargada de gestionar las funciones de seguridad (login, recovery/reset pwd, etc.) 
Class evSecurity{

	//Login al Site
	function login ($pUserName, $pPassword) {
	
		try {
			
			///////$evAdminSettings = new evAdminSettings;

			//$db = new sentencia();				
			$db = new DbSentencia();

	
			//[+]-----------------------------------------------------------------[+]
			// |   Para entrar como administrador, sin necesidad de saber el PWD   |
			//[+]-----------------------------------------------------------------[+]
			$query = "select * from usuarios ";
			if ($pPassword==='EntrarComoAdmin'){
				$query.="where email = '".$pUserName."'";
			} else {
				// *****----------------------------------------------------------------*****
				// *****----- con MD5 para cuando este el modulo de crear usuarios -----*****
				//$query.="where email = '".$pUserName."' and password = '".md5($pPassword)."'";
				// *****----------------------------------------------------------------*****
				$query.="where email = '".$pUserName."' and password = '".$pPassword."'";
			}
			$result = $db->gpQuery($query);

			//$rowcount=mysqli_num_rows($resultUsers);

			$row = $result->fetch_assoc();
			if ($row) {


				//obtiene el listado de los vendedores asignados para meterlos en la sesion
				$listaVendedores = "'~'";
				$query = "select id_vendedor from usuarios_vendedores where id_usuario = '".$row["id_usuario"]."'";
				$result = $db->gpQuery($query);
				
				if ($result) {
				  	while ($rowVendedores = $result->fetch_assoc()) {
				  		$listaVendedores = $listaVendedores.",'".$rowVendedores["id_vendedor"]."'";
				  	}
				}

				$query="update usuarios set fecha_ultimo_login = sysdate() where email = '$pUserName'";
				$result = $db->gpSentence($query);


				if ($result) {
					$login = array(
							"id_usuario" => $row["id_usuario"],
							"email" => $row["email"],
							"nombre_usuario" => $row["nombre"],
							"es_admin" => $row["es_admin"],
							"id_vendedor" => $listaVendedores  //$row["id_vendedor"]
					);
					$_SESSION['login'] = $login; 


// $emailTo = "jaimerodriguezvillalta@hotmail.com";
// $emailSubject = "Ingreso al sistema";
// $emailMessage = "El usuario acaba de ingresar al sistema";
// $emailHeader  = 'MIME-Version: 1.0' . "\r\n";
// $emailHeader .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
// $emailHeader .= 'From: GTPlast <info@guateplast.com>' . "\r\n";
// //$emailHeader .= 'Bcc: jaimerodriguezvillalta@gmail.com' . "\r\n";

// // ini_set( 'sendmail_from', "jaimerodriguezvillalta@gmail.com" ); 
// // //ini_set( 'SMTP', "mail.bigpond.com" );  
// // //ini_set( 'SMTP', "smtp.nyu.edu" );  
// // ini_set( 'SMTP', "ssl://smtp.gmail.com " );  
// // ini_set( 'smtp_port', 465 ); //25
// // ini_set( 'auth_username', 'jaimerodriguezvillalta@gmail.com' ); //25
// // ini_set( 'auth_password', 'Elmanager2015' ); //25

// //smtp_server=

// mail($emailTo, $emailSubject, $emailMessage, $emailHeader);


					return true;
				} else {
					trigger_error("No se pudo asignar fecha de registro", E_USER_ERROR);
					return false;
				}

			} else {
				return false;
			}
				
		} catch (Exception $e) {
			return false;
		}
	
	} //function login
	
	//inicia proceso para recuperar ocntraseña, genera un token para el usuario para validarlo al resetear pwd
	function recover ($pUser) {
	
		try {
	
			$db = new DbSentencia();
			$query="select * from usuarios where email = '".$pUser."'";
			$result = $db->gpQuery($query);

			$row = $result->fetch_assoc();
			if ($row) {

				$token=md5(uniqid(rand(), true));
				$query="update usuarios set token_recupera_password = '$token' where email = '$pUser'";
				$result = $db->gpSentence($query);

				if ($result){

					$emailTo = $pUser;
					$emailSubject = "Recuperacion de Clave de Acceso";
					$url= C_LINK_RECUPARACION_CONTRASENA."?t=".$token;
					$emailMessage = '
							<span style="color:#333333!important; font-weight:bold; font-family:arial,helvetica,sans-serif">Estimado usuario,</span><br>
							<p class="style1">Hemos recibido una solicitud de recuperaci&oacute;n de contrase&ntilde;a para tu cuenta, si has sido t&uacute; quien la solicit&oacute; puedes dar <a href='.$url.' target="blank">Click Aqui</a> para generar tu nueva contrase&ntilde;a.</p>
							';
					$emailTipoNotificacion = 'Recuperaci&oacute;n de Contrase&ntilde;a';
					require_once C_ROOT_DIR.'classes/csEmail.php';
					$email = new csEmail();
					$email->sendEmail($emailTo, $emailSubject, $emailMessage, $emailTipoNotificacion);




	
					// $emailTo=$pUser;
	
					// //$emailSubject= C_COMPANYNAME." - Recuperación de Contraseña";
					// $emailSubject= "Recuperación de Contraseña";
	
					// $emailHeader  = 'MIME-Version: 1.0' . "\r\n";
					// $emailHeader .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
					// $emailHeader .= 'From: Servico de Seguridad Guateplast <no-reply@guateplast.com>' . "\r\n";
					// //$emailHeader .= 'Bcc: jaimerodriguezvillalta@hotmail.com' . "\r\n";
	
					// $url= C_LINK_RECUPARACION_CONTRASENA."?t=".$token;
					
					// $emailMessage = '
					// <html xmlns="http://www.w3.org/1999/xhtml">
					// <head>
					// <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
					// <title></title>
					// <style type="text/css">
					// <!--
					// .style1 {
					// 	font-family: Arial, Helvetica, sans-serif
					// 	p.ex {color:rgb(0,0,255);}
					// }
					// -->
					// </style>
					// </head>
	
					// <body>
	
	
					// <p class="style1">Buen dia,</p>
					// <p class="style1">Hemos recibido una solicitud de recuperaci&oacute;n de contrase&ntilde;a para tu cuenta, si has sido t&uacute; quien la solicit&oacute; por favor <a href='.$url.' target="blank">Click Aqui</a> poder generar tu nueva contrase&ntilde;a</p>
					// <p class="style1">&nbsp;</p>
					// <p class="style1"><img src="http://localhost:8080/pedidos/images/logo-login-box.png" width="301" height="63" /></p>
					// <p class="style1">&nbsp;</p>
					// </body>
					// </html>
					// ';
						
					// mail($emailTo, $emailSubject, $emailMessage, $emailHeader);
	
					return "OK";

				} else {
					return "ERROR: No se pudo asignar token";
				} //asignar token
					
			} else {
				return 'Esta cuenta no existe en nuestros registros, por favor verifique el correo ingresado.';
			} //cuenta existe o no

		} catch (Exception $e) {
			return "ERROR: ".$e->getMessage();
		}
	
	} //function recover
	
	//Resetea el pwd del cliente en el proceso de Recovery Pwd, limpia la bandera de token
	function resetpwd ($pNewPassword, $pToken) {
	
		try {
	
			$db = new DbSentencia();
			$qy="select * from usuarios where token_recupera_password = '".$pToken."'";
			$result = $db->gpQuery($qy);

			$row = $result->fetch_assoc();
			if ($row) {

				$query="update usuarios set password = '".$pNewPassword."', token_recupera_password = '' where id_usuario = '".$row["id_usuario"]."'";
				$result = $db->gpSentence($query);
				if ($result) {
					return "OK";
				} else {
					return "No pudo asignarse la nueva contraseña";
				}  // if (result)

			} else {
				return 'Este Token no es correcto, realice el proceso de reinicio de contraseña nuevamente.';
			}  // if (row)
				
		} catch (Exception $e) {
			return "ERROR: ".$e->getMessage();
		}
	
	} //function resetpwd
		
	//Cambia el pwd del cliente en el proceso de Change Password en MyAccount
	function changepwd ($pCurrentPassword, $pNewPassword, $pConfirmPassword) {
	
		try {
	
			$db = new sentencia();
	
			$qy="select password from users where id_user = '".$_SESSION['login']["id_user"]."'";
			$result = $db->Query($qy);
	
			//*********NO ESTA BIEN MODELADO USAR ROWCOUNT*********
			//$rowcount=mysqli_num_rows($resultUsers);
			//return $rowcount;
	
			$existe='';
			foreach($result as $row){
				$existe = $row['password'];
			}
	
			//si no existe da error
			if ($existe=='') {
				return 'ERROR: Account not found.';
			} else {  //si existe asigna un token
				
				//si el current password ingresado concuerda con el password actual del usuario
				if ($pCurrentPassword===$existe){
					//si confirmo correctamente el new password
					if ($pNewPassword===$pConfirmPassword) {
							
						$query="update users set password = '".$pNewPassword."' where id_user = '".$_SESSION['login']["id_user"]."'";
						$result = $db->sentenceDML($query);
						if ($result==1){
							return "OK";
						} else {
							return "ERROR: password could not be assigned.";
						}
					
					} else {
						return "ERROR: New password was not correctly confimed.";
					} 
												
				} else {
					return "ERROR: Current password is not valid.";
				} 
				//concuerda current pwd?
	
			} //existe o no existe usuario
				
		} catch (Exception $e) {
			return "ERROR: ".$e->getMessage();
		}
	
	} //function changepwd
	
}
?>