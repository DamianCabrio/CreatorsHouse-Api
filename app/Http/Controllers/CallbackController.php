<?php

namespace App\Http\Controllers;

use App\Models\Creator;
use App\Models\MercadoPagoAccessToken;
use App\Services\MercadoPagoService;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use MercadoPago;

class CallbackController extends Controller
{
    use ApiResponser;

    /**
     * @var MercadoPagoService
     */
    private $mercadoPagoService;

    public function __construct(MercadoPagoService $mercadoPagoService)
    {
        $this->mercadoPagoService = $mercadoPagoService;
    }

    public function store(Request $request)
    {
        if ($request->has("code")) {

            $myBody["client_secret"] = config("services.mercado_pago.access_token");
            $myBody["grant_type"] = "authorization_code";
            $myBody["code"] = $request->code;
            $myBody["redirect_uri"] = config("services.mercado_pago.redirect_uri");

            $loggedInCreator = Creator::where('idUser', '=', $request->user()->id)->firstOrFail();

            $checkIfExiste = MercadoPagoAccessToken::alreadyExists($loggedInCreator->id);

            if ($checkIfExiste != null) {
                $checkIfExiste->delete();
            }

            $response = json_decode($this->mercadoPagoService->obtainMercadoPagoToken($myBody));

            $fields["idCreador"] = $loggedInCreator->id;
            $fields["expires_in"] = $response->expires_in;
            $fields["access_token"] = $response->access_token;

            $token = MercadoPagoAccessToken::create($fields);
            $loggedInCreator["hasMercadoPago"] = true;
            $loggedInCreator->save();
            if ($token != null) {
                return $this->showMessage("El token fue creado con exito");
            } else {
                return $this->errorResponse("Ocurrio un error", 404);
            }
        } else {
            return $this->errorResponse("Ha ocurrido un error, por favor intenta mas tarde", 422);
        }
    }

    public function createPayment(Request $request){
        MercadoPago\SDK::setAccessToken();
    }
}
