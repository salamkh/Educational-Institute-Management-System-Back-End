<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('test', function (Blueprint $table) {
            $table->bigIncrements('testId');
            $table->unsignedBigInteger('studentId');
            $table->unsignedBigInteger('sessionId');
            $table->unsignedBigInteger('teacherId');
            $table->unsignedBigInteger('courseId');

            $table->integer('value');

            $table->string('cause');
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

            $table->foreign('teacherId')
                ->references('tId')
                ->on('teacher')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreign('courseId')
                ->references('courseId')
                ->on('course')->
                onDelete('cascade');

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
        Schema::dropIfExists('test');
    }
}
