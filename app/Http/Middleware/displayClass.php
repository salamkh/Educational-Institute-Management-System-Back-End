<?php

namespace App\Http\Middleware;

use App\Traits\User_roles;
use App\Traits\userAuthorizations;
use Closure;
use Illuminate\Http\Request;

class displayClass
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
        $employee = $this->administrativeEmployee();
        $canaddClass = $this->displayClass();
        $manager = $this->isManager();
        if($employee || $canaddClass||$manager){
            return $next($request);
        }
        else {
            return response(["message"=>"ليس لديك صلاحيات"]);
        }
    }
}
