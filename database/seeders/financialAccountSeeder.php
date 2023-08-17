<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\authorization;

class financialAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        authorization::create(['name'=>"إضافة حساب مالي"]);
        authorization::create(['name'=>"عرض حساب مالي"]);
        authorization::create(['name'=>"حذف حساب مالي"]);
        authorization::create(['name'=>"البحث عن حساب مالي"]);
    }
}
