<?php

//TODO normalizar los datos y crear funciones del modelo

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

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
    public function setNameCategoryAttribute($name)
    {
        $this->attributes["name"] = strtolower($name);
    }

    public function getDescriptionAttribute($name)
    {
        return ucwords($name);
    }

    public function setDescriptionAttribute($surname)
    {
        $this->attributes["surname"] = strtolower($surname);
    }

    public function getSurnameAttribute($surname)
    {
        return ucwords($surname);
    }
}
