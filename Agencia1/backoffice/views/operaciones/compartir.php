<?php

	include "../../../conf/appConf.php";
	include "../../../util/controlSesion.php";
	include "../../../client/clienteOperaciones.php";

	if(!ControlSesion::sesion_iniciada()){
  		header('Location: ../login.php');
  	}

	if(isset($_POST['user']) && isset($_POST['pass']) && 
	   isset($_POST['agencia']) && isset($_POST['compartir']) &&
	   isset($_POST['id_operacion'])){

		$user           = $_POST['user'];
		$pass           = $_POST['pass'];
		$agencia        = $_POST['agencia'];
		$compartir 		= $_POST['compartir'];
		$id_operacion   = $_POST['id_operacion'];

		$client = new ClienteWSOperaciones();

		$respuestaJSON = $client->sendCompartirOperacion($user,$pass,$agencia,$compartir,$id_operacion);

		echo $respuestaJSON;

	}

?>