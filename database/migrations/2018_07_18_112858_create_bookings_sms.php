<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBookingsSms extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('booking_sms', function (Blueprint $table) {
            $table->increments('bookingSmsId');
            $table->unsignedInteger('bookingId');
            $table->smallInteger('smsType')->comment("1:Shift Confirmation,2:Final Confirmation,3:Transport,4:Payment");
            $table->unsignedInteger('staffId');
            $table->datetime('sentTime');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('booking_sms');
    }
}
