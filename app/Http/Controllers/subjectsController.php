<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Models\subject;
use App\Models\SubjectType;
use Illuminate\Http\Request;
use PDO;
use PhpParser\Node\Expr\FuncCall;
use App\Models\SubjectTeacher;
use App\Models\teacher;
use App\Models\User;

class subjectsController extends Controller
{
    public function addSubjectToType(Request $request)
    {
        $subject = new subject();
        $subject->name = $request->name;
        $subject->save();
        $subjectType = new SubjectType();
        $subjectType->typeId = $request->typeId;
        $subjectType->subjectId = $subject->sId;
        $subjectType->save();
        return response(["subject" => $subject, "message" => "تم اضافة المادة بنجاح"]);
    }
    public function getSubjectsTeacher($sujectId)
    {
        $subjectteachers = Subjectteacher::where('sId', $sujectId)->get();
        $teachers=[];
        for ($i = 0; $i < sizeof($subjectteachers); $i++) {
            $teachers[$i] = teacher::where('tId', $subjectteachers[$i]->tId)->get()->first();
            $teachers[$i]->name = User::find( $teachers[$i]->userId)->name;
        }
        return response( $teachers);

    }
    public function addSubject(Request $request)
    {
        $messages =  array(
            'name.required' => 'اسم المادة مطلوب'
        );
        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ], $messages);
        if ($validator->fails()) {
            $msg = (json_decode(json_encode($validator->errors(), JSON_UNESCAPED_UNICODE), true));
            $string = '';
            foreach (array_values($msg) as $value) {
                $string .=  $value[0] . " , ";
            }
            return response(["message" => "$string"], 422);
        }
        $subject = new subject();
        $subject->name = $request->name;
        $subject->save();
        return response(["subject" => $subject, "message" => "تم اضافة المادة بنجاح"]);
    }
    public function allSubjects()
    {
        $subject = subject::get();
        if (sizeof($subject) == 0) {
            return response(["message" => "لا يوجد مواد لعرضها"]);
        }
        return response(["subject" => $subject, "message" => "تم جلب المواد بنجاح"]);
    }
    public function editSubject($id, Request $request)
    {
        $messages =  array(
            'name.required' => 'اسم المادة مطلوب'
        );
        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ], $messages);
        if ($validator->fails()) {
            $msg = (json_decode(json_encode($validator->errors(), JSON_UNESCAPED_UNICODE), true));
            $string = '';
            foreach (array_values($msg) as $value) {
                $string .=  $value[0] . " , ";
            }
            return response(["message" => "$string"], 422);
        }
        $subject = subject::find($id);
        if ($subject) {
            $subject->name = $request->name;
            $subject->update();
            return response(['subject' => $subject, "message" => "تم التعديل بنجاح"]);
        }
        return response(["message" => "فشل عملية التعديل"]);
    }
    public function deleteSubject($id)
    {
        $subject = subject::find($id);
        if ($subject) {
            $subject->delete();
            return response(["message" => "تم حذف المادة بنجاح"]);
        }
        return response(["message" => "المادة غير موجودة"]);
    }
}
