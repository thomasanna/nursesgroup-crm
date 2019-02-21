<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientUnitBudget extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_unit_budget', function (Blueprint $table) {
            $table->increments('clientUnitBudgetId');
            $table->integer('clientUnitId');
            $table->integer('year');
            $table->integer('month');
            $table->double('amount', 15, 2);              
            $table->double('budget', 15, 2);              
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('client_unit_budget');
    }
}
