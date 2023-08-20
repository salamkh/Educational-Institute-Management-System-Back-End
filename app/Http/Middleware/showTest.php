<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Traits\User_roles;
use App\Traits\userAuthorizations;
use Tymon\JWTAuth\Facades\JWTAuth;


class showTest
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
        $user = auth()->guard('studentapi')->user();
        $id = $request->route('studentId');
        if($user){
            if($user->studentId==$id)
            {return $next($request);}
            else{
                return response (["message"=>"ليس لديك صلاحيات"]);
            }
        }
        $HR= $this->isHR();
        $manager = $this->isManager();
        $showTest=$this->showTest();
        $teacher=$this->isTeacher($request);
        if($HR||$manager||$showTest||$teacher)
        {
            return $next($request);
        }
        return response (["message"=>"ليس لديك صلاحيات"]);
    }
}
