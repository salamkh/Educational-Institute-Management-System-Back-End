<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Traits\User_roles;
use App\Traits\userAuthorizations;
class addType
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
        $canaddType = $this->addType();
        $manager= $this->isManager($request);
        if($employee || $canaddType||$manager){
        return $next($request);
        }
        else {
            return response(["message"=>"ليس لديك صلاحيات"]);
        }
    }
}
