<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIndexToBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bookings', function (Blueprint $table) {
            // $table->foreign('categoryId')->references('categoryId')->on('staff_categories');
            // $table->foreign('handledBy')->references('adminId')->on('admins');
            // $table->foreign('unitId')->references('clientUnitId')->on('client_units');
            // $table->foreign('shiftId')->references('shiftId')->on('shifts');
            // $table->foreign('staffId')->references('staffId')->on('staffs');
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
            //$table->dropForeign(['categoryId','handledBy','unitId','shiftId']);
        });
    }
}
