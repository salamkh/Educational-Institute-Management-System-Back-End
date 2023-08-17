<?php

namespace App\Http\Controllers;

use App\Models\financialAccounts;
use App\Models\financialOperations;
use Illuminate\Http\Request;
use App\Models\financialPeriod;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class financialPeriodController extends Controller
{
    public function addFinancialPeriod(Request $request)
    {
        $financialPeriod = new financialPeriod();
        $financialPeriod->startDate = $request->startDate;
        $financialPeriod->endDate = $request->endDate;
        $financialPeriod->description = $request->description;
        $financialPeriod->status = "مفتوحة";
        if ($financialPeriod->save()) {
            return response(["data" => $financialPeriod, "message" => "تم فتح دورة مالية جديدة بنجاح"]);
        }
        return response(["message" => "فشل عملية فتح دورة مالية"]);
    }
    public function extendFinancialPeriod($id, Request $request)
    {
        $financialPeriod = financialPeriod::find($id);
        if ($financialPeriod) {
            $financialPeriod->endDate = $request->endDate;
            if ($financialPeriod->update()) {
                return response(["data" => $financialPeriod, "message" => "تم تمديد مدة الدورة بنجاح"]);
            }
            return response(["message" => "فشل تمديد المدة "]);
        }
        return response(["message" => "فشل تمديد المدة "]);
    }
    public function getFinancialPeriod()
    {
        $financialPeriod = financialPeriod::orderBy('startDate', 'DESC')->get();
        return response(["data" => $financialPeriod, "message" => "تم احضار البيانات بنجاح"]);
    }
    public function getFPoperations($id)
    {
        $financialPeriod = financialPeriod::find($id);
        $data = [];
        if ($financialPeriod) {
            $operations = financialOperations::whereBetween('operationDate', [$financialPeriod->startDate, $financialPeriod->endDate])->get();
            if (sizeof($operations) != 0) {
                for ($i = 0; $i < sizeof($operations); $i++) {
                    $data[$i] = [
                        'description' => $operations[$i]->description,
                        'operationDate' => $operations[$i]->operationDate,
                        'balance' => $operations[$i]->balance,
                        'creditorName' => $operations[$i]->creditorName,
                        'debtorName' => $operations[$i]->debtorName
                    ];
                }
                return response(["data" => $data, "message" => "تم احضار العمليات بنجاح"]);
            }
            return response(["message" => "لا يوجد عمليات لعرضها"]);
        }
        return response(["message" => "فشل عملية العرض لم يتم ايجاد الدورة"]);
    }
    public function showFinancialPeriod($id)
    {
        $financialPeriod = financialPeriod::find($id);
        if ($financialPeriod) {
            return response(["data" => $financialPeriod, "message" => "تم عرض الدورة بنجاح"]);
        }
        return response(["message" => "لم يتم ايجاد الدورة"]);
    }
    public function closeFinancialPeriod($id)
    {
        $financialPeriod = financialPeriod::find($id);
        if ($financialPeriod->status == "مفتوحة") {
            DB::beginTransaction();
            $expenses = financialAccounts::where("status", "مصاريف")->get();
            $expensesCopy =  [];
            $totalExpenses = 0;
            for ($i = 0; $i < sizeof($expenses); $i++) {
                $expensesCopy[$i] = clone $expenses[$i];
                $totalExpenses += $expenses[$i]->balance;
                $expenses[$i]->balance = 0;
                $updated = $expenses[$i]->update();
                if ($updated == false) {
                    DB::rollBack();
                    return response(["message" => "فشل عملية الإغلاق"]);
                }
            }
            $revenues =  financialAccounts::where("status", "إيرادات")->get();
            $revenuesCopy = [];
            $totalRevenues = 0;
            for ($i = 0; $i < sizeof($revenues); $i++) {
                $revenuesCopy[$i] = clone $revenues[$i];
                $totalRevenues += $revenues[$i]->balance;
                $revenues[$i]->balance = 0;
                $updated = $revenues[$i]->update();
                if ($updated == false) {
                    DB::rollBack();
                    return response(["message" => "فشل عملية الإغلاق"]);
                }
            }
            $result = $totalRevenues - $totalExpenses;
            $financialAccount = new financialAccounts();
            $financialAccount->accountName = " أرباح و خسائر " . $financialPeriod->description . " : " . $financialPeriod->startDate . "__" . $financialPeriod->endDate;
            $financialAccount->status  = "خصوم";
            $financialAccount->balance = $result;
            if ($financialAccount->save()) {
                $financialPeriod->status = "مغلقة";
                $financialPeriod->resault = $result;
                if ($financialPeriod->update() == true) {
                    DB::commit();
                    $data = [
                        "revenues" => $revenuesCopy,
                        "totalRevenues" => $totalRevenues,
                        "expenses" => $expensesCopy,
                        "totalExpenses" => $totalExpenses,
                        "result" => $result,
                    ];
                    return response(["data" => $data, "message" => "تم إغلاق الدورة بنجاح"]);
                } else {
                    DB::rollBack();
                    return response(["message" => "فشل عملية الإغلاق"]);
                }
            } else {
                DB::rollBack();
                return response(["message" => "فشل عملية الإغلاق"]);
            }
        }
        return response(["message" => "فشل عملية الإغلاق"]);
    }
    public function getResult()
    {
        $financialPeriod = financialPeriod::where('status', "مفتوحة")->get()->first();
        if ($financialPeriod) {
            $expenses = financialAccounts::where("status", "مصاريف")->get();
            $revenues =  financialAccounts::where("status", "إيرادات")->get();
            $totalExpenses = 0;
            $totalRevenues = 0;
            for ($i = 0; $i < sizeof($expenses); $i++) {
                $totalExpenses += $expenses[$i]->balance;
            }
            for ($i = 0; $i < sizeof($revenues); $i++) {
                $totalRevenues += $revenues[$i]->balance;
            }
            $result = $totalRevenues - $totalExpenses;
            $data = [
                "expenses" => $expenses,
                "revenues" => $revenues,
                "totalExpenses" => $totalExpenses,
                "totalRevenues" => $totalRevenues,
                "result" => $result
            ];
            return response(["data" => $data, "message" => "تم احضار النتيجة بنجاح"]);
        } else {
            return response(["message" => "يجب أن يوجد دورة مفتوحة لحساب النتيجة الحالية"]);
        }
    }
}
