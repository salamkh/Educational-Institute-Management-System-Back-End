<?php
namespace App\Http\Controllers;

use App\Models\corusestuno;
use App\Models\Student;
use App\Models\StudentCourse;
use App\Models\Course;
use App\Models\subject;
use App\Models\Type;
use App\Models\Test;
use App\Models\teacher;
use App\Models\User;
use App\Models\Evaluation;
use App\Models\financialAccounts;
use App\Models\financialOperations;
use App\Models\financialPeriod;
use App\Models\financialStudentAccount;
use App\Models\financialStudentOperations;
use App\Models\financialTypeAccount;
use App\Models\studentDepts;
use Illuminate\Http\Request;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{

    //StudentCourse
    public function addStudentToCourse(Request $request)
    {
        DB::beginTransaction();
        $student = Student::find($request->studentId);
        $studentA = financialStudentAccount::where('studentId', $student->studentId)->get()->first();
        $studentAccount = financialAccounts::find($studentA->FAId);
        if (!$studentA || !$studentAccount || !$student) {
            DB::rollBack();
            return response(["message" => "فشلت عملية الإضافة"]);
        }
        $studentCourse = new StudentCourse();
        $studentCourse->courseId = $request->courseId;
        $studentCourse->studentId = $request->studentId;
        $studentCourse->studentAccount = $studentAccount->FAId;
        $save = $studentCourse->save();
        if (!$save) {
            DB::rollBack();
            return response(["message" => "فشلت عملية الإضافة"]);
        }
        $course = Course::find($request->courseId);
        if ($course) {
            $financialPeriod = financialPeriod::where('status', "مفتوحة")->get()->first();
            if (!(strtotime(date("Y-m-d")) >= strtotime($financialPeriod->startDate) && strtotime(date("Y-m-d")) <= strtotime($financialPeriod->endDate))) {
                DB::rollBack();
                return response(["message" => $financialPeriod->startDate . " & " . $financialPeriod->endDate . " : " . "يجب أن يكون تاريخ العملية ضمن تاريخ بداية و نهاية الدورة المفتوحة أي بين القيميتين"]);
            }
            $student = Student::find($request->studentId);
            $type = Type::find($course->typeId);
            $studentA = financialStudentAccount::where('studentId', $student->studentId)->get()->first();
            $typeA = financialTypeAccount::where('typeId', $course->typeId)->get()->first();
            $studentAccount = financialAccounts::find($studentA->FAId);
            $subject = subject::find($course->subjectId);
            $typeAccount = financialAccounts::find($typeA->FAId);
            $studentDepts = studentDepts::where('typeId', $typeAccount->FAId)->where('studentId', $studentAccount->FAId)->get()->first();
            if (!$course || !$type || !$studentA || !$studentAccount || !$typeA || !$subject || !$typeAccount) {
                DB::rollBack();
                return response(["message" => "فشلت عملية الإضافة"]);
            }
            if ($studentDepts) {
                $financialOperation = financialOperations::insertGetId([
                    'creditorId' => $typeAccount->FAId,
                    'creditorName' => $typeAccount->accountName,
                    'debtorId' => $studentAccount->FAId,
                    'debtorName' => $studentAccount->accountName,
                    'debtorBalance' => $studentAccount->balance,
                    'creditorBalance' => $typeAccount->balance,
                    'balance' => $course->cost,
                    'operationDate' => date("Y-m-d"),
                    'description' => " تسجيل الطالب في الدورة بمادة " . $subject->name,
                ]);
                if ($financialOperation) {
                    $studentAccount->balance = $studentAccount->balance - $course->cost;
                    if ($studentAccount->update()) {
                        $typeAccount->balance = $typeAccount->balance + $course->cost;
                        if ($typeAccount->update()) {
                            $studentOperation = new financialStudentOperations();
                            $studentOperation->studentId = $studentAccount->FAId;
                            $studentOperation->typeId = $typeAccount->FAId;
                            $studentOperation->operationType = "تسجيل";
                            $studentOperation->FOId = $financialOperation;
                            if ($studentOperation->save()) {
                                $studentDepts->deserevedAmount = $studentDepts->deserevedAmount + $course->cost;
                                if ($studentDepts->update()) {
                                    DB::commit();
                                    $stuNumber = sizeof(StudentCourse::where('courseId',$request->courseId)->get());
                                    $courseStuNo = corusestuno::where('courseId',$request->courseId)->orderBy("created_at")->get()->last();
                                    if(!$courseStuNo){
                                        $stNo = new corusestuno();
                                        $stNo->courseId=$request->courseId;
                                        $stNo->number=$stuNumber;
                                        $stNo->save();
                                    }
                                    else{
                                        if(strtotime(date("Y-m-d"))==strtotime(date("Y-m-d" , strtotime($courseStuNo->created_at)))){
                                            $courseStuNo->number=$stuNumber;
                                            $courseStuNo->update(); 
                                        }
                                        $coursestudent = new corusestuno();
                                        $coursestudent->courseId = $request->courseId;
                                        $coursestudent->number=$stuNumber;
                                        $courseStuNo->save();
                                    }
                                    
                                    return response(
                                        [
                                            'studentCourse' => $courseStuNo,
                                            'message' => 'تمت إضافة الطالب إلى الدورة بنجاح',
                                        ],
                                        200
                                    );
                                } else {
                                    DB::rollBack();
                                    return response(["message" => "فشلت عملية الإضافة"]);
                                }
                            } else {
                                DB::rollBack();
                                return response(["message" => "فشلت عملية الإضافة"]);
                            }
                        } else {
                            DB::rollBack();
                            return response(["message" => "فشلت عملية الإضافة"]);
                        }
                    } else {
                        DB::rollBack();
                        return response(["message" => "فشلت عملية الإضافة"]);
                    }
                } else {
                    DB::rollBack();
                    return response(["message" => "فشلت عملية الإضافة"]);
                }
            } else {
                $financialOperation = financialOperations::insertGetId([
                    'creditorId' => $typeAccount->FAId,
                    'creditorName' => $typeAccount->accountName,
                    'debtorId' => $studentAccount->FAId,
                    'debtorName' => $studentAccount->accountName,
                    'debtorBalance' => $studentAccount->balance,
                    'creditorBalance' => $typeAccount->balance,
                    'balance' => $course->cost,
                    'operationDate' => date("Y-m-d"),
                    'description' => " تسجيل الطالب في الدورة بمادة " . $subject->name,
                ]);
                if ($financialOperation) {
                    $studentAccount->balance = $studentAccount->balance - $course->cost;
                    if ($studentAccount->update()) {
                        $typeAccount->balance = $typeAccount->balance + $course->cost;
                        if ($typeAccount->update()) {
                            $studentOperation = new financialStudentOperations();
                            $studentOperation->studentId = $studentAccount->FAtId;
                            $studentOperation->typeId = $typeAccount->FAId;
                            $studentOperation->operationType = "تسجيل";
                            $studentOperation->FOId = $financialOperation;
                            if ($studentOperation->save()) {
                                $studentDepts = new studentDepts();
                                $studentDepts->deserevedAmount = $course->cost;
                                $studentDepts->paidAmount = 0;
                                $studentDepts->studentId = $studentAccount->FAId;
                                $studentDepts->typeId = $typeAccount->FAId;
                                if ($studentDepts->save()) {
                                    DB::commit();
                                    $stuNumber = sizeof(StudentCourse::where('courseId',$request->courseId)->get());
                                    $courseStuNo = corusestuno::where('courseId',$request->courseId)->orderBy("created_at")->get()->last();
                                    if(!$courseStuNo){
                                        $stNo = new corusestuno();
                                        $stNo->courseId=$request->courseId;
                                        $stNo->number=$stuNumber;
                                        $stNo->save();
                                    }
                                    else{
                                        if(strtotime(date("Y-m-d"))==strtotime(date("Y-m-d",strtotime($courseStuNo->created_at)))){
                                            $courseStuNo->number=$stuNumber;
                                            $courseStuNo->update(); 
                                        }
                                        $coursestudent = new corusestuno();
                                        $coursestudent->courseId = $request->courseId;
                                        $coursestudent->number=$stuNumber;
                                        $courseStuNo->save();
                                    }
                                    return response(
                                        [
                                            'studentCourse' => $studentCourse,
                                            'message' => 'تمت إضافة الطالب إلى الدورة بنجاح',
                                        ],
                                        200
                                    );
                                } else {
                                    DB::rollBack();
                                    return response(["message" => "فشلت عملية الإضافة"]);
                                }
                            } else {
                                DB::rollBack();
                                return response(["message" => "فشلت عملية الإضافة"]);
                            }
                        } else {
                            DB::rollBack();
                            return response(["message" => "فشلت عملية الإضافة"]);
                        }
                    } else {
                        DB::rollBack();
                        return response(["message" => "فشلت عملية الإضافة"]);
                    }
                } else {
                    DB::rollBack();
                    return response(["message" => "فشلت عملية الإضافة"]);
                }
            }
        } else {
            DB::rollBack();
            return response(["message" => "فشلت عملية الإضافة"]);
        }
        return response(["message" => "فشلت عملية الإضافة"]);
    }

    public function showstudentCoursess($id)
    {
      //  return  $user = auth()->guard('studentapi')->user();
        $student = StudentCourse::where('studentId', $id)->get();

        if (!$student) {
            $array = [];
            return response(
                [
                    'Course' => $array,
                    'message' => 'لا يوجد معلومات مطابقة لهذا العرض',
                ]
            );
        } else {
            for ($i = 0; $i < sizeof($student); $i++) {
                $course[$i] = Course::where('courseId', $student[$i]->courseId)->get()->first();
                $type = Type::find($course[$i]->typeId);
                $course[$i]->type = $type->name;
                $subject = subject::find($course[$i]->subjectId);
                $course[$i]->subject = $subject->name;
                $evaluation = Evaluation::where('studentId', $id)->where('courseId', $student[$i]->courseId)->get()->first();
                if ($evaluation) {
                    $course[$i]->behavior =  $evaluation->behavior;
                    $course[$i]->cause =  $evaluation->cause;                    
                    $teacher = User::find($evaluation->userId);
                    $course[$i]->teacher = $teacher->name;
                    $course[$i]->value =  $evaluation->value;
                }
            }
            return response(
                [
                    'Course' => $course,
                    'message' => 'تمت عملية العرض بنجاح',
                ]
            );
        }
    }
    public function deleteStudentFromCourse($cId, $sId)
    {
        $student = StudentCourse::where('courseId', $cId)->where('studentId', $sId)->get()->first();
        if (!$student) {
            return response([
                'message' => "هذاالطالب غير موجود"
            ]);
        }
        DB::table('student_course')->where('courseId', $cId)->where('studentId', $sId)->delete();
        $stuNumber = sizeof(StudentCourse::where('courseId',$cId)->get());
                $courseStuNo = corusestuno::where('courseId',$cId)->orderBy("created_at")->get()->last();
                if(!$courseStuNo){
                    $stNo = new corusestuno();
                    $stNo->courseId=$cId;
                    $stNo->number=$stuNumber;
                    $stNo->save();
                }
                else{
                    if(strtotime(date("Y-m-d"))==strtotime(date("Y-m-d" , strtotime($courseStuNo->created_at)))){
                        $courseStuNo->number=$stuNumber;
                        $courseStuNo->update(); 
                    }
                    $coursestudent = new corusestuno();
                    $coursestudent->courseId = $cId;
                    $coursestudent->number=$stuNumber;
                    $courseStuNo->save();
                } 
        return response([
            'student' => $student,
            'message' => "تم الحذف بنجاح"
        ]);
    }

    public function editStudentInfo($id, Request $request)
    {

        $student = Student::where('studentId', $id)->get()->first();
        $student->password= bcrypt($request->password);
        $student->myStatus= $request->myStatus;
        $student->gender= $request->gender;
        $student->address= $request->address;
        $student->phone= $request->phone;
        $student->birthdate= $request->birthdate;
        $student->name= $request->name;
        $student->save();

        return response([
            'Student' => $student,
            'message' => 'تمت عملية تعديل معلومات الطالب بنجاح',
        ], 200);
    }
    public function show($id)
    {
        $student = Student::find($id);
        if (!$student) {
            $array = [];
            return response(
                [
                    'Student' => $array,
                    'message' => 'لا يوجد معلومات مطابقة لهذا العرض',
                ]
            );
        } else {
            return response(
                [
                    'Student' => $student,
                    'message' => 'تمت عملية العرض بنجاح',
                ]
            );
        }
    }
    public function showStudentsInCourseDependOnGender($id, $gender)
    {
        $studentCourse = StudentCourse::where('courseId', $id)->get();
        $j = 0;
        for ($i = 0; $i < sizeof($studentCourse); $i++) {
            $s[$i] = Student::where('studentId', $studentCourse[$i]->studentId)
                ->where('gender', $gender)->get()->first();
            if ($s[$i]) {
                $students[$j] = $s[$i];
                $j++;
            }
        }
        if ($students) {
            $sortedStudent = collect($students);
            $students = $sortedStudent->sortBy('name', SORT_NATURAL);
            $students = $students->values()->all();
            for ($i = 0; $i < sizeof($students); $i++) {
                $evaluation = Evaluation::where('courseId', $id)->where('studentId', $students[$i]->studentId)->get()->first();
                if ($evaluation) {
                    $students[$i]->behavior = $evaluation->behavior;
                    $students[$i]->cause = $evaluation->cause;
                    $teacher = teacher::find($evaluation->teacherId);
                    $teacher = User::find($teacher->userId);
                    $students[$i]->teacher = $teacher->name;
                    $students[$i]->value = $evaluation->value;
                }
            }
            return response([
                'Students' => $students,
            ]);
        } else {
            return response(
                [
                    'Students' => $students,
                ]
            );
        }
    }
    public function delete($id)
    {
        $student = Student::find($id);

        if (!$student) {
            $array = [];
            return response($array, 404);
        } else {
            $studentCorses = StudentCourse::where('studentId',$id)->get();
          
            $array = [
                'message' => "تم حذف الطالب نهائيا"
            ];
        }
        $student->delete();
        for($i=0;$i<sizeof($studentCorses);$i++){
            $stuNumber = sizeof(StudentCourse::where('courseId',$studentCorses[$i]->courseId)->get());
            $courseStuNo = corusestuno::where('courseId',$studentCorses[$i]->courseId)->orderBy("created_at")->get()->last();
            if(!$courseStuNo){
                $stNo = new corusestuno();
                $stNo->courseId=$studentCorses[$i]->courseId;
                $stNo->number=$stuNumber;
                $stNo->save();
            }
            else{
                if(strtotime(date("Y-m-d"))==strtotime(date("Y-m-d" , strtotime($courseStuNo->created_at)))){
                    $courseStuNo->number=$stuNumber;
                    $courseStuNo->update(); 
                }
                $coursestudent = new corusestuno();
                $coursestudent->courseId = $studentCorses[$i]->courseId;
                $coursestudent->number=$stuNumber;
                $courseStuNo->save();
            } 
        }
        return response([
            'student' => $array,
            'message' => "تم الحذف بنجاح"
        ]);
    }

    public function showStudentsInCourseAlphabetically($id)
    {

        $studentCourse = StudentCourse::where('courseId', $id)->get();
        $students = null;
        for ($i = 0; $i < sizeof($studentCourse); $i++) {
            $students[$i] = Student::where('studentId', $studentCourse[$i]->studentId)->get()->first();
        }
        if ($students) {
            $sortedStudent = collect($students);
            $students = $sortedStudent->sortBy('name', SORT_NATURAL);
            $students = $students->values()->all();
        }

        if (!$students) {
            $array = [];
            return response([
                'Students' => $array,
                //'Students' => $array->getQuery()->orderBy('created_at', 'desc')->get(),
                'message' => 'لا يوجد معلومات مطابقة لهذا العرض',
            ]);
        } else {
            for ($i = 0; $i < sizeof($students); $i++) {
                $evaluation = Evaluation::where('courseId', $id)->where('studentId', $students[$i]->studentId)->get()->first();
                if ($evaluation) {
                    $students[$i]->behavior = $evaluation->behavior;
                    $students[$i]->cause = $evaluation->cause;
                    $teacher = teacher::find($evaluation->teacherId);
                    $teacher = User::find($teacher->userId);
                    $students[$i]->teacher = $teacher->name;
                    $students[$i]->value = $evaluation->value;
                }
            }
            return response(
                [
                    'Students' => $students,
                    'message' => 'تمت عملية العرض بنجاح',
                ]
            );
        }
    }

    public function showStudentsInCourse($id)
    {

        $studentCourse = StudentCourse::where('courseId', $id)->get();
        $students = null;
        for ($i = 0; $i < sizeof($studentCourse); $i++) {
            $students[$i] = Student::where('studentId', $studentCourse[$i]->studentId)->get()->first();
        }
        if (!$students) {
            $array = [];
            return response([
                'Students' => $array,
                'message' => 'لا يوجد معلومات مطابقة لهذا العرض',
            ]);
        } else
            return response(
                [
                    'Students' => $students,
                    'message' => 'تمت عملية العرض بنجاح',
                ]
            );
    }

    public function sortStudentsInCourseByTest($id)
    {

        $students = null;
        $test = Test::where('courseId', $id)->orderBy('value', 'desc')->get();
        for ($i = 0; $i < sizeof($test); $i++) {
            $students[$i] = Student::where('studentId', $test[$i]->studentId)->get()->first();
        }

        if ($test) {
            for ($i = 0; $i < sizeof($students); $i++) {
                for ($j = 0; $j < sizeof($test); $j++) {
                    if ($students[$i]->studentId == $test[$j]->studentId) {
                        $students[$i]->testId = $test[$j]->testId;
                        $students[$i]->teacherId = $test[$j]->teacherId;
                        $teacher = teacher::find($test[$j]->teacherId);
                        $teacher = User::find($teacher->userId);
                        $students[$i]->teacher = $teacher->name;
                        $students[$i]->value = $test[$j]->value;
                        $students[$i]->cause = $test[$j]->cause;
                    }
                }
            }
            return response([
                'SortTest' => $students,
            ]);
        } else {
            return response(
                [
                    'SortTest' => 'لا يوجد شيء لعرضه',
                ]
            );
        }
    }
    public function searchStudentInCourse($id, $name)
    {
        $studentCourse = StudentCourse::where('courseId', $id)->get();
        $students = null;
        $j = 0;
        for ($i = 0; $i < sizeof($studentCourse); $i++) {
            $students[$j] = Student::where('studentId', $studentCourse[$i]->studentId)->where('name', 'like', '%' . $name . '%')->get()->first();
            if ($students[$j]) {
                $j++;
            }
        }
        if ($students && sizeof($students) < $j) {
            array_pop($students);
        }
        if (!$students) {
            $array = [];
            return response([
                'Students' => $array,
                'message' => 'لا يوجد معلومات مطابقة لهذا البحث',
            ]);
        } else {
            for ($i = 0; $i < sizeof($students); $i++) {
                $evaluation = Evaluation::where('courseId', $id)->where('studentId', $students[$i]->studentId)->get()->first();
                if ($evaluation) {
                    $students[$i]->behavior = $evaluation->behavior;
                    $students[$i]->cause = $evaluation->cause;
                    $teacher = teacher::find($evaluation->teacherId);
                    $teacher = User::find($teacher->userId);
                    $students[$i]->teacher = $teacher->name;
                    $students[$i]->value = $evaluation->value;
                }
            }
        }
        return response(
            [
                'Students' => $students,
                'message' => 'تمت عملية البحث بنجاح',
            ]
        );
    }

    public function create(Request $request)
    {
        DB::beginTransaction();
        $student = new Student();
        $student = Student::insertGetId([
            'name' => $request->name,
            'birthdate' => $request->birthdate,
            'phone' => $request->phone,
            'address' => $request->address,
            'gender' => $request->gender,
            'myStatus' => $request->myStatus,
            'password' => bcrypt($request->password)
        ]);
        if ($student) {
            $financialAccount =  financialAccounts::insertGetId([
                'status' => "إيرادات",
                'accountName' => $request->name . "-" . $student,
                'balance' => 0
            ]);
            if ($financialAccount) {
                $studentAccount = new financialStudentAccount();
                $studentAccount->studentId = $student;
                $studentAccount->FAId = $financialAccount;
                $save = $studentAccount->save();
                if (!$save) {
                    DB::rollBack();
                    return response(["message" => "فشلت عملية الإضافة"]);
                }
            } else {
                DB::rollBack();
                return response(["message" => "فشلت عملية الإضافة"]);
            }
            $financialPeriod = financialPeriod::where('status', "مفتوحة")->get()->first();
            if (!(strtotime(date("Y-m-d")) >= strtotime($financialPeriod->startDate) && strtotime(date("Y-m-d")) <= strtotime($financialPeriod->endDate))) {
                DB::rollBack();
                return response(["message" => $financialPeriod->startDate . " & " . $financialPeriod->endDate . " : " . "يجب أن يكون تاريخ العملية ضمن تاريخ بداية و نهاية الدورة المفتوحة أي بين القيميتين"]);
            }
            $studentCourse = new StudentCourse();
            $studentCourse->studentId = $student;
            $studentCourse->courseId = $request->courseId;
            $studentCourse->studentAccount = $financialAccount;
            $save = $studentCourse->save();
            if (!$save) {
                DB::rollBack();
                return response(["message" => "فشلت عملية الإضافة"]);
            }
            $course = Course::find($request->courseId);
            $type = Type::find($course->typeId);
            $typeA = financialTypeAccount::where('typeId', $course->typeId)->get()->first();
            $subject = subject::find($course->subjectId);
            $typeAccount = financialAccounts::find($typeA->FAId);
            if (!$course || !$type || !$financialAccount || !$studentAccount || !$typeA || !$subject || !$typeAccount) {
                DB::rollBack();
                return response(["message" => "فشلت عملية الإضافة"]);
            }
            $financialStudentAccount = financialAccounts::find($financialAccount);
            $financialOperation = financialOperations::insertGetId([
                'creditorId' => $typeAccount->FAId,
                'creditorName' => $typeAccount->accountName,
                'debtorId' => $financialStudentAccount->FAId,
                'debtorName' => $financialStudentAccount->accountName,
                'debtorBalance' => $financialStudentAccount->balance,
                'creditorBalance' => $typeAccount->balance,
                'balance' => $course->cost,
                'operationDate' => date("Y-m-d"),
                'description' => " تسجيل الطالب في الدورة بمادة " . $subject->name,
            ]);

            if ($financialOperation) {
                $financialStudentAccount->balance = $financialStudentAccount->balance - $course->cost;
                if ($financialStudentAccount->update()) {
                    $typeAccount->balance = $typeAccount->balance + $course->cost;
                    if ($typeAccount->update()) {
                        $studentOperation = new financialStudentOperations();
                        $studentOperation->studentId = $financialStudentAccount->FAId;
                        $studentOperation->typeId = $typeAccount->FAId;
                        $studentOperation->operationType = "تسجيل";
                        $studentOperation->FOId = $financialOperation;
                        if ($studentOperation->save()) {
                            $studentDepts = new studentDepts();
                            $studentDepts->deserevedAmount = $course->cost;
                            $studentDepts->paidAmount = 0;
                            $studentDepts->studentId = $financialStudentAccount->FAId;
                            $studentDepts->typeId = $typeAccount->FAId;
                            if ($studentDepts->save()) {
                                DB::commit();
                                $stuNumber = sizeof(StudentCourse::where('courseId',$request->courseId)->get());
                                $courseStuNo = corusestuno::where('courseId',$request->courseId)->orderBy("created_at")->get()->last();
                                if(!$courseStuNo){
                                    $stNo = new corusestuno();
                                    $stNo->courseId=$request->courseId;
                                    $stNo->number=$stuNumber;
                                    $stNo->save();
                                }
                                else{
                                    if(strtotime(date("Y-m-d"))==strtotime(date("Y-m-d",strtotime($courseStuNo->created_at)))){
                                        $courseStuNo->number=$stuNumber;
                                        $courseStuNo->update(); 
                                    }
                                    $coursestudent = new corusestuno();
                                    $coursestudent->courseId = $request->courseId;
                                    $coursestudent->number=$stuNumber;
                                    $courseStuNo->save();
                                }
                                $student = Student::find($student);
                                return response(
                                    [
                                        'student' => $student,
                                        'message' => 'تمت إضافة الطالب بنجاح',
                                    ],
                                    200
                                );
                            } else {
                                DB::rollBack();
                                return response(["messgae" => "فشل عملية إضافة الطالب"]);
                            }
                        } else {
                            DB::rollBack();
                            return response(["messgae" => "فشل عملية إضافة الطالب"]);
                        }
                    } else {
                        DB::rollBack();
                        return response(["messgae" => "فشل عملية إضافة الطالب"]);
                    }
                } else {
                    DB::rollBack();
                    return response(["messgae" => "فشل عملية إضافة الطالب"]);
                }
            } else {
                DB::rollBack();
                return response(["messgae" => "فشل عملية إضافة الطالب"]);
            }
        } else {
            DB::rollBack();
            return response(["messgae" => "فشل عملية إضافة الطالب"]);
        }
        return response(["messgae" => "فشل عملية إضافة الطالب"]);
    }
    public function showAllStudentsAlphabetically()
    {
        $students = Student::get();

        if ($students) {
            $sortedStudent = collect($students);
            $students = $sortedStudent->sortBy('name', SORT_NATURAL);
            $students = $students->values()->all();
        }

        if (!$students) {
            $array = [];
            return response([
                'Students' => $array,
                'message' => 'لا يوجد معلومات مطابقة لهذا العرض',
            ]);
        } else {
            return response(
                [
                    'Students' => $students,
                    'message' => 'تمت عملية العرض بنجاح',
                ]
            );
        }
    }
    public function showAllStudentsDependOnGender($gender)
    {
        $students = Student::where('gender', $gender)->get();

        if ($students) {
            $sortedStudent = collect($students);
            $students = $sortedStudent->sortBy('name', SORT_NATURAL);
            $students = $students->values()->all();
        }
        return response([
            'Students' => $students,
        ]);
    }
    public function searchStudent($name)
    {
        $students = Student::where('name', 'like', '%' . $name . '%')->get();

        if (!$students) {
            $array = [];
            return response([
                'Students' => $array,
                'message' => 'لا يوجد معلومات مطابقة لهذا البحث',
            ]);
        } else {

            return response(
                [
                    'Students' => $students,
                    'message' => 'تمت عملية البحث بنجاح',
                ]
            );
        }
    }
}

