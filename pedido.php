<?php 
  $page = 'pedido'; require_once("header.php");
  error_reporting(E_ALL & ~E_NOTICE);

  $IdPedidoURL = isset($_GET['p']) ? $_GET['p'] : '';
  $esConfirmacion = (isset($_GET['t']) ? $_GET['t'] : '') == 'c';

  //obtener JSON del pedido
  require_once (C_ROOT_DIR.'classes/csPedidos.php');
  $csPedidos = new csPedidos();
  if ($esConfirmacion) {  // si es confirmacion de pedido envia parametro
      $rsPedido = $csPedidos->getDatosPedido($IdPedidoURL, false, true);
  } else{
      $rsPedido = $csPedidos->getDatosPedido($IdPedidoURL);
  }
  $hayPedido = !empty($rsPedido);

  //var_dump(is_array($rsDatos));
  //var_dump($rsPedido);
  //die();
  //  var_dump($rsPedido);

  if (!$hayPedido){ //no hay pedido
    $esEditable = true;
  } else {  //si hay pedido
    //$esEditable = $rsPedido['status'] == C_ESTATUS_PEDIDOS_INGRESADOS ? true : false;
    if ($rsPedido['status'] == C_ESTATUS_PEDIDOS_INGRESADOS) {
      if ($rsPedido['id_campania'] == C_ID_PEDIDOS_ESPECIALES) {
        $esEditable = false;
      } else {
        $esEditable = true;
      }
    } else if ($rsPedido['status'] == C_ESTATUS_PEDIDOS_RECHAZADOS) {
      $esEditable = true;
    } else {
      $esEditable = false;
    }
  } //editable?



  
?>

<!-- se agrego para soportar el listado de pedidos NO de linea -->
<link rel="stylesheet" type="text/css" href="css/bootstrap-table.css">
<script type="text/javascript" src="js/bootstrap-table.js"></script>

<script>
  var IdPedidoURL = "<?php echo $IdPedidoURL; ?>";
  var hayPedido = "<?php echo $hayPedido; ?>";
  var esConfirmacion = "<?php echo $esConfirmacion; ?>";
  var esEditable = "<?php echo $esEditable; ?>"
  //console.log(IdPedidoURL);
  //console.log(hayPedido);
  //console.log(esConfirmacion);
  //console.log(esEditable);
  doWait();
</script>

<!--  TESTING  HABILITAR DESPUES    creo que ya no
<script src="js/find5.js"></script>
-->

