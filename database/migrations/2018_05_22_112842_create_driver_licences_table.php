<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDriverLicencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('driver_licences', function (Blueprint $table) {
            $table->increments('driverLicenceId');
            $table->unsignedInteger('driverId');
            $table->string('number')->nullable();
            $table->date('dateOfIssue')->nullable();
            $table->date('dateOfExpiry')->nullable();
            $table->date('validFrom')->nullable();
            $table->date('validTo')->nullable();
            $table->string('issuedBy')->nullable();
            $table->string('softCopy')->nullable();
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
        Schema::dropIfExists('driver_licences');
    }
}
