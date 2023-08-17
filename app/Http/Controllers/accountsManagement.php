<?php

namespace App\Http\Controllers;

use App\Exports\UsersExport;
use App\Imports\TimeMonitoringImport;
use App\Imports\TestyImport;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\authorization;
use App\Models\dynammicTables;
use App\Models\retired;
use App\Models\role;
use App\Models\tableColumns;
use App\Models\teacher;
use App\Models\User;
use App\Models\userrole;
use App\Models\userauth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Tymon\JWTAuth\Facades\JWTFactory;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class accountsManagement extends Controller
{

    public function getUsers()
    {
        $user = User::get();
        $userData = [];
        if (sizeof($user) != 0) {
            $usersTable = dynammicTables::where('name','users')->get()->first();
            $userColumns = tableColumns::where('tableId',$usersTable->tableId)->get();
            $tableColumns = Schema::getColumnListing("users");
            for ($j = 0; $j < sizeof($user); $j++) {
                    $userroles = userrole::where('userId', $user[$j]->userId)->get();
                    $roles = [];
                    for ($i = 0; $i < sizeof($userroles); $i++) {
                        $roles[$i] = role::find($userroles[$i]->roleId)->role;
                    }
                    for($c=0;$c<sizeof($tableColumns)-2;$c++){
                        if($userColumns[$c]->EnglishName=="userId" || $userColumns[$c]->EnglishName=="password" || $userColumns[$c]->EnglishName=="imageIdentity"){
                            $userInfo[$c]=[
                                "label"=>"".$userColumns[$c]->arabicName."",
                                "viewType"=>"hidden",
                                "value"=>$user[$j]["".$userColumns[$c]->EnglishName.""]
                            ];
                        }
                        else{
                            $userInfo[$c]=[
                                "label"=>"".$userColumns[$c]->arabicName."",
                                "viewType"=>"visible",
                                "value"=>$user[$j]["".$userColumns[$c]->EnglishName.""]
                            ];
                        }
                        
                    }
                    $userData[$j] = [
                        'userInfo' => $userInfo,
                        'userRoles' => $roles
                    ];
                    $userInfo=[];
            }
            if (sizeof($userData) != 0) {
                return response(['userData' => $userData, 'message' => 'تم جلب المستخدمين بنجاح']);
            }
            return response(['userData' => [], 'message' => 'لا يوجد مستخدمين لعرضهم']);
        }
        return response(['userData' => [], 'message' => 'لا يوجد مستخدمين لعرضهم']);
    }

    public function deleteUser($id)
    {
        $user = User::find($id);
        if ($user) {
            $user->delete();
            return response(["message" => "تم الحذف بنجاح"]);
        } else {
            return response(["message" => "المستخدم غير موجود"]);
        }
    }
    public function profile($id)
    {
        $user = User::find($id);
        if ($user) {
            $userroles = userrole::where('userId', $id)->get();
            $roles = [];
            if (sizeof($userroles) != 0) {
                for ($i = 0; $i < sizeof($userroles); $i++) {
                    $roles[$i] = role::find($userroles[$i]->roleId);
                }
            }
            $userpermissions = userauth::where('userId', $id)->get();
            $per = [];
            if (sizeof($userpermissions) != 0) {
                for ($i = 0; $i < sizeof($userpermissions); $i++) {
                    $per[$i] = authorization::find($userpermissions[$i]->aId)->name;
                }
            }
            $user_retired = null;
            if ($user->accountStatus == "مستقيل") {
                $user_retired = retired::where('userId', $user->userId)->get()->first();
            }
            $user_data = [
                'user' => $user,
                'roles' => $roles,
                'permissions' => $per,
                'user_retired' => $user_retired
            ];
            return response([
                "message" => "User profile data",
                "data" => $user_data
            ]);
        }
        return response(['message' => "المستخدم غير موجود"]);
    }
    public function searchUserByname(Request $name)
    {
        $messages =  array(
            'name.required' => 'الاسم مطلوب'
        );
        $validator = Validator::make($name->all(), [
            'name' => "required"
        ], $messages);
        if ($validator->fails()) {
            $msg = (json_decode(json_encode($validator->errors(), JSON_UNESCAPED_UNICODE), true));
            $string = '';
            foreach (array_values($msg) as $value) {
                $string .=  $value[0] . " , ";
            }
            return response(["message" => "$string"], 422);
        }
        $user = User::where('name', 'like', '%' . $name->name . '%')->get();
        $usersTable = dynammicTables::where('name','users')->get()->first();
            $userColumns = tableColumns::where('tableId',$usersTable->tableId)->get();
            $tableColumns = Schema::getColumnListing("users");
        $userData = [];
        if (sizeof($user) != 0) {
            for ($i = 0; $i < sizeof($user); $i++) {
                $userroles = userrole::where('userId', $user[$i]->userId)->get();
                $roles = [];
                for ($j = 0; $j < sizeof($userroles); $j++) {
                    $roles[$j] = role::find($userroles[$j]->roleId)->role;
                }
                // $userInfo = [
                //     'userName' => $user[$i]->userName,
                //     'name' => $user[$i]->name,
                //     'userId' => $user[$i]->userId,
                //     'phoneNumber' => $user[$i]->phoneNumber,
                //     'accountStatus' => $user[$i]->accountStatus,
                //     'workTime' => $user[$i]->workTime
                // ];
                for($c=0;$c<sizeof($tableColumns)-2;$c++){
                    if($userColumns[$c]->EnglishName=="userId" || $userColumns[$c]->EnglishName=="password" || $userColumns[$c]->EnglishName=="imageIdentity"){
                        $userInfo[$c]=[
                            "label"=>"".$userColumns[$c]->arabicName."",
                            "viewType"=>"hidden",
                            "value"=>$user[$i]["".$userColumns[$c]->EnglishName.""]
                        ];
                    }
                    else{
                        $userInfo[$c]=[
                            "label"=>"".$userColumns[$c]->arabicName."",
                            "viewType"=>"visible",
                            "value"=>$user[$i]["".$userColumns[$c]->EnglishName.""]
                        ];
                    }
                    
                }
                $userData[$i] = [
                    'userInfo' => $userInfo,
                    'userRoles' => $roles
                ];
                $userInfo=[];
            }
            return response(['userData' => $userData, "message" => "تم احضار النتائج بنجاح"]);
        } else {
            return response(["message" => "لا يوجد نتائج لعرضها"]);
        }
    }
    public function searchUserByuserName(Request $name)
    {
        $messages =  array(
            'userName.required' => 'اسم المستخدم مطلوب'
        );
        $validator = Validator::make($name->all(), [
            'userName' => "required"
        ], $messages);
        if ($validator->fails()) {
            $msg = (json_decode(json_encode($validator->errors(), JSON_UNESCAPED_UNICODE), true));
            $string = '';
            foreach (array_values($msg) as $value) {
                $string .=  $value[0] . " , ";
            }
            return response(["message" => "$string"], 422);
        }
        $user = User::where('userName', 'like', '%' . $name->userName . '%')->get();
        $user_data = [];
        if (sizeof($user) != 0) {
            for ($i = 0; $i < sizeof($user); $i++) {
                $user_data[$i] = [
                    'userName' => $user[$i]->userName,
                    'userId' => $user[$i]->userId
                ];
            }
            return response(['data' => $user_data, "message" => "تم احضار النتائج بنجاح"]);
        } else {
            return response(["message" => "لا يوجد نتائج لعرضها"]);
        }
    }
    public function searchUserByRole($id)
    {
        $userroles = userrole::where('roleId', $id)->get();
        $usersTable = dynammicTables::where('name','users')->get()->first();
            $userColumns = tableColumns::where('tableId',$usersTable->tableId)->get();
            $tableColumns = Schema::getColumnListing("users");
        $user = [];
        //$userInfo = [];
        $userData = [];
        if (sizeof($userroles) != 0) {
            for ($i = 0; $i < sizeof($userroles); $i++) {
                $user[$i] = User::find($userroles[$i]->userId);
            }
        }
        if (sizeof($user) != 0) {
            for ($i = 0; $i < sizeof($user); $i++) {
                $userroles = userrole::where('userId', $user[$i]->userId)->get();
                $roles = [];
                for ($j = 0; $j < sizeof($userroles); $j++) {
                    $roles[$j] = role::find($userroles[$j]->roleId)->role;
                }
                // $userInfo = [
                //     'userName' => $user[$i]->userName,
                //     'name' => $user[$i]->name,
                //     'userId' => $user[$i]->userId,
                //     'phoneNumber' => $user[$i]->phoneNumber,
                //     'accountStatus' => $user[$i]->accountStatus,
                //     'workTime' => $user[$i]->workTime
                // ];
                for($c=0;$c<sizeof($tableColumns)-2;$c++){
                    if($userColumns[$c]->EnglishName=="userId" || $userColumns[$c]->EnglishName=="password"||$userColumns[$c]->EnglishName=="imageIdentity"){
                        $userInfo[$c]=[
                            "label"=>"".$userColumns[$c]->arabicName."",
                            "viewType"=>"hidden",
                            "value"=>$user[$i]["".$userColumns[$c]->EnglishName.""]
                        ];
                    }
                    else{
                        $userInfo[$c]=[
                            "label"=>"".$userColumns[$c]->arabicName."",
                            "viewType"=>"visible",
                            "value"=>$user[$i]["".$userColumns[$c]->EnglishName.""]
                        ];
                    }
                    
                }
                $userData[$i] = [
                    'userInfo' => $userInfo,
                    'userRoles' => $roles
                ];
                $userInfo=[];
            }
            return response(['userData' => $userData, "message" => "تم احضار النتائج بنجاح"]);
        } else {
            return response(["message" => "لا يوجد نتائج لعرضها"]);
        }
    }
    public function editUserProfil(Request $request)
    {

        $messages =  array(
            'phoneNumber.numeric' => 'يجب أن يكون رقم الهاتف أرقام فقط',
            'phoneNumber.starts_with' => 'يجب أن يبدأ رقم الهاتف ب 09',
            'salary.numeric' => 'الراتب يجب أن يكون رقم',
            'accountStatus.in' => 'حالة الحساب يجب أن تأخذ أحد القيمتين موظف أو مستقيل فقط',
            'workTime.in' => 'وقت الدوام يجب أن تأخذ أحد القيمتين صباحي أو مسائي فقط',
            'password.min' => "يجب أن تتألف كلمة السر من 8 محارف على الأقل",
            'email.email' => 'حقل الايميل يجب أن يحوي على ايميل فقط',
            'id.required' => 'رقم تعريف المستخدم مطلوب للتعديل',
        );
        $validator = Validator::make(request()->all(), [
            "phoneNumber" => "numeric|starts_with:09",
            "salary" => "numeric",
            "password" => "min:8",
            "email" => "email",
            "id" => "required"
        ], $messages);
        if ($validator->fails()) {
            $msg = (json_decode(json_encode($validator->errors(), JSON_UNESCAPED_UNICODE), true));
            $string = '';
            foreach (array_values($msg) as $value) {
                $string .=  $value[0] . " , ";
            }
            return response(["message" => "$string"], 422);
        }
        
        $user = User::find($request->id);
        $dynamicTable = dynammicTables::where('name','users')->get()->first();
        $tableColumns = tableColumns::where('tableId',$dynamicTable->tableId)->where("isUnique",'1')->get();
        for($i=0;$i<sizeof($tableColumns);$i++){
            if($request[$tableColumns[$i]->EnglishName]){
                $u = User::where($tableColumns[$i]->EnglishName, $request[$tableColumns[$i]->EnglishName])->get();
                for($j=0;$j<sizeof($u);$j++){
                    if ($u[$j]->userId != $request->id) {
                        return response(["message" => " لا يمكن تكرار حقل " . $tableColumns[$i]->arabicName . " لأكثر من حساب  "]);
                    }
                }
            }
        }
        if ($user) {
            if ($request->phoneNumber != null) {
                $len = strlen($request->phoneNumber);
                if ($len != 10) {
                    return response(["message" => "يجب أن يتألف رقم الهاتف من 10 أرقام فقط"]);
                }
            }
            $msg2 = [];

            if ($user->accountStatus == "موظف" && $request->accountStatus && $request->accountStatus == "مستقيل") {
                DB::beginTransaction();
                $messages =  array(
                    'cause.required' => 'حقل سبب الاستقالة مطلوب',
                    'retieredDate.required' => 'حقل تاريخ الاستقالة مطلوب'
                );
                $validator = Validator::make(request()->all(), [
                    "cause" => "required",
                    "retieredDate" => "required"
                ], $messages);
                if ($validator->fails()) {
                    $msg2 = json_encode($validator->errors(), JSON_UNESCAPED_UNICODE);
                    DB::rollBack();
                } else {
                    $retired = new retired();
                    $retired->cause = $request->cause;
                    $retired->retieredDate = $request->retieredDate;
                    $retired->userId = $request->id;
                    $retired->save();
                    $user->accountStatus = "مستقيل";
                    $update_status = $user->update();
                    if ($update_status) {
                        DB::commit();
                        $msg2 = "تم إضافة الاستقالة بنجاح";
                    } else {
                        DB::rollBack();
                        $msg2 = "فشل إضافة الاستقالة";
                    }
                }
            }
            if ($request->password) {
                $user->password = bcrypt($request->password);
                $user->update();
            }
        $user->update($request->except(['accountStatus', 'password']));
        $tableColumns = tableColumns::where('tableId',$dynamicTable->tableId)->where("columnType","إضافية")->get();
        for($i=0;$i<sizeof($tableColumns);$i++){
            if($request[$tableColumns[$i]->EnglishName]){
                $user[$tableColumns[$i]->EnglishName]=$request[$tableColumns[$i]->EnglishName];
            }
        }
        $user->update();
            return response(['data' => $user, "message" =>  "تم التعديل بنجاح"]);
        } else {
            return response(["message" => "فشلت عملية التعديل"]);
        }
    }
    public function updateUserRoles($id, Request $request)
    {
        $messages =  array(
            'roles.required' => 'حقل الأدوار يجب أن لا يكون فارغ'
        );
        $validator = Validator::make(request()->all(), [
            "roles" => "array|required",
        ], $messages);
        if ($validator->fails()) {
            $msg = (json_decode(json_encode($validator->errors(), JSON_UNESCAPED_UNICODE), true));
            $string = '';
            foreach (array_values($msg) as $value) {
                $string .=  $value[0] . " , ";
            }
            return response(["message" => "$string"], 422);
        }
        $deletedRoles = [];
        $addedRoles = [];
        $userRoles = userrole::where('userId', $id)->get();
        $count = 0;
        for ($i = 0; $i < sizeof($userRoles); $i++) {
            $isDel = false;
            for ($j = 0; $j < sizeof($request->roles); $j++) {
                if ($userRoles[$i]->roleId == $request->roles[$j]) {
                    $isDel = true;
                }
            }
            if ($isDel == false) {
                $deletedRoles[$count] = $userRoles[$i];
                $count++;
            }
        }
        $count = 0;
        for ($i = 0; $i < sizeof($request->roles); $i++) {
            $isAdded = false;
            for ($j = 0; $j < sizeof($userRoles); $j++) {
                if ($userRoles[$j]->roleId == $request->roles[$i]) {
                    $isAdded = true;
                }
            }
            if ($isAdded == false) {
                $addedRoles[$count] = $request->roles[$i];
                $count++;
            }
        }
        if (sizeof($deletedRoles) != 0) {
            for ($i = 0; $i < sizeof($deletedRoles); $i++) {
                $userRole = userrole::where('roleId', $deletedRoles[$i]->roleId)->get();
                if (sizeof($userRole) != 0) {
                    for ($j = 0; $j < sizeof($userRole); $j++) {
                        $userRole[$j]->delete();
                    }
                }
            }
        }
        if (sizeof($addedRoles) != 0) {
            for ($i = 0; $i < sizeof($addedRoles); $i++) {
                $role = role::find($addedRoles);
                if ($role) {
                    $userRole = new userrole();
                    $userRole->userId = $id;
                    $userRole->roleId = $addedRoles[$i];
                    $userRole->save();
                }
            }
        }
        return response(['message' => "تم تعديل الأدوار بنجاح"]);
    }
    public function updateUserِAuthorizations($id, Request $request)
    {
        $validator = Validator::make(request()->all(), [
            "permissions" => "array",
        ]);
        if ($validator->fails()) {
            $msg = (json_decode(json_encode($validator->errors(), JSON_UNESCAPED_UNICODE), true));
            $string = '';
            foreach (array_values($msg) as $value) {
                $string .=  $value[0] . " , ";
            }
            return response(["message" => "$string"], 422);
        }
        $deletedPermissions = [];
        $addedPermissions = [];
        $userPermissions = userauth::where('userId', $id)->get();

        if (sizeof($request->permissions) == 0) {
            if (sizeof($userPermissions) != 0) {
                for ($i = 0; $i < sizeof($userPermissions); $i++) {
                    $userPermissions[$i]->delete();
                }
                return response(["message" => "تم حذف جميع الصلاحيات"]);
            }
            return response(["message" => "لا يوجد صلاحيات لحذفها"]);
        }

        $count = 0;
        for ($i = 0; $i < sizeof($userPermissions); $i++) {
            $isDel = false;
            for ($j = 0; $j < sizeof($request->permissions); $j++) {

                if ($userPermissions[$i]->aId == $request->permissions[$j]) {
                    $isDel = true;
                }
            }
            if ($isDel == false) {
                $deletedPermissions[$count] = $userPermissions[$i];
                $count++;
            }
        }
        $count = 0;
        for ($i = 0; $i < sizeof($request->permissions); $i++) {
            $isAdded = false;
            for ($j = 0; $j < sizeof($userPermissions); $j++) {
                if ($userPermissions[$j]->aId == $request->permissions[$i]) {
                    $isAdded = true;
                }
            }
            if ($isAdded == false) {
                $addedPermissions[$count] = $request->permissions[$i];
                $count++;
            }
        }
        if (sizeof($deletedPermissions) != 0) {
            for ($i = 0; $i < sizeof($deletedPermissions); $i++) {
                $userPermission = userauth::where('aId', $deletedPermissions[$i]->aId)->get();
                if (sizeof($userPermission) != 0) {
                    for ($j = 0; $j < sizeof($userPermission); $j++) {
                        $userPermission[$j]->delete();
                    }
                }
            }
        }
        if (sizeof($addedPermissions) != 0) {
            for ($i = 0; $i < sizeof($addedPermissions); $i++) {
                $auth = authorization::find($addedPermissions[$i]);
                if ($auth) {
                    $userPermission = new userauth();
                    $userPermission->userId = $id;
                    $userPermission->aId = $addedPermissions[$i];
                    $userPermission->save();
                }
            }
        }
        return response(["message" => "تم تعديل الصلاحيات بنجاح"]);
    }
    public function getEmpNames()
    {
        $users = User::get();
        $data = [];
        $count=0;
        if (sizeof($users) != 0) {
            for ($i = 0; $i < sizeof($users); $i++) {
                $teacher = teacher::where('userId', $users[$i]->userId)->get();
                if (sizeof($teacher) == 0) {
                    $data[$count] = [
                        "userId" => $users[$i]->userId,
                        "userName" => $users[$i]->name
                    ];
                    $count++;
                }
            }
            if (sizeof($data) != 0) {
                return response(["data" => $data, "message" => "تم احضار المستخدمين بنجاح"]);
            }
            return response(["message" => "لا يوجد مستخدمين"]);
        }
        return response(["message" => "لا يوجد مستخدمين"]);
    }
    public function getTeachersNames()
    {
        $data = [];
        $teachers = teacher::get();
        if (sizeof($teachers) != 0) {
            for ($i = 0; $i < sizeof($teachers); $i++) {
                $user = User::find($teachers[$i]->userId);
                if ($user) {
                    $data[$i] = [
                        "userId" => $user->userId,
                        "teacherId" => $teachers[$i]->tId,
                        "userName" => $user->name,
                        "userName" => $user->userName
                    ];
                }
            }
            return response(["data" => $data, "message" => "تم احضار المستخدمين بنجاح"]);
        }
        return response(["message" => "لا يوجد مستخدمين"]);
    }
    public function showUserRetierd($id)
    {
        $user = User::find($id);
        if ($user) {
            $retired = retired::where('userId', $id)->get()->first();
            if ($retired) {
                return response(["data" => $retired, "message" => "تم احضار الاستقالة بنجاح"]);
            }
            return response(["message" => "الاستقالة غير موجودة"]);
        }
        return response(["message" => "الاستقالة غير موجودة"]);
    }
}
