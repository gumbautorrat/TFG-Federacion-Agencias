-- phpMyAdmin SQL Dump
-- version 4.1.12
-- http://www.phpmyadmin.net
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 25-08-2016 a las 21:10:38
-- Versión del servidor: 5.6.16
-- Versión de PHP: 5.5.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `federacion`
--

DELIMITER $$
--
-- Procedimientos
--
CREATE DEFINER=`agencia_up`@`localhost` PROCEDURE `actualizarAgencia`(_id_agencia INT,
                                _nombre VARCHAR(50),
                                _direccion VARCHAR(250),
                                _localidad VARCHAR(100),
                                _provincia VARCHAR(100),
                                _telefono VARCHAR(50),
                                _email VARCHAR(100))
BEGIN

declare result INT;

SELECT COUNT(*) INTO result FROM agencias WHERE id_agencia = _id_agencia;

/* Si no existe el id_agencia que queremos actualizar*/
IF result = 0 THEN 
  SELECT 0;
ELSE 
  UPDATE agencias
  SET nombre = _nombre,direccion = _direccion, localidad = _localidad,
      provincia = _provincia, telefono = _telefono,email = _email
  WHERE id_agencia = _id_agencia;

  SELECT 1;
END IF;

END$$

CREATE DEFINER=`agencia_up`@`localhost` PROCEDURE `actualizarCliente`(
						 _id_cliente INT,
                         _id_agencia INT,
						 _dni VARCHAR(9),
						 _nombre VARCHAR(50),
						 _primer_apellido VARCHAR(50),
						 _segundo_apellido VARCHAR(50),
						 _direccion VARCHAR(250),
						 _localidad VARCHAR(100),
						 _provincia VARCHAR(100),
						 _telefono VARCHAR(50),
						 _email VARCHAR(100),
                         _agencia VARCHAR(100))
BEGIN
declare result INT;
declare old_dni VARCHAR(9);

SELECT COUNT(*) INTO result FROM clientes WHERE id_cliente = _id_cliente;

/* Si no existe el id_cliente que queremos actualizar */
IF result = 0 THEN
  SELECT 0;
ELSE 

  /* Comprobamos que el cliente corresponda a la misma agencia a la que pertenece el usuario que hace la peticion */
   SELECT COUNT(*) INTO result 
   FROM clientes
   WHERE id_cliente = _id_cliente AND
		 id_agencia = (SELECT id_agencia 
					   FROM agencias 
					   WHERE nombre COLLATE utf8_bin = _agencia);
                       
   IF 'Federacion' COLLATE utf8_bin = _agencia OR result = 1 THEN
  
	  /* Nos guardamos el dni que aún no se actualizado en cliente que será igual en la tabla persona 
	  para que en el where encuentre el registro*/
	  SELECT id_persona INTO old_dni FROM clientes WHERE id_cliente = _id_cliente;
	  
	  /* Si intentamos cambiar a un dni que ya está entre todos los dni existentes 
	  saltará un error SQL y ni se actualizará personas ni clientes */
	  /*START TRANSACTION;*/
		  UPDATE personas
		  SET dni = _dni, nombre = _nombre, primer_apellido = _primer_apellido,
			  segundo_apellido = _segundo_apellido,direccion = _direccion, localidad = _localidad,
			  provincia = _provincia, telefono = _telefono,email = _email
		  WHERE dni = old_dni;
		  
		  UPDATE clientes
		  SET id_agencia = _id_agencia, id_persona = _dni
		  WHERE id_cliente = _id_cliente;
	  /*COMMIT;*/

	  SELECT 1;
      
   ELSE 
   
	  SELECT -1;
      
   END IF;
      
END IF;

END$$

CREATE DEFINER=`agencia_up`@`localhost` PROCEDURE `actualizarDemanda`(_id_demanda INT,
																	  _id_cliente INT,
																	  _descripcion VARCHAR(250),
																	  _compartir TINYINT)
BEGIN

declare result INT;

SELECT COUNT(*) INTO result 
FROM demandas
WHERE id_demanda = _id_demanda;
/* Si no existe el identificardor de la demanda que queremos actualizar */
IF result = 0 THEN
  SELECT 0;
ELSE 
  UPDATE demandas
  SET id_cliente = _id_cliente,descripcion = _descripcion, compartir = _compartir
  WHERE id_demanda = _id_demanda;
  
  SELECT 1;
END IF;

END$$

CREATE DEFINER=`agencia_up`@`localhost` PROCEDURE `actualizarFoto`(_id_foto INT,
																   _url VARCHAR(250),
                                                                   _id_inmueble INT)
BEGIN

declare result INT;

SELECT COUNT(*) INTO result 
FROM fotos 
WHERE id_foto = _id_foto;

/* Si no existe el identificardor de foto que queremos actualizar */
IF result = 0 THEN 
  SELECT 0;
ELSE 
  UPDATE fotos
  SET url = _url, id_inmueble = _id_inmueble
  WHERE id_foto = _id_foto;
  
  SELECT 1;
END IF;

END$$

CREATE DEFINER=`agencia_up`@`localhost` PROCEDURE `actualizarInmueble`(
						   _id_inmueble INT,
						   _id_agencia INT,
                           _id_propietario INT,
                           _direccion VARCHAR(250),
                           _codigo_postal VARCHAR(250),
                           _planta INT,
                           _localidad VARCHAR(100),
                           _provincia VARCHAR(100),
                           _descripcion_corta VARCHAR(250),
                           _descripcion_larga VARCHAR(1000),
                           _num_wc INT,
                           _num_habitacion INT,
                           _num_metros INT)
BEGIN

declare result INT;
SELECT COUNT(*) INTO result FROM inmuebles WHERE id_inmueble = _id_inmueble;

/* Si no existe el id_operacion que queremos actualizar*/
IF result = 0 THEN 
  SELECT 0;
ELSE 
  UPDATE inmuebles
  SET id_propietario = _id_propietario, id_agencia = _id_agencia,direccion = _direccion,
	  codigo_postal = _codigo_postal, planta = _planta, localidad = _localidad, provincia = _provincia,
      descripcion_corta = _descripcion_corta, descripcion_larga = _descripcion_larga, num_wc = _num_wc,
      num_habitaciones = _num_habitacion, num_metros = _num_metros
  WHERE id_inmueble = _id_inmueble;

  SELECT 1;
END IF;
    
END$$

CREATE DEFINER=`agencia_up`@`localhost` PROCEDURE `actualizarOperacion`(
						   _id_operacion INT,
						   _id_agencia INT,
                           _id_inmueble INT,
                           _id_tipo INT,
                           _id_cliente INT,
                           _precio DECIMAL(10,2),
                           _compartir TINYINT,
                           _disponible TINYINT,
                           _fianza DECIMAL(10,2),
                           _reserva DECIMAL(10,2),
                           _tiempo_meses INT,
                           _descuento DECIMAL(10,2),
                           _entrada DECIMAL(10,2),
                           _descripcion VARCHAR(250),
                           _observaciones VARCHAR(250))
BEGIN

declare result INT;
SELECT COUNT(*) INTO result FROM operaciones WHERE id_operacion = _id_operacion;

/* Si no existe el id_operacion que queremos actualizar*/
IF result = 0 THEN 
  SELECT 0;
ELSE 
  UPDATE operaciones
  SET id_operacion = _id_operacion, id_agencia = _id_agencia, id_inmueble = _id_inmueble,
	  id_tipo = _id_tipo, id_cliente = _id_cliente, precio = _precio, compartir = _compartir,
      disponible = _disponible, fianza = _fianza, reserva = _reserva, tiempo_meses = _tiempo_meses,
      descuento = _descuento, entrada = _entrada, descripcion = _descripcion, observaciones = _observaciones
  WHERE id_operacion = _id_operacion;

  SELECT 1;
END IF;
    
END$$

CREATE DEFINER=`agencia_up`@`localhost` PROCEDURE `actualizarPermisos`(_servicio VARCHAR(200),
																   _id_usuario INT,
                                                                   _servicio_upd VARCHAR(200),
																   _id_usuario_upd INT)
BEGIN

declare result INT;

SELECT COUNT(*) INTO result 
FROM permisos 
WHERE servicio COLLATE utf8_bin = _servicio AND id_usuario = _id_usuario;

/* Si no existe el identificardor de permiso que queremos actualizar */
IF result = 0 THEN 
  SELECT 0;
ELSE 
  UPDATE permisos
  SET servicio = _servicio_upd, id_usuario = _id_usuario_upd
  WHERE servicio COLLATE utf8_bin = _servicio AND id_usuario = _id_usuario;
  
  SELECT 1;
END IF;

END$$

CREATE DEFINER=`agencia_up`@`localhost` PROCEDURE `actualizarPersona`(_dni VARCHAR(9),
					   _nombre VARCHAR(50),
					   _primer_apellido VARCHAR(50),
					   _segundo_apellido VARCHAR(50),
					   _direccion VARCHAR(250),
					   _localidad VARCHAR(100),
					   _provincia VARCHAR(100),
					   _telefono VARCHAR(50),
					   _email VARCHAR(100))
BEGIN

declare result INT;

SELECT COUNT(*) INTO result FROM personas WHERE dni = _dni;

/* Si no existe el dni de la persona que queremos actualizar*/
IF result = 0 THEN 
  SELECT 0;
ELSE 
  UPDATE personas
  SET dni = _dni, nombre = _nombre, primer_apellido = _primer_apellido,
	  segundo_apellido = _segundo_apellido,direccion = _direccion, localidad = _localidad,
      provincia = _provincia, telefono = _telefono,email = _email
  WHERE dni = _dni;

  SELECT 1;
END IF;
                       
END$$

CREATE DEFINER=`agencia_up`@`localhost` PROCEDURE `actualizarPropietario`(
						 _id_propietario INT,
                         _id_agencia INT,
						 _dni VARCHAR(9),
						 _nombre VARCHAR(50),
						 _primer_apellido VARCHAR(50),
						 _segundo_apellido VARCHAR(50),
						 _direccion VARCHAR(250),
						 _localidad VARCHAR(100),
						 _provincia VARCHAR(100),
						 _telefono VARCHAR(50),
						 _email VARCHAR(100),
                         _agencia VARCHAR(100))
BEGIN
declare result INT;
declare old_dni VARCHAR(9);

SELECT COUNT(*) INTO result FROM propietarios WHERE id_propietario = _id_propietario;

/* Si no existe el id_propietario que queremos actualizar */
IF result = 0 THEN
  SELECT 0;
ELSE 

   /* Comprobamos que el propietario corresponda a la misma agencia a la que pertenece el usuario que hace la peticion */
   SELECT COUNT(*) INTO result 
   FROM propietarios
   WHERE id_propietario = _id_propietario AND
		 id_agencia = (SELECT id_agencia 
					   FROM agencias 
					   WHERE nombre COLLATE utf8_bin = _agencia);
  
   IF 'Federacion' COLLATE utf8_bin = _agencia OR result = 1 THEN
   
	  /* Nos guardamos el dni que aún no se actualizado en propietario que será igual en la tabla persona 
	  para que en el where encuentre el registro*/
	  SELECT id_persona INTO old_dni FROM propietarios WHERE id_propietario = _id_propietario;
	  
	  /* Si intentamos cambiar a un dni que ya está entre todos los dni existentes 
	  saltará un error SQL y ni se actualizará personas ni propietarios */
	  /*START TRANSACTION;*/
		  UPDATE personas
		  SET dni = _dni, nombre = _nombre, primer_apellido = _primer_apellido,
			  segundo_apellido = _segundo_apellido,direccion = _direccion, localidad = _localidad,
			  provincia = _provincia, telefono = _telefono,email = _email
		  WHERE dni = old_dni;
		  
		  UPDATE propietarios
		  SET id_agencia = _id_agencia, id_persona = _dni
		  WHERE id_propietario = _id_propietario;
	  /*COMMIT;*/

	  SELECT 1;

   ELSE 
   
	  SELECT -1;
      
   END IF;

END IF;

END$$

CREATE DEFINER=`agencia_up`@`localhost` PROCEDURE `actualizarTipoOperacion`(_id_tipo INT,
																		    _nombre VARCHAR(50),
																			_descripcion VARCHAR(250))
BEGIN

declare result INT;

SELECT COUNT(*) INTO result 
FROM tipo_operacion 
WHERE id_tipo = _id_tipo;
/* Si no existe el identificardor de foto que queremos actualizar */
IF result = 0 THEN 
  SELECT 0;
ELSE 
  UPDATE tipo_operacion
  SET nombre = _nombre, descripcion = _descripcion
  WHERE id_tipo = _id_tipo;
  
  SELECT 1;
END IF;

END$$

CREATE DEFINER=`agencia_up`@`localhost` PROCEDURE `actualizarUsuario`(
						 _id_usuario INT,
                         _id_agencia INT,
					     _usuario VARCHAR(100),
					     _dni VARCHAR(9),
					     _nombre VARCHAR(50),
					     _primer_apellido VARCHAR(50),
					     _segundo_apellido VARCHAR(50),
					     _direccion VARCHAR(250),
					     _localidad VARCHAR(100), 
					     _provincia VARCHAR(100),
					     _telefono VARCHAR(50),
					     _email VARCHAR(100),
                         _agencia VARCHAR(100))
BEGIN
declare result INT;
declare old_dni VARCHAR(9);

SELECT COUNT(*) INTO result FROM usuarios WHERE id_usuario = _id_usuario;

/* Si no existe el id_usuario que queremos actualizar*/
IF result = 0 THEN
  SELECT 0;
ELSE 

  /* Comprobamos que el usuario corresponda a la misma agencia a la que pertenece el usuario que hace la peticion */
   SELECT COUNT(*) INTO result 
   FROM usuarios
   WHERE id_usuario = _id_usuario AND
		 id_agencia = (SELECT id_agencia 
					   FROM agencias 
					   WHERE nombre COLLATE utf8_bin = _agencia);
                       
  IF 'Federacion' COLLATE utf8_bin = _agencia OR result = 1 THEN
  
	  /* Nos guardamos el dni que aún no se actualizado en usuario que será igual en la tabla persona 
	  para que en el where encuentre el registro*/
	  SELECT id_persona INTO old_dni FROM usuarios WHERE id_usuario = _id_usuario;
	  
	  /* Si intentamos cambiar a un dni que ya está entre todos los dni existentes 
	  saltará un error SQL y ni se actualizará personas ni propietarios */
	  /*START TRANSACTION;*/
		  UPDATE personas
		  SET dni = _dni, nombre = _nombre, primer_apellido = _primer_apellido,
			  segundo_apellido = _segundo_apellido,direccion = _direccion, localidad = _localidad,
			  provincia = _provincia, telefono = _telefono,email = _email
		  WHERE dni = old_dni;
		  
		  UPDATE usuarios
		  SET id_agencia = _id_agencia, usuario = _usuario, id_persona = _dni
		  WHERE id_usuario = _id_usuario;
	  /*COMMIT;*/

	  SELECT 1;

  ELSE 
   
	  SELECT -1;
      
  END IF;
  
END IF;

END$$

CREATE DEFINER=`agencia_in_de`@`localhost` PROCEDURE `addDelPermisos`(_usuario VARCHAR(100), _permiso VARCHAR(200) , _accion INT, _agencia VARCHAR(100))
BEGIN

DECLARE resultado int;
DECLARE _id_usuario int;
DECLARE id_agencia_usu1 int;
DECLARE id_agencia_usu2 int;

	/* Obtenemos el id_agencia del usuario que pide modificar permisos de otro usuario o de el mismo */
	SELECT id_agencia INTO id_agencia_usu1
    FROM agencias 
    WHERE nombre COLLATE utf8_bin = _agencia;
	
    /* Obtenemos el id_agencia del usuario a modificar permisos */
	SELECT id_agencia INTO id_agencia_usu2
	FROM usuarios 
	WHERE usuario COLLATE utf8_bin = _usuario;

	/* SI LA AGENCIA DEL USUARIO QUE QUIERE MODIFICAR PERMISOS DE OTRO USUARIO ES "FEDERACION",
       ENTONCES PUEDE CAMBIAR LOS PERMISOS DE CUALQUIER USUARIO */
	IF 'Federacion' COLLATE utf8_bin = _agencia OR id_agencia_usu1 = id_agencia_usu2 THEN 

		SET resultado = checkPermisos(_usuario,_permiso);
		
		SELECT id_usuario INTO _id_usuario 
				FROM usuarios 
				WHERE usuario COLLATE utf8_bin = _usuario;
	 
		/* AÑADIR */
		IF _accion = 1 THEN
				 /* EL USUARIO YA TIENE EL PERMISO QUE SE PRETENDE AÑADIR*/
			IF resultado = 1 THEN
				SELECT 3;
			ELSE /* EL USUARIO NO TIENE EL PERMISO QUE SE PRETENDE AÑADIR */
				INSERT INTO permisos(servicio, id_usuario) VALUES (_permiso,_id_usuario);
				SELECT 1;
			END IF;
		ELSE 
				/* EL USUARIO TIENE EL PERMISO QUE SE PRETENDE BORRAR */
			IF resultado = 1 THEN
				DELETE 
				FROM permisos
				WHERE servicio COLLATE utf8_bin = _permiso AND id_usuario = _id_usuario;
				SELECT 2;
			ELSE/* EL USUARIO NO TIENE EL PERMISO QUE SE PRETENDE BORRAR */
				SELECT 4;
			END IF;
		END IF;
	
    ELSE 
		SELECT -1;
    END IF;
	
END$$

CREATE DEFINER=`agencia_se`@`localhost` PROCEDURE `authUsuario`(_usuario VARCHAR(100),_agencia VARCHAR(100))
BEGIN
  SELECT pass 
  FROM usuarios 
  WHERE id_agencia = (SELECT id_agencia 
					  FROM agencias 
					  WHERE nombre COLLATE utf8_bin = _agencia) AND usuario COLLATE utf8_bin = _usuario;
END$$

CREATE DEFINER=`agencia_se`@`localhost` PROCEDURE `authUsuarioRoot`(_usuario VARCHAR(100))
BEGIN
  SELECT pass 
  FROM usuarios 
  WHERE usuario COLLATE utf8_bin = _usuario and id_agencia = 4;
                      
END$$

CREATE DEFINER=`agencia_up`@`localhost` PROCEDURE `compartirDemanda`(
						   _id_demanda INT,
                           _agencia VARCHAR(100),
                           _compartir TINYINT)
BEGIN
declare result INT;
SELECT COUNT(*) INTO result FROM demandas WHERE id_demanda = _id_demanda;

/* Si no existe el id_operacion que queremos actualizar*/
IF result = 0 THEN 
  SELECT 0;
ELSE 

  /* Comprobamos que la demanda corresponda a la misma agencia a la que pertenece el usuario que hace la peticion */
  SELECT COUNT(*) INTO result 
  FROM demandas d, clientes c 
  WHERE id_demanda = _id_demanda AND 
        c.id_cliente = d.id_cliente AND
		c.id_agencia = (SELECT id_agencia 
					    FROM agencias 
					    WHERE nombre COLLATE utf8_bin = _agencia);

  /* Solo se ejecuta la actualizacion si el usuario que hace la peticion, pertenece a la misma 
     agencia a la que pertenece la demanda o el usuario pertenece a la agencia Federacion */
  IF 'Federacion' COLLATE utf8_bin = _agencia OR result = 1 THEN
  
	  UPDATE demandas
	  SET compartir = _compartir
	  WHERE id_demanda = _id_demanda;

	  SELECT 1;
  
  ELSE 
  
	  SELECT -1;
    
  END IF;
  
END IF;
    
END$$

CREATE DEFINER=`agencia_up`@`localhost` PROCEDURE `compartirOperacion`(
						   _id_operacion INT,
                           _agencia VARCHAR(100),
                           _compartir TINYINT)
BEGIN
declare result INT;
SELECT COUNT(*) INTO result FROM operaciones WHERE id_operacion = _id_operacion;

/* Si no existe el id_operacion que queremos actualizar*/
IF result = 0 THEN 
  SELECT 0;
ELSE 
	
  /* Comprobamos que la operacion corresponda a la misma agencia a la que pertenece el usuario que hace la peticion */
  SELECT COUNT(*) INTO result 
  FROM operaciones 
  WHERE id_operacion = _id_operacion AND 
		id_agencia = (SELECT id_agencia 
					  FROM agencias 
					  WHERE nombre COLLATE utf8_bin = _agencia);
                      

  /* Solo se ejecuta la actualizacion si el usuario que hace la peticion, pertenece a la misma 
     agencia a la que pertenece la operacion o el usuario pertenece a la agencia Federacion */
  IF 'Federacion' COLLATE utf8_bin = _agencia OR result = 1 THEN
  
	  UPDATE operaciones
	  SET compartir = _compartir
	  WHERE id_operacion = _id_operacion;

	  SELECT 1;
  
  ELSE 
  
	  SELECT -1;
    
  END IF;
  
END IF;
    
END$$

CREATE DEFINER=`agencia_in`@`localhost` PROCEDURE `crearAgencia`(
						   _nombre VARCHAR(100),
                           _direccion VARCHAR(250),
                           _localidad VARCHAR(100),
                           _provincia VARCHAR(100),
                           _telefono VARCHAR(50),
						   _email VARCHAR(100))
BEGIN

	INSERT INTO agencias VALUES (null,_nombre,_direccion,_localidad,_provincia,_telefono,_email);
    
END$$

CREATE DEFINER=`agencia_in`@`localhost` PROCEDURE `crearCliente`(
						   _id_agencia INT,
                           _dni VARCHAR(9),
						   _nombre VARCHAR(50),
                           _primer_apellido VARCHAR(50),
                           _segundo_apellido VARCHAR(50),
                           _direccion VARCHAR(250),
                           _localidad VARCHAR(100), 
                           _provincia VARCHAR(100),
						   _telefono VARCHAR(50),
						   _email VARCHAR(100))
BEGIN
	/*START TRANSACTION;*/
		INSERT INTO personas VALUES (_dni,_nombre,_primer_apellido,_segundo_apellido,_direccion,_localidad,_provincia,_telefono,_email);
		INSERT INTO clientes VALUES (null,_id_agencia,_dni);
    /*COMMIT;*/
END$$

CREATE DEFINER=`agencia_in`@`localhost` PROCEDURE `crearClientePersona`(
						   _id_agencia INT,
                           _dni VARCHAR(9),
						   _nombre VARCHAR(50),
                           _primer_apellido VARCHAR(50),
                           _segundo_apellido VARCHAR(50),
                           _direccion VARCHAR(250),
                           _localidad VARCHAR(100), 
                           _provincia VARCHAR(100),
						   _telefono VARCHAR(50),
						   _email VARCHAR(100))
BEGIN
	declare result INT;
    SELECT COUNT(*) INTO result FROM personas WHERE dni = _dni;
	/*START TRANSACTION; */
		INSERT INTO clientes VALUES (null,_id_agencia,_dni);
        /* En el caso que no exista ya una persona con ese dni */
        IF result = 0 THEN 
			INSERT INTO personas VALUES (_dni,_nombre,_primer_apellido,_segundo_apellido,_direccion,_localidad,_provincia,_telefono,_email);
		END IF;
    /*COMMIT;*/
END$$

