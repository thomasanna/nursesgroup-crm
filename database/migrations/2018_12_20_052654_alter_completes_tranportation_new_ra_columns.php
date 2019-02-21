<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCompletesTranportationNewRaColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transport_completes', function (Blueprint $table) {
            $table->boolean('raGenerated')->default(0)->comment('1:Yes,0:No')->after('proceedToPay');
            $table->boolean('emailSent')->default(0)->comment('1:Yes,0:No')->after('raGenerated');
            $table->boolean('paymentRecorded')->default(0)->comment('1:Yes,0:No')->after('emailSent');

            $table->foreign('tripId')->references('tripId')->on('transportations');
            $table->foreign('driverId')->references('driverId')->on('drivers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transport_completes', function (Blueprint $table) {
            $table->dropColumn(['raGenerated','emailSent','paymentRecorded']);
            $table->dropForeign(['tripId','driverId']);
        });
    }
}
