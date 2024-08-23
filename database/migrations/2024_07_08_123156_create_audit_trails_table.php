<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('audit_trails', function (Blueprint $table) {
            $table->id();
            $table->string('action');
            $table->string('user');
            $table->unsignedBigInteger('payment_id')->nullable(); // Add payment_id column
            $table->unsignedBigInteger('reservation_id')->nullable(); // Add reservation_id column
            $table->timestamps(); // This automatically adds 'created_at' and 'updated_at'

            // Define foreign key constraints
            $table->foreign('payment_id')->references('id')->on('payments')->onDelete('cascade');
            $table->foreign('reservation_id')->references('id')->on('reservations')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('audit_trails', function (Blueprint $table) {
            // Drop the foreign keys first before dropping the columns or the table
            $table->dropForeign(['payment_id']);
            $table->dropForeign(['reservation_id']);
        });

        Schema::dropIfExists('audit_trails');
    }
};
