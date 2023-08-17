<?php

namespace App\Http\Middleware;

use App\Models\reward;
use Closure;
use Illuminate\Http\Request;
use App\Traits\User_roles;
use App\Traits\userAuthorizations;
use Tymon\JWTAuth\Facades\JWTAuth;

class updateReward
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
        $updateReward = $this->updateReward();
        $isManager = $this->isManager();
        if($hr==true||$updateReward==true||$isManager==true)
        {
            $reward = reward::find($request->route('id'));
            $rewardYear = date("Y",$reward->rewardDate);
            $date = date("Y");
            if(date("Y")!=$rewardYear){
                return response (["message"=>"أن يكون تاريخ التعديل تابع لنفس شهر المكافأة"]);
            }
            else{
                if(date("m") != date("m",$reward->rewardDate)){
                    return response (["message"=>"أن يكون تاريخ التعديل تابع لنفس شهر المكافأة"]);
                }
                else{
                    return $next($request);
                }
            }
            
        }
        return response (["message"=>"ليس لديك صلاحيات"]);
    }
}
