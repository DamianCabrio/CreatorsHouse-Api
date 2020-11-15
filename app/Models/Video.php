<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Video extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "video";

    protected $fillable = [
        'video', 'idPost'
    ];

    public function post()
    {
        return $this->hasOne(Post::class);
    }
}
