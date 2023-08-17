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

class addUsersMiddleware
{
    use User_roles , userAuthorizations;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $user = JWTAuth::parseToken()->authenticate();
        if ($user){
        $isHR = $this->isHR() ;
        $isManager = $this->isManager();
        $addedusers = $this->addUser();
        if ($isHR==true || $isManager==true || $addedusers==true){
            return $next($request);
        }
        return response (["message"=>"ليس لديك صلاحيات"]);
    }
    return response (["message"=>"ليس لديك صلاحيات"]);
    }
}
