<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Models\advance;
use App\Models\Course;
use App\Models\panchment;
use App\Models\pricingPlan;
use App\Models\pricingPlanCosts;
use App\Models\subject;
use App\Models\Type;
use App\Models\User;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\DB;
use PlanCosts;

use function PHPSTORM_META\type;

class pricingPlanController extends Controller
{
    public function addPlan(Request $request)
    {
        $message = array(
            "name.required" => "حقل الاسم مطلوب",
            "domains.required" => "حقل المجالات لا يجب أن يكون فارغ"
        );
        $validator = Validator::make(request()->all(), [
            "name" => "required",
            "domains" => "required"
        ], $message);

        if ($validator->fails()) {
            $msg = (json_decode(json_encode($validator->errors(), JSON_UNESCAPED_UNICODE), true));
            $string = '';
            foreach (array_values($msg) as $value) {
                $string .=  $value[0] . " , ";
            }
            return response(["message" => "$string"], 422);
        }
        $pricingPlan = pricingPlan::where('planName', $request->name)->get()->first();
        if ($pricingPlan) {
            return response(['message' => "لا يمكن تكرار اسم الخطة"]);
        }
        for ($i = 0; $i < sizeof($request->domains); $i++) {
            /////////////////////
            $message = array(
                "max.required" => "حقل نهاية المجال مطلوب",
                "min.required" => "حقل بداية المجال مطلوب",
                "cost.required" => "حقل كلفة المجال مطلوب",
                "min.numeric" => "حقل بداية المجال يجب أن يكون رقمي",
                "max.numeric" => "حقل نهاية المجال يجب أن يكون رقمي",
                "cost.numeric" => "حقل تلكفة المجال يجب أن يكون رقمي",
            );
            $validator = Validator::make($request->domains[$i], [
                "max" => "required|numeric",
                "min" => "required|numeric",
                "cost" => "required|numeric"
            ], $message);

            if ($validator->fails()) {
                $msg = (json_decode(json_encode($validator->errors(), JSON_UNESCAPED_UNICODE), true));
                $string = '';
                foreach (array_values($msg) as $value) {
                    $string .=  $value[0] . " , ";
                }
                return response(["message" => "$string"], 422);
            }
            //////////////////////
            if ($request->domains[$i]['max'] <= $request->domains[$i]['min']) {
                return response(["message" => "يجب أن تكون نهاية المجال أكبر من بداية المجال"]);
            }
            for ($j = 0; $j < $i; $j++) {
                if (($request->domains[$i]['max'] <= $request->domains[$j]['max'] && $request->domains[$i]['max'] >= $request->domains[$j]['min']) || ($request->domains[$i]['min'] <= $request->domains[$j]['max'] && $request->domains[$i]['min'] >= $request->domains[$j]['min'])) {
                    return response(["message" => "لا يمكن وجود تداخل في المجالات"]);
                }
            }
        }
        DB::beginTransaction();
        $planId =  pricingPlan::insertGetId([
            'planName' => $request->name
        ]);
        if (!$planId) {
            DB::rollBack();
            return response(["message" => "فشل إضافة الخطة"]);
        }
        for ($i = 0; $i < sizeof($request->domains); $i++) {
            $planCost = new pricingPlanCosts();
            $planCost->min = $request->domains[$i]['min'];
            $planCost->max = $request->domains[$i]['max'];
            $planCost->cost = $request->domains[$i]['cost'];
            $planCost->planId = $planId;
            $isSave = $planCost->save();
            if (!$isSave) {
                DB::rollBack();
                return response(["message" => "فشل إضافة الخطة"]);
            }
        }
        DB::commit();
        return response(["message" => "تم إضافة الخطة بنجاح"]);
    }
    public function showPricingPlans()
    {
        $pricingPlan = pricingPlan::get();
        $data = [];
        for ($i = 0; $i < sizeof($pricingPlan); $i++) {
            $data[$i]["plan"] = $pricingPlan[$i];
            $planCost = pricingPlanCosts::where('planId', $pricingPlan[$i]->planId)->get();
            $data[$i]["domains"] = $planCost;
            $courses = Course::where('planId', $pricingPlan[$i]->planId)->get();
            for ($j = 0; $j < sizeof($courses); $j++) {
                $data[$i]["courses"][$j] = [
                    "courseId" => $courses[$j]->courseId,
                    "subjectName" => subject::find($courses[$j]->subjectId)->name,
                    "typeName" => Type::find($courses[$j]->typeId)->name
                ];
            }
        }
        if (sizeof($data) != 0) {
            return response(["data" => $data, "message" => "تم احضار الخطط بنجاح"]);
        }
        return response(["message" => "لا يوجد خطط لعرضها"]);
    }
    public function deletePricingPlan($id)
    {
        $pricingPlan = pricingPlan::find($id);
        if ($pricingPlan) {
            $pricingPlan->delete();
            return response(["message" => "تم حذف الخطة بنجاح"]);
        }
        return response(["message" => "لم يتم ايجاد الخطة"]);
    }
    public function addDomainToPlan($id, Request $request)
    {
        $message = array(
            "max.required" => "حقل نهاية المجال مطلوب",
            "min.required" => "حقل بداية المجال مطلوب",
            "cost.required" => "حقل كلفة المجال مطلوب",
            "min.numeric" => "حقل بداية المجال يجب أن يكون رقمي",
            "max.numeric" => "حقل نهاية المجال يجب أن يكون رقمي",
            "cost.numeric" => "حقل تلكفة المجال يجب أن يكون رقمي",
        );
        $validator = Validator::make(request()->all(), [
            "max" => "required|numeric",
            "min" => "required|numeric",
            "cost" => "required|numeric"
        ], $message);

        if ($validator->fails()) {
            $msg = (json_decode(json_encode($validator->errors(), JSON_UNESCAPED_UNICODE), true));
            $string = '';
            foreach (array_values($msg) as $value) {
                $string .=  $value[0] . " , ";
            }
            return response(["message" => "$string"], 422);
        }
        if ($request->max < $request->min) {
            return response(["message" => "يجب أن تكون نهاية المجال أكبر من بداية المجال"]);
        }
        $pricingPlan = pricingPlan::find($id);
        if ($pricingPlan) {
            $planCost = pricingPlanCosts::where('planId', $id)->get();
            for ($i = 0; $i < sizeof($planCost); $i++) {
                if (($request->max < $planCost[$i]->max && $request->max > $planCost[$i]->min) || ($request->min < $planCost[$i]->max && $request->min > $planCost[$i]->min)) {
                    return response(["message" => "لا يجب أن يتداخل المجال الجديد مع مجالات أخرى"]);
                }
            }
            $planCost = new pricingPlanCosts();
            $planCost->max = $request->max;
            $planCost->min = $request->min;
            $planCost->planId = $id;
            $planCost->cost = $request->cost;
            $planCost->save();
            return response(["message" => "تم إضافة المجال الجديد بنجاح"]);
        } else {
            return response(["message" => "فشل عملية الإضافة لم يتم ايجاد الخطة"]);
        }
    }
    public function deleteDomainFromPlan($id)
    {
        $planCost = pricingPlanCosts::find($id);
        if ($planCost) {
            $planCost->delete();
            return response(["message" => "تم حذف المجال من الخطة بنجاح"]);
        } else {
            return response(["message" => "فشل عملية الحذف المجال غير موجود"]);
        }
    }
    public function getCoursesToAddToPlan()
    {
        $courses = Course::where('planId', null)->where('courseStatus', '!=', "مغلقة")->get();
        $data = [];
        for ($i = 0; $i < sizeof($courses); $i++) {
            $data[$i] = [
                'courseId' => $courses[$i]->courseId,
                'subjectName' => subject::find($courses[$i]->subjectId)->name,
                'typeName' => Type::find($courses[$i]->typeId)->name
            ];
        }
        if (sizeof($data) != 0) {
            return response(['data' => $data, "message" => "تم احضار البيانات بنجاح"]);
        }
        return response(["message" => "لا يوجد كورسات يمكن إضافتها"]);
    }
    public function addCourseToPlan(Request $request)
    {
        $message = array(
            "planId.required" =>  "رقم الخطة مطلوب",
            "courseId.required" => "رقم الكورس مطلوب"
        );
        $validator = Validator::make(request()->all(), [
            "planId" => "required",
            "courseId" => "required"
        ], $message);

        if ($validator->fails()) {
            $msg = (json_decode(json_encode($validator->errors(), JSON_UNESCAPED_UNICODE), true));
            $string = '';
            foreach (array_values($msg) as $value) {
                $string .=  $value[0] . " , ";
            }
            return response(["message" => "$string"], 422);
        }
        $course = Course::find($request->courseId);
        $plan = pricingPlan::find($request->planId);
        if ($course && $plan) {
            $course->planId = $plan->planId;
            if ($course->update()) {
                return response(["message" => "تم إضافة الخطة للكورس بنجاح"]);
            }
            return response(["message" => "فشل إضافة الخطة للكورس"]);
        }
        return response(["message" => "فشل إضافة الخطة للكورس"]);
    }
    public function deleteCoursefromPlan(Request $request)
    {
        $message = array(
            "courseId.required" => "رقم الكورس مطلوب"
        );
        $validator = Validator::make(request()->all(), [
            "courseId" => "required"
        ], $message);

        if ($validator->fails()) {
            $msg = (json_decode(json_encode($validator->errors(), JSON_UNESCAPED_UNICODE), true));
            $string = '';
            foreach (array_values($msg) as $value) {
                $string .=  $value[0] . " , ";
            }
            return response(["message" => "$string"], 422);
        }
        $course = Course::find($request->courseId);
        if ($course) {
            $course->planId = null;
            if ($course->update()) {
                return response(["message" => "تم حذف الكورس من الخطة بنجاح"]);
            }
            return response(["message" => "فشل عملية الحذف"]);
        }
        return response(["message" => "فشل عملية الحذف"]);
    }
}
