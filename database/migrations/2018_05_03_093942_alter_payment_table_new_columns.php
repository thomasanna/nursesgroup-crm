<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterPaymentTableNewColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->integer('timesheetId')->unsigned()->after('bookingId');
            $table->double('amount',15,2)->nullable()->after('timesheetId');
            $table->integer('verifiedBy')->unsigned()->nullable()->after('amount');
            $table->timestamp('verifiedOn')->nullable()->nullable()->after('verifiedBy');
            $table->integer('approvedBy')->unsigned()->nullable()->after('verifiedOn');
            $table->timestamp('approvedOn')->nullable()->nullable()->after('approvedBy');
            $table->boolean('status')->default(0)->comment('0:New,1:Verified,2:Approved,3:Reverted')->after('approvedOn');
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
            $table->dropColumn([
              'timesheetId',
              'amount',
              'verifiedBy',
              'verifiedOn',
              'approvedBy',
              'approvedOn',
              'status',
            ]);
        });
    }
}
