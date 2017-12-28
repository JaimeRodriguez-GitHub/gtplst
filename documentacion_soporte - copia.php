<?php
  $page = 'pedido'; require_once("header.php");
  error_reporting(E_ALL & ~E_NOTICE);
  if(!isset($_POST['data'])) {
  	echo ('<blockquote><p>Pedido Especial no encontrado!!</p></blockquote>');
  	die();
  }
?>

<blockquote>
	<h2>Carga de documentacion de soporte para el pedido # <strong><?php echo $_POST['data']; ?></strong></h2>
    <p>Para que este pedido sea autorizado usted debe proveer la documentacion de soporte que ampara la negociacion previamente realizada. Puede subir un archivo PDF, un archivo de WORD o una IMAGEN de tipo JPG o bien PNG.</p>
    <p>NO se permiten archivos mayores de 10Mb</p>
</blockquote>  
<br>

<span class="btn btn-success fileinput-button">
    <i class="glyphicon glyphicon-plus"></i>
    <span>Seleccionar archivo...</span>
    <!-- The file input field used as target for the file upload widget -->
    <input id="fileupload" type="file" name="files[]">
</span>    
<br>
<br>

<div id="progress" class="progress">
    <div class="progress-bar progress-bar-success"></div>
</div>

<div id="files" class="files"></div>
<br>

