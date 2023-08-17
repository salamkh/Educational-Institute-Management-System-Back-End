<?php

namespace Database\Seeders;

use App\Models\authorization;
use Illuminate\Database\Seeder;

class workLeaveSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        authorization::create(['name'=>"إضافة إجازة"]);
        authorization::create(['name'=>"تعديل إجازة"]);
        authorization::create(['name'=>"عرض إجازة"]);
        authorization::create(['name'=>"حذف إجازة"]);
    }
}