CREATE DEFINER=`agencia_in`@`localhost` PROCEDURE `crearClienteSinPersona`(
						   _id_agencia INT,
                           _dni VARCHAR(9))
BEGIN
	INSERT INTO clientes VALUES (null,_id_agencia,_dni);
END$$

CREATE DEFINER=`agencia_in`@`localhost` PROCEDURE `crearDemanda`(_id_cliente INT,_descripcion VARCHAR(250),_compartir TINYINT)
BEGIN
	INSERT INTO demandas(id_demanda,id_cliente,descripcion,compartir) VALUES (null,_id_cliente,_descripcion,_compartir);
END$$

CREATE DEFINER=`agencia_in`@`localhost` PROCEDURE `crearFoto`(_url VARCHAR(500),_id_inmueble INT)
BEGIN

	INSERT INTO fotos(id_foto, url, id_inmueble) VALUES (null,_url,_id_inmueble);
    
END$$

CREATE DEFINER=`agencia_in`@`localhost` PROCEDURE `crearInmueble`(
						   _id_agencia INT,
                           _id_propietario INT,
                           _direccion VARCHAR(250),
                           _codigo_postal VARCHAR(20),
                           _planta INT,
                           _localidad VARCHAR(100),
                           _provincia VARCHAR(100),
                           _descripcion_corta VARCHAR(250),
                           _descripcion_larga VARCHAR(1000),
                           _num_wc INT,
                           _num_habitaciones INT,
                           _num_metros INT)
BEGIN
	INSERT INTO inmuebles (id_inmueble,id_agencia,id_propietario,direccion,codigo_postal,planta,localidad,provincia,descripcion_corta,descripcion_larga,num_wc,num_habitaciones,num_metros) VALUES
						  (null,_id_agencia,_id_propietario,_direccion,_codigo_postal,_planta,_localidad,_provincia,_descripcion_corta,_descripcion_larga,_num_wc,_num_habitaciones,_num_metros);
    
END$$

CREATE DEFINER=`agencia_in`@`localhost` PROCEDURE `crearOperacion`(
						   _id_agencia INT,
                           _id_inmueble INT,
                           _id_tipo INT,
                           _id_cliente INT,
                           _precio DECIMAL(10,2),
                           _compartir TINYINT,
                           _disponible TINYINT,
                           _fianza DECIMAL(10,2),
                           _reserva DECIMAL(10,2),
                           _tiempo_meses INT,
                           _descuento DECIMAL(10,2),
                           _entrada DECIMAL(10,2),
                           _descripcion VARCHAR(250),
                           _observaciones VARCHAR(250))
BEGIN
	INSERT INTO operaciones (id_operacion,id_agencia,id_inmueble,id_tipo,id_cliente,fecha,precio,compartir,disponible,fianza,reserva,tiempo_meses,descuento,entrada,descripcion,observaciones) VALUES
						  (null,_id_agencia,_id_inmueble,_id_tipo,_id_cliente,CURDATE(),_precio,_compartir,_disponible,_fianza,_reserva,_tiempo_meses,_descuento,_entrada,_descripcion,_observaciones);
END$$

CREATE DEFINER=`agencia_in`@`localhost` PROCEDURE `crearPermiso`(_servicio VARCHAR(200),_id_usuario INT)
BEGIN

	INSERT INTO permisos(servicio, id_usuario) VALUES (_servicio,_id_usuario);

END$$

CREATE DEFINER=`agencia_in`@`localhost` PROCEDURE `crearPersona`(_dni VARCHAR(9),
					   _nombre VARCHAR(50),
					   _primer_apellido VARCHAR(50),
					   _segundo_apellido VARCHAR(50),
					   _direccion VARCHAR(250),
					   _localidad VARCHAR(100),
					   _provincia VARCHAR(100),
					   _telefono VARCHAR(50),
					   _email VARCHAR(100))
BEGIN
INSERT INTO personas VALUES (_dni,_nombre,_primer_apellido,_segundo_apellido,_direccion,_localidad,_provincia,_telefono,_email);
END$$

CREATE DEFINER=`agencia_in`@`localhost` PROCEDURE `crearPropietario`(
						   _id_agencia INT,
                           _dni VARCHAR(9),
						   _nombre VARCHAR(50),
                           _primer_apellido VARCHAR(50),
                           _segundo_apellido VARCHAR(50),
                           _direccion VARCHAR(250),
                           _localidad VARCHAR(100), 
                           _provincia VARCHAR(100),
						   _telefono VARCHAR(50),
						   _email VARCHAR(100))
BEGIN
	/*START TRANSACTION;*/ 
		INSERT INTO personas VALUES (_dni,_nombre,_primer_apellido,_segundo_apellido,_direccion,_localidad,_provincia,_telefono,_email);
        INSERT INTO propietarios VALUES (null,_id_agencia,_dni);
    /*COMMIT;*/
END$$

CREATE DEFINER=`agencia_in`@`localhost` PROCEDURE `crearPropietarioPersona`(
						   _id_agencia INT,
                           _dni VARCHAR(9),
						   _nombre VARCHAR(50),
                           _primer_apellido VARCHAR(50),
                           _segundo_apellido VARCHAR(50),
                           _direccion VARCHAR(250),
                           _localidad VARCHAR(100), 
                           _provincia VARCHAR(100),
						   _telefono VARCHAR(50),
						   _email VARCHAR(100))
BEGIN
	declare result INT;
    SELECT COUNT(*) INTO result FROM personas WHERE dni = _dni;
	/*START TRANSACTION;  */
		INSERT INTO propietarios VALUES (null,_id_agencia,_dni);
        /* En el caso que no exista ya una persona con ese dni */
        IF result = 0 THEN 
			INSERT INTO personas VALUES (_dni,_nombre,_primer_apellido,_segundo_apellido,_direccion,_localidad,_provincia,_telefono,_email);
		END IF;
    /*COMMIT;*/
END$$

CREATE DEFINER=`agencia_in`@`localhost` PROCEDURE `crearPropietarioSinPersona`(
						   _id_agencia INT,
                           _dni VARCHAR(9))
BEGIN
	 INSERT INTO propietarios VALUES (null,_id_agencia,_dni);
END$$

CREATE DEFINER=`agencia_in`@`localhost` PROCEDURE `crearTipoOperacion`(_nombre VARCHAR(50),_descripcion VARCHAR(250))
BEGIN
	INSERT INTO tipo_operacion(id_tipo,nombre, descripcion) VALUES (null,_nombre,_descripcion);
    
END$$

CREATE DEFINER=`agencia_in`@`localhost` PROCEDURE `crearUsuario`(
						   _id_agencia INT,
                           _usuario VARCHAR(100),
						   _pass VARCHAR(100),
                           _dni VARCHAR(9),
						   _nombre VARCHAR(50),
                           _primer_apellido VARCHAR(50),
                           _segundo_apellido VARCHAR(50),
                           _direccion VARCHAR(250),
                           _localidad VARCHAR(100), 
                           _provincia VARCHAR(100),
						   _telefono VARCHAR(50),
						   _email VARCHAR(100))
BEGIN
	/*START TRANSACTION; */
		INSERT INTO personas VALUES (_dni,_nombre,_primer_apellido,_segundo_apellido,_direccion,_localidad,_provincia,_telefono,_email);
		INSERT INTO usuarios (id_usuario,id_agencia,usuario,pass,id_persona)VALUES (null,_id_agencia,_usuario,_pass,_dni);
	/*COMMIT;*/
END$$

CREATE DEFINER=`agencia_in`@`localhost` PROCEDURE `crearUsuarioPersona`(
						   _id_agencia INT,
                           _usuario VARCHAR(100),
						   _pass VARCHAR(100),
                           _dni VARCHAR(9),
						   _nombre VARCHAR(50),
                           _primer_apellido VARCHAR(50),
                           _segundo_apellido VARCHAR(50),
                           _direccion VARCHAR(250),
                           _localidad VARCHAR(100), 
                           _provincia VARCHAR(100),
						   _telefono VARCHAR(50),
						   _email VARCHAR(100))
BEGIN
	declare result INT;
    SELECT COUNT(*) INTO result FROM personas WHERE dni = _dni;
	/*START TRANSACTION;*/
		INSERT INTO usuarios (id_usuario,id_agencia,usuario,pass,id_persona)VALUES (null,_id_agencia,_usuario,_pass,_dni);
        /* En el caso que no exista ya una persona con ese dni */
        IF result = 0 THEN 
			INSERT INTO personas VALUES (_dni,_nombre,_primer_apellido,_segundo_apellido,_direccion,_localidad,_provincia,_telefono,_email);
		END IF;
    /*COMMIT;*/
END$$

CREATE DEFINER=`agencia_in`@`localhost` PROCEDURE `crearUsuarioSinPersona`(
						   _id_agencia INT,
                           _usuario VARCHAR(100),
						   _pass VARCHAR(100),
                           _dni VARCHAR(9))
BEGIN
	INSERT INTO usuarios (id_usuario,id_agencia,usuario,pass,id_persona)VALUES (null,_id_agencia,_usuario,_pass,_dni);
END$$

CREATE DEFINER=`agencia_de`@`localhost` PROCEDURE `eliminarAgencia`(_id_agencia INT)
BEGIN

declare result INT;

SELECT COUNT(*) INTO result FROM agencias WHERE id_agencia = _id_agencia;

/* Si no existe el id_propietario que queremos actualiza */
IF result = 0 THEN 
	SELECT 0;
ELSE 
	DELETE FROM agencias
	WHERE id_agencia = _id_agencia;

	SELECT 1;
END IF;

END$$

CREATE DEFINER=`agencia_de`@`localhost` PROCEDURE `eliminarCliente`(_id_cliente INT,_agencia VARCHAR(100))
BEGIN
declare result INT;
declare prop INT;
declare usu INT;
declare _dni VARCHAR(9);

SELECT COUNT(*),id_persona INTO result,_dni FROM clientes WHERE id_cliente = _id_cliente;

/* Si no existe el id_cliente que queremos eliminar  */
IF result = 0 THEN 
	SELECT 0;
ELSE

   /* Comprobamos que el cliente corresponda a la misma agencia a la que pertenece el usuario que hace la peticion */
   SELECT COUNT(*) INTO result 
   FROM clientes
   WHERE id_cliente = _id_cliente AND
		 id_agencia = (SELECT id_agencia 
					   FROM agencias 
					   WHERE nombre COLLATE utf8_bin = _agencia);
                       
   IF 'Federacion' COLLATE utf8_bin = _agencia OR result = 1 THEN
                       
		/* Comprobamos si existe un propietario con el dni del cliente en la tabla propietarios*/
		SELECT COUNT(*) INTO prop FROM propietarios WHERE id_persona = _dni;
		/* Comprobamos si existe un usuario con el dni del cliente en la tabla usuarios*/
		SELECT COUNT(*) INTO usu FROM usuarios WHERE id_persona = _dni;
		
		DELETE FROM clientes
		WHERE id_cliente = _id_cliente;
		
		/* Solo en el caso de que el cliente no sea también propietario o usuario borramos los datos de 
		la tabla personas */
		IF prop = 0 AND usu = 0 THEN 
			 DELETE FROM personas
			 WHERE dni = _dni;
		END IF;
		
		SELECT 1;
	
    ELSE 
    
		SELECT -1;
        
    END IF;
END IF;

END$$

CREATE DEFINER=`agencia_de`@`localhost` PROCEDURE `eliminarDemanda`(_id_demanda INT)
BEGIN

declare result INT;
SELECT COUNT(*) INTO result FROM demandas WHERE id_demanda = _id_demanda;

/* Si no existe la demanda que queremos eliminar */
IF result = 0 THEN
	SELECT 0;
ELSE 
	DELETE FROM demandas
	WHERE id_demanda = _id_demanda;
    
	SELECT 1;
END IF;

END$$

CREATE DEFINER=`agencia_de`@`localhost` PROCEDURE `eliminarFoto`(_id_foto INT)
BEGIN

declare result INT;

SELECT COUNT(*) INTO result FROM fotos WHERE id_foto = _id_foto;

/* Si no existe el id_foto que queremos eliminar */
IF result = 0 THEN 
	SELECT 0;
ELSE 
	DELETE FROM fotos
	WHERE id_foto = _id_foto;
    
	SELECT 1;
END IF;

END$$

CREATE DEFINER=`agencia_de`@`localhost` PROCEDURE `eliminarInmueble`(_id_inmueble INT)
BEGIN
declare result INT;
SELECT COUNT(*) INTO result FROM inmuebles WHERE id_inmueble = _id_inmueble;

/* Si no existe el id_inmueble que queremos actualizar */
IF result = 0 THEN 
	SELECT 0;
ELSE 
	DELETE FROM inmuebles
	WHERE id_inmueble = _id_inmueble;

	SELECT 1;
END IF;

END$$

CREATE DEFINER=`agencia_de`@`localhost` PROCEDURE `eliminarOperacion`(_id_operacion INT)
BEGIN
declare result INT;
declare _id_inmueble INT;
declare _num_ops INT; /* Numero de operaciones que tiene el inmueble asociado a esta operacion, que no sean esta operacion */

SELECT COUNT(*), id_inmueble INTO result, _id_inmueble FROM operaciones WHERE id_operacion = _id_operacion;

/* Si no existe el id_propietario que queremos actualizar */
IF result = 0 THEN 
	SELECT 0;
ELSE 
	
    SELECT COUNT(*) INTO _num_ops 
    FROM operaciones 
    WHERE id_inmueble = _id_inmueble AND id_operacion <> _id_operacion;
    
    DELETE FROM operaciones
	WHERE id_operacion = _id_operacion;
	
    IF _num_ops = 0 THEN
		DELETE FROM inmuebles
		WHERE id_inmueble = _id_inmueble;
	END IF;
    
    SELECT 1;
	
END IF;

END$$

CREATE DEFINER=`agencia_de`@`localhost` PROCEDURE `eliminarPermiso`(_servicio VARCHAR(200),_id_usuario INT)
BEGIN

declare result INT;

SELECT COUNT(*) INTO result FROM permisos WHERE servicio COLLATE utf8_bin = _servicio AND id_usuario = _id_usuario;

/* Si no existe el identificador de permiso que queremos eliminar */
IF result = 0 THEN 
	SELECT 0;
ELSE 
	DELETE FROM permisos
	WHERE servicio COLLATE utf8_bin = _servicio AND id_usuario = _id_usuario;
    
	SELECT 1;
END IF;

END$$

CREATE DEFINER=`agencia_de`@`localhost` PROCEDURE `eliminarPersona`(_dni VARCHAR(9))
BEGIN
declare result INT;

SELECT COUNT(*) INTO result FROM personas WHERE dni = _dni;

/* Si no existe la persona que queremos eliminar */
IF result = 0 THEN 
	SELECT 0;
ELSE
	DELETE FROM personas
	WHERE dni = _dni;

	SELECT 1;
END IF;

END$$

CREATE DEFINER=`agencia_de`@`localhost` PROCEDURE `eliminarPropietario`(_id_propietario INT, _agencia VARCHAR(100))
BEGIN
declare result INT;
declare cli INT;
declare usu INT;
declare _dni VARCHAR(9);

SELECT COUNT(*),id_persona INTO result,_dni FROM propietarios WHERE id_propietario = _id_propietario;

/* Si no existe el id_propietario que queremos eliminar */
IF result = 0 THEN
	SELECT 0;
ELSE

   /* Comprobamos que el propietario corresponda a la misma agencia a la que pertenece el usuario que hace la peticion */
   SELECT COUNT(*) INTO result 
   FROM propietarios
   WHERE id_propietario = _id_propietario AND
		 id_agencia = (SELECT id_agencia 
					   FROM agencias 
					   WHERE nombre COLLATE utf8_bin = _agencia);
  
   IF 'Federacion' COLLATE utf8_bin = _agencia OR result = 1 THEN

		/* Comprobamos si existe un cliente con el dni del propietario en la tabla clientes*/
		SELECT COUNT(*) INTO cli FROM clientes WHERE id_persona = _dni;
		/* Comprobamos si existe un usuario con el dni del propietario en la tabla usuarios*/
		SELECT COUNT(*) INTO usu FROM usuarios WHERE id_persona = _dni;
		
		DELETE FROM propietarios
		WHERE id_propietario = _id_propietario;
		
		/* Solo en el caso de que el propietario no sea también cliente o usuario borramos los datos de 
		la tabla personas */
		IF cli = 0 AND usu = 0 THEN 
			 DELETE FROM personas
			 WHERE dni = _dni;
		END IF;
		
		SELECT 1;
        
   ELSE 
   
        SELECT -1;
      
   END IF;
   
END IF;

END$$

CREATE DEFINER=`agencia_de`@`localhost` PROCEDURE `eliminarTipoOperacion`(_id_tipo INT)
BEGIN

declare result INT;
SELECT COUNT(*) INTO result FROM tipo_operacion WHERE id_tipo = _id_tipo;

/* Si no existe el tipo_operacion que queremos eliminar */
IF result = 0 THEN 
	SELECT 0;
ELSE 
	DELETE FROM tipo_operacion
	WHERE id_tipo = _id_tipo;
    
	SELECT 1;
END IF;

END$$

CREATE DEFINER=`agencia_de`@`localhost` PROCEDURE `eliminarUsuario`(_id_usuario INT,_agencia VARCHAR(100))
BEGIN
declare result INT;
declare cli INT;
declare prop INT;
declare _dni VARCHAR(9);

SELECT COUNT(*),id_persona INTO result,_dni FROM usuarios WHERE id_usuario = _id_usuario;

/* Si no existe el id_usuario que queremos eliminar  */
IF result = 0 THEN 
	SELECT 0;
ELSE
	
   /* Comprobamos que el usuario corresponda a la misma agencia a la que pertenece el usuario que hace la peticion */
   SELECT COUNT(*) INTO result 
   FROM usuarios
   WHERE id_usuario = _id_usuario AND
		 id_agencia = (SELECT id_agencia 
					   FROM agencias 
					   WHERE nombre COLLATE utf8_bin = _agencia);
                       
   IF 'Federacion' COLLATE utf8_bin = _agencia OR result = 1 THEN

		/* Comprobamos si existe un cliente con el dni del usuario en la tabla clientes*/
		SELECT COUNT(*) INTO cli FROM clientes WHERE id_persona = _dni;
		/* Comprobamos si existe un usuario con el dni del usuario en la tabla propietarios*/
		SELECT COUNT(*) INTO prop FROM propietarios WHERE id_persona = _dni;
		
		DELETE FROM usuarios
		WHERE id_usuario = _id_usuario;

		/* Solo en el caso de que el usuario no sea también propietario o cliente borramos los datos de 
		la tabla personas */
		IF cli = 0 AND prop = 0 THEN 
			 DELETE FROM personas
			 WHERE dni = _dni;
		END IF;
		
		SELECT 1;
        
	ELSE 
    
		SELECT -1;
        
    END IF;
END IF;

END$$

CREATE DEFINER=`agencia_se`@`localhost` PROCEDURE `existePermisoUsuario`(_usuario VARCHAR(100), _servicio VARCHAR(200))
BEGIN

declare result INT;

  SELECT COUNT(*) INTO result
  FROM permisos
  WHERE id_usuario = (SELECT id_usuario 
					  FROM usuarios 
                      WHERE usuario COLLATE utf8_bin = TRIM(_usuario)) AND servicio COLLATE utf8_bin = _servicio;
                      
  IF result = 1 THEN
	SELECT 1;
  ELSE
	SELECT 0;
  END IF;
					
END$$

