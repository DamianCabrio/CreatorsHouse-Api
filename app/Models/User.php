<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Lumen\Auth\Authorizable;
use Laravel\Passport\HasApiTokens;

class User extends Model implements AuthenticatableContract, AuthorizableContract, CanResetPasswordContract
{
    use Authenticatable, Authorizable, HasFactory, SoftDeletes, HasApiTokens, Notifiable, CanResetPassword;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'email', 'password', 'isCreator', 'avatar', 'birthDate', 'name', 'surname', 'dni', 'isAdmin', 'isVerified', "verification_token"
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'verification_token', 'remember_token'
    ];

    protected $dates = ["deleted_at"];

    protected $table = "user";

    public static function generateVerificationCode()
    {
        return Str::random(40);
    }

    //Esta funcion te devuelve el creator para ese usuario relacion 1 a 1
    public function creator()
    {
        return $this->hasOne(Creator::class, 'idUser');
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

    public function followers()
    {
        return $this->hasMany(Follow::class, 'idUser');
    }
}
