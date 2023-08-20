<?php

namespace App\Http\Controllers;

use App\Models\corusestuno;
use App\Models\Course;
use App\Models\pricingPlanCosts;
use App\Models\Session;
use App\Models\sessionCost;
use App\Models\sessionCourse;
use App\Models\StudentCourse;
use App\Models\sessionStudentMonitoring;
use App\Models\Student;
use Illuminate\Http\Request;
use PlanCosts;

class SessionController extends Controller
{
    public function create(Request $request)
    {

        $studentNumber = sizeof(StudentCourse::where('courseId', $request->courseId)->get());
        $sessionId = Session::insertGetId([
            "courseId" => $request->courseId,
            "startTime" => $request->startTime,
            "date" => $request->date
        ]);
        $sessionCount = Session::where('courseId', $request->courseId)->get();
        $session = Session::find($sessionId);
        $session->sessionNumber = $sessionCount->count();
        $session->save();
        $studentCourse = StudentCourse::where('courseId', $request->courseId)->get();
        for ($i = 0; $i < sizeof($studentCourse); $i++) {
            $monitoring = new sessionStudentMonitoring();
            $monitoring->sessionId = $session->sessionId;
            $monitoring->studentId  = $studentCourse[$i]->studentId;
            $monitoring->studentStatus = 'حضور';
            $monitoring->save();
        }
        if ($request->cost) {
            $sessionCost = new sessionCost();
            $courseStuNo = corusestuno::where('courseId', $request->courseId)->orderBy("created_at")->get();
            for ($i = sizeof($courseStuNo) - 1; $i >= 0; $i--) {
                if (date("Y-m-d", strtotime($courseStuNo[$i]->created_at)) <= date("Y-m-d", strtotime($request->date))) {
                    $sessionCost->sessionId = $sessionId;
                    $sessionCost->cost = $request->cost;
                    $sessionCost->studentNumber = $courseStuNo[$i]->number;
                    $sessionCost->save();
                    break;
                }
            }
        } else {
            $planId = Course::find($request->courseId)->planId;
            if ($planId) {
                $cost = 0;
                $courseStuNo = corusestuno::where('courseId', $request->courseId)->orderBy("created_at")->get();
                $number = 0;
                for ($i = sizeof($courseStuNo) - 1; $i >= 0; $i--) {
                    if (strtotime(date("Y-m-d", strtotime($courseStuNo[$i]->created_at))) <= strtotime(date("Y-m-d", strtotime($request->date)))) {
                        $number = $courseStuNo[$i]->number;
                        break;
                    }
                }
                    $pricingPlan = pricingPlanCosts::where('planId',$planId)->orderBy("min")->get();
                    for ($i=0; $i < sizeof($pricingPlan); $i++) {
                        if ($number >= $pricingPlan[$i]->min && $number <= $pricingPlan[$i]->max) {
                            $cost = $pricingPlan[$i]->cost;
                            break;
                        } else if ($i != sizeof($pricingPlan) -1) {
                            if($number >= $pricingPlan[$i]->max && $number <= $pricingPlan[$i + 1]->min){
                                if ($number < ($pricingPlan[$i + 1]->min - $pricingPlan[$i]->max) / 2) {
                                    $cost = $pricingPlan[$i]->cost;
                                    break;
                            }
                            else{
                                $cost = $pricingPlan[$i+1]->cost;
                                    break;
                            }
                            } 
                        }
                    }
                    if ($i == sizeof($pricingPlan) && $number>$pricingPlan[$i-1]->max) {
                        
                        $cost = $pricingPlan[$i - 1]->cost;
                    }
                    $sessionCost = new sessionCost();
                    $sessionCost->sessionId = $sessionId;
                    $sessionCost->cost = $request->cost;
                    $sessionCost->studentNumber = $courseStuNo[$i]->number;
                    $sessionCost->save();
                }
             else {
                return response(["message" => "فشل إضافة الجلسة يجب إدخال قيمة مستحقات الاستاذ بسبب عدم وجود خطة لحساب الكلفة"]);
            }
        }
        return response(
            [
                'Session' => $session,
                'message' => 'تمت إضافة الجلسة بنجاح',
            ],
            200
        );
    }
    public function show($id)
    {
        $session = Session::find($id);
        if (!$session) {
            $array = [];
            return response([
                'Session' => $array,
                'message' => 'لا يوجد معلومات مطابقة لهذا العرض',
            ]);
        } else
            return response(
                [
                    'Session' => $session,
                    'message' => 'تمت عملية العرض بنجاح',
                ]
            );
    }

    public function showAllSession($id)
    {
        $session = Session::where('courseId', $id)->get();
        if (!$session) {
            $array = [];
            return response([
                'Session' => $array,
                'message' => 'لا يوجد معلومات مطابقة لهذا العرض',
            ]);
        } else
            return response(
                [
                    'Sessions' => $session,
                    'message' => 'تمت عملية العرض بنجاح',
                ]
            );
    }
    public function edit($id, Request $request)
    {

        $session = Session::where('sessionId', $id)->get()->first();
        $session->update($request->all());

        return response([
            'Session' => $session,
            'message' => 'تمت عملية تعديل الدورة بنجاح',
        ], 200);
    }
    public function destroy($id)
    {
        $session = Session::find($id);

        if (!$session) {
            $array = [];
            return response($array, 404);
        } else {

            $array = [
                'message' => "تم حذف الجلسة"
            ];
        }
        $session->delete();
        return response([
            'Session' => $array,
            'message' => "تم الحذف بنجاح"
        ]);
    }
}
