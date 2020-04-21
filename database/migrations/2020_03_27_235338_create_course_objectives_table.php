<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCourseObjectivesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_objectives', function (Blueprint $table) {
            $table->unsignedTinyInteger('id')->autoIncrement();
            $table->tinyInteger('course_id')->unsigned();
            $table->foreign('course_id')->references('id')->on('courses');
            $table->unsignedTinyInteger('objective_id')->nullable();
            $table->foreign('objective_id')->references('id')->on('objectives');
            $table->unsignedTinyInteger('other_objective_id')->nullable();
            $table->foreign('other_objective_id')->references('id')->on('other_objectives');
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
        Schema::dropIfExists('course_objectives');
    }
}