CREATE DEFINER=`agencia_se`@`localhost` PROCEDURE `numClientesFiltro`(_tipo_filtro VARCHAR(100), _filtro VARCHAR(100), _agencia VARCHAR(100))
BEGIN

  IF 'Federacion' COLLATE utf8_bin = _agencia THEN 
  
    IF _tipo_filtro = 'id' THEN
    
    SELECT COUNT(*)
    FROM clientes cli,personas p
    WHERE cli.id_persona = p.dni AND cli.id_cliente LIKE CONCAT(_filtro,'%') AND cli.id_cliente <> 1;
    
    ELSEIF _tipo_filtro = 'dni' THEN
    
    SELECT COUNT(*)
    FROM clientes cli,personas p
    WHERE cli.id_persona = p.dni AND p.dni LIKE CONCAT(_filtro,'%') AND cli.id_cliente <> 1;
    
    ELSEIF _tipo_filtro = 'nombre' THEN
    
    SELECT COUNT(*)
    FROM clientes cli,personas p
    WHERE cli.id_persona = p.dni AND p.nombre LIKE CONCAT(_filtro,'%') AND cli.id_cliente <> 1;
    
    ELSEIF _tipo_filtro = 'apell1' THEN
    
    SELECT COUNT(*)
    FROM clientes cli,personas p
    WHERE cli.id_persona = p.dni AND p.primer_apellido LIKE CONCAT(_filtro,'%') AND cli.id_cliente <> 1;
    
    ELSEIF _tipo_filtro = 'apell2' THEN
    
    SELECT COUNT(*)
    FROM clientes cli,personas p
    WHERE cli.id_persona = p.dni AND p.segundo_apellido LIKE CONCAT(_filtro,'%') AND cli.id_cliente <> 1;
    
    ELSEIF _tipo_filtro = 'direccion' THEN
    
    SELECT COUNT(*)
    FROM clientes cli,personas p
    WHERE cli.id_persona = p.dni AND p.direccion LIKE CONCAT(_filtro,'%') AND cli.id_cliente <> 1;
    
    ELSEIF _tipo_filtro = 'localidad' THEN
    
    SELECT COUNT(*)
    FROM clientes cli,personas p
    WHERE cli.id_persona = p.dni AND p.localidad LIKE CONCAT(_filtro,'%') AND cli.id_cliente <> 1;
    
    ELSEIF _tipo_filtro = 'telefono' THEN
    
    SELECT COUNT(*)
    FROM clientes cli,personas p
    WHERE cli.id_persona = p.dni AND p.telefono LIKE CONCAT(_filtro,'%') AND cli.id_cliente <> 1;
    
    ELSEIF _tipo_filtro = 'provincia' THEN
    
    SELECT COUNT(*)
    FROM clientes cli,personas p
    WHERE cli.id_persona = p.dni AND p.provincia LIKE CONCAT(_filtro,'%') AND cli.id_cliente <> 1;
    
    ELSEIF _tipo_filtro = 'email' THEN
    
    SELECT COUNT(*)
    FROM clientes cli,personas p
    WHERE cli.id_persona = p.dni AND p.email LIKE CONCAT(_filtro,'%') AND cli.id_cliente <> 1;
    
    ELSE
    
    SELECT COUNT(*)
    FROM clientes cli,personas p
    WHERE cli.id_persona = p.dni AND cli.id_cliente <> 1;
    
    END IF;
    
  ELSE 

    IF _tipo_filtro = 'id' THEN
    
    SELECT COUNT(*)
    FROM clientes cli,personas p
    WHERE cli.id_persona = p.dni AND cli.id_cliente LIKE CONCAT(_filtro,'%') AND 
        cli.id_agencia = (SELECT id_agencia 
                FROM agencias 
                WHERE nombre COLLATE utf8_bin = _agencia) AND cli.id_cliente <> 1;
    
    ELSEIF _tipo_filtro = 'dni' THEN
    
    SELECT COUNT(*)
    FROM clientes cli,personas p
    WHERE cli.id_persona = p.dni AND p.dni LIKE CONCAT(_filtro,'%') AND
        cli.id_agencia = (SELECT id_agencia 
                FROM agencias 
                WHERE nombre COLLATE utf8_bin = _agencia) AND cli.id_cliente <> 1;
    
    ELSEIF _tipo_filtro = 'nombre' THEN
    
    SELECT COUNT(*)
    FROM clientes cli,personas p
    WHERE cli.id_persona = p.dni AND p.nombre LIKE CONCAT(_filtro,'%') AND
        cli.id_agencia = (SELECT id_agencia 
                FROM agencias 
                WHERE nombre COLLATE utf8_bin = _agencia) AND cli.id_cliente <> 1;
    
    ELSEIF _tipo_filtro = 'apell1' THEN
    
    SELECT COUNT(*)
    FROM clientes cli,personas p
    WHERE cli.id_persona = p.dni AND p.primer_apellido LIKE CONCAT(_filtro,'%') AND
        cli.id_agencia = (SELECT id_agencia 
                FROM agencias 
                WHERE nombre COLLATE utf8_bin = _agencia) AND cli.id_cliente <> 1;
    
    ELSEIF _tipo_filtro = 'apell2' THEN
    
    SELECT COUNT(*)
    FROM clientes cli,personas p
    WHERE cli.id_persona = p.dni AND p.segundo_apellido LIKE CONCAT(_filtro,'%') AND 
        cli.id_agencia = (SELECT id_agencia 
                FROM agencias 
                WHERE nombre COLLATE utf8_bin = _agencia) AND cli.id_cliente <> 1;
    
    ELSEIF _tipo_filtro = 'direccion' THEN
    
    SELECT COUNT(*)
    FROM clientes cli,personas p
    WHERE cli.id_persona = p.dni AND p.direccion LIKE CONCAT(_filtro,'%') AND 
        cli.id_agencia = (SELECT id_agencia 
                FROM agencias 
                WHERE nombre COLLATE utf8_bin = _agencia) AND cli.id_cliente <> 1;
    
    ELSEIF _tipo_filtro = 'localidad' THEN
    
    SELECT COUNT(*)
    FROM clientes cli,personas p
    WHERE cli.id_persona = p.dni AND p.localidad LIKE CONCAT(_filtro,'%') AND 
        cli.id_agencia = (SELECT id_agencia 
                FROM agencias 
                WHERE nombre COLLATE utf8_bin = _agencia) AND cli.id_cliente <> 1;
    
    ELSEIF _tipo_filtro = 'telefono' THEN
    
    SELECT COUNT(*)
    FROM clientes cli,personas p
    WHERE cli.id_persona = p.dni AND p.telefono LIKE CONCAT(_filtro,'%') AND 
        cli.id_agencia = (SELECT id_agencia 
                FROM agencias 
                WHERE nombre COLLATE utf8_bin = _agencia) AND cli.id_cliente <> 1;
    
    ELSEIF _tipo_filtro = 'provincia' THEN
    
    SELECT COUNT(*)
    FROM clientes cli,personas p
    WHERE cli.id_persona = p.dni AND p.provincia LIKE CONCAT(_filtro,'%') AND 
        cli.id_agencia = (SELECT id_agencia 
                FROM agencias 
                WHERE nombre COLLATE utf8_bin = _agencia) AND cli.id_cliente <> 1;
    
    ELSEIF _tipo_filtro = 'email' THEN
    
    SELECT COUNT(*)
    FROM clientes cli,personas p
    WHERE cli.id_persona = p.dni AND p.email LIKE CONCAT(_filtro,'%') AND
        cli.id_agencia = (SELECT id_agencia 
                FROM agencias 
                WHERE nombre COLLATE utf8_bin = _agencia) AND cli.id_cliente <> 1;
    
    ELSE
    
    SELECT COUNT(*)
    FROM clientes cli,personas p
    WHERE cli.id_persona = p.dni AND 
        cli.id_agencia = (SELECT id_agencia 
                FROM agencias 
                WHERE nombre COLLATE utf8_bin = _agencia) AND cli.id_cliente <> 1;

    END IF;

  END IF;
  
END$$

CREATE DEFINER=`agencia_se`@`localhost` PROCEDURE `numClientesFiltroPrueba`(_tipo_filtro VARCHAR(100), _filtro VARCHAR(100), _agencia VARCHAR(100))
BEGIN

IF _tipo_filtro = 'id' THEN
    
    SELECT COUNT(*)
    FROM clientes cli,personas p
    WHERE cli.id_persona = p.dni AND cli.id_cliente LIKE CONCAT(_filtro,'%') AND cli.id_cliente <> 1;
    
    ELSEIF _tipo_filtro = 'dni' THEN
    
    SELECT COUNT(*)
    FROM clientes cli,personas p
    WHERE cli.id_persona = p.dni AND p.dni LIKE CONCAT(_filtro,'%') AND cli.id_cliente <> 1;
    
    ELSEIF _tipo_filtro = 'nombre' THEN
    
    SELECT COUNT(*)
    FROM clientes cli,personas p
    WHERE cli.id_persona = p.dni AND p.nombre LIKE CONCAT(_filtro,'%') AND cli.id_cliente <> 1;
    
    ELSEIF _tipo_filtro = 'apell1' THEN
    
    SELECT COUNT(*)
    FROM clientes cli,personas p
    WHERE cli.id_persona = p.dni AND p.primer_apellido LIKE CONCAT(_filtro,'%') AND cli.id_cliente <> 1;
    
    ELSEIF _tipo_filtro = 'apell2' THEN
    
    SELECT COUNT(*)
    FROM clientes cli,personas p
    WHERE cli.id_persona = p.dni AND p.segundo_apellido LIKE CONCAT(_filtro,'%') AND cli.id_cliente <> 1;
    
    ELSEIF _tipo_filtro = 'direccion' THEN
    
    SELECT COUNT(*)
    FROM clientes cli,personas p
    WHERE cli.id_persona = p.dni AND p.direccion LIKE CONCAT(_filtro,'%') AND cli.id_cliente <> 1;
    
    ELSEIF _tipo_filtro = 'localidad' THEN
    
    SELECT COUNT(*)
    FROM clientes cli,personas p
    WHERE cli.id_persona = p.dni AND p.localidad LIKE CONCAT(_filtro,'%') AND cli.id_cliente <> 1;
    
    ELSEIF _tipo_filtro = 'telefono' THEN
    
    SELECT COUNT(*)
    FROM clientes cli,personas p
    WHERE cli.id_persona = p.dni AND p.telefono LIKE CONCAT(_filtro,'%') AND cli.id_cliente <> 1;
    
    ELSEIF _tipo_filtro = 'provincia' THEN
    
    SELECT COUNT(*)
    FROM clientes cli,personas p
    WHERE cli.id_persona = p.dni AND p.provincia LIKE CONCAT(_filtro,'%') AND cli.id_cliente <> 1;
    
    ELSEIF _tipo_filtro = 'email' THEN
    
    SELECT COUNT(*)
    FROM clientes cli,personas p
    WHERE cli.id_persona = p.dni AND p.email LIKE CONCAT(_filtro,'%') AND cli.id_cliente <> 1;
    
    ELSE
    
    SELECT COUNT(*)
    FROM clientes cli,personas p
    WHERE cli.id_persona = p.dni AND cli.id_cliente <> 1 ;
    
    END IF;
  
END$$

CREATE DEFINER=`agencia_se`@`localhost` PROCEDURE `numInmuebles`(_agencia VARCHAR(100))
BEGIN

SELECT COUNT(*) FROM inmuebles WHERE id_agencia = (SELECT id_agencia FROM agencias WHERE nombre = _agencia);

END$$

CREATE DEFINER=`agencia_se`@`localhost` PROCEDURE `numInmueblesTipo`(_id_agencia INT,_tipo VARCHAR(50))
BEGIN

  IF _tipo = 'Venta' OR _tipo = 'Alquiler' OR _tipo = 'Traspaso' THEN 
	SELECT COUNT(*)
	FROM inmuebles i
    INNER JOIN operaciones o ON i.id_inmueble = o.id_inmueble
    INNER JOIN fotos f ON f.id_inmueble = i.id_inmueble
    INNER JOIN tipo_operacion t ON t.id_tipo = o.id_tipo
	WHERE (o.id_agencia = _id_agencia OR o.compartir = 1) AND 
		  f.url LIKE '%principal%' AND
          t.nombre = _tipo AND
          o.disponible = 1;
  ELSE 
	SELECT COUNT(*)
	FROM inmuebles i
    INNER JOIN operaciones o ON i.id_inmueble = o.id_inmueble
    INNER JOIN fotos f ON f.id_inmueble = i.id_inmueble
    INNER JOIN tipo_operacion t ON t.id_tipo = o.id_tipo
	WHERE (o.id_agencia = _id_agencia OR o.compartir = 1) AND 
		  f.url LIKE '%principal%' AND
          o.disponible = 1;
  END IF;
  
END$$

CREATE DEFINER=`agencia_de`@`localhost` PROCEDURE `numOperacionesFiltro`(_tipo VARCHAR(50), _agencia VARCHAR(100))
BEGIN

DECLARE _id_agencia INT;
    
    IF 'Federacion' COLLATE utf8_bin = _agencia THEN 
    
		IF _tipo IN (SELECT nombre FROM tipo_operacion) THEN
        
			SELECT COUNT(*)
			FROM operaciones o, clientes c, inmuebles i, propietarios p, tipo_operacion t
			WHERE o.id_tipo = t.id_tipo AND 
				  o.id_inmueble = i.id_inmueble AND
				  i.id_propietario = p.id_propietario AND
				  o.id_cliente = c.id_cliente AND
                  t.nombre = _tipo;
        
        ELSE
		
			SELECT COUNT(*)
			FROM operaciones o, clientes c, inmuebles i, propietarios p, tipo_operacion t
			WHERE o.id_tipo = t.id_tipo AND 
				  o.id_inmueble = i.id_inmueble AND
				  i.id_propietario = p.id_propietario AND
				  o.id_cliente = c.id_cliente;
		
        END IF;
        
	ELSE 
		
        IF _tipo IN (SELECT nombre FROM tipo_operacion) THEN
        
			SELECT COUNT(*)
			FROM operaciones o, clientes c, inmuebles i, propietarios p, tipo_operacion t
			WHERE (o.id_agencia = (SELECT id_agencia 
								FROM agencias 
								WHERE nombre COLLATE utf8_bin = _agencia) OR compartir = 1) AND
				  o.id_tipo = t.id_tipo AND
				  o.id_inmueble = i.id_inmueble AND
				  i.id_propietario = p.id_propietario AND
				  o.id_cliente = c.id_cliente AND
                  t.nombre = _tipo;
		
        ELSE
        
			SELECT COUNT(*)
			FROM operaciones o, clientes c, inmuebles i, propietarios p, tipo_operacion t
			WHERE (o.id_agencia = (SELECT id_agencia 
								FROM agencias 
								WHERE nombre COLLATE utf8_bin = _agencia) OR compartir = 1) AND
				  o.id_tipo = t.id_tipo AND
				  o.id_inmueble = i.id_inmueble AND
				  i.id_propietario = p.id_propietario AND
				  o.id_cliente = c.id_cliente;
			
        END IF;
        
    END IF;
END$$

CREATE DEFINER=`agencia_se`@`localhost` PROCEDURE `numPropietarios`()
BEGIN

SELECT COUNT(*) FROM propietarios;

END$$

CREATE DEFINER=`agencia_se`@`localhost` PROCEDURE `numPropietariosAgencia`(_agencia VARCHAR(100))
BEGIN

SELECT COUNT(*) FROM propietarios WHERE id_agencia = (SELECT id_agencia FROM agencias WHERE nombre COLLATE utf8_bin = _agencia);

END$$

CREATE DEFINER=`agencia_se`@`localhost` PROCEDURE `numPropietariosFiltro`(_tipo_filtro VARCHAR(100), _filtro VARCHAR(100), _agencia VARCHAR(100))
BEGIN

  IF 'Federacion' COLLATE utf8_bin = _agencia THEN 
  
    IF _tipo_filtro = 'id' THEN
    
    SELECT COUNT(*)
    FROM propietarios pro,personas p
    WHERE pro.id_persona = p.dni AND pro.id_propietario LIKE CONCAT(_filtro,'%');
    
    ELSEIF _tipo_filtro = 'dni' THEN
    
    SELECT COUNT(*)
    FROM propietarios pro,personas p
    WHERE pro.id_persona = p.dni AND p.dni LIKE CONCAT(_filtro,'%');
    
    ELSEIF _tipo_filtro = 'nombre' THEN
    
    SELECT COUNT(*)
    FROM propietarios pro,personas p
    WHERE pro.id_persona = p.dni AND p.nombre LIKE CONCAT(_filtro,'%');
    
    ELSEIF _tipo_filtro = 'apell1' THEN
    
    SELECT COUNT(*)
    FROM propietarios pro,personas p
    WHERE pro.id_persona = p.dni AND p.primer_apellido LIKE CONCAT(_filtro,'%');
    
    ELSEIF _tipo_filtro = 'apell2' THEN
    
    SELECT COUNT(*)
    FROM propietarios pro,personas p
    WHERE pro.id_persona = p.dni AND p.segundo_apellido LIKE CONCAT(_filtro,'%');
    
    ELSEIF _tipo_filtro = 'direccion' THEN
    
    SELECT COUNT(*)
    FROM propietarios pro,personas p
    WHERE pro.id_persona = p.dni AND p.direccion LIKE CONCAT(_filtro,'%');
    
    ELSEIF _tipo_filtro = 'localidad' THEN
    
    SELECT COUNT(*)
    FROM propietarios pro,personas p
    WHERE pro.id_persona = p.dni AND p.localidad LIKE CONCAT(_filtro,'%');
    
    ELSEIF _tipo_filtro = 'telefono' THEN
    
    SELECT COUNT(*)
    FROM propietarios pro,personas p
    WHERE pro.id_persona = p.dni AND p.telefono LIKE CONCAT(_filtro,'%');
    
    ELSEIF _tipo_filtro = 'provincia' THEN
    
    SELECT COUNT(*)
    FROM propietarios pro,personas p
    WHERE pro.id_persona = p.dni AND p.provincia LIKE CONCAT(_filtro,'%');
    
    ELSEIF _tipo_filtro = 'email' THEN
    
    SELECT COUNT(*)
    FROM propietarios pro,personas p
    WHERE pro.id_persona = p.dni AND p.email LIKE CONCAT(_filtro,'%');
    
    ELSE
    
    SELECT COUNT(*)
    FROM propietarios pro,personas p
    WHERE pro.id_persona = p.dni;
    
    END IF;
    
  ELSE 

    IF _tipo_filtro = 'id' THEN
    
    SELECT COUNT(*)
    FROM propietarios pro,personas p
    WHERE pro.id_persona = p.dni AND pro.id_propietario LIKE CONCAT(_filtro,'%') AND 
        pro.id_agencia = (SELECT id_agencia 
                FROM agencias 
                WHERE nombre COLLATE utf8_bin = _agencia);
    
    ELSEIF _tipo_filtro = 'dni' THEN
    
    SELECT COUNT(*)
    FROM propietarios pro,personas p
    WHERE pro.id_persona = p.dni AND p.dni LIKE CONCAT(_filtro,'%') AND
        pro.id_agencia = (SELECT id_agencia 
                FROM agencias 
                WHERE nombre COLLATE utf8_bin = _agencia);
    
    ELSEIF _tipo_filtro = 'nombre' THEN
    
    SELECT COUNT(*)
    FROM propietarios pro,personas p
    WHERE pro.id_persona = p.dni AND p.nombre LIKE CONCAT(_filtro,'%') AND
        pro.id_agencia = (SELECT id_agencia 
                FROM agencias 
                WHERE nombre COLLATE utf8_bin = _agencia);
    
    ELSEIF _tipo_filtro = 'apell1' THEN
    
    SELECT COUNT(*)
    FROM propietarios pro,personas p
    WHERE pro.id_persona = p.dni AND p.primer_apellido LIKE CONCAT(_filtro,'%') AND
        pro.id_agencia = (SELECT id_agencia 
                FROM agencias 
                WHERE nombre COLLATE utf8_bin = _agencia);
    
    ELSEIF _tipo_filtro = 'apell2' THEN
    
    SELECT COUNT(*)
    FROM propietarios pro,personas p
    WHERE pro.id_persona = p.dni AND p.segundo_apellido LIKE CONCAT(_filtro,'%') AND 
        pro.id_agencia = (SELECT id_agencia 
                FROM agencias 
                WHERE nombre COLLATE utf8_bin = _agencia);
    
    ELSEIF _tipo_filtro = 'direccion' THEN
    
    SELECT COUNT(*)
    FROM propietarios pro,personas p
    WHERE pro.id_persona = p.dni AND p.direccion LIKE CONCAT(_filtro,'%') AND 
        pro.id_agencia = (SELECT id_agencia 
                FROM agencias 
                WHERE nombre COLLATE utf8_bin = _agencia);
    
    ELSEIF _tipo_filtro = 'localidad' THEN
    
    SELECT COUNT(*)
    FROM propietarios pro,personas p
    WHERE pro.id_persona = p.dni AND p.localidad LIKE CONCAT(_filtro,'%') AND 
        pro.id_agencia = (SELECT id_agencia 
                FROM agencias 
                WHERE nombre COLLATE utf8_bin = _agencia);
    
    ELSEIF _tipo_filtro = 'telefono' THEN
    
    SELECT COUNT(*)
    FROM propietarios pro,personas p
    WHERE pro.id_persona = p.dni AND p.telefono LIKE CONCAT(_filtro,'%') AND 
        pro.id_agencia = (SELECT id_agencia 
                FROM agencias 
                WHERE nombre COLLATE utf8_bin = _agencia);
    
    ELSEIF _tipo_filtro = 'provincia' THEN
    
    SELECT COUNT(*)
    FROM propietarios pro,personas p
    WHERE pro.id_persona = p.dni AND p.provincia LIKE CONCAT(_filtro,'%') AND 
        pro.id_agencia = (SELECT id_agencia 
                FROM agencias 
                WHERE nombre COLLATE utf8_bin = _agencia);
    
    ELSEIF _tipo_filtro = 'email' THEN
    
    SELECT COUNT(*)
    FROM propietarios pro,personas p
    WHERE pro.id_persona = p.dni AND p.email LIKE CONCAT(_filtro,'%') AND
        pro.id_agencia = (SELECT id_agencia 
                FROM agencias 
                WHERE nombre COLLATE utf8_bin = _agencia);
    
    ELSE
    
    SELECT COUNT(*)
    FROM propietarios pro,personas p
    WHERE pro.id_persona = p.dni AND 
        pro.id_agencia = (SELECT id_agencia 
                FROM agencias 
                WHERE nombre COLLATE utf8_bin = _agencia);
    
    END IF;
  
  END IF;
  
END$$

CREATE DEFINER=`agencia_se`@`localhost` PROCEDURE `numPropietariosFiltroPrueba`(_tipo_filtro VARCHAR(100), _filtro VARCHAR(100), _agencia VARCHAR(100))
BEGIN

    IF _tipo_filtro = 'id' THEN
    
    SELECT COUNT(*)
    FROM propietarios pro,personas p
    WHERE pro.id_persona = p.dni AND pro.id_propietario LIKE CONCAT(_filtro,'%');
    
    ELSEIF _tipo_filtro = 'dni' THEN
    
    SELECT COUNT(*)
    FROM propietarios pro,personas p
    WHERE pro.id_persona = p.dni AND p.dni LIKE CONCAT(_filtro,'%');
    
    ELSEIF _tipo_filtro = 'nombre' THEN
    
    SELECT COUNT(*)
    FROM propietarios pro,personas p
    WHERE pro.id_persona = p.dni AND p.nombre LIKE CONCAT(_filtro,'%');
    
    ELSEIF _tipo_filtro = 'apell1' THEN
    
    SELECT COUNT(*)
    FROM propietarios pro,personas p
    WHERE pro.id_persona = p.dni AND p.primer_apellido LIKE CONCAT(_filtro,'%');
    
    ELSEIF _tipo_filtro = 'apell2' THEN
    
    SELECT COUNT(*)
    FROM propietarios pro,personas p
    WHERE pro.id_persona = p.dni AND p.segundo_apellido LIKE CONCAT(_filtro,'%');
    
    ELSEIF _tipo_filtro = 'direccion' THEN
    
    SELECT COUNT(*)
    FROM propietarios pro,personas p
    WHERE pro.id_persona = p.dni AND p.direccion LIKE CONCAT(_filtro,'%');
    
    ELSEIF _tipo_filtro = 'localidad' THEN
    
    SELECT COUNT(*)
    FROM propietarios pro,personas p
    WHERE pro.id_persona = p.dni AND p.localidad LIKE CONCAT(_filtro,'%');
    
    ELSEIF _tipo_filtro = 'telefono' THEN
    
    SELECT COUNT(*)
    FROM propietarios pro,personas p
    WHERE pro.id_persona = p.dni AND p.telefono LIKE CONCAT(_filtro,'%');
    
    ELSEIF _tipo_filtro = 'provincia' THEN
    
    SELECT COUNT(*)
    FROM propietarios pro,personas p
    WHERE pro.id_persona = p.dni AND p.provincia LIKE CONCAT(_filtro,'%');
    
    ELSEIF _tipo_filtro = 'email' THEN
    
    SELECT COUNT(*)
    FROM propietarios pro,personas p
    WHERE pro.id_persona = p.dni AND p.email LIKE CONCAT(_filtro,'%');
    
    ELSE
    
    SELECT COUNT(*)
    FROM propietarios pro,personas p
    WHERE pro.id_persona = p.dni;
    
    END IF;
  
END$$

