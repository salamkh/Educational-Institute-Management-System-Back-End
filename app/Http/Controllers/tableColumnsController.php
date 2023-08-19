<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Models\dynammicTables;
use App\Models\tableColumns;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class tableColumnsController extends Controller
{
    public function addColumn(Request $request)
    {
        $message = array(
            "tableId.required" => "رقم الميزة مطلوب",
            "table.required" => "اسم الميزة مطلوب",
            "fLabel.required" => "اسم الحقل باللغة العربية مطلوب",
            "fName.required" => "اسم الحقل باللغة الانكليزية مطلوب",
            "type.required" => "نوع الحقل مطلوب",
            "unique.required" => "تحديد أن يكون الحقل مميز أم لا مطلوبة",
        );
        $validator = Validator::make(request()->all(), [
            "tableId" => "required",
            "table" => "required",
            "fLabel" => "required",
            "fName" => "required",
            "type" => "required",
            "unique" => "required",
        ], $message);

        if ($validator->fails()) {
            $msg = (json_decode(json_encode($validator->errors(), JSON_UNESCAPED_UNICODE), true));
            $string = '';
            foreach (array_values($msg) as $value) {
                $string .=  $value[0] . " , ";
            }
            return response(["message" => "$string"], 422);
        }
        if ($request->type != "integer" && $request->type != "string" && $request->type != "double" && $request->type != "bigInteger" && $request->type != "enum" && $request->type != "date") {
            return response(["message" => "integer" . " or string" . " or bigInteger" . " or double" . " or enum" . " or date" . " : " . "يجب أن يكون نوع الحقل أحد القيم التالية"]);
        }
        $tableColumns = tableColumns::where('tableId', $request->tableId)->get();
        for ($i = 0; $i < sizeof($tableColumns); $i++) {
            if ($tableColumns[$i]->arabicName == $request->fLabel) {
                return response(["message" => " لا يمكن تكرار اسم الحقل باللغة العربية لنفس الجدول"]);
            }
            if ($tableColumns[$i]->EnglishName == $request->fName) {
                return response(["message" => " لا يمكن تكرار اسم الحقل باللغة الانكليزية لنفس الجدول"]);
            }
        }
        if ($request->type != "integer" && $request->type != "string" && $request->type != "double" && $request->type != "bigInteger" && $request->type != "enum" && $request->type != "date") {
            return response(["message" => "integer" . " or string" . " or bigInteger" . " or double" . " or enum" . " or date" . " : " . "يجب أن يكون نوع الحقل أحد القيم التالية"]);
        }
        if ($request->type == "enum") {
            $message = array(
                "pValues.required" => "القيم المحتملة للحقل مطلوبة",
            );
            $validator = Validator::make(request()->all(), [
                "pValues" => "required"
            ], $message);

            if ($validator->fails()) {
                $msg = (json_decode(json_encode($validator->errors(), JSON_UNESCAPED_UNICODE), true));
                $string = '';
                foreach (array_values($msg) as $value) {
                    $string .=  $value[0] . " , ";
                }
                return response(["message" => "$string"], 422);
            }
            if ($request->dValue) {
                $pass = false;
                for ($i = 0; $i < sizeof($request->pValues); $i++) {
                    if ($request->dValue == $request->pValues[$i]) {
                        $pass = true;
                    }
                }
                if ($pass == false) {
                    return response(["message" => "يجب أن تكون القيمة الافتراضية من إحدى القيم المحتملة"]);
                }
            }
        }
        $dynamicTable = dynammicTables::find($request->tableId);
        if ($dynamicTable) {
            if (Schema::hasColumn($request->table, $request->fName)) {
                return response(["message" => "لا يمكن إضافة هذا الحقل للميزة لأنه موجود مسبقا"]);
            }
            if ($request->unique == 1) {
                Schema::table($request->table, function (Blueprint $table) use ($request) {
                    if ($request->type == "bigInteger") {
                        $table->bigInteger($request->fName)->unique()->nullable();
                    }
                    if ($request->type == "integer") {
                        $table->integer($request->fName)->unique()->nullable();
                    }
                    if ($request->type == "string") {
                        $table->string($request->fName)->unique()->nullable();
                    }
                    if ($request->type == "double") {
                        $table->double($request->fName)->unique()->nullable();
                    }
                    if ($request->type == "date") {
                        $table->date($request->fName)->unique()->nullable();
                    }
                    if ($request->type == "enum") {
                        $table->enum($request->fName, $request->pValues)->unique();
                    }
                });
            } else if ($request->unique == 0) {
                Schema::table($request->table, function (Blueprint $table) use ($request) {
                    if (!$request->dValue) {
                        if ($request->type == "bigInteger") {
                            $table->bigInteger($request->fName)->nullable();
                        }
                        if ($request->type == "integer") {
                            $table->integer($request->fName)->nullable();
                        }
                        if ($request->type == "string") {
                            $table->string($request->fName)->nullable();
                        }
                        if ($request->type == "double") {
                            $table->double($request->fName)->nullable();
                        }
                        if ($request->type == "date") {
                            $table->date($request->fName)->nullable();
                        }
                        if ($request->type == "enum") {
                            $table->enum($request->fName, $request->pValues)->nullable();
                        }
                    } else {
                        if ($request->type == "bigInteger") {
                            $table->bigInteger($request->fName)->default($request->dValue)->nullable();
                        }
                        if ($request->type == "integer") {
                            $table->integer($request->fName)->default($request->dValue)->nullable();
                        }
                        if ($request->type == "string") {
                            $table->string($request->fName)->default($request->dValue)->nullable();
                        }
                        if ($request->type == "double") {
                            $table->double($request->fName)->default($request->dValue)->nullable();
                        }
                        if ($request->type == "date") {
                            $table->date($request->fName)->default($request->dValue)->nullable();
                        }
                        if ($request->type == "enum") {
                            $table->enum($request->fName, $request->pValues)->default($request->dValue)->nullable();
                        }
                    }
                });
            }
            if (Schema::hasColumn($request->table, $request->fName)) {
                $column = new tableColumns();
                $column->tableId = $request->tableId;
                $column->EnglishName = $request->fName;
                $column->arabicName = $request->fLabel;
                $column->dataType = $request->type;
                $column->columnType = "إضافية";
                if ($request->unique == 0) {
                    $column->isUnique = '0';
                } else {
                    $column->isUnique = '1';
                }
                $column->save();
                return response(["data" => $column, "message" => "تم إضافة الحقل بنجاح للميزة"]);
            }
            return response(["message" => "فشل عملية إضافة الحقل"]);
        }
    }
    public function getDynamicTables()
    {
        $dynamicTables = dynammicTables::get();
        return response(["data" => $dynamicTables, "message" => "تم احضار الجداول بنجاح"]);
    }
    public function getTableColumns($id)
    {
        $dynamicTable = dynammicTables::find($id);
        if ($dynamicTable) {
            $tableColumns = tableColumns::where('tableId', $id)->get();
            return response(["data" => $tableColumns, "message" => "تم احضار الحقول بنجاح"]);
        }
        return response(["message" => "هذه الميزة ليست ديناميكية"]);
    }
    public function deleteColumn($id)
    {
        $column = tableColumns::find($id);
        $dynamicTable = dynammicTables::find($column->tableId);
        if (!$column && !Schema::hasColumn($dynamicTable->name, $column->EnglishName)) {
            return response(["message" => "لم يتم ايجاد الحقل"]);
        } else {
            if ($column->columnType == "إضافية") {
                Schema::dropColumns($dynamicTable->name, $column->EnglishName);
                $column->delete();
                return response(["message" => "تم حذف الحقل بنجاح"]);
            } else {
                return response(["message" => "لا يمكن حذف الحقل لأنه حقل أساسي"]);
            }
        }
    }
    public function getTableColumnsForAddition($id)
    {
        $dynamicTable = dynammicTables::find($id);
        if ($dynamicTable) {
            $tableColumns = tableColumns::where('tableId', $id)->get();
            $columns = [];
            for ($j = 0; $j < sizeof($tableColumns); $j++) {
                if ($tableColumns[$j]->dataType != "enum") {
                    if ($tableColumns[$j]->EnglishName == "userId") {
                        $columns[$j] = [
                            "arabicName" => $tableColumns[$j]->arabicName,
                            "EnglishName" => $tableColumns[$j]->EnglishName,
                            "possibleValues" => [],
                            "viewType" => "hidden",
                        ];
                    } else {
                        $columns[$j] = [
                            "arabicName" => $tableColumns[$j]->arabicName,
                            "EnglishName" => $tableColumns[$j]->EnglishName,
                            "possibleValues" => [],
                            "viewType" => "visible"
                        ];
                    }
                } else {
                    $type = DB::select(DB::raw("SHOW COLUMNS FROM users WHERE Field = '{$tableColumns[$j]->EnglishName}'"))[0]->Type;
                    preg_match('/^enum\((.*)\)$/', $type, $matches);
                    $s = [];
                    $enum = explode("','", $matches[1]);
                    for ($i = 0; $i < sizeof($enum); $i++) {
                        $s[$i] = trim($enum[$i], "'");
                    }
                    if ($tableColumns[$j]->EnglishName == "userId" || $tableColumns[$j]->EnglishName == "accountStatus") {
                        $columns[$j] = [
                            "arabicName" => $tableColumns[$j]->arabicName,
                            "EnglishName" => $tableColumns[$j]->EnglishName,
                            "possibleValues" => $s,
                            "viewType" => "hidden"
                        ];
                        $s = [];
                    } else {
                        $columns[$j] = [
                            "arabicName" => $tableColumns[$j]->arabicName,
                            "EnglishName" => $tableColumns[$j]->EnglishName,
                            "possibleValues" => $s,
                            "viewType" => "visible"
                        ];
                        $s = [];
                    }
                }
            }
            return response(["data" => $columns, "message" => "تم احضار الحقول بنجاح"]);
        }
        return response(["message" => "هذه الميزة ليست ديناميكية"]);
    }
    public function getTableColumnsForEddition($id)
    {
        $dynamicTable = dynammicTables::where('name',"users")->get()->first();
        $user = User::find($id);
        if($user){
        if ($dynamicTable) {
            $tableColumns = tableColumns::where('tableId', $dynamicTable->tableId)->get();
            $columns = [];
            for ($j = 0; $j < sizeof($tableColumns); $j++) {
                if ($tableColumns[$j]->dataType != "enum") {
                    if ($tableColumns[$j]->EnglishName == "userId" || $tableColumns[$j]->EnglishName == "password" || $tableColumns[$j]->EnglishName == "imageIdentity") {
                        $columns[$j] = [
                            "arabicName" => $tableColumns[$j]->arabicName,
                            "EnglishName" => $tableColumns[$j]->EnglishName,
                            "possibleValues" => [],
                            "viewType" => "hidden",
                            "value"=>$user[$tableColumns[$j]->EnglishName]
                        ];
                    } else {
                        $columns[$j] = [
                            "arabicName" => $tableColumns[$j]->arabicName,
                            "EnglishName" => $tableColumns[$j]->EnglishName,
                            "possibleValues" => [],
                            "viewType" => "visible",
                            "value"=>$user[$tableColumns[$j]->EnglishName]
                        ];
                    }
                } else {
                    $type = DB::select(DB::raw("SHOW COLUMNS FROM users WHERE Field = '{$tableColumns[$j]->EnglishName}'"))[0]->Type;
                    preg_match('/^enum\((.*)\)$/', $type, $matches);
                    $s = [];
                    $enum = explode("','", $matches[1]);
                    for ($i = 0; $i < sizeof($enum); $i++) {
                        $s[$i] = trim($enum[$i], "'");
                    }
                    if ($tableColumns[$j]->EnglishName == "userId" || $tableColumns[$j]->EnglishName == "password") {
                        $columns[$j] = [
                            "arabicName" => $tableColumns[$j]->arabicName,
                            "EnglishName" => $tableColumns[$j]->EnglishName,
                            "possibleValues" => $s,
                            "viewType" => "hidden",
                            "value"=>$user[$tableColumns[$j]->EnglishName]
                        ];
                        $s = [];
                    } else {
                        $columns[$j] = [
                            "arabicName" => $tableColumns[$j]->arabicName,
                            "EnglishName" => $tableColumns[$j]->EnglishName,
                            "possibleValues" => $s,
                            "viewType" => "visible",
                            "value"=>$user[$tableColumns[$j]->EnglishName]
                        ];
                        $s = [];
                    }
                }
            }
            return response(["data" => $columns, "message" => "تم احضار الحقول بنجاح"]);
        }
        return response(["message" => "هذه الميزة ليست ديناميكية"]);
        }
        return response(["message"=>"المستخدم غير موجود"]);
    }
    public function getTableColumnsForProfile()
    {
        $dynamicTable = dynammicTables::where('name',"users")->get()->first();
        $user = auth()->user();
        if($user){
        if ($dynamicTable) {
            $tableColumns = tableColumns::where('tableId', $dynamicTable->tableId)->get();
            $columns = [];
            for ($j = 0; $j < sizeof($tableColumns); $j++) {
                    if ($tableColumns[$j]->EnglishName == "userId") {
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
            return response(["data" => $columns, "message" => "تم احضار الحقول بنجاح"]);
        }
        return response(["message" => "هذه الميزة ليست ديناميكية"]);
        }
        return response(["message"=>"المستخدم غير موجود"]);
    }
}
