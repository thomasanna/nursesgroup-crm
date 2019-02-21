<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterClientUnitSchedulesPaidBreak extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('client_unit_schedules', function (Blueprint $table) {
            $table->time('unPaidBreak')->nullable()->change();
            $table->time('paidBreak')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('client_unit_schedules', function (Blueprint $table) {
            $table->double('unPaidBreak',15,2)->nullable()->change();
            $table->double('paidBreak',15,2)->nullable()->change();
        });
    }
}
