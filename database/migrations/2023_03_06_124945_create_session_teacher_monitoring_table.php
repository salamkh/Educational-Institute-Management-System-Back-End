<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSessionTeacherMonitoringTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('session_teacher_monitoring', function (Blueprint $table) {
            $table->bigIncrements('sessionTeacherMonitoringId');
            $table->unsignedBigInteger('teacherId');
            $table->unsignedBigInteger('sessionId');

            $table->foreign('teacherId')
                ->references('tId')
                ->on('teacher')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('sessionId')
                ->references('sessionId')
                ->on('session')
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
        Schema::dropIfExists('session_teacher_monitoring');
    }
}
