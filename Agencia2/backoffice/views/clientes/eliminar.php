<?php

	include "../../../client/clienteClientes.php";

	$user           = $_POST['user'];
	$pass           = $_POST['pass'];
	$agencia        = $_POST['agencia'];
	$id 			= $_POST['id'];

	$client = new ClienteWSClientes();

	$respuestaJSON = $client->sendEliminarCliente($user,$pass,$agencia,$id);

	echo $respuestaJSON;

?>