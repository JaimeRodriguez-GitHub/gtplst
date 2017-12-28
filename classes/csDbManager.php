<?php

/* Clase encargada de gestionar las conexiones a la base de datos */
Class DB{

    //------ Local ------
    private $db_host='127.0.0.1';
	private $db_username='root';
	private $db_password='Lankin01';
	private $db_name='pedidos';

    //------ Remoto Test ------
	//private $db_host='www.guateplast-apg.com';
	//private $db_username='guatepla_jaime';
	//private $db_password='24271400';
	//private $db_name='guatepla_pedidos_test';

    //------ Remoto Produccion ------
	//private $db_host='www.guateplast-apg.com';
	//private $db_username='guatepla_jaime';
	//private $db_password='24271400';
	//private $db_name='guatepla_pedidos';

   	private $_connection;
   	private static $_instance;

   	//Función encargada de crear, si es necesario, el objeto. Esta es la función que debemos llamar desde fuera de la clase para instanciar el objeto, y así, poder utilizar sus métodos
 	public static function getInstance(){
    	if (!(self::$_instance instanceof self)){
        	self::$_instance = new self();
    	}
    	return self::$_instance;
	} //function getInstance

   	// Constructor privado, previene la creación de objetos vía new
	private function __construct() {
/*
mysqli_report(MYSQLI_REPORT_STRICT);
try {
		$this->_connection = new mysqli($this->db_host, $this->db_username, $this->db_password, $this->db_name);
} catch (Exception $e ) {
     echo "Service unavailable";
     echo "messagexxx: " . $e->getMessage();   // not in live code obviously...
     exit;
}
*/
		$this->_connection = new mysqli($this->db_host, $this->db_username, $this->db_password, $this->db_name);

		// Error handling
		if(mysqli_connect_error()) {
			trigger_error("Failed to conencto to MySQL: " . mysql_connect_error(), E_USER_ERROR);
		}
	}

	// Magic method clone is empty to prevent duplication of connection
	private function __clone() { }

	// Get mysqli connection
	protected function getConnection() {
		return $this->_connection;
	}

	//Cierra la conexion al destruirse la clase
	public function __destruct() {
    	//////////$this->_connection->close();
	}


   /*Realiza la conexión a la base de datos.*/
   /*
   public function conectar(){
     try {
     	//$this->link = new PDO("mysql:host=$this->servidor;dbname=$this->base_datos", $this->usuario, $this->password);
     	$this->link = new PDO("mysql:host=$this->db_host; dbname=$this->db_name", $this->db_user, $this->db_pwd);
	 } catch(PDOException $e) {
      	echo $e->getMessage();
     }  
   }  //function conectar
   */

   /*
	public function close(){
		$this->link = null;
	}
	*/

	/*Método para ejecutar una sentencia sql*/
	/*
	public function ejecutar($sql){
		//include_once 'classes/evErrorHandler.php';
		include_once 'evErrorHandler.php';
		// ini_set('display_errors', false);
		// error_reporting( E_ALL );	
		set_error_handler('customErrorHandler');
		register_shutdown_function('fatal_handler');   	
	    $this->stmt=$this->link->prepare($sql);
		 return $this->stmt;
	}
	*/
 



} //Class Db

class DbSentencia extends DB {

   	private $dbc;
   	private $mysqli;

   	//al instanciar la clase DBSentencia se obtiene una conexion
	function __construct() {
	}

	public function __call($name, $args) {
		switch ($name) {
            case 'gpQuery':
				switch (count($args)) {
                    case 1:
                        return call_user_func_array(array($this, 'gpQuery'), $args);
                    case 2:
                        return call_user_func_array(array($this, 'gpQueryCnn'), $args);
                 }
            case 'gpScalarQuery':
				switch (count($args)) {
                    case 1:
                        return call_user_func_array(array($this, 'gpScalarQuery'), $args);
                    case 2:
                        return call_user_func_array(array($this, 'gpScalarQueryCnn'), $args);
                 }
            case 'gpSentence':
				switch (count($args)) {
                    case 1:
                        return call_user_func_array(array($this, 'gpSentence'), $args);
                    case 2:
                        return call_user_func_array(array($this, 'gpSentenceCnn'), $args);
                 }
        }
    }

	public function getConnection() {
		return $this->getInstance()->getConnection();
	}

	public function gpStartTransaction() {
		try{
			$cnn = $this->getInstance()->getConnection();
			$result = $cnn->query('START TRANSACTION');	
			return $cnn;
		}
   		catch(Exception $e){
	   		echo "OOps!! Algo salio mal intenta de nuevo:  ".$e;
	   		return '';
	   	}   
	}

	public function gpCommitTransaction($cnn) {
		$cnn->query('COMMIT');
	}

	public function gpRollbackTransaction($cnn) {
		$cnn->query('ROLLBACK');
	}

	public function gpGetLastID($cnn) {
		//$sql = 'select LAST_INSERT_ID()';
		//$result = $cnn->query($sql);
		//return $result;
		return $cnn->insert_id;
	}

    protected function gpQuery($sql){
		try{
			//$dbc=$this->getInstance();
			//$mysqli = $dbc->getConnection();
			$cnn = $this->getInstance()->getConnection();
			$result = $cnn->query($sql);	
			return $result;
		}
   		catch(Exception $e){
	   		echo "OOps!! Algo salio mal intenta de nuevo:  ".$e;
	   	}   
   	} //gpQuery

    protected function gpQueryCnn($sql, $cnn){
		try{
			$result = $cnn->query($sql);	
			return $result;
		}
   		catch(Exception $e){
	   		echo "OOps!! Algo salio mal intenta de nuevo:  ".$e;
	   	}   
   	} //gpQueryCnn

    protected function gpScalarQuery($sql){
		try{
			$cnn = $this->getInstance()->getConnection();
			$result = $cnn->query($sql);
			mysqli_data_seek($result, 0);
        	$row = mysqli_fetch_array($result);
        	return $row[0];
		}
   		catch(Exception $e){
	   		echo $sql." OOps!! Algo salio mal intenta de nuevo:  ".$e;
	   	}   
   	} //gpScalarQuery

    protected function gpScalarQueryCnn($sql, $cnn){
		try{
			$result = $cnn->query($sql);	
			mysqli_data_seek($result, 0);
        	$row = mysqli_fetch_array($result);
        	return $row[0];
		}
   		catch(Exception $e){
	   		echo "OOps!! Algo salio mal intenta de nuevo:  ".$e;
	   	}   
   	} //gpScalarQueryCnn

    protected function gpSentence($sql){
		try{
			$cnn = $this->getInstance()->getConnection();
			$result = $cnn->query($sql);	
			return $result;
		}
   		catch(Exception $e){
	   		echo "OOps!! Algo salio mal intenta de nuevo:  ".$e;
	   	}   
   	} //gpSentence

    protected function gpSentenceCnn($sql, $cnn){
		try{
			$result = $cnn->query($sql);
			if(!$result) {
				return $cnn->error;
			} else { return $result; }
			
		}
   		catch(Exception $e){
	   		echo "OOps!! Algo salio mal intenta de nuevo:  ".$e;
	   	}   
   	} //gpSentence

}

?>