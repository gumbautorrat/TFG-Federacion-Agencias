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

					var id_usuario = 2;

					$.ajax({
					     data: {user: user, 
					     	    pass: passwd,
					     	    agencia: agencia},
	   					 url:  'http://apirest/usuario/' + id_usuario,
	    				 type: 'POST',

	    				 success: function(data, status, jqXHR) {
	    				 	// Do something with the result
	    				 	console.log(data);
	    				 	console.log(status);
	    				 	console.log(jqXHR);

	    				 	if(data["message"]){
	    				 		$("#container ul").append('<li>'+ '-Error- '+data["message"]+ '</li>');
	    				 	}else if(data) {
	    				 		mostrarResultado(data);
	    				 	}else{
	    				 		$("#container ul").append('<li>'+ '-Error- No existe ningún usuario con ese identificador </li>');
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

						$("#tabs").append('<li>'+ "ID USUARIO: "+datos["id_usuario"] + '</li>');
						$("#tabs").append('<li>'+ "USUARIO: "+datos["usuario"] + '</li>');
						$("#tabs").append('<li>'+ "ID AGENCIA: "+datos["id_agencia"] + '</li>');
						$("#tabs").append('<li>'+ "DNI: "+datos["dni"] + '</li>');
						$("#tabs").append('<li>'+ "NOMBRE: "+datos["nombre"] + '</li>');
						$("#tabs").append('<li>'+ "PRIMER APELLIDO: "+datos["primer_apellido"] + '</li>');
						$("#tabs").append('<li>'+ "SEGUNDO APELLIDO: "+datos["segundo_apellido"] + '</li>');
						$("#tabs").append('<li>'+ "DIRECCION: "+datos["direccion"] + '</li>');
						$("#tabs").append('<li>'+ "LOCALIDAD: "+datos["localidad"] + '</li>');
						$("#tabs").append('<li>'+ "PROVINCIA: "+datos["provincia"] + '</li>');
						$("#tabs").append('<li>'+ "TELEFONO: "+datos["telefono"] + '</li>');
						$("#tabs").append('<li>'+ "EMAIL: "+datos["email"] + '</li>');
						
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