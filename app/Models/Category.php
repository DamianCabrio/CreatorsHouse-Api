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

    protected $table = "category";

    public function creators()
    {
        return $this->belongsToMany(Creator::class);
    }

    public function setNameCategoryAttribute($nameCategory)
    {
        $this->attributes["nameCategory"] = strtolower($nameCategory);
    }

    public function getDescriptionAttribute($description)
    {
        return ucwords($description);
    }

    public function setDescriptionAttribute($description)
    {
        $this->attributes["description"] = strtolower($description);
    }

    public function getNameCategoryAttribute($nameCategory)
    {
        return ucwords($nameCategory);
    }
}
