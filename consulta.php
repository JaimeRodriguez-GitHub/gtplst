<?php  $page = 'consulta'; require_once("header.php"); ?>

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
    function pedidoSorter(a, b) {
      if (a.pedido < b.pedido) return -1;
      if (a.pedido > b.pedido) return 1;
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
        <table 
          id="tablaDatos"
          data-toggle="table" 
          class="table table-hover" 
          data-show-columns="true"
          data-sort-name="id_pedido"
          data-sort-order="desc"
          data-search="true"
          data-pagination="true"
          data-detail-view="true"
          data-detail-formatter="detailFormatter"
          data-search-text="<?php echo isset($_REQUEST['t']) ? 'Ingresado' : ''; ?>"
          > 
          <!-- 
          data-click-to-select="true" 

                        <th data-field="id_pedido"         data-sortable="true" data-align="center" data-sort-name="_pedido" data-sorter="pedidoSorter"># WEB</th>

          -->
          <thead>
            <tr>
              <th data-align="center"></th>
              <th data-field="id_pedido"         data-sortable="true" data-align="center"># WEB</th>
              <th data-field="id_pedido_alterno" data-sortable="true" data-align="center"># SAP</th>
              <th data-field="bit_fecha_ingreso" data-sortable="true" data-align="center" data-sort-name="_bit_fecha_ingreso_data" data-sorter="ingresoSorter" >INGRESADO</th>
              <th data-field="id_cliente"        data-sortable="true" data-align="left">CLIENTE</th>
              <th data-field="nombre_cliente"    data-sortable="true" data-align="left">NOMBRE</th>
              <th data-field="campania"          data-sortable="true" data-align="left">CAMPA&Ntilde;A</th>
              <th data-field="estatus_pedido"    data-sortable="true" data-align="left">ESTADO</th>
              <th data-field="total_solicitado"  data-sortable="true" data-align="right" data-sort-name="_total_solicitado_data" data-sorter="amountSorter">TOTAL PEDIDO</th>
              <th data-align="center">Abrir/Borrar</th>
<!--
              <th data-field="bit_fecha_ingreso"  data-sortable="true" data-sort-name="_bit_fecha_ingreso_data" data-sorter="ingresoSorter" data-align="center">INGRESO</th>
              <th data-field="fecha_entrega"      data-sortable="true" data-sort-name="_fecha_entrega_data" data-sorter="entregaSorter" data-align="center">ENTREGA</th>
              <th data-field="total_solicitado"   data-sortable="true" data-sort-name="_total_solicitado_data" data-sorter="amountSorter" data-align="right">TOTAL PEDIDO</th>
-->
            </tr>
          </thead>
          <tbody>
              <?php 
                // <td><a href=pedido.php?p=".$row["id_pedido"].">".$row["id_pedido"]."</a></td>
                // <td>".$row["id_pedido"]."</td>
                // <td data-pedido=".$row["id_pedido"]."><a href=pedido.php?p=".$row["id_pedido"].">".$row["id_pedido"]."</a></td>
                require_once (C_ROOT_DIR.'classes/csPedidos.php');
                $csPedidos = new csPedidos();
                $rsDatos = $csPedidos->getListadoPedidos();
                if ($rsDatos) {
                  while ($row = $rsDatos->fetch_assoc()) {
                      $botonBorrar = $row["status"]=='I' ? "<button onclick='BorrarPedido(".$row["id_pedido"].");' title='' type='button' class='btn btn-danger  btn-xs remove show_tip' data-original-title='Borrar'><span class='glyphicon glyphicon-trash'></span></button>" : "";
                      echo "<tr class=''>
                              <td class='text-center'>
                                <a href=pedido.php?p=".$row["id_pedido"]."><button title='' type='button' class='btn btn-primary btn-xs remove show_tip' data-original-title='Abrir'> <span class='glyphicon glyphicon-folder-open'></span></button></a>
                              </td>
                              <td>".$row["id_pedido"]."</td>
                              <td>".$row["id_pedido_alterno"]."</td>
                              <td data-ingreso='".$row["bit_fecha_ingreso_sort"]."'>".$row["bit_fecha_ingreso"]."</td>
                              <td>".$row["id_cliente"]."</td>
                              <td>".utf8_encode($row["nombre_cliente"])."</td>
                              <td>".utf8_encode($row["campania"])."</td>
                              <td>".utf8_encode($row["estatus_pedido"])."</td>
                              <td data-amount=".$row["total_solicitado"].">".toCurrency($row["total_solicitado"],2)."</td>                              
                              <td class='text-center'>
                                <a href=pedido.php?p=".$row["id_pedido"]."><button title='' type='button' class='btn btn-primary btn-xs remove show_tip' data-original-title='Abrir'> <span class='glyphicon glyphicon-folder-open'></span></button></a>
                                ".$botonBorrar."
                              </td>
                             </tr>"; 
                  } // while
                } // if rsDatos
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

            if(data["documentacion_soporte"]!='' && data["documentacion_soporte"]!=null)
              html.push('<a href="carga/archivos/' + data["documentacion_soporte"] + '" target="_blank" class="btn btn-info" role="button">Ver documentacion de soporte</a>');
//            else
//              html.push('<p>No tiene documentacion de soporte de la negociacion</p>');
            
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
