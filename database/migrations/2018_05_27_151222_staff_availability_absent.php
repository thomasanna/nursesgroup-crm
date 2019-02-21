<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class StaffAvailabilityAbsent extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('staff_avaiablity', function (Blueprint $table) {
            $table->tinyInteger('absent')->default(0)->after('night');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('staff_avaiablity', function (Blueprint $table) {
            $table->dropColumn('absent');            
        });
    }
}
