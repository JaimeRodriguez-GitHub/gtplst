<?php

session_start();
//error_reporting( E_ALL );
require_once 'constants.php';

//set_include_path( get_include_path() . PATH_SEPARATOR . $_SERVER['DOCUMENT_ROOT'] ); 
require_once C_ROOT_DIR.'lib/includes.php';

try {
	
	$cSecurity = new evSecurity();
	$result = $cSecurity->login($_POST['email'],$_POST['password']);
    
 	if ($result) {
		header('Location: ../dashboard.php');		 //.$cConstants::CONSTANT_HOMEURL
	} else {
		header('Location: ../index.php?attempt=failed');
	}
	
} catch (Exception $e) {
	echo $e->getMessage();
}

?>