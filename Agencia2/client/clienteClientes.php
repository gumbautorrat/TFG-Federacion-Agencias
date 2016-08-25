<?php

class ClienteWSClientes
{

    /***************************************************************

       Metodo que hace una peticion al Servicio Web de Clientes para que 
       elimine un cliente determinado

    ****************************************************************/
 
    public function sendEliminarCliente($user,$pass,$agencia,$id_cliente)
    {

        $data = array("user" => $user,"pass" => $pass,"agencia" => $agencia);
        $ch = curl_init("http://apirest/clientes/eliminar/$id_cliente");
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

       Metodo que hace una peticion al Servicio Web de Clientes para
       actualizar un cliente determinado

    ****************************************************************/
 
    public function sendActualizarCliente($user,$pass,$agencia,$id_cliente,$id_agencia,$dni,$nombre,
                                              $apell1,$apell2,$direccion,$localidad,$provincia,
                                              $telefono,$email)
    {

        $data = array("user" => $user,"pass" => $pass,"agencia" => $agencia,
                      "id_cliente" => $id_cliente,"id_agencia" => $id_agencia,
                      "dni" => $dni,"nombre" => $nombre, "primer_apellido"=> $apell1,
                      "segundo_apellido" => $apell2, "direccion" => $direccion,
                      "localidad" => $localidad, "provincia" => $provincia,
                      "telefono" => $telefono, "email" => $email);

        $ch = curl_init("http://apirest/clientes/actualizar/$id_cliente");
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