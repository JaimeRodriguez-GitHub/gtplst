<?php  
$page = 'aprobar-especiales'; 
require_once("header.php"); 
if (!userHasRoles('ADMINISTRADOR, AUTORIZADOR')) { echo noAccessPage(); die(); } 
?>

  <!--<script type="text/javascript" src="//code.jquery.com/jquery-2.1.0.js"></script>
  <script type="text/javascript" src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
  <link rel="stylesheet" type="text/css" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">-->
  

  
  <!--<link rel="stylesheet" type="text/css" href="/css/result-light.css"> -->
  


  <!--<link rel="stylesheet" type="text/css" href="https://rawgit.com/wenzhixin/bootstrap-table/master/src/bootstrap-table.css">-->
  <link rel="stylesheet" type="text/css" href="css/bootstrap-table.css">
  <!--<script type="text/javascript" src="https://rawgit.com/wenzhixin/bootstrap-table/master/src/bootstrap-table.js"></script>-->
  <script type="text/javascript" src="js/bootstrap-table.js"></script>
  <style type="text/css">  
  </style>
<!-- 
  <script type="text/javascript">//<![CDATA[
    $(window).load(function(){
    });//]]> 
  </script>
-->

  <script>
    doWait();
  </script>

  <script type="text/javascript">
    function monthSorter(a, b) {
      if (a.month < b.month) return -1;
      if (a.month > b.month) return 1;
      return 0;
    }
    function ingresoSorter(a, b) {
      if (a.ingreso < b.ingreso) return -1;
      if (a.ingreso > b.ingreso) return 1;
      return 0;
    }
    function entregaSorter(a, b) {
      if (a.entrega < b.entrega) return -1;
      if (a.entrega > b.entrega) return 1;
      return 0;
    }
    function amountSorter(a, b) {
      if (a.amount < b.amount) return -1;
      if (a.amount > b.amount) return 1;
      return 0;
    }
  </script>

  <div class="bootstrap-table">
    <div class="fixed-table-toolbar">
    </div>
    <div class="table-container" style="padding-bottom: 0px;">
      <div class="fixed-table-header" style="display: none;">
        <table></table>
      </div>
      <div class="fixed-table-body">
        <div class="fixed-table-loading" style="top: 41px;">
          Cargando, por favor espere...
        </div>
        <div id="toolbar" class=""><!-- btn-group -->
<!--          
          <button type="button" class="btn btn-default">
            <i class="glyphicon glyphicon-plus"></i>
          </button>
          <button type="button" class="btn btn-default">
            <i class="glyphicon glyphicon-heart"></i>
          </button>
          <button type="button" class="btn btn-default">
            <i class="glyphicon glyphicon-trash"></i>
          </button>
