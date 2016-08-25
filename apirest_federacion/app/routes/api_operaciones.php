<?php

if(!defined("SPECIALCONSTANT")) die("Acceso denegado"); 

/*****************************************************************************************************

	Funcion que recibe peticiones POST a la url http://apirest/operaciones/insertar/
	y hace un insert de una operacion en la BD

	Param Entrada: La informacion del usuario que lanza el servicio y los datos de la operacion
    a insertar.

    Param Salida: Mensaje JSON que nos informa del exito o error de la operacion.


********************************************************************************************************/

$app->post("/operaciones/insertar/", function() use($app)
{

	$NOM_SERVICIO = "operaciones/insertar";
	//datos del usuario a autenticar
	$user = $app->request->post("user");
	$pass = $app->request->post("pass");
	$agencia = $app->request->post("agencia"); //agencia desde la que se hace la peticion

	//datos de la operacion a insertar
	$id_agencia       = $app->request->post("id_agencia");
	$id_inmueble      = $app->request->post("id_inmueble");
	$id_tipo          = $app->request->post("id_tipo");
	$id_cliente       = $app->request->post("id_cliente");
	$precio           = $app->request->post("precio");
	$compartir        = $app->request->post("compartir");
	$disponible       = $app->request->post("disponible");
	$fianza           = $app->request->post("fianza");
	$reserva          = $app->request->post("reserva");
	$tiempo_meses     = $app->request->post("tiempo_meses");
	$descuento        = $app->request->post("descuento");
	$entrada          = $app->request->post("entrada");
	$descripcion      = $app->request->post("descripcion");
	$observaciones    = $app->request->post("observaciones");

	try{

		$app->response->headers->set("Content-type", "application/json");
		$app->response->status(200);

		//Obtenemos si el usuario tiene permiso para ejecutar el servicio
		$acceso = controlAccessUserToWS($user,$agencia,$pass,$NOM_SERVICIO);

		if($acceso){//Autenticacion de usuario y permisos correcto

			$connection = getConnectionAgencia();
			//Lamada a procedimiento almacenado
			$query = "CALL crearOperacion(?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
			$stmt = $connection->prepare($query);
			$stmt->bindParam(1,  $id_agencia);
			$stmt->bindParam(2,  $id_inmueble);
			$stmt->bindParam(3,  $id_tipo);
			$stmt->bindParam(4,  $id_cliente);
			$stmt->bindParam(5,  $precio);
			$stmt->bindParam(6,  $compartir);
			$stmt->bindParam(7,  $disponible);
			$stmt->bindParam(8,  $fianza);
			$stmt->bindParam(9,  $reserva);
			$stmt->bindParam(10, $tiempo_meses);
			$stmt->bindParam(11, $descuento);
			$stmt->bindParam(12, $entrada);
			$stmt->bindParam(13, $descripcion);
			$stmt->bindParam(14, $observaciones);
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

	Funcion que recibe peticiones PUT a la url http://apirest/operaciones/actualizar/:id_operacion
	y actualiza los datos de una operacion en la BD

	Param Entrada: La informacion del usuario que lanza el servicio y los datos de la operacion
    a actualizar.

    Param Salida: Mensaje JSON que nos informa del exito o error de la operacion.


********************************************************************************************************/

$app->put("/operaciones/actualizar/:id_operacion", function($id_operacion) use($app)
{

	$NOM_SERVICIO = "operaciones/actualizar";
	//datos del usuario a autenticar
	$user = $app->request->post("user");
	$pass = $app->request->post("pass");
	$agencia = $app->request->post("agencia");

	//datos de la operacion a actualizar
	$id_agencia       = $app->request->post("id_agencia");
	$id_inmueble      = $app->request->post("id_inmueble");
	$id_tipo          = $app->request->post("id_tipo");
	$id_cliente       = $app->request->post("id_cliente");
	$precio           = $app->request->post("precio");
	$compartir        = $app->request->post("compartir");
	$disponible       = $app->request->post("disponible");
	$fianza           = $app->request->post("fianza");
	$reserva          = $app->request->post("reserva");
	$tiempo_meses     = $app->request->post("tiempo_meses");
	$descuento        = $app->request->post("descuento");
	$entrada          = $app->request->post("entrada");
	$descripcion      = $app->request->post("descripcion");
	$observaciones    = $app->request->post("observaciones");
	
	try{

		$app->response->headers->set("Content-type", "application/json");
		$app->response->status(200);

		//Obtenemos si el usuario tiene permiso para ejecutar el servicio
		$acceso = controlAccessUserToWS($user,$agencia,$pass,$NOM_SERVICIO);

		if($acceso){//Autenticacion de usuario y permisos correcto
			$connection = getConnectionAgencia();
			//Lamada a procedimiento almacenado
			$query = "CALL actualizarOperacion(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
			$stmt = $connection->prepare($query);
			$stmt->bindParam(1,  $id_operacion);
			$stmt->bindParam(2,  $id_agencia);
			$stmt->bindParam(3,  $id_inmueble);
			$stmt->bindParam(4,  $id_tipo);
			$stmt->bindParam(5,  $id_cliente);
			$stmt->bindParam(6,  $precio);
			$stmt->bindParam(7,  $compartir);
			$stmt->bindParam(8,  $disponible);
			$stmt->bindParam(9,  $fianza);
			$stmt->bindParam(10, $reserva);
			$stmt->bindParam(11, $tiempo_meses);
			$stmt->bindParam(12, $descuento);
			$stmt->bindParam(13, $entrada);
			$stmt->bindParam(14, $descripcion);
			$stmt->bindParam(15, $observaciones);
			$stmt->execute();
			$result = $stmt->fetchcolumn();
			$connection = null;

			if($result == 0){
				$app->response->body(json_encode(array("status" => "Ok", "message" => "-Error- El id de la operacion que quiere actualizar no existe")));
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

	Funcion que recibe peticiones DELETE a la url http://apirest/operaciones/eliminar/:id_operacion
	y elimina una operacion de la BD

	Param Entrada: La informacion del usuario que lanza el servicio y los datos de la operacion
    a eliminar.

    Param Salida: Mensaje JSON que nos informa del exito o error de la operacion.


*******************************************************************************************************************/

$app->delete("/operaciones/eliminar/:id_operacion", function($id_operacion) use($app)
{

	$NOM_SERVICIO = "operaciones/eliminar";
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
			$query = "CALL eliminarOperacion(?)";
			$stmt = $connection->prepare($query);
			$stmt->bindParam(1, $id_operacion);
			$stmt->execute();
			$result = $stmt->fetchcolumn();
			$connection = null;

			if($result == 0){
				$app->response->body(json_encode(array("status" => "Ok", "message" => "-Error- El id de la operacion que quiere eliminar no existe")));
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

	Funcion que recibe peticiones POST a la url http://apirest/operaciones/
	y devuelve las operaciones que existen en la BD en formato JSON


************************************************************************************************/

$app->post("/operaciones/", function() use($app)
{

	$NOM_SERVICIO = "operaciones";
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
			$query = "CALL obtenerOperaciones()";
			$stmt = $connection->prepare($query);
			$stmt->execute();
			$operaciones = $stmt->fetchAll();
			$connection = null;

			$app->response->body(json_encode($operaciones));
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

	Funcion que recibe peticiones POST a la url http://apirest/operaciones_agencia_propia/
	y devuelve las operaciones de la agencia a la que pertenece el usuario en formato JSON

************************************************************************************************/

$app->post("/operaciones_agencia_propia/", function() use($app)
{

	$NOM_SERVICIO = "operaciones_agencia_propia";
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
			$query = "CALL obtenerOperacionesAgenciaPropia(?,?,?)";
			$stmt = $connection->prepare($query);
			$stmt->bindParam(1, $agencia);
			$stmt->bindParam(2, $limit);
			$stmt->bindParam(3, $offset);
			$stmt->execute();
			$operaciones = $stmt->fetchAll();
			$connection = null;

			$app->response->body(json_encode($operaciones));
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

	Funcion que recibe peticiones POST a la url http://apirest/operaciones/id_agencia
	y devuelve las operaciones de una agencia en formato JSON


************************************************************************************************/

$app->post("/operaciones/:id_agencia", function($id_agencia) use($app)
{

	$NOM_SERVICIO = "operaciones_agencia";
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
			$query = "CALL obtenerOperacionesAgencia(?)";
			$stmt = $connection->prepare($query);
			$stmt->bindParam(1, $id_agencia);
			$stmt->execute();
			$operaciones = $stmt->fetchAll();
			$connection = null;

			$app->response->body(json_encode($operaciones));
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

	Funcion que recibe peticiones POST a la url http://apirest/operaciones_paginado/id_agencia
	y devuelve las operaciones de una agencia de forma paginada en formato JSON


************************************************************************************************/

$app->post("/operaciones_paginado/:id_agencia", function($id_agencia) use($app)
{

	$NOM_SERVICIO = "operaciones_agencia_paginado";
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
			$query = "CALL obtenerOperacionesAgenciaPaginado(?,?,?)";
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

/***********************************************************************************************

	Funcion que recibe peticiones POST a la url http://apirest/operaciones_cliente/id_agencia
	y devuelve las operaciones de un cliente y de una agencia determinada de forma paginada en formato JSON


************************************************************************************************/

$app->post("/operaciones_cliente_paginado/:id_agencia", function($id_agencia) use($app)
{

	$NOM_SERVICIO = "operaciones_cliente_paginado";
	//datos del usuario a autenticar
	$user = $app->request->post("user");
	$pass = $app->request->post("pass");
	$agencia = $app->request->post("agencia");

	//id cliente
	$id_cliente = $app->request->post("id_cliente");

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
			$query = "CALL obtenerOperacionesClientePaginado(?,?,?,?)";
			$stmt = $connection->prepare($query);
			$stmt->bindParam(1, $id_agencia);
			$stmt->bindParam(2, $id_cliente);
			$stmt->bindParam(3, $limit);
			$stmt->bindParam(4, $offset);
			$stmt->execute();
			$operaciones = $stmt->fetchAll();
			$connection = null;
			$app->response->body(json_encode($operaciones));
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

/************************************************************************************************************************

	Funcion que recibe peticiones PUT a la url http://apirest/operaciones/compartir/:id_operacion
	y actualiza el campo compartir de la operacion con id igual a :id_operacion y devuelve la respuesta en formato JSON


*************************************************************************************************************************/

$app->put("/operaciones/compartir/:id_operacion", function($id_operacion) use($app)
{

	$NOM_SERVICIO = "operaciones/compartir";
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
			$query = "CALL compartirOperacion(?,?,?)";
			$stmt = $connection->prepare($query);
			$stmt->bindParam(1,  $id_operacion);
			$stmt->bindParam(2,  $agencia);
			$stmt->bindParam(3,  $compartir);
			$stmt->execute();
			$result = $stmt->fetchcolumn();
			$connection = null;

			if($result == 0){
				$app->response->body(json_encode(array("status" => "Ok", "message" => "-Error- El id de la operacion que quiere compartir/ocultar no existe")));
			}elseif($result == 1){
				$app->response->body(json_encode(array("status" => "Ok", "message" => "Operation Succesful")));
			}else{/*La peticion de compartir operacion no esta autorizada, porque la agencia que intenta actualizar el campo compartir 
			        la operacion no es 'Federacion' ni es la misma a la que pertenece la operacion */
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

/***********************************************************************************************

	Funcion que recibe peticiones POST a la url http://apirest/operaciones_paginado_filtro/
	y devuelve las operaciones de una agencia o compartidos por otras agencias, ademas de filtrar
	por tipo de operación y de forma paginada, los datos de salida se envian en formato JSON

************************************************************************************************/

$app->post("/operaciones_paginado_filtro/", function() use($app)
{

	$NOM_SERVICIO = "operaciones_paginado_filtro";
	//datos del usuario a autenticar
	$user = $app->request->post("user");
	$pass = $app->request->post("pass");
	$agencia = $app->request->post("agencia");

	//parametros paginacion
	$limit = $app->request->post("limit");
	$offset = $app->request->post("offset");
	//parametro para filtrar por tipo de operacion
	$tipo_filtro = $app->request->post("tipo_filtro");

	try{

		$app->response->headers->set("Content-type", "application/json");
		$app->response->status(200);

		//Obtenemos si el usuario tiene permiso para ejecutar el servicio
		$acceso = controlAccessUserToWS($user,$agencia,$pass,$NOM_SERVICIO);

		if($acceso){//Autenticacion de usuario y permisos correcto
			$connection = getConnectionAgencia();
			$query = "CALL obtenerOperacionesPaginadoFiltro(?,?,?,?)";
			$stmt = $connection->prepare($query);
			$stmt->bindParam(1, $limit);
			$stmt->bindParam(2, $offset);
			$stmt->bindParam(3, $tipo_filtro);
			$stmt->bindParam(4, $agencia);
			$stmt->execute();
			$operaciones = $stmt->fetchAll();
			$connection = null;

			$app->response->body(json_encode($operaciones));
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

	Funcion que recibe peticiones POST a la url http://apirest/num_operaciones_filtro/
	y devuelve el numero de operaciones filtrado que existe en la BD en formato JSON

*******************************************************************************************************************/

$app->post("/num_operaciones_filtro/", function() use($app)
{

	$NOM_SERVICIO = "num_operaciones_filtro";

	$agencia = $app->request->post("agencia");

	//alquiler,venta,traspaso
	$tipo_filtro = $app->request->post("tipo_filtro");
	
	try{

		$app->response->headers->set("Content-type", "application/json");
		$app->response->status(200);

		$connection = getConnectionAgencia();
		//Lamada a procedimiento almacenado
		$query = "CALL numOperacionesFiltro(?,?)";
		$stmt = $connection->prepare($query);
		$stmt->bindParam(1, $tipo_filtro);
		$stmt->bindParam(2, $agencia);
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