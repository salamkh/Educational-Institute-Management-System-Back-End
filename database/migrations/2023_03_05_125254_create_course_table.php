<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCourseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course', function (Blueprint $table) {
            $table->bigIncrements('courseId');
            $table->unsignedBigInteger('subjectId');
            $table->unsignedBigInteger('planId')->nullable();
            $table->unsignedBigInteger('typeId');
            $table->unsignedBigInteger('classId');
            $table->string('headlines')->nullable();
            $table->string('addElements')->nullable();
            $table->string('cost');
            $table->bigInteger('maxNStudent')->nullable();
            $table->bigInteger('sessionNumber')->nullable();
            $table->date('startDate');
            $table->date('endDate')->nullable();
            $table->time('startTime')->nullable();
            $table->bigInteger('duration');
            $table->set('courseStatus', ['مفتوحة','مغلقة','لم تبدأ بعد']);
            $table->set('courseDays', ['السبت','الأحد','الاثنين','الثلاثاء','الأربعاء','الخميس','الجمعة']);
            $table->string('room')->nullable();
            $table->foreign('subjectId')->references('sId') ->on('subject')-> onDelete('cascade');
            $table->foreign('typeId')->references('typeId')->on('type')->onDelete('cascade');
            $table->foreign('classId')->references('classId')->on('class')->onDelete('cascade');
            $table->foreign('planId')->references('planId')->on('pricingPlan')->onDelete('set null');


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
        Schema::dropIfExists('course');
    }
}
