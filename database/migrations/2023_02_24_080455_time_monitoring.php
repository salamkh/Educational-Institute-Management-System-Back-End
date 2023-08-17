<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TimeMonitoring extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('TimeMonitoring', function (Blueprint $table) {
            $table->bigIncrements('monId');
            $table->unsignedBigInteger('userId');
            $table->time('startTime');
            $table->time('exitTime');
            $table->date('date');
            $table->timestamps();
            $table->foreign('userId')->references('userId')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('TimeMonitoring');
    }
}
