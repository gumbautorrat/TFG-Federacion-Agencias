<?php

if(!defined("SPECIALCONSTANT")) die("Acceso denegado"); 

/*****************************************************************************************************

	Funcion que recibe peticiones POST a la url http://apirest/personas/insertar/
	y hace un insert de un cliente en la BD

	Param Entrada: La informacion del usuario que lanza el servicio y los datos de la persona
    a insertar.

    Param Salida: Mensaje JSON que nos informa del exito o error de la operacion.


********************************************************************************************************/

$app->post("/personas/insertar/", function() use($app)
{

	$NOM_SERVICIO = "personas/insertar";
	//datos del usuario a autenticar
	$user = $app->request->post("user");
	$pass = $app->request->post("pass");
	$agencia = $app->request->post("agencia");

	//datos de la persona a insertar
	$dni      		  = $app->request->post("dni");
	$nombre           = $app->request->post("nombre");
	$primer_apellido  = $app->request->post("primer_apellido");
	$segundo_apellido = $app->request->post("segundo_apellido");
	$direccion        = $app->request->post("direccion");
	$localidad        = $app->request->post("localidad");
	$provincia        = $app->request->post("provincia");
	$telefono         = $app->request->post("telefono");
	$email            = $app->request->post("email");
	
	try{

		$app->response->headers->set("Content-type", "application/json");
		$app->response->status(200);

		//Obtenemos si el usuario tiene permiso para ejecutar el servicio
		$acceso = controlAccessUserToWS($user,$agencia,$pass,$NOM_SERVICIO);

		if($acceso){//Autenticacion de usuario y permisos correcto

			$connection = getConnectionAgencia();
			//Lamada a procedimiento almacenado
			$query = "CALL crearPersona(?,?,?,?,?,?,?,?,?)";
			$stmt = $connection->prepare($query);
			$stmt->bindParam(1, $dni);
			$stmt->bindParam(2, $nombre);
			$stmt->bindParam(3, $primer_apellido);
			$stmt->bindParam(4, $segundo_apellido);
			$stmt->bindParam(5, $direccion);
			$stmt->bindParam(6, $localidad);
			$stmt->bindParam(7, $provincia);
			$stmt->bindParam(8, $telefono);
			$stmt->bindParam(9,$email);
			$stmt->execute();
			$connection = null;
			
   			$app->response->body(json_encode(array("status" => "Ok", "message" => "Operation Succesful")));
   		}else{
   			//Fallo en la Autenticacion de usaurio o de permisos
   			$app->response->body(json_encode(array("status" => "Ok", "message" => "Autentication Failure")));
   		}

	}
	catch(PDOException $e)
	{
		$app->response->body(json_encode(array("status" => "Ok", "message" => " PDOException ".$e->getMessage())));
	}
	catch(Exception $e)
	{
		$app->response->body(json_encode(array("status" => "Ok", "message" => " Exception ".$e->getMessage())));
	}
	
});

/*****************************************************************************************************

	Funcion que recibe peticiones PUT a la url http://apirest/personas/actualizar/
	y actualiza los datos de una persona en la BD

	Param Entrada: La informacion del usuario que lanza el servicio y los datos de la persona
    a actualizar.

    Param Salida: Mensaje JSON que nos informa del exito o error de la operacion.


********************************************************************************************************/

