<?php

namespace App\Http\Middleware;

use App\Models\panchment;
use Closure;
use Illuminate\Http\Request;
use App\Traits\User_roles;
use App\Traits\userAuthorizations;
use Tymon\JWTAuth\Facades\JWTAuth;

class updatePanchment
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
        $hr = $this->isHR();
        $updatePanchment = $this->updatepanchment();
        $isManager=$this->isManager();
        if($hr==true||$updatePanchment==true||$isManager==true)
        {
            $panchment = panchment::find($request->route('id'));
            $panchmentYear = date("Y",strtotime($panchment->panchDate));
            if(date("Y")!=$panchmentYear){
                return response (["message"=>"يجب أن يكون تاريخ التعديل تابع لنفس شهر الحسم"]);
            }
            else{
                if(date("m")!=date("m",strtotime($panchment->panchDate))){
                    return response (["message"=>"يجب أن يكون تاريخ التعديل تابع لنفس شهر الحسم"]);
                }
                else{
                    return $next($request);
                }
            }
        }
        return response (["message"=>"ليس لديك صلاحيات"]);
    }
}
