<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\authorization;

class typeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        authorization::create(['name'=>"إضافة نوع دورة"]);
    }
}
