
//Funcion que sirve para mostrar los dialogos que informan del resultado de alguna peticion al servicio web
function mostrarMensaje(mensaje,url_destino){
	$("#mensaje").text(mensaje);

	$( "#dialog" ).dialog({
        modal: true,
        buttons: {
            "Aceptar": function() {
                $( this ).dialog( "close" );
                window.location=url_destino; //redirigimos
            }
        }
    });
}

//Funcion especifica que no necesita redirigir
function mostrarMensajeEliminar(mensaje){
    $("#mensaje").text(mensaje);

    $( "#dialog" ).dialog({
        modal: true,
        buttons: {
            "Aceptar": function() {
                $( this ).dialog( "close" );
            }
        }
    });
}

function mostrarMensajePermisos(mensaje){
    mostrarMensajeEliminar(mensaje);
}

function mostrarMensajeOperaciones(mensaje){
    mostrarMensajeEliminar(mensaje);
}