<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterbookingAlertMessageInclude extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('booking_alert_logs', function (Blueprint $table) {
            $table->text('message')->nullable()->after('staffId');
            //
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
           $table->dropColumn('message');
            
        });
    }
}
