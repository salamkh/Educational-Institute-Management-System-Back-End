<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class testy extends Model
{
    use HasFactory;
    protected $table = 'testy';
    protected  $fillable = [
        "name" ,
        "userId",
    ];
}
