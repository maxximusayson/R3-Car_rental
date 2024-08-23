<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReservationsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');

            $table->unsignedBigInteger('car_id');
            $table->foreign('car_id')->references('id')->on('cars');

            $table->date('start_date');
            $table->date('end_date');
            $table->integer('days'); // Number of days of reservation

            $table->decimal('price_per_day', 10, 2);
            $table->decimal('total_price', 10, 2); // Total price of reservation

            $table->string('status')->default('active');
            $table->enum('payment_method', ['gcash', 'cash']);
            $table->string('payment_status')->default('pending');

            // Paths to uploaded files
            $table->text('driver_license')->nullable(); // Path to driver license file
            $table->text('proof_of_billing')->nullable(); // Path to proof of billing file
            
            $table->timestamps();
        });

        // In the migration file for reservations
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('car_id')->constrained()->onDelete('cascade');
            $table->date('start_date');
            $table->date('end_date');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('reservations');
    }
}
