<?php

namespace App\Http\Controllers;

use App\Models\Session;
use App\Models\sessionCourse;
use App\Models\sessionStudentMonitoring;
use App\Models\Student;
use App\Models\StudentCourse;
use App\Models\Test;
use Illuminate\Http\Request;

class SessionStudentMonitoringController extends Controller
{
    public function create(Request $request)
    {
        $monitoring = sessionStudentMonitoring::where('sessionId',$request->sessionId)->where('studentId',$request->studentId)->get()->first();
            $monitoring->studentStatus = $request->studentStatus;
            $monitoring->save();
        return response
        (
            [
                'monitoring' => $monitoring,
                'message' => 'تمت تعديل التفقد بنجاح',
            ]
            , 200
        );
    }

 
}
