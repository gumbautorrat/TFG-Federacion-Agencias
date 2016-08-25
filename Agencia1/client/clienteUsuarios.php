<?php

class ClienteWSUsuarios
{

    /***************************************************************

       Metodo que pregunta al Servicio Web de Usuarios si un determinado 
       usuario puede logearse en el sistama o no

    ****************************************************************/
 
    public function sendGetLogin($user,$pass,$agencia)
    {

        $data = array("user" => $user,"pass" => $pass,"agencia" => $agencia);
        $ch = curl_init("http://apirest/usuarios/login/");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS,http_build_query($data));
        $response = curl_exec($ch);
        curl_close($ch);

        if(!$response) 
        {
            return false;
        }
        else
        {
            return $response;
        }

    }

    /***************************************************************

       Metodo que hace una peticion al Servicio Web de Usuarios para que 
       elimine un usuario determinado

    ****************************************************************/
 
    public function sendEliminarUsuario($user,$pass,$agencia,$id_usuario)
    {

        $data = array("user" => $user,"pass" => $pass,"agencia" => $agencia);
        $ch = curl_init("http://apirest/usuarios/eliminar/$id_usuario");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        curl_setopt($ch, CURLOPT_POSTFIELDS,http_build_query($data));
        $response = curl_exec($ch);
        curl_close($ch);

        if(!$response) 
        {
            return false;
        }
        else
        {
            return $response;
        }

    }

     /***************************************************************

       Metodo que hace una peticion al Servicio Web de Usuarios para
       actualizar un usuario determinado

    ****************************************************************/
 
    public function sendActualizarCliente($user,$pass,$agencia,$id_usuario,$id_agencia,$usuario,$dni,
                                          $nombre,$apell1,$apell2,$direccion,$localidad,$provincia,
                                          $telefono,$email)
    {

        $data = array("user" => $user,"passwd" => $pass,"agencia" => $agencia,
                      "id_agencia" => $id_agencia,"usuario" => $usuario,"dni" => $dni,
                      "nombre" => $nombre,"primer_apellido"=> $apell1,
                      "segundo_apellido" => $apell2,"direccion" => $direccion,
                      "localidad" => $localidad,"provincia" => $provincia,
                      "telefono" => $telefono,"email" => $email);

        $ch = curl_init("http://apirest/usuarios/actualizar/$id_usuario");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch, CURLOPT_POSTFIELDS,http_build_query($data));
        $response = curl_exec($ch);
        curl_close($ch);

        if(!$response) 
        {
            return false;
        }
        else
        {
            return $response;
        }

    }

    /***************************************************************

       Metodo que solicita al Servicio Web todos los usuarios que hay 
       en la BD para rellenar un comboBox que hay en la vista 
       gestion de permisos. 

    ****************************************************************/
 
    public function sendGetUsers()
    {

        $ch = curl_init("http://apirest/usuarios/comboBox/");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        $response = curl_exec($ch);
        curl_close($ch);

        if(!$response) 
        {
            return false;
        }
        else
        {
            return $response;
        }

    }
    
}