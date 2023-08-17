<?php

namespace Database\Seeders;
use App\Models\authorization;
use Illuminate\Database\Seeder;

class pricingPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        authorization::create(['name'=>"إضافة خطة تسعير"]);
        authorization::create(['name'=>"تعديل خطة تسعير"]);
        authorization::create(['name'=>"عرض خطة تسعير"]);
        authorization::create(['name'=>"حذف خطة تسعير"]);
    }
}
