<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->string('course_id')->primary(); // Manually entered Course ID
            $table->string('course_name');
            $table->year('start_year'); // Start year (YYYY)
            $table->year('end_year');   // End year (YYYY)
            $table->integer('duration'); // Duration in months
            $table->integer('total_term');
            $table->text('description')->nullable();
            $table->timestamps(); // created_at & updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('courses');
    }
};
