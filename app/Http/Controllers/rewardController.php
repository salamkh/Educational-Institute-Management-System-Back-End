<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Models\reward;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class rewardController extends Controller
{
    public function addReward(Request $request)
    {
        $message = array(
            "userId.required" => "رقم المستخدم مطلوب",
            "userId.numeric" => "رقم المستخدم يتكون من أرقام فقط",
            "rewarddDate.required" => "تاريخ المكافأة مطلوب",
            "rewarddDate.date" => "حقل تاريخ المكافأة يجب أن يكون تاريخ",
            "cause.required" => "سبب المكافأة مطلوب",
            "balance.required" => "رصيد المكافأة مطلوب",
            "balance.numeric" => "رصيد المكافأة يتكون من أرقام فقط",
            "status.required" => "حالة المكافأة مطلوبة",
        );
        $validator = Validator::make(request()->all(), [
            "userId" => "required|numeric",
            "rewarddDate" => "required|date",
            "cause" => "required",
            "balance" => "required|numeric",
            "status" => "required",
        ], $message);

        if ($validator->fails()) {
            $msg = (json_decode(json_encode($validator->errors(), JSON_UNESCAPED_UNICODE), true));
            $string = '';
            foreach (array_values($msg) as $value) {
                $string .=  $value[0] . " , ";
            }
            return response(["message" => "$string"], 422);
        }
        $user = User::find($request->userId);
        if (!$user) {
            return response(["message" => "فشل إضافة المكافأة المستخدم غير موجود"]);
        } else {
            $reward = new reward();
            $reward->userId = $request->userId;
            $reward->rewarddDate = $request->rewarddDate;
            $reward->cause = $request->cause;
            $reward->balance = $request->balance;
            $reward->status = $request->status;
            $add = $reward->save();
            if ($add) {
                return response(["data" => $reward, "message" => "تم إضافة المكافأة بنجاح"]);
            }
            return response(["message" => "فشل إضافة المكافأة"]);
        }
    }

    public function updateReward($id, Request $request)
    {
        $message = array(
            "rewarddDate.date" => "حقل تاريخ المكافأة يجب أن يكون تاريخ",
            "balance.numeric" => "رصيد المكافأة يتكون من أرقام فقط"
        );
        $validator = Validator::make(request()->all(), [
            "rewarddDate" => "date",
            "balance" => "numeric"
        ], $message);

        if ($validator->fails()) {
            $msg = (json_decode(json_encode($validator->errors(), JSON_UNESCAPED_UNICODE), true));
            $string = '';
            foreach (array_values($msg) as $value) {
                $string .=  $value[0] . " , ";
            }
            return response(["message" => "$string"], 422);
        }
        $reward = reward::find($id);
        if ($reward) {
            if ($request->status != null) {
                $update = $reward->update($request->except('status'));
            } else {
                $update = $reward->update($request->all());
            }
            if ($update) {
                return response(["data" => $reward, "message" => "تم تعديل المكافأة بنجاح"]);
            }
            return response(["message" => "فشل تعديل المكافأة"]);
        }
        return response(["message" => "فشل تعديل المكافأة"]);
    }
    public function showUserReward($id)
    {
        $reward = reward::where('userId', $id)->get();
        if (sizeof($reward) != 0) {
            $data = [];
            for ($i = 0; $i < sizeof($reward); $i++) {
                $user = User::find($reward[$i]->userId);
                $data[$i] = [
                    "userName" => $user->name,
                    "advance" => $reward[$i]
                ];
            }
            return response(['data' => $data, "message" => "تم احضار البيانات بنجاح"]);
        }
        return response(["message" => "لا يوجد بيانات لعرضها"]);
    }

    public function RangeRewards(Request $request)
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

        $reward = reward::whereBetween('rewarddDate', [$request->startDate, $request->endDate])->orderBy('rewarddDate')->get();
        if (sizeof($reward) != 0) {
            $data = [];
            for ($i = 0; $i < sizeof($reward); $i++) {
                $user = User::find($reward[$i]->userId);
                $data[$i] = [
                    "userName" => $user->name,
                    "advance" => $reward[$i]
                ];
            }
            return response(["data" => $data, "message" => "تم احضار البيانات بنجاح"]);
        }
        return response(["message" => "لا يوجد بينانات لعرضها"]);
    }
    public function showUserRewardInRange($id)
    {
        $reward = reward::where('userId', $id)->where('status', "غير مصروفة")->orderBy('rewarddDate')->get();
        $data = [];
        if (sizeof($reward) != 0) {
           
            for ($i = 0; $i < sizeof($reward); $i++) {
                $user = User::find($reward[$i]->userId);
                $data[$i] = [
                    "userName" => $user->name,
                    "advance" => $reward[$i]
                ];
            }
            return response(["data" => $data, "message" => "تم احضار البيانات بنجاح"]);
        }
        return response(["data" => $data,"message" => "لا يوجد بينانات لعرضها"]);
    }
    public function changeٌRewardStatus($id, Request $request)
    {
        $message = array(
            "status.required" => "حالة المكافأة مطلوبة"
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
        if ($request->status != "غير مصروفة" && $request->status != "مصروفة") {
            return response(["message" => "حالة المكافأة يجب أن تكون مصروفة أو غير مصروفة"], 422);
        }
        $reward = reward::find($id);
        if ($reward) {
            $reward->status = $request->status;
            $reward->update();
            return response(["message" => "تم تعديل الحالة بنجاح"]);
        }
        return response(["message" => "فشل عملية التعديل"]);
    }
}
