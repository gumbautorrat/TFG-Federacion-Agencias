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
					var user             = "Ana";
					var passwd           = "123456";
					var agencia          = "Agencia_A";

					//Datos de la operacion a eliminar
					var id_operacion   = 129;

					$.ajax({
					     data: {user: user, 
					     	    pass: passwd,
					     	    agencia: agencia},
	   					 url:  'http://apirest/operaciones/eliminar/' + id_operacion,
	    				 type: 'DELETE',

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
					<center><a class="list-group-item active"><h2>Test Eliminar Operación</h2></a>
					<a href="#" class="list-group-item"><b>http://localhost/apirest_ope/operaciones/eliminar/:id_operacion </b></a>
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