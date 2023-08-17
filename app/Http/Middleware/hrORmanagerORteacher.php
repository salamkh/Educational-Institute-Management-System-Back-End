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

class hrORmanagerORteacher  
{
    use User_roles , userAuthorizations;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */

     /*
        يجب أن يكون المستخدم مدير أو مسؤول موارد بشرية أو استاذ
     */
    public function handle(Request $request, Closure $next)
    {
        $isHR = $this->isHR() ;
        $isManager = $this->isManager();
        $isTeacher = $this->isTeacher($request);
        $addedusers = $this->addUser();
        $editusrs = $this->editUser();
        $data = ["ishr"=>$isHR
        ,"ismanager"=>$isManager,"isteacher"=>$isTeacher,
    "addusre"=>$addedusers,
"editusers"=>$editusrs];
        return response($data);
            if ($isHR==true || $isManager==true || $isTeacher==true)
           { return $next($request);}

           return response("UnAuthinticated");
        }
}
