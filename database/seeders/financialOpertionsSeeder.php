<?php

namespace Database\Seeders;

use App\Models\authorization;
use Illuminate\Database\Seeder;

class financialOpertionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        authorization::create(['name'=>"إضافة عملية مالية"]);
        authorization::create(['name'=>"حذف عملية مالية"]);
        authorization::create(['name'=>"عرض عملية مالية"]);
    }
}
