<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class panchment extends Model
{
    use HasFactory;
    protected $table = 'panchment';
    protected $primaryKey = 'panchId';
    protected $fillable = [
        'panchDate',
        'userId',
        'cause',
        'balance'
    ];
}
