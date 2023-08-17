<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FinancailOperation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('financialOperation', function (Blueprint $table) {
            $table->bigIncrements('FOId');
            $table->date('operationDate');
            $table->string('description');
            $table->integer('balance');
            $table->unsignedBigInteger('creditorId')->nullable();
            $table->unsignedBigInteger('debtorId')->nullable();
            $table->string('creditorName');
            $table->string('debtorName');
            $table->integer('creditorBalance');
            $table->integer('debtorBalance');
            $table->foreign('creditorId')->references('FAId')->on('financialAccount')->onDelete('set null');
            $table->foreign('debtorId')->references('FAId')->on('financialAccount')->onDelete('set null');
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
        Schema::dropIfExists('financialOperation');
    }
}