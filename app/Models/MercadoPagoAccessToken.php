<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MercadoPagoAccessToken extends Model
{

    protected $table = "mercado_pago_tokens";

    protected $fillable = [
        "id_creator",
        "access_token",
        "expires_in"
    ];

    public static function alreadyExists($idCreador)
    {
        return MercadoPagoAccessToken::where('id_creator', '=', $idCreador)->first();
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(Creator::class);
    }

}
