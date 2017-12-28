<?php

session_start();
require_once 'constants.php';
require_once 'includes.php';

try {
	
	$cSecurity = new evSecurity();
	$result = $cSecurity->recover($_POST['email']);
	echo $result;
	
} catch (Exception $e) {
	echo $e->getMessage();
}
	
?>
