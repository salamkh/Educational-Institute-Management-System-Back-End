<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Evaluation;
use App\Models\Session;
use App\Models\Student;
use App\Models\StudentCourse;
use App\Models\subject;
use App\Models\teacher;
use App\Models\Test;
use App\Models\Type;
use App\Models\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class EvaluationController extends Controller
{

    public function create(Request $request)
    {
        $evaluation = Evaluation::where('courseId', $request->courseId)->where('studentId', $request->studentId)->get()->first();

        if (!$evaluation) {
            $user = JWTAuth::parseToken()->authenticate();
            $evaluation = new Evaluation();
            $evaluation->studentId = $request->studentId;
            $evaluation->userId = $user->userId;
            $evaluation->courseId = $request->courseId;
            $evaluation->behavior = $request->behavior;
            $evaluation->cause = $request->cause;

            $tests = Test::where('courseId', $request->courseId)
                ->where('studentId', $request->studentId)->get();
            $testValue = 0;

            if ($tests) {
                for ($i = 0; $i < sizeof($tests); $i++) {
                    $testValue = $testValue + $tests[$i]->value;
                }
                $testValue = $testValue / sizeof($tests);
                $evaluation->value = $testValue;
                $evaluation->save();
            } else {
                $evaluation->value = 0;
                $evaluation->save();
            }
        } else {
            $evaluation->update($request->all());
            $evaluation->save();
        }
        return response(
            [
                'evaluation' => $evaluation,
                'message' => 'تمت العملية بنجاح',
            ],
            200
        );
    }

    public function sortAllStudentsEvaluationInCourse($courseId)
    {

        $evaluation = Evaluation::where('courseId', $courseId)
            ->orderBy('value', 'desc')->get();

        $students = null;
        for ($i = 0; $i < sizeof($evaluation); $i++) {
            $students[$i] = Student::where('studentId', $evaluation[$i]->studentId)->get()->first();
            $students[$i]->behavior =  $evaluation[$i]->behavior;
            $students[$i]->cause =  $evaluation[$i]->cause;
            $teacher = User::find($evaluation[$i]->userId);
            $students[$i]->teacher = $teacher->name;
            $students[$i]->value =  $evaluation[$i]->value;
        }

        if (!$students) {
            $array = [];
            return response([
                'message' => 'لا يوجد معلومات مطابقة لهذا العرض',
            ]);
        } else
            return response(
                [

                    'students' => $students,
                    'message' => 'تمت عملية العرض بنجاح',
                ]
            );
    }
}
