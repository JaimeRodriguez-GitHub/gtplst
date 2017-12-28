<?php
class customException extends Exception {
	
	//[*]---------------------------------------------------------------[*]
	// | Parsea el error que ocurrio y devuelve el mensaje mas apropiado |
	//[*]---------------------------------------------------------------[*]
	public function errorMessage() {
		
		$errMessage = 'Error on line '.$this->getLine().' in '.$this->getFile().': <b>'.$this->getMessage();
		return $errMessage;
		
	} //errorMessage()
	
} //class customException
?>