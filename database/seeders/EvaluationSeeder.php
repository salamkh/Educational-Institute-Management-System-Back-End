<?php

namespace Database\Seeders;

use App\Models\authorization;
use Illuminate\Database\Seeder;

class EvaluationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        authorization::create(['name'=>"إنشاء تقييم"]);
        authorization::create(['name'=>"تعديل تقييم"]);
        authorization::create(['name'=>"عرض تقييم"]);
        authorization::create(['name'=>"عرض كل التقييم"]);
        authorization::create(['name'=>"ترتيب الطلاب حسب التقييم"]);

    }
}
