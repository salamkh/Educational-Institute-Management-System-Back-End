<?php

namespace App\Http\Controllers;

use App\Exports\timeMonitoringExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Models\authorization;
use App\Models\role;
use App\Models\subject;
use App\Models\subjectteacher;
use App\Models\teacher;
use App\Models\timeMonitoring;
use App\Models\User;
use App\Models\userrole;
use App\Models\userauth;
use Dotenv\Repository\RepositoryInterface;
use Illuminate\Support\Facades\Storage;
use Namshi\JOSE\Signer\OpenSSL\RSA;
use TimeMonitoring as GlobalTimeMonitoring;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UsersExport;
use App\Imports\TimeMonitoringImport;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Contracts\Cache\Store;

use function PHPUnit\Framework\returnSelf;
use Illuminate\Contracts\Filesystem\FileNotFoundException;

class timeMonitoringController extends Controller
{
    public function create(Request $request)
    {
        $message = array(
            "userId.required" => "رقم تعريف المستخدم مطلوب",
            "userId.numeric" => "رقم تعريف المستخدم يجب أن يحوي أرقام فقط",
            "startTime.required" => "وقت الدخول مطلوب",
            "exitTime.required" => "وقت الخروج مطلوب",
            "date.required" => "التاريخ مطلوب"
        );
        $validator = Validator::make(request()->all(), [
            "userId" => "required|numeric",
            "startTime" => "required",
            "exitTime" => "required",
            "date" => "required"
        ], $message);

        if ($validator->fails()) {
            return response()->json(["message" => json_encode($validator->errors(), JSON_UNESCAPED_UNICODE)], 400);
        }
        $timeMon = timeMonitoring::where('userId', $request->userId)->where('date', $request->date)->get()->first();
        if ($timeMon) {
            return response(["message" => "لا يمكن تسجيل تفقد لنفس الموظف مرتين"]);
        }
        if (date('h:i:s', strtotime($request->startTime)) >= date('h:i:s', strtotime($request->exitTime))) {
            return response(["message" => "لا يمكن أن يكون وقت الدخول أكبر من وقت الخروج"]);
        }
        $timeMon = new timeMonitoring();
        $timeMon->create($request->all());
        return response("تم اضافة التفقد بنجاح");
    }
    public function getTimeMon(Request $request)
    {
        $message = array(
            "date.required" => "حقل التاريخ مطلوب"
        );
        $validator = Validator::make(request()->all(), [
            "date" => "required"
        ], $message);

        if ($validator->fails()) {
            $msg = (json_decode(json_encode($validator->errors(), JSON_UNESCAPED_UNICODE), true));
            $string = '';
            foreach (array_values($msg) as $value) {
                $string .=  $value[0] . " , ";
            }
            return response(["message" => "$string"], 422);
        }
        $timeMon = timeMonitoring::where('date', $request->date)->get();
        $users = User::get();
        $user = [];
        $indexx = 0;
        for ($r = 0; $r < sizeof($users); $r++) {
            $teacher = teacher::where('userId', $users[$r]->userId)->get();
            if (sizeof($teacher) == 0) {
                $user[$indexx] = $users[$r];
                $indexx += 1;
            }
        }
        $data = [];
        if (sizeof($timeMon) != 0) {
            for ($i = 0; $i < sizeof($user); $i++) {

                $exist = false;
                $index = 0;
                for ($j = 0; $j < sizeof($timeMon); $j++) {
                    if ($user[$i]->userId == $timeMon[$j]->userId) {
                        $index = $j;
                        $exist = true;
                        break;
                    }
                }
                if ($exist == true) {
                    $data[$i]['timeMon'] = $timeMon[$index];
                    $data[$i]['userName'] = $user[$i]->name;
                    $data[$i]['userId'] = $user[$i]->userId;
                    $data[$i]['status'] = "موجود";
                } else {
                    $data[$i]['timeMon'] = [];
                    $data[$i]['userName'] = $user[$i]->name;
                    $data[$i]['userId'] = $user[$i]->userId;
                    $data[$i]['status'] = "غياب";
                }
            }
            return response(["data" => $data, "message" => "تم احضار التفقد بنجاح"]);
        }
        return response(["message" => "لا يوجد تفقد لعرضه"]);
    }

