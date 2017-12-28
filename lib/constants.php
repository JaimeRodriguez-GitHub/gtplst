<?php 
	define ("C_SITENAME", "");

	//LOCAL  WAMP64
	define ("C_ROOT_DIR", $_SERVER['DOCUMENT_ROOT'].C_SITENAME."/pedidos/");	
	//LOCAL
	//define ("C_ROOT_DIR", $_SERVER['DOCUMENT_ROOT'].C_SITENAME."pedidos/");	
	//REMOTO
	//define ("C_ROOT_DIR", $_SERVER['DOCUMENT_ROOT'].C_SITENAME."/");

	define ("C_LIMIT_QUERIES", 1000);	
	define ("C_ID_PEDIDOS_ESPECIALES", 1);	
	define ("C_ESTATUS_PEDIDOS_INGRESADOS", "I");
	define ("C_ESTATUS_PEDIDOS_RECHAZADOS", "R");

	//LOCAL
	define ("C_EMAIL_PEDIDOS_ESPECIALES", 'jaimerodriguezvillalta@hotmail.com');
	//REMOTO  *****  aqui va el email de andrea y del ingeniero *****
	//define ("C_EMAIL_PEDIDOS_ESPECIALES", 'wgonzalez@guateplast.com, andream@guateplast.com, jaimerodriguezvillalta@hotmail.com');

	//LOCAL
	define ("C_LINK_PEDIDOS_ESPECIALES", "http://localhost:8080/pedidos/aprobar-especiales.php");
	define ("C_LINK_CONSULTA_PEDIDOS", "http://localhost:8080/pedidos/consulta.php");
	define ("C_LINK_RECUPARACION_CONTRASENA", "http://localhost:8080/pedidos/resetpwd.html");
	//REMOTO
	//define ("C_LINK_PEDIDOS_ESPECIALES", "http://guateplast-apg.com/aprobar-especiales.php");
	//define ("C_LINK_CONSULTA_PEDIDOS", "http://guateplast-apg.com/consulta.php");
	//define ("C_LINK_RECUPARACION_CONTRASENA", "http://guateplast-apg.com/resetpwd.html");




	define ("C_LOGINURL", "index.php");
	define ("C_COMPANYNAME", "Guateplast");

	const CONSTANT_HOMEURL = 'inicio.php';
	const CONSTANT_LOGINURL = 'index.html';
	const CONSTANT_RECOVERURL = 'recover.html';
	const CONSTANT_RESETPWDURL = 'resetpwd.html';
	const CONSTANT_CHANGEPWDURL = 'myaccount_password.php';
?>