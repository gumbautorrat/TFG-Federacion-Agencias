<?php

if(!defined("SPECIALCONSTANT")) die("Acceso denegado");

//include "../conf/webServiceConf.php";

/*****************************************************************************************************

	Funcion que recibe peticiones POST a la url http://apirest/usuarios/insertar/
	y hace un insert de un usuario en la BD

	Param Entrada: La informacion del usuario que lanza el servicio y los datos del usuario
    a insertar.

    Param Salida: Mensaje JSON que nos informa del exito o error de la operacion.


********************************************************************************************************/

$app->post("/usuarios/insertar/", function() use($app)
{

	$NOM_SERVICIO = "usuarios/insertar";
	//datos del usuario a autenticar
	$user = $app->request->post("user");
	$passwd = $app->request->post("passwd");
	$agencia = $app->request->post("agencia");

	//datos del usuario a insertar
	$id_agencia       = $app->request->post("id_agencia");
	$usuario          = $app->request->post("usuario");
	$pass             = $app->request->post("pass");
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
		$acceso = controlAccessUserToWS($user,$agencia,$passwd,$NOM_SERVICIO);

		if($acceso){//Autenticacion de usuario y permisos correcto

			$connection = getConnectionAgencia();
			//Lamada a procedimiento almacenado
			$query = "CALL crearUsuario(?,?,?,?,?,?,?,?,?,?,?,?)";
			$stmt = $connection->prepare($query);
			$stmt->bindParam(1, $id_agencia);
			$stmt->bindParam(2, $usuario);
			$stmt->bindParam(3, $pass);
			$stmt->bindParam(4, $dni);
			$stmt->bindParam(5, $nombre);
			$stmt->bindParam(6, $primer_apellido);
			$stmt->bindParam(7, $segundo_apellido);
			$stmt->bindParam(8, $direccion);
			$stmt->bindParam(9, $localidad);
			$stmt->bindParam(10, $provincia);
			$stmt->bindParam(11, $telefono);
			$stmt->bindParam(12,$email);
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

	Funcion que recibe peticiones POST a la url http://apirest/usuarios/insertar_persona/
	y hace un insert de un usuario en la BD se diferencia de la función anterior, que antes de insertar
	en las dos tablas (usuarios y personas) comprueba si ya existe este propietario ya sea como cliente
	o como propietario.

	Param Entrada: La informacion del usuario que lanza el servicio y los datos del usuario
    a insertar.

    Param Salida: Mensaje JSON que nos informa del exito o error de la operacion.


********************************************************************************************************/

$app->post("/usuarios/insertar_persona/", function() use($app)
{

	$NOM_SERVICIO = "usuarios/insertar_persona";
	//datos del usuario a autenticar
	$user = $app->request->post("user");
	$passwd = $app->request->post("passwd");
	$agencia = $app->request->post("agencia");

	//datos del usuario a insertar
	$id_agencia       = $app->request->post("id_agencia");
	$usuario          = $app->request->post("usuario");
	$pass             = $app->request->post("pass");
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
		$acceso = controlAccessUserToWS($user,$agencia,$passwd,$NOM_SERVICIO);

		if($acceso){//Autenticacion de usuario y permisos correcto

			$connection = getConnectionAgencia();
			//Lamada a procedimiento almacenado
			$query = "CALL crearUsuarioPersona(?,?,?,?,?,?,?,?,?,?,?,?)";
			$stmt = $connection->prepare($query);
			$stmt->bindParam(1, $id_agencia);
			$stmt->bindParam(2, $usuario);
			$stmt->bindParam(3, $pass);
			$stmt->bindParam(4, $dni);
			$stmt->bindParam(5, $nombre);
			$stmt->bindParam(6, $primer_apellido);
			$stmt->bindParam(7, $segundo_apellido);
			$stmt->bindParam(8, $direccion);
			$stmt->bindParam(9, $localidad);
			$stmt->bindParam(10, $provincia);
			$stmt->bindParam(11, $telefono);
			$stmt->bindParam(12,$email);
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

	Funcion que recibe peticiones POST a la url http://apirest/usuarios/insertar_sin_usuario/
	y hace un insert de un usuario en la BD, difiere de las funciones anteriores en que solo inserta datos
	en la tabla usuarios.

	Param Entrada: La informacion del usuario que lanza el servicio y los datos del usuario
    a insertar.

    Param Salida: Mensaje JSON que nos informa del exito o error de la operacion.


********************************************************************************************************/

$app->post("/usuarios/insertar_sin_persona/", function() use($app)
{

	$NOM_SERVICIO = "usuarios/insertar_sin_persona/";
	//datos del usuario a autenticar
	$user = $app->request->post("user");
	$passwd = $app->request->post("passwd");
	$agencia = $app->request->post("agencia");

	//datos del usuario a insertar
	$id_agencia       = $app->request->post("id_agencia");
	$usuario          = $app->request->post("usuario");
	$pass             = $app->request->post("pass");
	$dni              = $app->request->post("dni");
	
	try{

		$app->response->headers->set("Content-type", "application/json");
		$app->response->status(200);

		//Obtenemos si el usuario tiene permiso para ejecutar el servicio
		$acceso = controlAccessUserToWS($user,$agencia,$passwd,$NOM_SERVICIO);

		if($acceso){//Autenticacion de usuario y permisos correcto

			$connection = getConnectionAgencia();
			//Lamada a procedimiento almacenado
			$query = "CALL crearUsuarioSinPersona(?,?,?,?)";
			$stmt = $connection->prepare($query);
			$stmt->bindParam(1, $id_agencia);
			$stmt->bindParam(2, $usuario);
			$stmt->bindParam(3, $pass);
			$stmt->bindParam(4, $dni);
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

	Funcion que recibe peticiones PUT a la url http://apirest/usuarios/actualizar/
	y actualiza los datos de un usuario en la BD

	Param Entrada: La informacion del usuario que lanza el servicio y los datos del usuario
    a actualizar.

    Param Salida: Mensaje JSON que nos informa del exito o error de la operacion.


********************************************************************************************************/

$app->put("/usuarios/actualizar/:id_usuario", function($id_usuario) use($app)
{

	$NOM_SERVICIO = "usuarios/actualizar";
	//datos del usuario a autenticar
	$user = $app->request->post("user");
	$passwd = $app->request->post("passwd");
	$agencia = $app->request->post("agencia");

	//datos del usuario a actualizar
	$id_agencia       = $app->request->post("id_agencia");
	$usuario          = $app->request->post("usuario");
	$dni              = $app->request->post("dni");
	$nombre           = $app->request->post("nombre");
	$primer_apellido  = $app->request->post("primer_apellido");
	$segundo_apellido = $app->request->post("segundo_apellido");
	$direccion        = $app->request->post("direccion");
	$localidad        = $app->request->post("localidad");
	$provincia        = $app->request->post("provincia");
	$telefono         = $app->request->post("telefono");
	$email            = $app->request->post("email");
	

		/*$app->response->headers->set("Content-type", "application/json");
		$app->response->status(200);
		$app->response->body(json_encode(array("status" => "Ok", "message" => $passwd)));*/

	try{

		$app->response->headers->set("Content-type", "application/json");
		$app->response->status(200);

		//Obtenemos si el usuario tiene permiso para ejecutar el servicio
		$acceso = controlAccessUserToWS($user,$agencia,$passwd,$NOM_SERVICIO);

		if($acceso){//Autenticacion de usuario y permisos correcto
			$connection = getConnectionAgencia();
			//Lamada a procedimiento almacenado
			$query = "CALL actualizarUsuario(?,?,?,?,?,?,?,?,?,?,?,?,?)";
			$stmt = $connection->prepare($query);
			$stmt->bindParam(1, $id_usuario);
			$stmt->bindParam(2, $id_agencia);
			$stmt->bindParam(3, $usuario);
			$stmt->bindParam(4, $dni);
			$stmt->bindParam(5, $nombre);
			$stmt->bindParam(6, $primer_apellido);
			$stmt->bindParam(7, $segundo_apellido);
			$stmt->bindParam(8, $direccion);
			$stmt->bindParam(9, $localidad);
			$stmt->bindParam(10,$provincia);
			$stmt->bindParam(11,$telefono);
			$stmt->bindParam(12,$email);
			$stmt->bindParam(13,$agencia);
			$stmt->execute();
			$result = $stmt->fetchcolumn();
			$connection = null;

			if($result == 0){
				$app->response->body(json_encode(array("status" => "Ok", "message" => "-Error- El id del usuario que quiere actualizar no existe")));
			}else if($result == 1){
				$app->response->body(json_encode(array("status" => "Ok", "message" => "Operation Succesful")));
			}else{/* La peticion de actualizar usuario no esta autorizada, porque la agencia a la que pertenece el usuario
				     que hace la peticion no es 'Federacion' ni es la misma a la que pertenece el usuario */
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

	Funcion que recibe peticiones DELETE a la url http://apirest/usuarios/eliminar/id_usuario
	y elimina un usuario de la BD

	Param Entrada: La informacion del usuario que lanza el servicio y los datos del usuario
    a eliminar.

    Param Salida: Mensaje JSON que nos informa del exito o error de la operacion.


*******************************************************************************************************************/

$app->delete("/usuarios/eliminar/:id_usuario", function($id_usuario) use($app)
{
	
	$NOM_SERVICIO = "usuarios/eliminar";
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
			$query = "CALL eliminarUsuario(?,?)";
			$stmt = $connection->prepare($query);
			$stmt->bindParam(1, $id_usuario);
			$stmt->bindParam(2, $agencia);
			$stmt->execute();
			$result = $stmt->fetchcolumn();
			$connection = null;

			if($result == 0){
				$app->response->body(json_encode(array("status" => "Ok", "message" => "-Error- El id del usuario que quiere eliminar no existe")));
			}else if($result == 1){
				$app->response->body(json_encode(array("status" => "Ok", "message" => "Operation Succesful")));
			}else{/* La peticion de eliminar usuario no esta autorizada, porque la agencia a la que pertenece el usuario
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

	Funcion que recibe peticiones POST a la url http://apirest/num_usuarios_filtro/
	y devuelve el numero de usuarios filtrado que existe en la BD en formato JSON

*******************************************************************************************************************/

$app->post("/num_usuarios_filtro/", function() use($app)
{

	$NOM_SERVICIO = "num_usuarios_filtro";

	$agencia = $app->request->post("agencia");

	//id,dni,nombre ...
	$tipo_filtro = $app->request->post("tipo_filtro");
	$filtro = $app->request->post("filtro");
	
	try{

		$app->response->headers->set("Content-type", "application/json");
		$app->response->status(200);

		$connection = getConnectionAgencia();

		if(MODO_PRUEBA == 1){
			$query = "CALL numUsuariosFiltroPrueba(?,?,?)";
		}else{
			$query = "CALL numUsuariosFiltro(?,?,?)";
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

	Funcion que recibe peticiones POST a la url http://apirest/usuario/:id_usuario
	y devuelve los datos del usuario que coincide con :id_usuario en formato JSON


************************************************************************************************/

$app->post("/usuario/:id_usuario", function($id_usuario) use($app)
{

	$NOM_SERVICIO = "usuario";
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
			$query = "CALL obtenerUsuario(?)";
			$stmt = $connection->prepare($query);
			$stmt->bindParam(1, $id_usuario);
			$stmt->execute();
			$usuario = $stmt->fetch();
			$connection = null;

			$app->response->body(json_encode($usuario));
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

	Funcion que recibe peticiones POST a la url http://apirest/usuarios/
	y devuelve los usuarios que existen en la BD en formato JSON


************************************************************************************************/

$app->post("/usuarios/", function() use($app)
{

	$NOM_SERVICIO = "usuarios";
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
			$query = "CALL obtenerUsuarios()";
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

	Funcion que recibe peticiones POST a la url http://apirest/usuarios/comboBox
	y devuelve el nombre e id de los usuarios que existen en la BD en formato JSON
	para rellenar un comboBox en la vista gestion de permisos


************************************************************************************************/

$app->get("/usuarios/comboBox/", function() use($app)
{

	$NOM_SERVICIO = "usuarios_comboBox";

	try{

		$app->response->headers->set("Content-type", "application/json");
		$app->response->status(200);

		$connection = getConnectionAgencia();
		$query = "CALL obtenerUsuariosComboBox()";
		$stmt = $connection->prepare($query);
		$stmt->execute();
		$usuarios = $stmt->fetchAll();
		$connection = null;

		$app->response->body(json_encode($usuarios));
		
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

	Funcion que recibe peticiones POST a la url http://apirest/usuarios/id_agencia
	y devuelve los usuarios de una agencia en formato JSON


************************************************************************************************/

$app->post("/usuarios/:id_agencia", function($id_agencia) use($app)
{

	$NOM_SERVICIO = "usuarios_agencia";
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
			$query = "CALL obtenerUsuariosAgencia(?)";
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

	Funcion que recibe peticiones POST a la url http://apirest/usuarios_paginado_filtro/
	y devuelve los usuarios de una agencia determinada o de todas la agencias dependiendo del tipo de 
	acceso del usuario (agencia o federacion) cuyos resultados son mostrados de forma paginada y con la 
	posibilidad de ser filtrados, estos datos son devueltos en formato JSON

************************************************************************************************/

$app->post("/usuarios_paginado_filtro/", function() use($app)
{

	$NOM_SERVICIO = "usuarios_paginado_filtro";

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

			if(MODO_PRUEBA == 1){ // Si estamos en modo prueba mostramos todos los usuarios
				$query = "CALL obtenerUsuariosPaginadoFiltroPrueba(?,?,?,?,?)";
			}else{ // Mostramos solo los usuarios de la agencia a la que pertenece el usuario que hace la peticion
				$query = "CALL obtenerUsuariosPaginadoFiltro(?,?,?,?,?)";
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

	Funcion que recibe peticiones POST a la url http://apirest/existeDniUsuario/:dni
	y devuelve si existe o no un determinado dni(id_persona) en la tabla usuarios formato JSON


************************************************************************************************/

$app->post("/existeDniUsuario/:dni", function($dni) use($app)
{

	$NOM_SERVICIO = "existe_dni_usuario";
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
			$query = "SELECT existeDniUsuario(?) as result";
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

	Funcion que recibe peticiones POST a la url http://apirest/existeIdUsuario/:id_usuario
	y devuelve si existe o no un determinado id_usuario en la tabla usuarios formato JSON


************************************************************************************************/

$app->post("/existeIdUsuario/:id_usuario", function($id_usuario) use($app)
{

	$NOM_SERVICIO = "existe_id_usuario";
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
			$query = "SELECT existeIdUsuario(?) as result";
			$stmt  = $connection->prepare($query);
			$stmt ->bindParam(1, $id_usuario);
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

	Funcion que recibe peticiones POST a la url http://apirest/login/
	y devuelve si el usuario se ha logeado o no correctamente en el sistema en formato JSON


************************************************************************************************/

$app->post("/usuarios/login/", function() use($app)
{

	$NOM_SERVICIO = "usuarios/login";
	//datos del usuario a autenticar
	$user = $app->request->post("user");
	$pass = $app->request->post("pass");
	$agencia = $app->request->post("agencia"); //Desde que agencia se intenta logear un usuario

	try{

		$app->response->headers->set("Content-type", "application/json");
		$app->response->status(200);
		
 		//Obtenemos el hash del usuario que dice ser
		$dbHash = getHash($user,$agencia);
		//Comprobamos que el hash devuelto (si el usuario y pass no son correctos y no pertenece a la agencia de la que dice ser, no devolverá hash) exista en la BD.
		$res = hashExists($dbHash,$pass);
		if($res == 1){
			$app->response->body(json_encode(array("status" => "Ok", "result" => "true")));
		}else{
			$app->response->body(json_encode(array("status" => "Ok", "result" => "false")));
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