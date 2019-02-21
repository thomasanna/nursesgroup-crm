<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDriverVehiclesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->increments('vehicleId');
            $table->unsignedInteger('driverId');
            $table->string('make')->nullable();
            $table->string('model')->nullable();
            $table->string('regNumber')->nullable();
            $table->string('color')->nullable();
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
        Schema::dropIfExists('vehicles');
    }
}
