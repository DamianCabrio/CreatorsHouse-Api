<?php

//TODO normalizar los datos y crear funciones del modelo

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

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
        return $this->hasOne(User::class, 'id');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function posts()
    {
        return $this->hasMany(Post::class, 'idCreator');
    }

    public function follows()
    {
        return $this->hasMany(Follow::class, 'idCreator');
    }

    public function mercadoPagoAccessTokens(): HasOne
    {
        return $this->hasOne(MercadoPagoAccessToken::class);
    }
}
