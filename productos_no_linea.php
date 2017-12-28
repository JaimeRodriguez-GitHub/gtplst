<?php

session_start();

//para sacar al usuario si expiro la sesion por timeout
if (!(isset($_SESSION['login']) && $_SESSION['login'] != '')) {
    header ("Location: index.php");
}

//error_reporting( E_ALL );
error_reporting( 0 );
//	session_start();
//echo "h2"; 
require_once 'lib/constants.php';
require_once 'lib/functions.php';

?>

<link rel="stylesheet" type="text/css" href="css/bootstrap-table.css">
<script type="text/javascript" src="js/bootstrap-table.js"></script>

<?php
    
//echo "h3"; 

echo ('                          <div class="bootstrap-table">');
echo ('                            <div class="fixed-table-toolbar">');
echo ('                            </div>');
echo ('                            <div class="table-container" style="padding-bottom: 0px;">');
echo ('                              <div class="fixed-table-header" style="display: none;">');
echo ('                                <table></table>');
echo ('                              </div>');
echo ('                              <div class="fixed-table-body">');
echo ('                                <div class="fixed-table-loading" style="top: 41px;">');
echo ('                                  Cargando, por favor espere...');
echo ('                                </div>');
echo ('                                <table ');
echo ('                                  id="tablaProductosNoLinea"');
echo ('                                  data-toggle="table" ');
echo ('                                  class="table table-hover" ');
echo ('                                  data-sort-name="descripcion"');
echo ('                                  data-sort-order="asc"');
echo ('                                  data-search="true"');
echo ('                                  data-pagination="true"');
echo ('                                  data-page-size="20"');
echo ('                                  data-page-list="[20, 50, 100]"');
echo ('                                  data-height="400"');
echo ('                                  > ');
echo ('                                  <thead>');
echo ('                                    <tr>');
echo ('                                      <th data-align="center"></th>');
echo ('                                      <th data-field="codigo_producto"   data-sortable="true" data-align="left">Codigo</th>');
echo ('                                      <th data-field="descripcion"       data-sortable="true" data-align="left">Descripcion</th>');
echo ('                                    </tr>');
echo ('                                  </thead>');
echo ('                                  <tbody>');
                                      
                                        require_once (C_ROOT_DIR.'classes/csProductos.php');
                                        $csProductos = new csProductos();
                                        $rsDatos = $csProductos->getProductosGeneral();        
                                        if ($rsDatos) {
                                            while ($row = $rsDatos->fetch_assoc()) {
                                                $botonSeleccionar = "<button  onclick='AgregarProductoGeneral(\"".$row["upc"]."\",\"".$row["codigo_producto"]."\",\"".$row["unidades_empaque"]."\",0,\"".fixSQL2($row["descripcion"])."\",0);' title='' type='button' class='btn btn-warning btn-xs remove show_tip' data-original-title='Seleccionar'><span class='glyphicon glyphicon-shopping-cart'></span></button>";
                                                //$botonSeleccionar = "<button  onclick='AgregarProductoGeneral(\"".$row["upc"]."\",\"".$row["codigo_producto"]."\",\"".$row["unidades_empaque"]."\",0,\"".fixSQL2($row["descripcion"])."\",0);' title='' type='button' class='btn btn-warning btn-xs remove show_tip' data-original-title='Seleccionar'><img src='images/add2cart1.png'></button>";
                                                echo "<tr class=''>
                                                      <td class='text-center'>".$botonSeleccionar."</td>
                                                      <td>".$row["codigo_producto"]."</td>
                                                      <td>".$row["descripcion"]."</td>                   
                                                      </td>
                                                     </tr>";
                                            } // while
                                        } // if rsDatos
                                                                                                                  
echo ('                                  </tbody>');
echo ('                                </table>');
echo ('                              </div>');
echo ('                              <div class="fixed-table-footer" style="display: none;">');
echo ('                                <table>');
echo ('                                  <tbody>');
echo ('                                    <tr></tr>');
echo ('                                  </tbody>');
echo ('                                </table>');
echo ('                              </div>');
echo ('                              <div class="fixed-table-pagination" style="display: none;"></div>');
echo ('                            </div>');
echo ('                          </div>');
echo ('                          <div class="clearfix"></div>  ');
      
?>
      