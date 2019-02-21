<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStaffAvaiabilityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('staff_avaiablity', function (Blueprint $table) {
            $table->increments('avaiabilityId');
            $table->integer('staffId')->unsigned();
            $table->date('date');
            $table->boolean('early')->default(0)->comment("0:Not Available,1:Available");
            $table->boolean('late')->default(0)->comment("0:Not Available,1:Available");
            $table->boolean('night')->default(0)->comment("0:Not Available,1:Available");
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
        Schema::dropIfExists('staff_avaiablity');
    }
}
