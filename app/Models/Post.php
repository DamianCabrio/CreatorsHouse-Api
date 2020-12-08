<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "post";

    protected $fillable = [
        'content', 'title', 'tipo', 'isPublic', "idCreator"
    ];


    /* public function creator()
    {
        return $this->belongsTo(Creator::class);
    } */

    public function creator()
    {
        return $this->hasOne(Creator::class, 'idCreador');
    }

    public function videos()
    {
        return $this->hasMany(Video::class, 'idPost');
    }

    public function likes()
    {
        return $this->hasMany(Like::class, 'idPost');
    }

    public function images()
    {
        return $this->hasMany(Image::class, 'idPost');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'idPost');
    }
}
