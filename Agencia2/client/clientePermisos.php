<?php

class ClienteWSPermisos
{


    /***************************************************************

       Metodo que hace una peticion al Servicio Web de Permisos para 
       añadir o eliminar un permiso determinado

    ****************************************************************/
 
    public function sendAddDelPermiso($user,$pass,$agencia,$usuario,$permiso,$accion)
    {

        $data = array("user" => $user,"pass" => $pass,"agencia" => $agencia,
                      "usuario" => $usuario,"permiso" => $permiso,"accion" => $accion);

        $ch = curl_init("http://apirest/permisos/addDel/");
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
    
}