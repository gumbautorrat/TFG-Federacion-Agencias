<?php 

	header('Content-Type: text/html; charset=utf-8');
	include "../client/clienteInmuebles.php";
	include "../util/util.php";
	include "../conf/appConf.php";

	//Desde la pagina principal nos pasan el tipo de operacion y el inmueble del que queremos obtener el detalle
	if(isset($_GET['tipo']) && isset($_GET['id_inmueble'])){

		$id_tipo = $_GET['tipo'];
		$id_inmueble = $_GET['id_inmueble'];

		//Creamos el objeto que hace las peticiones al servicio web de inmuebles
		$client = new ClienteWSInmuebles();
		//Hacemos la peticion al servicio web de inmuebles y obtenemos el detalle del inmueble solicitado en formato JSON
		$inmuebleDetalleJSON = $client->sendGetDetalleInmueble($id_inmueble,$id_tipo);
		$fotosInmuebleJSON   = $client->sendGetfotosInmueble($id_inmueble);

		//Convertimos la cadena JSON que nos ha devuelto el servicio web, en un objeto en el que podemos obtener el valor de los campos individualmente
		$inmDetalle = json_decode($inmuebleDetalleJSON);
		$fotosInmueble = json_decode($fotosInmuebleJSON);

		//Si alguien pone valores en la url que lleva a esta pagina y no se devuelve ningún resultado con esos valores redirigimos a la página principal
		if($inmuebleDetalleJSON == 'false'){
			header('Location: ../index.php');
		}

	}else{ //Devolvemos a quien intente acceder a la url sin los parametros validos a la pagina principal
		header('Location: ../index.php');
	}
	
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<title><?php echo TITULO ?></title>
	<link rel="stylesheet" href="../css/bootstrap.min.css">
	<link rel="stylesheet" href="../css/estilos.css">
	<script src="../js/jquery.js"></script>
	<script src="../js/bootstrap.min.js"></script>	
	
	<!-- Css del slider -->
    <script src="../sliderengine/jquery.js"></script>
    <script src="../sliderengine/amazingslider.js"></script>
    <script src="../sliderengine/initslider-1.js"></script>
    
</head>
<body>
	<header>
		<nav class="navbar navbar-inverse navbar-static-top" role="navigation">
			<div class="container">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navegacion-fm">
						<span class="sr-only">Desplegar / Ocultar Menu</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a href="../index.php" class="navbar-brand"><?php echo TITULO ?></a>
				</div>
				<!-- Incia Menu -->
				<div class="collapse navbar-collapse" id="navegacion-fm">
					<ul class="nav navbar-nav">
						<li class="active"><a>Detalle Inmueble</a></li>
					</ul>
				</div>
			</div>
		</nav>

	</header>

	<section class="jumbotron">
		<div class="container">
			<center><h1 id="titulo"><?php echo TITULO ?></h1>
			<p id="subtitulo">Encuentra tu inmueble</p><center>
		</div>
	</section>

	<section class="main container">
		<div class="row">
			<section class="posts col-md-2 "></section>
			<section class="posts col-md-8 ">
				<br><br><div class="panel panel-default">
					<div class="panel-heading desctitulo"><center><?php echo $inmDetalle->descripcion ?></center></div>
				</div>
			</section>
		</div>
	</section>

	<section class="container">
		<div class="row">
			<section class="posts col-md-2 "></section>
			<section class="posts col-md-4 ">
				<p><span class=\"post-fecha\">Publicado el <b><?php echo $inmDetalle->fecha ?></b></span></p>
			</section>
		</div>
		<div class="row">
			<section class="posts col-md-2 "></section>
			<section class="posts col-md-4 ">
				<p><span class=\"post-fecha\">Situado en <b><?php echo $inmDetalle->direccion.", ".$inmDetalle->localidad.", ".$inmDetalle->provincia ?></b></span></p>
			</section>
		</div>
	</section><br>

	<section class="container ">
		<div class="row">
			<section class="posts col-md-2 "></section>
			<section class="col-md-3 ">
				<div class="miga-de-pan datos_piso">
					<ol class="breadcrumb">
						<li><b><?php echo $inmDetalle->num_metros ?></b>m<sup>2</sup></li>
						<li><b><?php echo $inmDetalle->num_habitaciones ?></b> Hab.</li>
						<li><b><?php echo $inmDetalle->num_wc ?></b> Baños</li>
					</ol>
				</div>
			</section>
			<section class="posts col-md-0 "></section>
		</div>
	</section>
	
	<section class="container">
		<div class="row">
			<section class="posts col-md-2 "></section>
			<section class="col-md-3 ">
				<a style="CURSOR: default" class="btn btn-success tipo_detalle">Precio de <?php echo $inmDetalle->tipo ?> <b><?php echo darFormato($inmDetalle->precio) ?> €</b></a>
			</section>
		</div><br><br>
	</section>

	<section class="container">
		<div class="row">
			<section class="posts col-md-2 "></section>
			<section class="posts col-md-8 ">
			<p class="post-contenido text-justify desctitulo">Descripción</p>
				<p id="descripcion" class="post-contenido text-justify"><?php echo $inmDetalle->descripcion_larga ?></p>
			</section>
		</div>
	</section>

	<!-- Comienzo de slider -->
    <div id="amazingslider-1" style="display:block;position:relative;margin:16px auto 48px;margin-bottom:100px">
        <ul class="amazingslider-slides" style="display:none;">
        	<?php

				if($fotosInmuebleJSON != 'false'){ 
					foreach ($fotosInmueble as $foto) {
						echo "<li><img src=\"".$foto->url."\" alt=\"".$foto->descripcion."\" /></li>";
					}
				}

        	?>
        </ul>
    </div>				
	<!-- Fin de slider -->

	<footer class="footer navbar-inverse navbar-static-bottom">
	    <div class="container">
		    <div class="row" id="footer">
				<div class="col-xs-6">
					<p id="nombre">Autor - juavalm1@eui.upv.es</p>
				</div>
				<div class="col-xs-6">
					<ul class="list-inline text-right">
						<li><a href="">Inicio</a></li>
						<li><a href="">Contacto</a></li>
					</ul>
				</div>
			</div>
	    </div>
    </footer>
	
</body>
</html>