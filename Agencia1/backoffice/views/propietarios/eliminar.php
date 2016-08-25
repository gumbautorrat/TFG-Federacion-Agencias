<?php

	include "../../../client/clientePropietarios.php";

	$user           = $_POST['user'];
	$pass           = $_POST['pass'];
	$agencia        = $_POST['agencia'];
	$id             = $_POST['id'];

	$client = new ClienteWSPropietarios();

	$respuestaJSON = $client->sendEliminarPropietario($user,$pass,$agencia,$id);

	echo $respuestaJSON;

?>