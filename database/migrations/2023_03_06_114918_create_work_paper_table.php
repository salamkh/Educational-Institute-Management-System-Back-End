<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkPaperTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('work_paper', function (Blueprint $table) {
            $table->bigIncrements('workPaperId');
            $table->unsignedBigInteger('courseId');
            $table->unsignedBigInteger('teacherId');
            $table->string('path');
            $table->date('date');
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
        Schema::dropIfExists('work_paper');
    }
}
