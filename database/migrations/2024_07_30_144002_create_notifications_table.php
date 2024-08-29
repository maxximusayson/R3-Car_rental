<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // Type of notification (e.g., 'ReservationApproved', 'ReservationRejected')
            $table->morphs('notifiable'); // Polymorphic relationship fields: notifiable_id, notifiable_type
            $table->text('data'); // JSON data related to the notification
            $table->timestamp('read_at')->nullable(); // Timestamp for when the notification was read
            $table->timestamps(); // Created at and updated at timestamps
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