    public function deleteTM($id)
    {
        $timeMon = timeMonitoring::find($id);
        if ($timeMon) {
            $timeMon->delete();
            return response(["message" => "تم حذف التفقد بنجاح"]);
        }
        return response(["message" => "فشل عملية الحذف"]);
    }

    public function updateTimeMonitoring($id, Request $request)
    {
        $timeMonitoring = timeMonitoring::find($id);
        if ($timeMonitoring) {
            if ($request->startTime && $request->exitTime) {
                if (date('h:i:s', strtotime($request->startTime)) < date('h:i:s', strtotime($request->exitTime))) {
                    $timeMonitoring->startTime = $request->startTime;
                    $timeMonitoring->exitTime = $request->exitTime;
                    $timeMonitoring->update();
                    return response(["data" => $timeMonitoring, "message" => "تم تعديل التفقد بنجاح"]);
                }
                return response(["message" => "لا يمكن أن يكون وقت الدخول أكبر من وقت الخروج"]);
            }
            if ($request->startTime && !$request->exitTime) {
                if (date('h:i:s', strtotime($request->startTime)) >= date('h:i:s', strtotime($timeMonitoring->exitTime))) {
                    return response(["message" => "لا يمكن أن يكون وقت الدخول أكبر من وقت الخروج"]);
                }
                $timeMonitoring->startTime = $request->startTime;
                $timeMonitoring->update();
                return response(['timeMonitoring' => $timeMonitoring, 'message' => "تم التعديل بنجاح"]);
            }
            if (!$request->startTime && $request->exitTime) {
                if (date('h:i:s', strtotime($timeMonitoring->startTime)) >= date('h:i:s', strtotime($request->exitTime))) {
                    return response(["message" => "لا يمكن أن يكون وقت الدخول أكبر من وقت الخروج"]);
                }
                $timeMonitoring->exitTime = $request->exitTime;
                $timeMonitoring->update();
                return response(['timeMonitoring' => $timeMonitoring, 'message' => "تم التعديل بنجاح"]);
            }
        }
        return response(["message" => "فشل عملية التعديل"]);
    }

