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

					var id_agencia = 1;
					var limit = 2;     //saca 2 registros 
					var offset = 0;    //comenzando desde el 0 (primer registro de los que devuelva la query)

					$.ajax({
					     data: {user: user, 
					     	    pass: passwd,
					     	    agencia: agencia,
					     		limit: limit,
					     		offset: offset},
	   					 url:  'http://apirest/clientes_paginado/' + id_agencia,
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
	    				 		$("#container ul").append('<li>'+ '-Error- No existen clientes para esa agencia </li>');
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
						$("#tabs").append('<li>'+ "ID CLIENTE: "+datos[i]["id_cliente"] + '</li>');
						$("#tabs").append('<li>'+ "ID AGENCIA: "+datos[i]["id_agencia"] + '</li>');
						$("#tabs").append('<li>'+ "DNI: "+datos[i]["dni"] + '</li>');
						$("#tabs").append('<li>'+ "NOMBRE: "+datos[i]["nombre"] + '</li>');
						$("#tabs").append('<li>'+ "PRIMER APELLIDO: "+datos[i]["primer_apellido"] + '</li>');
						$("#tabs").append('<li>'+ "SEGUNDO APELLIDO: "+datos[i]["segundo_apellido"] + '</li>');
						$("#tabs").append('<li>'+ "DIRECCION: "+datos[i]["direccion"] + '</li>');
						$("#tabs").append('<li>'+ "LOCALIDAD: "+datos[i]["localidad"] + '</li>');
						$("#tabs").append('<li>'+ "PROVINCIA: "+datos[i]["provincia"] + '</li>');
						$("#tabs").append('<li>'+ "TELEFONO: "+datos[i]["telefono"] + '</li>');
						$("#tabs").append('<li>'+ "EMAIL: "+datos[i]["email"] + '</li>');
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