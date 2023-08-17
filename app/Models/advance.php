<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class advance extends Model
{
    use HasFactory;
    protected $table = 'advance';
    protected $primaryKey = 'advId';
    protected $fillable = [
        'advancedDate',
        'userId',
        'cause',
        'balance',
        'status'
    ];
}
