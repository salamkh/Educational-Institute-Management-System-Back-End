<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class StudentDepts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('studentDepts', function (Blueprint $table) {
            $table->bigIncrements('StDId');
            $table->integer('deserevedAmount')->default(0);
            $table->integer('paidAmount')->default(0);
            $table->unsignedBigInteger('studentId');
            $table->unsignedBigInteger('typeId');
            $table->foreign('studentId')->references('FAId')->on('financialaccount')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('typeId')->references('FAId')->on('financialaccount')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('studentDepts');
    }
}
