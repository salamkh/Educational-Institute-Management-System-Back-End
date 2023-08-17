<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\authorization;
use App\Models\Course;
use App\Models\dynammicTables;
use App\Models\financialAccounts;
use App\Models\financialUserAccount;
use App\Models\role;
use App\Models\subject;
use App\Models\subjectteacher;
use App\Models\tableColumns;
use App\Models\teacher;
use App\Models\TeacherCourse;
use App\Models\User;
use App\Models\userrole;
use App\Models\userauth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class teachersController extends Controller
{

    public function addTeacher(Request $request)
    {
        $messages =  array(
            'phoneNumber.required' => 'رقم الهاتف مطلوب',
            'phoneNumber.numeric' => 'يجب أن يكون رقم الهاتف أرقام فقط',
            'phoneNumber.starts_with' => 'يجب أن يبدأ رقم الهاتف ب 09',
            'name.required' => 'حقل الاسم مطلوب',
            'userName.required' => 'حقل اسم المستخدم مطلوب',
            'imageIdentity.required' => 'حقل صورة الهوية مطلوب',
            'salary.numeric' => 'الراتب يجب أن يكون رقم',
            // 'imageIdentity.required' => 'حقل صورة الهوية مطلوب',
            'password.min' => "يجب أن تتألف كلمة السر من 8 محارف على الأقل",
            'email.email' => 'حقل الايميل يجب أن يحوي على ايميل فقط',
            'address.required' => 'حقل العنوان   مطلوب',
            'roles.required' => 'حقل الأدوار لا يجب أن يكون فارغ ',
            'password.required' => 'حقل كلمة السر مطلوب',
            'subjects.required' => 'حقل المواد لا يجب أن يكون فارغ',
            'certificate.required' => 'حقل الشهادة مطلوب',
            'experience.required' => 'حقل الخبرة مطلوب',
            'cerDate.required' => 'حقل تاريخ الشهادة مطلوب',
        );
        $validator = Validator::make(request()->all(), [
            "name" => "required",
            "userName" => "required",
            "phoneNumber" => "required|numeric|starts_with:09",
            // "imageIdentity" => "required",
            "address" => "required",
            "roles" => "required|array",
            "password" => "required|min:8",
            "email" => "email",
            "permissions" => "array",
            "subjects" => "required",
            "certificate" => "required",
            "experience" => "required",
            "cerDate" => "required"
        ], $messages);
        if ($validator->fails()) {
            $msg = (json_decode(json_encode($validator->errors(), JSON_UNESCAPED_UNICODE), true));
            $string = '';
            foreach (array_values($msg) as $value) {
                $string .=  $value[0] . " , ";
            }
            return response(["message" => "$string"], 422);
        }
        $len = strlen($request->phoneNumber);
        if ($len != 10) {
            return response(["message" => "يجب أن يتألف رقم الهاتف من 10 أرقام فقط"]);
        }
        $u = User::where('phoneNumber', $request->phoneNumber)->get()->first();
        if ($u) {
            return response(["message" => "لا يمكن تكرار رقم الهاتف لأكثر من حساب"]);
        }
        $u = User::where('userName', $request->userName)->get()->first();
        if ($u) {
            return response(["message" => "لا يمكن تكرار اسم المستخدم لأكثر من حساب"]);
        }
        $u = User::where('imageIdentity', $request->imageIdentity)->get()->first();
        if ($u) {
            return response(["message" => "لا يمكن تكرار صورة الهوية لأكثر من حساب"]);
        }
        if ($request->email) {
            $u = User::where('email', $request->email)->get()->first();
            if ($u) {
                return response(["message" => "لا يمكن تكرار الايميل لأكثر من حساب"]);
            }
        }
        /////////
        $userTable = dynammicTables::where('name', 'users')->get()->first();
        $tableColumns = tableColumns::where('tableId', $userTable->tableId)->where('columnType', 'إضافية')->where('isUnique', '1')->get();
        $columns = [];
        $additionalInfo = [];
        for ($i = 0; $i < sizeof($tableColumns); $i++) {
            if ($request[$tableColumns[$i]->EnglishName]) {
                $u = User::where($tableColumns[$i]->EnglishName, $request[$tableColumns[$i]->EnglishName])->get()->first();
                if ($u) {
                    return response(["message" => " لا يمكن تكرار حقل " . $tableColumns[$i]->arabicName . " لأكثر من حساب  "]);
                }
            } else {
                return response(["message" => " حقل " . $tableColumns[$i]->arabicName . " مطلوب "]);
            }
        }
        $tableColumns = tableColumns::where('tableId', $userTable->tableId)->where('columnType', 'إضافية')->get();
        for ($i = 0; $i < sizeof($tableColumns); $i++) {
            if ($request[$tableColumns[$i]->EnglishName]) {
                $additionalInfo[$tableColumns[$i]->EnglishName] = $request[$tableColumns[$i]->EnglishName];
            }
        }
        //////////
        $FileName = $request->imageIdentity;
        if ($request->hasFile('imageIdentity')) {
            $FileName =  $request->imageIdentity->getClientOriginalName();
            Storage::disk('public')->putFileAs('/images', $request->imageIdentity, $FileName);
        }
        DB::beginTransaction();
        $userID =  User::insertGetId(array_merge([
            'name' => $request->name,
            'email' => $request->email,
            'phoneNumber' => $request->phoneNumber,
            'userName' => $request->userName,
             'imageIdentity' => $request->phoneNumber,
            'accountStatus' => "موظف",
            'address' => $request->address,
            'password' => bcrypt($request->password),
        ], $additionalInfo));
        if ($userID) {
            $financialAccount = financialAccounts::where('accountName',$request->name . "-" . $request->userName)->get()->first();
            if(!$financialAccount){
                $financialAccount =  financialAccounts::insertGetId([
                    'status' => "مصاريف",
                    'accountName' => $request->name . "-" . $request->userName,
                    'balance' => 0
                ]);
                if ($financialAccount) {
                    $userAccount = new financialUserAccount();
                    $userAccount->userId = $userID;
                    $userAccount->FAId = $financialAccount;
                    $userAccount->save();
                    DB::commit();
                } else {
                    DB::rollBack();
                }
            }
            else{
                DB::rollBack();
                return response(["message" => " فشلت عملية التسجيل يرجى تغيير الاسم الكامل أو اسم المستخدم"]); 
            }
        }
        else{
            DB::rollBack();
            return response(["message" => "فشلت عملية التسجيل "]);
        }
        $u = User::find($userID);
        if ($u) {
            for ($i = 0; $i < sizeof($request->roles); $i++) {
                $role = role::find($request->roles[$i]);
                if ($role) {
                    $userrole = new userrole();
                    $userrole->userId = $userID;
                    $userrole->roleId = $request->roles[$i];
                    $userrole->save();
                }
            }
            if ($request->permissions && sizeof($request->permissions) != 0) {
                for ($i = 0; $i < sizeof($request->permissions); $i++) {
                    $per = authorization::find($request->permissions[$i]);
                    if ($per) {
                        $userpermission = new userauth();
                        $userpermission->userId = $userID;
                        $userpermission->aId = $request->permissions[$i];
                        $userpermission->save();
                    }
                }
            }
            $teacherId =  teacher::insertGetId([
                'userId' => $userID,
                'certificate' => $request->certificate,
                'experience' => $request->experience,
                'cerDate' => $request->cerDate
            ]);
            $t = teacher::find($teacherId);
            if ($t) {
                for ($i = 0; $i < sizeof($request->subjects); $i++) {
                    $sub = subject::find($request->subjects[$i]);
                    if ($sub) {
                        $st = new subjectteacher();
                        $st->tId = $teacherId;
                        $st->sId = $request->subjects[$i];
                        $st->save();
                    }
                }
                $ts = new subjectteacher();
                $user = User::find($userID);
                $teacher = teacher::find($teacherId);

                return response()->json(["user" => $user, "teacher" => $teacher, "message" => " تمت عملية التسجيل"], 201);
            }
        }
        return response()->json(["message" => "فشلت عملية التسجيل "], 201);
    }
    public function allTeachers()
    {
        $tIds = teacher::get();
        $teachers = [];
        if (sizeof($tIds) != 0) {
            for ($i = 0; $i < sizeof($tIds); $i++) {
                $teachers[$i] = User::find($tIds[$i]->userId);
            }
            return response(["teachers" => $teachers, "message" => "تم جلب الاساتذة بنجاح"]);
        }
        return response(["message" => "لا يوجد أساتذة"]);
    }
    public function teacherSubjects($id)
    {
        $t = teacher::where('userId', $id)->get()->first();
        $ts = subjectteacher::where('tId', $t->tId)->get();
        $s = [];
        if (sizeof($ts) != 0) {
            for ($i = 0; $i < sizeof($ts); $i++) {
                $s[$i] = subject::find($ts[$i]->sId);
            }
            return response(["subjects" => $s, "message" => "تم جلب المواد بنجاح"]);
        }
        return response(["message" => "لا يوجد مواد"]);
    }
    public function teacherexperience($id)
    {
        $t = teacher::where('userId', $id)->get()->first();
        // $t = teacher::find($id);
        if ($t) {
            return response(["expereince" => $t, "message" => "تم جلب البيانات بنجاح"]);
        }
        return response(["message" => "لم يتم ايجاد البيانات "]);
    }
    public function editTeacherExp($id, Request $request)
    {
        $t = teacher::where('userId', $id)->get()->first();
        if ($t) {
            $t->update($request->all());
            return response(["experience" => $t, "message" => "تم التعديل بنجاح"]);
        }
        return response(["message" => "فشل عملية التعديل"]);
    }
    public function editTeacherSubjects($id, Request $request)
    {
        $t = teacher::where('userId', $id)->get()->first();
        $validator = Validator::make(request()->all(), [
            "subjects" => "array",
        ]);

        if ($validator->fails()) {
            $msg = (json_decode(json_encode($validator->errors(), JSON_UNESCAPED_UNICODE), true));
            $string = '';
            foreach (array_values($msg) as $value) {
                $string .=  $value[0] . " , ";
            }
            return response(["message" => "$string"], 422);
        }
        if (sizeof($request->subjects) == 0) {

            $teacherSubjects = subjectteacher::where('tId', $t->tId)->get();
            if (sizeof($teacherSubjects) != 0) {
                for ($i = 0; $i < sizeof($teacherSubjects); $i++) {
                    $teacherSubjects[$i]->delete();
                }
                return response(["message" => "تم حذف جميع المواد"]);
            }
            return response(["message" => "لا يوجد مواد لحذفها"]);
        }
        $teacherSubjects = subjectteacher::where('tId', $t->tId)->get();
        if (sizeof($teacherSubjects) == 0) {
            if (sizeof($request->subjects) != 0) {
                for ($i = 0; $i < sizeof($request->subjects); $i++) {
                    $subject = subject::find($request->subjects[$i]);
                    if ($subject) {
                        $teacherSubject = new subjectteacher();
                        $teacherSubject->tId = $t->tId;
                        $teacherSubject->sId = $request->subjects[$i];
                        $teacherSubject->save();
                    }
                }
                return response(["message" => "تم إضافة المواد بنجاح"]);
            }
            return response(["message" => "لا يوجد مواد لإضافتها"]);
        }
        $deletedSubjects = [];
        $addedSubjects = [];
        $count = 0;
        for ($i = 0; $i < sizeof($teacherSubjects); $i++) {
            $isDel = false;
            for ($j = 0; $j < sizeof($request->subjects); $j++) {
                if ($teacherSubjects[$i]->sId == $request->subjects[$j]) {
                    $isDel = true;
                }
            }
            if ($isDel == false) {
                $deletedSubjects[$count] = $teacherSubjects[$i];
                $count++;
            }
        }
        $count = 0;
        for ($i = 0; $i < sizeof($request->subjects); $i++) {
            $isAdded = false;
            for ($j = 0; $j < sizeof($teacherSubjects); $j++) {
                if ($teacherSubjects[$j]->sId == $request->subjects[$i]) {
                    $isAdded = true;
                }
            }
            if ($isAdded == false) {
                $addedSubjects[$count] = $request->subjects[$i];
                $count++;
            }
        }
        if (sizeof($deletedSubjects) != 0) {
            for ($i = 0; $i < sizeof($deletedSubjects); $i++) {
                $teacherSubject = subjectteacher::where('sId', $deletedSubjects[$i]->sId)->get();
                if (sizeof($teacherSubject) != 0) {
                    for ($j = 0; $j < sizeof($teacherSubject); $j++) {
                        $teacherSubject[$j]->delete();
                    }
                }
            }
        }
        if (sizeof($addedSubjects) != 0) {
            for ($i = 0; $i < sizeof($addedSubjects); $i++) {
                $teacherSubject = new subjectteacher();
                $teacherSubject->tId = $t->tId;
                $teacherSubject->sId = $addedSubjects[$i];
                $teacherSubject->save();
            }
        }
        return response(["message" => "تم تعديل المواد بنجاح"]);
    }
    public function getAlternativeTeachers($id, Request $request)
    {
        $messages =  array(
            'day.required' => 'اليوم مطلوب'
        );
        $validator = Validator::make(request()->all(), [
            "day" => "required",
        ], $messages);
        if ($validator->fails()) {
            $msg = (json_decode(json_encode($validator->errors(), JSON_UNESCAPED_UNICODE), true));
            $string = '';
            foreach (array_values($msg) as $value) {
                $string .=  $value[0] . " , ";
            }
            return response(["message" => "$string"], 422);
        }
        if ($request->day != "السبت" && $request->day != "الأحد" && $request->day != "الاثنين" && $request->day != "الثلاثاء" && $request->day != "الأربعاء" && $request->day != "الخميس" && $request->day != "الجمعة") {
            return response(["message" => "تم ادخال قيمة خاطئة لليوم"]);
        }
        $finalReaponse = [];
        $tc = TeacherCourse::where('teacherId', $id)->get();
        $teacherCourses = [];
        $teachers = [];
        $data = [];
        $c = 0;
        $response = [];
        $index = 0;
        $count = 0;
        if (sizeof($tc) != 0) {
            for ($i = 0; $i < sizeof($tc); $i++) {
                $course = Course::where('courseId', $tc[$i]->courseId)->where('courseDays', 'like', '%' . $request->day . '%')->where('courseStatus', "مفتوحة")->get()->first();
                if ($course) {
                    $teacherCourses[$count] = [
                        "courseId" => $course->courseId,
                        "subjectId" => $course->subjectId,
                        "typeId" => $course->typeId,
                        "startTime" => $course->startTime,
                        "endTime" => date('H:i:s', strtotime($course->startTime) + (60 * 60 * $course->duration))
                    ];
                    $count++;
                }
            }
            if (sizeof($teacherCourses) != 0) {
                for ($i = 0; $i < sizeof($teacherCourses); $i++) {
                    $subjectTeacher = subjectteacher::where('sId', $teacherCourses[$i]["subjectId"])->get();
                    for ($j = 0; $j < sizeof($subjectTeacher); $j++) {
                        if ($subjectTeacher[$j]->tId != $id) {
                            $teachers[$index] = [$subjectTeacher[$j]->tId];
                            $index++;
                        }
                    }
                    $data[$i] = [
                        "subjectId" => $teacherCourses[$i]["subjectId"],
                        "typeId" => $teacherCourses[$i]["typeId"],
                        "startTime" => $teacherCourses[$i]["startTime"],
                        "endTime" => $teacherCourses[$i]["endTime"],
                        "teachers" => $teachers
                    ];
                    $index = 0;
                    $teachers = [];
                }
                if (sizeof($data) == 0) {
                    return response(["message" => "لا يوجد أستاتذة بدلاء"]);
                }
                for ($i = 0; $i < sizeof($data); $i++) {
                    for ($j = 0; $j < sizeof($data[$i]["teachers"]); $j++) {
                        $teacherCourse = TeacherCourse::where('teacherId', $data[$i]["teachers"][$j])->get();
                        $sub = [];
                        $index = 0;
                        for ($k = 0; $k < sizeof($teacherCourse); $k++) {
                            $course = Course::where('courseId', $teacherCourse[$k]->courseId)->where('courseDays', 'like', '%' . $request->day . '%')->get()->first();
                            if ($course) {
                                $startTime = $course->startTime;
                                $endTime = date('H:i:s', strtotime($course->startTime) + (60 * 60 * $course->duration));
                                if ((strtotime($startTime) >= strtotime($data[$i]["startTime"]) && strtotime($startTime) < strtotime($data[$i]["endTime"]) || (strtotime($endTime) > strtotime($data[$i]["startTime"]) && strtotime($endTime) <= strtotime($data[$i]["endTime"])))) {
                                    $sub[$index] = $course;
                                    $index++;
                                }
                            }
                        }
                        if (sizeof($sub) == 0) {
                            $teacher = teacher::find($data[$i]["teachers"][$j][0]);
                            $user = User::find($teacher->userId);
                            $response[$c] = [
                                "teacherName" => $user->name
                            ];
                            $c++;
                        }
                    }
                    if (sizeof($response) != 0) {
                        $finalReaponse[$i] = [
                            "subjectId" => $data[$i]["subjectId"],
                            "typeId" => $data[$i]["typeId"],
                            "startTime" => $data[$i]["startTime"],
                            "endTime" => $data[$i]["endTime"],
                            "alternativeTeachers" => $response
                        ];
                    } else {
                        $response = ["teacherName" => "لا يوجد بدائل لهذه الجلسة"];
                        $finalReaponse[$i] = [
                            "subjectId" => $data[$i]["subjectId"],
                            "typeId" => $data[$i]["typeId"],
                            "startTime" => $data[$i]["startTime"],
                            "endTime" => $data[$i]["endTime"],
                            "alternativeTeachers" => $response
                        ];
                    }
                    $response = [];
                    $c = 0;
                }
                return response(["data" => $finalReaponse, "message" => ["تم ايجاد البدلاء بنجاح"]]);
            }
            return response(["message" => "لا يوجد حصص للأستاذ في هذا اليوم"]);
        }
        return response(["message" => "لا يوجد حصص للأستاذ في هذا اليوم"]);
    }
}
