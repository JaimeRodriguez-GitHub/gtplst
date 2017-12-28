<?php

require_once C_ROOT_DIR.'classes/csDbManager.php';
require_once C_ROOT_DIR.'classes/csErrorHandler.php';
require_once C_ROOT_DIR.'lib/functions.php';

set_error_handler('customErrorHandler');
register_shutdown_function('fatal_handler');


Class csPedidos {
	
	function getListadoPedidos($pIdCliente = '', $pTopN = '') {
		try {
			
			$query = "Select * From vw_pedidos";
			if($pIdCliente!='') { $query=$query." Where id_cliente = '$pIdCliente'"; }
			else { $query=$query." Where id_vendedor in (".$_SESSION['login']['id_vendedor'].")"; }
			$query=$query." Order by id_pedido desc"; 
			$query=$query." Limit ".C_LIMIT_QUERIES; 
			
			$db = new DbSentencia();
			$result = $db->gpQuery($query);
			return $result;

		} catch(Exception $e) {
			return "";
		}
	}  //function getPedidos

	function getPedidosIngresados($pIdCliente = '', $pIdVendedor = '', $pIdCampania = '', $pTopN = '') {
		try {
			
			$query = "Select * From vw_pedidos_ingresados Where 1=1";
			if($pIdCliente!='') { $query=$query." and id_cliente = '$pIdCliente'"; }
			if($pIdVendedor!='') { $query=$query." and id_vendedor = '$pIdVendedor'"; }
			if($pIdCampania!='') { $query=$query." and id_campania = '$pIdCampania'"; }
			$query=$query." Order by id_pedido desc"; 
			$query=$query." Limit ".C_LIMIT_QUERIES; 
			
			$db = new DbSentencia();
			$result = $db->gpQuery($query);
			return $result;

		} catch(Exception $e) {
			return "";
		}
	}  //function getPedidos

	function getPedidosEspeciales($pIdCliente = '', $pIdVendedor = '', $pTopN = '') {
		try {
			
			$query = "Select * From vw_pedidos_especiales Where 1=1";
			if($pIdCliente!='') { $query=$query." and id_cliente = '$pIdCliente'"; }
			if($pIdVendedor!='') { $query=$query." and id_vendedor in ($pIdVendedor)"; }
			$query=$query." Order by id_pedido desc"; 
			$query=$query." Limit ".C_LIMIT_QUERIES; 
			
			$db = new DbSentencia();
			$result = $db->gpQuery($query);
			return $result;

		} catch(Exception $e) {
			return "";
		}
	}  //function getPedidos

	function getPedidosParaConfirmar($pIdCliente = '', $pIdVendedor = '', $pIdCampania = '', $pTopN = '') {
		try {
			
			$query = "Select * From vw_pedidos_confirmar Where 1=1";
			if($pIdCliente!='') { $query=$query." and id_cliente = '$pIdCliente'"; }
			if($pIdVendedor!='') { $query=$query." and id_vendedor in ($pIdVendedor)"; }
			if($pIdCampania!='') { $query=$query." and id_campania = '$pIdCampania'"; }
			$query=$query." Order by id_pedido desc"; 
			$query=$query." Limit ".C_LIMIT_QUERIES; 
			
			$db = new DbSentencia();
			$result = $db->gpQuery($query);
			return $result;

		} catch(Exception $e) {
			return "";
		}
	}  //function getPedidos

	function Grabar($pNumeroPedido, $pDatosPedido) {
	// $pNumeroPedido = si es 0 es un pedido nuevo
	// $pDatosPedido = array con los datos a grabar
	// RETORNA: numero de pedido asignado

/*
  //$numPedido = gettype(json_decode($_POST['datos_pedido'],true));
  $numPedido = json_decode($_POST['datos_pedido'],true);
  $numPedido2 = json_decode(json_encode($numPedido['detalle']),true);
  $tipo1 = gettype($numPedido);
  $tipo2 = gettype($numPedido2);
  //var_dump($numPedido['detalle']);
  //echo $numPedido['2'];
  //echo count($numPedido2);
  //echo $numPedido['codigo_cliente'];
  echo $numPedido2[1]['codigo_producto'];
*/

//var_dump($pDatosPedido);

		$arrEncabezado = json_decode($pDatosPedido,true);
		$arrDetalle = json_decode(json_encode($arrEncabezado['detalle']),true);
		//return $arrEncabezado['codigo_cliente'];
		//return $arrDetalle[0]['codigo_producto'];
		//echo $pDatosPedido;
		//die();

		// return count($arrDetalle);
		// die();

		try {

			$db = new DbSentencia();
			$cnn = $db->gpStartTransaction();

			try{

				$tDia = substr($arrEncabezado['fecha_entrega'],0,2);
				$tMes = substr($arrEncabezado['fecha_entrega'],3,2);
				$tAno = substr($arrEncabezado['fecha_entrega'],6,4);
				$tFechaEntrega = $tAno."-".$tMes."-".$tDia;

				$query = "select ifnull(id_vendedor,'') from clientes where id_cliente = '".$arrEncabezado['codigo_cliente']."'";
				$id_vendedor = $db->gpScalarQuery($query);

				// si no trae # pedido es un nuevo pedido
				if (empty($arrEncabezado['id_pedido'])) { 
					$query = "insert into pedidos_encabezado (id_cliente,                             nombre_cliente,                                                      tipo_pago,                         direccion_entrega,                                                      id_vendedor,         fecha_entrega,        id_campania,                       observaciones,                                                   status, total_solicitado,                                          bit_usuario_ingreso)";
					$query = $query . "               values ('".$arrEncabezado['codigo_cliente']."', '".UnparseSQLInjection(fixSQL($arrEncabezado['nombre_cliente']))."', '".$arrEncabezado['tipo_pago']."', '".UnparseSQLInjection(fixSQL($arrEncabezado['direccion_entrega']))."', ".$id_vendedor.", '".$tFechaEntrega."', ".$arrEncabezado['id_campania'].", '".UnparseSQLInjection(fixSQL($arrEncabezado['observaciones']))."', 'I',    ".parseNumberToSave($arrEncabezado['total_solicitado']).", '".$_SESSION['login']['id_usuario']."')"; 
				} else {  // si trae es un update
					$update_estado = $arrEncabezado['es_confirmacion'] ? ", status = 'A' " : ", status = 'I' ";  //si es confirmar actualiza estatus
					$query = "update pedidos_encabezado set tipo_pago = '".$arrEncabezado['tipo_pago']."', direccion_entrega = '".UnparseSQLInjection(fixSQL($arrEncabezado['direccion_entrega']))."', fecha_entrega = '".$tFechaEntrega."', observaciones = '".UnparseSQLInjection(fixSQL($arrEncabezado['observaciones']))."', total_solicitado = ".parseNumberToSave($arrEncabezado['total_solicitado']).$update_estado;
					$query = $query . " where id_pedido = ".$arrEncabezado['id_pedido'];
				}
				//return $query;
				//die();
				$result = $db->gpSentence($query, $cnn);
				if (is_bool($result) and $result) {

					$hacerCommit = true;
					$last_id = empty($arrEncabezado['id_pedido']) ? $cnn->insert_id : $arrEncabezado['id_pedido'];

					$query = "delete from pedidos_detalle where id_pedido = ".$arrEncabezado['id_pedido'];
					$result = $db->gpSentence($query, $cnn);							
					$total_solicitado = 0;

					foreach ($arrDetalle as $key => $arrProductos) {

						//antes de la funcion $parseNumberToSave
						//$query = "insert into pedidos_detalle (id_pedido,    codigo_producto,                        upc,                        descripcion,                                unidades_empaque,                        precio,                                                  precio_base,                                       cantidad_solicitada,                        total_solicitado)";
						//$query = $query . "            values (".$last_id.", '".$arrProductos['codigo_producto']."', '".$arrProductos['upc']."', '".fixSQL($arrProductos['descripcion'])."', '".$arrProductos['unidades_empaque']."', ".str_replace('Q', '', $arrProductos['precio_final']).", ".str_replace('Q', '', $arrProductos['precio']).", '".$arrProductos['cantidad_solicitada']."', ".str_replace('Q', '', $arrProductos['total_solicitado']).")";
						$query = "insert into pedidos_detalle (id_pedido,    codigo_producto,                        upc,                        descripcion,                                unidades_empaque,                        precio,                                               precio_base,                                    cantidad_solicitada,                                           total_solicitado)";
						$query = $query . "            values (".$last_id.", '".$arrProductos['codigo_producto']."', '".$arrProductos['upc']."', '".fixSQL($arrProductos['descripcion'])."', '".$arrProductos['unidades_empaque']."', ".parseNumberToSave($arrProductos['precio_final']).", ".parseNumberToSave($arrProductos['precio']).", '".parseNumberToSave($arrProductos['cantidad_solicitada'])."', ".parseNumberToSave($arrProductos['total_solicitado']).")";
						//echo $query;
						//return $query;
						//die();
						$result = $db->gpSentence($query, $cnn);							
						//$result = $db->gpSentence($query, $cnn);							
						if (is_bool($result) and $result) {  //no hay error
						}
						else { //si hay error
							//return $result;
							//die();
							$duplicate = stripos($result, "duplicate");
							if(!is_bool($duplicate)) {
								//antes de la funcion $parseNumberToSave
								//$query = "update pedidos_detalle set cantidad_solicitada = cantidad_solicitada + ".$arrProductos['cantidad_solicitada'].", total_solicitado = total_solicitado + ".str_replace('Q', '', $arrProductos['total_solicitado'])." where id_pedido = ".$last_id." and codigo_producto = '".$arrProductos['codigo_producto']."'";
								$query = "update pedidos_detalle set cantidad_solicitada = cantidad_solicitada + ".parseNumberToSave($arrProductos['cantidad_solicitada']).", total_solicitado = total_solicitado + ".parseNumberToSave($arrProductos['total_solicitado'])." where id_pedido = ".$last_id." and codigo_producto = '".$arrProductos['codigo_producto']."'";
								$result = $db->gpSentence($query, $cnn);
								if (is_bool($result) and $result) { //no hay error
								} else {
									$last_id = $result;
									$hacerCommit = false;
									break;
								}
							} else {
								//return 'NO duplicate';
								$last_id = $result;
								$hacerCommit = false;
								break;							
							}
							
						}
						$total_solicitado = $total_solicitado + (parseNumberToSave($arrProductos['cantidad_solicitada']) * parseNumberToSave($arrProductos['precio_final']));
					}

					if($hacerCommit) {

						//actualiza totales
						$query = "update pedidos_encabezado set total_solicitado = ".$total_solicitado." where id_pedido = ".$last_id;
						$result = $db->gpSentence($query, $cnn);
						if (is_bool($result) and $result) { //no hay error

							$db->gpCommitTransaction($cnn);

							if ($arrEncabezado['id_campania']==C_ID_PEDIDOS_ESPECIALES) {
								$emailTo = C_EMAIL_PEDIDOS_ESPECIALES;
								$emailSubject = "Un pedido especial ha sido creado";
								$emailMessage = '
										<span style="color:#333333!important; font-weight:bold; font-family:arial,helvetica,sans-serif">Estimado usuario,</span><br>
										<p>Un nuevo Pedido Especial ha sido creado.</p>

										<table cellpadding="1">
											<tbody>
												<tr>
													<td valign="top">Pedido:</td>
													<td>'.$last_id.'</td>
												</tr>
												<tr>
													<td valign="top">Cliente:</td>
													<td>'.fixSQL($arrEncabezado["nombre_cliente"]).'</td>
												</tr>
												<tr>
													<td colspan="2"></td>
												</tr>
											</tbody>
										</table>

										<br>
										<a href="'.C_LINK_PEDIDOS_ESPECIALES.'"><button class="buttons">Revisar</button></a>
										';
								$emailTipoNotificacion = 'Ingreso de Pedido Especial';
								require_once C_ROOT_DIR.'classes/csEmail.php';
								$email = new csEmail();
								$email->sendEmail($emailTo, $emailSubject, $emailMessage, $emailTipoNotificacion);
							}

						} else {
							$last_id = $result;
							$db->gpRollbackTransaction($cnn);
						}  //actualiza totales
						
												
					} else {
						$db->gpRollbackTransaction($cnn);
						$last_id = "Error al grabar";
					}

					// if($hacerCommit){
					// 	$db->gpCommitTransaction($cnn);
					// } else {
					// 	$db->gpRollbackTransaction($cnn);
					// }

				} else {
					$db->gpRollbackTransaction($cnn);
					$last_id = $result;
					//$last_id = 0;
				}

			} catch(Exception $e) {
				$db->gpRollbackTransaction($cnn);
				$last_id = -1;
			}

		} catch(Exception $e) {
			$last_id = -2;
		}

		return $last_id;
	} //Grabar

	function Borrar($pNumeroPedido) {
	// $pNumeroPedido = si es 0 es un pedido nuevo
	// $pDatosPedido = array con los datos a grabar
	// RETORNA: true, false o el mensaje error

		$returnValue = false;

		try {

			$query = "Select estado_interfaz From pedidos_encabezado where id_pedido = $pNumeroPedido";			
			$db = new DbSentencia();
			$value = $db->gpScalarQuery($query);

			if ($value==0) {  //no lo ha tomado la interfaz de sincronizacion, se puede borrar

				$db = new DbSentencia();
				$cnn = $db->gpStartTransaction();

				try{

					$query = "delete from pedidos_detalle where id_pedido = $pNumeroPedido";
					$result = $db->gpSentence($query, $cnn);
					if (is_bool($result) and $result) {

						$query = "delete from pedidos_encabezado where id_pedido = $pNumeroPedido";
						$result = $db->gpSentence($query, $cnn);							
						if (is_bool($result) and $result) {  //no hay error
							$db->gpCommitTransaction($cnn);
							$returnValue = true;
						} else {
							$db->gpRollbackTransaction($cnn);
							$returnValue = $result;
						}

					} else {
						$db->gpRollbackTransaction($cnn);
						$returnValue = $result;
					}

				} catch(Exception $e) {
					$db->gpRollbackTransaction($cnn);
					$returnValue = $e->getMessage();
				}

			} else {  //ya lo tomo la interfaz para sincronizar, no se puede borrar
				$returnValue = 'No se puede borrar, el pedido ya ha sido descargado al sistema.';
			}  //lo tomo la interfaz si o no
	
		} catch(Exception $e) {
			$returnValue = $e->getMessage();
		}

		return $returnValue;
		
	} //Borrar

	function Aprobar($arrPedidos) {

		//$arrEncabezado = json_decode($pDatosPedido,true);
		//$arrDetalle = json_decode(json_encode($arrEncabezado['detalle']),true);
		$arrReturn = array();
		$arrAprobados = array();
		$arrNoAprobados = array();
		$todosAprobados = true;

		try{

			$db = new DbSentencia();

			//for($i = 0; $i < count($arrPedidos)); $i++) { //recorre pedidos para aprobarlos uno a uno
			foreach ($arrPedidos as $idPedido) {

				$query = "update pedidos_encabezado set status = 'A', bit_fecha_autoriza = sysdate(), bit_usuario_autoriza = ".$_SESSION['login']['id_usuario']." where id_pedido = ".$idPedido;
				$result = $db->gpSentence($query);
				if (is_bool($result) and $result) {

					array_push($arrAprobados, $idPedido);

					$query = "select email from usuarios where id_vendedor in (select id_vendedor from pedidos_encabezado where id_pedido = $idPedido)";			
					$db = new DbSentencia();
					$value = $db->gpScalarQuery($query);
					if ($value!='') {  
						$emailTo = $value;
						$emailSubject = "Pedido Aprobado";
						$emailMessage = '
								<span style="color:#333333!important; font-weight:bold; font-family:arial,helvetica,sans-serif">Estimado usuario,</span><br>
								<p>Se ha aprobado un pedido.</p>

								<table cellpadding="1">
									<tbody>
										<tr>
											<td valign="top">Pedido:</td>
											<td>'.$idPedido.'</td>
										</tr>
										<tr>
											<td colspan="2"></td>
										</tr>
									</tbody>
								</table>

								<br>
								<a href="'.C_LINK_CONSULTA_PEDIDOS.'"><button class="buttons">Revisar</button></a>
								';
						$emailTipoNotificacion = 'Aprobacion de Pedido';
						require_once C_ROOT_DIR.'classes/csEmail.php';
						$email = new csEmail();
						$email->sendEmail($emailTo, $emailSubject, $emailMessage, $emailTipoNotificacion);
					} // value!=''

				} else {
				 	$todosAprobados = false;
				 	array_push($arrNoAprobados, $idPedido);
				}

			}  //recorre pedidos para aprobarlos uno a uno

			//$arrReturn['Result'] = $todosAprobados ? 'TODOS_APROBADOS' : 'ALGUNOS_NO_APROBADOS';
			if($todosAprobados) {
				$arrReturn['Result'] = 'TODOS_APROBADOS';
				$arrReturn['idsAprobados'][] = $arrAprobados;
			} else {
				$arrReturn['Result'] = 'ALGUNOS_NO_APROBADOS';
				$arrReturn['idsAprobados'][] = $arrAprobados;
				$arrReturn['idsNoAprobados'][] = $arrNoAprobados;
			}
			//return json_encode($arrPedido);
			return $arrReturn;



		} catch (Exception $e) {		
			$arrReturn['Result'] = 'ERROR';
			$arrReturn['Message'] = $e->getMessage();
			return $arrReturn;
		}
	} //Aprobar

	function Rechazar($arrPedidos, $razon_rechazo) {

		$arrReturn = array();
		$arrRechazados = array();
		$arrNoRechazados = array();
		$todosRechazados = true;

		try{

			$db = new DbSentencia();

			foreach ($arrPedidos as $idPedido) {
				$query = "update pedidos_encabezado set status = 'R', razon_rechazo = '".$razon_rechazo."' where id_pedido = ".$idPedido;
				$result = $db->gpSentence($query);
				if (is_bool($result) and $result) {

					array_push($arrRechazados, $idPedido);

					$query = "select email from usuarios where id_vendedor in (select id_vendedor from pedidos_encabezado where id_pedido = $idPedido)";
					$db = new DbSentencia();
					$value = $db->gpScalarQuery($query);
					if ($value!='') {  
						$emailTo = $value;
						$emailSubject = "Pedido Rechazado";
						$emailMessage = '
								<span style="color:#333333!important; font-weight:bold; font-family:arial,helvetica,sans-serif">Estimado usuario,</span><br>
								<p>Se ha rechazado un pedido.</p>

								<table cellpadding="1">
									<tbody>
										<tr>
											<td valign="top">Pedido:</td>
											<td>'.$idPedido.'</td>
										</tr>
										<tr>
											<td valign="top">Razon de Rechazo:</td>
											<td>'.$razon_rechazo.'</td>
										</tr>
										<tr>
											<td colspan="2"></td>
										</tr>
									</tbody>
								</table>

								<br>
								<a href="'.C_LINK_CONSULTA_PEDIDOS.'"><button class="buttons">Revisar</button></a>
								';
						$emailTipoNotificacion = 'Rechazo de Pedido';
						require_once C_ROOT_DIR.'classes/csEmail.php';
						$email = new csEmail();
						$email->sendEmail($emailTo, $emailSubject, $emailMessage, $emailTipoNotificacion);
					} // value!=''

				} else {
				 	$todosRechazados = false;
				 	array_push($arrNoRechazados, $idPedido);
				}
			}  //recorre pedidos para rechazarlos uno a uno

			if($todosRechazados) {
				$arrReturn['Result'] = 'TODOS_RECHAZADOS';
				$arrReturn['idsRechazados'][] = $arrRechazados;
			} else {
				$arrReturn['Result'] = 'ALGUNOS_NO_RECHAZADOS';
				$arrReturn['idsRechazados'][] = $arrRechazados;
				$arrReturn['idsNoRechazados'][] = $arrNoRechazados;
			}
			return $arrReturn;

		} catch (Exception $e) {		
			$arrReturn['Result'] = 'ERROR';
			$arrReturn['Message'] = $e->getMessage();
			return $arrReturn;
		}

	} // Rechazar

	function getDatosPedido($pIdPedido,$pFiltrarVendedor=true,$pConfirmar=false){
	
		require_once C_ROOT_DIR.'classes/csUsuario.php';

		try {

			$hasAccess = true;
			if ($pConfirmar) {
				$user = new csUsuario();
				$confirmAccess = $user->hasRole('GESTOR');
			}

			// tiene acceso?
			if ($hasAccess) {

				$query = "Select a.*, 
							case 	when status = 'I' then 'Ingresado' 
									when status = 'A' then 'Autorizado' 
									when status = 'R' then 'Rechazado' 
									when status = 'F' then 'Facturado' 
									when status = 'D' then 'Despachado' 
									when status = 'P' then 'Despacho Parcial' 
							else '' end estatus_pedido,
							a.status,
							c.limite_credito,
							b.codigo_producto, b.upc, b.descripcion, b.unidades_empaque, b.precio, b.precio_base, b.cantidad_solicitada, b.cantidad_autorizada, b.cantidad_despachada, b.total_solicitado total_linea_solicitado,
							a.razon_rechazo
							From pedidos_encabezado a left outer join pedidos_detalle b on a.id_pedido = b.id_pedido
							     left outer join clientes c on a.id_cliente = c.id_cliente
							Where a.id_pedido = $pIdPedido";
				if ($pFiltrarVendedor) {
					//$query=$query." Where a.id_vendedor = '".$_SESSION['login']['id_vendedor']."' and a.id_pedido = $pIdPedido";
					$query=$query." and a.id_vendedor in (".$_SESSION['login']['id_vendedor'].")";
					//$query=$query." and a.id_vendedor = '0'";
				}
					
				$db = new DbSentencia();
				$result = $db->gpQuery($query);

				$arrPedido = array();
				if ($result) {
					$firstRow = true;
				  	while ($row = $result->fetch_assoc()) {
				  		if ($firstRow) {
				  			$firstRow=false;
							$arrPedido['id_pedido'] = $row['id_pedido'];
							$arrPedido['id_pedido_alterno'] = $row['id_pedido_alterno'];
							$arrPedido['id_cliente'] = $row['id_cliente'];
							$arrPedido['nombre_cliente'] = utf8_encode(fixSQL($row['nombre_cliente']));
							$arrPedido['tipo_pago'] = $row['tipo_pago'];
							$arrPedido['direccion_entrega'] = utf8_encode(fixSQL($row['direccion_entrega']));
							$arrPedido['fecha_entrega'] = $row['fecha_entrega'];
							$arrPedido['id_campania'] = $row['id_campania'];
							$arrPedido['observaciones'] = utf8_encode(fixSQL($row['observaciones']));
							$arrPedido['status'] = $row['status'];
							$arrPedido['estatus_pedido'] = $row['estatus_pedido'];
							$arrPedido['limite_credito'] = $row['limite_credito'];
							$arrPedido['razon_rechazo'] = $row['razon_rechazo'];
							$arrPedido['total_solicitado'] = $row['total_solicitado'];
							$arrPedido['total_autorizado'] = $row['total_autorizado'];
							$arrPedido['total_despachado'] = $row['total_despachado'];
							$arrPedido['bit_fecha_ingreso'] = $row['bit_fecha_ingreso'];
							$arrPedido['documentacion_soporte'] = $row['documentacion_soporte'];
				  		}
				  		$arrDetalle = array();
				  		$arrDetalle['codigo_producto'] = $row['codigo_producto'];
				  		$arrDetalle['upc'] = $row['upc'];
				  		$arrDetalle['descripcion'] = utf8_encode(fixSQL($row['descripcion']));
				  		$arrDetalle['unidades_empaque'] = $row['unidades_empaque'];
				  		$arrDetalle['precio'] = $row['precio'];
				  		$arrDetalle['precio_base'] = $row['precio_base'];
				  		$arrDetalle['cantidad_solicitada'] = $row['cantidad_solicitada'];
				  		$arrDetalle['cantidad_autorizada'] = $row['cantidad_autorizada'];
				  		$arrDetalle['cantidad_despachada'] = $row['cantidad_despachada'];
				  		$arrDetalle['total_linea_solicitado'] = $row['total_linea_solicitado'];
						$arrPedido['detalle'][] = $arrDetalle;
				  }    
				}

				return $arrPedido;

			} else {  // tiene acceso?
				return '';
			}  // tiene acceso?
						
		} catch (Exception $e) {
			return $e->getMessage();
		}

	} //getDatosPedido

	function getDatosDashboard() {
		try {

			$db = new DbSentencia();			
			$arrDashboard = array();

			$query = "Select count(1) From pedidos_encabezado Where id_vendedor in (".$_SESSION['login']['id_vendedor'].") and DATE_FORMAT(SYSDATE(),'%m%Y') = DATE_FORMAT(bit_fecha_ingreso,'%m%Y')";
			$value = $db->gpScalarQuery($query);
			$arrDashboard['PedidosHoy'] = $value;

			$query = "Select count(1) From pedidos_encabezado Where id_vendedor in (".$_SESSION['login']['id_vendedor'].") and status = 'I'";
			$value = $db->gpScalarQuery($query);
			$arrDashboard['PedidosPorConfirmar'] = $value;

			$query = "Select count(1) From pedidos_encabezado Where id_vendedor in (".$_SESSION['login']['id_vendedor'].") and status = 'I' and id_campania = 1";
			$value = $db->gpScalarQuery($query);
			$arrDashboard['PedidosEspecialesPorConfirmar'] = $value;

			$query = "Select id_pedido, DATE_FORMAT(bit_fecha_ingreso,'%d-%m') as bit_fecha_ingreso, nombre_cliente, total_solicitado From pedidos_encabezado Where id_vendedor in (".$_SESSION['login']['id_vendedor'].") and DATE_FORMAT(SYSDATE(),'%m%Y') = DATE_FORMAT(bit_fecha_ingreso,'%m%Y') order by id_pedido desc";
			$result = $db->gpQuery($query);
			if ($result) {
			  	while ($row = $result->fetch_assoc()) {
			  		$arrDetalle = array();
			  		$arrDetalle['id_pedido'] = $row['id_pedido'];
			  		$arrDetalle['bit_fecha_ingreso'] = $row['bit_fecha_ingreso'];
			  		$arrDetalle['nombre_cliente'] = utf8_encode(fixSQL($row['nombre_cliente']));
			  		$arrDetalle['total_solicitado'] = $row['total_solicitado'];
					$arrDashboard['UltimosPedidos'][] = $arrDetalle;
			  	}    
			}

			$query = "select * from (select b.nombre_corto campania, count(1) pedidos from pedidos_encabezado a LEFT OUTER JOIN cam_encabezado b on a.id_campania = b.id_campania";
			$query = $query."		  WHERE a.id_vendedor in (".$_SESSION['login']['id_vendedor'].") and DATE_FORMAT(SYSDATE(),'%m%Y') = DATE_FORMAT(a.bit_fecha_ingreso,'%m%Y')";
			$query = $query."		  GROUP BY b.nombre_corto) as tabla";
			$result = $db->gpQuery($query);
			if ($result) {
			  	while ($row = $result->fetch_assoc()) {
			  		$arrDetalle = array();
			  		$arrDetalle['campania'] = utf8_encode(fixSQL($row['campania']));
			  		$arrDetalle['pedidos'] = $row['pedidos'];
					$arrDashboard['Ultimos'][] = $arrDetalle;
			  	}    
			}

			return $arrDashboard;
			
		} catch (Exception $e) {
			return '';	
		}
	} // getDatosDashboard

	function setDocumentacionSoporte($pIdPedido, $pFileName) {
		try {

			$db = new DbSentencia();
			$query = "update pedidos_encabezado set documentacion_soporte = '".$pFileName."' where id_pedido = ".$pIdPedido;
			$result = $db->gpSentence($query);
			if (is_bool($result) and $result) 
				return true;
			else
				return false;

		} catch(Exception $e) {
			return "";
		}
	}  //function setDocumentacionSoporte


}

?>