<?php

namespace App\Traits;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

trait ConsumesExternalServices
{

    /**
     * @param $method
     * @param $requestUrl
     * @param array $formParams
     * @param array $headers
     * @return string
     * @throws GuzzleException
     */
    public function performsRequest($method, $requestUrl, $formParams = [], $headers = [])
    {
        $client = new Client([
            "base_uri" => $this->baseUri,
        ]);

        $response = $client->request($method, $requestUrl, ["form_params" => $formParams, "headers" => $headers]);

        return $response->getBody()->getContents();
    }
}
