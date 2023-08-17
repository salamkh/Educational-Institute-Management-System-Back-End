<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class financialPeriod extends Model
{
    use HasFactory;
    protected $table = 'financialPeriod';
    protected $primaryKey = 'FPId';
    protected $fillable = [
        'startDate',
        'endDate',
        'description',
        'status',
        'resault'
    ];
}
