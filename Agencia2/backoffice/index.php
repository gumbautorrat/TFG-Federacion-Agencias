<?php 

  include "../conf/appConf.php";
  include "../util/controlSesion.php";

  if(!ControlSesion::sesion_iniciada()){
  	header('Location: views/login.php');
  }

?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<title>Administración <?php echo TITULO ?></title>
	<link rel="stylesheet" href="../css/bootstrap.min.css">
	<link rel="stylesheet" href="../css/estilos.css">
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
				    <a href="" class="navbar-brand">Administración <?php echo TITULO ?></a>
				</div>
				<!-- Incia Menu -->
				<div class="collapse navbar-collapse" id="navegacion-fm">
					<ul class="nav navbar-nav">
						<li class="active"><a href="#">Inicio</a></li>
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button">
								Gestiones <span class="caret"></span>
							</a>
							<ul class="dropdown-menu" role="menu">
								<li class="divider"></li>
								<li><a href="views/operaciones/operaciones.php"><b>Operaciones</b></a></li>
								<li class="divider"></li>
								<li><a href="views/propietarios/propietarios.php"><b>Propietarios</b></a></li>
								<li class="divider"></li>
								<li><a href="#"><b>Inmuebles</b></a></li>
								<li class="divider"></li>
								<li><a href="#"><b>Clientes</b></a></li>
								<li class="divider"></li>
								<li><a href="#"><b>Demandas</b></a></li>
								<li class="divider"></li>
								<li><a href="#"><b>Agencias</b></a></li>
								<li class="divider"></li>
								<li><a href="views/usuarios/usuarios.php"><b>Usuarios</b></a></li>
								<li class="divider"></li>
								<li><a href="views/permisos/permisos.php"><b>Permisos</b></a></li>
								<li class="divider"></li>
							</ul>
						</li>
						<li class="#"><a href="#"></a></li>
					</ul>
					
					<span class="navbar-brand navbar-right"> usuari@ ( <?php echo ControlSesion::obtenerNomUsuario()." )" ?></span>
					<!--
					<form action="" class="navbar-form navbar-right" role="search">
						<div class="form-group">
							<input type="text" class="form-control" placeholder="buscar">
						</div>
						<button type="submit" class="btn btn-primary">
							<span class="glyphicon glyphicon-search"></span>
						</button>
					</form>
					-->

				</div>
			</div>
		</nav>

	</header>

	
	<section class="jumbotron">
		<div class="container">
			<center><h1 id="titulo-backoffice">Administración <?php echo TITULO ?></h1>
		</div>
	</section>


	<section class="main container">
		<div class="row">
			<section class="posts col-md-9 ">

				<div class="row">
					<div class="panel panel-default ">
		  				<div class="panel-heading"><center><b>Gestiones de Agencia</b></center></div>
		  			</div>
		  		</div>

		  		<div class="row">
		  			<div class="col-md-1"></div>
					<div class="panel panel-default col-md-4 btn-menu">
  						<div class="panel-body"><center><a href="views/operaciones/operaciones.php" class="col-btn-men"><b>Operaciones</b></a></center></div>
					</div>
					<div class="col-md-2"></div>
					<div class="panel panel-default col-md-4 btn-menu">
  						<div class="panel-body"><center><a href="views/propietarios/propietarios.php" class="col-btn-men"><b>Propietarios</b></a></center></div>
					</div>
					<div class="col-md-1"></div>
				</div>			

				<div class="row">
		  			<div class="col-md-1"></div>
					<div class="panel panel-default col-md-4 btn-menu ">
  						<div class="panel-body"><center><a href="#" class="col-btn-men"><b>Inmuebles</b></a></center></div>
					</div>
					<div class="col-md-2"></div>
					<div class="panel panel-default col-md-4 btn-menu">
  						<div class="panel-body"><center><a href="views/clientes/clientes.php" class="col-btn-men"><b>Clientes</b></a></center></div>
					</div>
					<div class="col-md-1"></div>
				</div>

				<div class="row">
		  			<div class="col-md-1"></div>
					<div class="panel panel-default col-md-4 btn-menu">
  						<div class="panel-body"><center><a href="#" class="col-btn-men"><b>Demandas</b></a></center></div>
					</div>
					<div class="col-md-2"></div>
					<div class="panel panel-default col-md-4 btn-menu">
  						<div class="panel-body"><center><a href="#" class="col-btn-men"><b>Agencias</b></a></center></div>
					</div>
					<div class="col-md-1"></div>
				</div>

				<div class="row">
		  			<div class="col-md-1"></div>
					<div class="panel panel-default col-md-4 btn-menu">
  						<div class="panel-body"><center><a href="views/usuarios/usuarios.php" class="col-btn-men"><b>Usuarios</b></a></center></div>
					</div>
					<div class="col-md-2"></div>
					<div class="panel panel-default col-md-4 btn-menu">
  						<div class="panel-body"><center><a href="views/permisos/permisos.php" class="col-btn-men"><b>Permisos</b></a></center></div>
					</div>
					<div class="col-md-1"></div>
				</div>			

				<div class="row" id="btn-salir">
		  			<div class="col-md-4"></div>
					
					<div class="panel panel-default col-md-4 btn-menu">
  						<div class="panel-body"><center><a href="../util/logout.php" class="col-btn-men"><b>Logout</b></a></center></div>
					</div>

					<div class="col-md-1"></div>
				</div>			

			</section>

			<aside class="col-md-3 hidden-xs hidden-sm">
		
				<div class="list-group">
					<center><a id="categorias" class="list-group-item active">Añadir</a>
					<a href="#" class="list-group-item"><b>Nueva Operación</b></a>
					<a href="#" class="list-group-item"><b>Nuevo Propietario</b></a>
					<a href="#" class="list-group-item"><b>Nuevo Inmueble</b></a>
					<a href="#" class="list-group-item"><b>Nuevo Cliente</b></a>
					<a href="#" class="list-group-item"><b>Nueva Demanda</b></a>
					<a href="#" class="list-group-item"><b>Nueva Agencia</b></a>
					<a href="#" class="list-group-item"><b>Nuevo Usuario</b></a>
					<a href="#" class="list-group-item"><b>Nuevo Permiso</b></a></center>
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

<script src="../js/jquery.js"></script>
<script src="../js/bootstrap.min.js"></script>
	
</body>
</html>