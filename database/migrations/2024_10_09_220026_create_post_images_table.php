<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostImagesTable extends Migration
{
    public function up()
    {
        Schema::create('post_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id')->constrained()->onDelete('cascade'); // This creates a foreign key reference to posts
            $table->string('image_path'); // Adjust the data type if necessary
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('post_images');
    }
}

