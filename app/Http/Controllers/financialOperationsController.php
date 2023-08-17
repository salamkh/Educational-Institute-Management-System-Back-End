<?php

namespace App\Http\Controllers;

use App\Models\financialAccounts;
use App\Models\financialOperations;
use App\Models\financialPeriod;
use App\Models\financialStudentOperations;
use App\Models\studentDepts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class financialOperationsController extends Controller
{
    public function addFinancialOperation(Request $request)
    {
        $creditorAccount = financialAccounts::find($request->creditorId);
        $creditorName = $creditorAccount->accountName;
        if ($creditorAccount->status == "أصول") {
            $creditorAccount->balance = ($creditorAccount->balance) - ($request->balance);
            $creditorAccount->update();
        } else if ($creditorAccount->status == "خصوم") {
            $creditorAccount->balance = ($creditorAccount->balance) + ($request->balance);
            $creditorAccount->update();
        } else if ($creditorAccount->status == "مصاريف") {
            $creditorAccount->balance = ($creditorAccount->balance) - ($request->balance);
            $creditorAccount->update();
        } else if ($creditorAccount->status == "إيرادات") {
            $creditorAccount->balance = ($creditorAccount->balance) + ($request->balance);
            $creditorAccount->update();
        }
        $depditorAccount = financialAccounts::find($request->debtorId);
        $depditorName = $depditorAccount->accountName;
        if ($depditorAccount->status == "أصول") {
            $depditorAccount->balance = ($depditorAccount->balance) + ($request->balance);
            $depditorAccount->update();
        } else if ($depditorAccount->status == "خصوم") {
            $depditorAccount->balance = ($depditorAccount->balance) - ($request->balance);
            $depditorAccount->update();
        } else if ($depditorAccount->status == "مصاريف") {
            $depditorAccount->balance = ($depditorAccount->balance) + ($request->balance);
            $depditorAccount->update();
        } else if ($depditorAccount->status == "إيرادات") {
            $depditorAccount->balance = ($depditorAccount->balance) - ($request->balance);
            $depditorAccount->update();
        }

        $financialOperation = new financialOperations();
        $financialOperation->operationDate = $request->operationDate;
        $financialOperation->balance = $request->balance;
        $financialOperation->description = $request->description;
        $financialOperation->creditorId = $request->creditorId;
        $financialOperation->debtorId = $request->debtorId;
        $financialOperation->creditorName = $creditorName;
        $financialOperation->debtorName = $depditorName;
        if ($financialOperation->save()) {
            return response(["data" => $financialOperation, "message" => "تمت إضافة العملية بنجاح"]);
        }
        return response(["message" => "فشلت إضافة العملية"]);
    }
    public function deleteFinancialOperation($id)
    {
        $financialOperation = financialOperations::find($id);
        $financialStudentOperation = financialStudentOperations::where('FOId', $id)->get()->first();
        if (!$financialStudentOperation) {
            if ($financialOperation) {
                $financialPeriod = financialPeriod::where('status', "مفتوحة")->get()->first();
                if (strtotime($financialOperation->operationDate) >= strtotime($financialPeriod->startDate) && strtotime($financialOperation->operationDate) <= strtotime($financialPeriod->endDate)) {
                    DB::beginTransaction();
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
                        return response(["message" => "تم الحذف بنجاح"]);
                    }
                    DB::rollBack();
                    return response(["message" => "فشل حذف العملية"]);
                }
                return response(["message" => "لا يمكن حذف عملية لا تنتمي لدورة مفتوحة"]);
            }
            return response(["message" => "لم يتم ايجاد العملية"]);
        } else {
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
                                $studentDepts->paidAmount = $studentDepts->paidAmount - $amount;
                                $studentDepts->update();
                            } else if ($operationType == "انسحاب") {
                                $studentDepts->deserevedAmount = $studentDepts->deserevedAmount + $amount;
                                $studentDepts->update();
                            } else if ($operationType == "حسم") {
                                $studentDepts->deserevedAmount = $studentDepts->deserevedAmount + $amount;
                                $studentDepts->update();
                            } else if ($operationType == "إرجاع") {
                                $studentDepts->paidAmount = $studentDepts->paidAmount + $amount;
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
    }
    public function getFinancialOperationsOfOpenPeriod()
    {
        $financialPeriod = financialPeriod::where('status', "مفتوحة")->get()->first();
        $financialOperations = financialOperations::whereBetween('operationDate', [$financialPeriod->startDate, $financialPeriod->endDate])->orderBy('operationDate')->get();
        if (sizeof($financialOperations) != 0) {
            return response(["data" => $financialOperations, "message" => "تم عرض العمليات بنجاح"]);
        }
        return response(["messgae" => "لا يوجد عمليات مضافة بعد"]);
    }
    public function getFinancialOperationsInRange(Request $request)
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
        $financialOperations = financialOperations::whereBetween('operationDate', [$request->startDate, $request->endDate])->orderBy('operationDate')->get();
        if (sizeof($financialOperations) != 0) {
            return response(["data" => $financialOperations, "message" => "تم احضار العمليات بنجاح"]);
        }
        return response(["message" => "لا يوجد عمليات لعرضها"]);
    }
    public function getFinancialOperationsOnAccountInRange($id, Request $request)
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
        $financialOperations = financialOperations::whereBetween('operationDate', [$request->startDate, $request->endDate])->orderBy('operationDate')->get();
        $data = [];
        $index = 0;
        for ($i = 0; $i < sizeof($financialOperations); $i++) {
            if ($financialOperations[$i]->creditorId == $id || $financialOperations[$i]->debtorId == $id) {
                $data[$index] = $financialOperations[$i];
                $index++;
            }
        }
        if (sizeof($data) != 0) {
            return response(["data" => $data, "message" => "تم عرض العمليات بنجاح"]);
        }
        return response(["message" => "لا يوجد عمليات لعرضها"]);
    }
    public function getFinancialOperationsOnAccountInOpenPeriod($id)
    {
        $financialPeriod = financialPeriod::where('status', "مفتوحة")->get()->first();
        if ($financialPeriod) {
            $financialOperations = financialOperations::whereBetween('operationDate', [$financialPeriod->startDate, $financialPeriod->endDate])->orderBy('operationDate')->get();
            $data = [];
            $index = 0;
            for ($i = 0; $i < sizeof($financialOperations); $i++) {
                if ($financialOperations[$i]->creditorId == $id || $financialOperations[$i]->debtorId == $id) {
                    $data[$index] = $financialOperations[$i];
                    $index++;
                }
            }
            if (sizeof($data) != 0) {
                return response(["data" => $data, "message" => "تم عرض العمليات بنجاح"]);
            }
            return response(["message" => "لا يوجد عمليات لعرضها"]);
        }
        return response(["message" => "لا يوجد عمليات لعرضها"]);
    }
}
