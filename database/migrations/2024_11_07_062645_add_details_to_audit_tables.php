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
        Schema::table('audit_tables', function (Blueprint $table) {
            $table->string('details')->nullable();  // Add the details column
        });
    }
    
    public function down()
    {
        Schema::table('audit_tables', function (Blueprint $table) {
            $table->dropColumn('details');  // Drop the details column in case of rollback
        });
    }
    
};
