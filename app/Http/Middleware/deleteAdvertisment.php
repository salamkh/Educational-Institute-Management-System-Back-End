<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Traits\User_roles;
use App\Traits\userAuthorizations;
use Tymon\JWTAuth\Facades\JWTAuth;

class deleteAdvertisment
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
        $deleteAdvertisment=$this->deleteAdvertisment();
        if($HR||$manager||$teacher||$deleteAdvertisment)
        {
            return $next($request);
        }
    }
}
