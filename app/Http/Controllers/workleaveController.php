<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Models\workleave;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class workleaveController extends Controller
{
    public function addWorkLeave(Request $request)
    {
        $message = array(
            "userId.required" => "رقم المستخدم مطلوب",
            "userId.numeric" => "رقم المستخدم يتكون من أرقام فقط",
            "duration.required" => "مدة الاجازة مطلوبة",
            "type.required" => "نوع الاجازة مطلوب",
            "startDate.required" => "تاريخ بداية الاجازة مطلوب"
        );
        $validator = Validator::make(request()->all(), [
            "userId" => "required|numeric",
            "duration" => "required",
            "type" => "required",
            "startDate" => "required"
        ], $message);

        if ($validator->fails()) {
            $msg = (json_decode(json_encode($validator->errors(), JSON_UNESCAPED_UNICODE), true));
            $string = '';
            foreach (array_values($msg) as $value) {
                $string .=  $value[0] . " , ";
            }
            return response(["message" => "$string"], 422);
        }
        if ($request->type == "ساعية" && !$request->startTime) {
            return response(["message" => "وقت بداية الاجازة مطلوب"]);
        }
        $user = User::find($request->userId);
        if ($user) {
            $workLeave = new workleave();
            $workLeave->userId = $request->userId;
            $workLeave->type = $request->type;
            $workLeave->duration = $request->duration;
            $workLeave->startDate = $request->startDate;
            $add = $workLeave->save();
            if ($request->type == "ساعية") {
                $workLeave->startTime = $request->startTime;
            }
            $add = $workLeave->save();
            if ($add) {
                return response(["data" => $workLeave, "message" => "تم إضافة الاجازة بنجاح"]);
            }
            return response(["message" => "فشل إضافة الاجازة"]);
        }
        return response(["message" => "المستخدم غير موجود"]);
    }
    public function updateReward($id, Request $request)
    {
        $workLeave = workleave::find($id);
        if ($workLeave) {
            if ($workLeave->type == "ساعية" && $request->startTime) {
                $workLeave->startTime = $request->startTime;
            }
            if ($request->duration) {
                $workLeave->duration = $request->duration;
            }
            if ($request->startDate) {
                $workLeave->startDate = $request->startDate;
            }
            $update = $workLeave->update();
            if ($update) {
                return response(["data" => $workLeave, "message" => "تم تعديل الاجازة بنجاح"]);
            }
            return response(["message" => "فشل تعديل الاجازة"]);
        }
        return response(["message" => "فشل تعديل الاجازة"]);
    }
    public function deleteWorkLeave($id)
    {
        $workLeave = workleave::find($id);
        if ($workLeave) {
            $workLeave->delete();
            return response(["message" => "تم حذف الاجازة"]);
        }
        return response(["message" => "فشل حذف الاجازة"]);
    }
    public function showUserWorkLeave($id)
    {
        $user = User::find($id);
        if ($user) {
            $workLeave = workleave::where('userId', $id)->get();
            $data = [];
            if (sizeof($workLeave) != 0) {
                for ($i = 0; $i < sizeof($workLeave); $i++) {
                    $workLeave[$i]->userName = $user->name;
                    $data[$i]['workLeave'] = $workLeave[$i];
                }
                return response(["data" => $data, "message" => "تم احضار الإجازات"]);
            }
            return response(["message" => "لا يوجد إجازات"]);
        }
        return response(["message" => "المستخدم غير موجود"]);
    }
    public function rangeWorkLeave(Request $request)
    {
        $message = array(
            "startDate.date" => "تاريخ بداية المدة حقل تاريخ",
            "startDate.required" => "تاريخ بداية الاجازة مطلوب",
            "endDate.date" => "تاريخ نهاية المدة حقل تاريخ",
            "endDate.required" => "تاريخ نهاية الاجازة مطلوب"
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
        $workLeave = workleave::whereBetween('startDate', [$request->startDate, $request->endDate])->orderBy('startDate')->get();
        $data = [];
        if (sizeof($workLeave) != 0) {
            for ($i = 0; $i < sizeof($workLeave); $i++) {
                $user = User::find($workLeave[$i]->userId);
                $workLeave[$i]->userName = $user->name;
                $data[$i]['workLeave'] = $workLeave[$i];
            }
            return response(["data" => $data, "message" => "تم احضار الإجازات بنجاح"]);
        }
        return response(["message" => "لا يوجد إجازات لعرضها"]);
    }
}
