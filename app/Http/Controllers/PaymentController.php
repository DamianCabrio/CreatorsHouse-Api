<?php

namespace App\Http\Controllers;

use App\Resolvers\PaymentPlatformResolver;
use App\Traits\ApiResponser;
use App\Traits\ConsumesExternalServices;
use Illuminate\Http\Request;

class PaymentController extends Controller
{

    use ApiResponser;
    use ConsumesExternalServices;

    protected $paymentPlatformResolver;

    public function __construct(PaymentPlatformResolver $paymentPlatformResolver){
        $this->paymentPlatformResolver = $paymentPlatformResolver;
    }

    public function pay(Request $request)
    {
        $rules = [
            "value" => ["required", "numeric", "min:1"],
            "currency" => ["required", "exists:currency,iso"],
            "payment_platform" => ["required", "exists:payment_platform,id"],
        ];

        $request->validate($rules);

        $paymentPlatform = $this->paymentPlatformResolver->resolveService($request->payment_platform);

        $paymentPlatform = resolve($paymentPlatform);

        return $paymentPlatform->handlePayment($request);
    }

    public function approval()
    {
        $paymenyPlatform = resolve(MercadoPagoService::class);

        return $paymenyPlatform->handleApproval();
    }

    public function cancelled()
    {
        return $this->errorResponse("El pago fue cancelado",404);
    }
}
