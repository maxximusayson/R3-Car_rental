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
        Schema::create('uploaded_images', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // ID ng user na nag-upload ng imahe
            $table->string('valid_id_url')->nullable(); // URL ng uploaded valid ID
            $table->string('proof_of_billing_url')->nullable(); // URL ng uploaded proof of billing
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('uploaded_images');
    }
};
