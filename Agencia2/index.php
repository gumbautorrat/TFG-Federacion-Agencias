<?php 
	
	include "conf/appConf.php"; 

?>

<!DOCTYPE html>
<html lang="es">
<head>
	
	<!-- Especifico agencia 2 cambiar color paginador -->
	<style type="text/css">
	    
		.pagination>li.active>a:link {
			border: 1px solid orange;
	 		background: orange;
	 		color: white;
		}

		.pagination>li>a {
	 		background: orange;
	  		color: white;
		}
	    
	</style>

	<!-- Especifico agencia 2 cambiar color paginador -->

	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<title><?php echo TITULO ?></title>
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="css/estilos.css">

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
					<a href="index.php?tipo=todos" class="navbar-brand"><?php echo TITULO ?></a>
				</div>
				<!-- Incia Menu -->
				<div class="collapse navbar-collapse" id="navegacion-fm">
					<ul class="nav navbar-nav">
						<li class="active"><a>Inicio</a></li>
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button">
								Categorias <span class="caret"></span>
							</a>
							<ul class="dropdown-menu" role="menu">
								<li class="divider"></li>
								<li><a href="index.php?tipo=Venta" >Venta</a></li>
								<li class="divider"></li>
								<li><a href="index.php?tipo=Alquiler" >Alquiler</a></li>
								<li class="divider"></li>
								<li><a href="index.php?tipo=Traspaso" >Traspaso</a></li>
								<li class="divider"></li>
							</ul>
						</li>
						<li class="#"><a href="backoffice/index.php">Gestión Interna</a></li>
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
			<section class="posts col-md-9 ">

				<div class="panel panel-default">
					<?php  

						if(isset($_GET['tipo'])){
							if($_GET['tipo'] == "Venta"){
								echo "<div class=\"panel-heading\"><b>Últimos Inmuebles en Venta</b></div>";
							}elseif($_GET['tipo'] == "Alquiler"){
								echo "<div class=\"panel-heading\"><b>Últimos Inmuebles en Alquiler</b></div>";
							}elseif($_GET['tipo'] == "Traspaso"){
								echo "<div class=\"panel-heading\"><b>Últimos Inmuebles que se Traspasan</b></div>";
							}else{
								echo "<div class=\"panel-heading\"><b>Últimos Inmuebles a la venta, alquiler y traspaso</b></div>";
							}
						}else{
							echo "<div class=\"panel-heading\"><b>Últimos Inmuebles a la venta, alquiler y traspaso</b></div>";
						}
						
					?>
	  			</div>
				<div id="articulos">
				</div>
					<nav>
						<div class="center-block">
							<ul class="pagination">
							</ul>
						</div>
					</nav>
			</section>

			<aside class="col-md-3 hidden-xs hidden-sm">
				<div class="list-group">
					<center><a id="categorias" class="list-group-item active">Categorias</a></center>
					<a href="index.php?tipo=Venta" class="list-group-item">Venta</a>
					<a href="index.php?tipo=Alquiler" class="list-group-item">Alquiler</a>
					<a href="index.php?tipo=Traspaso" class="list-group-item">Traspaso</a>
				</div>
			</aside>
		</div>
	</section>

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

<script src="js/jquery.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/paginador.js"></script>

<?php 
	
	echo "<script>";
	echo "var agencia =".AGENCIA.";";

	if(isset($_GET['tipo'])){
		echo "var tipo = '".$_GET['tipo']."';";
	}else{
		echo "var tipo = '';";
	}

	echo "main(agencia,tipo);";
	echo "</script>";

?>
	
</body>
</html>  