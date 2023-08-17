<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FinancailUserAccount extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('financialUserAccount', function (Blueprint $table) {
            $table->bigIncrements('FUAId');
            $table->unsignedBigInteger('FAId');
            $table->unsignedBigInteger('userId')->nullable();
            $table->foreign('FAId')->references('FAId')->on('financialAccount')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('userId')->references('userId')->on('users')->onDelete('set null')->onUpdate('cascade');
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
        Schema::dropIfExists('financialUserAccount');
    }
}