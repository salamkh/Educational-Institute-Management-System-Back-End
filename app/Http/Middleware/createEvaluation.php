<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

use App\Traits\User_roles;
use App\Traits\userAuthorizations;
use Tymon\JWTAuth\Facades\JWTAuth;


class createEvaluation
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
        $teacher = $this->isTeacher($request);
        $manager = $this->isManager();
        $createEvaluation = $this->createEvaluation();
        if($teacher||$createEvaluation||$manager)
        {
            return $next($request);
        }
        return response (["message"=>"ليس لديك صلاحيات"]);

    }
}
