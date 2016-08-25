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
    
}