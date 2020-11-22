<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Image extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "image";

    protected $fillable = [
        'image', 'idPost'
    ];

    public function post()
    {
        return $this->hasOne(Post::class, 'idPost');
    }
}
