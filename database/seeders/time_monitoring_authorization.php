<?php

namespace Database\Seeders;
use App\Models\authorization;
use Illuminate\Database\Seeder;

class time_monitoring_authorization extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        authorization::create(['name'=>"إضافة تفقد"]);
        authorization::create(['name'=>"تعديل تفقد"]);
        authorization::create(['name'=>"حذف تفقد"]);
        authorization::create(['name'=>"عرض تفقد"]);
        authorization::create(['name'=>"تصدير نموذج تفقد"]);
        authorization::create(['name'=>"تصدير تفقد"]);
    }
}
