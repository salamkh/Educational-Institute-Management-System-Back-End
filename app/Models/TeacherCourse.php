<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeacherCourse extends Model
{
    use HasFactory;
    protected $table='teacher_course';
    protected $primaryKey='teacherCourseId';
    public $timestamps=false;
    protected $fillable = [
        'teacherCourseId',
        'teacherId',
        'courseId',
    ];

}