CREATE DEFINER=`agencia_se`@`localhost` PROCEDURE `numUsuariosFiltro`(_tipo_filtro VARCHAR(100), _filtro VARCHAR(100), _agencia VARCHAR(100))
BEGIN

  IF 'Federacion' COLLATE utf8_bin = _agencia THEN 
  
    IF _tipo_filtro = 'id' THEN

    SELECT COUNT(*)
    FROM usuarios usu,personas p
    WHERE usu.id_persona = p.dni AND usu.id_usuario LIKE CONCAT(_filtro,'%');
    
    ELSEIF _tipo_filtro = 'dni' THEN
    
    SELECT COUNT(*)
    FROM usuarios usu,personas p
    WHERE usu.id_persona = p.dni AND p.dni LIKE CONCAT(_filtro,'%');
    
    ELSEIF _tipo_filtro = 'nombre' THEN
    
    SELECT COUNT(*)
    FROM usuarios usu,personas p
    WHERE usu.id_persona = p.dni AND p.nombre LIKE CONCAT(_filtro,'%');
    
    ELSEIF _tipo_filtro = 'apell1' THEN
    
    SELECT COUNT(*)
    FROM usuarios usu,personas p
    WHERE usu.id_persona = p.dni AND p.primer_apellido LIKE CONCAT(_filtro,'%');
    
    ELSEIF _tipo_filtro = 'apell2' THEN
    
    SELECT COUNT(*)
    FROM usuarios usu,personas p
    WHERE usu.id_persona = p.dni AND p.segundo_apellido LIKE CONCAT(_filtro,'%');
    
    ELSEIF _tipo_filtro = 'usuario' THEN
    
    SELECT COUNT(*)
    FROM usuarios usu,personas p
    WHERE usu.id_persona = p.dni AND usu.usuario LIKE CONCAT(_filtro,'%');
    
    ELSEIF _tipo_filtro = 'localidad' THEN
    
    SELECT COUNT(*)
    FROM usuarios usu,personas p
    WHERE usu.id_persona = p.dni AND p.localidad LIKE CONCAT(_filtro,'%');
    
    ELSEIF _tipo_filtro = 'telefono' THEN
    
    SELECT COUNT(*)
    FROM usuarios usu,personas p
    WHERE usu.id_persona = p.dni AND p.telefono LIKE CONCAT(_filtro,'%');
    
    ELSEIF _tipo_filtro = 'provincia' THEN
    
    SELECT COUNT(*)
    FROM usuarios usu,personas p
    WHERE usu.id_persona = p.dni AND p.provincia LIKE CONCAT(_filtro,'%');
    
    ELSEIF _tipo_filtro = 'email' THEN
    
    SELECT COUNT(*)
    FROM usuarios usu,personas p
    WHERE usu.id_persona = p.dni AND p.email LIKE CONCAT(_filtro,'%');
    
    ELSE
    
    SELECT COUNT(*)
    FROM usuarios usu,personas p
    WHERE usu.id_persona = p.dni;
    
    END IF;
    
  ELSE 

    IF _tipo_filtro = 'id' THEN
    
    SELECT COUNT(*)
    FROM usuarios usu,personas p
    WHERE usu.id_persona = p.dni AND usu.id_usuario LIKE CONCAT(_filtro,'%') AND 
        usu.id_agencia = (SELECT id_agencia 
                FROM agencias 
                WHERE nombre COLLATE utf8_bin = _agencia);
    
    ELSEIF _tipo_filtro = 'dni' THEN
    
    SELECT COUNT(*)
    FROM usuarios usu,personas p
    WHERE usu.id_persona = p.dni AND p.dni LIKE CONCAT(_filtro,'%') AND
        usu.id_agencia = (SELECT id_agencia 
                FROM agencias 
                WHERE nombre COLLATE utf8_bin = _agencia);
    
    ELSEIF _tipo_filtro = 'nombre' THEN
    
    SELECT COUNT(*)
    FROM usuarios usu,personas p
    WHERE usu.id_persona = p.dni AND p.nombre LIKE CONCAT(_filtro,'%') AND
        usu.id_agencia = (SELECT id_agencia 
                FROM agencias 
                WHERE nombre COLLATE utf8_bin = _agencia);
    
    ELSEIF _tipo_filtro = 'apell1' THEN
    
    SELECT COUNT(*)
    FROM usuarios usu,personas p
    WHERE usu.id_persona = p.dni AND p.primer_apellido LIKE CONCAT(_filtro,'%') AND
        usu.id_agencia = (SELECT id_agencia 
                FROM agencias 
                WHERE nombre COLLATE utf8_bin = _agencia);
    
    ELSEIF _tipo_filtro = 'apell2' THEN
    
    SELECT COUNT(*)
    FROM usuarios usu,personas p
    WHERE usu.id_persona = p.dni AND p.segundo_apellido LIKE CONCAT(_filtro,'%') AND 
        usu.id_agencia = (SELECT id_agencia 
                FROM agencias 
                WHERE nombre COLLATE utf8_bin = _agencia);
    
    ELSEIF _tipo_filtro = 'usuario' THEN
    
    SELECT COUNT(*)
    FROM usuarios usu,personas p
    WHERE usu.id_persona = p.dni AND usu.usuario LIKE CONCAT(_filtro,'%') AND 
        usu.id_agencia = (SELECT id_agencia 
                FROM agencias 
                WHERE nombre COLLATE utf8_bin = _agencia);
    
    ELSEIF _tipo_filtro = 'localidad' THEN
    
    SELECT COUNT(*)
    FROM usuarios usu,personas p
    WHERE usu.id_persona = p.dni AND p.localidad LIKE CONCAT(_filtro,'%') AND 
        usu.id_agencia = (SELECT id_agencia 
                FROM agencias 
                WHERE nombre COLLATE utf8_bin = _agencia);
    
    ELSEIF _tipo_filtro = 'telefono' THEN
    
    SELECT COUNT(*)
    FROM usuarios usu,personas p
    WHERE usu.id_persona = p.dni AND p.telefono LIKE CONCAT(_filtro,'%') AND 
        usu.id_agencia = (SELECT id_agencia 
                FROM agencias 
                WHERE nombre COLLATE utf8_bin = _agencia);
    
    ELSEIF _tipo_filtro = 'provincia' THEN
    
    SELECT COUNT(*)
    FROM usuarios usu,personas p
    WHERE usu.id_persona = p.dni AND p.provincia LIKE CONCAT(_filtro,'%') AND 
        usu.id_agencia = (SELECT id_agencia 
                FROM agencias 
                WHERE nombre COLLATE utf8_bin = _agencia);
    
    ELSEIF _tipo_filtro = 'email' THEN
    
    SELECT COUNT(*)
    FROM usuarios usu,personas p
    WHERE usu.id_persona = p.dni AND p.email LIKE CONCAT(_filtro,'%') AND
        usu.id_agencia = (SELECT id_agencia 
                FROM agencias 
                WHERE nombre COLLATE utf8_bin = _agencia);
    
    ELSE
    
    SELECT COUNT(*)
    FROM usuarios usu,personas p
    WHERE usu.id_persona = p.dni AND 
        usu.id_agencia = (SELECT id_agencia 
                FROM agencias 
                WHERE nombre COLLATE utf8_bin = _agencia);
    
    END IF;
  
  END IF;
  
END$$

CREATE DEFINER=`agencia_se`@`localhost` PROCEDURE `numUsuariosFiltroPrueba`(_tipo_filtro VARCHAR(100), _filtro VARCHAR(100), _agencia VARCHAR(100))
BEGIN
  
    IF _tipo_filtro = 'id' THEN

    SELECT COUNT(*)
    FROM usuarios usu,personas p
    WHERE usu.id_persona = p.dni AND usu.id_usuario LIKE CONCAT(_filtro,'%');
    
    ELSEIF _tipo_filtro = 'dni' THEN
    
    SELECT COUNT(*)
    FROM usuarios usu,personas p
    WHERE usu.id_persona = p.dni AND p.dni LIKE CONCAT(_filtro,'%');
    
    ELSEIF _tipo_filtro = 'nombre' THEN
    
    SELECT COUNT(*)
    FROM usuarios usu,personas p
    WHERE usu.id_persona = p.dni AND p.nombre LIKE CONCAT(_filtro,'%');
    
    ELSEIF _tipo_filtro = 'apell1' THEN
    
    SELECT COUNT(*)
    FROM usuarios usu,personas p
    WHERE usu.id_persona = p.dni AND p.primer_apellido LIKE CONCAT(_filtro,'%');
    
    ELSEIF _tipo_filtro = 'apell2' THEN
    
    SELECT COUNT(*)
    FROM usuarios usu,personas p
    WHERE usu.id_persona = p.dni AND p.segundo_apellido LIKE CONCAT(_filtro,'%');
    
    ELSEIF _tipo_filtro = 'direccion' THEN
    
    SELECT COUNT(*)
    FROM usuarios usu,personas p
    WHERE usu.id_persona = p.dni AND p.direccion LIKE CONCAT(_filtro,'%');
    
    ELSEIF _tipo_filtro = 'localidad' THEN
    
    SELECT COUNT(*)
    FROM usuarios usu,personas p
    WHERE usu.id_persona = p.dni AND p.localidad LIKE CONCAT(_filtro,'%');
    
    ELSEIF _tipo_filtro = 'telefono' THEN
    
    SELECT COUNT(*)
    FROM usuarios usu,personas p
    WHERE usu.id_persona = p.dni AND p.telefono LIKE CONCAT(_filtro,'%');
    
    ELSEIF _tipo_filtro = 'provincia' THEN
    
    SELECT COUNT(*)
    FROM usuarios usu,personas p
    WHERE usu.id_persona = p.dni AND p.provincia LIKE CONCAT(_filtro,'%');
    
    ELSEIF _tipo_filtro = 'email' THEN
    
    SELECT COUNT(*)
    FROM usuarios usu,personas p
    WHERE usu.id_persona = p.dni AND p.email LIKE CONCAT(_filtro,'%');
    
    ELSE
    
    SELECT COUNT(*)
    FROM usuarios usu,personas p
    WHERE usu.id_persona = p.dni;
    
    END IF;
  
END$$

CREATE DEFINER=`agencia_se`@`localhost` PROCEDURE `obtenerAgencia`(_id_agencia INT)
BEGIN
	
  SELECT *
  FROM agencias
  WHERE id_agencia = _id_agencia
  ORDER BY nombre;

END$$

CREATE DEFINER=`agencia_se`@`localhost` PROCEDURE `obtenerAgencias`()
BEGIN
	
  SELECT *
  FROM agencias
  ORDER BY nombre;

END$$

CREATE DEFINER=`agencia_se`@`localhost` PROCEDURE `obtenerCliente`(_id_cliente INT)
BEGIN

  SELECT c.id_cliente, c.id_agencia, p.dni, p.nombre, p.primer_apellido,p.segundo_apellido,p.direccion,p.localidad,p.provincia,p.telefono,p.email
  FROM clientes c,personas p
  WHERE c.id_persona = p.dni AND id_cliente = _id_cliente
  ORDER BY nombre;

END$$

CREATE DEFINER=`agencia_se`@`localhost` PROCEDURE `obtenerClientes`()
BEGIN

  SELECT c.id_cliente, c.id_agencia, p.dni, p.nombre, p.primer_apellido,p.segundo_apellido,p.direccion,p.localidad,p.provincia,p.telefono,p.email
  FROM clientes c,personas p
  WHERE c.id_persona = p.dni
  ORDER BY nombre;

END$$

CREATE DEFINER=`agencia_se`@`localhost` PROCEDURE `obtenerClientesAgencia`(_id_agencia INT)
BEGIN

  SELECT c.id_cliente, c.id_agencia, p.dni, p.nombre, p.primer_apellido,p.segundo_apellido,p.direccion,p.localidad,p.provincia,p.telefono,p.email
  FROM clientes c,personas p
  WHERE c.id_persona = p.dni and id_agencia = _id_agencia
  ORDER BY nombre;

END$$

CREATE DEFINER=`agencia_se`@`localhost` PROCEDURE `obtenerClientesAgenciaPaginado`(_id_agencia INT,_limit INT,_offset INT)
BEGIN
   
   SELECT c.id_cliente, c.id_agencia, p.dni, p.nombre, p.primer_apellido,p.segundo_apellido,p.direccion,p.localidad,p.provincia,p.telefono,p.email
   FROM clientes c,personas p
   WHERE c.id_persona = p.dni and id_agencia = _id_agencia
   ORDER BY nombre
   LIMIT _limit OFFSET _offset;

END$$

CREATE DEFINER=`agencia_se`@`localhost` PROCEDURE `obtenerClientesPaginadoFiltro`(_limit INT, _offset INT, _tipo_filtro VARCHAR(100), _filtro VARCHAR(100), _agencia VARCHAR(100))
BEGIN

	IF 'Federacion' COLLATE utf8_bin = _agencia THEN

	  IF _tipo_filtro = 'id' THEN
	  
		SELECT cli.id_cliente, cli.id_agencia, p.dni, p.nombre, p.primer_apellido,p.segundo_apellido,p.direccion,p.localidad,p.provincia,p.telefono,p.email
		FROM clientes cli,personas p
		WHERE cli.id_persona = p.dni AND cli.id_cliente LIKE CONCAT(_filtro,'%') AND cli.id_cliente <> 1
		ORDER BY cli.id_cliente
		LIMIT _limit OFFSET _offset;
		
	  ELSEIF _tipo_filtro = 'dni' THEN

		SELECT cli.id_cliente, cli.id_agencia, p.dni, p.nombre, p.primer_apellido,p.segundo_apellido,p.direccion,p.localidad,p.provincia,p.telefono,p.email
		FROM clientes cli,personas p
		WHERE cli.id_persona = p.dni AND p.dni LIKE CONCAT(_filtro,'%') AND cli.id_cliente <> 1
		ORDER BY p.dni
		LIMIT _limit OFFSET _offset;
		
	  ELSEIF _tipo_filtro = 'nombre' THEN
		
		SELECT cli.id_cliente, cli.id_agencia, p.dni, p.nombre, p.primer_apellido,p.segundo_apellido,p.direccion,p.localidad,p.provincia,p.telefono,p.email
		FROM clientes cli,personas p
		WHERE cli.id_persona = p.dni AND p.nombre LIKE CONCAT(_filtro,'%') AND cli.id_cliente <> 1
		ORDER BY p.nombre
		LIMIT _limit OFFSET _offset;
		
	  ELSEIF _tipo_filtro = 'apell1' THEN
		
		SELECT cli.id_cliente, cli.id_agencia, p.dni, p.nombre, p.primer_apellido,p.segundo_apellido,p.direccion,p.localidad,p.provincia,p.telefono,p.email
		FROM clientes cli,personas p
		WHERE cli.id_persona = p.dni AND p.primer_apellido LIKE CONCAT(_filtro,'%') AND cli.id_cliente <> 1
		ORDER BY p.primer_apellido
		LIMIT _limit OFFSET _offset;
		
	  ELSEIF _tipo_filtro = 'apell2' THEN
		
		SELECT cli.id_cliente, cli.id_agencia, p.dni, p.nombre, p.primer_apellido,p.segundo_apellido,p.direccion,p.localidad,p.provincia,p.telefono,p.email
		FROM clientes cli,personas p
		WHERE cli.id_persona = p.dni AND p.segundo_apellido LIKE CONCAT(_filtro,'%') AND cli.id_cliente <> 1
		ORDER BY p.segundo_apellido
		LIMIT _limit OFFSET _offset;
		
	  ELSEIF _tipo_filtro = 'direccion' THEN
		
		SELECT cli.id_cliente, cli.id_agencia, p.dni, p.nombre, p.primer_apellido,p.segundo_apellido,p.direccion,p.localidad,p.provincia,p.telefono,p.email
		FROM clientes cli,personas p
		WHERE cli.id_persona = p.dni AND p.direccion LIKE CONCAT(_filtro,'%') AND cli.id_cliente <> 1
		ORDER BY p.direccion
		LIMIT _limit OFFSET _offset;
		
	  ELSEIF _tipo_filtro = 'localidad' THEN
		
		SELECT cli.id_cliente, cli.id_agencia, p.dni, p.nombre, p.primer_apellido,p.segundo_apellido,p.direccion,p.localidad,p.provincia,p.telefono,p.email
		FROM clientes cli,personas p
		WHERE cli.id_persona = p.dni AND p.localidad LIKE CONCAT(_filtro,'%') AND cli.id_cliente <> 1
		ORDER BY p.localidad
		LIMIT _limit OFFSET _offset;
		
	  ELSEIF _tipo_filtro = 'telefono' THEN
		
		SELECT cli.id_cliente, cli.id_agencia, p.dni, p.nombre, p.primer_apellido,p.segundo_apellido,p.direccion,p.localidad,p.provincia,p.telefono,p.email
		FROM clientes cli,personas p
		WHERE cli.id_persona = p.dni AND p.telefono LIKE CONCAT(_filtro,'%') AND cli.id_cliente <> 1
		ORDER BY p.telefono
		LIMIT _limit OFFSET _offset;
		
	  ELSEIF _tipo_filtro = 'provincia' THEN
		
		SELECT cli.id_cliente, cli.id_agencia, p.dni, p.nombre, p.primer_apellido,p.segundo_apellido,p.direccion,p.localidad,p.provincia,p.telefono,p.email
		FROM clientes cli,personas p
		WHERE cli.id_persona = p.dni AND p.provincia LIKE CONCAT(_filtro,'%') AND cli.id_cliente <> 1
		ORDER BY p.provincia
		LIMIT _limit OFFSET _offset;
		
	  ELSEIF _tipo_filtro = 'email' THEN
		
		SELECT cli.id_cliente, cli.id_agencia, p.dni, p.nombre, p.primer_apellido,p.segundo_apellido,p.direccion,p.localidad,p.provincia,p.telefono,p.email
		FROM clientes cli,personas p
		WHERE cli.id_persona = p.dni AND p.email LIKE CONCAT(_filtro,'%') AND cli.id_cliente <> 1
		ORDER BY p.email
		LIMIT _limit OFFSET _offset;
		
	  ELSE
		
		SELECT cli.id_cliente, cli.id_agencia, p.dni, p.nombre, p.primer_apellido,p.segundo_apellido,p.direccion,p.localidad,p.provincia,p.telefono,p.email
		FROM clientes cli,personas p
		WHERE cli.id_persona = p.dni AND cli.id_cliente <> 1
		ORDER BY p.nombre,p.primer_apellido,p.segundo_apellido
		LIMIT _limit OFFSET _offset;
		
	  END IF;
      
	ELSE
		
	  IF _tipo_filtro = 'id' THEN
	  
		SELECT cli.id_cliente, cli.id_agencia, p.dni, p.nombre, p.primer_apellido,p.segundo_apellido,p.direccion,p.localidad,p.provincia,p.telefono,p.email
		FROM clientes cli,personas p
		WHERE cli.id_persona = p.dni AND cli.id_cliente LIKE CONCAT(_filtro,'%') AND 
			  cli.id_agencia = (SELECT id_agencia 
								FROM agencias 
								WHERE nombre COLLATE utf8_bin = _agencia) AND cli.id_cliente <> 1
		ORDER BY cli.id_cliente
		LIMIT _limit OFFSET _offset;
		
	  ELSEIF _tipo_filtro = 'dni' THEN

		SELECT cli.id_cliente, cli.id_agencia, p.dni, p.nombre, p.primer_apellido,p.segundo_apellido,p.direccion,p.localidad,p.provincia,p.telefono,p.email
		FROM clientes cli,personas p
		WHERE cli.id_persona = p.dni AND p.dni LIKE CONCAT(_filtro,'%') AND 
			  cli.id_agencia = (SELECT id_agencia 
								FROM agencias 
								WHERE nombre COLLATE utf8_bin = _agencia) AND cli.id_cliente <> 1
		ORDER BY p.dni
		LIMIT _limit OFFSET _offset;
		
	  ELSEIF _tipo_filtro = 'nombre' THEN
		
		SELECT cli.id_cliente, cli.id_agencia, p.dni, p.nombre, p.primer_apellido,p.segundo_apellido,p.direccion,p.localidad,p.provincia,p.telefono,p.email
		FROM clientes cli,personas p
		WHERE cli.id_persona = p.dni AND p.nombre LIKE CONCAT(_filtro,'%') AND 
			  cli.id_agencia = (SELECT id_agencia 
								FROM agencias 
								WHERE nombre COLLATE utf8_bin = _agencia) AND cli.id_cliente <> 1
		ORDER BY p.nombre
		LIMIT _limit OFFSET _offset;
		
	  ELSEIF _tipo_filtro = 'apell1' THEN
		
		SELECT cli.id_cliente, cli.id_agencia, p.dni, p.nombre, p.primer_apellido,p.segundo_apellido,p.direccion,p.localidad,p.provincia,p.telefono,p.email
		FROM clientes cli,personas p
		WHERE cli.id_persona = p.dni AND p.primer_apellido LIKE CONCAT(_filtro,'%') AND 
			  cli.id_agencia = (SELECT id_agencia 
								FROM agencias 
								WHERE nombre COLLATE utf8_bin = _agencia) AND cli.id_cliente <> 1
		ORDER BY p.primer_apellido
		LIMIT _limit OFFSET _offset;
		
	  ELSEIF _tipo_filtro = 'apell2' THEN
		
		SELECT cli.id_cliente, cli.id_agencia, p.dni, p.nombre, p.primer_apellido,p.segundo_apellido,p.direccion,p.localidad,p.provincia,p.telefono,p.email
		FROM clientes cli,personas p
		WHERE cli.id_persona = p.dni AND p.segundo_apellido LIKE CONCAT(_filtro,'%') AND 
			  cli.id_agencia = (SELECT id_agencia 
								FROM agencias 
								WHERE nombre COLLATE utf8_bin = _agencia) AND cli.id_cliente <> 1
		ORDER BY p.segundo_apellido
		LIMIT _limit OFFSET _offset;
		
	  ELSEIF _tipo_filtro = 'direccion' THEN
		
		SELECT cli.id_cliente, cli.id_agencia, p.dni, p.nombre, p.primer_apellido,p.segundo_apellido,p.direccion,p.localidad,p.provincia,p.telefono,p.email
		FROM clientes cli,personas p
		WHERE cli.id_persona = p.dni AND p.direccion LIKE CONCAT(_filtro,'%') AND 
			  cli.id_agencia = (SELECT id_agencia 
								FROM agencias 
								WHERE nombre COLLATE utf8_bin = _agencia) AND cli.id_cliente <> 1
		ORDER BY p.direccion
		LIMIT _limit OFFSET _offset;
		
	  ELSEIF _tipo_filtro = 'localidad' THEN
		
		SELECT cli.id_cliente, cli.id_agencia, p.dni, p.nombre, p.primer_apellido,p.segundo_apellido,p.direccion,p.localidad,p.provincia,p.telefono,p.email
		FROM clientes cli,personas p
		WHERE cli.id_persona = p.dni AND p.localidad LIKE CONCAT(_filtro,'%') AND 
			  cli.id_agencia = (SELECT id_agencia 
								FROM agencias 
								WHERE nombre COLLATE utf8_bin = _agencia) AND cli.id_cliente <> 1
		ORDER BY p.localidad
		LIMIT _limit OFFSET _offset;
		
	  ELSEIF _tipo_filtro = 'telefono' THEN
		
		SELECT cli.id_cliente, cli.id_agencia, p.dni, p.nombre, p.primer_apellido,p.segundo_apellido,p.direccion,p.localidad,p.provincia,p.telefono,p.email
		FROM clientes cli,personas p
		WHERE cli.id_persona = p.dni AND p.telefono LIKE CONCAT(_filtro,'%') AND 
			  cli.id_agencia = (SELECT id_agencia 
								FROM agencias 
								WHERE nombre COLLATE utf8_bin = _agencia) AND cli.id_cliente <> 1
		ORDER BY p.telefono
		LIMIT _limit OFFSET _offset;
		
	  ELSEIF _tipo_filtro = 'provincia' THEN
		
		SELECT cli.id_cliente, cli.id_agencia, p.dni, p.nombre, p.primer_apellido,p.segundo_apellido,p.direccion,p.localidad,p.provincia,p.telefono,p.email
		FROM clientes cli,personas p
		WHERE cli.id_persona = p.dni AND p.provincia LIKE CONCAT(_filtro,'%') AND 
			  cli.id_agencia = (SELECT id_agencia 
								FROM agencias 
								WHERE nombre COLLATE utf8_bin = _agencia) AND cli.id_cliente <> 1
		ORDER BY p.provincia
		LIMIT _limit OFFSET _offset;
		
	  ELSEIF _tipo_filtro = 'email' THEN
		
		SELECT cli.id_cliente, cli.id_agencia, p.dni, p.nombre, p.primer_apellido,p.segundo_apellido,p.direccion,p.localidad,p.provincia,p.telefono,p.email
		FROM clientes cli,personas p
		WHERE cli.id_persona = p.dni AND p.email LIKE CONCAT(_filtro,'%') AND 
			  cli.id_agencia = (SELECT id_agencia 
								FROM agencias 
								WHERE nombre COLLATE utf8_bin = _agencia) AND cli.id_cliente <> 1
		ORDER BY p.email
		LIMIT _limit OFFSET _offset;
		
	  ELSE
		
		SELECT cli.id_cliente, cli.id_agencia, p.dni, p.nombre, p.primer_apellido,p.segundo_apellido,p.direccion,p.localidad,p.provincia,p.telefono,p.email
		FROM clientes cli,personas p
		WHERE cli.id_persona = p.dni AND 
			  cli.id_agencia = (SELECT id_agencia 
								FROM agencias 
								WHERE nombre COLLATE utf8_bin = _agencia) AND cli.id_cliente <> 1
		ORDER BY p.nombre,p.primer_apellido,p.segundo_apellido
		LIMIT _limit OFFSET _offset;
		
	  END IF;
        
    END IF;
  
