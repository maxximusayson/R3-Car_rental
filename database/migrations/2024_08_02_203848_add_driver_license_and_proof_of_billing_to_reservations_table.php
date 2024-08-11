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
        Schema::table('reservations', function (Blueprint $table) {
            $table->string('driver_license')->nullable(); // Add nullable() if the field is optional
            $table->string('proof_of_billing')->nullable(); // Add nullable() if the field is optional
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down()
{
    Schema::table('reservations', function (Blueprint $table) {
        $table->dropColumn(['driver_license', 'proof_of_billing']);
    });
}
};
