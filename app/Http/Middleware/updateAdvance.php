<?php

namespace App\Http\Middleware;

use App\Models\advance;
use Closure;
use Illuminate\Http\Request;
use App\Traits\User_roles;
use App\Traits\userAuthorizations;
use Tymon\JWTAuth\Facades\JWTAuth;

class updateAdvance
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
        $updateAdvance = $this->updateAdvance();
        $isManager=$this->isManager();
        if($hr==true||$updateAdvance==true||$isManager==true)
        {
            $advance = advance::find($request->route('id'));
            $advanceYear = date("Y",strtotime($advance->advancedDate));
            if(date("Y")!=$advanceYear){
                return response (["message"=>"يجب أن يكون تاريخ التعديل تابع لنفس شهر السلفة"]);
            }
            else{
                if(date("m")!=date("m",strtotime($advance->advancedDate))){
                    return response (["message"=>"يجب أن يكون تاريخ التعديل تابع لنفس شهر السلفة"]);
                }
                else{
                    return $next($request);
                }
            }
        }
        return response (["message"=>"ليس لديك صلاحيات"]);
    }
}
