<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGpsDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gps_data', function (Blueprint $table) {
            $table->id();
            $table->string('gps_id')->index(); // Store GPS device ID
            $table->decimal('latitude', 10, 7)->nullable(); // Latitude, precision to 7 decimal places
            $table->decimal('longitude', 10, 7)->nullable(); // Longitude, precision to 7 decimal places
            $table->decimal('speed', 5, 2)->default(0.0); // Speed of the device
            $table->integer('satellites')->default(0); // Number of satellites
            $table->string('gps_status')->default('No Signal'); // Status of the GPS
            $table->timestamp('timestamp')->nullable(); // Timestamp of the GPS data
            $table->timestamps(); // Laravel's created_at and updated_at fields
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gps_data');
    }
}