END$$

CREATE DEFINER=`agencia_se`@`localhost` PROCEDURE `obtenerClientesPaginadoFiltroPrueba`(_limit INT, _offset INT, _tipo_filtro VARCHAR(100), _filtro VARCHAR(100), _agencia VARCHAR(100))
BEGIN

	  IF _tipo_filtro = 'id' THEN
	  
		SELECT cli.id_cliente, cli.id_agencia, p.dni, p.nombre, p.primer_apellido,p.segundo_apellido,p.direccion,p.localidad,p.provincia,p.telefono,p.email
		FROM clientes cli,personas p
		WHERE cli.id_persona = p.dni AND cli.id_cliente LIKE CONCAT(_filtro,'%') AND cli.id_cliente <> 1
		ORDER BY cli.id_cliente
		LIMIT _limit OFFSET _offset;
		
	  ELSEIF _tipo_filtro = 'dni' THEN

		SELECT cli.id_cliente, cli.id_agencia, p.dni, p.nombre, p.primer_apellido,p.segundo_apellido,p.direccion,p.localidad,p.provincia,p.telefono,p.email
		FROM clientes cli,personas p
		WHERE cli.id_persona = p.dni AND p.dni LIKE CONCAT(_filtro,'%') AND cli.id_cliente <> 1
		ORDER BY p.dni
		LIMIT _limit OFFSET _offset;
		
	  ELSEIF _tipo_filtro = 'nombre' THEN
		
		SELECT cli.id_cliente, cli.id_agencia, p.dni, p.nombre, p.primer_apellido,p.segundo_apellido,p.direccion,p.localidad,p.provincia,p.telefono,p.email
		FROM clientes cli,personas p
		WHERE cli.id_persona = p.dni AND p.nombre LIKE CONCAT(_filtro,'%') AND cli.id_cliente <> 1
		ORDER BY p.nombre
		LIMIT _limit OFFSET _offset;
		
	  ELSEIF _tipo_filtro = 'apell1' THEN
		
		SELECT cli.id_cliente, cli.id_agencia, p.dni, p.nombre, p.primer_apellido,p.segundo_apellido,p.direccion,p.localidad,p.provincia,p.telefono,p.email
		FROM clientes cli,personas p
		WHERE cli.id_persona = p.dni AND p.primer_apellido LIKE CONCAT(_filtro,'%') AND cli.id_cliente <> 1
		ORDER BY p.primer_apellido
		LIMIT _limit OFFSET _offset;
		
	  ELSEIF _tipo_filtro = 'apell2' THEN
		
		SELECT cli.id_cliente, cli.id_agencia, p.dni, p.nombre, p.primer_apellido,p.segundo_apellido,p.direccion,p.localidad,p.provincia,p.telefono,p.email
		FROM clientes cli,personas p
		WHERE cli.id_persona = p.dni AND p.segundo_apellido LIKE CONCAT(_filtro,'%') AND cli.id_cliente <> 1
		ORDER BY p.segundo_apellido
		LIMIT _limit OFFSET _offset;
		
	  ELSEIF _tipo_filtro = 'direccion' THEN
		
		SELECT cli.id_cliente, cli.id_agencia, p.dni, p.nombre, p.primer_apellido,p.segundo_apellido,p.direccion,p.localidad,p.provincia,p.telefono,p.email
		FROM clientes cli,personas p
		WHERE cli.id_persona = p.dni AND p.direccion LIKE CONCAT(_filtro,'%') AND cli.id_cliente <> 1
		ORDER BY p.direccion
		LIMIT _limit OFFSET _offset;
		
	  ELSEIF _tipo_filtro = 'localidad' THEN
		
		SELECT cli.id_cliente, cli.id_agencia, p.dni, p.nombre, p.primer_apellido,p.segundo_apellido,p.direccion,p.localidad,p.provincia,p.telefono,p.email
		FROM clientes cli,personas p
		WHERE cli.id_persona = p.dni AND p.localidad LIKE CONCAT(_filtro,'%') AND cli.id_cliente <> 1
		ORDER BY p.localidad
		LIMIT _limit OFFSET _offset;
		
	  ELSEIF _tipo_filtro = 'telefono' THEN
		
		SELECT cli.id_cliente, cli.id_agencia, p.dni, p.nombre, p.primer_apellido,p.segundo_apellido,p.direccion,p.localidad,p.provincia,p.telefono,p.email
		FROM clientes cli,personas p
		WHERE cli.id_persona = p.dni AND p.telefono LIKE CONCAT(_filtro,'%') AND cli.id_cliente <> 1
		ORDER BY p.telefono
		LIMIT _limit OFFSET _offset;
		
	  ELSEIF _tipo_filtro = 'provincia' THEN
		
		SELECT cli.id_cliente, cli.id_agencia, p.dni, p.nombre, p.primer_apellido,p.segundo_apellido,p.direccion,p.localidad,p.provincia,p.telefono,p.email
		FROM clientes cli,personas p
		WHERE cli.id_persona = p.dni AND p.provincia LIKE CONCAT(_filtro,'%') AND cli.id_cliente <> 1
		ORDER BY p.provincia
		LIMIT _limit OFFSET _offset;
		
	  ELSEIF _tipo_filtro = 'email' THEN
		
		SELECT cli.id_cliente, cli.id_agencia, p.dni, p.nombre, p.primer_apellido,p.segundo_apellido,p.direccion,p.localidad,p.provincia,p.telefono,p.email
		FROM clientes cli,personas p
		WHERE cli.id_persona = p.dni AND p.email LIKE CONCAT(_filtro,'%') AND cli.id_cliente <> 1
		ORDER BY p.email
		LIMIT _limit OFFSET _offset;
		
	  ELSE
		
		SELECT cli.id_cliente, cli.id_agencia, p.dni, p.nombre, p.primer_apellido,p.segundo_apellido,p.direccion,p.localidad,p.provincia,p.telefono,p.email
		FROM clientes cli,personas p
		WHERE cli.id_persona = p.dni AND cli.id_cliente <> 1
		ORDER BY p.nombre,p.primer_apellido,p.segundo_apellido
		LIMIT _limit OFFSET _offset;
		
	  END IF;
  
END$$

CREATE DEFINER=`agencia_se`@`localhost` PROCEDURE `obtenerDemandas`(_id_cliente INT)
BEGIN
	
  SELECT *
  FROM demandas
  WHERE id_cliente = _id_cliente;

END$$

CREATE DEFINER=`agencia_se`@`localhost` PROCEDURE `obtenerDetalleInmueble`(_id_inmueble INT,_id_tipo INT)
BEGIN

  SELECT i.descripcion_corta, i.descripcion_larga, i.direccion,i.codigo_postal ,i.localidad,
         i.provincia, i.planta, i.num_metros, i.num_habitaciones, i.num_wc, o.precio,o.descripcion, date_format(o.fecha,'%d/%m/%Y') as fecha, t.nombre as tipo
  FROM inmuebles i
  INNER JOIN operaciones o ON i.id_inmueble = o.id_inmueble
  INNER JOIN tipo_operacion t ON t.id_tipo = _id_tipo
  WHERE o.id_inmueble = _id_inmueble AND o.id_tipo = _id_tipo;
  
END$$

CREATE DEFINER=`agencia_se`@`localhost` PROCEDURE `obtenerFotos`(_id_inmueble INT)
BEGIN
	
  SELECT * from fotos 
  WHERE id_inmueble = _id_inmueble
  ORDER BY id_foto;

END$$

CREATE DEFINER=`agencia_se`@`localhost` PROCEDURE `obtenerFotosAgencia`(_id_inmueble INT,_agencia VARCHAR(100))
BEGIN
	
  SELECT f.id_foto,f.url,f.id_inmueble from fotos f INNER JOIN inmuebles i ON f.id_inmueble = i.id_inmueble
  WHERE i.id_agencia = (SELECT id_agencia FROM agencias WHERE nombre = _agencia) 
					   AND i.id_inmueble = _id_inmueble;

END$$

CREATE DEFINER=`agencia_se`@`localhost` PROCEDURE `obtenerInmuebles`()
BEGIN
  SELECT *
  FROM inmuebles
  ORDER BY id_inmueble;
END$$

CREATE DEFINER=`agencia_se`@`localhost` PROCEDURE `obtenerInmueblesAgencia`(_id_agencia INT)
BEGIN
  SELECT *
  FROM inmuebles
  WHERE id_agencia = _id_agencia
  ORDER BY id_inmueble;
END$$

CREATE DEFINER=`agencia_se`@`localhost` PROCEDURE `obtenerInmueblesAgenciaPaginado`(_id_agencia INT, _limit INT, _offset INT)
BEGIN
  SELECT *
  FROM inmuebles
  WHERE id_agencia = _id_agencia
  ORDER BY id_inmueble
  LIMIT _limit OFFSET _offset;
END$$

CREATE DEFINER=`agencia_se`@`localhost` PROCEDURE `obtenerInmueblesPaginado`(_id_agencia INT, _limit INT, _offset INT,_tipo VARCHAR(50))
BEGIN

  IF _tipo = 'Venta' OR _tipo = 'Alquiler' OR _tipo = 'Traspaso' THEN 
	SELECT i.id_inmueble,i.direccion,i.codigo_postal,i.planta,i.localidad,
           i.provincia,i.descripcion_corta,i.descripcion_larga,i.num_wc,
           i.num_habitaciones,i.num_metros,o.precio,o.descripcion,i.id_agencia,date_format(o.fecha,'%d/%m/%Y') as fecha,o.compartir,t.nombre as tipo,t.id_tipo,f.url
	FROM inmuebles i
    INNER JOIN operaciones o ON i.id_inmueble = o.id_inmueble
    INNER JOIN fotos f ON f.id_inmueble = i.id_inmueble
    INNER JOIN tipo_operacion t ON t.id_tipo = o.id_tipo
	WHERE (o.id_agencia = _id_agencia OR o.compartir = 1) AND 
		  f.url LIKE '%principal%' AND
          t.nombre = _tipo AND 
          o.disponible  = 1
	ORDER BY o.fecha,i.id_inmueble
	LIMIT _limit OFFSET _offset;
  ELSE 
	SELECT i.id_inmueble,i.direccion,i.codigo_postal,i.planta,i.localidad,
           i.provincia,i.descripcion_corta,i.descripcion_larga,i.num_wc,
           i.num_habitaciones,i.num_metros,o.precio,o.descripcion,i.id_agencia,date_format(o.fecha,'%d/%m/%Y') as fecha,o.compartir,t.nombre as tipo,t.id_tipo,f.url
	FROM inmuebles i
    INNER JOIN operaciones o ON i.id_inmueble = o.id_inmueble
    INNER JOIN fotos f ON f.id_inmueble = i.id_inmueble
    INNER JOIN tipo_operacion t ON t.id_tipo = o.id_tipo
	WHERE (o.id_agencia = _id_agencia OR o.compartir = 1) AND 
		  f.url LIKE '%principal%' AND
          o.disponible = 1
	ORDER BY o.fecha,i.id_inmueble
	LIMIT _limit OFFSET _offset;
  END IF;
  
END$$

CREATE DEFINER=`agencia_se`@`localhost` PROCEDURE `obtenerInmueblesPropietarioPaginado`(_id_agencia INT,_id_propietario INT, _limit INT, _offset INT)
BEGIN
  SELECT *
  FROM inmuebles
  WHERE id_agencia = _id_agencia AND id_propietario = _id_propietario
  ORDER BY id_inmueble
  LIMIT _limit OFFSET _offset;
END$$

CREATE DEFINER=`agencia_de`@`localhost` PROCEDURE `obtenerOperaciones`()
BEGIN
	SELECT id_operacion,id_agencia,id_inmueble,id_tipo,id_cliente,date_format(fecha,'%d/%m/%Y') as fecha,precio,compartir,disponible,fianza,reserva,tiempo_meses,descuento,entrada,descripcion,observaciones
    FROM operaciones
    ORDER BY id_operacion;
END$$

CREATE DEFINER=`agencia_de`@`localhost` PROCEDURE `obtenerOperacionesAgencia`(_id_agencia INT)
BEGIN
	SELECT id_operacion,id_agencia,id_inmueble,id_tipo,id_cliente,date_format(fecha,'%d/%m/%Y') as fecha,precio,compartir,disponible,fianza,reserva,tiempo_meses,descuento,entrada,descripcion,observaciones
    FROM operaciones
    WHERE id_agencia = _id_agencia
    ORDER BY id_operacion;
END$$

CREATE DEFINER=`agencia_de`@`localhost` PROCEDURE `obtenerOperacionesAgenciaPaginado`(_id_agencia INT, _limit INT, _offset INT)
BEGIN
	SELECT id_operacion,id_agencia,id_inmueble,id_tipo,id_cliente,date_format(fecha,'%d/%m/%Y') as fecha,precio,compartir,disponible,fianza,reserva,tiempo_meses,descuento,entrada,descripcion,observaciones
    FROM operaciones
    WHERE id_agencia = _id_agencia
    ORDER BY id_operacion
    LIMIT _limit OFFSET _offset;
END$$

CREATE DEFINER=`agencia_de`@`localhost` PROCEDURE `obtenerOperacionesAgenciaPropia`(_agencia VARCHAR(100), _limit INT, _offset INT)
BEGIN
	IF 'Federacion' COLLATE utf8_bin = _agencia THEN 
    
		SELECT id_operacion,id_agencia,id_inmueble,id_tipo,id_cliente,date_format(fecha,'%d/%m/%Y') as fecha,precio,compartir,disponible,fianza,reserva,tiempo_meses,descuento,entrada,descripcion,observaciones
		FROM operaciones
		ORDER BY id_operacion
		LIMIT _limit OFFSET _offset;
        
	ELSE
    
        SELECT id_operacion,id_agencia,id_inmueble,id_tipo,id_cliente,date_format(fecha,'%d/%m/%Y') as fecha,precio,compartir,disponible,fianza,reserva,tiempo_meses,descuento,entrada,descripcion,observaciones
		FROM operaciones
		WHERE id_agencia = (SELECT id_agencia 
							FROM agencias 
							WHERE nombre COLLATE utf8_bin = _agencia)
		ORDER BY id_operacion
		LIMIT _limit OFFSET _offset;
    
    END IF;
END$$

CREATE DEFINER=`agencia_de`@`localhost` PROCEDURE `obtenerOperacionesClientePaginado`(_id_agencia INT,_id_cliente INT, _limit INT, _offset INT)
BEGIN
	SELECT id_operacion,id_agencia,id_inmueble,id_tipo,id_cliente,date_format(fecha,'%d/%m/%Y') as fecha,precio,compartir,disponible,fianza,reserva,tiempo_meses,descuento,entrada,descripcion,observaciones
    FROM operaciones
    WHERE id_agencia = _id_agencia AND id_cliente = _id_cliente
    ORDER BY id_operacion
    LIMIT _limit OFFSET _offset;
END$$

CREATE DEFINER=`agencia_de`@`localhost` PROCEDURE `obtenerOperacionesPaginadoFiltro`(_limit INT, _offset INT, _tipo VARCHAR(50), _agencia VARCHAR(100))
BEGIN

DECLARE _id_agencia INT;

	/* Si la agencia del propietario que hace la peticion es Federacion, la consulta no tiene en cuenta la agencia a la que estan asocidas las operaciones */
    IF 'Federacion' COLLATE utf8_bin = _agencia THEN 
    
		IF _tipo IN (SELECT nombre FROM tipo_operacion) THEN
        
			SELECT o.id_operacion,o.id_agencia,o.descripcion,o.id_inmueble,p.id_propietario,1 as pro_self,o.id_cliente,1 as cli_self,t.nombre as tipo,t.id_tipo,o.precio,date_format(o.fecha,'%d/%m/%Y') as fecha,o.compartir,1 as op_self
			FROM operaciones o, clientes c, inmuebles i, propietarios p, tipo_operacion t
			WHERE o.id_tipo = t.id_tipo AND 
				  o.id_inmueble = i.id_inmueble AND
				  i.id_propietario = p.id_propietario AND
				  o.id_cliente = c.id_cliente AND
                  t.nombre = _tipo
			ORDER BY o.fecha,o.id_operacion
            LIMIT _limit OFFSET _offset;
        
        ELSE
		
			SELECT o.id_operacion,o.id_agencia,o.descripcion,o.id_inmueble,p.id_propietario,1 as pro_self,o.id_cliente,1 as cli_self,t.nombre as tipo,t.id_tipo,o.precio,date_format(o.fecha,'%d/%m/%Y') as fecha,o.compartir,1 as op_self
			FROM operaciones o, clientes c, inmuebles i, propietarios p, tipo_operacion t
			WHERE o.id_tipo = t.id_tipo AND 
				  o.id_inmueble = i.id_inmueble AND
				  i.id_propietario = p.id_propietario AND
				  o.id_cliente = c.id_cliente
			ORDER BY o.fecha,o.id_operacion
            LIMIT _limit OFFSET _offset;
		
        END IF;
        
	ELSE 
    
		SELECT id_agencia  INTO _id_agencia
		FROM agencias 
		WHERE nombre COLLATE utf8_bin = _agencia;
		
        IF _tipo IN (SELECT nombre FROM tipo_operacion) THEN
        
			SELECT o.id_operacion,o.id_agencia,o.descripcion,o.id_inmueble,p.id_propietario,p.id_agencia as agen_prop,IF(p.id_agencia = _id_agencia, 1,0)as pro_self,o.id_cliente,c.id_agencia as agen_cli,IF(c.id_agencia = _id_agencia, 1,0)as cli_self,t.nombre as tipo,t.id_tipo,o.precio,date_format(o.fecha,'%d/%m/%Y') as fecha,o.compartir,IF(o.id_agencia = _id_agencia, 1,0) as op_self
			FROM operaciones o, clientes c, inmuebles i, propietarios p, tipo_operacion t
			WHERE (o.id_agencia = (SELECT id_agencia 
								FROM agencias 
								WHERE nombre COLLATE utf8_bin = _agencia) OR compartir = 1) AND
				  o.id_tipo = t.id_tipo AND
				  o.id_inmueble = i.id_inmueble AND
				  i.id_propietario = p.id_propietario AND
				  o.id_cliente = c.id_cliente AND
                  t.nombre = _tipo
			ORDER BY o.fecha,o.id_operacion
            LIMIT _limit OFFSET _offset;
		
        ELSE
        
			SELECT o.id_operacion,o.id_agencia,o.descripcion,o.id_inmueble,p.id_propietario,p.id_agencia as agen_prop,IF(p.id_agencia = _id_agencia, 1,0)as pro_self,o.id_cliente,c.id_agencia as agen_cli,IF(c.id_agencia = _id_agencia, 1,0)as cli_self,t.nombre as tipo,t.id_tipo,o.precio,date_format(o.fecha,'%d/%m/%Y') as fecha,o.compartir,IF(o.id_agencia = _id_agencia, 1,0) as op_self
			FROM operaciones o, clientes c, inmuebles i, propietarios p, tipo_operacion t
			WHERE (o.id_agencia = (SELECT id_agencia 
								FROM agencias 
								WHERE nombre COLLATE utf8_bin = _agencia) OR compartir = 1) AND
				  o.id_tipo = t.id_tipo AND
				  o.id_inmueble = i.id_inmueble AND
				  i.id_propietario = p.id_propietario AND
				  o.id_cliente = c.id_cliente
			ORDER BY o.fecha,o.id_operacion
            LIMIT _limit OFFSET _offset;
			
        END IF;
        
    END IF;
END$$

CREATE DEFINER=`agencia_se`@`localhost` PROCEDURE `obtenerPermisos`(_id_usuario INT)
BEGIN

  SELECT *
  FROM permisos
  WHERE id_usuario = _id_usuario;
  
END$$

CREATE DEFINER=`agencia_se`@`localhost` PROCEDURE `obtenerPermisosExistentes`()
BEGIN

  SELECT distinct servicio
  FROM permisos;
  
END$$

CREATE DEFINER=`agencia_se`@`localhost` PROCEDURE `obtenerPermisosTabla`(_usuario VARCHAR(100), _tabla VARCHAR(100) , _agencia VARCHAR(100))
BEGIN

DECLARE id_agencia_usu1 int;
DECLARE id_agencia_usu2 int;

DECLARE perm_add  int;
DECLARE perm_upd  int;
DECLARE perm_del  int;
DECLARE perm_list int;

DECLARE res VARCHAR(4);

	/* Obtenemos el id_agencia del usuario que pide consultar permisos de otro usuario o de el mismo */
	SELECT id_agencia INTO id_agencia_usu1
    FROM agencias 
    WHERE nombre COLLATE utf8_bin = _agencia;

    /* Obtenemos el id_agencia del usuario a consultar permisos */
	SELECT id_agencia INTO id_agencia_usu2
	FROM usuarios 
	WHERE usuario COLLATE utf8_bin = _usuario;

	/* SI LA AGENCIA DEL USUARIO QUE QUIERE CONSULTAR PERMISOS DE OTRO USUARIO ES "FEDERACION",
       ENTONCES PUEDE CONSULTAR LOS PERMISOS DE CUALQUIER USUARIO */
	IF 'Federacion' COLLATE utf8_bin = _agencia OR id_agencia_usu1 = id_agencia_usu2 THEN 
		
        SELECT COUNT(*) into perm_add
        FROM usuarios u ,permisos p
        WHERE u.id_usuario = p.id_usuario AND
			  u.usuario = _usuario AND
			  p.servicio COLLATE utf8_bin = CONCAT(_tabla,'/insertar');
              
		SELECT COUNT(*) into perm_upd
        FROM usuarios u ,permisos p
        WHERE u.id_usuario = p.id_usuario AND
			  u.usuario = _usuario AND
			  p.servicio COLLATE utf8_bin = CONCAT(_tabla,'/actualizar');
              
		SELECT COUNT(*) into perm_del
        FROM usuarios u ,permisos p
        WHERE u.id_usuario = p.id_usuario AND
              u.usuario = _usuario AND
			  p.servicio COLLATE utf8_bin = CONCAT(_tabla,'/eliminar');
              
		SELECT COUNT(*) into perm_list
        FROM usuarios u ,permisos p
        WHERE u.id_usuario = p.id_usuario AND
              u.usuario = _usuario AND
              /*p.servicio COLLATE utf8_bin = _tabla;*/
              p.servicio COLLATE utf8_bin = CONCAT(_tabla,'_paginado_filtro');
			  
        
		IF perm_add = 1 then
			SET res = '1';
        ELSE
			SET res = '0';
        END IF;
        
        IF perm_upd = 1 then
			SET res = CONCAT(res,'1');
        ELSE
			SET res = CONCAT(res,'0');
        END IF;
        
        IF perm_del = 1 then
			SET res = CONCAT(res,'1');
        ELSE
			SET res = CONCAT(res,'0');
        END IF;
        
        IF perm_list = 1 then
			SET res = CONCAT(res,'1');
        ELSE
			SET res = CONCAT(res,'0');
        END IF;
        
        SELECT res;
	
    ELSE 
		SELECT -1;
    END IF;
	
