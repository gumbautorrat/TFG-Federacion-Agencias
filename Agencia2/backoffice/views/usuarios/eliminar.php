<?php

	include "../../../client/clienteUsuarios.php";

	$user           = $_POST['user'];
	$pass           = $_POST['pass'];
	$agencia        = $_POST['agencia'];
	$id 			= $_POST['id'];

	$client = new ClienteWSUsuarios();

	$respuestaJSON = $client->sendEliminarUsuario($user,$pass,$agencia,$id);

	echo $respuestaJSON;

?>