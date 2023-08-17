<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSessionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('session', function (Blueprint $table) {
            $table->bigIncrements('sessionId');
            $table->integer('sessionNumber')->default(0);

            $table->unsignedBigInteger('courseId');
            $table->time('startTime')->nullable();
            $table->date('date')->nullable();
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
        Schema::dropIfExists('session');
    }
}
