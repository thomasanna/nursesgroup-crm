<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterClientsTableMay25 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->string('name', 255)->nullable()->change();
            $table->string('landlineNumber', 15)->nullable()->change();
            $table->string('altPhoneNumber', 15)->nullable()->change();
            $table->string('mobileNumber', 15)->nullable()->change();
            $table->string('fax', 15)->nullable()->change();
            $table->string('email', 100)->nullable()->change();
            $table->string('personInCharge', 255)->nullable()->change();
            $table->string('companyNumber', 255)->nullable()->change();
            $table->text('registeredAddress')->nullable()->change();
            $table->text('businessAddress')->nullable()->change();
            $table->integer('numberOfBranches')->nullable()->change();
            $table->dropColumn('typeOfCompany');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->tinyInteger('typeOfCompany')->default(1);
        });
    }
}
