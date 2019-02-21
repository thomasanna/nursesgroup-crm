<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTimesheetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('timesheets', function (Blueprint $table) {
          $table->increments('timesheetId');
          $table->integer('bookingId')->unsigned();
          $table->integer('number')->nullable();
          $table->tinyInteger('status')->default(0)->comment('0:New|1:Checked|2:Verified');
          $table->text('comments')->nullable();
          $table->integer('editedBy')->nullable();
          $table->integer('verifiedBy')->nullable();
          $table->time('startTime')->nullable();
          $table->time('endTime')->nullable();
          $table->double('breakHours',15,2)->nullable();
          $table->integer('staffHours')->nullable();
          $table->integer('unitHours')->nullable();

          $table->timestamps();
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
        Schema::dropIfExists('timesheets');
    }
}
