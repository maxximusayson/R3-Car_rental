<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddReservationAndPaymentToAuditsTable extends Migration
{
    public function up()
    {
        Schema::table('audits', function (Blueprint $table) {
            // Ensure the columns are unsignedBigInteger
            $table->unsignedBigInteger('reservation_id')->nullable()->change();
            $table->unsignedBigInteger('payment_id')->nullable()->change();

            // Add foreign key constraints
            $table->foreign('reservation_id')->references('id')->on('reservations')->onDelete('cascade');
            $table->foreign('payment_id')->references('id')->on('payments')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('audits', function (Blueprint $table) {
            $table->dropForeign(['reservation_id']);
            $table->dropForeign(['payment_id']);
            $table->dropColumn(['reservation_id', 'payment_id']);
        });
    }
}
