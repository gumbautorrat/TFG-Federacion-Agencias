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
  			li span{
  			 	color: black;
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

					var id_agencia = 2;
					var limit = 2;     //saca 2 registros 
					var offset = 0;    //comenzando desde el 0 (primer registro de los que devuelva la query)

					$.ajax({
					     data: {user: user, 
					     	    pass: passwd,
					     	    agencia: agencia,
					     		limit: limit,
					     		offset: offset},
	   					 url:  'http://apirest/operaciones_paginado/' + id_agencia,
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
	    				 	}else{
	    				 		$("#container ul").append('<center><li>'+ '-Error- No existen inmuebles para esa agencia </li></center>');
	    				 	}
	    				 	
	    				 },

	    				 error: function (jqXHR, status) {            
              			 	// error handler
              			 	console.log(status);
	    				 	console.log(jqXHR);
	    				 	$("#container ul").append('<center><li>'+ jqXHR["responseText"] + '</li></center>');
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
						$("#tabs").append('<li>'+ "ID TIPO: <span>"+datos[i]["id_tipo"] + '</span></li>');
						$("#tabs").append('<li>'+ "ID CLIENTE: <span>"+datos[i]["id_cliente"] + '</span></li>');
						$("#tabs").append('<li>'+ "FECHA: <span>"+datos[i]["fecha"] + '</span></li>');
						$("#tabs").append('<li>'+ "PRECIO: <span><span>"+datos[i]["precio"] + '</span></li>');
						$("#tabs").append('<li>'+ "COMPARTIR: <span>"+datos[i]["compartir"] + '</span></li>');
						$("#tabs").append('<li>'+ "DISPONIBLE: <span>"+datos[i]["disponible"] + '</span></li>');
						$("#tabs").append('<li>'+ "FIANZA: <span>"+datos[i]["fianza"] + '</span></li>');
						$("#tabs").append('<li>'+ "RESERVA: <span>"+datos[i]["reserva"] + '</span></li>');
						$("#tabs").append('<li>'+ "TIEMPO MESES: <span>"+datos[i]["tiempo_meses"] + '</span></li>');
						$("#tabs").append('<li>'+ "DESCUENTO: <span>"+datos[i]["descuento"] + '</span></li>');
						$("#tabs").append('<li>'+ "ENTRADA: <span>"+datos[i]["entrada"] + '</span></li>');
						$("#tabs").append('<li>'+ "DESCRIPCION: <span>"+datos[i]["descripcion"] + '</span></li>');
						$("#tabs").append('<li>'+ "OBSERVACIONES: <span>"+datos[i]["observaciones"] + '</span></li>');
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
					<center><a class="list-group-item active"><h2>Test Listar las Operaciones de una Agencia (Paginado)</h2></a>
					<a href="#" class="list-group-item"><b>http://localhost/apirest_ope/operaciones/:id_agencia</b></a>
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