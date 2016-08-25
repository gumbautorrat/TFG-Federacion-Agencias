<?php 

  include "../../../conf/appConf.php";
  include "../../../util/controlSesion.php";
  include "../../../client/clienteUsuarios.php";

  if(!ControlSesion::sesion_iniciada()){
  	header('Location: views/login.php');
  }

  //Recogemos los datos del usuario seleccionado en la pag anterior
  if(isset($_GET["id"]) && isset($_GET["id_agencia"]) &&
  	 isset($_GET["usuario"]) && isset($_GET["nombre"]) && 
  	 isset($_GET["apell1"]) && isset($_GET["apell2"]) && 
  	 isset($_GET["direccion"]) &&isset($_GET["localidad"]) && 
  	 isset($_GET["provincia"]) &&isset($_GET["telefono"]) && 
  	 isset($_GET["email"]))
  {

        $id         = $_GET["id"];
        $id_agencia = $_GET["id_agencia"];
        $usuario    = $_GET["usuario"];
	  	$dni        = $_GET["dni"];
	  	$nombre     = $_GET["nombre"];
	  	$apell1     = $_GET["apell1"];
	  	$apell2     = $_GET["apell2"];
	  	$direccion  = $_GET["direccion"];
	  	$localidad  = $_GET["localidad"];
	  	$provincia  = $_GET["provincia"];
	  	$telefono   = $_GET["telefono"];
	  	$email      = $_GET["email"];

  }

?>

