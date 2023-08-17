<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FinancailStudentOperation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('financialStudentOperation', function (Blueprint $table) {
            $table->bigIncrements('FSOId');
            $table->unsignedBigInteger('studentId')->nullable();
            $table->unsignedBigInteger('FOId');
            $table->unsignedBigInteger('typeId')->nullable();
            $table->enum('operationType', ['تسجيل', 'انسحاب', 'دفع', 'إرجاع', 'حسم']);
            $table->foreign('FOId')->references('FOId')->on('financialoperation')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('studentId')->references('FAId')->on('financialaccount')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('typeId')->references('FAId')->on('financialaccount')->onDelete('set null')->onUpdate('cascade');
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
        Schema::dropIfExists('financialStudentOperation');
    }
}
