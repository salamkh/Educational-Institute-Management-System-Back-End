<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FinancailTypeAccount extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('financialTypeAccount', function (Blueprint $table) {
            $table->bigIncrements('FTAId');
            $table->unsignedBigInteger('FAId');
            $table->unsignedBigInteger('typeId')->nullable();
            $table->foreign('FAId')->references('FAId')->on('financialAccount')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('typeId')->references('typeId')->on('type')->onDelete('set null')->onUpdate('cascade');
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
        Schema::dropIfExists('financialTypeAccount');
    }
}
