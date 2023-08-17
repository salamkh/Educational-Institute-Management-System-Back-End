<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Retired extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Retiered', function (Blueprint $table) {
            $table->bigIncrements('rId');
            $table->unsignedBigInteger('userId');
            $table->date('retieredDate');
            $table->timestamps();
$table->string('cause');
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
        
Schema::dropIfExists('Retiered');
    }
}
