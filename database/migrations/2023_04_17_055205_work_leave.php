<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class WorkLeave extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('WorkLeave', function (Blueprint $table) {
            $table->bigIncrements('wId');
            $table->unsignedBigInteger('userId');
            $table->date('startDate')->nullable(true);
            $table->time('startTime')->nullable(true);
            $table->integer('duration');
            $table->enum('type', array( 'ساعية','أيام'));
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
        Schema::dropIfExists('WorkLeave');

    }
}
