<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class financialStudentAccount extends Model
{
    use HasFactory;
    protected $table = 'financialstudentaccount';
    protected $primaryKey = 'FSAId';
    protected $fillable = [
        'FAId',
        'studentId'
    ];
}
