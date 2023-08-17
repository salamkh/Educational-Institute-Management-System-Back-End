<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PlanCosts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('PlanCosts', function (Blueprint $table) {
            $table->bigIncrements('planCostId');
            $table->unsignedBigInteger('planId');
            $table->integer('max');
            $table->integer('min');
            $table->integer('cost');
            $table->foreign('planId')->references('planId')->on('PricingPlan')->onDelete('cascade')->onUpdate('cascade');
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
