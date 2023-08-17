<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('userId');
            $table->string('name');
            $table->string('userName')->unique();
            $table->string('email')->unique()->nullable();
            $table->string('password');
            $table->bigInteger('salary')->nullable();
            $table->string('phoneNumber');
            $table->string('imageIdentity')->unique();
            $table->string('address');
            $table->enum('accountStatus', array( 'موظف','مستقيل'));
            $table->enum('workTime', array( 'مسائي','صباحي')); 
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
        Schema::dropIfExists('users');
    }
}