////////////////////////////////////////////
// namespace App\Http\Controllers;

// use App\Models\Student;
// use App\Models\StudentCourse;
// use App\Models\Course;
// use App\Models\subject;
// use App\Models\Type;
// use App\Models\Test;
// use App\Models\teacher;
// use App\Models\User;
// use App\Models\Evaluation;
// use App\Models\financialAccounts;
// use App\Models\financialOperations;
// use App\Models\financialPeriod;
// use App\Models\financialStudentAccount;
// use App\Models\financialStudentOperations;
// use App\Models\financialTypeAccount;
// use App\Models\studentDepts;
// use App\Models\sessionStudentMonitoring;
// use Illuminate\Http\Request;
// use Illuminate\Contracts\Encryption\DecryptException;
// use Illuminate\Support\Facades\Crypt;
// use Illuminate\Support\Facades\DB;

// class StudentController extends Controller
// {

//     //StudentCourse
//     public function addStudentToCourse(Request $request)
//     {
//         DB::beginTransaction();
//         $studentCourse = new StudentCourse();
//         $studentCourse->courseId = $request->courseId;
//         $studentCourse->studentId = $request->studentId;
//         $save = $studentCourse->save();
//         if (!$save) {
//             DB::rollBack();
//             return response(["message" => "فشلت عملية الإضافة"]);
//         }
//         $course = Course::find($request->courseId);
//         if ($course) {
//             $financialPeriod = financialPeriod::where('status', "مفتوحة")->get()->first();
//             if (!(strtotime(date("Y-m-d")) >= strtotime($financialPeriod->startDate) && strtotime(date("Y-m-d")) <= strtotime($financialPeriod->endDate))) {
//                 DB::rollBack();
//                 return response(["message" => $financialPeriod->startDate . " & " . $financialPeriod->endDate . " : " . "يجب أن يكون تاريخ العملية ضمن تاريخ بداية و نهاية الدورة المفتوحة أي بين القيميتين"]);
//             }
//             $student = Student::find($request->studentId);
//             $type = Type::find($course->typeId);
//             $studentDepts = studentDepts::where('typeId', $course->typeId)->where('studentId', $student->studentId)->get()->first();
//             $studentA = financialStudentAccount::where('studentId', $student->studentId)->get()->first();
//             $typeA = financialTypeAccount::where('typeId', $course->typeId)->get()->first();
//             $studentAccount = financialAccounts::find($studentA->FAId);
//             $subject = subject::find($course->subjectId);
//             $typeAccount = financialAccounts::find($typeA->FAId);
//             if (!$course || !$type || !$studentA || !$studentAccount || !$typeA || !$subject || !$typeAccount) {
//                 DB::rollBack();
//                 return response(["message" => "فشلت عملية الإضافة"]);
//             }
//             if ($studentDepts) {
//                 $financialOperation = financialOperations::insertGetId([
//                     'creditorId' => $typeAccount->FAId,
//                     'creditorName' => $typeAccount->accountName,
//                     'debtorId' => $studentAccount->FAId,
//                     'debtorName' => $studentAccount->accountName,
//                     'debtorBalance' => $studentAccount->balance,
//                     'creditorBalance' => $typeAccount->balance,
//                     'balance' => $course->cost,
//                     'operationDate' => date("Y-m-d"),
//                     'description' => " تسجيل الطالب في الدورة بمادة " . $subject->name,
//                 ]);
//                 if ($financialOperation) {
//                     $studentAccount->balance = $studentAccount->balance - $course->cost;
//                     if ($studentAccount->update()) {
//                         $typeAccount->balance = $typeAccount->balance + $course->cost;
//                         if ($typeAccount->update()) {
//                             $studentOperation = new financialStudentOperations();
//                             $studentOperation->studentId = $request->studentId;
//                             $studentOperation->typeId = $course->typeId;
//                             $studentOperation->operationType = "تسجيل";
//                             $studentOperation->FOId = $financialOperation;
//                             if ($studentOperation->save()) {
//                                 $studentDepts->deserevedAmount = $studentDepts->deserevedAmount + $course->cost;
//                                 if ($studentDepts->update()) {
//                                     DB::commit();
//                                     return response(
//                                         [
//                                             'studentCourse' => $studentCourse,
//                                             'message' => 'تمت إضافة الطالب إلى الدورة بنجاح',
//                                         ],
//                                         200
//                                     );
//                                 } else {
//                                     DB::rollBack();
//                                     return response(["message" => "فشلت عملية الإضافة"]);
//                                 }
//                             } else {
//                                 DB::rollBack();
//                                 return response(["message" => "فشلت عملية الإضافة"]);
//                             }
//                         } else {
//                             DB::rollBack();
//                             return response(["message" => "فشلت عملية الإضافة"]);
//                         }
//                     } else {
//                         DB::rollBack();
//                         return response(["message" => "فشلت عملية الإضافة"]);
//                     }
//                 } else {
//                     DB::rollBack();
//                     return response(["message" => "فشلت عملية الإضافة"]);
//                 }
//             } else {
//                 $financialOperation = financialOperations::insertGetId([
//                     'creditorId' => $typeAccount->FAId,
//                     'creditorName' => $typeAccount->accountName,
//                     'debtorId' => $studentAccount->FAId,
//                     'debtorName' => $studentAccount->accountName,
//                     'debtorBalance' => $studentAccount->balance,
//                     'creditorBalance' => $typeAccount->balance,
//                     'balance' => $course->cost,
//                     'operationDate' => date("Y-m-d"),
//                     'description' => " تسجيل الطالب في الدورة بمادة " . $subject->name,
//                 ]);
//                 if ($financialOperation) {
//                     $studentAccount->balance = $studentAccount->balance - $course->cost;
//                     if ($studentAccount->update()) {
//                         $typeAccount->balance = $typeAccount->balance + $course->cost;
//                         if ($typeAccount->update()) {
//                             $studentOperation = new financialStudentOperations();
//                             $studentOperation->studentId = $request->studentId;
//                             $studentOperation->typeId = $course->typeId;
//                             $studentOperation->operationType = "تسجيل";
//                             $studentOperation->FOId = $financialOperation;
//                             if ($studentOperation->save()) {
//                                 $studentDepts = new studentDepts();
//                                 $studentDepts->deserevedAmount = $course->cost;
//                                 $studentDepts->paidAmount = 0;
//                                 $studentDepts->studentId = $request->studentId;
//                                 $studentDepts->typeId = $course->typeId;
//                                 $studentDepts->studentName = $student->name;
//                                 $studentDepts->typeName = $type->name;
//                                 if ($studentDepts->save()) {
//                                     DB::commit();
//                                     return response(
//                                         [
//                                             'studentCourse' => $studentCourse,
//                                             'message' => 'تمت إضافة الطالب إلى الدورة بنجاح',
//                                         ],
//                                         200
//                                     );
//                                 } else {
//                                     DB::rollBack();
//                                     return response(["message" => "فشلت عملية الإضافة"]);
//                                 }
//                             } else {
//                                 DB::rollBack();
//                                 return response(["message" => "فشلت عملية الإضافة"]);
//                             }
//                         } else {
//                             DB::rollBack();
//                             return response(["message" => "فشلت عملية الإضافة"]);
//                         }
//                     } else {
//                         DB::rollBack();
//                         return response(["message" => "فشلت عملية الإضافة"]);
//                     }
//                 } else {
//                     DB::rollBack();
//                     return response(["message" => "فشلت عملية الإضافة"]);
//                 }
//             }
//         } else {
//             DB::rollBack();
//             return response(["message" => "فشلت عملية الإضافة"]);
//         }
//       /*  return response(["message" => "فشلت عملية الإضافة"]);
//    */ }

