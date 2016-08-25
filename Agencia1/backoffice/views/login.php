<?php 

	include "../../conf/appConf.php";
	include "../../util/validarLogin.php";
	include "../../util/controlSesion.php";

?>

<?php 
	
	// Si estamos logeados y intentamos acceder al login nos redirige a index.php (del backoffice)
	if(ControlSesion::sesion_iniciada()){
		header('Location: ../index.php');
	}

?>

<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<title>Administración <?php echo TITULO ?></title>
	<link rel="stylesheet" href="../../css/bootstrap.min.css">
	<link rel="stylesheet" href="../../css/estilos.css">
</head>
<body>

	<section class="jumbotron" id="jumblogin">
		<div class="container">
			<center><h1 id="titulo-backoffice">Administración <?php echo TITULO ?></h1>
		</div>
	</section>

	<br>
	<br>
	<br>

	<div class="container">
		<div class="row">
			<div class="col-sm-1 col-md-2 col-lg-3">
			</div>
			<div class="col-sm-10 col-md-8 col-lg-6">
				<div class="panel panel-default">
					<div class="panel-heading text-center">
						<h4>Iniciar sesión</h4>
					</div>
					<div class="panel-body">
						
						<br>
						<form role="form" method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>">
							<center><h2>Introduce tus datos de Acceso</h2></center>
							<br>
							<br>
								<div class="row"><div class="col-xs-2 col-sm-1 col-md-1"></div>
									<div class="col-xs-8 col-sm-10 col-md-10">
										<input type="text" name="usuario" id="intput-usuario" class="form-control" placeholder="Usuario" 
											   <?php  
											   		//para que al pulsar iniciar sesion no borre el contenido del campo usuario
											   		if(isset($_POST['login'])){ 
											   			echo 'value="'.$_POST['usuario'].'"';
											   		}
											   ?>
											   required autofocus>
										<br>
									</div>
								</div>
								<div class="row"><div class="col-xs-2 col-sm-1 col-md-1"></div>
									<div class="col-xs-8 col-sm-10 col-md-10">
										<input type="password" name="pass" id="input-pass" class="form-control" placeholder="Contraseña" required>
										<br>
									</div>
								</div>
								<div class="row"><div class="col-xs-2 col-sm-1 col-md-1"></div>
									<div class="col-xs-8 col-sm-10 col-md-10">

										<?php 

											/* Modo agencia    = El usuario no ha pulsado el checkbox de modo federacion y si es logeado correctamente
											                     los datos a los que acceda seran exclusivos de dicha agencia. 
											   Modo federacion = El usuario ha pulsado el checkbox de modo federacion y si es logeado correctamente 
																 los datos a los que acceda seran de todas la agencias. */

											// Si hemos pulsado el boton de login
											if(isset($_POST['login'])){ 

												$agencia = NOMBRE_AGENCIA; // Por defecto ponemos que accede en modo agencia

												if(isset($_POST['typeAccess'])){ // Si pretendemos acceder en modo federacion (checkbox seleccionado)
													$agencia = NOMBRE_FEDERACION;
												}
												
												// Llamada a funcion que gestiona si nos hemos logeado correctamente y nos devuelve el resultado
												$resultado = validarLogin($_POST['usuario'],$_POST['pass'],$agencia);

												if($resultado == EXITO_AUTENTIFICACION){
													// Inicializamos las variables de sesion y redirigimos al menu principal del backoffice
													ControlSesion::iniciar_sesion($_POST['usuario'],$_POST['pass'],$agencia);
													header('Location: ../index.php');
												}elseif($resultado == ERROR_AUTENTIFICACION){ ?>
													<!-- Mostramos un mensaje de error -->
													<div class="alert alert-danger alert-dismissable">
  														<button type="button" class="close" data-dismiss="alert">&times;</button>
  														<strong>¡Error! </strong>Tu usuario o contraseña no son correctos.
													</div>
										<?php	}elseif($resultado == ERROR_CAMPOS_VACIOS){ ?>
													<div class="alert alert-warning alert-dismissable">
  														<button type="button" class="close" data-dismiss="alert">&times;</button>
  														<strong>¡Atención! </strong>Completa los campos vacíos.
													</div>
										<?php	}else{ ?>
													<div class="alert alert-danger alert-dismissable">
  														<button type="button" class="close" data-dismiss="alert">&times;</button>
  														<strong>¡Error! </strong><?php echo $resultado; ?>
													</div>
										<?php	}

											} 

										?>
										
									</div>
								</div>
								<div class="row"><div class="col-xs-2 col-sm-1 col-md-1"></div>
									<div class="col-xs-8 col-sm-10 col-md-10">
										<button type="submit" name="login" id="btn-login" class="btn btn-lg <?php echo BTN_LOGIN_CLASS?> btn-block">
											Iniciar sesión
										</button>
									</div>
								</div>
								<br>
								<div class="row"><div class="col-xs-2 col-sm-1 col-md-1"></div>
									<div class="col-xs-8 col-sm-10 col-md-10">
										<center><label class="checkbox-inline"><input type="checkbox" name="typeAccess" value="1"><b>Modo Federación</b></label></center>
									</div>
								</div>
						</form>
						<br>

					</div>
				</div>
			</div>
		</div>
	</div>

<script src="../../js/jquery.js"></script>
<script src="../../js/bootstrap.min.js"></script>
	
</body>
</html>