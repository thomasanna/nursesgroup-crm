<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AltrTimesheetsTableRemovePaymentColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('timesheets', function (Blueprint $table) {
            $table->dropColumn(['paymentYear','paymentWeek']);
            $table->renameColumn('editedBy', 'checkInBy');
            $table->string('timesheetRefId')->after('number')->nullable();
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
            $table->renameColumn('checkInBy', 'editedBy');
            $table->integer('paymentYear')->nullable();
            $table->integer('paymentWeek')->nullable();
        });
    }
}
