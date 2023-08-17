<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MyClass extends Model
{
    use HasFactory;
    protected $table='class';
    protected $primaryKey='classId';
    public $timestamps=false;
    protected $fillable = [
        'classId',
        'name',

    ];
}
