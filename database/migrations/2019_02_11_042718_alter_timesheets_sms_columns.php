<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTimesheetsSmsColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('timesheets', function (Blueprint $table) {
            $table->boolean('smsAcceptedStatus')->after('unitHours')->default(0)->comment('0:Not Sent, 1:Sent');
            $table->boolean('smsRejectedStatus')->after('smsAcceptedStatus')->default(0)->comment('0:Not Sent, 1:Sent');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('timesheets', function (Blueprint $table) {
            $table->dropColumn(['smsRejectedStatus','smsAcceptedStatus']);
        });
    }
}
