<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResultDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('result_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('result_id');
            $table->unsignedBigInteger('unit_id');
            $table->decimal('mark', 5, 2);
            $table->enum('status', ['pass', 'fail']);
            $table->timestamps();
            
            // Add unique constraint to prevent duplicate entries
            $table->unique(['result_id', 'unit_id']);
            
            // Add foreign key references
            $table->foreign('result_id')
                  ->references('id')
                  ->on('results')
                  ->onDelete('cascade');
                  
            $table->foreign('unit_id')
                  ->references('id')
                  ->on('units')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('result_details');
    }
}