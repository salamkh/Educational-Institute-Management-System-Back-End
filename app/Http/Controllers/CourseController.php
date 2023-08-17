<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\MyClass;
use App\Models\subject;
use App\Models\subjectteacher;
use App\Models\teacher;
use App\Models\TeacherCourse;
use App\Models\Type;
use App\Models\User;
use Illuminate\Http\Request;

class CourseController extends Controller
{

    public function create(Request $request)
    {
        $course=new Course();
        $course->subjectId=$request->subjectId;
        $course->typeId=$request->typeId;
        $course->classId=$request->classId;
        $course->headlines=$request->headlines;
        $course->addElements=$request->addElements;
        $course->cost=$request->cost;
        if($request->maxNStudent){
        $course->maxNStudent=$request->maxNStudent;
        }
        else{
            $course->maxNStudent=30;
        }
        $course->sessionNumber=$request->sessionNumber;
        $course->courseDays=$request->courseDays;
        $course->startDate=$request->startDate;
        $course->endDate=$request->endDate;
        $course->startTime=$request->startTime;
        $course->duration=$request->duration;
        $course->room=$request->room;
        $course->courseStatus='لم تبدأ بعد';
        $course->save();
        $teacherCourse = new TeacherCourse();
        $teacherCourse->teacherId = $request->teacherId;
        $teacherCourse->courseId = $course->courseId;
        $teacherCourse->save();

        return response
        (
            [
                'course' => $course,
                'teacherCourse'=>$teacherCourse,
                'message' => 'تمت إضافة الدورة بنجاح',
            ]
            , 200
        );
    }


    public function startCourse($id)
    {
        $course=Course::where('courseId',$id)->get()->first();
        $course->courseStatus='مفتوحة';
        $course->save();

        return response([
            'Course' => $course,
            'message' => 'تمت عملية تحديد حالة الدورة بنجاح',
        ], 200);


    }
    public function closeCourse($id)
    {
        $course=Course::where('courseId',$id)->get()->first();
        $course->courseStatus='مغلقة';
        $course->save();

        return response([
            'Course' => $course,
            'message' => 'تمت عملية تحديد حالة الدورة بنجاح',
        ], 200);


    }
    public function show($id)
    {
        $course = Course::find($id);
        if (!$course) {
            $array = [];
            return response(
                [
                'Course' => $array,
                'message' => 'لا يوجد معلومات مطابقة لهذا العرض',
                ]
            );

        }
        else{
                $type=Type::find($course->typeId);
                $course->type=$type->name;
                $subject=subject::find($course->subjectId);
                $course->subject=$subject->name;
                $class=MyClass::find($course->classId);
                $course->class=$class->name;
                $teacher=TeacherCourse::where('courseId',$course->courseId )->get()->first();
                $teacher= teacher::find($teacher->teacherId );
                $teacher= User::find( $teacher->userId);
                $course->teacher=$teacher->name;

            return response(
                [
                    'Course' => $course,
                    'message' => 'تمت عملية العرض بنجاح',
                ]
            );
        }
    }
    public function showAllEnableCourse(){
        $course = Course::where('courseStatus','مفتوحة')->orWhere('courseStatus','لم تبدأ بعد')->get();

        if (!$course) {
            $array = [];
            return response([
                'Course' => $array,
                'message' => 'لا يوجد معلومات مطابقة لهذا العرض',
            ]);

        }
        else{
            for($i=0;$i<sizeof($course);$i++){
                $type=Type::find($course[$i]->typeId);
                $course[$i]->typeId=$type->name;
                $subject=subject::find($course[$i]->subjectId);
                $course[$i]->subjectId=$subject->name;
                $class=MyClass::find($course[$i]->classId);
                $course[$i]->classId=$class->name;
            }
            return response(
                [
                    'Course' => $course,
                    'message' => 'تمت عملية العرض بنجاح',
                ]
            );
        }

    }
    public function showAllCourse(){
        $course = Course::get();

        if (!$course) {
            $array = [];
            return response([
                'Course' => $array,
                'message' => 'لا يوجد معلومات مطابقة لهذا العرض',
            ]);

        }
        else{
            for($i=0;$i<sizeof($course);$i++){
                $type=Type::find($course[$i]->typeId);
                $course[$i]->typeId=$type->name;
                $subject=subject::find($course[$i]->subjectId);
                $course[$i]->subjectId=$subject->name;
                $class=MyClass::find($course[$i]->classId);
                $course[$i]->classId=$class->name;
            }
            return response(
                [
                    'Course' => $course,
                    'message' => 'تمت عملية العرض بنجاح',
                ]
            );
        }

    }


    public function edit($id,Request $request)
    {

        $course=Course::where('courseId',$id)->get()->first();
        $course->update($request->all());
        $teacherCourse =TeacherCourse::where('courseId',$id);
        $teacherCourse->teacherId = $request->teacherId;
       if($course)
       {
           $course->update($request->all());
           $teacherCourse =TeacherCourse::where('courseId',$id);

       }
       // $teacherCourse->save();
        return response([
            'Course' => $course,
            'message' => 'تمت عملية تعديل الدورة بنجاح',
        ], 200);

    }


    public function showCoursesInType($id){
        $course=Course::where('typeId',$id)->get();

        if (!$course) {
            $array = [];
            return response(
                [
                    'Course' => $array,
                    'message' => 'لا يوجد معلومات مطابقة لهذا العرض',
                ]
            );

        }
        else{
            for($i=0;$i<sizeof($course);$i++){
                $type=Type::find($course[$i]->typeId);
                $course[$i]->typeId=$type->name;
                $subject=subject::find($course[$i]->subjectId);
                $course[$i]->subjectId=$subject->name;
                $class=MyClass::find($course[$i]->classId);
                $course[$i]->classId=$class->name;
            }
            return response(
                [
                    'Course' => $course,
                    'message' => 'تمت عملية العرض بنجاح',
                ]
            );
        }

    }

    public function destroy($id)
    {
        $course = Course::find($id);

        if (!$course) {
            $array = [];
            return response($array, 404);
        } else {

            $array = [
                'message' => "تم حذف الدورة"
            ];
        }
        $course->delete();
        return response([
            'course'=>$array,
            'message'=>"تم الحذف بنجاح"]);

    }
}
