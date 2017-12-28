<?php  
$page = 'confirmar'; 
require_once("header.php"); 
if (!userHasRoles('GESTOR, ADMINISTRADOR')) { echo noAccessPage(); die(); } 
?>

  <link rel="stylesheet" type="text/css" href="css/bootstrap-table.css">
  <script type="text/javascript" src="js/bootstrap-table.js"></script>

  <script>
    doWait();
  </script>

  <script type="text/javascript">
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
        <div id="toolbar" class="">
          <button id="btnAprobar" class="btn btn-primary" disabled>
            <i class="glyphicon glyphicon-ok"></i> Confirmar
          </button>
        </div>        
        <table 
          id="tablaDatos"
          data-toggle="table" 
          class="table table-hover" 
          data-show-columns="true"
          data-sort-name="id_pedido"
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
              <th></th>
            </tr>
          </thead>
          <tbody>
              <?php 
                require_once (C_ROOT_DIR.'classes/csPedidos.php');
                $csPedidos = new csPedidos();
                $rsDatos = $csPedidos->getPedidosParaConfirmar();
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
                          <td class='text-center'>
                              <a href=pedido.php?p=".$row["id_pedido"]."&t=c><button title='' type='button' class='btn btn-primary btn-xs remove show_tip' data-original-title='Abrir'> <span class='glyphicon glyphicon-folder-open'></span></button></a>
                          </td>
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
        selections = [];

    $table.on('check.bs.table uncheck.bs.table check-all.bs.table uncheck-all.bs.table', function () {
        $aprobar.prop('disabled', !$table.bootstrapTable('getSelections').length);
        selections = getIdSelections();
    });

    $aprobar.click(function () {
        doWait();
        var ids = getIdSelections();
        swal({
          title: "¿Seguro de CONFIRMAR ["+ids.length+"] pedidos?",
          type: "info", 
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
                          text: ids.length+" pedidos fueron CONFIRMADOS con exito !!!",
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
                          text: data.idsNoAprobados[0].length+" Pedidos NO pudieron ser Confirmados",
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

            html.push('<p><b>Observaciones:</b> ' + data["observaciones"] + '</p>');

            //VERSION 1
            //*******************************************
            html.push('<table id="tablaDatosProductos" class="table ">');
            html.push(' <thead>');
            html.push('   <th>UPC</th>');
            html.push('   <th>CODIGO</th>');
            html.push('   <th>DESCRIPCION</th>');
            html.push('   <th class="text-center">U/E</th>');
            html.push('   <th class="text-right">CANTIDAD</th>');
            html.push('   <th class="text-right">PRECIO</th>');
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
