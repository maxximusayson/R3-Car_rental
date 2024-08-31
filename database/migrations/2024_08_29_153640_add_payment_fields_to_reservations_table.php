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
        $table->decimal('amount_paid', 10, 2)->default(0)->after('total_price');
        $table->decimal('remaining_balance', 10, 2)->default(0)->after('amount_paid');
    });
}

public function down()
{
    Schema::table('reservations', function (Blueprint $table) {
        $table->dropColumn('amount_paid');
        $table->dropColumn('remaining_balance');
    });
}
};
