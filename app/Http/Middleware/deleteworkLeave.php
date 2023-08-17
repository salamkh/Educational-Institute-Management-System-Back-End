<?php

namespace App\Http\Middleware;

use App\Models\workleave;
use Closure;
use Illuminate\Http\Request;
use App\Traits\User_roles;
use App\Traits\userAuthorizations;
use Tymon\JWTAuth\Facades\JWTAuth;

class deleteworkLeave
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
        $deleteWorkLeave = $this->deleteWorkLeave();

        if($hr==true||$manager==true||$deleteWorkLeave==true)
        {
            $worlLeave = workleave::find($request->route('id'));
            if($worlLeave && $worlLeave->type=="ساعية"){
              if(strtotime(date("Y-m-d"))<=strtotime($worlLeave->startDate))
                { 
                    $time = date("H",strtotime($worlLeave->startTime));
                $now = date("H");
                if($now>$time){
                    return response(["message"=>"لا يمكن حذف الإجازة الساعية بعد انقضاء الساعة الأولى منها"]);
                }
                else{
                    return $next($request);
                }
            }
            return response(["message"=>"لا يمكن حذف الإجازة الساعية بعد تاريخها"]);

            } 
            else if($worlLeave && $worlLeave->type=="أيام"){
                if(strtotime(date("Y-m-d"))<=strtotime($worlLeave->startDate)){
                    return $next($request);
                }
                return response(["message"=>"لا يمكن حذف الإجازة بعد تاريخها"]);
            }
            return $next($request);
        }
        return response (["message"=>"ليس لديك صلاحيات"]);
    }
}
