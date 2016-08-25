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

					var id_cliente = 1;

					$.ajax({
					     data: {user: user, 
					     	    pass: passwd,
					     	    agencia: agencia},
	   					 url:  'http://apirest/demandas/' + id_cliente,
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
	    				 		$("#tabs").append('<li> NO EXISTEN DEMANDAS PARA ESE CLIENTE </li>');
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
					
					$("#tabs").append('<li>DEMANDAS DEL CLIENTE '+datos[0]["id_cliente"]+'</li><br/>');
					for(i = 0; i < datos.length; i++) {
						$("#tabs").append('<li>'+ "ID DEMANDA: "+datos[i]["id_demanda"] + '</li>');
						$("#tabs").append('<li>'+ "ID CLIENTE: "+datos[i]["id_cliente"] + '</li>');
						$("#tabs").append('<li>'+ "DESCRIPCION: "+datos[i]["descripcion"] + '</li>');
						$("#tabs").append('<li>'+ "COMPARTIR: "+datos[i]["compartir"] + '</li>');
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