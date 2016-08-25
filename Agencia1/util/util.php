<?php

// Funcion que formatea el precio poniendole un punto en los miles
function darFormato($precio) {

	$result = "";

	while(strlen($precio) > 3 ){
	 $result = '.' . substr($precio, strlen($precio) - 3) . $result;
	 $precio = substr($precio, 0,strlen($precio) - 3);
	}

	$result = $precio.$result;

	return $result;

}

?>