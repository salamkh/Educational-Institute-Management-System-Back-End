<?php

namespace App\Http\Middleware;

use App\Models\role;
use App\Models\userrole;
use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Traits\User_roles;
use App\Traits\userAuthorizations;

class hrManager extends User_roles
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    /*
    يجب أن يكون المستخدم مدير أو مسؤول موارد بشرية
    */
    public function handle(Request $request, Closure $next)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $userRoles = userrole::where('userId',$user->userId)->get();
        $isHR = $this->isHR();
            $isManager =$this->isManager();
            if ($isHR==true || $isManager==true)
           { return $next($request);}
           return response("UnAuthinticated");
        }
}
