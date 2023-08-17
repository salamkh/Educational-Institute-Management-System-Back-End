<?php

namespace Database\Seeders;

use App\Models\authorization;
use App\Models\panchment;
use Illuminate\Database\Seeder;

class panchmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        authorization::create(['name'=>"إضافة حسم"]);
        authorization::create(['name'=>"تعديل حسم"]);
        authorization::create(['name'=>"عرض حسم"]);
        authorization::create(['name'=>"تغيير حالة حسم"]);
    }
}
