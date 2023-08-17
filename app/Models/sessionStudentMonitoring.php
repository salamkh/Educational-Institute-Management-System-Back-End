<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class sessionStudentMonitoring extends Model
{
    use HasFactory;

    protected $table='session_student_monitoring';
    protected $primaryKey='sessionStudentMonitoringId';
    public $timestamps=false;

    protected $fillable = [
        'sessionStudentMonitoringId',
        'sessionId',
        'studentId',
        'studentStatus',
    ];

}