-->
          <button id="btnAprobar" class="btn btn-primary" disabled>
            <i class="glyphicon glyphicon-ok"></i> Autorizar
          </button>
          <button id="btnRechazar" class="btn btn-danger" disabled>
            <i class="glyphicon glyphicon-remove"></i> Rechazar
          </button>
        </div>        
        <table 
          id="tablaDatos"
          data-toggle="table" 
          class="table table-hover" 
          data-show-columns="true"
          data-sort-name="stargazers_count"
          data-sort-order="desc"
          data-search="true"
          data-toolbar="#toolbar"
          data-pagination="true"
          data-detail-view="true"
          data-detail-formatter="detailFormatter"
          > 
          <!-- 
          data-click-to-select="true" 
          -->
          <thead>
            <tr>
              <th data-field="state"              data-checkbox="true"></th>
              <th data-field="order_id"          data-sortable="true" data-align="center"># PEDIDO</th>
              <th data-field="nombre_vendedor"    data-sortable="true">VENDEDOR</th>
              <th data-field="id_cliente"         data-sortable="true">ID CLIENTE</th>
              <th data-field="nombre_cliente"     data-sortable="true">NOMBRE CLIENTE</th>
              <th data-field="campania"           data-sortable="true">CAMPAÑA</th>
              <th data-field="bit_fecha_ingreso"  data-sortable="true" data-sort-name="_bit_fecha_ingreso_data" data-sorter="ingresoSorter" data-align="center">INGRESO</th>
              <th data-field="fecha_entrega"      data-sortable="true" data-sort-name="_fecha_entrega_data" data-sorter="entregaSorter" data-align="center">ENTREGA</th>
              <th data-field="total_solicitado"   data-sortable="true" data-sort-name="_total_solicitado_data" data-sorter="amountSorter" data-align="right">TOTAL PEDIDO</th>
            </tr>
          </thead>
          <tbody>           
              <?php 
                require_once (C_ROOT_DIR.'classes/csPedidos.php');
                $csPedidos = new csPedidos();
                $rsDatos = $csPedidos->getPedidosEspeciales('','');
                if ($rsDatos) {
                  $index = 0;
                  while ($row = $rsDatos->fetch_assoc()) {
                      echo "<tr class=''>
                              <td></td>
                              <td>".$row["order_id"]."</td>
                              <td>".utf8_encode($row["nombre_vendedor"])."</td>
                              <td>".$row["id_cliente"]."</td>
                              <td>".utf8_encode($row["nombre_cliente"])."</td>
                              <td>".utf8_encode($row["campania"])."</td>
                              <td data-ingreso='".$row["bit_fecha_ingreso_sort"]."'>".$row["bit_fecha_ingreso"]."</td>
                              <td data-entrega='".$row["fecha_entrega_sort"]."'>".$row["fecha_entrega"]."</td>
                              <td data-amount=".$row["total_solicitado"].">".toCurrency($row["total_solicitado"],2)."</td>
                             </tr>"; 
                  }
                }
              ?>
          </tbody>
        </table>
      </div>
      <div class="fixed-table-footer" style="display: none;">
        <table>
          <tbody>
            <tr></tr>
          </tbody>
        </table>
      </div>
      <div class="fixed-table-pagination" style="display: none;"></div>
    </div>
  </div>
  <div class="clearfix"></div>

  <script>


    var $table = $('#tablaDatos'),
        $aprobar = $('#btnAprobar'),
        $rechazar = $('#btnRechazar'),
        selections = [];

    $table.on('check.bs.table uncheck.bs.table ' +
            'check-all.bs.table uncheck-all.bs.table', function () {
        $aprobar.prop('disabled', !$table.bootstrapTable('getSelections').length);
        $rechazar.prop('disabled', !$table.bootstrapTable('getSelections').length);
        // save your data, here just save the current page
        selections = getIdSelections();
        // push or splice the selections if you want to save all data selections
        //console.log(selections);
    });

    $table.on('expand-row.bs.table', function (e, index, row, $detail) {
        //console.log(e);
        //console.log(index);
        //console.log(row.order_id);  // ************* aqui va el idPedido
        //console.log($detail);
        //var html = [];
        //html.push('<p><b>Observaciones:</b> ' + 'sdfsdfsdf sdlsdf sdkjsdf; ' + '</p>');
        //return html.join('');
    });    

    


















































    $approve.click(function () {
        doWait();
        var ids = getIdSelections();
        swal({
          title: "Are you sure to approve ["+ids.length+"] orders?",
          type: "warning",
          showCancelButton: true,
          confirmButtonText: "Yes",
          cancelButtonText: "No",
          confirmButtonColor: "#DD6B55",                                                                            
          showLoaderOnConfirm: true,
          closeOnConfirm: false
        },
        function(isConfirm) {
          setTimeout(function() {
            if(isConfirm){

              $.ajax({
                dataType: "json",
                method: "POST",
                url: 'approve_orders.php',
                async: false,
                data: {ids: ids} //send the selected IDs to be approved
              })  //ajax

              .done(function(data){
                switch (data.Result) {
                  
                  //ALL were approved
                  case 'ALL_APPROVED':
                    $table.bootstrapTable('remove', {
                        field: 'orderId',
                        values: ids
                    });
                    $table.bootstrapTable('uncheckAll');
                    $aprobar.prop('disabled', true);
                    swal({  
                          title: "Process Complete!", 
                          text: ids.length+" orders where APPROVED successfully!",
                          type: "success",
                          closeOnConfirm: true
                        });
                    break;

                  //SOME were not approved
                  case 'SOME_NOT_APPROVED':
                    if (data.approvedIds[0].length > 0) {
                      $table.bootstrapTable('remove', {
                          field: 'orderId',
                          values: data.approvedIds[0]
                      });
                    }
                    swal({  
                          title: "Warning", 
                          text: data.notApprovedIds[0].length+" orders WERE NOT approved",
                          type: "warning",
                          closeOnConfirm: true
                        });
                    break;

                  //ERROR raised
                  case 'ERROR':
                    swal({  
                          title: "Error", 
                          text: data.Message,
                          type: "error",
                          closeOnConfirm: true
                        });                  
                    break;
                  default:

                } //switch
              })  //ajax done

              .fail(function(jqXHR) {
                console.log(jqXHR);
                swal({  
                      title: "An error occurred", 
                      text: 'Please call the Administrator',
                      type: "error",
                      closeOnConfirm: true
                    });                  
              }); //ajax fail

            }  //if isConfirm
          },2000);  //timeout
        }); //swal
        doUnWait(1000);
    }); // approve.click

































    $rechazar.click(function () {
        doWait();
        var ids = getIdSelections();
        swal({   
          title: "Rechazando ["+ids.length+"] pedidos...",
          //text: "Escriba la razon de rechazo:",   
          type: "input",   
          showCancelButton: true,   
          showLoaderOnConfirm: true,
          closeOnConfirm: false,   
          animation: "slide-from-top",   
          inputPlaceholder: "Escriba la razon de rechazo" 
        }, 
        function(inputValue){   
          if (inputValue === false) 
            return false;      
          if (inputValue === "") {     
            swal.showInputError("Debe escribir la razon de rechazo!");     
            return false   
          }
          setTimeout(function() {
          $.ajax({
            dataType: "json",
            method: "POST",
            url: 'rechazar_pedido.php',
            async: false,
            data: {ids: ids, razon: inputValue}
          })  //ajax
          .done(function(data){
            
            switch (data.Result) {
              
              case 'TODOS_RECHAZADOS':
                $table.bootstrapTable('remove', {
                    field: 'order_id',
                    values: ids
                });
                $table.bootstrapTable('uncheckAll');
                $aprobar.prop('disabled', true);
                swal({  
                      title: "Proceso completado", 
                      text: ids.length+" pedidos fueron RECHAZADOS con exito !!!",
                      type: "success",
                      closeOnConfirm: true
                    });
                break;

              case 'ALGUNOS_NO_RECHAZADOS':
                if (data.idsRechazados[0].length > 0) {
                  $table.bootstrapTable('remove', {
                      field: 'order_id',
                      values: data.idsRechazados[0]
                  });
                }
                swal({  
                      title: "Advertencia", 
                      text: data.idsNoRechazados[0].length+" Pedidos NO pudieron ser Rechazados",
                      type: "warning",
                      closeOnConfirm: true
                    });
                break;
              case 'ERROR':
                swal({  
                      title: "Error", 
                      text: data.Message,
                      type: "error",
                      closeOnConfirm: true
                    });                  
                break;
              default:
            } //switch
            
          })  //ajax done
          .fail(function(jqXHR) {
            console.log(jqXHR);
            swal({  
                  title: "Ocurrio un error", 
                  text: 'Comuniquese con el administrador',
                  type: "error",
                  closeOnConfirm: true
                });                  
          }); //ajax fail
          },2000);

        }); // swal rechaza si o no
        doUnWait(1000);
    }); // rechazar.click

    function getIdSelections() {
        return $.map($table.bootstrapTable('getSelections'), function (row) {
          return row.order_id;
        });
    }


    function detailFormatter(index, row) {

        var html = [];

        $.ajax({
          dataType: "json",
          url: 'lib/getajaxvalues.php',
          async: false,
          data: {action: 'getDatosPedido', p: row.order_id}
          }).done(function(data){
            if(data["documentacion_soporte"]=='' || data["documentacion_soporte"]==null)
              html.push('<p>No tiene documentacion de soporte de la negociacion</p>');
            else
              html.push('<a href="carga/archivos/' + data["documentacion_soporte"] + '" target="_blank" class="btn btn-info" role="button">Ver documentacion de soporte</a>');
            html.push('<p><b>Observaciones:</b> ' + data["observaciones"] + '</p>');
            if (data["razon_rechazo"] != null && data["razon_rechazo"] != '') {
                html.push('<p><b>Raz&oacute;n de Rechazo Previa:</b> ' + data["razon_rechazo"] + '</p>');  
            }
            


            //VERSION 2
            //*******************************************
            // html.push('<div class="bootstrap-table">');
            // html.push('<div class="fixed-table-toolbar">');
            // html.push('</div>');
            // html.push('<div class="table-container" style="padding-bottom: 20px;">');
            // html.push('<div class="fixed-table-header" style="display: none;">');
            // html.push('  <table></table>');
            // html.push('</div>');
            // html.push('<div class="fixed-table-body">');

                      
            // html.push('<table id="tablaDatos2" data-toggle="table" class="table table-hover" data-show-columns="true" data-sort-name="stargazers_count" data-sort-order="desc" data-search="true" data-toolbar="#toolbar" data-pagination="true" data-detail-view="true" data-detail-formatter="detailFormatter">');
            // //html.push('<table id="tablaDatos2" data-toggle="table" class="table table-hover"  data-show-columns="true" data-search="true">');

            // html.push('  <thead>');
            // html.push('    <tr>');
            // html.push('      <th data-field="upc"                      data-sortable="true">UPC</th>');
            // html.push('      <th data-field="codigo_producto"          data-sortable="true">CODIGO ARTICULO</th>');
            // html.push('      <th data-field="descripcion"              data-sortable="true">DESCRIPCION</th>');
            // html.push('      <th data-field="unidades_empaque"         data-sortable="true" data-align="center">U/E</th>');
            // html.push('      <th data-field="cantidad_solicitada"      data-sortable="true" data-align="right">CANTIDAD</th>');
            // html.push('      <th data-field="precio"                   data-sortable="true" data-align="right">PRECIO</th>');
            // html.push('      <th data-field="total_linea_solicitado"   data-sortable="true" data-align="right" data-sort-name="_total_soxlicitado_data" data-sorter="amounstSorter">TOTAL</th>');
            // html.push('    </tr>');
            // html.push('  </thead>');
            // html.push('  <tbody>');
            // for (i = 0; i < data['detalle'].length; i++) {          
            //   html.push('<tr class="">');
            //   html.push('<td>'+data['detalle'][i]['upc']+'</td>');
            //   html.push('<td>'+data['detalle'][i]['codigo_producto']+'</td>');
            //   html.push('<td>'+data['detalle'][i]['descripcion']+'</td>');
            //   html.push('<td>'+data['detalle'][i]['unidades_empaque']+'</td>');
            //   html.push('<td>'+data['detalle'][i]['cantidad_solicitada']+'</td>');
            //   html.push('<td>'+parseInt(data['detalle'][i]['precio']).toCurrency()+'</td>');
            //   html.push('<td data-amounmt="'+data['detalle'][i]['total_linea_solicitado']+'">'+parseFloat(data['detalle'][i]['total_linea_solicitado']).toCurrency()+'</td>');
            //   html.push('</tr>');
            // }
            // html.push('  </tbody>');
            // html.push('</table>'); 
            // html.push('</div>');
            // html.push('<div class="fixed-table-footer" style="display: none;">');
            // html.push('  <table>');
            // html.push('    <tbody>');
            // html.push('      <tr></tr>');
            // html.push('    </tbody>');
            // html.push('  </table>');
            // html.push('</div>');
            // html.push('<div class="fixed-table-pagination" style="display: none;"></div>');
            // html.push('</div>');
            // html.push('</div>');
            //*******************************************

            //VERSION 1
            //*******************************************
            html.push('<table id="tablaDatosProductos" class="table ">');
            html.push(' <thead>');
            html.push('   <th>UPC</th>');
            html.push('   <th>CODIGO</th>');
            html.push('   <th>DESCRIPCION</th>');
            html.push('   <th class="text-center">U/E</th>');
            html.push('   <th class="text-right">CANTIDAD</th>');
            html.push('   <th class="text-right">PRECIO BASE</th>');
            html.push('   <th class="text-right">PRECIO FINAL</th>');
            html.push('   <th class="text-right">TOTAL</th>');
            html.push(' </thead>');
            html.push(' <tbody>');
            for (i = 0; i < data['detalle'].length; i++) {          
              html.push('<tr>');
              html.push('<td>'+data['detalle'][i]['upc']+'</td>');
              html.push('<td>'+data['detalle'][i]['codigo_producto']+'</td>');
              html.push('<td>'+data['detalle'][i]['descripcion']+'</td>');
              html.push('<td class="text-center">'+data['detalle'][i]['unidades_empaque']+'</td>');
              html.push('<td class="text-right">'+data['detalle'][i]['cantidad_solicitada']+'</td>');
              html.push('<td class="text-right">'+parseFloat(data['detalle'][i]['precio_base']).toCurrency(4)+'</td>');
              html.push('<td class="text-right">'+parseFloat(data['detalle'][i]['precio']).toCurrency(4)+'</td>');
              html.push('<td class="text-right">'+parseFloat(data['detalle'][i]['total_linea_solicitado']).toCurrency(2)+'</td>');
              html.push('</tr>');
            }
            html.push(' </tbody>');
            html.push('</table><br>');       
            //*******************************************

        }); //ajax done

        return html.join('');

    }

  </script>

  <script>
    doUnWait(1000);
  </script>

