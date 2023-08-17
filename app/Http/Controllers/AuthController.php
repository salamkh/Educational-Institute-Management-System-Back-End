<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\authorization;
use App\Models\dynammicTables;
use App\Models\financialAccounts;
use App\Models\financialUserAccount;
use App\Models\role;
use App\Models\tableColumns;
use App\Models\User;
use App\Models\userrole;
use App\Models\userauth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTFactory;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use PhpParser\Node\Expr\Cast\String_;
use Ramsey\Uuid\Codec\StringCodec;
use Symfony\Component\Mime\Encoder\Rfc2231Encoder;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */


    public function register(Request $request)
    {
        $messages =  array(
            'phoneNumber.required' => 'رقم الهاتف مطلوب',
            'phoneNumber.numeric' => 'يجب أن يكون رقم الهاتف أرقام فقط',
            'phoneNumber.starts_with' => 'يجب أن يبدأ رقم الهاتف ب 09',
            'name.required' => 'حقل الاسم مطلوب',
            'userName.required' => 'حقل اسم المستخدم مطلوب',
            'salary.required' => 'حقل الراتب مطلوب',
            // 'imageIdentity.required' => 'حقل صورة الهوية مطلوب',
            'salary.numeric' => 'الراتب يجب أن يكون رقم',
            'workTime.required' => 'حقل أوقات الدوام  مطلوب',
            'password.min' => "يجب أن تتألف كلمة السر من 8 محارف على الأقل",
            'email.email' => 'حقل الايميل يجب أن يحوي على ايميل فقط',
            'address.required' => 'حقل العنوان   مطلوب',
            'roles.required' => 'حقل الأدوار لا يجب أن يكون فارغ ',
            'password.required' => 'حقل كلمة السر مطلوب',

        );
        $validator = Validator::make(request()->all(), [
            "name" => "required",
            "userName" => "required",
            "phoneNumber" => "required|numeric|starts_with:09",
            "salary" => "required|numeric",
            // "imageIdentity" => "required",
            "workTime" => "required",
            "address" => "required",
            "roles" => "required|array",
            "password" => "required|min:8",
            "email" => "email",
            "permissions" => "array"
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
        $FileName = $request->imageIdentity;
        if ($request->hasFile('imageIdentity')) {
            $FileName =  $request->imageIdentity->getClientOriginalName();
            Storage::disk('public')->putFileAs('/images', $request->imageIdentity, $FileName);
        }
        $tableColumns = tableColumns::where('tableId', $userTable->tableId)->where('columnType', 'إضافية')->get();
        for ($i = 0; $i < sizeof($tableColumns); $i++) {
            if ($request[$tableColumns[$i]->EnglishName]) {
                $additionalInfo[$tableColumns[$i]->EnglishName] = $request[$tableColumns[$i]->EnglishName];
            }
        }
        DB::beginTransaction();
        $userID =  User::insertGetId(array_merge([
            'name' => $request->name,
            'email' => $request->email,
            'phoneNumber' => $request->phoneNumber,
            'salary' => $request->salary,
            'userName' => $request->userName,
            'imageIdentity' =>  $request->phoneNumber,
            'accountStatus' => "موظف",
            'workTime' => $request->workTime,
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
        for ($i = 0; $i < sizeof($request->roles); $i++) {
            $role = role::find($request->roles[$i]); {
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
        if ($userID) {
            $user = User::find($userID);
            return response(["user" => $user, "message" => "تم التسجيل بنجاح"]);
        }
        return response(["message" => "فشل عملية التسجيل"]);
    }
    public function login(Request $request)
    {
        $messages =  array(
            'userName.required' => ' اسم المستخدم مطلوب',
            'password.required' => 'كلمة السر مطلوبة'
        );
        $validator = Validator::make($request->all(), [
            'userName' => 'required',
            'password' => 'required',
        ], $messages);

        if ($validator->fails()) {
            $msg = (json_decode(json_encode($validator->errors(), JSON_UNESCAPED_UNICODE), true));
            $string = '';
            foreach (array_values($msg) as $value) {
                $string .=  $value[0] . " , ";
            }
            return response(["message" => "$string"], 422);
        }
        $credentials = request(['userName', 'password']);
        $user = User::where('userName', $request->userName)->get()->first();
        if (!$user) {
            return response(["message" => "المستخدم غير موجود"]);
        }
        if ($user->accountStatus == "موظف") {
            if (!$token = auth()->attempt($credentials)) {
                return response(['message' => 'فشل تسجيل الدخول تحقق من البيانات المدخلة'], 401);
            }
            $user = auth()->user();
            $user->accessToken = $token;
            $user->expires_in = JWTFactory::getTTL() * 60;
            $user->tokenType = "bearer";
            if ($user) {
                return response(['user' => $user, "message" => "تم تسجيل الدخول بنجاح"]);
            }
            return response(["message" => "فشل تسجيل الدخول "]);
        }
        return response(["message" => "حالة الحساب مستقيل لا يمكن تسجيل الدخول"]);
    }

    public function logout()
    {
        auth()->logout();

        return response(['message' => 'Successfully logged out']);
    }
    public function refresh(Request $request)
    {
        $user = auth()->user();
        $user->accessToken = $request->accessToken;
        $user->tokenType = "bearer";
        return response($user);
    }
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => JWTFactory::getTTL() * 60
        ]);
    }
    public function me()
    {
        $dynamicTable = dynammicTables::where('name',"users")->get()->first();
        $user = auth()->user();
        if($user){
            $userroles = userrole::where('userId', $user->userId)->get();
                    $roles = [];
                    for ($i = 0; $i < sizeof($userroles); $i++) {
                        $roles[$i] = role::find($userroles[$i]->roleId)->role;
                    }
                    $userpermissions = userauth::where('userId', $user->userId)->get();
                    $permissions = [];
                    for ($i = 0; $i < sizeof($userpermissions); $i++) {
                        $permissions[$i] = authorization::find($userpermissions[$i]->aId)->name;
                    }
        if ($dynamicTable) {
            $tableColumns = tableColumns::where('tableId', $dynamicTable->tableId)->get();
            $columns = [];
            for ($j = 0; $j < sizeof($tableColumns); $j++) {
                    if ($tableColumns[$j]->EnglishName == "userId" || $tableColumns[$j]->EnglishName == "password"||$tableColumns[$j]->EnglishName == "imageIdentity" ) {
                        $columns[$j] = [
                            "arabicName" => $tableColumns[$j]->arabicName,
                            "EnglishName" => $tableColumns[$j]->EnglishName,
                            "viewType" => "hidden",
                            "value"=>$user[$tableColumns[$j]->EnglishName]
                        ];
                    } else {
                        $columns[$j] = [
                            "arabicName" => $tableColumns[$j]->arabicName,
                            "EnglishName" => $tableColumns[$j]->EnglishName,
                            "viewType" => "visible",
                            "value"=>$user[$tableColumns[$j]->EnglishName]
                        ];
                    }   
            }
            $data=[
                "userId"=> $user->userId,
                "userData"=>$columns,
                "userRoles"=>$roles,
                "userPermissions"=>$permissions
            ];
            return response(["data" => $data, "message" => "تم احضار الحقول بنجاح"]);
        }
        return response(["message" => "هذه الميزة ليست ديناميكية"]);
        }
        return response(["message"=>"المستخدم غير موجود"]);
        /////////////
        return response(["data" => auth()->user(), "message" => "تم احضار البيانات بنجاح"]);
    }
    public function editProfil(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $messages =  array(
            'phoneNumber.numeric' => 'يجب أن يكون رقم الهاتف أرقام فقط',
            'phoneNumber.starts_with' => 'يجب أن يبدأ رقم الهاتف ب 09',
            'salary.numeric' => 'الراتب يجب أن يكون رقم',
            'accountStatus.in' => 'حالة الحساب يجب أن تأخذ أحد القيمتين موظف أو مستقيل فقط',            'imageIdentity.required' => 'حقل صورة الهوية مطلوب',
            'workTime.in' => 'وقت الدوام يجب أن تأخذ أحد القيمتين صباحي أو مسائي فقط',
            'password.min' => "يجب أن تتألف كلمة السر من 8 محارف على الأقل",
            'email.email' => 'حقل الايميل يجب أن يحوي على ايميل فقط',
        );
        $validator = Validator::make(request()->all(), [
            "phoneNumber" => "numeric|starts_with:09",
            "salary" => "numeric",
            "accountStatus" => "in:['مستقيل','موظف']",
            "workTime" => "in:['صباحي','مسائي']",
            "password" => "min:8",
            "email" => "email",
        ], $messages);
        if ($validator->fails()) {
            return response(json_encode($validator->errors(), JSON_UNESCAPED_UNICODE), 422);
            // return response(, 422);
        }
        $user->update($request->except(['imageIdentity']));
        return response(["user" => $user, "message" => "تم التعديل بنجاح"]);
    }
}
