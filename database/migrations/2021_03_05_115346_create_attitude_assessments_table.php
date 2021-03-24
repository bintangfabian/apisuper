<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttitudeAssessmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attitude_assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id');
            $table->char('semester');
            $table->string('behavior');
            $table->string('neatness');
            $table->string('discipline');
            $table->string('cooperation');
            $table->string('creative');
            $table->string('information');
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
        Schema::dropIfExists('attitude_assessments');
    }
}
