<?php

if(!defined("SPECIALCONSTANT")) die("Acceso denegado"); 

/*****************************************************************************************************

	Funcion que recibe peticiones POST a la url http://apirest/demandas/insertar/
	y hace un insert de una demanda en la BD

	Param Entrada: La informacion del usuario que lanza el servicio y los datos de la demanda
    a insertar.

    Param Salida: Mensaje JSON que nos informa del exito o error de la operacion.


********************************************************************************************************/

$app->post("/demandas/insertar/", function() use($app)
{

	$NOM_SERVICIO = "demandas/insertar";
	//datos del usuario a autenticar
	$user = $app->request->post("user");
	$pass = $app->request->post("pass");
	$agencia = $app->request->post("agencia");

	//datos de la demanda a insertar
	$id_cliente = $app->request->post("id_cliente");
	$descripcion = $app->request->post("descripcion");
	$compartir = $app->request->post("compartir");
	
	try{

		$app->response->headers->set("Content-type", "application/json");
		$app->response->status(200);

		//Obtenemos si el usuario tiene permiso para ejecutar el servicio
		$acceso = controlAccessUserToWS($user,$agencia,$pass,$NOM_SERVICIO);

		if($acceso){//Autenticacion de usuario y permisos correcto

			$connection = getConnectionAgencia();
			//Lamada a procedimiento almacenado
			$query = "CALL crearDemanda(?,?,?)";
			$stmt = $connection->prepare($query);
			$stmt->bindParam(1, $id_cliente);
			$stmt->bindParam(2, $descripcion);
			$stmt->bindParam(3, $compartir);
			$stmt->execute();
			$connection = null;
			
   			$app->response->body(json_encode(array("status" => "Ok", "message" => "Operation Succesful")));
   		}else{
   			//Fallo en la Autenticacion de usuario o de permisos
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

	Funcion que recibe peticiones PUT a la url http://apirest/demandas/actualizar/
	y actualiza los datos de una demanda en la BD

	Param Entrada: La informacion del usuario que lanza el servicio y los datos de la demanda
    a actualizar.

    Param Salida: Mensaje JSON que nos informa del exito o error de la operacion.


********************************************************************************************************/

$app->put("/demandas/actualizar/:id_demanda", function($id_demanda) use($app)
{

	$NOM_SERVICIO = "demandas/actualizar";
	//datos del usuario a autenticar
	$user = $app->request->post("user");
	$pass = $app->request->post("pass");
	$agencia = $app->request->post("agencia");

	//datos de la demanda a actualizar
	$id_cliente = $app->request->post("id_cliente");
	$descripcion = $app->request->post("descripcion");
	$compartir = $app->request->post("compartir");
	
	try{

		$app->response->headers->set("Content-type", "application/json");
		$app->response->status(200);

		//Obtenemos si el usuario tiene permiso para ejecutar el servicio
		$acceso = controlAccessUserToWS($user,$agencia,$pass,$NOM_SERVICIO);

		if($acceso){//Autenticacion de usuario y permisos correcto
			$connection = getConnectionAgencia();
			//Lamada a procedimiento almacenado
			$query = "CALL actualizarDemanda(?,?,?,?)";
			$stmt = $connection->prepare($query);
			$stmt->bindParam(1, $id_demanda);
			$stmt->bindParam(2, $id_cliente);
			$stmt->bindParam(3, $descripcion);
			$stmt->bindParam(4, $compartir);
			$stmt->execute();
			$result = $stmt->fetchcolumn();
			$connection = null;

			if($result == 0){
				$app->response->body(json_encode(array("status" => "Ok", "message" => "-Error- El identificador de la demanda que quiere actualizar no existe")));
			}else{
				$app->response->body(json_encode(array("status" => "Ok", "message" => "Operation Succesful")));
			}
   		}else{
   			//Fallo en la Autenticacion de usuario o de permisos
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

	Funcion que recibe peticiones DELETE a la url http://apirest/demandas/eliminar/id_cliente
	y elimina una demanda de la BD

	Param Entrada: La informacion de usuario que lanza el servicio y el identificador de la demanda
    a eliminar.

    Param Salida: Mensaje JSON que nos informa del exito o error de la operacion.


*******************************************************************************************************************/

$app->delete("/demandas/eliminar/:id_demanda", function($id_demanda) use($app)
{

	$NOM_SERVICIO = "demandas/eliminar";
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
			$query = "CALL eliminarDemanda(?)";
			$stmt = $connection->prepare($query);
			$stmt->bindParam(1, $id_demanda);
			$stmt->execute();
			$result = $stmt->fetchcolumn();
			$connection = null;

			if($result == 0){
				$app->response->body(json_encode(array("status" => "Ok", "message" => "-Error- El identificador de la demanda que quiere eliminar no existe")));
			}else{
				$app->response->body(json_encode(array("status" => "Ok", "message" => "Operation Succesful")));
			}
		}else{
   			//Fallo en la Autenticacion de usuario o de permisos
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

	Funcion que recibe peticiones POST a la url http://apirest/demandas/id_cliente
	y devuelve las demandas que tiene de un determinado cliente en formato JSON


************************************************************************************************/

$app->post("/demandas/:id_cliente", function($id_cliente) use($app)
{

	$NOM_SERVICIO = "demandas";
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
			$query = "CALL obtenerDemandas(?)";
			
			$stmt = $connection->prepare($query);
			$stmt->bindParam(1, $id_cliente);
			$stmt->execute();
			$tipo_operacion = $stmt->fetchAll();
			$connection = null;

			$app->response->body(json_encode($tipo_operacion));
		}else{
   			//Fallo en la Autenticacion de usuario o de permisos
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

/************************************************************************************************************************

	Funcion que recibe peticiones PUT a la url http://apirest/demandas/compartir/:id_demanda
	y actualiza el campo compartir de la demanda con id igual a :id_demanda y devuelve la respuesta en formato JSON


*************************************************************************************************************************/

$app->put("/demandas/compartir/:id_demanda", function($id_demanda) use($app)
{

	$NOM_SERVICIO = "demandas/compartir";
	//datos del usuario a autenticar
	$user = $app->request->post("user");
	$pass = $app->request->post("pass");
	$agencia = $app->request->post("agencia");

	//datos de la operacion a actualizar
	$compartir = $app->request->post("compartir");
	
	try{

		$app->response->headers->set("Content-type", "application/json");
		$app->response->status(200);

		//Obtenemos si el usuario tiene permiso para ejecutar el servicio
		$acceso = controlAccessUserToWS($user,$agencia,$pass,$NOM_SERVICIO);

		if($acceso){//Autenticacion de usuario y permisos correcto
			$connection = getConnectionAgencia();
			//Lamada a procedimiento almacenado
			$query = "CALL compartirDemanda(?,?,?)";
			$stmt = $connection->prepare($query);
			$stmt->bindParam(1,  $id_demanda);
			$stmt->bindParam(2,  $agencia);
			$stmt->bindParam(3,  $compartir);
			$stmt->execute();
			$result = $stmt->fetchcolumn();
			$connection = null;

			if($result == 0){
				$app->response->body(json_encode(array("status" => "Ok", "message" => "-Error- El id de la demanda que quiere compartir/ocultar no existe")));
			}elseif($result == 1){
				$app->response->body(json_encode(array("status" => "Ok", "message" => "Operation Succesful")));
			}else{/*La peticion de compartir operacion no esta autorizada, porque la agencia que intenta compartir la operacion no es 'Federacion' ni es 
			        la misma a la que pertenece la operacion */
				$app->response->body(json_encode(array("status" => "Ok", "message" => "Autentication Failure"))); 
			}
   		}else{
   			//Fallo en la Autenticacion de usuario o de permisos
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