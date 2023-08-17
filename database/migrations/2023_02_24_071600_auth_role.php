<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AuthRole extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('AuthRole', function (Blueprint $table) {
            $table->bigIncrements('arId');
            $table->unsignedBigInteger('aId');
            $table->unsignedBigInteger('roleId');
            $table->timestamps();
            $table->foreign('aId')->references('aId')->on('Authorization')->onDelete('cascade');
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
        Schema::dropIfExists('AuthRole');
    }
}
