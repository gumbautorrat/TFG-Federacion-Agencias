<?php
	
	include "ControlSesion.php";
	
	if(ControlSesion::sesion_iniciada()){
		ControlSesion::cerrar_sesion();
		header('Location: ../backoffice/views/login.php');
	}else{
		header('Location: ../backoffice/views/login.php');
	}

?>