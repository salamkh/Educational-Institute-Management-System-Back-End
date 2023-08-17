<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Panchment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Panchment', function (Blueprint $table) {
            $table->bigIncrements('panchId');
            $table->unsignedBigInteger('userId');
            $table->date('panchDate');
            $table->string('cause');
            $table->integer('balance');
            $table->enum('status', array( 'غير مطبقة','مطبقة'));
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
        Schema::dropIfExists('Panchment');

    }
}
