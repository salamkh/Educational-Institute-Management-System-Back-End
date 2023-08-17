<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Advance extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Advance', function (Blueprint $table) {
            $table->bigIncrements('advId');
            $table->unsignedBigInteger('userId');
            $table->date('advancedDate');
            $table->string('cause');
            $table->integer('balance');
            $table->enum('status', array( 'مستردة','مدفوعة'));
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
        Schema::dropIfExists('Advance');

    }
}
