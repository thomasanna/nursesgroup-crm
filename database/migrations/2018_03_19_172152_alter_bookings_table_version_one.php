<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterBookingsTableVersionOne extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['bookingReferenceId','time']);
            $table->smallInteger('modeOfRequest')->nullable()->comment('1:Email,2:Phone,3:SMS')->change();
            $table->smallInteger('homeStatus')->default(1)->comment('1:New,2:Cancelled,3:Unable to Cover,4:Confirmed')->change();
            $table->smallInteger('staffStatus')->default(1)->comment('1:New,2:Informed,3:Confirmed,4:Search Closed')->change();
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
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn('deleted_at');
            $table->string('bookingReferenceId');
            $table->string('time');
        });
    }
}
