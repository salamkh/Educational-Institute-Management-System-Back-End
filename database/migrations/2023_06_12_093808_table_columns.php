<?php

use Brick\Math\BigInteger;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TableColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tableColumns', function (Blueprint $table) {
            $table->bigIncrements('tableColId');
            $table->unsignedBigInteger('tableId');
            $table->string('arabicName');
            $table->string('EnglishName');
            $table->string('dataType');
            $table->enum('columnType', array( 'أساسية','إضافية'));
            $table->enum('isUnique', array( '1','0')); 
            $table->timestamps();
            $table->foreign('tableId')->references('tableId')->on('dynamicTables')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tableColumns');
    }
}
