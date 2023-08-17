<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\financialAccounts;
use App\Models\financialOperations;
use App\Models\financialPeriod;
use App\Models\financialStudentAccount;
use App\Models\financialTypeAccount;
use App\Models\financialUserAccount;
use App\Models\StudentCourse;
use App\Models\teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\Calculation\Statistical\Size;

class financialAccountsController extends Controller
{
    public function addFinancialAccount(Request $request)
    {
        $message = array(
            "accountName.required" => " اسم الحساب مطلوب",
            "status.required" => "نوع الحساب مطلوب"
        );
        $validator = Validator::make(request()->all(), [
            "accountName" => "required",
            "status" => "required"
        ], $message);

        if ($validator->fails()) {
            $msg = (json_decode(json_encode($validator->errors(), JSON_UNESCAPED_UNICODE), true));
            $string = '';
            foreach (array_values($msg) as $value) {
                $string .=  $value[0] . " , ";
            }
            return response(["message" => "$string"], 422);
        }
        if ($request->status != "أصول" && $request->status != "خصوم" && $request->status != "إيرادات" && $request->status != "مصاريف") {
            return response(["message" => "نوع الحساب يجب أن يكون أصول أو خصوم أو مصاريف أو إيرادات"]);
        }
        $FA = financialAccounts::where('accountName', $request->accountName)->get()->first();
        if ($FA) {
            return response(["message" => "لا يمكن تكرار الاسم لأكثر من حساب"]);
        }
        $financialAccount  = new financialAccounts();
        $financialAccount->accountName = $request->accountName;
        $financialAccount->status = $request->status;
        $financialAccount->balance = 0;
        if ($financialAccount->Save()) {
            return response(["data" => $financialAccount, "message" => "تم إضافة الحساب بنجاح"]);
        }
        return response(["message" => "فشل إضافة الحساب"]);
    }
    public function deleteFinancialAccount($id)
    {
        $financialPeriod = financialPeriod::where('status', "مفتوحة")->get()->first();
        $financialOperations = financialOperations::whereBetween('operationDate', [$financialPeriod->startDate, $financialPeriod->endDate])->get();
        for ($i = 0; $i < sizeof($financialOperations); $i++) {
            if ($financialOperations[$i]->creditorId == $id || $financialOperations[$i]->debtorId == $id) {
                return response(["message" => "لا يمكن حذف الحساب في حال وجوده كطرف في أحد عمليات الدورة المفتوحة"]);
            }
        }
        $financialAccount = financialAccounts::find($id);
        if ($financialAccount) {
            if ($financialAccount->balance == 0) {
                $studentAccount = financialStudentAccount::where('FAId', $id)->get()->first();
                if ($studentAccount && $studentAccount->studentId != null) {
                    return response(["message" => "فشل حذف حساب طالب لا يزال مسجل بالنظام"]);
                }
                $userAccount = financialUserAccount::where('FAId', $id)->get()->first();
                if ($userAccount && $userAccount->userId != null) {
                    return response(["message" => "فشل حذف حساب مستخدم لا يزال مسجل بالنظام"]);
                }
                $typeAccount = financialTypeAccount::where('FAId', $id)->get()->first();
                if ($typeAccount && $typeAccount->typeId != null) {
                    return response(["message" => "فشل حذف حساب دورة لا تزال موجودة بالنظام"]);
                }
                if ($financialAccount->delete()) {
                    return response(["message" => "تم حذف الحساب بنجاح"]);
                }
                return response(["message" => "فشل حذف الحساب"]);
            }
            return response(["message" => "لا يمكن حذف الحساب يجب أن يكون رصيده يساوي الصفر"]);
        }
        return response(["message" => "الحساب غير موجود"]);
    }
    public function getAllFinancialAccounts()
    {
        $financialAccounts = financialAccounts::get();
        if (sizeof($financialAccounts) != 0) {
            return response(["data" => $financialAccounts, "message" => "تم احضار الحسابات بنجاح"]);
        }
        return response(["message" => "لا يوجد حسابات لعرضها"]);
    }
    public function getFinancialAccountsByType(Request $request)
    {
        $message = array(
            "status.required" => "نوع الحساب مطلوب"
        );
        $validator = Validator::make(request()->all(), [
            "status" => "required"
        ], $message);

        if ($validator->fails()) {
            $msg = (json_decode(json_encode($validator->errors(), JSON_UNESCAPED_UNICODE), true));
            $string = '';
            foreach (array_values($msg) as $value) {
                $string .=  $value[0] . " , ";
            }
            return response(["message" => "$string"], 422);
        }
        $financialAccounts = financialAccounts::where('status', $request->status)->get();
        if (sizeof($financialAccounts) != 0) {
            return response((["data" => $financialAccounts, "message" => "تم احضار الحسابات بنجاح"]));
        }
        return response(["message" => "لا يوجد حسابات لعرضها"]);
    }
    public function searchFinancialAccountsByName(Request $request)
    {
        $message = array(
            "accountName.required" => " اسم الحساب مطلوب",
        );
        $validator = Validator::make(request()->all(), [
            "accountName" => "required",
        ], $message);

        if ($validator->fails()) {
            $msg = (json_decode(json_encode($validator->errors(), JSON_UNESCAPED_UNICODE), true));
            $string = '';
            foreach (array_values($msg) as $value) {
                $string .=  $value[0] . " , ";
            }
            return response(["message" => "$string"], 422);
        }
        $financialAccount = financialAccounts::where('accountName', 'like', '%' . $request->accountName . '%')->get();
        if (sizeof($financialAccount) != 0) {
            return response(["data" => $financialAccount, "message" => "تم ايجاد نتائج البحث"]);
        }
        return response(["message" => "لم يتم العثور على نتائج"]);
    }
    public function getAllUsersFinancialAccounts()
    {
        $userAccount = financialUserAccount::get();
        if (sizeof($userAccount) == 0) {
            return response(["message" => "لا يوجد حسابات لعرضها"]);
        }
        $data = [];
        for ($i = 0; $i < sizeof($userAccount); $i++) {
            $financialAccount = financialAccounts::find($userAccount[$i]->FAId);
            $financialAccount->userId = $userAccount[$i]->userId;
            $data[$i] = $financialAccount;
        }
        if (sizeof($data) != 0) {
            return response(["data" => $data, "message" => "تم احضار الحسابات بنجاح"]);
        }
        return response(["message" => "لا يوجد حسابات لعرضها"]);
    }
    public function getAllStudentsFinancialAccounts()
    {
        $studentAccount = financialStudentAccount::get();
        if (sizeof($studentAccount) == 0) {
            return response(["message" => "لا يوجد حسابات لعرضها"]);
        }
        $data = [];
        for ($i = 0; $i < sizeof($studentAccount); $i++) {
            $financialAccount = financialAccounts::find($studentAccount[$i]->FAId);
            $data[$i] = $financialAccount;
        }
        if (sizeof($data) != 0) {
            return response(["data" => $data, "message" => "تم احضار الحسابات بنجاح"]);
        }
        return response(["message" => "لا يوجد حسابات لعرضها"]);
    }
    public function getAllTypeFinancialAccounts()
    {
        $typeAccount = financialTypeAccount::get();
        if (sizeof($typeAccount) == 0) {
            return response(["message" => "لا يوجد حسابات لعرضها"]);
        }
        $data = [];
        for ($i = 0; $i < sizeof($typeAccount); $i++) {
            $financialAccount = financialAccounts::find($typeAccount[$i]->FAId);
            $data[$i] = $financialAccount;
        }
        if (sizeof($data) != 0) {
            return response(["data" => $data, "message" => "تم احضار الحسابات بنجاح"]);
        }
        return response(["message" => "لا يوجد حسابات لعرضها"]);
    }
    public function getAllTeacherFinancialAccounts()
    {
        $userAccount = financialUserAccount::get();
        if (sizeof($userAccount) == 0) {
            return response(["message" => "لا يوجد حسابات لعرضها"]);
        }
        $data = [];
        $IsTeacher = [];
        $count = 0;
        for ($j = 0; $j < sizeof($userAccount); $j++) {
            $teacher = teacher::where('userId', $userAccount[$j]->userId)->get()->first();
            if ($teacher) {
                $IsTeacher[$count] = $userAccount[$j];
                $count++;
            }
        }
        for ($i = 0; $i < sizeof($IsTeacher); $i++) {
            $financialAccount = financialAccounts::find($IsTeacher[$i]->FAId);
            $financialAccount->userId = $IsTeacher[$i]->userId;
            $data[$i] = $financialAccount;
        }
        if (sizeof($data) != 0) {
            return response(["data" => $data, "message" => "تم احضار الحسابات بنجاح"]);
        }
        return response(["message" => "لا يوجد حسابات لعرضها"]);
    }
    public function getAllEmployeesFinancialAccounts()
    {
        $userAccount = financialUserAccount::get();
        if (sizeof($userAccount) == 0) {
            return response(["message" => "لا يوجد حسابات لعرضها"]);
        }
        $data = [];
        $IsImp = [];
        $count = 0;
        for ($j = 0; $j < sizeof($userAccount); $j++) {
            $teacher = teacher::where('userId', $userAccount[$j]->userId)->get()->first();
            if (!$teacher) {
                $IsImp[$count] = $userAccount[$j];
                $count++;
            }
        }
        for ($i = 0; $i < sizeof($IsImp); $i++) {
            $financialAccount = financialAccounts::find($IsImp[$i]->FAId);
            $data[$i] = $financialAccount;
        }
        if (sizeof($data) != 0) {
            return response(["data" => $data, "message" => "تم احضار الحسابات بنجاح"]);
        }
        return response(["message" => "لا يوجد حسابات لعرضها"]);
    }
    // public function getStudentsAccountsBelongToType($id)
    // {
    //     $typeCourses = Course::where('typeId', $id)->get();
    //     $studentsIds = [];
    //     if (sizeof($typeCourses) != 0) {
    //         for ($i = 0; $i < sizeof($typeCourses); $i++) {
    //             $studentCourse = StudentCourse::where('courseId', $typeCourses[$i]->courseId)->get();
    //             for ($j = sizeof($studentsIds); $j < sizeof($studentCourse); $j++) {
    //                 $studentsIds[$j] = $studentCourse[$j]->studentId;
    //             }
    //         }
    //         $count = 0;
    //         $data = [];
    //         $studentAccount = [];
    //         for ($k = 0; $k < sizeof($studentsIds); $k++) {
    //             $stAc = financialStudentAccount::where('studentId', $studentsIds[$k])->get()->first();
    //             if ($stAc) {
    //                 $data[$count] = financialAccounts::find($stAc->FAId);
    //                 $count++;
    //             }
    //         }
    //         if (sizeof($data) == 0) {
    //             return response(["message" => "لا يوجد حسابات لعرضها"]);
    //         }
    //         return response(["data" => $data, "message" => "تم احضار الحسابات بنجاح"]);
    //     }
    //     return response(["message" => "لا يوجد حسابات لعرضها"]);
    // }
    public function getStudentsAccountsBelongToType($id)
    {
        $typeCourses = Course::where('typeId', $id)->get();
        $studentsIds = [];
        $count = 0;
        if (sizeof($typeCourses) != 0) {
            for ($i = 0; $i < sizeof($typeCourses); $i++) {
                $studentCourse = StudentCourse::where('courseId', $typeCourses[$i]->courseId)->get();
                for ($j = 0; $j < sizeof($studentCourse); $j++) {
                        $studentsIds[$count] = $studentCourse[$j]->stduentAccount;
                        $count++;
                }
            }
            $data = [];
            $studentAccount = [];
            for ($k = 0; $k < sizeof(array_unique($studentsIds)); $k++) {
                $data[$k] = financialAccounts::find(array_unique($studentsIds)[$k]);
            }
            if (sizeof($data) == 0) {
                return response(["message" => "لا يوجد حسابات لعرضها"]);
            }
            return response(["data" => $data, "message" => "تم احضار الحسابات بنجاح"]);
        }
        return response(["message" => "لا يوجد حسابات لعرضها"]);
    }
    
}
