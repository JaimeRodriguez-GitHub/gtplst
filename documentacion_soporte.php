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

    $('#fileupload').fileupload({
        url: url,
        dataType: 'json',
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
        },
        error: function (e, data) {
            console.log("error: " + data.total);
        },
        processfail: function (e, data) {
            alert(data.files[data.index].name + "\n" + data.files[data.index].error);
        },
        progressall: function (e, data) {
            var progress = parseInt(data.loaded / data.total * 100, 10);
            //console.log ("t: " + data.total);
            //console.log ("l: " + data.loaded);
            //console.log ("p: " + progress);
            $('#progress .progress-bar').css(
                'width',
                progress + '%'
            );
            //if (data.total < 1000000) {
            //} else {
            //    console.log('muy grande');
            //    $('#progress .progress-bar').html('Muy grande');
            //    return false;
            //}
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
