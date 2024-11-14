<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGpsLogTable extends Migration
{
    public function up()
    {
        Schema::create('gps_log', function (Blueprint $table) {
            // Auto-incrementing primary key
            $table->id();

            // Foreign key referencing the 'car' table
            $table->unsignedBigInteger('car_id');
            $table->foreign('car_id')->references('id')->on('cars')->onDelete('cascade');

            // GPS data fields
            $table->string('gps_id');
            $table->string('filename');
            $table->timestamp('date'); // Or you can use $table->dateTime('date') for specific date and time format.

            // Laravel timestamps (optional, if you want to track created_at and updated_at)
            $table->timestamps(); 

            // Optionally, you can add unique constraints if needed
            $table->unique(['car_id', 'gps_id', 'date']); // Prevent duplicate logs for the same GPS at the same time.
        });
    }

    public function down()
    {
        // Drop the table if the migration is rolled back
        Schema::dropIfExists('gps_log');
    }
}
