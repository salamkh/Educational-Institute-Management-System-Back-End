<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Advertisment extends Model
{
    use HasFactory;
    protected $table='advertisement';
    protected $primaryKey='advertisementId';
    public $timestamps=false;
    protected $fillable = [
        'advertisementId',
        'userId',
        'advertismentContent',
        'type',
        'date',
    ];

}