<?php require_once("footer.php"); ?>







<?php 
function SaveOrder($pOrderId, $pOrderDetail) {

    $arrHeader = json_decode($pOrderDetail,true);
    $arrDetail = json_decode(json_encode($arrHeader['detail']),true);

    try {

      $db = new DbSentence();
      $cnn = $db->gpStartTransaction();

      try{

        $tDay = substr($arrHeader['delivery_date'],0,2);
        $tMonth = substr($arrHeader['delivery_date'],3,2);
        $tYear = substr($arrHeader['delivery_date'],6,4);
        $tDeliveryDate = $tYear."-".$tMonth."-".$tDay;

        $query = "select ifnull(salesman_id,'') from clients where client_id = '".$arrHeader['clientId']."'";
        $salesmanId = $db->gpScalarQuery($query);

        // if order_id is empty is a new order
        if (empty($arrHeader['order_id'])) { 
          $query = "insert into orders_header (client_id, client_name, payment_type, delivery_address, salesman_id, delivery_date, campaign_id, comments, order_status, total_amount, bit_user)";
          $query = $query . " values ('".$arrHeader['client_id']."', '".UnparseSQLInjection(fixSQL($arrHeader['client_name']))."', '".$arrHeader['payment_type']."', '".UnparseSQLInjection(fixSQL($arrHeader['delivery_address']))."', ".$salesmanId.", '".$tDeliveryDate."', ".$arrHeader['campaign_id'].", '".UnparseSQLInjection(fixSQL($arrHeader['comments']))."', 'I', ".parseNumberToSave($arrHeader['total_amount']).", '".$_SESSION['login']['userId']."')"; 
        } else {  // update an existing order
          $update_status = $arrHeader['is_approval'] ? ", order_status = 'A' " : ", order_status = 'I' ";  //set the correct status 
          $query = "update orders_header set payment_type = '".$arrHeader['payment_type']."', delivery_address = '".UnparseSQLInjection(fixSQL($arrHeader['delivery_address']))."', delivery_date = '".$tDeliveryDate."', comments = '".UnparseSQLInjection(fixSQL($arrHeader['comments']))."', total_amount = ".parseNumberToSave($arrHeader['total_amount']).$update_status;
          $query = $query . " where order_id = ".$arrHeader['order_id'];
        }

        $result = $db->gpSentence($query, $cnn);  //save header
        if (is_bool($result) and $result) {

          $doCommit = true;
          $last_id = empty($arrHeader['order_id']) ? $cnn->insert_id : $arrHeader['order_id'];

          $query = "delete from orders_detail where order_id = ".$arrHeader['order_id'];
          $result = $db->gpSentence($query, $cnn); 
          $totalAmount = 0;

          foreach ($arrDetail as $key => $arrProducts) {

            $query = "insert into orders_detail (order_id, product_id, upc, description,  packing_units, final_price, base_price, orderded_qty, total_amount)";
            $query = $query . " values (".$last_id.", '".$arrProducts['product_id']."', '".$arrProducts['upc']."', '".fixSQL($arrProducts['description'])."', '".$arrProducts['packing_units']."', ".parseNumberToSave($arrProducts['final_price']).", ".parseNumberToSave($arrProducts['base_price']).", '".parseNumberToSave($arrProducts['orderded_qty'])."', ".parseNumberToSave($arrProducts['total_amount']).")";
            $result = $db->gpSentence($query, $cnn);              

            if !(is_bool($result) and $result) {  // if error

              $duplicate = stripos($result, "duplicate");
              if(!is_bool($duplicate)) {
                $query = "update orders_detail set orderded_qty = orderded_qty + ".parseNumberToSave($arrProducts['orderded_qty']).", total_amount = total_amount + ".parseNumberToSave($arrProducts['total_amount'])." where order_id = ".$last_id." and product_id = '".$arrProducts['product_id']."'";
                $result = $db->gpSentence($query, $cnn);
                if @(is_bool($result) and $result) { // if error
                  $last_id = $result;
                  $doCommit = false;
                  break;
                }
              } else {
                $last_id = $result;
                $doCommit = false;
                break;              
              }
              
            }
            $totalAmount = $totalAmount + (parseNumberToSave($arrProducts['total_amount']) * parseNumberToSave($arrProducts['final_price']));
          } //loop in products detail saving to DB

          if($hacerCommit) {

            //update totals
            $query = "update orders_header set total_amount = ".$totalAmount." where order_id = ".$last_id;
            $result = $db->gpSentence($query, $cnn);
            if (is_bool($result) and $result) { //no error

              $db->gpCommitTransaction($cnn);

              // alerts internal users
              if ($arrHeader['campaign_id']==C_CAMPAIGN) {
                $emailTo = C_EMAIL_CAMPAIGN;
                $emailSubject = "An order has been placed";
                $emailMessage = '
                    <span style="color:#333333!important; font-weight:bold; font-family:arial,helvetica,sans-serif">Dear User,</span><br>
                    <p>A new order has been placed.</p>

                    <table cellpadding="1">
                      <tbody>
                        <tr>
                          <td valign="top">Order Id:</td>
                          <td>'.$last_id.'</td>
                        </tr>
                        <tr>
                          <td valign="top">Client:</td>
                          <td>'.fixSQL($arrHeader["client_name"]).'</td>
                        </tr>
                        <tr>
                          <td colspan="2"></td>
                        </tr>
                      </tbody>
                    </table>

                    <br>
                    <a href="'.C_LINK_ORDERS.'"><button class="buttons">Click to review</button></a>
                    ';
                $emailType = 'New Orders';
                require_once C_ROOT_DIR.'classes/csEmail.php';
                $email = new csEmail();
                $email->sendEmail($emailTo, $emailSubject, $emailMessage, $emailType);
              }

            } else {
              $last_id = $result;
              $db->gpRollbackTransaction($cnn);
            }  //update totals
            
                        
          } else {
            $db->gpRollbackTransaction($cnn);
            $last_id = "Error saving order";
          } //doCommit y/n

        } else {
          $db->gpRollbackTransaction($cnn);
          $last_id = $result;
        } //save header

      } catch(Exception $e) {
        $db->gpRollbackTransaction($cnn);
        $last_id = -1;
      }

    } catch(Exception $e) {
      $last_id = -2;
    }

    return $last_id;
  } //function SaveOrder

?>









<?php 

Class DB{

  private $db_host='XXXXXXXXXX';
  private $db_username='XXXXXXXXXX';
  private $db_password='XXXXXXXXXX';
  private $db_name='XXXXXXXXXX';

  private $_connection;
  private static $_instance;

  public static function getInstance(){
      if (!(self::$_instance instanceof self)){
          self::$_instance = new self();
      }
      return self::$_instance;
  } //function getInstance

  private function __construct() {
    $this->_connection = new mysqli($this->db_host, $this->db_username, $this->db_password, $this->db_name);
    if(mysqli_connect_error()) {
      trigger_error("Failed to conencto to MySQL: " . mysql_connect_error(), E_USER_ERROR);
    }
  }

  private function __clone() { }

  protected function getConnection() {
    return $this->_connection;
  }

  public function __destruct() {
  }

} //Class Db

?>



