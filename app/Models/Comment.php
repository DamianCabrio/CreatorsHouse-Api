<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "comment";

    protected $fillable = [
        "text",
        "idPost",
        "idUser",
    ];

    public function post()
    {
        return $this->hasOne(Post::class, 'idPost');
    }

    public function user()
    {
        return $this->hasOne(User::class, "idUser");
    }
}