<!--<form id="myForm" data-toggle="validator">-->
<form id="myForm" onsubmit="return false;">

    <section class="row">
    
      <article class="col-xs-8 col-sm-8 col-md-9">

        <!-- Seleccion autocomplete Cliente --> 
        <div class="form-group row">
            <label for="comboboxClientes" class="col-xs-3 col-sm-3 form-control-label">Cliente</label>
            <div class="col-xs-9 col-sm-9">
              <?php if($hayPedido) { ?>
                <span id="view_nombre_cliente"></span>
              <?php } else { ?>
                <select class="form-control" id="comboboxClientes">
                  <option value=""></option>
                </select>      
              <?php } ?>
            </div>
        </div>
        <span class="hide" id="sp_codigo_cliente"></span>
        <span class="hide" id="sp_nombre_cliente"></span>
        <?php if($hayPedido) { ?>
        <script>
            $('#view_nombre_cliente').text('<?php echo $rsPedido["nombre_cliente"]; ?>');
            $('#sp_codigo_cliente').text('<?php echo $rsPedido["id_cliente"]; ?>');
            $('#sp_nombre_cliente').text('<?php echo $rsPedido["nombre_cliente"]; ?>');
        </script>
        <?php } ?>          
        <!-- Seleccion autocomplete Cliente --> 

        <!-- Tipo de Pago --> 
        <div class="form-group row">
            <label for="tipo_pago" class="col-xs-3 col-sm-3 form-control-label">Condicion de Entrega</label>
            <div class="col-xs-9 col-sm-9">
                <select class="form-control" id="tipo_pago" name="tipo_pago" >
                  <option value="2">Credito</option>
                  <option value="3">Deposito</option>
                  <option value="4">Pago Anticipado</option>
                  <option value="1">Pago Contra Entrega</option>
                </select>                
            </div>
        </div>
        <?php if($hayPedido) { ?>
          <script>
              $('#tipo_pago').val("<?php echo $rsPedido['tipo_pago']; ?>");
          </script>
          <?php if(!$esEditable) { ?>
            <script>
                $('#tipo_pago').attr("disabled", true); 
            </script>
          <?php } ?>
        <?php } ?>
        <!-- Tipo de Pago --> 


        <!-- Tipo de Pedido --> 
        <div class="form-group row">
            <label for="tipo_pedido" class="col-xs-3 col-sm-3 form-control-label">Lista de Precios</label>
            <div class="col-xs-9 col-sm-9">
                <select class="form-control" id="tipo_pedido" name="tipo_pedido">
                    <option value="0">Seleccionar...</option>
                    <?php 
                        require_once (C_ROOT_DIR.'classes/csCampanias.php');
                        $csCampanias = new csCampanias();
                        $rsCampanias = $csCampanias->getCampanias();
                        if ($rsCampanias) {
                            while ($row = $rsCampanias->fetch_assoc()) {
                                 // creo que esta por error, no deja cuando ponto el REQUIRE de cspedidos al inicio:         echo "<div data-value='".$row["id_cliente"]."~".$row["limite_credito"]."~".utf8_encode($row["direccion_entrega"])."~".$row["dias_entrega"]."~".utf8_encode($row["nombre"])."'>".$row["id_cliente"].' - '.utf8_encode($row["nombre"])."</div>";
                                 echo "<option value='".$row["id_campania"]."'>".utf8_encode($row["nombre_corto"])."</option>";
                            }
                        }
                    ?>
                </select>
              </div>
        </div>
        <?php if($hayPedido) { ?>
        <script>
            $('#tipo_pedido').val("<?php echo $rsPedido['id_campania']; ?>");
            $('#tipo_pedido').attr("disabled", true); 
        </script>
        <?php } ?>        
        <!-- Tipo de Pedido -->         

      </article>

      <!-- Pedido # --> 
      <aside class="col-xs-4 col-sm-4 col-md-3 pedido-num">

                    <div class="panel panel-red">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-comments fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div id="numero_pedido" class="huge"><?php echo $hayPedido ? $rsPedido['id_pedido'] : ''; ?></div>
                                    <div id="estatus_pedido"><?php echo $hayPedido ? $rsPedido['estatus_pedido'] : ''; ?></div>
                                </div>
                            </div>
                        </div>
                        <div class="panel-footer">
                            <div><small>SAP #</small><span class="pull-right" id="pedido_alterno"><small><?php echo $hayPedido ? $rsPedido['id_pedido_alterno'] : ''; ?></small></span></div>
                            <div><small>L.Credito</small><span class="pull-right" id="limite_credito"><small><?php echo $hayPedido ? toCurrency($rsPedido['limite_credito']) : ''; ?></small></span></div>
                            <div id="div_estatus_cliente"></div>
                            <div class="clearfix"></div>
                        </div>
                    </div>
<!-- VERSION ORIGINAL       
<div class="col-lg-3 col-md-6">
</div>


        <p>
          Pedido #<br>
          <span id="numero_pedido">?</span><br>
          Limite de Credito<br>
          <b><span id="limite_credito"></span><b>
        </p>
