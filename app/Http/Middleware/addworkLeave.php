<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Traits\User_roles;
use App\Traits\userAuthorizations;
use Tymon\JWTAuth\Facades\JWTAuth;

class addworkLeave
{
    use userAuthorizations,User_roles;
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
        $addWorkLeave = $this->addWorkLeave();

        if($hr==true||$manager==true||$addWorkLeave==true)
        {
            if($request->type && $request->type == "ساعية"){
                if(strtotime(date("Y-m-d")) <= strtotime($request->startDate) && strtotime(date("H:i:s")) <= strtotime($request->startTime)){
                    return $next($request);
                }
                return response(["message"=>"يمكن إضافة الإجازة الساعية في نفس اليوم كحد أقصى و قبل وقت إجازة"]);
            }
            else if ($request->type && $request->type == "أيام"){
                if(strtotime(date("Y-m-d")) < strtotime($request->startDate)){
                    return $next($request);
                }
                return response(["message"=>"يمكن إضافة الإجازة الأيام قبل يوم الإجازة كحد أقصى"]);
            } 
        }
        return response (["message"=>"ليس لديك صلاحيات"]);
    }
}
