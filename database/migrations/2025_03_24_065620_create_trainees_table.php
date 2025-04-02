<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('trainees', function (Blueprint $table) {
            $table->string('rim_id')->primary(); // Manually entered RIM ID (Primary Key)
            $table->string('name');
            $table->string('course_name');
            $table->string('cid')->unique();
            $table->string('email')->unique();
            $table->string('contact');
            $table->string('emergency_contact');
            $table->text('address');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('trainees');
    }
};

