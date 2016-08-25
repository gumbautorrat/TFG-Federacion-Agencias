<?php

if(!defined("SPECIALCONSTANT")) die("Acceso denegado"); 

/*****************************************************************************************************

	Funcion que recibe peticiones POST a la url http://apirest/permisos/insertar/
	y hace un insert de un permiso en la BD

	Param Entrada: La informacion del usuario que lanza el servicio y los datos del permiso
    a insertar.

    Param Salida: Mensaje JSON que nos informa del exito o error de la operacion.


********************************************************************************************************/

$app->post("/permisos/insertar/", function() use($app)
{

	$NOM_SERVICIO = "permisos/insertar";
	//datos del usuario a autenticar
	$user = $app->request->post("user");
	$pass = $app->request->post("pass");
	$agencia = $app->request->post("agencia");

	//datos del permiso a insertar
	$servicio = $app->request->post("servicio");
	$id_usuario = $app->request->post("id_usuario");
	
	try{

		$app->response->headers->set("Content-type", "application/json");
		$app->response->status(200);

		//Obtenemos si el usuario tiene permiso para ejecutar el servicio
		$acceso = controlAccessUserToWS($user,$agencia,$pass,$NOM_SERVICIO);

		if($acceso){//Autenticacion de usuario y permisos correcto

			$connection = getConnectionAgencia();
			//Lamada a procedimiento almacenado
			$query = "CALL crearPermiso(?,?)";
			$stmt = $connection->prepare($query);
			$stmt->bindParam(1, $servicio);
			$stmt->bindParam(2, $id_usuario);
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

	Funcion que recibe peticiones PUT a la url http://apirest/permisos/actualizar/
	y actualiza los datos de un permiso en la BD

	Param Entrada: La informacion del usuario que lanza el servicio y los datos del permiso
    a actualizar.

    Param Salida: Mensaje JSON que nos informa del exito o error de la operacion.


********************************************************************************************************/

$app->put("/permisos/actualizar/:id_usuario", function($id_usuario) use($app)
{

	$NOM_SERVICIO = "permisos/actualizar";
	//datos del usuario a autenticar
	$user = $app->request->post("user");
	$pass = $app->request->post("pass");
	$agencia = $app->request->post("agencia");

	//datos del permiso a actualizar
	$servicio = $app->request->post("servicio");

	$servicio_upd = $app->request->post("servicio_upd");
	$id_usuario_upd = $app->request->post("id_usuario_upd");
	
	try{

		$app->response->headers->set("Content-type", "application/json");
		$app->response->status(200);

		//Obtenemos si el usuario tiene permiso para ejecutar el servicio
		$acceso = controlAccessUserToWS($user,$agencia,$pass,$NOM_SERVICIO);

		if($acceso){//Autenticacion de usuario y permisos correcto
			$connection = getConnectionAgencia();
			//Lamada a procedimiento almacenado
			$query = "CALL actualizarPermisos(?,?,?,?)";
			$stmt = $connection->prepare($query);
			$stmt->bindParam(1, $servicio);
			$stmt->bindParam(2, $id_usuario);
			$stmt->bindParam(3, $servicio_upd);
			$stmt->bindParam(4, $id_usuario_upd);
			$stmt->execute();
			$result = $stmt->fetchcolumn();
			$connection = null;

			if($result == 0){
				$app->response->body(json_encode(array("status" => "Ok", "message" => "-Error- El identificador del permiso que quiere actualizar no existe")));
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

	Funcion que recibe peticiones DELETE a la url http://apirest/permisos/eliminar/servicio
	y elimina un permiso de la BD

	Param Entrada: La informacion de usuario que lanza el servicio y los datos del permiso
    a eliminar.

    Param Salida: Mensaje JSON que nos informa del exito o error de la operacion.


*******************************************************************************************************************/

$app->delete("/permisos/eliminar/:id_usuario", function($id_usuario) use($app)
{

	$NOM_SERVICIO = "permisos/eliminar";
	//datos del usuario a autenticar
	$user = $app->request->post("user");
	$pass = $app->request->post("pass");
	$agencia = $app->request->post("agencia");

	//datos del permiso a eliminar
	$servicio = $app->request->post("servicio");
	
	try{

		$app->response->headers->set("Content-type", "application/json");
		$app->response->status(200);

		//Obtenemos si el usuario tiene permiso para ejecutar el servicio
		$acceso = controlAccessUserToWS($user,$agencia,$pass,$NOM_SERVICIO);

		if($acceso){//Autenticacion de usuario y permisos correcto
			$connection = getConnectionAgencia();
			//Lamada a procedimiento almacenado
			$query = "CALL eliminarPermiso(?,?)";
			$stmt = $connection->prepare($query);
			$stmt->bindParam(1, $servicio);
			$stmt->bindParam(2, $id_usuario);
			$stmt->execute();
			$result = $stmt->fetchcolumn();
			$connection = null;

			if($result == 0){
				$app->response->body(json_encode(array("status" => "Ok", "message" => "-Error- El identificador del permiso que quiere eliminar no existe")));
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

	Funcion que recibe peticiones POST a la url http://apirest/permisos/id_usuario
	y devuelve los permisos que tiene de un determinado usuario en formato JSON


************************************************************************************************/

$app->post("/permisos/:id_usuario", function($id_usuario) use($app)
{

	$NOM_SERVICIO = "permisos";
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
			$query = "CALL obtenerPermisos(?)";
			
			$stmt = $connection->prepare($query);
			$stmt->bindParam(1, $id_usuario);
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

	Funcion que recibe peticiones POST a la url http://apirest/permisos_existentes/
	y devuelve todos los diferentes permisos (servicios) que existen, en formato JSON


************************************************************************************************/

$app->post("/permisos_existentes/", function() use($app)
{

	$NOM_SERVICIO = "permisos_existentes";
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
			$query = "CALL obtenerPermisosExistentes()";
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

/*******************************************************************************************************************

	Funcion que recibe peticiones POST a la url http://apirest/permisos/existe/:usuario
	y devuelve si un usuario tiene un determinado permiso en formato JSON

*******************************************************************************************************************/

$app->post("/permisos/existe/:usuario", function($usuario) use($app)
{

	$NOM_SERVICIO = "permisos/existe";

	$servicio = $app->request->post("servicio");
	
	try{

		$app->response->headers->set("Content-type", "application/json");
		$app->response->status(200);

		$connection = getConnectionAgencia();
		//Lamada a procedimiento almacenado
		$query = "CALL existePermisoUsuario(?,?)";
		$stmt = $connection->prepare($query);
		$stmt->bindParam(1, $usuario);
		$stmt->bindParam(2, $servicio);
		$stmt->execute();
		$result = $stmt->fetchcolumn();
		$connection = null;

		$app->response->body(json_encode(array("status" => "Ok", "message" => "Operation Succesful", "existe" => $result)));

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

	Funcion que recibe peticiones POST a la url http://apirest/permisos/addDel/ y añade o elimina un permiso 
	de un usuario determinado y devuelve una respuesta en formato JSON

*******************************************************************************************************************/

$app->post("/permisos/addDel/", function() use($app)
{

	$NOM_SERVICIO = "permisos/addDel";
	//datos del usuario a autenticar
	$user = $app->request->post("user");
	$pass = $app->request->post("pass");
	$agencia = $app->request->post("agencia");

	//datos del permiso a añadir/eliminar de un usuario
	$usuario = $app->request->post("usuario");
	$permiso = $app->request->post("permiso");
	$accion = $app->request->post("accion");
	
	try{

		$app->response->headers->set("Content-type", "application/json");
		$app->response->status(200);

		//Obtenemos si el usuario tiene permiso para ejecutar el servicio
		$acceso = controlAccessUserToWS($user,$agencia,$pass,$NOM_SERVICIO);

		if($acceso){//Autenticacion de usuario y permisos correcto
			$connection = getConnectionAgencia();
			//Lamada a procedimiento almacenado
			$query = "CALL addDelPermisos(?,?,?,?)";
			$stmt = $connection->prepare($query);
			$stmt->bindParam(1, $usuario);
			$stmt->bindParam(2, $permiso);
			$stmt->bindParam(3, $accion);
			$stmt->bindParam(4, $agencia);
			$stmt->execute();
			$result = $stmt->fetchcolumn();
			$connection = null;

			if($result == 1){ // EL USUARIO NO TIENE EL PERMISO QUE SE PRETENDE AÑADIR Y SE AÑADE CORRECTAMENTE
				$app->response->body(json_encode(array("status" => "Ok", "message" => "add OK")));
			}else if($result == 2){ // EL USUARIO TIENE EL PERMISO QUE SE PRETENDE ELIMINAR Y SE ELIMINA CORRECTAMENTE
				$app->response->body(json_encode(array("status" => "Ok", "message" => "del OK")));
			}else if($result == 3){ // EL USUARIO YA TIENE EL PERMISO QUE SE PRETENDE AÑADIR Y NO SE AÑADE DE NUEVO
				$app->response->body(json_encode(array("status" => "Ok", "message" => "add NOK"))); 
			}else if($result == 4){ // EL USUARIO NO TIENE EL PERMISO QUE SE PRETENDE BORRAR Y ESTE NO SE ELIMINA PORQUE NO EXISTE
				$app->response->body(json_encode(array("status" => "Ok", "message" => "del NOK"))); 
			}else{ // INTENTAMOS CAMBIAR PERMISOS DE UN USUARIO QUE NO ES DE NUESTA AGENCIA Y NUESTRA AGENCIA NO ES FEDERACION
				$app->response->body(json_encode(array("status" => "Ok", "message" => "NOK")));
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

	Funcion que recibe peticiones POST a la url http://apirest/permisos/tabla/ y consulta los permisos que tiene
	un usuario determinado de una tabla especifica de la BD y devuelve una respuesta en formato JSON

*******************************************************************************************************************/

$app->post("/permisos/tabla/", function() use($app)
{

	$NOM_SERVICIO = "permisos/tabla";
	//datos del usuario a autenticar
	$user = $app->request->post("user");
	$pass = $app->request->post("pass");
	$agencia = $app->request->post("agencia");

	$usuario = $app->request->post("usuario");
	$tabla = $app->request->post("tabla");
	
	try{

		$app->response->headers->set("Content-type", "application/json");
		$app->response->status(200);

		//Obtenemos si el usuario tiene permiso para ejecutar el servicio
		$acceso = controlAccessUserToWS($user,$agencia,$pass,$NOM_SERVICIO);

		if($acceso){//Autenticacion de usuario y permisos correcto
			$connection = getConnectionAgencia();
			//Lamada a procedimiento almacenado
			$query = "CALL obtenerPermisosTabla(?,?,?)";
			$stmt = $connection->prepare($query);
			$stmt->bindParam(1, $usuario);
			$stmt->bindParam(2, $tabla);
			$stmt->bindParam(3, $agencia);
			$stmt->execute();
			$result = $stmt->fetchcolumn();
			$connection = null;

			if($result == -1){ // INTENTAMOS CONSULTAR PERMISOS DE UN USUARIO QUE NO ES DE NUESTA AGENCIA Y NUESTRA AGENCIA NO ES FEDERACION
				$app->response->body(json_encode(array("status" => "Ok", "message" => "NOK")));
			}else{
				$app->response->body(json_encode(array("status" => "Ok", "message" => "OK","add" => $result[0],"upd" => $result[1],
					                                                                       "del" => $result[2],"list" => $result[3])));
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