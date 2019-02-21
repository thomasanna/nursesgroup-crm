<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTripClubsDriverIdTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('trip_clubs', function (Blueprint $table) {
            $table->unsignedInteger('driverId')->after('dayNumber');
            $table->string('title')->after('dayNumber')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('trip_clubs', function (Blueprint $table) {
            $table->dropColumn(['driverId','title']);
        });
    }
}
