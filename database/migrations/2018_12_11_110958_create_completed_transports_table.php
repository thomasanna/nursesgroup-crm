<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompletedTransportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transport_completes', function (Blueprint $table) {
            $table->increments('completedTransId');
            $table->unsignedInteger('tripId');
            $table->unsignedInteger('driverId');
            $table->year('taxYear');
            $table->smallInteger('payeeWeek');
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
        Schema::dropIfExists('transport_completes');
    }
}
