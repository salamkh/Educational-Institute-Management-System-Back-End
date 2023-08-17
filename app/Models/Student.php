<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Student extends Authenticatable implements JWTSubject
{
    use HasFactory;
    protected $table='student';
    protected $primaryKey='studentId';
    public $timestamps=false;
    protected $hidden = [
        'password',
        'remember_token',
    ];
    protected $fillable = [
        'studentId',
        'name',
        'birthdate',
        'phone',
        'studentPhone',
        'parentPhone',
        'address',
        'gender',
        'myStatus',
        'password',
    ];
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
 
    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

}
