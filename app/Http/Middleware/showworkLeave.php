<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Traits\User_roles;
use App\Traits\userAuthorizations;
use Tymon\JWTAuth\Facades\JWTAuth;

class showworkLeave
{
    use userAuthorizations , User_roles;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $hr = $this->isHR();
        $manager = $this->isManager();
        $showWorkLeave = $this->showWorkLeave();
        $editWorkLeave = $this->updateWorkLeave();
        $deleteWorkLeave = $this->deleteWorkLeave();

        if($hr==true||$showWorkLeave==true||$manager==true||$editWorkLeave==true||$deleteWorkLeave==true)
        {
            return $next($request);
        }
        return response (["message"=>"ليس لديك صلاحيات"]);
    }
}
