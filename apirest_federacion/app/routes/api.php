<?php

header('Access-Control-Allow-Origin: *'); //Para permitir conexiones Ajax desde otro dominio diferente

if(!defined("SPECIALCONSTANT")) die("Acceso denegado"); //Para evitar que se acceda directamente sin pasar por index.php

include "webservice_conf.php";
include "api_agencias.php";
include "api_clientes.php";
include "api_demandas.php";
include "api_fotos.php";
include "api_inmuebles.php";
include "api_operaciones.php";
include "api_permisos.php";
include "api_personas.php";
include "api_propietarios.php";
include "api_tipo_operacion.php";
include "api_usuarios.php";

/***********************************************************************************************

	Funcion que se encarga de determinar si un usuario concreto del Sistema esta o no autorizado 
	para ejecutar un Servicio

************************************************************************************************/

function controlAccessUserToWS($user,$agencia,$pass,$name_ws){

	 try{
	 	//Obtenemos el hash del usuario
		$dbHash = getHash($user,$agencia);

		//Si nos han devuelto el hash
		if($dbHash){
			//Comprobamos que la contraseña de entrada combinada con el hash del usuario y el mismo hash sean iguales
			if(strcmp(crypt($pass, $dbHash), $dbHash) == 0){
				//Comprobamos que tenga permisos para realizar la operacion y devolvemos el resultado
				return havePermission($user,$name_ws);
			}else{
				return 0;
			}
		}else{
			return 0;
		}
	}
	catch(PDOException $e)
	{
		throw $e;
	}

}

/***********************************************************************************************

	Funcion que recibe el nombre de usuario del usuario y la agencia a la que pertenece
	y nos devuelve el hash de su clave en el caso de que este existiera en la BD.

************************************************************************************************/

function getHash($user,$agencia){

    try{

		$connection = getConnectionAgencia();
		//Lamada a procedimiento almacenado
		$query = "CALL authUsuario(?,?)";
		$stmt = $connection->prepare($query);
		$stmt->bindParam(1, $user);
		$stmt->bindParam(2, $agencia);
		$stmt->execute();
		$dbHash = $stmt->fetchcolumn();
		$connection = null;

		return $dbHash;

	}
	catch(PDOException $e)
	{
		throw $e;
	}

}

/***********************************************************************************************

	Funcion que recibe el nombre de usuario y el servicio que se dispone a ejecutar,
	y nos retorna si dicho usuario tiene o no permisos para ejecutar el servicio.

************************************************************************************************/

function havePermission($user,$name_ws){

 	try{

		$connection = getConnectionAgencia();
		//Lamada a funcion almacenada
		$query = "SELECT checkPermisos(?,?) as result";
		$stmt  = $connection->prepare($query);
		$stmt ->bindParam(1, $user);
		$stmt ->bindParam(2, $name_ws);
		$stmt ->execute();
		$res = $stmt ->fetch();
		$connection = null;

		return $res['result'] == 1;

	}
	catch(PDOException $e)
	{
		throw $e;
	}

}

/***********************************************************************************************

	Funcion que comprueba si el hash que le pasamos existe en la BD

************************************************************************************************/

function hashExists($dbHash,$pass){
	//Si el hash no es vacio
	if($dbHash){
		//Comprobamos que la contraseña de entrada combinada con el hash del usuario y el mismo hash sean iguales
		if(strcmp(crypt($pass, $dbHash), $dbHash) == 0){
			return 1;
		}else{
			return 0;
		}
	}else{
		return 0;
	}
}