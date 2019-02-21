<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientUnitSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_unit_schedules', function (Blueprint $table) {
            $table->increments('clientUnitScheduleId');
            $table->integer('clientUnitId')->unsigned();
            $table->integer('staffCategoryId')->unsigned();
            $table->integer('shiftId')->unsigned()->comment("1=>Early,2=>Longday,3=>Late,4=>Night,5=>Twilight");
            $table->time('startTime')->nullable();
            $table->time('endTime')->nullable();
            $table->double('unPaidBreak',15,2)->nullable();
            $table->double('paidBreak',15,2)->nullable();
            $table->double('totalHoursUnit',15,2)->nullable();
            $table->double('totalHoursStaff',15,2)->nullable();
            $table->boolean('status')->default(1)->comment('1:Active,0:Iactive');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('client_unit_schedules');
    }
}
