<?php

namespace Database\Seeders;

use App\Models\authorization;
use Illuminate\Database\Seeder;

class AdvertismentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        authorization::create(['name'=>"إنشاء إعلان"]);
        authorization::create(['name'=>"تعديل إعلان"]);
        authorization::create(['name'=>"عرض الإعلان"]);
        authorization::create(['name'=>"عرض كل الإعلانات"]);
        authorization::create(['name'=>"حذف إعلان"]);
        authorization::create(['name'=>"إنشاء تسميع"]);

    }
}
