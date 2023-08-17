<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class StaticSessionCost extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('StaticSessionCost', function (Blueprint $table) {
            $table->bigIncrements('sessionCostId');
            $table->unsignedBigInteger('courseId');
            $table->integer('cost');
            $table->foreign('courseId')->references('courseId')->on('course')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('PlanCosts');
    }
}
