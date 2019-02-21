<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterStaffPreferedUnitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('staff_preferred_clients');
        Schema::create('staff_preferred_units', function (Blueprint $table) {
            $table->increments('staffPreferredUnitId');
            $table->integer('staffId')->unsigned();
            $table->integer('unitId')->unsigned();
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
      Schema::dropIfExists('staff_preferred_units');
      Schema::create('staff_preferred_clients', function (Blueprint $table) {
          $table->increments('StaffPreferredClientId');
          $table->integer('staffId')->unsigned();
          $table->integer('clientId')->unsigned();
          $table->boolean('status')->default(1);
          $table->timestamps();
      });
    }
}
