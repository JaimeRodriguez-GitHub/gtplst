<?php //$page="login"; require_once("header.php");?>

<!DOCTYPE html>
<html>
<head>
    <meta name="keywords" content="" />
    <meta name="description" content="" />
    <meta charset="UTF-8">
    <title>Pedidos Guateplast</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="images/favicon.ico">
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
    <link href="css/bootstrap-formhelpers.min.css" rel="stylesheet">
    <link href="css/datepicker.css" rel="stylesheet">
    <link href="css/templatemo_style.css" rel="stylesheet">    
    <link href="css/menu-style.css" rel="stylesheet">
    <link href='//fonts.googleapis.com/css?family=Raleway:400,100,600' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="css/sweetalert.css">
    <link href="css/login-styles.css" rel="stylesheet" />    



    <script src="//code.jquery.com/jquery-2.0.3.min.js"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
    <script src="js/jquery-ui.js"></script>  
    <script src="js/jquery.blockUI.js"></script>  
    <script src="js/jquery.number.min.js"></script>  
    <script src="js/bootstrap-datepicker.min.js"></script>
<!--    <script src="js/bootstrap-dialog.min.js"></script>  -->
    <script src="js/funciones.js"></script>
<!--    <script src="js/find5.js"></script>  SOLO TESTING -->
    <script src="js/validator.js"></script>
    <script src="js/sweetalert-dev.js"></script>
</head>
<body>


<div class="container">
  <div class="col-sm-8 col-sm-offset-2">


    <div class="card card-container">
      <img id="profile-img" class="profile-img-card" src="images/LogoGuatePlast.jpg" />
      <p id="profile-name" class="profile-name-card"></p>

      <div id="form-olvidado">
        <form accept-charset="UTF-8" role="form" id="login-form" method="post" action="lib/access.php">
          <fieldset>
            <div class="form-group input-group">
              <span class="input-group-addon">@</span>
              <input class="form-control" placeholder="Correo electrónico" name="email" id="email" type="email" required="" >
            </div>
            <div class="form-group input-group">
              <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
              <input value="" class="form-control" placeholder="Contraseña" name="password" type="password" value="" required="" autofocus="">
            </div>
            <div class="form-group">
              <button type="submit" class="btn btn-primary btn-block">Ingresar</button>
              <div class="pull-center" style="display: none;" id="alert_fail_login"> <!-- failure alert-->
                <br>
                <div class="text-center alert alert-danger" id="alert_fail_login_text">
                </div>
              </div>              
              <div style="display: none;" id="alert_success_login"> <!-- success alert-->
                <br>
                <div class="text-justify alert alert-success" id="alert_success_login_text">
                    Su contraseña ha sido reiniciada correctamente, por favor escriba sus datos para ingresar al sistema.
                </div>  
              </div>
              <p class="help-block">
                <a class="pull-right active" href="#" id="olvidado"><small>¿Olvidaste tu contraseña?</small></a>
              </p>
            </div>
          </fieldset>
        </form>
      </div> <!-- Div para User y Password -->
      
      <div style="display: none;" id="form-olvidado">
        <h4 class="">¿Olvidaste tu contraseña?</h4>
        <!--<form accept-charset="UTF-8" role="form" id="login-recordar" method="post" action="lib/recover.php">-->
        <!--<form accept-charset="UTF-8" role="form" id="login-recordar">-->
          <fieldset>
            <span class="help-block">
              <p class="text-justify">Ingresa tu correo electrónico y te enviaremos las instrucciones para crear una nueva contraseña.</p>
            </span>
            <div class="form-group input-group">
              <span class="input-group-addon">@</span>
              <input class="form-control" placeholder="Email" name="email" id="email-olvidado" type="email" required="">
            </div>
            <button type="submit" class="btn btn-primary btn-block" id="btn-olvidado">Continuar</button>

            <div style="display: none;" id="alert_fail"> <!-- failure alert-->
              <br>
              <div class="text-center alert alert-danger" id="alert_fail_text">
              </div>
            </div>

            <div style="display: none;" id="alert_success"> <!-- success alert-->
              <br>
              <div class="text-justify alert alert-success" id="alert_success_text">
                  Se ha enviado un correo a tu buzón con instrucciones para crear tu nueva contraseña. Si no aparece en tu bandeja de Entrada, asegurate de revisar tu bandeja de Correo No Deseado.
              </div>  
            </div>


            <p class="help-block">
              <a class="active" href="#" id="acceso"><small>&iexcl;Ya tengo mi contraseña!</small></a>
            </p>
          </fieldset>
        <!--</form>-->
      </div> <!-- Div para Olvide mi Contrasena -->

<!--  SNIPPET NORMAL
            <form name="form" class="form-signin" action="login.php" role="form">
    
                <span id="reauth-email" class="reauth-email"></span>
                <div class="form-group">
                    <input value="jaimerodriguezvillalta@hotmail.com" type="email" name="username" id="inputEmail" class="form-control" placeholder="Correo Electrónico"  required autofocus />
                    <span class="help-block help-block-messages">Correo requerido</span>
                </div>

                <div class="form-group">
                    <input value="123"type="password" name="password" id="inputPassword" class="form-control" placeholder="Contraseña" required />
                    <span class="help-block help-block-messages">Contraseña requerida</span>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                    	INGRESAR
                        <span style="display: none;"><i class="glyphicon glyphicon-refresh spinning"></i></span>
                    </button>
                </div>

            </form>
            <p>   
            <a href="forgotpw.php" class="forgot-password">
                ¿Olvidaste tu contraseña?
            </a>
            </p>
SNIPPET NORMAL -->

    </div><!-- /card-container  -->
  </div> <!-- columnas -->
</div> <!-- container -->

<script type="text/javascript">

//        $(function(){     //PARA DESHABILITAR BOTON
//            $('button').click(function() {
//                $(this).toggleClass('active');
//                });
//            });

$(document).ready(function() {
  var attempt = getUrlParameter('attempt');
  if (attempt=='failed'){
     $("#alert_fail_login_text").html('Usuario o contraseña incorrectos'); 
     $("#alert_fail_login").hide(); 
     $("#alert_fail_login").fadeIn(1000); 
  }     
  if (attempt=='success'){
     $("#alert_success_login").hide(); 
     $("#alert_success_login").fadeIn(1000); 
  }     

	$('#email').val(localStorage.email);
  $('#olvidado').click(function(e) {
    e.preventDefault();
    $("#alert_success").hide(); 
    $("#alert_fail").hide(); 
    $('div#form-olvidado').toggle('500');
    $('#email-olvidado').focus();
  });
  $('#acceso').click(function(e) {
    e.preventDefault();
    $('div#form-olvidado').toggle('500');
    $('#password').focus();
  });
});

$('#login-form').submit(function() {
	localStorage.email = $('#email').val();
});		

$("#btn-olvidado").click( function(e) {
  $.ajax({
    method: "POST",
    url: "lib/recover.php",
    data: { email: $("#email-olvidado").val() }
  })
  .done(function( msg ) {
      if (msg=='OK') {
        $("#alert_success").hide(); 
        $("#alert_fail").hide(); 
        $("#alert_success").fadeIn(1000); 

      } else {
        $("#alert_fail_text").html(msg); 
        $("#alert_success").hide(); 
        $("#alert_fail").hide(); 
        $("#alert_fail").fadeIn(1000); 
      }
  })
  .fail(function() {
        $("#alert_fail_text").html('Servicio no disponible, por favor intente mas tarde.'); 
        $("#alert_fail").hide(); 
        $("#alert_fail").fadeIn(1000); 
  });

});

</script>

</body>
</html>