<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<title>Actualizar Usuario <?php echo TITULO ?></title>
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
						<li class="active"><a>Actualizar Usuario</a></li>
					</ul>
					<span class="navbar-brand navbar-right"> usuari@ ( <?php echo ControlSesion::obtenerNomUsuario()." )" ?></span>
				</div>
			</div>
		</nav>
	</header>
	
	<section class="jumbotron">
		<div class="container">
			<center><h1 id="titulo-backoffice">Administación <?php echo TITULO ?></h1>
		</div>
	</section>

	<section class="main container">
		<div class="row">
			<section class="posts col-md-11 ">

				<div class="row">
					<div class="col-xs-3"></div>
					<div class="col-xs-7">
						<div class="panel panel-default ">
							<div class="panel-heading"><center><h4>Actualizar Usuario</h4></center></div>
						</div>
		  			</div>
		  		</div>
		  		<br>
				
		  		<form method="post" id="form" class="form-horizontal" action="<?php echo $_SERVER['PHP_SELF'] ?>">
		  			<input type="hidden" name="inputId" value="<?php if(isset($id)) echo $id; ?>">
		  			<input type="hidden" name="inputIdAgencia" value="<?php if(isset($id_agencia)) echo $id_agencia; ?>">
				    <div class="form-group">
				        <label class="control-label col-xs-4">Dni:</label>
				        <div class="col-xs-5">
				            <input type="text" class="form-control" name="inputDni" id="inputDni" value="<?php if(isset($dni)) echo $dni; ?>" placeholder="Dni" readonly>
				        </div>
				    </div>
				    <div class="form-group">
				        <label class="control-label col-xs-4">Usuario:</label>
				        <div class="col-xs-5">
				            <input type="text" class="form-control" name="inputUsuario" id="inputUsuario" value="<?php if(isset($usuario)) echo $usuario; ?>" placeholder="Usuario" readonly>
				        </div>
				    </div>
				    <div class="form-group">
				        <label class="control-label col-xs-4">Nombre:</label>
				        <div class="col-xs-5">  <!-- onfocus="this.value = this.value;" sirve para que el cursor se ponga al final del texto que hay en el input -->
				            <input type="text" class="form-control" name="inputNombre" id="inputNombre" value="<?php if(isset($apell1)) echo $nombre; ?>" placeholder="Nombre" onfocus="this.value = this.value;" autofocus>
				        </div>
				    </div>
				    <div class="form-group">
				        <label class="control-label col-xs-4">1º Apellido:</label>
				        <div class="col-xs-5">
				            <input type="text" class="form-control" name="inputApell1" id="inputApell1" value="<?php if(isset($apell1)) echo $apell1; ?>" placeholder="1º Apellido">
				        </div>
				    </div>
				    <div class="form-group">
				        <label class="control-label col-xs-4">2º Apellido:</label>
				        <div class="col-xs-5">
				            <input type="text" class="form-control" name="inputApell2" id="inputApell2" value="<?php if(isset($apell2)) echo $apell2; ?>" placeholder="2º Apellido">
				        </div>
				    </div>
				    <div class="form-group">
				        <label class="control-label col-xs-4">Dirección:</label>
				        <div class="col-xs-5">
				            <textarea rows="3" class="form-control" name="inputDireccion" id="inputDireccion" placeholder="Dirección"><?php if(isset($direccion)) echo $direccion; ?></textarea>
				        </div>
				    </div>
				    <div class="form-group">
				        <label class="control-label col-xs-4">Localidad:</label>
				        <div class="col-xs-5">
				            <input type="text" class="form-control" name="inputLocalidad" id="inputLocalidad" value="<?php if(isset($localidad)) echo $localidad; ?>" placeholder="Localidad">
				        </div>
				    </div>
				    <div class="form-group">
				        <label class="control-label col-xs-4">Provincia:</label>
				        <div class="col-xs-5">
				            <input type="text" class="form-control" name="inputProvincia" id="inputProvincia" value="<?php if(isset($provincia)) echo $provincia; ?>" placeholder="Provincia">
				        </div>
				    </div>
				    <div class="form-group">
				        <label class="control-label col-xs-4" >Teléfono:</label>
				        <div class="col-xs-5">
				            <input type="tel" class="form-control" name="inputTelefono" id="inputTelefono"value="<?php if(isset($telefono)) echo $telefono; ?>" placeholder="Teléfono">
				        </div>
				    </div>
				    <div class="form-group">
        				<label class="control-label col-xs-4">Email:</label>
        				<div class="col-xs-5">
            				<input type="email" class="form-control" name="inputEmail" id="inputEmail" value="<?php if(isset($email)) echo $email; ?>" placeholder="Email">
        				</div>
    				</div>
				    
				    <br>
				    <div class="form-group">
				        <div class="col-xs-offset-4 col-xs-9">
				            <input type="submit" name="actualizar" class="btn <?php echo BTN_ACTUALIZAR_CLASS ?>" value="Enviar" onclick="abrir_dialog()">
				            <input id="btn-limpiar" class="btn btn-default" value="Limpiar" onclick="limpiar(document)" >
				            <!--<input id="btn-limpiar" class="btn btn-default" value="Limpiar" onclick="abrir_dialog()" >-->
				        </div>
				    </div>
				</form>
				
				<div class="row">
					<div class="col-xs-2"></div>
					<div class="col-xs-8">
						<div class="page-header"></div>
					</div>
				</div>

			</section>
		</div>
	</section>
	
	<!-- Dialog -->
	<div id="dialog" title="Actualizar Cliente" style="display:none;">
    	<p id="mensaje"></p>
    </div>
    <!-- Dialog -->

<script src="../../../js/jquery.js"></script>
<script src="../../../js/bootstrap.min.js"></script>
<script src="../../../js/actualizar.js"></script>

<!-- Dialog -->
<link rel="stylesheet" href="../../../jquery-dialog/jquery-ui.css" />
<script src="../../../jquery-dialog/external/jquery/jquery.js"></script>
<script src="../../../jquery-dialog/jquery-ui.js"></script>
<script src="../../../jquery-dialog/dialog.js"></script>
<!-- Dialog -->

<?php

//Si se ha enviado el formulario para actualizar el cliente
if(isset($_POST['actualizar'])){

  	$id         = $_POST["inputId"];
  	$id_agencia = $_POST["inputIdAgencia"];
  	$usuario    = $_POST["inputUsuario"];
	$dni        = $_POST["inputDni"];
  	$nombre     = $_POST["inputNombre"];
  	$apell1     = $_POST["inputApell1"];
  	$apell2     = $_POST["inputApell2"];
  	$direccion  = $_POST["inputDireccion"];
  	$localidad  = $_POST["inputLocalidad"];
  	$provincia  = $_POST["inputProvincia"];
  	$telefono   = $_POST["inputTelefono"];
  	$email      = $_POST["inputEmail"];

  	$user      = ControlSesion::obtenerNomUsuario();
  	$pass      = ControlSesion::obtenerPassword();
  	$agencia   = ControlSesion::obtenerAgencia();

  	$cliente = new ClienteWSUsuarios();

  	//Hacemos la peticion al servicio web de usuarios y obtenemos la respuesta a la peticion de actualizacion en formato JSON
  	$resultadoJSON = $cliente->sendActualizarCliente($user,$pass,$agencia,$id,$id_agencia,$usuario,$dni,
                                          			 $nombre,$apell1,$apell2,$direccion,$localidad,$provincia,
                                          			 $telefono,$email);

    //Convertimos la cadena JSON que nos ha devuelto el servicio web, en un objeto en el que podemos obtener el valor de los campos individualmente
	$resultado = json_decode($resultadoJSON);

	//Donde se redireccionara despues de mostrar el mensaje que corresponda 
	$url_destino = "usuarios.php";

	if(strcmp($resultado->message,"Operation Succesful") == 0){
		echo "<script>mostrarMensaje(\"El usuario ha sido actualizado correctamente\",'".$url_destino."');</script>";
	}elseif(strcmp($resultado->message,"Autentication Failure") == 0){
		echo "<script>mostrarMensaje(\"No tienes permisos para actualizar usuarios o el usuario que intentas actualizar no es de tu agencia\",'".$url_destino."');</script>";
	}else{
		echo "<script>mostrarMensaje('".$resultado->message."','".$url_destino."');</script>";
	}

}

?>

</body>
</html>