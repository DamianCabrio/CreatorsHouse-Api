<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Laravel\Lumen\Auth\Authorizable;

class User extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable, HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'email', 'password', 'isCreator', 'avatar', 'birthDate', 'name', 'surname', 'dni', 'isAdmin', 'isVerified',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'email', 'verificationToken', 'remember_token'
    ];

    protected $dates = ["deleted_at"];

    protected $table = "user";

    public static function generateVerificationCode()
    {
        return Str::random(40);
    }

    public function setNameAttribute($name)
    {
        $this->attributes["name"] = strtolower($name);
    }

    public function getNameAttribute($name)
    {
        return ucwords($name);
    }

    public function setSurnameAttribute($surname)
    {
        $this->attributes["surname"] = strtolower($surname);
    }

    public function getSurnameAttribute($surname)
    {
        return ucwords($surname);
    }

    public function setEmailAttribute($email)
    {
        $this->attributes["email"] = strtolower($email);
    }

    public function isVerified()
    {
        return $this->verified == true;
    }

    public function isAdmin()
    {
        return $this->admin == true;
    }
}
