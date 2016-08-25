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
					
					var id_inmueble = 2;
					var id_tipo = 2;

					$.ajax({
					     data: {id_tipo: id_tipo},
	   					 url:  'http://apirest/inmueble/' + id_inmueble,
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
	    				 		$("#container ul").append('<li>'+ '-Error- El inmueble solicitado no existe en la BD </li>');
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

					$("#tabs").append('<li>'+ "DESCRIPCION CORTA: "+datos["descripcion_corta"] + '</li>');
					$("#tabs").append('<li>'+ "DESCRIPCION LARGA: "+datos["descripcion_larga"] + '</li>');
					$("#tabs").append('<li>'+ "DIRECCION: "+datos["direccion"] + '</li>');
					$("#tabs").append('<li>'+ "CODIGO POSTAL: "+datos["codigo_postal"] + '</li>');
					$("#tabs").append('<li>'+ "LOCALIDAD: "+datos["localidad"] + '</li>');
					$("#tabs").append('<li>'+ "PROVINCIA: "+datos["provincia"] + '</li>');
					$("#tabs").append('<li>'+ "PLANTA: "+datos["planta"] + '</li>');
					$("#tabs").append('<li>'+ "NUM METROS: "+datos["num_metros"] + '</li>');
					$("#tabs").append('<li>'+ "NUM HABITACIONES: "+datos["num_habitaciones"] + '</li>');
					$("#tabs").append('<li>'+ "NUM WC: "+datos["num_wc"] + '</li>');
					$("#tabs").append('<li>'+ "PRECIO: "+datos["precio"] + '</li>');
					$("#tabs").append('<li>'+ "FECHA: "+datos["fecha"] + '</li>');
					$("#tabs").append('<li>'+ "TIPO: "+datos["tipo"] + '</li>');
						
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