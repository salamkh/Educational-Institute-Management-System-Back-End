<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Traits\User_roles;
use App\Traits\userAuthorizations;

class deleteFinancialOperation
{
    use userAuthorizations, User_roles;
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
        $isManager = $this->isManager();
        $canDel = $this->deleteFinancialOperation();
        if ($isAccountant == true || $canDel == true || $isManager==true) {
            return $next($request);
        }
        return response(["message" => "ليس لديك صلاحية للقيام بهذه العملية"]);
    }
}
