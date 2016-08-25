<?php 
	
	include "../../../conf/appConf.php";
	include "../../../util/controlSesion.php";

	if(!ControlSesion::sesion_iniciada()){
  		header('Location: ../login.php');
  	}

?>
<!DOCTYPE html>
<html>
	<head>

		<title>Gestión de Clientes</title>
		<meta http-equiv="content-type" content="text/html; charset=utf-8">
	    <link href="../../../css/bootstrap.min.css" rel="stylesheet">
	    <link href="../../../css/estilos_propietarios.css" rel="stylesheet">
	    <script src="../../../js/jquery.js"></script>	
		<script src="../../../js/bootstrap.min.js"></script>
		<script src="../../../js/paginador_personas.js"></script>

		<!-- Dialog -->
		<link rel="stylesheet" href="../../../jquery-dialog/jquery-ui.css" />
		<script src="../../../jquery-dialog/external/jquery/jquery.js"></script>
		<script src="../../../jquery-dialog/jquery-ui.js"></script>
		<script src="../../../jquery-dialog/dialog.js"></script>
		<!-- Dialog -->
	
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
						<li class="active"><a href="#">Gestión Clientes</a></li>
						<!--<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button">
								Gestiones <span class="caret"></span>
							</a>
							<ul class="dropdown-menu" role="menu">
								<li class="divider"></li>
								<li><a href="views/operaciones/operaciones.php"><b>Operaciones</b></a></li>
								<li class="divider"></li>
								<li><a href="views/propietarios/propietarios.html"><b>Propietarios</b></a></li>
								<li class="divider"></li>
								<li><a href="#"><b>Inmuebles</b></a></li>
								<li class="divider"></li>
								<li><a href="#"><b>Clientes</b></a></li>
								<li class="divider"></li>
								<li><a href="#"><b>Demandas</b></a></li>
								<li class="divider"></li>
								<li><a href="#"><b>Agencias</b></a></li>
								<li class="divider"></li>
								<li><a href="#"><b>Usuarios</b></a></li>
								<li class="divider"></li>
								<li><a href="#"><b>Permisos</b></a></li>
								<li class="divider"></li>
							</ul>
						</li>-->
						<li class="#"><a href="#"></a></li>
					</ul>

					<form action="" class="navbar-form navbar-right col-md-3 hidden-xs hidden-sm" role="search" method="post">
						<select class="form-control" name="tipo_filtro">
							<?php 
								if(isset($_POST['tipo_filtro']) && isset($_POST['valor_filtro'])){
									if($_POST['tipo_filtro'] == 'id'){
										echo "<option value=\"id\" selected=\"true\">Id</option>";
									}else{
										echo "<option value=\"id\">Id</option>";
									}
									if($_POST['tipo_filtro'] == 'dni'){
										echo "<option value=\"dni\" selected=\"true\">Dni</option>";
									}else{
										echo "<option value=\"dni\">Dni</option>";
									}
									if($_POST['tipo_filtro'] == 'nombre'){
										echo "<option value=\"nombre\" selected=\"true\">Nombre</option>";
									}else{
										echo "<option value=\"nombre\">Nombre</option>";
									}
									if($_POST['tipo_filtro'] == 'apell1'){
										echo "<option value=\"apell1\" selected=\"true\">Primer Apellido</option>";
									}else{
										echo "<option value=\"apell1\">Primer Apellido</option>";
									}
									if($_POST['tipo_filtro'] == 'apell2'){
										echo "<option value=\"apell2\" selected=\"true\">Segundo Apellido</option>";
									}else{
										echo "<option value=\"apell2\">Segundo Apellido</option>";
									}
									if($_POST['tipo_filtro'] == 'direccion'){
										echo "<option value=\"direccion\" selected=\"true\">Direccion</option>";
									}else{
										echo "<option value=\"direccion\">Direccion</option>";
									}
									if($_POST['tipo_filtro'] == 'localidad'){
										echo "<option value=\"localidad\" selected=\"true\">Localidad</option>";
									}else{
										echo "<option value=\"localidad\">Localidad</option>";
									}
									if($_POST['tipo_filtro'] == 'provincia'){
										echo "<option value=\"provincia\" selected=\"true\">Provincia</option>";
									}else{
										echo "<option value=\"provincia\">Provincia</option>";
									}
									if($_POST['tipo_filtro'] == 'telefono'){
										echo "<option value=\"telefono\" selected=\"true\">Telefono</option>";
									}else{
										echo "<option value=\"telefono\">Telefono</option>";
									}
									if($_POST['tipo_filtro'] == 'email'){
										echo "<option value=\"email\" selected=\"true\">Email</option>";
									}else{
										echo "<option value=\"email\">Email</option>";
									}
								}else{ ?>

									<option value="id">Id</option>
									<option value="dni">Dni</option>
									<option value="nombre" >Nombre</option>
							  		<option value="apell1">Primer Apellido</option>
							  		<option value="apell2">Segundo Apellido</option>
							  		<option value="direccion">Direccion</option>
							  		<option value="localidad">Localidad</option>
							  		<option value="provincia">Provincia</option>
							  		<option value="telefono">Telefono</option>
							  		<option value="email">Email</option>

						<?php   } ?>
						</select>
						<div class="form-group">
							<?php 
								/*if(isset($_POST['tipo_filtro']) && isset($_POST['valor_filtro'])){ 
									echo "<input type=\"text\" class=\"form-control\" placeholder=\"filtrar\" name=\"valor_filtro\" value=\"".$_POST['valor_filtro']."\" autofocus>";
							    }else{
									echo "<input type=\"text\" class=\"form-control\" placeholder=\"filtrar\" name=\"valor_filtro\" autofocus>";
								}*/
							?>
							<input type="text" class="form-control" placeholder="filtrar" name="valor_filtro" autofocus>
						</div>
						<button type="sutmit" class="<?php echo SEARCH_CLASS ?>">
							<span class="glyphicon glyphicon-search"></span>
						</button>
					</form>
				</div>
			</div>
		</nav>

	</header>

	<section class="jumbotron">
		<div class="container">
			<center><h1 id="titulo-backoffice">Administración <?php echo TITULO ?></h1>
		</div>
	</section>
		
	<div class="<?php echo TABLA_CLASS ?>" id="main"> 
		<div class="panel-heading"><center><b>Listado de Clientes</b><center></div>
		<div class="panel-body">
			<!--<table class="table table-striped table-hover ">-->
			<table class="table table-hover ">
				<thead>
				  <tr>
				  	<th>Id</th>
				  	<th>Id Agencia</th>
					<th>Dni</th>
					<th>Nombre</th>
					<th>1º Apellido</th>
					<th>2º Apellido</th>
					<th>Dirección</th>
					<th>Localidad</th>
					<th>Provincia</th>
					<th>Telefono</th>
					<th>Email</th>
					<th id="actualizar"></th>
					<th id="eliminar"></th>
				  </tr>
				</thead>
				<tbody id="miTabla">

				</tbody>
			</table>

			<div class="col-md-12 text-center">
				<ul class="pagination" id="paginador"></ul>
			</div>

		</div>

		<div class="row">
			<div class="col-md-4"></div>
				<div class="panel panel-default col-md-4 btn-menu">
	  			<div class="panel-body"><center><a href="../../index.php" class="col-btn-volver col-btn-men"><b>Volver</b></a></center></div>
			</div>
		</div>
	</div>

	<?php 
	
		echo "<script>";

			echo "var user = '".ControlSesion::obtenerNomUsuario()."';";
			echo "var passwd = '".ControlSesion::obtenerPassword()."';";
			echo "var agencia = '".ControlSesion::obtenerAgencia()."';";

			//Si hemos llegado a esta pagina pulsando el boton de la lupa (filtro)
			if(isset($_POST['tipo_filtro']) && isset($_POST['valor_filtro'])){
				echo "var tipo_filtro = '".$_POST['tipo_filtro']."';";
				echo "var filtro = '".$_POST['valor_filtro']."';";
			}else{
				echo "var tipo_filtro = 'todos';";// no se filtra nada
				echo "var filtro = '';";
			}

			echo "main(user,passwd,tipo_filtro,filtro,agencia,2);";
			
		echo "</script>";

	?>

	<!-- Dialog -->
	<div id="dialog" title="Eliminar Cliente" style="display:none;">
    	<p id="mensaje"></p>
    </div>
    <!-- Dialog -->
		
	</body>
</html>