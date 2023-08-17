<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class reward extends Model
{
    use HasFactory;
    protected $table = 'reward';
    protected $primaryKey = 'rrId';
    protected $fillable = [
        'rewarddDate',
        'userId',
        'cause',
        'balance'
    ];
}
