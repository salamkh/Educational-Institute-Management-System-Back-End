<?php

namespace App\Http\Middleware;

use App\Traits\User_roles;
use App\Traits\userAuthorizations;
use Closure;
use Illuminate\Http\Request;

class deleteStudent
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
        $hr = $this->isHR();
        $manager = $this->isManager();
        $deleteStudent = $this->deleteStudent();

        if($hr||$manager||$deleteStudent)
        {
            return $next($request);
        }
        return response (["message"=>"ليس لديك صلاحيات"]);

    }
}
