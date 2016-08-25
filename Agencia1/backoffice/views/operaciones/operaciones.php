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

		<title>Gestión de Operaciones</title>
		<meta http-equiv="content-type" content="text/html; charset=utf-8">
	    <link href="../../../css/bootstrap.min.css" rel="stylesheet">
	    <link href="../../../css/estilos_propietarios.css" rel="stylesheet">
	    <script src="../../../js/jquery.js"></script>	
		<script src="../../../js/bootstrap.min.js"></script>
		<script src="../../../js/paginador_personas.js"></script>
		<script src="../../../js/relocate.js"></script>

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
						<li class="active"><a href="#">Gestión Operaciones</a></li>
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
								if(isset($_POST['tipo_filtro'])){
									if($_POST['tipo_filtro'] == 'venta'){
										echo "<option value=\"venta\" selected=\"true\">Venta</option>";
									}else{
										echo "<option value=\"venta\">Venta</option>";
									}
									if($_POST['tipo_filtro'] == 'alquiler'){
										echo "<option value=\"alquiler\" selected=\"true\">Alquiler</option>";
									}else{
										echo "<option value=\"alquiler\">Alquiler</option>";
									}
									if($_POST['tipo_filtro'] == 'traspaso'){
										echo "<option value=\"traspaso\" selected=\"true\">Traspaso</option>";
									}else{
										echo "<option value=\"traspaso\">Traspaso</option>";
									}
									if($_POST['tipo_filtro'] == 'todas'){
										echo "<option value=\"todas\" selected=\"true\">Todas</option>";
									}else{
										echo "<option value=\"todas\">Todas</option>";
									}
								}else{ ?>

									<option value="venta">Venta</option>
									<option value="alquiler" >Alquiler</option>
									<option value="traspaso">Traspaso</option>
									<option value="traspaso">Todas</option>

						<?php   } ?>
						</select>
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
		<div class="panel-heading"><center><b>Listado de Operaciones</b><center></div>
		<div class="panel-body">
			<!--<table class="table table-striped table-hover ">-->
			<table class="table table-hover ">
				<thead>
				  <tr>
				  	<th>Id Operación</th>
				  	<th>Fecha</th>
				  	<th>Tipo</th>
				  	<th>Descripción</th>
				  	<!--<th>Id Agencia</th>-->
				  	<th>Precio</th>
				  	<th>Inmueble</th>
					<th>Propietario</th>
					<!--<th>Pro_SELF</th>-->
					<th>Cliente</th>
					<!--<th>Cli_SELF</th>-->
					
					<th>Compart/Ocult</th>
					<!--<th>Op_SELF</th>-->
					<!--<th id="eliminar"></th>-->
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
			if(isset($_POST['tipo_filtro'])){
				echo "var tipo_filtro = '".$_POST['tipo_filtro']."';";
				echo "var filtro = '';";
			}else{
				echo "var tipo_filtro = 'todos';";// no se filtra nada
				echo "var filtro = '';";
			}

			echo "main(user,passwd,tipo_filtro,filtro,agencia,4);";
			
		echo "</script>";

	?>

	<!-- Dialog -->
	<div id="dialog" title="Eliminar Usuario" style="display:none;">
    	<p id="mensaje"></p>
    </div>
    <!-- Dialog -->
		
	</body>
</html>