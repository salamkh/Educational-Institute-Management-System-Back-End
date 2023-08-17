<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\financialPeriod;

class extendFinancialPeriodValidation
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
            "endDate.required" => "تاريخ نهاية الدورة المالية مطلوب",
            "endDate.date" => "حقل تاريخ نهاية الدورة المالية يجب أن يكون تاريخ",
        );
        $validator = Validator::make(request()->all(), [
            "endDate" => "required|date",
        ], $message);

        if ($validator->fails()) {
            $msg = (json_decode(json_encode($validator->errors(), JSON_UNESCAPED_UNICODE), true));
            $string = '';
            foreach (array_values($msg) as $value) {
                $string .=  $value[0] . " , ";
            }
            return response(["message" => "$string"], 422);
        }
        $financialPeriod = financialPeriod::find($request->route('id'));
        if ($financialPeriod) {
            if ($financialPeriod->status == "مغلقة") {
                return response(["message" => "لا يمكن تمديد الدورة يجب أن تكون مفتوحة"]);
            }
            if (date('Y-m-d', strtotime($financialPeriod->endDate)) >= date('Y-m-d', strtotime($request->endDate))) {
                return response(["message" => "يجب أن يكون التاريخ الجديد أكبر من تاريخ نهاية الدورة"]);
            }
        }

        return $next($request);
    }
}
