<?php

namespace Database\Seeders;
use App\Models\tableColumns;
use App\Models\dynammicTables;
use Illuminate\Database\Seeder;

class tableColumnsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $userTable = dynammicTables::where('name','users')->get()->first();
        // if($userTable){
        //     tableColumns::create(['tableId'=>$userTable->tableId,"arabicName"=>"رقم المستخدم","EnglishName"=>"userId","dataType"=>"bigInteger","columnType"=>"أساسية","isUnique"=>'1']);
        //     tableColumns::create(['tableId'=>$userTable->tableId,"arabicName"=>"الاسم الكامل","EnglishName"=>"name","dataType"=>"string","columnType"=>"أساسية","isUnique"=>'0']);
        //     tableColumns::create(['tableId'=>$userTable->tableId,"arabicName"=>"اسم المستخدم","EnglishName"=>"userName","dataType"=>"string","columnType"=>"أساسية","isUnique"=>'1']);
        //     tableColumns::create(['tableId'=>$userTable->tableId,"arabicName"=>"الايميل","EnglishName"=>"email","dataType"=>"string","columnType"=>"أساسية","isUnique"=>1]);
        //     tableColumns::create(['tableId'=>$userTable->tableId,"arabicName"=>"كلمة السر","EnglishName"=>"password","dataType"=>"string","columnType"=>"أساسية","isUnique"=>'0']);
        //     tableColumns::create(['tableId'=>$userTable->tableId,"arabicName"=>"الراتب","EnglishName"=>"salary","dataType"=>"bigInteger","columnType"=>"أساسية","isUnique"=>'0']);
        //     tableColumns::create(['tableId'=>$userTable->tableId,"arabicName"=>"رقم الهاتف","EnglishName"=>"phoneNumber","dataType"=>"string","columnType"=>"أساسية","isUnique"=>'1']);
        //     tableColumns::create(['tableId'=>$userTable->tableId,"arabicName"=>"صورة الهوية","EnglishName"=>"imageIdentity","dataType"=>"string","columnType"=>"أساسية","isUnique"=>'1']);
        //     tableColumns::create(['tableId'=>$userTable->tableId,"arabicName"=>"حالة الحساب","EnglishName"=>"accountStatus","dataType"=>"enum","columnType"=>"أساسية","isUnique"=>'0']);
        //     tableColumns::create(['tableId'=>$userTable->tableId,"arabicName"=>"أوقات الدوام","EnglishName"=>"workTime","dataType"=>"enum","columnType"=>"أساسية","isUnique"=>'0']);
        //     tableColumns::create(['tableId'=>$userTable->tableId,"arabicName"=>"العنوان","EnglishName"=>"address","dataType"=>"string","columnType"=>"أساسية","isUnique"=>'0']);

            
        // }

        $teacherTable = dynammicTables::where('name','teacher')->get()->first();
        if($teacherTable){
            tableColumns::create(['tableId'=>$teacherTable->tableId,"arabicName"=>"رقم الأستاذ","EnglishName"=>"tId ","dataType"=>"bigInteger","columnType"=>"أساسية","isUnique"=>'1']);
            tableColumns::create(['tableId'=>$teacherTable->tableId,"arabicName"=>"الشهادة","EnglishName"=>"certificate","dataType"=>"string","columnType"=>"أساسية","isUnique"=>'0']);
            tableColumns::create(['tableId'=>$teacherTable->tableId,"arabicName"=>"الخبرات","EnglishName"=>"experience","dataType"=>"string","columnType"=>"أساسية","isUnique"=>'0']);
            tableColumns::create(['tableId'=>$teacherTable->tableId,"arabicName"=>"رقم المستخدم","EnglishName"=>"userId","dataType"=>"bigInteger","columnType"=>"أساسية","isUnique"=>'0']);
            tableColumns::create(['tableId'=>$teacherTable->tableId,"arabicName"=>"تاريخ الشهادة","EnglishName"=>"cerDate","dataType"=>"date","columnType"=>"أساسية","isUnique"=>'0']); 
            }
            $studentTable = dynammicTables::where('name','student')->get()->first();
        if($studentTable){
            tableColumns::create(['tableId'=>$studentTable->tableId,"arabicName"=>"رقم الطالب","EnglishName"=>"studentId","dataType"=>"bigInteger","columnType"=>"أساسية","isUnique"=>'1']);
            tableColumns::create(['tableId'=>$studentTable->tableId,"arabicName"=>"اسم الطالب","EnglishName"=>"name","dataType"=>"string","columnType"=>"أساسية","isUnique"=>'0']);
            tableColumns::create(['tableId'=>$studentTable->tableId,"arabicName"=>"تاريخ الميلاد","EnglishName"=>"birthdate","dataType"=>"date","columnType"=>"أساسية","isUnique"=>'0']);
            tableColumns::create(['tableId'=>$studentTable->tableId,"arabicName"=>"الجنس","EnglishName"=>"gender","dataType"=>"string","columnType"=>"أساسية","isUnique"=>'0']);
            tableColumns::create(['tableId'=>$studentTable->tableId,"arabicName"=>"كلمة السر","EnglishName"=>"password","dataType"=>"string","columnType"=>"أساسية","isUnique"=>'0']);
            tableColumns::create(['tableId'=>$studentTable->tableId,"arabicName"=>"العنوان","EnglishName"=>"address","dataType"=>"string","columnType"=>"أساسية"]);
            tableColumns::create(['tableId'=>$studentTable->tableId,"arabicName"=>"رقم الهاتف","EnglishName"=>"phone","dataType"=>"string","columnType"=>"أساسية","isUnique"=>'0']);
            tableColumns::create(['tableId'=>$studentTable->tableId,"arabicName"=>"حالة الطالب","EnglishName"=>"myStatus","dataType"=>"string","columnType"=>"أساسية","isUnique"=>'0']);
           
        }

        $courseTable = dynammicTables::where('name','course')->get()->first();
        if($courseTable){
            tableColumns::create(['tableId'=>$courseTable->tableId,"arabicName"=>"رقم الكورس","EnglishName"=>"courseId ","dataType"=>"bigInteger","columnType"=>"أساسية","isUnique"=>'1']);
            tableColumns::create(['tableId'=>$courseTable->tableId,"arabicName"=>"رقم المادة","EnglishName"=>"subjectId","dataType"=>"bigInteger","columnType"=>"أساسية","isUnique"=>'0']);
            tableColumns::create(['tableId'=>$courseTable->tableId,"arabicName"=>"رقم نوع الدورة","EnglishName"=>"typeId","dataType"=>"bigInteger","columnType"=>"أساسية","isUnique"=>'0']);
            tableColumns::create(['tableId'=>$courseTable->tableId,"arabicName"=>"رقم فئة الدورة","EnglishName"=>"classId","dataType"=>"bigInteger","columnType"=>"أساسية","isUnique"=>'0']);
            tableColumns::create(['tableId'=>$courseTable->tableId,"arabicName"=>"العناصر المعرفية التي تقدمها الدورة","EnglishName"=>"headlines","dataType"=>"string","columnType"=>"أساسية","isUnique"=>'0']);
            tableColumns::create(['tableId'=>$courseTable->tableId,"arabicName"=>"العناصر التي تضاف إلى قيمة الدورة","EnglishName"=>"addElements","dataType"=>"string","columnType"=>"أساسية","isUnique"=>'0']);
            tableColumns::create(['tableId'=>$courseTable->tableId,"arabicName"=>"كلفة الدورة","EnglishName"=>"cost","dataType"=>"string","columnType"=>"أساسية","isUnique"=>'0']);
            tableColumns::create(['tableId'=>$courseTable->tableId,"arabicName"=>"عدد الطلاب الأعظمي","EnglishName"=>"maxNStudent","dataType"=>"bigInteger","columnType"=>"أساسية","isUnique"=>'0']);

            tableColumns::create(['tableId'=>$courseTable->tableId,"arabicName"=>"عدد الجلسات","EnglishName"=>"sessionNumber","dataType"=>"bigInteger","columnType"=>"أساسية","isUnique"=>'0']);
            tableColumns::create(['tableId'=>$courseTable->tableId,"arabicName"=>"تاريخ البداية","EnglishName"=>"startDate","dataType"=>"date","columnType"=>"أساسية","isUnique"=>'0']);
            tableColumns::create(['tableId'=>$courseTable->tableId,"arabicName"=>"تاريخ النهاية","EnglishName"=>"endDate","dataType"=>"date","columnType"=>"أساسية","isUnique"=>'0']);
            tableColumns::create(['tableId'=>$courseTable->tableId,"arabicName"=>"وقت البداية","EnglishName"=>"startTime","dataType"=>"time","columnType"=>"أساسية","isUnique"=>'0']);
            tableColumns::create(['tableId'=>$courseTable->tableId,"arabicName"=>"مدة الجلسة","EnglishName"=>"duration","dataType"=>"bigInteger","columnType"=>"أساسية","isUnique"=>'0']);
            tableColumns::create(['tableId'=>$courseTable->tableId,"arabicName"=>"حالة الكورس","EnglishName"=>"courseStatus","dataType"=>"set","columnType"=>"أساسية","isUnique"=>'0']);
            tableColumns::create(['tableId'=>$courseTable->tableId,"arabicName"=>"أيام الكورس","EnglishName"=>"courseDays","dataType"=>"set","columnType"=>"أساسية","isUnique"=>'0']);
            tableColumns::create(['tableId'=>$courseTable->tableId,"arabicName"=>"القاعة","EnglishName"=>"room","dataType"=>"string","columnType"=>"أساسية","isUnique"=>'0']);
           
        }
        
    }
}
