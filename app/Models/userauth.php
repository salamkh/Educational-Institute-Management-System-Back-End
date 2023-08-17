<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class userauth extends Model
{
    use HasFactory;
    protected $table = 'userauth';
    protected $primaryKey = 'uaId';
    protected $fillable = [
        'name',
        'email',
        'password',
        'userName',
        'salary',
        'phoneNumber',
        'imageIdentity',
        'accountStatus',
        'workTime'
    ];
}
