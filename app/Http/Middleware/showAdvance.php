<?php

namespace App\Http\Middleware;

use App\Models\role;
use App\Models\userrole;
use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\teacher;
use App\Traits\User_roles;
use App\Traits\userAuthorizations;

class showAdvance
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
        $manager = $this->isManager();
        $hr = $this->isHR();
        $showAdvances = $this->showAdvance();
        $editAdvance = $this->updateAdvance();

        if($hr==true||$showAdvances==true||$manager==true||$editAdvance==true||$accountant==true)
        {
            return $next($request);
        }
        return response (["message"=>"ليس لديك صلاحيات"]);
    }
}