$app->put("/personas/actualizar/:dni", function($dni) use($app)
{

	$NOM_SERVICIO = "personas/actualizar";
	//datos del usuario a autenticar
	$user = $app->request->post("user");
	$pass = $app->request->post("pass");
	$agencia = $app->request->post("agencia");

	//datos de la persona a actualizar
	$nombre           = $app->request->post("nombre");
	$primer_apellido  = $app->request->post("primer_apellido");
	$segundo_apellido = $app->request->post("segundo_apellido");
	$direccion        = $app->request->post("direccion");
	$localidad        = $app->request->post("localidad");
	$provincia        = $app->request->post("provincia");
	$telefono         = $app->request->post("telefono");
	$email            = $app->request->post("email");
	
	try{

		$app->response->headers->set("Content-type", "application/json");
		$app->response->status(200);

		//Obtenemos si el usuario tiene permiso para ejecutar el servicio
		$acceso = controlAccessUserToWS($user,$agencia,$pass,$NOM_SERVICIO);

		if($acceso){//Autenticacion de usuario y permisos correcto
			$connection = getConnectionAgencia();
			//Lamada a procedimiento almacenado
			$query = "CALL actualizarPersona(?,?,?,?,?,?,?,?,?)";
			$stmt = $connection->prepare($query);
			$stmt->bindParam(1, $dni);
			$stmt->bindParam(2, $nombre);
			$stmt->bindParam(3, $primer_apellido);
			$stmt->bindParam(4, $segundo_apellido);
			$stmt->bindParam(5, $direccion);
			$stmt->bindParam(6, $localidad);
			$stmt->bindParam(7, $provincia);
			$stmt->bindParam(8, $telefono);
			$stmt->bindParam(9, $email);
			$stmt->execute();
			$result = $stmt->fetchcolumn();
			$connection = null;

			if($result == 0){
				$app->response->body(json_encode(array("status" => "Ok", "message" => "-Error- El dni de la persona que quiere actualizar no existe")));
			}else{
				$app->response->body(json_encode(array("status" => "Ok", "message" => "Operation Succesful")));
			}
   		}else{
   			//Fallo en la Autenticacion de usaurio o de permisos
   			$app->response->body(json_encode(array("status" => "Ok", "message" => "Autentication Failure")));
   		}
   		
	}
	catch(PDOException $e)
	{
		$app->response->body(json_encode(array("status" => "Ok", "message" => " PDOException ".$e->getMessage())));
	}
	catch(Exception $e)
	{
		$app->response->body(json_encode(array("status" => "Ok", "message" => " Exception ".$e->getMessage())));
	}
	
});

/*******************************************************************************************************************

	Funcion que recibe peticiones DELETE a la url http://apirest/personas/eliminar/dni
	y elimina una persona de la BD

	Param Entrada: La informacion del usuario que lanza el servicio y los datos de la persona
    a eliminar.

    Param Salida: Mensaje JSON que nos informa del exito o error de la operacion.


*******************************************************************************************************************/

$app->delete("/personas/eliminar/:dni", function($dni) use($app)
{

	$NOM_SERVICIO = "personas/eliminar";
	//datos del usuario a autenticar
	$user = $app->request->post("user");
	$pass = $app->request->post("pass");
	$agencia = $app->request->post("agencia");
	
	try{

		$app->response->headers->set("Content-type", "application/json");
		$app->response->status(200);

		//Obtenemos si el usuario tiene permiso para ejecutar el servicio
		$acceso = controlAccessUserToWS($user,$agencia,$pass,$NOM_SERVICIO);

		if($acceso){//Autenticacion de usuario y permisos correcto
			$connection = getConnectionAgencia();
			//Lamada a procedimiento almacenado
			$query = "CALL eliminarPersona(?)";
			$stmt = $connection->prepare($query);
			$stmt->bindParam(1, $dni);
			$stmt->execute();
			$result = $stmt->fetchcolumn();
			$connection = null;

			if($result == 0){
				$app->response->body(json_encode(array("status" => "Ok", "message" => "-Error- El dni de la persona que quiere eliminar no existe")));
			}else{
				$app->response->body(json_encode(array("status" => "Ok", "message" => "Operation Succesful")));
			}
		}else{
   			//Fallo en la Autenticacion de usaurio o de permisos
   			$app->response->body(json_encode(array("status" => "Ok", "message" => "Autentication Failure")));
   		}

	}
	catch(PDOException $e)
	{
		$app->response->body(json_encode(array("status" => "Ok", "message" => " PDOException ".$e->getMessage())));
	}
	catch(Exception $e)
	{
		$app->response->body(json_encode(array("status" => "Ok", "message" => " Exception ".$e->getMessage())));
	}
	
});


