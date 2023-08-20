<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CoruseStuNo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('corusestuno', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('courseId');
            $table->integer('number');
            $table->timestamps();
            $table->foreign('courseId')->references('courseId')->on('course')->onDelete('casecade');
              });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
