<?php

namespace App\Http\Middleware;

use App\Models\financialAccounts;
use App\Models\financialPeriod;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class addFinancialOperationValidation
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
        $messages =  array(
            'operationDate.required' => 'حقل تاريخ العملية مطلوب',
            'operationDate.date' => 'حقل تاريخ العملية يجب أن يكون تاريخ',
            'balance.required' => 'حقل رصيد العملية مطلوب',
            'balance.numeric' => 'حقل رصيد العملية يجب أن يكون رقم',
            'description.required' => 'حقل وصف تفاصيل العملية مطلوب',
            'creditorId.required' => 'رقم تعريف الحساب الدائن مطلوب',
            'creditorId.numeric' => 'رقم تعريف الحساب الدائن يجب أن يكون رقم',
            'debtorId.required' => 'رقم تعريف الحساب المدين مطلوب',
            'debtorId.numeric' => 'رقم تعريف الحساب المدين يجب أن يكون رقم',
        );
        $validator = Validator::make(request()->all(), [
            "operationDate" => "required|date",
            "description" => "required",
            "balance" => "required|numeric",
            "creditorId" => "required|numeric",
            "debtorId" => "required|numeric",
        ], $messages);

        if ($validator->fails()) {
            $msg = (json_decode(json_encode($validator->errors(), JSON_UNESCAPED_UNICODE), true));
            $string = '';
            foreach (array_values($msg) as $value) {
                $string .=  $value[0] . " , ";
            }
            return response(["message" => "$string"], 422);
        }
        $financialPeriod = financialPeriod::where('status',"مفتوحة")->get()->first();
        if(!(strtotime($request->operationDate)>=strtotime($financialPeriod->startDate) && strtotime($request->operationDate)<=strtotime($financialPeriod->endDate) )){
            return response(["message"=>$financialPeriod->startDate." & ".$financialPeriod->endDate." : "."يجب أن يكون تاريخ العملية ضمن تاريخ بداية و نهاية الدورة المفتوحة أي بين القيميتين"]);
        }
        $financialAccount = financialAccounts::find($request->creditorId);
        if(!$financialAccount){
            return response(["message"=>"لم يتم العثور على الحساب الدائن"]);
        }
        $financialAccount = financialAccounts::find($request->debtorId);
        if(!$financialAccount){
            return response(["message"=>"لم يتم العثور على الحساب المدين"]);
        }
        return $next($request);
    }
}
