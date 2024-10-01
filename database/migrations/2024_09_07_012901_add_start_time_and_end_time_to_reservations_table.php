<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStartTimeAndEndTimeToReservationsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->time('start_time')->nullable();  // Add start_time column
            $table->time('end_time')->nullable();    // Add end_time column
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->dropColumn('start_time');  // Remove start_time column
            $table->dropColumn('end_time');    // Remove end_time column
        });
    }
}
