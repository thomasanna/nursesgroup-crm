<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->bigIncrements('bookingId');
            $table->bigInteger('bookingReferenceId')->nullable();
            $table->integer('categoryId')->unsigned()->nullable();
            $table->date('date')->nullable();
            $table->time('time')->nullable();
            $table->integer('handledBy')->unsigned()->nullable();
            $table->integer('unitId')->unsigned()->nullable();
            $table->smallInteger('modeOfRequest')->nullable();
            $table->string('requestedBy')->nullable();
            $table->integer('shiftId')->unsigned()->nullable();
            $table->integer('noOfShifts')->unsigned()->default(1);
            $table->smallInteger('homeStatus')->unsigned()->nullable();
            $table->integer('staffId')->unsigned()->nullable();
            $table->smallInteger('staffStatus')->unsigned()->nullable();
            $table->text('importantNotes')->nullable();
            $table->boolean('newSmsStatus')->default(0)->comment('0:Not Sent,1:Sent');
            $table->boolean('confirmSmsStatus')->default(0)->comment('0:Not Sent,1:Sent');
            $table->boolean('tvStatus')->default(0)->comment('0:Not Sent,1:Sent');
            $table->boolean('paymentStatus')->default(0)->comment('0:UnPaid,1:Paid');
            $table->boolean('invoiceStatus')->default(0)->comment('0:Sent,1:Processing');
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
        Schema::dropIfExists('bookings');
    }
}
