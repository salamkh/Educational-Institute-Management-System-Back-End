<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\advance;
use App\Models\authorization;

class advanceSeerder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        authorization::create(['name'=>"إضافة سلفة"]);
        authorization::create(['name'=>"تعديل سلفة"]);
        authorization::create(['name'=>"عرض سلفة"]);
        authorization::create(['name'=>"تغيير حالة سلفة"]);
    }
}
