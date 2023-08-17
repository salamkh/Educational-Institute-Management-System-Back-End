<?php

namespace Database\Seeders;

use App\Models\authorization;
use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        authorization::create(['name'=>"إضافة طالب"]);
        authorization::create(['name'=>"تعديل طالب"]);
        authorization::create(['name'=>"استعراض طالب"]);
        authorization::create(['name'=>"استعراض جميع طالب"]);
        authorization::create(['name'=>"البحث عن طالب"]);
       }
}
