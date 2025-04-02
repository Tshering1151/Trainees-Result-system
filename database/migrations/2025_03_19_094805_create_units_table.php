<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('units', function (Blueprint $table) {
            $table->id();
            $table->string('course_id');  // Define course_id as string to match the `courses` table
            $table->integer('term');
            $table->string('unit_name');
            $table->string('lecture');
            $table->text('description')->nullable();
            $table->timestamps();

            // Foreign Key Constraint (referencing `course_id` in the `courses` table)
            $table->foreign('course_id')->references('course_id')->on('courses')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('units');
    }
};