/***********************************************************************************************

	Funcion que recibe peticiones POST a la url http://apirest/personas/dni
	y devuelve los datos de la persona cuyo dni coincide con el dni que nos pasan en la peticion.
	Los datos son devueltos en formato JSON


************************************************************************************************/

$app->post("/personas/:dni", function($dni) use($app)
{

	$NOM_SERVICIO = "persona";
	//datos del usuario a autenticar
	$user = $app->request->post("user");
	$pass = $app->request->post("pass");
	$agencia = $app->request->post("agencia");

	try{

		$app->response->headers->set("Content-type", "application/json");
		$app->response->status(200);

		//Obtenemos si el usuario tiene permiso para ejecutar el servicio
		$acceso = controlAccessUserToWS($user,$agencia,$pass,$NOM_SERVICIO);

		if($acceso){//Autenticacion de usuario y permisos correcto
			$connection = getConnectionAgencia();
			$query = "CALL obtenerPersona(?)";
			$stmt = $connection->prepare($query);
			$stmt->bindParam(1, $dni);
			$stmt->execute();
			$persona = $stmt->fetch();
			$connection = null;

			$app->response->body(json_encode($persona));
		}else{
   			//Fallo en la Autenticacion de usaurio o de permisos
   			$app->response->body(json_encode(array("status" => "Ok", "message" => "Autentication Failure")));
   		}
		
	}
	catch(PDOException $e)
	{
		$app->response->body(json_encode(array("status" => "Ok", "message" => " PDOException ".$e->getMessage())));
	}
	catch(Exception $e)
	{
		$app->response->body(json_encode(array("status" => "Ok", "message" => " Exception ".$e->getMessage())));
	}

});

/***********************************************************************************************

	Funcion que recibe peticiones POST a la url http://localhost/apirest_cli/clientes_paginado/id_agencia
	y devuelve los clientes de una agencia de forma paginada en formato JSON


************************************************************************************************/

$app->post("/clientes_paginado/:id_agencia", function($id_agencia) use($app)
{

	$NOM_SERVICIO = "clientes_agencia_paginado";
	//datos del usuario a autenticar
	$user = $app->request->post("user");
	$pass = $app->request->post("pass");
	$agencia = $app->request->post("agencia");

	//parametros paginacion
	$limit = $app->request->post("limit");
	$offset = $app->request->post("offset");

	try{

		$app->response->headers->set("Content-type", "application/json");
		$app->response->status(200);

		//Obtenemos si el usuario tiene permiso para ejecutar el servicio
		$acceso = controlAccessUserToWS($user,$agencia,$pass,$NOM_SERVICIO);

		if($acceso){//Autenticacion de usuario y permisos correcto
			$connection = getConnectionAgencia();
			$query = "CALL obtenerClientesAgenciaPaginado(?,?,?)";
			$stmt = $connection->prepare($query);
			$stmt->bindParam(1, $id_agencia);
			$stmt->bindParam(2, $limit);
			$stmt->bindParam(3, $offset);
			$stmt->execute();
			$propietarios = $stmt->fetchAll();
			$connection = null;

			$app->response->body(json_encode($propietarios));
		}else{
   			//Fallo en la Autenticacion de usaurio o de permisos
   			$app->response->body(json_encode(array("status" => "Ok", "message" => "Autentication Failure")));
   		}
		
	}
	catch(PDOException $e)
	{
		$app->response->body(json_encode(array("status" => "Ok", "message" => " PDOException ".$e->getMessage())));
	}
	catch(Exception $e)
	{
		$app->response->body(json_encode(array("status" => "Ok", "message" => " Exception ".$e->getMessage())));
	}

});