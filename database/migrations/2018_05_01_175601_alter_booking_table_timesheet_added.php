<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterBookingTableTimesheetAdded extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::table('bookings', function (Blueprint $table) {
          $table->integer('timesheetNumber')->nullable()->after('invoiceStatus');
          $table->tinyInteger('timesheetStatus')->default(0)->after('timesheetNumber')->comment('0:New|1:Checked|2:Verified');
          $table->text('timesheetComments')->nullable()->after('timesheetStatus');
          $table->integer('timesheetEditedUser')->nullable()->after('timesheetComments');
          $table->integer('timesheetVerifiedUser')->nullable()->after('timesheetEditedUser');
          $table->string('break')->nullable()->after('timesheetVerifiedUser');
          $table->integer('staffHour')->nullable()->after('break');
          $table->integer('unitHour')->nullable()->after('staffHour');
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
            'timesheetNumber',
            'timesheetStatus',
            'timesheetComments',
            'timesheetEditedUser',
            'timesheetVerifiedUser',
            'break',
            'staffHour',
            'unitHour'
          ]);
        });
    }
}
