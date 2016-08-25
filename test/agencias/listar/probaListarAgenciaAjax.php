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

					var id_agencia = 2;

					$.ajax({
					     data: {user: user, 
					     	    pass: passwd,
					     	    agencia: agencia},
	   					 url:  'http://apirest/agencias/' + id_agencia,
	    				 type: 'POST',

	    				 success: function(data, status, jqXHR) {
	    				 	// Do something with the result
	    				 	console.log(data);
	    				 	console.log(status);
	    				 	console.log(jqXHR);

	    				 	if(data["message"]){
	    				 		$("#container ul").append('<li>'+ '-Error- '+data["message"]+ '</li>');
	    				 	}else if((data)) {
	    				 		mostrarResultado(data);
	    				 	}else{
	    				 		$("#container ul").append('<li>'+ '-Error- La agencia no existe </li>');
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

			function mostrarResultado(datos){
				if(!entrado){
					entrado = true;

						$("#tabs").append('<li>'+ "ID AGEN: "+datos["id_agencia"] + '</li>');
						$("#tabs").append('<li>'+ "NOMBRE: "+datos["nombre"] + '</li>');
						$("#tabs").append('<li>'+ "DIRECCION: "+datos["direccion"] + '</li>');
						$("#tabs").append('<li>'+ "LOCALIDAD: "+datos["localidad"] + '</li>');
						$("#tabs").append('<li>'+ "PROVINCIA: "+datos["provincia"] + '</li>');
						$("#tabs").append('<li>'+ "TELEFONO: "+datos["telefono"] + '</li>');
						$("#tabs").append('<li>'+ "EMAIL: "+datos["email"] + '</li>');
						$("#tabs").append('<li>'+ "***************" + '</li>');

				}
			}

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