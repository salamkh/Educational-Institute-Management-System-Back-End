<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\role;
use App\Models\userrole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class rolesController extends Controller
{
    public function getRoles (){
        $roles = role::get();
        if(sizeof($roles)==0){
            return response(["message"=>"لا يوجد أدوار لعرضها"]);
        }
        return response(["roles"=>$roles,"message"=>"تم جلب الادوار بنجاح"]);
    }
    public function getUserRole ($id){
        $roles = userrole::where('userId',$id)->get();
        $roleIds=[];
        $userRoles=[];
        if (sizeof($roles)!=0){
            for ($i=0;$i<sizeof($roles);$i++){
                $roleIds[$i]=$roles[$i]->roleId;
            }
            for($j=0;$j<sizeof($roleIds);$j++)
          {
            $userRoles[$j]= role::find($roleIds[$j]);
        }
        return response(["user_roles"=>$userRoles,"message"=>"تم جلب ادوار المستخدم بنجاح"]);
        }
        return response(["message"=>"لا يملك المستخدم ادوار لعرضها"]);
    }
}
