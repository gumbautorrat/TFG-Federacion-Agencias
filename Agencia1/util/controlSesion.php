<?php 

	class ControlSesion{

		public static function iniciar_sesion($usuario, $pass, $agencia){
			if(session_id() == ''){
				session_start();
			}
			
			$_SESSION['usuario'] = $usuario;
			$_SESSION['pass'] = $pass;
			$_SESSION['agencia'] = $agencia;
			
		}

		public static function cerrar_sesion(){

			if(session_id() == ''){
				session_start();
			}

			if(isset($_SESSION['usuario'])){
				unset($_SESSION['usuario']);
			}

			if(isset($_SESSION['pass'])){
				unset($_SESSION['pass']);
			}

			if(isset($_SESSION['agencia'])){
				unset($_SESSION['agencia']);
			}

			session_destroy();
		}

		public static function sesion_iniciada(){
			if(session_id() == ''){
				session_start();
			}

			if(isset($_SESSION['usuario']) && isset($_SESSION['pass']) && isset($_SESSION['agencia'])){
				return true;
			}else{
				return false;
			}
		}

		public static function obtenerNomUsuario(){
			return $_SESSION['usuario'];
		}

		public static function obtenerPassword(){
			return $_SESSION['pass'];
		}

		public static function obtenerAgencia(){
			return $_SESSION['agencia'];
		}

	}

?>