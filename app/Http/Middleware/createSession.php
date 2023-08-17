<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Traits\User_roles;
use App\Traits\userAuthorizations;
use Tymon\JWTAuth\Facades\JWTAuth;

class createSession
{use User_roles,userAuthorizations;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $HR= $this->isHR();
        $manager = $this->isManager();
        $teacher=$this->isTeacher($request);
        $createSession=$this->createSession();
        if($HR||$manager||$teacher||$createSession)
        {
            return $next($request);
        }
    }
}
