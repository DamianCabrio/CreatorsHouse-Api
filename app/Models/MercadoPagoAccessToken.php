<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MercadoPagoAccessToken extends Model
{

    protected $table = "mercado_pago_tokens";

    protected $fillable = [
        "idCreador",
        "access_token",
        "expires_in"
    ];

    public static function alreadyExists($idCreador)
    {
        return MercadoPagoAccessToken::where('idCreador', '=', $idCreador)->first();
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(Creator::class);
    }

}
