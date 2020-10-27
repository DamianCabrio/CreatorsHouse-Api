<?php

//TODO normalizar los datos y crear funciones del modelo

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Creator extends Model
{
    use HasFactory, SoftDeletes;

    protected $dates = ["deleted_at"];
    protected $fillable = [
        "banner",
        "description",
        "instagram",
        "youtube",
        "costVip",
        "emailMercadoPago",
        "idUser",
    ];

    protected $hidden = [
        "pivot"
    ];

    protected $table = "creator";

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }
}