<script>
$(function () {
    'use strict';
    var id_pedido = <?php echo $_POST['data']; ?>;
    var url = 'carga/'+'?pn='+id_pedido;
    var nombre_archivo = '';
    //alert(url);
    $('#fileupload').fileupload({
        url: url,
        dataType: 'json',
        autoUpload: false,
        acceptFileTypes: /(\.|\/)(gif|jpe?g|png)$/i,
        maxFileSize: 999000,
        //add: function (e, data) {
            //console.log('2');
        //    console.log(data.total);
        //    return true;
            //console.log(data.total);
            //if (data.total > 100000) {
            //    alert('no se puede');
            //    
            //    return false;
            //}
        //},
        done: function (e, data) {
            $.each(data.result.files, function (index, file) {
                //$('<p/>').text(file.name).appendTo('#files');
                nombre_archivo = file.name
            });

			var form = document.createElement("form");
			form.method = 'post';
			form.action = 'documentacion_soporte_update.php';
			var input = document.createElement('input');
			input.type = "text";
			input.name = "nombre_archivo";
			input.value = nombre_archivo;
			form.appendChild(input);
			var input2 = document.createElement('input');
			input2.type = "text";
			input2.name = "id_pedido";
			input2.value = id_pedido;
			form.appendChild(input2);
			form.submit();


    //         $.each(data.result.files, function (index, file) {
			 //    $.ajax({
				// 	dataType: "json",
				// 	url: 'lib/setajaxvalues.php',
				// 	async: false,
				// 	data: {action: 'setLinkDocumentacionSoporte', p: id_pedido, filename: file.name}
				// }).done(function(datax){
				// 	alert(done);
				// 	if(datax)
				//     	swal({  title: "Carga completada!",   
				//             text: "La documentacion ha sido cargada con exito.",   
				//             closeOnConfirm: false,
				//             type: "success"
				//         	},
				// 		  	function(){ 
				// 		  		location = 'pedido.php';
				// 		});  		       
				//     else
				//     	swal({  title: "Carga NO completada!",   
				//             text: "No se pudo cargar la documentacion. Intentelo de nuevo.",   
				//             closeOnConfirm: true,
				//             type: "error"
				//         	});  		       
				// }).fail(function() {
				// 	alert('fallo');
			 //    	swal({  title: "Carga NO completada!",   
			 //            text: "No se pudo cargar la documentacion. Intentelo de nuevo.",   
			 //            closeOnConfirm: true,
			 //            type: "error"
			 //        	});  		       
  		// 		}); //ajax done
    //         });


	  //   	swal({  title: "Carga completada!",   
	  //           text: "La documentacion ha sido cargada con exito.",   
	  //           closeOnConfirm: false,
	  //           type: "success"
	  //       	},
			//   	function(){ 
			//   		//location = 'pedido.php';
			// });  		       

//alert(file.name);

		  //   $.ajax({
				// dataType: "json",
				// url: 'lib/setajaxvalues.php',
				// async: false,
				// data: {action: 'setLinkDocumentacionSoporte', p: id_pedido, filename: file.name}
				// }).done(function(data){
				// 	alert(done);
				// 	if(done)
				//     	swal({  title: "Carga completada!",   
				//             text: "La documentacion ha sido cargada con exito.",   
				//             closeOnConfirm: false,
				//             type: "success"
				//         	},
				// 		  	function(){ 
				// 		  		location = 'pedido.php';
				// 		});  		       
				//     else
				//     	swal({  title: "Carga NO completada!",   
				//             text: "No se pudo cargar la documentacion. Intentelo de nuevo.",   
				//             closeOnConfirm: true,
				//             type: "error"
				//         	});  		       
				// }).fail(function() {
				// 	alert('fallo');
			 //    	swal({  title: "Carga NO completada!",   
			 //            text: "No se pudo cargar la documentacion. Intentelo de nuevo.",   
			 //            closeOnConfirm: true,
			 //            type: "error"
			 //        	});  		       
  		// 		}); //ajax done



		    // $.ajax({
		    // 		url: "borrar_pedido.php",
		    //         data: {n: pPedidoBorrar},
		    //         type: "POST",
		    //         //async: false,
		    //         //dataType: "json",
		    //         cache:false,
		    //         success: function(data){
		    //         	if(data==='1') {
						// 	swal({	title: "Pedido # "+pPedidoBorrar, 
						// 	  		text: "borrado con exito !!!",
						// 	  		type: "success",
						// 	  		closeOnConfirm: false 
						// 	  	},
						// 	  	function(){ 
						// 	  		//swal.close();											  		
						// 	  		location = location.protocol + '//' + location.host + location.pathname;
						// 	  	});
		    //         	} else {
						// 	swal({	title: "No se pudo borrar", 
						// 	  		text: data,
						// 	  		type: "error",
						// 	  		closeOnConfirm: false 
						// 	  	},
						// 	  	function(){ 
						// 	  		swal.close();
						// 	  		//location.reload(); 
						// 	  	});						            		
		    //         	}
		    //           	console.log(data);
		    //         },
		    //         error: function(e){
		    //         	doUnWait();
		    //         	console.log(e.responseText);
						// swal({title: "Alerta", 
						// 	  text: e.responseText,
						// 	  type: "error"});
		    //         }
		    // });

        },
        error: function (e, data) {
            console.log("error: " + data.total);
        },
        processfail: function (e, data) {
            alert(data.files[data.index].name + "\n" + data.files[data.index].error);
        },
        progressall: function (e, data) {
            var progress = parseInt(data.loaded / data.total * 100, 10);
            console.log ("t: " + data.total);
            console.log ("l: " + data.loaded);
            console.log ("p: " + progress);
            $('#progress .progress-bar').css(
                'width',
                progress + '%'
            );
            if (data.total < 1000000) {
            } else {
                console.log('muy grande');
                $('#progress .progress-bar').html('Muy grande');
                return false;
            }
        }
    }).on('fileuploadprocessalways', function (e, data) {
        var index = data.index,
            file = data.files[index],
            node = $(data.context.children()[index]);
        if (file.preview) {
            node
                .prepend('<br>')
                .prepend(file.preview);
        }
        if (file.error) {
            node
                .append('<br>')
                .append($('<span class="text-danger"/>').text(file.error));
        }
        if (index + 1 === data.files.length) {
            data.context.find('button')
                .text('Upload')
                .prop('disabled', !!data.files.error);
        }
    }).on('fileuploadfail', function (e, data) {
        $.each(data.files, function (index) {
            var error = $('<span class="text-danger"/>').text('File upload failed.');
            $(data.context.children()[index])
                .append('<br>')
                .append(error);
        });
    }).prop('disabled', !$.support.fileInput)
        .parent().addClass($.support.fileInput ? undefined : 'disabled');
});
</script>

<?php $page = 'pedido'; require_once("footer.php"); ?>