//     public function showstudentCoursess($id)
//     {
//         // return  $user = auth()->guard('studentapi')->user();
//         $student = StudentCourse::where('studentId', $id)->get();

//         if (!$student) {
//             $array = [];
//             return response(
//                 [
//                     'Course' => $array,
//                     'message' => 'لا يوجد معلومات مطابقة لهذا العرض',
//                 ]
//             );
//         } else {
//             for ($i = 0; $i < sizeof($student); $i++) {
//                 $course[$i] = Course::where('courseId', $student[$i]->courseId)->get()->first();
//                 $type = Type::find($course[$i]->typeId);
//                 $course[$i]->type = $type->name;
//                 $subject = subject::find($course[$i]->subjectId);
//                 $course[$i]->subject = $subject->name;
//                 $evaluation = Evaluation::where('studentId', $id)->where('courseId', $student[$i]->courseId)->get()->first();
//                 if ($evaluation) {
//                     $course[$i]->behavior =  $evaluation->behavior;
//                     $course[$i]->cause =  $evaluation->cause;
//                     $teacher = teacher::find($evaluation->teacherId);
//                     $teacher = User::find($teacher->userId);
//                     $course[$i]->teacher = $teacher->name;
//                     $course[$i]->value =  $evaluation->value;
//                 }
//             }
//             return response(
//                 [
//                     'Course' => $course,
//                     'message' => 'تمت عملية العرض بنجاح',
//                 ]
//             );
//         }
//     }
//     public function deleteStudentFromCourse($cId, $sId)
//     {
//         $student = StudentCourse::where('courseId', $cId)->where('studentId', $sId)->get()->first();
//         if (!$student) {
//             return response([
//                 'message' => "هذاالطالب غير موجود"
//             ]);
//         }
//         DB::table('student_course')->where('courseId', $cId)->where('studentId', $sId)->delete();
//         return response([
//             'student' => $student,
//             'message' => "تم الحذف بنجاح"
//         ]);
//     }

