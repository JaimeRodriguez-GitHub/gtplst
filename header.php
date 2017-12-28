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

//echo "h3"; 
//  require_once("phpGrid_Lite/conf.php");  
///echo "h4:".C_ROOT_DIR; 
//die();

?>
<!DOCTYPE html>
<html>
<head>
    <meta name="keywords" content="" />
    <meta name="description" content="" />
    <meta charset="UTF-8">
    <title>Pedidos Guateplast</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="images/favicon.ico">
    

    <!-- CSS -->
    <!--
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/jquery-ui.css">
    -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">    
    <link href="css/bootstrap-formhelpers.min.css" rel="stylesheet">
<!--    <link href="css/bootstrap-dialog.min.css" rel="stylesheet">  -->
    <link href="css/datepicker.css" rel="stylesheet">
    <link href="css/templatemo_style.css" rel="stylesheet">    
    <link href="css/menu-style.css" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Raleway:400,100,600' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="css/sweetalert.css">
<!-- UPLOAD FILE opcion 1 -->
    <link rel="stylesheet" href="css/jquery.fileupload.css">
<!-- UPLOAD FILE opcion 1 -->


    <?php if (isset($page) and $page==='login') { ?>
        <link href="css/login-styles.css" rel="stylesheet" />
    <?php } else { ?>
        <link href="css/main-style.css" rel="stylesheet" />
    <?php } ?>

    <!-- JS -->
    <!--
    <script src="//code.jquery.com/jquery-2.0.3.min.js"></script>
        <script src="js/jquery-2.0.3.min.js"></script>
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>  
    -->
    <script src="//code.jquery.com/jquery-2.0.3.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
    <script src="js/jquery-ui.js"></script>  
    <script src="js/jquery.blockUI.js"></script>  
    <script src="js/jquery.number.min.js"></script>  
    <script src="js/bootstrap-datepicker.min.js"></script>
<!--    <script src="js/bootstrap-dialog.min.js"></script>  -->
    <script src="js/funciones.js"></script>
<!--    <script src="js/find5.js"></script>  SOLO TESTING -->
    <script src="js/validator.js"></script>
    <script src="js/sweetalert-dev.js"></script>
    
<!-- UPLOAD FILE opcion 1 -->
    <script src="js/jquery.iframe-transport.js"></script>
    <script src="js/jquery.fileupload.js"></script>
<!-- UPLOAD FILE opcion 1 -->

    <script src="//code.angularjs.org/1.6.1/angular.min.js"></script>





    <!-- <script src="js/bootstrap-datepicker.js"></script> -->
    <!-- <link href="css/datepicker.css" rel="stylesheet"> -->

    <!-- <link href="css/bootstrap-datepicker.css" rel="stylesheet"> -->
    <!-- <link href="css/bootstrap-datepicker.min.css" rel="stylesheet"> -->
    <!-- <link href="css/bootstrap-datepicker.standalone.css" rel="stylesheet"> -->
    <!-- <link href="css/bootstrap-datepicker.standalone.min.css" rel="stylesheet"> -->
    <!-- <link href="css/bootstrap-datepicker3.css" rel="stylesheet"> -->
    
    <!-- <link href="css/bootstrap-datepicker3.min.css" rel="stylesheet"> -->
    
    <!-- <link href="css/bootstrap-datepicker3.standalone.css" rel="stylesheet"> -->
    <!-- <link href="css/bootstrap-datepicker3.standalone.min.css" rel="stylesheet"> -->


<script>

$.fn.datepicker.defaults.format = "dd/mm/yyyy";
$.fn.datepicker.defaults.autoclose = true;
$.fn.datepicker.defaults.todayHighlight = true; 


/*$(document).ready(function() {
	$('#fecha_entrega').datepicker({
	    format: "dd/MM/yyyy"
	});


	$('.datepicker').datepicker({
	    format: 'dd/mm/yyyy',
	    startDate: '-3d'
	});

});
*/

</script>



