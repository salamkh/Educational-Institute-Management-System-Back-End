<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SubjectTeacher extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('SubjectTeacher', function (Blueprint $table) {
            $table->bigIncrements('stId');
            $table->unsignedBigInteger('tId');
            $table->unsignedBigInteger('sId');
            $table->timestamps();
            $table->foreign('tId')->references('tId')->on('Teacher')->onDelete('cascade');
            $table->foreign('sId')->references('sId')->on('Subject')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('SubjectTeacher');
    }
}
