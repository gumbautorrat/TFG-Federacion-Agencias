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
  			 ul{ 
  			 	 list-style:none;
  			 	 color: #045FB4;
  			 	 margin-left: -45px;
  			   }
  			li span{
  			 	color: black;
  			  }
		</style>

		<script>

			var entrado = false;
			$(function(){
				$("#btn-1").on("click",function(){
					
					// Datos del usuario
					var user             = "Benito";
					var passwd           = "123456";
					var agencia          = "Agencia_B";

					$.ajax({
					     data: {user: user, 
					     	    pass: passwd,
					     	    agencia: agencia},
	   					 url:  'http://apirest/operaciones_paginado_tipo/',
	    				 type: 'POST',

	    				 success: function(data, status, jqXHR) {
	    				 	// Do something with the result
	    				 	console.log(data);
	    				 	console.log(status);
	    				 	console.log(jqXHR);

	    				 	if(data["message"]){
	    				 		$("#container ul").append('<center><li>'+ '-Error- '+data["message"]+ '</li></center>');
	    				 	}else if((data[0])) {
	    				 		mostrarResultado(data);
	    				 	}
	    				 	
	    				 },

	    				 error: function (jqXHR, status) {            
              			 	// error handler
              			 	console.log(status);
	    				 	console.log(jqXHR);
	    				 	$("#container ul").append('<center><li>'+ jqXHR["responseText"] + '</li><center>');
         				 }
					});

				});
			});

			function mostrarResultado(datos){
				if(!entrado){
					entrado = true;

					for(i = 0; i < datos.length; i++) {

						$("#tabs").append('<li>'+ "ID OPERACION: <span>"+datos[i]["id_operacion"] + '</span></li>');
						$("#tabs").append('<li>'+ "ID AGENCIA: <span>"+datos[i]["id_agencia"] + '</span></li>');
						$("#tabs").append('<li>'+ "ID INMUEBLE: <span>"+datos[i]["id_inmueble"] + '</span></li>');
						$("#tabs").append('<li>'+ "ID PROPIETARIO: <span>"+datos[i]["id_propietario"] + '</span></li>');
						$("#tabs").append('<li>'+ "PROPIETARIO PROPIO (1=SI,0=NO): <span>"+datos[i]["pro_self"] + '</span></li>');
						$("#tabs").append('<li>'+ "ID CLIENTE: <span>"+datos[i]["id_cliente"] + '</span></li>');
						$("#tabs").append('<li>'+ "CLIENTE PROPIO (1=SI,0=NO): <span>"+datos[i]["cli_self"] + '</span></li>');
						$("#tabs").append('<li>'+ "TIPO DE OPERACION: <span>"+datos[i]["tipo"] + '</span></li>');
						$("#tabs").append('<li>'+ "PRECIO: <span><span>"+datos[i]["precio"] + '</span></li>');
						$("#tabs").append('<li>'+ "FECHA: <span>"+datos[i]["fecha"] + '</span></li>');
						$("#tabs").append('<li>'+ "COMPARTIR: <span>"+datos[i]["compartir"] + '</span></li>');
						$("#tabs").append('<li>'+ "OPERACION PROPIA (1=SI,0=NO): <span>"+datos[i]["op_self"] + '</span></li>');
						$("#tabs").append('<li>'+ "***********************************************************************************************************" + '</li>');

					}

				}
			}

		</script>
   </head>

  <body>
	  	<br>
	  	<br>
		<div class="main container">
			<div class="row">
			<div class="col-md-3"></div>
			<div class="col-md-6">
				<div class="list-group">
					<center><a class="list-group-item active"><h2>Test Listar Todas las Operaciones propias y compartidas</h2></a>
					<a href="#" class="list-group-item"><b>http://apirest/operaciones_paginado_tipo/</b></a>
					<a href="#" class="list-group-item"><b><button type="button" id="btn-1">ENVIAR PETICION</button></b></a>
					<a href="#" class="list-group-item"><b></b></a>
					<a href="#" class="list-group-item"><b>Resultado</b></a></center>
					<a href="#" class="list-group-item"><div id="container"><b><ul id="tabs"></ul></b></div></a>
				</div>
			</div>
			</div>
		</div>
	</body>

</html>