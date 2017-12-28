<?php
if(!isset($_SESSION)) { session_start(); }
//ini_set('display_errors', false);
error_reporting( E_ALL );
require_once 'constants.php';
require_once C_ROOT_DIR.'classes/csErrorHandler.php';
set_error_handler('customErrorHandler');
register_shutdown_function('fatal_handler');

try {
	
	require_once C_ROOT_DIR.'lib/includes.php';
	$cSecurity = new evSecurity();
	$result = $cSecurity->resetpwd($_POST['password'], $_POST['t']);
	if ($result=='OK') {
		header('Location: ../index.php?attempt=success');
	} else {
		header('Location: ../resetpwd.html?attempt=failed');
	}
} catch (Exception $e) {
	echo $e->getMessage();
}
	
?>
