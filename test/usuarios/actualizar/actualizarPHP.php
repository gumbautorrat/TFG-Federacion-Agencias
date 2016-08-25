<?php

	include "clienteUsuarios.php";
	
	$cli = new ClienteWSUsuarios();

	$resultJSON = $cli->sendActualizarCliente('Fede','1234567','Federacion',4,2,'Antonia','73580994Q','Pelambre',
                                              'Contreras','Boloix','C/ Mandril','Paiporta','Valencia',
                                              '1704909','antonia@gmail.com');

	echo $resultJSON;

?>