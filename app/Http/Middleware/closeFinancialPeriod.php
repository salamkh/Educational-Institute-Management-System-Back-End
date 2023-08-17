<?php

namespace App\Http\Middleware;

use App\Models\financialPeriod;
use Closure;
use Illuminate\Http\Request;
use App\Traits\User_roles;
use App\Traits\userAuthorizations;

class closeFinancialPeriod
{
    use userAuthorizations,User_roles;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $isManager = $this->isManager();
        $isAccountant = $this->isAccountant();
        $closePeriod = $this->closeFinancialPeriod();
        if($isAccountant==true || $closePeriod==true || $isManager==true){////////
            $financialPeriod = financialPeriod::find($request->route('id'));
            if($financialPeriod)
           { if((strtotime(date("Y-m-d"))<strtotime($financialPeriod->endDate))){
                return response(["message"=>"لا يمكن إجراء عملية الإغلاق قبل تاريخ نهاية الدورة"]);
            }
            return $next($request);}
            else{
                return response(["message"=>"فشل عملية الاغلاق الدورة غير موجودة"]);
            }
        }
        return response(["message"=>"ليس لديك صلاحيات للقيام بهذه العملية"]);
    }
}