-->        

      </aside>
      <!-- Pedido # --> 

  </section>    

  <!-- Direccion Entrega --> 
  <div class="form-group row">
    <label for="direccion_entrega" class="col-xs-2 col-sm-2 form-control-label">Direccion Entrega</label>
      <div class="col-xs-10 col-sm-10">
      <textarea class="form-control" id="direccion_entrega" name="direccion_entrega" readonly></textarea>
      <div class="help-block with-errors"></div>
      </div>
  </div>
  <?php if($hayPedido) { ?>
      <script>
          $('#direccion_entrega').val('<?php echo $rsPedido["direccion_entrega"]; ?>');
      </script>
      <?php if(!$esEditable) { ?>
        <script>
          $('#direccion_entrega').attr("disabled", true); 
        </script>
      <?php } ?>
  <?php } ?>          
  <!-- Direccion Entrega --> 

  <!-- Fecha Entrega --> 
  <div class="form-group row">
    <label for="fecha_entrega" class="col-xs-2 col-sm-2 form-control-label">Fecha Entrega</label>
    <div class="span5 col-xs-10 col-sm-10" id="fecha_entrega_container">
      <div class="input-group date" data-provide="datepicker">
          <input type="text" class="form-control" id="fecha_entrega" name="fecha_entrega" required> <!-- value="12-02-2012" -->
          <span class="input-group-addon"><i class="glyphicon glyphicon-th"></i></span>
      </div>
      <div class="help-block with-errors"></div>
    </div>
  </div>
  <?php if($hayPedido) { ?>
      <script>
          $('#fecha_entrega').val('<?php echo toLocalDate($rsPedido['fecha_entrega']); ?>');
      </script>
      <?php if(!$esEditable) { ?>
        <script>
          $('#fecha_entrega').attr("disabled", true);
          $(document).off('.datepicker.data-api');
        </script>
      <?php } ?>
  <?php } ?>        
  <!-- Fecha Entrega --> 

  <!-- Observaciones --> 
  <div class="form-group row">
    <label for="observaciones" class="col-xs-2 col-sm-2 form-control-label">Observaciones</label>
      <div class="col-xs-10 col-sm-10">
      <textarea class="form-control" id="observaciones" name="observaciones"></textarea>
      </div>
  </div>
  <?php if($hayPedido) { ?>
      <script>
          $('#observaciones').val("<?php echo $rsPedido['observaciones']; ?>");
      </script>
      <?php if(!$esEditable) { ?>
        <script>
          $('#observaciones').attr("disabled", true); 
        </script>
      <?php } ?>
  <?php } ?>          
  <!-- Observaciones --> 

  <!-- Agregar Documentacion Soporte --> 
  <?php if($hayPedido and $rsPedido['status']=='I' and $rsPedido['id_campania'] == C_ID_PEDIDOS_ESPECIALES) { ?>
  <div id="div_agregar_documentacion" class="input-group" style="margin-bottom: 5px !important;">
    <button class="btn btn-warning" id="btnAgregarDocumentacionSoporte" type="button">Agregar Documentacion de Soporte</button>
  </div>   
  <?php } ?>          
  <!-- Agregar Documentacion Soporte --> 
 
  <!-- Razon de Rechazo --> 
  <?php if($hayPedido and $rsPedido['status']=='R') { ?>
  <div class="form-group row">
    <label for="razon_rechazo" class="col-xs-2 col-sm-2 form-control-label">Razon de Rechazo</label>
      <div class="col-xs-10 col-sm-10">
            <div class="alert alert-danger">
      <span id="razon_rechazo" name="razon_rechazo"></span>
              <!-- <div id="error_agregar"></div> -->
            </div>
      </div>
  </div>
  <script>
      $('#razon_rechazo').text("<?php echo $rsPedido['razon_rechazo']; ?>");
  </script>
  <?php } ?>          
  <!-- Razon de Rechazo --> 



  <?php 
    //if(!$hayPedido) //ya no es por ese campo ahora es por EsEditable
    if($esEditable) 
    { 
  ?>  <!-- consulta o ingreso -->
  <!-- Busqueda de productos -->
  <section>

    <!-- Agregar Producto -->
    <div class="panel panel-default">
      <div class="panel-body ">

        <!-- Seleccion autocomplete producto -->
        <div class="row form-group col-xs-12">
          <label for="combobox" class="form-control-label">Elegir Articulo</label>
          <select class="form-control" id="combobox">
            <option value=""></option>
          </select>
        </div>  
        <!-- Seleccion autocomplete producto -->
          
        <!-- Seleccionar producto No de Linea para pedidos especiales -->    
        <div class="row form-group col-xs-12" id="div_producto_general" style="display:none;">
<!--            <a href="#modalTable" id="productosNoLinea" data-toggle="modal" class="default">O seleccionar un producto "NO de Linea"</a>          -->
            <a href="#" id="productosNoLinea" data-toggle="modal" class="default">Seleccionar un producto que no esta en el listado de Mayorista</a>       
            
            
            
            <div class="modal fade" id="modalTable" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">Listado de Productos General</h4>
                        </div>
                        <div class="modal-body">
                            <div id=divProductosNoLinea></div>
                      
                        </div>                    
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                        </div>
                        <script>
                                function AgregarProductoGeneral(pUPC, pCodigo, pUE, pPrecio, pDescripcion, pPrecioFinal) {
                                    $('#sp_producto_upc').text(pUPC);
                                    $('#sp_producto_codigo').text(pCodigo);
                                    $('#sp_producto_ue').text(pUE);
                                    $('#sp_producto_precio').text(pPrecio);
                                    $('#sp_descripcion').text(pDescripcion);
                                    $('#precio_final').val(pPrecioFinal);            
                                    $('#cantidad_solicitar').val('');
                                    $('.custom-combobox-input').val(pDescripcion);
                                    $('#modalTable').modal('hide');
                                }
                        </script>

                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->
            
            <script>            
                $(function(){
                  $("#productosNoLinea").click(function(event) {
                      
                      doWait();

                      $.ajax({
                             type: "POST",
                             //url: $(this).attr('action'),
                             url: 'productos_no_linea.php',
                             //async: false,
                             //dataType: 'json',
                             success: function(data)
                             {                 

                                //display data...
                                //$('#myModal').find('#modalAlert').addClass('alert-success');
                                //$('#modalTable').find('#tablaProductosNoLinea').html(data.message).show; 
                                $('#modalTable').find('#divProductosNoLinea').html(data).show; 
                                //$('#myModal').find('#modalAlert').removeClass('hidden');

                                $('#modalTable').modal('show');
                             }
                           });
                      
                      doUnWait(5000);

                      return false;  
                  });
                });
            </script>
        </div>          
        <!-- Seleccionar producto especial para pedidos especiales -->

        <!-- Datos producto y boton agregar -->
        <div class="form-group row">
          <!-- Datos producto -->
          <div class="col-xs-6 col-sm-4">
            <ul class="list-group clear-list m-t">
                <li class="list-group-item fist-item">
                    <small><span id="sp_producto_upc" class="pull-right"></span></small>
                    <span class="label label-success"><span class="glyphicon glyphicon-hand-right" aria-hidden="true"></span></span>
                    <small>Stock:</small>
                </li>
                <li class="list-group-item">
                    <small><span id="sp_producto_codigo" class="pull-right"></span></small>
                    <span class="label label-success"><span class="glyphicon glyphicon-hand-right" aria-hidden="true"></span></span>
                    <small>Codigo:</small>
                </li>
            </ul>
          </div>
          <div class="col-xs-6 col-sm-4">
            <ul class="list-group clear-list m-t">
                <li class="list-group-item fist-item">
                    <small><span id="sp_producto_ue" class="pull-right"></span></small>
                    <span class="label label-success"><span class="glyphicon glyphicon-hand-right" aria-hidden="true"></span></span>
                    <small>U.Empaque:</small>
                </li>
                <li class="list-group-item">
                    <small><span id="sp_producto_precio" class="pull-right"></span></small>
                    <span class="label label-success"><span class="glyphicon glyphicon-hand-right" aria-hidden="true"></span></span>
                    <small>Precio:</small>
                </li>
                <li style="display:none;">
                    <small><span id="sp_descripcion"></span></small>
                </li>
            </ul>
          </div>
          <!-- Datos producto -->

          <!-- Ingreso de cantidad, precio y boton agregar-->
          <div class="col-xs-12 col-sm-4">   
            <div class="alert alert-success form-group" role="alert">
              <div id="div_precio_final" class="input-group" style="margin-bottom: 5px !important; display:none;">
                <span class="input-group-addon input-primary" style="background-color: transparent !important; color: #3c763d !important;">Precio Final:      Q</span>
                <input type="number" pattern="[0-9]+([\,|\.][0-9]+)?" class="form-control" id="precio_final" placeholder="Ingrese precio..." min="0" step="0.01">
              </div>
                <div id="div_agregar_producto" class="input-group">
                    <input type="number" class="form-control" id="cantidad_solicitar_empaque" placeholder="Empaques" min="0" step="1">
                    <input type="number" class="form-control" id="cantidad_solicitar" placeholder="Unidades" min="0" step="1">
                    <span class="input-group-btn">
                        <button class="btn btn-primary" id="btnAgregarProducto" type="button" style="height:68px">Agregar</button>
                    </span>
              </div>
              <div id="error_agregar"></div>
            </div>
          </div>
          <!-- Ingreso de cantidad y boton agregar -->
        </div>  
        <!-- Datos producto y boton agregar -->

      </div> <!--panel body -->
    </div> <!--panel -->
    <!-- Agregar Producto -->

  </section>
  <!-- Busqueda de productos -->
  <?php } ?> <!-- consulta o ingreso -->

  <!-- Listado de productos -->
  <section>
    <div class="row">
      <div class="col-md-12">
        <div class="table-responsive">
          <table id="myTable" class="table table-bordred table-striped">
            <thead>
              <th></th>
              <th>UPC</th>
              <th>CODIGO</th>
              <th class="text-right">CANT</th>
              <th class="text-center">U/E</th>
              <th>DESCRIPCION</th>
              <?php if($hayPedido and $rsPedido['id_campania']==C_ID_PEDIDOS_ESPECIALES)  { ?> 
                <th class="text-right">PRECIO BASE</th>
                <th class="text-right">PRECIO FINAL</th>
              <?php } else { ?>
                <th class="text-right">PRECIO</th>
              <?php } ?>
              <th class="text-right">TOTAL</th>
              <th></th>
            </thead>
            <tbody>
            </tbody>
            <tfoot>
              <tr>
                <td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                <?php if($hayPedido and $rsPedido['id_campania']==1)  { ?> 
                  <td></td>
                <?php } ?>

              </tr>
            </tfoot>
          </table>
        </div>  <!-- table-responsive -->