END$$

CREATE DEFINER=`agencia_se`@`localhost` PROCEDURE `obtenerPersona`(_dni VARCHAR(9))
BEGIN

  SELECT *
  FROM personas
  WHERE dni = _dni;
  
END$$

CREATE DEFINER=`agencia_se`@`localhost` PROCEDURE `obtenerPropietario`(_id_propietario INT)
BEGIN

  SELECT pr.id_propietario, pr.id_agencia, p.dni, p.nombre, p.primer_apellido,p.segundo_apellido,p.direccion,p.localidad,p.provincia,p.telefono,p.email
  FROM propietarios pr,personas p
  WHERE pr.id_persona = p.dni AND id_propietario = _id_propietario
  ORDER BY nombre;
END$$

CREATE DEFINER=`agencia_se`@`localhost` PROCEDURE `obtenerPropietarios`()
BEGIN
	
  SELECT pro.id_propietario, pro.id_agencia, p.dni, p.nombre, p.primer_apellido,p.segundo_apellido,p.direccion,p.localidad,p.provincia,p.telefono,p.email
  FROM propietarios pro,personas p
  WHERE pro.id_persona = p.dni
  ORDER BY p.nombre,p.primer_apellido,p.segundo_apellido;
  
END$$

CREATE DEFINER=`agencia_se`@`localhost` PROCEDURE `obtenerPropietariosAgencia`(_id_agencia INT)
BEGIN
	
  SELECT pro.id_propietario, pro.id_agencia, p.dni, p.nombre, p.primer_apellido,p.segundo_apellido,p.direccion,p.localidad,p.provincia,p.telefono,p.email
  FROM propietarios pro,personas p
  WHERE id_agencia = _id_agencia AND pro.id_persona = p.dni
  ORDER BY nombre,primer_apellido,segundo_apellido;
  
END$$

CREATE DEFINER=`agencia_se`@`localhost` PROCEDURE `obtenerPropietariosNomAgencia`(_agencia VARCHAR(100))
BEGIN
	
  SELECT pro.id_propietario, pro.id_agencia, p.dni, p.nombre, p.primer_apellido,p.segundo_apellido,p.direccion,p.localidad,p.provincia,p.telefono,p.email
  FROM propietarios pro,personas p
  WHERE pro.id_persona = p.dni AND pro.id_agencia = (SELECT id_agencia FROM agencias WHERE nombre COLLATE utf8_bin = _agencia)
  ORDER BY nombre,primer_apellido,segundo_apellido;
  
END$$

CREATE DEFINER=`agencia_se`@`localhost` PROCEDURE `obtenerPropietariosPaginado`(_limit INT, _offset INT)
BEGIN
	
  SELECT pro.id_propietario, pro.id_agencia, p.dni, p.nombre, p.primer_apellido,p.segundo_apellido,p.direccion,p.localidad,p.provincia,p.telefono,p.email
  FROM propietarios pro,personas p
  WHERE pro.id_persona = p.dni
  ORDER BY p.nombre,p.primer_apellido,p.segundo_apellido
  LIMIT _limit OFFSET _offset;

END$$

CREATE DEFINER=`agencia_se`@`localhost` PROCEDURE `obtenerPropietariosPaginadoAgencia`(_limit INT, _offset INT, _agencia VARCHAR(100))
BEGIN
	
  SELECT pro.id_propietario, pro.id_agencia, p.dni, p.nombre, p.primer_apellido,p.segundo_apellido,p.direccion,p.localidad,p.provincia,p.telefono,p.email
  FROM propietarios pro,personas p
  WHERE pro.id_persona = p.dni AND pro.id_agencia = (SELECT id_agencia FROM agencias WHERE nombre COLLATE utf8_bin = _agencia)
  ORDER BY p.nombre,p.primer_apellido,p.segundo_apellido
  LIMIT _limit OFFSET _offset;
  
END$$

CREATE DEFINER=`agencia_se`@`localhost` PROCEDURE `obtenerPropietariosPaginadoFiltro`(_limit INT, _offset INT, _tipo_filtro VARCHAR(100), _filtro VARCHAR(100), _agencia VARCHAR(100))
BEGIN

	IF 'Federacion' COLLATE utf8_bin = _agencia THEN
    
	  IF _tipo_filtro = 'id' THEN
	  
		SELECT pro.id_propietario, pro.id_agencia, p.dni, p.nombre, p.primer_apellido,p.segundo_apellido,p.direccion,p.localidad,p.provincia,p.telefono,p.email
		FROM propietarios pro,personas p
		WHERE pro.id_persona = p.dni AND pro.id_propietario LIKE CONCAT(_filtro,'%') 
		ORDER BY pro.id_propietario
		LIMIT _limit OFFSET _offset;
		
	  ELSEIF _tipo_filtro = 'dni' THEN

		SELECT pro.id_propietario, pro.id_agencia, p.dni, p.nombre, p.primer_apellido,p.segundo_apellido,p.direccion,p.localidad,p.provincia,p.telefono,p.email
		FROM propietarios pro,personas p
		WHERE pro.id_persona = p.dni AND p.dni LIKE CONCAT(_filtro,'%') 
		ORDER BY p.dni
		LIMIT _limit OFFSET _offset;
		
	  ELSEIF _tipo_filtro = 'nombre' THEN
		
		SELECT pro.id_propietario, pro.id_agencia, p.dni, p.nombre, p.primer_apellido,p.segundo_apellido,p.direccion,p.localidad,p.provincia,p.telefono,p.email
		FROM propietarios pro,personas p
		WHERE pro.id_persona = p.dni AND p.nombre LIKE CONCAT(_filtro,'%') 
		ORDER BY p.nombre
		LIMIT _limit OFFSET _offset;
		
	  ELSEIF _tipo_filtro = 'apell1' THEN
		
		SELECT pro.id_propietario, pro.id_agencia, p.dni, p.nombre, p.primer_apellido,p.segundo_apellido,p.direccion,p.localidad,p.provincia,p.telefono,p.email
		FROM propietarios pro,personas p
		WHERE pro.id_persona = p.dni AND p.primer_apellido LIKE CONCAT(_filtro,'%') 
		ORDER BY p.primer_apellido
		LIMIT _limit OFFSET _offset;
		
	  ELSEIF _tipo_filtro = 'apell2' THEN
		
		SELECT pro.id_propietario, pro.id_agencia, p.dni, p.nombre, p.primer_apellido,p.segundo_apellido,p.direccion,p.localidad,p.provincia,p.telefono,p.email
		FROM propietarios pro,personas p
		WHERE pro.id_persona = p.dni AND p.segundo_apellido LIKE CONCAT(_filtro,'%') 
		ORDER BY p.segundo_apellido
		LIMIT _limit OFFSET _offset;
		
	  ELSEIF _tipo_filtro = 'direccion' THEN
		
		SELECT pro.id_propietario, pro.id_agencia, p.dni, p.nombre, p.primer_apellido,p.segundo_apellido,p.direccion,p.localidad,p.provincia,p.telefono,p.email
		FROM propietarios pro,personas p
		WHERE pro.id_persona = p.dni AND p.direccion LIKE CONCAT(_filtro,'%') 
		ORDER BY p.direccion
		LIMIT _limit OFFSET _offset;
		
	  ELSEIF _tipo_filtro = 'localidad' THEN
		
		SELECT pro.id_propietario, pro.id_agencia, p.dni, p.nombre, p.primer_apellido,p.segundo_apellido,p.direccion,p.localidad,p.provincia,p.telefono,p.email
		FROM propietarios pro,personas p
		WHERE pro.id_persona = p.dni AND p.localidad LIKE CONCAT(_filtro,'%') 
		ORDER BY p.localidad
		LIMIT _limit OFFSET _offset;
		
	  ELSEIF _tipo_filtro = 'telefono' THEN
		
		SELECT pro.id_propietario, pro.id_agencia, p.dni, p.nombre, p.primer_apellido,p.segundo_apellido,p.direccion,p.localidad,p.provincia,p.telefono,p.email
		FROM propietarios pro,personas p
		WHERE pro.id_persona = p.dni AND p.telefono LIKE CONCAT(_filtro,'%') 
		ORDER BY p.telefono
		LIMIT _limit OFFSET _offset;
		
	  ELSEIF _tipo_filtro = 'provincia' THEN
		
		SELECT pro.id_propietario, pro.id_agencia, p.dni, p.nombre, p.primer_apellido,p.segundo_apellido,p.direccion,p.localidad,p.provincia,p.telefono,p.email
		FROM propietarios pro,personas p
		WHERE pro.id_persona = p.dni AND p.provincia LIKE CONCAT(_filtro,'%') 
		ORDER BY p.provincia
		LIMIT _limit OFFSET _offset;
		
	  ELSEIF _tipo_filtro = 'email' THEN
		
		SELECT pro.id_propietario, pro.id_agencia, p.dni, p.nombre, p.primer_apellido,p.segundo_apellido,p.direccion,p.localidad,p.provincia,p.telefono,p.email
		FROM propietarios pro,personas p
		WHERE pro.id_persona = p.dni AND p.email LIKE CONCAT(_filtro,'%') 
		ORDER BY p.email
		LIMIT _limit OFFSET _offset;
		
	  ELSE
		
		SELECT pro.id_propietario, pro.id_agencia, p.dni, p.nombre, p.primer_apellido,p.segundo_apellido,p.direccion,p.localidad,p.provincia,p.telefono,p.email
		FROM propietarios pro,personas p
		WHERE pro.id_persona = p.dni
		ORDER BY p.nombre,p.primer_apellido,p.segundo_apellido
		LIMIT _limit OFFSET _offset;
		
	  END IF;
      
	ELSE
		
	  IF _tipo_filtro = 'id' THEN
	  
		SELECT pro.id_propietario, pro.id_agencia, p.dni, p.nombre, p.primer_apellido,p.segundo_apellido,p.direccion,p.localidad,p.provincia,p.telefono,p.email
		FROM propietarios pro,personas p
		WHERE pro.id_persona = p.dni AND pro.id_propietario LIKE CONCAT(_filtro,'%') AND 
			  pro.id_agencia = (SELECT id_agencia 
								FROM agencias 
								WHERE nombre COLLATE utf8_bin = _agencia)
		ORDER BY pro.id_propietario
		LIMIT _limit OFFSET _offset;
		
	  ELSEIF _tipo_filtro = 'dni' THEN

		SELECT pro.id_propietario, pro.id_agencia, p.dni, p.nombre, p.primer_apellido,p.segundo_apellido,p.direccion,p.localidad,p.provincia,p.telefono,p.email
		FROM propietarios pro,personas p
		WHERE pro.id_persona = p.dni AND p.dni LIKE CONCAT(_filtro,'%') AND 
			  pro.id_agencia = (SELECT id_agencia 
								FROM agencias 
								WHERE nombre COLLATE utf8_bin = _agencia)
		ORDER BY p.dni
		LIMIT _limit OFFSET _offset;
		
	  ELSEIF _tipo_filtro = 'nombre' THEN
		
		SELECT pro.id_propietario, pro.id_agencia, p.dni, p.nombre, p.primer_apellido,p.segundo_apellido,p.direccion,p.localidad,p.provincia,p.telefono,p.email
		FROM propietarios pro,personas p
		WHERE pro.id_persona = p.dni AND p.nombre LIKE CONCAT(_filtro,'%') AND 
			  pro.id_agencia = (SELECT id_agencia 
								FROM agencias 
								WHERE nombre COLLATE utf8_bin = _agencia)
		ORDER BY p.nombre
		LIMIT _limit OFFSET _offset;
		
	  ELSEIF _tipo_filtro = 'apell1' THEN
		
		SELECT pro.id_propietario, pro.id_agencia, p.dni, p.nombre, p.primer_apellido,p.segundo_apellido,p.direccion,p.localidad,p.provincia,p.telefono,p.email
		FROM propietarios pro,personas p
		WHERE pro.id_persona = p.dni AND p.primer_apellido LIKE CONCAT(_filtro,'%') AND 
			  pro.id_agencia = (SELECT id_agencia 
								FROM agencias 
								WHERE nombre COLLATE utf8_bin = _agencia)
		ORDER BY p.primer_apellido
		LIMIT _limit OFFSET _offset;
		
	  ELSEIF _tipo_filtro = 'apell2' THEN
		
		SELECT pro.id_propietario, pro.id_agencia, p.dni, p.nombre, p.primer_apellido,p.segundo_apellido,p.direccion,p.localidad,p.provincia,p.telefono,p.email
		FROM propietarios pro,personas p
		WHERE pro.id_persona = p.dni AND p.segundo_apellido LIKE CONCAT(_filtro,'%') AND 
			  pro.id_agencia = (SELECT id_agencia 
								FROM agencias 
								WHERE nombre COLLATE utf8_bin = _agencia)
		ORDER BY p.segundo_apellido
		LIMIT _limit OFFSET _offset;
		
	  ELSEIF _tipo_filtro = 'direccion' THEN
		
		SELECT pro.id_propietario, pro.id_agencia, p.dni, p.nombre, p.primer_apellido,p.segundo_apellido,p.direccion,p.localidad,p.provincia,p.telefono,p.email
		FROM propietarios pro,personas p
		WHERE pro.id_persona = p.dni AND p.direccion LIKE CONCAT(_filtro,'%') AND 
			  pro.id_agencia = (SELECT id_agencia 
								FROM agencias 
								WHERE nombre COLLATE utf8_bin = _agencia)
		ORDER BY p.direccion
		LIMIT _limit OFFSET _offset;
		
	  ELSEIF _tipo_filtro = 'localidad' THEN
		
		SELECT pro.id_propietario, pro.id_agencia, p.dni, p.nombre, p.primer_apellido,p.segundo_apellido,p.direccion,p.localidad,p.provincia,p.telefono,p.email
		FROM propietarios pro,personas p
		WHERE pro.id_persona = p.dni AND p.localidad LIKE CONCAT(_filtro,'%') AND 
			  pro.id_agencia = (SELECT id_agencia 
								FROM agencias 
								WHERE nombre COLLATE utf8_bin = _agencia)
		ORDER BY p.localidad
		LIMIT _limit OFFSET _offset;
		
	  ELSEIF _tipo_filtro = 'telefono' THEN
		
		SELECT pro.id_propietario, pro.id_agencia, p.dni, p.nombre, p.primer_apellido,p.segundo_apellido,p.direccion,p.localidad,p.provincia,p.telefono,p.email
		FROM propietarios pro,personas p
		WHERE pro.id_persona = p.dni AND p.telefono LIKE CONCAT(_filtro,'%') AND 
			  pro.id_agencia = (SELECT id_agencia 
								FROM agencias 
								WHERE nombre COLLATE utf8_bin = _agencia)
		ORDER BY p.telefono
		LIMIT _limit OFFSET _offset;
		
	  ELSEIF _tipo_filtro = 'provincia' THEN
		
		SELECT pro.id_propietario, pro.id_agencia, p.dni, p.nombre, p.primer_apellido,p.segundo_apellido,p.direccion,p.localidad,p.provincia,p.telefono,p.email
		FROM propietarios pro,personas p
		WHERE pro.id_persona = p.dni AND p.provincia LIKE CONCAT(_filtro,'%') AND 
			  pro.id_agencia = (SELECT id_agencia 
								FROM agencias 
								WHERE nombre COLLATE utf8_bin = _agencia)
		ORDER BY p.provincia
		LIMIT _limit OFFSET _offset;
		
	  ELSEIF _tipo_filtro = 'email' THEN
		
		SELECT pro.id_propietario, pro.id_agencia, p.dni, p.nombre, p.primer_apellido,p.segundo_apellido,p.direccion,p.localidad,p.provincia,p.telefono,p.email
		FROM propietarios pro,personas p
		WHERE pro.id_persona = p.dni AND p.email LIKE CONCAT(_filtro,'%') AND 
			  pro.id_agencia = (SELECT id_agencia 
								FROM agencias 
								WHERE nombre COLLATE utf8_bin = _agencia)
		ORDER BY p.email
		LIMIT _limit OFFSET _offset;
		
	  ELSE
		
		SELECT pro.id_propietario, pro.id_agencia, p.dni, p.nombre, p.primer_apellido,p.segundo_apellido,p.direccion,p.localidad,p.provincia,p.telefono,p.email
		FROM propietarios pro,personas p
		WHERE pro.id_persona = p.dni AND 
			  pro.id_agencia = (SELECT id_agencia 
								FROM agencias 
								WHERE nombre COLLATE utf8_bin = _agencia)
		ORDER BY p.nombre,p.primer_apellido,p.segundo_apellido
		LIMIT _limit OFFSET _offset;
		
	  END IF;
        
    END IF;
  
END$$

CREATE DEFINER=`agencia_se`@`localhost` PROCEDURE `obtenerPropietariosPaginadoFiltroPrueba`(_limit INT, _offset INT, _tipo_filtro VARCHAR(100), _filtro VARCHAR(100), _agencia VARCHAR(100))
BEGIN
    
	  IF _tipo_filtro = 'id' THEN
	  
		SELECT pro.id_propietario, pro.id_agencia, p.dni, p.nombre, p.primer_apellido,p.segundo_apellido,p.direccion,p.localidad,p.provincia,p.telefono,p.email
		FROM propietarios pro,personas p
		WHERE pro.id_persona = p.dni AND pro.id_propietario LIKE CONCAT(_filtro,'%') 
		ORDER BY pro.id_propietario
		LIMIT _limit OFFSET _offset;
		
	  ELSEIF _tipo_filtro = 'dni' THEN

		SELECT pro.id_propietario, pro.id_agencia, p.dni, p.nombre, p.primer_apellido,p.segundo_apellido,p.direccion,p.localidad,p.provincia,p.telefono,p.email
		FROM propietarios pro,personas p
		WHERE pro.id_persona = p.dni AND p.dni LIKE CONCAT(_filtro,'%') 
		ORDER BY p.dni
		LIMIT _limit OFFSET _offset;
		
	  ELSEIF _tipo_filtro = 'nombre' THEN
		
		SELECT pro.id_propietario, pro.id_agencia, p.dni, p.nombre, p.primer_apellido,p.segundo_apellido,p.direccion,p.localidad,p.provincia,p.telefono,p.email
		FROM propietarios pro,personas p
		WHERE pro.id_persona = p.dni AND p.nombre LIKE CONCAT(_filtro,'%') 
		ORDER BY p.nombre
		LIMIT _limit OFFSET _offset;
		
	  ELSEIF _tipo_filtro = 'apell1' THEN
		
		SELECT pro.id_propietario, pro.id_agencia, p.dni, p.nombre, p.primer_apellido,p.segundo_apellido,p.direccion,p.localidad,p.provincia,p.telefono,p.email
		FROM propietarios pro,personas p
		WHERE pro.id_persona = p.dni AND p.primer_apellido LIKE CONCAT(_filtro,'%') 
		ORDER BY p.primer_apellido
		LIMIT _limit OFFSET _offset;
		
	  ELSEIF _tipo_filtro = 'apell2' THEN
		
		SELECT pro.id_propietario, pro.id_agencia, p.dni, p.nombre, p.primer_apellido,p.segundo_apellido,p.direccion,p.localidad,p.provincia,p.telefono,p.email
		FROM propietarios pro,personas p
		WHERE pro.id_persona = p.dni AND p.segundo_apellido LIKE CONCAT(_filtro,'%') 
		ORDER BY p.segundo_apellido
		LIMIT _limit OFFSET _offset;
		
	  ELSEIF _tipo_filtro = 'direccion' THEN
		
		SELECT pro.id_propietario, pro.id_agencia, p.dni, p.nombre, p.primer_apellido,p.segundo_apellido,p.direccion,p.localidad,p.provincia,p.telefono,p.email
		FROM propietarios pro,personas p
		WHERE pro.id_persona = p.dni AND p.direccion LIKE CONCAT(_filtro,'%') 
		ORDER BY p.direccion
		LIMIT _limit OFFSET _offset;
		
	  ELSEIF _tipo_filtro = 'localidad' THEN
		
		SELECT pro.id_propietario, pro.id_agencia, p.dni, p.nombre, p.primer_apellido,p.segundo_apellido,p.direccion,p.localidad,p.provincia,p.telefono,p.email
		FROM propietarios pro,personas p
		WHERE pro.id_persona = p.dni AND p.localidad LIKE CONCAT(_filtro,'%') 
		ORDER BY p.localidad
		LIMIT _limit OFFSET _offset;
		
	  ELSEIF _tipo_filtro = 'telefono' THEN
		
		SELECT pro.id_propietario, pro.id_agencia, p.dni, p.nombre, p.primer_apellido,p.segundo_apellido,p.direccion,p.localidad,p.provincia,p.telefono,p.email
		FROM propietarios pro,personas p
		WHERE pro.id_persona = p.dni AND p.telefono LIKE CONCAT(_filtro,'%') 
		ORDER BY p.telefono
		LIMIT _limit OFFSET _offset;
		
	  ELSEIF _tipo_filtro = 'provincia' THEN
		
		SELECT pro.id_propietario, pro.id_agencia, p.dni, p.nombre, p.primer_apellido,p.segundo_apellido,p.direccion,p.localidad,p.provincia,p.telefono,p.email
		FROM propietarios pro,personas p
		WHERE pro.id_persona = p.dni AND p.provincia LIKE CONCAT(_filtro,'%') 
		ORDER BY p.provincia
		LIMIT _limit OFFSET _offset;
		
	  ELSEIF _tipo_filtro = 'email' THEN
		
		SELECT pro.id_propietario, pro.id_agencia, p.dni, p.nombre, p.primer_apellido,p.segundo_apellido,p.direccion,p.localidad,p.provincia,p.telefono,p.email
		FROM propietarios pro,personas p
		WHERE pro.id_persona = p.dni AND p.email LIKE CONCAT(_filtro,'%') 
		ORDER BY p.email
		LIMIT _limit OFFSET _offset;
		
	  ELSE
		
		SELECT pro.id_propietario, pro.id_agencia, p.dni, p.nombre, p.primer_apellido,p.segundo_apellido,p.direccion,p.localidad,p.provincia,p.telefono,p.email
		FROM propietarios pro,personas p
		WHERE pro.id_persona = p.dni
		ORDER BY p.nombre,p.primer_apellido,p.segundo_apellido
		LIMIT _limit OFFSET _offset;
		
	  END IF;

END$$

