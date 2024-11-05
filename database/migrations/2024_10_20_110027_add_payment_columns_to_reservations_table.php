<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPaymentColumnsToReservationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('reservations', function (Blueprint $table) {
            if (!Schema::hasColumn('reservations', 'payment_method')) {
                $table->string('payment_method')->nullable()->after('remaining_balance');
            }
            if (!Schema::hasColumn('reservations', 'payment_status')) {
                $table->string('payment_status')->nullable()->after('payment_method');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('reservations', function (Blueprint $table) {
            if (Schema::hasColumn('reservations', 'payment_method')) {
                $table->dropColumn('payment_method');
            }
            if (Schema::hasColumn('reservations', 'payment_status')) {
                $table->dropColumn('payment_status');
            }
        });
    }
}
