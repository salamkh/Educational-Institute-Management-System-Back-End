<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeacherCourseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teacher_course', function (Blueprint $table) {
            $table->bigIncrements('teacherCourseId');
            $table->unsignedBigInteger('teacherId');
            $table->unsignedBigInteger('courseId');
            $table->foreign('teacherId')
                ->references('tId')
                ->on('teacher')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreign('courseId')
                ->references('courseId')
                ->on('course')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('teacher_course');
    }
}
