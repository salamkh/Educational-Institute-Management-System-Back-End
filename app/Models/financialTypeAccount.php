<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class financialTypeAccount extends Model
{
    use HasFactory;
    protected $table = 'financialtypeaccount';
    protected $primaryKey = 'FTAId';
    protected $fillable = [
        'FAId',
        'typeId'
    ];
}
