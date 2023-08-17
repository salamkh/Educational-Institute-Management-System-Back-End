<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Traits\User_roles;
use App\Traits\userAuthorizations;

class showFinancialOperation
{
    use User_roles, userAuthorizations;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $isAccountant = $this->isAccountant();
        $canAdd = $this->addFinancialOperation();
        $canDel = $this->deleteFinancialOperation();
        $canShow = $this->showFinancialOperation();
        $isManager = $this->isManager();
        if ($isAccountant == true || $canAdd == true || $canDel == true ||  $canShow == true || $isManager == true) {
            return $next($request);
        }
        return response(["message" => "ليس لديك صلاحية للقيام بهذه العملية"]);
    }
}
