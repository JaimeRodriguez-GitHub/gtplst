<?php

require 'csDbConn.php';

/* Clase encargada de gestionar las conexiones a la base de datos */
Class sentencia{

    public function gpSelect($sql){
		try{
			$db=DB::getInstance();
			$mysqli = $db->getConnection();
			$result = $mysqli->query($sql);	
			return $result;
		}
   		catch(Exception $e){
	   		echo "OOps!! Algo salio mal intenta de nuevo:  ".$e;
	   	}   
   	} //Query
   
    public function gpQuery($sql){
		try{
			$db=DB::getInstance();
			$mysqli = $db->getConnection();
			$result = $mysqli->query($sql);	
			return $result;
		}
   		catch(Exception $e){
	   		echo "OOps!! Algo salio mal intenta de nuevo:  ".$e;
	   	}   
   	} //Query

	public function sentenceDML($query,&$affected_rows=0){
	   	// Connect to the database
	   	
	   	$db = new evDB();
	   	$connection = $db->connect();

	   	try {
	   		if ($connection->query($query) === TRUE) {
	   			$affected_rows=$connection->affected_rows;
	   			return TRUE;
	   		}else {
	   			trigger_error("Error: " . $connection->error . "<br>Query: " . $query);
	   			//return "Error: " . $connection->error . "<br>Query: " . $query;
	   			return FALSE;
	   		}
	   		$connection->close();	   		
	   	} catch (Exception $e) {
	   		$connection->close();
	   		return "ERROR: ".$e->getMessage();	   		
	   	}
	   	
   	}  #sentenceDML
    
	public function multiSentenceDML($query){
	   	// Connect to the database
	   	
	   	$db = new evDB();
	   	$connection = $db->connect();

	   	try {
	   		if ($connection->multi_query($query) === TRUE) {
	   			return TRUE;
	   		}else {
	   			trigger_error("Error: " . $connection->error . "<br>Query: " . $query);
	   			//return "Error: " . $connection->error . "<br>Query: " . $query;
	   			return FALSE;
	   		}
	   		$connection->close();	   		
	   	} catch (Exception $e) {
	   		$connection->close();
	   		return "ERROR: ".$e->getMessage();	   		
	   	}
	   	
   	}  #sentenceDML
   	
   	public function sentenciaSQL($sql){    	
        	    	
		try{ 
			$bd=Db::getInstance();
			$bd->conectar();
			$transaction = "start TRANSACTION";
			$stmt=$bd->ejecutar($transaction);
			$stmt->execute();	

			$stmt=$bd->ejecutar($sql);
			$stmt->execute();
		
			$transaction = "Commit";
			$stmt=$bd->ejecutar($transaction);
			$stmt->execute();

			return 1;				
  	   	}
   		catch(Exception $e){
	   		echo "Ups. Algo Salio mal Intenta de nuevo  ";
	   	}  
   	} #sentenciaSQL
   
} #Class

?>