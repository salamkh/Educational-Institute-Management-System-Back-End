<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateScheduleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schedule', function (Blueprint $table) {
            $table->bigIncrements('scheduleId');
            $table->enum('courseDays', ['السبت', 'الأحد', 'الاثنين', 'الثلاثاء', 'الأربعاء', 'الخميس', 'الجمعة']);
            $table->time('startTime');
            $table->unsignedBigInteger('courseId');
            $table->unsignedBigInteger('teacherId');

            $table->foreign('courseId')
                ->references('courseId')
                ->on('course')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            

            $table->foreign('teacherId')
                ->references('tId')
                ->on('teacher')
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
        Schema::dropIfExists('schedule');
    }
}
