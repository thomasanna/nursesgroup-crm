<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterBookingTableVersionFive extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bookings', function (Blueprint $table) {
          $table->time('outBoundPickupTime')->nullable()->after('outBoundDriver');
          $table->time('inBoundPickupTime')->nullable()->after('inBoundDriver');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
         Schema::table('bookings', function (Blueprint $table) {
          $table->dropColumn([
            'outBoundPickupTime',
            'inBoundPickupTime'
          ]);
        });
    }
}
