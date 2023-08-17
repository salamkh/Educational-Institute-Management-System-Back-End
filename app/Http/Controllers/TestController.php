<?php

namespace App\Http\Controllers;

use App\Models\Session;
use App\Models\Student;
use App\Models\StudentCourse;
use App\Models\Test;
use App\Models\User;
use App\Models\teacher;
use App\Models\sessionStudentMonitoring;
use App\Models\Evaluation;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class TestController extends Controller
{
    public function create(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $teacher = teacher::where('userId', $user->userId)->get()->first();
        $test = Test::where('sessionId', $request->sessionId)->where('studentId', $request->studentId)->get()->first();
        if (!$test) {
            $test = new Test();
            $test->sessionId = $request->sessionId;
            $test->teacherId = $teacher->tId;
            $test->studentId = $request->studentId;
            $test->value = $request->value;
            $test->cause = $request->cause;
            $test->courseId = $request->courseId;
            $test->save();
        } else {
            $test->sessionId = $request->sessionId;
            $test->teacherId = 8;
            $test->studentId = $request->studentId;
            $test->value = $request->value;
            $test->cause = $request->cause;
            $test->courseId = $request->courseId;
            $test->save();
        }

        $evaluation = Evaluation::where('courseId', $request->courseId)->where('studentId', $request->studentId)->get();
        $tests = Test::where('courseId',  $request->courseId)->where('studentId', $request->studentId)->get();
        $testValue = 0;
        for ($i = 0; $i < sizeof($tests); $i++) {
            $testValue = $testValue + $tests[$i]->value;
        }
        $testValue = $testValue / sizeof($tests);
        $evaluation->value = $testValue;
        $evaluation->save();
        return response(
            [
                'message' => 'تمت إضافة التسميع بنجاح',
            ],
            200
        );
    }
    public function showAllTests($sessionId)
    {
        $test = Test::where('sessionId', $sessionId)->get();
        $session = Session::find($sessionId);
        $studentCourse = StudentCourse::where('courseId', $session->courseId)->get();
        $studentsTest = null;
        for ($i = 0; $i < sizeof($studentCourse); $i++) {
            $studentsTest[$i] = Student::where('studentId', $studentCourse[$i]->studentId)->get()->first();
            $monitoring = sessionStudentMonitoring::where('sessionId', $sessionId)->where('studentId', $studentsTest[$i]->studentId)->get()->first();
            if ($monitoring) {
                $studentsTest[$i]->studentStatus = $monitoring->studentStatus;
            }
        }
        if ($studentsTest) {
            $sortedStudent = collect($studentsTest);
            $studentsTest = $sortedStudent->sortBy('name', SORT_NATURAL);
            $studentsTest = $studentsTest->values()->all();
        }
        if (!$test) {
            return response([
                'Tests' => $studentsTest,
                'message' => 'لا يوجد معلومات مطابقة لهذا العرض',
            ]);
        } else {
            for ($i = 0; $i < sizeof($studentsTest); $i++) {
                for ($j = 0; $j < sizeof($test); $j++) {
                    if ($studentsTest[$i]->studentId == $test[$j]->studentId) {
                        $studentsTest[$i]->testId = $test[$j]->testId;
                        $studentsTest[$i]->teacherId = $test[$j]->teacherId;
                        $teacher = teacher::find($test[$j]->teacherId);
                        $teacher = User::find($teacher->userId);
                        $studentsTest[$i]->teacher = $teacher->name;
                        $studentsTest[$i]->value = $test[$j]->value;
                        $studentsTest[$i]->cause = $test[$j]->cause;
                    }
                }
            }
            return response(
                [
                    'Tests' => $studentsTest,
                    'message' => 'تمت عملية التعديل بنجاح',
                ]
            );
        }
    }
    public function searchAboutStudent($sessionId, $name)
    {
        $test = Test::where('sessionId', $sessionId)->get();
        $session = Session::find($sessionId);
        $studentCourse = StudentCourse::where('courseId', $session->courseId)->get();
        $studentsTest = null;
        $student = null;
        $j = 0;
        for ($i = 0; $i < sizeof($studentCourse); $i++) {
            $student[$i] = Student::where('studentId', $studentCourse[$i]->studentId)->where('name', 'like', '%' . $name . '%')->get()->first();
            if ($student[$i]) {
                $studentsTest[$j] = $student[$i];
                $monitoring = sessionStudentMonitoring::where('sessionId', $sessionId)->where('studentId', $student[$i]->studentId)->get()->first();
                if ($monitoring) {
                    $studentsTest[$j]->studentStatus = $monitoring->studentStatus;
                }
                $j++;
            }
        }
        if ($studentsTest) {
            if (sizeof($studentsTest) > $j) {
                array_pop($studentsTest);
            }
            $sortedStudent = collect($studentsTest);
            $studentsTest = $sortedStudent->sortBy('name', SORT_NATURAL);
            $studentsTest = $studentsTest->values()->all();
        }
        if (sizeof($test) == 0 && $studentsTest) {
            return response([
                'Tests' => $studentsTest,
                'message' => 'لا يوجد معلومات مطابقة لهذا العرض',
            ]);
        } else if ($studentsTest) {
            for ($i = 0; $i < sizeof($studentsTest); $i++) {
                for ($j = 0; $j < sizeof($test); $j++) {
                    if ($studentsTest[$i]->studentId == $test[$j]->studentId) {
                        $studentsTest[$i]->testId = $test[$j]->testId;
                        $studentsTest[$i]->teacherId = $test[$j]->teacherId;
                        $teacher = teacher::find($test[$j]->teacherId);
                        $teacher = User::find($teacher->userId);
                        $studentsTest[$i]->teacher = $teacher->name;
                        $studentsTest[$i]->value = $test[$j]->value;
                        $studentsTest[$i]->cause = $test[$j]->cause;
                    }
                }
            }
            return response(
                [
                    'Tests' => $studentsTest,
                    'message' => 'تمت عملية العرض بنجاح',
                ]
            );
        }
    }
    public function showStudentsInCourseDependOnGenderWithTest($sessionId, $gender)
    {
        $test = Test::where('sessionId', $sessionId)->get();
        $session = Session::find($sessionId);
        $studentCourse = StudentCourse::where('courseId', $session->courseId)->get();
        $studentsTest = null;
        $student = null;
        $j = 0;
        for ($i = 0; $i < sizeof($studentCourse); $i++) {
            $student[$i] = Student::where('studentId', $studentCourse[$i]->studentId)->where('gender', $gender)->get()->first();

            if ($student[$i]) {
                $studentsTest[$j] = $student[$i];
                $monitoring = sessionStudentMonitoring::where('sessionId', $sessionId)->where('studentId', $student[$i]->studentId)->get()->first();
                if ($monitoring) {
                    $studentsTest[$j]->studentStatus = $monitoring->studentStatus;
                }
                $j++;
            }
        }
        if ($studentsTest) {
            if (sizeof($studentsTest) > $j) {
                array_pop($studentsTest);
            }
            $sortedStudent = collect($studentsTest);
            $studentsTest = $sortedStudent->sortBy('name', SORT_NATURAL);
            $studentsTest = $studentsTest->values()->all();
        }
        if (sizeof($test) == 0 && $studentsTest) {
            return response([
                'Tests' => $studentsTest,
                'message' => 'لا يوجد معلومات مطابقة لهذا العرض',
            ]);
        } else if ($studentsTest) {
            for ($i = 0; $i < sizeof($studentsTest); $i++) {
                for ($j = 0; $j < sizeof($test); $j++) {
                    if ($studentsTest[$i]->studentId == $test[$j]->studentId) {
                        $studentsTest[$i]->testId = $test[$j]->testId;
                        $studentsTest[$i]->teacherId = $test[$j]->teacherId;
                        $teacher = teacher::find($test[$j]->teacherId);
                        $teacher = User::find($teacher->userId);
                        $studentsTest[$i]->teacher = $teacher->name;
                        $studentsTest[$i]->value = $test[$j]->value;
                        $studentsTest[$i]->cause = $test[$j]->cause;
                    }
                }
            }
            return response(
                [
                    'Tests' => $studentsTest,
                    'message' => 'تمت عملية العرض بنجاح',
                ]
            );
        }
    }

    public function showStudentTestsInCourse($courseId, $studentId)
    {
        $sessions = Session::where('courseId', $courseId)->get();
        if (!$sessions) {
            $array = [];
            return response($array);
        }
        for ($i = 0; $i < sizeof($sessions); $i++) {
            $monitoring = sessionStudentMonitoring::where('sessionId', $sessions[$i]->sessionId)->where('studentId', $studentId)->get()->first();
            if ($monitoring) {
                $sessions[$i]->studentStatus = $monitoring->studentStatus;
            }
            $test = Test::where('sessionId', $sessions[$i]->sessionId)->where('studentId', $studentId)->get()->first();
            if ($test) {
                $sessions[$i]->testId = $test->testId;
                $sessions[$i]->value = $test->value;
                $sessions[$i]->cause = $test->cause;
                $teacher = teacher::find($test->teacherId);
                $teacher = User::find($teacher->userId);
                $sessions[$i]->teacher = $teacher->name;
            }
        }
        return response([
            'Tests' => $sessions,
        ]);
    }
}
