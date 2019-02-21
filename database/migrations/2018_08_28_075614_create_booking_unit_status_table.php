<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBookingUnitStatusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('booking_unit_status', function (Blueprint $table) {
            $table->increments('bookingUnitStatusId');
            $table->unsignedInteger('bookingId')->unique();
            $table->unsignedInteger('confirmedBy')->default(0);
            $table->timestamp('confirmedAt')->nullable();
            $table->unsignedInteger('temporaryBy')->default(0);
            $table->timestamp('temporaryAt')->nullable();
            $table->unsignedInteger('unableToCoverBy')->default(0);
            $table->timestamp('unableToCoverAt')->nullable();
            $table->unsignedInteger('cancelledBy')->default(0);
            $table->timestamp('cancelledAt')->nullable();
            $table->unsignedInteger('bookingErrorBy')->default(0);
            $table->timestamp('bookingErrorAt')->nullable();
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
        Schema::dropIfExists('booking_unit_status');
    }
}
