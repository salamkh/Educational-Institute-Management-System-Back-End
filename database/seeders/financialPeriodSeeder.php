<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\authorization;

class financialPeriodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        authorization::create(['name'=>"فتح دورة مالية"]);
        authorization::create(['name'=>"تمديد دورة مالية"]);
        authorization::create(['name'=>"عرض دورة مالية"]);
        authorization::create(['name'=>"إغلاق دورة مالية"]);
    }
}
