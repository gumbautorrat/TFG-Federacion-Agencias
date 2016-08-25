<?php

class ClienteWSOperaciones
{

    /***************************************************************

       Metodo que hace una peticion al Servicio Web de Operaciones para
       que actualize el atributo compartir de una operacion determinada

    ****************************************************************/
 
    public function sendCompartirOperacion($user,$pass,$agencia,$compartir,$id_operacion)
    {

        $data = array("user" => $user,"pass" => $pass,"agencia" => $agencia,"compartir" => $compartir);
        $ch = curl_init("http://apirest/operaciones/compartir/$id_operacion");
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
    
}