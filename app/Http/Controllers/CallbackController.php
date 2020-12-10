<?php

namespace App\Http\Controllers;

use App\Models\Creator;
use App\Models\Follow;
use App\Models\MercadoPagoAccessToken;
use App\Models\User;
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

    public function store(Request $request, $code)
    {
        if (isset($code)) {
            $myBody["client_secret"] = config("services.mercado_pago.access_token");
            $myBody["grant_type"] = "authorization_code";
            $myBody["code"] = $code;
            $myBody["redirect_uri"] = config("services.mercado_pago.redirect_uri");

            $loggedInCreator = Creator::where('idUser', '=', $request->user()->id)->firstOrFail();

            $checkIfExiste = MercadoPagoAccessToken::alreadyExists($loggedInCreator->id);

            if ($checkIfExiste != null) {
                $checkIfExiste->delete();
            }

            $response = json_decode($this->mercadoPagoService->obtainMercadoPagoToken($myBody));

            $fields["id_creator"] = $loggedInCreator->id;
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

    public function createPayment(Request $request, $idCreador)
    {
        $creador = Creator::findOrFail($idCreador);
        $userCreator = User::findOrFail($creador->idUser);
        $mercadoPagoToken = MercadoPagoAccessToken::where("id_creator", "=", $creador->id)->firstOrFail();
        $access_token = $mercadoPagoToken->access_token;
        // Agrega credenciales
        MercadoPago\SDK::setAccessToken($access_token);
        // Crea un objeto de preferencia
        $preference = new MercadoPago\Preference();
        // Crea un ítem en la preferencia
        $item = new MercadoPago\Item();
        $item->title = "Suscripcion - " . $userCreator->username;
        $item->quantity = 1;
        $item->currency_id = "ARS";
        $item->unit_price = $creador->costVip;

        $payer = new MercadoPago\Payer();

        $preference->items = array($item);
        $preference->payer = $payer;
        $preference->notification_url = "http://apich.stange.ar/public_html/notification_ipn";
        $preference->back_urls = array(
            "success" => "http://localhost:8080/#/creator/" . $idCreador,
        );
        $preference->external_reference = "vip";
        $preference->auto_return = "approved";
        $preference->save();
        return $this->successResponse($preference->init_point);
    }

    public function donate(Request $request, $idCreador,$monto){
        $creador = Creator::findOrFail($idCreador);
        $userCreator = User::findOrFail($creador->idUser);
        $mercadoPagoToken = MercadoPagoAccessToken::where("id_creator", "=", $creador->id)->firstOrFail();
        $access_token = $mercadoPagoToken->access_token;
        // Agrega credenciales
        MercadoPago\SDK::setAccessToken($access_token);
        // Crea un objeto de preferencia
        $preference = new MercadoPago\Preference();
        // Crea un ítem en la preferencia
        $item = new MercadoPago\Item();
        $item->title = "Donacion - " . $userCreator->username;
        $item->quantity = 1;
        $item->currency_id = "ARS";
        $item->unit_price = $monto;

        $payer = new MercadoPago\Payer();

        $preference->items = array($item);
        $preference->payer = $payer;
        $preference->notification_url = "http://apich.stange.ar/public_html/notification_ipn";
        $preference->back_urls = array(
            "success" => "http://localhost:8080/#/creator/" . $idCreador,
        );
        $preference->external_reference = "donar";
        $preference->auto_return = "approved";
        $preference->save();
        return $this->successResponse($preference->init_point);
    }

    public function makeVip(Request $request, $idCreador){
        $alreadyFollow = Follow::where([["idUser","=",$request->user()->id], ["idCreator", "=", $idCreador]])->first();

        if(is_null($alreadyFollow)){
            $fields["idCreator"] = $idCreador;
            $fields["idUser"] = $request->user()->id;
            $fields["isVip"] = true;
            $fields["dateVip"] = date("Y-m-d");
            $follow = Follow::create($fields);
            $follow->save();
            return $this->showMessage("Usted se convirtio en VIP");
        }else{
            if($alreadyFollow->isVip == 1){
                return $this->errorResponse("Usted ya es  VIP",400);
            }else{
                $alreadyFollow->isVip = 1;
                $alreadyFollow->dateVip = date("Y-m-d");
                $alreadyFollow->save();
                return $this->showMessage("Usted se convirtio en VIP");
            }
        }
    }

    public function removeVip(Request $request){
        $follows = Follow::where([["idUser", "=" ,$request->user()->id], ["isVip", "=", 1]])->get();

        if(!is_null($follows)){
            $currentMonth = date("m");
            $currentYear = date("Y");
            $seHicieronCambios = false;
            foreach ($follows as $follow){
                $timeVipDB= strtotime($follow->dateVip);
                $monthDB = date("m",$timeVipDB);
                $yearDB = date("Y",$timeVipDB);

                if ($monthDB < $currentMonth || $yearDB < $currentYear){
                    $seHicieronCambios = true;
                    $follow->isVip = 0;
                    $follow->dateVip = null;
                    $follow->save();
                }
            }
        }
        if($seHicieronCambios){
            return $this->showMessage("Se hicieron cambios");
        }else{
            return $this->errorResponse("No se hicieon cambios",400);
        }
    }
}
