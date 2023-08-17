<?php

namespace App\Http\Controllers;

use App\Models\Calender;
use Illuminate\Http\Request;

class CalenderController extends Controller
{
    public function create(Request $request)
    {
        $calender =new Calender() ;
        $calender->note = $request->note;
        $calender->date = $request->date;
        $calender->save();
        return response
        (
            [
                'Calender' => $calender,
                'message' => 'تمت إضافة الملاحظة بنجاح',
            ]
            , 200
        );
    }

    public function edit($id,Request $request)
    {

        $calender=Calender::where('calenderId',$id)->get()->first();
        $calender->update($request->all());

        return response([
            'Calender' => $calender,
            'message' => 'تمت عملية تعديل الملاحظة بنجاح',
        ], 200);

    }
    public function destroy($id)
    {
        $calender = Calender::find($id);

        if (!$calender) {
            $array = [];
            return response($array, 404);
        } else {

            $array = [
                'message' => "تم حذف الملاحظة"
            ];
        }
        $calender->delete();
        return response([
            'Calender'=>$array,
            'message'=>"تم الحذف بنجاح"]);
    }
    public function showAllCalenders()
    {
        $calender = Calender::get();
        if (!$calender) {
            $array = [];
            return response([
                'Calender' => $array,
                'message' => 'لا يوجد معلومات مطابقة لهذا العرض',
            ]);

        }
        else
            return response(
                [
                    'Calenders' => $calender,
                    'message' => 'تمت عملية العرض بنجاح',
                ]
            );

    }

    public function show($date)
    {
        $calender = Calender::where('date',$date)->get()->first();
        if (!$calender) {
            $array = [];
            return response([
                'Calender' => $array,
                'message' => 'لا يوجد معلومات مطابقة لهذا العرض',
            ]);

        }
        else
            return response(
                [
                    'Calender' => $calender,
                    'message' => 'تمت عملية العرض بنجاح',
                ]
            );
    }


}