</head>
<body ng-app>
<?php if (isset($page) and $page != 'login') { ?>
	<header>

<!--MENU ORIGINAL
    <!--
    	<div id="templatemo_home">
    		<div class="templatemo_top">
      			<div class="container templatemo_container">
        			<div class="row">
          				<div class="col-xs-2 col-sm-1 col-md-1">
            				<div class="logo">
              					<a href="index.php"><img src="images/LogoGuatePlastTransparente.png" alt="Logo Guateplast"></a>
            				</div>
          				</div>
          				<div class="col-xs-2 col-sm-1 col-md-1 heading-info">
          					<span><?php echo $_SESSION['login']['nombre_usuario']; ?></span>
          				</div>
          				<div class="col-xs-8 col-sm-10 col-md-10 templatemo_col9">
				          	<div id="top-menu">
					            <nav class="mainMenu">
					              <ul class="nav">
					                <li><a <?php if($page==='pedido')             { ?> class="menu menu-selected" href="javascript:void(0);" <?php } ?> class="menu" href="pedido.php">Pedido</a></li>
					                <li><a <?php if($page==='consulta')           { ?> class="menu menu-selected" href="javascript:void(0);" <?php } ?> class="menu" href="consulta.php">Consultas</a></li>
                                    <li><a <?php if($page==='aprobar-especiales') { ?> class="menu menu-selected" href="javascript:void(0);" <?php } ?> class="menu" href="aprobar-especiales.php">Pedidos Especiales</a></li>
					                <li><a <?php if($page==='cerrar')             { ?> class="menu menu-selected" href="javascript:void(0);" <?php } ?> class="menu" href="javascript:void(0);">Cerrar Sesion</a></li>
					              </ul>
					            </nav>
				            </div>
    					</div>
       				</div>
      			</div>
    		</div>
    	</div>
    	<div class="clear"></div>
MENU ORIGINAL -->

<!-- MENU TEST 1
    <div class="navbar-more-overlay"></div>
    <nav class="navbar navbar-default navbar-static-top animate">
        <div class="container navbar-more visible-xs">
            <ul class="nav navbar-nav">
                <li>
                    <a href="#">
                        <span class="menu-icon fa fa-picture-o"></span>
                        Photos
                    </a>
                </li>
                <li>
                    <a href="#">
                        <span class="menu-icon fa fa-bell-o"></span>
                        Reservations
                    </a>
                </li>
                <li>
                    <a href="#">
                        <span class="menu-icon fa fa-picture-o"></span>
                        Photos
                    </a>
                </li>
                <li>
                    <a href="#">
                        <span class="menu-icon fa fa-bell-o"></span>
                        Reservations
                    </a>
                </li>
                <li>
                    <a href="#">
                        <span class="menu-icon fa fa-picture-o"></span>
                        Photos
                    </a>
                </li>
                <li>
                    <a href="#">
                        <span class="menu-icon fa fa-bell-o"></span>
                        Reservations
                    </a>
                </li>
                <li>
                    <a href="#">
                        <span class="menu-icon fa fa-picture-o"></span>
                        Photos
                    </a>
                </li>
                <li>
                    <a href="#">
                        <span class="menu-icon fa fa-bell-o"></span>
                        Reservations
                    </a>
                </li>
                <li>
                    <a href="#">
                        <span class="menu-icon fa fa-picture-o"></span>
                        Photos
                    </a>
                </li>
                <li>
                    <a href="#">
                        <span class="menu-icon fa fa-bell-o"></span>
                        Reservations
                    </a>
                </li>
                <li>
                    <a href="#">
                        <span class="menu-icon fa fa-picture-o"></span>
                        Photos
                    </a>
                </li>
                <li>
                    <a href="#">
                        <span class="menu-icon fa fa-bell-o"></span>
                        Reservations
                    </a>
                </li>
                <li>
                    <a href="#">
                        <span class="menu-icon fa fa-picture-o"></span>
                        Photos
                    </a>
                </li>
                <li>
                    <a href="#">
                        <span class="menu-icon fa fa-bell-o"></span>
                        Reservations
                    </a>
                </li>
            </ul>
        </div>
        <div class="container">
            <div class="navbar-header hidden-xs">
                <a class="navbar-brand" href="#">Brand</a>
            </div>

            <ul class="nav navbar-nav navbar-left mobile-bar">
                <li>
                    <a href="#">
                        <span class="menu-icon fa fa-home"></span>
                        Home
                    </a>
                </li>
                <li>
                    <a href="#">
                        <span class="menu-icon fa fa-info"></span>
                        <span class="hidden-xs">About the Boat</span>
                        <span class="visible-xs">About</span>
                    </a>
                </li>
                <li class="hidden-xs">
                    <a href="#">
                        <span class="menu-icon fa fa-picture-o"></span>
                        Photos
                    </a>
                </li>
                <li>
                    <a href="#">
                        <span class="menu-icon fa fa-ship"></span>
                        Cruises
                    </a>
                </li>
                <li class="hidden-xs">
                    <a href="#">
                        <span class="menu-icon fa fa-bell-o"></span>
                        Reservations
                    </a>
                </li>
                <li>
                    <a href="#">
                        <span class="menu-icon fa fa-phone"></span>                     
                        <span class="hidden-xs">Contact Us</span>
                        <span class="visible-xs">Contact</span>
                    </a>
                </li>
                <li class="visible-xs">
                    <a href="#navbar-more-show">
                        <span class="menu-icon fa fa-bars"></span>
                        More
                    </a>
                </li>
            </ul>
        </div>
    </nav>
MENU TEST 1 -->

<nav class="navbar navbar-default menu-navbar">
  <div class="container">

    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed menu-toggle-button" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Navegacion</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="dashboard.php">
        <img class="menu-logo" src="images/LogoGuatePlastTransparente.png" alt="Logo Guateplast">
      </a>
    </div>

    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <!-- 
        <li class="active"><a href="#">Pedidos <span class="sr-only">(current)</span></a></li> 
        <li><a href="#">Consultas</a></li>
        <li><a href="#">Aprobar Especiales</a></li>
        -->
        <?php 
//            require_once C_ROOT_DIR.'classes/csUsuario.php';
//            $user = new csUsuario();
        ?>            
        <li><a <?php if($page==='pedido')             { ?> class="menu menu-selected" href="javascript:void(0);" <?php } ?> class="menu" href="pedido.php">Pedido <span class="sr-only">(current)</span></a></li>
        <li><a <?php if($page==='consulta')           { ?> class="menu menu-selected" href="javascript:void(0);" <?php } ?> class="menu" href="consulta.php">Consultas</a></li>
        <?php
//            $hasAccess = $user->hasRole('GESTOR, ADMINISTRADOR');
//            if ($hasAccess) {
            if (userHasRoles('GESTOR, ADMINISTRADOR, AUTORIZADOR')) {
        ?>
            <li><a <?php if($page==='confirmar')          { ?> class="menu menu-selected" href="javascript:void(0);" <?php } ?> class="menu" href="confirmar.php">Confirmar Pedidos</a></li>
        <?php }?>

        <?php
//            $hasAccess = $user->hasRole('ADMINISTRADOR');
//            if ($hasAccess) {
            if (userHasRoles('ADMINISTRADOR, AUTORIZADOR')) {
        ?>
            <li><a <?php if($page==='aprobar-especiales') { ?> class="menu menu-selected" href="javascript:void(0);" <?php } ?> class="menu" href="aprobar-especiales.php">Pedidos Especiales</a></li>
        <?php }?>

        <!--
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Dropdown <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="#">Action</a></li>
            <li><a href="#">Another action</a></li>
            <li><a href="#">Something else here</a></li>
            <li role="separator" class="divider"></li>
            <li><a href="#">Separated link</a></li>
            <li role="separator" class="divider"></li>
            <li><a href="#">One more separated link</a></li>
          </ul>
        </li>
        -->
      </ul>
      <ul class="nav navbar-nav navbar-right">
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php echo $_SESSION['login']['nombre_usuario']; ?> <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a class="menu" href="logout.php">Cerrar Sesion</a></li>
          </ul>
        </li>
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>

 	</header>
 	<div class="container">
 		<br><!--<br><br><br><br> <!--espacio para el header> -->
<?php } ?>


