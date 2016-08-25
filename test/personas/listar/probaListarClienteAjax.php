<?php 
header('Content-Type: text/html; charset=utf-8');
?>

<!DOCTYPE HTML>
<html>

  <head>
  		<title>TEST DATOS PERSONA</title>
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

					//Persona de la que queremos recuperar los datos
					var dni = "78499484S";

					$.ajax({
					     data: {user: user, 
					     	    pass: passwd,
					     	    agencia: agencia},
	   					 url:  'http://apirest/personas/' + dni,
	    				 type: 'POST',

	    				 success: function(data, status, jqXHR) {
	    				 	// Do something with the result
	    				 	console.log(data);
	    				 	console.log(status);
	    				 	console.log(jqXHR);

	    				 	//Si la peticion nos devuelve false , es porque cuando el SW hace la consulta el select no le ha devuelto ningún registro.
	    				 	//O sea que no existe ninguna persona con el dni que le hemos pasado por parámetro a la petición
	    				 	if(data == false){
	    				 		$("#container ul").append('<li>'+ 'No existe ninguna persona con ese dni'+ '</li>');
	    				 	}

	    				 	if(data["message"]){
	    				 		$("#container ul").append('<li>'+ '-Error- '+data["message"]+ '</li>');
	    				 	}else if((data)) {
	    				 		mostrarResultado(data);
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
  	<h2>TEST OBTENER DATOS PERSONA</h2>
	<button type="button" id="btn-1">ENVIAR PETICION</button>

	<div id="container">
    <ul id="tabs">
       
    </ul>
	</div>

  </body>
</html>