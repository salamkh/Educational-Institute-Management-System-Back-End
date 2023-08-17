<?php

namespace Database\Seeders;
use App\Models\authorization;
use Illuminate\Database\Seeder;

class authorizationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        authorization::create(['name'=>"إضافة حساب"]);
        authorization::create(['name'=>"تعديل حساب"]);
        authorization::create(['name'=>"حذف حساب"]);
        authorization::create(['name'=>"عرض الحسابات"]);
        authorization::create(['name'=>"البحث عن المستخدمين "]);
        authorization::create(['name'=>"عرض الصلاحيات"]);
        authorization::create(['name'=>"عرض الأدوار"]);
        authorization::create(['name'=>"إضافة مواد"]);
        authorization::create(['name'=>"حذف مواد"]);
        authorization::create(['name'=>"تعديل مواد"]);
        authorization::create(['name'=>"عرض جميع المواد"]);
    }
}
