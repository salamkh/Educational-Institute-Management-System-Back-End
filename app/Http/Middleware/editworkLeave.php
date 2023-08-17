<?php

namespace App\Http\Middleware;

use App\Models\workleave;
use Closure;
use Illuminate\Http\Request;
use App\Traits\User_roles;
use App\Traits\userAuthorizations;
use Tymon\JWTAuth\Facades\JWTAuth;

class editworkLeave
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
        $editWorkLeave = $this->updateWorkLeave();
        if($hr==true||$manager==true||$editWorkLeave==true)
        {
                    return $next($request);
        }
        return response (["message"=>"ليس لديك صلاحيات"]);
    }
}
