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
              <th data-field="id_pedido"          data-sortable="true" data-align="center"># PEDIDO</th>
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
                              <td>".$row["id_pedido"]."</td>
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
        //console.log(row.id_pedido);  // ************* aqui va el idPedido
        //console.log($detail);
        //var html = [];
        //html.push('<p><b>Observaciones:</b> ' + 'sdfsdfsdf sdlsdf sdkjsdf; ' + '</p>');
        //return html.join('');
    });    

    $aprobar.click(function () {
        doWait();
        var ids = getIdSelections();
        swal({
          title: "¿Seguro de APROBAR ["+ids.length+"] pedidos?",
          type: "warning", 
          showCancelButton: true, 
          confirmButtonText: "Si",
          cancelButtonText: "No",
          confirmButtonColor: "#DD6B55",
          showLoaderOnConfirm: true,
          closeOnConfirm: false
          //,
          //closeOnConfirm: false,
          //closeOnCancel: false 
        },
          function(isConfirm) {
          setTimeout(function() {
            if(isConfirm){

              $.ajax({
                dataType: "json",
                method: "POST",
                url: 'aprobar_pedido.php',
                async: false,
                data: {ids: ids}
              })  //ajax
              .done(function(data){
                switch (data.Result) {
                  
                  case 'TODOS_APROBADOS':
                    $table.bootstrapTable('remove', {
                        field: 'id_pedido',
                        values: ids
                    });
                    $table.bootstrapTable('uncheckAll');
                    $aprobar.prop('disabled', true);
                    swal({  
                          title: "Proceso completado", 
                          text: ids.length+" pedidos fueron AUTORIZADOS con exito !!!",
                          type: "success",
                          closeOnConfirm: true
                        });
                    break;

                  case 'ALGUNOS_NO_APROBADOS':
                    if (data.idsAprobados[0].length > 0) {
                      $table.bootstrapTable('remove', {
                          field: 'id_pedido',
                          values: data.idsAprobados[0]
                      });
                    }
                    swal({  
                          title: "Advertencia", 
                          text: data.idsNoAprobados[0].length+" Pedidos NO pudieron ser Autorizados",
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

            } else {
              // //swal.close();
              // return false;
            }  //if isConfirm
            },2000);
          }  //function (swal)
        ); //swal
        doUnWait(1000);
    }); // aprobar.click

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
                    field: 'id_pedido',
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
                      field: 'id_pedido',
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
          return row.id_pedido;
        });
    }


    function detailFormatter(index, row) {

        var html = [];

        $.ajax({
          dataType: "json",
          url: 'lib/getajaxvalues.php',
          async: false,
          data: {action: 'getDatosPedido', p: row.id_pedido}
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