    public function export(Request $request)
    {
        $message = array(
            "fileName.required"=>"  اسم الملف مطلوب",
        );
        $validator = Validator::make(request()->all(), [
           "fileName" => "required"
        ],$message);

        if ($validator->fails()) {
            $msg = (json_decode(json_encode( $validator->errors(),JSON_UNESCAPED_UNICODE),true));
            $string='';
          foreach (array_values($msg) as $value){
              $string .=  $value[0]." , ";
          }
              return response(["message"=>"$string"], 422); 
          }
          return   Excel::download(new UsersExport,"$request->fileName".'.xlsx');
    }
    public function import(Request $request)
    {
        $message = array(
            "TimeMonitoringFile.required" => "الملف مطلوب",
        );
        $validator = Validator::make(request()->all(), [
            "TimeMonitoringFile" => "required"
        ], $message);

        if ($validator->fails()) {
            $msg = (json_decode(json_encode($validator->errors(), JSON_UNESCAPED_UNICODE), true));
            $string = '';
            foreach (array_values($msg) as $value) {
                $string .=  $value[0] . " , ";
            }
            return response(["message" => "$string"], 422);
        }

        try {
            if (Excel::import(new TimeMonitoringImport, $request->TimeMonitoringFile)) {
                return response(["message" => "تم استيراد التفقد بنجاح"]);
            } else {
                return response(["message" => "فشل استيراد التفقد بنجاح"]);
            }
        } catch (Exception $e) {
            if ($e instanceof \Illuminate\Contracts\Filesystem\FileNotFoundException) {
                return response(["message" => "الملف غير موجود!!"]);
            }
            return response(["message" => "فشل استيراد التفقد"]);
        }
    }
    public function TimeMonitorintExp(Request $request)
    {
        $message = array(
            "fileName.required" => "  اسم الملف مطلوب",
            "date.required" => "  حقل التاريخ مطلوب",
        );
        $validator = Validator::make(request()->all(), [
            "fileName" => "required",
            "date" => "required"
        ], $message);

        if ($validator->fails()) {
            return response()->json(["message" => json_encode($validator->errors(), JSON_UNESCAPED_UNICODE)], 400);
        }
        $array = [];
        $timeMonitoring = timeMonitoring::where('date', $request->date)->get();
        for ($i = 0; $i < sizeof($timeMonitoring); $i++) {
            $user  = User::find($timeMonitoring[$i]->userId);
            if ($user) {
                $array[$i]['userId'] = $user->userId;
                $array[$i]['name'] = $user->name;
            }
            $array[$i]['startTime'] = $timeMonitoring[$i]->startTime;
            $array[$i]['exitTime'] = $timeMonitoring[$i]->exitTime;
            $array[$i]['date'] = $timeMonitoring[$i]->date;
        }
        return Excel::download(new timeMonitoringExport($array), $request->fileName . '.xlsx');
    }

