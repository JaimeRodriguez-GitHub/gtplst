<?php

/* Clase encargada de gestionar las conexiones a la base de datos */
Class DB{

	//$db_host='www.perfetomail.com';
	//$db_user='perfetodb';
	//$db_pwd='P3rf3tin27012014';
	//$db_name='dsp';
	private $db_host='www.guateplast-apg.com';
	private $db_username='guatepla_jaime';
	private $db_password='24271400';
	private $db_name='guatepla_pedidos';

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
	public function getConnection() {
		return $this->_connection;
	}

	//Cierra la conexion al destruirse la clase
	public function __destruct() {
    	$this->_connection->close();
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

?>