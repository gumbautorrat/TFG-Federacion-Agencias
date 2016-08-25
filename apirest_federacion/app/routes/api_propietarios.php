<?php

if(!defined("SPECIALCONSTANT")) die("Acceso denegado");

//include "../conf/webServiceConf.php";

/*****************************************************************************************************

	Funcion que recibe peticiones POST a la url http://apirest/propietarios/insertar/
	y hace un insert de un propietario en la BD

	Param Entrada: La informacion del usuario que lanza el servicio y los datos del propietario
    a insertar.

    Param Salida: Mensaje JSON que nos informa del exito o error de la operacion.


********************************************************************************************************/

$app->post("/propietarios/insertar/", function() use($app)
{

	$NOM_SERVICIO = "propietarios/insertar";
	//datos del usuario a autenticar
	$user = $app->request->post("user");
	$pass = $app->request->post("pass");
	$agencia = $app->request->post("agencia");

	//datos del propietario a insertar
	$id_agencia       = $app->request->post("id_agencia");
	$dni              = $app->request->post("dni");
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
			$query = "CALL crearPropietario(?,?,?,?,?,?,?,?,?,?)";
			$stmt = $connection->prepare($query);
			$stmt->bindParam(1, $id_agencia);
			$stmt->bindParam(2, $dni);
			$stmt->bindParam(3, $nombre);
			$stmt->bindParam(4, $primer_apellido);
			$stmt->bindParam(5, $segundo_apellido);
			$stmt->bindParam(6, $direccion);
			$stmt->bindParam(7, $localidad);
			$stmt->bindParam(8, $provincia);
			$stmt->bindParam(9, $telefono);
			$stmt->bindParam(10,$email);
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
		if($e->getCode() == 23000){
			$app->response->body(json_encode(array("status" => "Ok", "message" => "Error -duplicate key in DB- El dni Insertado ya existe")));
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

	Funcion que recibe peticiones POST a la url http://apirest/propietarios/insertar_persona/
	y hace un insert de un propietario en la BD se diferencia de la función anterior, que antes de insertar
	en las dos tablas (propietarios y personas) comprueba si ya existe este propietario ya sea como cliente
	o como usuario.

	Param Entrada: La informacion del usuario que lanza el servicio y los datos del propietario
    a insertar.

    Param Salida: Mensaje JSON que nos informa del exito o error de la operacion.


********************************************************************************************************/

$app->post("/propietarios/insertar_persona/", function() use($app)
{

	$NOM_SERVICIO = "propietarios/insertar_persona";
	//datos del usuario a autenticar
	$user = $app->request->post("user");
	$pass = $app->request->post("pass");
	$agencia = $app->request->post("agencia");

	//datos del propietario a insertar
	$id_agencia       = $app->request->post("id_agencia");
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
			$query = "CALL crearPropietarioPersona(?,?,?,?,?,?,?,?,?,?)";
			$stmt = $connection->prepare($query);
			$stmt->bindParam(1, $id_agencia);
			$stmt->bindParam(2, $dni);
			$stmt->bindParam(3, $nombre);
			$stmt->bindParam(4, $primer_apellido);
			$stmt->bindParam(5, $segundo_apellido);
			$stmt->bindParam(6, $direccion);
			$stmt->bindParam(7, $localidad);
			$stmt->bindParam(8, $provincia);
			$stmt->bindParam(9, $telefono);
			$stmt->bindParam(10,$email);
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

	Funcion que recibe peticiones POST a la url http://apirest/propietarios/insertar_sin_persona/
	y hace un insert de un propietario en la BD difiere de las funciones anteriores en que solo inserta datos
	en la tabla propietarios.

	Param Entrada: La informacion del usuario que lanza el servicio y los datos del propietario
    a insertar.

    Param Salida: Mensaje JSON que nos informa del exito o error de la operacion.


********************************************************************************************************/

$app->post("/propietarios/insertar_sin_persona/", function() use($app)
{

	$NOM_SERVICIO = "propietarios/insertar_sin_persona";
	//datos del usuario a autenticar
	$user = $app->request->post("user");
	$pass = $app->request->post("pass");
	$agencia = $app->request->post("agencia");

	//datos del propietario a insertar
	$id_agencia       = $app->request->post("id_agencia");
	$dni      		  = $app->request->post("dni");
	
	try{

		$app->response->headers->set("Content-type", "application/json");
		$app->response->status(200);

		//Obtenemos si el usuario tiene permiso para ejecutar el servicio
		$acceso = controlAccessUserToWS($user,$agencia,$pass,$NOM_SERVICIO);

		if($acceso){//Autenticacion de usuario y permisos correcto

			$connection = getConnectionAgencia();
			//Lamada a procedimiento almacenado
			$query = "CALL crearPropietarioSinPersona(?,?)";
			$stmt = $connection->prepare($query);
			$stmt->bindParam(1, $id_agencia);
			$stmt->bindParam(2, $dni);
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

	Funcion que recibe peticiones PUT a la url http://apirest/propietarios/actualizar/
	y actualiza los datos de un propietario en la BD

	Param Entrada: La informacion del usuario que lanza el servicio y los datos del propietario
    a actualizar.

    Param Salida: Mensaje JSON que nos informa del exito o error de la operacion.


********************************************************************************************************/

$app->put("/propietarios/actualizar/:id_propietario", function($id_propietario) use($app)
{

	$NOM_SERVICIO = "propietarios/actualizar";
	//datos del usuario a autenticar
	$user = $app->request->post("user");
	$pass = $app->request->post("pass");
	$agencia = $app->request->post("agencia");

	//datos del propietario a actualizar
	$id_agencia       = $app->request->post("id_agencia");
	$dni              = $app->request->post("dni");
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
			$query = "CALL actualizarPropietario(?,?,?,?,?,?,?,?,?,?,?,?)";
			$stmt = $connection->prepare($query);
			$stmt->bindParam(1, $id_propietario);
			$stmt->bindParam(2, $id_agencia);
			$stmt->bindParam(3, $dni);
			$stmt->bindParam(4, $nombre);
			$stmt->bindParam(5, $primer_apellido);
			$stmt->bindParam(6, $segundo_apellido);
			$stmt->bindParam(7, $direccion);
			$stmt->bindParam(8, $localidad);
			$stmt->bindParam(9, $provincia);
			$stmt->bindParam(10,$telefono);
			$stmt->bindParam(11,$email);
			$stmt->bindParam(12,$agencia);
			$stmt->execute();
			$result = $stmt->fetchcolumn();
			$connection = null;

			if($result == 0){
				$app->response->body(json_encode(array("status" => "Ok", "message" => "-Error- El id del propietario que quiere actualizar no existe")));
			}else if($result == 1){
				$app->response->body(json_encode(array("status" => "Ok", "message" => "Operation Succesful")));
			}else{/* La peticion de actualizar propietario no esta autorizada, porque la agencia a la que pertenece el usuario
				     que hace la peticion no es 'Federacion' ni es la misma a la que pertenece el propietario */
				$app->response->body(json_encode(array("status" => "Ok", "message" => "Autentication Failure"))); 
			}
   		}else{
   			//Fallo en la Autenticacion de usaurio o de permisos
   			$app->response->body(json_encode(array("status" => "Ok", "message" => "Autentication Failure")));
   		}

	}
	catch(PDOException $e)
	{
		if($e->getCode() == 23000){
			$app->response->body(json_encode(array("status" => "Ok", "message" => "Error -duplicate key in DB- El dni al que intentas actualizar ya existe")));
		}else{
			$app->response->body(json_encode(array("status" => "Ok", "message" => " PDOException ".$e->getMessage())));
		}
	}
	catch(Exception $e)
	{
		$app->response->body(json_encode(array("status" => "Ok", "message" => " Exception ".$e->getMessage())));
	}
	
});

/*******************************************************************************************************************

	Funcion que recibe peticiones DELETE a la url http://apirest/propietarios/eliminar/id_propietario
	y elimina a un propietario de la BD

	Param Entrada: La informacion del usuario que lanza el servicio y los datos del propietario
    a eliminar.

    Param Salida: Mensaje JSON que nos informa del exito o error de la operacion.


*******************************************************************************************************************/

$app->delete("/propietarios/eliminar/:id_propietario", function($id_propietario) use($app)
{

	$NOM_SERVICIO = "propietarios/eliminar";;
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
			$query = "CALL eliminarPropietario(?,?)";
			$stmt = $connection->prepare($query);
			$stmt->bindParam(1, $id_propietario);
			$stmt->bindParam(2, $agencia);
			$stmt->execute();
			$result = $stmt->fetchcolumn();
			$connection = null;

			if($result == 0){
				$app->response->body(json_encode(array("status" => "Ok", "message" => "-Error- El id del propietario que quiere eliminar no existe")));
			}else if($result == 1){
				$app->response->body(json_encode(array("status" => "Ok", "message" => "Operation Succesful")));
			}else{/* La peticion de eliminar propietario no esta autorizada, porque la agencia a la que pertenece el usuario
				     que hace la peticion no es 'Federacion' ni es la misma a la que pertenece el propietario */
				$app->response->body(json_encode(array("status" => "Ok", "message" => "Autentication Failure"))); 
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

	Funcion que recibe peticiones GET a la url http://apirest/num_propietarios/
	y devuelve el numero de propietarios que existe en la BD en formato JSON

*******************************************************************************************************************/

$app->get("/num_propietarios/", function() use($app)
{

	$NOM_SERVICIO = "num_propietarios";
	
	try{

		$app->response->headers->set("Content-type", "application/json");
		$app->response->status(200);

		$connection = getConnectionAgencia();
		//Lamada a procedimiento almacenado
		$query = "CALL numPropietarios()";
		$stmt = $connection->prepare($query);
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

	Funcion que recibe peticiones POST a la url http://apirest/num_propietarios_filtro/
	y devuelve el numero de propietarios filtrado que existe en la BD en formato JSON

*******************************************************************************************************************/

$app->post("/num_propietarios_filtro/", function() use($app)
{

	$NOM_SERVICIO = "num_propietarios_filtro";

	$agencia = $app->request->post("agencia");

	//id,dni,nombre ...
	$tipo_filtro = $app->request->post("tipo_filtro");
	$filtro = $app->request->post("filtro");
	
	try{

		$app->response->headers->set("Content-type", "application/json");
		$app->response->status(200);

		$connection = getConnectionAgencia();

		if(MODO_PRUEBA == 1){
			$query = "CALL numPropietariosFiltroPrueba(?,?,?)";
		}else{
			$query = "CALL numPropietariosFiltro(?,?,?)";
		}
		
		$stmt = $connection->prepare($query);
		$stmt->bindParam(1, $tipo_filtro);
		$stmt->bindParam(2, $filtro);
		$stmt->bindParam(3, $agencia);
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

	Funcion que recibe peticiones GET a la url http://apirest/num_propietarios/agencia
	y devuelve el numero de propietarios que existe de una agencia determinada en formato JSON

*******************************************************************************************************************/

$app->get("/num_propietarios/:agencia", function($agencia) use($app)
{

	$NOM_SERVICIO = "num_propietarios_agencia";
	
	try{

		$app->response->headers->set("Content-type", "application/json");
		$app->response->status(200);

		$connection = getConnectionAgencia();
		//Lamada a procedimiento almacenado
		$query = "CALL numPropietariosAgencia(?)";
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

/***********************************************************************************************

	Funcion que recibe peticiones POST a la url http://apirest/propietario/:id_propietario
	y devuelve los datos del propietario que coincide con :id_propietario en formato JSON


************************************************************************************************/

$app->post("/propietario/:id_propietario", function($id_propietario) use($app)
{

	$NOM_SERVICIO = "propietario";
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
			$query = "CALL obtenerPropietario(?)";
			$stmt = $connection->prepare($query);
			$stmt->bindParam(1, $id_propietario);
			$stmt->execute();
			$propietario = $stmt->fetch();
			$connection = null;

			$app->response->body(json_encode($propietario));
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

	Funcion que recibe peticiones POST a la url http://apirest/propietarios/
	y devuelve todos los propietarios de la BD en formato JSON


************************************************************************************************/

$app->post("/propietarios/", function() use($app)
{

	$NOM_SERVICIO = "propietarios";
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
			$query = "CALL obtenerPropietarios()";
			
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

	Funcion que recibe peticiones POST a la url http://apirest/propietarios/agencia
	y devuelve los propietarios de una agencia en formato JSON


************************************************************************************************/

$app->post("/propietarios/:id_agencia", function($id_agencia) use($app)
{

	$NOM_SERVICIO = "propietarios_agencia";
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
			$query = "CALL obtenerPropietariosAgencia(?)";
			
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

	Funcion que recibe peticiones POST a la url http://apirest/propietarios_paginado/
	y devuelve los propietarios que hay en la BD de forma paginada en formato JSON
	Servicio usado por ROOT

************************************************************************************************/

$app->post("/propietarios_paginado/", function() use($app)
{

	$NOM_SERVICIO = "propietarios_paginado";

	//datos del usuario a autenticar
	$user = $app->request->post("user");
	$pass = $app->request->post("pass");

	//parametros paginacion
	$limit = $app->request->post("limit");
	$offset = $app->request->post("offset");

	try{

		$app->response->headers->set("Content-type", "application/json");
		$app->response->status(200);

		//Obtenemos si el ususuario que en este caso no esta vinculado a ninguna agencia, tiene permiso para ejecutar el servicio
		$acceso = controlAccessUserToWSRoot($user,$pass,$NOM_SERVICIO);

		if($acceso){//Autenticacion de usuario y permisos correcto
			$connection = getConnectionAgencia();
			$query = "CALL obtenerPropietariosPaginado(?,?)";
			$stmt = $connection->prepare($query);
			$stmt->bindParam(1, $limit);
			$stmt->bindParam(2, $offset);
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

	Funcion que recibe peticiones POST a la url http://apirest/propietarios_paginado_filtro/
	y devuelve los propietarios de una agencia determinada o de todas la agencias dependiendo del tipo de 
	acceso del usuairo (agencia o federacion) cuyos resultados son mostrados de forma paginada y con la 
	posibilidad de ser filtrados, estos datos son devueltos en formato JSON

************************************************************************************************/

$app->post("/propietarios_paginado_filtro/", function() use($app)
{

	$NOM_SERVICIO = "propietarios_paginado_filtro";

	//datos del usuario a autenticar
	$user = $app->request->post("user");
	$pass = $app->request->post("pass");
	$agencia = $app->request->post("agencia");

	//parametros paginacion
	$limit = $app->request->post("limit");
	$offset = $app->request->post("offset");

	//id,dni,nombre ...
	$tipo_filtro = $app->request->post("tipo_filtro");
	$filtro = $app->request->post("filtro");

	try{

		$app->response->headers->set("Content-type", "application/json");
		$app->response->status(200);

		//Obtenemos si el ususuario tiene permiso para ejecutar el servicio
		$acceso = controlAccessUserToWS($user,$agencia,$pass,$NOM_SERVICIO);

		if($acceso){//Autenticacion de usuario y permisos correcto
			$connection = getConnectionAgencia();

			if(MODO_PRUEBA == 1){ // Si estamos en modo prueba mostramos todos los propietarios
				$query = "CALL obtenerPropietariosPaginadoFiltroPrueba(?,?,?,?,?)";
			}else{ // Mostramos solo los propietarios de la agencia a la que pertenece el usuario que hace la peticion
				$query = "CALL obtenerPropietariosPaginadoFiltro(?,?,?,?,?)";
			}
			
			$stmt = $connection->prepare($query);
			$stmt->bindParam(1, $limit);
			$stmt->bindParam(2, $offset);
			$stmt->bindParam(3, $tipo_filtro);
			$stmt->bindParam(4, $filtro);
			$stmt->bindParam(5, $agencia);
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

	Funcion que recibe peticiones POST a la url http://apirest/existeDniPropietario/:dni
	y devuelve si existe o no un determinado dni(id_persona) en la tabla propietarios formato JSON


************************************************************************************************/

$app->post("/existeDniPropietario/:dni", function($dni) use($app)
{

	$NOM_SERVICIO = "existe_dni_propietario";
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
			//Lamada a funcion almacenada
			$query = "SELECT existeDniPropietario(?) as result";
			$stmt  = $connection->prepare($query);
			$stmt ->bindParam(1, $dni);
			$stmt ->execute();
			$res = $stmt ->fetch();
			$connection = null;

			$app->response->body(json_encode($res));
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

	Funcion que recibe peticiones POST a la url http://apirest/existeIdPropietario/:id_propietario
	y devuelve si existe o no un determinado id_propietario en la tabla propietarios formato JSON


************************************************************************************************/

$app->post("/existeIdPropietario/:id_propietario", function($id_propietario) use($app)
{

	$NOM_SERVICIO = "existe_id_propietario";
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
			//Lamada a funcion almacenada
			$query = "SELECT existeIdPropietario(?) as result";
			$stmt  = $connection->prepare($query);
			$stmt ->bindParam(1, $id_propietario);
			$stmt ->execute();
			$res = $stmt ->fetch();
			$connection = null;

			$app->response->body(json_encode($res));
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