<?php

//TODO normalizar los datos y crear funciones del modelo

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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
        return $this->hasOne(User::class, 'idUser');
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

    public function images()
    {
        return $this->hasManyThrough(Image::class, Post::class, 'idCreator', 'idPost');
        //return $this->hasManyThrough('App\Resource', 'App\Class');
    }

    public function videos()
    {
        return $this->hasManyThrough(Video::class, Post::class, 'idCreator', 'idPost');
    }

    public function likes()
    {
        return $this->hasManyThrough(Like::class, Post::class, 'idCreator', 'idPost');
    }
}
