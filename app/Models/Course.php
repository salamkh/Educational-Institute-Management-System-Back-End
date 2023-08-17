<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;
    protected $table='course';
    protected $primaryKey='courseId';
    public $timestamps=false;
    protected $fillable = [
        'courseId',
        'subjectId',
        'typeId',
        'classId',
        'headlines',
        'addElements',
        'cost',
        'maxNStudent',
        'sessionNumber',
        'courseStatus',
        'courseDays',
        'startDate',
        'endDate',
        'startTime',
        'duration',
        'room',

    ];

}
