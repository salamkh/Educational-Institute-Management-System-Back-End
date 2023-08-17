<?php

namespace Database\Seeders;

use App\Models\authorization;
use Illuminate\Database\Seeder;

class TestingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        authorization::create(['name'=>"إنشاء تسميع"]);
        authorization::create(['name'=>"تعديل تسميع"]);
        authorization::create(['name'=>"عرض التسميع"]);
        authorization::create(['name'=>"عرض كل التسميعات لطالب"]);
        authorization::create(['name'=>"حذف كل التسميعات لطالب"]);
    }
}
