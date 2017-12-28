<?php $page = 'dashboard'; require_once("header.php"); ?>

<?php
require_once (C_ROOT_DIR.'classes/csPedidos.php');
$csDatos = new csPedidos();
$rsDatos = $csDatos->getDatosDashboard();

//var_dump($rsDatos);
//die();

//echo count($rsDatos['PedidosMesPorCampania']);
//var_dump($rsDatos['PedidosMesPorCampania']);
//echo json_encode($rsDatos);    
//var_dump($rsDatos);

?>
<script>
  //var xdata = "<?php echo json_encode($rsDatos['Ultimos']); ?>";
  //console.log(xdata);
</script>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<div class="row">
                <div class="col-lg-4 col-md-6">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <span style="font-size:4.5em;"  class="glyphicon glyphicon-shopping-cart"></span>
                                    <i class="fa fa-comments fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="xhuge"><?php echo $rsDatos['PedidosHoy']; ?></div>
                                    <div>Pedidos ingresados en el mes!</div>
                                </div>
                            </div>
                        </div>
                        <a href="consulta.php">
                            <div class="panel-footer">
                                <span class="pull-right">Ver Detalles</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="panel panel-green">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <span style="font-size:4.5em;color: #fff;"  class="glyphicon glyphicon-question-sign"></span>
                                    <i class="fa fa-comments fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="xhuge"><?php echo $rsDatos['PedidosPorConfirmar']; ?></div>
                                    <div>Pedidos por confirmar!</div>
                                </div>
                            </div>
                        </div>
                        <a href="consulta.php?t=c">
                            <div class="panel-footer">
                                <span class="pull-right">Ver Detalles</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="panel panel-orange">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <span style="font-size:4.5em;color: #fff;"  class="glyphicon glyphicon-question-sign"></span>
                                    <i class="fa fa-comments fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="xhuge"><?php echo $rsDatos['PedidosEspecialesPorConfirmar']; ?></div>
                                    <div>Pedidos especiales por confirmar!</div>
                                </div>
                            </div>
                        </div>
                        <a href="consulta.php?t=a">
                            <div class="panel-footer">
                                <span class="pull-right">Ver Detalles</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
</div>


<div class="panel panel-success">
                        <div class="panel-heading">
                            Pedidos Recientes
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-5">
                                <div class="panel panel-primary">                                    
                                <div class="panel-heading">Pedidos de este mes</div>    
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover table-striped">
                                            <thead>
                                                <tr class="danger">
                                                    <th>#</th>
                                                    <th>Ingreso</th>
                                                    <th>Cliente</th>
                                                    <th class="text-right">Monto</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            <?php 
                                            if (isset($rsDatos['UltimosPedidos'])){
                                                foreach ($rsDatos['UltimosPedidos'] as $key => $arrUltimosPedidos) {
                                                    echo '  <tr>
                                                                <td>'.$arrUltimosPedidos["id_pedido"].'</td>
                                                                <td>'.$arrUltimosPedidos["bit_fecha_ingreso"].'</td>
                                                                <td>'.$arrUltimosPedidos["nombre_cliente"].'</td>
                                                                <td class="text-right">'.toCurrency($arrUltimosPedidos["total_solicitado"]).'</td>
                                                            </tr>';
                                                }
                                            }
                                            ?>                                                
                                            </tbody>
                                        </table>
                                    </div>
                                    <!-- /.table-responsive -->
                                </div>                                    
                                </div>

                                <!-- /.col-lg-4 (nested) -->                               
                                <div class="col-lg-7">

                                    <div id="divPedidosPorCampania" style="width: 100%; height: 500px;"></div>

                                </div>                                
                                <!-- /.col-lg-8 (nested) -->
                            </div>
                            <!-- /.row -->
                        </div>
                        <!-- /.panel-body -->
                    </div>

<script type="text/javascript">
      google.charts.load('current', {'packages':['bar']});
      google.charts.setOnLoadCallback(drawStuff);

      function drawStuff() {

        var data = new google.visualization.arrayToDataTable([
          ['Campanias', 'Pedidos'],

          <?php foreach ($rsDatos['Ultimos'] as $key => $arrDatos) {
                echo '["'.$arrDatos["campania"].'",'.$arrDatos["pedidos"].'],';
          }?>
        ]);

        var options = {
          title: 'Pedidos ingresados por campaña este mes',
          legend: { position: 'none' },        
          hAxis: { slantedText: true, slantedTextAngle: 45}
        };

        var chart = new google.charts.Bar(document.getElementById('divPedidosPorCampania'));
        // Convert the Classic options to Material options.    
        chart.draw(data, google.charts.Bar.convertOptions(options));

      };
</script>