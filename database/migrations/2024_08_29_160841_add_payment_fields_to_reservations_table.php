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
        $table->decimal('total_price', 10, 2)->default(0);
        $table->decimal('amount_paid', 10, 2)->default(0);
        $table->decimal('remaining_balance', 10, 2)->default(0);
    });
}

public function down()
{
    Schema::table('reservations', function (Blueprint $table) {
        $table->dropColumn(['total_price', 'amount_paid', 'remaining_balance']);
    });
}
};
