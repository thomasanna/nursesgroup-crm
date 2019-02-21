<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStaffsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('staffs', function (Blueprint $table) {
            $table->increments('staffId');
            $table->string('forname');
            $table->string('surname');
            $table->integer('categoryId');
            $table->string('email',100)->unique();
            $table->string('mobile',15)->unique();
            $table->string('whatsappNumber',15)->nullable();
            $table->smallInteger('gender')->comment('1:Male,2:Female');
            $table->string('address');
            $table->integer('pincode')->nullable();
            $table->smallInteger('modeOfTransport')->unsigned();
            $table->string('pickupLocation')->nullable();
            $table->integer('branchId')->unsigned();
            $table->integer('zoneId')->unsigned();
            $table->integer('bandId')->unsigned();
            $table->tinyInteger('paymentMode')->comment('1:Sefies,2:Payee');
            $table->double('payRateWeekday',15,2)->nullable();
            $table->double('payRateWeekNight',15,2)->nullable();
            $table->double('payRateWeekendDay',15,2)->nullable();
            $table->double('payRateWeekendNight',15,2)->nullable();
            $table->double('payRateSpecialBhday',15,2)->nullable();
            $table->double('payRateSpecialBhnight',15,2)->nullable();
            $table->double('payRateBhday',15,2)->nullable();
            $table->double('payRateBhnight',15,2)->nullable();
            $table->boolean('status')->default(1)->comment('1:Active,0:Iactive');
            $table->softDeletes();
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
        Schema::dropIfExists('staffs');
    }
}
