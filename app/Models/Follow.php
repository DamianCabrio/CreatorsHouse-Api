<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Follow extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "follow";

    public function user(){
        return $this->hasOne(User::class);
    }

    public function creator(){
        return $this->hasOne(User::class);
    }
}
