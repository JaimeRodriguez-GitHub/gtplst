<?php  $page = 'usuarios'; require_once("header.php"); ?>

<link rel="stylesheet" type="text/css" href="css/bootstrap-table.css">
<script type="text/javascript" src="js/bootstrap-table.js"></script>

<script>
doWait();
</script>

<p class="toolbar">
    <a class="create btn btn-primary" href="javascript:">Nuevo Usuario</a>
    <span class="alert"></span>
</p>

<table id="table"
       data-show-columns="true"
       data-search="true"
       data-query-params="queryParams"
       data-toolbar=".toolbar">
    <thead>
    <tr>
        <th data-field="id_usuario"   data-sortable="true" data-align="center">ID</th>
        <th data-field="nombre"       data-sortable="true" data-align="left"  >NOMBRE</th>
        <th data-field="email"        data-sortable="true" data-align="left"  >EMAIL</th>
        <th data-field="action" data-align="center" data-formatter="actionFormatter" data-events="actionEvents">Accion</th>
    </tr>
    </thead>
    <tbody>
      <?php 
        require_once (C_ROOT_DIR.'classes/csUsuario.php');
        $csUsuario = new csUsuario();
        $rsDatos = $csUsuario->getUsuarios();
        if ($rsDatos) {
          while ($row = $rsDatos->fetch_assoc()) {
              echo "<tr class=''>
                      <td>".$row["id_usuario"]."</td>
                      <td>".utf8_encode($row["nombre"])."</td>
                      <td>".$row["email"]."</td>
                      <td></td>
                     </tr>"; 
          } // while
        } // if rsDatos
      ?>
    </tbody>
</table>

<div id="modal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Nombre</label>
                    <input type="text" class="form-control" name="nombre" placeholder="Nombre">
                </div>
                <!--
                <div class="form-group">
                    <label>Stars</label>
                    <input type="number" class="form-control" name="stargazers_count" placeholder="Stars">
                </div>
                -->
                <div class="form-group">
                    <label>Email</label>
                    <input type="text" class="form-control" id="email" name="email" placeholder="Email">
                </div>
                <div class="form-group">
                    <label>Contraseña</label>
                    <input type="text" class="form-control" name="contrasena" placeholder="Contraseña">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary submit">Guardar</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!--
<table data-toggle="table"
       data-height="300"
       data-search="true">
    <thead>
    <tr>
        <th data-field="name">Name</th>
        <th data-field="stargazers_count">Stars</th>
        <th data-field="forks_count">Forks</th>
        <th data-field="action" data-formatter="actionFormatter" data-events="actionEvents">Action</th>
    </tr>
    </thead>
    <tbody>
        <tr><td>n1</td><td>s1</td><td>f1</td><td></td></tr>
        <tr><td>n2</td><td>s2</td><td>f2</td><td></td></tr>
    </tbody>    
</table>
-->

<script>
// function actionFormatter(value, row, index) {
//     return [
//         '<a class="like" href="javascript:void(0)" title="Like">',
//         '<i class="glyphicon glyphicon-heart"></i>',
//         '</a>',
//         '<a class="edit ml10" href="javascript:void(0)" title="Edit">',
//         '<i class="glyphicon glyphicon-edit"></i>',
//         '</a>',
//         '<a class="remove ml10" href="javascript:void(0)" title="Remove">',
//         '<i class="glyphicon glyphicon-remove"></i>',
//         '</a>'
//     ].join('');
// }

window.actionEvents = {
    'click .like': function (e, value, row, index) {
        alert('You click like icon, row: ' + JSON.stringify(row));
        console.log(value, row, index);
    },
    'click .edit': function (e, value, row, index) {
        alert('You click edit icon, row: ' + JSON.stringify(row));
        console.log(value, row, index);
    },
    'click .remove': function (e, value, row, index) {
        //alert('You click remove icon, row: ' + JSON.stringify(row));
        //console.log(e, value, row, index);
        BorrarUsuario(row.id_usuario);
    }
};
</script>


