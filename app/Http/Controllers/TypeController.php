<?php

namespace App\Http\Controllers;

use App\Models\financialAccounts;
use App\Models\financialTypeAccount;
use App\Models\subject;
use App\Models\SubjectType;
use App\Models\Type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TypeController extends Controller
{

    public function create(Request $request)
    {

        $type =  Type::insertGetId([
            'name' => $request->name
        ]);
        if ($type) {
            DB::beginTransaction();
            $financialAccount =  financialAccounts::insertGetId([
                'status' => "إيرادات",
                'accountName' => $type . "-" . $request->name,
                'balance' => 0
            ]);
            if ($financialAccount) {
                $typeAccount = new financialTypeAccount();
                $typeAccount->typeId = $type;
                $typeAccount->FAId = $financialAccount;
                $typeAccount->save();
                DB::commit();
            } else {
                DB::rollBack();
            }
            $type = type::find($type);
            return response(
                [
                    'Type' => $type,
                    'message' => 'تمت إضافة النوع بنجاح',
                ],
                200
            );
        }
    }

    public function showSubjectsInType($id)
    {

        $subjectType = SubjectType::where('typeId', $id)->get();
        $subject = null;
        $subject;
        for ($i = 0; $i < sizeof($subjectType); $i++) {
            $subject[$i] = Subject::where('sId', $subjectType[$i]->subjectId)->get()->first();
        }
        if (!$subject) {
            $array = [];
            return response([
                'Subject' => $array,
                'message' => 'لا يوجد معلومات مطابقة لهذا العرض',
            ]);
        } else
            return response(
                [
                    'subject' => $subject,
                    'message' => 'تمت عملية العرض بنجاح',
                ]
            );
    }
    public function show($id)
    {
        $type = Type::find($id);
        if (!$type) {
            $array = [];
            return response([
                'Type' => $array,
                'message' => 'لا يوجد معلومات مطابقة لهذا العرض',
            ]);
        } else
            return response(
                [
                    'Type' => $type,
                    'message' => 'تمت عملية العرض بنجاح',
                ]
            );
    }
    public function showAllTypes()
    {
        $type = Type::get();
        if (!$type) {
            $array = [];
            return response([
                'Types' => $array,
                'message' => 'لا يوجد معلومات مطابقة لهذا العرض',
            ]);
        } else
            return response(
                [
                    'Types' => $type,
                    'message' => 'تمت عملية العرض بنجاح',
                ]
            );
    }


    public function edit($id, Request $request)
    {
        $type = Type::where('typeId', $id)->get()->first();
        $type->name = $request->name;
        $type->save();
        return response([
            'Type' => $type,
            'message' => 'تمت عملية تعديل النوع بنجاح',
        ], 200);
    }
    public function destroy($id)
    {
        $type = Type::find($id);

        if (!$type) {
            $array = [];
            return response($array, 404);
        } else {

            $array = [
                'message' => "تم حذف النوع"
            ];
        }
        $type->delete();
        return response([
            'type' => $array,
            'message' => "تم الحذف بنجاح"
        ]);
    }
    public function search($name)
    {
        $type = Type::where('name', 'like', '%' . $name . '%')->get();
        if (!$type) {
            $array = [];
            return response($array);
        }
        return response($type);
    }
}
