<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\financialAccounts;
use App\Models\financialOperations;
use App\Models\financialPeriod;
use App\Models\financialStudentAccount;
use App\Models\financialStudentOperations;
use App\Models\financialTypeAccount;
use App\Models\StudentCourse;
use App\Models\studentDepts;
use App\Models\subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class financialStudentOperationController extends Controller
{
    public function addStudentFOperation(Request $request)
    {
        $message = array(
            "SFAId.required" => "رقم حساب الطالب مطلوب",
            "TFAId.required" => "رقم حساب نوع الدورة مطلوب",
            "SFAId.numeric" => "رقم حساب الطالب هو حقل رقمي",
            "TFAId.numeric" => "رقم حساب نوع الدورة هو حقل رقمي",
            "accountId.required" => "رقم الحساب مطلوب",
            "accountId.numeric" => "رقم الحساب هو حقل رقمي",
            "balance.required" => "رصيد العملية مطلوب",
            "balance.numeric" => "رصيد العملية هو حقل رقمي",
            "operationType.required" => "نوع العملية مطلوب",
            "operationDate.required" => "تاريخ العملية مطلوب",
            "operationDate.date" => "تاريخ العملية هو حقل تاريخ",
            "description.required" => "تفاصيل العملية مطلوبة"
        );
        $validator = Validator::make(request()->all(), [
            "SFAId" => "required|numeric",
            "TFAId" => "required|numeric",
            "accountId" => "required|numeric",
            "balance" => "required|numeric",
            "operationType" => "required",
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
        if ($request->operationType != "دفع" && $request->operationType != "إرجاع" && $request->operationType != "انسحاب" && !$request->operationType == "حسم") {
            return response(["message" =>"يجب أن يكون نوع العملية إما دفع أو ارجاع أو انسحاب أو حسم"]);
        }
        $financialPeriod = financialPeriod::where('status', "مفتوحة")->get()->first();
        if (!(strtotime($request->operationDate) >= strtotime($financialPeriod->startDate) && strtotime($request->operationDate) <= strtotime($financialPeriod->endDate))) {
            return response(["message" => $financialPeriod->startDate . " & " . $financialPeriod->endDate . " : " . "يجب أن يكون تاريخ العملية ضمن تاريخ بداية و نهاية الدورة المفتوحة أي بين القيميتين"]);
        }
        $studentAccount = financialAccounts::find($request->SFAId);
        if (!$studentAccount) {
            return response(["message" => "لا يمكن إضافة العملية حساب الطالب غير موجود"]);
        }
        $typeAccount = financialAccounts::find($request->TFAId);
        if (!$typeAccount) {
            return response(["message" => "لا يمكن إضافة العملية حساب نوع الدورة غير موجود"]);
        }
        $financialAccount = financialAccounts::find($request->accountId);
        if (!$financialAccount) {
            return response(["message" => "الحساب المالي المرسل غير موجود"]);
        }
        $user = JWTAuth::parseToken()->authenticate();
        // $studentAccount  = financialAccounts::where('FAId', $studentAccount->FAId)->get()->first();
        // $typeAccount  = financialAccounts::where('FAId', $typeAccount->FAId)->get()->first();
        if ($request->operationType == "دفع") {
            DB::beginTransaction();
            $financialOperation = financialOperations::insertGetId([
                'operationDate' => $request->operationDate,
                'balance' => $request->balance,
                'description' => $request->description,
                'creditorId' => $studentAccount->FAId,
                'debtorId' => $financialAccount->FAId,
                'creditorName' => $studentAccount->accountName,
                'debtorName' => $financialAccount->accountName
            ]);
            if ($financialOperation) {
                $studentAccount->balance = $studentAccount->balance + $request->balance;
                if ($studentAccount->update()) {
                    if ($financialAccount->status == "أصول" || $financialAccount->status == "مصاريف") {
                        $financialAccount->balance = $financialAccount->balance + $request->balance;
                    } else if ($financialAccount->status == "إيرادات" || $financialAccount->status == "خصوم") {
                        $financialAccount->balance = $financialAccount->balance - $request->balance;
                    }
                    if ($financialAccount->update()) {
                        $studentOperation = new financialStudentOperations();
                        $studentOperation->FOId = $financialOperation;
                        $studentOperation->studentId = $request->SFAId;
                        $studentOperation->typeId = $request->TFAId;
                        $studentOperation->operationType = $request->operationType;
                        if ($studentOperation->save()) {
                            $studentDepts = studentDepts::where('studentId', $studentAccount->FAId)->where('typeId', $typeAccount->FAId)->get()->first();
                            if ($studentDepts) {
                                $studentDepts->paidAmount = $studentDepts->paidAmount + $request->balance;
                                $studentDepts->update();
                                DB::commit();
                                $data = [
                                    "operationType" => $request->operationType,
                                    "balance" => $request->balance,
                                    "studentName" => $studentAccount->accountName,
                                    "userName" => $user->name,
                                    "operationDate" => $request->operationDate,
                                    "description" => $request->description,
                                ];
                                return response(["data" => $data, "message" => "تم إضافة عملية الدفع بنجاح"]);
                            } else {
                                DB::rollBack();
                                return response(["message" => "فشل إضافة العملية"]);
                            }
                        } else {
                            DB::rollBack();
                            return response(["message" => "فشل إضافة العملية"]);
                        }
                    }
                }
            } else {
                DB::rollBack();
                return response(["message" => "فشل إضافة العملية"]);
            }
        } else if ($request->operationType == "إرجاع") {
            DB::beginTransaction();
            $financialOperation = financialOperations::insertGetId([
                'operationDate' => $request->operationDate,
                'balance' => $request->balance,
                'description' => $request->description,
                'creditorId' => $financialAccount->FAId,
                'debtorId' => $studentAccount->FAId,
                'creditorName' => $financialAccount->accountName,
                'debtorName' => $studentAccount->accountName,
            ]);
            if ($financialOperation) {
                $studentAccount->balance = $studentAccount->balance - $request->balance;
                if ($studentAccount->update()) {
                    if ($financialAccount->status == "أصول" || $financialAccount->status == "مصاريف") {
                        $financialAccount->balance = $financialAccount->balance - $request->balance;
                    } else if ($financialAccount->status == "إيرادات" || $financialAccount->status == "خصوم") {
                        $financialAccount->balance = $financialAccount->balance + $request->balance;
                    }
                    if ($financialAccount->update()) {
                        $studentOperation = new financialStudentOperations();
                        $studentOperation->FOId = $financialOperation;
                        $studentOperation->studentId = $studentAccount->FAId;
                        $studentOperation->typeId = $typeAccount->FAId;
                        $studentOperation->operationType = $request->operationType;
                        if ($studentOperation->save()) {
                            $studentDepts = studentDepts::where('studentId', $studentAccount->FAId)->where('typeId', $typeAccount->FAId)->get()->first();
                            if ($studentDepts) {
                                $studentDepts->paidAmount = $studentDepts->paidAmount - $request->balance;
                                $studentDepts->update();
                                DB::commit();
                                $data = [
                                    "operationType" => $request->operationType,
                                    "balance" => $request->balance,
                                    "studentName" => $studentAccount->accountName,
                                    "userName" => $user->name,
                                    "operationDate" => $request->operationDate,
                                    "description" => $request->description,
                                ];
                                return response(["data" => $data, "message" => "تم إضافة عملية الإرجاع بنجاح"]);
                            } else {
                                DB::rollBack();
                                return response(["message" => "فشل إضافة العملية"]);
                            }
                        } else {
                            DB::rollBack();
                            return response(["message" => "فشل إضافة العملية"]);
                        }
                    }
                }
            } else {
                DB::rollBack();
                return response(["message" => "فشل إضافة العملية"]);
            }
        } else if ($request->operationType == "انسحاب" || $request->operationType == "حسم") {
            DB::beginTransaction();
            $financialOperation = financialOperations::insertGetId([
                'operationDate' => $request->operationDate,
                'balance' => $request->balance,
                'description' => $request->description,
                'creditorId' => $studentAccount->FAId,
                'debtorId' => $typeAccount->FAId,
                'creditorName' => $studentAccount->accountName,
                'debtorName' => $typeAccount->accountName
            ]);
            if ($financialOperation) {
                $studentAccount->balance = $studentAccount->balance + $request->balance;
                if ($studentAccount->update()) {
                    $typeAccount->balance = $typeAccount->balance - $request->balance;
                    if ($typeAccount->update()) {
                        $studentOperation = new financialStudentOperations();
                        $studentOperation->FOId = $financialOperation;
                        $studentOperation->studentId = $studentAccount->FAId;
                        $studentOperation->typeId = $typeAccount->FAId;
                        $studentOperation->operationType = $request->operationType;
                        if ($studentOperation->save()) {
                            $studentDepts = studentDepts::where('studentId', $studentAccount->FAId)->where('typeId', $typeAccount->FAId)->get()->first();
                            if ($studentDepts) {
                                $studentDepts->deserevedAmount = $studentDepts->deserevedAmount - $request->balance;
                                $studentDepts->update();
                                DB::commit();
                                $data = [
                                    "operationType" => $request->operationType,
                                    "balance" => $request->balance,
                                    "studentName" => $studentAccount->accountName,
                                    "userName" => $user->name,
                                    "operationDate" => $request->operationDate,
                                    "description" => $request->description,
                                ];
                                return response(["data" => $data, "message" => "تم حسم المبلغ بنجاح"]);
                            } else {
                                DB::rollBack();
                                return response(["message" => "فشل إضافة العملية"]);
                            }
                        } else {
                            DB::rollBack();
                            return response(["message" => "فشل إضافة العملية"]);
                        }
                    }
                }
            } else {
                DB::rollBack();
                return response(["message" => "فشل إضافة العملية"]);
            }
        }
    }
    public function deleteStudentOPeration($id)
    {
        $financialOperation = financialOperations::find($id);
        if ($financialOperation) {
            $studentOperation  = financialStudentOperations::where('FOId', $id)->get()->first();
            $amount = $financialOperation->balance;
            $operationType = $studentOperation->operationType;
            $studentDepts = studentDepts::where('typeId', $studentOperation->typeId)->where('studentId', $studentOperation->studentId)->get()->first();
            if ($operationType != "تسجيل") {
                DB::beginTransaction();
                $financialPeriod = financialPeriod::where('status', "مفتوحة")->get()->first();
                if (strtotime($financialOperation->operationDate) >= strtotime($financialPeriod->startDate) && strtotime($financialOperation->operationDate) <= strtotime($financialPeriod->endDate)) {
                    $creditorAccount = financialAccounts::find($financialOperation->creditorId);
                    if ($creditorAccount->status == "أصول") {
                        $creditorAccount->balance = $creditorAccount->balance + $financialOperation->balance;
                        if (!($creditorAccount->update())) {
                            DB::rollBack();
                        }
                    } else if ($creditorAccount->status == "خصوم") {
                        $creditorAccount->balance = $creditorAccount->balance - $financialOperation->balance;
                        if (!($creditorAccount->update())) {
                            DB::rollBack();
                        }
                    } else if ($creditorAccount->status == "مصاريف") {
                        $creditorAccount->balance = $creditorAccount->balance + $financialOperation->balance;
                        if (!($creditorAccount->update())) {
                            DB::rollBack();
                        }
                    } else if ($creditorAccount->status == "إيرادات") {
                        $creditorAccount->balance = $creditorAccount->balance - $financialOperation->balance;
                        if (!($creditorAccount->update())) {
                            DB::rollBack();
                        }
                    }
                    $depditorAccount = financialAccounts::find($financialOperation->debtorId);
                    if ($depditorAccount->status == "أصول") {
                        $depditorAccount->balance = $depditorAccount->balance - $financialOperation->balance;
                        if (!($depditorAccount->update())) {
                            DB::rollBack();
                        }
                    } else if ($depditorAccount->status == "خصوم") {
                        $depditorAccount->balance = $depditorAccount->balance + $financialOperation->balance;
                        if (!($depditorAccount->update())) {
                            DB::rollBack();
                        }
                    } else if ($depditorAccount->status == "مصاريف") {
                        $depditorAccount->balance = $depditorAccount->balance - $financialOperation->balance;
                        if (!($depditorAccount->update())) {
                            DB::rollBack();
                        }
                    } else if ($depditorAccount->status == "إيرادات") {
                        $depditorAccount->balance = $depditorAccount->balance + $financialOperation->balance;
                        if (!($depditorAccount->update())) {
                            DB::rollBack();
                        }
                    }
                    if ($financialOperation->delete()) {
                        DB::commit();
                        if ($operationType == "دفع") {
                            $studentDepts->paidAmount = $studentDepts->paidAmount-$amount;
                            $studentDepts->update();
                        } else if ($operationType == "انسحاب") {
                            $studentDepts->deserevedAmount = $studentDepts->deserevedAmount+$amount;
                            $studentDepts->update();
                        } else if ($operationType == "حسم") {
                            $studentDepts->deserevedAmount = $studentDepts->deserevedAmount+$amount;
                            $studentDepts->update();
                        } else if ($operationType == "إرجاع") {
                            $studentDepts->paidAmount = $studentDepts->paidAmount+$amount;
                            $studentDepts->update();
                        }
                        return response(["message" => "تم الحذف بنجاح"]);
                    } else {
                        DB::rollBack();
                        return response(["message" => "فشل حذف العملية"]);
                    }
                } else {
                    return response(["message" => "لا يمكن حذف العملية ما لم تكن ضمن دورة مالية مفتوحة "]);
                }
            } else {
                return response(["message" => "لا يمكن حذف عمليات التسجيل"]);
            }
        } else {
            return response(["message" => "لم يتم إيجاد العملية"]);
        }
    }
    public function showStudentOperationForType(Request $request)
    {
        $message = array(
            "studentId.required" => "رقم حساب الطالب مطلوب",
            "typeId.required" => "رقم حساب نوع الدورة مطلوب",
            "studentId.numeric" => "رقم حساب الطالب هو حقل رقمي",
            "typeId.numeric" => "رقم حساب نوع الدورة هو حقل رقمي",
        );
        $validator = Validator::make(request()->all(), [
            "studentId" => "required|numeric",
            "typeId" => "required|numeric",
        ], $message);

        if ($validator->fails()) {
            $msg = (json_decode(json_encode($validator->errors(), JSON_UNESCAPED_UNICODE), true));
            $string = '';
            foreach (array_values($msg) as $value) {
                $string .=  $value[0] . " , ";
            }
            return response(["message" => "$string"], 422);
        }
        $count =0;
        $data=[];
        $studentTypeOperations = financialStudentOperations::where('studentId',$request->studentId)->where('typeId',$request->typeId)->orderBy('created_at','DESC')->get();
        for($i=0;$i<sizeof($studentTypeOperations);$i++){
            $financialOperation = financialOperations::find($studentTypeOperations[$i]->FOId);
            if($financialOperation){
                $data[$count]=[
                    "creditorName"=>$financialOperation->creditorName,
                    "debtorName"=>$financialOperation->debtorName,
                    "operationDate"=>$financialOperation->operationDate,
                    "description"=>$financialOperation->description,
                    "balance"=>$financialOperation->balance,
                    "operationType"=>$studentTypeOperations[$i]->operationType,
                    "FOId"=>$studentTypeOperations[$i]->FOId,
                ];
                $count++;
            }
        }
        if(sizeof($data)!=0){
            return response(["data"=>$data,"message"=>"تم احضار العمليات بنجاح"]);
        }
        return response(["message"=>"لا يوجد عمليات لعرضها"]);
    }
    public function showStudentDeptsInType($id){
        $openPeriod = financialPeriod::where('status',"مفتوحة")->get()->first();
        $studentDepts1 = studentDepts::where('typeId',$id)->whereBetween('created_at',[$openPeriod->startDate,$openPeriod->endDate])->orderBy('created_at')->get();
        $studentDepts2 = studentDepts::where('typeId',$id)->whereNotBetween('created_at',[$openPeriod->startDate,$openPeriod->endDate])->whereColumn('deserevedAmount','!=','paidAmount')->orderBy('created_at')->get();
        $studentDepts=array_merge(iterator_to_array($studentDepts1),iterator_to_array($studentDepts2));
        $type = financialTypeAccount::where('FAId',$id)->get()->first()->typeId;
        $typeCourses = Course::where('typeId', $type)->get();
        $stdCourses = [];
        $data=[];
        $count=0;
        for($i=0;$i<sizeof($studentDepts);$i++){
            $studentAccount = financialAccounts::find($studentDepts[$i]->studentId);
            $data[$i]["studentAccount"]=$studentAccount->accountName;
            $data[$i]["studentAccountNO"]=$studentDepts[$i]->studentId;
            $data[$i]["deserevedAmount"]=$studentDepts[$i]->deserevedAmount;
            $data[$i]["paidAmount"]=$studentDepts[$i]->paidAmount;
            $data[$i]["restedAmount"]=$studentDepts[$i]->deserevedAmount-$studentDepts[$i]->paidAmount;
            $data[$i]["deserevedAmount"]=$studentDepts[$i]->deserevedAmount;
            for($j=0;$j<sizeof($typeCourses);$j++){
                $sc = StudentCourse::where('courseId',$typeCourses[$j]->courseId)->where('studentAccount',$studentDepts[$i]->studentId)->get()->first();
                if($sc){
                    $stdCourses[$count]=subject::find(Course::find($typeCourses[$j]->courseId)->subjectId)->name;
                    $count++;
                }
            }
            $data[$i]["studentCourses"]=$stdCourses;
            $stdCourses = [];
            $count=0;
        }
        if(sizeof($data)!=0){
            return response(["data"=>$data,"message"=>"تم احضار البيانات بنجاح"]);
        }
        return response(["message"=>"لا يوجد بيانات لعرضها"]);
    }
    public function showStudentDeptsInTypeInOpenPeriod($id){
        $openPeriod = financialPeriod::where('status',"مفتوحة")->get()->first();
        $studentDepts = studentDepts::where('typeId',$id)->whereBetween('updated_at',[$openPeriod->startDate,$openPeriod->endDate])->orderBy('created_at')->get();
        $type = financialTypeAccount::where('FAId',$id)->get()->first()->typeId;
        $typeCourses = Course::where('typeId', $type)->get();
        $stdCourses = [];
        $data=[];
        $count=0;
        for($i=0;$i<sizeof($studentDepts);$i++){
            $studentAccount = financialAccounts::find($studentDepts[$i]->studentId);
            $data[$i]["studentAccount"]=$studentAccount->accountName;
            $data[$i]["studentAccountNO"]=$studentDepts[$i]->studentId;
            $data[$i]["deserevedAmount"]=$studentDepts[$i]->deserevedAmount;
            $data[$i]["paidAmount"]=$studentDepts[$i]->paidAmount;
            $data[$i]["restedAmount"]=$studentDepts[$i]->deserevedAmount-$studentDepts[$i]->paidAmount;
            $data[$i]["deserevedAmount"]=$studentDepts[$i]->deserevedAmount;
            for($j=0;$j<sizeof($typeCourses);$j++){
                $sc = StudentCourse::where('courseId',$typeCourses[$j]->courseId)->where('studentAccount',$studentDepts[$i]->studentId)->get()->first();
                if($sc){
                    $stdCourses[$count]=subject::find(Course::find($typeCourses[$j]->courseId)->subjectId)->name;
                    $count++;
                }
            }
            $data[$i]["studentCourses"]=$stdCourses;
            $stdCourses = [];
            $count=0;
        }
        if(sizeof($data)!=0){
            return response(["data"=>$data,"message"=>"تم احضار البيانات بنجاح"]);
        }
        return response(["message"=>"لا يوجد بيانات لعرضها"]);
    }
    public function studentDeptForType($studentId,$typeId){
        $studentDepts = studentDepts::where('studentId',$studentId)->where('typeId',$typeId)->get()->first();
        $type = financialTypeAccount::where('FAId',$typeId)->get()->first()->typeId;
        $typeCourses = Course::where('typeId', $type)->get();
        $stdCourses = [];
        $data=[];
        $count=0;
            $studentAccount = financialAccounts::find($studentDepts->studentId);
            if($studentAccount){
                $data[0]["studentAccount"]=$studentAccount->accountName;
                $data[0]["studentAccountNO"]=$studentDepts->studentId;
                $data[0]["deserevedAmount"]=$studentDepts->deserevedAmount;
                $data[0]["paidAmount"]=$studentDepts->paidAmount;
                $data[0]["restedAmount"]=$studentDepts->deserevedAmount-$studentDepts->paidAmount;
                $data[0]["deserevedAmount"]=$studentDepts->deserevedAmount;
                for($j=0;$j<sizeof($typeCourses);$j++){
                    $sc = StudentCourse::where('courseId',$typeCourses[$j]->courseId)->where('studentAccount',$studentDepts->studentId)->get()->first();
                    if($sc){
                        $stdCourses[$count]=subject::find(Course::find($typeCourses[$j]->courseId)->subjectId)->name;
                        $count++;
                    }
                }
                $data[0]["studentCourses"]=$stdCourses;
            }
     
            $stdCourses = [];
            $count=0;
        if(sizeof($data)!=0){
            return response(["data"=>$data,"message"=>"تم احضار البيانات بنجاح"]);
        }
        return response(["message"=>"لا يوجد بيانات لعرضها"]);
    }
}
