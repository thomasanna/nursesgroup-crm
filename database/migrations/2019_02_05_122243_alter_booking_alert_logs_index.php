<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterBookingAlertLogsIndex extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('booking_alert_logs', function (Blueprint $table) {
            //$table->foreign('bookingId')->references('bookingId')->on('bookings');
            //$table->foreign('staffId')->references('staffId')->on('staffs');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('booking_alert_logs', function (Blueprint $table) {
            //$table->dropForeign(['bookingId','staffId']);
        });
    }
}
