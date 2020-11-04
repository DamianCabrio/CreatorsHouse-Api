<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "post";

    public function creator()
    {
        return $this->belongsTo(Creator::class);
    }

    /*  public function creator()
    {
        return $this->hasOne(Creator::class);
    } */

    public function videos()
    {
        return $this->belongsToMany(Video::class);
    }

    public function likes()
    {
        return $this->belongsToMany(Like::class);
    }

    public function images()
    {
        return $this->belongsToMany(Image::class);
    }
}
