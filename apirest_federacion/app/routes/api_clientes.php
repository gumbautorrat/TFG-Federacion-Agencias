<?php

if(!defined("SPECIALCONSTANT")) die("Acceso denegado");

//include "../conf/webServiceConf.php";

/*****************************************************************************************************

	Funcion que recibe peticiones POST a la url http://apirest/clientes/insertar/
	y hace un insert de un cliente en la BD

	Param Entrada: La informacion del usuario que lanza el servicio y los datos del cliente
    a insertar.

    Param Salida: Mensaje JSON que nos informa del exito o error de la operacion.


********************************************************************************************************/

$app->post("/clientes/insertar/", function() use($app)
{

	$NOM_SERVICIO = "clientes/insertar";
	//datos del usuario a autenticar
	$user = $app->request->post("user");
	$pass = $app->request->post("pass");
	$agencia = $app->request->post("agencia");

	//datos del cliente a insertar
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
			$query = "CALL crearCliente(?,?,?,?,?,?,?,?,?,?)";
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

	Funcion que recibe peticiones POST a la url http://apirest/clientes/insertar_persona/
	y hace un insert de un cliente en la BD se diferencia de la función anterior, que antes de insertar
	en las dos tablas (clientes y personas) comprueba si ya existe este cliente ya sea como propietario
	o como usuario.

	Param Entrada: La informacion del usuario que lanza el servicio y los datos del cliente
    a insertar.

    Param Salida: Mensaje JSON que nos informa del exito o error de la operacion.


********************************************************************************************************/

$app->post("/clientes/insertar_persona/", function() use($app)
{

	$NOM_SERVICIO = "clientes/insertar_persona";
	//datos del usuario a autenticar
	$user = $app->request->post("user");
	$pass = $app->request->post("pass");
	$agencia = $app->request->post("agencia");

	//datos del cliente a insertar
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
			$query = "CALL crearClientePersona(?,?,?,?,?,?,?,?,?,?)";
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

	Funcion que recibe peticiones POST a la url http://apirest/clientes/insertar_sin_persona/
	y hace un insert de un cliente en la BD difiere de las funciones anteriores en que solo inserta datos
	en la tabla clientes.

	Param Entrada: La informacion del usuario que lanza el servicio y los datos del cliente
    a insertar.

    Param Salida: Mensaje JSON que nos informa del exito o error de la operacion.


********************************************************************************************************/

$app->post("/clientes/insertar_sin_persona/", function() use($app)
{

	$NOM_SERVICIO = "clientes/insertar_sin_persona";
	//datos del usuario a autenticar
	$user = $app->request->post("user");
	$pass = $app->request->post("pass");
	$agencia = $app->request->post("agencia");

	//datos del cliente a insertar
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
			$query = "CALL crearClienteSinPersona(?,?)";
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

	Funcion que recibe peticiones PUT a la url http://apirest/clientes/actualizar/
	y actualiza los datos de un cliente en la BD

	Param Entrada: La informacion del usuario que lanza el servicio y los datos del cliente
    a actualizar.

    Param Salida: Mensaje JSON que nos informa del exito o error de la operacion.


********************************************************************************************************/

$app->put("/clientes/actualizar/:id_cliente", function($id_cliente) use($app)
{

	$NOM_SERVICIO = "clientes/actualizar";
	//datos del usuario a autenticar
	$user = $app->request->post("user");
	$pass = $app->request->post("pass");
	$agencia = $app->request->post("agencia");

	//datos del cliente a actualizar
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
			$query = "CALL actualizarCliente(?,?,?,?,?,?,?,?,?,?,?,?)";
			$stmt = $connection->prepare($query);
			$stmt->bindParam(1, $id_cliente);
			$stmt->bindParam(2, $id_agencia);
			$stmt->bindParam(3, $dni);
			$stmt->bindParam(4, $nombre);
			$stmt->bindParam(5, $primer_apellido);
			$stmt->bindParam(6, $segundo_apellido);
			$stmt->bindParam(7, $direccion);
			$stmt->bindParam(8, $localidad);
			$stmt->bindParam(9, $provincia);
			$stmt->bindParam(10, $telefono);
			$stmt->bindParam(11,$email);
			$stmt->bindParam(12,$agencia);
			$stmt->execute();
			$result = $stmt->fetchcolumn();
			$connection = null;

			if($result == 0){
				$app->response->body(json_encode(array("status" => "Ok", "message" => "-Error- El id del cliente que quiere actualizar no existe")));
			}else if($result == 1){
				$app->response->body(json_encode(array("status" => "Ok", "message" => "Operation Succesful")));
			}else{/* La peticion de actualizar cliente no esta autorizada, porque la agencia a la que pertenece el usuario
				     que hace la peticion no es 'Federacion' ni es la misma a la que pertenece el cliente */
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

	Funcion que recibe peticiones DELETE a la url http://apirest/clientes/eliminar/id_cliente
	y elimina un cliente de la BD

	Param Entrada: La informacion del usuario que lanza el servicio y los datos del cliente
    a eliminar.

    Param Salida: Mensaje JSON que nos informa del exito o error de la operacion.


*******************************************************************************************************************/

$app->delete("/clientes/eliminar/:id_cliente", function($id_cliente) use($app)
{

	$NOM_SERVICIO = "clientes/eliminar";
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
			$query = "CALL eliminarCliente(?,?)";
			$stmt = $connection->prepare($query);
			$stmt->bindParam(1, $id_cliente);
			$stmt->bindParam(2, $agencia);
			$stmt->execute();
			$result = $stmt->fetchcolumn();
			$connection = null;

			if($result == 0){
				$app->response->body(json_encode(array("status" => "Ok", "message" => "-Error- El id del cliente que quiere eliminar no existe")));
			}else if($result == 1){
				$app->response->body(json_encode(array("status" => "Ok", "message" => "Operation Succesful")));
			}else{/* La peticion de eliminar cliente no esta autorizada, porque la agencia a la que pertenece el usuario
				     que hace la peticion no es 'Federacion' ni es la misma a la que pertenece el cliente */
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

	Funcion que recibe peticiones POST a la url http://apirest/num_clientes_filtro/
	y devuelve el numero de clientes filtrado que existe en la BD en formato JSON

*******************************************************************************************************************/

$app->post("/num_clientes_filtro/", function() use($app)
{

	$NOM_SERVICIO = "num_clientes_filtro";

	$agencia = $app->request->post("agencia");

	//id,dni,nombre ...
	$tipo_filtro = $app->request->post("tipo_filtro");
	$filtro = $app->request->post("filtro");
	
	try{

		$app->response->headers->set("Content-type", "application/json");
		$app->response->status(200);

		$connection = getConnectionAgencia();

		if(MODO_PRUEBA == 1){
			$query = "CALL numClientesFiltroPrueba(?,?,?)";
		}else{
			$query = "CALL numClientesFiltro(?,?,?)";
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

/***********************************************************************************************

	Funcion que recibe peticiones POST a la url http://apirest/cliente/:id_cliente
	y devuelve los datos del cliente que coincide con :id_cliente en formato JSON


************************************************************************************************/

$app->post("/cliente/:id_cliente", function($id_cliente) use($app)
{

	$NOM_SERVICIO = "cliente";
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
			$query = "CALL obtenerCliente(?)";
			$stmt = $connection->prepare($query);
			$stmt->bindParam(1, $id_cliente);
			$stmt->execute();
			$cliente = $stmt->fetch();
			$connection = null;

			$app->response->body(json_encode($cliente));
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

	Funcion que recibe peticiones POST a la url http://apirest/clientes/
	y devuelve los clientes que existen en la BD en formato JSON


************************************************************************************************/

$app->post("/clientes/", function() use($app)
{

	$NOM_SERVICIO = "clientes";
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
			$query = "CALL obtenerClientes()";
			$stmt = $connection->prepare($query);
			$stmt->execute();
			$clientes = $stmt->fetchAll();
			$connection = null;

			$app->response->body(json_encode($clientes));
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

	Funcion que recibe peticiones POST a la url http://apirest/clientes/id_agencia
	y devuelve los clientes de una agencia en formato JSON


************************************************************************************************/

$app->post("/clientes/:id_agencia", function($id_agencia) use($app)
{

	$NOM_SERVICIO = "clientes_agencia";
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
			$query = "CALL obtenerClientesAgencia(?)";
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

	Funcion que recibe peticiones POST a la url http://apirest/clientes_paginado/id_agencia
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

/***********************************************************************************************

	Funcion que recibe peticiones POST a la url http://apirest/clientes_paginado_filtro/
	y devuelve los clientes de una agencia determinada o de todas la agencias dependiendo del tipo de 
	acceso del usuario (agencia o federacion) cuyos resultados son mostrados de forma paginada y con la 
	posibilidad de ser filtrados, estos datos son devueltos en formato JSON

************************************************************************************************/

$app->post("/clientes_paginado_filtro/", function() use($app)
{

	$NOM_SERVICIO = "clientes_paginado_filtro";

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

			if(MODO_PRUEBA == 1){ // Si estamos en modo prueba mostramos todos los clientes
				$query = "CALL obtenerClientesPaginadoFiltroPrueba(?,?,?,?,?)";
			}else{ // Mostramos solo los clientes de la agencia a la que pertenece el usuario que hace la peticion
				$query = "CALL obtenerClientesPaginadoFiltro(?,?,?,?,?)";
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

	Funcion que recibe peticiones POST a la url http://apirest/existeDniCliente/:id_cliente
	y devuelve si existe o no un determinado dni(id_persona) en la tabla clientes formato JSON


************************************************************************************************/

$app->post("/existeDniCliente/:dni", function($dni) use($app)
{

	$NOM_SERVICIO = "existe_dni_cliente";
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
			$query = "SELECT existeDniCliente(?) as result";
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

	Funcion que recibe peticiones POST a la url http://apirest/existeIdCliente/:id_cliente
	y devuelve si existe o no un determinado id_cliente en la tabla clientes formato JSON


************************************************************************************************/

$app->post("/existeIdCliente/:id_cliente", function($id_cliente) use($app)
{

	$NOM_SERVICIO = "existe_id_cliente";
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
			$query = "SELECT existeIdCliente(?) as result";
			$stmt  = $connection->prepare($query);
			$stmt ->bindParam(1, $id_cliente);
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