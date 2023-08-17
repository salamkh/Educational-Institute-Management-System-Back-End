<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class timeMonitoring extends Model
{
    use HasFactory;
    protected $table = 'timemonitoring';
    protected $primaryKey = 'monId';
    protected $fillable = [
        'userId',
        'startTime',
        'exitTime',
        'date'
    ];
}
