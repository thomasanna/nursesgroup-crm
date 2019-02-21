<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterBookingsTableVersionFour extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->tinyInteger('modeOfCancelRequest')->nullable()->after('confirmSmsStatus')->comment('1:Email,2:Phone,3:SMS');
            $table->date('cancelDate')->nullable()->after('modeOfCancelRequest');
            $table->string('cancelRequestedBy')->nullable()->after('cancelDate');
            $table->text('cancelExplainedReason')->nullable()->after('cancelRequestedBy');
            $table->tinyInteger('canceledOrUTCreason')->nullable()->after('cancelExplainedReason');
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
              'modeOfCancelRequest',
              'cancelDate',
              'cancelRequestedBy',
              'cancelExplainedReason',
              'canceledOrUTCreason',
            ]);
        });
    }
}
