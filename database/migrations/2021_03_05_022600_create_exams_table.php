<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exams', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('description');
            $table->date('started_date');
            $table->foreignId('grade_id');
            $table->foreignId('subject_id');
            $table->foreignId('user_id');
            $table->time('started_time');
            $table->date('end_date');
            $table->time('end_time');
            $table->integer('duration');
            $table->integer('minimum_score');
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
        Schema::dropIfExists('exams');
    }
}
