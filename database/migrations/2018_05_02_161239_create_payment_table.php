<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->increments('paymentId');
            $table->integer('bookingId')->unsigned();
            $table->integer('categoryId')->unsigned()->nullable();
            $table->date('date')->nullable();
            $table->integer('handledBy')->unsigned()->nullable();
            $table->integer('unitId')->unsigned()->nullable();
            $table->smallInteger('modeOfRequest')->nullable();
            $table->string('requestedBy')->nullable();
            $table->integer('shiftId')->unsigned()->nullable();
            $table->integer('noOfShifts')->unsigned()->default(1);
            $table->smallInteger('unitStatus')->unsigned()->nullable();
            $table->integer('staffId')->unsigned()->nullable();
            $table->tinyInteger('staffAllocateStatus')->nullable()->comment('1:Dummy,2:Confirmed,3:Potential');

            $table->tinyInteger('modeOfTransport')->nullable()->comment('1:Self,2:Self+Lift,3:Lift,4:Transport Required');
            $table->string('outBoundDriver')->nullable();
            $table->time('outBoundPickupTime')->nullable();

            $table->string('inBoundDriver')->nullable();
            $table->time('inBoundPickupTime')->nullable();
            $table->double('bonus',15,2)->nullable();
            $table->double('transportAllowence',15,2)->nullable();
            $table->smallInteger('staffStatus')->unsigned()->nullable();

            $table->text('importantNotes')->nullable();
            $table->boolean('newSmsStatus')->default(0)->comment('0:Not Sent,1:Sent');
            $table->boolean('confirmSmsStatus')->default(0)->comment('0:Not Sent,1:Sent');

            $table->tinyInteger('modeOfCancelRequest')->nullable()->comment('1:Email,2:Phone,3:SMS');
            $table->date('cancelDate')->nullable();
            $table->string('cancelRequestedBy')->nullable();
            $table->text('cancelExplainedReason')->nullable();
            $table->tinyInteger('canceledOrUTCreason')->nullable();

            $table->boolean('paymentStatus')->default(0)->comment('0:UnPaid,1:Paid');
            $table->boolean('invoiceStatus')->default(0)->comment('0:Sent,1:Processing');

            $table->integer('timesheetNumber')->nullable();
            $table->tinyInteger('timesheetStatus')->default(0)->comment('0:New|1:Checked|2:Verified');
            $table->text('timesheetComments')->nullable();
            $table->integer('timesheetEditedUser')->nullable();
            $table->integer('timesheetVerifiedUser')->nullable();
            $table->string('break')->nullable();
            $table->integer('staffHour')->nullable();
            $table->integer('unitHour')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payments');
    }
}