<div style="width: 150px; float: right;" class="text-right alert alert-success" id="div_total_pedido"></div>
<span class="hide" id="sp_totalpedido">0</span>
      </div>
    </div> 
  </section>
  <!-- Listado de productos -->

  
  <!-- Upload archivo -->
  <section>
    <div>
    </div>
  </section>
  <!-- Upload archivo -->
  

  <?php if($hayPedido) { ?>  <!-- consulta o ingreso -->
  <script>

    if (esEditable) {
      colAccion =  '<td class="text-center"><button title="" type="button" class="btn btn-danger btn-xs remove show_tip" data-original-title="Borrar de la lista"><span class="glyphicon glyphicon-trash"></span></button></td>';
    } else {
      colAccion =  '<td></td>';
    }
    
    <?php 
    foreach ($rsPedido['detalle'] as $key => $arrDetallePedido) {
    ?>
        var rowProducto = '<tr>';
        //rowProducto = rowProducto+'<td></td>';
        //rowProducto = rowProducto+'<td class="text-center"><button title="" type="button" class="btn btn-danger btn-xs remove show_tip" data-original-title="Borrar de la lista"><span class="glyphicon glyphicon-trash"></span></button></td>';
        rowProducto = rowProducto+colAccion;
        rowProducto = rowProducto+'<td>'+'<?php echo $arrDetallePedido["upc"]; ?>'+'</td>';
        rowProducto = rowProducto+'<td>'+'<?php echo $arrDetallePedido["codigo_producto"]; ?>'+'</td>';
        rowProducto = rowProducto+'<td class="text-right">'+'<?php echo $arrDetallePedido["cantidad_solicitada"]; ?>'+'</td>';
        rowProducto = rowProducto+'<td class="text-center">'+'<?php echo $arrDetallePedido["unidades_empaque"]; ?>'+'</td>';
        rowProducto = rowProducto+'<td>'+'<?php echo $arrDetallePedido["descripcion"]; ?>'+'</td>';

        <?php if($hayPedido and $rsPedido['id_campania']==1)  { ?> 
          rowProducto = rowProducto+'<td class="text-right">'+'<?php echo toCurrency($arrDetallePedido["precio_base"],4); ?>'+'</td>';
          rowProducto = rowProducto+'<td class="text-right">'+'<?php echo toCurrency($arrDetallePedido["precio"],4); ?>'+'</td>';
        <?php } else { ?> 
          rowProducto = rowProducto+'<td class="text-right">'+'<?php echo toCurrency($arrDetallePedido["precio"],4); ?>'+'</td>';
        <?php } ?>

        rowProducto = rowProducto+'<td class="text-right">'+'<?php echo toCurrency($arrDetallePedido["total_linea_solicitado"],2); ?>'+'</td>';
        rowProducto = rowProducto+colAccion;
        //rowProducto = rowProducto+'<td class="text-center"><button title="" type="button" class="btn btn-danger btn-xs remove show_tip" data-original-title="Borrar de la lista"><span class="glyphicon glyphicon-trash"></span></button></td>';
        //rowProducto = rowProducto+'<td></td>';
        rowProducto = rowProducto+'</tr>';
        $("#myTable tbody").append(rowProducto);
    <?php }  ?> //foreach
    $('#sp_totalpedido').text('<?php echo $rsPedido["total_solicitado"]; ?>');
    $('#div_total_pedido').text('<?php echo toCurrency($rsPedido["total_solicitado"],2); ?>');
  </script>
  <?php }  ?>  <!-- consulta o ingreso -->


  <!-- Botones de Accion -->
  <div class="form-group row">

    <?php if(!$hayPedido) { ?>  <!-- consulta o ingreso -->
        <button class="btn btn-primary !important btn-lg" id='btnGuardar'>Guardar</button> <!-- btn-block -->
    <?php } else if ($esEditable) { ?>  <!-- consulta o ingreso -->
        <button class="btn btn-primary !important btn-lg" id='btnGuardar'><?php echo $esConfirmacion ? 'Confirmar' : 'Guardar';?></button> <!-- btn-block -->
        <?php if(!$esConfirmacion) { ?>  <!-- consulta o ingreso -->
            <button class="btn btn-warning !important btn-lg" id='btnBorrar'>Borrar</button> <!-- btn-block -->
        <?php }  ?>  <!-- consulta o ingreso -->
    <?php }  ?>  <!-- consulta o ingreso -->
    <!--<button type="submit" class="btn btn-primary">Submit</button>-->
    <!--<button type="submit" class="btn btn-primary btn-lg " >Guardar</button>-->
  </div>
  <!-- Botones de Accion -->

