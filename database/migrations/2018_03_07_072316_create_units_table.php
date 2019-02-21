<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUnitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_units', function (Blueprint $table) {
            $table->increments('clientUnitId');
            $table->string('name');
            $table->tinyInteger('type')->comment('1: Nursing Home,2: Care home, 3: Residential,4:Dialysis, 5: NHS,6: Private, 7:Others')->nullable();
            $table->integer('clientId')->unsigned()->nullable();
            $table->integer('branchId')->unsigned()->nullable();
            $table->text('businessAddress')->nullable();
            $table->string('nameOfManager')->nullable();
            $table->bigInteger('fax')->nullable();
            $table->string('website')->nullable();
            $table->text('address')->nullable();
            $table->string('postCode')->nullable();
            $table->string('email')->nullable();
            $table->string('localAuthoritySocialServices')->nullable();
            $table->string('nameOfDeputyManager')->nullable();
            $table->string('nameOfRotaHRAdministrator')->nullable();
            $table->string('residenceCapacity')->nullable();
            $table->tinyInteger('agencyUsageLevelHCA')->comment('1:Low,2:Medium,3:High')->nullable();
            $table->tinyInteger('agencyUsageLevelRGN')->comment('1:Low,2:Medium,3:High')->nullable();
            $table->tinyInteger('agencyUsageLevelOthers')->comment('1:Low,2:Medium,3:High')->nullable();
            $table->tinyInteger('invoiceFrequency')->comment('1:Weekly,2:Monthly')->nullable();
            $table->tinyInteger('paymentTermAgreed')->nullable();
            $table->string('latestCQCReport')->nullable();
            $table->boolean('status')->default(1);
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
        Schema::dropIfExists('client_units');
    }
}
