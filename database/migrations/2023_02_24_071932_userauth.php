<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Userauth extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('UserAuth', function (Blueprint $table) {
            $table->bigIncrements('uaId');
            $table->unsignedBigInteger('userId');
            $table->unsignedBigInteger('aId');
            $table->timestamps();
            $table->foreign('userId')->references('userId')->on('users')->onDelete('cascade');
            $table->foreign('aId')->references('aId')->on('Authorization')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('UserAuth');
    }
}
