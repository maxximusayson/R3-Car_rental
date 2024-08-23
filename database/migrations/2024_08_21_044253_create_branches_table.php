<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBranchesTable extends Migration
{
    public function up()
    {
        Schema::create('branches', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., "Marikina Branch"
            $table->string('location'); // e.g., "Pasay"
            $table->decimal('latitude', 10, 7)->nullable(); // Optional: for geolocation
            $table->decimal('longitude', 10, 7)->nullable(); // Optional: for geolocation
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('branches');
    }
}