CREATE DEFINER=`agencia_se`@`localhost` PROCEDURE `obtenerTipoOperacionDeOperacion`(_id_operacion INT)
BEGIN
	
  SELECT t.nombre,t.descripcion,o.id_operacion
  FROM tipo_operacion t,operaciones o 
  WHERE t.id_tipo = o.id_tipo AND o.id_operacion = _id_operacion;
  
END$$

CREATE DEFINER=`agencia_se`@`localhost` PROCEDURE `obtenerTiposOperacion`()
BEGIN
	
  SELECT *
  FROM tipo_operacion;
  
END$$

CREATE DEFINER=`agencia_se`@`localhost` PROCEDURE `obtenerUsuario`(_id_usuario INT)
BEGIN

  SELECT u.id_usuario, u.usuario, u.id_agencia, p.dni, p.nombre, p.primer_apellido,p.segundo_apellido,p.direccion,p.localidad,p.provincia,p.telefono,p.email
  FROM usuarios u,personas p
  WHERE u.id_persona = p.dni AND id_usuario = _id_usuario
  ORDER BY nombre;
END$$

CREATE DEFINER=`agencia_se`@`localhost` PROCEDURE `obtenerUsuarios`()
BEGIN
	
  SELECT u.id_usuario, u.usuario, u.pass, u.id_agencia, p.dni, p.nombre, p.primer_apellido,p.segundo_apellido,p.direccion,p.localidad,p.provincia,p.telefono,p.email
  FROM usuarios u,personas p
  WHERE u.id_persona = p.dni
  ORDER BY u.usuario;
  
END$$

CREATE DEFINER=`agencia_se`@`localhost` PROCEDURE `obtenerUsuariosAgencia`(_id_agencia INT)
BEGIN
  SELECT u.id_usuario, u.usuario, u.pass, u.id_agencia, p.dni, p.nombre, p.primer_apellido,p.segundo_apellido,p.direccion,p.localidad,p.provincia,p.telefono,p.email
  FROM usuarios u,personas p
  WHERE u.id_persona = p.dni AND id_agencia = _id_agencia
  ORDER BY u.usuario;
END$$

CREATE DEFINER=`agencia_se`@`localhost` PROCEDURE `obtenerUsuariosComboBox`()
BEGIN
	
  SELECT id_usuario, usuario
  FROM usuarios
  ORDER BY usuario;

END$$

CREATE DEFINER=`agencia_se`@`localhost` PROCEDURE `obtenerUsuariosPaginadoFiltro`(_limit INT, _offset INT, _tipo_filtro VARCHAR(100), _filtro VARCHAR(100), _agencia VARCHAR(100))
BEGIN

	IF 'Federacion' COLLATE utf8_bin = _agencia THEN

	  IF _tipo_filtro = 'id' THEN
	
		SELECT usu.id_usuario, usu.id_agencia, usu.usuario, p.dni, p.nombre, p.primer_apellido,p.segundo_apellido,p.direccion,p.localidad,p.provincia,p.telefono,p.email
		FROM usuarios usu,personas p
		WHERE usu.id_persona = p.dni AND usu.id_usuario LIKE CONCAT(_filtro,'%') 
		ORDER BY usu.id_usuario
		LIMIT _limit OFFSET _offset;
		
	  ELSEIF _tipo_filtro = 'dni' THEN

		SELECT usu.id_usuario, usu.id_agencia, usu.usuario, p.dni, p.nombre, p.primer_apellido,p.segundo_apellido,p.direccion,p.localidad,p.provincia,p.telefono,p.email
		FROM usuarios usu,personas p
		WHERE usu.id_persona = p.dni AND p.dni LIKE CONCAT(_filtro,'%') 
		ORDER BY p.dni
		LIMIT _limit OFFSET _offset;
		
	  ELSEIF _tipo_filtro = 'nombre' THEN
		
		SELECT usu.id_usuario, usu.id_agencia, usu.usuario, p.dni, p.nombre, p.primer_apellido,p.segundo_apellido,p.direccion,p.localidad,p.provincia,p.telefono,p.email
		FROM usuarios usu,personas p
		WHERE usu.id_persona = p.dni AND p.nombre LIKE CONCAT(_filtro,'%') 
		ORDER BY p.nombre
		LIMIT _limit OFFSET _offset;
		
	  ELSEIF _tipo_filtro = 'apell1' THEN
		
		SELECT usu.id_usuario, usu.id_agencia, usu.usuario, p.dni, p.nombre, p.primer_apellido,p.segundo_apellido,p.direccion,p.localidad,p.provincia,p.telefono,p.email
		FROM usuarios usu,personas p
		WHERE usu.id_persona = p.dni AND p.primer_apellido LIKE CONCAT(_filtro,'%') 
		ORDER BY p.primer_apellido
		LIMIT _limit OFFSET _offset;
		
	  ELSEIF _tipo_filtro = 'apell2' THEN
		
		SELECT usu.id_usuario, usu.id_agencia, usu.usuario, p.dni, p.nombre, p.primer_apellido,p.segundo_apellido,p.direccion,p.localidad,p.provincia,p.telefono,p.email
		FROM usuarios usu,personas p
		WHERE usu.id_persona = p.dni AND p.segundo_apellido LIKE CONCAT(_filtro,'%') 
		ORDER BY p.segundo_apellido
		LIMIT _limit OFFSET _offset;
		
	  ELSEIF _tipo_filtro = 'usuario' THEN
		
		SELECT usu.id_usuario, usu.id_agencia, usu.usuario, p.dni, p.nombre, p.primer_apellido,p.segundo_apellido,p.direccion,p.localidad,p.provincia,p.telefono,p.email
		FROM usuarios usu,personas p
		WHERE usu.id_persona = p.dni AND usu.usuario LIKE CONCAT(_filtro,'%') 
		ORDER BY usu.usuario
		LIMIT _limit OFFSET _offset;
		
	  ELSEIF _tipo_filtro = 'localidad' THEN
		
		SELECT usu.id_usuario, usu.id_agencia, usu.usuario, p.dni, p.nombre, p.primer_apellido,p.segundo_apellido,p.direccion,p.localidad,p.provincia,p.telefono,p.email
		FROM usuarios usu,personas p
		WHERE usu.id_persona = p.dni AND p.localidad LIKE CONCAT(_filtro,'%') 
		ORDER BY p.localidad
		LIMIT _limit OFFSET _offset;
		
	  ELSEIF _tipo_filtro = 'telefono' THEN
		
		SELECT usu.id_usuario, usu.id_agencia, usu.usuario, p.dni, p.nombre, p.primer_apellido,p.segundo_apellido,p.direccion,p.localidad,p.provincia,p.telefono,p.email
		FROM usuarios usu,personas p
		WHERE usu.id_persona = p.dni AND p.telefono LIKE CONCAT(_filtro,'%') 
		ORDER BY p.telefono
		LIMIT _limit OFFSET _offset;
		
	  ELSEIF _tipo_filtro = 'provincia' THEN
		
		SELECT usu.id_usuario, usu.id_agencia, usu.usuario, p.dni, p.nombre, p.primer_apellido,p.segundo_apellido,p.direccion,p.localidad,p.provincia,p.telefono,p.email
		FROM usuarios usu,personas p
		WHERE usu.id_persona = p.dni AND p.provincia LIKE CONCAT(_filtro,'%') 
		ORDER BY p.provincia
		LIMIT _limit OFFSET _offset;
		
	  ELSEIF _tipo_filtro = 'email' THEN
		
		SELECT usu.id_usuario, usu.id_agencia, usu.usuario, p.dni, p.nombre, p.primer_apellido,p.segundo_apellido,p.direccion,p.localidad,p.provincia,p.telefono,p.email
		FROM usuarios usu,personas p
		WHERE usu.id_persona = p.dni AND p.email LIKE CONCAT(_filtro,'%') 
		ORDER BY p.email
		LIMIT _limit OFFSET _offset;
		
	  ELSE
		
		SELECT usu.id_usuario, usu.id_agencia, usu.usuario, p.dni, p.nombre, p.primer_apellido,p.segundo_apellido,p.direccion,p.localidad,p.provincia,p.telefono,p.email
		FROM usuarios usu,personas p
		WHERE usu.id_persona = p.dni
		ORDER BY p.nombre,p.primer_apellido,p.segundo_apellido
		LIMIT _limit OFFSET _offset;
		
	  END IF;
      
	ELSE
		
	  IF _tipo_filtro = 'id' THEN
	  
		SELECT usu.id_usuario, usu.id_agencia, usu.usuario, p.dni, p.nombre, p.primer_apellido,p.segundo_apellido,p.direccion,p.localidad,p.provincia,p.telefono,p.email
		FROM usuarios usu,personas p
		WHERE usu.id_persona = p.dni AND usu.id_usuario LIKE CONCAT(_filtro,'%') AND 
			  usu.id_agencia = (SELECT id_agencia 
								FROM agencias 
								WHERE nombre COLLATE utf8_bin = _agencia)
		ORDER BY usu.id_usuario
		LIMIT _limit OFFSET _offset;
		
	  ELSEIF _tipo_filtro = 'dni' THEN

		SELECT usu.id_usuario, usu.id_agencia, usu.usuario, p.dni, p.nombre, p.primer_apellido,p.segundo_apellido,p.direccion,p.localidad,p.provincia,p.telefono,p.email
		FROM usuarios usu,personas p
		WHERE usu.id_persona = p.dni AND p.dni LIKE CONCAT(_filtro,'%') AND 
			  usu.id_agencia = (SELECT id_agencia 
								FROM agencias 
								WHERE nombre COLLATE utf8_bin = _agencia)
		ORDER BY p.dni
		LIMIT _limit OFFSET _offset;
		
	  ELSEIF _tipo_filtro = 'nombre' THEN
		
		SELECT usu.id_usuario, usu.id_agencia, usu.usuario, p.dni, p.nombre, p.primer_apellido,p.segundo_apellido,p.direccion,p.localidad,p.provincia,p.telefono,p.email
		FROM usuarios usu,personas p
		WHERE usu.id_persona = p.dni AND p.nombre LIKE CONCAT(_filtro,'%') AND 
			  usu.id_agencia = (SELECT id_agencia 
								FROM agencias 
								WHERE nombre COLLATE utf8_bin = _agencia)
		ORDER BY p.nombre
		LIMIT _limit OFFSET _offset;
		
	  ELSEIF _tipo_filtro = 'apell1' THEN
		
		SELECT usu.id_usuario, usu.id_agencia, usu.usuario, p.dni, p.nombre, p.primer_apellido,p.segundo_apellido,p.direccion,p.localidad,p.provincia,p.telefono,p.email
		FROM usuarios usu,personas p
		WHERE usu.id_persona = p.dni AND p.primer_apellido LIKE CONCAT(_filtro,'%') AND 
			  usu.id_agencia = (SELECT id_agencia 
								FROM agencias 
								WHERE nombre COLLATE utf8_bin = _agencia)
		ORDER BY p.primer_apellido
		LIMIT _limit OFFSET _offset;
		
	  ELSEIF _tipo_filtro = 'apell2' THEN
		
		SELECT usu.id_usuario, usu.id_agencia, usu.usuario, p.dni, p.nombre, p.primer_apellido,p.segundo_apellido,p.direccion,p.localidad,p.provincia,p.telefono,p.email
		FROM usuarios usu,personas p
		WHERE usu.id_persona = p.dni AND p.segundo_apellido LIKE CONCAT(_filtro,'%') AND 
			  usu.id_agencia = (SELECT id_agencia 
								FROM agencias 
								WHERE nombre COLLATE utf8_bin = _agencia)
		ORDER BY p.segundo_apellido
		LIMIT _limit OFFSET _offset;
		
	  ELSEIF _tipo_filtro = 'usuario' THEN
		
		SELECT usu.id_usuario, usu.id_agencia, usu.usuario, p.dni, p.nombre, p.primer_apellido,p.segundo_apellido,p.direccion,p.localidad,p.provincia,p.telefono,p.email
		FROM usuarios usu,personas p
		WHERE usu.id_persona = p.dni AND usu.usuario LIKE CONCAT(_filtro,'%') AND 
			  usu.id_agencia = (SELECT id_agencia 
								FROM agencias 
								WHERE nombre COLLATE utf8_bin = _agencia)
		ORDER BY usu.usuario
		LIMIT _limit OFFSET _offset;
		
	  ELSEIF _tipo_filtro = 'localidad' THEN
		
		SELECT usu.id_usuario, usu.id_agencia, usu.usuario, p.dni, p.nombre, p.primer_apellido,p.segundo_apellido,p.direccion,p.localidad,p.provincia,p.telefono,p.email
		FROM usuarios usu,personas p
		WHERE usu.id_persona = p.dni AND p.localidad LIKE CONCAT(_filtro,'%') AND 
			  usu.id_agencia = (SELECT id_agencia 
								FROM agencias 
								WHERE nombre COLLATE utf8_bin = _agencia)
		ORDER BY p.localidad
		LIMIT _limit OFFSET _offset;
		
	  ELSEIF _tipo_filtro = 'telefono' THEN
		
		SELECT usu.id_usuario, usu.id_agencia, usu.usuario, p.dni, p.nombre, p.primer_apellido,p.segundo_apellido,p.direccion,p.localidad,p.provincia,p.telefono,p.email
		FROM usuarios usu,personas p
		WHERE usu.id_persona = p.dni AND p.telefono LIKE CONCAT(_filtro,'%') AND 
			  usu.id_agencia = (SELECT id_agencia 
								FROM agencias 
								WHERE nombre COLLATE utf8_bin = _agencia)
		ORDER BY p.telefono
		LIMIT _limit OFFSET _offset;
		
	  ELSEIF _tipo_filtro = 'provincia' THEN
		
		SELECT usu.id_usuario, usu.id_agencia, usu.usuario, p.dni, p.nombre, p.primer_apellido,p.segundo_apellido,p.direccion,p.localidad,p.provincia,p.telefono,p.email
		FROM usuarios usu,personas p
		WHERE usu.id_persona = p.dni AND p.provincia LIKE CONCAT(_filtro,'%') AND 
			  usu.id_agencia = (SELECT id_agencia 
								FROM agencias 
								WHERE nombre COLLATE utf8_bin = _agencia)
		ORDER BY p.provincia
		LIMIT _limit OFFSET _offset;
		
	  ELSEIF _tipo_filtro = 'email' THEN
		
		SELECT usu.id_usuario, usu.id_agencia, usu.usuario, p.dni, p.nombre, p.primer_apellido,p.segundo_apellido,p.direccion,p.localidad,p.provincia,p.telefono,p.email
		FROM usuarios usu,personas p
		WHERE usu.id_persona = p.dni AND p.email LIKE CONCAT(_filtro,'%') AND 
			  usu.id_agencia = (SELECT id_agencia 
								FROM agencias 
								WHERE nombre COLLATE utf8_bin = _agencia)
		ORDER BY p.email
		LIMIT _limit OFFSET _offset;
		
	  ELSE
		
		SELECT usu.id_usuario, usu.id_agencia, usu.usuario, p.dni, p.nombre, p.primer_apellido,p.segundo_apellido,p.direccion,p.localidad,p.provincia,p.telefono,p.email
		FROM usuarios usu,personas p
		WHERE usu.id_persona = p.dni AND 
			  usu.id_agencia = (SELECT id_agencia 
								FROM agencias 
								WHERE nombre COLLATE utf8_bin = _agencia)
		ORDER BY p.nombre,p.primer_apellido,p.segundo_apellido
		LIMIT _limit OFFSET _offset;
		
	  END IF;
        
    END IF;
  
END$$

CREATE DEFINER=`agencia_se`@`localhost` PROCEDURE `obtenerUsuariosPaginadoFiltroPrueba`(_limit INT, _offset INT, _tipo_filtro VARCHAR(100), _filtro VARCHAR(100), _agencia VARCHAR(100))
BEGIN

	  IF _tipo_filtro = 'id' THEN
	
		SELECT usu.id_usuario, usu.id_agencia, usu.usuario, p.dni, p.nombre, p.primer_apellido,p.segundo_apellido,p.direccion,p.localidad,p.provincia,p.telefono,p.email
		FROM usuarios usu,personas p
		WHERE usu.id_persona = p.dni AND usu.id_usuario LIKE CONCAT(_filtro,'%') 
		ORDER BY usu.id_usuario
		LIMIT _limit OFFSET _offset;
		
	  ELSEIF _tipo_filtro = 'dni' THEN

		SELECT usu.id_usuario, usu.id_agencia, usu.usuario, p.dni, p.nombre, p.primer_apellido,p.segundo_apellido,p.direccion,p.localidad,p.provincia,p.telefono,p.email
		FROM usuarios usu,personas p
		WHERE usu.id_persona = p.dni AND p.dni LIKE CONCAT(_filtro,'%') 
		ORDER BY p.dni
		LIMIT _limit OFFSET _offset;
		
	  ELSEIF _tipo_filtro = 'nombre' THEN
		
		SELECT usu.id_usuario, usu.id_agencia, usu.usuario, p.dni, p.nombre, p.primer_apellido,p.segundo_apellido,p.direccion,p.localidad,p.provincia,p.telefono,p.email
		FROM usuarios usu,personas p
		WHERE usu.id_persona = p.dni AND p.nombre LIKE CONCAT(_filtro,'%') 
		ORDER BY p.nombre
		LIMIT _limit OFFSET _offset;
		
	  ELSEIF _tipo_filtro = 'apell1' THEN
		
		SELECT usu.id_usuario, usu.id_agencia, usu.usuario, p.dni, p.nombre, p.primer_apellido,p.segundo_apellido,p.direccion,p.localidad,p.provincia,p.telefono,p.email
		FROM usuarios usu,personas p
		WHERE usu.id_persona = p.dni AND p.primer_apellido LIKE CONCAT(_filtro,'%') 
		ORDER BY p.primer_apellido
		LIMIT _limit OFFSET _offset;
		
	  ELSEIF _tipo_filtro = 'apell2' THEN
		
		SELECT usu.id_usuario, usu.id_agencia, usu.usuario, p.dni, p.nombre, p.primer_apellido,p.segundo_apellido,p.direccion,p.localidad,p.provincia,p.telefono,p.email
		FROM usuarios usu,personas p
		WHERE usu.id_persona = p.dni AND p.segundo_apellido LIKE CONCAT(_filtro,'%') 
		ORDER BY p.segundo_apellido
		LIMIT _limit OFFSET _offset;
		
	  ELSEIF _tipo_filtro = 'usuario' THEN
		
		SELECT usu.id_usuario, usu.id_agencia, usu.usuario, p.dni, p.nombre, p.primer_apellido,p.segundo_apellido,p.direccion,p.localidad,p.provincia,p.telefono,p.email
		FROM usuarios usu,personas p
		WHERE usu.id_persona = p.dni AND usu.usuario LIKE CONCAT(_filtro,'%') 
		ORDER BY usu.usuario
		LIMIT _limit OFFSET _offset;
		
	  ELSEIF _tipo_filtro = 'localidad' THEN
		
		SELECT usu.id_usuario, usu.id_agencia, usu.usuario, p.dni, p.nombre, p.primer_apellido,p.segundo_apellido,p.direccion,p.localidad,p.provincia,p.telefono,p.email
		FROM usuarios usu,personas p
		WHERE usu.id_persona = p.dni AND p.localidad LIKE CONCAT(_filtro,'%') 
		ORDER BY p.localidad
		LIMIT _limit OFFSET _offset;
		
	  ELSEIF _tipo_filtro = 'telefono' THEN
		
		SELECT usu.id_usuario, usu.id_agencia, usu.usuario, p.dni, p.nombre, p.primer_apellido,p.segundo_apellido,p.direccion,p.localidad,p.provincia,p.telefono,p.email
		FROM usuarios usu,personas p
		WHERE usu.id_persona = p.dni AND p.telefono LIKE CONCAT(_filtro,'%') 
		ORDER BY p.telefono
		LIMIT _limit OFFSET _offset;
		
	  ELSEIF _tipo_filtro = 'provincia' THEN
		
		SELECT usu.id_usuario, usu.id_agencia, usu.usuario, p.dni, p.nombre, p.primer_apellido,p.segundo_apellido,p.direccion,p.localidad,p.provincia,p.telefono,p.email
		FROM usuarios usu,personas p
		WHERE usu.id_persona = p.dni AND p.provincia LIKE CONCAT(_filtro,'%') 
		ORDER BY p.provincia
		LIMIT _limit OFFSET _offset;
		
	  ELSEIF _tipo_filtro = 'email' THEN
		
		SELECT usu.id_usuario, usu.id_agencia, usu.usuario, p.dni, p.nombre, p.primer_apellido,p.segundo_apellido,p.direccion,p.localidad,p.provincia,p.telefono,p.email
		FROM usuarios usu,personas p
		WHERE usu.id_persona = p.dni AND p.email LIKE CONCAT(_filtro,'%') 
		ORDER BY p.email
		LIMIT _limit OFFSET _offset;
		
	  ELSE
		
		SELECT usu.id_usuario, usu.id_agencia, usu.usuario, p.dni, p.nombre, p.primer_apellido,p.segundo_apellido,p.direccion,p.localidad,p.provincia,p.telefono,p.email
		FROM usuarios usu,personas p
		WHERE usu.id_persona = p.dni
		ORDER BY p.nombre,p.primer_apellido,p.segundo_apellido
		LIMIT _limit OFFSET _offset;
		
	  END IF;
  
END$$

CREATE DEFINER=`agencia_se`@`localhost` PROCEDURE `usuarioTienePermisos`(_usuario VARCHAR(100), _servicio VARCHAR(200))
BEGIN

declare result INT;

  SELECT COUNT(*) INTO result
  FROM permisos
  WHERE id_usuario = (SELECT id_usuario 
					  FROM usuarios 
                      WHERE usuario COLLATE utf8_bin = _usuario) AND servicio COLLATE utf8_bin = _servicio;
                      
  IF result = 1 THEN
	SELECT 1;
  ELSE
	SELECT 0;
  END IF;
					
END$$

--
-- Funciones
--
CREATE DEFINER=`agencia_se`@`localhost` FUNCTION `checkPermisos`(_usuario VARCHAR(100),_name_ws VARCHAR(200)) RETURNS tinyint(4)
BEGIN

  declare result TINYINT;
  
  /* Devuelve 1 si el usuario tiene permisos para el servicio de nombre name_ws */
  SELECT COUNT(*) into result
  FROM usuarios u INNER JOIN permisos p ON u.id_usuario = p.id_usuario
  WHERE u.usuario COLLATE utf8_bin = _usuario AND p.servicio COLLATE utf8_bin = _name_ws;
  
  IF result = 0 THEN 
	RETURN 0;
  else
	RETURN 1;
  END IF;
  
END$$

CREATE DEFINER=`agencia_se`@`localhost` FUNCTION `existeDniCliente`(_dni VARCHAR(9)) RETURNS tinyint(4)
BEGIN

  declare result TINYINT;
  
  SELECT COUNT(*) INTO result
  FROM clientes
  WHERE id_persona = _dni;
  
  RETURN result;

END$$

CREATE DEFINER=`agencia_se`@`localhost` FUNCTION `existeDniPropietario`(_dni VARCHAR(9)) RETURNS tinyint(4)
BEGIN

  declare result TINYINT;
  
  SELECT COUNT(*) INTO result
  FROM propietarios
  WHERE id_persona = _dni;
  
  RETURN result;

