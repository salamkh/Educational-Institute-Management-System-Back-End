<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Models\advance;
use App\Models\panchment;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class panchmentController extends Controller
{
    public function addPanchment (Request $request){
        $message = array(
            "userId.required"=>"رقم المستخدم مطلوب",
            "userId.numeric"=>"رقم المستخدم يتكون من أرقام فقط",
            "panchDate.required" => "تاريخ الخصم مطلوب",
            "panchDate.date"=>"حقل تاريخ الخصم يجب أن يكون تاريخ",
            "cause.required"=>"سبب الخصم مطلوب",
            "balance.required"=>"رصيد الخصم مطلوب",
            "balance.numeric"=>"رصيد الخصم يتكون من أرقام فقط",
            "status.required"=>"حالة العقوبة مطلوبة",
        );
        $validator = Validator::make(request()->all(), [
            "userId" => "required|numeric",
            "panchDate" => "required|date",
            "cause"=>"required",
            "status"=>"required",
            "balance"=>"required|numeric"
         ],$message);
  
         if ($validator->fails()) {
            $msg = (json_decode(json_encode( $validator->errors(),JSON_UNESCAPED_UNICODE),true));
            $string='';
          foreach (array_values($msg) as $value){
              $string .=  $value[0]." , ";
          }
              return response(["message"=>"$string"], 422); 
          }
        $user = User::find($request->userId);
        if(!$user){
            return response(["message"=>"فشل إضافة الخضم المستخدم غير موجود"]);
        }
        else{
        $panchment = new panchment();
        $panchment->userId = $request->userId;
        $panchment->panchDate = $request->panchDate;
        $panchment->cause = $request->cause;
        $panchment->balance = $request->balance;
        $panchment->status=$request->status;
        $add = $panchment->save();
        if($add){
            return response(["data"=>$panchment,"message"=>"تم إضافة الخصم بنجاح"]);
        }
        return response(["message"=>"فشل إضافة الخصم"]);
    }
    }
    public function updatePancment ($id , Request $request){
        $message = array(
            "panchDate.date"=>"حقل تاريخ الخصم يجب أن يكون تاريخ",
            "balance.numeric"=>"رصيد الخصم يتكون من أرقام فقط"
        );
        $validator = Validator::make(request()->all(), [
            "panchDate" => "date",
            "balance"=>"numeric"
         ],$message);
  
         if ($validator->fails()) {
            $msg = (json_decode(json_encode( $validator->errors(),JSON_UNESCAPED_UNICODE),true));
            $string='';
          foreach (array_values($msg) as $value){
              $string .=  $value[0]." , ";
          }
              return response(["message"=>"$string"], 422); 
          }
        $panchment = panchment::find($id);
        if($panchment){
            if($request->status!=null){
            $update = $panchment->update($request->except('status'));
            }
            else{
                $update = $panchment->update($request->all());
            }
            if($update){
                return response(["data"=>$panchment,"message"=>"تم تعديل الخصم بنجاح"]);
            }
            return response(["message"=>"فشل تعديل الخصم"]);
        }
        return response(["message"=>"فشل تعديل الخصم"]);
    }
    public function showUserPanchent($id){
        $user = DB::table('users')->select('name')->where('userId',$id)->get()->first();
        $panchment = panchment::where('userId',$id)->get();
        $data=[];
        if (sizeof($panchment)!=0){
            for ($i=0;$i<sizeof($panchment);$i++){
                $data[$i]=[
                    "userName"=>$user->name,
                    "advance"=>$panchment[$i]
                ];
            }
           
            return response(['data'=>$data,"message"=>"تم احضار البيانات بنجاح"]);
        }
        return response(["message"=>"لا يوجد بيانات لعرضها"]);
    }

    public function RangePanchments(Request $request){
        $message = array(
            "startDate.required"=>"تاريخ البداية مطلوب",
            "endDate.required" => "تاريخ النهاية مطلوب",
            "startDate.date" => "تاريخ البداية هو حقل تاريخ",
            "endDate.date" => "تاريخ النهاية هو حقل تاريخ"
        );
        $validator = Validator::make(request()->all(), [
           "startDate"=>"required|date",
           "endDate"=>"required|date"
         ],$message);
  
         if ($validator->fails()) {
            $msg = (json_decode(json_encode( $validator->errors(),JSON_UNESCAPED_UNICODE),true));
            $string='';
          foreach (array_values($msg) as $value){
              $string .=  $value[0]." , ";
          }
              return response(["message"=>"$string"], 422); 
          }

          $panchment = panchment::whereBetween('panchDate',[$request->startDate,$request->endDate])->orderBy('panchDate')->get();
          if(sizeof($panchment)!=0){
            $data=[];
            for ($i=0;$i<sizeof($panchment);$i++){
                $user = User::find($panchment[$i]->userId);
                $data[$i]=[
                    "userName"=>$user->name,
                    "advance"=>$panchment[$i]
                ];
            }
            return response(["data"=>$data,"message"=>"تم احضار البيانات بنجاح"]);
          }
          return response(["message"=>"لا يوجد بينانات لعرضها"]);
    }
    public function showUserPanchentInRange($id){
          $panchment = panchment::where('userId',$id)->where('status',"غير مطبقة")->orderBy('panchDate')->get();
          $data=[];
          if(sizeof($panchment)!=0){
            
            for ($i=0;$i<sizeof($panchment);$i++){
                $user = User::find($panchment[$i]->userId);
                $data[$i]=[
                    "userName"=>$user->name,
                    "advance"=>$panchment[$i]
                ];
            }
            return response(["data"=>$data,"message"=>"تم احضار البيانات بنجاح"]);
          }
          return response(["data"=>$data,"message"=>"لا يوجد بينانات لعرضها"]);
    
    }
    public function changePanchmentStatus($id){
          $panchment = panchment::find($id);
          if ($panchment){
           if ($panchment->status=="مطبقة"){
            $panchment->status="غير مطبقة";
           }
           else{
            $panchment->status="مطبقة";
           }
            $panchment->update();
            return response(["message"=>"تم تعديل الحالة بنجاح"]);
          }
          return response(["message"=>"فشل عملية التعديل لم يتم ايجاد العقوبة"]);
    }
}
