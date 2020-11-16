<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentPlatform extends Model
{

    public $table = "payment_platform";

    protected $fillable = [
        "name","image"
    ];
}
