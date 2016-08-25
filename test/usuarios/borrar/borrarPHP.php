<?php

	include "clienteUsuarios.php";
	
	$cli = new ClienteWSUsuarios();

	//$resultJSON = $cli->sendEliminarUsuario('Ana','123456','Agencia_A',4);
	//$resultJSON = $cli->sendEliminarUsuario('Benito','123456','Agencia_B',4);
	$resultJSON = $cli->sendEliminarUsuario('Fede','1234567','Federacion',4);

	echo $resultJSON;

?>