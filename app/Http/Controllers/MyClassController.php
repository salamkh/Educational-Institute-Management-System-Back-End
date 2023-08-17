<?php

namespace App\Http\Controllers;

use App\Models\MyClass;
use Illuminate\Http\Request;

class MyClassController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createMyClass(Request $request)
    {
        $myClass = new MyClass();
        $myClass->name = $request->name;
        $myClass->save();
       // return response()->json($myClass);

        return response([
            'Class' => $myClass,
            'message' => 'تمت إضافة الفئة بنجاح',
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }
    public function getCourseStatus(Request $request)
    {


    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $myClass = MyClass::find($id);
        if (!$myClass) {
            $array = [];
            return response([
                'Class' => $array,
                'message' => 'لا يوجد معلومات مطابقة لهذا العرض',
            ]);

        }
        else
        return response(
            [
                'Class' => $myClass,
                'message' => 'تمت عملية العرض بنجاح',
            ]
            );
    }
    public function showAllClasses(){
        $myClass = MyClass::get();
        if (!$myClass) {
            $array = [];
            return response([
                'Class' => $array,
                'message' => 'لا يوجد معلومات مطابقة لهذا العرض',
            ]);

        }
        else
            return response(
                [
                    'Class' => $myClass,
                    'message' => 'تمت عملية العرض بنجاح',
                ]
            );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id,Request $request)
    {
        $myClass=MyClass::where('classId',$id)->get()->first();
        //$myClass=MyClass::findOrFill($id);
        $myClass->classId=$request->classId;
        $myClass->name=$request->name;
        $myClass->save();
        return response([
            'Class' => $myClass,
            'message' => 'تمت عملية تعديل الفئة بنجاح',
        ], 200);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $myClass = MyClass::find($id);

        if (!$myClass) {
            $array = [];
            return response($array, 404);
        } else {

            $array = [

                'message' => "تم حذف الفئة"
            ];
        }
        $myClass->delete();
        return response($array);

    }


    public function search($name)
        {
            $myClass =MyClass::where('name', 'like', '%' . $name . '%')->get();
            if (!$myClass) {
                $array = [];
                return response($array);
            }
            return response($myClass);

        }

}
