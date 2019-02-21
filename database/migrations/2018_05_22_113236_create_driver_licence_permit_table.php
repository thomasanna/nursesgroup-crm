<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDriverLicencePermitTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('driver_licence_permits', function (Blueprint $table) {
            $table->increments('driverLicencePermitId');
            $table->unsignedInteger('driverId');
            $table->unsignedInteger('licenceId');
            $table->enum('permit', [1,2,3,4]);
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
        Schema::dropIfExists('driver_licence_permits');
    }
}
