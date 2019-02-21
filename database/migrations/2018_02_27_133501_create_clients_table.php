<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->increments('clientId');
            $table->string('name');
            $table->string('landlineNumber',15)->unique();
            $table->string('altPhoneNumber',15);
            $table->string('mobileNumber',15)->unique();
            $table->string('fax',15);
            $table->string('email',100)->unique();
            $table->string('personInCharge');
            $table->string('companyNumber');
            $table->text('registeredAddress');
            $table->text('businessAddress');
            $table->tinyInteger('typeOfCompany');
            $table->integer('numberOfBranches')->unsigned();
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
        Schema::dropIfExists('clients');
    }
}
