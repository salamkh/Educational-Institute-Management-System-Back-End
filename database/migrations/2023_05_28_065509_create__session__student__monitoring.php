<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSessionStudentMonitoring extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('session_student_monitoring', function (Blueprint $table) {
            $table->bigIncrements('sessionStudentMonitoringId');
            $table->unsignedBigInteger('sessionId');
            $table->unsignedBigInteger('studentId');
            $table->unsignedBigInteger('courseId');
            $table->set('studentStatus', ['حضور','غياب']);
            $table->foreign('studentId')
                ->references('studentId')
                ->on('student')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('sessionId')
                ->references('sessionId')
                ->on('session')
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
        Schema::dropIfExists('session_student_monitoring');
    }
}
