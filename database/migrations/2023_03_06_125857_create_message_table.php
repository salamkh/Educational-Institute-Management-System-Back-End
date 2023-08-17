<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMessageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('message', function (Blueprint $table) {
            $table->bigIncrements('messageId');
            $table->unsignedBigInteger('userId');
            $table->unsignedBigInteger('studentId');
            $table->string('content');
            $table->string('userName');
            $table->string('type');
            $table->foreign('userId')
                ->references('userId')
                ->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');
                $table->foreign('studentId')
                ->references('studentId')
                ->on('student')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreign('userName')
                ->references('userName')
                ->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');

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
        Schema::dropIfExists('message');
    }
}
