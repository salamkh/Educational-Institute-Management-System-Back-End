<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class financialStudentOperations extends Model
{
    use HasFactory;
    protected $table = 'financialstudentoperation';
    protected $primaryKey = 'FSOId';
    protected $fillable = [
        'studentId',
        'typeId',
        'operationType'
    ];
}
