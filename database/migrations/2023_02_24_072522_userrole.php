<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Userrole extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('UserRole', function (Blueprint $table) {
            $table->bigIncrements('urId');
            $table->unsignedBigInteger('userId');
            $table->unsignedBigInteger('roleId');
            $table->timestamps();
            $table->foreign('userId')->references('userId')->on('users')->onDelete('cascade');
            $table->foreign('roleId')->references('roleId')->on('Role')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('UserRole');
    }
}
