<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestCourse extends Model
{
    use HasFactory;
    protected $table='test_course';
    protected $primaryKey='testCourseId';
    public $timestamps=false;
    protected $fillable = [
        'testCourseId',
        'testId',
        'courseId',
    ];

}
