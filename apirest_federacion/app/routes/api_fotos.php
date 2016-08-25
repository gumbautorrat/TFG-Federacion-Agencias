<?php

if(!defined("SPECIALCONSTANT")) die("Acceso denegado"); 

/*****************************************************************************************************

	Funcion que recibe peticiones POST a la url http://apirest/fotos/insertar/
	y hace un insert de una foto en la BD

	Param Entrada: La informacion del usuario que lanza el servicio y los datos de la foto
    a insertar.

    Param Salida: Mensaje JSON que nos informa del exito o error de la operacion.


********************************************************************************************************/

$app->post("/fotos/insertar/", function() use($app)
{

	$NOM_SERVICIO = "fotos/insertar";
	//datos del usuario a autenticar
	$user = $app->request->post("user");
	$pass = $app->request->post("pass");
	$agencia = $app->request->post("agencia");

	//datos de la foto a insertar
	$url = $app->request->post("url");
	$id_inmueble = $app->request->post("id_inmueble");
	
	try{

		$app->response->headers->set("Content-type", "application/json");
		$app->response->status(200);

		//Obtenemos si el usuario tiene permiso para ejecutar el servicio
		$acceso = controlAccessUserToWS($user,$agencia,$pass,$NOM_SERVICIO);

		if($acceso){//Autenticacion de usuario y permisos correcto

			$connection = getConnectionAgencia();
			//Lamada a procedimiento almacenado
			$query = "CALL crearFoto(?,?)";
			$stmt = $connection->prepare($query);
			$stmt->bindParam(1, $url);
			$stmt->bindParam(2, $id_inmueble);
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

		if($e->getCode()==23000){
			$app->response->body(json_encode(array("status" => "Ok", "message" => "-Error- ya existe una foto con la misma url")));
		}else{
			$app->response->body(json_encode(array("status" => "Ok", "message" => " PDOException ".$e->getMessage())));
		}
		
	}
	catch(Exception $e)
	{
		$app->response->body(json_encode(array("status" => "Ok", "message" => " Exception ".$e->getMessage())));
	}
	
});

/*****************************************************************************************************

	Funcion que recibe peticiones PUT a la url http://apirest/fotos/actualizar/
	y actualiza los datos de una foto en la BD

	Param Entrada: La informacion del usuario que lanza el servicio y los datos de la foto
    a actualizar.

    Param Salida: Mensaje JSON que nos informa del exito o error de la operacion.


********************************************************************************************************/

$app->put("/fotos/actualizar/:id_foto", function($id_foto) use($app)
{

	$NOM_SERVICIO = "fotos/actualizar";
	//datos del usuario a autenticar
	$user = $app->request->post("user");
	$pass = $app->request->post("pass");
	$agencia = $app->request->post("agencia");

	//datos de la foto a actualizar
	$url = $app->request->post("url");
	$id_inmueble = $app->request->post("id_inmueble");
	
	try{

		$app->response->headers->set("Content-type", "application/json");
		$app->response->status(200);

		//Obtenemos si el usuario tiene permiso para ejecutar el servicio
		$acceso = controlAccessUserToWS($user,$agencia,$pass,$NOM_SERVICIO);

		if($acceso){//Autenticacion de usuario y permisos correcto
			$connection = getConnectionAgencia();
			//Lamada a procedimiento almacenado
			$query = "CALL actualizarFoto(?,?,?)";
			$stmt = $connection->prepare($query);
			$stmt->bindParam(1, $id_foto);
			$stmt->bindParam(2, $url);
			$stmt->bindParam(3, $id_inmueble);
			$stmt->execute();
			$result = $stmt->fetchcolumn();
			$connection = null;

			if($result == 0){
				$app->response->body(json_encode(array("status" => "Ok", "message" => "-Error- El id de la foto que quiere actualizar no existe")));
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

	Funcion que recibe peticiones DELETE a la url http://apirest/fotos/eliminar/id_foto
	y elimina una foto de la BD

	Param Entrada: La informacion de usuario que lanza el servicio y los datos de la foto
    a eliminar.

    Param Salida: Mensaje JSON que nos informa del exito o error de la operacion.


*******************************************************************************************************************/

$app->delete("/fotos/eliminar/:id_foto", function($id_foto) use($app)
{

	$NOM_SERVICIO = "fotos/eliminar";
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
			$query = "CALL eliminarFoto(?)";
			$stmt = $connection->prepare($query);
			$stmt->bindParam(1, $id_foto);
			$stmt->execute();
			$result = $stmt->fetchcolumn();
			$connection = null;

			if($result == 0){
				$app->response->body(json_encode(array("status" => "Ok", "message" => "-Error- El id de la foto que quiere eliminar no existe")));
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

	Funcion que recibe peticiones POST a la url http://apirest/fotos/id_inmueble
	y devuelve las fotos de un determinado inmueble en formato JSON


************************************************************************************************/

$app->post("/fotos/:id_inmueble", function($id_inmueble) use($app)
{

	$NOM_SERVICIO = "fotos";
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
			$query = "CALL obtenerFotos(?)";
			
			$stmt = $connection->prepare($query);
			$stmt->bindParam(1, $id_inmueble);
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

/***********************************************************************************************

	Funcion que recibe peticiones POST a la url http://apirest/fotos_agencia/id_inmueble
	y devuelve las fotos de un determinado inmueble de una agencia en formato JSON


************************************************************************************************/

$app->post("/fotos_agencia/:id_inmueble", function($id_inmueble) use($app)
{

	$NOM_SERVICIO = "fotos_agencia";
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
			$query = "CALL obtenerFotosAgencia(?,?)";
			
			$stmt = $connection->prepare($query);
			$stmt->bindParam(1, $id_inmueble);
			$stmt->bindParam(2, $agencia);
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