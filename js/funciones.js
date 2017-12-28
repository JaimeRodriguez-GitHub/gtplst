var C_ID_PEDIDOS_ESPECIALES = 1;

//[+]-----------------------------------------------------------------------[+]
//   Extension de tipos
//[+]-----------------------------------------------------------------------[+]
String.prototype.lpad = function(pPadChar, pLength) {
  return this.length >= pLength ? this.toString() : new Array(pLength - this.length + 1).join(pPadChar) + this;
}
String.prototype.parseSQLInjection = function() {
  tmpVar = this.replace("UNION","UNIO_");
  tmpVar = tmpVar.replace("SELECT","SELEC_");
  tmpVar = tmpVar.replace("FROM","FRO_");
  return tmpVar;
}
String.prototype.fixSQL = function() {
  return this.replace(/'/g, "\\'");
}
Number.prototype.toCurrency = function(decimales) {
	//No funciona en las TABLETS
	//return (isNaN(this)) ? '' : (this.toLocaleString('en-IN',{ style: 'currency', currency: 'GTQ', currencyDisplay: 'code' })).replace('GTQ','Q');
	//Se tuvo que usar un plug-in de jQuery
	return 'Q'+$.number(this, decimales, '.', ',')
}
String.prototype.parseNumberToSave = function() {
	returnValue = this.replace('Q', '');
	returnValue = this.replace(',', '');
	return returnValue;
}

//[+]-----------------------------------------------------------------------[+]
//   Funciones globales
//[+]-----------------------------------------------------------------------[+]
function doWait() {
	$.blockUI({message: '<img src="images/n-loadingBlueTransparent.gif" style="width:20px;" /> Un momento por favor...',css: {padding: '15px',border: '2px solid #5AA625'}});
}

function doUnWait(pMiliSeconds) {
	if (pMiliSeconds > 0) {
		setTimeout(function(){$.unblockUI();},pMiliSeconds);
	} else {
		$.unblockUI();	
	}
}

function getUrlParameter(sParam)
{
    var sPageURL = window.location.search.substring(1);
    var sURLVariables = sPageURL.split('&');
    for (var i = 0; i < sURLVariables.length; i++) 
    {
        var sParameterName = sURLVariables[i].split('=');
        if (sParameterName[0] == sParam) 
        {
            return sParameterName[1];
        }
    }
}   


function fixSQL(pText){
	//return pText.replace('''','\'');
	return pText.replace(/'/g, "\\'");
}

function valida_multiplos(pCantidad, vMultiplo, pTipoPedido) {
	if (pCantidad=='' || vMultiplo=='' || pCantidad==0)
		return false;
	else if (pTipoPedido == C_ID_PEDIDOS_ESPECIALES) 
	    return true;
	else {
	    var vMultiplo = pCantidad % vMultiplo;
	    return (vMultiplo == 0);
	}
}

function valida_precio(pPrecio, pTipoPedido) {
	if (pTipoPedido == C_ID_PEDIDOS_ESPECIALES) {
		if (!$.isNumeric(pPrecio)) {
			return false;
		} else if (pPrecio <= 0) {
			return false;
		} else {
			return true;
		}
	} else {
		return true;
	}
}

function calculaTotalPedido(x, y, pTipo) {
	//pTipo = R = resta
	//pTipo = S o vacio = suma
	y = pTipo==='R' ? y * -1 : y;
	return (x + y);
}

//function getMonthName() {
//	var monthNames = ["Jan", "Feb", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"
//	];
//var d = new Date();
//document.write("The current month is " + monthNames[d.getMonth()]);
//}

function BorrarUsuario(pIdBorrar) {
				swal({
					title: "¿Seguro de borrar usuario "+pIdBorrar,
					type: "warning", 
					showCancelButton: true, 
					confirmButtonText: "Si",
					cancelButtonText: "No",
					confirmButtonColor: "#DD6B55",
					closeOnConfirm: false,
					//closeOnCancel: false 
				},
					function(isConfirm) {
			        	if(isConfirm){ 			        		
						    $.ajax({
						    		url: "borrar_usuario.php",
						            data: {n: pIdBorrar},
						            type: "POST",
						            //async: false,
						            //dataType: "json",
						            cache:false,
						            success: function(data){
						            	if(data==='1') {
											swal({	title: "Usuario "+pIdBorrar, 
											  		text: "borrado con exito !!!",
											  		type: "success",
											  		closeOnConfirm: false 
											  	},
											  	function(){ 
											  		//swal.close();											  		
											  		location = location.protocol + '//' + location.host + location.pathname;
											  	});
						            	} else {
											swal({	title: "No se pudo borrar", 
											  		text: data,
											  		type: "error",
											  		closeOnConfirm: false 
											  	},
											  	function(){ 
											  		swal.close();
											  		//location.reload(); 
											  	});						            		
						            	}
						              	console.log(data);
						            },
						            error: function(e){
						            	doUnWait();
						            	console.log(e.responseText);
										swal({title: "Alerta", 
											  text: e.responseText,
											  type: "error"});
						            }
						    });
						}  //if isConfirm
					}  //function (swal)
				); //swal	
}

function BorrarPedido(pPedidoBorrar) {

				swal({
					title: "¿Seguro de borrar pedido # "+pPedidoBorrar,
					type: "warning", 
					showCancelButton: true, 
					confirmButtonText: "Si",
					cancelButtonText: "No",
					confirmButtonColor: "#DD6B55",
					closeOnConfirm: false,
					//closeOnCancel: false 
				},
					function(isConfirm) {
			        	if(isConfirm){ 			        		
						    $.ajax({
						    		url: "borrar_pedido.php",
						            data: {n: pPedidoBorrar},
						            type: "POST",
						            //async: false,
						            //dataType: "json",
						            cache:false,
						            success: function(data){
						            	if(data==='1') {
											swal({	title: "Pedido # "+pPedidoBorrar, 
											  		text: "borrado con exito !!!",
											  		type: "success",
											  		closeOnConfirm: false 
											  	},
											  	function(){ 
											  		//swal.close();											  		
											  		location = location.protocol + '//' + location.host + location.pathname;
											  	});
						            	} else {
											swal({	title: "No se pudo borrar", 
											  		text: data,
											  		type: "error",
											  		closeOnConfirm: false 
											  	},
											  	function(){ 
											  		swal.close();
											  		//location.reload(); 
											  	});						            		
						            	}
						              	console.log(data);
						            },
						            error: function(e){
						            	doUnWait();
						            	console.log(e.responseText);
										swal({title: "Alerta", 
											  text: e.responseText,
											  type: "error"});
						            }
						    });
						}  //if isConfirm
					}  //function (swal)
				); //swal	

}


$(document).ready(function(){

	$href = $(location).attr('href');  //averigua que URL esta en pantalla	

	//Ingreso de pedidos
	if ($href.toLowerCase().indexOf("pedido.php") >= 0) {

		// ************************************
		// TEST - habilitar despues para evitar page refresh
		// ************************************
	    // window.onbeforeunload = function() {
	    //     return "Seguro de abandonar?";
	    // }
	    // ************************************


		if ($('#tipo_pedido').val() == C_ID_PEDIDOS_ESPECIALES) {
			$("#div_precio_final").show();	
			$("#div_producto_general").show();	
		}

		$('#myForm').on('keyup keypress', function(e) {
		  var keyCode = e.keyCode || e.which;
		  if (keyCode === 13) { 
		    e.preventDefault();
		    return false;
		  }
		});	  

		(function( $ ) { 

			// combo de productos
		    $.widget( "custom.combobox", {
		      _create: function() {
		        this.wrapper = $( "<span>" )
		          .addClass( "custom-combobox" )
		          .insertAfter( this.element );
		 
		        this.element.hide();
		        this._createAutocomplete();
		        this._createShowAllButton();
		      },

		      _createAutocomplete: function() {
		        var selected = this.element.children( ":selected" ),
		          value = selected.val() ? selected.text() : "";
		 
		        this.input = $( "<input>" )
		          .appendTo( this.wrapper )
		          .val( value )
		          .attr( "title", "" )
		          //.addClass( "custom-combobox-input ui-widget ui-widget-content ui-state-default ui-corner-left" )
		          .addClass( "custom-combobox-input ui-widget ui-widget-content ui-state-default ui-corner-left " )
		          .autocomplete({
		            delay: 0,
		            //disabled: true,
		            minLength: 0,
		            select: function( event, ui ) {
		              arrDatosProducto = ui.item.option.value.split('~');
		              //console.log(arrDatosProducto);                              
		              $('#sp_producto_upc').text(arrDatosProducto[0]);
		              $('#sp_producto_codigo').text(arrDatosProducto[1]);
		              $('#sp_producto_ue').text(arrDatosProducto[2]);
		              var n = parseFloat(arrDatosProducto[4]);
		              n = n * 1.12;
		              precio_producto = n.toCurrency(4);
		              $('#sp_producto_precio').text(precio_producto);
		              $('#sp_descripcion').text(arrDatosProducto[3]);
		              $('#precio_final').val(n);
		            },
		            source: $.proxy( this, "_source" )
		          })
		          .tooltip({
		            tooltipClass: "ui-state-highlight"
		          });
		 
		        this._on( this.input, {
		          autocompleteselect: function( event, ui ) {
		            ui.item.option.selected = true;
		            this._trigger( "select", event, {
		              item: ui.item.option
		            });
		          },
		 
		          autocompletechange: "_removeIfInvalid"
		        });
		      },
		 
		      _createShowAllButton: function() {
		        var input = this.input,
		          wasOpen = false;
		        $( "<a>" )
		          .attr( "tabIndex", -1 )
		          .attr( "title", "Mostrar todos" )
		          .tooltip()
		          .appendTo( this.wrapper )
		          .button({
		            icons: {
		              primary: "ui-icon-triangle-1-s"
		            },
		            text: false
		          })
		          .removeClass( "ui-corner-all" )
		          .addClass( "custom-combobox-toggle ui-corner-right" )
		          .mousedown(function() {
		            wasOpen = input.autocomplete( "widget" ).is( ":visible" );
		          })
		          .click(function() {
		            input.focus();
		 
		            // Close if already visible
		            if ( wasOpen ) {
		              return;
		            }
		 
		            // Pass empty string as value to search for, displaying all results
		            input.autocomplete( "search", "" );
		          });
		      },
		 
		      _source: function( request, response ) {
		        var matcher = new RegExp( $.ui.autocomplete.escapeRegex(request.term), "i" );
		        response( this.element.children( "option" ).map(function() {
		          var text = $( this ).text();
		          if ( this.value && ( !request.term || matcher.test(text) ) )
		            return {
		              label: text,
		              value: text,
		              option: this
		            };
		        }) );
		      },
		 
		      _removeIfInvalid: function( event, ui ) {
		 
		        // Selected an item, nothing to do
		        if ( ui.item ) {
		          return;
		        }
		 
		        // Search for a match (case-insensitive)
		        var value = this.input.val(),
		          valueLowerCase = value.toLowerCase(),
		          valid = false;
		        this.element.children( "option" ).each(function() {
		          if ( $( this ).text().toLowerCase() === valueLowerCase ) {
		            this.selected = valid = true;
		            return false;
		          }
		        });
		 
		        // Found a match, nothing to do
		        if ( valid ) {
		          return;
		        }
		 
		        // Remove invalid value
		        this.input
		          .val( "" )
		          .attr( "title", "[" + value + "] No se encontro ninguna concordancia" )
		          .tooltip( "open" );
		        this.element.val( "" );
		        $('#sp_producto_upc').text('');
		        $('#sp_producto_codigo').text('');
		        $('#sp_producto_ue').text('');
		        $('#sp_producto_precio').text('');
		        $('#sp_descripcion').text('');        
		        $('#precio_final').text('');        
		        this._delay(function() {
		          this.input.tooltip( "close" ).attr( "title", "" );
		        }, 2500 );
		        this.input.autocomplete( "instance" ).term = "";
		      },
		 
		      _destroy: function() {
		        this.wrapper.remove();
		        this.element.show();
		      }
	
			}); // combo de productos

			// combo de clientes
			$.widget( "custom.comboboxClientes", {
		      _create: function() {
		        this.wrapper = $( "<span>" )
		          .addClass( "custom-combobox" )
		          .insertAfter( this.element );
		 
		        this.element.hide();
		        this._createAutocomplete();
		        this._createShowAllButton();
		      },

		      _createAutocomplete: function() {
		        var selected = this.element.children( ":selected" ),
		          value = selected.val() ? selected.text() : "";
		        this.input = $( "<input>" )
		          .appendTo( this.wrapper )
		          .val( value )
		          .attr( "title", "" )
		          .attr( "required", "" )
		          .addClass( "custom-comboboxclientes-input ui-widget ui-widget-content ui-state-default ui-corner-left " )
		          .autocomplete({
		            delay: 0,
		            minLength: 0,
		            select: function( event, ui ) {
		              arrDatosCliente = ui.item.option.value.replace("\\-\\","'").split('~');
//console.log(ui.item.option.value);
//console.log(ui.item.option.value.replace("\\-\\","'"));
		              //codigo cliente
		              $('#sp_codigo_cliente').text(arrDatosCliente[0]);
		              
		              //limite credito
		              var n = parseFloat(arrDatosCliente[1]);
		              limite_credito = n.toCurrency(4);
		              $('#limite_credito').text(limite_credito);

                      //estatus del cliente                        
                      //$('#estatus_cliente').text(''arrDatosCliente[5]);
                      if (arrDatosCliente[5]!='') {
                          $('#div_estatus_cliente').html('<div class="text-center alert alert-danger" id="alert_fail_login_text" style="display: -webkit-inline-box; width: 100%; text-align: -webkit-center; text-decoration:blink;"><strong>'+arrDatosCliente[5]+'</strong></div>');
                      } else {
                          $('#div_estatus_cliente').html('');
                      }
		              //direccion entrega
		              var direccion = arrDatosCliente[2];
		              $('#direccion_entrega').val(direccion);

//console.log(arrDatosCliente[3]);
		              //fecha entrega
		              if (arrDatosCliente[3] === undefined) {
		                fecha_entrega="";
		              } else {
		                var dias_entrega = arrDatosCliente[3];
		                var fechaTmp = new Date();
		                fechaTmp.setDate(parseInt(fechaTmp.getDate()) + (dias_entrega=='' ? 0 : parseInt(dias_entrega)));
		                var dd = fechaTmp.getDate() + '';
		                var mm = fechaTmp.getMonth() + 1 + '';
		                var y = fechaTmp.getFullYear();
		                var fecha_entrega = dd.lpad('0',2) + '/'+ mm.lpad('0',2) + '/'+ y;            
		              }
		              $('#fecha_entrega').val(fecha_entrega);

//console.log(arrDatosCliente[4]);
		              //nombre cliente
		              $('#sp_nombre_cliente').text(arrDatosCliente[4]);

		            },
		            source: $.proxy( this, "_source" )
		          })
		          .tooltip({
		            tooltipClass: "ui-state-highlight"
		          });
		 
		        this._on( this.input, {
		          autocompleteselect: function( event, ui ) {
		            ui.item.option.selected = true;
		            this._trigger( "select", event, {
		              item: ui.item.option
		            });
		          },
		 
		          autocompletechange: "_removeIfInvalid"
		        });
		      },
		 
		      _createShowAllButton: function() {
		        var input = this.input,
		          wasOpen = false;
		        $( "<a>" )
		          .attr( "tabIndex", -1 )
		          .attr( "title", "Mostrar todos" )
		          .tooltip()
		          .appendTo( this.wrapper )
		          .button({
		            icons: {
		              primary: "ui-icon-triangle-1-s"
		            },
		            text: false
		          })
		          .removeClass( "ui-corner-all" )
		          .addClass( "custom-combobox-toggle ui-corner-right" )
		          .mousedown(function() {
		            wasOpen = input.autocomplete( "widget" ).is( ":visible" );
		          })
		          .click(function() {
		            input.focus();
		 
		            // Close if already visible
		            if ( wasOpen ) {
		              return;
		            }
		 
		            // Pass empty string as value to search for, displaying all results
		            input.autocomplete( "search", "" );
		          });
		      },
		 
		      _source: function( request, response ) {
		        var matcher = new RegExp( $.ui.autocomplete.escapeRegex(request.term), "i" );
		        response( this.element.children( "option" ).map(function() {
		          var text = $( this ).text();
		          if ( this.value && ( !request.term || matcher.test(text) ) )
		            return {
		              label: text,
		              value: text,
		              option: this
		            };
		        }) );
		      },
		 
		      _removeIfInvalid: function( event, ui ) {
		 
		        // Selected an item, nothing to do
		        if ( ui.item ) {
		          return;
		        }
		 
		        // Search for a match (case-insensitive)
		        var value = this.input.val(),
		          valueLowerCase = value.toLowerCase(),
		          valid = false;
		        this.element.children( "option" ).each(function() {
		          if ( $( this ).text().toLowerCase() === valueLowerCase ) {
		            this.selected = valid = true;
		            return false;
		          }
		        });
		 
		        // Found a match, nothing to do
		        if ( valid ) {
		          return;
		        }
		 
		        // Remove invalid value
		        this.input
		          .val( "" )
		          .attr( "title", "[" + value + "] No se encontro ninguna concordancia" )
		          .tooltip( "open" );
		        this.element.val( "" );
				$('#sp_codigo_cliente').text('');
		        $('#limite_credito').text('');
		        $('#direccion_entrega').val('');
		        $('#fecha_entrega').val('');
				$('#sp_nombre_cliente').text('');
		        this._delay(function() {
		          this.input.tooltip( "close" ).attr( "title", "" );
		        }, 2500 );
		        this.input.autocomplete( "instance" ).term = "";
		      },
		 
		      _destroy: function() {
		        this.wrapper.remove();
		        this.element.show();
		      }

		    });  // combo de clientes

		})( jQuery );
 
////////////////////////////	    var lastSel = $("#tipo_pedido option:selected");
		var s = document.createElement("script");
		s.type = "text/javascript";
		s.src = "js/find5.js";

	    $( "#combobox" ).combobox();
	    $( "#comboboxClientes" ).comboboxClientes();
	    $( "#toggle" ).click(function() {
		    //$( "#combobox" ).toggle();
	    });


	    $.getJSON('lib/getajaxvalues.php', {action: 'getClientesUsuario', idCampania: $(this).val()}, function(data){
	        var options = '';
	        for (var x = 0; x < data.length; x++) {
	            //options += '<option value="' + data[x]['id'] + '" mytag_upc="' + data[x]['mytag_upc'] + '" mytag_codigo="' + data[x]['mytag_codigo'] + '">' + data[x]['reg'] + '</option>';
	            //options += '<option value="' + data[x]['id'] + '">' + data[x]['reg'] + '</option>';
	            options += "<option value='" + data[x]['id'] + "'>" + data[x]['reg'] + "</option>";
//console.log("<option value='" + data[x]['id'] + "'>");	            
	        }
	        $('#comboboxClientes').html(options);
	        $('.custom-combobox-input').val('');
	    });

	    $('#tipo_pedido').focus(function (){
			lastSel = $(this).val();
	    });


 	    //$('#tipo_pedido').on('change', function (){
 	    $('#tipo_pedido').change(function (){
			if(lastSel!='0') {
				swal({
					title: "¿Seguro de cambiar tipo de pedido?",
					text: "Se borraran los productos agregados al pedido actual",
					type: "warning", 
					showCancelButton: true, 
					confirmButtonText: "Si",
					cancelButtonText: "No",
					confirmButtonColor: "#DD6B55"
					//,
					//closeOnConfirm: false,
					//closeOnCancel: false 
				},
					function(isConfirm) {
			        	if(isConfirm){
							$('#sp_producto_upc').text('');
							$('#sp_producto_codigo').text('');
							$('#sp_producto_ue').text('');
							$('#sp_producto_precio').text('');
							$('#sp_descripcion').text('');
							$('#cantidad_solicitar').val('');
							$('#cantidad_solicitar_empaque').val('');
							$("#myTable tbody").html("");
							if ($("#tipo_pedido").val() == C_ID_PEDIDOS_ESPECIALES) {
								$("#div_precio_final").show();
								$("#div_producto_general").show();
								$("#myTable thead").html('<th></th><th>UPC</th><th>CODIGO</th><th class="text-right">CANT</th><th class="text-center">U/E</th><th>DESCRIPCION</th><th class="text-right">PRECIO BASE</th><th class="text-right">PRECIO FINAL</th><th class="text-right">TOTAL</th><th></th>'); 
								$("#myTable tfoot").html('<tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>');
							} else {
								$("#div_precio_final").hide();
								$("#div_producto_general").hide();
								$("#myTable thead").html('<th></th><th>UPC</th><th>CODIGO</th><th class="text-right">CANT</th><th class="text-center">U/E</th><th>DESCRIPCION</th><th class="text-right">PRECIO</th><th class="text-right">TOTAL</th><th></th>'); 
								$("#myTable tfoot").html('<tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>');
							}             
							$('#sp_totalpedido').text('0');
							$('#div_total_pedido').text('');
							$.getJSON('lib/getajaxvalues.php', {action: 'getProductosCampania', idCampania: $("#tipo_pedido").val()}, function(data){
							  var options = '';
							  for (var x = 0; x < data.length; x++) {
							      options += '<option value="' + data[x]['id'] + '" mytag_upc="' + data[x]['mytag_upc'] + '" mytag_codigo="' + data[x]['mytag_codigo'] + '">' + data[x]['reg'] + '</option>';
							  }
							  $('#combobox').html(options);
							  $('.custom-combobox-input').val('');
							});
			            	//swal.close();
			        	} else {
			            	$("#tipo_pedido").val(lastSel); // added parenthesis (edit)
			            	//swal.close();
			            	return false;
						}
					}  //function (swal)
				); //swal
			} else {
				$('#sp_producto_upc').text('');
				$('#sp_producto_codigo').text('');
				$('#sp_producto_ue').text('');
				$('#sp_producto_precio').text('');
				$('#sp_descripcion').text('');
				$('#cantidad_solicitar').val('');
				$('#cantidad_solicitar_empaque').val('');
				$("#myTable tbody").html("");
				if ($("#tipo_pedido").val() == C_ID_PEDIDOS_ESPECIALES) {
					$("#div_precio_final").show();
					$("#div_producto_general").show();
					$("#myTable thead").html('<th></th><th>UPC</th><th>CODIGO</th><th class="text-right">CANT</th><th class="text-center">U/E</th><th>DESCRIPCION</th><th class="text-right">PRECIO BASE</th><th class="text-right">PRECIO FINAL</th><th class="text-right">TOTAL</th><th></th>'); 
					$("#myTable tfoot").html('<tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>');	
				} else {
					$("#div_precio_final").hide();
					$("#div_producto_general").hide();
					$("#myTable thead").html('<th></th><th>UPC</th><th>CODIGO</th><th class="text-right">CANT</th><th class="text-center">U/E</th><th>DESCRIPCION</th><th class="text-right">PRECIO</th><th class="text-right">TOTAL</th><th></th>'); 
					$("#myTable tfoot").html('<tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>');	
				}             
				$('#sp_totalpedido').text('0');
				$('#div_total_pedido').text('');
				$.getJSON('lib/getajaxvalues.php', {action: 'getProductosCampania', idCampania: $("#tipo_pedido").val()}, function(data){
				  var options = '';
				  for (var x = 0; x < data.length; x++) {
				      options += '<option value="' + data[x]['id'] + '" mytag_upc="' + data[x]['mytag_upc'] + '" mytag_codigo="' + data[x]['mytag_codigo'] + '">' + data[x]['reg'] + '</option>';
				  }
				  $('#combobox').html(options);
				  $('.custom-combobox-input').val('');
				});
			} //lastSel != 0
//ESTE FUNCIONA
			// currentSel = $(this).val();
			// confirmaCambio = confirm('Desea cambiar tipo de pedido? Se borraran los productos agregados al pedido.');
			// if (confirmaCambio) {
			// 	lastSel = currentSel;
			// 	$('#sp_producto_upc').text('');
			// 	$('#sp_producto_codigo').text('');
			// 	$('#sp_producto_ue').text('');
			// 	$('#sp_producto_precio').text('');
			// 	$('#sp_descripcion').text('');
			// 	$('#cantidad_solicitar').val('');
			// 	$("#myTable tbody").html("");
			// 	$('#sp_totalpedido').text('0');
			// 	$('#div_total_pedido').text('');
			// 	$.getJSON('lib/getajaxvalues.php', {action: 'getProductosCampania', idCampania: $(this).val()}, function(data){
			// 	  var options = '';
			// 	  for (var x = 0; x < data.length; x++) {
			// 	      options += '<option value="' + data[x]['id'] + '" mytag_upc="' + data[x]['mytag_upc'] + '" mytag_codigo="' + data[x]['mytag_codigo'] + '">' + data[x]['reg'] + '</option>';
			// 	  }
			// 	  $('#combobox').html(options);
			// 	  $('.custom-combobox-input').val('');
			// 	});				
			// } else {
			// 	$("#tipo_pedido").val(lastSel); // added parenthesis (edit)
		 // 		return false;
			// }
//ESTE FUNCIONA

	    }); //tipo_pedido.change

        //evita punto decimal
        $("#cantidad_solicitar").keypress(function(e) {
			return (e.which!=46);
		});
        //evita punto decimal
        $("#cantidad_solicitar_empaque").keypress(function(e) {
			return (e.which!=46);
		});

		$('#precio_final').on('change keyup', function(event) {
		    if ( event.target.validity.valid ) {
		        $('#test2').text($(this).val());
		         $("#btnAgregarProducto").prop("disabled",false);
		    } else {
		        $('#test2').text('NOPE');
		         $("#btnAgregarProducto").prop("disabled",true);
		    }    
		});		


		$('#btnAgregarDocumentacionSoporte').click(function() {  // boton que aparece para agregar documentacion a pedidos especiales
			var form = document.createElement("form");
			form.method = 'post';
			form.action = 'documentacion_soporte.php';
			var input = document.createElement('input');
			input.type = "text";
			input.name = "data";
			input.value = $('#numero_pedido').text();
			form.appendChild(input);
			form.submit();
		}); //btnAgregarDocumentacionSoporte.click

		$('#btnAgregarProducto').click(function() {
			if (valida_multiplos($('#cantidad_solicitar').val(),$('#sp_producto_ue').text(), $("#tipo_pedido").val())) {
				if (valida_precio($('#precio_final').val(), $("#tipo_pedido").val())) {					
					var rowProducto = '<tr>';
					rowProducto = rowProducto+'<td class="text-center"><button title="" type="button" class="btn btn-danger btn-xs remove show_tip" data-original-title="Borrar de la lista"><span class="glyphicon glyphicon-trash"></span></button></td>';
					rowProducto = rowProducto+'<td>'+$('#sp_producto_upc').text()+'</td>';
					rowProducto = rowProducto+'<td>'+$('#sp_producto_codigo').text()+'</td>';
					rowProducto = rowProducto+'<td class="text-right">'+$('#cantidad_solicitar').val()+'</td>';
					rowProducto = rowProducto+'<td class="text-center">'+$('#sp_producto_ue').text()+'</td>';
					rowProducto = rowProducto+'<td>'+$('#sp_descripcion').text()+'</td>';
					rowProducto = rowProducto+'<td class="text-right">'+$('#sp_producto_precio').text()+'</td>';
					if ($("#tipo_pedido").val() == C_ID_PEDIDOS_ESPECIALES) {
						vPrecioFinal = $('#precio_final').val() * 1;
						rowProducto = rowProducto+'<td class="text-right">'+vPrecioFinal.toCurrency(4)+'</td>';
						vTotalLinea = $('#precio_final').val() * $('#cantidad_solicitar').val();
					} else {
						vTotalLinea = $('#sp_producto_precio').text().substr(1) * $('#cantidad_solicitar').val();
					}
					rowProducto = rowProducto+'<td class="text-right">'+vTotalLinea.toCurrency(2)+'</td>';
					rowProducto = rowProducto+'<td class="text-center"><button title="" type="button" class="btn btn-danger btn-xs remove show_tip" data-original-title="Borrar de la lista"><span class="glyphicon glyphicon-trash"></span></button></td>';
					rowProducto = rowProducto+'</tr>';
					var vTotalPedido = calculaTotalPedido(parseFloat($('#sp_totalpedido').text()), vTotalLinea);
					$('#sp_totalpedido').text(vTotalPedido.toFixed(2));
					$('#div_total_pedido').text(vTotalPedido.toCurrency(2));
					$("#myTable tbody").append(rowProducto);
		            $('#sp_producto_upc').text('');
		            $('#sp_producto_codigo').text('');
		            $('#sp_producto_ue').text('');
		            $('#sp_producto_precio').text('');
		            $('#sp_descripcion').text('');
		            $('#precio_final').val('');
		            $('#cantidad_solicitar').val('');
		            $('#cantidad_solicitar_empaque').val('');
		            $('.custom-combobox-input').val('');
				} else {
					$("#singleAdvice").stop(true, false).remove();
					$('#error_agregar').append('<span id="singleAdvice" style="display: inherit" class="advice alert alert-warning">' + 'Debe ingresar el precio del articulo.</span>')
	    			$("#singleAdvice").delay(3000).fadeOut(1500);
				}
			} else {
				$("#singleAdvice").stop(true, false).remove();
				$('#error_agregar').append('<span id="singleAdvice" style="display: inherit" class="advice alert alert-warning">' + 'Debe ingresar una cantidad multiplo de ['+$('#sp_producto_ue').text()+'].</span>')
    			$("#singleAdvice").delay(3000).fadeOut(1500);
			}
		}); //btnAgregarProducto.click

		$("table#myTable").on("click", ".remove", function () {
			var $row = $(this).closest('tr');
			if ($("#tipo_pedido").val() == C_ID_PEDIDOS_ESPECIALES) {
				vTotalLinea = $row[0].childNodes[8].textContent.substr(1);
			} else {
				vTotalLinea = $row[0].childNodes[7].textContent.substr(1);	
			}			
			var vTotalPedido = calculaTotalPedido(parseFloat($('#sp_totalpedido').text()), vTotalLinea.parseNumberToSave(), 'R');
			$('#sp_totalpedido').text(vTotalPedido);
			$('#div_total_pedido').text(vTotalPedido.toCurrency(4));
			$row.fadeOut(500, function(){
		    	$(this).closest('tr').remove();
			});
    	});

    	$("#btnGuardar").click( function() {
    		//alert(esConfirmacion);
			doWait();
    		if ((''==$('.custom-comboboxclientes-input').val()) ||
    			(''==$('#direccion_entrega').val()) ||
    			(''==$('#fecha_entrega').val()) ||
				('0'==$('#tipo_pedido').val())
			   ) {
	            	//swal("", "Ingrese todos los datos", "warning");
	          		//swal({text: "Ingrese todos los datos", type: "warning"});
					//swal({title: "",   text: "Ingrese todos los datos",   type: "warning",   showCancelButton: true,   confirmButtonColor: "#DD6B55",   confirmButtonText: "Yes, delete it!",   closeOnConfirm: false }, 
					swal({title: "", 
						  text: "Ingrese todos los datos",   
						  type: "warning",   
						  confirmButtonColor: "#DD6B55"});

    		} else {
	    		rows =$("table#myTable")[0].tBodies[0].rows.length;
	    		if (rows == 0 ) {
					swal({title: "", 
						  text: "Debe ingresar articulos a la orden",
						  type: "warning",
						  confirmButtonColor: "#DD6B55"});
	    		} else {  //si se puede guardar

	    			doWait();
					var rowsProductos = $("table#myTable")[0].tBodies[0].rows;
				    var nuevo_pedido = {
				      'id_pedido': $('#numero_pedido').text(),
				      'es_confirmacion': esConfirmacion,
				      'codigo_cliente': $('#sp_codigo_cliente').text(),
				      'nombre_cliente': $('#sp_nombre_cliente').text().parseSQLInjection(),
				      'tipo_pago': $('#tipo_pago').val(),
				      'direccion_entrega': $('#direccion_entrega').val().parseSQLInjection(),
				      'fecha_entrega': $('#fecha_entrega').val(),
				      'id_campania': $('#tipo_pedido').val(),
				      'observaciones': $('#observaciones').val().parseSQLInjection(),
				      'total_solicitado': $('#sp_totalpedido').text(),
				      'detalle': []
				    };

				    $.each(rowsProductos,function(index){
			      		nuevo_pedido.detalle.push({ 'upc': rowsProductos[index].cells[1].textContent, 'codigo_producto': rowsProductos[index].cells[2].textContent, 'cantidad_solicitada': rowsProductos[index].cells[3].textContent, 'unidades_empaque': rowsProductos[index].cells[4].textContent, 'descripcion': rowsProductos[index].cells[5].textContent, 'precio': rowsProductos[index].cells[6].textContent, 'precio_final': $("#tipo_pedido").val() == C_ID_PEDIDOS_ESPECIALES ? rowsProductos[index].cells[7].textContent : rowsProductos[index].cells[6].textContent, 'total_solicitado': $("#tipo_pedido").val() == C_ID_PEDIDOS_ESPECIALES ? rowsProductos[index].cells[8].textContent : rowsProductos[index].cells[7].textContent });
				    });

//console.log(JSON.stringify(nuevo_pedido));
//console.log(fixSQL($('#sp_nombre_cliente').text().parseSQLInjection()));
//console.log($('#sp_nombre_cliente').text());
				    
				    $.ajax({
				    		url: "grabar_pedido.php",
				            data: {n: 0, datos_pedido:JSON.stringify(nuevo_pedido)},
				            //data: {n: 0, datos_pedido:JSON(nuevo_pedido)},
				            //data: {n: 0, datos_pedido: nuevo_pedido},
				            type: "POST",
				            async: false,
				            dataType: "json",
				            cache:false,
				            success: function(data){
				            	doUnWait();
				            	//alert($('#tipo_pedido').val());
				              	// swal("Pedido # "+data, "", "success");
				              	if($('#tipo_pedido').val()=="1") {
				              		vtexto = "grabado con exito.  A continuacion por favor suba la documentacion de soporte";
				              	} else {
				              		vtexto = "grabado con exito !!!";
				              	}
								swal({title: "Pedido # "+data, 
									  text: vtexto,
									  type: "success",
									  closeOnConfirm: false },
									  function(){ 
						              	if($('#tipo_pedido').val()=="1") { //si es pedido especial pide doc.soporte
									  		//se hace un post atraves de un Form para no exponer el numero de pedido
											var form = document.createElement("form");
											form.method = 'post';
											form.action = 'documentacion_soporte.php';
											var input = document.createElement('input');
											input.type = "text";
											input.name = "data";
											input.value = data;
											form.appendChild(input);
											form.submit();

						              	} else {
										  	//location.reload(); 
									  		location = location.protocol + '//' + location.host + location.pathname;
						              	}
								});

				              	//console.log(data);
				              //alert('Items added');
				            },
				            error: function(e){
				            	doUnWait();
				            	//alert('hubo erro');
				            	//console.log(e.message);
				            	console.log(e.responseText);
								swal({title: "Alerta", 
									  text: e.responseText,
									  type: "error"});
				            }
				    });
				    
	    		}
    		}
        	doUnWait();
    	});  //btnGuardar.click
		     
    	$("#btnBorrar").click( function() {
    		BorrarPedido($('#numero_pedido').text());
    	});
        
        //calcula unidades al ingresar empaques y viceversa
    	$("#cantidad_solicitar_empaque").keyup( function() {
            v = this.value * $("#sp_producto_ue").text();
            $("#cantidad_solicitar").val(v);
    	});  
    	$("#cantidad_solicitar").keyup( function() {
            v = this.value / $("#sp_producto_ue").text();
            $("#cantidad_solicitar_empaque").val(v);
    	});  
        
        
	}  //Ingreso de pedidos

	// //Consulta de pedidos
	// if ($href.toLowerCase().indexOf("consulta.php") >= 0) {

 //    	function BorrarPedido {

	// 			swal({
	// 				title: "¿Seguro de borrar pedido # "+$('#numero_pedido').text(),
	// 				type: "warning", 
	// 				showCancelButton: true, 
	// 				confirmButtonText: "Si",
	// 				cancelButtonText: "No",
	// 				confirmButtonColor: "#DD6B55",
	// 				closeOnConfirm: false,
	// 				//closeOnCancel: false 
	// 			},
	// 				function(isConfirm) {
	// 		        	if(isConfirm){ 
	// 		        		pedido=$('#numero_pedido').text();
	// 					    $.ajax({
	// 					    		url: "borrar_pedido.php",
	// 					            data: {n: $('#numero_pedido').text()},
	// 					            type: "POST",
	// 					            //async: false,
	// 					            //dataType: "json",
	// 					            cache:false,
	// 					            success: function(data){
	// 					            	if(data==='1') {
	// 										swal({	title: "Pedido # "+pedido, 
	// 										  		text: "borrado con exito !!!",
	// 										  		type: "success",
	// 										  		closeOnConfirm: false 
	// 										  	},
	// 										  	function(){ 
	// 										  		//swal.close();											  		
	// 										  		location = location.protocol + '//' + location.host + location.pathname;
	// 										  	});
	// 					            	} else {
	// 										swal({	title: "No se pudo borrar", 
	// 										  		text: data,
	// 										  		type: "error",
	// 										  		closeOnConfirm: false 
	// 										  	},
	// 										  	function(){ 
	// 										  		swal.close();
	// 										  		//location.reload(); 
	// 										  	});						            		
	// 					            	}
	// 					              	console.log(data);
	// 					            },
	// 					            error: function(e){
	// 					            	doUnWait();
	// 					            	console.log(e.responseText);
	// 									swal({title: "Alerta", 
	// 										  text: e.responseText,
	// 										  type: "error"});
	// 					            }
	// 					    });
	// 					}  //if isConfirm
	// 				}  //function (swal)
	// 			); //swal
 //    	});  //btnBorrar.click


	// }  //Consulta de pedidos


});