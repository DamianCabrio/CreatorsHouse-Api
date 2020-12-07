<?php
$client = new SoapClient(null, array(
    'location' => "http://localhost:8080/server.php",
    'uri' => "http://localhost:8080/server.php",
    'trace' => 1
));
try {
    echo $return = $client->__soapCall("saludar", [""]);
} catch (SOAPFault $e) {
    echo $e->getMessage() . PHP_EOL;
}
