<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SessionCost extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('SessionCost', function (Blueprint $table) {
            $table->bigIncrements('SCId');
            $table->unsignedBigInteger('sessionId');
            $table->integer('cost');
            $table->integer('studentNumber');
            $table->foreign('sessionId')->references('sessionId')->on('session')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('SessionCost');
    }
}
