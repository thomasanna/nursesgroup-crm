<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUnitInformedLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_unit_informed_logs', function (Blueprint $table) {
            $table->increments('unitInformedLogId');
            $table->unsignedInteger('bookingId');
            $table->unsignedInteger('informedTo');
            $table->date('date')->nullable();
            $table->tinyInteger('modeOfInform')->nullable()->comment("1:Email,2:Phone,3:SMS");
            $table->string('notes')->nullable();
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
        Schema::dropIfExists('client_unit_informed_logs');
    }
}
