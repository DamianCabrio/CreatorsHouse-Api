<?php

namespace App\Resolvers;

use App\Models\PaymentPlatform;
use App\Traits\ApiResponser;

class PaymentPlatformResolver{
    use ApiResponser;

    protected  $paymentPlatforms;

    public function __construct(){
        $this->paymentPlatforms = PaymentPlatform::all();
    }

    public function resolveService($paymentPlatformId){
        $name = strtolower($this->paymentPlatforms->firstWhere("id", $paymentPlatformId));
        $service = config("services.{$name}.class");

        if ($service) {
            return resolve($service);
        }

        return $this->errorResponse("La pasarela de pago no existe",404);
    }
}