//     public function editStudentInfo($id, Request $request)
//     {

    //     $student = Student::where('studentId', $id)->get()->first();
    //     $student->update($request->all());

    //     if ($request->password) {
    //         $student->password = bcrypt($request->password);
    //     }
    //     $student->save();

    //     return response([
    //         'Student' => $student,
    //         'message' => 'تمت عملية تعديل معلومات الطالب بنجاح',
    //     ], 200);
    // }
    // public function show($id)
    // {
    //     $student = Student::find($id);
    //     if (!$student) {
    //         $array = [];
    //         return response(
    //             [
    //                 'Student' => $array,
    //                 'message' => 'لا يوجد معلومات مطابقة لهذا العرض',
    //             ]
    //         );
    //     } else {
    //         return response(
    //             [
    //                 'Student' => $student,
    //                 'message' => 'تمت عملية العرض بنجاح',
    //             ]
    //         );
    //     }
    // }
    // public function showStudentsInCourseDependOnGender($id, $gender)
    // {
    //     $studentCourse = StudentCourse::where('courseId', $id)->get();
    //     $j = 0;
    //     for ($i = 0; $i < sizeof($studentCourse); $i++) {
    //         $s[$i] = Student::where('studentId', $studentCourse[$i]->studentId)
    //             ->where('gender', $gender)->get()->first();
    //         if ($s[$i]) {
    //             $students[$j] = $s[$i];
    //             $j++;
    //         }
    //     }
    //     if ($students) {
    //         $sortedStudent = collect($students);
    //         $students = $sortedStudent->sortBy('name', SORT_NATURAL);
    //         $students = $students->values()->all();
    //         for ($i = 0; $i < sizeof($students); $i++) {
    //             $evaluation = Evaluation::where('courseId', $id)->where('studentId', $students[$i]->studentId)->get()->first();
    //             if ($evaluation) {
    //                 $students[$i]->behavior = $evaluation->behavior;
    //                 $students[$i]->cause = $evaluation->cause;
    //                 $teacher = teacher::find($evaluation->teacherId);
    //                 $teacher = User::find($teacher->userId);
    //                 $students[$i]->teacher = $teacher->name;
    //                 $students[$i]->value = $evaluation->value;
    //             }
    //         }
    //         return response([
    //             'Students' => $students,
    //         ]);
    //     } else {
    //         return response(
    //             [
    //                 'Students' => $students,
    //             ]
    //         );
    //     }
    // }
    // public function delete($id)
    // {
    //     $student = Student::find($id);

    //     if (!$student) {
    //         $array = [];
    //         return response($array, 404);
    //     } else {

    //         $array = [
    //             'message' => "تم حذف الطالب نهائيا"
    //         ];
    //     }
    //     $student->delete();
    //     return response([
    //         'student' => $array,
    //         'message' => "تم الحذف بنجاح"
    //     ]);
    // }

    // public function showStudentsInCourseAlphabetically($id)
    // {

    //     $studentCourse = StudentCourse::where('courseId', $id)->get();
    //     $students = null;
    //     for ($i = 0; $i < sizeof($studentCourse); $i++) {
    //         $students[$i] = Student::where('studentId', $studentCourse[$i]->studentId)->get()->first();
    //     }
    //     if ($students) {
    //         $sortedStudent = collect($students);
    //         $students = $sortedStudent->sortBy('name', SORT_NATURAL);
    //         $students = $students->values()->all();
    //     }

    //     if (!$students) {
    //         $array = [];
    //         return response([
    //             'Students' => $array,
    //             //'Students' => $array->getQuery()->orderBy('created_at', 'desc')->get(),
    //             'message' => 'لا يوجد معلومات مطابقة لهذا العرض',
    //         ]);
    //     } else {
    //         for ($i = 0; $i < sizeof($students); $i++) {
    //             $evaluation = Evaluation::where('courseId', $id)->where('studentId', $students[$i]->studentId)->get()->first();
    //             if ($evaluation) {
    //                 $students[$i]->behavior = $evaluation->behavior;
    //                 $students[$i]->cause = $evaluation->cause;
    //                 $teacher = teacher::find($evaluation->teacherId);
    //                 $teacher = User::find($teacher->userId);
    //                 $students[$i]->teacher = $teacher->name;
    //                 $students[$i]->value = $evaluation->value;
    //             }
    //         }
    //         return response(
    //             [
    //                 'Students' => $students,
    //                 'message' => 'تمت عملية العرض بنجاح',
    //             ]
    //         );
    //     }
    // }

    // public function showStudentsInCourse($id)
    // {

    //     $studentCourse = StudentCourse::where('courseId', $id)->get();
    //     $students = null;
    //     for ($i = 0; $i < sizeof($studentCourse); $i++) {
    //         $students[$i] = Student::where('studentId', $studentCourse[$i]->studentId)->get()->first();
    //     }
    //     if (!$students) {
    //         $array = [];
    //         return response([
    //             'Students' => $array,
    //             'message' => 'لا يوجد معلومات مطابقة لهذا العرض',
    //         ]);
    //     } else
    //         return response(
    //             [
    //                 'Students' => $students,
    //                 'message' => 'تمت عملية العرض بنجاح',
    //             ]
    //         );
    // }

    // public function sortStudentsInCourseByTest($id)
    // {
    //     $students = null;
    //     $test = Test::where('sessionId', $id)->orderBy('value', 'desc')->get();
    //     for ($i = 0; $i < sizeof($test); $i++) {
    //         $students[$i] = Student::where('studentId', $test[$i]->studentId)->get()->first();
    //     }

    //     if ($test) {
    //         for ($i = 0; $i < sizeof($students); $i++) {
    //             for ($j = 0; $j < sizeof($test); $j++) {
    //                 if ($students[$i]->studentId == $test[$j]->studentId) {
    //                     $students[$i]->testId = $test[$j]->testId;
    //                     $students[$i]->teacherId = $test[$j]->teacherId;
    //                     $teacher = teacher::find($test[$j]->teacherId);
    //                     $teacher = User::find($teacher->userId);
    //                     $students[$i]->teacher = $teacher->name;
    //                     $students[$i]->value = $test[$j]->value;
    //                     $students[$i]->cause = $test[$j]->cause;
    //                 }
    //             }
    //             $monitoring = sessionStudentMonitoring::where('sessionId', $id)->where('studentId', $students[$i]->studentId)->get()->first();
    //             if ($monitoring) {
    //                 $students[$i]->studentStatus = $monitoring->studentStatus;
    //             }
    //         }
    //         return response([
    //             'SortTest' => $students,
    //         ]);
    //     } else {
    //         return response(
    //             [
    //                 'SortTest' => 'لا يوجد شيء لعرضه',
    //             ]
    //         );
    //     }
    // }
    // public function searchStudentInCourse($id, $name)
    // {
    //     $studentCourse = StudentCourse::where('courseId', $id)->get();
    //     $students = null;
    //     $j = 0;
    //     for ($i = 0; $i < sizeof($studentCourse); $i++) {
    //         $students[$j] = Student::where('studentId', $studentCourse[$i]->studentId)->where('name', 'like', '%' . $name . '%')->get()->first();
    //         if ($students[$j]) {
    //             $j++;
    //         }
    //     }
    //     if ($students && !$students[$j]) {
    //         array_pop($students);
    //     }
    //     if (!$students) {
    //         $array = [];
    //         return response([
    //             'Students' => $array,
    //             'message' => 'لا يوجد معلومات مطابقة لهذا البحث',
    //         ]);
    //     } else {
    //         for ($i = 0; $i < sizeof($students); $i++) {
    //             $evaluation = Evaluation::where('courseId', $id)->where('studentId', $students[$i]->studentId)->get()->first();
    //             if ($evaluation) {
    //                 $students[$i]->behavior = $evaluation->behavior;
    //                 $students[$i]->cause = $evaluation->cause;
    //                 $teacher = teacher::find($evaluation->teacherId);
    //                 $teacher = User::find($teacher->userId);
    //                 $students[$i]->teacher = $teacher->name;
    //                 $students[$i]->value = $evaluation->value;
    //             }
    //         }
    //     }
    //     return response(
    //         [
    //             'Students' => $students,
    //             'message' => 'تمت عملية البحث بنجاح',
    //         ]
    //     );
    // }

    // public function create(Request $request)
    // {
    //     $student = new Student();
    //     $student = Student::insertGetId([
    //         'name' => $request->name,
    //         'birthdate' => $request->birthdate,
    //         'phone' => $request->phone,
    //         'address' => $request->address,
    //         'gender' => $request->gender,
    //         'myStatus' => $request->myStatus,
    //         'password' => bcrypt($request->password)
    //     ]);
    //     if ($student) {
    //         DB::beginTransaction();
    //         $financialAccount =  financialAccounts::insertGetId([
    //             'status' => "إيرادات",
    //             'accountName' => $request->name . "-" . $student,
    //             'balance' => 0
    //         ]);
    //         if ($financialAccount) {
    //             $studentAccount = new financialStudentAccount();
    //             $studentAccount->studentId = $student;
    //             $studentAccount->FAId = $financialAccount;
    //             $save = $studentAccount->save();
    //             if (!$save) {
    //                 DB::rollBack();
    //                 return response(["message" => "فشلت عملية الإضافة"]);
    //             }
    //         } else {
    //             DB::rollBack();
    //             return response(["message" => "فشلت عملية الإضافة"]);
    //         }
    //         $financialPeriod = financialPeriod::where('status', "مفتوحة")->get()->first();
    //         /*if (!(strtotime(date("Y-m-d")) >= strtotime($financialPeriod->startDate) && strtotime(date("Y-m-d")) <= strtotime($financialPeriod->endDate))) {
    //             DB::rollBack();
    //             return response(["message" => $financialPeriod->startDate . " & " . $financialPeriod->endDate . " : " . "يجب أن يكون تاريخ العملية ضمن تاريخ بداية و نهاية الدورة المفتوحة أي بين القيميتين"]);
    //         }*/
    //         $studentCourse = new StudentCourse();
    //         $studentCourse->studentId = $student;
    //         $studentCourse->courseId = $request->courseId;
    //         $save = $studentCourse->save();
    //         if (!$save) {
    //             DB::rollBack();
    //             return response(["message" => "فشلت عملية الإضافة"]);
    //         }
    //     /*    $course = Course::find($request->courseId);
    //         $type = Type::find($course->typeId);
    //         $studentA = financialStudentAccount::where('studentId', $student)->get()->first();
    //         $typeA = financialTypeAccount::where('typeId', $course->typeId)->get()->first();
    //         $studentAccount = financialAccounts::find($studentA->FAId);
    //         $subject = subject::find($course->subjectId);
    //         $typeAccount = financialAccounts::find($typeA->FAId);
    //         if (!$course || !$type || !$studentA || !$studentAccount ||
    //             !$typeA || !$subject || !$typeAccount) {
    //             DB::rollBack();
    //             return response(["message" => "فشلت عملية الإضافة"]);
    //         }
    //         $financialOperation = financialOperations::insertGetId([
    //             'creditorId' => $typeAccount->FAId,
    //             'creditorName' => $typeAccount->accountName,
    //             'debtorId' => $studentAccount->FAId,
    //             'debtorName' => $studentAccount->accountName,
    //             'debtorBalance' => $studentAccount->balance,
    //             'creditorBalance' => $typeAccount->balance,
    //             'balance' => $course->cost,
    //             'operationDate' => date("Y-m-d"),
    //             'description' => " تسجيل الطالب في الدورة بمادة " . $subject->name,
    //         ]);*/

    //         /*if ($financialOperation) {
    //             $studentAccount->balance = $studentAccount->balance - $course->cost;
    //             if ($studentAccount->update()) {
    //                 $typeAccount->balance = $typeAccount->balance + $course->cost;
    //                 if ($typeAccount->update()) {
    //                     $studentOperation = new financialStudentOperations();
    //                     $studentOperation->studentId = $student;
    //                     $studentOperation->typeId = $course->typeId;
    //                     $studentOperation->operationType = "تسجيل";
    //                     $studentOperation->FOId = $financialOperation;
    //                     if ($studentOperation->save()) {
    //                         $studentDepts = new studentDepts();
    //                         $studentDepts->deserevedAmount = $course->cost;
    //                         $studentDepts->paidAmount = 0;
    //                         $studentDepts->studentId = $student;
    //                         $studentDepts->typeId = $course->typeId;
    //                         $studentDepts->studentName = $request->name;
    //                         $studentDepts->typeName = $type->name;
    //                         if ($studentDepts->save()) {
    //                             DB::commit();
    //                             $student = Student::find($student);
    //                             return response(
    //                                 [
    //                                     'student' => $student,
    //                                     'message' => 'تمت إضافة الطالب بنجاح',
    //                                 ],
    //                                 200
    //                             );
    //                         } else {
    //                             DB::rollBack();
    //                             return response(["messgae" => "فشل عملية إضافة الطالب"]);
    //                         }
    //                     } else {
    //                         DB::rollBack();
    //                         return response(["messgae" => "فشل عملية إضافة الطالب"]);
    //                     }
    //                 } else {
    //                     DB::rollBack();
    //                     return response(["messgae" => "فشل عملية إضافة الطالب"]);
    //                 }
    //             } else {
    //                 DB::rollBack();
    //                 return response(["messgae" => "فشل عملية إضافة الطالب"]);
    //             }
    //         } else {
    //             DB::rollBack();
    //             return response(["messgae" => "فشل عملية إضافة الطالب"]);
    //         }*/
    //     } /*else {
    //         DB::rollBack();
    //         return response(["messgae" => "فشل عملية إضافة الطالب"]);
    //     }*/
    //     return response(["messgae" => "تمت عملية إضافة الطالب"]);
    // }
    
//}
