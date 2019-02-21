<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterBookingsTableMay3 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn([
              'tvStatus',
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
              'modeOfTransport',
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
        Schema::table('bookings', function (Blueprint $table) {
          $table->integer('timesheetNumber')->nullable();
          $table->tinyInteger('timesheetStatus')->default(0)->after('timesheetNumber')->comment('0:New|1:Checked|2:Verified');
          $table->text('timesheetComments')->nullable()->after('timesheetStatus');
          $table->integer('timesheetEditedUser')->nullable()->after('timesheetComments');
          $table->integer('timesheetVerifiedUser')->nullable()->after('timesheetEditedUser');
          $table->string('break')->nullable()->after('timesheetVerifiedUser');
          $table->integer('staffHour')->nullable()->after('break');
          $table->integer('unitHour')->nullable()->after('staffHour');
          $table->integer('tvStatus')->nullable()->after('unitHour');
          $table->integer('paymentStatus')->nullable()->after('tvStatus');
          $table->integer('invoiceStatus')->nullable()->after('paymentStatus');
          $table->integer('modeOfTransport')->nullable()->after('paymentStatus');
        });
    }
}
