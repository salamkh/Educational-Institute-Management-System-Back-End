<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\financialPeriod;

class financialPeriodCond
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $message = array(
            "startDate.required" => "تاريخ بداية الدورة المالية مطلوب",
            "startDate.date" => "حقل تاريخ بداية الدورة المالية يجب أن يكون تاريخ",
            "endDate.required" => "تاريخ نهاية الدورة المالية مطلوب",
            "endDate.date" => "حقل تاريخ نهاية الدورة المالية يجب أن يكون تاريخ",
            "description.required" => "اسم الدورة المالية مطلوب"
        );
        $validator = Validator::make(request()->all(), [
            "startDate" => "required|date",
            "endDate" => "required|date",
            "description" => "required",
        ], $message);

        if ($validator->fails()) {
            $msg = (json_decode(json_encode($validator->errors(), JSON_UNESCAPED_UNICODE), true));
            $string = '';
            foreach (array_values($msg) as $value) {
                $string .=  $value[0] . " , ";
            }
            return response(["message" => "$string"], 422);
        }
        if (date('Y-m-d', strtotime($request->startDate))  >= date('Y-m-d', strtotime($request->endDate))) {
            return response(["message" => "يجب أن يكون تاريخ بداية الدورة أقل من تاريخ نهاية الدورة"]);
        }
        $financialPeriods = financialPeriod::orderBy('startDate', 'DESC')->get();
        if (sizeof($financialPeriods) != 0) {
            for ($i = 0; $i < sizeof($financialPeriods); $i++) {
                if ($financialPeriods[$i]->status == "مفتوحة") {
                    return response(["message" => "لا يمكن فتح دورة جديدة في حال وجود دورة مفتوحة"]);
                }
            }
            if (date('Y-m-d', strtotime($financialPeriods[0]->endDate)) >= date('Y-m-d', strtotime($request->startDate))) {
                return response(["message" => "تاريخ بداية الدورة الجديدة يجب أن يكون من تاريخ نهاية آخر دورة مغلقة"]);
            }
        }
        return $next($request);
    }
}
