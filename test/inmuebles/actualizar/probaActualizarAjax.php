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

					// id inmueble a actualizar
					var id_inmueble       = 7;

					// Datos del inmueble a insertar
					var id_agencia        = 1;
					var id_propietario    = 5;
					
                    var direccion 		  = "C/ Uchana I n 26";
                    var codigo_postal     = "46410";
                    var planta            = "2";
                    var localidad 		  = "Sueca";
                    var provincia 		  = "Valencia";

                    var desc_corta        = "Apartamento aseado";
                    var desc_larga        = "Apartamento aseado ubicado en el centro con muebles incluidos";
                    var num_wc            = "2";
                    var num_habita        = "5";
                    var num_metros        = "100";

					$.ajax({
					     data: {user: user, 
					     	    pass: passwd,
					     	    agencia: agencia,
					     	   	id_agencia: id_agencia,
					     	    id_propietario: id_propietario,
					     	    direccion: direccion,
					     	    codigo_postal: codigo_postal,
					     	    planta: planta,
					     	    localidad: localidad,
					     	    provincia: provincia,
					     	    desc_corta: desc_corta,
					     	    desc_larga: desc_larga,
					     	    num_wc: num_wc,
					     	    num_habita: num_habita,
					     	    num_metros: num_metros},
	   					 url:  'http://apirest/inmuebles/actualizar/' + id_inmueble,
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