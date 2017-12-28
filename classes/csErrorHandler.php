<?php

//[*]---------------------------------------------------------------[*]
// | Parsea el error que ocurrio y devuelve el mensaje mas apropiado |
//[*]---------------------------------------------------------------[*]
function customErrorHandler($errno, $errstr, $errfile, $errline) {
		
	/*
	if (!(error_reporting() & $errno)) {
		// This error code is not included in error_reporting
		return;
	}
	*/
	
	switch ($errno) {
		case E_USER_ERROR:
			echo "<b>My ERROR</b> [$errno] $errstr<br />\n";
			echo "  Fatal error on line $errline in file $errfile";
			echo ", PHP " . PHP_VERSION . " (" . PHP_OS . ")<br />\n";
			echo "Aborting...<br />\n";
        	break;
	
		case E_USER_WARNING:
			echo "<b>My WARNING</b> [$errno] $errstr<br />\n";
			break;
	
		case E_USER_NOTICE:
			echo "<b>My NOTICE</b> [$errno] $errstr<br />\n";
			break;
	
		default:
			//die("<b>Error:</b> [$errno] $errstr <br> at line [$errline] in $errfile");
			echo "Error: [$errno] $errstr at line [$errline] in $errfile\n";
			throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
			//trigger_error('Error: config is not an array or is not set', E_USER_ERROR);
			break;
	}
	
	
	/* Don't execute PHP internal error handler */
	return true;	
		
	
}


// function error_handler($output)
// {
// 	$error = error_get_last();
// 	$output = "";
// 	foreach ($error as $info => $string)
// 		$output .= "{$info}: {$string}\n";
// 	return $output;
// }

function fatal_handler() {

	$error  = error_get_last();
	
	//check if it's a core/fatal error, otherwise it's a normal shutdown
	if($error !== NULL && $error['type'] === E_ERROR) {
		//error_mail(format_error( $errno, $errstr, $errfile, $errline));
		die ($error['message']);
		
		/*
		//Bit hackish, but the set_exception_handler will return the old handler
		function fakeHandler() { }
		$handler = set_exception_handler('fakeHandler');
		restore_exception_handler();
		if($handler !== null) {
			call_user_func($handler, new ErrorException($error['message'], $error['type'], 0, $error['file'], $error['line']));
		}
		*/
	}
}


?>