</form>

<?php if($hayPedido and $esEditable) { ?>
<script>
    $('#sp_producto_upc').text('');
    $('#sp_producto_codigo').text('');
    $('#sp_producto_ue').text('');
    $('#sp_producto_precio').text('');
    $('#sp_descripcion').text('');
    $('#cantidad_solicitar').val('');
    $.getJSON('lib/getajaxvalues.php', {action: 'getProductosCampania', idCampania: $("#tipo_pedido").val()}, function(data){
      var options = '';
      for (var x = 0; x < data.length; x++) {
          options += '<option value="' + data[x]['id'] + '" mytag_upc="' + data[x]['mytag_upc'] + '" mytag_codigo="' + data[x]['mytag_codigo'] + '">' + data[x]['reg'] + '</option>';
      }
      $('#combobox').html(options);
      $('.custom-combobox-input').val('');
    });
</script>
<?php } ?>


<script>
  doUnWait(1000);
  if(IdPedidoURL!='' && hayPedido!=true) {
    swal({  title: "Pedido No Existe",   
            text: "Este pedido no existe o usted no tiene acceso.",   
            timer: 3000,   
            showConfirmButton: false,
            type: "warning"
        });  
  }  
</script>

<?php $page = 'pedido'; require_once("footer.php"); ?>

