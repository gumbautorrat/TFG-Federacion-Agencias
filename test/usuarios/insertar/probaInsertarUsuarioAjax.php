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

					// Datos del usuario a insertar
					var id_agencia        = 1;
					var usuario			  = "Rigoberto";
                    var pass              = "S;SD:;AS:D;AS:D;A:SD;A:SD;";
                    var dni               = "73580994S";
                    var nombre            = "David";
                    var primer_apellido   = "Valdepeñas";
                    var segundo_apellido  = "Moncho";
                    var direccion 		  = "C/ Jaime I nº 26";
                    var localidad 		  = "Sueca";
                    var provincia 		  = "Valencia";
                    var telefono		  = "961704909";
                    var email             = "gumbautorrat@hotmail.com";

					$.ajax({
					     data: {user: user, 
					     	    passwd: passwd,
					     	    agencia: agencia,
					     	    id_agencia: id_agencia,
					     		usuario: usuario,
					     		pass: pass,
					     		dni: dni,
					     		nombre: nombre,
					     	    primer_apellido: primer_apellido,
					     	    segundo_apellido: segundo_apellido,
					     	    direccion: direccion,
					     	    localidad: localidad,
					     	    provincia: provincia,
					     	    telefono: telefono,
					     		email: email},
	   					 url:  'http://apirest/usuarios/insertar/',
	    				 type: 'POST',

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