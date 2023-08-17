<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Traits\User_roles;
use App\Traits\userAuthorizations;

class addFinancialAccount
{
    use User_roles,userAuthorizations;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $accountant = $this->isAccountant();
        $addAccount = $this->addFinancialAccount();
        $isManager = $this->isManager();
        if ($accountant==true || $addAccount==true||$isManager==true){
            return $next($request);
        }
        return response(["message"=>"ليس بديك صلاحية للقيام بهذه العملية"]);
        
    }
}
