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

class show_time_monitoring
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
        $timeMonitor = $this->timeMonitor();
        $manager = $this->isManager();
        $showTimeMonitoring = $this->showTimeMonitoring();
        $editTimeMonitoring = $this->editTimeMonitoring();
        $deleteTimeMonitoring = $this->deleteTimeMonitoring();
        if($timeMonitor==true||$showTimeMonitoring==true||$manager==true||$editTimeMonitoring==true||$deleteTimeMonitoring==true)
        {
            return $next($request);
        }
        return response (["message"=>"ليس لديك صلاحيات"]);
    }
}
