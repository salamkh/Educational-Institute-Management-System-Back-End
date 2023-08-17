<?php

namespace App\Http\Controllers;

use App\Models\Advertisment;
use App\Models\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class AdvertismentController extends Controller
{
    public function create(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $advertisment =new Advertisment() ;
        $advertisment->userId = $user->userId;
        $advertisment->advertismentContent = $request->advertismentContent;
        $advertisment->type = $request->type;
        $advertisment->date = $request->date;
        $advertisment->save();


        return response
        (
            [
                'Advertisment' => $advertisment,
                'message' => 'تمت إضافة الإعلان بنجاح',
            ]
            , 200
        );
    }

    public function show($id)
    {
        $advertisment = Advertisment::find($id);
        if (!$advertisment) {
            $array = [];
            return response([
                'Advertisment' => $array,
                'message' => 'لا يوجد معلومات مطابقة لهذا العرض',
            ]);

        }
        else
            return response(
                [
                    'Advertisment' => $advertisment,
                    'message' => 'تمت عملية العرض بنجاح',
                ]
            );

    }

    public function edit($id,Request $request)
    {

        $advertisment=Advertisment::where('advertisementId',$id)->get()->first();
        $advertisment->update($request->all());

        return response([
            'Advertisment' => $advertisment,
            'message' => 'تمت عملية تعديل الإعلان بنجاح',
        ], 200);

    }
    public function showAllAdvertisment()
    {
        $advertisment = Advertisment::get();
        if (!$advertisment) {
            $array = [];
            return response([
                'Advertisment' => $array,
                'message' => 'لا يوجد معلومات مطابقة لهذا العرض',
            ]);

        }
        else{
            for($i=0;$i<sizeof($advertisment);$i++){
                $user = User::find($advertisment[$i]->userId);
            $advertisment[$i]->user = $user->name;
            }
            return response(
                [
                    'Advertisment' => $advertisment,
                    'message' => 'تمت عملية العرض بنجاح',
                ]
            );
        }    
    }

    public function destroy($id)
    {
        $advertisment = Advertisment::find($id);

        if (!$advertisment) {
            $array = [];
            return response($array, 404);
        } else {

            $array = [
                'message' => "تم حذف الإعلان"
            ];
        }
        $advertisment->delete();
        return response([
            'Advertisment'=>$array,
            'message'=>"تم الحذف بنجاح"]);

    }




}
