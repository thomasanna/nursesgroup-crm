<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransportationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transportations', function (Blueprint $table) {
            $table->increments('tripId');
            $table->integer('bookingId')->nullable();
            $table->tinyInteger('direction')->default(0)->comment('1:outbound|2:inbound');
            $table->integer('driverId')->nullable();
            $table->integer('staffId')->nullable();
            $table->integer('journeyId')->nullable(); 
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
        Schema::dropIfExists('transportations');
    }
}
