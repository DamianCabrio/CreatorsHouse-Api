<?php

namespace App\Http\Controllers;

use SoapClient;
use App\Traits\ApiResponser;

class SoapController extends Controller
{
    public function getCity($ip)
    {

        $ip = $ip[0] . $ip[1] . $ip[2] . '.' . $ip[3] . $ip[4] . $ip[5] . '.' . $ip[6] . $ip[7] . $ip[8] . '.' . $ip[9] . $ip[10] . $ip[11];
        //echo ($ip);
        //Crea un objeto de clase Soap
        //url es el servicio SOAP que esta activo (debemos verificar que funciona ingresandola en el navegador)
        $client = new SoapClient('http://ws.cdyne.com/ip2geo/ip2geo.asmx?wsdl');
        // Crea el array con los parametros de entrada que requiere el servicio SOAP
        $param = array(
            'ipAddress' => $ip,
            'licenseKey' => '0',
        );
        //Llama al servicio con los parametros de entrada
        $result = $client->ResolveIP($param);
        //Muestra todo lo que devuelve el servicio
        //print_r($result);
        //Muestra solo la ciudad de donde proviende la IP
        $city = $result->ResolveIPResult->City;

        return json_encode($city);
    }
}
