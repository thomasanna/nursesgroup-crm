<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransportArchives extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transport_archives', function (Blueprint $table) {
            $table->increments('archiveId');
            $table->unsignedInteger('tripId');
            $table->timestamps();
        });

      //  Schema::dropIfExists('trip_clubs');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::create('trip_clubs', function (Blueprint $table) {
        //     $table->increments('clubId');
        //     $table->tinyInteger('dayNumber');
        //     $table->timestamps();
        // });
        Schema::dropIfExists('transport_archives');
    }
}
