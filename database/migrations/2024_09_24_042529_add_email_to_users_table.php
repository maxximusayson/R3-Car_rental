<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEmailToUsersTable extends Migration
{
    public function up()
    {
        // Check if the email column does not exist before adding it
        if (!Schema::hasColumn('users', 'email')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('email')->nullable(false);
            });
        }
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('email');
        });
    }
}
