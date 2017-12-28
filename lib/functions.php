<?php
function fixSQL($pString) {
	$returnValue = $pString;
	//$returnValue = utf8_decode(str_replace("'", "\'", $returnValue));
	//$returnValue = utf8_decode(str_replace('"', '\"', $returnValue));
	/////////////////////////////////$returnValue = str_replace("'", "\\-\\", $returnValue);
	$returnValue = str_replace("'", "\'", $returnValue);
	$returnValue = str_replace('"', '\"', $returnValue);
	//$returnValue = utf8_encode($returnValue);
	return $returnValue;
	//return utf8_decode(str_replace('"', '\"', $pString));
	//return utf8_decode(str_replace('"', '\"', $pString));
	//return $pString.replace("'", "\\'");
}
function fixSQL2($pString) {
	$returnValue = $pString;
	$returnValue = str_replace("'", "\\-\\", $returnValue);
	return $returnValue;
}
function fixSQLRead($pString) {
	return $pString;
	//return str_replace('"', "'", $pString);
	//return $pString.replace("'", "\\'");
}
function UnparseSQLInjection($pString) {
	$tmpVar=$pString;
	$tmpVar=str_replace('UNIO_', "UNION", $tmpVar);
	$tmpVar=str_replace('SELEC_', "SELECT", $tmpVar);
	$tmpVar=str_replace('FRO_', "FROM", $tmpVar);
	return $tmpVar;
}
function toCurrency($pNumber, $decimales=4){
	return 'Q'.number_format($pNumber, $decimales, '.', ',').'';
}
function parseNumberToSave($pNumber){
	$returnValue = str_replace('Q', '', $pNumber);
	$returnValue = str_replace(',', '', $returnValue);
	return $returnValue;
}
function toLocalDate($pDateToFormat){
	//return date_format($pDateToFormat, 'Y-m-d');
	$date = new DateTime($pDateToFormat);
	return $date->format('d-m-Y');
}
function userHasRoles($idRoles){
	require_once C_ROOT_DIR.'classes/csUsuario.php';
    $user = new csUsuario();
    return $user->hasRole($idRoles);
}
function noAccessPage(){
	$message = '<div class="alert alert-danger" role="alert">
  				<span style="font-size:3.5em; float: left;"  class="glyphicon glyphicon-lock" aria-hidden="true"></span>
  				<div style="margin: -8px 0px 0px 60px;"><h2 class="alert-danger">Lo sentimos!</h2>
  					<span class="sr-only">Error:</span>
  					<p class="alert-danger">Usted no tiene privilegios de acceso para la opci&oacute;n seleccionada.</p>
  				</div>
			</div>
			';
return $message;
}

?>
