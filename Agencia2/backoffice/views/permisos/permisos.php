<?php 

	include "../../../conf/appConf.php";
	include "../../../util/controlSesion.php";
	include "../../../client/clienteUsuarios.php";

?>

<?php 
	
	if(!ControlSesion::sesion_iniciada()){
		header('Location: ../../views/login.php');
	}

	//Obtenemos los usuarios para rellenar el comboBox
	$client = new ClienteWSUsuarios();
	$usersJSON = $client->sendGetUsers();
	$users = json_decode($usersJSON);

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
						
						<form role="form" method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>">
							
							<br>
								<div class="row"><div class="col-xs-2 col-sm-1 col-md-2"></div>
									<div class="col-xs-8 col-sm-10 col-md-4">
										<label class="control-label col-xs-4">Usuario:</label>
										<select id="usuario" class="form-control" name="usuario">
											<?php
												$option = "";
												foreach ($users as $user) {
													$option = "<option value=\"".$user->usuario."\"";
													if(strcmp(ControlSesion::obtenerNomUsuario(),$user->usuario) == 0){
														$option .=" selected";
													}
													$option .=">".$user->usuario."</option>";
													echo $option;
												}
											?>
										</select>
									</div>

									<div class="col-xs-8 col-sm-10 col-md-4">
										<label class="control-label col-xs-4">Tabla:</label>
										<select id="tabla" class="form-control" name="tabla">
											<option value="propietarios">propietarios</option>
											<option value="clientes">clientes</option>
									  		<option value="usuarios">usuarios</option>
										</select>
									</div>
								</div>
								
								<br>
								<br>
								<br>

								<div class="row">
									<div class="col-xs-2 col-sm-1 col-md-1"></div>
									<div class="col-xs-8 col-sm-10 col-md-10">         
									  <table class="table table-bordered">
									    <thead>
									      <tr>
									        <th><center>Permisos</center></th>
									        <th><center>Estado</center></th>
									  		<th><center>Modificar</center></th>
									      </tr>
									    </thead>
									    <tbody>
									      <tr>
									        <td><center>Insertar</center></td>
									        <td><center><span id="add-est"></span></center></td>
									        <td>
									        	<center>
										        	<button id="btn-add" type="button" class="btn btn-sm" value="add">
										        		<span id="add"></span>
										        	</button>
									        	</center>
									        </td>
									      </tr>
									      <tr>
									        <td><center>Actualizar</center></td>
									        <td><center><span id="upd-est"></span></center></td>
									        <td>
									        	<center>
										        	<button id="btn-upd" type="button" class="btn btn-sm" value="upd">
										        		<span id="upd" ></span>
										        	</button>
									        	</center>
									        </td>
									      </tr>
									      <tr>
									        <td><center>Eliminar</center></td>
									        <td><center><span id="del-est"></span></center></td>
									        <td>
									        	<center>
										        	<button id="btn-del" type="button" class="btn btn-sm" value="del">
										        		<span id="del" ></span>
										        	</button>
									        	</center>
									        </td>
									      </tr>
									      <tr>
									        <td><center>Listar</center></td>
									        <td><center><span id="list-est"></span></center></td>
									        <td>
									        	<center>
										        	<button id="btn-list" type="button" class="btn btn-sm" value="list">
										        		<span id="list" ></span>
										        	</button>
									        	</center>
									        </td>
									      </tr>
									    </tbody>
									  </table>
									</div>
								</div>
								
								<br>
								<div class="row"><div class="col-xs-2 col-sm-1 col-md-12"></div>
									<div class="col-md-1"></div>
									<div class="panel panel-default col-md-10 btn-menu">
				  						<div class="panel-body"><center><a href="../../index.php" class="col-btn-men"><b>Volver</b></a></center></div>
									</div>
								</div>

						</form>

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
<script src="../../../js/permisos.js"></script>
<!-- Dialog -->

<script>

<?php 

	  echo "var usuario = '".ControlSesion::obtenerNomUsuario()."';";
	  echo "var pasword = '".ControlSesion::obtenerPassword()."';";
	  echo "var agencia = '".ControlSesion::obtenerAgencia()."';"; 

	  echo "main();";

?>

</script>
	
</body>
</html>