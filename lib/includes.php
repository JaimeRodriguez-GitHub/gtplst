<?php
#------ header obligatorio en todas las paginas -----#
if(!isset($_SESSION)) { session_start(); }
//ini_set('display_errors', false);
error_reporting( E_ALL );
//error_reporting( 0 );

//set_include_path( get_include_path() . PATH_SEPARATOR . $_SERVER['DOCUMENT_ROOT'] ); 

require_once C_ROOT_DIR.'lib/constants.php';
require_once C_ROOT_DIR.'classes/csErrorHandler.php';
set_error_handler('customErrorHandler');
register_shutdown_function('fatal_handler');
include_once C_ROOT_DIR.'classes/csSeguridad.php';
#------ header obligatorio en todas las paginas -----#
?>
