<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubjectTypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subject_type', function (Blueprint $table) {
            $table->bigIncrements('subjectTypeId');

            $table->unsignedBigInteger('typeId');
            $table->unsignedBigInteger('subjectId');

            $table->foreign('typeId')
                ->references('typeId')
                ->on('type')->
                onDelete('cascade');

            $table->foreign('subjectId')
                ->references('sId')
                ->on('subject')->
                onDelete('cascade');

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
        Schema::dropIfExists('subject_type');
    }
}
