<?php

class ClienteWSInmuebles
{

    /***************************************************************

       Metodo que solicita al Servicio Web el detalle de un determinado 
       inmueble 

    ****************************************************************/
 
    public function sendGetDetalleInmueble($id_inmueble,$id_tipo)
    {

        $data = array("id_tipo" => $id_tipo);
        $ch = curl_init("http://apirest/inmueble/$id_inmueble");
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

       Metodo que solicita al Servicio Web las fotos de un determinado 
       inmueble 

    ****************************************************************/
 
    public function sendGetfotosInmueble($id_inmueble)
    {

        $ch = curl_init("http://apirest/inmueble/fotos/$id_inmueble");
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