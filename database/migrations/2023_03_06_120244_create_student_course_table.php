<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentCourseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_course', function (Blueprint $table) {
            $table->bigIncrements('studentCourseId');
            $table->unsignedBigInteger('courseId');
            $table->unsignedBigInteger('studentId')->nullable();
            $table->unsignedBigInteger('studentAccount');

            $table->foreign('courseId')
                ->references('courseId')
                ->on('course')
                ->onDelete('cascade')
                ->onUpdate('cascade');


            $table->foreign('studentId')
                ->references('studentId')
                ->on('student')
                ->onDelete('set null')
                ->onUpdate('cascade');
                
                
            $table->foreign('studentAccount')
            ->references('FAId')
            ->on('financialaccount')
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
        Schema::dropIfExists('student_course');
    }
}
