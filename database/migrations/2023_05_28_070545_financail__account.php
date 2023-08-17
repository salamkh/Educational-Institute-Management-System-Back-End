<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FinancailAccount extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('financialAccount', function (Blueprint $table) {
            $table->bigIncrements('FAId');
            $table->unique('accountName');
            $table->enum('status', array('خصوم','أصول','مصاريف','إيرادات'));
            $table->integer('balance');
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
        Schema::dropIfExists('financialAccount');
    }
}
