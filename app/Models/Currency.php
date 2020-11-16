<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    protected $primaryKey = "iso";

    public $table = "currency";

    public  $incrementing = false;

    protected $fillable = [
        "iso",
    ];
}
