<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\MyClass;
use App\Models\subject;
use App\Models\teacher;
use App\Models\TeacherCourse;
use App\Models\Type;
use App\Models\User;
use Illuminate\Http\Request;

class SchedualController extends Controller
{
    public function showSchedual()
    {
        $course = Course::where('courseStatus', 'مفتوحة')->get();

        if (!$course) {
            $array = [];
            return response([
                'Course' => $array,
                'message' => 'لا يوجد معلومات مطابقة لهذا العرض',
            ]);
        } else {
            for ($i = 0; $i < sizeof($course); $i++) {
                $type = Type::find($course[$i]->typeId);
                $course[$i]->typeId = $type->name;
                $subject = subject::find($course[$i]->subjectId);
                $course[$i]->subjectId = $subject->name;
                $teacher = TeacherCourse::where('courseId', $course[$i]->courseId)->get()->first();
                if ($teacher) {
                    $teacher = teacher::where('tId', $teacher->teacherId)->get()->first();
                    $teacher = User::find($teacher->userId);
                    $course[$i]->teacher = $teacher->name;
                }
            }
            $satarday = null;
            $sunday = null;
            $monday = null;
            $tusday = null;
            $wednesday = null;
            $thursday = null;
            $friday = null;
            $s1 = -1;
            $s2 = -1;
            $s3 = -1;
            $s4 = -1;
            $s5 = -1;
            $s6 = -1;
            $s7 = -1;

            for ($t = 0; $t < sizeof($course); $t++) {
                if (in_array('السبت', explode(',', $course[$t]->courseDays))) {
                    $s1++;
                    $satarday[$s1] = $course[$t];
                }
                if (in_array('الأحد', explode(',', $course[$t]->courseDays))) {
                    $s2++;
                    $sunday[$s2] = $course[$t];
                }
                if (in_array('الاثنين', explode(',', $course[$t]->courseDays))) {
                    $s3++;
                    $monday[$s3] = $course[$t];
                }
                if (in_array('الثلاثاء', explode(',', $course[$t]->courseDays))) {
                    $s4++;
                    $tusday[$s4] = $course[$t];
                }
                if (in_array('الأربعاء', explode(',', $course[$t]->courseDays))) {
                    $s5++;
                    $wednesday[$s5] = $course[$t];
                }
                if (in_array('الخميس', explode(',', $course[$t]->courseDays))) {
                    $s6++;
                    $thursday[$s6] = $course[$t];
                }
                if (in_array('الجمعة', explode(',', $course[$t]->courseDays))) {
                    $s7++;
                    $friday[$s7] = $course[$t];
                }
            }

            return response(
                [
                    'satarday' => $course,
                    'sanday' => $sunday,
                    'monday' => $monday,
                    'tusday' => $tusday,
                    'wednesday' => $wednesday,
                    'thursday' => $thursday,
                    'friday' => $friday,
                    'message' => 'تمت عملية العرض بنجاح',
                ]
            );
        }
    }
}
