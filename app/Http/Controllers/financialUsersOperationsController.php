<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\financialAccounts;
use App\Models\financialOperations;
use App\Models\financialUserAccount;
use App\Models\Session;
use App\Models\sessionCost;
use App\Models\sessionTeacherMonitoring;
use App\Models\subject;
use App\Models\teacher;
use App\Models\Type;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class financialUsersOperationsController extends Controller
{
    public function getEmployeeSalary($id)
    {
        $financialAccount = financialAccounts::find($id);
        if ($financialAccount) {
            $userAccount = financialUserAccount::where('FAId', $id)->get()->first();
            if ($userAccount) {
                $user = User::find($userAccount->userId);
                if ($user) {
                    return response(["data" => $user->salary, "message" => "تم احضار البيانات بنجاح"]);
                }
                return response(["message" => "تعذر الوصل للبيانات لم يتم إيجاد الحساب"]);
            }
            return response(["message" => "تعذر الوصل للبيانات لم يتم إيجاد الحساب"]);
        }
        return response(["message" => "تعذر الوصل للبيانات لم يتم إيجاد الحساب"]);
    }
    public function getTeacherSalary($id, Request $request)
    {
        $message = array(
            "startDate.required" => "تاريخ البداية مطلوب",
            "endDate.required" => "تاريخ النهاية مطلوب",
            "startDate.date" => "تاريخ البداية هو حقل تاريخ",
            "endDate.date" => "تاريخ النهاية هو حقل تاريخ"
        );
        $validator = Validator::make(request()->all(), [
            "startDate" => "required|date",
            "endDate" => "required|date"
        ], $message);

        if ($validator->fails()) {
            $msg = (json_decode(json_encode($validator->errors(), JSON_UNESCAPED_UNICODE), true));
            $string = '';
            foreach (array_values($msg) as $value) {
                $string .=  $value[0] . " , ";
            }
            return response(["message" => "$string"], 422);
        }
        $financialAccount = financialAccounts::find($id);
        if ($financialAccount) {
            $userAccount = financialUserAccount::where('FAId', $id)->get()->first();
            if ($userAccount) {
                $user = User::find($userAccount->userId);
                if ($user) {
                    $teacher = teacher::where('userId', $user->userId)->get()->first();
                    if ($teacher) {
                        $allSessions = Session::whereBetween('date', [$request->startDate, $request->endDate])->get();
                        $teacherSessions = [];
                        $count = 0;
                        for ($i = 0; $i < sizeof($allSessions); $i++) {
                            $session_teacher_monitoring = sessionTeacherMonitoring::where('teacherId', $teacher->tId)->where('sessionId', $allSessions[$i]->sessionId)->get()->first();
                            if ($session_teacher_monitoring) {
                                $teacherSessions[$count] = $allSessions[$i];
                                $count++;
                            }
                        }
                        $data = [];
                        $total  = 0;
                        for ($i = 0; $i < sizeof($teacherSessions); $i++) {
                            $course = Course::find($teacherSessions[$i]->courseId);
                            $type = Type::find($course->typeId);
                            $subject = subject::find($course->subjectId);
                            $sessionCost = sessionCost::where('sessionId', $teacherSessions[$i]->sessionId)->get()->first();
                            $data[$i] = [
                                "sessionDate" => $teacherSessions[$i]->date,
                                "subjectName" => $subject->name,
                                "typeName" => $type->name,
                                "studentNumber" => $sessionCost->studentNumber,
                                "sessionCost" => $sessionCost->cost
                            ];
                            $total += $sessionCost->cost;
                        }
                        $allData = [];
                        if (sizeof($data) == 0) {
                            $allData['total'] = 0;
                            return response(["data" => $allData, "message" => "لم يقم الاستاذ بأي جلسة خلال المدة المطلوبة"]);
                        } else {
                            $allData['total'] = $total;
                            $allData['data'] = $data;
                            return response(["data" => $allData, "message" => "تم احضار البيانات بنجاح"]);
                        }
                    } else {
                        return response(["message" => "تعذر الوصل للبيانات لم يتم إيجاد الحساب"]);
                    }
                } else {
                    return response(["message" => "تعذر الوصل للبيانات لم يتم إيجاد الحساب"]);
                }
            } else {
                return response(["message" => "تعذر الوصل للبيانات لم يتم إيجاد الحساب"]);
            }
        } else {
            return response(["message" => "تعذر الوصل للبيانات لم يتم إيجاد الحساب"]);
        }
    }
    public function paySalary(Request $request){
        
        $message = array(
            "UFAId.required" => "رقم حساب الموظف مطلوب",
            "creditorId.required" => "رقم الحساب الدائن مطلوب",
            "UFAId.numeric" => "رقم حساب الموظف هو حقل رقمي",
            "creditorId.numeric" => "رقم الحساب الدائن هو حقل رقمي",
            "balance.required" => "رصيد العملية مطلوب",
            "balance.numeric" => "رصيد العملية هو حقل رقمي",
            "operationDate.required" => "تاريخ العملية مطلوب",
            "operationDate.date" => "تاريخ العملية هو حقل تاريخ",
            "description.required" => "تفاصيل العملية مطلوبة"
        );
        $validator = Validator::make(request()->all(), [
            "UFAId" => "required|numeric",
            "creditorId" => "required|numeric",
            "balance" => "required|numeric",
            "operationDate" => "required|date",
            "description" => "required"
        ], $message);

        if ($validator->fails()) {
            $msg = (json_decode(json_encode($validator->errors(), JSON_UNESCAPED_UNICODE), true));
            $string = '';
            foreach (array_values($msg) as $value) {
                $string .=  $value[0] . " , ";
            }
            return response(["message" => "$string"], 422);
        }
        $user = JWTAuth::parseToken()->authenticate();
        $userAccount = financialAccounts::find($request->UFAId);
        $isUser = financialUserAccount::where('FAId',$request->UFAId)->get()->first();
        $creditor = financialAccounts::find($request->creditorId);
        if($userAccount && $isUser && $creditor){
            DB::beginTransaction();
            if($creditor->status=="أصول"){
                $creditor->balance = $creditor->balance - $request->balance;
                $udpated = $creditor->update();
                if(!$udpated){
                    DB::rollBack();
                }
            }
            else if($creditor->status=="خصوم"){
                $creditor->balance = $creditor->balance + $request->balance;
                $udpated = $creditor->update();
                if(!$udpated){
                    DB::rollBack();
                }
            }
            else if ($creditor->status=="مصاريف"){
                $creditor->balance = $creditor->balance - $request->balance;
                $udpated = $creditor->update();
                if(!$udpated){
                    DB::rollBack();
                }
            }
            else if ($creditor->status=="إيرادات"){
                $creditor->balance = $creditor->balance + $request->balance;
                $udpated = $creditor->update();
                if(!$udpated){
                    DB::rollBack();
                }
            }
            $userAccount->balance = $userAccount->balance + $request->balance;
                $udpated = $userAccount->update();
                if(!$udpated){
                    DB::rollBack();
                }
                $financialOperation = new financialOperations();
                $financialOperation->operationDate = $request->operationDate;
                $financialOperation->balance = $request->balance;
                $financialOperation->description = $request->description;
                $financialOperation->creditorId = $request->creditorId;
                $financialOperation->debtorId = $request->UFAId;
                $financialOperation->creditorName = $creditor->accountName;
                $financialOperation->debtorName = $userAccount->accountName;
                if ($financialOperation->save()) {
                    DB::commit();
                    $data=[
                        "operation"=>$financialOperation,
                        "userName"=>$user->name
                    ];
                    return response(["data" => $data, "message" => "تمت إضافة العملية بنجاح"]);
                }
                else{
                    DB::rollBack();
                    return response(["message" => "فشل عملية الدفع"]);
                }
        }
        return response (["message"=>"فشل عملية الدفع"]);
    }
}
