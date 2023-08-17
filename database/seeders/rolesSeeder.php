<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\role;

class rolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        role::create(['role'=>"مدير"]);
        role::create(['role'=>"محاسب"]);
        role::create(['role'=>"موظف إداري"]);
        role::create(['role'=>"مراقب الدوام"]);
        role::create(['role'=>"أستاذ"]);
        role::create(['role'=>"مسؤول الموارد البشرية"]);
    }
}
