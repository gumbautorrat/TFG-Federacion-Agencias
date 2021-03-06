<?php if(!defined("SPECIALCONSTANT")) die("Acceso denegado");

function getConnectionAgencia()
{

	try{

		$db_username = "agencia";
		$db_password = "123456";

		$host = "localhost";
		$db_name = "federacion";
		$db_conection_string = "mysql:host=".$host.";dbname=".$db_name;

		$connection = new PDO($db_conection_string,$db_username, $db_password,array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
		$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

	}
	catch(PDOException $e)
	{
		throw new PDOException("Conexión fallida a la Base de Datos", 1);
	}

	return $connection;

}