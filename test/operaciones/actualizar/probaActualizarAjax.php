<?php 
header('Content-Type: text/html; charset=utf-8');
?>

<!DOCTYPE HTML>
<html>

  <head>
	    <meta charset="UTF-8">
		<script src="http://code.jquery.com/jquery-2.2.1.min.js"></script>
		<link rel="stylesheet" href="../../css/bootstrap.min.css">
		<script src="../../js/jquery.js"></script>
		<script src="../../js/bootstrap.min.js"></script>

		<style type="text/css">
  			 ul{ 
  			 	 list-style:none;
  			 	 color: #045FB4;
  			 	 margin-left: -45px;
  			   }
		</style>
		<script>

			var entrado = false;
			$(function(){
				$("#btn-1").on("click",function(){
					
					// Datos del usuario
					var user              = "Ana";
					var passwd            = "123456";
					var agencia           = "Agencia_A";

					// id operacion a actualizar
					var id_operacion      = 119;

					// Datos de la operacion a insertar
					// La fecha no se actualiza dado que a una operacion se le asigna la fecha del momento en la que esta es creada.
					// En el caso de querer modificar la fecha, podemos optar por borrar la operación y crear una nueva.
					var id_agencia        = 1;
					var id_inmueble       = 2;
					var id_tipo           = 5;
					var id_cliente        = 1;
					var precio            = 350;
					var compartir         = 1;
					var disponible        = 1;
					var fianza            = 123.34;
					var reserva           = 200.43;
					var tiempo_meses      = 12;
					var descuento         = 10.4;
					var entrada           = 2300.4;
					var descripcion       = 'DESCRIPCION ACTUALIZADA';
					var observaciones     = 'OBSERVACION ACTUALIZADA';

					$.ajax({
					     data: {user: user, 
					     	    pass: passwd,
					     	    agencia: agencia,
					     	    id_agencia: id_agencia,
					     		id_inmueble: id_inmueble,
					     		id_tipo: id_tipo,
					     		id_cliente: id_cliente,
					     		precio: precio,
					     		compartir: compartir,
					     		disponible: disponible,
					     		fianza: fianza,
					     		reserva: reserva,
					     		tiempo_meses: tiempo_meses,
					     		descuento: descuento,
					     		entrada: entrada,
					     		descripcion: descripcion,
					     		observaciones: observaciones},
	   					 url:  'http://apirest/operaciones/actualizar/' + id_operacion,
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
	  	<br>
	  	<br>
		<div class="main container">
			<div class="row">
			<div class="col-md-2"></div>
			<div class="col-md-8">
				<div class="list-group">
					<center><a class="list-group-item active"><h2>Test Actualizar Operación</h2></a>
					<a href="#" class="list-group-item"><b>http://localhost/apirest_ope/operaciones/actualizar/:id_operacion </b></a>
					<a href="#" class="list-group-item"><b><button type="button" id="btn-1">ENVIAR PETICION</button></b></a>
					<a href="#" class="list-group-item"><b></b></a>
					<a href="#" class="list-group-item"><b>Resultado</b></a>
					<a href="#" class="list-group-item"><b><div id="container"><ul id="tabs"></ul></div></b></a></center>
				</div>
			</div>
			</div>
		</div>
    </body>
    
</html>