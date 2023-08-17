<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Reward extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Reward', function (Blueprint $table) {
            $table->bigIncrements('rrId');
            $table->unsignedBigInteger('userId');
            $table->date('rewarddDate');
            $table->string('cause');
            $table->enum('status', array( 'مصروفة','غير مصروفة'));
            $table->integer('balance');
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
        Schema::dropIfExists('Reward');

    }
}
