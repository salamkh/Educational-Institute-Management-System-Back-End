<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FinancailStudentAccount extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('financialStudentAccount', function (Blueprint $table) {
            $table->bigIncrements('FSAId');
            $table->unsignedBigInteger('FAId');
            $table->unsignedBigInteger('studentId')->nullable();
            $table->foreign('FAId')->references('FAId')->on('financialAccount')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('studentId')->references('studentId')->on('student')->onDelete('set null')->onUpdate('cascade');
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
        Schema::dropIfExists('financialStudentAccount');
    }
}
