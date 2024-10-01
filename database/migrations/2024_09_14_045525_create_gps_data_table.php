<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGpsDataTable extends Migration
{
    public function up()
    {
        Schema::create('gps_data', function (Blueprint $table) {
            $table->id();
            $table->string('gps_id');
            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);
            $table->float('speed');
            $table->integer('satellites');
            $table->string('gps_status');
            $table->timestamp('timestamp');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('gps_data');
    }
}

