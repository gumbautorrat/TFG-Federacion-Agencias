<?php 
header('Content-Type: text/html; charset=utf-8');
?>

<!DOCTYPE HTML>
<html>

  <head>
	    <meta charset="UTF-8">
	    <!-- jQuery -->
		<script src="http://code.jquery.com/jquery-2.2.1.min.js"></script>

		<script>

			var entrado = false;
			$(function(){
				$("#btn-1").on("click",function(){
					
					// Datos del usuario
					var user             = "Ana";
					var passwd           = "123456";
					var agencia          = "Agencia_A";

					// Datos de la demanda a actualizar
					//identificador
					var id_demanda = 2;

					//datos a actualizar
					var id_cliente  = 1;
					var descripcion = "Quiero el Caserioooooooooo";
					var compartir   = 1;

					$.ajax({
					     data: {user: user, 
					     	    pass: passwd,
					     	    agencia: agencia,
					     	    id_cliente: id_cliente,
					     		descripcion: descripcion,
					     		compartir: compartir},
	   					 url:  'http://apirest/demandas/actualizar/' + id_demanda,
	    				 type: 'PUT',

	    				 success: function(data, status, jqXHR) {
	    				 	// Do something with the result
	    				 	console.log(data);
	    				 	console.log(status);
	    				 	console.log(jqXHR);

	    				 	if(data["message"]){
	    				 		$("#container ul").append('<li>' +data["message"]+ '</li>');
	    				 	}
	    				 	
	    				 },

	    				 error: function (jqXHR, status) {            
              			 	// error handler
              			 	console.log(status);
	    				 	console.log(jqXHR);
	    				 	$("#container ul").append('<li>'+ jqXHR["responseText"] + '</li>');
         				 }
					});

				});
			});

		</script>
   </head>

  <body>
	<button type="button" id="btn-1">ENVIAR PETICION</button>

	<div id="container">
    <ul id="tabs">
       
    </ul>
	</div>

  </body>
</html>