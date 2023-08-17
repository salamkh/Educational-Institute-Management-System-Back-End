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

class showSubjectsMiddleware
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
        $isManager = $this->isManager();
        $showSubjects = $this->showSubjects();
        $editSub = $this->editSubjects();
        $deleteSub = $this->deleteSubjects();
        if ( $isManager==true || $showSubjects==true || $editSub==true || $deleteSub == true){
            return $next($request);
        }
        return response (["message"=>"ليس لديك صلاحيات"]);
        }
}