<script>


    var API_URL = 'http://' + location.host + ':3001/list/';

//    var $table = $('#table').bootstrapTable({url: API_URL}),
    var $table = $('#table').bootstrapTable(),
        $modal = $('#modal').modal({show: false}),
        $alert = $('.alert').hide();
//console.log ($table);

    $(function () {
        // create event
        $('.create').click(function () {            
            showModal($(this).text());            
        });

        $modal.find('.submit').click(function () {
            var row = {};

            row['id_usuario'] = $modal.data('id') ? $modal.data('id') : '';
            //trae los datos ingresados
            $modal.find('input[name]').each(function () {
                row[$(this).attr('name')] = $(this).val();
            });

//console.log(row);

            $.ajax({
                url: 'grabar_usuario.php',
                type: "POST",                
                async: false,
                cache: false,
                dataType: "json",
                data: {datos_usuario:JSON.stringify(row)},
                success: function () {
                    $modal.modal('hide');
                    $table.bootstrapTable('refresh');
                    showAlert(($modal.data('id') ? 'Update' : 'Create') + ' item successful!', 'success');
                    location.reload();
                },
                error: function () {
                    $modal.modal('hide');
                    showAlert(($modal.data('id') ? 'Update' : 'Create') + ' item error!', 'danger');
                }
            });

        }); //submit click
    });

    function queryParams(params) {
        return {};
    }

    function actionFormatter(value, row) {
        return [
            '<a class="update" href="javascript:void(0)" title="Modificar">',
            '  <i class="glyphicon glyphicon-edit"></i>',
            '</a>&nbsp;&nbsp;',
            '<a class="remove" href="javascript:void(0)" title="Borrar">',
            '  <i class="glyphicon glyphicon-remove-circle"></i>',
            '</a>'
        ].join('');
        /*
        return [
            '<a class="update" href="javascript:click_update(',row.id_usuario,')" title="Modificar">',
            '  <i class="glyphicon glyphicon-edit"></i>',
            '</a>&nbsp;&nbsp;',
            '<a class="remove" href="javascript:click_remove(',row.id_usuario,')" title="Borrar">',
            '  <i class="glyphicon glyphicon-remove-circle"></i>',
            '</a>'
        ].join('');
        */
    }

//     // update and delete events
//     window.actionEvents = {
//         'click .update': function (e, value, row, index) {
// console.log(value, row, index);
//         },
//         'click .remove': function (e, value, row, index) {
// console.log(value, row, index);
//         }
//     };

/*
'click .like': function (e, value, row, index) {
        alert('You click like icon, row: ' + JSON.stringify(row));
        console.log(value, row, index);
    },
    'click .edit': function (e, value, row, index) {
        alert('You click edit icon, row: ' + JSON.stringify(row));
        console.log(value, row, index);
    },
    'click .remove': function (e, value, row, index) {
        alert('You click remove icon, row: ' + JSON.stringify(row));
        console.log(value, row, index);
    }    
*/

    function click_update(pId) {
console.log('editar: '+pId);        
$table.bootstrapTable('refresh');
    }

    function click_remove(pId) {
console.log('borrar: '+pId);        
$table.bootstrapTable('refresh');
    }


//$('#nombre-usuario').focus();
    function showModal(title, row) {
console.log(title); console.log(row);
        row = row || {
            nombre: '',
            //stargazers_count: 0,
            email: '',
            contrasena: ''
        }; // default row value
console.log(row.id);
        $modal.data('id', row.id);
        $modal.find('.modal-title').text(title);
        for (var name in row) {
            $modal.find('input[name="' + name + '"]').val(row[name]);
        }
        $modal.modal('show');        

    }

    function showAlert(title, type) {
        $alert.attr('class', 'alert alert-' + type || 'success')
              .html('<i class="glyphicon glyphicon-check"></i> ' + title).show();
        setTimeout(function () {
            $alert.hide();
        }, 3000);
    }

</script>

  <script>
    doUnWait(1000);
  </script>

<?php require_once("footer.php"); ?>
