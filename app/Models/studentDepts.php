<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class studentDepts extends Model
{
    use HasFactory;
    protected $table = 'studentdepts';
    protected $primaryKey = 'StDId';
    protected $fillable = [
        'deserevedAmount',
        'paidAmount',
        'studentId',
        'typeId',
        'studentName',
        'typeName'
    ];
}
