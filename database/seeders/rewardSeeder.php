<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\authorization;

class rewardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        authorization::create(['name'=>"إضافة مكافأة"]);
        authorization::create(['name'=>"تعديل مكافأة"]);
        authorization::create(['name'=>"عرض مكافأة"]);
        authorization::create(['name'=>"تغيير حالة مكافأة"]);
    }
}
