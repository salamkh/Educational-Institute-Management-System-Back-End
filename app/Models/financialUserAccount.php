<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class financialUserAccount extends Model
{
    use HasFactory;
    protected $table = 'financialuseraccount';
    protected $primaryKey = 'FUAId';
    protected $fillable = [
        'FAId',
        'userId'
    ];
}
