<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterPaymentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn([
              'categoryId',
              'date',
              'handledBy',
              'unitId',
              'modeOfRequest',
              'requestedBy',
              'shiftId',
              'noOfShifts',
              'unitStatus',
              'staffId',
              'staffAllocateStatus',
              'modeOfTransport',
              'outBoundDriver',
              'outBoundPickupTime',
              'inBoundDriver',
              'inBoundPickupTime',
              'bonus',
              'transportAllowence',
              'staffStatus',
              'importantNotes',
              'newSmsStatus',
              'confirmSmsStatus',
              'modeOfCancelRequest',
              'cancelDate',
              'cancelRequestedBy',
              'cancelExplainedReason',
              'canceledOrUTCreason',
              'paymentStatus',
              'invoiceStatus',
              'timesheetNumber',
              'timesheetStatus',
              'timesheetComments',
              'timesheetEditedUser',
              'timesheetVerifiedUser',
              'break',
              'staffHour',
              'unitHour',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payments', function (Blueprint $table) {
            //
        });
    }
}
