<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserIdToAuditTrailsTable extends Migration
{
    public function up()
    {
        Schema::table('audit_trails', function (Blueprint $table) {
            // Adding the user_id column
            $table->unsignedBigInteger('user_id')->nullable()->after('action'); // Add this line
        });
    }

    public function down()
    {
        Schema::table('audit_trails', function (Blueprint $table) {
            // Dropping the user_id column if the migration is rolled back
            $table->dropColumn('user_id'); // Add this line
        });
    }
}
