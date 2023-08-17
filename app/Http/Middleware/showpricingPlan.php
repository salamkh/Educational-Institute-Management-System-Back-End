<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Traits\User_roles;
use App\Traits\userAuthorizations;

class showpricingPlan
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
        $HR = $this->isHR();
        $add = $this->addPricingPlan();
        $edit = $this->editPricingPlan();
        $delete = $this->deletePricingPlan();
        $show = $this->showPricingPlan();
        if($isManager==true || $add==true || $delete==true || $edit==true || $show==true || $HR==true){
            return $next($request);
        }
        return response(["message"=>"ليس لديك صلاحية للقيام بهذه العملية"]);
    }
}
