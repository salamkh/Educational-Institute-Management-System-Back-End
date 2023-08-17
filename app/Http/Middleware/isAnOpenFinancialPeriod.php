<?php

namespace App\Http\Middleware;

use App\Models\financialPeriod;
use Closure;
use Illuminate\Http\Request;

class isAnOpenFinancialPeriod
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
        $pass=false;
        for ($i=0;$i<sizeof($finacialPeriods);$i++){
            if($finacialPeriods[$i]->status == "مفتوحة"){
                    $pass=true;
            }
        }
        if($pass == true){
        return $next($request);
        }
        return response(["message"=>"لا يمكنك القيام بأي عملية في حال عدم وجود أي دورة مفتوحة"]);
    }
}