<?php 
// $emailTo = "jaimerodriguezvillalta@hotmail.com";
// $emailSubject = "Ingreso al sistema";
// $emailMessage = "El usuario acaba de ingresar al sistema";






// $emailHeader  = 'MIME-Version: 1.0' . "\r\n";
// $emailHeader .= 'Content-type: text/html; charset=utf-8' . "\r\n";
// $emailHeader .= 'From: jaimerodriguezvillalta@gmail.com' . "\r\n";
// //$emailHeader .= 'Bcc: jaimerodriguezvillalta@gmail.com' . "\r\n";

// // ini_set( 'sendmail_from', "jaimerodriguezvillalta@gmail.com" ); 
// // //ini_set( 'SMTP', "mail.bigpond.com" );  
// // //ini_set( 'SMTP', "smtp.nyu.edu" );  
// // ini_set( 'SMTP', "ssl://smtp.gmail.com " );  
// // ini_set( 'smtp_port', 465 ); //25
// // ini_set( 'auth_username', 'jaimerodriguezvillalta@gmail.com' ); //25
// // ini_set( 'auth_password', 'Elmanager2015' ); //25

// //smtp_server=
// var_dump(ini_get("SMTP")); 
// mail($emailTo, $emailSubject, $emailMessage, $emailHeader);
?>

<?php //var_dump($_SESSION['login']); ?>
