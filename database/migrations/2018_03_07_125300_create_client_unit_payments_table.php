<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientUnitPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_unit_payments', function (Blueprint $table) {
          $table->increments('clientUnitPaymentId');
          $table->integer('clientUnitId')->unsigned();
          $table->integer('staffCategoryId')->unsigned();
          $table->double('payRateWeekday',15,2)->nullable();
          $table->double('payRateWeekNight',15,2)->nullable();
          $table->double('payRateWeekendDay',15,2)->nullable();
          $table->double('payRateWeekendNight',15,2)->nullable();
          $table->double('payRateSpecialBhday',15,2)->nullable();
          $table->double('payRateSpecialBhnight',15,2)->nullable();
          $table->double('payRateBhday',15,2)->nullable();
          $table->double('payRateBhnight',15,2)->nullable();
          $table->double('transportAllowance',15,2)->nullable();
          $table->boolean('status')->default(1);
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
        Schema::dropIfExists('client_unit_payments');
    }
}
