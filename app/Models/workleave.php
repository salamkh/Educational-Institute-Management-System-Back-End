<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class workleave extends Model
{
    use HasFactory;
    protected $table = 'workleave';
    protected $primaryKey = 'wId';
    protected $fillable = [
        'startDate',
        'userId',
        'startTime',
        'duration',
        'type'
    ];
}
