<?php

namespace App\Http\Middleware;

use App\Models\financialOperations;
use App\Models\financialPeriod;
use Closure;
use Illuminate\Http\Request;

class ensureClosedCond
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
        $finacialPeriods = financialPeriod::get();
        $pass=true;
        for ($i=0;$i<sizeof($finacialPeriods);$i++){
            if($finacialPeriods[$i]->status == "مفتوحة"){
                $endDate = strtotime($finacialPeriods[$i]->endDate);
                $now = strtotime(date("Y-m-d"));
                if($now > $endDate){
                    $pass=false;
                }
            }
        }
        if($pass == true){
        return $next($request);
        }
        return response(["message"=>"لا يمكنك القيام بأي عملية قبل اغلاق الدورة الحالية"]);
    }
}