    public function getTimeMonitoringForperiodForAllUsers(Request $request)
    {

        $message = array(
            "startDate.required" => "حقل تارخ البداية مطلوب",
            "endDate.required" => "حقل تاريخ النهاية مطلوب"
        );
        $validator = Validator::make(request()->all(), [
            "startDate" => "required",
            "endDate" => "required",
        ], $message);

        if ($validator->fails()) {
            $msg = (json_decode(json_encode($validator->errors(), JSON_UNESCAPED_UNICODE), true));
            $string = '';
            foreach (array_values($msg) as $value) {
                $string .=  $value[0] . " , ";
            }
            return response(["message" => "$string"], 422);
        }
        $dates = DB::table('timemonitoring')->select('date')->whereBetween('date', [$request->startDate, $request->endDate])->distinct()->get();
        if (sizeof($dates) != 0) {
            $t = 0;
            $data = [];
            for ($k = 0; $k < sizeof($dates); $k++) {
                $timeMon = timeMonitoring::where('date', $dates[$k]->date)->get();
                $users = User::get();
                if (sizeof($timeMon) != 0) {
                    for ($i = 0; $i < sizeof($users); $i++) {
                        $teacher = teacher::where('userId', $users[$i]->userId)->get();
                        if (sizeof($teacher) == 0) {
                            $exist = false;
                            $index = 0;
                            for ($j = 0; $j < sizeof($timeMon); $j++) {
                                if ($users[$i]->userId == $timeMon[$j]->userId) {
                                    $index = $j;
                                    $exist = true;
                                    break;
                                }
                            }
                            if ($exist == true) {
                                $data[$t]['timeMon'] = $timeMon[$index];
                                $data[$t]['userName'] = $users[$i]->name;
                                $data[$t]['userId'] = $users[$i]->userId;
                                $data[$t]['status'] = "موجود";
                                $t++;
                            } else {
                                $data[$t]['timeMon'] = [];
                                $data[$t]['date'] = $dates[$k]->date;
                                $data[$t]['userName'] = $users[$i]->name;
                                $data[$t]['userId'] = $users[$i]->userId;
                                $data[$t]['status'] = "غياب";
                                $t++;
                            }
                        }
                    }
                }
            }
            if (sizeof($data) == 0) {
                return response(["message" => "لا يوجد تفقد لعرضه"]);
            }
            return response(["data" => $data, "message" => "تم احضار التفقد بنجاح"]);
        }
        return response(["message" => "لا يوجد تفقد لعرضه"]);
    }
    public function getTimeMonitoringForperiodForUser($id, Request $request)
    {

        $message = array(
            "startDate.required" => "حقل تارخ البداية مطلوب",
            "endDate.required" => "حقل تاريخ النهاية مطلوب"
        );
        $validator = Validator::make(request()->all(), [
            "startDate" => "required",
            "endDate" => "required",
        ], $message);
        if ($validator->fails()) {
            $msg = (json_decode(json_encode($validator->errors(), JSON_UNESCAPED_UNICODE), true));
            $string = '';
            foreach (array_values($msg) as $value) {
                $string .=  $value[0] . " , ";
            }
            return response(["message" => "$string"], 422);
        }
        $user = User::find($id);
        if ($user) {
            $teacher = teacher::where('userId', $user->userId)->get();
            if (sizeof($teacher) == 0) {
                $dates = DB::table('timemonitoring')->select('date')->whereBetween('date', [$request->startDate, $request->endDate])->orderBy('date')->distinct()->get();
                if (sizeof($dates) != 0) {
                    $t = 0;
                    $data = [];
                    for ($k = 0; $k < sizeof($dates); $k++) {
                        $timeMon = timeMonitoring::where('date', $dates[$k]->date)->where('userId', $id)->get()->first();
                        $userName = DB::table('users')->select('name')->where('userId', $id)->get()->first();
                        if ($timeMon) {
                            $data[$t]['timeMon'] = $timeMon;
                            $data[$t]['userName'] = $userName->name;
                            $data[$t]['userId'] = $id;
                            $data[$t]['status'] = "موجود";
                            $t++;
                        } else {
                            $data[$t]['timeMon'] = [];
                            $data[$t]['userName'] = $userName->name;
                            $data[$t]['userId'] = $id;
                            $data[$t]['date'] = $dates[$k]->date;
                            $data[$t]['status'] = "غياب";
                            $t++;
                        }
                    }
                    if (sizeof($data) == 0) {
                        return response(["message" => "لا يوجد تفقد لعرضه"]);
                    }
                    return response(["data" => $data, "message" => "تم احضار التفقد بنجاح"]);
                }
                return response(["message" => "لا يوجد تفقد لعرضه"]);
            }
            return response(["message" => "المستخدم غير موجود"]);
        }
        return response(["message" => "المستخدم غير موجود"]);
    }

    public function getUserTimeMonitoring($id, Request $request)
    {
        $message = array(
            "date.required" => "حقل التاريخ مطلوب"
        );
        $validator = Validator::make(request()->all(), [
            "date" => "required"
        ], $message);

        if ($validator->fails()) {
            $msg = (json_decode(json_encode($validator->errors(), JSON_UNESCAPED_UNICODE), true));
            $string = '';
            foreach (array_values($msg) as $value) {
                $string .=  $value[0] . " , ";
            }
            return response(["message" => "$string"], 422);
        }
        $user = User::find($id);
        if ($user) {
            $timeMonitoring = timeMonitoring::where('userId', $id)->where('date', $request->date)->get()->first();
            $data = [];
            if ($timeMonitoring) {
                $data[0]['timeMon'] = $timeMonitoring;
                $data[0]['userName'] = $user->name;
                $data[0]['userId'] = $user->userId;
                $data[0]['status'] = "موجود";

                return response(["data" => $data, "message" => "تم احضار التفقد بنجاح"]);
            }
            $data[0]['timeMon'] = $timeMonitoring;
            $data[0]['userName'] = $user->name;
            $data[0]['userId'] = $user->userId;
            $data[0]['status'] = "غياب";

            return response(["data" => $data, "message" => "لا يوجد تفقد في هذا اليوم"]);
        }
        return response(["message" => "المستخدم غير موجود"]);
    }
}
