<?php

if(!defined("SPECIALCONSTANT")) die("Acceso denegado"); 

/*****************************************************************************************************

	Funcion que recibe peticiones POST a la url http://apirest/inmuebles/insertar/
	y hace un insert de un inmueble en la BD

	Param Entrada: La informacion del usuario que lanza el servicio y los datos del inmueble
    a insertar.

    Param Salida: Mensaje JSON que nos informa del exito o error de la operacion.


********************************************************************************************************/

$app->post("/inmuebles/insertar/", function() use($app)
{

	$NOM_SERVICIO = "inmuebles/insertar";
	//datos del usuario a autenticar
	$user = $app->request->post("user");
	$pass = $app->request->post("pass");
	$agencia = $app->request->post("agencia");

	//datos del inmueble a insertar
	$id_agencia       = $app->request->post("id_agencia");
	$id_propietario   = $app->request->post("id_propietario");
	
	$direccion        = $app->request->post("direccion");
	$codigo_postal    = $app->request->post("codigo_postal");
	$planta           = $app->request->post("planta");
	$localidad        = $app->request->post("localidad");
	$provincia        = $app->request->post("provincia");

	$desc_corta       = $app->request->post("desc_corta");
	$desc_larga       = $app->request->post("desc_larga");
	$num_wc           = $app->request->post("num_wc");
	$num_habita       = $app->request->post("num_habita");
	$num_metros       = $app->request->post("num_metros");

	try{

		$app->response->headers->set("Content-type", "application/json");
		$app->response->status(200);

		//Obtenemos si el usuario tiene permiso para ejecutar el servicio
		$acceso = controlAccessUserToWS($user,$agencia,$pass,$NOM_SERVICIO);

		if($acceso){//Autenticacion de usuario y permisos correcto

			$connection = getConnectionAgencia();
			//Lamada a procedimiento almacenado
			$query = "CALL crearInmueble(?,?,?,?,?,?,?,?,?,?,?,?)";
			$stmt = $connection->prepare($query);
			$stmt->bindParam(1, $id_agencia);
			$stmt->bindParam(2, $id_propietario);
			
			$stmt->bindParam(3, $direccion);
			$stmt->bindParam(4, $codigo_postal);
			$stmt->bindParam(5, $planta);
			$stmt->bindParam(6, $localidad);
			$stmt->bindParam(7, $provincia);

			$stmt->bindParam(8, $desc_corta);
			$stmt->bindParam(9, $desc_larga);
			$stmt->bindParam(10, $num_wc);
			$stmt->bindParam(11, $num_habita);
			$stmt->bindParam(12, $num_metros);
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

	Funcion que recibe peticiones PUT a la url http://apirest/inmuebles/actualizar/
	y actualiza los datos de un inmueble en la BD

	Param Entrada: La informacion del usuario que lanza el servicio y los datos del cliente
    a actualizar.

    Param Salida: Mensaje JSON que nos informa del exito o error de la operacion.


********************************************************************************************************/

$app->put("/inmuebles/actualizar/:id_inmueble", function($id_inmueble) use($app)
{

	$NOM_SERVICIO = "inmuebles/actualizar";
	//datos del usuario a autenticar
	$user = $app->request->post("user");
	$pass = $app->request->post("pass");
	$agencia = $app->request->post("agencia");

	//datos del inmueble a actualizar
	$id_agencia       = $app->request->post("id_agencia");
	$id_propietario   = $app->request->post("id_propietario");
	
	$direccion        = $app->request->post("direccion");
	$codigo_postal    = $app->request->post("codigo_postal");
	$planta           = $app->request->post("planta");
	$localidad        = $app->request->post("localidad");
	$provincia        = $app->request->post("provincia");

	$desc_corta       = $app->request->post("desc_corta");
	$desc_larga       = $app->request->post("desc_larga");
	$num_wc           = $app->request->post("num_wc");
	$num_habita       = $app->request->post("num_habita");
	$num_metros       = $app->request->post("num_metros");
	
	try{

		$app->response->headers->set("Content-type", "application/json");
		$app->response->status(200);

		//Obtenemos si el usuario tiene permiso para ejecutar el servicio
		$acceso = controlAccessUserToWS($user,$agencia,$pass,$NOM_SERVICIO);

		if($acceso){//Autenticacion de usuario y permisos correcto
			$connection = getConnectionAgencia();
			//Lamada a procedimiento almacenado
			$query = "CALL actualizarInmueble(?,?,?,?,?,?,?,?,?,?,?,?,?)";
			$stmt = $connection->prepare($query);
			$stmt->bindParam(1, $id_inmueble);
			$stmt->bindParam(2, $id_agencia);
			$stmt->bindParam(3, $id_propietario);
			
			$stmt->bindParam(4, $direccion);
			$stmt->bindParam(5, $codigo_postal);
			$stmt->bindParam(6, $planta);
			$stmt->bindParam(7, $localidad);
			$stmt->bindParam(8, $provincia);

			$stmt->bindParam(9, $desc_corta);
			$stmt->bindParam(10, $desc_larga);
			$stmt->bindParam(11, $num_wc);
			$stmt->bindParam(12, $num_habita);
			$stmt->bindParam(13, $num_metros);
			$stmt->execute();
			$result = $stmt->fetchcolumn();
			$connection = null;

			if($result == 0){
				$app->response->body(json_encode(array("status" => "Ok", "message" => "-Error- El id del inmueble que quiere actualizar no existe")));
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

	Funcion que recibe peticiones DELETE a la url http://apirest/inmuebles/eliminar/id_inmueble
	y elimina un inmueble de la BD

	Param Entrada: La informacion del usuario que lanza el servicio y los datos del cliente
    a eliminar.

    Param Salida: Mensaje JSON que nos informa del exito o error de la operacion.


*******************************************************************************************************************/

$app->delete("/inmuebles/eliminar/:id_inmueble", function($id_inmueble) use($app)
{

	$NOM_SERVICIO = "inmuebles/eliminar";
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
			$query = "CALL eliminarInmueble(?)";
			$stmt = $connection->prepare($query);
			$stmt->bindParam(1, $id_inmueble);
			$stmt->execute();
			$result = $stmt->fetchcolumn();
			$connection = null;

			if($result == 0){
				$app->response->body(json_encode(array("status" => "Ok", "message" => "-Error- El id del inmueble que quiere eliminar no existe")));
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

	Funcion que recibe peticiones POST a la url http://apirest/inmueble/id_inmueble
	y devuelve los datos del detalle del inmueble que coincide con :id_inmueble en formato JSON


************************************************************************************************/

$app->post("/inmueble/:id_inmueble", function($id_inmueble) use($app)
{

	$NOM_SERVICIO = "detalle_inmueble";
	//datos del usuario a autenticar
	$id_tipo = $app->request->post("id_tipo");

	try{

		$app->response->headers->set("Content-type", "application/json");
		$app->response->status(200);
		
		$connection = getConnectionAgencia();
		$query = "CALL obtenerDetalleInmueble(?,?)";
		$stmt = $connection->prepare($query);
		$stmt->bindParam(1, $id_inmueble);
		$stmt->bindParam(2, $id_tipo);
		$stmt->execute();
		$detalleInmueble = $stmt->fetch();
		$connection = null;

		$app->response->body(json_encode($detalleInmueble));
		
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

	Funcion que recibe peticiones POST a la url http://apirest/inmueble/fotos/id_inmueble
	y devuelve las fotos de un determinado inmueble en formato JSON


************************************************************************************************/

$app->get("/inmueble/fotos/:id_inmueble", function($id_inmueble) use($app)
{

	$NOM_SERVICIO = "inmueble/fotos";

	try{

		$app->response->headers->set("Content-type", "application/json");
		$app->response->status(200);

		$connection = getConnectionAgencia();
		$query = "CALL obtenerFotos(?)";
		
		$stmt = $connection->prepare($query);
		$stmt->bindParam(1, $id_inmueble);
		$stmt->execute();
		$fotosInmueble = $stmt->fetchAll();
		$connection = null;

		$app->response->body(json_encode($fotosInmueble));
		
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

	Funcion que recibe peticiones POST a la url http://apirest/inmuebles/
	y devuelve los inmuebles que existen en la BD en formato JSON


************************************************************************************************/

$app->post("/inmuebles/", function() use($app)
{

	$NOM_SERVICIO = "inmuebles";
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
			$query = "CALL obtenerInmuebles()";
			$stmt = $connection->prepare($query);
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

	Funcion que recibe peticiones POST a la url http://apirest/inmuebles/id_agencia
	y devuelve los inmuebles de una agencia en formato JSON


************************************************************************************************/

$app->post("/inmuebles/:id_agencia", function($id_agencia) use($app)
{

	$NOM_SERVICIO = "inmuebles_agencia";
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
			$query = "CALL obtenerInmueblesAgencia(?)";
			$stmt = $connection->prepare($query);
			$stmt->bindParam(1, $id_agencia);
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

	Funcion que recibe peticiones POST a la url http://apirest/inmuebles_paginado/id_agencia
	y devuelve los inmuebles de una agencia de forma paginada en formato JSON


************************************************************************************************/

/*$app->post("/inmuebles_paginado/:id_agencia", function($id_agencia) use($app)
{

	$NOM_SERVICIO = "inmuebles_agencia_paginado";
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
			$query = "CALL obtenerInmueblesAgenciaPaginado(?,?,?)";
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
		$app->response->body(json_encode(array("status" => "Ok", "message" => $e->getMessage())));
	}
	catch(Exception $e)
	{
		$app->response->body(json_encode(array("status" => "Ok", "message" => "Error al conectar a la BD")));
	}

});*/

/***********************************************************************************************

	Funcion que recibe peticiones POST a la url http://apirest/inmuebles_paginado/id_agencia
	y devuelve los inmuebles de una agencia de forma paginada en formato JSON
	VERSION SIN COMPROBAR PERMISOS

************************************************************************************************/

$app->post("/inmuebles_paginado/:id_agencia", function($id_agencia) use($app)
{

	$NOM_SERVICIO = "inmuebles_agencia_paginado";

	//parametros paginacion
	$limit = $app->request->post("limit");
	$offset = $app->request->post("offset");

	try{

		$app->response->headers->set("Content-type", "application/json");
		$app->response->status(200);

		$connection = getConnectionAgencia();
		$query = "CALL obtenerInmueblesAgenciaPaginado(?,?,?)";
		$stmt = $connection->prepare($query);
		$stmt->bindParam(1, $id_agencia);
		$stmt->bindParam(2, $limit);
		$stmt->bindParam(3, $offset);
		$stmt->execute();
		$inmuebles = $stmt->fetchAll();
		$connection = null;

		$app->response->body(json_encode($inmuebles));
		
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

	Funcion que recibe peticiones POST a la url http://apirest/inmuebles_propietario/id_agencia
	y devuelve los inmuebles de un propietario en formato JSON


************************************************************************************************/

$app->post("/inmuebles_propietario_paginado/:id_agencia", function($id_agencia) use($app)
{

	$NOM_SERVICIO = "inmuebles_propietario_paginado";
	//datos del usuario a autenticar
	$user = $app->request->post("user");
	$pass = $app->request->post("pass");
	$agencia = $app->request->post("agencia");

	//id propietario
	$id_propietario = $app->request->post("id_propietario");

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
			$query = "CALL obtenerInmueblesPropietarioPaginado(?,?,?,?)";
			$stmt = $connection->prepare($query);
			$stmt->bindParam(1, $id_agencia);
			$stmt->bindParam(2, $id_propietario);
			$stmt->bindParam(3, $limit);
			$stmt->bindParam(4, $offset);
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

/*******************************************************************************************************************

	Funcion que recibe peticiones GET a la url http://apirest/num_inmuebles/agencia
	y devuelve el numero de inmuebles que existe de una agencia determinada en formato JSON

*******************************************************************************************************************/

$app->get("/num_inmuebles/:agencia", function($agencia) use($app)
{

	$NOM_SERVICIO = "num_inmuebles";
	
	try{

		$app->response->headers->set("Content-type", "application/json");
		$app->response->status(200);

		$connection = getConnectionAgencia();
		//Lamada a procedimiento almacenado
		$query = "CALL numInmuebles(?)";
		$stmt = $connection->prepare($query);
		$stmt->bindParam(1, $agencia);
		$stmt->execute();
		$result = $stmt->fetchcolumn();
		$connection = null;

		$app->response->body(json_encode(array("status" => "Ok", "message" => "Operation Succesful", "total" => $result)));

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

	Funcion que recibe peticiones GET a la url http://apirest/num_inmuebles_tipo/id_agencia
	y devuelve el numero de inmuebles que existe de una agencia determinada al hacer un filtrado por tipo de operacion
	en formato JSON

*******************************************************************************************************************/

$app->post("/num_inmuebles_tipo/:id_agencia", function($id_agencia) use($app)
{

	$NOM_SERVICIO = "num_inmuebles_tipo";

	//parametro para filtrar por tipo de operacion
	$tipo = $app->request->post("tipo");
	
	try{

		$app->response->headers->set("Content-type", "application/json");
		$app->response->status(200);

		$connection = getConnectionAgencia();
		//Lamada a procedimiento almacenado
		$query = "CALL numInmueblesTipo(?,?)";
		$stmt = $connection->prepare($query);
		$stmt->bindParam(1, $id_agencia);
		$stmt->bindParam(2, $tipo);
		$stmt->execute();
		$result = $stmt->fetchcolumn();
		$connection = null;

		$app->response->body(json_encode(array("status" => "Ok", "message" => "Operation Succesful", "total" => $result)));

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

	Funcion que recibe peticiones POST a la url http://apirest/inmuebles_paginado_tipo/id_agencia
	y devuelve los inmuebles de una agencia o compartidos por otras agencias, ademas de filtrar por tipo de 
	operación y de forma paginada, los datos de salida se envian en formato JSON

************************************************************************************************/

$app->post("/inmuebles_paginado_tipo/:id_agencia", function($id_agencia) use($app)
{

	$NOM_SERVICIO = "inmuebles_paginado_tipo";

	//parametros paginacion
	$limit = $app->request->post("limit");
	$offset = $app->request->post("offset");
	//parametro para filtrar por tipo de operacion
	$tipo = $app->request->post("tipo");

	try{

		$app->response->headers->set("Content-type", "application/json");
		$app->response->status(200);

		$connection = getConnectionAgencia();
		$query = "CALL obtenerInmueblesPaginado(?,?,?,?)";
		$stmt = $connection->prepare($query);
		$stmt->bindParam(1, $id_agencia);
		$stmt->bindParam(2, $limit);
		$stmt->bindParam(3, $offset);
		$stmt->bindParam(4, $tipo);
		$stmt->execute();
		$inmuebles = $stmt->fetchAll();
		$connection = null;

		$app->response->body(json_encode($inmuebles));
		
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