<?php 

	include "../../../conf/appConf.php";
	include "../../../util/controlSesion.php";
	include "../../../client/clientePermisos.php";

?>

<?php 
	
	if(!ControlSesion::sesion_iniciada()){
		header('Location: ../../views/login.php');
	}

?>

<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<title>Administración <?php echo TITULO ?></title>
	<link rel="stylesheet" href="../../../css/bootstrap.min.css">
	<link rel="stylesheet" href="../../../css/estilos.css">
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
				    <a href="../../index.php" class="navbar-brand">Administración <?php echo TITULO ?></a>
				</div>
				<!-- Incia Menu -->
				<div class="collapse navbar-collapse" id="navegacion-fm">
					<ul class="nav navbar-nav">
						<li class="active"><a href="#">Gestión Permisos</a></li>
					</ul>
					<span class="navbar-brand navbar-right"> usuari@ ( <?php echo ControlSesion::obtenerNomUsuario()." )" ?></span>
				</div>
			</div>
		</nav>
	</header>

	<section class="jumbotron" id="jumblogin">
		<div class="container">
			<center><h1 id="titulo-backoffice">Administración <?php echo TITULO ?></h1>
		</div>
	</section>

	<br>

	<div class="container">
		<div class="row">
			<div class="col-sm-1 col-md-2 col-lg-3">
			</div>
			<div class="col-sm-10 col-md-8 col-lg-6">
				<div class="panel panel-default">
					<div class="panel-heading text-center">
						<h3>Gestión de Permisos</h3>
					</div>
					<div class="panel-body">
						
						<br>
						<form role="form" method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>">
							
							<br>
								<div class="row"><div class="col-xs-2 col-sm-1 col-md-1"></div>
									<div class="col-xs-8 col-sm-10 col-md-3">
										<label class="control-label col-xs-3">Usuario:</label>
										<select class="form-control" name="usuario">
											<option value="Ana">Ana</option>
											<option value="Benito">Benito</option>
									  		<option value="Fede">Fede</option>
										</select>
									</div>
									
									<div class="col-xs-8 col-sm-10 col-md-3">
										<label class="control-label col-xs-3">Tipo:</label>
										<select class="form-control" name="tipo">
											<option value="actualizar">Actualizar</option>
									  		<option value="eliminar">Eliminar</option>
										</select>
									</div>

									<div class="col-xs-8 col-sm-10 col-md-4">
										<label class="control-label col-xs-4">Tabla:</label>
										<select class="form-control" name="tabla">
											<option value="propietarios">propietarios</option>
											<option value="clientes">clientes</option>
									  		<option value="usuarios">usuarios</option>
										</select>
									</div>
								</div>

								<br>
								<br>
								
								<div class="col-xs-8 col-sm-10 col-md-5"></div>
								<label class="control-label col-xs-1">Acción:</label>
								<div class="row"><div class="col-xs-2 col-sm-1 col-md-12"></div>
									<div class="col-xs-8 col-sm-10 col-md-4"></div>
									<div class="col-xs-8 col-sm-10 col-md-3">
										<select class="form-control" name="accion">
											<option value="add">Añadir</option>
									  		<option value="del">Quitar</option>
										</select>
									</div><div class="col-xs-8 col-sm-10 col-md-3"></div>
									<div class="col-xs-8 col-sm-10 col-md-4">
										<button type="submit" name="login" id="btn-login" class="btn btn-lg <?php echo BTN_LOGIN_CLASS?> btn-block">
											Aceptar
										</button>
									</div>
								</div>
								<br>
								<br>
								<br>
								<div class="row"><div class="col-xs-2 col-sm-1 col-md-12"></div>
									<div class="col-md-1"></div>
									<div class="panel panel-default col-md-10 btn-menu">
				  						<div class="panel-body"><center><a href="../../index.php" class="col-btn-men"><b>Volver</b></a></center></div>
									</div>
								</div>

						</form>
						<br>

					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- Dialog -->
	<div id="dialog" title="Gestion de Permisos" style="display:none;">
    	<p id="mensaje"></p>
    </div>
    <!-- Dialog -->

<script src="../../../js/jquery.js"></script>
<script src="../../../js/bootstrap.min.js"></script>

<!-- Dialog -->
<link rel="stylesheet" href="../../../jquery-dialog/jquery-ui.css" />
<script src="../../../jquery-dialog/external/jquery/jquery.js"></script>
<script src="../../../jquery-dialog/jquery-ui.js"></script>
<script src="../../../jquery-dialog/dialog.js"></script>
<!-- Dialog -->

<?php

	//Recogemos los datos enviados 
	if(isset($_POST["login"]))
	{

		$user      = ControlSesion::obtenerNomUsuario();
	  	$pass      = ControlSesion::obtenerPassword();
	  	$agencia   = ControlSesion::obtenerAgencia();

		$usuario = $_POST["usuario"];

		$tabla   = $_POST["tabla"];
		$tipo    = $_POST["tipo"];
		$permiso = $tabla."/".$tipo;

		$accion = 0;
		if(strcmp($_POST["accion"],"add") == 0) $accion = 1;

		$cliente = new ClienteWSPermisos();

	  	//Hacemos la peticion al servicio web de propietarios y obtenemos la respuesta a la peticion de actualizacion en formato JSON
	    $resultadoJSON = $cliente->sendAddDelPermiso($user,$pass,$agencia,$usuario,$permiso,$accion);

	    //Convertimos la cadena JSON que nos ha devuelto el servicio web, en un objeto en el que podemos obtener el valor de los campos individualmente
		$resultado = json_decode($resultadoJSON);

		if(strcmp($resultado->message,"Autentication Failure") == 0){
			echo "<script>mostrarMensajePermisos(\"No tienes permisos para añadir/eliminar permisos a usuarios\");</script>";
		}elseif(strcmp($resultado->message,"NOK") == 0){
			echo "<script>mostrarMensajePermisos(\"No tienes permisos para añadir/eliminar permisos a usuarios de otra agencia\");</script>";
		}elseif(strcmp($resultado->message,"add OK") == 0){
			echo "<script>mostrarMensajePermisos(\"El permiso ha sido añadido correctamente\");</script>";
		}elseif(strcmp($resultado->message,"del OK") == 0){
			echo "<script>mostrarMensajePermisos(\"El permiso ha sido eliminado correctamente\");</script>";
		}elseif(strcmp($resultado->message,"add NOK") == 0){
			echo "<script>mostrarMensajePermisos(\"El usuario ya tenia el permiso que se le pretendía añadir\");</script>";
		}elseif(strcmp($resultado->message,"del NOK") == 0){
			echo "<script>mostrarMensajePermisos(\"El usuario no tenia el permiso que se le pretendía eliminar\");</script>";
		}else{
			echo "<script>mostrarMensajePermisos('".$resultado->message."');</script>";
		}

	}

?>
	
</body>
</html>