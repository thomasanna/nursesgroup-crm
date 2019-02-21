<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTaxWeeksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tax_weeks', function (Blueprint $table) {
            $table->increments('taxWeekId');
            $table->unsignedInteger('taxYearId');
            $table->unsignedInteger('weekNumber');
            $table->date('date'); 
        });

        Schema::dropIfExists('tax_calenders');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tax_weeks');
    }
}
