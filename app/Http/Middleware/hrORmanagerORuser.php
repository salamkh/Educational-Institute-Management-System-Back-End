<?php

namespace App\Http\Middleware;

use App\Models\role;
use App\Models\userrole;
use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Traits\User_roles;

class hrORmanagerORuser extends User_roles
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */

     /*
  
     المستخدم الذي يطلب يجب أت يكون مدير أ مسؤول موارد بشرية أو يطلب معلوماته الشخصية 
     أي أن يتوافق ال 
     id
     الطلب مع المستخدم الذي يطلب
     */
    public function handle(Request $request, Closure $next)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $isMe = $this->myID($request);
        $isHR = $this->isHR();
        $isManager = $this->isManager();
          if ($isHR==true || $isManager==true || $isMe==true)
           { return $next($request);}
           return response("UnAuthinticated");
        }
}
