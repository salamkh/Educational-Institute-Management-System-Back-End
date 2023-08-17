<?php
namespace App\Traits;

use App\Models\role;
use App\Models\teacher;
use App\Models\userrole;
use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

trait User_roles {

    public function isHR (){
        $hr = false ;
        $user = JWTAuth::parseToken()->authenticate();
        $userRoles = userrole::where('userId',$user->userId)->get();
        if (sizeof($userRoles)!=0){
            for ($i=0;$i<sizeof($userRoles);$i++){
                $role = role::find($userRoles[$i]->roleId);
                if($role->role == "مسؤول الموارد البشرية"){
                    $hr=true;
                    break;
                }
            }
        }
        return $hr;
    }
    public function isManager(){
        $manager = false ;
        $user = JWTAuth::parseToken()->authenticate();
        $userRoles = userrole::where('userId',$user->userId)->get();
        if (sizeof($userRoles)!=0){
            for ($i=0;$i<sizeof($userRoles);$i++){
                $role = role::find($userRoles[$i]->roleId);
                if($role->role == "مدير"){
                    $manager=true;
                    break;
                }
            }
        }
        return $manager;
    }
    public function isAccountant(){
        $accountant = false ;
        $user = JWTAuth::parseToken()->authenticate();
        $userRoles = userrole::where('userId',$user->userId)->get();
        if (sizeof($userRoles)!=0){
            for ($i=0;$i<sizeof($userRoles);$i++){
                $role = role::find($userRoles[$i]->roleId);
                if($role->role == "محاسب"){
                    $accountant=true;
                    break;
                }
            }
        }
        return $accountant; 
    }
    public function isTeacher (Request $request){
        $user = JWTAuth::parseToken()->authenticate();
        $isTeacher = false;
        if ($request->route('id')!=null)
            {
                $teacher = teacher::find($request->route('id'));
                if ($teacher){
                    if ($teacher->userId == $user->userId){
                        $isTeacher = true;
                    }
                }
            }
            return $isTeacher;
    }
    public function administrativeEmployee(){
        $employee = false ;
        $user = JWTAuth::parseToken()->authenticate();
        $userRoles = userrole::where('userId',$user->userId)->get();
        if (sizeof($userRoles)!=0){
            for ($i=0;$i<sizeof($userRoles);$i++){
                $role = role::find($userRoles[$i]->roleId);
                if($role->role == "موظف إداري"){
                    $employee=true;
                    break;
                }
            }
        }
        return $employee; 
    }
    public function timeMonitor(){
        $timeMonitor = false ;
        $user = JWTAuth::parseToken()->authenticate();
        $userRoles = userrole::where('userId',$user->userId)->get();
        if (sizeof($userRoles)!=0){
            for ($i=0;$i<sizeof($userRoles);$i++){
                $role = role::find($userRoles[$i]->roleId);
                if($role->role == "مراقب الدوام"){
                    $timeMonitor=true;
                    break;
                }
            }
        }
        return $timeMonitor; 
    }
    public function myID (Request $request){
        $myId = false;
        $user = JWTAuth::parseToken()->authenticate();
        if ($request->route('id')!=null)
        {
        if ($user->userId == $request->route('id'))
        $myId = true;
        }
        return $myId;
    }
}