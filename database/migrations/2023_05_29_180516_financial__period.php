<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FinancialPeriod extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('financialPeriod', function (Blueprint $table) {
            $table->bigIncrements('FPId');
            $table->date('startDate');
            $table->date('endDate');
            $table->string('description');
            $table->enum('status', array( 'مغلقة','مفتوحة'));
            $table->integer('resault')->nullable();
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
        Schema::dropIfExists('financialPeriod');
    }
}
