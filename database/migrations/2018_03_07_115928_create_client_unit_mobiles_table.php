<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientUnitMobilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_unit_mobiles', function (Blueprint $table) {
          $table->increments('clientUnitMobileId');
          $table->integer('clientUnitId')->unsigned();
          $table->string('number',15);
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
        Schema::dropIfExists('client_unit_mobiles');
    }
}
