<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\authorization;
use App\Models\userauth;
use Illuminate\Support\Facades\Validator;
class authorizationsController extends Controller
{
    public function getAuthorizations (){
        $authorization = authorization::get();
        if(sizeof($authorization)==0){
            return response(["message"=>"لا يوجد صلاحيات لعرضها"]);
        }
        return response(["authorizations"=>$authorization,"message"=>"تم جلب الصلاحيات بنجاح"]);
    }
    public function getUserAuth ($id){
        $auths = userauth::where('userId',$id)->get();
        $authIds=[];
        $userAuths=[];
        if (sizeof($auths)!=0){
            for ($i=0;$i<sizeof($auths);$i++){
                $authIds[$i]=$auths[$i]->aId;
            }
            for($j=0;$j<sizeof($authIds);$j++)
          {
            $userAuths[$j]= authorization::find($authIds[$j]);
        }
        return response(["userAuth"=>$userAuths,"message"=>"تم جلب صلاحيات المستخدم بنجاح"]);
        }
        else {
            return response(["message"=>"لا يملك المستخدم أي سماحيات إضافية"]);
        }
    }
}
