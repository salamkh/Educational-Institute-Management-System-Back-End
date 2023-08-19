<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Models\advance;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class advanceController extends Controller
{
    public function addAdvance (Request $request){
        $message = array(
            "userId.required"=>"رقم المستخدم مطلوب",
            "userId.numeric"=>"رقم المستخدم يتكون من أرقام فقط",
            "advancedDate.required" => "تاريخ المنح مطلوب",
            "advancedDate.date"=>"حقل تاريخ السلفة يجب أن يكون تاريخ",
            "cause.required"=>"سبب السلفة مطلوب",
            "balance.required"=>"رصيد السلفة مطلوب",
            "balance.numeric"=>"رصيد السلفة يتكون من أرقام فقط",
            "status.required"=>"حالة السلفة مطلوبة"
        );
        $validator = Validator::make(request()->all(), [
            "userId" => "required|numeric",
            "advancedDate" => "required|date",
            "cause"=>"required",
            "balance"=>"required|numeric",
            "status"=>"required"
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
            return response(["message"=>"فشل إضافة السلفة المستخدم غير موجود"]);
        }
        $advance = new advance();
        $advance->userId = $request->userId;
        $advance->advancedDate = $request->advancedDate;
        $advance->cause = $request->cause;
        $advance->balance = $request->balance;
        $advance->status = $request->status;
        $add = $advance->save();
        if($add){
            return response(["data"=>$advance,"message"=>"تم إضافة السلفة بنجاح"]);
        }
        return response(["message"=>"فشل إضافة السلفة"]);
    }

    public function updateAdvance($id,Request $request){
        $message = array(
            "advancedDate.date"=>"حقل تاريخ السلفة يجب أن يكون تاريخ",
            "balance.numeric"=>"رصيد السلفة يتكون من أرقام فقط"
        );
        $validator = Validator::make(request()->all(), [
            "advancedDate" => "date",
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
        $advance = advance::find($id);
        if($advance){
            if ($request->status!=null){
            $update = $advance->update($request->except('status'));
            }else{
                $update = $advance->update($request->all());
            }
            if($update){
                return response(["data"=>$advance,"message"=>"تم تعديل السلفة بنجاح"]);
            }
            return response(["message"=>"فشل تعديل السلفة"]);
        }
        return response(["message"=>"فشل تعديل السلفة"]);
    }
    public function showUserAdvances($id){
        $advance = advance::where('userId',$id)->get();
        $user = DB::table('users')->select('name')->where('userId',$id)->get()->first();
        $data=[];
        for ($i=0;$i<sizeof($advance);$i++){
           
            $data[$i]=[
                "userName"=>$user->name,
                "advance"=>$advance[$i]
            ];
        }
        if (sizeof($advance)!=0){
            return response(['data'=>$data,"message"=>"تم احضار البيانات بنجاح"]);
        }
        return response(["message"=>"لا يوجد بيانات لعرضها"]);
    }
    public function RangeAdvances(Request $request){
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

          $advance = advance::whereBetween('advancedDate',[$request->startDate,$request->endDate])->orderBy('advancedDate')->get();
          if(sizeof($advance)!=0){
            $data=[];
            for ($i=0;$i<sizeof($advance);$i++){
                $user = User::find($advance[$i]->userId);
                $data[$i]=[
                    "userName"=>$user->name,
                    "advance"=>$advance[$i]
                ];
            }
            return response(["data"=>$data,"message"=>"تم احضار البيانات بنجاح"]);
          }
          return response(["message"=>"لا يوجد بينانات لعرضها"]);
    }
    public function showUserAdvancesInRange($id){
          $advance = advance::where('userId',$id)->where('status',"مدفوعة")->orderBy('advancedDate')->get();
          $data=[];
          if(sizeof($advance)!=0){
            
            for ($i=0;$i<sizeof($advance);$i++){
                $user = User::find($advance[$i]->userId);
                $data[$i]=[
                    "userName"=>$user->name,
                    "advance"=>$advance[$i]
                ];
            }
            return response(["data"=>$data,"message"=>"تم احضار البيانات بنجاح"]);
          }
          return response(["data"=>$data,"message"=>"لا يوجد بينانات لعرضها"]);
    }
    public function changeAdvanceStatus($id , Request $request){
        $message = array(
            "status.required"=>"حالة السلفة مطلوبة"
        );
        $validator = Validator::make(request()->all(), [
            "status"=>"required"
         ],$message);
         if ($validator->fails()) {
            $msg = (json_decode(json_encode( $validator->errors(),JSON_UNESCAPED_UNICODE),true));
            $string='';
          foreach (array_values($msg) as $value){
              $string .=  $value[0]." , ";
          }
              return response(["message"=>"$string"], 422); 
          }
          if ($request->status != "مدفوعة" && $request->status != "مستردة"){
            return response(["message"=>"حالة السلفة يجب أن تكون مدفوعة أو مستردة"], 422); 
          }
          $advance = advance::find($id);
          if ($advance){
            $advance->status=$request->status;
            $advance->update();
            return response(["message"=>"تم تعديل الحالة بنجاح"]);
          }
          return response(["message"=>"فشل عملية التعديل"]);
    }
}
