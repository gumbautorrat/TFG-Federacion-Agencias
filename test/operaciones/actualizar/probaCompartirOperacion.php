<?php 
header('Content-Type: text/html; charset=utf-8');
?>

<!DOCTYPE HTML>
<html>

  <head>
	    <meta charset="UTF-8">
		<link rel="stylesheet" href="../../css/bootstrap.min.css">
		<script src="../../js/jquery.js"></script>
		<script src="../../js/bootstrap.min.js"></script>

		<style type="text/css">
	  			 ul  { 
		  			 	 list-style:none;
		  			 	 color: #045FB4;
		  			 	 margin-left: -45px;
	  			     }
  	         	 .lab{
			     	     margin-left: 55px;
  			         }
	  		     .inp {
						 margin-left: 25px;
	  			     }
		</style>
		<script>

			var entrado = false;
			$(function(){
				$("#btn-1").on("click",function(){
					
					// Datos del usuario
					var user          = $("#usu").val();
					var passwd        = $("#pass").val();
					var agencia       = $("#agen").val();

					// id operacion a actualizar
					var id_operacion  = $("#id_op").val();

					// Datos de la operacion a compartir
					var compartir     = $('#chck:checked').val();

					if(compartir != 1){
						compartir = 0;
					}

					$.ajax({
					     data: {user: user, 
					     	    pass: passwd,
					     	    agencia: agencia,
					     		compartir: compartir},
	   					 url:  'http://apirest/operaciones/compartir/' + id_operacion,
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
		<div class="main container">
			<div class="row">
			<div class="col-md-2"></div>
			<div class="col-md-8">
				<div class="list-group">
					<center><a class="list-group-item active"><h2>Test Compartir Operación</h2></a>
					<a class="list-group-item"><b>http://localhost/apirest_ope/operaciones/compartir/:id_operacion </b></a>
					<a class="list-group-item">
						
						<div class="form-group">	
							<div class="checkbox">
								<div class="col-md-4"></div>
								<div class="container">
								<div class="row">
								
								<div class="col-md-2">
									<span class="lab"><b>Usuario:</b></Span>
									<input id="usu" type="text" class="form-control inp" value="">
									<span class="lab"><b>Password:</b></Span>
									<input id="pass" type="text" class="form-control inp" value="">
									<span class="lab"><b>Agencia:</b></Span>
									<input id="agen" type="text" class="form-control inp" value="">
								</div>
								</div>
								</div>
							</div>
						</div>
						
					</a>

					<a class="list-group-item">
						
						<div class="form-group">	
							<div class="checkbox">
								<div class="col-md-4"></div>
								<div class="container">
								<div class="row">
								
								<div class="col-md-2">
									<span class="lab"><b>Id Operacion:</b></Span>
									<input id="id_op" type="text" class="form-control inp" value="">
								</div>

								</div>
								</div>
							</div>
						</div>
						
					</a>
					<a class="list-group-item">
						<center>
						<div class="form-group">	
							<div class="checkbox">
								<label><input id="chck" type="checkbox" value="1"><b>Compartir</b></label>
							</div>
						</div>
						</center>
					</a>
					<a class="list-group-item"><b><button type="button" id="btn-1">ENVIAR PETICION</button></b></a>
					<a class="list-group-item"><b></b></a>
					<a class="list-group-item"><b>Resultado</b></a>
					<a class="list-group-item"><b><div id="container"><ul id="tabs"></ul></div></b></a></center>
				</div>
			</div>
			</div>
		</div>
    </body>
    
</html>

