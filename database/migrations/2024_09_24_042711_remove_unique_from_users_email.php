<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveUniqueFromUsersEmail extends Migration
{
    public function up()
    {
        // Check if the unique index exists before trying to drop it
        if (Schema::hasTable('users') && Schema::hasColumn('users', 'email')) {
            $indexName = 'users_email_unique'; // Adjust if necessary

            if (Schema::hasIndex('users', $indexName)) {
                Schema::table('users', function (Blueprint $table) use ($indexName) {
                    $table->dropUnique($indexName); // Drop the unique index
                });
            }
        }
    }

    public function down()
    {
        // Optionally re-add the unique index if needed
        Schema::table('users', function (Blueprint $table) {
            $table->unique('email'); // Recreate the unique index
        });
    }
}