END$$

CREATE DEFINER=`agencia_se`@`localhost` FUNCTION `existeDniUsuario`(_dni VARCHAR(9)) RETURNS tinyint(4)
BEGIN

  declare result TINYINT;
  
  SELECT COUNT(*) INTO result
  FROM usuarios
  WHERE id_persona = _dni;
  
  RETURN result;

END$$

CREATE DEFINER=`agencia_se`@`localhost` FUNCTION `existeIdCliente`(_id_cliente INT) RETURNS tinyint(4)
BEGIN

  declare result TINYINT;
  
  SELECT COUNT(*) INTO result
  FROM clientes
  WHERE id_cliente = _id_cliente;
  
  RETURN result;

END$$

CREATE DEFINER=`agencia_se`@`localhost` FUNCTION `existeIdPropietario`(_id_propietario INT) RETURNS tinyint(4)
BEGIN

  declare result TINYINT;
  
  SELECT COUNT(*) INTO result
  FROM propietarios
  WHERE id_propietario = _id_propietario;
  
  RETURN result;

END$$

CREATE DEFINER=`agencia_se`@`localhost` FUNCTION `existeIdUsuario`(_id_usuario INT) RETURNS tinyint(4)
BEGIN

  declare result TINYINT;
  
  SELECT COUNT(*) INTO result
  FROM usuarios
  WHERE id_usuario = _id_usuario;
  
  RETURN result;

END$$

CREATE DEFINER=`agencia_se`@`localhost` FUNCTION `isRoot`(_usuario VARCHAR(100)) RETURNS tinyint(4)
BEGIN

  declare result TINYINT;
  
  SELECT COUNT(*) into result
  FROM usuarios
  WHERE usuario COLLATE utf8_bin = _usuario AND id_agencia = 4;
  
  IF result = 0 THEN 
	RETURN 0;
  else
	RETURN 1;
  END IF;

END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `agencias`
--

CREATE TABLE IF NOT EXISTS `agencias` (
  `id_agencia` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `direccion` varchar(250) DEFAULT NULL,
  `localidad` varchar(100) DEFAULT NULL,
  `provincia` varchar(100) DEFAULT NULL,
  `telefono` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_agencia`),
  UNIQUE KEY `nombre` (`nombre`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Volcado de datos para la tabla `agencias`
--

INSERT INTO `agencias` (`id_agencia`, `nombre`, `direccion`, `localidad`, `provincia`, `telefono`, `email`) VALUES
(1, 'Agencia_A', 'C/ Hospital nº 2 camión', 'Alzira', 'Valencia', '961704909', 'agencia_a@gmail.es'),
(2, 'Agencia_B', 'C/ Corcuera nº 7', 'Benimaclet', 'Valencia', '964567543', 'agencia_b@gmail.es'),
(3, 'Agencia_C', 'C/ Salamanca nº 24', 'Sueca', 'Valencia', '964567890', 'agencia_c@gmail.es'),
(4, 'Federacion', NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE IF NOT EXISTS `clientes` (
  `id_cliente` int(11) NOT NULL AUTO_INCREMENT,
  `id_agencia` int(11) NOT NULL,
  `id_persona` varchar(9) NOT NULL,
  PRIMARY KEY (`id_cliente`),
  UNIQUE KEY `dni_UNIQUE` (`id_persona`),
  KEY `fk_clientes_agencias1_idx` (`id_agencia`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`id_cliente`, `id_agencia`, `id_persona`) VALUES
(1, 1, '0'),
(2, 1, '73580994Z'),
(3, 2, '73580994V'),
(4, 4, '73580994A');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `demandas`
--

CREATE TABLE IF NOT EXISTS `demandas` (
  `id_demanda` int(11) NOT NULL AUTO_INCREMENT,
  `id_cliente` int(11) NOT NULL,
  `descripcion` varchar(250) DEFAULT NULL,
  `compartir` tinyint(4) NOT NULL,
  PRIMARY KEY (`id_demanda`),
  UNIQUE KEY `descripcion` (`descripcion`),
  KEY `fk_demandas_clientes1_idx` (`id_cliente`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `fotos`
--

CREATE TABLE IF NOT EXISTS `fotos` (
  `id_foto` int(11) NOT NULL AUTO_INCREMENT,
  `url` varchar(250) NOT NULL,
  `descripcion` varchar(100) NOT NULL,
  `id_inmueble` int(11) NOT NULL,
  PRIMARY KEY (`id_foto`),
  UNIQUE KEY `url` (`url`),
  KEY `fk_Fotos_inmuebles1_idx` (`id_inmueble`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- Volcado de datos para la tabla `fotos`
--

INSERT INTO `fotos` (`id_foto`, `url`, `descripcion`, `id_inmueble`) VALUES
(1, 'http://agencia1/img/1principal.jpg', 'Imagen exterior de la casa', 1),
(2, 'http://agencia2/img/2principal.jpg', 'Imagen exterior de la casa', 2),
(3, 'http://agencia1/img/1cocina.jpg\r\n', 'Imagen de la cocina', 1),
(4, 'http://agencia1/img/1vestidor.jpg', 'Imagen del vestidor', 1),
(5, 'http://agencia1/img/1entrada.jpg\r\n', 'Imagen de la entrada', 1),
(6, 'http://agencia2/img/2salon.jpg', 'Imagen del salón', 2),
(7, 'http://agencia2/img/2dormitorio1.jpg', 'Imagen del dormitorio de matrimonio', 2),
(8, 'http://agencia2/img/2dormitorio2.jpg', 'Imagen del dormitorio de invitados', 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inmuebles`
--

CREATE TABLE IF NOT EXISTS `inmuebles` (
  `id_inmueble` int(11) NOT NULL AUTO_INCREMENT,
  `direccion` varchar(250) NOT NULL DEFAULT '',
  `codigo_postal` varchar(20) NOT NULL DEFAULT '',
  `planta` int(11) DEFAULT NULL,
  `localidad` varchar(100) DEFAULT NULL,
  `provincia` varchar(100) DEFAULT NULL,
  `descripcion_corta` varchar(250) DEFAULT NULL,
  `descripcion_larga` varchar(1000) DEFAULT NULL,
  `num_wc` int(11) DEFAULT NULL,
  `num_habitaciones` int(11) DEFAULT NULL,
  `num_metros` int(11) DEFAULT NULL,
  `id_agencia` int(11) NOT NULL,
  `id_propietario` int(11) NOT NULL,
  PRIMARY KEY (`direccion`,`codigo_postal`),
  UNIQUE KEY `id_inmueble` (`id_inmueble`),
  KEY `fk_inmueble_agencia_idx` (`id_agencia`),
  KEY `fk_inmueble_propietario_idx` (`id_propietario`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Volcado de datos para la tabla `inmuebles`
--

INSERT INTO `inmuebles` (`id_inmueble`, `direccion`, `codigo_postal`, `planta`, `localidad`, `provincia`, `descripcion_corta`, `descripcion_larga`, `num_wc`, `num_habitaciones`, `num_metros`, `id_agencia`, `id_propietario`) VALUES
(1, 'C/ Ramón y Cajal', '46410', 2, 'Alzira', 'Valencia', 'Casa en la montaña con vistas al mar', 'Casa ubicada en las afueras de la ciudad donde el aire es puro y las vistas hermosas.\r\nTiene 4 enormes habitaciones a un lado y un gran salón al otro totalmente amueblado con muebles de la máxima calidad. Además la estancia tiene una gran chimenea y dos baños. Al fondo hay una despensa y una zona de oficina y también una terraza donde hay otro aseo y unas escaleras para acceder a la azotea donde hay un cuarto trastero donde hay un espacio muy amplio para guardar cualquier cosa.\r\nLa casa es perfecta para disfrutar de unas vacaciones o de fiestas y reuniones familiares. No pierdas la oportunidad y acercarte a verla.', 2, 5, 100, 1, 1),
(2, 'C/ Uchana I nº 26', '42410', 2, 'Sueca', 'Valencia', 'Casa de lujo ubicada en la zona centro', 'Fantástica casa de lujo que se encuetra en la zona del instituto Joan Fuster, a menos 100 metros del ajuntamiento y de la estación del tren.\r\nTiene una disposición tipo loft con 4 enormes habitaciones a un lado y un gran salón al otro donde pueden verse las antiguas paredes de cantos que han sido rescatadas. Además la estancia tiene un gran tragaluz al centro y dos baños (uno que puede mejorarse y un aseo). Al fondo hay una despensa y una zona de office y también una terraza donde hay otro aseo y unas escaleras para acceder a la azotea donde hay una 5ª habitación.\r\nLa casa tiene infinitas posibilidades con una inversión mínima porque el grueso de la restauración está prácticamente hecho y en la calle donde se aloja están a punto de iniciarse obras de mejora por parte del Ayuntamiento de Sueca. Mejor, acercarse a verla.', 3, 8, 150, 2, 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `operaciones`
--

CREATE TABLE IF NOT EXISTS `operaciones` (
  `id_operacion` int(11) NOT NULL AUTO_INCREMENT,
  `id_agencia` int(11) NOT NULL,
  `id_inmueble` int(11) NOT NULL,
  `id_tipo` int(11) NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `fecha` date DEFAULT NULL,
  `precio` int(11) DEFAULT NULL,
  `compartir` tinyint(4) DEFAULT NULL,
  `disponible` tinyint(4) DEFAULT NULL,
  `fianza` decimal(10,2) DEFAULT NULL,
  `reserva` decimal(10,2) DEFAULT NULL,
  `tiempo_meses` int(11) DEFAULT NULL,
  `descuento` decimal(10,2) NOT NULL,
  `entrada` decimal(10,2) DEFAULT NULL,
  `descripcion` varchar(250) NOT NULL,
  `observaciones` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`id_inmueble`,`id_tipo`),
  UNIQUE KEY `id_operacion_UNIQUE` (`id_operacion`),
  KEY `fk_operaciones_inmuebles1_idx` (`id_inmueble`),
  KEY `fk_operaciones_tipo_operacion1_idx` (`id_tipo`),
  KEY `fk_operaciones_clientes1_idx` (`id_cliente`),
  KEY `id_agencia` (`id_agencia`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=17 ;

--
-- Volcado de datos para la tabla `operaciones`
--

INSERT INTO `operaciones` (`id_operacion`, `id_agencia`, `id_inmueble`, `id_tipo`, `id_cliente`, `fecha`, `precio`, `compartir`, `disponible`, `fianza`, `reserva`, `tiempo_meses`, `descuento`, `entrada`, `descripcion`, `observaciones`) VALUES
(1, 1, 1, 1, 3, '2016-03-20', 60000, 1, 1, '123.34', '200.43', 12, '10.40', '2300.40', 'Se vende casa en la montaña con vistas al mar', 'Observo atentamente'),
(2, 3, 1, 2, 1, '2016-04-07', 123, 0, 1, '123.00', '12.00', 12, '1.00', '1.00', 'Se alquila casa en la montaña con vistas al mar', 'observations'),
(16, 4, 1, 3, 1, '2016-06-08', 39, 0, 1, '0.00', '0.00', 0, '0.00', '0.00', 'Se traspasa casa en la montaña con vistas al mar', 'Nada'),
(4, 3, 2, 1, 1, '2016-04-15', 250000, 0, 1, NULL, NULL, NULL, '0.00', NULL, 'Se vende casa de lujo ubicada en la zona centro', NULL),
(3, 2, 2, 2, 1, '2016-04-15', 900, 0, 1, '200.00', '1000.00', 1, '10.00', '1000.00', 'Se alquila casa de lujo ubicada en la zona centro', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permisos`
--

CREATE TABLE IF NOT EXISTS `permisos` (
  `servicio` varchar(200) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  PRIMARY KEY (`servicio`,`id_usuario`),
  KEY `fk_servicios_usuarios1_idx` (`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `permisos`
--

INSERT INTO `permisos` (`servicio`, `id_usuario`) VALUES
('', 0),
('agencias', 1),
('clientes', 1),
('clientes/eliminar', 1),
('clientes_paginado_filtro', 1),
('operaciones/compartir', 1),
('operaciones_paginado_filtro', 1),
('permisos/addDel', 1),
('permisos/tabla', 1),
('propietarios/actualizar', 1),
('propietarios/eliminar', 1),
('propietarios/insertar', 1),
('propietarios_paginado_filtro', 1),
('usuarios/actualizar', 1),
('usuarios/eliminar', 1),
('usuarios_paginado_filtro', 1),
('agencia', 2),
('agencias/actualizar', 2),
('agencias/eliminar', 2),
('agencias/insertar', 2),
('cliente', 2),
('clientes/actualizar', 2),
('clientes/insertar_persona', 2),
('clientes/insertar_sin_persona', 2),
('clientes_agencia', 2),
('clientes_agencia_paginado', 2),
('clientes_paginado_filtro', 2),
('demandas', 2),
('demandas/actualizar', 2),
('demandas/compartir', 2),
('demandas/eliminar', 2),
('demandas/insertar', 2),
('existe_dni_cliente', 2),
('existe_dni_propietario', 2),
('existe_dni_usuario', 2),
('existe_id_cliente', 2),
('existe_id_propietario', 2),
('existe_id_usuario', 2),
('fotos', 2),
('fotos/actualizar', 2),
('fotos/eliminar', 2),
('fotos/insertar', 2),
('fotos_agencia', 2),
('inmuebles', 2),
('inmuebles/actualizar', 2),
('inmuebles/eliminar', 2),
('inmuebles/insertar', 2),
('inmuebles_agencia', 2),
('inmuebles_agencia_paginado', 2),
('inmuebles_propietario_paginado', 2),
('operaciones', 2),
('operaciones/actualizar', 2),
('operaciones/compartir', 2),
('operaciones/eliminar', 2),
('operaciones/insertar', 2),
('operaciones_agencia', 2),
('operaciones_agencia_paginado', 2),
('operaciones_agencia_propia', 2),
('operaciones_cliente_paginado', 2),
('operaciones_paginado_filtro', 2),
('operaciones_paginado_tipo', 2),
('permisos', 2),
('permisos/actualizar', 2),
('permisos/addDel', 2),
('permisos/eliminar', 2),
('permisos/insertar', 2),
('permisos/tabla', 2),
('permisos_existentes', 2),
('persona', 2),
('personas/actualizar', 2),
('personas/eliminar', 2),
('personas/insertar', 2),
('propietario', 2),
('propietarios', 2),
('propietarios/actualizar', 2),
('propietarios/insertar_persona', 2),
('propietarios/insertar_sin_persona', 2),
('propietarios_agencia', 2),
('propietarios_paginado_filtro', 2),
('tipos_operacion', 2),
('tipo_operacion/actualizar', 2),
('tipo_operacion/eliminar', 2),
('tipo_operacion/insertar', 2),
('tipo_operacion_de_operacion', 2),
('usuario', 2),
('usuarios/eliminar', 2),
('usuarios/insertar_persona', 2),
('usuarios/insertar_sin_persona/', 2),
('usuarios_agencia', 2),
('usuarios_paginado_filtro', 2),
('clientes/actualizar', 3),
('clientes_paginado_filtro', 3),
('operaciones/compartir', 3),
('operaciones_paginado_filtro', 3),
('permisos/addDel', 3),
('permisos/tabla', 3),
('propietarios', 3),
('propietarios/actualizar', 3),
('propietarios/eliminar', 3),
('propietarios/insertar', 3),
('propietarios_paginado_filtro', 3),
('usuarios/actualizar', 3),
('usuarios_paginado_filtro', 3),
('clientes_paginado_filtro', 4),
('operaciones/compartir', 4),
('operaciones_paginado_filtro', 4),
('propietarios_paginado_filtro', 4),
('usuarios/actualizar', 4),
('usuarios_paginado_filtro', 4),
('clientes_paginado_filtro', 5),
('propietarios/insertar', 5),
('usuarios_paginado_filtro', 5),
('clientes/actualizar', 6),
('clientes/eliminar', 6),
('propietarios/actualizar', 6),
('usuarios/eliminar', 6),
('propietarios/eliminar', 7);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `personas`
--

CREATE TABLE IF NOT EXISTS `personas` (
  `dni` varchar(9) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `primer_apellido` varchar(50) NOT NULL,
  `segundo_apellido` varchar(50) NOT NULL,
  `direccion` varchar(250) NOT NULL,
  `localidad` varchar(100) NOT NULL,
  `provincia` varchar(100) NOT NULL,
  `telefono` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  PRIMARY KEY (`dni`),
  KEY `dni` (`dni`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `personas`
--

INSERT INTO `personas` (`dni`, `nombre`, `primer_apellido`, `segundo_apellido`, `direccion`, `localidad`, `provincia`, `telefono`, `email`) VALUES
('0', '', '', '', '', '', '', '', ''),
('73580994A', 'Amancio', 'Garcia', 'Robles', 'C/ Fortuna', 'Alcorcón', 'Madrid', '961704900', 'amancio@gmail.com'),
('73580994B', 'Bernardos', 'Balero', 'Batiato', 'C / Utiel', 'Coslada', 'Madrid', '961704901', 'bernardo@gmail.com'),
('73580994C', 'Carlos', 'Vargas', 'Lozano', 'C/ Independencia', 'Mataró', 'Barcelona', '961704902', 'carlos@gmail.com'),
('73580994D', 'Daniel', 'Suarez', 'Gracia', 'C/ Pelleter nº 6', 'Valencia', 'Valencia', '961704903', 'daniel@gmail.com'),
('73580994S', 'Ana', 'Anaya', 'Amibo', 'C/ Anastasia nº 27', 'Riola', 'Valencia', '961704332', 'ana@gmail.com'),
('73580994T', 'Benito', 'Barragán', 'Basora', 'C/ Botella', 'Barcelona', 'Barcelona', '961704909', 'benito@gmail.com'),
('73580994V', 'Galindo', 'Perejill', 'Montes', 'C/ La huerta', 'Sollana', 'Valencia', '964789876', 'galindo@gmail.com'),
('73580994W', 'Federico', 'Falchiani', 'Falcado', 'C/ Falsillo', 'Ferrol', 'La Coruña', '961411381', 'federico@hotmail.com'),
('73580994Z', 'Juan', 'Rodriguez', 'Martinez', 'C/ Corbera', 'Cullera', 'Valencia', '961705678', 'juan@gmail.com'),
('73580995A', 'Marta', 'Fernandez', 'Alcazar', 'C / Valencia', 'Alicante', 'Alicante', '961708978', 'marta@gmail.com'),
('73580995B', 'Paco', 'Fernandez', 'Tapias', 'C / Pacheco', 'Alzira', 'Valencia', '34333455', 'paco@gmail.com');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `propietarios`
--

CREATE TABLE IF NOT EXISTS `propietarios` (
  `id_propietario` int(11) NOT NULL AUTO_INCREMENT,
  `id_agencia` int(11) NOT NULL,
  `id_persona` varchar(9) NOT NULL,
  PRIMARY KEY (`id_propietario`),
  UNIQUE KEY `dni_UNIQUE` (`id_persona`),
  KEY `id_agencia` (`id_agencia`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Volcado de datos para la tabla `propietarios`
--

INSERT INTO `propietarios` (`id_propietario`, `id_agencia`, `id_persona`) VALUES
(1, 1, '73580994A'),
(2, 1, '73580994B'),
(3, 2, '73580994C'),
(4, 2, '73580994D');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_operacion`
--

CREATE TABLE IF NOT EXISTS `tipo_operacion` (
  `id_tipo` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  `descripcion` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`id_tipo`),
  UNIQUE KEY `nombre` (`nombre`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Volcado de datos para la tabla `tipo_operacion`
--

INSERT INTO `tipo_operacion` (`id_tipo`, `nombre`, `descripcion`) VALUES
(1, 'Venta', ''),
(2, 'Alquiler', ''),
(3, 'Traspaso', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE IF NOT EXISTS `usuarios` (
  `id_usuario` int(11) NOT NULL AUTO_INCREMENT,
  `usuario` varchar(100) NOT NULL,
  `pass` varchar(100) NOT NULL,
  `id_agencia` int(11) NOT NULL,
  `id_persona` varchar(9) NOT NULL,
  PRIMARY KEY (`id_usuario`),
  UNIQUE KEY `usuario_UNIQUE` (`usuario`),
  KEY `fk_comerciales_agencias1_idx` (`id_agencia`),
  KEY `id_persona` (`id_persona`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `usuario`, `pass`, `id_agencia`, `id_persona`) VALUES
(1, 'Ana', '$2y$10$JfAchiZFITyEsXGmkxmQKO57M8Jm0hbjfdvbD0UJHMGWQ67lg0UMS', 1, '73580994S'),
(2, 'Benito', '$2y$10$JfAchiZFITyEsXGmkxmQKO57M8Jm0hbjfdvbD0UJHMGWQ67lg0UMS', 2, '73580994T'),
(3, 'Fede', '$1$4H5.5m1.$kjN6uZikQ0c4XP59nloqi/', 4, '73580994W'),
(4, 'Marta', '$2y$10$JfAchiZFITyEsXGmkxmQKO57M8Jm0hbjfdvbD0UJHMGWQ67lg0UMS', 2, '73580995A'),
(5, 'Paco', '$2y$10$JfAchiZFITyEsXGmkxmQKO57M8Jm0hbjfdvbD0UJHMGWQ67lg0UMS', 1, '73580995B');

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD CONSTRAINT `fk_clientes_agencias` FOREIGN KEY (`id_agencia`) REFERENCES `agencias` (`id_agencia`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_clientes_personas` FOREIGN KEY (`id_persona`) REFERENCES `personas` (`dni`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `demandas`
--
ALTER TABLE `demandas`
  ADD CONSTRAINT `fk_demandas_clientes` FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`id_cliente`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `fotos`
--
ALTER TABLE `fotos`
  ADD CONSTRAINT `fk_fotos_inmuebles` FOREIGN KEY (`id_inmueble`) REFERENCES `inmuebles` (`id_inmueble`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `inmuebles`
--
ALTER TABLE `inmuebles`
  ADD CONSTRAINT `fk_inmuebles_agencias` FOREIGN KEY (`id_agencia`) REFERENCES `agencias` (`id_agencia`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_inmuebles_propietarios` FOREIGN KEY (`id_propietario`) REFERENCES `propietarios` (`id_propietario`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `operaciones`
--
ALTER TABLE `operaciones`
  ADD CONSTRAINT `fk_operaciones_agencias` FOREIGN KEY (`id_agencia`) REFERENCES `agencias` (`id_agencia`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_operaciones_inmuebles` FOREIGN KEY (`id_inmueble`) REFERENCES `inmuebles` (`id_inmueble`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_operaciones_tipo_operacion` FOREIGN KEY (`id_tipo`) REFERENCES `tipo_operacion` (`id_tipo`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `propietarios`
--
ALTER TABLE `propietarios`
  ADD CONSTRAINT `fk_propietarios_agencias` FOREIGN KEY (`id_agencia`) REFERENCES `agencias` (`id_agencia`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_propietarios_personas` FOREIGN KEY (`id_persona`) REFERENCES `personas` (`dni`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `fk_usuarios_agencias` FOREIGN KEY (`id_agencia`) REFERENCES `agencias` (`id_agencia`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_usuarios_personas` FOREIGN KEY (`id_persona`) REFERENCES `personas` (`dni`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
