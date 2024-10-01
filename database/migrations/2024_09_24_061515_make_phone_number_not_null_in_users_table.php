<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class MakePhoneNumberNotNullInUsersTable extends Migration
{
    public function up()
    {
        // First, ensure all phone_number fields are filled
        DB::table('users')->whereNull('phone_number')->update(['phone_number' => 'default_number']); // Replace 'default_number' with an actual number

        Schema::table('users', function (Blueprint $table) {
            // Set phone_number to NOT NULL
            $table->string('phone_number')->nullable(false)->change();
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // Optionally revert to nullable if needed
            $table->string('phone_number')->nullable()->change();
        });
    }
}

