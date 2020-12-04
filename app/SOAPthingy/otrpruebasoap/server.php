<?php

class MiClase
{
    public function saludar()
    {
        $frases = ['El amor hace pasar el tiempo, el tiempo hace pasar el amor.', 'Amar sin esperanza debe ser muy triste; pero más triste debe ser vivir sin la esperanza de amar.', 'Lo más triste en este mundo es querer a alguien que antes te quería a ti.'];
        $num = rand(0, 2);
        return $frases[$num] . func_get_args()[0] . PHP_EOL;
    }
}

try {
    $server = new SoapServer(
        null,
        [
            'uri' => 'http://localhost:8080/soap_server.php',
        ]
    );

    $server->setClass('MiClase');
    $server->handle();
} catch (SOAPFault $f) {
    print $f->faultstring;
}