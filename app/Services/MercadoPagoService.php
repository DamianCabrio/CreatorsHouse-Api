<?php

namespace App\Services;

use App\Traits\ConsumesExternalServices;

class MercadoPagoService
{
    use ConsumesExternalServices;

    /**
     * The base uri to consume the authors service
     * @var string
     */
    public $baseUri;

    public function __construct()
    {
        $this->baseUri = config("services.mercado_pago.base_api_url");
    }

    public function obtainMercadoPagoToken($data)
    {
        return $this->performsRequest("POST", "/oauth/token", $data);
    }
}
