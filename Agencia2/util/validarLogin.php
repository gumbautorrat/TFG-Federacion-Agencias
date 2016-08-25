<?php 

	include "../../client/clienteUsuarios.php";
	
	const EXITO_AUTENTIFICACION = 1;
	const ERROR_AUTENTIFICACION = 2;      
	const ERROR_CAMPOS_VACIOS   = 3;
	
	// Funcion que gestiona si un usuario se logea correctamente en el sistema
	function validarLogin($usuario,$pass,$agencia){
		// Si los campos usuario y pass no estan vacios
		if(variableIniciada($usuario) && variableIniciada($pass)){
			//Creamos el objeto que hace las peticiones al servicio web de usuarios
			$client = new ClienteWSUsuarios();

			// Hacemos la peticion al Servicio Web y obtenemos la respuesta en formato JSON
			$resultadoLoginJSON = $client->sendGetLogin($usuario,$pass,$agencia);
			
			//Convertimos la cadena JSON que nos ha devuelto el Servicio Web, en un objeto en el que podemos obtener el valor de los campos individualmente
			$resultadoLogin = json_decode($resultadoLoginJSON);

			if(isset($resultadoLogin->result)){

				if($resultadoLogin->result == 'true'){
					return EXITO_AUTENTIFICACION;
				}else{
					return ERROR_AUTENTIFICACION;
				}

			}else{
				//Devolvemos el mensaje de error enviado por el Servicio Web
				if(isset($resultadoLogin->message))
					return $resultadoLogin->message;

			}
			
		}else{
			return ERROR_CAMPOS_VACIOS;
		}
	}
	
	// Funcion que comprueba que una variable exista y que no sea vacia 
	function variableIniciada($variable){
		if(isset($variable) && !empty($variable)){
			return true;
		}else{
			return false;
		}
	}

?>