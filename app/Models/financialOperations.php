<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class financialOperations extends Model
{
    use HasFactory;
    protected $table = 'financialOperation';
    protected $primaryKey = 'FOId';
    protected $fillable = [
        'operationDate',
        'description',
        'balance',
        'creditorId',
        'debtorId',
        'creditorName',
        'debtorName'
    ];
}
