<?php
namespace Database\Seeders;
use App\Models\authorization;
use Illuminate\Database\Seeder;
class courseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        authorization::create(['name'=>"إضافة فئة"]);
        authorization::create(['name'=>"تعديل فئة"]);
        authorization::create(['name'=>"حذف فئة"]);
        authorization::create(['name'=>"استعراض فئة"]);
        authorization::create(['name'=>"إضافة مادة"]);
        authorization::create(['name'=>"تعديل مادة"]);
        authorization::create(['name'=>"حذف مادة"]);
        authorization::create(['name'=>"استعراض مادة"]);
        authorization::create(['name'=>"إنشاء دورة"]);
        authorization::create(['name'=>"إغلاق دورة"]);
        authorization::create(['name'=>"تعديل معلومات دورة"]);
        authorization::create(['name'=>"حذف دورة"]);
        authorization::create(['name'=>"استعراض دورة"]);
        authorization::create(['name'=>"استعراض جميع الدورات ضمن نوع محدد"]);
    }
}
