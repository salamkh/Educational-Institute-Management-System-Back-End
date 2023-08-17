<?php

namespace Database\Seeders;
use App\Models\dynammicTables;
use Illuminate\Database\Seeder;

class dynamicTablesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        dynammicTables::create(['name'=>"users","lable"=>"المستخدمين"]);
        dynammicTables::create(['name'=>"student","lable"=>"الطلاب"]);
        dynammicTables::create(['name'=>"teacher","lable"=>"الأساتذة"]);
        dynammicTables::create(['name'=>"course","lable"=>"الكورسات"]);
    }
}
