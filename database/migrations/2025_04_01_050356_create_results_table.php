<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('results', function (Blueprint $table) {
            $table->id();
            $table->string('course_id');
            $table->string('rim_id');
            $table->integer('term');
            $table->timestamps();
            
            // Add unique constraint to prevent duplicate entries
            $table->unique(['course_id', 'rim_id', 'term']);
            
            // Add foreign key references if needed
            // $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
            // $table->foreign('rim_id')->references('rim_id')->on('trainees')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('results');
    }
}