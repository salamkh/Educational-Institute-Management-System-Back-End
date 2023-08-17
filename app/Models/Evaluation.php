<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{
    use HasFactory;
    protected $table='evaluation';
    protected $primaryKey='evaluationId';
    public $timestamps=false;
    protected $fillable = [
        'evaluationId',
        'studentId',
        'courseId',
        'teacherId',
        'cause',
        'behavior',
        'value',
    ];

}
