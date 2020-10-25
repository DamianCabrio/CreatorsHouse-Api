<?php

//TODO normalizar los datos y crear funciones del modelo

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected $dates = ["deleted_at"];
    protected $fillable = [
        "nameCategory",
        "description",
    ];

    protected $hidden = [
        "pivot"
    ];

    public function creators()
    {
        return $this->belongsToMany(Creator::class);
    }
}
