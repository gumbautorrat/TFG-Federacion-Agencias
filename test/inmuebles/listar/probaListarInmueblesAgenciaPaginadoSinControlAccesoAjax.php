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
					
					var id_agencia = 1;
					var limit = 2;     //saca 2 registros 
					var offset = 0;    //comenzando desde el 0 (primer registro de los que devuelva la query)

					$.ajax({
					     data: {limit: limit,
					     		offset: offset},
	   					 url:  'http://apirest/inmuebles_paginado/' + id_agencia,
	    				 type: 'POST',

	    				 success: function(data, status, jqXHR) {
	    				 	// Do something with the result
	    				 	console.log(data);
	    				 	console.log(status);
	    				 	console.log(jqXHR);

	    				 	if(data["message"]){
	    				 		$("#container ul").append('<li>'+ '-Error- '+data["message"]+ '</li>');
	    				 	}else if((data[0])) {
	    				 		mostrarResultado(data);
	    				 	}else{
	    				 		$("#container ul").append('<li>'+ '-Error- No existen inmuebles para esa agencia </li>');
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

					for(i = 0; i < datos.length; i++) {
						$("#tabs").append('<li>'+ "ID INMUEBLE: "+datos[i]["id_inmueble"] + '</li>');
						$("#tabs").append('<li>'+ "ID PROPIETARIO: "+datos[i]["id_propietario"] + '</li>');
						$("#tabs").append('<li>'+ "ID AGENCIA: "+datos[i]["id_agencia"] + '</li>');
						$("#tabs").append('<li>'+ "DIRECCION: "+datos[i]["direccion"] + '</li>');
						$("#tabs").append('<li>'+ "COD POSTAL: "+datos[i]["codigo_postal"] + '</li>');
						$("#tabs").append('<li>'+ "PLANTA: "+datos[i]["planta"] + '</li>');
						$("#tabs").append('<li>'+ "LOCALIDAD: "+datos[i]["localidad"] + '</li>');
						$("#tabs").append('<li>'+ "PROVINCIA: "+datos[i]["provincia"] + '</li>');
						$("#tabs").append('<li>'+ "DESC CORTA: "+datos[i]["descripcion_corta"] + '</li>');
						$("#tabs").append('<li>'+ "DESC LARGA: "+datos[i]["descripcion_larga"] + '</li>');
						$("#tabs").append('<li>'+ "NUM WC: "+datos[i]["num_wc"] + '</li>');
						$("#tabs").append('<li>'+ "NUM HABITACIONES: "+datos[i]["num_habitaciones"] + '</li>');
						$("#tabs").append('<li>'+ "NUM METROS: "+datos[i]["num_metros"] + '</li>');	
						$("#tabs").append('<li>'+ "***************" + '</li>');
					}

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